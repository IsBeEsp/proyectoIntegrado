<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    use HasFactory;
    protected $fillable = ['nombre', 'color', 'user_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function paginas(){
        return $this->hasMany(Pagina::class);
    }

    public function tags(){
        return $this->hasMany(Tag::class);
    }
}
