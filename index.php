<?php 
//Configurando o projeto "loja virtual"
//banco mysql - workbench

//require_once () -> requer_uma vez. O PHP irá verificar se o arquivo já foi incluído, e se sim,
//não inclui (require) novamente.
require_once("vendor/autoload.php");

//Slim framework -> é um micro-framework bastante leve e prático, possui como principal característica a 
//implementação RESTful, facilita a criação de APIs de pequeno ou médio porte de maneira organizada
$app = new \Slim\Slim();

$app->config('debug', true);

$app->get('/', function() {
    
	//criando a classe sql
	// a barra investida '\' vem do namespace
	$sql = new Hcode\DB\Sql();

	//executando uma quary (consulta)
	$results = $sql->select("SELECT * FROM tb_users"); //tabela no banco

	//exibe na tela
	echo json_encode($results);

});

$app->run();

//composer.json:
//Esse arquivo é responsável por conter todas as dependências do projeto e suas versões.
//lista as dependências do projeto e suas versões

//composer.lock:
//Este ficheiro é importante para o composer saber as versões específicas dos packages q foram instaladas.
//O lock é criado toda vez q é instalado ou atualizado o composer.
 ?>