@extends('layouts.app')

@section('active_product', 'active-page')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3>Ubah Produk</h3>
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('admin.product.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="">Foto Produk</label>
                                <input type="file" class="form-control" name="image" required accept="image/*">
                                <input type="hidden" name="id" value="{{ $product->id }}">
                            </div>
                            <div class="form-group">
                                <label for="">Nama</label>
                                <input type="text" class="form-control" name="name" required value="{{ $product->name }}">
                            </div>
                            <div class="form-group">
                                <label for="">Harga</label>
                                <input type="text" class="form-control" name="price" required value="{{ $product->price }}">
                            </div>
                            <div class="form-group">
                                <label for="">Status</label>
                                <select name="status" id="" class="form-control">
                                    <option value="draft" {{ $product->status == 'draft' ? 'selected' : '' }}>Draf</option>
                                    <option value="publish" {{ $product->status == 'publish' ? 'selected' : '' }}>Terbit</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Deskripsi</label>
                                <textarea name="description" id="editor" cols="30" rows="10" class="form-control" required style="overflow:scroll; max-height:300px">{{ $product->description }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Kirim</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection