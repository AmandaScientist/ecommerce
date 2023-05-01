<?php 
//classe Model

namespace Hcode\Model;

//o '\' eh pq eh apartir da raiz
//usa o 'use' qdo estah em outro namespace
use \Hcode\DB\Sql;
use \Hcode\Model;

class User extends Model {

	const SESSION = "User";

	protected $fields = [
		"iduser", "idperson", "deslogin", "despassword", "inadmin", "dtergister"
	];

	public static function login($login, $password):User
	{

		//acessando o banco de dados
		$db = new Sql();

		//acessando a consulta
		$results = $db->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(
			":LOGIN"=>$login
		));

		//se encontrou ou nao o login
		//igual a zero, n encontrou nada, ai estoura uma exception
		//'\exception para encontrar a principal
		if (count($results) === 0) {
			throw new \Exception("Usuário inexistente ou senha inválida.");
		}

		//os registros encontrados
		$data = $results[0];

		//verificando a senha do usuario
		if (password_verify($password, $data["despassword"]) === true) {

			$user = new User();

			//valor pra todos os campos retornados do banco
			$user->setData($data);

			//se a sessao existir ta logado, se nao tem q redirecionar
			$_SESSION[User::SESSION] = $user->getValues();

			return $user;

		} else {

			throw new \Exception("Usuário inexistente ou senha inválida.");

		}

	}
	
	//funcao para verificar s estah logado
	public static function verifyLogin($inadmin = true)
	{

		if (
			!isset($_SESSION[User::SESSION])
			|| 
			!$_SESSION[User::SESSION] //se for falsa
			||
			!(int)$_SESSION[User::SESSION]["iduser"] > 0 //verifica o id do usuario
			||
			(bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin //se ta logado na administacao
		) {
			
			//se n tiver uma sessao redireciona
			header("Location: /admin/login");

			exit; //exit para n fazer mais nada

		}

	}

	//o logout exclui a session
	public static function logout()
	{

		//limpa essa session
		$_SESSION[User::SESSION] = NULL;

	}


}

 ?>