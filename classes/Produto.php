<?php

/**
 * Created by PhpStorm.
 * User: j.norenberg
 * Date: 23/02/2015
 * Time: 15:22
 */
class Produto extends \ORM\ProdutoORM {
    public function __construct($id = null) {
        parent::__construct($id);
    }
    //Atributos padr�es de cria��o.
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

    //Valida��o de cadastro.
    /**
     * @return bool
     * @throws ValidationException
     */
    protected function validationSave() {
        if (empty($this->titulo)) {
            throw new ValidationException("O t�tulo do produto n�o foi informado.");
        } else if (empty($this->descricao)) {
            throw new ValidationException("A descri��o do produto n�o foi informada.");
        } else if (empty($this->idioma)) {
            throw new ValidationException("O idioma do produto n�o foi informado.");
        } else if (empty($this->idCategoria)) {
            throw new ValidationException("A categoria do produto n�o foi informada.");
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

    public function getImagens($tipo = null) {
        $imagem = new \Imagem();
        return $imagem->getAll("idObjeto = '" . $this->id . "' AND objeto = '" . $this->nomeEntidade . "'" . (!is_null($tipo) ? " AND tipo='$tipo'" : null), array('dataCadastro'), '*');
    }

    public function getCapa($update = false) {
        if (is_null($this->capa) OR $update) {
            $imagens = $this->getImagens('CAPA');
            $this->capa = $imagens[0];
        }
        return $this->capa;
    }

    /**
     * @param bool $update
     * @return Arquivo
     * @throws Exception
     */
    public function getCatalogo($update = false) {
        if (is_null($this->catalogo) OR $update) {
            $arquivo = new Arquivo();
            $arquivos = $arquivo->getAll("tipo='CATALOGO' AND idObjeto = '" . $this->id . "' AND objeto = '" . $this->nomeEntidade . "'", array('dataCadastro DESC'), array('1'));
            if (count($arquivos) > 0) {
                $this->catalogo = $arquivos[0];
            } else {
                $this->catalogo = $arquivo;
            }
        }
        return $this->catalogo;
    }

    /**
     * @param bool $update
     * @return Arquivo
     * @throws Exception
     */
    public function getTabela($update = false) {
        if (is_null($this->tabela) OR $update) {
            $arquivo = new Arquivo();
            $arquivos = $arquivo->getAll("tipo='TABELA' AND idObjeto = '" . $this->id . "' AND objeto = '" . $this->nomeEntidade . "'", array('dataCadastro DESC'), array('1'));
            if (count($arquivos) > 0) {
                $this->tabela = $arquivos[0];
            } else {
                $this->tabela = $arquivo;
            }
        }
        return $this->tabela;
    }

} 