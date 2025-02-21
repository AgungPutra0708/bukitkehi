<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\User;
use App\Models\Income;
use App\Models\Ticket;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\Aparatur;
use App\Models\Checkout;
use App\Models\Statistik;
use App\Models\Destination;
use App\Models\Facility;
use App\Models\SupportObject;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function index()
    {
        $destinations = Destination::all()->take(3);
        $tickets = Ticket::all()->take(3);
        $aparatur = Aparatur::all();
        $news = News::latest()->take(3)->get();
        $products = Product::latest()->take(3)->get();
        return view('landing', compact('destinations', 'tickets', 'aparatur', 'news', 'products'));
    }
    public function login()
    {
        return view('login');
    }
    public function loginPost(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            if (Auth::user()->level == 'admin') {

                Session::put('user_id', Crypt::encrypt(Auth::user()->id));
                return redirect('/admin/dashboard');
            } elseif (Auth::user()->level == 'user') {
                return redirect()->intended('/');
            }
        }

        return redirect('/login')->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
    public function register()
    {
        return view('register');
    }
    public function registerPost(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'level' => 'user',
            'password' => Hash::make($request->password),
        ]);
        return redirect('/login')->with('success', 'Register berhasil, silahkan login');
    }

    // Destination
    public function destination()
    {
        $destination = Destination::all();
        return view('destination', compact('destination'));
    }

    public function destinationDetail($slug)
    {
        $destination = Destination::where('slug', $slug)->first();
        return view('destination-detail', compact('destination'));
    }

    // Article
    public function news()
    {
        $news = News::where('status', 'publish')->get();
        return view('news', compact('news'));
    }

    public function newsDetail($slug)
    {
        $news = News::where('slug', $slug)->first();
        return view('newsDetail', compact('news'));
    }

    // Galery
    public function gallery()
    {
        $vidio = Gallery::where('type', 'video')->get();
        $image = Gallery::where('type', 'image')->get();
        return view('gallery', compact('vidio', 'image'));
    }

    // Product
    public function product()
    {
        $product = Product::all();
        return view('product', compact('product'));
    }
    public function productShow($slug)
    {
        $product = Product::where('slug', $slug)->first();
        return view('product-detail', compact('product'));
    }

    // Facility
    public function facility()
    {
        $facility = Facility::all();
        return view('facility', compact('facility'));
    }
    public function facilityDetail($slug)
    {
        $facility = Facility::where('slug', $slug)->first();
        return view('facility-detail', compact('facility'));
    }

    // views
    public function views()
    {
        return view('views');
    }

    // object
    public function objects()
    {
        return view('objects');
    }
    public function objectsDetail($id)
    {
        $objects = SupportObject::find($id);
        return view('objects-detail', compact('objects'));
    }

    public function findNearestStores(Request $request)
    {
        $userLat = $request->input('latitude');
        $userLng = $request->input('longitude');
        $radius = $request->input('radius', 20); // Default radius dalam km
        $type = $request->input('type', ''); // Filter berdasarkan tipe

        $query = "
            SELECT o.id, o.name, o.latitude, o.longitude, o.tipe, o.description,
                (SELECT image FROM support_object_images WHERE object_id = o.id LIMIT 1) AS image,
                (6371 * ACOS(
                    COS(RADIANS(?)) 
                    * COS(RADIANS(o.latitude)) 
                    * COS(RADIANS(o.longitude) - RADIANS(?)) 
                    + SIN(RADIANS(?)) 
                    * SIN(RADIANS(o.latitude))
                )) AS distance
            FROM objek_pendukung o
        ";

        $params = [$userLat, $userLng, $userLat];

        if (!empty($type)) {
            $query .= " WHERE o.tipe = ?";
            $params[] = $type;
        }

        $query .= " HAVING distance < ? ORDER BY distance ASC";
        $params[] = $radius;

        $stores = DB::select($query, $params);

        return response()->json($stores);
    }

    public function contact()
    {
        return view('contact');
    }

    public function ticket()
    {
        $ticketS = Ticket::all();

        // Simpan data rating, klik, dan checkout tiap tiket
        $ticketData = [];

        foreach ($ticketS as $ticket) {
            $ticketData[$ticket->id] = [
                'rating' => $ticket->averageRating(),
                'clicks' => $ticket->totalClicks(),
                'checkout' => $ticket->totalCheckout(),
            ];
        }

        // Hitung Cosine Similarity antar tiket
        $similarityMatrix = [];

        foreach ($ticketData as $ticketA => $dataA) {
            foreach ($ticketData as $ticketB => $dataB) {
                if ($ticketA !== $ticketB) {
                    // Perhitungan cosine similarity
                    $dotProduct = ($dataA['rating'] * $dataB['rating']) +
                                ($dataA['clicks'] * $dataB['clicks']) +
                                ($dataA['checkout'] * $dataB['checkout']);

                    $magnitudeA = sqrt(pow($dataA['rating'], 2) + pow($dataA['clicks'], 2) + pow($dataA['checkout'], 2));
                    $magnitudeB = sqrt(pow($dataB['rating'], 2) + pow($dataB['clicks'], 2) + pow($dataB['checkout'], 2));

                    $similarityMatrix[$ticketA][$ticketB] = ($magnitudeA * $magnitudeB) ? ($dotProduct / ($magnitudeA * $magnitudeB)) : 0;
                }
            }
        }
        // Ambil semua tiket yang sudah pernah dibeli oleh siapa pun
        $purchasedTickets = Cart::pluck('ticket_id')->toArray();

        // Buat daftar rekomendasi tiket
        $recommendations = [];

        foreach ($ticketData as $ticketId => $data) {
            if (in_array($ticketId, $purchasedTickets)) { // Hanya tiket yang belum pernah dibeli siapa pun
                $score = 0;
                foreach ($purchasedTickets as $purchasedTicket) {
                    if (isset($similarityMatrix[$purchasedTicket][$ticketId])) {
                        $score += $similarityMatrix[$purchasedTicket][$ticketId];
                    }
                }
                if ($score > 0) {
                    $recommendations[$ticketId] = $score;
                }
            }
        }

        // Urutkan berdasarkan skor tertinggi
        arsort($recommendations);

        // Ambil 5 tiket rekomendasi teratas
        $recommendedTickets = Ticket::whereIn('id', array_keys(array_slice($recommendations, 0, 5, true)))->get();

        // Ambil semua tiket yang tersedia
        $tickets = Ticket::where('status', 'publish')->get();
        $supportObjects = SupportObject::all();

        return view('ticket', compact('tickets', 'supportObjects', 'recommendedTickets'));
    }

    // Sejarah
    public function sejarah()
    {
        return view('sejarah');
    }

    // Statistik
    public function statistik()
    {
        $statistik = Statistik::all();
        $tahun = Statistik::select('tahun')->distinct()->get();

        // income
        $incomes = Income::all();
        $income_tahun = Income::select('tahun')->distinct()->get();
        return view('statistik', compact('statistik', 'tahun', 'incomes', 'income_tahun'));
    }
    public function getStatistikData(Request $request)
    {
        // Validasi input
        $request->validate([
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12',
        ]);

        $year = $request->input('year');
        $month = $request->input('month');

        // Ambil data statistik berdasarkan tahun dan bulan
        $statistik = Statistik::where('tahun', $year)
            ->where('bulan', $month)
            ->get();

        // Ambil data income beserta detailnya
        $incomes = Income::where('tahun', $year)
            ->where('bulan', $month)
            ->with(['incomeDetail'])
            ->get();

        // Pisahkan data berdasarkan tipe
        $facilityIncomes = $incomes->flatMap->incomeDetail->where('type', 2); // Data fasilitas
        $ticketIncomes = $incomes->flatMap->incomeDetail->whereIn('type', [0, 1]); // Data tiket

        // Proses data fasilitas
        $groupedFacilities = $facilityIncomes->groupBy('facilities_id')->map(function ($group, $facilitiesId) {
            $relatedName = optional($group->first()->fasilitas)->name ?? 'Fasilitas Tidak Ditemukan';
            $totalAmount = $group->sum('amount');

            return [
                'related_id' => $facilitiesId ?? 'unknown_facility',
                'related_name' => $relatedName,
                'total_amount' => $totalAmount,
            ];
        });

        // Proses data tiket
        $groupedTickets = $ticketIncomes->groupBy('ticket_id')->map(function ($group, $ticketId) {
            $relatedName = optional($group->first()->tiket)->name ?? 'Tiket Tidak Ditemukan';

            return [
                'related_id' => $ticketId ?? 'unknown_ticket',
                'related_name' => $relatedName,
                'total_amount' => $group->sum('amount'),
            ];
        });

        // Gabungkan hasil
        $groupedIncomes = $groupedFacilities->merge($groupedTickets)->values();

        // Format data untuk response
        $visitorStatistics = view('visitor-statistics', compact('statistik'))->render();
        $incomeStatistics = view('income-statistics', compact('groupedIncomes'))->render();

        return response()->json([
            'visitorStatistics' => $visitorStatistics,
            'incomeStatistics' => $incomeStatistics,
        ]);
    }
    public function statistikTahunPost(Request $request)
    {
        $tahun = $request->tahun;
        return redirect()->route('landing.statistik.tahun', $tahun);
    }

    public function statistikTahun($tahun)
    {
        $statistik = Statistik::where('tahun', $tahun)->get();
        $incomes = Income::where('tahun', $tahun)->get();
        $tahun = $tahun;
        $tahunList = Statistik::select('tahun')->distinct()->get();
        return view('statistik-tahun', compact('statistik', 'incomes', 'tahun', 'tahunList'));
    }

    public function ticketShow($name)
    {
        $ticket = Ticket::find($name);

        if (!$ticket) {
            return redirect('/ticket')->withErrors('Tiket tidak ditemukan.');
        }

        return view('ticket-detail', compact('ticket'));
    }
}
