<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $fillable = ['nombre', 'color', 'libro_id'];

    public function libro(){
        return $this->belongsTo(Libro::class);
    }

    public function entradas(){
        return $this->belongsToMany(Entrada::class);
    }
}