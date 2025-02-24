<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Checkout Page </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: #f0f2f5;
            /* Ubah warna latar belakang */
            /* Light background for better contrast */
        }

        .card {
            border-radius: 10px;
            /* Ubah radius sudut */
            /* Rounded corners for the card */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            /* Ubah bayangan */
            /* Subtle shadow */
        }

        h3 {
            color: #0056b3;
            /* Ubah warna judul */
            /* Primary color for headings */
        }

        .btn-primary {
            background-color: #007bff;
            /* Ubah warna tombol */
            /* Green button for submission */
            border: 1px solid #007bff;
            /* Tambahkan border */
            /* Remove border */
        }

        .btn-primary:hover {
            background-color: #0056b3;
            /* Ubah warna saat hover */
            /* Darker green on hover */
        }

        .alert-danger {
            background-color: #f8d7da;
            /* Tetap sama */
            /* Light red for error messages */
            border-color: #f5c6cb;
            /* Red border */
        }

        @media (max-width: 768px) {
            table {
                border: none;
            }

            table thead {
                display: none;
                /* Sembunyikan header asli */
            }

            table tbody tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid #ddd;
                border-radius: 10px;
                padding: 1rem;
            }

            table tbody td {
                display: flex;
                justify-content: space-between;
                padding: 0.5rem 0;
                border: none;
            }

            table tbody td::before {
                content: attr(data-label);
                /* Ambil label dari atribut data-label */
                font-weight: bold;
                width: 50%;
                text-align: left;
                padding-right: 10px;
            }

            table tbody td img {
                width: 80px;
                height: auto;
            }
        }
    </style>
</head>

<body>
    <section class="trending pt-4 pb-0 bg-lgrey mb-4 mt-4">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <h3 class="mb-4">Detail Tiket</h3>
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>Photo</th>
                                    <th>Nama</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Total Harga</th>
                                    <th>Biaya Admin</th>
                                    <th>Total Pembayaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order as $item)
                                <tr>
                                    <td data-label="Photo">
                                        <img src="{{ asset('storage/ticket/' . $item->ticket->photo) }}"
                                            alt="{{ $item->ticket->name }}" class="img-fluid"
                                            style="max-width: 100px; height: auto;">
                                    </td>
                                    <td data-label="Nama">{{ $item->ticket->name }}</td>
                                    <td data-label="Harga">Rp. {{ number_format($item->ticket->price, 0, ',', '.') }}</td>
                                    <td data-label="Jumlah">
                                        <input type="number" id="qty" value="{{ $item->quantity ?? '1' }}" min="1"
                                            class="form-control w-50 text-center">
                                    </td>
                                    <td data-label="Total Harga"><span id="total">Rp.
                                            {{ number_format($item->ticket->price * $item->quantity, 0, ',', '.') }}</span></td>
                                    <td data-label="Biaya Admin"><span id="fee">Rp.
                                            {{ number_format($item->ticket->price * $item->quantity * 0.02 + 5000, 0, ',', '.') }}</span></td>
                                    <td data-label="Total Pembayaran"><span id="finalTotal">Rp.
                                            {{ number_format($item->ticket->price * $item->quantity * 1.02 + 5000, 0, ',', '.') }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5>Pilih Metode Pembayaran:</h5>
                            <select id="paymentMethod" class="form-control">
                                <option value="online">Online</option>
                                <option value="cod">Bayar di Tempat (COD)</option>
                            </select>
                        </div>
                        <div class="col-md-6 text-end mt-3 mt-md-0">
                            <button class="btn btn-primary" id="payButton">Bayar</button>
                            <a class="btn btn-danger" href="{{ route('user.order') }}">Batal</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eHz" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        document.getElementById('payButton').addEventListener('click', function() {
            let totalAmount = 0;
            let fee = 0;
            let finalAmount = 0;

            // Ambil semua elemen quantity dan harga tiket
            document.querySelectorAll('tbody tr').forEach(row => {
                let qty = parseInt(row.querySelector('input[type="number"]').value);
                let price = parseInt(row.querySelector('[data-label="Harga"]').innerText.replace(/\D/g, ''));
                totalAmount += price * qty; // Total harga seluruh tiket
            });

            // Ambil metode pembayaran
            let paymentMethod = document.getElementById('paymentMethod').value;

            if (paymentMethod === 'online') {
                fee = totalAmount * 0.02 + 5000; // Biaya admin hanya untuk online
            }

            finalAmount = totalAmount + fee; // Total pembayaran akhir

            if (paymentMethod === 'online') {
                // Proses pembayaran online dengan Midtrans
                fetch('/charge', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        amount: finalAmount,
                        first_name: '{{ auth()->user()->first_name }}',
                        last_name: '{{ auth()->user()->last_name }}',
                        email: '{{ auth()->user()->email }}',
                        phone: '{{ auth()->user()->phone }}',
                        order_details: @json($order->map(fn($item) => [
                            'ticket_id' => $item->ticket->id,
                            'quantity' => $item->quantity,
                            'price' => $item->ticket->price
                        ])),
                        total_price: finalAmount
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.snapToken) {
                        snap.pay(data.snapToken, {
                            onSuccess: function(result) {
                                handlePayment(result, data.order_id);
                            },
                            onPending: function(result) {
                                alert('Pembayaran pending!');
                            },
                            onError: function(result) {
                                alert('Pembayaran gagal!');
                            },
                            onClose: function() {
                                // Jika pengguna menutup modal tanpa membayar, update status ke 'batal'
                                updateCheckoutStatus(data.order_id, 'rejected');
                            }
                        });
                    } else {
                        alert('Gagal mendapatkan Snap Token');
                    }
                })
                .catch(error => console.error('Error:', error));
            } else {
                // Proses pembayaran COD (simpan ke database)
                fetch('/process-cod', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        order_details: @json($order->map(fn($item) => [
                            'ticket_id' => $item->ticket->id,
                            'quantity' => $item->quantity,
                            'price' => $item->ticket->price
                        ])),
                        total_price: finalAmount
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Pesanan berhasil dibuat, silakan bayar di tempat');
                        window.location.href = '/order';
                    } else {
                        alert('Gagal memproses pesanan COD');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });

        function handlePayment(result, orderId) {
            // Proses setelah pembayaran berhasil
            fetch('/handle-payment-success', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        order_id: orderId,
                        payment_result: result
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Pembayaran berhasil!');
                        window.location.href = '/order';
                    } else {
                        alert('Gagal memproses pembayaran');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        // Fungsi untuk mengubah status checkout menjadi batal
        function updateCheckoutStatus(orderId, status) {
            fetch('/checkout/cancel', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ order_id: orderId, status: status })
            })
            .then(response => response.json())
            .then(data => {
                alert('Pembayaran close!');
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>

</html>