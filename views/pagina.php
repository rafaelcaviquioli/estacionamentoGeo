<?php
$pagina = $this->request->query->get('pagina');
$imagens = $this->request->query->get('imagens');
$idioma = $this->request->query->get('idioma');

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
                    <?php echo (isset($pagina) AND $pagina->isLoad()) ? "Alteração de Página" : "Criação de Página" ?>
                </strong>
            </h3>
        </div>
        <div class="panel-body">
            <div class="row clearfix">
                <div class="col-md-12 column">
                    <form class="form-horizontal" role="form" method="POST" action="?acao=AdmPagina&idIdioma=<?php echo $idioma->getId() ?><?php echo (isset($pagina) AND $pagina->isLoad()) ? ("&id=" . $pagina->getId()) : NULL ?>">
                        <?php if (isset($pagina) AND $pagina->isLoad()) { ?>
                            <div class="form-group">
                                <label class="col-sm-2 text-right">Id</label>

                                <div class="col-sm-1">
                                    <?php echo isset($pagina) ? $pagina->getId() : NULL ?>
                                </div>
                                <label class="col-sm-2 text-right">Atualizado em</label>

                                <div class="col-sm-7">
                                    <?php echo Tool::converteData("Y-m-d H:i:S", "d/m/Y \á\s H:i", $pagina->getDataAtualizado()) . " por " . $pagina->getOperadorAtualizacao(); ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="form-group">
                            <label for="idioma" class="col-sm-2 control-label">Ídioma</label>

                            <div class="col-sm-10">
                                <?php echo $idioma->getDescricao(); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="menu" class="col-sm-2 control-label">Menu</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="menu" name="menu" value="<?php echo isset($pagina) ? $pagina->getMenu() : NULL ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-success">Salvar</button>
                                <a href="?acao=AdmPagina&idIdioma=<?php echo $idioma->getId(); ?>&list" class="btn btn-default">Voltar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


<?php if (isset($pagina) AND $pagina->isLoad()) { ?>

    <?php if (in_array($pagina->getId(), Config::$paginasPermitemNovosTextos) OR count($this->request->query->get('textos')) > 0) { ?>
        <div class="row clearfix tamanhoPadrao">
            <div class="col-md-12 column">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="col-sm-1 col-xs-1">Id</th>
                            <th class="col-sm-5 col-xs-5">Título</th>
                            <th class="col-sm-3 col-xs-3">Atualizado</th>
                            <th class="col-sm-3 col-xs-3">Opções</th>
                        </tr>
                    </thead>
                    <?php if (count($this->request->query->get('textos'))) { ?>
                    <tbody>
                        <?php
                        /** @var Pagina $pagina */
                        foreach ($this->request->query->get('textos') as $texto) {
                            ?>
                            <tr>
                                <td class="col-sm-1 col-xs-1"><?= $texto->getId(); ?></td>
                                <td class="col-sm-5 col-xs-5"><?= $texto->getTItulo(); ?></td>
                                <td class="col-sm-3 col-xs-3"><?= Tool::converteData("Y-m-d H:i:s", "d/m/Y H:i", $texto->getDataAtualizado()); ?></td>
                                <td class='col-sm-3 col-xs-3'>
                                    <div class="hidden-xs">
                                        <a href="?acao=AdmTexto&idPagina=<?php echo $pagina->getId(); ?>&id=<?= $texto->getId(); ?>" class="btn btn-success">Editar</a>
                                        <?php if (!in_array($texto->getId(), Config::$textosPadroes)) { ?>
                                            <button type="button" data-toggle="modal" data-target="#myModal<?= $texto->getId(); ?>" class="btn btn-danger">Excluir</button>
                                        <?php } ?>
                                    </div>
                                    <div class="visible-xs">
                                        <a href="?acao=AdmTexto&idPagina=<?php echo $pagina->getId(); ?>&id=<?= $texto->getId(); ?>" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span></a>
                                        <?php if (!in_array($texto->getId(), Config::$textosPadroes)) { ?>
                                            <button type="button" data-toggle="modal" data-target="#myModal<?= $texto->getId(); ?>" class="btn btn-danger">
                                                <span class="glyphicon glyphicon-remove"></span></button>
                                        <?php } ?>
                                    </div>
                                    <?php if (!in_array($texto->getId(), Config::$textosPadroes)) { ?>
                                        <form name="delete" action="?acao=AdmTexto&id=<?= $texto->getId(); ?>" method="POST">
                                            <input type="hidden" name="_method" value="DELETE" />
                                            <?php Tool::modal("Exclusão de Texto", "Deseja realmente efetuar a exclusão do texto " . $texto->getTitulo() . "?", "Excluir", $texto->getId()); ?>
                                        </form>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php } else { ?>
                    </table>
                    <div class="alert alert-info">Nenhuma página encontrada.</div>
                <?php
                }
                if (in_array($pagina->getId(), Config::$paginasPermitemNovosTextos)) {
                    ?>
                    <a href="?acao=AdmTexto&idPagina=<?php echo $pagina->getId(); ?>" class="btn btn-success">
                        <spam class="glyphicon glyphicon-plus"></spam>
                        Adicionar Texto</a>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
    <br />
    <link href="css/uploadfile.css" rel="stylesheet">
    <div class="panel panel-default tamanhoPadrao">
        <div class="panel-heading">
            <h3 class="panel-title">
                <spam class="glyphicon glyphicon-list-alt"></spam>
                <strong>Upload dos Banners</strong>
            </h3>
        </div>
        <div class="panel-body">
            <div id="mulitplefileuploader">Upload</div>

            <div id="status"></div>
            <script>

                $(document).ready(function () {
                    $('#salvarDescricao').click(function () {
                        alert('apertou');
                        $('#salvar').submit();
                    });
                    function criaMiniatura(id, idObjeto, nome, descricao) {
                        $.ajax({
                            type: "GET",
                            url: "miniaturaImagem.php",
                            data: {id: id, nome: nome, descricao: descricao, idObjeto: idObjeto}
                        }).done(function (msg) {
                            $('#imagens').append(msg);
                        });
                    }

                    <?php foreach ($imagens as $imagem) { ?>
                    criaMiniatura('<?php echo $imagem->getId(); ?>', '<?php echo $pagina->getId(); ?>', '<?php echo $imagem->getNome(); ?>', '<?php echo $imagem->getDescricao(); ?>');
                    <?php } ?>
                    var settings = {
                        url: "?acao=AdmImagem&tipo=BANNER&objeto=Pagina&idObjeto=<?php echo isset($pagina) ? $pagina->getId() : NULL ?>",
                        method: "POST",
                        allowedTypes: "jpg,jpeg,png,gif",
                        fileName: "Filedata",
                        multiple: true,
                        showStatusAfterSuccess: false,
                        autoSubmit: true,
                        onSuccess: function (files, data, xhr) {
                            imagem = jQuery.parseJSON(data);
                            if (parseInt(data.id) <= 0) {
                                $("#status").html("<div class='alert alert-danger'>Upload falhou: " + data.erro + "</div>").fadeIn(500).delay(3000).fadeOut(500);
                            } else {
                                criaMiniatura(imagem.id, imagem.idObjeto, imagem.nome, imagem.descricao);
                                $("#status").html("<div class='alert alert-success'>Efetuado Upload com sucesso</div>").fadeIn(500).delay(3000).fadeOut(500);
                            }

                        },
                        onError: function (files, status, errMsg) {
                            $("#status").html("<div class='alert alert-danger'>Upload falhou</div>").fadeIn(500).delay(3000).fadeOut(500);
                        }
                    }
                    $("#mulitplefileuploader").uploadFile(settings);
                });
            </script>
            <div class="row col-md-12" id="imagens">
            </div>
        </div>
    </div>
<?php } ?>