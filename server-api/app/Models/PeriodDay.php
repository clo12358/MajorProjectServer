<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_id',
        'date',
        'flow',
        'has_clots',
    ];

    public function period()
    {
        return $this->belongsTo(Period::class);
    }
}