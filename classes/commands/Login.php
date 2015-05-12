<?php
namespace commands;
use \Usuario;
class Login extends Command {

    public function execute() {
        switch ($this->request->getMethod()) {
            case self::$GET:
                $content = $this->getRenderView("login.php");
                $this->response->setContent($content);
                $this->response->send();
                break;
            case self::$POST:
                try {
                    $login = $this->request->request->get('login');
                    $senha = $this->request->request->get('senha');

                    $usuarioAutenticador = new Usuario();
                    $idUsuario = $usuarioAutenticador->autenticar($login, $senha);

                    //Registra autenticação
                    $this->session->set("status", true);

                    $usuario = new Usuario($idUsuario);

                    //Armazena usuário na Sessão
                    $this->session->set("usuario", $usuario);

                    $redirectResponse = new \Symfony\Component\HttpFoundation\RedirectResponse("?acao=Home");
                    $redirectResponse->send();
                    exit();
                } catch (\ValidationException $e) {
                    $this->session->getFlashBag()->set('error', $e->getMessage());
                    $content = $this->getRenderView(self::$VIEW_LOGIN);
                    $this->response->setContent($content);
                    $this->response->send();
                } catch (\Exception $e) {
                    //Joga a mensagem de erro temporariamente na sessão
                    $this->session->getFlashBag()->set('error', $e->getMessage());

                    //Renderiza a view e lança no response.
                    $content = $this->getRenderView(self::$VIEW_ERROR);
                    $this->response->setContent($content);
                    $this->response->send();
                }
                break;
        }
    }

}

?>
