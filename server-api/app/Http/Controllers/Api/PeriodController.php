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

    public function store(Request $request)
    {
        $data = $request->validate([
            'start_date' => ['required','date'],
            'end_date'   => ['nullable','date','after_or_equal:start_date'],
            'flow_level' => ['nullable','in:light,medium,heavy'],
            'has_clots'  => ['nullable','boolean'],
        ]);

        $user = $request->user();
        $start = Carbon::parse($data['start_date'])->startOfDay();

        $payload = DB::transaction(function () use ($user, $start, $data) {

            // close previous open cycle
            $previous = Cycle::where('user_id', $user->id)
                ->whereNull('end_date')
                ->orderByDesc('start_date')
                ->first();

            if ($previous) {
                $previous->end_date = $start->copy()->subDay()->toDateString();
                $previous->cycle_length = Carbon::parse($previous->start_date)
                    ->diffInDays(Carbon::parse($previous->end_date)) + 1;
                $previous->save();
            }

            // create new cycle
            $cycle = Cycle::create([
                'user_id' => $user->id,
                'start_date' => $start->toDateString(),
                'end_date' => null,
                'cycle_length' => null,
            ]);

            // create period in that cycle
            $period = Period::create([
                'cycle_id' => $cycle->id,
                'start_date' => $start->toDateString(),
                'end_date' => $data['end_date'] ?? null,
                'flow_level' => $data['flow_level'] ?? null,
                'has_clots' => (bool)($data['has_clots'] ?? false),
            ]);

            return ['cycle' => $cycle, 'period' => $period];
        });

        return response()->json($payload, 201);
    }

    public function update(Request $request, Period $period)
    {
        // ownership check via cycle
        if ($period->cycle->user_id !== $request->user()->id) {
            abort(403);
        }

        $data = $request->validate([
            'start_date' => ['sometimes','date'],
            'end_date'   => ['sometimes','nullable','date'],
            'flow_level' => ['sometimes','nullable','in:light,medium,heavy'],
            'has_clots'  => ['sometimes','boolean'],
        ]);

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