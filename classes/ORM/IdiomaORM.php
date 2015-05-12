<?php
/**
 * Created by PhpStorm.
 * User: j.norenberg
 * Date: 23/02/2015
 * Time: 15:11
 */

namespace ORM;


class IdiomaORM extends ORM {

    protected $id;
    protected $descricao;
    protected $simplificado;

    public function __construct($id = null) {
        parent::__construct('Idioma', 'idioma', 'id');

        $this->persistAttribute('descricao');
        $this->persistAttribute('simplificado');

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
    public function getSimplificado() {
        return $this->simplificado;
    }

    /**
     * @param mixed $simplificado
     */
    public function setSimplificado($simplificado) {
        $this->simplificado = $simplificado;
    }

    /**
     * @param null $id
     * @return \Idioma
     */
    static function newInstance($id = null) {
        return new \Idioma($id);
    }


}