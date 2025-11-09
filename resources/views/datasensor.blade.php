@extends('layouts.main')

@section('content')

    <div class="container">
        <center>
            <h2 class="mb-4">📊 Semua Data Sensor</h2>
            <!-- {{-- Asumsikan CSS Bootstrap 4 sudah dimuat di layouts.main, jika tidak, pastikan link CSS berikut aktif --}} -->
             <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> 
        </center>

        {{-- Filter tanggal --}}
        <form method="GET" action="{{ route('data.sensor') }}" class="mb-3 d-flex align-items-center">
            <input type="date" name="tanggal" id="filter_tanggal" value="{{ request('tanggal') }}" class="form-control w-auto me-2">

            {{-- Tombol tampilkan --}}
            <button type="submit" class="btn btn-primary me-2">
                <i class="fas fa-search"></i> Tampilkan
            </button>

            {{-- Tombol reset --}}
            <a href="{{ route('data.sensor') }}" class="btn btn-secondary me-2">
                <i class="fas fa-sync-alt"></i> Reset
            </a>
            
            {{-- TOMBOL DELETE BARU (Ditempatkan di ujung kanan) --}}
            <button type="button" class="btn btn-warning ms-auto" data-toggle="modal" data-target="#deleteByMonthModal">
                <i class="fas fa-trash-alt"></i> Hapus Data Per Bulan
            </button>
            
        </form>

        {{-- Pesan Status Setelah Penghapusan --}}
        @if (session('delete_success'))
            <div class="alert alert-success">{{ session('delete_success') }}</div>
        @endif
        @if (session('delete_error'))
            <div class="alert alert-warning">{{ session('delete_error') }}</div>
        @endif


        {{-- Tampilkan tabel hanya jika ada data --}}
        @if ($allData->count() > 0)
            <div class="mb-3">
                {{-- Tombol Ekspor PDF --}}
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#pdfConfirmModal" id="showPdfModalBtn">
                    <i class="fa-regular fa-file-pdf"></i> Ekspor ke PDF
                </button>
            </div>

            {{-- Style khusus tabel --}}
            <style>
                .sensor-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                    font-size: 14px;
                    text-align: center;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                    border-radius: 8px;
                    overflow: hidden;
                }

                .sensor-table th,
                .sensor-table td {
                    border: 1px solid #ddd;
                    padding: 10px;
                }

                .sensor-table th {
                    background: #212529;
                    color: #fff;
                }

                .sensor-table tr:nth-child(even) {
                    background: #f8f9fa;
                }

                .badge {
                    display: inline-block;
                    padding: 5px 10px;
                    border-radius: 8px;
                    color: white;
                    font-weight: bold;
                }

                .badge-gas {
                    background: #dc3545;
                }

                .badge-suhu {
                    background: #0d6efd;
                }

                .badge-kelembaban {
                    background: #198754;
                }

                .pagination {
                    display: flex;
                    justify-content: left;
                    flex-wrap: wrap;
                    padding-left: 0;
                    list-style: none;
                }

                .pagination li {
                    margin: 0 3px;
                }
            </style>

            <table class="sensor-table">
                <thead>
                    <tr>
                        <th>Gas (ppm)</th>
                        <th>Suhu (°C)</th>
                        <th>Kelembaban (%)</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($allData as $index => $data)
                        <tr>
                            <td><span class="badge badge-gas">{{ $data->gas }}</span></td>
                            <td><span class="badge badge-suhu">{{ $data->suhu }}</span></td>
                            <td><span class="badge badge-kelembaban">{{ $data->kelembaban }}</span></td>
                            <td>{{ $data->created_at->format('d M Y H:i:s') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-3">
                <nav>
                    <ul class="pagination">
                        {{-- Tombol Previous --}}
                        @if ($allData->onFirstPage())
                            <li class="page-item disabled"><span class="page-link">Previous</span></li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $allData->previousPageUrl() }}" rel="prev">Previous</a>
                            </li>
                        @endif

                        {{-- Angka Halaman --}}
                        @for ($i = 1; $i <= $allData->lastPage(); $i++)
                            @if ($i == $allData->currentPage())
                                <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                            @else
                                <li class="page-item"><a class="page-link"
                                        href="{{ $allData->url($i) }}">{{ $i }}</a></li>
                            @endif
                        @endfor

                        {{-- Tombol Next --}}
                        @if ($allData->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $allData->nextPageUrl() }}" rel="next">Next</a>
                            </li>
                        @else
                            <li class="page-item disabled"><span class="page-link">Next</span></li>
                        @endif
                    </ul>
                </nav>
            </div>
        @else
            <div class="alert alert-info">Silakan pilih tanggal atau belum ada data sensor.</div>
        @endif
    </div>

    {{-- START: BOOTSTRAP MODAL UNTUK KONFIRMASI PENGHAPUSAN PER BULAN (PENTING) --}}
    <div class="modal fade" id="deleteByMonthModal" tabindex="-1" role="dialog" aria-labelledby="deleteByMonthModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteByMonthModalLabel"><i class="fas fa-exclamation-triangle me-2"></i> Konfirmasi Hapus Data Per Bulan</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                {{-- Form POST ke route delete.sensor.month --}}
                <form id="deleteByMonthForm" method="POST" action="{{ route('delete.sensor.month') }}">
                    @csrf
                    @method('DELETE') {{-- WAJIB menggunakan method DELETE --}}
                    
                    <div class="modal-body">
                        <p class="text-danger font-weight-bold">PERINGATAN: Aksi ini akan menghapus semua data sensor secara permanen untuk bulan yang Anda pilih dan TIDAK dapat dibatalkan!</p>
                        <p>Pilih bulan dan tahun data sensor yang ingin Anda hapus:</p>
                        
                        <div class="form-group">
                            <label for="month_to_delete">Bulan</label>
                            <select name="month" id="month_to_delete" class="form-control" required>
                                @php
                                    // Membuat array Bulan
                                    $months = [
                                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
                                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
                                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                    ];
                                @endphp
                                @foreach ($months as $num => $name)
                                    <option value="{{ $num }}" {{ $num == date('n') ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mt-3">
                            <label for="year_to_delete">Tahun</label>
                            <select name="year" id="year_to_delete" class="form-control" required>
                                @for ($y = date('Y'); $y >= 2020; $y--)
                                    <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash-alt me-1"></i> Ya, Hapus Permanen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- END: BOOTSTRAP MODAL PENGHAPUSAN --}}


    {{-- START: STRUKTUR BOOTSTRAP MODAL UNTUK KONFIRMASI PDF (Sudah Ada) --}}
    <div class="modal fade" id="pdfConfirmModal" tabindex="-1" role="dialog" aria-labelledby="pdfConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="pdfConfirmModalLabel"><i class="fas fa-exclamation-circle me-2"></i> Konfirmasi Ekspor Laporan PDF</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin mengunduh laporan data sensor dalam format PDF?</p>
                    <p class="mt-2 text-muted small">Laporan ini akan menggunakan data yang saat ini difilter berdasarkan tanggal.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    {{-- Tombol unduh final --}}
                    <a id="finalPdfDownloadLink" href="#" class="btn btn-danger">
                        <i class="fas fa-download me-1"></i> Ya, Unduh Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
    {{-- END: STRUKTUR BOOTSTRAP MODAL UNTUK KONFIRMASI PDF --}}

    {{-- Kebutuhan JavaScript Bootstrap untuk Modal --}}
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <script>
        // Fungsi untuk memperbarui link unduhan PDF (Sudah Ada)
        $(document).ready(function() {
            // Ketika tombol pemicu modal PDF diklik
            $('#showPdfModalBtn').on('click', function() {
                const tanggal = document.getElementById('filter_tanggal').value;
                let url = "{{ route('data.sensor.pdf', ['tanggal' => '__tanggal__']) }}";
                url = url.replace('__tanggal__', tanggal);
                document.getElementById('finalPdfDownloadLink').href = url;
            });
        });
    </script>
@endsection