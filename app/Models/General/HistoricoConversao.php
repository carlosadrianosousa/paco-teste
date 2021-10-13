<?php

namespace App\Models\General;
use App\User;
use Illuminate\Database\Eloquent\Model;
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





}
