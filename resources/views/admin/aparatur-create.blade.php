@extends('layouts.app')

@section('active_aparatur', 'active-page')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3>Tambah Aparatur</h3>
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('admin.aparatur.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="">Foto Aparatur</label>
                                <input type="file" class="form-control" name="image" required accept="image/*">
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Nama</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Posisi</label>
                                <input type="text" class="form-control" name="position" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Kirim</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
