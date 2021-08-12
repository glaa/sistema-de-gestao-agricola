<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reuniao extends Model
{
    protected $fillable = [
        'participantes',
        'ata',
    ];

    public static $rules = [
        //Para o app o campo participantes não é obrigatório
        'participantes' => 'nullable',
        'ata' => 'required',
    ];

    public function agendamentoReuniao(){
        return $this->belongsTo('App\Models\AgendamentoReuniao');
    }

    public function fotosReuniao(){
        return $this->hasMany('\App\Models\FotosReuniao', 'reuniao_id', 'id');
    }

    public function retificacao(){
        return $this->hasMany('\App\Models\Retificacao', 'reuniao_id', 'id');
    }
}
