@extends('layouts.app')

@section('active_riwayat', 'active-page')

@section('style')
<style>
    .box {
        font-family: 'Poppins', sans-serif;
    }

    .rate {
        border-bottom-right-radius: 12px;
        border-bottom-left-radius: 12px;
    }

    /* Rating Display */
    .rating-display {
        display: flex;
        gap: 3px;
        font-size: 24px;
        color: gray;
    }

    .rating-display .star {
        color: lightgray; /* Warna default bintang kosong */
    }

    .rating-display .star.filled {
        color: gold; /* Warna bintang yang terisi */
    }

    .buttons {
        top: 36px;
        position: relative;
    }

    .rating-submit {
        border-radius: 8px;
        color: #fff;
        height: auto;
    }

    .rating-submit:hover {
        color: #fff;
    }
</style>
@endsection

@section('content')
    <div class="container">
        <div class="card mb-4">
            <div class="card-body">
                <h3>Riwayat Klik Tiket (5 Terakhir)</h3>
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Tiket</th>
                                <th>User Klik</th>
                                <th>Waktu Klik</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clicked as $click)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $click->ticket->name }}</td>
                                    <td>{{ $click->user->name }}</td>
                                    <td>{{ $click->created_at->format('d M Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h3>Riwayat Rating Tiket (5 Terakhir)</h3>
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Tiket</th>
                                <th>User</th>
                                <th>Rating</th>
                                <th>Waktu Rating</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($rating as $rate)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $rate->ticket ? $rate->ticket->name : 'Tiket Tidak Ditemukan' }}</td>
                                <td>{{ $rate->user ? $rate->user->name : 'User Tidak Ditemukan' }}</td>
                                <td>
                                    <div class="rating-display">
                                        @for ($i = 1; $i <= $rate->rating; $i++)
                                            <span class="star filled">★</span>
                                        @endfor
                                    </div>
                                </td>
                                <td>{{ $rate->created_at->format('d M Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
