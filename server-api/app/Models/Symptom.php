<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Symptom extends Model
{
    //Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
