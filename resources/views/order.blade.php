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
                                    <a href="{{ $item->ticket ? route('ticket.show', $item->ticket->id) : '#' }}">
                                        <div class="trend-item2 rounded">
                                            <img src="{{ asset('storage/' . ($item->ticket ? 'ticket/' . $item->ticket->photo : 'facility/' . $item->facility->image)) }}" 
                                                class="img" alt="">
                                        </div>
                                    </a>
                                </div>
                                <div class="col-lg-5 col-md-6">
                                    <div class="trend-content position-relative text-md-start text-center">
                                        <small>
                                            @if ($item->ticket)
                                                @if ($item->ticket->type == '0')
                                                    Tiket Terusan
                                                @else
                                                    Tiket Satuan
                                                @endif
                                            @else
                                                Fasilitas
                                            @endif
                                        </small>
                                        <h3 class="mb-1">{{ $item->ticket ? $item->ticket->name : $item->facility->name }}</h3>
                                        <h6 class="theme mb-0"><i class="icon-location-pin"></i> Pamekasan, Jawa Timur</h6>
                                        <p class="mt-4 mb-0">
                                            {!! Str::limit($item->ticket ? $item->ticket->description : $item->facility->description, 20) !!}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3">
                                    <div class="d-flex align-items-center justify-content-end my-2">
                                        <h3 class="mb-0" id="total-price-{{ $item->id }}">
                                            Rp. {{ number_format(($item->ticket ? $item->ticket->price : $item->facility->price) * $item->quantity, 0, ',', '.') }}
                                        </h3>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-end mb-2 me-1">
                                        <button class="btn btn-sm btn-outline-primary" onclick="decreaseQty({{ $item->id }})">-</button>
                                        <input type="text" id="qty-{{ $item->id }}" class="form-control text-center mx-2"
                                            value="{{ $item->quantity ?? '1' }}" style="width: 60px;" readonly>
                                        <button class="btn btn-sm btn-outline-primary" onclick="increaseQty({{ $item->id }})">+</button>
                                    </div>
                                    <input type="hidden" id="price-{{ $item->id }}" 
                                        value="{{ $item->ticket ? $item->ticket->price : $item->facility->price }}">
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if (!$cart->isEmpty())
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('checkout', Crypt::encrypt($user)) }}" class="nir-btn">Check-out</a>
                        </div>
                    @endif
                </div>                
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
                            <th>Kuantitas</th>
                            <th>Harga Total</th>
                            <th>Status</th>
                            <th>E-Tiket</th>
                        </tr>
                    </thead>
                    <tbody class="table-hover table-striped">
                        @if ($orders->isEmpty())
                            <tr>
                                <td colspan="8">Tidak Ada Data Keranjang</td>
                            </tr>
                        @else
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $order->code }}</td>
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
                                    <td rowspan="2" class="text-center align-content-center">
                                        @if ($order->status == 'accepted')
                                            <a href="{{ route('user.e-ticket', $order->code) }}"
                                                class="btn btn-primary ">View
                                                E-Ticket</a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6">
                                        <strong>Detail Tiket:</strong>
                                        <ul>
                                            @foreach ($order->carts as $cart)
                                                @if ($cart->ticket)
                                                    <li>{{ $cart->ticket->name }} - {{ $cart->quantity }} x Rp.
                                                        {{ number_format($cart->ticket->price, 0, ',', '.') }}</li>
                                                @else
                                                    <li>{{ $cart->facility->name }} - {{ $cart->quantity }} x Rp.
                                                        {{ number_format($cart->facility->price, 0, ',', '.') }}</li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        function updateTotalPrice(id) {
            let qty = parseInt(document.getElementById('qty-' + id).value);
            let price = parseInt(document.getElementById('price-' + id).value);
            let totalPriceElement = document.getElementById('total-price-' + id);

            // Format harga ke dalam format Rp.
            let formattedPrice = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(price * qty);

            totalPriceElement.innerText = formattedPrice;
        }

        function increaseQty(id) {
            let qtyInput = document.getElementById('qty-' + id);
            qtyInput.value = parseInt(qtyInput.value) + 1;
            updateTotalPrice(id);

            $.ajax({
                url: '/cart/add/' + id,
                type: 'POST',
                data: {
                    quantity: qtyInput.value,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Berhasil menambah item', response);
                },
                error: function(xhr) {
                    console.log('Error:', xhr);
                }
            });
        }

        function decreaseQty(id) {
            let qtyInput = document.getElementById('qty-' + id);

            if (parseInt(qtyInput.value) > 1) {
                qtyInput.value = parseInt(qtyInput.value) - 1;
                updateTotalPrice(id);

                $.ajax({
                    url: '/cart/update/' + id,
                    type: 'POST',
                    data: {
                        quantity: qtyInput.value,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log('Berhasil mengurangi item', response);
                    },
                    error: function(xhr) {
                        console.log('Error:', xhr);
                    }
                });
            } else {
                Swal.fire({
                    title: 'Hapus Item?',
                    text: 'Jumlah tiket 1, jika dikurangi maka item akan dihapus. Lanjutkan?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/cart/destroy/' + id,
                            type: 'GET',
                            success: function(response) {
                                Swal.fire('Terhapus!', 'Item berhasil dihapus.', 'success')
                                    .then(() => location.reload());
                            },
                            error: function(xhr) {
                                Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus.', 'error');
                                console.log('Error:', xhr);
                            }
                        });
                    }
                });
            }
        }
    </script>
@endsection
