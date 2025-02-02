@extends('layouts.app')

@section('active_news', 'active-page')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3>Berita</h3>
                <a href="{{ route('admin.news.create') }}" class="btn btn-primary">Tambah</a>
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Penulis</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($news as $new)
                                <tr class="align-middle" style="">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $new->title }}</td>
                                    <td>{{ $new->author }}</td>
                                    <td>{{ $new->status }}</td>
                                    <td>
                                        <a href="{{ route('admin.news.edit', $new->id) }}" class="btn btn-warning">Ubah</a>
                                        <a href="{{ route('admin.news.destroy', $new->id) }}" class="btn btn-danger">Hapus</a>
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