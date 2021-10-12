<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Models\General\PerfilUsuario;
use App\Models\Utils\SearchUtils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PerfilUsuarioController extends Controller
{


    public function GridView()
    {
        return view('general.perfil_usuario.PerfilUsuarioGrid', [

        ]);
    }


    public function AddView()
    {

        return view('general.perfil_usuario.PerfilUsuarioForm', [
            'action' => 'add'
        ]);
    }

    public function EditView($id)
    {

        $perfil = PerfilUsuario::find($id);

        if ($perfil->super){
            return response()->json(['success' => false,'message' => 'O perfil de SuperUsuário NÃO pode ser editado'],400);
        }


        return view('general.perfil_usuario.PerfilUsuarioForm', [
            'action' => 'edit',
            'perfil' => $perfil

        ]);
    }


    public function View($id)
    {

        $perfil = PerfilUsuario::find($id);
        return view('general.perfil_usuario.PerfilUsuarioForm', [
            'action' => 'view',
            'perfil' => $perfil

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

        $query = DB::table('perfil_usuario')
            ->selectRaw('id, nome, descricao')
            ->orderBy('id');

        $query = SearchUtils::createQuery($request, $query, 'having');



        return $query->get();
    }
}
