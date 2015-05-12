<?php

namespace commands;

use \Certificacao;
use \Exception;
use \ValidationException;

class AdmCertificacao extends Command {

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
                        $certificacao = new Certificacao();
                        $certificacoes = $certificacao->getAll(null, array('titulo'), '*');


                        $this->request->query->set("certificacoes", $certificacoes);
                        $content = $this->getRenderViewInBase("certificacoes.php");
                    } else {
                        if (isset($id)) {
                            $certificacao = new Certificacao($id);
                            //Verifica se o parametro ID foi setado e busca o registro
                            $this->request->query->set("imagens", $certificacao->getImagens());
                            $this->request->query->set("certificacao", $certificacao);
                        }

                        $content = $this->getRenderViewInBase("certificacao.php");
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

                        $certificacao = new Certificacao();
                        $certificacoes = $certificacao->getAll($idioma > 0 ? "idioma='$idioma'" : null, array('titulo'), '*');

                        $this->request->query->set("idiomas", \Idioma::newInstance()->getAll(null, array('descricao'), '*'));
                        $this->request->query->set("idiomaAtual", \Idioma::newInstance($idioma));

                        $this->request->query->set("certificacoes", $certificacoes);
                        $content = $this->getRenderViewInBase("certificacoes.php");
                    } else {
                        //Busca campos do formulario
                        $ativo = $this->request->request->get('ativo');
                        $titulo = $this->request->request->get('titulo');
                        $descricao = $this->request->request->get('descricao');
                        $idioma = $this->request->request->get('idioma');

                        $certificacao = new Certificacao($id);
                        $certificacao->setAtivo($ativo);
                        $certificacao->setTitulo($titulo);
                        $certificacao->setDescricao($descricao);
                        $certificacao->setIdioma($idioma);

                        //Caso seja criação inseri operador
                        if (is_null($id)) {
                            $certificacao->setOperadorCadastro($this->session->get('usuario')->getNome());
                            $certificacao->setOperadorAtualizacao($this->session->get('usuario')->getNome());
                        } else {
                            $certificacao->setOperadorAtualizacao($this->session->get('usuario')->getNome());
                        }
                        $certificacao->save(false);

                        $message = "A certificação " . $certificacao->getTitulo();
                        $message .= is_null($id) ? " criada com sucesso." : " alterada com sucesso.";
                        $this->session->getFlashBag()->set('success', $message);

                        $id = $certificacao->getId();

                        $redirectResponse = new \Symfony\Component\HttpFoundation\RedirectResponse("?acao=AdmCertificacao&id=$id");
                        $redirectResponse->send();
                    }
                } catch (ValidationException $e) {
                    //Erro de validação, renderiza novamente o form, e envia o objeto usuário que acabou
                    //de ser preenchido com novas informações porém ainda não foi salvo.
                    $this->session->getFlashBag()->set("error", $e->getMessage());
                    $this->request->query->set("certificacao", $certificacao);
                    $this->request->query->set("idiomas", \Idioma::newInstance()->getAll(null, array('descricao'), '*'));
                    $content = $this->getRenderViewInBase("certificacao.php");
                } catch (Exception $e) {
                    $this->session->getFlashBag()->set('error', $e->getMessage());
                    $content = $this->getRenderView(self::$VIEW_ERROR);
                }
                break;
            case self::$DELETE:
                try {
                    //Busca parametro ID.
                    $id = $this->request->query->get('id');
                    $certificacao = new Certificacao($id);
                    $titulo = $certificacao->getTitulo();
                    $certificacao->delete();

                    $this->session->getFlashBag()->set('success', "Certificação $titulo foi excluída com sucesso.");

                    $redirectResponse = new \Symfony\Component\HttpFoundation\RedirectResponse("?acao=AdmCertificacao&list");
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

