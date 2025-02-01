@extends('layouts.app')

@section('active_destination', 'active-page')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3>Create Gallery</h3>
                <div class="row">
                    <div class="col-md-6">
                        <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="">File Gambar</label>
                                <input type="file" class="form-control" name="file" accept="image/*">
                                <small class="form-text text-muted">Hanya file gambar yang diizinkan (jpeg, png, jpg, gif,
                                    svg).</small>
                                @error('file')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">Link Video</label>
                                <input type="text" class="form-control" name="video"
                                    placeholder="Masukkan URL video (contoh: YouTube)">
                                <small class="form-text text-muted">Hanya link video yang valid (contoh:
                                    https://youtube.com).</small>
                                @error('video')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
