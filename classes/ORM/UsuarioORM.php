<?php

namespace ORM;

class UsuarioORM extends ORM {

    protected $id;
    protected $login;
    protected $senha;

    function __construct($id = NULL) {
        parent::__construct("Usuario", "usuario", "id");
        // Persistência dos dados.
        $this->persistAttribute("login");
        $this->persistAttribute("senha");
        if (!is_null($id)) {
            $this->id = $id;
            $this->load();
        }
    }

    function getId() {
        return $this->id;
    }



    function getLogin() {
        return $this->login;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setLogin($login) {
        $this->login = $login;
    }

    public function setSenha($senha) {
        $this->senha = md5($senha);
    }

    function getSenha() {
        return $this->senha;
    }

}
