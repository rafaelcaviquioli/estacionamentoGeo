<?php
$categoria = $this->request->query->get('categoriaServicos');
$idiomas = $this->request->query->get('idiomas');
$imagens = $this->request->query->get('imagens');

$error = $this->session->getFlashBag()->get('error');
Tool::alert("error", $error);

$success = $this->session->getFlashBag()->get('success');
Tool::alert("success", $success);
?>
    <div class="panel panel-default tamanhoPadrao">
        <div class="panel-heading">
            <h3 class="panel-title">
                <spam class="glyphicon glyphicon-list-alt"></spam>
                <strong>
                    <?php echo (isset($categoria) AND $categoria->isLoad()) ? "Alteração de Categoria" : "Criação de Categoria" ?>
                </strong>
            </h3>
        </div>
        <div class="panel-body">
            <div class="row clearfix">
                <div class="col-md-12 column">
                    <form class="form-horizontal" role="form" method="POST" action="?acao=AdmCategoriaServicos<?php echo (isset($categoria) AND $categoria->isLoad()) ? ("&id=" . $categoria->getId()) : NULL ?>">
                        <?php if (isset($categoria) AND $categoria->isLoad()) { ?>
                            <div class="form-group">
                                <label class="col-sm-2 text-right">Id</label>

                                <div class="col-sm-1">
                                    <?php echo isset($categoria) ? $categoria->getId() : NULL ?>
                                </div>
                                <label class="col-sm-2 text-right">Atualizado em</label>

                                <div class="col-sm-7">
                                    <?php echo Tool::converteData("Y-m-d H:i:S", "d/m/Y \á\s H:i", $categoria->getDataAtualizado()) . " por " . $categoria->getOperadorAtualizacao(); ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="form-group">
                            <label for="ativo" class="col-sm-2 control-label">Ativo</label>

                            <div class="col-sm-10">
                                <label><input id="ativo" name="ativo" value="1" type="checkbox" <?php echo (isset($categoria) AND $categoria->getAtivo() OR !isset($categoria) OR !$categoria->isLoad()) ? "checked" : NULL ?>/></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="titulo" class="col-sm-2 control-label">Ídioma</label>

                            <div class="col-sm-10">
                                <select class="form-control" name="idioma" id="idioma">
                                    <?php foreach ($idiomas AS $idioma) {
                                        echo "<option value='" . $idioma->getId() . "'" . ((isset($categoria) AND $idioma->getId() == $categoria->getIdioma()) ? 'selected' : null) . ">" . $idioma->getDescricao() . "</option>";
                                    }?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="titulo" class="col-sm-2 control-label">Título</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo isset($categoria) ? $categoria->getTitulo() : NULL ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-success">Salvar</button>
                                <button type="button" class="btn btn-default" id="back">Voltar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>