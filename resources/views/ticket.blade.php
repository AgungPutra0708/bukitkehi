@extends('layouts.landing')

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
                <div class="col-lg-12">
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
                                            <small>Paket Tiket </small>
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
                                                {{-- <span class="mb-0">Dari</span> --}}
                                                <h3 class="mb-0">Rp. {{ number_format($ticket->price, 0, ',', '.') }}</h3>
                                                {{-- <small>Per Dewasa</small> --}}
                                            </div>
                                            <a href="{{ route('checkout', $ticket->id) }}" class="nir-btn">Check-out</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="row">
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
                    <div class="col-lg-4 col-md-6 support-object-item" data-type="{{ $object->tipe }}">
                        <a href="{{ route('landing.objects.detail', $object->id) }}" class="text-decoration-none">
                            <div
                                class="trend-full bg-white rounded box-shadow overflow-hidden p-4 mb-4 d-flex flex-column h-100">

                                <!-- Gambar atau No Image -->
                                <div class="trend-item2 rounded d-flex align-items-center justify-content-center"
                                    style="width: 100%; height: 200px; background: #f0f0f0; border-radius: 10px; flex-shrink: 0;">

                                    @if ($object->image && file_exists(public_path('storage/objek/' . $object->image)))
                                        <img src="{{ asset('storage/objek/' . $object->image) }}"
                                            class="img-fluid w-100 rounded" alt="{{ $object->name }}"
                                            style="max-height: 200px; object-fit: cover;">
                                    @else
                                        <div class="text-center w-100" style="font-size: 18px; color: #aaa;">
                                            No Image
                                        </div>
                                    @endif
                                </div>

                                <!-- Konten Card -->
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
@endsection
