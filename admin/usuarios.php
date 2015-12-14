<?php
require('common.inc.php');

$regraUsuario = new Usuario();

$cod_situacao = null;
if (array_key_exists('situacao', $_GET))
    $cod_situacao = intval($_GET['situacao']);
$usuarios = $regraUsuario->listar($cod_situacao);

require("header.inc.php");
require('menu-principal.inc.php');
?>
<div class="container" style="margin-top: 80px">
    <div class="row">
        <div class="col-md-8">
            <ol class="breadcrumb">
                <li><i class="icon icon-home"></i> <a href="<?php echo WEB_PATH; ?>/admin/">Início</a></li>
                <li class="current"><i class="icon icon-users"></i> <a href="<?php echo WEB_PATH; ?>/admin/usuarios">Usuários</a></li>
                <!--li class="current"><i class="icon icon-home"></i> <a href="imovel?id=">Novo Imóvel</a></li-->
            </ol>
        </div>
        <div class="col-md-4">
            <ul class="nav nav-pills pull-right">
                <li><a href="<?php echo WEB_PATH; ?>/admin/usuario"><i class="icon icon-plus"></i> <span>Novo</span></a></li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <table class="table table-striped table-hover table-responsive">
                        <thead>
                            <tr>
                                <th><a href="#">Foto</a></th>
                                <th><a href="#">Nome</a></th>
                                <th><a href="#">Email</a></th>
                                <th><a href="#">Tipo</a></th>
                                <th><a href="#">Situação</a></th>
                                <th class="text-right"><a href="#">Opções</a></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $usuario) : ?>
                            <?php $url = WEB_PATH.'/admin/usuario/'.strtolower(sanitize_slug($usuario->nome)).'-'.$usuario->id_usuario; ?>
                            <tr>
                                <td>
                                    <a href="<?php echo $url; ?>"><img src="<?php echo get_gravatar($usuario->email, 24); ?>" class="img-circle" /></a>
                                </td>
                                <td><a href="<?php echo $url; ?>"><?php echo $usuario->nome; ?></a></td>
                                <td><a href="mailto:<?php echo $usuario->email; ?>"><?php echo $usuario->email; ?></a></td>
                                <td><?php echo $usuario->tipo; ?></td>
                                <td><?php echo $usuario->situacao; ?></td>
                                <td class="text-right">
                                    <a href="<?php echo $url; ?>"><i class="icon icon-pencil"></i> Alterar</a> 
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div><!--panel-body-->
            </div><!--panel-default-->
        </div><!--col-md-8-->
    </div><!--row-->
</div>

<?php require("footer.inc.php"); ?>