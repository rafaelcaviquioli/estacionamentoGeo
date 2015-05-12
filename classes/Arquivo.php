<?php
use ORM\ArquivoORM;
class Arquivo extends ArquivoORM {

    public function __construct($id = NULL) {
        parent::__construct($id);
    }

    //Atributos padrões de criação.
    protected function beforeInsert() {
        $this->ativo = true;
        $this->dataCadastro = date("Y-m-d H:i:s");
    }

    //Validação de cadastro.
    protected function validationSave() {
        if (empty($this->nome)) {
            throw new ValidationException("Erro ao obter o nome da arquivo.");
        } else
        if (empty($this->objeto)) {
            throw new ValidationException("Tipo de objeto associado a arquivo não foi informado.");
        } else
        if (empty($this->idObjeto)) {
            throw new ValidationException("Id do objeto associado a arquivo não foi informado.");
        } else {
            return true;
        }
    }
    
    
    public function delete(){
        $dirarquivo = "arquivos/uploads/".$this->nome;
        parent::delete();
        if(unlink($dirarquivo)){
            return TRUE;
        }else{
            throw new Exception("Não foi possível excluir a arquivo do servidor.");
        }
    }
}

?>
