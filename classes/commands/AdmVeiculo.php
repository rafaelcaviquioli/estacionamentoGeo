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
                    $kml = $this->request->query->get('kml');

                    $veiculo = new Veiculo($id);

                    if (isset($kml)) {
                        $conexao = \ConnectionPDO::getConnection();
                        $stmt = $conexao->prepare("SELECT st_askml(ponto) ponto, data FROM veiculo_posicao WHERE id_veiculo = :id AND ponto is not null");
                        if ($stmt === false) {
                            trigger_error('Wrong SQL:  Error: ' . $conexao->errno . ' ' . $conexao->error, E_USER_ERROR);
                        }
                        $stmt->bindValue(':id', $id, \PDO::PARAM_STR);

                        $stmt->bindColumn('ponto', $ponto);
                        $stmt->bindColumn('data', $data);


                        $stmt->execute();
                        header('Content-type: text/kml');
                        header("Content-Disposition: attachment; filename=\"" . $veiculo->getPlaca() . ".kml\"");

                        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>" .
                        "<kml xmlns=\"http://www.opengis.net/kml/2.2\">";

                        while ($dados = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                            $data = \Tool::converteData("Y-m-d H:i:s", "d/m/Y H:i:s", $data);
                            echo "<Placemark><name>$data</name>";
                            echo $ponto;
                            echo "</Placemark>";
                        }
                        echo "</kml>";



                        die();
                    }



                    $veiculos = $veiculo->getAll();

                    $this->request->query->set("veiculo", $veiculo);
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

                    $veiculo = new Veiculo($id);
                    $veiculo->setPlaca($placa);
                    $veiculo->save();


                    $message = "O veículo " . $veiculo->getId() . " foi";
                    $message .= is_null($id) ? " criado com sucesso." : " alterado com sucesso.";
                    $this->session->getFlashBag()->set('success', $message);

                    $id = $veiculo->getId();


                    $redirectResponse = new \Symfony\Component\HttpFoundation\RedirectResponse("?acao=AdmVeiculo");
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
