<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Associacao extends Authenticatable
{
  #LEMBRA Q ISSO TEM SENHAAAAAAA

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'celular',
        'logo',
    ];

    public static $regras_validacao_criar = [
        'celular' => 'nullable|numeric|min:10',
    ];

    public static $regras_validacao_editar = [
        'celular' => 'nullable|numeric|min:10',
        'logo_associacao' => 'nullable|file|mimes:png|max:2048'
    ];

    public function user() {
        return $this->hasOne('\App\Models\User', 'id', 'user_id');
    }

    public function ocs(){
        return $this->hasMany('App\Models\Ocs', 'associacao_id');
    }

}
