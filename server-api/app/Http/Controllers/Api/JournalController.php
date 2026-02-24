<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyLog;
use App\Models\Journal;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    public function upsert(Request $request, DailyLog $dailyLog)
    {
        if ($dailyLog->cycle->user_id !== $request->user()->id) {
            abort(403);
        }

        $data = $request->validate([
            'entry' => ['nullable','string'],
            'feeling' => ['nullable','in:great,good,okay,low,awful'],
        ]);

        $journal = Journal::updateOrCreate(
            ['daily_log_id' => $dailyLog->id],
            ['entry' => $data['entry'] ?? null, 'feeling' => $data['feeling'] ?? null]
        );

        return response()->json($journal);
    }
}