<?php

class Estacionamento extends \ORM\EstacionamentoORM {

    public function __construct($id = null) {
        parent::__construct($id);
    }

    //Validação de cadastro.
    /**
     * @return bool
     * @throws ValidationException
     */
    protected function validationSave() {
        if (empty($this->nome)) {
            throw new ValidationException("O nome não foi informado.");
        } else if (empty($this->valor)) {
            throw new ValidationException("O valor não foi informado.");
        } else {
            return true;
        }
    }

    public function getPoligono() {
        $conexao = \ConnectionPDO::getConnection();
        $stmt = $conexao->prepare("SELECT st_astext(poligono) as poligono FROM estacionamento WHERE id = :id");
        if ($stmt === false) {
            trigger_error('Wrong SQL:  Error: ' . $conexao->errno . ' ' . $conexao->error, E_USER_ERROR);
        }
        $stmt->bindValue(':id', $this->id, \PDO::PARAM_STR);
        $stmt->bindColumn('poligono', $poligono);

        $stmt->execute();
        $stmt->fetch();
        
        return $poligono;
    }
    public function getPoligonoArray(){
        $poligono = $this->getPoligono();
        $pontos = array();
        
        $poligono = str_replace(array("POLYGON((", "))"), "", $poligono);
        //-47 -25,-47 -24.9,-46.9 -24.9,-46.9 -25,-47 -25
        foreach (explode(",", $poligono) as $ponto) {
            $pontos[] = str_replace(" ", ", ", $ponto);
        }
        
        return $pontos;
    }

}
