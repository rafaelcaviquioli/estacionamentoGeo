<?php
/**
 * Created by PhpStorm.
 * User: j.norenberg
 * Date: 23/02/2015
 * Time: 15:11
 */

namespace ORM;


class PaginaORM extends ORM {
    protected $id;
    protected $ativo;
    protected $dataCadastro;
    protected $dataAtualizado;
    protected $operadorCadastro;
    protected $operadorAtualizacao;
    protected $idioma;
    protected $menu;
    protected $sequencia;

    public function __construct($id = null) {
        parent::__construct('Pagina', 'pagina', 'id');

        $this->persistAttribute('ativo');
        $this->persistAttribute('dataCadastro');
        $this->persistAttribute('dataAtualizado');
        $this->persistAttribute('operadorCadastro');
        $this->persistAttribute('operadorAtualizacao');
        $this->persistAttribute('idioma');
        $this->persistAttribute('menu');
        $this->persistAttribute('sequencia');

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
    public function getIdioma() {
        return $this->idioma;
    }

    /**
     * @param mixed $idioma
     */
    public function setIdioma($idioma) {
        $this->idioma = $idioma;
    }

    /**
     * @return mixed
     */
    public function getMenu() {
        return $this->menu;
    }

    /**
     * @param mixed $menu
     */
    public function setMenu($menu) {
        $this->menu = $menu;
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
    public function getSequencia() {
        return $this->sequencia;
    }

    /**
     * @param mixed $sequencia
     */
    public function setSequencia($sequencia) {
        $this->sequencia = $sequencia;
    }


    /**
     * @param null $id
     * @return Pagina
     */
    static function newInstance($id = null) {
        return new \Pagina($id);
    }


}