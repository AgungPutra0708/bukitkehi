@if ($groupedIncomes->isEmpty())
    <p class="text-muted">Data penghasilan tidak ditemukan untuk bulan dan tahun yang dipilih.</p>
@else
    <canvas id="incomeChart"></canvas>
    <script>
        // Labels diambil dari nama tiket atau fasilitas
        var incomeLabels = {!! json_encode($groupedIncomes->pluck('related_name')) !!};

        // Data jumlah penghasilan
        var incomeData = {!! json_encode($groupedIncomes->pluck('total_amount')) !!};

        new Chart(document.getElementById('incomeChart'), {
            type: 'line',
            data: {
                labels: incomeLabels, // Labels diambil dari nama tiket/fasilitas
                datasets: [{
                    label: 'Penghasilan',
                    data: incomeData,
                    borderColor: '#FF6384',
                    fill: false
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Tiket / Fasilitas' // Judul sumbu X
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Penghasilan (IDR)' // Judul sumbu Y
                        },
                        ticks: {
                            beginAtZero: true,
                            callback: function(value, index, values) {
                                return value.toLocaleString('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR'
                                });
                            }
                        }
                    }
                }
            }
        });
    </script>
@endif
