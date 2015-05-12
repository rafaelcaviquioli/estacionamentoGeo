<?php

namespace ORM;

class ImagemORM extends ORM {

    protected $id;
    protected $dataCadastro;
    protected $operadorCadastro;
    protected $idObjeto;
    protected $objeto;
    protected $descricao;
    protected $nome;
    protected $tipo;

    function __construct($id = NULL) {
        parent::__construct("Imagem", "imagem", "id");

        // Persistência dos dados.
        $this->persistAttribute("id");
        $this->persistAttribute("dataCadastro");
        $this->persistAttribute("operadorCadastro");
        $this->persistAttribute("idObjeto");
        $this->persistAttribute("objeto");
        $this->persistAttribute("descricao");
        $this->persistAttribute("nome");
        $this->persistAttribute("tipo");


        if (!is_null($id)) {
            $this->id = $id;
            $this->load();
        }
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getDataCadastro() {
        return $this->dataCadastro;
    }

    public function setDataCadastro($dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }

    public function getOperadorCadastro() {
        return $this->operadorCadastro;
    }

    public function setOperadorCadastro($operadorCadastro) {
        $this->operadorCadastro = $operadorCadastro;
    }

    public function getIdObjeto() {
        return $this->idObjeto;
    }

    public function setIdObjeto($idObjeto) {
        $this->idObjeto = $idObjeto;
    }

    public function getObjeto() {
        return $this->objeto;
    }

    public function setObjeto($objeto) {
        $this->objeto = $objeto;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function getNome() {
        if ($this->nome == '') {
            return "semfoto.jpg";
        }
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

}

?>
