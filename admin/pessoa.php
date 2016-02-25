<?php
require('common.inc.php');

$regraPessoa = new Pessoa();
$regraCurso = new Curso();

$meses = array(
    1 => 'Janeiro',
    2 => 'Fevereiro',
    3 => 'Março',
    4 => 'Abril',
    5 => 'Maio',
    6 => 'Junho',
    7 => 'Julho',
    8 => 'Agosto',
    9 => 'Setembro',
    10 => 'Outubro',
    11 => 'Novembro',
    12 => 'Dezembro'
);

try {
    
    if (array_key_exists('responsavel', $_GET) && array_key_exists('aluno', $_GET)) {
        $id_responsavel = intval($_GET['responsavel']);
        $id_aluno = intval($_GET['aluno']);
        $regraPessoa->relacionar($id_responsavel, $id_aluno);
        header('Location: '.WEB_PATH.'/admin/pessoa?pessoa='.$id_aluno);
        exit();
    }
    if (array_key_exists('excluir', $_GET)) {
        $id_pessoa = intval($_GET['excluir']);
        $regraPessoa->excluir($id_pessoa);
        $msgsucesso = 'Pessoa removida com sucesso!';
        header('Location: '.WEB_PATH.'/admin/pessoas?sucesso='.  urlencode($msgsucesso));
        exit();
    }
    if (array_key_exists('excluir-relacionamento', $_GET)) {
        $dados = explode('-', $_GET['excluir-relacionamento']);
        $id_pessoa = intval($_GET['pessoa']);
        $id_responsavel = intval($dados[0]);
        $id_aluno = intval($dados[1]);
        $regraPessoa->excluirRelacionamento($id_responsavel, $id_aluno);
        $msgsucesso = 'Relacionamento excluído com sucesso!';
        header('Location: '.WEB_PATH.'/admin/pessoa?pessoa='.$id_pessoa.'&sucesso='.  urlencode($msgsucesso));
        exit();
    }
    if (array_key_exists('gerar', $_GET)) {
        $regraMovimento = new Movimento();
        
        $id_pessoa = intval($_GET['pessoa']);
        $dados = explode('-', $_GET['gerar']);
        $mes = intval($dados[0]);
        if (!($mes > 0))
            $mes = null;
        $ano = intval($dados[1]);
        
        $msgsucesso = 'Movimentos gerados com sucesso!';
        $regraMovimento->gerarMensalidade($ano, $mes, $id_pessoa);
        header('Location: '.WEB_PATH.'/admin/pessoa?pessoa='.$id_pessoa.'&sucesso='.  urlencode($msgsucesso));
        exit();
    }
}
catch (Exception $e) {
    $msgerro = $e->getMessage();
}

$id_pessoa = intval($_GET['pessoa']);
if ($id_pessoa > 0) {
    $pessoa = $regraPessoa->pegar($id_pessoa);
    $GLOBALS['_pessoa'] = $pessoa;
}

