<?php
header('Content-Type: text/html; charset=utf-8');
require_once 'vendor/autoload.php';

try {
    $request = Symfony\Component\HttpFoundation\Request::createFromGlobals();
    $response = new \Symfony\Component\HttpFoundation\Response();
    $session = new \Symfony\Component\HttpFoundation\Session\Session();
    $request->request->set("__DIR__", __DIR__);

    //Habilita sobrescrita de método http
    Symfony\Component\HttpFoundation\Request::enableHttpMethodParameterOverride();

    $frontController = new FrontController($request, $response, $session);
    
    $frontController->process();
} catch (Exception $e) {
    print_r($e->getTraceAsString());
}
