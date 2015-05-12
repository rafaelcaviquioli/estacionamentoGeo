<?php
/**
 * Classe com o escopo global
 */
class FrontController {

    private $request;
    private $response;
    private $session;
    //Commands staticos.
    static $COMMAND_HOME = "Home";
    static $COMMAND_LOGIN = "Login";
    static $COMMAND_LOGIN_REDIRECT = "LoginRedirect";
    static $COMMAND_ERROR = "Error";
    static $VAR_COMMAND = "acao";
    private $commandsPublic;

    function __construct($request, $response, $session) {
        $this->request = $request;
        $this->response = $response;
        $this->session = $session;

        $this->commandsPublic = array(self::$COMMAND_LOGIN, self::$COMMAND_LOGIN_REDIRECT, 'Auth', 'AdmArquivo');
    }

    private function getCommand() {
        $command = $this->request->query->get(self::$VAR_COMMAND);

        //Caso o command seja nullo assume-se pagina inicial.
        if (empty($command)) {
            if ($this->session->get("status")) {
                $command = self::$COMMAND_HOME;
            } else {
                $command = self::$COMMAND_LOGIN;
            }
        }

        return $command;
    }

    public function process() {
        //Inicia a sessão caso ela já não esteja iniciada.
        if (!$this->session->isStarted()) {
            $this->session->start();
        }
        //Verifica se a sessão é nula e o comando é publico. Ou se a sessão está ativa.
        if ((!$this->session->get("status") && $this->isCommandPublic() || $this->session->get("status") && !$this->isCommandPublic())) {

            try {
                $refClass = new ReflectionClass("\commands\\" . $this->getCommand());
            } catch (Exception $e) {
                $this->session->getFlashBag()->set('error', $e->getMessage());
                $refClass = new ReflectionClass("\commands\Error");
            }
            //Instancia a classe que foi passada pelo parametro acao, e envia o request e response via construtor
            $insClass = $refClass->newInstance($this->request, $this->response, $this->session);

            //Chama metodo execute.
            $execute = $refClass->getMethod("execute");

            //Passa atributos.
            $execute->invokeArgs($insClass, array($this->request, $this->response));
        } else if ($this->isCommandPublic() && $this->session->get("status")) {
            //Chama comando Home - Comando publico já está logado.
            $this->setCommand("Home");
            $this->process();
        } else {
            //Chama comando Login
            $this->setCommand("LoginRedirect");
            $this->process();
        }
    }

    private function setCommand($command) {
        $this->request->query->set(self::$VAR_COMMAND, $command);
    }

    private function isCommandPublic() {
        return in_array($this->getCommand(), $this->commandsPublic);
    }

}

?>
