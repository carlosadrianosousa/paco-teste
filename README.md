<p align="center">
  <img width="200" height="190" src="https://i.imgur.com/38nWF8X.jpg">
</p>

# Sistema com integração à API Conversão Monetária - Grupo Paco
Respositório para teste prático.

**Ferramentas Utilizadas Backend**

* Framework Laravel versão 7.30.4
* Base de Dados MariaDB 10.4.11 (Compatível com MySQL)

**Ferramentas Utilizadas Front-end**

* Bootstrap v4.1.3
* jQuery v3.6.0
* w2ui 1.5.rc1 (nightly)
* Demais componentes de terceiros para datepicker, máscaras de input e afins

**REQUISITOS DA APLICAÇÃO**

Conforme o solicitado, a aplicação deve:

* Possuir front-end confeccionado, preferencialmente, em Laravel
* Base de dados MySQL
* Possuir front-end em Bootstrap, HTML5 e CSS
* Sistema de Login
* Controle de Sessão
* Utilização da API Exchange Rates (https://exchangeratesapi.io) para a conversão das moedas BRL, USD e CAD.
* Armazenamento de Histórico das Conversões, com paginação.
* Demais possibilidades, à critério do desenvolvedor.

**DA REALIZAÇÃO DAS ATIVIDADES**

Conforme o solicitado, foi realizado:

* Sistema de Login (e-mail e senha)
* Gerenciamento de Usuários na própria aplicação
* Gerenciamento de Perfis de usuário (Perfil administrador e usuário comum)
* Controle de Sessão
* Gerenciamento de permissões através de Gates
* Gerenciamento de Chaves de API, armazenadas com Criptografia em base de dados
* Criação de sistema de Cache para economia de requisições, visto que a API possui planos pagos.
* Possibilidade de conversão monetária mesmo sem chave de API (baseada em cache)
* Armazenamento de histórico de conversões, com infinite scroll
* Backend confeccionado em Laravel
* Base de dados MariaDB (Compatível com MySQL)
* Front-end confeccionado em Boostrap 4, HTML5 e CSS
* Sistema parcialmente responsivo, funcional nos browsers: Google Chrome, Ópera e Firefox.

**USUÁRIOS PADRÕES**

Após a execução do seed (php artisan db:seed), dois usuários são criados, um com superprivilégios e outro sem:

* Super -  user: paco@teste.com - pass: 123456
* Normal - user: carlos@teste.com - pass: 123456

