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
        'flow_level',
        'has_clots',
    ];
    
    //Relationships
    public function cycle()
    {
        return $this->belongsTo(Cycle::class);
    }
}
