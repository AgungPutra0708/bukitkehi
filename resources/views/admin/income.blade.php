@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- Table Card -->
                <div class="card">
                    <div class="card-header">
                        <h4>Penghasilan</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="incomeTable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tahun</th>
                                        <th>Bulan</th>
                                        <th>Jumlah</th>
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

                <!-- Dynamic Form Card -->
                <div class="card">
                    <div class="card-header">
                        <h4>Masukan Penghasilan</h4>
                    </div>
                    <div class="card-body">
                        <form id="dynamicIncomeForm">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="tahun">Tahun</label>
                                <input type="number" class="form-control" id="tahun" name="tahun" required>
                                <input type="hidden" class="form-control" id="id" name="id">
                            </div>
                            <div class="form-group mb-3">
                                <label for="bulan">Bulan</label>
                                <select name="bulan" id="bulan" class="form-control" required>
                                    @foreach (range(1, 12) as $month)
                                        <option value="{{ $month }}">{{ date('F', mktime(0, 0, 0, $month, 10)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <table class="table table-bordered" id="dynamicTable">
                                <thead>
                                    <tr>
                                        <th style="width: 25%">Tipe Tiket</th>
                                        <th style="width: 25%">Tiket/Fasilitas</th>
                                        <th style="width: 20%">Harga</th>
                                        <th style="width: 5%">Jumlah</th>
                                        <th style="width: 25%">Total</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input type="hidden" class="form-control" id="id_detail" name="id_detail">
                                            <select name="type[]" class="form-control type" required>
                                                <option value="">Pilih Tipe</option>
                                                <option value="0">Tiket Terusan</option>
                                                <option value="1">Tiket Satuan</option>
                                                <option value="2">Fasilitas</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="related_data[]" class="form-control related_data" required>
                                                <option value="">Pilih Data</option>
                                            </select>
                                        </td>
                                        <td><input type="text" name="harga_satuan[]"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                                class="form-control harga_satuan" required></td>
                                        <td><input type="text" name="jumlah_terjual[]"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                                class="form-control jumlah_terjual" required></td>
                                        <td><input type="text" name="total[]"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                                class="form-control total" readonly></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <button type="button"
                                                    class="btn btn-sm btn-success me-2 addRow text-center">
                                                    <i class="material-icons">Tambah</i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger removeRow text-center">
                                                    <i class="material-icons">Hapus</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-primary">Kirim</button>
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
            // Load data into the table via AJAX
            function loadTableData() {
                $.ajax({
                    url: '{{ route('admin.income.data') }}',
                    method: 'GET',
                    success: function(data) {
                        var tbody = $('#incomeTable tbody');
                        tbody.empty();
                        $.each(data, function(index, item) {
                            tbody.append(`
                    <tr>
                        <td>${item.tahun}</td>
                        <td>${item.bulan}</td>
                        <td>${item.amount}</td>
                        <td>
                            <button class="btn btn-sm btn-warning editRow" data-id="${item.id}">Edit</button>
                            <button class="btn btn-sm btn-danger deleteRow" data-id="${item.id}">Hapus</button>
                        </td>
                    </tr>
                `);
                        });
                    }
                });
            }
            // Load the data into the form when the Edit button is clicked
            $(document).on('click', '.editRow', function() {
                var incomeId = $(this).data('id');

                $.ajax({
                    url: '{{ url('admin/income') }}/' + incomeId, // Make sure this URL is correct
                    method: 'GET',
                    success: function(data) {
                        // Populate form with income data
                        $('#id').val(data.id);
                        $('#tahun').val(data.tahun);
                        $('#bulan').val(data.bulan);

                        // Clear existing dynamic rows
                        $('#dynamicTable tbody').empty();

                        // Add rows for income details
                        $.each(data.income_detail, function(index, detail) {
                            var newRow = `
                <tr>
                    <td>
                        <input type="hidden" class="form-control" id="id_detail" name="id_detail" value="${detail.id}">
                        <select name="type[]" class="form-control type" required data-ticket="${detail.type == 2 ? detail.facilities_id : detail.ticket_id}">
                            <option value="0" ${detail.type == 0 ? 'selected' : ''}>Tiket Terusan</option>
                            <option value="1" ${detail.type == 1 ? 'selected' : ''}>Tiket Satuan</option>
                            <option value="2" ${detail.type == 2 ? 'selected' : ''}>Fasilitas</option>
                        </select>
                    </td>
                    <td>
                        <select name="related_data[]" class="form-control related_data" required>
                            <option value="">Pilih Data</option>
                        </select>
                    </td>
                    <td><input type="text" name="harga_satuan[]" value="${detail.harga_satuan}" class="form-control harga_satuan" required></td>
                    <td><input type="text" name="jumlah_terjual[]" value="${detail.jumlah}" class="form-control jumlah_terjual" required></td>
                    <td><input type="text" name="total[]" value="${detail.amount}" class="form-control total" readonly></td>
                    <td>
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-sm btn-success me-2 addRow text-center">
                                <i class="material-icons">add</i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger removeRow text-center">
                                <i class="material-icons">delete</i>
                            </button>
                        </div>
                    </td>
                </tr>`;

                            // Append the row to the table
                            $('#dynamicTable tbody').append(newRow);

                            // Trigger type change to populate related_data dropdown
                            var lastRow = $('#dynamicTable tbody tr').last();
                            lastRow.find('.type').trigger('change', [detail
                                .related_data
                            ]);
                        });
                    }
                });
            });

            // Add new row
            $(document).on('click', '.addRow', function() {
                let newRow = `
                <tr>
                    <td>
                        <select name="type[]" class="form-control type" required>
                            <option value="">Pilih Tipe</option>
                            <option value="0">Tiket Terusan</option>
                            <option value="1">Tiket Satuan</option>
                            <option value="2">Fasilitas</option>
                        </select>
                    </td>
                    <td>
                        <select name="related_data[]" class="form-control related_data" required>
                            <option value="">Pilih Data</option>
                        </select>
                    </td>
                    <td><input type="text" name="harga_satuan[]" oninput="this.value = this.value.replace(/[^0-9]/g, '');" class="form-control harga_satuan" required></td>
                    <td><input type="text" name="jumlah_terjual[]" oninput="this.value = this.value.replace(/[^0-9]/g, '');" class="form-control jumlah_terjual" required></td>
                    <td><input type="text" name="total[]" oninput="this.value = this.value.replace(/[^0-9]/g, '');" class="form-control total" readonly></td>
                    <td>
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-sm btn-success me-2 addRow text-center">
                                <i class="material-icons">add</i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger removeRow text-center">
                                <i class="material-icons">delete</i>
                            </button>
                        </div>
                    </td>
                </tr>`;
                $('#dynamicTable tbody').append(newRow);
            });

            // Remove row
            $(document).on('click', '.removeRow', function() {
                $(this).closest('tr').remove();
            });

            // Calculate total dynamically
            $(document).on('input', '.harga_satuan, .jumlah_terjual', function() {
                let row = $(this).closest('tr');
                let hargaSatuan = parseFloat(row.find('.harga_satuan').val()) || 0;
                let jumlahTerjual = parseFloat(row.find('.jumlah_terjual').val()) || 0;
                let total = hargaSatuan * jumlahTerjual;
                row.find('.total').val(total);
            });

            // Load related data dynamically and update price
            $(document).on('change', '.type', function() {
                let selectedType = $(this).val();
                var ticket = $(this).data('ticket');
                let row = $(this).closest('tr');
                let relatedDataDropdown = row.find('.related_data');
                let hargaSatuanField = row.find('.harga_satuan');
                let url = '';

                if (selectedType === '0') {
                    url = '{{ route('admin.tiket-terusan.data') }}';
                } else if (selectedType === '1') {
                    url = '{{ route('admin.tiket-satuan.data') }}';
                } else if (selectedType === '2') {
                    url = '{{ route('admin.fasilitas.data') }}';
                }

                if (url) {
                    $.get(url, function(data) {
                        relatedDataDropdown.html('<option value="">Pilih Data</option>');
                        $.each(data, function(index, item) {
                            relatedDataDropdown.append(
                                `<option value="${item.id}" data-price="${item.price}">${item.name}</option>`
                            );
                        });
                        // If 'ticket' value exists and matches, set it as selected
                        if (ticket) {
                            relatedDataDropdown.val(ticket).change();
                        }
                    });

                } else {
                    relatedDataDropdown.html('<option value="">Pilih Data</option>');
                }
            });

            // Update price when related data is selected
            $(document).on('change', '.related_data', function() {
                let selectedOption = $(this).find(':selected');
                let price = parseInt(selectedOption.data('price'));
                let row = $(this).closest('tr');
                row.find('.harga_satuan').val(price).trigger('input');
            });

            // Form submission
            $('#dynamicIncomeForm').on('submit', function(e) {
                e.preventDefault();

                let totalSum = 0;
                $('.total').each(function() {
                    totalSum += parseFloat($(this).val()) || 0;
                });

                let formData = $(this).serialize() + '&total_sum=' + totalSum;

                let id = $('#id').val();
                let url = '';
                if (id) {
                    url = '{{ route('admin.income.update') }}';
                } else {
                    url = '{{ route('admin.income.store') }}';
                }

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        alert(response.message);
                        loadTableData();
                        $('#dynamicIncomeForm')[0].reset(); // Reset the form
                        $('#dynamicTable tbody').empty(); // Clear the table rows
                    },
                    error: function(xhr) {
                        alert('An error occurred: ' + xhr.responseText);
                    }
                });
            });

            // Initial table data load
            loadTableData();
        });
    </script>
@endsection