require("header.inc.php");
require('menu-principal.inc.php');
require('movimento-modal.inc.php');
require('movimento-pagar.inc.php');
require('movimento-cancelar.inc.php');
?>
<div class="container" style="margin-top: 80px">
    <div class="row">
        <div class="col-md-5">
            <ol class="breadcrumb">
                <li><i class="icon icon-home"></i> <a href="<?php echo WEB_PATH; ?>/admin/">Início</a></li>
                <li class="current"><i class="icon icon-users"></i> <a href="<?php echo WEB_PATH; ?>/admin/pessoas">Pessoas</a></li>
                <li class="current"><i class="icon icon-user"></i> <a href="<?php echo WEB_PATH; ?>/admin/pessoa?pessoa=<?php echo $pessoa->id_pessoa; ?>"><?php echo $pessoa->nome; ?></a></li>
            </ol>
        </div>
        <div class="col-md-7">
            <ul class="nav nav-pills pull-right">
                <?php if (!is_null($pessoa)) : ?>
                <?php if ($pessoa->tipo === TIPO_ALUNO) : ?>
                <li role="presentation" class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="icon icon-dollar"></i> Gerar <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo WEB_PATH; ?>/admin/pessoa?pessoa=<?php echo $pessoa->id_pessoa; ?>&gerar=0-<?php echo date('Y'); ?>"><i class="icon icon-calendar"></i> <?php echo "Todo o ano de ".date('Y'); ?></a> </li>
                        <li role="separator" class="divider"></li>
                        <?php for ($i = 1; $i <= 12; $i++) : ?>
                        <li><a href="<?php echo WEB_PATH; ?>/admin/pessoa?pessoa=<?php echo $pessoa->id_pessoa; ?>&gerar=<?php echo $i; ?>-<?php echo date('Y'); ?>"><i class="icon icon-calendar"></i> <?php echo $meses[$i]." de ".date('Y'); ?></a> </li>
                        <?php endfor; ?>
                    </ul>
                </li>
                <li><a href="#" class="movimento" data-aluno="<?php echo $pessoa->id_pessoa; ?>" data-aluno-nome="<?php echo $pessoa->nome; ?>"><i class="icon icon-dollar"></i> <span>Novo movimento</span></a></li>
                <?php else : ?>
                <li><a target="_blank" href="movimento-imprimir?pessoa=<?php echo $pessoa->id_pessoa; ?>&ano=<?php echo date('Y'); ?>"><i class="icon icon-print"></i> <span>Imprimir Carnê <?php echo date('Y'); ?></span></a></li>
                <li><a href="#" class="movimento" data-pessoa="<?php echo $pessoa->id_pessoa; ?>" data-pessoa-nome="<?php echo $pessoa->nome; ?>"><i class="icon icon-dollar"></i> <span>Novo movimento</span></a></li>
                <?php endif; ?>
                <li><a href="<?php echo WEB_PATH; ?>/admin/pessoa-form?pessoa=<?php echo $pessoa->id_pessoa; ?>"><i class="icon icon-pencil"></i> <span>Alterar</span></a></li>
                <li><a href="<?php echo WEB_PATH; ?>/admin/pessoa-form?excluir=<?php echo $pessoa->id_pessoa; ?>"><i class="icon icon-remove"></i> <span>Excluir</span></a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="icon icon-reorder"></i> Dados Básicos</h3>
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
                    <div class="row">
                        <div class="col-md-2">
                            <img src="<?php echo get_gravatar($pessoa->email1); ?>" class="img-circle" />
                        </div>
                        <div class="col-md-10">
                            <dl class="dl-horizontal">
                                <dt>Nome:</dt>
                                <dd>
                                    <?php echo $pessoa->nome; ?>
                                    <?php if ($pessoa->tipo == TIPO_ALUNO) : ?>
                                    <span class="label label-success">Aluno</span>
                                    <?php elseif ($pessoa->tipo == TIPO_RESPONSAVEL) : ?>
                                    <span class="label label-info">Responsável</span>
                                    <?php endif; ?>
                                </dd>
                                <?php if ($pessoa->id_turma > 0) : ?>
                                <dt>Turma:</dt>
                                <dd><?php echo $pessoa->turma; ?></dd>
                                <?php endif; ?>
                                <?php if (!isNullOrEmpty($pessoa->cpf_cnpj)) : ?>
                                <dt>CPF/CNPJ:</dt>
                                <dd><?php echo $pessoa->cpf_cnpj; ?></dd>
                                <?php endif; ?>
                                <?php if ($pessoa->valor_mensalidade > 0) : ?>
                                <dt>Valor da Mensalidade:</dt>
                                <dd><?php echo number_format($pessoa->valor_mensalidade, 2, ',', '.'); ?></dd>
                                <?php endif; ?>
                                <?php if (!is_null($pessoa->data_nascimento)) : ?>
                                <dt>Dt. Nasc.:</dt>
                                <dd><?php echo date('d/m/Y', strtotime($pessoa->data_nascimento)); ?></dd>
                                <?php endif; ?>
                                <?php if (!is_null($pessoa->genero)) : ?>
                                <dt>Gênero:</dt>
                                <dd><?php echo ($pessoa->genero == 'm') ? 'Masculino' : 'Feminino';  ?></dd>
                                <?php endif; ?>
                                <?php if (!is_null($pessoa->telefone1)) : ?>
                                <dt>Telefones:</dt>
                                <dd><?php echo formatar_telefone($pessoa->telefone1);  ?></dd>
                                <?php endif; ?>
                                <?php if (!is_null($pessoa->telefone2)) : ?>
                                <dt>&nbsp;</dt>
                                <dd><?php echo formatar_telefone($pessoa->telefone2);  ?></dd>
                                <?php endif; ?>
                                <?php if (!is_null($pessoa->telefone3)) : ?>
                                <dt>&nbsp;</dt>
                                <dd><?php echo formatar_telefone($pessoa->telefone3);  ?></dd>
                                <?php endif; ?>
                                <?php if (!is_null($pessoa->telefone4)) : ?>
                                <dt>&nbsp;</dt>
                                <dd><?php echo formatar_telefone($pessoa->telefone4);  ?></dd>
                                <?php endif; ?>
                                <?php if (!is_null($pessoa->email1)) : ?>
                                <dt>Emails:</dt>
                                <dd><a href="mailto:<?php echo $pessoa->email1;  ?>"><?php echo $pessoa->email1;  ?></a></dd>
                                <?php endif; ?>
                                <?php if (!is_null($pessoa->email2)) : ?>
                                <dt>&nbsp;</dt>
                                <dd><a href="mailto:<?php echo $pessoa->email2;  ?>"><?php echo $pessoa->email2;  ?></a></dd>
                                <?php endif; ?>
                                <?php if (!is_null($pessoa->email3)) : ?>
                                <dt>&nbsp;</dt>
                                <dd><a href="mailto:<?php echo $pessoa->email3;  ?>"><?php echo $pessoa->email3;  ?></a></dd>
                                <?php endif; ?>
                                <?php if (!is_null($pessoa->email4)) : ?>
                                <dt>&nbsp;</dt>
                                <dd><a href="mailto:<?php echo $pessoa->email4;  ?>"><?php echo $pessoa->email4;  ?></a></dd>
                                <?php endif; ?>
                                <?php if (!is_null($pessoa->endereco)) : ?>
                                <dt>Endereço:</dt>
                                <dd><?php echo $pessoa->endereco;  ?></dd>
                                <?php endif; ?>
                                <?php if (!is_null($pessoa->endereco)) : ?>
                                <dt>Complemento:</dt>
                                <dd><?php echo $pessoa->complemento;  ?></dd>
                                <?php endif; ?>
                                <?php if (!is_null($pessoa->bairro)) : ?>
                                <dt>Bairro:</dt>
                                <dd><?php echo $pessoa->bairro;  ?></dd>
                                <?php endif; ?>
                                <?php if (!is_null($pessoa->cidade)) : ?>
                                <dt>Cidade:</dt>
                                <dd><?php echo $pessoa->cidade;  ?></dd>
                                <?php endif; ?>
                                <?php if (!is_null($pessoa->uf)) : ?>
                                <dt>UF:</dt>
                                <dd><?php echo $pessoa->uf;  ?></dd>
                                <?php endif; ?>
                            </dl>
                        </div>
                    </div>
                </div><!--panel-body-->
            </div><!--panel-default-->
        </div><!--col-md-8-->
        <div class="col-md-4">
            <?php if ($pessoa->tipo == TIPO_ALUNO) : ?>
            <?php $responsaveis = $regraPessoa->listarResponsavel($id_pessoa); ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="icon icon-users"></i> Responsáveis</h3>
                </div>
                <div class="panel-body">
                    <?php if (count($responsaveis) > 0) : ?>
                    <?php foreach ($responsaveis as $responsavel) : ?>
                    <?php
                        $telefones = array();
                        if (!isNullOrEmpty($responsavel->telefone1))
                            $telefones[] = formatar_telefone($responsavel->telefone1);
                        if (!isNullOrEmpty($responsavel->telefone2))
                            $telefones[] = formatar_telefone($responsavel->telefone2);
                        if (!isNullOrEmpty($responsavel->telefone3))
                            $telefones[] = formatar_telefone($responsavel->telefone3);
                        if (!isNullOrEmpty($responsavel->telefone4))
                            $telefones[] = formatar_telefone($responsavel->telefone4);
                    ?>
                    <div class="row">
                        <div class="col-md-2">
                            <img src="<?php echo get_gravatar($responsavel->email1, 40); ?>" class="img-circle" />
                        </div>
                        <div class="col-md-10">
                            <a class="pull-right" href="<?php echo WEB_PATH; ?>/admin/pessoa?pessoa=<?php echo $pessoa->id_pessoa; ?>&excluir-relacionamento=<?php echo $responsavel->id_pessoa; ?>-<?php echo $pessoa->id_pessoa; ?>"><i class="icon icon-remove"></i></a>
                            <a href="<?php echo WEB_PATH; ?>/admin/pessoa?pessoa=<?php echo $responsavel->id_pessoa; ?>" style="font-weight: bold"><?php echo $responsavel->nome; ?></a>
                            <?php if (!isNullOrEmpty($responsavel->email1)) : ?>
                            <br /><a href="mailto:<?php echo $responsavel->email1; ?>"><i class="icon icon-envelope"></i> <?php echo $responsavel->email1; ?></a>
                            <?php endif; ?>
                            <?php if (count($telefones) > 0) : ?>
                            <br /><small><i class="icon icon-phone"></i> <?php echo implode(', ', $telefones); ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <hr />
                    <?php endforeach; ?>
                    <?php endif; ?>
                    <div class="text-right">
                        <div class="btn-group dropup">
                            <button type="button" class="btn btn-default"><i class="icon icon-plus"></i> Adicionar</button>
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo WEB_PATH; ?>/admin/pessoa-form?aluno=<?php echo $pessoa->id_pessoa; ?>">Cadastrar um novo</a></li>
                                <li><a href="<?php echo WEB_PATH; ?>/admin/pessoa-selecionar?aluno=<?php echo $pessoa->id_pessoa; ?>">Selecionar um já existente</a></li>
                            </ul>
                        </div>
                    </div>
                </div><!--panel-body-->
            </div><!--panel-default-->
            <?php endif; //$pessoa->tipo == TIPO_ALUNO ?>
            <?php if ($pessoa->tipo == TIPO_RESPONSAVEL) : ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="icon icon-reorder"></i> Alunos</h3>
                </div>
                <div class="panel-body">
                    <?php $alunos = $regraPessoa->listarAluno($id_pessoa); ?>
                    <?php if (count($alunos) > 0) : ?>
                    <?php foreach ($alunos as $aluno) : ?>
                    <?php
                        $telefones = array();
                        if (!isNullOrEmpty($aluno->telefone1))
                            $telefones[] = formatar_telefone($aluno->telefone1);
                        if (!isNullOrEmpty($aluno->telefone2))
                            $telefones[] = formatar_telefone($aluno->telefone2);
                        if (!isNullOrEmpty($aluno->telefone3))
                            $telefones[] = formatar_telefone($aluno->telefone3);
                        if (!isNullOrEmpty($aluno->telefone4))
                            $telefones[] = formatar_telefone($aluno->telefone4);
                    ?>
                    <div class="row">
                        <div class="col-md-2">
                            <img src="<?php echo get_gravatar($aluno->email1, 40); ?>" class="img-circle" />
                        </div>
                        <div class="col-md-10">
                            <a class="pull-right" href="<?php echo WEB_PATH; ?>/admin/pessoa?pessoa=<?php echo $pessoa->id_pessoa; ?>&excluir-relacionamento=<?php echo $pessoa->id_pessoa; ?>-<?php echo $aluno->id_pessoa; ?>"><i class="icon icon-remove"></i></a>
                            <a href="<?php echo WEB_PATH; ?>/admin/pessoa?pessoa=<?php echo $aluno->id_pessoa; ?>" style="font-weight: bold"><?php echo $aluno->nome; ?></a>
                            <?php if ($aluno->tipo == TIPO_ALUNO) : ?>
                            <br /><i class="icon icon-graduation-cap"></i> <?php echo $aluno->turma; ?>
                            <?php endif; ?>
                            <?php if (!isNullOrEmpty($aluno->email1)) : ?>
                            <br /><a href="mailto:<?php echo $aluno->email1; ?>"><i class="icon icon-envelope"></i> <?php echo $aluno->email1; ?></a>
                            <?php endif; ?>
                            <?php if (count($telefones) > 0) : ?>
                            <br /><small><i class="icon icon-phone"></i> <?php echo implode(', ', $telefones); ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <hr />
                    <?php endforeach; ?>
                    <?php endif; ?>
                    <div class="text-right">
                        <div class="btn-group dropup">
                            <button type="button" class="btn btn-default"><i class="icon icon-plus"></i> Adicionar</button>
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo WEB_PATH; ?>/admin/pessoa-form?responsavel=<?php echo $pessoa->id_pessoa; ?>">Cadastrar um novo</a></li>
                                <li><a href="<?php echo WEB_PATH; ?>/admin/pessoa-selecionar?responsavel=<?php echo $pessoa->id_pessoa; ?>">Selecionar um já existente</a></li>
                            </ul>
                        </div>
                    </div>
                </div><!--panel-body-->
            </div><!--panel-default-->
            <?php endif; //$pessoa->tipo == TIPO_RESPONSAVEL ?>
        </div><!--col-md-4-->
    </div><!--row-->
    <div class="row">
        <div class="col-md-12">
            <?php require('movimento.inc.php'); ?>
        </div><!--col-md-12-->
    </div><!--row-->
</div>

<?php require("footer.inc.php"); ?>