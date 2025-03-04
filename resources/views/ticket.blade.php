@extends('layouts.landing')

@section('style')
    <style>
        .recommendation-card {
            display: flex;
            align-items: center;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            padding: 15px;
            text-align: left;
            gap: 15px;
            /* Jarak antara gambar dan teks */
        }

        .recommendation-card img {
            width: 100px;
            /* Ukuran gambar lebih kecil */
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            flex-shrink: 0;
            /* Mencegah gambar mengecil */
        }

        .recommendation-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .recommendation-content h5 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .recommendation-content p {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .recommendation-btn {
            text-align: right;
        }

        .recommendation-btn .nir-btn {
            font-size: 14px;
            padding: 6px 12px;
        }
    </style>
@endsection

@section('content')
    <!-- BreadCrumb Starts -->
    <section class="breadcrumb-main pb-20 pt-14" style="background-image:url({{ asset('assets-landing/images/18.jpg') }})">
        <div class="section-shape section-shape1 top-inherit bottom-0" style="background-image: url(images/shape8.png);">
        </div>
        <div class="breadcrumb-outer">
            <div class="container">
                <div class="breadcrumb-content text-center">
                    <h1 class="mb-3">Harga Dan Pemesanan Tiket</h1>
                    <nav aria-label="breadcrumb" class="d-block">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Beranda</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tiket</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div class="dot-overlay"></div>
    </section>
    <!-- BreadCrumb Ends -->

    <!-- top Destination starts -->
    <section class="trending pt-6 pb-6 bg-lgrey">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="destination-list">
                        @foreach ($tickets as $ticket)
                            <div class="trend-full bg-white rounded box-shadow overflow-hidden p-4 mb-4">
                                <div class="row">
                                    <div class="col-lg-4 col-md-3">
                                        <div class="trend-item2 rounded">
                                            <a href="tour-single.html"
                                                style="background-image: url({{ asset('storage/ticket/' . $ticket->photo) }});"></a>
                                            <div class="color-overlay"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-md-6">
                                        <div class="trend-content position-relative text-md-start text-center">
                                            <small>
                                                @if ($ticket->type == 0)
                                                    Tiket Terusan
                                                @else
                                                    Tiket Satuan
                                                @endif
                                            </small>
                                            <h3 class="mb-1"><a
                                                    href="{{ route('ticket.show', $ticket->id) }}">{{ $ticket->name }}</a>
                                            </h3>
                                            <h6 class="theme mb-0"><i class="icon-location-pin"></i> Pamekasan, Jawa Timur
                                            </h6>
                                            <p class="mt-4 mb-0">{!! Str::limit($ticket->description, 20) !!}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3">
                                        <div class="trend-content text-md-end text-center">
                                            <div class="trend-price my-2">
                                                <h3 class="mb-0">Rp. {{ number_format($ticket->price, 0, ',', '.') }}</h3>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-end mb-2 me-1">
                                                <button class="btn btn-sm btn-outline-primary"
                                                    onclick="decreaseQty({{ $ticket->id }})">-</button>
                                                <input type="text" id="qty-{{ $ticket->id }}"
                                                    class="form-control text-center mx-2" value="1"
                                                    style="width: 60px;">
                                                <button class="btn btn-sm btn-outline-primary"
                                                    onclick="increaseQty({{ $ticket->id }})">+</button>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                {{-- <a href="{{ route('checkout', $ticket->id) }}" class="nir-btn me-2">Beli</a> --}}
                                                <button class="nir-btn btn-secondary"
                                                    onclick="addToCart({{ $ticket->id }})"><i
                                                        class="fa-solid fa-cart-shopping"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-4">
                    @if ($recommendedTickets->count() > 0)
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <h4 class="mb-3">Rekomendasi Tiket Untuk Anda</h4>
                            </div>
                        </div>
                        <div class="row">
                            @foreach ($recommendedTickets as $ticket)
                                <div class="col-lg-12 mb-3">
                                    <div class="recommendation-card">
                                        <!-- Gambar di kiri -->
                                        <img src="{{ asset('storage/ticket/' . $ticket->photo) }}"
                                            alt="{{ $ticket->name }}">

                                        <!-- Konten di kanan -->
                                        <div class="recommendation-content">
                                            <h5>{{ $ticket->name }}</h5>
                                            <p>{{ number_format($ticket->price, 0, ',', '.') }} IDR</p>
                                            <div class="recommendation-btn">
                                                <a href="{{ route('ticket.show', $ticket->id) }}"
                                                    class="nir-btn btn-sm">Lihat Detail</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-lg-12 text-center">
                            <a href="https://wa.me/6285231065084?text=Nama%20%3A%0AAcara%20%3A%0AJumlah%20orang%20%3A%0ATanggal%20%3A%0AFasilitas%20yang%20di%20butuhkan%20%3A" 
                               target="_blank" 
                               class="btn btn-success btn-lg">
                                <i class="fab fa-whatsapp"></i> Reservasi via WhatsApp
                            </a>
                        </div>
                    </div>                    
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2>Rekomendasi Objek Pendukung</h2>
                </div>
                <div class="col-lg-12 text-center">
                    <div class="d-flex flex-wrap justify-content-center m-n1">
                        <button type="button" class="btn btn-outline-dark m-1" data-filter="*">All</button>
                        <button type="button" class="btn btn-outline-dark m-1" data-filter="1">Hotel</button>
                        <button type="button" class="btn btn-outline-dark m-1" data-filter="2">Restoran/Wisata
                            Kuliner</button>
                        <button type="button" class="btn btn-outline-dark m-1" data-filter="3">Tempat Wisata
                            Lainnya</button>
                    </div>
                </div>
            </div>
            <div class="row pt-2">
                @foreach ($supportObjects as $object)
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-1 support-object-item" data-type="{{ $object->tipe }}">
                        <a href="{{ route('landing.objects.detail', $object->id) }}" class="text-decoration-none">
                            <div class="trend-full bg-white rounded box-shadow overflow-hidden p-4 mb-4 d-flex flex-column h-100"
                                style="max-height: 400px!important;">
            
                                <div class="trend-item2 rounded d-flex align-items-center justify-content-center"
                                    style="width: 100%; height: 200px; background: #f0f0f0; border-radius: 10px; flex-shrink: 0;">
                                    @if (!empty($object->image))
                                        <img src="{{ asset('storage/objek/' . $object->image) }}"
                                            class="img-fluid w-100 rounded" alt="{{ $object->name }}"
                                            style="max-height: 200px; object-fit: cover;">
                                    @else
                                        <div class="text-center w-100" style="font-size: 18px; color: #aaa;">
                                            No Image
                                        </div>
                                    @endif
                                </div>
            
                                <div class="trend-content position-relative text-md-start text-center mt-3 flex-grow-1">
                                    <h3 class="mb-1">{{ $object->name }}</h3>
                                    <h6 class="theme mb-0"><i class="icon-location-pin"></i> {{ $object->address }}</h6>
                                </div>
            
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>       
        </div>
    </section>
    <!-- top Destination ends -->

    <script>
        function increaseQty(id) {
            let qtyInput = document.getElementById('qty-' + id);
            qtyInput.value = parseInt(qtyInput.value) + 1;
        }

        function decreaseQty(id) {
            let qtyInput = document.getElementById('qty-' + id);
            if (qtyInput.value > 1) {
                qtyInput.value = parseInt(qtyInput.value) - 1;
            }
        }

        function addToCart(id) {
            let qty = document.getElementById('qty-' + id).value;

            if (qty <= 0 || isNaN(qty)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Jumlah tidak valid',
                    text: 'Masukkan jumlah tiket yang benar!',
                });
                return;
            }

            @if (!Auth::check())
                Swal.fire({
                    icon: 'warning',
                    title: 'Harap Login!',
                    text: 'Silakan login terlebih dahulu untuk menambahkan tiket ke keranjang.',
                    confirmButtonText: 'Login'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route('login') }}';
                    }
                });
            @endif

            @if (Auth::check())
                $.ajax({
                    url: '/cart/add/' + id,
                    type: 'POST',
                    data: {
                        quantity: qty,
                        type: 'ticket',
                        _token: $('meta[name="csrf-token"]').attr('content') // Tambahkan CSRF Token
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Ditambahkan ke keranjang.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Harap Login!',
                            text: 'Silakan login terlebih dahulu untuk menambahkan tiket ke keranjang.',
                            confirmButtonText: 'Login'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '{{ route('login') }}';
                            }
                        });
                    }
                });
            @endif
        }
    </script>
@endsection
