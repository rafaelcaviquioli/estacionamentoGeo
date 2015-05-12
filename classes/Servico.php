<?php

/**
 * Created by PhpStorm.
 * User: j.norenberg
 * Date: 23/02/2015
 * Time: 15:22
 */
class Servico extends \ORM\ServicoORM {
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
            throw new ValidationException("O Título do serviço não foi informado.");
        } else if (empty($this->descricao)) {
            throw new ValidationException("A descrição do serviço não foi informada.");
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

    public function getImagens($tipo = null) {
        $imagem = new \Imagem();
        return $imagem->getAll("idObjeto = '" . $this->id . "' AND objeto = '" . $this->nomeEntidade . "'" . (!is_null($tipo) ? " AND tipo='$tipo'" : null), array('dataCadastro'), '*');
    }

    public function getCapa($update = false) {
        if (is_null($this->capa) OR $update) {
            $imagens = $this->getImagens('CAPA');
            if(count($imagens) > 0) {
                $this->capa = $imagens[0];
            }else{
                $this->capa = new Imagem();
            }
        }
        return $this->capa;
    }

} 