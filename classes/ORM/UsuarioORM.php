<?php
namespace ORM;
class UsuarioORM extends ORM {
    protected $id;
    protected $ativo;
    protected $dataCadastro;
    protected $operadorCadastro;
    protected $nome;
    protected $sobrenome;
    protected $login;
    protected $senha;
    function __construct($id = NULL) {
        parent::__construct("Usuario", "usuario", "id");
        // Persistência dos dados.
        $this->persistAttribute("id");
        $this->persistAttribute("ativo");
        $this->persistAttribute("dataCadastro");
        $this->persistAttribute("operadorCadastro");
        $this->persistAttribute("nome");
        $this->persistAttribute("sobrenome");
        $this->persistAttribute("login");
        $this->persistAttribute("senha");
        if (!is_null($id)) {
            $this->id = $id;
            $this->load();
        }
    }
    // Setters and Getters
    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }
    public function getAtivo() {
        return $this->ativo;
    }
    public function setAtivo($ativo) {
        $this->ativo = $ativo;
    }
    public function getDataCadastro() {
        return $this->dataCadastro;
    }
    public function setDataCadastro($dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }
    public function getOperadorCadastro() {
        return $this->operadorCadastro;
    }
    public function setOperadorCadastro($operadorCadastro) {
        $this->operadorCadastro = $operadorCadastro;
    }
    public function getNome() {
        return $this->nome;
    }
    public function setNome($nome) {
        $this->nome = $nome;
    }
    public function getSobrenome() {
        return $this->sobrenome;
    }
    public function setSobrenome($sobrenome) {
        $this->sobrenome = $sobrenome;
    }
    public function getLogin() {
        return $this->login;
    }
    public function setLogin($login) {
        $this->login = $login;
    }
    public function getSenha() {
        return $this->senha;
    }
    public function setSenha($senha) {
        $this->senha = md5($senha);
    }
}
?>