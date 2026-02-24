<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //Relationships
    public function symptoms()
    {
        return $this->hasMany(Symptom::class);
    }
}
