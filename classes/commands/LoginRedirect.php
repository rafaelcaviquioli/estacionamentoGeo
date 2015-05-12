<?php
namespace commands;
class LoginRedirect extends Command {

    public function execute() {
        $this->session->getFlashBag()->set("warning", "Você precisa fazer login para acessar esta área.");
        $redirectResponse = new \Symfony\Component\HttpFoundation\RedirectResponse("?acao=Login");
        $redirectResponse->send();
    }

}

?>
