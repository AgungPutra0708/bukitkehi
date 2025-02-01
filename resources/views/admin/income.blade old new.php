@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- Table Card -->
                <div class="card">
                    <div class="card-header">
                        <h4>Income</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="incomeTable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tahun</th>
                                        <th>Bulan</th>
                                        <th>Nama</th>
                                        <th>Amount</th>
                                        <th>Tipe</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Edit Form Card -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5>Income</h5>
                    </div>
                    <div class="card-body">
                        <form id="incomeForm">
                            @csrf
                            <input type="hidden" name="id" id="incomeId">
                            <div class="form-group mb-3">
                                <label for="editType">Tipe</label>
                                <select name="type" id="editType" class="form-control" required>
                                    <option value="">Pilih Tipe</option>
                                    <option value="0">Tiket Terusan</option>
                                    <option value="1">Tiket Satuan</option>
                                    <option value="2">Fasilitas</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="editRelatedData">Data Tiket/Fasilitas</label>
                                <select name="related_data" id="editRelatedData" class="form-control" required>
                                    <option value="">Pilih Data</option>
                                    <!-- Data akan dimuat secara dinamis -->
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="editBulan">Bulan</label>
                                <select name="bulan" id="editBulan" class="form-control" required>
                                    @foreach (range(1, 12) as $month)
                                        <option value="{{ $month }}">{{ date('F', mktime(0, 0, 0, $month, 10)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="editTahun">Tahun</label>
                                <input type="number" class="form-control" id="editTahun" name="tahun" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="editAmount">Amount</label>
                                <input type="text" class="form-control" id="editAmount" name="amount" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Load data via AJAX
            loadIncomeTable();

            function loadIncomeTable() {
                $('#incomeTable tbody').html('');
                $.get('{{ route('admin.income.data') }}', function(data) {
                    $.each(data, function(index, income) {
                        $('#incomeTable tbody').append(`
                            <tr>
                                <td>${income.tahun}</td>
                                <td>${income.bulan}</td>
                                <td>${income.name}</td>
                                <td>Rp. ${income.amount}</td>
                                <td>${income.type}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm editBtn" data-id="${income.id}">Edit</button>
                                    <button class="btn btn-danger btn-sm deleteBtn" data-id="${income.id}">Hapus</button>
                                </td>
                            </tr>
                        `);
                    });
                });
            }

            // Handle form submit for both store and update
            $('#incomeForm').submit(function(e) {
                e.preventDefault();

                let formData = $(this).serialize();
                let url = $('#incomeId').val() ?
                    '{{ route('admin.income.update') }}' :
                    '{{ route('admin.income.store') }}'; // Switch URL based on action

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    success: function() {
                        alert('Data berhasil disimpan!');
                        $('#incomeForm')[0].reset();
                        $('#incomeId').val('');
                        loadIncomeTable();
                    },
                    error: function() {
                        alert('Terjadi kesalahan!');
                    },
                });
            });

            // Handle edit button
            $(document).on('click', '.editBtn', function() {
                let id = $(this).data('id');
                $.get(`{{ url('admin/income') }}/${id}`, function(data) {
                    // Set field lainnya
                    $('#incomeId').val(data.id);
                    $('#editBulan').val(data.bulan);
                    $('#editTahun').val(data.tahun);
                    $('#editAmount').val(data.amount);
                    $('#editType').val(data.type);

                    // Muat data untuk dropdown terkait berdasarkan tipe
                    let url = '';
                    if (data.type === 0) {
                        url = '{{ route('admin.tiket-terusan.data') }}';
                    } else if (data.type === 1) {
                        url = '{{ route('admin.tiket-satuan.data') }}';
                    } else if (data.type === 2) {
                        url = '{{ route('admin.fasilitas.data') }}';
                    }

                    if (url) {
                        console.log(data.type);
                        console.log(data.related_id);

                        // Ambil data dari server
                        $.get(url, function(relatedData) {
                            $('#editRelatedData').html(
                                '<option value="">Pilih Data</option>');
                            $.each(relatedData, function(index, item) {
                                $('#editRelatedData').append(
                                    `<option value="${item.id}">${item.name}</option>`
                                );
                            });

                            // Set nilai dropdown setelah opsi selesai dimuat
                            $('#editRelatedData').val(data.related_id);
                        });
                    }
                });
            });

            // Load data untuk dropdown kedua berdasarkan tipe
            $('#editType').change(function() {
                let selectedType = $(this).val();
                let url = '';

                // Tentukan URL berdasarkan tipe yang dipilih
                if (selectedType === '0') {
                    url = '{{ route('admin.tiket-terusan.data') }}';
                } else if (selectedType === '1') {
                    url = '{{ route('admin.tiket-satuan.data') }}';
                } else if (selectedType === '2') {
                    url = '{{ route('admin.fasilitas.data') }}';
                }

                if (url) {
                    // Ambil data dari server
                    $.get(url, function(data) {
                        // Kosongkan dropdown sebelum diisi
                        $('#editRelatedData').html('<option value="">Pilih Data</option>');

                        // Tambahkan data ke dropdown
                        $.each(data, function(index, item) {
                            $('#editRelatedData').append(
                                `<option value="${item.id}">${item.name}</option>`
                            );
                        });
                    });
                } else {
                    // Kosongkan dropdown jika tipe tidak valid
                    $('#editRelatedData').html('<option value="">Pilih Data</option>');
                }
            });

            // Handle delete button
            $(document).on('click', '.deleteBtn', function() {
                if (confirm('Yakin ingin menghapus data ini?')) {
                    let id = $(this).data('id');
                    $.ajax({
                        url: `{{ url('admin/income') }}/${id}`,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function() {
                            alert('Data berhasil dihapus!');
                            loadIncomeTable();
                        },
                        error: function() {
                            alert('Terjadi kesalahan!');
                        },
                    });
                }
            });
        });
    </script>
@endsection
