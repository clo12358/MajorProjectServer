<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cycle;
use App\Models\Period;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeriodController extends Controller
{
    public function index(Request $request)
    {
        $periods = Period::whereHas('cycle', function ($q) use ($request) {
                $q->where('user_id', $request->user()->id);
            })
            ->orderByDesc('start_date')
            ->get();

        return response()->json($periods);
    }

    //  NEW: GET /periods/{period}
    // Returns a single period with all its period_days (flow history)
    public function show(Request $request, Period $period)
    {
        if ($period->cycle->user_id !== $request->user()->id) {
            abort(403);
        }

        $period->load([
            'days' => function ($q) {
                $q->orderBy('date');
            }
        ]);

        return response()->json($period);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date'   => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $user = $request->user();
        $start = Carbon::parse($data['start_date'])->startOfDay();

        $payload = DB::transaction(function () use ($user, $start, $data) {

            // 1) close previous open cycle (end_date null)
            $previous = Cycle::where('user_id', $user->id)
                ->whereNull('end_date')
                ->orderByDesc('start_date')
                ->first();

            if ($previous) {
                $previousEnd = $start->copy()->subDay()->toDateString();

                // avoid invalid end_date
                if ($previousEnd >= $previous->start_date) {
                    $previous->end_date = $previousEnd;
                    $previous->cycle_length = Carbon::parse($previous->start_date)
                        ->diffInDays(Carbon::parse($previous->end_date)) + 1;
                    $previous->save();
                }
            }

            // 2) create new cycle
            $cycle = Cycle::create([
                'user_id' => $user->id,
                'start_date' => $start->toDateString(),
                'end_date' => null,
                'cycle_length' => null,
            ]);

            // 3) create new period inside the new cycle
            $period = Period::create([
                'cycle_id' => $cycle->id,
                'start_date' => $start->toDateString(),
                'end_date' => $data['end_date'] ?? null,
            ]);

            return ['cycle' => $cycle, 'period' => $period];
        });

        return response()->json($payload, 201);
    }

    public function update(Request $request, Period $period)
    {
        if ($period->cycle->user_id !== $request->user()->id) {
            abort(403);
        }

        $data = $request->validate([
            'start_date' => ['sometimes', 'date'],
            'end_date'   => ['sometimes', 'nullable', 'date'],
        ]);

        // if both provided, enforce end_date >= start_date
        if (array_key_exists('start_date', $data) && array_key_exists('end_date', $data) && $data['end_date'] !== null) {
            $start = Carbon::parse($data['start_date'])->startOfDay();
            $end = Carbon::parse($data['end_date'])->startOfDay();

            if ($end->lt($start)) {
                return response()->json(['message' => 'end_date must be after or equal to start_date'], 422);
            }
        }

        $period->update($data);

        return response()->json($period);
    }

    public function destroy(Request $request, Period $period)
    {
        if ($period->cycle->user_id !== $request->user()->id) {
            abort(403);
        }

        $period->delete();
        return response()->json(['message' => 'Deleted']);
    }
}