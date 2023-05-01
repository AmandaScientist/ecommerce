<?php 
//Configurando o projeto "loja virtual"
//banco mysql - workbench

//configuração do 'slim	'

//inicia o uso de sessões
session_start();

//require_once () -> requer_uma vez. O PHP irá verificar se o arquivo já foi incluído, e se sim,
//não inclui (require) novamente.
require_once("vendor/autoload.php");

use \Slim\Slim; //use eh namespace
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;

//Slim framework -> é um micro-framework bastante leve e prático, possui como principal característica a 
//implementação RESTful, facilita a criação de APIs de pequeno ou médio porte de maneira organizada
$app = new Slim();

$app->config('debug', true);

$app->get('/', function() { //qual eh a rota q estou chamando /
    
	//criacao da variavel
	//chama o construtor
	$page = new Page();

	$page->setTpl("index"); //adiciona o arquivo h1 (index)
});

//rota da administração
$app->get('/admin', function() { //qual eh a rota q estou chamando /

	//pra saber s estah logado
	//:: metodo estatico
	User::verifyLogin();
    
	//criacao da variavel
	//chama o construtor
	$page = new PageAdmin();

	$page->setTpl("index"); //adiciona o arquivo h1 (index)
});

//rota para o login
//acessa via get
$app->get('/admin/login', function(){

	//bloco da função. Criar a pagina
	//chama o método construtor
	//uma nova pagina do admin
	//desabilitando o header e o footer padrao
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	//chamando o template q foi criado no login.html
	$page->setTpl("login");

});

//criando uma rota post
$app->post('/admin/login', function(){

	//validando o login
	//User -> model
	User::login($_POST["login"], $_POST["password"]);

	//se nao der erro, redireciona a pagina
	header("Location: /admin");
	exit; //para parar a execucao


});

//rota do logout
$app->get('/admin/logout', function() {

	User::logout();

	//redireciona para a tela de login
	header("Location: /admin/login");
	exit;
});

$app->run(); //depois 	q tudo carrega, ai eh executado (roda a aplicacao)



//composer.json:
//Esse arquivo é responsável por conter todas as dependências do projeto e suas versões.
//lista as dependências do projeto e suas versões

//composer.lock:
//Este ficheiro é importante para o composer saber as versões específicas dos packages q foram instaladas.
//O lock é criado toda vez q é instalado ou atualizado o composer.
 ?>