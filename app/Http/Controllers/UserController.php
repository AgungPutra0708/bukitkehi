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
use Illuminate\Support\Facades\Crypt;
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

    public function cartAdd(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $ticket = Ticket::find($id);
        if (!$ticket) {
            return response()->json(['error' => 'Ticket not found'], 404);
        }

        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->where('ticket_id', $ticket->id)->first();
        $qty = (int) $request->input('quantity', 1);

        if ($qty <= 0) {
            return response()->json(['error' => 'Invalid quantity'], 400);
        }

        if ($cart) {
            $cart->quantity += $qty;
            $cart->save();
        } else {
            $cart = new Cart();
            $cart->quantity = $qty;
            $cart->user_id = $user->id;
            $cart->ticket_id = $ticket->id;
            $cart->save();
        }

        return;
    }

    public function cartUpdate(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $ticket = Ticket::find($id);
        if (!$ticket) {
            return response()->json(['error' => 'Ticket not found'], 404);
        }

        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->where('ticket_id', $ticket->id)->first();
        $qty = (int) $request->input('quantity', 1);

        if ($qty <= 0) {
            return response()->json(['error' => 'Invalid quantity'], 400);
        }

        if ($cart) {
            $cart->quantity = $qty;
            $cart->save();
        }

        return;
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
        $paymentInfo = PaymentInformation::all();
        $order = Cart::where('user_id', Crypt::decrypt($id))->get();
        return view('checkout', compact('order', 'paymentInfo'));
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
        $user = Auth::user();

        // Ambil semua item di Cart berdasarkan user_id
        $cartItems = Cart::where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Keranjang kosong'], 400);
        }

        // Hitung total quantity dan total price dari Cart
        $totalQuantity = $cartItems->sum('quantity');
        $totalPrice = $cartItems->sum(fn($item) => $item->ticket->price * $item->quantity);

        // Tambah biaya admin untuk pembayaran online
        $adminFee = ($totalPrice * 0.02) + 5000;
        $finalAmount = $totalPrice + $adminFee;

        // Simpan ke tabel Checkout
        $checkout = new Checkout();
        $checkout->code = 'CHK-' . time() . '-' . Str::random(10);
        $checkout->user_id = $user->id;
        $checkout->quantity = $totalQuantity;
        $checkout->status = 'pending';
        $checkout->ticket_date = now();
        $checkout->total_price = $finalAmount;
        $checkout->save();

        // Update semua Cart agar memiliki checkout_id
        foreach ($cartItems as $item) {
            $item->checkout_id = $checkout->id;
            $item->save();
        }

        // Parameter transaksi Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $checkout->code,
                'gross_amount' => $finalAmount,
            ],
            'customer_details' => [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
            ],
        ];

        try {
            // Dapatkan Snap Token dari Midtrans
            $snapToken = Snap::getSnapToken($params);

            return response()->json([
                'snapToken' => $snapToken,
                'order_id' => $checkout->code,
                'gross_amount' => $finalAmount
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function processCOD(Request $request)
    {
        $user = Auth::user();

        // Ambil semua item di Cart berdasarkan user_id
        $cartItems = Cart::where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Keranjang kosong'], 400);
        }

        // Hitung total quantity dan total price dari Cart
        $totalQuantity = $cartItems->sum('quantity');
        $totalPrice = $cartItems->sum(fn($item) => $item->ticket->price * $item->quantity);

        // Simpan ke tabel Checkout dengan metode COD
        $checkout = new Checkout();
        $checkout->code = 'CHK-' . time() . '-' . Str::random(10);
        $checkout->user_id = $user->id;
        $checkout->quantity = $totalQuantity;
        $checkout->status = 'pending'; // Pending sebelum dikonfirmasi
        $checkout->ticket_date = now();
        $checkout->total_price = $totalPrice;
        $checkout->save();

        // Update semua Cart agar memiliki checkout_id
        foreach ($cartItems as $item) {
            $item->checkout_id = $checkout->id;
            $item->save();
        }

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
        $cart = Cart::where('user_id', Auth::user()->id)->where('checkout_id', NULL)->get();
        $orders = Checkout::where('user_id', Auth::user()->id)->with('carts.ticket')->get();
        $user = Auth::user()->id;
        // var_dump($orders);
        // exit;
        return view('order', compact('orders', 'cart', 'user'));
    }

    public function eTicket($code)
    {
        $order = Checkout::where('code', $code)
            ->where('user_id', Auth::user()->id)
            ->with('carts.ticket', 'carts.rating', 'user') // Tambahkan relasi rating
            ->firstOrFail();

        // Buat QR Code
        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
        $qrcode = base64_encode($generator->getBarcode($code, $generator::TYPE_CODE_128));

        return view('e-ticket', compact('order', 'qrcode'));
    }
}
