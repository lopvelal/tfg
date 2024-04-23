<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservasUsuario extends Model
{
    use HasFactory;

    protected $fillable = ['orden', 'user_id', 'reserva_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function reserva(){
        return $this->belongsTo(Reserva::class);
    }
}
