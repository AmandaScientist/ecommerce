<?php 

namespace Hcode\DB;

//BD
class Sql {

	const HOSTNAME = "127.0.0.1";
	const USERNAME = "root";
	const PASSWORD = "Admin.071122";
	const DBNAME = "db_ecommerce";

	//variável de conexão
	private $conn;

	public function __construct()
	{

		$this->conn = new \PDO(
			"mysql:dbname=".Sql::DBNAME.";host=".Sql::HOSTNAME, 
			Sql::USERNAME,
			Sql::PASSWORD
		);

	}
	//setParams() -> define parâmetros de configuração
	private function setParams($statement, $parameters = array())
	{

		foreach ($parameters as $key => $value) {
			
			$this->bindParam($statement, $key, $value);

		}

	}
	//bindParam() -> função para vincular um parâmetro ao nome da variável específica
	private function bindParam($statement, $key, $value)
	{

		$statement->bindParam($key, $value);

	}
	//linha
	public function query($rawQuery, $params = array())
	{

		$stmt = $this->conn->prepare($rawQuery);

		$this->setParams($stmt, $params);

		$stmt->execute();

	}
	//linha
	public function select($rawQuery, $params = array()):array
	{

		$stmt = $this->conn->prepare($rawQuery);

		$this->setParams($stmt, $params);

		$stmt->execute();

		//fetchAll() -> retorna um array com todas as linhas da consulta
		//array associativo
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);

	}

}

 ?>