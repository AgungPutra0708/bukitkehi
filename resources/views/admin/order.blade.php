@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4>Pesanan Tiket</h4>
                            <button type="button" class="btn btn-primary sendIncomeOnline">Kirim Penghasilan Bulanan</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                                    type="button" role="tab" aria-controls="home" aria-selected="true">Pembelian
                                    Masuk</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                                    type="button" role="tab" aria-controls="profile" aria-selected="false">Riwayat
                                    Pembelian</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected"
                                    type="button" role="tab" aria-controls="rejected" aria-selected="false">Pembelian
                                    Ditolak</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>Email</th>
                                                <th>Jumlah</th>
                                                <th>Total</th>
                                                <th>Bukti Pembayaran</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders_pending as $order)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $order->user->name }}</td>
                                                    <td>{{ $order->user->email }}</td>
                                                    <td>{{ $order->quantity }}</td>
                                                    <td>Rp. {{ number_format($order->total_price) }}</td>
                                                    <td>
                                                        <a href="{{ asset('storage/payment_proof/' . $order->payment_proof) }}"
                                                            target="_blank">
                                                            <img src="{{ asset('storage/payment_proof/' . $order->payment_proof) }}"
                                                                alt=""
                                                                style="object-fit: cover; max-width: 150px; height: 100px;">
                                                        </a>
                                                    </td>
                                                    <td>
                                                        @if ($order->status == 'pending')
                                                            <button type="button" class="btn btn-warning"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#exampleModalLive{{ $order->id }}">
                                                                Tertunda
                                                            </button>
                                                        @elseif($order->status == 'accepted')
                                                            <button type="button" class="btn btn-primary"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#exampleModalLive{{ $order->id }}">
                                                                Disetujui
                                                            </button>
                                                        @elseif($order->status == 'rejected')
                                                            <button type="button" class="btn btn-danger"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#exampleModalLive{{ $order->id }}">
                                                                Ditolak
                                                            </button>
                                                        @endif
                                                        <div class="modal fade" id="exampleModalLive{{ $order->id }}"
                                                            tabindex="-1" aria-labelledby="exampleModalLiveLabel"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLiveLabel">
                                                                            Modal
                                                                            Judul
                                                                        </h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form action="{{ route('admin.order.update') }}"
                                                                            method="POST">
                                                                            @csrf

                                                                            <div class="form-group">
                                                                                <label for="status">Status</label>
                                                                                <input type="hidden" name="id"
                                                                                    value="{{ $order->id }}">
                                                                                <select name="status" id="status"
                                                                                    class="form-control">
                                                                                    <option value="pending"
                                                                                        {{ $order->status == 'pending' ? 'selected' : '' }}>
                                                                                        Tertunda</option>
                                                                                    <option value="accepted"
                                                                                        {{ $order->status == 'accepted' ? 'selected' : '' }}>
                                                                                        Disetujui</option>
                                                                                    <option value="rejected"
                                                                                        {{ $order->status == 'rejected' ? 'selected' : '' }}>
                                                                                        Ditolak</option>
                                                                                </select>
                                                                            </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Tutup</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Simpan
                                                                            Perubahan</button>
                                                                        </form>
                                                                    </div>
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
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>Email</th>
                                                <th>Jumlah</th>
                                                <th>Total</th>
                                                <th>Bukti Pembayaran</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders_success as $order)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $order->user->name }}</td>
                                                    <td>{{ $order->user->email }}</td>
                                                    <td>{{ $order->quantity }}</td>
                                                    <td>Rp. {{ number_format($order->total_price) }}</td>
                                                    <td>
                                                        <a href="{{ asset('storage/payment_proof/' . $order->payment_proof) }}"
                                                            target="_blank">
                                                            <img src="{{ asset('storage/payment_proof/' . $order->payment_proof) }}"
                                                                alt=""
                                                                style="object-fit: cover; max-width: 150px; height: 100px;">
                                                        </a>
                                                    </td>
                                                    <td>
                                                        @if ($order->status == 'pending')
                                                            <button type="button" class="btn btn-warning"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#exampleModalLive{{ $order->id }}">
                                                                Tertunda
                                                            </button>
                                                        @elseif($order->status == 'accepted')
                                                            <button type="button" class="btn btn-primary"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#exampleModalLive{{ $order->id }}">
                                                                Disetujui
                                                            </button>
                                                        @elseif($order->status == 'rejected')
                                                            <button type="button" class="btn btn-danger"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#exampleModalLive{{ $order->id }}">
                                                                Ditolak
                                                            </button>
                                                        @endif
                                                        <div class="modal fade" id="exampleModalLive{{ $order->id }}"
                                                            tabindex="-1" aria-labelledby="exampleModalLiveLabel"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title"
                                                                            id="exampleModalLiveLabel">Modal
                                                                            Judul
                                                                        </h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form action="{{ route('admin.order.update') }}"
                                                                            method="POST">
                                                                            @csrf

                                                                            <div class="form-group">
                                                                                <label for="status">Status</label>
                                                                                <input type="hidden" name="id"
                                                                                    value="{{ $order->id }}">
                                                                                <select name="status" id="status"
                                                                                    class="form-control">
                                                                                    <option value="pending"
                                                                                        {{ $order->status == 'pending' ? 'selected' : '' }}>
                                                                                        Tertunda</option>
                                                                                    <option value="accepted"
                                                                                        {{ $order->status == 'accepted' ? 'selected' : '' }}>
                                                                                        Disetujui</option>
                                                                                    <option value="rejected"
                                                                                        {{ $order->status == 'rejected' ? 'selected' : '' }}>
                                                                                        Ditolak</option>
                                                                                </select>
                                                                            </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Tutup</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Simpan
                                                                            Perubahan</button>
                                                                        </form>
                                                                    </div>
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
                            <div class="tab-pane fade" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>Email</th>
                                                <th>Jumlah</th>
                                                <th>Total</th>
                                                <th>Bukti Pembayaran</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders_rejected as $order)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $order->user->name }}</td>
                                                    <td>{{ $order->user->email }}</td>
                                                    <td>{{ $order->quantity }}</td>
                                                    <td>Rp. {{ number_format($order->total_price) }}</td>
                                                    <td>
                                                        <a href="{{ asset('storage/payment_proof/' . $order->payment_proof) }}"
                                                            target="_blank">
                                                            <img src="{{ asset('storage/payment_proof/' . $order->payment_proof) }}"
                                                                alt=""
                                                                style="object-fit: cover; max-width: 150px; height: 100px;">
                                                        </a>
                                                    </td>
                                                    <td>
                                                        @if ($order->status == 'pending')
                                                            <button type="button" class="btn btn-warning"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#exampleModalLive{{ $order->id }}">
                                                                Tertunda
                                                            </button>
                                                        @elseif($order->status == 'accepted')
                                                            <button type="button" class="btn btn-primary"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#exampleModalLive{{ $order->id }}">
                                                                Disetujui
                                                            </button>
                                                        @elseif($order->status == 'rejected')
                                                            <button type="button" class="btn btn-danger"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#exampleModalLive{{ $order->id }}">
                                                                Ditolak
                                                            </button>
                                                        @endif
                                                        <div class="modal fade" id="exampleModalLive{{ $order->id }}"
                                                            tabindex="-1" aria-labelledby="exampleModalLiveLabel"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title"
                                                                            id="exampleModalLiveLabel">Modal
                                                                            Judul
                                                                        </h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form action="{{ route('admin.order.update') }}"
                                                                            method="POST">
                                                                            @csrf

                                                                            <div class="form-group">
                                                                                <label for="status">Status</label>
                                                                                <input type="hidden" name="id"
                                                                                    value="{{ $order->id }}">
                                                                                <select name="status" id="status"
                                                                                    class="form-control">
                                                                                    <option value="pending"
                                                                                        {{ $order->status == 'pending' ? 'selected' : '' }}>
                                                                                        Tertunda</option>
                                                                                    <option value="accepted"
                                                                                        {{ $order->status == 'accepted' ? 'selected' : '' }}>
                                                                                        Disetujui</option>
                                                                                    <option value="rejected"
                                                                                        {{ $order->status == 'rejected' ? 'selected' : '' }}>
                                                                                        Ditolak</option>
                                                                                </select>
                                                                            </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Tutup</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Simpan
                                                                            Perubahan</button>
                                                                        </form>
                                                                    </div>
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
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="incomeModal" tabindex="-1" aria-labelledby="incomeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="incomeModalLabel">Pilih Tahun dan Bulan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="incomeForm" action="{{ route('admin.order.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="year" class="form-label">Tahun</label>
                                <select class="form-select" id="year" name="year" required>
                                    <option value="" selected disabled>Pilih Tahun</option>
                                    @for ($i = date('Y'); $i >= date('Y') - 10; $i--)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="month" class="form-label">Bulan</label>
                                <select class="form-select" id="month" name="month" required>
                                    <option value="" selected disabled>Pilih Bulan</option>
                                    @foreach (['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $index => $month)
                                        <option value="{{ $index + 1 }}">{{ $month }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="totalIncome" class="form-label">Total Penghasilan</label>
                                <input type="text" name="totalIncome" id="totalIncome" class="form-control" readonly
                                    required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-send" disabled>Kirim Data</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.sendIncomeOnline').click(function() {
                console.log('clicked');

                $('#incomeModal').modal('show');
            });


            // Trigger AJAX only when both year and month are selected
            $('#year, #month').change(function() {
                let year = $('#year').val();
                let month = $('#month').val();

                // Check if both year and month are selected
                if (year && month) {
                    let url = "{{ route('admin.order.total') }}";

                    // Perform the AJAX request
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            year: year,
                            month: month,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            // Populate the total income input field
                            $('#totalIncome').val(data.total);
                            $('.btn-send').prop('disabled', false);
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', error);
                        }
                    });
                }
            });
        });
    </script>
@endsection
