<?php
namespace commands;
class Logout extends Command {

    public function execute() {
        $this->session->invalidate();
        $redirectResponse = new \Symfony\Component\HttpFoundation\RedirectResponse("?acao=Login");
        $redirectResponse->send();
    }
}

?>
