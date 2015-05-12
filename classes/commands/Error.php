<?php
namespace commands;
class Error extends Command {

    public function execute() {
        
        $content = $this->getRenderView("error.php");
        $this->response->setContent($content);
        $this->response->send();
    }
}

?>
