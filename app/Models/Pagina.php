<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagina extends Model
{
    use HasFactory;
    protected $fillable = ['libro_id', 'created_at'];

    public function libro(){
        return $this->belongsTo(Libro::class);
    }

    public function entradas(){
        return $this->belongsToMany(Entrada::class);
    }
}