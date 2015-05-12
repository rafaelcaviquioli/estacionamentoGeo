<?php

namespace commands;

use \Estrutura;
use \Exception;
use \ValidationException;

class AdmEstrutura extends Command {

    public function execute() {
        switch ($this->request->getMethod()) {
            case self::$GET:
                try {
                    //Busca parametro ID.
                    $id = $this->request->query->get('id');
                    $list = $this->request->query->get('list');

                    $this->request->query->set("idiomas", \Idioma::newInstance()->getAll(null, array('descricao'), '*'));
                    if (isset($list)) {
                        //Busca todas os registros
                        $estrutura = new Estrutura();
                        $estruturas = $estrutura->getAll(null, array('titulo'), '*');

                        $this->request->query->set("estruturas", $estruturas);
                        $content = $this->getRenderViewInBase("estruturas.php");
                    } else {
                        if (isset($id)) {
                            $estrutura = new Estrutura($id);
                            //Verifica se o parametro ID foi setado e busca o registro
                            $this->request->query->set("imagens", $estrutura->getImagens());
                            $this->request->query->set("estrutura", $estrutura);
                        }

                        $content = $this->getRenderViewInBase("estrutura.php");
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
                    $search = $this->request->query->get('search');
                    if (isset($search)) {
                        $idioma = $this->request->request->get('idioma');

                        $estrutura = new Estrutura();
                        $estruturas = $estrutura->getAll($idioma > 0 ? "idioma='$idioma'" : null, array('titulo'), '*');


                        $this->request->query->set("idiomas", \Idioma::newInstance()->getAll(null, array('descricao'), '*'));
                        $this->request->query->set("idiomaAtual", \Idioma::newInstance($idioma));

                        $this->request->query->set("estruturas", $estruturas);
                        $content = $this->getRenderViewInBase("estruturas.php");
                    } else {
                        //Busca campos do formulario
                        $ativo = $this->request->request->get('ativo');
                        $titulo = $this->request->request->get('titulo');
                        $descricao = $this->request->request->get('descricao');
                        $idioma = $this->request->request->get('idioma');

                        $estrutura = new Estrutura($id);
                        $estrutura->setAtivo($ativo);
                        $estrutura->setTitulo($titulo);
                        $estrutura->setDescricao($descricao);
                        $estrutura->setIdioma($idioma);

                        //Caso seja criação inseri operador
                        if (is_null($id)) {
                            $estrutura->setOperadorCadastro($this->session->get('usuario')->getNome());
                            $estrutura->setOperadorAtualizacao($this->session->get('usuario')->getNome());
                        } else {
                            $estrutura->setOperadorAtualizacao($this->session->get('usuario')->getNome());
                        }
                        $estrutura->save(false);

                        $message = "A estrutura " . $estrutura->getTitulo();
                        $message .= is_null($id) ? " criada com sucesso." : " alterada com sucesso.";
                        $this->session->getFlashBag()->set('success', $message);

                        $id = $estrutura->getId();

                        $redirectResponse = new \Symfony\Component\HttpFoundation\RedirectResponse("?acao=AdmEstrutura&id=$id");
                        $redirectResponse->send();
                    }
                } catch (ValidationException $e) {
                    //Erro de validação, renderiza novamente o form, e envia o objeto usuário que acabou
                    //de ser preenchido com novas informações porém ainda não foi salvo.
                    $this->session->getFlashBag()->set("error", $e->getMessage());
                    $this->request->query->set("estrutura", $estrutura);
                    $this->request->query->set("idiomas", \Idioma::newInstance()->getAll(null, array('descricao'), '*'));
                    $content = $this->getRenderViewInBase("estrutura.php");
                } catch (Exception $e) {
                    $this->session->getFlashBag()->set('error', $e->getMessage());
                    $content = $this->getRenderView(self::$VIEW_ERROR);
                }
                break;
            case self::$DELETE:
                try {
                    //Busca parametro ID.
                    $id = $this->request->query->get('id');
                    $estrutura = new Estrutura($id);
                    $titulo = $estrutura->getTitulo();
                    $estrutura->delete();

                    $this->session->getFlashBag()->set('success', "Estrutura $titulo foi excluída com sucesso.");

                    $redirectResponse = new \Symfony\Component\HttpFoundation\RedirectResponse("?acao=AdmEstrutura&list");
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

