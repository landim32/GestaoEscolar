<?php
require('common.inc.php');

$regraCurso = new Curso();
$regraTurma = new Turma();
$cursos = $regraCurso->listar(CURSO_ATIVO);
$turnos = $regraTurma->listarTurno();

try {
    if (array_key_exists('excluir', $_GET)) {
        $id_turma = intval($_GET['excluir']);
        $regraTurma->excluir($id_turma);
        $msgsucesso = 'Turma removida com sucesso!';
        header('Location: '.WEB_PATH.'/admin/turmas?sucesso='.  urlencode($msgsucesso));
        exit();
    }
    
    if (count($_POST) > 0) {
    
        $turma = $regraTurma->pegarDoPost();
        if ($turma->id_turma > 0) {
            $regraTurma->alterar($turma);
            $msgsucesso = 'Curso alterar com sucesso!';
        }
        else {
            $regraTurma->inserir($turma);
            $msgsucesso = 'Curso adicionado com sucesso!';
        }
        header('Location: '.WEB_PATH.'/admin/turmas?sucesso='.  urlencode($msgsucesso));
        exit();
    }
}
catch (Exception $e) {
    $msgerro = $e->getMessage();
}

$id_turma = intval($_GET['turma']);

$cod_situacao = null;
if (array_key_exists('situacao', $_GET))
    $cod_situacao = intval($_GET['situacao']);
$turmas = $regraTurma->listar($cod_situacao);

require("header.inc.php");
require('menu-principal.inc.php');
?>
<div class="container" style="margin-top: 80px">
    <div class="row">
        <div class="col-md-8">
            <ol class="breadcrumb">
                <li><i class="icon icon-home"></i> <a href="<?php echo WEB_PATH; ?>/admin/">Início</a></li>
                <li class="current"><i class="icon icon-users"></i> <a href="<?php echo WEB_PATH; ?>/admin/turmas">Turmas</a></li>
            </ol>
        </div>
        <div class="col-md-4">
            <ul class="nav nav-pills pull-right">
                <li><a href="<?php echo WEB_PATH; ?>/admin/turmas?turma=0"><i class="icon icon-plus"></i> <span>Novo</span></a></li>
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
                                <th><a href="#">Turma</a></th>
                                <th><a href="#">Curso</a></th>
                                <th><a href="#">Turno</a></th>
                                <th class="text-right"><a href="#">Opções</a></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (array_key_exists('turma', $_GET) && $id_turma == 0) : ?>
                            <form method="POST" class="form-vertical">
                            <tr>
                                <td>
                                    <input type="text" name="nome" class="form-control input-sm" placeholder="Preencha o nome da turma" />
                                </td>
                                <td>
                                    <select name="id_curso" class="form-control">
                                        <?php foreach ($cursos as $curso) : ?>
                                        <option value="<?php echo $curso->id_curso; ?>"><?php echo $curso->nome; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <select name="turno" class="form-control">
                                        <?php foreach ($turnos as $turno => $turno_nome) : ?>
                                        <option value="<?php echo $turno; ?>"><?php echo $turno_nome; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td class="text-right">
                                    <button type="submit" class="btn btn-sm btn-primary">Inserir</button>
                                    <a href="turmas" class="btn btn-sm btn-default">Cancelar</a>
                                </td>
                            </tr>
                            </form>
                            <?php elseif (count($turmas) <= 0) : ?>
                            <tr>
                                <td colspan="4">
                                    <i class="icon icon-warning"></i> Nenhuma turma cadastrado!
                                </td>
                            </tr>
                            <?php endif; ?>
                            <?php foreach ($turmas as $turma) : ?>
                            <?php $url = WEB_PATH.'/admin/turmas?turma='.$turma->id_turma; ?>
                            <?php if ($turma->id_turma == $id_turma) : ?>
                            <form method="POST" class="form-vertical">
                            <input type="hidden" name="id_turma" value="<?php echo $id_turma; ?>" />
                            <tr>
                                <td>
                                    <input type="text" name="nome" class="form-control input-sm" value="<?php echo $turma->nome; ?>" />
                                </td>
                                <td>
                                    <select name="id_curso" class="form-control">
                                        <option value="">--selecione--</option>
                                        <?php foreach ($cursos as $curso) : ?>
                                        <option value="<?php echo $curso->id_curso; ?>"<?php echo ($turma->id_curso == $curso->id_curso) ? ' selected="selected"' : ''; ?>><?php echo $curso->nome; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <select name="turno" class="form-control">
                                        <option value="">--selecione--</option>
                                        <?php foreach ($turnos as $turno => $turno_nome) : ?>
                                        <option value="<?php echo $turno; ?>"<?php echo ($turma->turno == $turno) ? ' selected="selected"' : ''; ?>><?php echo $turno_nome; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td class="text-right">
                                    <button type="submit" class="btn btn-sm btn-primary">Alterar</button>
                                    <a href="turmas" class="btn btn-sm btn-default">Cancelar</a>
                                </td>
                            </tr>
                            </form>
                            <?php else : ?>
                            <tr>
                                <td><a href="<?php echo $url; ?>"><?php echo $turma->nome; ?></a></td>
                                <td><a href="<?php echo $url; ?>"><?php echo $turma->curso; ?></a></td>
                                <td><a href="<?php echo $url; ?>"><?php echo $turma->turno_nome; ?></a></td>
                                <td class="text-right">
                                    <a href="<?php echo $url; ?>" class="btn btn-xs btn-default"><i class="icon icon-pencil"></i> Alterar</a> 
                                    <a href="<?php echo WEB_PATH.'/admin/turmas?excluir='.$turma->id_turma; ?>" class="btn btn-xs btn-default"><i class="icon icon-remove"></i> Excluir</a> 
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