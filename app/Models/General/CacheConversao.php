<?php

namespace App\Models\General;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;




class CacheConversao extends Model
{
    protected $table = 'cache_conversao';
    protected $dates = ['ref_date'];

    public function usuario()
    {

        return $this->belongsTo(User::class, 'usuario_id', 'id');

    }





}
