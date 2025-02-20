@extends('layouts.app')

@section('active_objects', 'active-page')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3>{{ isset($objectData) ? 'Ubah' : 'Tambah' }} Objek Pendukung</h3>
                <div class="row">
                    <div class="col-md-12">
                        <form
                            action="{{ isset($objectData) ? route('admin.objects.update') : route('admin.objects.store') }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="">Foto Objek</label>
                                @if(isset($objectData) && $objectData->images->count() > 0)
                                    <div class="mb-3">
                                        @foreach($objectData->images as $image)
                                            <img src="{{ asset('storage/objek/' . $image->image) }}" alt="" style="object-fit: cover; max-width: 150px; height: 100px; margin-right: 10px;">
                                        @endforeach
                                    </div>
                                @endif
                                <input type="file" class="form-control" name="images[]" multiple accept="image/*">
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Nama*</label>
                                <input type="text" class="form-control" name="name"
                                    value="{{ isset($objectData) ? $objectData->name : '' }}" required>
                                <input type="hidden" class="form-control" name="id"
                                    {{ isset($objectData) ? 'required' : '' }}
                                    value="{{ isset($objectData) ? $objectData->id : '' }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Tipe*</label>
                                <select name="type" class="form-control" required>
                                    <option value="1"
                                        {{ isset($objectData) ? ($objectData->type == '1' ? 'selected' : '') : '' }}>Hotel
                                    </option>
                                    <option value="2"
                                        {{ isset($objectData) ? ($objectData->type == '2' ? 'selected' : '') : '' }}>
                                        Restoran/Wisata Kuliner</option>
                                    <option value="3"
                                        {{ isset($objectData) ? ($objectData->type == '3' ? 'selected' : '') : '' }}>Tempat
                                        Wisata Lainnya</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Lintang*</label>
                                <input type="text" class="form-control" name="latitude"
                                    value="{{ isset($objectData) ? $objectData->latitude : '' }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Garis Bujur*</label>
                                <input type="text" class="form-control" name="longitude"
                                    value="{{ isset($objectData) ? $objectData->longitude : '' }}" required>
                            </div>
                            <div class="form-group">
                                <label for="">Alamat*</label>
                                <textarea name="address" id="" cols="30" rows="3" class="form-control" required>{{ isset($objectData) ? $objectData->address : '' }}</textarea>
                            </div>
                            <div class="form-group" id="description">
                                <label for="">Deskripsi</label>
                                <textarea name="description" id="editor" cols="30" rows="10" class="form-control"
                                    style="overflow:scroll; max-height:300px">{{ isset($objectData) ? $objectData->description : '' }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Kirim</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
