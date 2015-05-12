<?php

namespace ORM;

class VeiculoORM extends ORM {

    protected $id;
    protected $placa;

    function __construct($id = NULL) {
        parent::__construct("Veiculo", "veiculo", "id");
        // Persistência dos dados.
        $this->persistAttribute("id");
        $this->persistAttribute("placa");

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

    public function getPlaca() {
        return $this->placa;
    }

    public function setPlaca($placa) {
        $this->placa = $placa;
    }

}
