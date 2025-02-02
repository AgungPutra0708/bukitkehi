@extends('layouts.app')

@section('active_news', 'active-page')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3>Ubah Berita & Artikel</h3>
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('admin.news.update', $news->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="">Foto Berita/Artikel</label>
                                <input type="file" class="form-control" name="image" accept="image/*">
                                <img src="{{ asset('storage/news/' . $news->image) }}" alt="" width="100">
                            </div>
                            <div class="form-group">
                                <label for="">Judul</label>
                                <input type="text" class="form-control" name="title" required
                                    value="{{ $news->title }}">
                                <input type="hidden" name="id" value="{{ $news->id }}">
                            </div>
                            <div class="form-group">
                                <label for="">Penulis</label>
                                <input type="text" class="form-control" name="author" required
                                    value="{{ $news->author }}">
                            </div>
                            <div class="form-group">
                                <label for="">Status</label>
                                <select name="status" id="" class="form-control">
                                    <option value="draft" {{ $news->status == 'draft' ? 'selected' : '' }}>Draf</option>
                                    <option value="publish" {{ $news->status == 'publish' ? 'selected' : '' }}>Terbit
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Konten</label>
                                <textarea name="content" id="editor" cols="30" rows="10" class="form-control" required
                                    style="overflow:scroll; max-height:300px">{{ $news->content }}</textarea>
                            </div>
                            {{-- <div class="form-group">
                                <label for="">Video</label>
                                <input type="file" class="form-control" name="video" accept="video/*">
                                <video src="{{ asset('storage/news/' . $news->video) }}" alt="" width="100"></video>
                            </div> --}}
                            <div class="form-group">
                                <label for="">Video</label>
                                <input type="text" class="form-control" name="video" value="{{ $news->video }}">
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
