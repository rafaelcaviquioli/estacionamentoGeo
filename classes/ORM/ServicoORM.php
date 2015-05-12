<?php
/**
 * Created by PhpStorm.
 * User: j.norenberg
 * Date: 23/02/2015
 * Time: 15:11
 */

namespace ORM;


class ServicoORM extends ORM {

    protected $id;
    protected $ativo;
    protected $dataCadastro;
    protected $dataAtualizado;
    protected $operadorCadastro;
    protected $operadorAtualizacao;
    protected $principal;
    protected $idioma;
    protected $titulo;
    protected $descricao;
    protected $idCategoria;

    public function __construct($id = null) {
        parent::__construct('Servico', 'servico', 'id');

        $this->persistAttribute('ativo');
        $this->persistAttribute('dataCadastro');
        $this->persistAttribute('dataAtualizado');
        $this->persistAttribute('operadorCadastro');
        $this->persistAttribute('operadorAtualizacao');
        $this->persistAttribute('idioma');
        $this->persistAttribute('titulo');
        $this->persistAttribute('descricao');
        $this->persistAttribute('principal');
        $this->persistAttribute('idCategoria');

        if (!is_null($id)) {
            $this->id = $id;
            $this->load();
        }

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
        return $this;
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
        return $this;
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
        return $this;
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
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdioma() {
        return $this->idioma;
    }

    /**
     * @param mixed $idioma
     */
    public function setIdioma($idioma) {
        $this->idioma = $idioma;
        return $this;
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
        return $this;
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
        return $this;
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
        return $this;
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
     * @return mixed
     */
    public function getPrincipal() {
        return $this->principal;
    }

    /**
     * @param mixed $principal
     */
    public function setPrincipal($principal) {
        $this->principal = $principal;
    }

    /**
     * @return mixed
     */
    public function getIdCategoria() {
        return $this->idCategoria;
    }

    /**
     * @param mixed $idCategoria
     */
    public function setIdCategoria($idCategoria) {
        $this->idCategoria = $idCategoria;
    }


    /**
     * @param null $id
     * @return \Servico
     */
    static function newInstance($id = null) {
        return new \Servico($id);
    }


}