<?php

class Veiculo extends \ORM\VeiculoORM {

    public function __construct($id = null) {
        parent::__construct($id);
    }

    //Validação de cadastro.
    /**
     * @return bool
     * @throws ValidationException
     */
    protected function validationSave() {
        if (empty($this->placa)) {
            throw new ValidationException("A placa não foi informada.");
        } else {
            return true;
        }
    }

}
