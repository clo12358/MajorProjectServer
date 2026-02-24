<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cycle extends Model
{
    //Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function periods()
    {
        return $this->hasMany(Period::class);
    }

    public function dailyLogs()
    {
        return $this->hasMany(DailyLog::class);
    }
}
