<?php
$success = $this->session->getFlashBag()->get('success');
$error = $this->session->getFlashBag()->get('error');
$idiomas = $this->request->query->get('idiomas');
$idiomaAtual = $this->request->query->get('idiomaAtual');

Tool::alert("success", $success);
Tool::alert("error", $error);
?>
<script>
    $(document).ready(function () {
        $('#idioma').change(function () {
            $('#filtro').submit();
        });
    })
</script>
<div class="row">
    <div class="col-md-12 column">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><span class="glyphicon glyphicon-filter"></span><strong> Filtro</strong></h3>
            </div>
            <div class="panel-body">
                <div class="row clearfix">
                    <div class="col-md-12 column">
                        <form id='filtro' class="form-horizontal" role="form" method="POST" action="?acao=AdmCertificacao&search">
                            <div class="form-group">
                                <label for="titulo" class="col-sm-2 control-label">Ídioma</label>

                                <div class="col-sm-10">
                                    <select class="form-control" name="idioma" id="idioma">
                                        <option value="">Todos</option>
                                        <?php foreach ($idiomas AS $idioma) {
                                            echo "<option value='" . $idioma->getId() . "'" . ((isset($idiomaAtual) AND $idioma->getId() == $idiomaAtual->getId()) ? 'selected' : null) . ">" . $idioma->getDescricao() . "</option>";
                                        }?>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
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
                    <th class="col-sm-3 col-xs-3">Título</th>
                    <th class="col-sm-2 col-xs-2">Atualizado</th>
                    <th class="col-sm-3 col-xs-3">Opções</th>
                </tr>
            </thead>
            <?php if (count($this->request->query->get('certificacoes'))) { ?>
            <tbody>
                <?php
                foreach ($this->request->query->get('certificacoes') as $certificacao) {
                    ?>
                    <tr>
                        <td class="col-sm-1 col-xs-1"><?= $certificacao->getId(); ?></td>
                        <td class="col-sm-3 col-xs-3"><?= $certificacao->getTitulo(); ?></td>
                        <td class="col-sm-2 col-xs-2"><?= Tool::converteData("Y-m-d H:i:s", "d/m/Y H:i", $certificacao->getDataAtualizado()); ?></td>
                        <td class='col-sm-3 col-xs-3'>
                            <div class="hidden-xs">
                                <a href="?acao=AdmCertificacao&id=<?= $certificacao->getId(); ?>" class="btn btn-success">Editar</a>
                                <button type="button" data-toggle="modal" data-target="#myModal<?= $certificacao->getId(); ?>" class="btn btn-danger">Excluir</button>
                            </div>
                            <div class="visible-xs">
                                <a href="?acao=AdmCertificacao&id=<?= $certificacao->getId(); ?>" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span></a>
                                <button type="button" data-toggle="modal" data-target="#myModal<?= $certificacao->getId(); ?>" class="btn btn-danger">
                                    <span class="glyphicon glyphicon-remove"></span></button>
                            </div>
                            <form name="delete" action="?acao=AdmCertificacao&id=<?= $certificacao->getId(); ?>" method="POST">
                                <input type="hidden" name="_method" value="DELETE" />
                                <?php Tool::modal("Exclusão de Certificação", "Deseja realmente efetuar a exclusão da certificação " . $certificacao->getTitulo() . "?", "Excluir", $certificacao->getId()); ?>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } else { ?>
            </table>
            <div class="alert alert-info">Nenhuma certificação encontrada.</div>
        <?php } ?>
        <a href="?acao=AdmCertificacao" class="btn btn-success">
            <spam class="glyphicon glyphicon-plus"></spam>
            Adicionar Certificação</a>
    </div>
</div>
