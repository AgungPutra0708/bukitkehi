@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Informasi Pembayaran</h4>
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            Tambah Informasi Pembayaran
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Tambah Informasi Pembayaran</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('admin.payment.information.store') }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label for="bank_name">Nama Bank</label>
                                                <input type="text" class="form-control" id="bank_name" name="bank_name"
                                                    required>
                                            </div>
                                            <div class="form-group">
                                                <label for="bank_account_number">Nomor Akun Bank</label>
                                                <input type="text" class="form-control" id="bank_account_number"
                                                    name="bank_account_number" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="bank_account_name">Nama Akun Bank</label>
                                                <input type="text" class="form-control" id="bank_account_name"
                                                    name="bank_account_name" required>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Bank</th>
                                        <th>Nomor Akun Bank</th>
                                        <th>Nama Akun Bank</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($paymentInfo as $paymentInfo)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $paymentInfo->bank_name }}</td>
                                            <td>{{ $paymentInfo->bank_account_number }}</td>
                                            <td>{{ $paymentInfo->bank_account_name }}</td>
                                            <td>
                                                <!-- Button trigger modal -->
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#editPaymentInformationModal{{ $paymentInfo->id }}">
                                                    Ubah Informasi Pembayaran
                                                </button>

                                                <!-- Modal -->
                                                <div class="modal fade"
                                                    id="editPaymentInformationModal{{ $paymentInfo->id }}" tabindex="-1"
                                                    aria-labelledby="editPaymentInformationModalLabel{{ $paymentInfo->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="editPaymentInformationModalLabel{{ $paymentInfo->id }}">
                                                                    Ubah Informasi Pembayaran
                                                                </h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form
                                                                    action="{{ route('admin.payment.information.update') }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <label for="bank_name">Nama Bank</label>
                                                                        <input type="text" class="form-control"
                                                                            id="bank_name" name="bank_name"
                                                                            value="{{ $paymentInfo->bank_name }}" required>
                                                                        <input type="hidden" name="id"
                                                                            value="{{ $paymentInfo->id }}">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="bank_account_number">Nomor Akun Bank</label>
                                                                        <input type="text" class="form-control"
                                                                            id="bank_account_number"
                                                                            name="bank_account_number"
                                                                            value="{{ $paymentInfo->bank_account_number }}"
                                                                            required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="bank_account_name">Nama Akun Bank</label>
                                                                        <input type="text" class="form-control"
                                                                            id="bank_account_name" name="bank_account_name"
                                                                            value="{{ $paymentInfo->bank_account_name }}"
                                                                            required>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Tutup</button>
                                                                        <button type="button" class="btn btn-primary">Simpan Perubahan</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <a href="{{ route('admin.payment.information.destroy', $paymentInfo->id) }}"
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
        </div>
    @endsection
