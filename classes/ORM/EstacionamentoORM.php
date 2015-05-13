<?php

namespace ORM;

class EstacionamentoORM extends ORM {

    protected $id;
    protected $nome;
    protected $valor;

    function __construct($id = NULL) {
        parent::__construct("Estacionamento", "estacionamento", "id");
        // Persistência dos dados.
        $this->persistAttribute("id");
        $this->persistAttribute("nome");
        $this->persistAttribute("valor");

        if (!is_null($id)) {
            $this->id = $id;
            $this->load();
        }
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getValor() {
        return $this->valor;
    }

    public function setValor($valor) {
        $this->valor = $valor;
    }

}
