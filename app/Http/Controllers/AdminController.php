<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Income;
use App\Models\Ticket;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\Aparatur;
use App\Models\Checkout;
use App\Models\Facility;
use App\Models\Statistik;
use App\Models\Destination;
use App\Models\IncomeDetail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PaymentInformation;
use App\Models\SupportObject;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function destination()
    {
        $destinations = Destination::latest()->get();
        return view('admin.destination', compact('destinations'));
    }

    public function destinationCreate()
    {
        return view('admin.destination-create');
    }

    public function destinationStore(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required',
            'short_description' => 'required',
            'content' => 'required',
            // 'video' => 'mimes:mp4,avi,mkv,webm|max:50048',
            'video' => 'nullable|url',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            // Menggunakan storage untuk menyimpan gambar
            $image->storeAs('destination', $imageName, 'public');
        }
        // if ($request->hasFile('video')) {
        //     $video = $request->file('video');
        //     $videoName = time() . '.' . $video->getClientOriginalExtension();
        //     $video->storeAs('destination', $videoName, 'public');
        // }
        $slug = Str::slug($request->name);

        $destination = new Destination();
        $destination->slug = $slug;
        $destination->image = $imageName; // Menyimpan nama gambar ke model
        $destination->name = $request->name;
        $destination->short_description = $request->short_description;
        $destination->content = $request->content;
        $destination->video = $request->video;
        $destination->user_id = Crypt::decrypt(Session::get('user_id'));
        $destination->save();
        return redirect()->route('admin.destination');
    }

    public function destinationEdit($id)
    {
        $destination = Destination::find($id);
        return view('admin.destination-edit', compact('destination'));
    }

    public function destinationUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required',
            'short_description' => 'required',
            'content' => 'required',
            'video' => 'nullable|url',
        ]);

        $destination = Destination::find($request->id);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            // Menggunakan storage untuk menyimpan gambar
            $image->storeAs('destination', $imageName, 'public');
            $destination->image = $imageName;
        }
        // if ($request->hasFile('video')) {
        //     $video = $request->file('video');
        //     $videoName = time() . '.' . $video->getClientOriginalExtension();
        //     $video->storeAs('destination', $videoName, 'public');
        //     $destination->video = $videoName;
        // }


        $destination->name = $request->name;
        $destination->short_description = $request->short_description;
        $destination->content = $request->content;
        $destination->video = $request->video;
        $destination->user_id = Crypt::decrypt(Session::get('user_id'));
        $destination->save();
        return redirect()->route('admin.destination');
    }

    public function destinationDestroy($id)
    {
        $destination = Destination::find($id);
        $photo = $destination->image;
        $video = $destination->video;
        Storage::delete('public/destination/' . $photo);
        Storage::delete('public/destination/' . $video);
        $destination->delete();
        return redirect()->route('admin.destination');
    }

    // Gallery
    public function gallery()
    {
        $galleries = Gallery::latest()->get();
        return view('admin.gallery', compact('galleries'));
    }

    public function galleryCreate()
    {
        return view('admin.gallery-create');
    }

    public function galleryStore(Request $request)
    {
        // Validasi kustom: Salah satu harus diisi (file gambar atau link video)
        $validator = Validator::make($request->all(), [
            'file' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:50000', // Hanya gambar yang diizinkan
            'video' => 'nullable|url', // Hanya URL yang valid
        ]);

        $validator->after(function ($validator) use ($request) {
            if (!$request->hasFile('file') && !$request->filled('video')) {
                $validator->errors()->add('file', 'Salah satu dari file gambar atau link video harus diisi.');
                $validator->errors()->add('video', 'Salah satu dari file gambar atau link video harus diisi.');
            }
            if ($request->hasFile('file') && $request->filled('video')) {
                $validator->errors()->add('file', 'Hanya salah satu yang boleh diisi: file gambar atau link video.');
                $validator->errors()->add('video', 'Hanya salah satu yang boleh diisi: file gambar atau link video.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Tentukan jenis file dan nama file
        $jenis = null;
        $fileName = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $jenis = 'image';
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('gallery', $fileName, 'public');
        }

        if ($request->filled('video')) {
            $jenis = 'video';
            $fileName = $request->input('video'); // Menyimpan URL video
        }

        // Simpan ke database
        $gallery = new Gallery();
        $gallery->file = $fileName;
        $gallery->type = $jenis;
        $gallery->user_id = Crypt::decrypt(Session::get('user_id'));
        $gallery->save();

        return redirect()->route('admin.gallery');
    }

    public function galleryDestroy($id)
    {
        $gallery = Gallery::find($id);
        $gallery->delete();
        return redirect()->route('admin.gallery');
    }

    // News
    public function news()
    {
        $news = News::latest()->get();
        return view('admin.news', compact('news'));
    }

    public function newsCreate()
    {
        return view('admin.news-create');
    }

    public function newsStore(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required',
            'author' => 'required',
            'status' => 'required',
            'content' => 'required',
            // 'video' => 'mimes:mp4,avi,mkv,webm',
            'video' => 'nullable|url',
        ]);
        $news = new News();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('news', $imageName, 'public');
        }
        // if ($request->hasFile('video')) {
        //     $video = $request->file('video');
        //     $videoName = time() . '.' . $video->getClientOriginalExtension();
        //     $video->storeAs('news', $videoName, 'public');
        //     $news->video = $videoName;
        // }
        $news->slug = Str::slug($request->title);
        $news->image = $imageName;
        $news->title = $request->title;
        $news->author = $request->author;
        $news->status = $request->status;
        $news->content = $request->content;
        $news->video = $request->video;
        $news->user_id = Crypt::decrypt(Session::get('user_id'));
        $news->save();
        return redirect()->route('admin.news')->with('success', 'News & Article created successfully');
    }
    public function newsEdit($id)
    {
        $news = News::find($id);
        return view('admin.news-edit', compact('news'));
    }

    public function newsUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required',
            'author' => 'required',
            'status' => 'required',
            'content' => 'required',
            // 'video' => 'mimes:mp4,avi,mkv,webm',
            'video' => 'nullable|url',
        ]);

        $news = News::find($request->id);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('news', $imageName, 'public');
            $news->image = $imageName;
        }
        // if ($request->hasFile('video')) {
        //     $video = $request->file('video');
        //     $videoName = time() . '.' . $video->getClientOriginalExtension();
        //     $video->storeAs('news', $videoName, 'public');
        //     $news->video = $videoName;
        // }
        $news->slug = Str::slug($request->title);
        $news->title = $request->title;
        $news->author = $request->author;
        $news->status = $request->status;
        $news->content = $request->content;
        $news->video = $request->video;
        $news->user_id = Crypt::decrypt(Session::get('user_id'));
        $news->save();
        return redirect()->route('admin.news')->with('success', 'News & Article updated successfully');
    }
    public function newsDestroy($id)
    {
        $news = News::find($id);
        $photo = $news->image;
        $video = $news->video;
        Storage::delete('public/news/' . $photo);
        Storage::delete('public/news/' . $video);
        $news->delete();

        return redirect()->route('admin.news')->with('success', 'News & Article deleted successfully');
    }

    // product
    public function product()
    {
        $products = Product::latest()->get();
        return view('admin.product', compact('products'));
    }
    public function productCreate()
    {
        return view('admin.product-create');
    }

    public function productStore(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'status' => 'required',
            'description' => 'required',
        ]);
        $product = new Product();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('product', $imageName, 'public');
            $product->image = $imageName;
        }
        $slug = Str::slug($request->name);
        $product->slug = $slug;
        $product->name = $request->name;
        $product->price = $request->price;
        $product->status = $request->status;
        $product->description = $request->description;
        $product->user_id = Crypt::decrypt(Session::get('user_id'));
        $product->save();
        return redirect()->route('admin.product')->with('success', 'Product created successfully');
    }
    public function productEdit($id)
    {
        $product = Product::find($id);
        return view('admin.product-edit', compact('product'));
    }

    public function productUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'name' => 'required',
            'price' => 'required',
            'status' => 'required',
            'description' => 'required',
        ]);
        $product = Product::find($request->id);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('product', $imageName, 'public');
            $product->image = $imageName;
        }
        $product->name = $request->name;
        $product->price = $request->price;
        $product->status = $request->status;
        $product->description = $request->description;
        $product->user_id = Crypt::decrypt(Session::get('user_id'));
        $product->save();
        return redirect()->route('admin.product')->with('success', 'Product updated successfully');
    }
    public function productDestroy($id)
    {
        $product = Product::find($id);
        $photo = $product->image;
        Storage::delete('public/product/' . $photo);
        $product->delete();
        return redirect()->route('admin.product')->with('success', 'Product deleted successfully');
    }

    // Facility
    public function facility()
    {
        $facilities = Facility::latest()->get();
        return view('admin.facility', compact('facilities'));
    }
    public function facilityCreate()
    {
        return view('admin.facility-create');
    }
    public function facilityStore(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required',
            'description' => 'required',
        ]);
        $facility = new Facility();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('facility', $imageName, 'public');
            $facility->image = $imageName;
        }
        $slug = Str::slug($request->name);
        $facility->name = $request->name;
        $facility->slug = $slug;
        $facility->description = $request->description;
        $facility->user_id = Crypt::decrypt(Session::get('user_id'));
        $facility->save();
        return redirect()->route('admin.facility')->with('success', 'Facility created successfully');
    }

    public function facilityEdit($id)
    {
        $facility = Facility::find($id);
        return view('admin.facility-edit', compact('facility'));
    }
    public function facilityUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required',
            'description' => 'required',
        ]);
        $facility = Facility::find($request->id);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('facility', $imageName, 'public');
            $facility->image = $imageName;
        }
        $facility->name = $request->name;
        $facility->description = $request->description;
        $facility->user_id = Crypt::decrypt(Session::get('user_id'));
        $facility->save();
        return redirect()->route('admin.facility')->with('success', 'Facility updated successfully');
    }
    public function facilityDestroy($id)
    {
        $facility = Facility::find($id);
        $facility->delete();
        return redirect()->route('admin.facility')->with('success', 'Facility deleted successfully');
    }

    // Object Pendukung
    public function objectSupport()
    {
        $objectData = SupportObject::latest()->get();
        return view('admin.objects', compact('objectData'));
    }
    public function objectSupportCreate()
    {
        return view('admin.objects-form');
    }
    public function objectSupportStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:1,2,3', // Validasi sesuai opsi dropdown
            'longitude' => 'required',
            'latitude' => 'required',
            'address' => 'required',
            'description' => 'nullable',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $supportObject = new SupportObject();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('objek', $imageName, 'public');
            $supportObject->image = $imageName;
        }
        $supportObject->name = $request->name;
        $supportObject->tipe = $request->type;
        $supportObject->longitude = $request->longitude;
        $supportObject->latitude = $request->latitude;
        $supportObject->address = $request->address;
        $supportObject->description = $request->description;
        $supportObject->user_id = auth()->id();
        $supportObject->save();

        return redirect()->route('admin.objects')->with('success', 'Objek Pendukung berhasil ditambahkan');
    }

    // edit
    public function objectSupportEdit($id)
    {
        $objectData = SupportObject::find($id);
        return view('admin.objects-form', compact('objectData'));
    }
    public function objectSupportUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'name' => 'required|string|max:255',
            'type' => 'required|in:1,2,3', // Validasi sesuai opsi dropdown
            'longitude' => 'required',
            'latitude' => 'required',
            'address' => 'required',
            'description' => 'nullable',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $supportObject = SupportObject::find($request->id);
        if (!$supportObject) {
            return redirect()->route('admin.objects')->with('error', 'Objek Pendukung tidak ditemukan');
        }
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('objek', $imageName, 'public');
            $supportObject->image = $imageName;
        }

        $supportObject->name = $request->name;
        $supportObject->tipe = $request->type;
        $supportObject->longitude = $request->longitude;
        $supportObject->latitude = $request->latitude;
        $supportObject->address = $request->address;
        $supportObject->description = $request->description;
        $supportObject->user_id = auth()->id();
        $supportObject->save();

        return redirect()->route('admin.objects')->with('success', 'Objek Pendukung berhasil diperbarui');
    }
    public function objectSupportDestroy($id)
    {
        $aparatur = Aparatur::find($id);
        $photo = $aparatur->image;
        Storage::delete('public/aparatur/' . $photo);
        $aparatur->delete();
        return redirect()->route('admin.aparatur')->with('success', 'Aparatur deleted successfully');
    }

    // Aparatur
    public function aparatur()
    {
        $aparaturs = Aparatur::latest()->get();
        return view('admin.aparatur', compact('aparaturs'));
    }
    public function aparaturCreate()
    {
        return view('admin.aparatur-create');
    }
    public function aparaturStore(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required',
            'position' => 'required',
        ]);
        $aparatur = new Aparatur();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('aparatur', $imageName, 'public');
            $aparatur->image = $imageName;
        }
        $aparatur->name = $request->name;
        $aparatur->position = $request->position;
        $aparatur->user_id = Crypt::decrypt(Session::get('user_id'));
        $aparatur->save();
        return redirect()->route('admin.aparatur')->with('success', 'Aparatur created successfully');
    }

    // edit
    public function aparaturEdit($id)
    {
        $aparatur = Aparatur::find($id);
        return view('admin.aparatur-edit', compact('aparatur'));
    }
    public function aparaturUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required',
            'position' => 'required',
        ]);
        $aparatur = Aparatur::find($request->id);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('aparatur', $imageName, 'public');
            $aparatur->image = $imageName;
        }
        $aparatur->name = $request->name;
        $aparatur->position = $request->position;
        $aparatur->user_id = Crypt::decrypt(Session::get('user_id'));
        $aparatur->save();
        return redirect()->route('admin.aparatur')->with('success', 'Aparatur updated successfully');
    }
    public function aparaturDestroy($id)
    {
        $aparatur = Aparatur::find($id);
        $photo = $aparatur->image;
        Storage::delete('public/aparatur/' . $photo);
        $aparatur->delete();
        return redirect()->route('admin.aparatur')->with('success', 'Aparatur deleted successfully');
    }

    // Ticket
    public function ticket()
    {
        $tickets = Ticket::latest()->get();
        return view('admin.ticket', compact('tickets'));
    }
    public function ticketCreate()
    {
        return view('admin.ticket-create');
    }
    public function ticketStore(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required',
            'price' => 'required|numeric',
            'status' => 'required',
            'type_ticket' => 'required',
            'description' => 'required',
        ]);
        $ticket = new Ticket();
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('ticket', $imageName, 'public');
            $ticket->photo = $imageName;
        }
        $ticket->name = $request->name;
        $ticket->price = str_replace(',', '', $request->price);
        $ticket->status = $request->status;
        $ticket->type = $request->type_ticket;
        $ticket->description = $request->description;
        $ticket->save();
        return redirect()->route('admin.ticket')->with('success', 'Ticket created successfully');
    }
    public function ticketEdit($id)
    {
        $ticket = Ticket::find($id);
        return view('admin.ticket-edit', compact('ticket'));
    }
    public function ticketUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required',
            'price' => 'required|numeric',
            'status' => 'required',
            'type_ticket' => 'required',
            'description' => 'required',
        ]);
        $ticket = Ticket::find($request->id);
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('ticket', $imageName, 'public');
            $ticket->photo = $imageName;
        }
        $ticket->name = $request->name;
        $ticket->price = str_replace(',', '', $request->price);
        $ticket->status = $request->status;
        $ticket->type = $request->type_ticket;
        $ticket->description = $request->description;
        $ticket->save();
        return redirect()->route('admin.ticket')->with('success', 'Ticket updated successfully');
    }
    public function ticketDestroy($id)
    {
        $ticket = Ticket::find($id);
        $photo = $ticket->photo;
        Storage::delete('public/ticket/' . $photo);
        $ticket->delete();
        return redirect()->route('admin.ticket')->with('success', 'Ticket deleted successfully');
    }

    // Order
    public function order()
    {
        $orders_request = Checkout::where('status', 'request')->get();
        $orders_success = Checkout::where('status', 'accepted')->get();
        $orders_pending = Checkout::where('status', 'pending')->get();
        $orders_rejected = Checkout::where('status', 'rejected')->get();
        return view('admin.order', compact('orders_request', 'orders_success', 'orders_pending', 'orders_rejected'));
    }
    public function orderUpdate(Request $request)
    {
        $order = Checkout::find($request->id);
        $order->status = $request->status;
        $order->save();
        return redirect()->route('admin.order')->with('success', 'Order updated successfully');
    }
    public function storeTotal(Request $request)
    {
        $request->validate([
            'year' => 'required',
            'month' => 'required',
        ]);

        // Filter Checkout dengan status 'accepted' berdasarkan bulan dan tahun pada ticket_date
        $total = Checkout::select('ticket_id', DB::raw('SUM(total_price) as total_price_sum'), DB::raw('SUM(quantity) as total_quantity'))
            ->with('ticket')
            ->where('status', 'accepted')
            ->whereYear('ticket_date', $request->year)
            ->whereMonth('ticket_date', $request->month)
            ->groupBy('ticket_id')
            ->get();

        $income = Income::where('bulan', $request->month)->where('tahun', $request->year)->first();

        if ($income) {
            $income->amount = $total->sum('total_price_sum') + $income->amount;
            $income->save();
        } else {
            $income = new Income();
            $income->bulan = $request->month;
            $income->tahun = $request->year;
            $income->amount = $total->sum('total_price_sum');
            $income->save();
        }

        foreach ($total as $t) {
            $incomeDetail = IncomeDetail::where('income_id', $income->id)->where('ticket_id', $t->ticket_id)->where('metode', 'online')->first();
            if ($incomeDetail) {
                $incomeDetail->jumlah = $t->total_quantity == $incomeDetail->jumlah ? $t->total_quantity : $t->total_quantity + $incomeDetail->jumlah;
                $incomeDetail->amount = $t->total_price_sum == $incomeDetail->amount ? $t->total_price_sum : $t->total_price_sum + $incomeDetail->amount;
                $incomeDetail->save();
            } else {
                $incomeDetail = new IncomeDetail();
                $incomeDetail->income_id = $income->id;
                $incomeDetail->ticket_id = $t->ticket_id;
                $incomeDetail->facilities_id = null;
                $incomeDetail->type = $t->ticket->type;
                $incomeDetail->metode = 'online';
                $incomeDetail->harga_satuan = $t->ticket->price;
                $incomeDetail->jumlah = $t->total_quantity;
                $incomeDetail->amount = $t->total_price_sum;
                $incomeDetail->save();
            }

            // Update Income total amount
            $income->amount = IncomeDetail::where('income_id', $income->id)->sum('amount');
            $income->save();
        }
        return redirect()->route('admin.order')->with('success', 'Total created successfully');
    }
    public function getTotalAccepted(Request $request)
    {
        $year = $request->year;
        $month = $request->month;

        // Validasi input
        if (!$year || !$month) {
            return response()->json(['error' => 'Tahun dan bulan diperlukan!'], 400);
        }

        // Filter Checkout dengan status 'accepted' berdasarkan bulan dan tahun pada ticket_date
        $total = Checkout::where('status', 'accepted')
            ->whereYear('ticket_date', $year)
            ->whereMonth('ticket_date', $month)
            ->sum('total_price');

        return response()->json(['total' => $total]);
    }

    // Payment Information
    public function paymentInformation()
    {
        $paymentInfo = PaymentInformation::latest()->get();
        return view('admin.payment-information', compact('paymentInfo'));
    }
    public function paymentInformationStore(Request $request)
    {
        $request->validate([
            'bank_name' => 'required',
            'bank_account_number' => 'required|numeric',
            'bank_account_name' => 'required',
        ]);
        $paymentInfo = new PaymentInformation();
        $paymentInfo->bank_name = $request->bank_name;
        $paymentInfo->bank_account_number = $request->bank_account_number;
        $paymentInfo->bank_account_name = $request->bank_account_name;
        $paymentInfo->user_id = Crypt::decrypt(Session::get('user_id'));
        $paymentInfo->save();
        return redirect()->route('admin.payment.information')->with('success', 'Payment Information created successfully');
    }
    public function paymentInformationUpdate(Request $request)
    {
        $request->validate([
            'bank_name' => 'required',
            'bank_account_number' => 'required|numeric',
            'bank_account_name' => 'required',
        ]);
        $paymentInfo = PaymentInformation::find($request->id);
        $paymentInfo->bank_name = $request->bank_name;
        $paymentInfo->bank_account_number = $request->bank_account_number;
        $paymentInfo->bank_account_name = $request->bank_account_name;
        $paymentInfo->user_id = Crypt::decrypt(Session::get('user_id'));
        $paymentInfo->save();
        return redirect()->route('admin.payment.information')->with('success', 'Payment Information updated successfully');
    }
    public function paymentInformationDestroy($id)
    {
        $paymentInfo = PaymentInformation::find($id);
        $paymentInfo->delete();
        return redirect()->route('admin.payment.information')->with('success', 'Payment Information deleted successfully');
    }

    // Statistik
    public function statistik()
    {
        $statistik = Statistik::all();
        return view('admin.statistik', compact('statistik'));
    }

    public function statistikStore(Request $request)
    {
        $request->validate([
            'bulan' => 'required|numeric',
            'tahun' => 'required|numeric',
            'pengunjung_perempuan' => 'required|numeric',
            'pengunjung_laki_laki' => 'required|numeric',
            'tidak_diketahui' => 'required|numeric',
        ]);
        if (Statistik::where('bulan', $request->bulan)->where('tahun', $request->tahun)->exists()) {
            return redirect()->route('admin.statistik')->with('error', 'Statistik already exists');
        }
        $statistik = new Statistik();
        $statistik->bulan = $request->bulan;
        $statistik->tahun = $request->tahun;
        $statistik->jumlah_perempuan = $request->pengunjung_perempuan;
        $statistik->jumlah_lakilaki = $request->pengunjung_laki_laki;
        $statistik->tidak_diketahui = $request->tidak_diketahui;
        $statistik->user_id = Crypt::decrypt(Session::get('user_id'));
        $statistik->save();
        return redirect()->route('admin.statistik')->with('success', 'Statistik created successfully');
    }

    public function statistikEdit($id)
    {
        $statistik = Statistik::find($id);
        return view('admin.statistik-edit', compact('statistik'));
    }
    public function statistikUpdate(Request $request)
    {
        $request->validate([
            'bulan' => 'required|numeric',
            'tahun' => 'required|numeric',
            'pengunjung_perempuan' => 'required|numeric',
            'pengunjung_laki_laki' => 'required|numeric',
            'tidak_diketahui' => 'required|numeric',
        ]);
        $statistik = Statistik::find($request->id);
        $statistik->bulan = $request->bulan;
        $statistik->tahun = $request->tahun;
        $statistik->jumlah_perempuan = $request->pengunjung_perempuan;
        $statistik->jumlah_lakilaki = $request->pengunjung_laki_laki;
        $statistik->tidak_diketahui = $request->tidak_diketahui;
        $statistik->user_id = Crypt::decrypt(Session::get('user_id'));
        $statistik->save();
        return redirect()->route('admin.statistik')->with('success', 'Statistik updated successfully');
    }
    public function statistikDestroy($id)
    {
        $statistik = Statistik::find($id);
        $statistik->delete();
        return redirect()->route('admin.statistik')->with('success', 'Statistik deleted successfully');
    }

    public function income()
    {
        return view('admin.income');
    }

    public function incomeData()
    {
        $incomes = Income::all();
        $data = $incomes->map(function ($income) {
            return [
                'id' => $income->id,
                'tahun' => $income->tahun,
                'bulan' => date('F', mktime(0, 0, 0, $income->bulan, 10)),
                'amount' => number_format($income->amount, 0, ',', '.'),
            ];
        });
        return response()->json($data);
    }

    public function incomeStore(Request $request)
    {
        $request->validate([
            'bulan'          => 'required|numeric',
            'tahun'          => 'required|numeric',
            'total_sum'      => 'required',
            'type'           => 'array|required',
            'related_data'   => 'array|required',
            'harga_satuan'   => 'array|required',
            'jumlah_terjual' => 'array|required',
        ]);

        // Create the Income record only once
        $income = Income::create([
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'amount' => $request->total_sum,
        ]);

        // Loop through the income details and create them
        foreach ($request->type as $index => $type) {
            IncomeDetail::create([
                'income_id'     => $income->id,
                'type'          => $type,
                'metode'          => 'manual',
                'ticket_id'     => $type == 2 ? null : $request->related_data[$index],
                'facilities_id' => $type == 2 ? $request->related_data[$index] : null,
                'harga_satuan'  => $request->harga_satuan[$index],
                'jumlah'        => $request->jumlah_terjual[$index],
                'amount'        => $request->harga_satuan[$index] * $request->jumlah_terjual[$index],
            ]);
        }

        return response()->json(['message' => 'Income stored successfully']);
    }

    public function incomeShow($id)
    {
        $income = Income::with('incomeDetail')->find($id);
        return response()->json($income);
    }

    public function incomeUpdate(Request $request)
    {
        $request->validate([
            'bulan'          => 'required|numeric',
            'tahun'          => 'required|numeric',
            'total_sum'      => 'required',
            'type'           => 'array|required',
            'related_data'   => 'array|required',
            'harga_satuan'   => 'array|required',
            'jumlah_terjual' => 'array|required',
        ]);

        $income = Income::find($request->id);
        $income->update([
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'amount' => $request->total_sum,
        ]);

        // Loop through the income details and create them
        foreach ($request->type as $index => $type) {
            if (isset($request->id_detail[$index])) {
                $incomeDetail = IncomeDetail::find($request->id_detail[$index]);
                $incomeDetail->update([
                    'type'          => $type,
                    'ticket_id'     => $type == 2 ? null : $request->related_data[$index],
                    'facilities_id' => $type == 2 ? $request->related_data[$index] : null,
                    'harga_satuan'  => $request->harga_satuan[$index],
                    'jumlah'        => $request->jumlah_terjual[$index],
                    'amount'        => $request->harga_satuan[$index] * $request->jumlah_terjual[$index],
                ]);
            } else {
                IncomeDetail::create([
                    'income_id'     => $income->id,
                    'type'          => $type,
                    'ticket_id'     => $type == 2 ? null : $request->related_data[$index],
                    'facilities_id' => $type == 2 ? $request->related_data[$index] : null,
                    'harga_satuan'  => $request->harga_satuan[$index],
                    'jumlah'        => $request->jumlah_terjual[$index],
                    'amount'        => $request->harga_satuan[$index] * $request->jumlah_terjual[$index],
                ]);
            }
        }

        return response()->json(['message' => 'Income updated successfully']);
    }

    public function incomeDestroy($id)
    {
        $income = Income::find($id);
        $income->delete();

        return response()->json(['message' => 'Income deleted successfully']);
    }

    public function getTiketTerusan()
    {
        $data = Ticket::select('id', 'name', 'price')->where('type', 0)->get();
        return response()->json($data);
    }

    public function getTiketSatuan()
    {
        $data = Ticket::select('id', 'name', 'price')->where('type', 1)->get();
        return response()->json($data);
    }

    public function getFasilitas()
    {
        $data = Facility::select('id', 'name')->get();
        return response()->json($data);
    }

    public function exportTiketToExcel()
    {
        // Ambil data tiket dan fasilitas
        $tikets = DB::table('tickets')->get();
        $fasilitas = DB::table('facilities')->get();

        // Pisahkan tiket berdasarkan kategori
        $tiketTerusan = $tikets->filter(function ($tiket) {
            return stripos($tiket->name, 'paket') !== false;
        });

        $tiketSatuan = $tikets->filter(function ($tiket) {
            return stripos($tiket->name, 'paket') === false;
        });

        $kategoriData = [
            'Tiket Terusan' => $tiketTerusan,
            'Tiket Satuan'  => $tiketSatuan,
            'Fasilitas'     => $fasilitas,
        ];

        // Buat file Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'Kategori');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'Total Pendapatan');

        // Isi data
        $row = 2; // Mulai dari baris ke-2
        foreach ($kategoriData as $kategori => $items) {
            if ($items->isEmpty()) {
                continue; // Skip kategori kosong
            }

            // Tulis data per kategori
            foreach ($items as $item) {
                $sheet->setCellValue("A{$row}", $kategori); // Kosongkan kolom 
                $sheet->getStyle("A{$row}")->getFont()->setBold(true);
                $sheet->setCellValue("B{$row}", $item->name);
                $sheet->setCellValue("C{$row}", ''); // Tambahkan nilai total jika ada data terkait
                $row++;
            }

            // Tambahkan jarak antar kategori
            $row++;
        }

        // Simpan file Excel dalam format XLS
        $fileName = 'Tiket_Report.xls';
        $writer = new Xls($spreadsheet);
        $filePath = storage_path("app/public/{$fileName}");
        $writer->save($filePath);

        // Unduh file
        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
