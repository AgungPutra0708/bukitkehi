@extends('layouts.landing')

@section('style')
    <style>
        .box {
            font-family: 'Poppins', sans-serif;
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
                                            <img src="data:image/png;base64,{{ $qrcode }}" alt="QR Code" style="width: 200px; height: 100px;"/>
                                            @else
                                                <p>Kode QR tidak tersedia.</p>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="booking-border mb-4">
                                <h4 class="border-b pb-2 mb-2">Tiket {{ $order->ticket->name }}</h4>
                                
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
    <!-- top Destination ends -->

@endsection

