<?php

namespace commands;

use \Servico;
use \Exception;
use \ValidationException;

class AdmServico extends Command {

    public function execute() {
        switch ($this->request->getMethod()) {
            case self::$GET:
                try {
                    //Busca parametro ID.
                    $id = $this->request->query->get('id');
                    $list = $this->request->query->get('list');

                    $this->request->query->set("idiomas", \Idioma::newInstance()->getAll(null, array('descricao'), '*'));
                    $this->request->query->set("categorias", \CategoriaServicos::newInstance()->getAll(null, array('titulo'), '*'));
                    if (isset($list)) {
                        //Busca todas os registros
                        $servico = new Servico();
                        $servicos = $servico->getAll(null, array('titulo'), '*');

                        $this->request->query->set("servicos", $servicos);
                        $content = $this->getRenderViewInBase("servicos.php");
                    } else {
                        if (isset($id)) {
                            $servico = new Servico($id);
                            //Verifica se o parametro ID foi setado e busca o registro
                            $this->request->query->set("imagens", $servico->getImagens());
                            $this->request->query->set("servico", $servico);
                        }

                        $content = $this->getRenderViewInBase("servico.php");
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
                        $idCategoria = $this->request->request->get('categoria');

                        $where = array();
                        if ($idioma > 0) {
                            $where[] = "idioma='$idioma'";
                        }
                        if ($idCategoria > 0) {
                            $where[] = "idCategoria='$idCategoria'";
                        }
                        $where = count($where) > 0 ? implode(' AND ', $where) : null;

                        $servico = new Servico();
                        $servicos = $servico->getAll($where, array('titulo'), '*');

                        $this->request->query->set("idiomas", \Idioma::newInstance()->getAll(null, array('descricao'), '*'));
                        $this->request->query->set("categorias", \CategoriaServicos::newInstance()->getAll(null, array('titulo'), '*'));
                        $this->request->query->set("idiomaAtual", \Idioma::newInstance($idioma));
                        $this->request->query->set("categoriaAtual", \CategoriaServicos::newInstance($idCategoria));

                        $this->request->query->set("servicos", $servicos);
                        $content = $this->getRenderViewInBase("servicos.php");
                    } else {
                        //Busca campos do formulario
                        $ativo = $this->request->request->get('ativo');
                        $principal = $this->request->request->get('principal');
                        $titulo = $this->request->request->get('titulo');
                        $descricao = $this->request->request->get('descricao');
                        $idioma = $this->request->request->get('idioma');
                        $idCategoria = $this->request->request->get('categoria');

                        $servico = new Servico($id);
                        $servico->setAtivo($ativo);
                        $servico->setPrincipal($principal);
                        $servico->setTitulo($titulo);
                        $servico->setDescricao($descricao);
                        $servico->setIdioma($idioma);
                        $servico->setIdCategoria($idCategoria);

                        //Caso seja criação inseri operador
                        if (is_null($id)) {
                            $servico->setOperadorCadastro($this->session->get('usuario')->getNome());
                            $servico->setOperadorAtualizacao($this->session->get('usuario')->getNome());
                        } else {
                            $servico->setOperadorAtualizacao($this->session->get('usuario')->getNome());
                        }
                        $servico->save(false);

                        $message = "O serviço " . $servico->getTitulo();
                        $message .= is_null($id) ? " criado com sucesso." : " alterado com sucesso.";
                        $this->session->getFlashBag()->set('success', $message);

                        $id = $servico->getId();

                        $redirectResponse = new \Symfony\Component\HttpFoundation\RedirectResponse("?acao=AdmServico&id=$id");
                        $redirectResponse->send();
                    }
                } catch (ValidationException $e) {
                    //Erro de validação, renderiza novamente o form, e envia o objeto usuário que acabou
                    //de ser preenchido com novas informações porém ainda não foi salvo.
                    $this->session->getFlashBag()->set("error", $e->getMessage());
                    $this->request->query->set("servico", $servico);
                    $this->request->query->set("idiomas", \Idioma::newInstance()->getAll(null, array('descricao'), '*'));
                    $this->request->query->set("categorias", \CategoriaServicos::newInstance()->getAll(null, array('titulo'), '*'));

                    $content = $this->getRenderViewInBase("servico.php");
                } catch (Exception $e) {
                    $this->session->getFlashBag()->set('error', $e->getMessage());
                    $content = $this->getRenderView(self::$VIEW_ERROR);
                }
                break;
            case self::$DELETE:
                try {
                    //Busca parametro ID.
                    $id = $this->request->query->get('id');
                    $servico = new Servico($id);
                    $titulo = $servico->getTitulo();
                    $servico->delete();

                    $this->session->getFlashBag()->set('success', "Serviço $titulo foi excluído com sucesso.");

                    $redirectResponse = new \Symfony\Component\HttpFoundation\RedirectResponse("?acao=AdmServico&list");
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

