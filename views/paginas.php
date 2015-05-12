<?php
$success = $this->session->getFlashBag()->get('success');
$error = $this->session->getFlashBag()->get('error');
$idioma = $this->request->query->get('idioma');

Tool::alert("success", $success);
Tool::alert("error", $error);
?>
<div class="row clearfix">
    <div class="col-md-12 column">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="col-sm-2 col-xs-2">Ídioma</th>
                    <th class="col-sm-5 col-xs-5">Menu</th>
                    <th class="col-sm-3 col-xs-3">Atualizado</th>
                    <th class="col-sm-2 col-xs-2">Opções</th>
                </tr>
            </thead>
            <?php if (count($this->request->query->get('paginas'))) { ?>
            <tbody>
                <?php
                /** @var Pagina $pagina */
                foreach ($this->request->query->get('paginas') as $pagina) {
                    ?>
                    <tr>
                        <td class="col-sm-2 col-xs-2"><?= $pagina->getIdiomaObj()->getDescricao(); ?></td>
                        <td class="col-sm-5 col-xs-5"><?= $pagina->getMenu(); ?></td>
                        <td class="col-sm-3 col-xs-3"><?= Tool::converteData("Y-m-d H:i:s", "d/m/Y H:i", $pagina->getDataAtualizado()); ?></td>
                        <td class='col-sm-2 col-xs-2'>
                            <div class="hidden-xs">
                                <a href="?acao=AdmPagina&idIdioma=<?php echo $idioma->getId(); ?>&id=<?= $pagina->getId(); ?>" class="btn btn-success">Editar</a>
                            </div>
                            <div class="visible-xs">
                                <a href="?acao=AdmPagina&idIdioma=<?php echo $idioma->getId(); ?>&id=<?= $pagina->getId(); ?>" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span></a>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } else { ?>
            </table>
            <div class="alert alert-info">Nenhuma página encontrada.</div>
        <?php } ?>
<!--        <a href="?acao=AdmPagina&idIdioma=--><?php //echo $idioma->getId(); ?><!--" class="btn btn-success">-->
<!--            <spam class="glyphicon glyphicon-plus"></spam>-->
<!--            Adicionar Página</a>-->
    </div>
</div>
