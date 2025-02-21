@extends('layouts.landing')

@section('style')
<style>
    .box {
        font-family: 'Poppins', sans-serif;
    }

    .rate {
        border-bottom-right-radius: 12px;
        border-bottom-left-radius: 12px
    }

    .rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center
    }

    .rating>input {
        display: none
    }

    .rating>label {
        position: relative;
        width: 1em;
        font-size: 30px;
        font-weight: 300;
        color: #FFD600;
        cursor: pointer
    }

    .rating>label::before {
        content: "\2605";
        position: absolute;
        opacity: 0
    }

    .rating>label:hover:before,
    .rating>label:hover~label:before {
        opacity: 1 !important
    }

    .rating>input:checked~label:before {
        opacity: 1
    }

    .rating:hover>input:checked~label:before {
        opacity: 0.4
    }

    .buttons {
        top: 36px;
        position: relative
    }

    .rating-submit {
        border-radius: 8px;
        color: #fff;
        height: auto
    }

    .rating-submit:hover {
        color: #fff
    }
</style>
@endsection
@section('content')
<!-- top Destination starts -->
<section class="trending pt-6 pb-0 bg-lgrey">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-xs-12 mb-4">
                <div class="payment-book">
                    <div class="booking-box">
                        <div class="booking-box-title d-md-flex align-items-center bg-title p-4 mb-4 rounded text-md-start text-center">
                            <i class="fa fa-check px-4 py-3 bg-white rounded title fs-5"></i>
                            @if ($order->status == 'accepted')
                            <div class="title-content ms-md-3">
                                <h3 class="mb-1 white">{{ $order->ticket->name }}</h3>
                                <p class="mb-0 white">Terima kasih. Pesanan pemesanan Anda dikonfirmasi sekarang.</p>
                            </div>
                            @else
                            <h3 class="mb-1 white">Pesanan pemesanan Anda adalah {{ $order->status }}.</h3>
                            @endif
                        </div>
                        <div class="travellers-info mb-4">
                            <table>
                                <thead>
                                    <th>Nomor Pemesanan</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Metode Pembayaran</th>
                                    <th>Status</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="theme2">{{ $order->code }}</td>
                                        <td class="theme2">{{ $order->ticket_date }}</td>
                                        <td class="theme2">Rp. {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                        <td class="theme2">Transfer Bank </td>
                                        <td class="theme2">
                                            @if ($order->status == 'accepted')
                                            <span class="badge bg-success">{{ $order->status }}</span>
                                            @elseif ($order->status == 'rejected')
                                            <span class="badge bg-danger">{{ $order->status }}</span>
                                            @else
                                            <span class="badge bg-warning">{{ $order->status }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="travellers-info mb-4">
                            <h4>Informasi Wisatawan</h4>
                            <table>
                                <tr>
                                    <td>Nomor Pemesanan</td>
                                    <td>{{ $order->code }}</td>
                                </tr>
                                <tr>
                                    <td>Nama Pertama</td>
                                    <td>{{ $order->user->name }}</td>
                                </tr>

                                <tr>
                                    <td>Alamat Email</td>
                                    <td>{{ $order->user->email }}</td>
                                </tr>
                                <tr>
                                    <td>Kode QR</td> <!-- Ubah label dari Barcode menjadi QR Code -->
                                    <td>
                                        @if ($qrcode)
                                        <img src="data:image/png;base64,{{ $qrcode }}" alt="QR Code" style="width: 200px; height: 100px;" />
                                        @else
                                        <p>Kode QR tidak tersedia.</p>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="booking-border mb-4">
                            <h4 class="border-b pb-2 mb-2">Tiket yang Dipesan</h4>
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Tiket</th>
                                        <th>Jumlah</th>
                                        <th>Harga</th>
                                        <th>Total</th>
                                        <th>Rating</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->carts as $cart)
                                    <tr>
                                        <td>{{ $cart->ticket->name }}</td>
                                        <td class="text-center">{{ $cart->quantity }}x</td>
                                        <td class="text-end">Rp. {{ number_format($cart->ticket->price, 0, ',', '.') }}</td>
                                        <td class="text-end">Rp. {{ number_format($cart->ticket->price * $cart->quantity, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center mt-5">
                                                <div class="text-center mb-5">
                                                    <div class="rating" data-ticket-id="{{ $cart->ticket->id }}" data-cart-id="{{ $cart->id }}">
                                                        @php $userRating = $cart->rating ? $cart->rating->rating : null; @endphp
                                                        @for ($i = 5; $i >= 1; $i--)
                                                        <input type="radio" name="rating_{{ $cart->id }}" value="{{ $i }}" id="{{ $i }}_{{ $cart->id }}"
                                                            {{ $userRating == $i ? 'checked' : '' }}>
                                                        <label for="{{ $i }}_{{ $cart->id }}">☆</label>
                                                        @endfor
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-xs-12 mb-4 ps-4">
                <div class="sidebar-sticky">
                    <div class="list-sidebar">
                        <div class="sidebar-item bordernone bg-white rounded box-shadow overflow-hidden p-3 mb-4">
                            <h4>Butuh Bantuan Pemesanan?</h4>
                            <div class="sidebar-module-inner">
                                <p class="mb-2">Pembayaran tinggi 24/7 Untuk Tindakan Pencegahan dan keuntungan berbeda.</p>
                                <ul class="help-list">
                                    <li class="border-b pb-1 mb-1"><span class="font-weight-bold">Saluran Berita</span>: +475 15 123 21</li>
                                    <li class="border-b pb-1 mb-1"><span class="font-weight-bold">Email</span>: support@Yatriiworld.com</li>
                                    <li><span class="font-weight-bold">Obrolan Langsung</span>: Yatriiworld (Skype)</li>
                                </ul>
                            </div>
                        </div>
                        <div class="sidebar-item bg-white rounded box-shadow overflow-hidden p-3 mb-4">
                            <h4>Mengapa memesan dengan kami?</h4>
                            <div class="sidebar-module-inner">
                                <ul class="featured-list-sm">
                                    <li class="border-b pb-2 mb-2">
                                        <h6 class="mb-0">Tanpa Biaya Pemesanan</h6>
                                        Kami tidak membebankan biaya tambahan kepada Anda untuk memesan kamar hotel bersama kami
                                    </li>
                                    <li class="border-b pb-2 mb-2">
                                        <h6 class="mb-0">Tidak Ada Pembatalan yang Dilihat</h6>
                                        Kami tidak membebankan biaya pembatalan atau modifikasi kepada Anda jika rencana berubah
                                    </li>
                                    <li class="border-b pb-2 mb-2">
                                        <h6 class="mb-0">Konfirmasi Instan</h6>
                                        Konfirmasi pemesanan instan baik pemesanan online atau melalui telepon
                                    </li>
                                    <li>
                                        <h6 class="mb-0">Pemesanan Fleksibel</h6>
                                        Anda dapat memesan hingga satu tahun penuh sebelumnya atau hingga saat Anda menginap
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".rating input").forEach(function(radio) {
            radio.addEventListener("change", function() {
                let ratingContainer = this.closest(".rating");
                let ticketId = ratingContainer.dataset.ticketId;
                let cartId = ratingContainer.dataset.cartId;
                let ratingValue = this.value;

                fetch("{{ route('rating.store') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({
                            ticket_id: ticketId,
                            cart_id: cartId,
                            rating: ratingValue
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: "Berhasil!",
                                text: "Rating berhasil dikirim!",
                                icon: "success",
                                confirmButtonText: "OK"
                            });
                        } else {
                            Swal.fire({
                                title: "Gagal!",
                                text: "Gagal mengirim rating!",
                                icon: "error",
                                confirmButtonText: "Coba Lagi"
                            });
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        Swal.fire({
                            title: "Oops!",
                            text: "Terjadi kesalahan. Silakan coba lagi!",
                            icon: "error",
                            confirmButtonText: "OK"
                        });
                    });
            });
        });
    });
</script>
@endsection