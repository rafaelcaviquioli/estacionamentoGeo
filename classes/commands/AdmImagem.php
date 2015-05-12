<?php

namespace commands;

use \Imagem;
use \UploadImagem;
use \Exception;
use \ValidationException;

class AdmImagem extends Command {

    static $destinoUpload = "/imagens/uploads/";

    public function execute() {
        switch ($this->request->getMethod()) {
            case self::$POST:
                try {
                    //Busca parametro ID.
                    $idObjeto = $this->request->query->get('idObjeto');
                    $objeto = $this->request->query->get('objeto');
                    $tipo = $this->request->query->get('tipo');

                    //Upload do arquivo
                    if (!empty($_FILES)) {
                        try {
                            $fileUpload = $fileContent = $this->request->files->get('Filedata');
                            $fileTypes = array('jpg', 'jpeg', 'gif', 'png');
                            if (in_array(strtolower($fileUpload->getClientOriginalExtension()), $fileTypes)) {
                                $newName = $objeto . date('dmYHis') . rand(0, 10000000);
                                while (file_exists($this->request->request->get('__DIR__') . self::$destinoUpload . $newName)) {
                                    $newName = $objeto . date('dmYHis') . rand(0, 10000000);
                                }
                                $imgsize = getimagesize($_FILES['Filedata']['tmp_name']);
                                $uploadImagem = new UploadImagem($_FILES['Filedata']);
                                if ($uploadImagem->uploaded) {
                                    $uploadImagem->file_new_name_body = $newName;
                                    if ($imgsize[0] > 1928) {
                                        $uploadImagem->file_max_size = 10000000000;
                                        $uploadImagem->image_resize = true;
                                        $uploadImagem->image_ratio_y = true;
                                        $uploadImagem->image_x = 1928;
                                    }
                                    $uploadImagem->Process($this->request->request->get('__DIR__') . self::$destinoUpload);
                                    if ($uploadImagem->error) {
                                        $content = array("erro" => $uploadImagem->error);
                                        break;
                                    }
                                    $uploadImagem->Clean();
                                }
                                $imagem = new Imagem();
                                $imagem->setIdObjeto($idObjeto);
                                $imagem->setObjeto($objeto);
                                $imagem->setTipo($tipo);
                                $imagem->setNome($newName . "." . strtolower($fileUpload->getClientOriginalExtension()));
                                $usuario = $this->session->get('usuario');
                                if (isset($usuario)) {
                                    $imagem->setOperadorCadastro($usuario->getNome());
                                }
                                $imagem->save();

                                $content = json_encode(array('erro' => '', 'id' => $imagem->getId(), 'idObjeto' => $imagem->getIdObjeto(), 'nome' => $imagem->getNome(), 'descricao' => $imagem->getDescricao()));
                            } else {
                                $content = 'Tipo de arquivo inválido: ' . $fileUpload->getClientOriginalExtension();
                            }
                        } catch (Exception $e) {
                            $content = $e->getMessage();
                        }
                    } else {
                        $content = "Arquivo não recebido.";
                    }
                    // Fim do Upload do arquivo
                } catch (ValidationException $e) {
                    $content = $e->getMessage();
                }
                break;
            case self::$DELETE:
                //Busca parametro ID.
                $id = $this->request->query->get('id');
                $idObjeto = $this->request->query->get('idObjeto');
                $imagem = new Imagem($id);
                $descricao = $imagem->getDescricao();
                $objeto = $imagem->getObjeto();
                $tipo = $imagem->getTipo();
                $imagem->delete();

                $this->session->getFlashBag()->set('success', "Imagem $descricao excluída com sucesso.");

                $redirectResponse = new \Symfony\Component\HttpFoundation\RedirectResponse("?acao=Adm" . $objeto . "&id=$idObjeto");
                $redirectResponse->send();

                break;
            case self::$PUT:
                try {

                    //Busca parametro ID.
                    $id = $this->request->query->get('id');
                    $idObjeto = $this->request->query->get('idObjeto');
                    $descricao = $this->request->request->get('descricao');

                    $imagem = new Imagem($id);
                    $imagem->setDescricao($descricao);
                    $imagem->save();

                    $this->session->getFlashBag()->set('success', "Imagem $descricao alterada com sucesso.");
                } catch (Exception $e) {
                    $this->session->getFlashBag()->set("error", $e->getMessage());
                }
                $redirectResponse = new \Symfony\Component\HttpFoundation\RedirectResponse("?acao=Adm" . $imagem->getObjeto() . "&id=$idObjeto");
                $redirectResponse->send();
                break;
            default :
                $content = "Não foi possível distinguir o método.";
                break;
        }
        $this->response->setContent($content);
        $this->response->send();
    }

}

?>
