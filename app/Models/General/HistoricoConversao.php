<?php

namespace App\Models\General;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;




class HistoricoConversao extends Model
{
    protected $table = 'historico_conversao';
    protected $dates = ['ref_date'];

    public function moeda_origem()
    {
        return $this->belongsTo(Moeda::class, 'moeda_origem_id', 'id');
    }

    public function moeda_destino()
    {
        return $this->belongsTo(Moeda::class, 'moeda_destino_id', 'id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'id');
    }


    public static function getCacheInfo($user_id = 0){
        $user_id = !$user_id?Auth::user()->id:$user_id;

        $query = collect(DB::select("
            SELECT
            COUNT(CASE WHEN cached IS TRUE THEN cached END) as cached,
            COUNT(CASE WHEN cached IS FALSE THEN cached END) as not_cached
            FROM historico_conversao
            WHERE usuario_id = ?
        ",[$user_id]))->first();

        return $query;
    }





}
