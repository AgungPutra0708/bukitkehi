@extends('layouts.landing')

@section('content')
    <!-- BreadCrumb Starts -->
    <section class="breadcrumb-main pb-20 pt-14"
        style="background-image: url({{ asset('assets-landing/images/bg/bg1.jpg') }});">
        <div class="section-shape section-shape1 top-inherit bottom-0" style="background-image: url(images/shape8.png);">
        </div>
        <div class="breadcrumb-outer">
            <div class="container">
                <div class="breadcrumb-content text-center">
                    <h1 class="mb-3">Pesanan Saya</h1>
                    <nav aria-label="breadcrumb" class="d-block">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Beranda</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Pesanan Saya</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div class="dot-overlay"></div>
    </section>
    <!-- BreadCrumb Ends -->
    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-12">
                <div class="text-center">
                    <h2>Keranjang Saya</h2>
                </div>
                <div class="destination-list">
                    @foreach ($cart as $item)
                        <div class="trend-full bg-white rounded box-shadow overflow-hidden p-2 mb-2">
                            <div class="row">
                                <div class="col-lg-4 col-md-3">
                                    <a href="{{ route('ticket.show', $item->ticket->id) }}">
                                        <div class="trend-item2 rounded">
                                            <img src="{{ asset('storage/ticket/' . $item->ticket->photo) }}" class="img"
                                                alt="">
                                        </div>
                                    </a>
                                </div>
                                <div class="col-lg-5 col-md-6">
                                    <div class="trend-content position-relative text-md-start text-center">
                                        <small>
                                            @if ($item->ticket->type == '0')
                                                Tiket Terusan
                                            @else
                                                Tiket Satuan
                                            @endif
                                        </small>
                                        <h3 class="mb-1">
                                            {{ $item->ticket->name }}
                                        </h3>
                                        <h6 class="theme mb-0"><i class="icon-location-pin"></i> Pamekasan, Jawa Timur
                                        </h6>
                                        <p class="mt-4 mb-0">{!! Str::limit($item->ticket->description, 20) !!}</p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3">
                                    <div class="d-flex align-items-center justify-content-end my-2">
                                        <h3 class="mb-0">Rp.
                                            {{ number_format($item->ticket->price * $item->quantity, 0, ',', '.') }}
                                        </h3>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-end mb-2 me-1">
                                        <button class="btn btn-sm btn-outline-primary"
                                            onclick="decreaseQty({{ $item->ticket->id }})">-</button>
                                        <input type="text" id="qty-{{ $item->ticket->id }}"
                                            class="form-control text-center mx-2" value="{{ $item->quantity ?? '1' }}"
                                            style="width: 60px;">
                                        <button class="btn btn-sm btn-outline-primary"
                                            onclick="increaseQty({{ $item->ticket->id }})">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="d-flex justify-content-end">
                        <a href="#" class="nir-btn">Checkout</a>
                    </div>
                </div>
                {{-- <table class="table">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Tiket</th>
                            <th>Kuantitas</th>
                            <th>Harga Total</th>
                        </tr>
                    </thead>
                    <tbody class="table-hover table-striped">
                        @foreach ($cart as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->ticket->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>Rp. {{ number_format($item->ticket->price * $item->quantity, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table> --}}
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="text-center">
                    <h2>Riwayat Pesanan</h2>
                </div>
                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Tiket</th>
                            <th>Kuantitas</th>
                            <th>Harga Total</th>
                            <th>Status</th>
                            <th>E-Tiket</th>
                        </tr>
                    </thead>
                    <tbody class="table-hover table-striped">
                        @foreach ($orders as $order)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $order->code }}</td>
                                <td>{{ $order->ticket->name }}</td>
                                <td>{{ $order->quantity }}</td>
                                <td>Rp. {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td>
                                    @if ($order->status == 'accepted')
                                        <label class="badge bg-success">Diterima</label>
                                    @elseif($order->status == 'rejected')
                                        <label class="badge bg-danger">Ditolak</label>
                                    @else
                                        <label class="badge bg-warning">Menunggu Konfirmasi</label>
                                    @endif
                                </td>
                                @if ($order->status == 'accepted')
                                    <td>
                                        <a href="{{ route('user.e-ticket', $order->code) }}" class="btn btn-primary">View
                                            E-Ticket</a>
                                    </td>
                                @else
                                    <td>
                                        <label class="badge bg-warning">Menunggu Konfirmasi</label>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
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

            $.ajax({
                url: '/cart/add/' + id,
                type: 'POST',
                data: {
                    quantity: qty,
                    _token: $('meta[name="csrf-token"]').attr('content') // Tambahkan CSRF Token
                },
                success: function(response) {
                    console.log('berhasil', response);
                },
                error: function(xhr) {
                    console.log('error', xhr);
                }
            });
        }
    </script>
@endsection
