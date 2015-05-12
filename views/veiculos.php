<?php
$success = $this->session->getFlashBag()->get('success');
$error = $this->session->getFlashBag()->get('error');
$idiomas = $this->request->query->get('idiomas');
$categorias = $this->request->query->get('categorias');
$idiomaAtual = $this->request->query->get('idiomaAtual');
$categoriaAtual = $this->request->query->get('categoriaAtual');

Tool::alert("success", $success);
Tool::alert("error", $error);
?><script>
    $(document).ready(function() {
        $('#idioma').change(function() {
            $('#filtro').submit();
        });
        $('#categoria').change(function() {
            $('#filtro').submit();
        });
    })
</script>
<div class="row">
    <div class="col-md-12 column">
        <div class="panel panel-default">
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
</div>
<div class="row clearfix">
    <div class="col-md-12 column">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="col-sm-1 col-xs-1">Id</th>
                    <th class="col-sm-2 col-xs-2">Placa</th>
                    <th class="col-sm-3 col-xs-3">Opções</th>
                </tr>
            </thead>
            <?php if (count($this->request->query->get('veiculos'))) { ?>
                <tbody>
                    <?php
                    foreach ($this->request->query->get('veiculos') as $veiculo) {
                        ?>
                        <tr>
                            <td class="col-sm-1 col-xs-1"><?php echo $veiculo->getId(); ?></td>
                            <td class="col-sm-5 col-xs-4"><?php echo $veiculo->getPlaca(); ?></td>
                            <td class='col-sm-2 col-xs-3'>
                                <div class="hidden-xs">
                                    <a href="?acao=AdmVeiculo&id=<?php echo $veiculo->getId(); ?>" class="btn btn-success">Editar</a>
                                    <button type="button" data-toggle="modal" data-target="#myModal<?php echo $veiculo->getId(); ?>" class="btn btn-danger">Excluir</button>
                                </div>
                                <div class="visible-xs">
                                    <a href="?acao=AdmVeiculo&id=<?php echo $veiculo->getId(); ?>" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span></a>
                                    <button type="button" data-toggle="modal" data-target="#myModal<?php echo $veiculo->getId(); ?>" class="btn btn-danger">
                                        <span class="glyphicon glyphicon-remove"></span></button>
                                </div>
                                <form name="delete" action="?acao=AdmVeiculo&id=<?php echo $veiculo->getId(); ?>" method="POST">
                                    <input type="hidden" name="_method" value="DELETE" />
                                    <?php Tool::modal("Exclusão de Veiculo", "Deseja realmente efetuar a exclusão do veiculo " . $veiculo->getPlaca() . "?", "Excluir", $veiculo->getId()); ?>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            </table>
            <div class="alert alert-info">Nenhum veiculo encontrado.</div>
        <?php } ?>
        <a href="?acao=AdmVeiculo" class="btn btn-success">
            <spam class="glyphicon glyphicon-plus"></spam>
            Adicionar Veículo</a>
    </div>
</div>
