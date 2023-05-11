<?php 
//classe Model

//essa classe fica no namespace principal
namespace Hcode;

class Model {

	//tem todos os valores dos campos
	private $values = [];

	//public function setData($data)
	//{

		//foreach ($data as $key => $value)
		//{

			//$this->{"set".$key}($value);

		//}

	//}

	public function __call($name, $args)
	{
		//para saber s eh um metodo get ou set
		//substr -> substring
		// 3 quer dizer a quantidade
		$method = substr($name, 0, 3);

		//qual o nome do campo
		//$fieldName = substr($name, 3, strlen($name));

		//if (in_array($fieldName, $this->fields))
		//{
			
			switch ($method)
			{

				case "get":
					return $this->values[$fieldName];
				break;

				case "set":
					$this->values[$fieldName] = $args[0];
				break;

			}

		//}

	}

	//chama os metodos automaticamente
	public function setData ($data = array()){

		foreach ($data as $key => $value) {

			//tudo q é dinamico no PHP é entre chaves
			$this-> {"set".$key}($value);
		}
	}

	public function getValues()
	{
		//retorno do atributo
		return $this->values;

	}
}

 ?>
