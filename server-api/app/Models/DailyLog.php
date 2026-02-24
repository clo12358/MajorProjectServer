<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyLog extends Model
{
    //Relationships
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
