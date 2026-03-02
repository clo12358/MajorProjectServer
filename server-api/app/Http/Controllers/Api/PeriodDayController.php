<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\PeriodDay;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PeriodDayController extends Controller
{
    // GET /periods/{period}/days
    public function index(Request $request, Period $period)
    {
        if ($period->cycle->user_id !== $request->user()->id) {
            abort(403);
        }

        return response()->json(
            $period->days()->orderBy('date')->get()
        );
    }

    // PUT /periods/{period}/days
    public function upsert(Request $request, Period $period)
    {
        if ($period->cycle->user_id !== $request->user()->id) {
            abort(403);
        }

        $data = $request->validate([
            'date' => ['required','date'],
            'flow' => ['nullable','in:light,medium,heavy'],
            'has_clots' => ['nullable','boolean'],
        ]);

        $date = Carbon::parse($data['date'])->toDateString();

        if ($date < $period->start_date) {
            return response()->json(['message' => 'Date cannot be before period start.'], 422);
        }
        if ($period->end_date && $date > $period->end_date) {
            return response()->json(['message' => 'Date cannot be after period end.'], 422);
        }

        $day = PeriodDay::updateOrCreate(
            ['period_id' => $period->id, 'date' => $date],
            [
                'flow' => $data['flow'] ?? null,
                'has_clots' => (bool)($data['has_clots'] ?? false),
            ]
        );

        return response()->json($day);
    }
}