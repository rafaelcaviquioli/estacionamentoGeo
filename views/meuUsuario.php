<?php
$usuario = $this->session->get("usuario");
$alterarSenhaForm = $this->request->query->get('alterarSenhaForm');
$senhaAtual = $this->request->request->get('senhaAtual');
$novaSenha = $this->request->request->get('novaSenha');
$confirmaNovaSenha = $this->request->request->get('confirmaNovaSenha');

$success = $this->session->getFlashBag()->get('success');
$warning = $this->session->getFlashBag()->get('warning');
$error = $this->session->getFlashBag()->get('error');

?>
<div class="row clearfix">
    <div class="col-md-12 column">
        <div class="row clearfix tamanhoPadrao">
            <div class="col-md-12 column">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <spam class="glyphicon glyphicon-user"></spam> <strong><?php echo $usuario->getLogin(); ?></strong>
                        </h3>
                    </div>
                    <div class="panel-body">
                        Login: <?php echo $usuario->getLogin(); ?>
                    </div>
                    <div class="panel-body">
                        Senha: &bull; &bull; &bull; &bull; &bull; &bull; &bull; &bull;
                        <?php if (!isset($alterarSenhaForm)) { ?>
                            <a href="?acao=MeuUsuario&alterarSenhaForm" class="btn btn-link">Alterar Senha</a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    Tool::alert("success", $success);
    Tool::alert("warning", $warning);
    Tool::alert("error", $error);
?>
<?php if (isset($alterarSenhaForm)) { ?>
    <div class="row clearfix" id="divAlteracaoSenha">
        <div class="col-md-12 column">
            <div class="row clearfix tamanhoPadrao">
                <div class="col-md-12 column">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <strong>Alteração de senha</strong>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" action="?acao=MeuUsuario" method="POST">
                                <div class="form-group">
                                    <label for="senhaAtual" class="col-sm-3 control-label">Senha atual</label>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control" id="senhaAtual" name="senhaAtual" value="<?php echo (isset($senhaAtual) ? $senhaAtual : NULL) ?>"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="novaSenha" class="col-sm-3 control-label">Nova senha</label>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control" id="novaSenha" name="novaSenha" value="<?php echo (isset($novaSenha) ? $novaSenha : NULL) ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="novaSenhaRepita" class="col-sm-3 control-label">Confirme a nova senha</label>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control" id="confirmaNovaSenha" name="confirmaNovaSenha" value="<?php echo (isset($confirmaNovaSenha) ? $confirmaNovaSenha : NULL) ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-6">
                                        <button type="submit" class="btn btn-success">Salvar</button>
                                        <a href="?acao=meuUsuario" class="btn btn-danger">Cancelar</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>