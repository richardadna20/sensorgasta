<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Digunakan untuk manipulasi tanggal dan bulan
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\SensorData; // Pastikan Model ini sudah ada dan benar

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

    // --- METHOD BARU: HAPUS DATA PER BULAN (FIXED TIMEZONE) ---
    /**
     * Menghapus semua data sensor berdasarkan bulan dan tahun yang dipilih.
     */
    public function deleteByMonth(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            // Dipermudah: hanya min 2020.
            'year' => 'required|integer|min:2020', 
        ]);

        // Lakukan konversi eksplisit ke integer (int)
        $month = (int) $request->month;
        $year = (int) $request->year;

        try {
            
            // 1. Buat tanggal 1 bulan terpilih
            $date = Carbon::createSafe($year, $month, 1);
            
            if (!$date) {
                return redirect()->route('data.sensor')->with('delete_error', "Kombinasi bulan dan tahun tidak valid.");
            }

            // 2. Tentukan rentang waktu awal dan akhir bulan.
            // PENTING: Set timezone ke 'UTC' agar cocok dengan penyimpanan created_at di database.
            $startDate = $date->copy()->startOfMonth()->setTimezone('UTC'); // <-- FIX TIMEZONE
            $endDate = $date->copy()->endOfMonth()->setTimezone('UTC');     // <-- FIX TIMEZONE

            // Query untuk menghapus data menggunakan Model SensorData
            $deletedCount = SensorData::whereBetween('created_at', [$startDate, $endDate])
                                        ->delete();

            if ($deletedCount > 0) {
                // Untuk pesan success/error, kita kembalikan lagi ke Timezone lokal 
                // agar nama bulan yang ditampilkan benar bagi user (misal: "Oktober 2024")
                $monthName = $date->translatedFormat('F Y'); 
                return redirect()->route('data.sensor')->with('delete_success', "Berhasil menghapus **{$deletedCount}** data sensor untuk bulan **{$monthName}**.");
            } else {
                $monthName = $date->translatedFormat('F Y'); 
                return redirect()->route('data.sensor')->with('delete_error', "Tidak ditemukan data sensor untuk bulan **{$monthName}**.");
            }
        } catch (\Exception $e) {
            // Tangkap exception jika ada kegagalan tak terduga (misalnya error database)
            // Anda bisa log $e->getMessage() untuk debugging lebih lanjut
            return redirect()->route('data.sensor')->with('delete_error', "Terjadi kesalahan sistem saat menghapus data.");
        }
    }
}