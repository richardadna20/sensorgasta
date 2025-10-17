@extends('layouts.main')

@section('content')
    <div class="container">
        <h2 class="mb-4"><i class="fas fa-chart-line text-primary"></i> Grafik Sensor</h2>
        {{-- Link Bootstrap di sini tidak ideal, sebaiknya ada di layouts.main --}}
        {{-- FIX: Mengganti xintegrity menjadi integrity --}}
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" xintegrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        
        {{-- Filter tanggal --}}
        <form method="GET" action="{{ route('grafik') }}" class="row mb-3">
            <div class="col-md-3">
                <label for="start_date" class="form-label">Tanggal Mulai</label>
                <input type="date" name="start_date" id="start_date" class="form-control"
                    value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label">Tanggal Akhir</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            
            {{-- Tombol Aksi (Tampilkan, Reset, dan Ekspor Grafik) --}}
            <div class="col-md-6 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2 shadow-sm">
                    <i class="fas fa-search"></i> Tampilkan
                </button>
                <a href="{{ route('grafik') }}" class="btn btn-secondary me-2 shadow-sm">
                    <i class="fas fa-sync-alt"></i> Reset
                </a>
                
                {{-- Tombol untuk menampilkan pratinjau Modal --}}
            </div>
        </form>
        
<button type="button" id="showPreviewBtn" class="btn btn-success shadow-sm" onclick="showChartPreview()" disabled>
                    <i class="fas fa-eye me-1"></i> Pratinjau & Ekspor
                </button>


        <div class="card shadow p-3">
           <canvas id="sensorChart"></canvas>
        </div>
    </div>

    {{-- START: STRUKTUR BOOTSTRAP MODAL UNTUK PRATINJAU GAMBAR --}}
    <div class="modal fade" id="chartPreviewModal" tabindex="-1" role="dialog" aria-labelledby="chartPreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="chartPreviewModalLabel">Pratinjau Grafik Sensor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    {{-- Tempat gambar pratinjau akan dimuat --}}
                    <img id="chartPreviewImage" class="img-fluid border shadow-sm" alt="Pratinjau Grafik">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    {{-- Tombol ini akan memanggil fungsi unduh final --}}
                    <button type="button" id="finalDownloadBtn" class="btn btn-success" onclick="downloadImage()">
                        <i class="fas fa-download me-1"></i> Unduh Gambar (PNG)
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- END: STRUKTUR BOOTSTRAP MODAL UNTUK PRATINJAU GAMBAR --}}

    {{-- Kebutuhan JavaScript Bootstrap untuk Modal --}}
    {{-- CATATAN: Pastikan layout utama Anda juga memuat jQuery dan Popper.js --}}
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" xintegrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" xintegrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" xintegrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

@endsection

@section('extra-js')
    <script>
        // Variabel untuk menyimpan instance Chart.js dan URL Gambar
        let myChart; 
        let chartImageURL = ''; // URL gambar yang akan disimpan sementara

        // Data dari Blade (Dipindahkan ke luar fungsi untuk parsing yang lebih aman)
        const labels = @json($labels);
        const gasData = @json($gasData);
        const suhuData = @json($suhuData);
        const kelembabanData = @json($kelembabanData);

        // Fungsi untuk membuat dan menampilkan chart
        function createChart() {
            const showPreviewBtn = document.getElementById('showPreviewBtn');
            const chartContainer = document.querySelector('.card.shadow.p-3'); 

            // Hapus konten sebelumnya (pesan "tidak ada data") jika ada
            chartContainer.innerHTML = '<canvas id="sensorChart"></canvas>';
            
            if (labels && labels.length > 0) {
                const ctx = document.getElementById('sensorChart').getContext('2d');
                
                // Hancurkan chart lama jika ada, untuk menghindari duplikasi
                if (myChart) {
                    myChart.destroy();
                }

                // Simpan instance chart ke myChart
                myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                                label: 'Gas',
                                data: gasData,
                                borderColor: 'red',
                                fill: false,
                                tension: 0.2
                            },
                            {
                                label: 'Suhu',
                                data: suhuData,
                                borderColor: 'blue',
                                fill: false,
                                tension: 0.2
                            },
                            {
                                label: 'Kelembaban',
                                data: kelembabanData,
                                borderColor: 'green',
                                fill: false,
                                tension: 0.2
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false, 
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
                
                // Aktifkan tombol pratinjau setelah grafik berhasil dibuat
                showPreviewBtn.disabled = false;
            } else {
                // Tampilkan pesan jika tidak ada data
                if (chartContainer) {
                    chartContainer.innerHTML = '<div class="alert alert-warning mb-0 text-center"><i class="fas fa-exclamation-triangle me-2"></i>Tidak ada data sensor untuk periode ini. Silakan ganti tanggal filter.</div>';
                }
                showPreviewBtn.disabled = true;
            }
        }
        
        // Fungsi BARU: Menampilkan Pratinjau Grafik di Modal
        function showChartPreview() {
            if (!myChart) {
                console.error("Chart belum diinisialisasi.");
                return;
            }

            // 1. Konversi canvas ke URL gambar (Base64)
            chartImageURL = myChart.toBase64Image('image/png', 1.0); 
            
            // 2. Muat gambar ke dalam elemen di modal
            const previewImage = document.getElementById('chartPreviewImage');
            previewImage.src = chartImageURL;
            
            // 3. Tampilkan modal menggunakan jQuery (kebutuhan Bootstrap 4)
            $('#chartPreviewModal').modal('show');
        }

        // Fungsi BARU: Mengunduh gambar dari Modal
        function downloadImage() {
            if (!chartImageURL) {
                console.error("URL gambar tidak ditemukan.");
                return;
            }

            // 1. Buat elemen link temporer
            const link = document.createElement('a');
            link.href = chartImageURL;
            link.download = `grafik-sensor-${new Date().toISOString().slice(0, 10)}.png`;
            
            // 2. Simulasikan klik untuk memulai download
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            // 3. Tutup modal setelah unduh
            $('#chartPreviewModal').modal('hide');
        }

        // Panggil fungsi createChart saat halaman dimuat
        createChart();
    </script>
@endsection
