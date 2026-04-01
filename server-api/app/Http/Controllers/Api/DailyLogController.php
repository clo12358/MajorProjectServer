<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cycle;
use App\Models\DailyLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DailyLogController extends Controller
{
    public function index(Request $request)
    {
        $cycle = Cycle::where('user_id', $request->user()->id)
            ->orderByDesc('start_date')
            ->first();

        if (!$cycle) {
            return response()->json([]);
        }

        $logs = DailyLog::where('cycle_id', $cycle->id)
            ->with(['dailySymptoms.symptom.category', 'journal'])
            ->orderByDesc('date')
            ->get();

        return response()->json($logs);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => ['required', 'date'],
        ]);

        $user = $request->user();
        $date = Carbon::parse($data['date'])->toDateString();

        $cycle = Cycle::where('user_id', $user->id)
            ->where('start_date', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->where('end_date', '>=', $date)
                  ->orWhereNull('end_date');
            })
            ->orderByDesc('start_date')
            ->first();

        if (!$cycle) {
            return response()->json(['message' => 'No cycle exists for this date. Log a period start first.'], 422);
        }

        $log = DailyLog::firstOrCreate(
            ['cycle_id' => $cycle->id, 'date' => $date],
        );

        return response()->json($log->load(['dailySymptoms.symptom.category', 'journal']), 201);
    }

    public function show(Request $request, DailyLog $dailyLog)
    {
        if ($dailyLog->cycle->user_id !== $request->user()->id) {
            abort(403);
        }

        return response()->json($dailyLog->load(['dailySymptoms.symptom.category', 'journal']));
    }

    public function update(Request $request, DailyLog $dailyLog)
    {
        if ($dailyLog->cycle->user_id !== $request->user()->id) {
            abort(403);
        }

        $dailyLog->update($data);

        return response()->json($dailyLog->load(['dailySymptoms.symptom.category', 'journal']));
    }

    public function destroy(Request $request, DailyLog $dailyLog)
    {
        if ($dailyLog->cycle->user_id !== $request->user()->id) {
            abort(403);
        }

        $dailyLog->delete();
        return response()->json(['message' => 'Deleted']);
    }
}