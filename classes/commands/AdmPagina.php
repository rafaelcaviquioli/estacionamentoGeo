<?php

namespace commands;

use \Pagina;
use \Exception;
use \ValidationException;

class AdmPagina extends Command {

    public function execute() {
        switch ($this->request->getMethod()) {
            case self::$GET:
                try {
                    //Busca parametro ID.
                    $id = $this->request->query->get('id');
                    $idIdioma = $this->request->query->get('idIdioma');
                    $list = $this->request->query->get('list');


                    $this->request->query->set("idioma", new \Idioma($idIdioma));

                    if (isset($list)) {
                        //Busca todas os registros
                        $pagina = new Pagina();
                        $paginas = $pagina->getAll("idioma='$idIdioma'", array('sequencia'), '*');

                        $this->request->query->set("paginas", $paginas);
                        $content = $this->getRenderViewInBase("paginas.php");
                    } else {
                        if (isset($id)) {
                            //Verifica se o parametro ID foi setado e busca o registro
                            $pagina = new Pagina($id);
                            $this->request->query->set("textos", $pagina->getTextos());
                            $this->request->query->set("imagens", $pagina->getImagens('BANNER'));
                            $this->request->query->set("pagina", $pagina);
                        }

                        $content = $this->getRenderViewInBase("pagina.php");
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
                    $idIdioma = $this->request->query->get('idIdioma');

                    //Busca campos do formulario
                    $ativo = $this->request->request->get('ativo');
                    $menu = $this->request->request->get('menu');

                    $pagina = new Pagina($id);
                    $pagina->setAtivo($ativo);
                    $pagina->setIdioma($idIdioma);
                    $pagina->setMenu($menu);

                    //Caso seja criação inseri operador
                    if (is_null($id)) {
                        $pagina->setOperadorCadastro($this->session->get('usuario')->getNome());
                        $pagina->setOperadorAtualizacao($this->session->get('usuario')->getNome());
                    } else {
                        $pagina->setOperadorAtualizacao($this->session->get('usuario')->getNome());
                    }
                    $pagina->save(false);

                    $message = "A pagina " . $pagina->getMenu();
                    $message .= is_null($id) ? " criada com sucesso." : " alterada com sucesso.";
                    $this->session->getFlashBag()->set('success', $message);

                    $id = $pagina->getId();

                    $redirectResponse = new \Symfony\Component\HttpFoundation\RedirectResponse("?acao=AdmPagina&idIdioma=$idIdioma&id=$id");
                    $redirectResponse->send();

                } catch (ValidationException $e) {
                    //Erro de validação, renderiza novamente o form, e envia o objeto usuário que acabou
                    //de ser preenchido com novas informações porém ainda não foi salvo.
                    $this->session->getFlashBag()->set("error", $e->getMessage());
                    $this->request->query->set("pagina", $pagina);
                    $content = $this->getRenderViewInBase("pagina.php");
                } catch (Exception $e) {
                    $this->session->getFlashBag()->set('error', $e->getMessage());
                    $content = $this->getRenderView(self::$VIEW_ERROR);
                }
                break;
            case self::$DELETE:
                try {
                    //Busca parametro ID.
                    $id = $this->request->query->get('id');
                    $pagina = new Pagina($id);
                    $menu = $pagina->getMenu();
                    $pagina->delete();

                    $this->session->getFlashBag()->set('success', "Pagina $menu foi excluída com sucesso.");

                    $redirectResponse = new \Symfony\Component\HttpFoundation\RedirectResponse("?acao=AdmPagina&list");
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

