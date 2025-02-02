@extends('layouts.landing')

@section('content')
    <!-- BreadCrumb Starts -->
    <section class="breadcrumb-main pb-20 pt-14"
        style="background-image: url({{ asset('storage/objek/' . $objects->image) }});" style="object-fit: cover">
        <div class="section-shape section-shape1 top-inherit bottom-0" style="background-image: url(images/shape8.png);">
        </div>
        <div class="breadcrumb-outer">
            <div class="container">
                <div class="breadcrumb-content text-center">
                    <h1 class="mb-3">{{ $objects->name }}</h1>
                    <nav aria-label="breadcrumb" class="d-block">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Beranda</a></li> 
                            <li class="breadcrumb-item active" aria-current="page"> Detail Objek</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div class="dot-overlay"></div>
    </section>
    <!-- BreadCrumb Ends -->

    <!-- top Objek starts -->
    <section class="trending pt-6 pb-0 bg-lgrey">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="single-content">
                        <div id="highlight" class="mb-4">
                            <div class="single-full-title border-b mb-2 pb-2">
                                <div class="single-title">
                                    <h2 class="mb-1">{{ $objects->name }}</h2>
                                    <div class="rating-main d-flex align-items-center">
                                        <p class="mb-0 me-2"><i class="icon-location-pin"></i> {{ $objects->address }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="description-images mb-4">
                                <img src="{{ asset('storage/objek/' . $objects->image) }}" alt=""
                                    class="w-100 rounded">
                            </div>

                            <div class="description mb-2">
                                <h4>Description</h4>
                                <p>{!! $objects->description !!}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- sidebar starts -->
                <div class="col-lg-4">
                    <div class="sidebar-sticky">
                        <div class="list-sidebar">

                            <div class="sidebar-item mb-4">
                                <h4 class="">All Menu</h4>
                                <ul class="sidebar-category">
                                    <li><a href="{{ route('landing.destination') }}">Wahana</a></li>
                                    <li><a href="{{ route('landing.gallery') }}">Galeri</a></li>
                                    <li><a href="{{ route('landing.product') }}">Produk</a></li>
                                    <li class="active"><a href="{{ route('landing.objects') }}">Objek</a></li>
                                    <li><a href="{{ route('landing.news') }}">Artikel</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- top Objek ends -->
@endsection
