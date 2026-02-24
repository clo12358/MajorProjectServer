<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cycle;
use Illuminate\Http\Request;

class CycleController extends Controller
{
    public function index(Request $request)
    {
        $cycles = Cycle::where('user_id', $request->user()->id)
            ->with(['periods'])
            ->orderByDesc('start_date')
            ->get();

        return response()->json($cycles);
    }

    public function show(Request $request, Cycle $cycle)
    {
        if ($cycle->user_id !== $request->user()->id) {
            abort(403);
        }

        return response()->json($cycle->load(['periods','dailyLogs.dailySymptoms.symptom','dailyLogs.journal']));
    }
}