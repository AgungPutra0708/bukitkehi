@extends('layouts.landing')

@section('content')
    <!-- BreadCrumb Starts -->
    <section class="breadcrumb-main pb-20 pt-14" style="background-image: url(images/bg/bg1.jpg);">
        <div class="breadcrumb-outer">
            <div class="container">
                <div class="breadcrumb-content text-center">
                    <h1 class="mb-3 text-white">Statistik</h1>
                    <nav aria-label="breadcrumb" class="d-block">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#" class="text-light">Beranda</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><span class="text-light">Statistik</span>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div class="dot-overlay"></div>
    </section>
    <!-- BreadCrumb Ends -->

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <label for="year-select" class="form-label">Pilih Tahun</label>
                <select id="year-select" class="form-control">
                    <option value="">Pilih Tahun</option>
                    @for ($i = date('Y'); $i >= 2000; $i--)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-6">
                <label for="month-select" class="form-label">Pilih Bulan</label>
                <select id="month-select" class="form-control">
                    <option value="">Pilih Bulan</option>
                    @foreach ([
            '1' => 'Januari',
            '2' => 'Februari',
            '3' => 'Maret',
            '4' => 'April',
            '5' => 'Mei',
            '6' => 'Juni',
            '7' => 'Juli',
            '8' => 'Agustus',
            '9' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ] as $key => $month)
                        <option value="{{ $key }}">{{ $month }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <h3 class="text-primary">Statistik Pengunjung Wisata</h3>
        <div id="visitor-statistics">
            <p class="text-muted">Silakan pilih tahun dan bulan untuk melihat statistik pengunjung.</p>
        </div>
    </div>

    <div class="container py-5">
        <h3 class="text-primary">Penghasilan Wisata</h3>
        <div id="income-statistics">
            <p class="text-muted">Silakan pilih tahun dan bulan untuk melihat data penghasilan.</p>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#year-select, #month-select').on('change', function() {
                const year = $('#year-select').val();
                const month = $('#month-select').val();

                if (year && month) {
                    $.ajax({
                        url: "{{ route('landing.statistik.data') }}",
                        type: "GET",
                        data: {
                            year: year,
                            month: month
                        },
                        success: function(response) {
                            // Update statistics
                            $('#visitor-statistics').html(response.visitorStatistics);
                            $('#income-statistics').html(response.incomeStatistics);
                        },
                        error: function() {
                            alert('Gagal mengambil data. Silakan coba lagi.');
                        }
                    });
                }
            });
        });
    </script>
@endsection
