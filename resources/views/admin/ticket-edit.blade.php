@extends('layouts.app')

@section('active_ticket', 'active-page')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3>Ubah Ticket</h3>
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('admin.ticket.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="">Foto Ticket</label>
                                <input type="hidden" name="id" value="{{ $ticket->id }}">
                                <input type="file" class="form-control" name="photo" accept="image/*"
                                    value="{{ $ticket->photo }}">
                            </div>
                            <div class="form-group">
                                <label for="">Nama</label>
                                <input type="text" class="form-control" name="name" required
                                    value="{{ $ticket->name }}">
                            </div>
                            <div class="form-group">
                                <label for="">Harga</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">RP</span>
                                    </div>
                                    <input type="text" class="form-control" name="price" id="price" required
                                        oninput="formatRupiah(this)" value="{{ $ticket->price }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Tipe Tiket</label>
                                <select name="type_ticket" id="" class="form-control">
                                    <option value="0" {{ $ticket->type == '0' ? 'selected' : '' }}>Tiket Terusan
                                    </option>
                                    <option value="1" {{ $ticket->type == '1' ? 'selected' : '' }}>Tiket Satuan
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Status</label>
                                <select name="status" id="" class="form-control">
                                    <option value="draft" {{ $ticket->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="publish" {{ $ticket->status == 'publish' ? 'selected' : '' }}>Publish
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Deskripsi</label>
                                <textarea name="description" id="editor" cols="30" rows="10" class="form-control" required
                                    style="overflow:scroll; max-height:300px">{{ $ticket->description }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Kirim</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
