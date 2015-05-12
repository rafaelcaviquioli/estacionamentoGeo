<?php

$error = $this->session->getFlashBag()->get('error');
Tool::alert("error", $error);

$success = $this->session->getFlashBag()->get('success');
Tool::alert("success", $success);


?>
    <script type="text/javascript">
        $(document).ready(function () {

        });
    </script>
    <div class="panel panel-default tamanhoPadrao">
        <div class="panel-heading">
            <h3 class="panel-title">
                <spam class="glyphicon glyphicon-list-alt"></spam>
                <strong>
                    <?php echo (isset($veiculo) AND $veiculo->isLoad()) ? "Alteração de veículo" : "Cadastro de Veículo" ?>
                </strong>
            </h3>
        </div>
        <div class="panel-body">
            <div class="row clearfix">
                <div class="col-md-12 column">
                    <form class="form-horizontal" role="form" method="POST" action="?acao=AdmVeiculo<?php echo (isset($veiculo) AND $veiculo->isLoad()) ? ("&id=" . $veiculo->getId()) : NULL ?>" enctype="multipart/form-data">
                        <?php if (isset($veiculo) AND $veiculo->isLoad()) { ?>
                            <div class="form-group">
                                <label class="col-sm-2 text-right">Id</label>

                                <div class="col-sm-1">
                                    <?php echo isset($veiculo) ? $veiculo->getId() : NULL ?>
                                </div>
                            </div>
                        <?php } ?>
                       
                        <div class="form-group">
                            <label for="titulo" class="col-sm-2 control-label">Placa</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="placa" name="placa" value="<?php echo isset($veiculo) ? $veiculo->getPlaca() : NULL ?>" />
                            </div>
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
<?php if (isset($veiculo) AND $veiculo->isLoad()) { ?>

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
                criaMiniatura('<?php echo $imagem->getId(); ?>', '<?php echo $veiculo->getId(); ?>', '<?php echo $imagem->getNome(); ?>', '<?php echo $imagem->getDescricao(); ?>');
                <?php } ?>
                var settings = {
                    url: "?acao=AdmImagem&tipo=CAPA&objeto=Produto&idObjeto=<?php echo isset($veiculo) ? $veiculo->getId() : NULL ?>",
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
                criaMiniatura('<?php echo $imagem->getId(); ?>', '<?php echo $veiculo->getId(); ?>', '<?php echo $imagem->getNome(); ?>', '<?php echo $imagem->getDescricao(); ?>');
                <?php } ?>
            });
        </script>
    <?php } ?>
    <script>

        $(document).ready(function () {
            <?php foreach ($selos as $imagem2) { ?>
            criaMiniatura2('<?php echo $imagem2->getId(); ?>', '<?php echo $veiculo->getId(); ?>', '<?php echo $imagem2->getNome(); ?>', '<?php echo $imagem2->getDescricao(); ?>');
            <?php } ?>
            var settings2 = {
                url: "?acao=AdmImagem&tipo=SELO&objeto=Produto&idObjeto=<?php echo isset($veiculo) ? $veiculo->getId() : NULL ?>",
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