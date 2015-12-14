<?php
require('common.inc.php');

$regraPessoa = new Pessoa();
if ($_GET['tipo'] == 'aluno')
    $tipo = TIPO_ALUNO;
if ($_GET['tipo'] == 'responsavel')
    $tipo = TIPO_RESPONSAVEL;
if (array_key_exists('p', $_GET))
    $palavraChave = $_GET['p'];
else
    $palavraChave = null;
$pessoas = $regraPessoa->listar($tipo, PESSOA_ATIVO, $palavraChave);

require("header.inc.php");
require('menu-principal.inc.php');
?>
<div class="container" style="margin-top: 80px">
    <div class="row">
        <div class="col-md-8">
            <ol class="breadcrumb">
                <li><i class="icon icon-home"></i> <a href="<?php echo WEB_PATH; ?>/admin/">Início</a></li>
                <li class="current"><i class="icon icon-users"></i> <a href="<?php echo WEB_PATH; ?>/admin/pessoas">Pessoas</a></li>
            </ol>
        </div>
        <div class="col-md-4">
            <ul class="nav nav-pills pull-right">
                <li><a href="<?php echo WEB_PATH; ?>/admin/pessoa-form"><i class="icon icon-plus"></i> <span>Novo</span></a></li>
                <?php if (!is_null($pessoa)) : ?>
                <li><a href="<?php echo WEB_PATH; ?>/admin/pessoa-form?excluir=<?php echo $pessoa->id_pessoa; ?>"><i class="icon icon-remove"></i> <span>Excluir</span></a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    
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
    
    <div class="row">
        <div class="col-md-9">
            <?php foreach ($pessoas as $pessoa) : ?>
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-2">
                            <img src="<?php echo get_gravatar($pessoa->email1); ?>" class="img-circle" />
                        </div>
                        <div class="col-md-10">
                            <a href="<?php echo WEB_PATH; ?>/admin/pessoa?pessoa=<?php echo $pessoa->id_pessoa; ?>" style="font-weight: bold"><?php echo $pessoa->nome; ?></a>
                            <?php if ($pessoa->tipo == TIPO_ALUNO) : ?>
                            <span class="label label-success">Aluno</span>
                            <?php elseif ($pessoa->tipo == TIPO_RESPONSAVEL) : ?>
                            <span class="label label-info">Responsável</span>
                            <?php endif; ?>
                            <?php if ($pessoa->tipo == TIPO_ALUNO) : ?>
                            <br /><i class="icon icon-graduation-cap"></i> <?php echo $pessoa->turma; ?>
                            <?php endif; ?>
                            <?php if (!isNullOrEmpty($pessoa->email1)) : ?>
                            <br /><a href="mailto:<?php echo $pessoa->email1; ?>"><i class="icon icon-envelope"></i> <?php echo $pessoa->email1; ?></a>
                            <?php endif; ?>
                            <div class="row">
                                <?php if (!isNullOrEmpty($pessoa->telefone1)) : ?>
                                <div class="col-md-3">
                                    <i class="icon icon-phone"></i> <?php echo formatar_telefone($pessoa->telefone1); ?>
                                </div>
                                <?php endif; ?>
                                <?php if (!isNullOrEmpty($pessoa->telefone2)) : ?>
                                <div class="col-md-3">
                                    <i class="icon icon-phone"></i> <?php echo formatar_telefone($pessoa->telefone2); ?>
                                </div>
                                <?php endif; ?>
                                <?php if (!isNullOrEmpty($pessoa->telefone3)) : ?>
                                <div class="col-md-3">
                                    <i class="icon icon-phone"></i> <?php echo formatar_telefone($pessoa->telefone3); ?>
                                </div>
                                <?php endif; ?>
                                <?php if (!isNullOrEmpty($pessoa->telefone4)) : ?>
                                <div class="col-md-3">
                                    <i class="icon icon-phone"></i> <?php echo formatar_telefone($pessoa->telefone4); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="col-md-3">
            <div class="list-group">
                <a href="<?php echo WEB_PATH; ?>/admin/pessoas" class="<?php echo (!array_key_exists('tipo', $_GET)) ? 'list-group-item active' : 'list-group-item'; ?>"><i class="icon icon-users"></i> Todos</a>
                <a href="<?php echo WEB_PATH; ?>/admin/pessoas?tipo=aluno" class="<?php echo ($_GET['tipo'] == 'aluno') ? 'list-group-item active' : 'list-group-item'; ?>"><i class="icon icon-child"></i> Alunos</a>
                <a href="<?php echo WEB_PATH; ?>/admin/pessoas?tipo=responsavel" class="<?php echo ($_GET['tipo'] == 'responsavel') ? 'list-group-item active' : 'list-group-item'; ?>"><i class="icon icon-male"></i> Responsáveis</a>
            </div>
        </div>
    </div>
</div>

<?php require("footer.inc.php"); ?>