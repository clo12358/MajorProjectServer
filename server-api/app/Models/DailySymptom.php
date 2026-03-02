<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySymptom extends Model
{
    use HasFactory;

    protected $fillable = [
        'daily_log_id',
        'symptom_id',
        'severity',
    ];

    public function dailyLog()
    {
        return $this->belongsTo(DailyLog::class);
    }

    public function symptom()
    {
        return $this->belongsTo(Symptom::class);
    }
}