<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Models\General\HistoricoConversao;
use App\Models\General\Moeda;
use App\Models\General\PerfilUsuario;
use App\Models\Utils\SearchUtils;
use App\User;
use Carbon\Carbon;
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


        if (!$request->exchange_api_key && !Auth::user()->exchange_api_key)
            return response()->json(['success' => false,'message' => 'Nenhuma chave de API foi informada e não existe uma chave de API salva na base de dados para este usuário. COD.: 3XMK'],400);

        $api_key = (!$request->exchange_api_key)?Crypt::decrypt(Auth::user()->exchange_api_key):$request->exchange_api_key;


        $response = Http::withOptions([
            'timeout' => 20,
        ])->get($this->check_api_url."?access_key=$api_key");

        return $response;



    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //IMPORTANTE! - INICIALIZA-SE A TRANSAÇÃO
        DB::beginTransaction();
        try {

            $obj = new PerfilUsuario();

            $obj->nome = $request->nome;
            $obj->descricao = ($request->descricao)?$request->descricao:"";
            $obj->save();


        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);

        }

        DB::commit();
        return response()->json(['success' => true, 'message' => 'Registro Cadastrado com Sucesso!']);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //IMPORTANTE! - INICIALIZA-SE A TRANSAÇÃO
        DB::beginTransaction();
        try {

            $obj = PerfilUsuario::find($id);

            $obj->nome = $request->nome;
            $obj->descricao = ($request->descricao)?$request->descricao:"";
            $obj->save();


        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()],400);

        }

        DB::commit();
        return response()->json(['success' => true, 'message' => 'Registro Editado com Sucesso!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        $obj = PerfilUsuario::find($id);

        if ($obj->super){
            return response()->json(['success' => false,'message' => 'O perfil de SuperUsuário NÃO pode ser apagado'],400);
        }

        try {
            $obj->delete();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'O registro foi deletado com sucesso !']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Não foi possível deletar o registro, verifique se existe algum impedimento no cadastro.'],400);
        }
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
                HIST.created_at, DATE_FORMAT(HIST.created_at,'%d/%m/%Y H:i:s') as created_at_masked")
            ->join('users AS USU','HIST.usuario_id','=','USU.id')
            ->where('HIST.usuario_id','=',Auth::user()->id)
            ->orderBy('created_at','DESC');


        $query = SearchUtils::createQuery($request, $query, 'having');

        return $query->get();
    }
}
