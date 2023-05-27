<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrada extends Model
{
    use HasFactory;
    protected $fillable = ['contenido','compleated_at'];

    public function paginas(){
        return $this->belongsToMany(Pagina::class);
    }

    public function tags(){
        return $this->belongsToMany(Tag::class)->withPivot('orden')->orderBy('orden', 'asc');
    }

    public function entradas(){
        return $this->belongsToMany(Entrada::class);
    }
}