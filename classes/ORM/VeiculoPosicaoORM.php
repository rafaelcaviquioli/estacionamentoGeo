<?php

namespace ORM;

class VeiculoPosicaoORM extends ORM {

    protected $id;
    protected $ponto;
    protected $id_veiculo;
    protected $data;

    function __construct($id = NULL) {
        parent::__construct("VeiculoPosicao", "veiculo_posicao", "id");
        // Persistência dos dados.
        $this->persistAttribute("id_veiculo");
        //$this->persistAttribute("ponto");
        $this->persistAttribute("data");

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

    function getPonto() {
        return $this->ponto;
    }

    function getId_veiculo() {
        return $this->id_veiculo;
    }

    function setPonto($ponto) {
        $this->ponto = $ponto;
    }

    function setId_veiculo($id_veiculo) {
        $this->id_veiculo = $id_veiculo;
    }

    function getData() {
        return $this->data;
    }

    function setData($data) {
        $this->data = $data;
    }

}
