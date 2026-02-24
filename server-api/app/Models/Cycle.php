<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cycle extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'cycle_length',
    ];
    
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
