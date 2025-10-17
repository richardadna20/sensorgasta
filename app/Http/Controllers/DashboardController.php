<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\SensorData;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        // 🔹 Ambil semua data sensor hari ini
        $sensorData = DB::table('sensor_data')
            ->whereDate('created_at', $today)
            ->orderBy('created_at', 'asc')
            ->get();

        // 🔹 Labels waktu untuk grafik
        $labels = $sensorData->pluck('created_at')
            ->map(fn($dt) => Carbon::parse($dt)->format('H:i'))
            ->toArray();

        // 🔹 GAS
        $gasData = $sensorData->pluck('gas')->filter()->values();
        $jumlahGas = $gasData->count();
        $rataGas   = $gasData->avg() ?? 0;
        $maxGas    = $gasData->max() ?? 0;
        $statusGas = $this->getStatusGas($maxGas);

        // 🔹 SUHU
        $suhuData = $sensorData->pluck('suhu')->filter()->values();
        $jumlahSuhu = $suhuData->count();
        $rataSuhu   = $suhuData->avg() ?? 0;
        $maxSuhu    = $suhuData->max() ?? 0;
        $statusSuhu = $this->getStatusSuhu($maxSuhu);

        // 🔹 KELEMBABAN
        $kelembabanData = $sensorData->pluck('kelembaban')->filter()->values();
        $jumlahKelembaban = $kelembabanData->count();
        $rataKelembaban   = $kelembabanData->avg() ?? 0;
        $maxKelembaban    = $kelembabanData->max() ?? 0;
        $statusKelembaban = $this->getStatusKelembaban($maxKelembaban);

        return view('dashboard', [
            // GAS
            'jumlahGas' => $jumlahGas,
            'rataGas'   => round($rataGas, 2),
            'maxGas'    => $maxGas,
            'statusGas' => $statusGas,
            'gasLabels' => $labels,
            'gasData'   => $gasData->toArray(),

            // SUHU
            'jumlahSuhu' => $jumlahSuhu,
            'rataSuhu'   => round($rataSuhu, 2),
            'maxSuhu'    => $maxSuhu,
            'statusSuhu' => $statusSuhu,
            'suhuLabels' => $labels,
            'suhuData'   => $suhuData->toArray(),

            // KELEMBABAN
            'jumlahKelembaban' => $jumlahKelembaban,
            'rataKelembaban'   => round($rataKelembaban, 2),
            'maxKelembaban'    => $maxKelembaban,
            'statusKelembaban' => $statusKelembaban,
            'kelembabanLabels' => $labels,
            'kelembabanData'   => $kelembabanData->toArray(),
        ]);
    }

    private function getStatusGas($ppm)
    {
        if ($ppm > 300) return 'Bahaya';
        if ($ppm > 150) return 'Waspada';
        return 'Aman';
    }

    private function getStatusSuhu($suhu)
    {
        if ($suhu > 40) return 'Bahaya';
        if ($suhu > 30) return 'Waspada';
        return 'Normal';
    }

    private function getStatusKelembaban($kelembaban)
    {
        if ($kelembaban > 80 || $kelembaban < 30) return 'Bahaya';
        return 'Normal';
    }
public function dataSensor(Request $request)
{
    $query = SensorData::query();

    // Filter tanggal
    if ($request->filled('tanggal')) {
        $query->whereDate('created_at', $request->tanggal);
    }

    $allData = $query->orderBy('created_at', 'desc')->paginate(20);

    return view('datasensor', compact('allData'));
}

public function downloadPDF(Request $request)
{
    $query = SensorData::query();

    if ($request->filled('tanggal')) {
        $query->whereDate('created_at', $request->tanggal);
    }

    $allData = $query->orderBy('created_at', 'desc')->get();

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('datasensorpdf', compact('allData'));

    return $pdf->download('laporandatasensor.pdf');
}
public function grafik(Request $request)
{
    $query = SensorData::query();

    // Filter tanggal (opsional)
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('created_at', [
            $request->start_date . " 00:00:00",
            $request->end_date . " 23:59:59"
        ]);
    }

    $data = $query->orderBy('created_at')->get();

    return view('grafik', [
        'labels' => $data->pluck('created_at')->map(fn($d) => $d->format('Y-m-d H:i'))->toArray(),
        'gasData' => $data->pluck('gas')->toArray(),
        'suhuData' => $data->pluck('suhu')->toArray(),
        'kelembabanData' => $data->pluck('kelembaban')->toArray(),
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
    ]);
}

}


