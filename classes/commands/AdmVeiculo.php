<?php

namespace commands;

use \Exception;
use \ValidationException;
use \Veiculo;

class AdmVeiculo extends Command {

    public function execute() {
        switch ($this->request->getMethod()) {
            case self::$GET:
                try {
                    //Busca parametro ID.
                    $id = $this->request->query->get('id');
                    $list = $this->request->query->get('list');

                    $veiculo = new Veiculo();
                    $veiculos = $veiculo->getAll();

                    $this->request->query->set("veiculos", $veiculos);
                    $content = $this->getRenderViewInBase("veiculos.php");
                } catch (Exception $e) {
                    $this->session->getFlashBag()->set('error', $e->getMessage());
                    $content = $this->getRenderView(self::$VIEW_ERROR);
                }
                break;
            case self::$POST:
                try {
                    //Busca parametro ID.
                    $id = $this->request->query->get('id');

                    //Busca campos do formulario
                    $placa = $this->request->request->get('placa');

                    $veiculo = new Veiculo();
                    $veiculo->setPlaca($placa);
                    $veiculo->save();


                    $message = "O veículo " . $veiculo->getId() . " foi";
                    $message .= is_null($id) ? " criado com sucesso." : " alterado com sucesso.";
                    $this->session->getFlashBag()->set('success', $message);

                    $id = $veiculo->getId();


                    $redirectResponse = new \Symfony\Component\HttpFoundation\RedirectResponse("?acao=AdmVeiculo&id=$id");
                    $redirectResponse->send();
                } catch (ValidationException $e) {
                    //Erro de validação, renderiza novamente o form, e envia o objeto usuário que acabou
                    //de ser preenchido com novas informações porém ainda não foi salvo.

                    $veiculos = $veiculo->getAll();
                    $this->request->query->set("veiculos", $veiculos);


                    $this->session->getFlashBag()->set("error", $e->getMessage());
                    $content = $this->getRenderViewInBase("veiculos.php");
                } catch (Exception $e) {
                    $this->session->getFlashBag()->set('error', $e->getMessage());
                    $content = $this->getRenderView(self::$VIEW_ERROR);
                }
                break;
            case self::$DELETE:
                try {
                    //Busca parametro ID.
                    $id = $this->request->query->get('id');
                    $veiculo = new Veiculo($id);
                    $placa = $veiculo->getPlaca();
                    $veiculo->delete();

                    $this->session->getFlashBag()->set('success', "Veiculo $placa foi excluído com sucesso.");

                    $redirectResponse = new \Symfony\Component\HttpFoundation\RedirectResponse("?acao=AdmVeiculo&list");
                    $redirectResponse->send();
                    break;
                } catch (Exception $e) {
                    $this->session->getFlashBag()->set('error', $e->getMessage());
                    $content = $this->getRenderView(self::$VIEW_ERROR);
                }
        }
        $this->response->setContent($content);
        $this->response->send();
    }

}
