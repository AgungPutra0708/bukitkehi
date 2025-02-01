@if ($statistik->isEmpty())
    <p class="text-muted">Data statistik tidak ditemukan untuk bulan dan tahun yang dipilih.</p>
@else
    <canvas id="visitorChart"></canvas>
    <script>
        var visitorData = {
            labels: {!! json_encode($statistik->pluck('bulan')) !!},
            datasets: [{
                    label: 'Laki-Laki',
                    data: {!! json_encode($statistik->pluck('jumlah_lakilaki')) !!},
                    backgroundColor: '#36A2EB'
                },
                {
                    label: 'Perempuan',
                    data: {!! json_encode($statistik->pluck('jumlah_perempuan')) !!},
                    backgroundColor: '#FF6384'
                },
                {
                    label: 'Tidak Diketahui',
                    data: {!! json_encode($statistik->pluck('tidak_diketahui')) !!},
                    backgroundColor: '#FFCE56'
                }
            ]
        };

        new Chart(document.getElementById('visitorChart'), {
            type: 'bar',
            data: visitorData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Statistik Pengunjung'
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Bulan'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Jumlah Pengunjung'
                        }
                    }
                }
            }
        });
    </script>
@endif
