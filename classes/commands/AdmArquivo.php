<?php

namespace commands;

use \Arquivo;
use \Exception;
use \ValidationException;

class AdmArquivo extends Command {

    public function execute() {
        switch ($this->request->getMethod()) {
            case self::$GET:
                try {
                    //Busca parametro ID.
                    $id = $this->request->query->get('id');
                    $excluir = $this->request->query->get('excluir');
                    $baixar = $this->request->query->get('baixar');

                    if (isset($excluir) AND isset($id)) {
                        $arquivo = new \Arquivo($id);
                        $objeto = $arquivo->getObjeto();
                        $idObjeto = $arquivo->getIdObjeto();
                        $arquivo->delete();

                        $this->session->getFlashBag()->set('success', "O arquivo foi excluído com sucesso.");
                        $redirectResponse = new \Symfony\Component\HttpFoundation\RedirectResponse("?acao=Adm$objeto&id=$idObjeto");
                        $redirectResponse->send();
                    } else if (isset($baixar) AND isset($id)) {
                        $arquivo = new \Arquivo($id);
                        $arquivoTipo = end(explode('.', $arquivo->getNome()));
                        $arquivoCaminho = file_get_contents($this->request->request->get('__DIR__') . '/arquivos/uploads/' . $arquivo->getNome());

                        header("Content-type: application/$arquivoTipo");
                        header("Content-type: application/force-download");
                        header('Content-Disposition: attachment; filename="' . $arquivo->getNome() . '"');
                        header("Pragma: no-cache");
                        $content = $arquivoCaminho;
                    }
                } catch (Exception $e) {
                    $this->session->getFlashBag()->set('error', $e->getMessage());
                    $content = $this->getRenderView(self::$VIEW_ERROR);
                }
                break;
        }
        $this->response->setContent($content);
        $this->response->send();
    }

}

