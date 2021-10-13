<?php

use Illuminate\Database\Seeder;

class initSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Insere-se os perfis de usuários
        \Illuminate\Support\Facades\DB::unprepared("
            INSERT INTO perfil_usuario (id, nome, descricao, super) VALUES
            (1,'Administrador','Superusuário do Sistema, não editável e não removível.',1),
            (2,'Usuário Comum','Usuário comum do sistema, para fins de testes.',0)
        ");

        //Inserem-se dois usuários: um administrador e um comum.
        $user = new App\User();
        $user->id = 1;
        $user->name = 'Administrador - Grupo PACO';
        $user->password = \Illuminate\Support\Facades\Hash::make('123456');
        $user->email = 'paco@teste.com';
        $user->ativo = 1;
        $user->perfil_id = 1;
        $user->save();

        $user = new App\User();
        $user->id = 2;
        $user->name = 'Carlos Adriano Sousa Silva';
        $user->password = \Illuminate\Support\Facades\Hash::make('123456');
        $user->email = 'carlos@teste.com';
        $user->ativo = 1;
        $user->perfil_id = 2;
        $user->save();

        //Popula-se as tabelas de moeda
        \Illuminate\Support\Facades\DB::unprepared("
            INSERT INTO moeda(id,descricao) VALUES
            ('USD','Dólar Estadunidense'),
            ('BRL','Real Brasileiro'),
            ('CAD','Dólar Canadense');
        ");
    }
}
