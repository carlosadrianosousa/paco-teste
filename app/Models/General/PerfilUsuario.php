<?php

namespace App\Models\General;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class PerfilUsuario extends Model
{
    protected $table = 'perfil_usuario';

    public function usuarios()
    {

        return $this->hasMany(User::class, 'perfil_id', 'id');

    }





}
