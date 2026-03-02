<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;

    protected $fillable = [
        'cycle_id',
        'start_date',
        'end_date',
    ];

    public function cycle()
    {
        return $this->belongsTo(Cycle::class);
    }

    public function days()
    {
        return $this->hasMany(PeriodDay::class);
    }
}