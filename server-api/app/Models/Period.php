<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    //Relationships
    public function cycle()
    {
        return $this->belongsTo(Cycle::class);
    }
}
