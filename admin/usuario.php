<?php
require('common.inc.php');

$usuario = null;

$regraUsuario = new Usuario();
$id_usuario = intval($_GET['usuario']);
if ($id_usuario > 0)
    $usuario = $regraUsuario->pegar($id_usuario);

if (count($_POST) > 0) {
    try {
        if ($id_usuario > 0) {
            $usuario = $regraUsuario->pegarDoPost($usuario);
            $regraUsuario->alterar($usuario);
            $msgsucesso = 'Usuário alterado com sucesso!';
        }
        else {
            $usuario = $regraUsuario->pegarDoPost();
            $id_usuario = $regraUsuario->inserir($usuario);
            $usuario->id_usuario = $id_usuario;
            $msgsucesso = 'Usuário cadastrado com sucesso!';
            
            $url  = WEB_PATH."/admin/usuario/";
            $url .= strtolower(sanitize_slug($usuario->nome));
            $url .= '-'.$usuario->id_usuario;
            $url .= '?sucesso='.urlencode($msgsucesso);
            header('Location: '.$url);
            exit();
        }
    }
    catch (Exception $e) {
        $msgerro = $e->getMessage();
    }
}


require("header.inc.php");
require('menu-principal.inc.php');
?>
<div class="container" style="margin-top: 80px">
    <div class="row">
        <div class="col-md-8">
            <ol class="breadcrumb">
                <li><i class="icon icon-home"></i> <a href="<?php echo WEB_PATH; ?>/admin/">Início</a></li>
                <li><i class="icon icon-users"></i> <a href="<?php echo WEB_PATH; ?>/admin/usuarios">Usuários</a></li>
                <?php if (is_null($usuario)) : ?>
                <li class="current"><i class="icon icon-user"></i> Novo Usuário</li>
                <?php else : ?>
                <li class="current"><i class="icon icon-user"></i> <?php echo $usuario->nome; ?></li>
                <?php endif; ?>
            </ol>
        </div>
        <div class="col-md-4">
            <ul class="nav nav-pills pull-right">
                <li><a href="<?php echo WEB_PATH; ?>/admin/usuario"><i class="icon icon-plus"></i> <span>Novo</span></a></li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-offset-2 col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="icon icon-user"></i> Cadastro de Usuários</h3>
                </div>
                <div class="panel-body">
                    <?php if (isset($msgerro)) : ?>
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <i class="icon icon-warning"></i> 
                        <?php echo $msgerro; ?>
                    </div>
                    <?php elseif (isset($msgsucesso)) : ?>
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <i class="icon icon-thumbs-up"></i> 
                        <?php echo $msgsucesso; ?>
                    </div>
                    <?php elseif (array_key_exists('sucesso', $_GET)) : ?>
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <i class="icon icon-thumbs-up"></i> 
                        <?php echo $_GET['sucesso']; ?>
                    </div>
                    <?php endif; ?>
                    
                    
                    <form method="POST" class="form-horizontal">
                    <input type="hidden" name="id_usuario" value="<?php echo $usuario->id_usuario; ?>" />
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img src="<?php echo get_gravatar($usuario->email, 180); ?>" class="img-circle" />
                            <span class="help-block" style="font-size: 80%">
                                Para cadastrar uma foto, acesse o site 
                                <a target="_blank" href="https://pt.gravatar.com/">https://pt.gravatar.com/</a> 
                                e crie uma conta.
                            </span>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="col-md-4 control-label">Nome<span class="required">*</span>:</label>
                                <div class="col-md-8">
                                    <input type="text" name="nome" class="form-control required" value="<?php echo $usuario->nome; ?>" placeholder="Seu nome completo">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Email<span class="required">*</span>:</label>
                                <div class="col-md-8">
                                    <input type="text" name="email" class="form-control required" value="<?php echo $usuario->email; ?>" placeholder="Seu email">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Tipo<span class="required">*</span>:</label>
                                <div class="col-md-8">
                                    <select name="cod_tipo" class="form-control required">
                                        <option value="">--selecione--</option>
                                        <?php foreach ($regraUsuario->listarTipo() as $cod_tipo => $tipo) : ?>
                                        <option value="<?php echo $cod_tipo; ?>" <?php echo ($usuario->cod_tipo == $cod_tipo) ? 'selected="selected"' : ''; ?>><?php echo $tipo; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Situação<span class="required">*</span>:</label>
                                <div class="col-md-8">
                                    <select name="cod_situacao" class="form-control required">
                                        <option value="">--selecione--</option>
                                        <?php foreach ($regraUsuario->listarSituacao() as $cod_situacao => $situacao) : ?>
                                        <option value="<?php echo $cod_situacao; ?>" <?php echo ($usuario->cod_situacao == $cod_situacao) ? 'selected="selected"' : ''; ?>><?php echo $situacao; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <hr />
                            <div class="form-group">
                                <label class="col-md-4 control-label">Senha<span class="required">*</span>:</label>
                                <div class="col-md-8">
                                    <input type="password" name="senha" class="form-control required" placeholder="Preencha a senha caso queira alterar">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Confirmar<span class="required">*</span>:</label>
                                <div class="col-md-8">
                                    <input type="password" name="senha_confirma" class="form-control required" placeholder="Preencha a senha caso queira alterar">
                                </div>
                            </div>
                            <div class="text-right">
                                 <button type="submit" class="btn btn-lg btn-primary">Salvar</button>
                            </div>
                        </div><!--col-md-4-->
                    </div><!--row-->
                    </form>
                </div><!--panel-body-->
            </div><!--panel-default-->
        </div><!--col-md-8-->
    </div><!--row-->
</div>

<?php require("footer.inc.php"); ?>