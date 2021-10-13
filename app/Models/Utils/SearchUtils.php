<?php

/*
 *
 * @author Carlos Adriano Sousa Silva (carlos.adriano.sousa.silva@hotmail.com)
 */


namespace App\Models\Utils;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Validation\ValidatesRequests;


class SearchUtils
{


    /**
     * Método Principal da classe
     * Retorna a Query de acordo com os parâmetros de busca
     * @param Request $request
     * @param type $db
     * @return type
     */
    public static function createQuery(Request $request, $db, $method = 'where'){


        $method = mb_strtoupper($method);




        if ($request->input('request')){

            //Busca Automática
            $search = $request->input('request');
            $search = json_decode($search,false);

            //Tratamento da limitação
            if (isset($search->limit))
                $db->limit((int)$search->limit);

            if (isset($search->offset))
                $db->offset((int)$search->offset);

            if (!isset($search->search))
                return $db;

            if (isset($search->search)){

                foreach ($search->search as $s) {

                    $data = self::validateFieldTypeValue($s->field,$s->type,$s->value);

                    if (!$data){
                        continue;
                    }

                    //Repassa os parâmetros para $s
                    $s->field = $data['field'];
                    $s->type = $data['type'];
                    $s->value = $data['value'];

                    //echo $s->value;


                    switch ($s->operator) {
                        case 'is':{

                            if ($method == 'WHERE'){
                                $db->where($s->field, '=', $s->value);
                            }else{
                                $db->having($s->field, '=', $s->value);
                            }
                        break;}
                        case 'more': {
                            if ($method == 'WHERE'){
                                $db->where($s->field, '>', $s->value);
                            }else{
                                $db->having($s->field, '>', $s->value);
                            }

                            break;}
                        case 'less': {
                            if ($method == 'WHERE'){
                                $db->where($s->field, '<', $s->value);
                            }else{
                                $db->having($s->field, '<', $s->value);
                            }
                            break;}
                        case 'between':{
                            if ($method == 'WHERE'){
                                $db->whereBetween($s->field, $s->value);
                            }else{
                                $db->havingRaw($s->field.' >= "'. $s->value[0].'" AND '.$s->field.' <= "'.$s->value[1].'"');
                            }

                            break;}
                        case 'begins':{
                            if ($method == 'WHERE'){
                                $db->where($s->field, 'like', (string)("".$s->value."%"));
                            }else{
                                $db->having($s->field, 'like', (string)("".$s->value."%"));
                            }

                            break;}
                        case 'contains': {
                            if ($method == 'WHERE'){
                                $db->where($s->field, 'like', (string)("%".$s->value."%"));
                            }else{
                                $db->having($s->field, 'like', (string)("%".$s->value."%"));
                            }
                            break;}
                        case 'ends': {
                            if ($method == 'WHERE'){
                                $db->where($s->field, 'like', (string)("%".$s->value.""));
                            }else{
                                $db->having($s->field, 'like', (string)("%".$s->value.""));
                            }
                            break;}
                        default: break;



                    }

                    //echo($db->toSql())."<br>";


                }

            }


            /*ORDENAÇÃO AUTOMÁTICA ESTÁ FUNCIONADO
            SÓ DESCOMENTAR O CÓDIGO ABAIXO, ATÉ ANTES DO RETURN
            SUPORTE INCLUSIVE A ORDENAÇÃO POR MÚLTIPLAS COLUNAS
            */


            //Ordenação Automática
            $sort = $request->input('request');
            $sort = json_decode($sort,false);

            if (isset($sort->sort)){


                //FAZ A ORDENAÇÃO, CASO EXISTAM OS PARÂMETROS DE ORDENAÇÃO
                if (isset($db->orders) && sizeof($db->orders)){
                    $db->orders = null;
                }

                //Condicional Para Eloquent Builder Illuminate\Database\Eloquent\Builder
                if (method_exists($db,'getQuery')){
                    $db->getQuery()->orders = null;
                }

                foreach ($sort->sort as $st){
                    $db->orderBy($st->field, $st->direction);
                }


            }



            return $db;

        }

    }

    /**
     * Traduz os campos do Plugin e Busca para tipos de regra de validação do laravel.
     * @param type $type
     * @return boolean|string
     */
    private static function translateType($type){

        switch ($type) {
            case 'text': return 'string'; break;
            case 'alphanumeric': 'alpha'; break;
            case 'int': return 'integer'; break;
            case 'integer': return 'integer'; break;
            case 'numeric': return 'numeric'; break;
            case 'float': return 'numeric'; break;
            case 'money': return 'numeric'; break;
            case 'currency': return 'numeric'; break;
            case 'percent': return 'numeric'; break;

            case 'date': return 'date'; break;
            case 'time': return 'time'; break;
            case 'list': return 'list'; break;

            //Abaixo estão os Tipos sem Suporte
            case 'combo': return false; break;
            case 'enum': return false; break;
            case 'hex': return false; break;
            default: return false; break;

        }

    }

    /**
     * Valida o campo, tipo do campo e o valor
     * FUNCIONALIDADE INCOMPLETA
     * @param type $field
     * @param type $type
     * @param type $value
     * @return boolean
     */
    private static function validateFieldTypeValue ($field,$type,$value){


        if (!isset($field) || !isset($type)){
            return false;
        }


        $type = self::translateType($type);

        if (!$type){
            //Tipo Inválido!
            return false;
        }
        //Trata as exceções de data e hora

        //Se for formato de Data
        if ($type == 'date'){
            $type = 'date_format:"Y-m-d"';



            if (is_array($value)){
                $value[0] = \DateTime::createFromFormat('d/m/Y', $value[0]);
                $value[1] = \DateTime::createFromFormat('d/m/Y', $value[1]);

                if ($value[0] && $value[1] ) {
                    $value[0] = $value[0]->format('Y-m-d');
                    $value[1] = $value[1]->format('Y-m-d');
                }


            }else{
                $value = \DateTime::createFromFormat('d/m/Y', $value);

                if ($value) {
                    $value = $value->format('Y-m-d');
                }

            }





        }


        //Se for formato de HORA
        if ($type == 'time'){
            $type = 'date_format:"H:i:s"';

            $value = \DateTime::createFromFormat('H:i:s', $value);

            if ($value) {
                $value = $value->format('H;i:s');
            } else {
                return false;
            }
        }

        //Se o formato for LIST
        if ($type == 'list'){

            $value = explode('|', $value);

            if (sizeof($value) != 2){

                return false;
            }

            $type = $value[1];
            $value = $value[0];

            if ($type != 'list'){
                $type = self::translateType($type);
            }else{
                return false;
            }



        }






        //Cria o Array de Regras
        $rules = [
                'value.*' => "|required|".$type

        ];

        //Cria o Array de Inputs
        $input = ['value' => $field];


        if (\Validator::make($input, $rules)->passes()){
            return array('field'=>$field, 'type'=>$type,'value' => $value);
        }

        //return array('field'=>$field, 'type'=>$type);

        return false;



    }



}







