<?php
require('common.inc.php');

if (array_key_exists('aluno', $_GET)) {
    $tipo = TIPO_RESPONSAVEL;
    $id_aluno = intval($_GET['aluno']);
}
elseif (array_key_exists('responsavel', $_GET)) {
    $tipo = TIPO_ALUNO;
    $id_responsavel = intval($_GET['responsavel']);
}

$regraPessoa = new Pessoa();
$pessoas = $regraPessoa->listar($tipo);

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
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <table class="table table-striped table-condensed table-hover">
                        <thead>
                            <tr>
                                <th><a href="#">Nome</a></th>
                                <th><a href="#">Email</a></th>
                                <th><a href="#">Telefone 1</a></th>
                                <th><a href="#">Telefone 2</a></th>
                                <th><a href="#">Opções</a></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pessoas as $pessoa) : ?>
                            <?php 
                                if ($tipo == TIPO_ALUNO)
                                    $url = WEB_PATH.'/admin/pessoa?responsavel='.$id_responsavel.'&aluno='.$pessoa->id_pessoa; 
                                else
                                    $url = WEB_PATH.'/admin/pessoa?responsavel='.$pessoa->id_pessoa.'&aluno='.$id_aluno; 
                             ?>
                            <tr>
                                <td><a href="<?php echo $url; ?>"><?php echo $pessoa->nome; ?></a></td>
                                <td><a href="<?php echo $url; ?>"><?php echo $pessoa->email1; ?></a></td>
                                <td><a href="<?php echo $url; ?>"><?php echo formatar_telefone($pessoa->telefone1); ?></a></td>
                                <td><a href="<?php echo $url; ?>"><?php echo formatar_telefone($pessoa->telefone2); ?></a></td>
                                <td><a href="<?php echo $url; ?>"><i class="icon icon-plus"></i> Adicionar</a></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require("footer.inc.php"); ?>