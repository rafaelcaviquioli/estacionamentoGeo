<?php

namespace commands;

use \Exception;
use \ValidationException;
use \Estacionamento;
use ConnectionPDO;
use \PDO;

class AdmEstacionamento extends Command {

    public function execute() {
        switch ($this->request->getMethod()) {
            case self::$GET:
                try {
                    //Busca parametro ID.
                    $id = $this->request->query->get('id');
                    $list = $this->request->query->get('list');
                    $kml = $this->request->query->get('kml');
                    $estacionamento = new Estacionamento($id);

                    if (isset($list)) {
                        $estacionamentos = $estacionamento->getAll();

                        $this->request->query->set("estacionamentos", $estacionamentos);
                        $content = $this->getRenderViewInBase("estacionamentos.php");
                    } else if (isset($kml)) {
                        $conexao = ConnectionPDO::getConnection();
                        $stmt = $conexao->prepare("SELECT st_askml(poligono) poligono, nome FROM estacionamento WHERE id = :id");
                        if ($stmt === false) {
                            trigger_error('Wrong SQL:  Error: ' . $conexao->errno . ' ' . $conexao->error, E_USER_ERROR);
                        }
                        $stmt->bindValue(':id', $id, PDO::PARAM_STR);

                        $stmt->bindColumn('poligono', $poligono);
                        $stmt->bindColumn('nome', $nome);
                        $stmt->execute();
                        $stmt->fetch();

                        header('Content-type: text/kml');
                        header("Content-Disposition: attachment; filename=\"$nome.kml\"");
                        
                        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>" . 
                               "<kml xmlns=\"http://www.opengis.net/kml/2.2\"><Placemark>".
                               "<name>$nome</name>" . $poligono .
                            "</Placemark></kml>";
                        die();
                    } else {
                        $this->request->query->set("estacionamento", $estacionamento);
                        $content = $this->getRenderViewInBase("estacionamento.php");
                    }
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
                    $nome = $this->request->request->get('nome');
                    $valor = $this->request->request->get('valor');

                    $estacionamento = new Estacionamento($id);

                    $estacionamento->setNome($nome);
                    $estacionamento->setValor($valor);


                    $pontos = $this->request->request->get('pontos');
                    if (count($pontos)) {
                        $pontosTratados = array();
                        foreach ($pontos as $ponto) {
                            $pontosTratados[] = str_replace(",", " ", str_replace(" ", "", $ponto));
                        }
                        $poligono = "st_geomfromtext('POLYGON((" . implode(",", $pontosTratados) . "))', 4326)";
                        //-48.0 -26.0, -48.0 -25.9, -48.0 -25.7,-48.0 -25.6,  -48.0 -26.0
//                        $conexao = ConnectionPDO::getConnection();
//                        $stmt = $conexao->prepare("UPDATE estacionamento SET poligono = $poligono WHERE id = :id");
//                        if ($stmt === false) {
//                            trigger_error('Wrong SQL:  Error: ' . $conexao->errno . ' ' . $conexao->error, E_USER_ERROR);
//                        }
//                        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
//                        $stmt->execute();
//                        $stmt->fetch();
                        $estacionamento->setPoligono($poligono);
                    }
                    $estacionamento->save();

                    $message = "O estacionamento " . $estacionamento->getId() . " foi";
                    $message .= is_null($id) ? " criado com sucesso." : " alterado com sucesso.";
                    $this->session->getFlashBag()->set('success', $message);



                    $redirectResponse = new \Symfony\Component\HttpFoundation\RedirectResponse("?acao=AdmEstacionamento&list");
                    $redirectResponse->send();
                } catch (ValidationException $e) {
                    //Erro de validação, renderiza novamente o form, e envia o objeto usuário que acabou
                    //de ser preenchido com novas informações porém ainda não foi salvo.

                    $estacionamentos = $estacionamento->getAll();
                    $this->request->query->set("estacionamentos", $estacionamentos);


                    $this->session->getFlashBag()->set("error", $e->getMessage());
                    $content = $this->getRenderViewInBase("estacionamentos.php");
                } catch (Exception $e) {
                    $this->session->getFlashBag()->set('error', $e->getMessage());
                    $content = $this->getRenderView(self::$VIEW_ERROR);
                }
                break;
            case self::$DELETE:
                try {
                    //Busca parametro ID.
                    $id = $this->request->query->get('id');
                    $estacionamento = new Estacionamento($id);
                    $nome = $estacionamento->getNome();
                    $estacionamento->delete();

                    $this->session->getFlashBag()->set('success', "Estacionamento $nome foi excluído com sucesso.");

                    $redirectResponse = new \Symfony\Component\HttpFoundation\RedirectResponse("?acao=AdmEstacionamento&list");
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
