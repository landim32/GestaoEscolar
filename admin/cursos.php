<?php
require('common.inc.php');

$regraCurso = new Curso();

try {
    if (array_key_exists('excluir', $_GET)) {
        $id_curso = intval($_GET['excluir']);
        $regraCurso->excluir($id_curso);
        $msgsucesso = 'Curso removido com sucesso!';
        header('Location: '.WEB_PATH.'/admin/cursos?sucesso='.  urlencode($msgsucesso));
        exit();
    }
    
    if (count($_POST) > 0) {
    
        $curso = $regraCurso->pegarDoPost();
        if ($curso->id_curso > 0) {
            $regraCurso->alterar($curso);
            $msgsucesso = 'Curso alterar com sucesso!';
        }
        else {
            $regraCurso->inserir($curso);
            $msgsucesso = 'Curso adicionado com sucesso!';
        }
        header('Location: '.WEB_PATH.'/admin/cursos?sucesso='.  urlencode($msgsucesso));
        exit();
    }
}
catch (Exception $e) {
    $msgerro = $e->getMessage();
}

$id_curso = intval($_GET['curso']);

$cod_situacao = null;
if (array_key_exists('situacao', $_GET))
    $cod_situacao = intval($_GET['situacao']);
$cursos = $regraCurso->listar($cod_situacao);

require("header.inc.php");
require('menu-principal.inc.php');
?>
<div class="container" style="margin-top: 80px">
    <div class="row">
        <div class="col-md-8">
            <ol class="breadcrumb">
                <li><i class="icon icon-home"></i> <a href="<?php echo WEB_PATH; ?>/admin/">Início</a></li>
                <li class="current"><i class="icon icon-users"></i> <a href="<?php echo WEB_PATH; ?>/admin/cursos">Cursos</a></li>
            </ol>
        </div>
        <div class="col-md-4">
            <ul class="nav nav-pills pull-right">
                <li><a href="<?php echo WEB_PATH; ?>/admin/cursos?curso=0"><i class="icon icon-plus"></i> <span>Novo</span></a></li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-offset-2 col-md-8">
            <div class="panel panel-default">
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
                    
                    <table class="table table-striped table-hover table-responsive">
                        <thead>
                            <tr>
                                <th><a href="#">Curso</a></th>
                                <th class="text-right"><a href="#">Opções</a></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (array_key_exists('curso', $_GET) && $id_curso == 0) : ?>
                            <form method="POST" class="form-vertical">
                            <tr>
                                <td>
                                    <input type="text" name="nome" class="form-control input-sm" placeholder="Preencha o nome do curso" />
                                </td>
                                <td class="text-right">
                                    <button type="submit" class="btn btn-sm btn-primary">Inserir</button>
                                    <a href="cursos" class="btn btn-sm btn-default">Cancelar</a>
                                </td>
                            </tr>
                            </form>
                            <?php elseif (count($cursos) <= 0) : ?>
                            <tr>
                                <td colspan="2">
                                    <i class="icon icon-warning"></i> Nenhum curso cadastrado!
                                </td>
                            </tr>
                            <?php endif; ?>
                            <?php foreach ($cursos as $curso) : ?>
                            <?php $url = 'cursos?curso='.$curso->id_curso; ?>
                            <?php if ($curso->id_curso == $id_curso) : ?>
                            <form method="POST" class="form-vertical">
                            <input type="hidden" name="id_curso" value="<?php echo $id_curso; ?>" />
                            <tr>
                                <td>
                                    <input type="text" name="nome" class="form-control input-sm" value="<?php echo $curso->nome; ?>" />
                                </td>
                                <td class="text-right">
                                    <button type="submit" class="btn btn-sm btn-primary">Alterar</button>
                                    <a href="cursos" class="btn btn-sm btn-default">Cancelar</a>
                                </td>
                            </tr>
                            </form>
                            <?php else : ?>
                            <tr>
                                <td><a href="<?php echo $url; ?>"><?php echo $curso->nome; ?></a></td>
                                <td class="text-right">
                                    <a href="<?php echo $url; ?>"><i class="icon icon-pencil"></i> Alterar</a> 
                                    <a href="<?php echo 'cursos?excluir='.$curso->id_curso; ?>"><i class="icon icon-remove"></i> Excluir</a> 
                                </td>
                            </tr>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div><!--panel-body-->
            </div><!--panel-default-->
        </div><!--col-md-8-->
    </div><!--row-->
</div>

<?php require("footer.inc.php"); ?>