@extends('layouts.landing')

@section('content')
    <!-- BreadCrumb Starts -->
    <section class="breadcrumb-main pb-20 pt-14"
        style="background-image: url({{ asset('storage/facility/' . $facility->image) }});" style="object-fit: cover">
        <div class="section-shape section-shape1 top-inherit bottom-0" style="background-image: url(images/shape8.png);">
        </div>
        <div class="breadcrumb-outer">
            <div class="container">
                <div class="breadcrumb-content text-center">
                    <h1 class="mb-3">{{ $facility->name }}</h1>
                    <nav aria-label="breadcrumb" class="d-block">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Beranda</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail Fasilitas</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div class="dot-overlay"></div>
    </section>
    <!-- BreadCrumb Ends -->

    <!-- top Facility starts -->
    <section class="trending pt-6 pb-0 bg-lgrey">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="single-content">
                        <div id="highlight" class="mb-4">
                            <div class="single-full-title border-b mb-2 pb-2">
                                <div class="single-title">
                                    <h2 class="mb-1">{{ $facility->name }}</h2>
                                    <div class="rating-main d-flex align-items-center">
                                        <p class="mb-0 me-2"><i class="icon-location-pin"></i> Kabupaten Pamekasan, Jawa
                                            Timur </p>
                                    </div>
                                </div>
                            </div>
                            <div class="description-images mb-4">
                                <img src="{{ asset('storage/facility/' . $facility->image) }}" alt=""
                                    class="w-100 rounded">
                            </div>

                            <div class="description mb-2">
                                <div class="row align-items-center">
                                    <div class="col-md-6 col-6 text-start">
                                        <h3 class="mb-0">Rp. {{ number_format($facility->price, 0, ',', '.') }}</h3>
                                    </div>
                                    <div class="col-md-6 col-6 d-flex align-items-center justify-content-end">
                                        <button class="btn btn-sm btn-outline-primary" onclick="decreaseQty({{ $facility->id }})">-</button>
                                        <input type="text" id="qty-{{ $facility->id }}" class="form-control text-center mx-2" value="1" style="width: 60px;">
                                        <button class="btn btn-sm btn-outline-primary" onclick="increaseQty({{ $facility->id }})">+</button>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col d-flex align-items-center justify-content-end">
                                        <button class="nir-btn btn-secondary" onclick="addToCart({{ $facility->id }})">
                                            <i class="fa-solid fa-cart-shopping"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>                            
                            <div class="description mb-2">
                                <h4>Deskripsi</h4>
                                <p>{!! $facility->description !!}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- sidebar starts -->
                <div class="col-lg-4">
                    <div class="sidebar-sticky">
                        <div class="list-sidebar">

                            <div class="sidebar-item mb-4">
                                <h4 class="">Semua Menu</h4>
                                <ul class="sidebar-category">
                                    <li><a href="{{ route('landing.destination') }}">Wahana</a></li>
                                    <li><a href="{{ route('landing.gallery') }}">Galeri</a></li>
                                    <li><a href="{{ route('landing.product') }}">Produk</a></li>
                                    <li class="active"><a href="{{ route('landing.facility') }}">Fasilitas</a></li>
                                    <li><a href="{{ route('landing.news') }}">Artikel</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- top Facility ends -->

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
                        type: 'facility',
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
