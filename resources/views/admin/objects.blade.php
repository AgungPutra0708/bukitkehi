@extends('layouts.app')

@section('active_objects', 'active-page')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3>Objek Pendukung</h3>
                <a href="{{ route('admin.objects.create') }}" class="btn btn-primary">Tambah</a>
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Tipe</th>
                                <th>Garis Bujur</th>
                                <th>Lintang</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($objectData as $items)
                                <tr class="align-middle" style="">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $items->name }}</td>
                                    <td>
                                        @if ($items->tipe == '1')
                                            Hotel
                                        @elseif ($items->tipe == '2')
                                            Restoran/Wisata Kuliner
                                        @else
                                            Tempat Wisata Lainnya
                                        @endif
                                    </td>
                                    <td>{{ $items->longitude }}</td>
                                    <td>{{ $items->latitude }}</td>
                                    <td>
                                        <a href="{{ route('admin.objects.edit', $items->id) }}"
                                            class="btn btn-primary">Ubah</a>
                                        <a href="{{ route('admin.objects.destroy', $items->id) }}"
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
