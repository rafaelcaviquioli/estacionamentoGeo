<?php

namespace commands;

use \Texto;
use \Exception;
use \ValidationException;

class AdmTexto extends Command {

    public function execute() {
        switch ($this->request->getMethod()) {
            case self::$GET:
                try {
                    //Busca parametro ID.
                    $id = $this->request->query->get('id');
                    $excluirArquivo = $this->request->query->get('excluirArquivo');
                    $baixarArquivo = $this->request->query->get('baixarArquivo');
                    $idPagina = $this->request->query->get('idPagina');
                    $list = $this->request->query->get('list');

                    $this->request->query->set("pagina", new \Pagina($idPagina));

                    if (isset($list)) {
                        //Busca todas os registros
                        $texto = new Texto();
                        $textos = $texto->getAll(null, array('titulo'), '*');

                        $this->request->query->set("textos", $textos);
                        $content = $this->getRenderViewInBase("textos.php");
                    } else if (isset($excluirArquivo) AND isset($id)) {
                        $texto = new Texto($id);
                        $texto->getArquivo()->delete();
                        $texto->getArquivo(true);
                        $this->request->query->set("texto", $texto);

                        $this->session->getFlashBag()->set('success', "O arquivo foi excluído com sucesso.");
                        $redirectResponse = new \Symfony\Component\HttpFoundation\RedirectResponse("?acao=AdmTexto&id=$id");
                        $redirectResponse->send();
                    } else if (isset($baixarArquivo) AND isset($id)) {
                        $texto = new Texto($id);
                        $arquivoTipo = end(explode('.', $texto->getArquivo()->getNome()));
                        $arquivoCaminho = file_get_contents($this->request->request->get('__DIR__') . '/arquivos/uploads/' . $texto->getArquivo()->getNome());

                        header("Content-type: application/$arquivoTipo");
                        header("Content-type: application/force-download");
                        header('Content-Disposition: attachment; filename="' . $texto->getArquivo()->getNome() . '"');
                        header("Pragma: no-cache");
                        $content = $arquivoCaminho;
                    } else {
                        if (isset($id)) {
                            $texto = new Texto($id);
                            //Verifica se o parametro ID foi setado e busca o registro
                            $this->request->query->set("texto", $texto);
                        }

                        $content = $this->getRenderViewInBase("texto.php");
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
                    $idPagina = $this->request->query->get('idPagina');

                    //Busca campos do formulario
                    $ativo = $this->request->request->get('ativo');
                    $titulo = $this->request->request->get('titulo');
                    $textoDesc = $this->request->request->get('texto');

                    $texto = new Texto($id);
                    $texto->setAtivo($ativo);
                    $texto->setIdPagina($idPagina);
                    $texto->setTitulo($titulo);
                    $texto->setTexto($textoDesc);

                    //Caso seja criação inseri operador
                    if (is_null($id)) {
                        $texto->setOperadorCadastro($this->session->get('usuario')->getNome());
                        $texto->setOperadorAtualizacao($this->session->get('usuario')->getNome());
                    } else {
                        $texto->setOperadorAtualizacao($this->session->get('usuario')->getNome());
                    }
                    $texto->save(false);

                    //Upload do arquivo
                    if ($_FILES['arquivo']['name'] != '') {
                        $fileUpload = $fileContent = $this->request->files->get('arquivo');
                        $fileTypes = array('doc', 'docx', 'txt', 'pdf', 'odt');
                        if (in_array(strtolower($fileUpload->getClientOriginalExtension()), $fileTypes)) {
                            $newName = $fileUpload->getClientOriginalName();
                            $fileUpload->move($this->request->request->get('__DIR__') . '/arquivos/uploads/', $newName);

                            if ($texto->getArquivo()->isLoad()) {
                                $texto->getArquivo()->delete();
                                $texto->getArquivo(true);
                            }
                            $arquivo = new \Arquivo();
                            $arquivo->setIdObjeto($texto->getId());
                            $arquivo->setObjeto('Texto');
                            $arquivo->setNome($newName);
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

                    $message = "O texto " . $texto->getTitulo();
                    $message .= is_null($id) ? " criado com sucesso." : " alterado com sucesso.";
                    $this->session->getFlashBag()->set('success', $message);

                    $id = $texto->getId();

                    $redirectResponse = new \Symfony\Component\HttpFoundation\RedirectResponse("?acao=AdmTexto&idPagina=$idPagina&id=$id");
                    $redirectResponse->send();

                } catch (ValidationException $e) {
                    //Erro de validação, renderiza novamente o form, e envia o objeto usuário que acabou
                    //de ser preenchido com novas informações porém ainda não foi salvo.
                    $this->session->getFlashBag()->set("error", $e->getMessage());
                    $this->request->query->set("texto", $texto);
                    $content = $this->getRenderViewInBase("texto.php");
                } catch (Exception $e) {
                    $this->session->getFlashBag()->set('error', $e->getMessage());
                    $content = $this->getRenderView(self::$VIEW_ERROR);
                }
                break;
            case self::$DELETE:
                try {
                    //Busca parametro ID.
                    $id = $this->request->query->get('id');
                    $texto = new Texto($id);
                    $titulo = $texto->getTitulo();
                    $texto->delete();

                    $this->session->getFlashBag()->set('success', "Texto $titulo foi excluído com sucesso.");

                    $redirectResponse = new \Symfony\Component\HttpFoundation\RedirectResponse("?acao=AdmTexto&list");
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

