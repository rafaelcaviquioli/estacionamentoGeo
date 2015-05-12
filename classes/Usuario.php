<?php

class Usuario extends \ORM\UsuarioORM {

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
            throw new ValidationException("Nome do usuário não preenchido.");
        } else
        if (empty($this->login)) {
            throw new ValidationException("Login do usuário não preenchido.");
        } else
        if (empty($this->senha)) {
            throw new ValidationException("Senha do usuário não preenchida.");
        } else {
            return true;
        }
    }

    public function autenticar($login, $senha) {
        $rs = Connection::getConnection()->prepare("SELECT ativo, id, login FROM usuario WHERE login = ? AND senha = MD5(?)");
        $rs->bind_param('ss', $login, $senha);
        if ($rs->execute()) {
            $rs->store_result();
            $rs->bind_result($ativo, $id, $loginUsuario);
            $rs->fetch();
            if ($rs->num_rows == 1) {
                //Usuário encontrado
                if ($ativo) {
                    if (Tool::validaId($id)) {
                        //Autenticado, retorna o id usuário.
                        return $id;
                    } else {
                        throw new Exception("Erro na autenticação, código do usuário não encontrado.");
                    }
                } else {
                    //Usuário desativado
                    throw new ValidationException("O usuário " . $loginUsuario . " está desativado.");
                }
            } else {
                throw new ValidationException("Login ou senha inválido.");
            }
        } else {
            throw new Exception("Erro ao buscar usuários do banco de dados.");
        }
    }

    public function getNomeCompleto() {
        return $this->nome . " " . $this->sobrenome;
    }

    public function alterarSenha($senhaAtual, $novaSenha, $confirmaNovaSenha) {
        if (empty($senhaAtual)) {
            throw new ValidationException('Senha atual não preenchida.', '03');
        }
        if (empty($novaSenha)) {
            throw new ValidationException('Preencha a nova senha', '04');
        }
        if (empty($confirmaNovaSenha)) {
            throw new ValidationException('Preencha confirmação da nova senha', '05');
        }
        if (md5($senhaAtual) == $this->getSenha()) {
            if ($novaSenha == $confirmaNovaSenha) {
                $this->setSenha($novaSenha);
                $this->save();
                return true;
            } else {
                throw new ValidationException('A confirmação da nova senha não confere.', '01');
            }
        } else {
            throw new ValidationException("Senha atual não confere.", '02');
        }
    }

}

?>
