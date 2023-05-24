<?php 
//Configurando o projeto "Ecommerce"
//banco mysql - workbench 8

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
//implementação RESTful, criação de APIs de pequeno ou médio porte.
$app = new Slim();

$app->config('debug', true);

//ROTAS

//qual a rota que estou chamando '/'
$app->get('/', function() { 
    
	//chama o construtor

	$page = new Page();

	//adiciona o arquivo h1 (index)
	$page->setTpl("index"); 
});

//rota para a administração (admin)
$app->get('/admin', function() {

	//pra saber se está logado
	//:: metodo estatico
	//User::verifyLogin();
    
	$page = new PageAdmin();

	//adiciona o arquivo h1 (index)
	$page->setTpl("index"); 
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

	//chamando o template que foi criado no login.html
	$page->setTpl("login");
});

//criando uma rota post para o login
$app->post('/admin/login', function(){

	//validando o login
	//User -> model
	User::login($_POST["login"], $_POST["password"]);

	//se nao der erro, redireciona a pagina
	header("Location: /admin");
	exit; //para parar a execucao

});

//rota para o logout
$app->get('/admin/logout', function() {

	User::logout();

	//redireciona para a tela de login
	header("Location: /admin/login");
	exit;
});

//rota para listar todos os usuários
$app -> get("/admin/users/", function() {

	User::verifyLogin();

	//rendenrizar
	$users = User::listAll();

	$page = new PageAdmin();

	//chamando o template que foi criado no login.html
	$page->setTpl("users", array(
		"users"=>$users
	));

});

//rota para o create (criar usuário)
$app->get("/admin/users/create", function () { 

	//verifica se  o usuário está logado na aplicação
	User::verifyLogin(); 

	$page = new PageAdmin(); 

	$page->setTpl("users-create"); 
});

//rota para excluir usuário
$app->get("/admin/users/:iduser/delete", function($iduser){

	User::verifyLogin();

	$user = new User();

	//carrega o usuário
	$user->get((int)$iduser);

	$user->delete();

	header("Location: /admin/users");
	exit;

});

//rota para o update (atualizar usuário)
//iduser -> usuário em especifico para atualizar
$app ->get("/admin/users/:iduser", function($iduser) {

	User::verifyLogin();

	$user = new User();

	//carrega o usuário
	$user->get((int)$iduser);

	$page = new PageAdmin();

	//chamando o template que foi criado no login.html
	$page->setTpl("users-update", array(
		"user"=>$user->getValues()
	 ));

});

//rota para salvar o usuário que foi criado
$app->post("/admin/users/create", function(){

	User::verifyLogin();

	$user = new User();

	//se o inadmin (administrador) já foi definido
	//caso sim, o valor é 1 , caso não o valor é 0
	$_POST["inadmin"] = (isset($_POST["inadmin"]))? 1:0;

	//password_hash -> cria um novo hash de senha
	$_POST['despassword'] = password_hash($_POST["despassword"], PASSWORD_DEFAULT, [

		"cost"=>12

	]);

	$user->setData($_POST);

	//save -> executar o insert no BD
	$user->save();

	//visualizar na tabela
	header("Location: /admin/users");
	exit;
});

//rota para salvar a edição do usuário (update)
$app->post("/admin/users/:iduser", function($iduser) {

    User::verifyLogin();

    $user = new User();

	//fazendo a validação
	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

	//carregando os usuários (dados)
    $user->get((int)$iduser);

    $user->setData($_POST);

    $user->update();

    header("Location: /admin/users");
    exit;
});


$app->run(); //depois que tudo é carregado, é executado (roda a aplicacao)


//composer.json: lista as dependências do projeto e suas versões
//composer.lock: O lock é criado toda vez que é instalado ou atualizado o composer.

 ?>