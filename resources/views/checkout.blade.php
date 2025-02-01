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
                <img class="card-img-top img-fluid" src="{{ asset('storage/ticket/' . $ticket->photo) }}"
                    alt="{{ $ticket->name }}" style="height: 400px; object-fit: cover;">
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
                                <tr>
                                    <td data-label="Photo">
                                        <img src="{{ asset('storage/ticket/' . $ticket->photo) }}"
                                            alt="{{ $ticket->name }}" class="img-fluid"
                                            style="max-width: 100px; height: auto;">
                                    </td>
                                    <td data-label="Nama">{{ $ticket->name }}</td>
                                    <td data-label="Harga">Rp. {{ number_format($ticket->price, 0, ',', '.') }}</td>
                                    <td data-label="Jumlah">
                                        <div class="d-flex align-items-center justify-content-end">
                                            <button class="btn btn-secondary me-2" onclick="updateQuantity(-1)">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" id="qty" value="1" min="1"
                                                class="form-control w-50 text-center">
                                            <button class="btn btn-secondary ms-2" onclick="updateQuantity(1)">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td data-label="Total Harga"><span id="total">Rp.
                                            {{ number_format($ticket->price, 0, ',', '.') }}</span></td>
                                    <td data-label="Biaya Admin"><span id="fee">Rp.
                                            {{ number_format($ticket->price * 0.02 + 5000, 0, ',', '.') }}</span></td>
                                    <td data-label="Total Pembayaran"><span id="finalTotal">Rp.
                                            {{ number_format($ticket->price * 1.02 + 5000, 0, ',', '.') }}</span></td>
                                </tr>
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
            let qty = document.getElementById('qty').value;
            let price = {{ $ticket->price }};
            let totalAmount = price * qty; // Tanpa biaya admin pada awalnya
            let fee = 0; // Biaya admin dimulai dengan 0
            let finalAmount = totalAmount + fee; // Final total tanpa biaya admin

            // Tentukan apakah metode pembayaran menggunakan COD atau Online
            let paymentMethod = document.getElementById('paymentMethod').value;

            if (paymentMethod === 'online') {
                // Hitung biaya admin hanya untuk pembayaran online (biaya 2% + Rp 5000)
                fee = totalAmount * 0.02 + 5000;
                finalAmount = totalAmount + fee;
            }

            // Melanjutkan proses sesuai metode pembayaran
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
                            ticket_id: {{ $ticket->id }},
                            quantity: qty,
                            ticket_date: '{{ now()->format('Y-m-d') }}',
                            total_price: finalAmount
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.snapToken) {
                            snap.pay(data.snapToken, {
                                onSuccess: function(result) {
                                    handlePayment(result, data
                                        .order_id); // Panggil fungsi saat pembayaran sukses
                                },
                                onPending: function(result) {
                                    alert('Pembayaran pending!');
                                },
                                onError: function(result) {
                                    alert('Pembayaran gagal!');
                                }
                            });
                        } else {
                            alert('Gagal mendapatkan Snap Token');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            } else {
                // Proses pembayaran COD (simpan ke database sebagai pending)
                fetch('/process-cod', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            ticket_id: {{ $ticket->id }},
                            quantity: qty,
                            ticket_date: '{{ now()->format('Y-m-d') }}',
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
                    .catch(error => {
                        console.error('Error:', error);
                    });
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
    </script>
    <script>
        function updateQuantity(change) {
            var qtyInput = document.getElementById('qty');
            var totalPrice = document.getElementById('total');
            var feeDisplay = document.getElementById('fee');
            var finalTotalDisplay = document.getElementById('finalTotal');
            var paymentMethod = document.getElementById('paymentMethod').value;
            var currentQty = parseInt(qtyInput.value);

            // Update quantity with the change
            var newQty = currentQty + change;

            // Ensure quantity does not go below 1
            if (newQty >= 1) {
                qtyInput.value = newQty;

                // Calculate the total price
                var price = {{ $ticket->price }};
                var newTotal = price * newQty;

                // Initialize fee for COD (no fee)
                var fee = 0;

                // Calculate fee only for online payment
                if (paymentMethod === 'online') {
                    fee = newTotal * 0.02 + 5000;
                }

                // Calculate final total (total price + fee)
                var finalTotal = newTotal + fee;

                // Update the displayed values
                totalPrice.innerText = 'Rp. ' + newTotal.toLocaleString('id-ID');
                feeDisplay.innerText = 'Rp. ' + fee.toLocaleString('id-ID');
                finalTotalDisplay.innerText = 'Rp. ' + finalTotal.toLocaleString('id-ID');
            }
        }
    </script>
</body>

</html>
