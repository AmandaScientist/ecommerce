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
	public function __construct($opts = array())
	{

		$this->options = array_merge($this->defaults, $opts); //mescla os arrays e gurda no options

		 //criando e configurando template rain
        // config
		$config = array(
		    "base_url"      => null,
		    "tpl_dir"       => $_SERVER['DOCUMENT_ROOT']."/views/", //vai trazer onde estah a paginas/diretorio
		    "cache_dir"     => $_SERVER['DOCUMENT_ROOT']."/views-cache/",
		    "debug"         => false // false, nao vamos precisar
		);

		// create the Tpl object
		Tpl::configure( $config );

		$this->tpl = new Tpl();

		//os dados estaram na chave 'data' desse options
		if ($this->options['data']) $this->setData($this->options['data']);

		//desenhar o template na tela. O 'draw' espera o nome do arquivo
		//'draw' eh o metodo 'tpl'
		if ($this->options['header'] === true) $this->tpl->draw("header", false);

	}

	//método magico destruct
	public function __destruct()
	{
		//repete em todas as paginas
		if ($this->options['footer'] === true) $this->tpl->draw("footer", false);

	}

	private function setData($data = array())
	{
		//busca os dados , chave e valor
		foreach($data as $key => $val)
		{
			//a chave e o valor
			$this->tpl->assign($key, $val);

		}

	}

	//para o conteudo da pagina
	public function setTpl($tplname, $data = array(), $returnHTML = false)
	{

		$this->setData($data);

		return $this->tpl->draw($tplname, $returnHTML);

	}

}

 ?>