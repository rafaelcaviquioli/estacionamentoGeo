<?php
namespace commands;
use \Usuario;
use \Exception;
use \ValidationException;
class MeuUsuario extends Command {

    public function execute() {
        switch ($this->request->getMethod()) {
            case self::$GET:
                //Nenhuma ação adicional, pois no fim do código é chamado a view MeuUsuario
                $content = $this->getRenderViewInBase("meuUsuario.php");
                break;
            case self::$POST:
                $usuarioSession = $this->session->get("usuario");
                $usuario = new Usuario($usuarioSession->getId());

                $senhaAtual = $this->request->request->get('senhaAtual');
                $novaSenha = $this->request->request->get('novaSenha');
                $confirmaNovaSenha = $this->request->request->get('confirmaNovaSenha');

                try {
                    $usuario->alterarSenha($senhaAtual, $novaSenha, $confirmaNovaSenha);
                    $this->session->getFlashBag()->set('success', 'Senha alterada com sucesso.');
                    $content = $this->getRenderViewInBase("meuUsuario.php");
                } catch (ValidationException $e) {
                    $this->session->getFlashBag()->set("error", $e->getMessage());
                    $this->request->query->set('alterarSenhaForm', '');
                    $this->request->request->set('senhaAtual', $senhaAtual);

                    if ($e->getCode() != '02') {
                        $this->request->request->set('novaSenha', $novaSenha);
                        $this->request->request->set('confirmaNovaSenha', $confirmaNovaSenha);
                    }
                    $content = $this->getRenderViewInBase("meuUsuario.php");
                } catch (Exception $e) {
                    $this->session->getFlashBag()->set('error', $e->getMessage());
                    $content = $this->getRenderView(self::$VIEW_ERROR);
                }
                break;
        }
        $this->response->setContent($content);
        $this->response->send();
    }

}

?>
