<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Models\General\CacheConversao;
use App\Models\General\HistoricoConversao;
use App\Models\General\Moeda;
use App\Models\General\PerfilUsuario;
use App\Models\Utils\SearchUtils;
use App\User;
use Carbon\Carbon;
use Composer\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class ConversaoController extends Controller
{

    //Url para checagem de token
    protected $check_api_url = "http://api.exchangeratesapi.io/v1/symbols";
    protected $convert_api_history = "http://api.exchangeratesapi.io/v1";
    protected $convert_api_latest = "http://api.exchangeratesapi.io/v1/latest";
    protected $allowed_currencies = ['USD','BRL','CAD'];


    //No caso, este controller possui apenas um único formulário!
    public function FormView()
    {

        $user = Auth::user();
        $cur_date = new Carbon();
        $moedas = Moeda::all();
        $cache_info = HistoricoConversao::getCacheInfo($user->id);


        return view('general.conversao_moeda.ConversaoMoedaForm', [
            'user' => $user,
            'cur_date' => $cur_date,
            'moedas' => $moedas,
            'cache_info' => $cache_info
        ]);
    }

    public function checkAPI(Request $request){

        $this->validate($request,[
            'exchange_api_key' => 'present'
        ]);


        if (!$request->exchange_api_key && !Auth::user()->exchange_api_key)
            return response()->json(['success' => false,'message' => 'Nenhuma chave de API foi informada e não existe uma chave de API salva na base de dados para este usuário. COD.: 3XMK'],400);


        $api_key = empty((string)trim($request->exchange_api_key))?Crypt::decrypt(Auth::user()->exchange_api_key):$request->exchange_api_key;


        $response = Http::withOptions([
            'timeout' => 20,
        ])->get($this->check_api_url."?access_key=$api_key");

        return $response;

    }


    public function convert(Request $request)
    {

        $this->validate($request,[
            'ref_date' => 'required|date_format:"d/m/Y"',
            'moeda_origem' => 'required|string|in:BRL,CAD,USD',
            'valor_origem' => 'required|numeric|required|min:0.01',
            'moeda_destino' => 'required|string|in:BRL,CAD,USD',
            'cache' => 'boolean'
        ]);

        if ($request->moeda_origem == $request->moeda_destino)
            return response()->json(['success' => false,'message' => 'Moeda de Origem e Destino são indênticas. COD.: MU12'],400);

        //PARTE-SE DO PRINCÍPIO QUE EXISTE CHAVE DE API - REVER ISSO!

        $api_key = Crypt::decrypt(Auth::user()->exchange_api_key);


        //Recupera-se a data do servidor
        $now_carbon = Carbon::now()->startOfDay();
        $ref_date = Carbon::createFromFormat('d/m/Y', $request->ref_date)->startOfDay();

        //Caso data da consulta seja posterior à data atual, ERRO
        if ($ref_date->gt($now_carbon))
            return response()->json(['success' => false,'message' => 'Data e conversão é maior que data atual. B49Z'],400);

        $now_unix_timestamp = time();//Timestamp UNIX, com precisão de segundo
        $allowed_currencies_str = implode(',',$this->allowed_currencies);

        //data de Referência, formato Y-m-d
        $ref_date_str = $ref_date->format('Y-m-d');

        $use_cache = !empty($request->cache)?$request->cache:0;

        $cache_obj = CacheConversao::where("ref_date",'=',$ref_date->format('Y-m-d'))
                        ->where('api_timestamp','<=',$now_unix_timestamp)
                        ->first();

        $cached_info = ($cache_obj && $use_cache)?1:0; //Verifica se as informações vieram do Cache

        //Se não tiver que utilizar cache, SEMPRE realize a busca via API
        //IMPORTANTE! - INICIALIZA-SE A TRANSAÇÃO
        DB::beginTransaction();
        try {

            if (!$use_cache || !$cache_obj){


                //Se a data de referência for igual a data do servidor, endpoint LATEST
                //Caso contrátio, endpoins HISTORY
                if ($ref_date->equalTo($now_carbon)){
                    $api_endpoint = "http://api.exchangeratesapi.io/v1/latest?access_key=$api_key&symbols=$allowed_currencies_str";
                }else{
                    $api_endpoint = "http://api.exchangeratesapi.io/v1/$ref_date_str?access_key=$api_key&symbols=$allowed_currencies_str";
                }

                $response = Http::withOptions([
                    'timeout' => 20,
                ])->get($api_endpoint);

                $body_resp = json_decode($response->getBody(), true);

                if ($response->status() != 200)
                    return response()->json(['success' => false,'message' => $body_resp['error']['message'],$response->status()]);

                //Caso a requisição tenha sido realizada com sucesso, o cache deve ser salvo

                if (!$cache_obj)
                    $cache_obj = new CacheConversao();

                $rates = $body_resp['rates'];

                $cache_obj->ref_date = $ref_date_str;
                $cache_obj->usuario_id = Auth::user()->id;
                $cache_obj->valor_usd = 1; //Na base de dados, a moeda base é sempre Dólar Estadunidense
                $cache_obj->valor_brl = $rates['USD'] / $rates['BRL'];
                $cache_obj->valor_cad = $rates['USD'] / $rates['CAD'];
                $cache_obj->api_timestamp = $body_resp['timestamp'];
                $cache_obj->save();

                //Recupera-se o objeto novamente simplesmente para se recuperar com exatas 6 casas decimais
                $cache_obj = CacheConversao::find($cache_obj->id);



            }


            //Persiste-se o histórico de fato
            $historico = new HistoricoConversao();
            $historico->ref_date = $ref_date_str;
            $historico->usuario_id = Auth::user()->id;
            $historico->moeda_origem_id = $request->moeda_origem;
            $historico->valor_origem = $request->valor_origem;
            $historico->moeda_destino_id = $request->moeda_destino;

            //Atenção
            //Na aplicação, a base é sempre dólar!
            $historico->valor_destino = $request->valor_origem * ($cache_obj['valor_'.mb_strtolower($request->moeda_origem)] / $cache_obj['valor_'.mb_strtolower($request->moeda_destino)]);

            /*if ($request->moeda_origem != 'USD'){
                $historico->valor_destino = $request->valor_origem * ($cache_obj['valor_'.mb_strtolower($request->moeda_origem)] / $cache_obj['valor_'.mb_strtolower($request->moeda_destino)]);
            }else{
                $historico->valor_destino = $request->valor_origem * ( 1.000000 / $cache_obj['valor_'.mb_strtolower($request->moeda_destino)]);
            }*/

            $historico->cached = $cached_info;
            $historico->api_timestamp = $now_unix_timestamp;
            $historico->save();

            //Objeto de Retorno com informações da conversão

            $return_obj = new \stdClass();
            $return_obj->cache = HistoricoConversao::getCacheInfo(Auth::user()->id);
            $return_obj->valor_conversao = $historico->valor_destino;


        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);

        }

        DB::commit();
        return response()->json(['success' => true, 'message' => 'OK','data' => $return_obj]);
    }



    public function listar(Request $request)
    {



        $query = DB::table('historico_conversao AS HIST')
            ->selectRaw("
                HIST.id,
                HIST.ref_date, DATE_FORMAT(HIST.ref_date,'%d/%m/%Y') as ref_date_masked,
                HIST.moeda_origem_id, HIST.valor_origem, HIST.moeda_destino_id, HIST.valor_destino,
                HIST.cached,
                IF (HIST.cached IS TRUE,'SIM','NÃO') as cached_escrito,
                HIST.created_at, DATE_FORMAT(HIST.created_at,'%d/%m/%Y %H:%i:%s') as created_at_masked")
            ->join('users AS USU','HIST.usuario_id','=','USU.id')
            ->where('HIST.usuario_id','=',Auth::user()->id)
            ->orderBy('created_at','DESC');


        $query = SearchUtils::createQuery($request, $query, 'having');

        return $query->get();
    }
}
