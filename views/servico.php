<?php
$servico = $this->request->query->get('servico');
$idiomas = $this->request->query->get('idiomas');
$imagens = $this->request->query->get('imagens');
$categorias = $this->request->query->get('categorias');

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
                    <?php echo (isset($servico) AND $servico->isLoad()) ? "Alteração de Serviço" : "Criação de Serviço" ?>
                </strong>
            </h3>
        </div>
        <div class="panel-body">
            <div class="row clearfix">
                <div class="col-md-12 column">
                    <form class="form-horizontal" role="form" method="POST" action="?acao=AdmServico<?php echo (isset($servico) AND $servico->isLoad()) ? ("&id=" . $servico->getId()) : NULL ?>">
                        <?php if (isset($servico) AND $servico->isLoad()) { ?>
                            <div class="form-group">
                                <label class="col-sm-2 text-right">Id</label>

                                <div class="col-sm-1">
                                    <?php echo isset($servico) ? $servico->getId() : NULL ?>
                                </div>
                                <label class="col-sm-2 text-right">Atualizado em</label>

                                <div class="col-sm-7">
                                    <?php echo Tool::converteData("Y-m-d H:i:S", "d/m/Y \á\s H:i", $servico->getDataAtualizado()) . " por " . $servico->getOperadorAtualizacao(); ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="form-group">
                            <label for="ativo" class="col-sm-2 control-label">Ativo</label>

                            <div class="col-sm-10">
                                <label><input id="ativo" name="ativo" value="1" type="checkbox" <?php echo (isset($servico) AND $servico->getAtivo() OR !isset($servico) OR !$servico->isLoad()) ? "checked" : NULL ?>/></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="principal" class="col-sm-2 control-label">Princípal</label>

                            <div class="col-sm-10">
                                <label><input id="principal" name="principal" value="1" type="checkbox" <?php echo (isset($servico) AND $servico->getPrincipal()) ? "checked" : NULL ?>/></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="titulo" class="col-sm-2 control-label">Ídioma</label>

                            <div class="col-sm-10">
                                <select class="form-control" name="idioma" id="idioma">
                                    <?php foreach ($idiomas AS $idioma) {
                                        echo "<option value='" . $idioma->getId() . "'" . ((isset($servico) AND $idioma->getId() == $servico->getIdioma()) ? 'selected' : null) . ">" . $idioma->getDescricao() . "</option>";
                                    }?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="categoria" class="col-sm-2 control-label">Categoria</label>

                            <div class="col-sm-10">
                                <select class="form-control" name="categoria" id="categoria">
                                    <?php foreach ($categorias AS $categoria) {
                                        echo "<option value='" . $categoria->getId() . "'" . ((isset($servico) AND $categoria->getId() == $servico->getIdCategoria()) ? 'selected' : null) . ">" . $categoria->getTitulo() . "</option>";
                                    }?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="titulo" class="col-sm-2 control-label">Título</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo isset($servico) ? $servico->getTitulo() : NULL ?>" />
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="col-sm-12">
                                <?php
                                $nomeEditor = "descricao";
                                $conteudoEditor = isset($servico) ? $servico->getDescricao() : NULL;
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

<?php if (isset($servico) AND $servico->isLoad()) { ?>
    <link href="css/uploadfile.css" rel="stylesheet">
    <div class="panel panel-default tamanhoPadrao">
        <div class="panel-heading">
            <h3 class="panel-title">
                <spam class="glyphicon glyphicon-list-alt"></spam>
                <strong>Upload de imagens</strong>
            </h3>
        </div>
        <div class="panel-body">
            <?php if (count($imagens) == 0) { ?>

                <div id="mulitplefileuploader">Upload</div>

                <div id="status"></div>

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

                        <?php foreach ($imagens as $imagem) { ?>
                        criaMiniatura('<?php echo $imagem->getId(); ?>', '<?php echo $servico->getId(); ?>', '<?php echo $imagem->getNome(); ?>', '<?php echo $imagem->getDescricao(); ?>');
                        <?php } ?>
                        var settings = {
                            url: "?acao=AdmImagem&objeto=Servico&idObjeto=<?php echo isset($servico) ? $servico->getId() : NULL ?>",
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
                                    $("#status").html("<div class='alert alert-success'>Efetuado Upload com sucesso</div>").fadeIn(500).delay(3000).fadeOut(500).remove();
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

            <?php }else{ ?>
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

                        <?php foreach ($imagens as $imagem) { ?>
                        criaMiniatura('<?php echo $imagem->getId(); ?>', '<?php echo $servico->getId(); ?>', '<?php echo $imagem->getNome(); ?>', '<?php echo $imagem->getDescricao(); ?>');
                        <?php } ?>
                    });
                </script>
            <?php } ?>
            <div class="row col-md-12" id="imagens">
            </div>
        </div>
    </div>
<?php } ?>