<?php

use App\Models\GasReading;

public function store(Request $request)
{
    $validated = $request->validate([
        'sensor_id' => 'required|integer',
        'ppm' => 'required|numeric'
    ]);

    GasReading::create($validated);

    return response()->json(['message' => 'Data berhasil disimpan'], 201);
}

