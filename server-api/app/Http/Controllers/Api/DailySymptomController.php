<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyLog;
use App\Models\DailySymptom;
use Illuminate\Http\Request;

class DailySymptomController extends Controller
{

    public function store(Request $request, DailyLog $dailyLog)
    {
        if ($dailyLog->cycle->user_id !== $request->user()->id) {
            abort(403);
        }

        $data = $request->validate([
            'items' => ['required','array','min:1'],
            'items.*.symptom_id' => ['required','integer','exists:symptoms,id'],
            'items.*.severity' => ['nullable','in:1,2,3,4,5'],
        ]);

        foreach ($data['items'] as $item) {
            DailySymptom::updateOrCreate(
                [
                    'daily_log_id' => $dailyLog->id,
                    'symptom_id' => $item['symptom_id'],
                ],
                [
                    'severity' => $item['severity'] ?? null,
                ]
            );
        }

        return response()->json(
            $dailyLog->load(['dailySymptoms.symptom.category','journal'])
        );
    }

    public function destroy(Request $request, DailyLog $dailyLog, DailySymptom $dailySymptom)
    {
        if ($dailyLog->cycle->user_id !== $request->user()->id) {
            abort(403);
        }

        // ensures the symptom belongs to this log
        if ($dailySymptom->daily_log_id !== $dailyLog->id) {
            abort(404);
        }

        $dailySymptom->delete();

        return response()->json(
            $dailyLog->load(['dailySymptoms.symptom.category','journal'])
        );
    }
}