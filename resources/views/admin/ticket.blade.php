@extends('layouts.app')

@section('active_ticket', 'active-page')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3>Tiket</h3>
                <a href="{{ route('admin.ticket.create') }}" class="btn btn-primary">Tambah</a>
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tickets as $ticket)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><img src="{{ asset('storage/ticket/' . $ticket->photo) }}" alt="" style="width: 100px; height: 100px;"></td>
                                    <td>{{ $ticket->name }}</td>
                                    <td>{{ 'Rp. ' . number_format($ticket->price, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($ticket->status == 'draft')
                                            <span class="badge badge-danger">Draf</span>
                                        @else
                                            <span class="badge badge-success">Terbit</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.ticket.edit', $ticket->id) }}" class="btn btn-warning">Ubah</a>
                                        <a href="{{ route('admin.ticket.destroy', $ticket->id) }}" class="btn btn-danger">Hapus</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection