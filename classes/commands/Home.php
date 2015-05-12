<?php
namespace commands;
class Home extends Command {

    public function execute() {
        
        $content = $this->getRenderViewInBase("home.php");
        $this->response->setContent($content);
        $this->response->send();
    }
}

?>
