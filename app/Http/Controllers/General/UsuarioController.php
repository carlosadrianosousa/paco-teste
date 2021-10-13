<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Models\General\PerfilUsuario;
use App\Models\Utils\SearchUtils;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{


    public function GridView()
    {
        return view('general.usuario.UsuarioGrid', [

        ]);
    }


    public function AddView()
    {
        $perfis = PerfilUsuario::all();
        return view('general.usuario.UsuarioForm', [
            'action' => 'add',
            'perfis' => $perfis
        ]);
    }

    public function EditView($id)
    {

        $usuario = User::find($id);


        if ($usuario->id == 1 && \Auth::user()->id != 1){
            return response()->json(['success' => false,'message' => 'Este usuário NÃO pode ser editado!']);
        }
        $perfis = PerfilUsuario::all();
        return view('general.usuario.UsuarioForm', [
            'action' => 'edit',
            'usuario' => $usuario,
            'perfis' => $perfis

        ]);
    }


    public function View($id)
    {

        $usuario = User::find($id);
        $perfis = PerfilUsuario::all();
        return view('general.usuario.UsuarioForm', [
            'action' => 'view',
            'usuario' => $usuario,
            'perfis' => $perfis

        ]);
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
        if (!$request->senha){
            return response()->json(['success' => false, 'message' => 'Informe a senha.']);
        }

        if ($request->senha != $request->senha_confirm){
            return response()->json(['success' => false, 'message' => 'As senhas não conferem. Por favor, informe a nova senha.']);
        }

        $tmp = User::whereEmail($request->email)->first();
        if ($tmp){
            return response()->json(['success' => false, 'message' => 'O email informado pertence à outro usuário.'],400);
        }

        //IMPORTANTE! - INICIALIZA-SE A TRANSAÇÃO
        DB::beginTransaction();
        try {

            $obj = new User();

            $obj->name = $request->nome;
            $obj->password = Hash::make($request->senha);
            $obj->email = $request->email;
            $obj->ativo = 1;
            $obj->perfil_id = $request->perfil_id;
            $obj->ativo = $request->ativo;

            if ($request->exchange_api_key)
                $obj->exchange_api_key = Crypt::encrypt($request->exchange_api_key);

            $obj->save();


        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);

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
        if ($request->senha && $request->senha != $request->senha_confirm){
            return response()->json(['success' => false, 'message' => 'As senhas não conferem. Por favor, informe a nova senha.'],400);
        }

        $tmp = User::whereEmail($request->email)->first();
        if ($tmp && $tmp->id != $id){
            return response()->json(['success' => false, 'message' => 'O email informado pertence à outro usuário.'],400);
        }

        if ($tmp->id == 1 && !$request->ativo)
            return response()->json(['success' => false, 'message' => 'Você não pode inativar este usuário'],400);

        //IMPORTANTE! - INICIALIZA-SE A TRANSAÇÃO
        DB::beginTransaction();
        try {

            $obj = User::find($id);

            $obj->name = $request->nome;

            if ($request->senha){
                $obj->password = Hash::make($request->senha);
            }

            $obj->email = $request->email;
            $obj->perfil_id = $request->perfil_id;
            $obj->ativo = $request->ativo;

            if ($request->exchange_api_key)
                $obj->exchange_api_key = Crypt::encrypt($request->exchange_api_key);

            $obj->save();


        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()],500);

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

        $obj = User::find($id);

        if ($obj->id == 1){
            return response()->json(['success' => false,'message' => 'Este usuário NÃO pode ser apagado!'],400);
        }

        if ($obj->id == Auth::user()->id){
            return response()->json(['success' => false,'message' => 'Este usuário está logado no sistema. O mesmo não pode ser apagado.'],400);
        }



        try {
            $obj->delete();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'O registro foi deletado com sucesso !']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Não foi possível deletar o registro, verifique se existe algum impedimento no cadastro.'],500);
        }
    }

    public function listar(Request $request)
    {

        $query = DB::table('users AS USU')
            ->selectRaw("
                    USU.id, USU.name as nome_usuario, USU.email,PU.nome as nome_perfil,
                    USU.ativo, IF(USU.ativo = 1,'Ativo','Inativo') as ativo_escrito
                ")
            ->join('perfil_usuario AS PU','USU.perfil_id','=','PU.id')
            ->orderBy('id');

        $query = SearchUtils::createQuery($request, $query, 'having');

        return $query->get();
    }
}
