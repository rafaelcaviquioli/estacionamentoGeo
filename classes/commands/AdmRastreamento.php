<?php

namespace commands;

use \Exception;
use Veiculo;

class AdmRastreamento extends Command {

    public function execute() {
        switch ($this->request->getMethod()) {
            case self::$GET:
                try {
                    $veiculo = new Veiculo();
                    $veiculos = $veiculo->getAll();


                    $this->request->query->set("veiculos", $veiculos);
                    $content = $this->getRenderViewInBase("rastreamento.php");
                } catch (Exception $e) {
                    $this->session->getFlashBag()->set('error', $e->getMessage());
                    $content = $this->getRenderView(self::$VIEW_ERROR);
                }
                break;
            case self::$POST:
                try {
                    $id_veiculo = $this->request->request->get('veiculo');
                    $this->request->request->get('data');
                    $data = \Tool::converteData("d/m/Y H:i:s", "Y-m-d H:i:s", $this->request->request->get('data'));


                    $posicao = str_replace(",", " ", str_replace(" ", "", $this->request->request->get('posicao')));
                    $ponto = "st_geomfromtext('POINT($posicao)', 4326)";


                    $veiculoPosicao = new \VeiculoPosicao();
                    $veiculoPosicao->setId_veiculo($id_veiculo);
                    $veiculoPosicao->setData($data);
                    $veiculoPosicao->save();

                    $id = $veiculoPosicao->getId();

                    $conexao = \ConnectionPDO::getConnection();
                    $stmt = $conexao->prepare("UPDATE veiculo_posicao SET ponto = $ponto WHERE id = :id");
                    if ($stmt === false) {
                        trigger_error('Wrong SQL:  Error: ' . $conexao->errno . ' ' . $conexao->error, E_USER_ERROR);
                    }
                    $stmt->bindValue(':id', $id, \PDO::PARAM_STR);
                    $stmt->execute();
                    $stmt->fetch();

                    $redirectResponse = new \Symfony\Component\HttpFoundation\RedirectResponse("?acao=AdmRastreamento");
                    $redirectResponse->send();
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
