<?php
$success = $this->session->getFlashBag()->get('success');
$error = $this->session->getFlashBag()->get('error');


Tool::alert("success", $success);
Tool::alert("error", $error);
?><script>
    $(document).ready(function() {
    })
</script>
<div class="row clearfix">
    <div class="col-md-12 column">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="col-sm-1 col-xs-1">Id</th>
                    <th class="col-sm-2 col-xs-2">Nome</th>
                    <th class="col-sm-2 col-xs-2">Valor</th>
                    <th class="col-sm-3 col-xs-3">Opções</th>
                </tr>
            </thead>
            <?php if (count($this->request->query->get('estacionamentos'))) { ?>
                <tbody>
                    <?php
                    foreach ($this->request->query->get('estacionamentos') as $estacionamento) {
                        ?>
                        <tr>
                            <td class="col-sm-1 col-xs-1"><?php echo $estacionamento->getId(); ?></td>
                            <td class="col-sm-3 col-xs-4"><?php echo $estacionamento->getNome(); ?></td>
                            <td class="col-sm-2 col-xs-4">R$ <?php echo Tool::formatToMoney($estacionamento->getValor()); ?></td>
                            <td class='col-sm-2'>
                                <div class="hidden-xs">
                                    <a href="?acao=AdmEstacionamento&id=<?php echo $estacionamento->getId(); ?>&kml" class="btn btn-primary">KML</a>
                                    <a href="?acao=AdmEstacionamento&id=<?php echo $estacionamento->getId(); ?>" class="btn btn-success">Editar</a>
                                    <button type="button" data-toggle="modal" data-target="#myModal<?php echo $estacionamento->getId(); ?>" class="btn btn-danger">Excluir</button>
                                </div>
                                <div class="visible-xs">
                                    <a href="?acao=AdmEstacionamento&id=<?php echo $estacionamento->getId(); ?>" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span></a>
                                    <button type="button" data-toggle="modal" data-target="#myModal<?php echo $estacionamento->getId(); ?>" class="btn btn-danger">
                                        <span class="glyphicon glyphicon-remove"></span></button>
                                </div>
                                <form name="delete" action="?acao=AdmEstacionamento&id=<?php echo $estacionamento->getId(); ?>" method="POST">
                                    <input type="hidden" name="_method" value="DELETE" />
                                    <?php Tool::modal("Exclusão de Estacionamento", "Deseja realmente efetuar a exclusão do estacionamento " . $estacionamento->getNome() . "?", "Excluir", $estacionamento->getId()); ?>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            </table>
            <div class="alert alert-info">Nenhum estacionamento encontrado.</div>
        <?php } ?>
        <a href="?acao=AdmEstacionamento" class="btn btn-success">
            <spam class="glyphicon glyphicon-plus"></spam>
            Adicionar Estacionamento</a>
    </div>
</div>
