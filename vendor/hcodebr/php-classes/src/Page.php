<?php 
//vai servir de base para todas as paginas 
//gerencias as telas html

//especifica onde a classe estah -> namespace
namespace Hcode;

//namespace rain
use Rain\Tpl;

class Page {

	//atributo privado
	private $tpl;
	private $options = [];
	private $defaults = [
		"header"=>true,
		"footer"=>true,
		"data"=>[]
	];

	 //método mágico construtor
	public function __construct($opts = array(), $tpl_dir = "/views/")
	{

		$this->defaults["data"]["session"] = $_SESSION;

		$this->options = array_merge($this->defaults, $opts); //mescla os arrays e gurda no options

		 //criando e configurando template rain
        // config
		$config = array(
		    "tpl_dir"       => $_SERVER["DOCUMENT_ROOT"].$tpl_dir, //vai trazer onde estah a paginas/diretorio
		    "cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
		    "debug"         => false // false, nao vamos precisar
		);

		// create the Tpl object
		Tpl::configure( $config );

		$this->tpl = new Tpl();

		//os dados estaram na chave 'data' desse options
		$this->setData($this->options["data"]);

		//desenhar o template na tela. O 'draw' espera o nome do arquivo
		//'draw' eh o metodo 'tpl'
		if ($this->options["header"] === true) $this->tpl->draw("header");

	}

	private function setData($data = array())
	{
		//busca os dados , chave e valor
		foreach($data as $key => $value)
		{
			//a chave e o valor
			$this->tpl->assign($key, $value);

		}

	}

	//para o conteudo da pagina
	public function setTpl($name, $data = array(), $returnHTML = false)
	{

		$this->setData($data);

		return $this->tpl->draw($name, $returnHTML);

	}

	//método magico destruct
	public function __destruct()
	{
		//repete em todas as paginas
		//se mandou a opcao footer, entao carrega o footer
		//ou seja, na tela de login, n tem o menu e o rodapé
		if ($this->options["footer"] === true) $this->tpl->draw("footer");

	}
}

 ?>