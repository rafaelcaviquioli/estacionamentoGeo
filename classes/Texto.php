<?php

/**
 * Created by PhpStorm.
 * User: j.norenberg
 * Date: 23/02/2015
 * Time: 15:22
 */
class Texto extends \ORM\TextoORM {
    public function __construct($id = null) {
        parent::__construct($id);
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
        if (empty($this->titulo)) {
            throw new ValidationException("O Título não foi informado.");
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

    public function loadByDesc($desc, $idPagina) {
        $textos = self::newInstance()->getAll("idPagina='" . $idPagina . "' AND descricao='" . $desc . "'");
        if (count($textos) == 1) {
            $texto = $textos[0];
            $this->setId($texto->getId());
            $this->setDescricao($texto->getDescricao());
            $this->setIdPagina($texto->getIdPagina());
            $this->setTexto($texto->getTexto());
            $this->setTitulo($texto->getTitulo());
        } else {
            $this->setDescricao($desc);
            $this->setIdPagina($idPagina);
        }
        return $this;
    }

    public function getArquivo($update = false) {
        if (is_null($this->arquivo) OR $update) {
            $arquivo = new Arquivo();
            $arquivos = $arquivo->getAll("idObjeto = '" . $this->id . "' AND objeto = '" . $this->nomeEntidade . "'", array('dataCadastro DESC'), array('1'));
            if (count($arquivos) > 0) {
                $this->arquivo = $arquivos[0];
            } else {
                $this->arquivo = $arquivo;
            }
        }
        return $this->arquivo;
    }
} 