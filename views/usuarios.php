<?php $success = $this->session->getFlashBag()->get('success'); ?>
<div class="row clearfix">
    <div class="col-md-12 column">
        <?php Tool::alert("success", $success); ?>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="col-sm-1 col-xs-1">Id</th>
                    <th class="col-sm-2 col-xs-3">Login</th>
                    <th class="col-sm-2 col-xs-3">Opções</th>
                </tr>
            </thead>
            <?php if (count($this->request->query->get('usuarios'))) { ?>
                <tbody>
                    <?php foreach ($this->request->query->get('usuarios') as $usuario) { ?>
                        <tr>
                            <td class="col-sm-1 col-xs-1"><?php echo  $usuario->getId(); ?></td>
                            <td class="col-sm-2 col-xs-3"><?php echo  $usuario->getLogin(); ?></td>
                            <td class='col-sm-2 col-xs-3'>
                                <div class="hidden-xs">
                                    <a href="?acao=AdmUsuario&id=<?php echo  $usuario->getId(); ?>" class="btn btn-success">Editar</a>
                                    <button type="button" data-toggle="modal" data-target="#myModal<?php echo  $usuario->getId(); ?>" class="btn btn-danger">Excluir</button>
                                </div>
                                <div class="visible-xs">
                                    <a href="?acao=AdmUsuario&id=<?php echo  $usuario->getId(); ?>" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span></a>
                                    <button type="button" data-toggle="modal" data-target="#myModal<?php echo  $usuario->getId(); ?>" class="btn btn-danger">
                                        <span class="glyphicon glyphicon-remove"></span></button>
                                </div>
                                <form name="delete" action="?acao=AdmUsuario&id=<?php echo  $usuario->getId(); ?>" method="POST">
                                    <input type="hidden" name="_method" value="DELETE" />
                                    <?php Tool::modal("Exclusão de usuário", "Confirma a exclusão do usuário " . $usuario->getLogin() . " ?", "Excluir", $usuario->getId()); ?>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            <?php } ?>
        </table>
        <a href="?acao=AdmUsuario" class="btn btn-success">
            <spam class="glyphicon glyphicon-plus"></spam>
            Adicionar Usuário</a>
    </div>
</div>
