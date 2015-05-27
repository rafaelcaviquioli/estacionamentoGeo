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
<form class="form-horizontal" role="form" method="POST" action="?acao=AdmRastreamento" enctype="multipart/form-data">
    <div class="panel panel-default tamanhoPadrao">
        <div class="panel-heading">
            <h3 class="panel-title">
                <spam class="glyphicon glyphicon-list-alt"></spam>
                <strong>
                    Cadastro de dados de rastreamento
                </strong>
            </h3>
        </div>
        <div class="panel-body">
            <div class="row clearfix">
                <div class="col-md-12 column">

                    <div class="form-group">
                        <label for="titulo" class="col-sm-2 control-label">Veículo</label>

                        <div class="col-sm-10">
                            <select name="veiculo" id="veiculo" class="form-control">
                                <option selected value=""></option>
                                <?php
                                if (count($this->request->query->get('veiculos'))) {
                                    foreach ($this->request->query->get('veiculos') as $veiculo) {
                                        echo "<option value='" . $veiculo->getId() . "'>" . $veiculo->getPlaca() . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="titulo" class="col-sm-2 control-label">Data</label>

                        <div class="col-sm-10">
                            <input type="datetime" class="form-control" id="data" name="data" value="<?php echo date('d/m/Y H:i:s'); ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="titulo" class="col-sm-2 control-label">Posição</label>

                        <div class="col-sm-10">
                            <input type="datetime" class="form-control" id="posicao" name="posicao" value="" placeholder="Long, Lat" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-success">Salvar</button>
                        <button type="button" class="btn btn-default" id="back">Voltar</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</form>