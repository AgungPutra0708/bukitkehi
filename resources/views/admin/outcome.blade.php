@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- Table Card -->
                <div class="card">
                    <div class="card-header">
                        <h4>Pengeluaran</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="outcomeTable" class="table table-bordered">
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
                        <h4>Masukan Pengeluaran</h4>
                    </div>
                    <div class="card-body">
                        <form id="dynamicOutcomeForm">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="tahun">Tahun</label>
                                <input type="number" class="form-control" id="tahun" name="tahun" required>
                                <input type="hidden" class="form-control" id="id" name="id" value=""
                                    autocomplete="off">
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
                                        <th>Nama Pengeluaran</th>
                                        <th>Total</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input type="hidden" class="form-control" id="id_detail" name="id_detail[]"
                                                value="" autocomplete="off">
                                            <input type="text" name="name[]" class="form-control name" required>
                                        </td>
                                        <td>
                                            <input type="text" name="amount[]"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                                class="form-control amount" required>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <button type="button"
                                                    class="btn btn-sm btn-success me-2 addRow text-center">
                                                    <i class="material-icons">add</i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger removeRow text-center">
                                                    <i class="material-icons">delete</i>
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
                    url: '{{ route('admin.outcome.data') }}',
                    method: 'GET',
                    success: function(data) {
                        var tbody = $('#outcomeTable tbody');
                        tbody.empty();
                        $.each(data, function(index, item) {
                            tbody.append(`
                    <tr>
                        <td>${item.tahun}</td>
                        <td>${item.bulan}</td>
                        <td>${item.total_amount}</td>
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
                var outcomeId = $(this).data('id');

                $.ajax({
                    url: '{{ url('admin/outcome') }}/' + outcomeId, // Make sure this URL is correct
                    method: 'GET',
                    success: function(data) {
                        // Populate form with outcome data
                        $('#id').val(data.id);
                        $('#tahun').val(data.tahun);
                        $('#bulan').val(data.bulan);

                        // Clear existing dynamic rows
                        $('#dynamicTable tbody').empty();

                        // Add rows for outcome details
                        $.each(data.outcome_detail, function(index, detail) {
                            var newRow = `
                            <tr>
                                <td>
                                    <input type="hidden" class="form-control" id="id_detail" name="id_detail[]" value="${detail.id}" autocomplete="off">
                                    <input type="text" name="name[]" value="${detail.name}" class="form-control name" required>
                                </td>
                                <td>
                                    <input type="text" name="amount[]" value="${detail.amount}" class="form-control amount" required>
                                </td>
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

            $(document).on("click", ".deleteRow", function() {
                let id = $(this).data("id"); // Ambil ID dari tombol delete
                let url = `/admin/outcome/${id}`; // URL endpoint untuk delete
                let btn = $(this); // Simpan tombol yang diklik

                if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                    $.ajax({
                        url: url,
                        type: "DELETE",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr(
                                "content") // Pastikan CSRF token dikirim
                        },
                        success: function(response) {
                            alert(response.message); // Tampilkan pesan sukses
                            btn.closest("tr").remove(); // Hapus baris dari tabel
                        },
                        error: function(xhr) {
                            alert("Terjadi kesalahan saat menghapus data.");
                        }
                    });
                }
            });

            // Add new row
            $(document).on('click', '.addRow', function() {
                let newRow = `
                <tr>
                    <td>
                        <input type="text" name="name[]" class="form-control name" required>
                    </td>
                    <td>
                        <input type="text" name="amount[]" oninput="this.value = this.value.replace(/[^0-9]/g, '');" class="form-control amount" required>
                    </td>
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

            // Form submission
            $('#dynamicOutcomeForm').on('submit', function(e) {
                e.preventDefault();

                let totalSum = 0;
                $('.amount').each(function() {
                    totalSum += parseFloat($(this).val()) || 0;
                });

                let formData = $(this).serialize() + '&total_sum=' + totalSum;

                let id = $('#id').val();
                let url = '';
                if (id) {
                    url = '{{ route('admin.outcome.update') }}';
                } else {
                    url = '{{ route('admin.outcome.store') }}';
                }

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        alert(response.message);
                        loadTableData();
                        $('#dynamicOutcomeForm')[0].reset(); // Reset the form
                        $('#dynamicTable tbody').empty(); // Clear the table rows
                        let newRow = `
                        <tr>
                            <td>
                                <input type="text" name="name[]" class="form-control name" required>
                            </td>
                            <td>
                                <input type="text" name="amount[]" oninput="this.value = this.value.replace(/[^0-9]/g, '');" class="form-control amount" required>
                            </td>
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
