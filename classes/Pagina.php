<?php

/**
 * Created by PhpStorm.
 * User: j.norenberg
 * Date: 23/02/2015
 * Time: 15:22
 */
class Pagina extends \ORM\PaginaORM {
    public function __construct($id = null) {
        parent::__construct($id);
    }

    /**
     * @param $descTexto
     * @param bool $update
     * @return Texto
     */
    public function getTexto($descTexto, $update = false) {
        if (is_null($this->$descTexto) OR $update) {
            $texto = Texto::newInstance()->loadByDesc($descTexto, $this->id);
            $this->$descTexto = $texto;
        }
        return $this->$descTexto;
    }

    public function getTextos($update = false, $where = null, $order = array('titulo'), $limit = '*') {
        if (is_null($this->textos) OR $update) {
            $this->textos = Texto::newInstance()->getAll("idPagina='" . $this->id . "'" . ($where != null ? " AND " . $where : null), $order, $limit);
        }
        return $this->textos;
    }


    //Atributos padrões de criação.
    /**
     *
     */
    protected function beforeInsert() {
        $this->ativo = true;
        $this->dataCadastro = date("Y-m-d H:i:s");
    }

    /**
     *
     */
    protected function beforeSave() {
        $this->dataAtualizado = date("Y-m-d H:i:s");
    }

    //Validação de cadastro.
    /**
     * @return bool
     * @throws ValidationException
     */
    protected function validationSave() {
        if (empty($this->menu)) {
            throw new ValidationException("O Título do menu não foi informado.");
        } else if (empty($this->idioma)) {
            throw new ValidationException("O idioma da página não foi informado.");
        } else {
            return true;
        }
    }

    /**
     * @return mixed
     */
    public function getUltimaAtualizacao() {
        $conexao = Connection::getConnection();
        $consulta = mysqli_fetch_assoc($conexao->query("SELECT MAX(dataAtualizado) AS dataAtualizado FROM " . $this->tabelaEntidade));
        return $consulta['dataAtualizado'];
    }

    /**
     * @param bool $update
     * @return Idioma
     */
    public function getIdiomaObj($update = false) {
        if (is_null($this->idiomaObj) OR $update) {
            $this->idiomaObj = new Idioma($this->getIdioma());
        }
        return $this->idiomaObj;
    }

    public function getImagens($tipo = null) {
        $imagem = new \Imagem();
        return $imagem->getAll("idObjeto = '" . $this->id . "' AND objeto = '" . $this->nomeEntidade . "'" . (!is_null($tipo) ? " AND tipo='$tipo'" : $tipo), array('dataCadastro'), '*');
    }


} 