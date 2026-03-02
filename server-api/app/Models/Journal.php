<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;

    protected $fillable = [
        'daily_log_id',
        'entry',
        'feeling',
    ];

    public function dailyLog()
    {
        return $this->belongsTo(DailyLog::class);
    }
}