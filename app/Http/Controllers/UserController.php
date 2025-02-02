<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Ticket;
use App\Models\Product;
use App\Models\Checkout;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PaymentInformation;
use Illuminate\Support\Facades\Auth;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Midtrans\Config;
use Midtrans\Snap;

class UserController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function cartAdd($id)
    {
        $ticket = Ticket::find($id);
        if (!$ticket) {
            return redirect()->back()->with('error', 'Ticket not found');
        }
        $user = Auth::user();
        //    cek apakah sudah ada di cart
        $cart = Cart::where('user_id', $user->id)->where('ticket_id', $ticket->id)->first();
        if ($cart) {
            $cart->quantity += 1;
            $cart->save();
        } else {
            $cart = new Cart();
            $cart->quantity = 1;
            $cart->user_id = $user->id;
            $cart->ticket_id = $ticket->id;
            $cart->save();
        }
        return redirect()->back()->with('success', 'Ticket added to cart successfully!');
    }

    public function cartCount()
    {
        return count((array) session('cart'));
    }

    public function cart()
    {
        $cartCount = $this->cartCount();
        $carts = Cart::where('user_id', Auth::user()->id)->get();
        $total = 0;
        foreach ($carts as $cart) {
            $total += $cart->ticket->price * $cart->quantity;
        }
        return view('cart', compact('cartCount', 'carts', 'total'));
    }

    public function cartDestroy($id)
    {
        $cart = Cart::find($id);
        $cart->delete();
        return redirect()->back()->with('success', 'Ticket removed from cart successfully!');
    }

    // Checkout
    public function checkout($id)
    {
        $ticket = Ticket::find($id);
        $paymentInfo = PaymentInformation::all();
        return view('checkout', compact('ticket', 'paymentInfo'));
    }

    // public function checkoutProcess(Request $request)
    // {
    //     $request->validate([
    //         'ticket_date' => 'required',
    //         'quantity' => 'required',
    //         'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10048',
    //     ]);
    //     $ticket = Ticket::find($request->ticket_id);
    //     if (!$ticket) {
    //         return redirect()->back()->with('error', 'Ticket not found');
    //     }
    //     if ($request->payment_proof) {
    //         $payment_proof = $request->file('payment_proof');
    //         $payment_proof_name = time() . '.' . $payment_proof->getClientOriginalExtension();
    //         // Use storage instead of public path
    //         $payment_proof->storeAs('payment_proof', $payment_proof_name, 'public');
    //     }
    //     $total_price = $ticket->price * $request->quantity;
    //     $randomString = Str::random(10);
    //     $code = 'CHK-' . time() . '-' . $randomString;
    //     $user = Auth::user();
    //     $order = new Checkout();
    //     $order->code = $code;
    //     $order->user_id = $user->id;
    //     $order->ticket_id = $request->ticket_id;
    //     $order->quantity = $request->quantity;
    //     $order->status = 'pending';
    //     $order->ticket_date = $request->ticket_date;
    //     $order->total_price = $total_price;
    //     $order->payment_proof = $payment_proof_name;
    //     $order->save();
    //     return redirect()->route('user.order')->with('success', 'Checkout successfully!');
    // }

    public function createCharge(Request $request)
    {
        $amount = $request->amount;
        $user = Auth::user();

        // Buat data checkout terlebih dahulu dengan status pending
        $order = new Checkout();
        $order->code = 'CHK-' . time() . '-' . Str::random(10);
        $order->user_id = $user->id;
        $order->ticket_id = $request->ticket_id;
        $order->quantity = $request->quantity;
        $order->status = 'pending';
        $order->ticket_date = $request->ticket_date;
        $order->total_price = $amount;
        $order->save();

        // Parameter dasar transaksi Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $order->code, // Gunakan kode order dari database
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
            ],
        ];

        try {
            // Dapatkan Snap token dari Midtrans
            $snapToken = Snap::getSnapToken($params);

            return response()->json([
                'snapToken' => $snapToken,
                'order_id' => $order->code,
                'gross_amount' => $amount
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function processCOD(Request $request)
    {
        // Hapus atau ganti dd("masuk") dengan log untuk debug
        // Log::info("Masuk ke processCOD"); // Alternatif menggunakan log

        $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'quantity' => 'required|integer|min:1',
            'ticket_date' => 'required|date',
        ]);

        $ticket = Ticket::find($request->ticket_id);
        if (!$ticket) {
            return redirect()->back()->with('error', 'Ticket not found');
        }
        $user = Auth::user();

        $order = new Checkout();
        $order->code = 'CHK-' . time() . '-' . Str::random(10);
        $order->user_id = $user->id;
        $order->ticket_id = $ticket->id;
        $order->quantity = $request->quantity;
        $order->status = 'pending'; // Status pending untuk COD
        $order->ticket_date = $request->ticket_date;
        $order->total_price = $request->total_price;
        $order->save();

        return response()->json(['success' => true, 'message' => 'Pesanan COD berhasil diproses']);
    }

    public function handlePaymentSuccess(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'payment_result' => 'required',
        ]);

        $order = Checkout::where('code', $request->order_id)->first();
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Pesanan tidak ditemukan']);
        }

        // Update status pesanan menjadi accepted setelah pembayaran berhasil
        $order->status = 'accepted';
        $order->payment_proof = "Online"; // Simpan hasil pembayaran sebagai bukti
        $order->save();

        return response()->json(['success' => true, 'message' => 'Pembayaran berhasil diproses']);
    }

    // Order
    public function order()
    {
        $orders = Checkout::where('user_id', Auth::user()->id)->get();
        return view('order', compact('orders'));
    }

    public function eTicket($code)
    {
        $order = Checkout::where('code', $code)->first();
        // Ganti BarcodeGeneratorPNG dengan QRCodeGenerator
        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG(); // Pastikan menggunakan generator yang sesuai untuk QR Code
        $qrcode = base64_encode($generator->getBarcode($code, $generator::TYPE_CODE_128)); // Ubah ke QR Code
        $order->qrcode = $qrcode; // Simpan QR Code ke dalam order
        return view('e-ticket', compact('order', 'qrcode')); // Ganti 'barcode' dengan 'qrcode'
    }
}
