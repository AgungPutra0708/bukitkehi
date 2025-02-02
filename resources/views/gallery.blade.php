@extends('layouts.landing')

@section('content')
    <!-- BreadCrumb Starts -->
    <section class="breadcrumb-main pb-20 pt-14" style="background-image:url({{ asset('assets-landing/images/15.jpg') }})">
        <div class="section-shape section-shape1 top-inherit bottom-0" style="background-image: url(images/shape8.png);">
        </div>
        <div class="breadcrumb-outer">
            <div class="container">
                <div class="breadcrumb-content text-center">
                    <h1 class="mb-3">Galeri Satu</h1>
                    <nav aria-label="breadcrumb" class="d-block">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Beranda</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Galeri Satu</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div class="dot-overlay"></div>
    </section>
    <!-- BreadCrumb Ends -->

    <!-- Gallery starts -->
    <div class="gallery pt-6 pb-0">
        <div class="container">
            <div class="section-title mb-6 text-center w-75 mx-auto">
                <h4 class="mb-1 theme1">Galeri Kami</h4>
                <h2 class="mb-1">Beberapa Potret <span class="theme">Indah</span></h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.</p>
            </div>
            <div class="row">

                @foreach ($image as $item)
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <div class="gallery-item mb-4 rounded overflow-hidden">
                            <div class="gallery-image">
                                <img src="{{ asset('storage/gallery/' . $item->file) }}" alt="image">
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
    <div class="gallery pt-6 pb-0">
        <div class="container">
            <div class="section-title mb-6 text-center w-75 mx-auto">
                <h4 class="mb-1 theme1">Video Kami</h4>
                <p>Galeri Video </p>
            </div>
            <div class="row">
                @foreach ($vidio as $item)
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="gallery-item mb-4 rounded overflow-hidden">
                            <div class="gallery-image">
                                @if (filter_var($item->file, FILTER_VALIDATE_URL))
                                    @if (strpos($item->file, 'youtube.com') !== false || strpos($item->file, 'youtu.be') !== false)
                                        <!-- Video dari YouTube -->
                                        <div class="video-container">
                                            <iframe src="{{ str_replace('watch?v=', 'embed/', $item->file) }}"
                                                class="rounded" frameborder="0"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                allowfullscreen>
                                            </iframe>
                                        </div>
                                    @else
                                        <!-- Video dari URL file video langsung -->
                                        <video src="{{ $item->file }}"
                                            style="width: 100%; height: 100%; object-fit: cover;" class="w-100 rounded"
                                            controls>
                                        </video>
                                    @endif
                                @else
                                    <video src="{{ asset('storage/gallery/' . $item->file) }}" alt="video" controls
                                        class="w-100"></video>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Gallery Ends -->
@endsection
