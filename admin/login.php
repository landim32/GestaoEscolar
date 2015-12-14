<?php 
require('common.inc.php');

//var_dump($_POST);
if (count($_POST) > 0) {
    if (array_key_exists('acao', $_POST) && $_POST['acao'] == 'logar') {
        $email = $_POST['email'];
        $senha = $_POST['senha'];
        if ($regraUsuario->logar($email, $senha)) {
            header('Location: '.WEB_PATH.'/admin/');
            exit();
        } 
        else
            $msgerro = 'Email ou senha inválida!';
    }
}

?>
<?php require('header.inc.php'); ?>
<?php require('menu-principal.inc.php'); ?>
<div class="container" style="margin-top: 100px">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="icon icon-user"></i> Entre com sua conta</h3>
                </div>
                <div class="panel-body" style="padding-top: 40px;">
                    <form method="POST" class="form-vertical">
                        <input type="hidden" name="acao" value="logar">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="icon icon-envelope"></i></span>
                                <input type="text" class="form-control input-lg" name="email" placeholder="Seu email" value="<?php echo $_POST['email']; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="icon icon-lock"></i></span>
                                <input type="password" class="form-control input-lg" name="senha" placeholder="Sua senha" value="<?php echo $_POST['senha']; ?>" />
                            </div>
                        </div>
                        <?php if (isset($msgerro)) : ?>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <i class="icon icon-warning"></i> <?php echo $msgerro; ?>
                        </div>
                        <?php endif; ?>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-lg btn-primary">Entrar</button>
                        </div>
                    </form>
                </div><!--panel-body-->
            </div><!--panel-default-->
        </div>
    </div>
</div>
<?php require('footer.inc.php'); ?>