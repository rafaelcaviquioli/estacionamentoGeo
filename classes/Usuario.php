<?php

class Usuario extends \ORM\UsuarioORM {

    public function __construct($id = NULL) {
        parent::__construct($id);
    }

    //Atributos padrões de criação.
    protected function beforeInsert() {
        $this->ativo = true;
    }

    //Validação de cadastro.
    protected function validationSave() {
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
        $conexao = ConnectionPDO::getConnection();
        $stmt = $conexao->prepare("SELECT ativo, id, login FROM usuario WHERE login = :login AND senha = md5(:senha)");
        if ($stmt === false) {
            trigger_error('Wrong SQL:  Error: ' . $conexao->errno . ' ' . $conexao->error, E_USER_ERROR);
        }
        $stmt->bindValue(':login', $login, PDO::PARAM_STR);
        $stmt->bindValue(':senha', $senha, PDO::PARAM_STR);
        $stmt->bindColumn('ativo', $ativo);
        $stmt->bindColumn('id', $id);
        $stmt->bindColumn('login', $login);
        $stmt->execute();
        $stmt->fetch();

        if ($stmt->rowCount() == 1) {
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
