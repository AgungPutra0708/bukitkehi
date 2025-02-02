@extends('layouts.app')

@section('active_destination', 'active-page')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3>Wahana</h3>
                <a href="{{ route('admin.destination.create') }}" class="btn btn-primary">Tambah</a>
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($destinations as $destination)
                                <tr class="align-middle" style="">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <img src="{{ asset('storage/destination/' . $destination->image) }}" alt=""
                                            style="object-fit: cover; max-width: 150px; height: 100px;">
                                    </td>
                                    <td>{{ $destination->name }}</td>
                                    <td>
                                        <a href="{{ route('admin.destination.edit', $destination->id) }}"
                                            class="btn btn-primary">Ubah</a>
                                        <a href="{{ route('admin.destination.destroy', $destination->id) }}"
                                            class="btn btn-danger">Hapus</a>
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
