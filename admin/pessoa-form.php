<?php
require('common.inc.php');

$regraPessoa = new Pessoa();
$regraTurma = new Turma();

try {
    if (array_key_exists('excluir', $_GET)) {
        $id_pessoa = intval($_GET['excluir']);
        $regraPessoa->excluir($id_pessoa);
        $msgsucesso = 'Pessoa removida com sucesso!';
        header('Location: '.WEB_PATH.'/admin/pessoas?sucesso='.  urlencode($msgsucesso));
        exit();
    }
    
    if (count($_POST) > 0) {
        $id_pessoa = intval($_POST['id_pessoa']);
        if ($id_pessoa > 0) {
            $pessoa = $regraPessoa->pegar($id_pessoa);
            $pessoa = $regraPessoa->pegarDoPost($pessoa);
            $regraPessoa->alterar($pessoa);
            $msgsucesso = 'Pessoa alterar com sucesso!';
        }
        else {
            $pessoa = $regraPessoa->pegarDoPost($pessoa);
            //var_dump($pessoa, $_POST);
            //exit();
            $id_pessoa = $regraPessoa->inserir($pessoa);
            $msgsucesso = 'Pessoa adicionado com sucesso!';
        }
        header('Location: '.WEB_PATH.'/admin/pessoa?pessoa='.$id_pessoa.'&sucesso='.  urlencode($msgsucesso));
        exit();
    }
}
catch (Exception $e) {
    $msgerro = $e->getMessage();
}

