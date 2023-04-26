<?php 
//Admin do projeto
//extend a classe page, ou seja herança, herda tudo dessa classe
//tudo q for publico e protegido conseguimos acessar dessa classe

namespace Hcode;

//classe pageAdmin
class pageAdmin extends Page {

    public function __construct($opts = array(), $tpl_dir = "/views/admin/"){

        //chamando o construto da classe base 'page'
        //onde tudo eh aproveitado
        //herança
        parent::__construct($opts, $tpl_dir);
    }
}


?>