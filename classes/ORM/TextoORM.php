<?php
/**
 * Created by PhpStorm.
 * User: j.norenberg
 * Date: 23/02/2015
 * Time: 15:11
 */

namespace ORM;


class TextoORM extends ORM {
    protected $id;
    protected $ativo;
    protected $dataCadastro;
    protected $dataAtualizado;
    protected $operadorCadastro;
    protected $operadorAtualizacao;
    protected $idPagina;
    protected $descricao;
    protected $titulo;
    protected $texto;

    public function __construct($id = null) {
        parent::__construct('Texto', 'texto', 'id');

        $this->persistAttribute('ativo');
        $this->persistAttribute('dataCadastro');
        $this->persistAttribute('dataAtualizado');
        $this->persistAttribute('operadorCadastro');
        $this->persistAttribute('operadorAtualizacao');
        $this->persistAttribute('idPagina');
        $this->persistAttribute('descricao');
        $this->persistAttribute('titulo');
        $this->persistAttribute('texto');

        if (!is_null($id)) {
            $this->id = $id;
            $this->load();
        }

    }

    /**
     * @return mixed
     */
    public function getDescricao() {
        return $this->descricao;
    }

    /**
     * @param mixed $descricao
     */
    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    /**
     * @return null
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param null $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getIdPagina() {
        return $this->idPagina;
    }

    /**
     * @param mixed $idPagina
     */
    public function setIdPagina($idPagina) {
        $this->idPagina = $idPagina;
    }

    /**
     * @return mixed
     */
    public function getTexto() {
        return $this->texto;
    }

    /**
     * @param mixed $texto
     */
    public function setTexto($texto) {
        $this->texto = $texto;
    }

    /**
     * @return mixed
     */
    public function getTitulo() {
        return $this->titulo;
    }

    /**
     * @param mixed $titulo
     */
    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    /**
     * @return mixed
     */
    public function getOperadorCadastro() {
        return $this->operadorCadastro;
    }

    /**
     * @param mixed $operadorCadastro
     */
    public function setOperadorCadastro($operadorCadastro) {
        $this->operadorCadastro = $operadorCadastro;
    }

    /**
     * @return mixed
     */
    public function getAtivo() {
        return $this->ativo;
    }

    /**
     * @param mixed $ativo
     */
    public function setAtivo($ativo) {
        $this->ativo = $ativo;
    }

    /**
     * @return mixed
     */
    public function getDataAtualizado() {
        return $this->dataAtualizado;
    }

    /**
     * @param mixed $dataAtualizado
     */
    public function setDataAtualizado($dataAtualizado) {
        $this->dataAtualizado = $dataAtualizado;
    }

    /**
     * @return mixed
     */
    public function getDataCadastro() {
        return $this->dataCadastro;
    }

    /**
     * @param mixed $dataCadastro
     */
    public function setDataCadastro($dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }

    /**
     * @return mixed
     */
    public function getOperadorAtualizacao() {
        return $this->operadorAtualizacao;
    }

    /**
     * @param mixed $operadorAtualizacao
     */
    public function setOperadorAtualizacao($operadorAtualizacao) {
        $this->operadorAtualizacao = $operadorAtualizacao;
    }


    /**
     * @param null $id
     * @return \Texto
     */
    static function newInstance($id = null) {
        return new \Texto($id);
    }


}