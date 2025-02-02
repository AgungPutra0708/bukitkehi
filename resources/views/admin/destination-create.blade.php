@extends('layouts.app')

@section('active_destination', 'active-page')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3>Tambah Wahana</h3>
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('admin.destination.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="">Foto Wahana</label>
                                <input type="file" class="form-control" name="image" required accept="image/*">
                            </div>
                            <div class="form-group">
                                <label for="">Nama</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="">Deskripsi Singkat</label>
                                <textarea name="short_description" id="" cols="30" rows="3" class="form-control" required></textarea>
                            </div>
                            <div class="form-group" id="description">
                                <label for="">Deskripsi</label>
                                <textarea name="content" id="editor" cols="30" rows="10" class="form-control" required
                                    style="overflow:scroll; max-height:300px"></textarea>
                            </div>
                            <div class="form-group mb-2">
                                <label for="">Tautan Video</label>
                                <input type="text" class="form-control" name="video">
                                {{-- <input type="file" class="form-control" name="video" accept="video/*"> --}}
                            </div>
                            <button type="submit" class="btn btn-primary">Kirim</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
