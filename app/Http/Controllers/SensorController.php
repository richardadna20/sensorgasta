<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorReading; // model untuk tabel sensor_readings

class SensorController extends Controller
{
    public function index()
    {
        // Ambil data terakhir (gas + suhu)
        $latest = SensorReading::latest()->take(20)->get();

        return response()->json($latest);
    }

    public function store(Request $request)
    {
        $reading = new SensorReading();
        $reading->sensor_id = $request->sensor_id;
        $reading->type = $request->type; // gas / suhu
        $reading->value = $request->value;
        $reading->save();

        return response()->json(['status' => 'ok']);
    }
}