$id_aluno = intval($_GET['aluno']);
$id_responsavel = intval($_GET['responsavel']);
$id_pessoa = intval($_GET['pessoa']);
if ($id_pessoa > 0) {
    $pessoa = $regraPessoa->pegar($id_pessoa);
}
if ($id_aluno > 0) {
    $aluno = $regraPessoa->pegar($id_aluno);
    if (!($id_pessoa > 0)) {
        $pessoa = new stdClass();
        $pessoa->tipo = TIPO_RESPONSAVEL;
        $pessoa->endereco = $aluno->endereco;
        $pessoa->complemento = $aluno->complemento;
        $pessoa->bairro = $aluno->bairro;
        $pessoa->cidade = $aluno->cidade;
        $pessoa->uf = $aluno->uf;
        $pessoa->telefone1 = $aluno->telefone1;
        $pessoa->telefone2 = $aluno->telefone2;
        $pessoa->telefone3 = $aluno->telefone3;
        $pessoa->telefone4 = $aluno->telefone4;
    }
}
if ($id_responsavel > 0) {
    $responsavel = $regraPessoa->pegar($id_responsavel);
    if (!($id_pessoa > 0)) {
        $pessoa = new stdClass();
        $pessoa->tipo = TIPO_ALUNO;
        $pessoa->endereco = $responsavel->endereco;
        $pessoa->complemento = $responsavel->complemento;
        $pessoa->bairro = $responsavel->bairro;
        $pessoa->cidade = $responsavel->cidade;
        $pessoa->uf = $responsavel->uf;
        $pessoa->telefone1 = $responsavel->telefone1;
        $pessoa->telefone2 = $responsavel->telefone2;
        $pessoa->telefone3 = $responsavel->telefone3;
        $pessoa->telefone4 = $responsavel->telefone4;
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
    
    <form method="POST" class="form-vertical">
    <?php if (!is_null($pessoa)) : ?>
    <input type="hidden" name="id_pessoa" value="<?php echo $pessoa->id_pessoa; ?>" />
    <?php endif; ?>
    <?php if ($id_aluno > 0) : ?>
    <input type="hidden" name="id_aluno" value="<?php echo $id_aluno; ?>" />
    <?php endif; ?>
    <?php if ($id_responsavel > 0) : ?>
    <input type="hidden" name="id_responsavel" value="<?php echo $id_responsavel; ?>" />
    <?php endif; ?>
    <div class="row">
        <div class="col-md-7">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="icon icon-reorder"></i> Dados Básicos</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="control-label">Nome<span class="required">*</span>:</label>
                                <input type="text" name="nome" class="form-control required" value="<?php echo $pessoa->nome; ?>" placeholder="Preencha o nome (obrigatório)" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <?php if ((is_null($pessoa) || $pessoa->tipo == TIPO_ALUNO)) : ?>
                            <div class="form-group">
                                <label class="control-label">Turma<span class="required">*</span>:</label>
                                <select name="id_turma" class="form-control required">
                                    <?php foreach ($regraTurma->listar() as $turma) : ?>
                                    <option value="<?php echo $turma->id_turma; ?>"<?php echo ($turma->id_turma == $pessoa->id_turma) ? 'selected="selected"' : ''; ?>><?php echo $turma->nome; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Data Nasc.:</label>
                                <input type="text" name="data_nascimento" maxlength="10" class="form-control" value="<?php echo (!is_null($pessoa) && !is_null($pessoa->data_nascimento)) ? date('d/m/Y', strtotime($pessoa->data_nascimento)) : ''; ?>" />
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="control-label">Gênero:</label><br />
                                <div class="btn-group" data-toggle="buttons" id="genero">
                                    <label class="<?php echo ($pessoa->genero == 'm') ? 'btn active' : 'btn'; ?>">
                                        <input type="radio" name="genero" value="m"<?php echo ($pessoa->genero == 'm') ? ' checked="checked"' : ''; ?>><i class="icon icon-male"></i> Masculino
                                    </label>
                                    <label class="<?php echo ($pessoa->genero == 'f') ? 'btn active' : 'btn'; ?>">
                                        <input type="radio" name="genero" value="f"<?php echo ($pessoa->genero == 'f') ? ' checked="checked"' : ''; ?>><i class="icon icon-female"></i> Feminino
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Tipo:</label><br />
                                <div class="btn-group" data-toggle="buttons" id="genero">
                                    <label class="<?php echo ($pessoa->tipo == 'a') ? 'btn active' : 'btn'; ?>">
                                        <input type="radio" name="tipo" value="a"<?php echo ($pessoa->tipo == 'a') ? ' checked="checked"' : ''; ?>>Aluno
                                    </label>
                                    <label class="<?php echo ($pessoa->tipo == 'r') ? 'btn active' : 'btn'; ?>">
                                        <input type="radio" name="tipo" value="r"<?php echo ($pessoa->tipo == 'r') ? ' checked="checked"' : ''; ?>>Responsável
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label class="control-label">CPF/CNPJ:</label>
                                <input type="text" name="cpf_cnpj" maxlength="20" class="form-control cpf" value="<?php echo (!is_null($pessoa) && !is_null($pessoa->cpf_cnpj)) ? $pessoa->cpf_cnpj : ''; ?>" />
                            </div>
                        </div>
                        <?php if ((is_null($pessoa) || $pessoa->tipo == TIPO_ALUNO)) : ?>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="control-label">Valor da Mensalidade:</label>
                                <input type="text" name="valor_mensalidade" maxlength="20" class="form-control money text-right" value="<?php echo (!is_null($pessoa) && $pessoa->valor_mensalidade > 0) ? number_format($pessoa->valor_mensalidade, 2, ',', '.') : ''; ?>" />
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div><!--panel-body-->
            </div><!--panel-default-->
            
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="icon icon-map-marker"></i> Endereço</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="control-label">Endereço:</label>
                        <input type="text" name="endereco" class="form-control" value="<?php echo $pessoa->endereco; ?>" placeholder="Preencha o endereço" />
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Complemento:</label>
                                <input type="text" name="complemento" class="form-control" value="<?php echo $pessoa->complemento; ?>" />
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="control-label">Bairro:</label>
                                <input type="text" name="bairro" class="form-control" value="<?php echo $pessoa->bairro; ?>" placeholder="Preencha o bairro" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group">
                                <label class="control-label">Cidade:</label>
                                <input type="text" name="cidade" class="form-control" value="<?php echo $pessoa->cidade; ?>" placeholder="Preencha a cidade" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">UF:</label>
                                <input type="text" name="uf" class="form-control" value="<?php echo $pessoa->uf; ?>" />
                            </div>
                        </div>
                    </div>
                </div><!--panel-body-->
            </div><!--panel-default-->
        </div><!--col-md-4-->
        <div class="col-md-5">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="icon icon-envelope"></i> Dados de Contatos</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Telefone 1:</label>
                                <input type="text" name="telefone1" class="form-control" value="<?php echo $pessoa->telefone1; ?>" placeholder="Preencha o telefone" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Telefone 2:</label>
                                <input type="text" name="telefone2" class="form-control" value="<?php echo $pessoa->telefone2; ?>" placeholder="Preencha o telefone" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Telefone 3:</label>
                                <input type="text" name="telefone3" class="form-control" value="<?php echo $pessoa->telefone3; ?>" placeholder="Preencha o telefone" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Telefone 4:</label>
                                <input type="text" name="telefone4" class="form-control" value="<?php echo $pessoa->telefone4; ?>" placeholder="Preencha o telefone" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Email:</label>
                        <input type="text" name="email1" class="form-control" value="<?php echo $pessoa->email1; ?>" placeholder="Preencha o email" />
                    </div>
                </div><!--panel-body-->
            </div><!--panel-default-->
            <div class="text-right">
                <button type="submit" class="btn btn-lg btn-primary">Salvar</button>
                <a href="<?php echo WEB_PATH; ?>/admin/pessoas" class="btn btn-lg btn-default">Cancelar</a>
            </div>
        </div><!--col-md-4-->
    </div><!--row-->
    
    </form>
</div>

<?php require("footer.inc.php"); ?>