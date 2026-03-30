<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyLog;
use App\Models\Journal;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    // GET /journals
    // Return all journal entries for the authenticated user
    public function index(Request $request)
    {
        $journals = Journal::whereHas('dailyLog.cycle', function ($q) use ($request) {
                $q->where('user_id', $request->user()->id);
            })
            ->with([
                'dailyLog:id,cycle_id,date',
            ])
            ->orderByDesc('created_at')
            ->get();

        return response()->json($journals);
    }

    // GET /daily-logs/{dailyLog}/journal
    public function show(Request $request, DailyLog $dailyLog)
    {
        if ($dailyLog->cycle->user_id !== $request->user()->id) {
            abort(403);
        }

        $journal = $dailyLog->journal;

        if (!$journal) {
            return response()->json(['message' => 'No journal entry found.'], 404);
        }

        return response()->json($journal);
    }

    // PUT /daily-logs/{dailyLog}/journal
    public function upsert(Request $request, DailyLog $dailyLog)
    {
        if ($dailyLog->cycle->user_id !== $request->user()->id) {
            abort(403);
        }

        $data = $request->validate([
            'entry' => ['nullable', 'string'],
            'feeling' => ['nullable', 'in:great,good,okay,low,awful'],
        ]);

        $journal = Journal::updateOrCreate(
            ['daily_log_id' => $dailyLog->id],
            [
                'entry' => $data['entry'] ?? null,
                'feeling' => $data['feeling'] ?? null,
            ]
        );

        return response()->json($journal);
    }
}