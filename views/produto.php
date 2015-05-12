<?php
/** @var Produto $produto */
$produto = $this->request->query->get('produto');
$capa = $this->request->query->get('capa');
$selos = $this->request->query->get('selos');
$idiomas = $this->request->query->get('idiomas');
$categorias = $this->request->query->get('categorias');

$error = $this->session->getFlashBag()->get('error');
Tool::alert("error", $error);

$success = $this->session->getFlashBag()->get('success');
Tool::alert("success", $success);


?>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#alterarCatalogo').click(function () {
                $('#fCatalogo').show();
                $('#btCatalogo').hide();
            });
            $('#cancelCatalogo').click(function () {
                $('#fCatalogo').hide();
                $('#btCatalogo').show();
            });
            $('#alterarTabela').click(function () {
                $('#fTabela').show();
                $('#btTabela').hide();
            });
            $('#cancelTabela').click(function () {
                $('#fTabela').hide();
                $('#btTabela').show();
            });
        });
    </script>
    <div class="panel panel-default tamanhoPadrao">
        <div class="panel-heading">
            <h3 class="panel-title">
                <spam class="glyphicon glyphicon-list-alt"></spam>
                <strong>
                    <?php echo (isset($produto) AND $produto->isLoad()) ? "Alteração de Produto" : "Criação de Produto" ?>
                </strong>
            </h3>
        </div>
        <div class="panel-body">
            <div class="row clearfix">
                <div class="col-md-12 column">
                    <form class="form-horizontal" role="form" method="POST" action="?acao=AdmProduto<?php echo (isset($produto) AND $produto->isLoad()) ? ("&id=" . $produto->getId()) : NULL ?>" enctype="multipart/form-data">
                        <?php if (isset($produto) AND $produto->isLoad()) { ?>
                            <div class="form-group">
                                <label class="col-sm-2 text-right">Id</label>

                                <div class="col-sm-1">
                                    <?php echo isset($produto) ? $produto->getId() : NULL ?>
                                </div>
                                <label class="col-sm-2 text-right">Atualizado em</label>

                                <div class="col-sm-7">
                                    <?php echo Tool::converteData("Y-m-d H:i:S", "d/m/Y \á\s H:i", $produto->getDataAtualizado()) . " por " . $produto->getOperadorAtualizacao(); ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="form-group">
                            <label for="ativo" class="col-sm-2 control-label">Ativo</label>

                            <div class="col-sm-10">
                                <label><input id="ativo" name="ativo" value="1" type="checkbox" <?php echo (isset($produto) AND $produto->getAtivo() OR !isset($produto) OR !$produto->isLoad()) ? "checked" : NULL ?>/></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ativo" class="col-sm-2 control-label">Destaque</label>

                            <div class="col-sm-10">
                                <label><input id="destaque" name="destaque" value="1" type="checkbox" <?php echo (isset($produto) AND $produto->getDestaque()) ? "checked" : NULL ?>/></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="idioma" class="col-sm-2 control-label">Ídioma</label>

                            <div class="col-sm-10">
                                <select class="form-control" name="idioma" id="idioma">
                                    <?php foreach ($idiomas AS $idioma) {
                                        echo "<option value='" . $idioma->getId() . "'" . ((isset($produto) AND $idioma->getId() == $produto->getIdioma()) ? 'selected' : null) . ">" . $idioma->getDescricao() . "</option>";
                                    }?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="categoria" class="col-sm-2 control-label">Categoria</label>

                            <div class="col-sm-10">
                                <select class="form-control" name="categoria" id="categoria">
                                    <?php foreach ($categorias AS $categoria) {
                                        echo "<option value='" . $categoria->getId() . "'" . ((isset($produto) AND $categoria->getId() == $produto->getIdCategoria()) ? 'selected' : null) . ">" . $categoria->getTitulo() . "</option>";
                                    }?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="titulo" class="col-sm-2 control-label">Título</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo isset($produto) ? $produto->getTitulo() : NULL ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="catalogo" class="col-sm-2 control-label">Catálogo</label>

                            <div class="col-sm-10">
                                <div id="fCatalogo" <?php
                                if (isset($produto) AND $produto->getCatalogo()->isLoad()) {
                                    echo "style='display: none; '";
                                }
                                ?>>
                                    <div class="col-sm-12">
                                        <div class="btn btn-success" style="float: left;">
                                            Upload
                                            <input id="catalogo" name="catalogo" value="1" type="file" onchange="document.getElementById('file-falso1').value = this.value;" style="position: absolute; cursor: pointer; width: 70px; height: 34px ;top: 0px; left: 15px; z-index: 100; opacity: 0;">
                                        </div>
                                        <div class="col-sm-6">
                                            <input name="file-falso1" type="text" id="file-falso1" class="form-control" disabled="" style="float: left;" value="<?php
                                            if (isset($produto) AND $produto->getCatalogo()->isLoad()) {
                                                ?><?php echo $produto->getCatalogo()->getNome();
                                            } ?>">
                                        </div>
                                    </div>
                                    <?php if (isset($produto) AND $produto->isLoad() AND $produto->getCatalogo()->isLoad()) { ?>
                                        <div class="col-sm-12">
                                            <a class="btn btn-danger" href="?acao=AdmProduto&excluirCatalogo&id=<?php echo $produto->getId(); ?>"><span class="glyphicon glyphicon-remove"></span> Excluir</a>
                                            <a id="cancelCatalogo" class="btn btn-warning"><span class="glyphicon glyphicon-ban-circle"></span> Cancelar</a>
                                        </div>
                                    <?php } ?>
                                </div>
                                <?php if (isset($produto) AND $produto->isLoad()) { ?>
                                    <div id="btCatalogo" <?php
                                    if (!$produto->getCatalogo()->isLoad()) {
                                        echo "style='display: none; '";
                                    }
                                    ?>>
                                        <a href="?acao=AdmProduto&baixarCatalogo&idPagina&id=<?php echo $produto->getId(); ?>" class="btn btn-info"><span class="glyphicon glyphicon-download"></span> Baixar</a>
                                        <a id="alterarCatalogo" class="btn btn-warning"><span class="glyphicon glyphicon-pencil"></span> Alterar</a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tabela" class="col-sm-2 control-label">Tabela</label>

                            <div class="col-sm-10">
                                <div id="fTabela" <?php
                                if (isset($produto) AND $produto->getTabela()->isLoad()) {
                                    echo "style='display: none; '";
                                }
                                ?>>
                                    <div class="col-sm-12">
                                        <div class="btn btn-success" style="float: left;">
                                            Upload
                                            <input id="tabela" name="tabela" value="1" type="file" onchange="document.getElementById('file-falso2').value = this.value;" style="position: absolute; cursor: pointer; width: 70px; height: 34px ;top: 0px; left: 15px; z-index: 100; opacity: 0;">
                                        </div>
                                        <div class="col-sm-6">
                                            <input name="file-falso2" type="text" id="file-falso2" class="form-control" disabled="" style="float: left;" value="<?php
                                            if (isset($produto) AND $produto->getTabela()->isLoad()) {
                                                ?><?php echo $produto->getTabela()->getNome();
                                            } ?>">
                                        </div>
                                    </div>
                                    <?php if (isset($produto) AND $produto->isLoad() AND $produto->getTabela()->isLoad()) { ?>
                                        <div class="col-sm-12">
                                            <a class="btn btn-danger" href="?acao=AdmProduto&excluirTabela&id=<?php echo $produto->getId(); ?>"><span class="glyphicon glyphicon-remove"></span> Excluir</a>
                                            <a id="cancelTabela" class="btn btn-warning"><span class="glyphicon glyphicon-ban-circle"></span> Cancelar</a>
                                        </div>
                                    <?php } ?>
                                </div>
                                <?php if (isset($produto) AND $produto->isLoad()) { ?>
                                    <div id="btTabela" <?php
                                    if (!$produto->getTabela()->isLoad()) {
                                        echo "style='display: none; '";
                                    }
                                    ?>>
                                        <a href="?acao=AdmProduto&baixarTabela&idPagina&id=<?php echo $produto->getId(); ?>" class="btn btn-info"><span class="glyphicon glyphicon-download"></span> Baixar</a>
                                        <a id="alterarTabela" class="btn btn-warning"><span class="glyphicon glyphicon-pencil"></span> Alterar</a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="col-sm-12">
                                <?php
                                $nomeEditor = "descricao";
                                $conteudoEditor = isset($produto) ? $produto->getDescricao() : NULL;
                                include('views/editor.php');
                                ?>
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
<?php if (isset($produto) AND $produto->isLoad()) { ?>

    <link href="css/uploadfile.css" rel="stylesheet">


    <?php if (count($capa) == 0) { ?>
        <script>

            $(document).ready(function () {
                $('#salvarDescricao').click(function () {
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

                <?php foreach ($capa as $imagem) { ?>
                criaMiniatura('<?php echo $imagem->getId(); ?>', '<?php echo $produto->getId(); ?>', '<?php echo $imagem->getNome(); ?>', '<?php echo $imagem->getDescricao(); ?>');
                <?php } ?>
                var settings = {
                    url: "?acao=AdmImagem&tipo=CAPA&objeto=Produto&idObjeto=<?php echo isset($produto) ? $produto->getId() : NULL ?>",
                    method: "POST",
                    allowedTypes: "jpg,jpeg,png,gif",
                    fileName: "Filedata",
                    multiple: false,
                    showStatusAfterSuccess: false,
                    autoSubmit: true,
                    onSuccess: function (files, data, xhr) {
                        imagem = jQuery.parseJSON(data);
                        if (parseInt(data.id) <= 0) {
                            $("#status").html("<div class='alert alert-danger'>Upload falhou: " + data.erro + "</div>").fadeIn(500).delay(3000).fadeOut(500);
                        } else {
                            criaMiniatura(imagem.id, imagem.idObjeto, imagem.nome, imagem.descricao);
                            $("#status").html("<div class='alert alert-success'>Efetuado Upload com sucesso</div>").fadeIn(500).delay(3000).fadeOut(500);
                            window.location.reload();
                        }

                    },
                    onError: function (files, status, errMsg) {
                        $("#status").html("<div class='alert alert-danger'>Upload falhou</div>").fadeIn(500).delay(3000).fadeOut(500);
                    }
                }
                $("#mulitplefileuploader").uploadFile(settings);
            });
        </script>

    <?php } else { ?>
        <script>
            $(document).ready(function () {
                $('#salvarDescricao').click(function () {
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

                <?php foreach ($capa as $imagem) { ?>
                criaMiniatura('<?php echo $imagem->getId(); ?>', '<?php echo $produto->getId(); ?>', '<?php echo $imagem->getNome(); ?>', '<?php echo $imagem->getDescricao(); ?>');
                <?php } ?>
            });
        </script>
    <?php } ?>
    <script>

        $(document).ready(function () {
            <?php foreach ($selos as $imagem2) { ?>
            criaMiniatura2('<?php echo $imagem2->getId(); ?>', '<?php echo $produto->getId(); ?>', '<?php echo $imagem2->getNome(); ?>', '<?php echo $imagem2->getDescricao(); ?>');
            <?php } ?>
            var settings2 = {
                url: "?acao=AdmImagem&tipo=SELO&objeto=Produto&idObjeto=<?php echo isset($produto) ? $produto->getId() : NULL ?>",
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
                        criaMiniatura2(imagem.id, imagem.idObjeto, imagem.nome, imagem.descricao);
                        $("#status").html("<div class='alert alert-success'>Efetuado Upload com sucesso</div>").fadeIn(500).delay(3000).fadeOut(500);
                    }

                },
                onError: function (files, status, errMsg) {
                    $("#status").html("<div class='alert alert-danger'>Upload falhou</div>").fadeIn(500).delay(3000).fadeOut(500);
                }
            }

            function criaMiniatura2(id, idObjeto, nome, descricao) {
                $.ajax({
                    type: "GET",
                    url: "miniaturaImagem.php",
                    data: {id: id, nome: nome, descricao: descricao, idObjeto: idObjeto}
                }).done(function (msg) {
                    $('#imagens2').append(msg);
                });
            }

            $("#mulitplefileuploader2").uploadFile(settings2);
        });
    </script>
    <div class="row tamanhoPadrao">
        <div id="uploadCapa" class="panel panel-default" style="width: 48%; margin-right: 2%; float: left;">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <spam class="glyphicon glyphicon-list-alt"></spam>
                    <strong>Upload da Capa</strong>
                </h3>
            </div>
            <div class="panel-body">
                <?php if (count($capa) == 0) { ?>

                    <div id="mulitplefileuploader">Upload</div>

                <?php } ?>
                <div class="row col-md-12" id="imagens">
                </div>
            </div>
        </div>
        <div class="panel panel-default" style="width: 48%; margin-left: 2%; float: left;">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <spam class="glyphicon glyphicon-list-alt"></spam>
                    <strong>Upload de Selos</strong>
                </h3>
            </div>
            <div class="panel-body">
                <div id="mulitplefileuploader2">Upload</div>
            </div>
        </div>

    </div>
    <div class="row tamanhoPadrao">
        <div id="status" style="width: 100%"></div>
        <div style="width: 100%" id="imagens2"></div>
    </div>
<?php } ?>