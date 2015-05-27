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
                    $relatorio = $this->request->query->get('relatorio');
                    $estacionamento = new Estacionamento($id);

                    if (isset($relatorio)) {
                        $conexao = \ConnectionPDO::getConnection();
                        $stmt = $conexao->prepare("SELECT e.nome, v.placa, ve.data_entrada entrada, ve.data_saida saida, age(ve.data_entrada, ve.data_saida) as valor FROM veiculo_estacionamento ve INNER JOIN veiculo v ON v.id = ve.id_veiculo INNER JOIN estacionamento e ON e.id = ve.id_estacionamento");
                        if ($stmt === false) {
                            trigger_error('Wrong SQL:  Error: ' . $conexao->errno . ' ' . $conexao->error, E_USER_ERROR);
                        }
                        $stmt->execute();
                        $relatorio = array();
                        while ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $dados['situacao'] = $dados['saida'] == "" ? false : true;
                            $relatorio[] = $dados;
                            
                        }
                        $this->request->query->set("relatorio", $relatorio);

                        $content = $this->getRenderViewInBase("relatorio_uso.php");
                    } else if (isset($list)) {
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
                        "<kml xmlns=\"http://www.opengis.net/kml/2.2\"><Placemark>" .
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
                    $estacionamento->save();


                    $pontos = $this->request->request->get('pontos');
                    if (count($pontos)) {
                        $pontosTratados = array();
                        foreach ($pontos as $ponto) {
                            $pontosTratados[] = str_replace(",", " ", str_replace(" ", "", $ponto));
                        }
                        $poligono = "st_geomfromtext('POLYGON((" . implode(",", $pontosTratados) . "))', 4326)";
                        $conexao = ConnectionPDO::getConnection();
                        $sql = "UPDATE estacionamento SET poligono = $poligono WHERE id = :id";
                        $stmt = $conexao->prepare($sql);

                        if ($stmt === false) {
                            trigger_error('Wrong SQL:  Error: ' . $conexao->errno . ' ' . $conexao->error, E_USER_ERROR);
                        }
                        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
                        if (!$stmt->execute()) {
                            print_r($conexao->errorInfo());
                            echo $sql;
                            die();
                        }
                        $stmt->fetch();
                    }

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
