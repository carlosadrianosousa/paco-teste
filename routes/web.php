<?php

use Illuminate\Support\Facades\Route;
//use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', function () {
    if (Auth::check()){
        //return view('home');
        return redirect('/');
    }

    return view('auth.login');

});


Route::get('', [
    'as' => 'login',
    'uses' => 'Auth\LoginController@showLoginForm'
]);
Route::post('Login', [
    'as' => '',
    'uses' => 'Auth\LoginController@login'
]);
Route::get('logout', [
    'as' => 'logout',
    'uses' => 'Auth\LoginController@logout'
]);

//DEMAIS ROTAS DA APLICAÇÃO
//COMO A APLICAÇÃO É PEQUENA, TODAS AS ROTAS FORAM COLOCADAS NESTE ARQUIVO
/**Perfil de Usuário**/


Route::group(['middleware' => ['auth']], function () {

    //CADASTRO DE PERFIL DE USUÁRIO
    route::post('PerfilUsuario/Grid', ['as' => 'perfil_usuario.GridView', 'uses' => 'General\PerfilUsuarioController@GridView']);
    route::post('PerfilUsuario/NovoForm', ['as' => 'perfil_usuario.AddView', 'uses' => 'General\PerfilUsuarioController@AddView']);
    route::post('PerfilUsuario/EditarForm/{id}', ['as' => 'perfil_usuario.EditView', 'uses' => 'General\PerfilUsuarioController@EditView']);
    route::post('PerfilUsuario/VisualizarForm/{id}', ['as' => 'perfil_usuario.View', 'uses' => 'General\PerfilUsuarioController@View']);
    route::get('PerfilUsuario', ['as' => 'perfil_usuario.listar', 'uses' => 'General\PerfilUsuarioController@listar']);
    route::post('PerfilUsuario', ['as' => 'perfil_usuario.store', 'uses' => 'General\PerfilUsuarioController@store']);
    route::put('PerfilUsuario/{id}', ['as' => 'perfil_usuario.update', 'uses' => 'General\PerfilUsuarioController@update']);
    route::delete('PerfilUsuario/{id}', ['as' => 'perfil_usuario.destroy', 'uses' => 'General\PerfilUsuarioController@destroy']);


    //CADASTRO DE USUÁRIO
    route::post('Usuario/Grid', ['as' => 'usuario.GridView', 'uses' => 'General\UsuarioController@GridView']);
    route::post('Usuario/NovoForm', ['as' => 'usuario.AddView', 'uses' => 'General\UsuarioController@AddView']);
    route::post('Usuario/EditarForm/{id}', ['as' => 'usuario.EditView', 'uses' => 'General\UsuarioController@EditView']);
    route::post('Usuario/VisualizarForm/{id}', ['as' => 'usuario.View', 'uses' => 'General\UsuarioController@View']);
    route::get('Usuario', ['as' => 'usuario.listar', 'uses' => 'General\UsuarioController@listar']);
    route::post('Usuario', ['as' => 'usuario.store', 'uses' => 'General\UsuarioController@store']);
    route::put('Usuario/{id}', ['as' => 'usuario.update', 'uses' => 'General\UsuarioController@update']);
    route::delete('Usuario/{id}', ['as' => 'usuario.destroy', 'uses' => 'General\UsuarioController@destroy']);

});
