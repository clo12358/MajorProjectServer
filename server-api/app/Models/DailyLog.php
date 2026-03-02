<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'cycle_id',
        'date',
        'energy_level',
    ];

    public function cycle()
    {
        return $this->belongsTo(Cycle::class);
    }

    public function dailySymptoms()
    {
        return $this->hasMany(DailySymptom::class);
    }

    public function journal()
    {
        return $this->hasOne(Journal::class);
    }
}