<?php

namespace commands;

use \CategoriaServicos;
use \Exception;
use \ValidationException;

class AdmCategoriaServicos extends Command {

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
                        $categoria = new CategoriaServicos();
                        $categorias = $categoria->getAll(null, array('titulo'), '*');

                        $this->request->query->set("categoriasServicos", $categorias);
                        $content = $this->getRenderViewInBase("categoriasServicos.php");
                    } else {
                        if (isset($id)) {
                            $categoria = new CategoriaServicos($id);
                            //Verifica se o parametro ID foi setado e busca o registro
                            $this->request->query->set("imagens", $categoria->getImagens());
                            $this->request->query->set("categoriaServicos", $categoria);
                        }

                        $content = $this->getRenderViewInBase("categoriaServicos.php");
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

                        $categoria = new CategoriaServicos();
                        $categorias = $categoria->getAll($idioma > 0 ? "idioma='$idioma'" : null, array('titulo'), '*');


                        $this->request->query->set("idiomas", \Idioma::newInstance()->getAll(null, array('descricao'), '*'));
                        $this->request->query->set("idiomaAtual", \Idioma::newInstance($idioma));

                        $this->request->query->set("categoriasServicos", $categorias);
                        $content = $this->getRenderViewInBase("categoriasServicos.php");
                    } else {
                        //Busca campos do formulario
                        $ativo = $this->request->request->get('ativo');
                        $titulo = $this->request->request->get('titulo');
                        $idioma = $this->request->request->get('idioma');

                        $categoria = new CategoriaServicos($id);
                        $categoria->setAtivo($ativo);
                        $categoria->setTitulo($titulo);
                        $categoria->setIdioma($idioma);

                        //Caso seja criação inseri operador
                        if (is_null($id)) {
                            $categoria->setOperadorCadastro($this->session->get('usuario')->getNome());
                            $categoria->setOperadorAtualizacao($this->session->get('usuario')->getNome());
                        } else {
                            $categoria->setOperadorAtualizacao($this->session->get('usuario')->getNome());
                        }
                        $categoria->save(false);

                        $message = "A categoriaServicos " . $categoria->getTitulo();
                        $message .= is_null($id) ? " criada com sucesso." : " alterada com sucesso.";
                        $this->session->getFlashBag()->set('success', $message);

                        $id = $categoria->getId();

                        $redirectResponse = new \Symfony\Component\HttpFoundation\RedirectResponse("?acao=AdmCategoriaServicos&id=$id");
                        $redirectResponse->send();
                    }
                } catch (ValidationException $e) {
                    //Erro de validação, renderiza novamente o form, e envia o objeto usuário que acabou
                    //de ser preenchido com novas informações porém ainda não foi salvo.
                    $this->session->getFlashBag()->set("error", $e->getMessage());
                    $this->request->query->set("categoriaServicos", $categoria);
                    $this->request->query->set("idiomas", \Idioma::newInstance()->getAll(null, array('descricao'), '*'));
                    $content = $this->getRenderViewInBase("categoriaServicos.php");
                } catch (Exception $e) {
                    $this->session->getFlashBag()->set('error', $e->getMessage());
                    $content = $this->getRenderView(self::$VIEW_ERROR);
                }
                break;
            case self::$DELETE:
                try {
                    //Busca parametro ID.
                    $id = $this->request->query->get('id');
                    $categoria = new CategoriaServicos($id);
                    $titulo = $categoria->getTitulo();
                    $categoria->delete();

                    $this->session->getFlashBag()->set('success', "CategoriaServicos $titulo foi excluída com sucesso.");

                    $redirectResponse = new \Symfony\Component\HttpFoundation\RedirectResponse("?acao=AdmCategoriaServicos&list");
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

