<?php

namespace App\Models\General;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;




class Moeda extends Model
{
    protected $table = 'moeda';
    protected $keyType = 'string';

    public function historicos()
    {

        return $this->hasMany(User::class, 'perfil_id', 'id');

    }





}
