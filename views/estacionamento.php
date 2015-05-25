<?php
$error = $this->session->getFlashBag()->get('error');
Tool::alert("error", $error);

$success = $this->session->getFlashBag()->get('success');
Tool::alert("success", $success);

$estacionamento = $this->request->query->get('estacionamento');
?>
<script type="text/javascript">
    $(document).ready(function () {
        $('[rel=add]').click(function () {
            var trAtual = $(this).parent().parent();
            var novoTr = $(trAtual).clone();
            $(novoTr).find('[rel=erro]').remove();
            $(novoTr).find("input").val(null);

            $(novoTr).find("[rel=add]").attr({
                'rel': 'del',
                'class': 'btn btn-danger botao-right'
            }).html("<span class=\"glyphicon glyphicon-trash\"></span>");
            $(novoTr).insertAfter($("[rel=" + $(trAtual).attr('rel') + "]:last"));
            iniciaDel();
        });
    });
    function iniciaDel() {
        $('[rel=del]').click(function () {
            $(this).parent().parent().remove();
        });
    }
</script>
<div class="panel panel-default tamanhoPadrao">
    <div class="panel-heading">
        <h3 class="panel-title">
            <spam class="glyphicon glyphicon-list-alt"></spam>
            <strong>
                <?php echo (isset($estacionamento) AND $estacionamento->isLoad()) ? "Alteração de estacionamento" : "Cadastro de Estacionamento" ?>
            </strong>
        </h3>
    </div>
    <div class="panel-body">
        <div class="row clearfix">
            <div class="col-md-12 column">
                <form class="form-horizontal" role="form" method="POST" action="?acao=AdmEstacionamento<?php echo (isset($estacionamento) AND $estacionamento->isLoad()) ? ("&id=" . $estacionamento->getId()) : NULL ?>" enctype="multipart/form-data">
                    <?php if (isset($estacionamento) AND $estacionamento->isLoad()) { ?>
                        <div class="form-group">
                            <label class="col-sm-2 text-right">Id</label>

                            <div class="col-sm-1">
                                <?php echo isset($estacionamento) ? $estacionamento->getId() : NULL ?>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="form-group">
                        <label for="nome" class="col-sm-2 control-label">Nome</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nome" name="nome" value="<?php echo isset($estacionamento) ? $estacionamento->getNome() : NULL ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="valor" class="col-sm-2 control-label">Valor</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="valor" name="valor" value="<?php echo isset($estacionamento) ? $estacionamento->getValor() : NULL ?>" />
                        </div>
                    </div>
                    <?php
                    if (isset($estacionamento) AND $estacionamento->isLoad()) {

                        $pontos = $estacionamento->getPoligonoArray()
                        ?>
                        <div class="form-group">
                            <div class="col-sm-2"></div>
                            <label class="col-sm-10 control-label" style="text-align: left;">Cadastre a área do estácionamento informando os pontos do polígono</label>
                        </div>
                        <?php
                        if (count($pontos)) {
                            $i = 1;
                            foreach ($pontos as $ponto) {
                                ?>
                                <div class="form-group" rel="ponto">
                                    <label for="valor" class="col-sm-2 control-label" rel="contador">Ponto <?php echo $i; ?></label>

                                    <div class="col-sm-4">
                                        <input type="text" class="form-control input-left" id="ponto-1" name="pontos[]" value="<?php echo $ponto; ?>" placeholder="Lat, Long" style="width: auto !important;"/>
                                        <button type="button" class="btn btn-success botao-right" rel="add"><span class="glyphicon glyphicon-plus"></span></button>
                                    </div>
                                </div>
                                <?php
                            $i++;
                            
                            }
                        } else {
                            ?>
                            <div class="form-group" rel="ponto">
                                <label for="valor" class="col-sm-2 control-label" rel="contador">Ponto 1</label>

                                <div class="col-sm-4">
                                    <input type="text" class="form-control input-left" id="ponto-1" name="pontos[]" value="" placeholder="Lat, Long" style="width: auto !important;"/>
                                    <button type="button" class="btn btn-success botao-right" rel="add"><span class="glyphicon glyphicon-plus"></span></button>
                                </div>
                            </div>
                        <?php } ?>
                    <?php }
                    ?>
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
