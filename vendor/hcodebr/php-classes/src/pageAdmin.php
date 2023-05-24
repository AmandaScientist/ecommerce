<?php 
//Administração do projeto (admin)
//extend a classe page, ou seja herança, herda tudo dessa classe
//tudo que for publico e protegido conseguimos acessar dessa classe

namespace Hcode;

//classe PageAdmin
class PageAdmin extends Page {

    public function __construct($opts = array(), $tpl_dir = "/views/admin/"){ //caminho

        //chamando o construtor da classe base 'Page'
        parent::__construct($opts, $tpl_dir);
    }
}


?>