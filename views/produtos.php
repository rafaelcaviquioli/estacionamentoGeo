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
    $(document).ready(function () {
        $('#idioma').change(function () {
            $('#filtro').submit();
        });
        $('#categoria').change(function () {
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
                        <form id='filtro' class="form-horizontal" role="form" method="POST" action="?acao=AdmProduto&search">
                            <div class="form-group">
                                <label for="titulo" class="col-sm-2 control-label">Ídioma</label>

                                <div class="col-sm-10">
                                    <select class="form-control" name="idioma" id="idioma">
                                        <option value="0">Todos</option>
                                        <?php foreach ($idiomas AS $idioma) {
                                            echo "<option value='" . $idioma->getId() . "'" . ((isset($idiomaAtual) AND $idioma->getId() == $idiomaAtual->getId()) ? 'selected' : null) . ">" . $idioma->getDescricao() . "</option>";
                                        }?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="categoria" class="col-sm-2 control-label">Categoria</label>

                                <div class="col-sm-10">
                                    <select class="form-control" name="categoria" id="categoria">
                                        <option value="0">Todos</option>
                                        <?php foreach ($categorias AS $categoria) {
                                            echo "<option value='" . $categoria->getId() . "'" . ((isset($categoriaAtual) AND $categoria->getId() == $categoriaAtual->getId()) ? 'selected' : null) . ">" . $categoria->getTitulo() . "</option>";
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
                    <th class="col-sm-2 col-xs-2">Título</th>
                    <th class="col-sm-1 col-xs-1">Destaque</th>
                    <th class="col-sm-2 col-xs-2">Atualizado</th>
                    <th class="col-sm-3 col-xs-3">Opções</th>
                </tr>
            </thead>
            <?php if (count($this->request->query->get('produtos'))) { ?>
            <tbody>
                <?php
                foreach ($this->request->query->get('produtos') as $produto) {
                    ?>
                    <tr>
                        <td class="col-sm-1 col-xs-1"><?= $produto->getId(); ?></td>
                        <td class="col-sm-5 col-xs-4"><?= $produto->getTitulo(); ?></td>
                        <td class="col-sm-1 col-xs-1"><?= $produto->getDestaque() ? 'Sim' : 'Não'; ?></td>
                        <td class="col-sm-2 col-xs-3"><?= Tool::converteData("Y-m-d H:i:s", "d/m/Y H:i", $produto->getDataAtualizado()); ?></td>
                        <td class='col-sm-2 col-xs-3'>
                            <div class="hidden-xs">
                                <a href="?acao=AdmProduto&id=<?= $produto->getId(); ?>" class="btn btn-success">Editar</a>
                                <button type="button" data-toggle="modal" data-target="#myModal<?= $produto->getId(); ?>" class="btn btn-danger">Excluir</button>
                            </div>
                            <div class="visible-xs">
                                <a href="?acao=AdmProduto&id=<?= $produto->getId(); ?>" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span></a>
                                <button type="button" data-toggle="modal" data-target="#myModal<?= $produto->getId(); ?>" class="btn btn-danger">
                                    <span class="glyphicon glyphicon-remove"></span></button>
                            </div>
                            <form name="delete" action="?acao=AdmProduto&id=<?= $produto->getId(); ?>" method="POST">
                                <input type="hidden" name="_method" value="DELETE" />
                                <?php Tool::modal("Exclusão de Produto", "Deseja realmente efetuar a exclusão do produto " . $produto->getTitulo() . "?", "Excluir", $produto->getId()); ?>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } else { ?>
            </table>
            <div class="alert alert-info">Nenhum produto encontrado.</div>
        <?php } ?>
        <a href="?acao=AdmProduto" class="btn btn-success">
            <spam class="glyphicon glyphicon-plus"></spam>
            Adicionar Produto</a>
    </div>
</div>
