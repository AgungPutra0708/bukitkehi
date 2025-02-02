@extends('layouts.app')

@section('active_facility', 'active-page')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3>Fasilitas</h3>
                <a href="{{ route('admin.facility.create') }}" class="btn btn-primary">Tambah</a>
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($facilities as $facility)
                                <tr class="align-middle" style="">
                                    <td>{{ $loop->iteration }}</td>
                                    <td><img src="{{ asset('storage/facility/' . $facility->image) }}" alt="" width="100"></td>
                                    <td>{{ $facility->name }}</td>
                                    <td>{{ $facility->description }}</td>
                                    <td>
                                        <a href="{{ route('admin.facility.edit', $facility->id) }}" class="btn btn-warning">Ubah</a>
                                        <a href="{{ route('admin.facility.destroy', $facility->id) }}" class="btn btn-danger">Hapus</a>
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