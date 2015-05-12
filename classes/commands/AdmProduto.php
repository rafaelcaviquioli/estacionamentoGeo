<?php

namespace commands;

use \Produto;
use \Exception;
use \ValidationException;

class AdmProduto extends Command {

    public function execute() {
        switch ($this->request->getMethod()) {
            case self::$GET:
                try {
                    //Busca parametro ID.
                    $id = $this->request->query->get('id');
                    $list = $this->request->query->get('list');
                    $excluirCatalogo = $this->request->query->get('excluirCatalogo');
                    $baixarCatalogo = $this->request->query->get('baixarCatalogo');
                    $excluirTabela = $this->request->query->get('excluirTabela');
                    $baixarTabela = $this->request->query->get('baixarTabela');

                    if (isset($list)) {
                        //Busca todas os registros
                        $produto = new Produto();
                        $produtos = $produto->getAll(null, array('titulo'), '*');

                        $this->request->query->set("idiomas", \Idioma::newInstance()->getAll(null, array('descricao'), '*'));
                        $this->request->query->set("categorias", \Categoria::newInstance()->getAll(null, array('titulo'), '*'));

                        $this->request->query->set("produtos", $produtos);
                        $content = $this->getRenderViewInBase("produtos.php");
                    } else if (isset($excluirCatalogo) AND isset($id)) {
                        $produto = new Produto($id);
                        $produto->getCatalogo()->delete();
                        $produto->getCatalogo(true);

                        $this->session->getFlashBag()->set('success', "O arquivo foi excluído com sucesso.");
                        $redirectResponse = new \Symfony\Component\HttpFoundation\RedirectResponse("?acao=AdmTexto&id=$id");
                        $redirectResponse->send();
                    } else if (isset($baixarCatalogo) AND isset($id)) {
                        $produto = new Produto($id);
                        $arquivoTipo = end(explode('.', $produto->getCatalogo()->getNome()));
                        $arquivoCaminho = file_get_contents($this->request->request->get('__DIR__') . '/arquivos/uploads/' . $produto->getCatalogo()->getNome());

                        header("Content-type: application/$arquivoTipo");
                        header("Content-type: application/force-download");
                        header('Content-Disposition: attachment; filename="' . $produto->getCatalogo()->getNome() . '"');
                        header("Pragma: no-cache");
                        $content = $arquivoCaminho;
                    } else if (isset($excluirTabela) AND isset($id)) {
                        $produto = new Produto($id);
                        $produto->getTabela()->delete();
                        $produto->getTabela(true);

                        $this->session->getFlashBag()->set('success', "O arquivo foi excluído com sucesso.");
                        $redirectResponse = new \Symfony\Component\HttpFoundation\RedirectResponse("?acao=AdmTexto&id=$id");
                        $redirectResponse->send();
                    } else if (isset($baixarTabela) AND isset($id)) {
                        $produto = new Produto($id);
                        $arquivoTipo = end(explode('.', $produto->getTabela()->getNome()));
                        $arquivoCaminho = file_get_contents($this->request->request->get('__DIR__') . '/arquivos/uploads/' . $produto->getTabela()->getNome());

                        header("Content-type: application/$arquivoTipo");
                        header("Content-type: application/force-download");
                        header('Content-Disposition: attachment; filename="' . $produto->getTabela()->getNome() . '"');
                        header("Pragma: no-cache");
                        $content = $arquivoCaminho;
                    } else {
                        if (isset($id)) {
                            $produto = new Produto($id);
                            //Verifica se o parametro ID foi setado e busca o registro
                            $this->request->query->set("capa", $produto->getImagens('CAPA'));
                            $this->request->query->set("selos", $produto->getImagens('SELO'));
                            $this->request->query->set("produto", $produto);
                        }

                        $this->request->query->set("idiomas", \Idioma::newInstance()->getAll(null, array('descricao'), '*'));
                        $this->request->query->set("categorias", \Categoria::newInstance()->getAll(null, array('titulo'), '*'));

                        $content = $this->getRenderViewInBase("produto.php");
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
                        $produto = new Produto();
                        $produtos = $produto->getAll($where, array('titulo'), '*');

                        $this->request->query->set("idiomas", \Idioma::newInstance()->getAll(null, array('descricao'), '*'));
                        $this->request->query->set("categorias", \Categoria::newInstance()->getAll(null, array('titulo'), '*'));
                        $this->request->query->set("idiomaAtual", \Idioma::newInstance($idioma));
                        $this->request->query->set("categoriaAtual", \Categoria::newInstance($idCategoria));

                        $this->request->query->set("produtos", $produtos);
                        $content = $this->getRenderViewInBase("produtos.php");
                    } else {
                        //Busca campos do formulario
                        $ativo = $this->request->request->get('ativo');
                        $destaque = $this->request->request->get('destaque');
                        $titulo = $this->request->request->get('titulo');
                        $descricao = $this->request->request->get('descricao');
                        $idioma = $this->request->request->get('idioma');
                        $idCategoria = $this->request->request->get('categoria');

                        $produto = new Produto($id);
                        $produto->setAtivo($ativo);
                        $produto->setDestaque($destaque);
                        $produto->setTitulo($titulo);
                        $produto->setDescricao($descricao);
                        $produto->setIdioma($idioma);
                        $produto->setIdCategoria($idCategoria);

                        //Caso seja criação inseri operador
                        if (is_null($id)) {
                            $produto->setOperadorCadastro($this->session->get('usuario')->getNome());
                            $produto->setOperadorAtualizacao($this->session->get('usuario')->getNome());
                        } else {
                            $produto->setOperadorAtualizacao($this->session->get('usuario')->getNome());
                        }
                        $produto->save(false);

                        //Upload do arquivo
                        if ($_FILES['tabela']['name'] != '') {
                            $fileUpload = $fileContent = $this->request->files->get('tabela');
                            $fileTypes = array('doc', 'docx', 'txt', 'pdf', 'odt');
                            if (in_array(strtolower($fileUpload->getClientOriginalExtension()), $fileTypes)) {
                                if ($produto->getTabela()->isLoad()) {
                                    $produto->getTabela()->delete();
                                    $produto->getTabela(true);
                                }

                                $newName = $fileUpload->getClientOriginalName();
                                $i = 0;
                                while (file_exists($this->request->request->get('__DIR__') . '/arquivos/uploads/' . $newName)) {
                                    $i++;
                                    $newName = str_replace('.' . $fileUpload->getClientOriginalExtension(), '', $fileUpload->getClientOriginalName()) . "($i)" . $fileUpload->getClientOriginalExtension();
                                }
                                $fileUpload->move($this->request->request->get('__DIR__') . '/arquivos/uploads/', $newName);

                                $arquivo = new \Arquivo();
                                $arquivo->setIdObjeto($produto->getId());
                                $arquivo->setObjeto('Produto');
                                $arquivo->setNome($newName);
                                $arquivo->setTipo('TABELA');
                                $usuario = $this->session->get('usuario');
                                if (isset($usuario)) {
                                    $arquivo->setOperadorCadastro($usuario->getNome());
                                }
                                $arquivo->save();
                            } else {
                                $this->session->getFlashBag()->set('error', 'Tipo de arquivo inválido: ' . $fileUpload->getClientOriginalExtension());
                            }
                        }
                        if ($_FILES['catalogo']['name'] != '') {
                            $fileUpload = $fileContent = $this->request->files->get('catalogo');
                            $fileTypes = array('doc', 'docx', 'txt', 'pdf', 'odt');
                            if (in_array(strtolower($fileUpload->getClientOriginalExtension()), $fileTypes)) {
                                if ($produto->getCatalogo()->isLoad()) {
                                    $produto->getCatalogo()->delete();
                                    $produto->getCatalogo(true);
                                }

                                $newName = $fileUpload->getClientOriginalName();
                                $i = 0;
                                while (file_exists($this->request->request->get('__DIR__') . '/arquivos/uploads/' . $newName)) {
                                    $i++;
                                    $newName = str_replace('.' . $fileUpload->getClientOriginalExtension(), '', $fileUpload->getClientOriginalName()) . "($i)." . $fileUpload->getClientOriginalExtension();
                                }
                                $fileUpload->move($this->request->request->get('__DIR__') . '/arquivos/uploads/', $newName);

                                $arquivo = new \Arquivo();
                                $arquivo->setIdObjeto($produto->getId());
                                $arquivo->setObjeto('Produto');
                                $arquivo->setNome($newName);
                                $arquivo->setTipo('CATALOGO');
                                $usuario = $this->session->get('usuario');
                                if (isset($usuario)) {
                                    $arquivo->setOperadorCadastro($usuario->getNome());
                                }
                                $arquivo->save();
                            } else {
                                $this->session->getFlashBag()->set('error', 'Tipo de arquivo inválido: ' . $fileUpload->getClientOriginalExtension());
                            }
                        }
                        // Fim do Upload do arquivo

                        $message = "O produto " . $produto->getTitulo();
                        $message .= is_null($id) ? " criado com sucesso." : " alterado com sucesso.";
                        $this->session->getFlashBag()->set('success', $message);

                        $id = $produto->getId();

                        $redirectResponse = new \Symfony\Component\HttpFoundation\RedirectResponse("?acao=AdmProduto&id=$id");
                        $redirectResponse->send();
                    }
                } catch (ValidationException $e) {
                    //Erro de validação, renderiza novamente o form, e envia o objeto usuário que acabou
                    //de ser preenchido com novas informações porém ainda não foi salvo.
                    $this->session->getFlashBag()->set("error", $e->getMessage());
                    $this->request->query->set("produto", $produto);

                    $this->request->query->set("idiomas", \Idioma::newInstance()->getAll(null, array('descricao'), '*'));
                    $this->request->query->set("categorias", \Categoria::newInstance()->getAll(null, array('titulo'), '*'));
                    $content = $this->getRenderViewInBase("produto.php");
                } catch (Exception $e) {
                    $this->session->getFlashBag()->set('error', $e->getMessage());
                    $content = $this->getRenderView(self::$VIEW_ERROR);
                }
                break;
            case self::$DELETE:
                try {
                    //Busca parametro ID.
                    $id = $this->request->query->get('id');
                    $produto = new Produto($id);
                    $titulo = $produto->getTitulo();
                    $produto->delete();

                    $this->session->getFlashBag()->set('success', "Produto $titulo foi excluído com sucesso.");

                    $redirectResponse = new \Symfony\Component\HttpFoundation\RedirectResponse("?acao=AdmProduto&list");
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

