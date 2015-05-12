<?php
$texto = $this->request->query->get('texto');
$pagina = $this->request->query->get('pagina');

$error = $this->session->getFlashBag()->get('error');
Tool::alert("error", $error);

$success = $this->session->getFlashBag()->get('success');
Tool::alert("success", $success);
?>

<script type="text/javascript">
    $(document).ready(function () {
        $('#alterarArquivo').click(function () {
            $('#fArquivo').show();
            $('#btArquivo').hide();
        });
        $('#cancelArquivo').click(function () {
            $('#fArquivo').hide();
            $('#btArquivo').show();
        });
    });
</script>
<div class="panel panel-default tamanhoPadrao">
    <div class="panel-heading">
        <h3 class="panel-title">
            <spam class="glyphicon glyphicon-list-alt"></spam>
            <strong>
                <?php echo (isset($texto) AND $texto->isLoad()) ? "Alteração de Texto" : "Criação de Texto" ?>
            </strong>
        </h3>
    </div>
    <div class="panel-body">
        <div class="row clearfix">
            <div class="col-md-12 column">
                <form class="form-horizontal" role="form" method="POST" action="?acao=AdmTexto&idPagina=<?php echo $pagina->getId();
                echo (isset($texto) AND $texto->isLoad()) ? ("&id=" . $texto->getId()) : NULL ?>" enctype="multipart/form-data">
                    <?php if (isset($texto) AND $texto->isLoad()) { ?>
                        <div class="form-group">
                            <label class="col-sm-2 text-right">Id</label>

                            <div class="col-sm-1">
                                <?php echo isset($texto) ? $texto->getId() : NULL ?>
                            </div>
                            <label class="col-sm-2 text-right">Atualizado em</label>

                            <div class="col-sm-7">
                                <?php echo Tool::converteData("Y-m-d H:i:S", "d/m/Y \á\s H:i", $texto->getDataAtualizado()) . " por " . $texto->getOperadorAtualizacao(); ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="titulo" class="col-sm-2 control-label">Título</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo isset($texto) ? $texto->getTitulo() : NULL ?>" />
                        </div>
                    </div>
                    <?php if (isset($texto) AND in_array($texto->getDescricao(), Config::$uploadFile)) { ?>
                        <div class="form-group">
                            <label for="arquivo" class="col-sm-2 control-label">Arquivo</label>

                            <div class="col-sm-10">
                                <div id="fArquivo" <?php
                                if ($texto->getArquivo()->isLoad()) {
                                    echo "style='display: none; '";
                                }
                                ?>>
                                    <div class="col-sm-12">
                                        <div class="btn btn-success" style="float: left;">
                                            Upload
                                            <input id="arquivo" name="arquivo" value="1" type="file" onchange="document.getElementById('file-falso').value = this.value;" style="position: absolute; cursor: pointer; width: 70px; height: 34px ;top: 0px; left: 15px; z-index: 100; opacity: 0;">
                                        </div>
                                        <div class="col-sm-6">
                                            <input name="file-falso" type="text" id="file-falso" class="form-control" disabled="" style="float: left;" value="<?php
                                            if ($texto->getArquivo()->isLoad()) {
                                                ?><?php echo $texto->getArquivo()->getNome();
                                            } ?>">
                                        </div>
                                    </div>
                                    <?php if (isset($texto) AND $texto->isLoad() AND $texto->getArquivo()->isLoad()) { ?>
                                        <div class="col-sm-12">
                                            <a class="btn btn-danger" href="?acao=AdmTexto&excluirArquivo&idPagina=<?php echo $pagina->getId(); ?>&id=<?php echo $texto->getId(); ?>"><span class="glyphicon glyphicon-remove"></span> Excluir</a>
                                            <a id="cancelArquivo" class="btn btn-warning"><span class="glyphicon glyphicon-ban-circle"></span> Cancelar</a>
                                        </div>
                                    <?php } ?>
                                </div>
                                <?php if (isset($texto) AND $texto->isLoad()) { ?>
                                    <div id="btArquivo" <?php
                                    if (!$texto->getArquivo()->isLoad()) {
                                        echo "style='display: none; '";
                                    }
                                    ?>>
                                        <a href="?acao=AdmTexto&baixarArquivo&idPagina&id=<?php echo $texto->getId(); ?>" class="btn btn-info"><span class="glyphicon glyphicon-download"></span> Baixar</a>
                                        <a id="alterarArquivo" class="btn btn-warning"><span class="glyphicon glyphicon-pencil"></span> Alterar</a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php
                    }
                    if (!isset($texto) OR !in_array($texto->getDescricao(), Config::$titulos)) {
                        ?>
                        <div class="form-group ">
                            <div class="col-sm-12">
                                <?php
                                $nomeEditor = "texto";
                                $conteudoEditor = isset($texto) ? $texto->getTexto() : NULL;
                                include('views/editor.php');
                                ?>
                            </div>
                        </div>
                    <?php
                    } ?>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-success">Salvar</button>
                            <a href="?acao=AdmPagina&idIdioma=<?php echo $pagina->getIdioma(); ?>&id=<?php echo $pagina->getId(); ?>" class="btn btn-default">Voltar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>