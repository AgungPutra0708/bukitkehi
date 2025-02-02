@extends('layouts.app')

@section('active_news', 'active-page')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3>Tambah Berita & Artikel</h3>
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="">Foto Berita/Artikel</label>
                                <input type="file" class="form-control" name="image" required accept="image/*">
                            </div>
                            <div class="form-group">
                                <label for="">Judul</label>
                                <input type="text" class="form-control" name="title" required>
                            </div>
                            <div class="form-group">
                                <label for="">Penulis</label>
                                <input type="text" class="form-control" name="author" required>
                            </div>
                            <div class="form-group">
                                <label for="">Status</label>
                                <select name="status" id="" class="form-control">
                                    <option value="draft">Draf</option>
                                    <option value="publish">Terbit</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Konten</label>
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
