<?php
$estrutura = $this->request->query->get('estrutura');
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
                    <?php echo (isset($estrutura) AND $estrutura->isLoad()) ? "Alteração de Estrutura" : "Criação de Estrutura" ?>
                </strong>
            </h3>
        </div>
        <div class="panel-body">
            <div class="row clearfix">
                <div class="col-md-12 column">
                    <form class="form-horizontal" role="form" method="POST" action="?acao=AdmEstrutura<?php echo (isset($estrutura) AND $estrutura->isLoad()) ? ("&id=" . $estrutura->getId()) : NULL ?>">
                        <?php if (isset($estrutura) AND $estrutura->isLoad()) { ?>
                            <div class="form-group">
                                <label class="col-sm-2 text-right">Id</label>

                                <div class="col-sm-1">
                                    <?php echo isset($estrutura) ? $estrutura->getId() : NULL ?>
                                </div>
                                <label class="col-sm-2 text-right">Atualizado em</label>

                                <div class="col-sm-7">
                                    <?php echo Tool::converteData("Y-m-d H:i:S", "d/m/Y \á\s H:i", $estrutura->getDataAtualizado()) . " por " . $estrutura->getOperadorAtualizacao(); ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="form-group">
                            <label for="ativo" class="col-sm-2 control-label">Ativo</label>

                            <div class="col-sm-10">
                                <label><input id="ativo" name="ativo" value="1" type="checkbox" <?php echo (isset($estrutura) AND $estrutura->getAtivo() OR !isset($estrutura) OR !$estrutura->isLoad()) ? "checked" : NULL ?>/></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="titulo" class="col-sm-2 control-label">Ídioma</label>

                            <div class="col-sm-10">
                                <select class="form-control" name="idioma" id="idioma">
                                    <?php foreach ($idiomas AS $idioma) {
                                        echo "<option value='" . $idioma->getId() . "'" . ((isset($estrutura) AND $idioma->getId() == $estrutura->getIdioma()) ? 'selected' : null) . ">" . $idioma->getDescricao() . "</option>";
                                    }?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="titulo" class="col-sm-2 control-label">Título</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo isset($estrutura) ? $estrutura->getTitulo() : NULL ?>" />
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="col-sm-12">
                                <?php
                                $nomeEditor = "descricao";
                                $conteudoEditor = isset($estrutura) ? $estrutura->getDescricao() : NULL;
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

<?php if (isset($estrutura) AND $estrutura->isLoad()) { ?>
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
                        criaMiniatura('<?php echo $imagem->getId(); ?>', '<?php echo $estrutura->getId(); ?>', '<?php echo $imagem->getNome(); ?>', '<?php echo $imagem->getDescricao(); ?>');
                        <?php } ?>
                        var settings = {
                            url: "?acao=AdmImagem&objeto=Estrutura&idObjeto=<?php echo isset($estrutura) ? $estrutura->getId() : NULL ?>",
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
                        criaMiniatura('<?php echo $imagem->getId(); ?>', '<?php echo $estrutura->getId(); ?>', '<?php echo $imagem->getNome(); ?>', '<?php echo $imagem->getDescricao(); ?>');
                        <?php } ?>
                    });
                </script>
            <?php } ?>
            <div class="row col-md-12" id="imagens">
            </div>
        </div>
    </div>
<?php } ?>