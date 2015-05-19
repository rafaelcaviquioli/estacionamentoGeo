<?php
$usuario = $this->request->query->get('usuario');
$error = $this->session->getFlashBag()->get('error');
?>
<?php
Tool::alert("error", $error);
?>
<div class="panel panel-default tamanhoPadrao">
    <div class="panel-heading">
        <h3 class="panel-title">
            <spam class="glyphicon glyphicon-user"></spam>
            <strong>
                <?php echo (isset($usuario) AND $usuario->isLoad()) ? "Alteração de usuário" : "Criação de usuário" ?>
            </strong>
        </h3>
    </div>
    <div class="panel-body">
        <div class="row clearfix">
            <div class="col-md-12 column">
                <form class="form-horizontal" role="form" method="POST" action="?acao=AdmUsuario<?php echo isset($usuario) ? "&id=" . $usuario->getId() : NULL ?>">
                    <?php if (isset($usuario) AND $usuario->isLoad()) { ?>
                        <div class="form-group">
                            <label class="col-sm-2 text-right">Id</label>
                            <div class="col-sm-1">
                                <?php echo isset($usuario) ? $usuario->getId() : NULL ?>
                            </div>

                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="ativo" class="col-sm-2 control-label">Ativo</label>
                        <div class="col-sm-10">
                            <label><input id="ativo" name="ativo" value="1" type="checkbox" <?php echo (isset($usuario) AND $usuario->getAtivo() OR !isset($usuario)) ? "checked" : NULL ?>/></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="login" class="col-sm-2 control-label">Login</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="login" name="login"  value="<?php echo isset($usuario) ? $usuario->getLogin() : NULL ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="senha" class="col-sm-2 control-label">Senha</label>
                        <div class="col-sm-5">
                            <input type="password" class="form-control" id="senha" name="senha" <?php echo isset($usuario) ? "disabled" : NULL ?> />
                        </div>
                        <?php if (isset($usuario)) { ?>
                            <a id="alterarSenha" type="button" class="btn btn-link">Alterar Senha</a>
                            <a id="cancelarAlterarSenha" type="button" class="btn btn-link" style="display: none;">Cancelar alteração de Senha</a>
                        <?php } ?>
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
<script>
    $(document).ready(function() {
        $('#alterarSenha').click(function() {
            $('#senha').removeAttr('disabled').focus();
            $('#cancelarAlterarSenha').show();
            $(this).hide();
        });
        $('#cancelarAlterarSenha').click(function() {
            $('#alterarSenha').show();
            $('#senha').attr('disabled', 'disabled').val('');
            $(this).hide();
        });
    });
</script>