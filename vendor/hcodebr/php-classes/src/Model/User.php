<?php 
//classe User

namespace Hcode\Model;

//o '\' apartir da raiz
//usa o 'use' qdo está em outro namespace
use \Hcode\DB\Sql;
use \Hcode\Model;

//herda tudo da class Model (publico e protegido)
class User extends Model {

	const SESSION = "User";

	//campos da tabela (users, persons)
	protected $fields = [
		"iduser", "idperson", "deslogin", "despassword", "inadmin", "dtregister", "desperson", "nrphone", "desemail"
	];

	//user
	public static function login($login, $password)
	{

		//acessando o banco de dados
		$sql = new Sql();

		//acessando a consulta
		$results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(
			":LOGIN"=>$login
		));

		//se encontrou ou nao o login
		//igual a zero, não encontrou nada, estoura uma exception
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

			//se a sessao existir, o user está logado, se nao está, tem que redirecionar
			$_SESSION[User::SESSION] = $user->getValues();

			return $user;

		} else {

			throw new \Exception("Usuário inexistente ou senha inválida.");

		}

	}
	
	//funcao para verificar se está logado
	public static function verifyLogin($inadmin = true)
	{

		if (
			!isset($_SESSION[User::SESSION]) //se ela não for definida/redirecionada
			|| 
			!$_SESSION[User::SESSION] //se for falsa
			||
			!(int)$_SESSION[User::SESSION]["iduser"] > 0 //verifica o id do usuario
			||
			(bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin //se está logado na administração
		) {
			
			//se não tiver uma sessao, redireciona
			header("Location: /admin/login");

			exit; //exit para encerrar
		}
	}
	
	//o logout exclui a session
	public static function logout()
	{

		//limpa essa session (atual)
		$_SESSION[User::SESSION] = NULL;

	}

	//adicionando o método (listar usuários) do banco
	public static function listAll(){

		$sql = new Sql();

		return $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) ORDER BY b.desperson");
		//após isso retorna para rota listar usuários
	}

	//método para salvar os dados no banco de dados
	public function save() {

		$sql = new Sql();

		//criando uma procedure para a consulta
		//arquivo do banco
		//informar os dados na ordem

		/*
		pdesperson VARCHAR(64), 
		pdeslogin VARCHAR(64), 
		pdespassword VARCHAR(256), 
		pdesemail VARCHAR(128), 
		pnrphone BIGINT, 
		pinadmin TINYINT
		*/

		//chamando essa procedure (users_save) BD
		//CALL -> chama o retorno da chamada fornecida no primeiro parâmetro
		$results = $sql->select("CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", 
		array(
			//passando os dados que estão no objeto
			":desperson"=>$this->getdesperson(),
			":deslogin"=>$this->getdeslogin(),
			":despassword"=>$this->getdespassword(),
			":desemail"=>$this->getdesemail(),
			":nrphone"=>$this->getnrphone(),
			":inadmin"=>$this->getinadmin()
		));

		//primeiro registro (posição[0])
		//setando o objeto
		$this->setData($results[0]);
		
	}

	//método get para editar o usuário
	public function get($iduser)
		{
			$sql = new Sql();
		
			$results = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = :iduser;", array(
			":iduser"=>$iduser
		));
		
			//dados
			//$data = $results[0];
		
			$this->setData($results[0]);
		}
	//método para atualização (update)
	public function update(){

		$sql = new Sql();

		//procedure de atualização
		$results = $sql->select("CALL sp_usersupdate_save(:iduser, :desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", 
		array(
			//passando os dados que estão no objeto
			":iduser"=>$this->getiduser(),
			":desperson"=>$this->getdesperson(),
			":deslogin"=>$this->getdeslogin(),
			":despassword"=>$this->getdespassword(),
			":desemail"=>$this->getdesemail(),
			":nrphone"=>$this->getnrphone(),
			":inadmin"=>$this->getinadmin()
		));

		//primeiro registro (posição[0])
		//setando o objeto
		$this->setData($results[0]);
		}

	//método delete
	public function delete(){

		$sql = new Sql();

		//procedure deletar
		$sql->query("CALL sp_users_delete(:iduser)", array(

			":iduser"=>$this->getiduser()
		));

		}
	}

 ?>