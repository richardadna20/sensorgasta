@extends('layouts.main')

@section('content')
<div class="container">
    <h2 class="mb-4">📊 Dashboard Monitoring Sensor</h2>

    {{-- Ringkasan Kondisi --}}
    <div class="row">
        {{-- Gas --}}
        <div class="col-md-4">
            <div class="card text-center shadow mb-4">
                <div class="card-header bg-danger text-white">Gas (ppm)</div>
                <div class="card-body">
                    <p><strong>Jumlah:</strong> {{ $jumlahGas }}</p>
                    <p><strong>Rata-rata:</strong> {{ $rataGas }}</p>
                    <p><strong>Maksimum:</strong> {{ $maxGas }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge 
                            @if($statusGas == 'Bahaya') bg-danger 
                            @elseif($statusGas == 'Waspada') bg-warning 
                            @else bg-success @endif">
                            {{ $statusGas }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Suhu --}}
        <div class="col-md-4">
            <div class="card text-center shadow mb-4">
                <div class="card-header bg-warning">Suhu (°C)</div>
                <div class="card-body">
                    <p><strong>Jumlah:</strong> {{ $jumlahSuhu }}</p>
                    <p><strong>Rata-rata:</strong> {{ $rataSuhu }}</p>
                    <p><strong>Maksimum:</strong> {{ $maxSuhu }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge 
                            @if($statusSuhu == 'Bahaya') bg-danger 
                            @elseif($statusSuhu == 'Waspada') bg-warning 
                            @else bg-success @endif">
                            {{ $statusSuhu }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Kelembaban --}}
        <div class="col-md-4">
            <div class="card text-center shadow mb-4">
                <div class="card-header bg-primary text-white">Kelembaban (%)</div>
                <div class="card-body">
                    <p><strong>Jumlah:</strong> {{ $jumlahKelembaban }}</p>
                    <p><strong>Rata-rata:</strong> {{ $rataKelembaban }}</p>
                    <p><strong>Maksimum:</strong> {{ $maxKelembaban }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge 
                            @if($statusKelembaban == 'Bahaya') bg-danger 
                            @else bg-success @endif">
                            {{ $statusKelembaban }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Grafik --}}
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card shadow">
                <div class="card-header">📈 Grafik Gas</div>
                <div class="card-body">
                    <canvas id="gasChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-12 mb-4">
            <div class="card shadow">
                <div class="card-header">🌡️ Grafik Suhu</div>
                <div class="card-body">
                    <canvas id="suhuChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-12 mb-4">
            <div class="card shadow">
                <div class="card-header">💧 Grafik Kelembaban</div>
                <div class="card-body">
                    <canvas id="kelembabanChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // GAS
    new Chart(document.getElementById('gasChart'), {
        type: 'line',
        data: {
            labels: @json($gasLabels),
            datasets: [{
                label: 'Gas (ppm)',
                data: @json($gasData),
                borderColor: 'red',
                backgroundColor: 'rgba(255,0,0,0.2)',
                fill: true,
                tension: 0.3
            }]
        }
    });

    // SUHU
    new Chart(document.getElementById('suhuChart'), {
        type: 'line',
        data: {
            labels: @json($suhuLabels),
            datasets: [{
                label: 'Suhu (°C)',
                data: @json($suhuData),
                borderColor: 'orange',
                backgroundColor: 'rgba(255,165,0,0.2)',
                fill: true,
                tension: 0.3
            }]
        }
    });

    // KELEMBABAN
    new Chart(document.getElementById('kelembabanChart'), {
        type: 'line',
        data: {
            labels: @json($kelembabanLabels),
            datasets: [{
                label: 'Kelembaban (%)',
                data: @json($kelembabanData),
                borderColor: 'blue',
                backgroundColor: 'rgba(0,0,255,0.2)',
                fill: true,
                tension: 0.3
            }]
        }
    });
</script>
@endsection
