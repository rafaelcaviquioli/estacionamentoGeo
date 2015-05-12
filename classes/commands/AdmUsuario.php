<?php
namespace commands;
use \Usuario;
class AdmUsuario extends Command {

    public function execute() {
        switch ($this->request->getMethod()) {
            case self::$GET:
                //Busca parametro ID.
                $id = $this->request->query->get('id');
                $list = $this->request->query->get('list');

                if (isset($id)) {
                    //Verifica se o parametro ID foi setado e busca o usuario
                    $usuario = new Usuario($id);

                    $this->request->query->set("usuario", $usuario);
                    $content = $this->getRenderViewInBase("usuario.php");
                } else if(isset($list)){
                    //Busca todos os usuários
                    $usuario = new Usuario();
                    $usuarios = $usuario->getAll();

                    $this->request->query->set("usuarios", $usuarios);
                    $content = $this->getRenderViewInBase("usuarios.php");
                }else{
                    $content = $this->getRenderViewInBase("usuario.php");
                }
                break;
            case self::$POST:
                try {
                    //Busca parametro ID.
                    $id = $this->request->query->get('id');

                    //Busca campos do formulario
                    $ativo = $this->request->request->get('ativo');
                    $nome = $this->request->request->get('nome');
                    $sobrenome = $this->request->request->get('sobrenome');
                    $login = $this->request->request->get('login');
                    $senha = $this->request->request->get('senha');

                    $usuario = new Usuario($id);
                    $usuario->setAtivo($ativo);
                    $usuario->setNome($nome);
                    $usuario->setSobrenome($sobrenome);
                    $usuario->setLogin($login);

                    //Caso seja criação inseri operador
                    if(is_null($id)){
                        $usuario->setOperadorCadastro($this->session->get('usuario')->getNome());
                    }
                    
                    if (!is_null($senha)) {
                        $usuario->setSenha($senha);
                    }

                    $usuario->save();
                    
                    $message = "Usuário " . $usuario->getNome();
                    $message .= is_null($id) ? " criado com sucesso." : " alterado com sucesso.";
                    $this->session->getFlashBag()->set('success', $message);
                    
                    $redirectResponse = new \Symfony\Component\HttpFoundation\RedirectResponse("?acao=AdmUsuario&list");
                    $redirectResponse->send();
                } catch (ValidationException $e) {
                    //Erro de validação, renderiza novamente o form, e envia o objeto usuário que acabou
                    //de ser preenchido com novas informações porém ainda não foi salvo.
                    $this->session->getFlashBag()->set("error", $e->getMessage());
                    $this->request->query->set("usuario", $usuario);
                    $content = $this->getRenderViewInBase("usuario.php");
                } catch (Exception $e) {
                    $this->session->getFlashBag()->set('error', $e->getMessage());
                    $content = $this->getRenderView(self::$VIEW_ERROR);
                }
                break;
            case self::$DELETE:
                //Busca parametro ID.
                $id = $this->request->query->get('id');
                $usuario = new Usuario($id);
                $nomeUsuario = $usuario->getNome();
                $usuario->delete();

                $this->session->getFlashBag()->set('success', "Usuário $nomeUsuario excluído com sucesso.");

                $redirectResponse = new \Symfony\Component\HttpFoundation\RedirectResponse("?acao=AdmUsuario&list");
                $redirectResponse->send();
                break;
        }
        $this->response->setContent($content);
        $this->response->send();
    }

}

?>
