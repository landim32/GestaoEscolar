<?php
require('common.inc.php');

$regraMovimento = new Movimento();

$id_tipo = intval($_GET['tipo']);
if (array_key_exists('situacao', $_GET))
    $cod_situacao = intval($_GET['situacao']);
else
    $cod_situacao = MOVIMENTO_EXECUTADO;

if (array_key_exists('ini', $_GET)) {
    $dataIni = explode('-', $_GET['ini']);
    $dataIni = mktime(0,0,0,$dataIni[1], $dataIni[2], $dataIni[0]);
}
else {
    $dataIni = strtotime(date('Y-m-01'));
}

if (array_key_exists('fim', $_GET)) {
    $dataFim = explode('-', $_GET['fim']);
    $dataFim = mktime(0,0,0,$dataFim[1], $dataFim[2], $dataFim[0]);
}
else {
    $dataFim = strtotime(date('Y-m-t'));
}

if (count($_POST) > 0) {
    //var_dump($_POST);
    //exit();
    try {
        if ($_POST['acao'] == 'movimento-filtrar') {
            $id_tipo = intval($_POST['id_tipo']);
            $cod_situacao = intval($_POST['cod_situacao']);
            $dataIni = explode('/', $_POST['ini']);
            $dataIni = mktime(0,0,0,$dataIni[1], $dataIni[0], $dataIni[2]);
            $dataFim = explode('/', $_POST['fim']);
            $dataFim = mktime(0,0,0,$dataFim[1], $dataFim[0], $dataFim[2]);
            $url  = 'movimentos?situacao='.$cod_situacao;
            $url .= '&ini='.date('Y-m-d', $dataIni);
            $url .= '&fim='.date('Y-m-d', $dataFim);
            if ($id_tipo > 0)
                $url .= '&tipo='.$id_tipo;
            header("Location: $url");
            exit();
        }
        elseif ($_POST['acao'] == 'movimento-novo') {
            $movimento = $regraMovimento->pegarDoPost();
            $regraMovimento->inserir($movimento);
        }
    }
    catch (Exception $e) {
        $msgErro = $e->getMessage();
    }
}
$movimentos = $regraMovimento->extrato($cod_situacao, $id_tipo, $dataIni, $dataFim);

/*
$situacao = ($_GET['situacao'] == '') ? 1 : intval($_GET['situacao']);
$clientes = $regraCliente->listar($situacao);

if (array_key_exists('excluir', $_POST)) {
    $id_cliente = intval($_POST['excluir']);
    $regraCliente->excluir($id_cliente);
    echo 'ok';
    exit();
}
 */
if (!function_exists('toolbar_breadcrump')) :
function toolbar_breadcrump() { 
    echo '<li><a href="#movimentoModal" data-toggle="modal"><i class="icon icon-plus"></i> <span>'._('New finance move').'</span></a></li>';
    /*
    if (IS_ADMIN) {
        echo '<li class="dropdown">';
        echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon icon-search"></i> <span>'._('By status').'</span> <i class="icon icon-angle-down small"></i></a>';
        echo '<ul class="dropdown-menu">';
        
        echo '<li><a href="movimentos?situacao='.MOVIMENTO_EXECUTADO.'">';
        echo '<i class="'.(($_GET['situacao'] == MOVIMENTO_EXECUTADO) ? 'icon icon-check-square-o' : 'icon icon-square-o').'"></i> ';
        echo '<span>'._('Balance').'</span></a></li>';
        
        echo '<li><a href="movimentos?situacao='.MOVIMENTO_AGENDADO.'">';
        echo '<i class="'.(($_GET['situacao'] == MOVIMENTO_AGENDADO) ? 'icon icon-check-square-o' : 'icon icon-square-o').'"></i> ';
        echo '<span>'._('Scheduled payments').'</span></a></li>';
        
        echo '<li><a href="movimentos?situacao='.MOVIMENTO_CANCELADO.'">';
        echo '<i class="'.(($_GET['situacao'] == MOVIMENTO_CANCELADO) ? 'icon icon-check-square-o' : 'icon icon-square-o').'"></i> ';
        echo '<span>'._('Reversed payments').'</span></a></li>';
        
        echo '</ul></li>';
    }
     */
}
endif;

require("header.inc.php");
require('movimento-modal.inc.php');
require('menu-principal.inc.php');
?>
<div class="container" style="margin-top: 80px">
    <div class="row">
        <div class="col-md-8">
            <ol class="breadcrumb">
                <li><i class="icon icon-home"></i> <a href="<?php echo WEB_PATH; ?>/admin/">Início</a></li>
                <li class="current"><i class="icon icon-users"></i> <a href="<?php echo WEB_PATH; ?>/admin/pessoas">Pessoas</a></li>
                <li class="current"><i class="icon icon-dollar"></i> <a href="<?php echo WEB_PATH; ?>/admin/movimentos">Financeiro</a></li>
            </ol>
        </div>
        <div class="col-md-4">
            <ul class="nav nav-pills pull-right">
                <li><a href="#movimentoModal" data-toggle="modal"><i class="icon icon-plus"></i> <span>Novo</span></a></li>
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
            <?php if (isset($msgErro)) : ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <i class="icon icon-cancel-circled"></i> 
                <?php echo $msgErro; ?>
            </div>
            <?php elseif (isset($msgSucesso)) : ?>
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <i class="icon icon-thumbs-up"></i> 
                <?php echo $msgSucesso; ?>
            </div>
            <?php endif; ?>
            <div class="panel panel-default">
                <div class="panel-body">
                    <form method="POST" class="form-horizontal no-validate">
                        <input type="hidden" name="acao" value="movimento-filtrar" />
                        <input type="hidden" name="id_tipo" value="<?php echo $id_tipo; ?>" />
                        <input type="hidden" name="cod_situacao" value="<?php echo $cod_situacao; ?>" />
                        <div class="form-group">
                            <label class="control-label col-md-offset-4 col-md-1">De:</label>
                            <div class="col-md-2">
                                <input type="text" class="form-control dateITA datepicker" name="ini" value="<?php echo date('d/m/Y', $dataIni); ?>" />
                            </div>
                            <label class="control-label col-md-1">Até:</label>
                            <div class="col-md-2">
                                <input type="text" class="form-control dateITA datepicker" name="fim" value="<?php echo date('d/m/Y', $dataFim); ?>" />
                            </div>
                            <div class="col-md-2 text-right">
                                <button class="btn btn-primary" type="submit"><i class="icon icon-filter"></i> Filtrar</button>
                            </div>
                        </div>
                    </form>
                    <hr />
                    <?php //var_dump($movimentos); ?>
                    <table class="table table-striped table-hover table-responsive">
                        <thead>
                            <tr>
                                <th class="text-right"><a href="#">Vencimento</a></th>
                                <th><a href="#">Responsável</a></th>
                                <th><a href="#">Aluno</a></th>
                                <th class="text-right"><a href="#">Crédito</a></th>
                                <th class="text-right"><a href="#">Débito</a></th>
                                <th class="text-right"><a href="#">Saldo</a></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($movimentos) > 0) : ?>
                            <?php foreach ($movimentos as $movimento) : ?>
                            <tr style="<?php echo ($movimento->cod_situacao == MOVIMENTO_SALDO) ? 'font-weight: bold' : ''; ?>">
                                <td style="<?php echo ($movimento->cod_situacao == MOVIMENTO_CANCELADO) ? 'text-decoration: line-through' : ''; ?>" class="text-right"><?php echo $movimento->data_str; ?></td>
                                <td>
                                    <span style="<?php echo ($movimento->cod_situacao == MOVIMENTO_CANCELADO) ? 'text-decoration: line-through' : ''; ?>"><?php echo $movimento->responsavel; ?></span> <?php 
                                    switch ($movimento->cod_situacao) {
                                        case MOVIMENTO_AGENDADO:
                                            echo '<span class="label label-info">Agendado</span>';
                                            break;
                                        case MOVIMENTO_CANCELADO:
                                            echo '<span class="label label-warning">Estornado</span>';
                                            break;
                                    }
                                ?></td>
                                <td>
                                <span style="<?php echo ($movimento->cod_situacao == MOVIMENTO_CANCELADO) ? 'text-decoration: line-through' : ''; ?>"><?php echo $movimento->aluno; ?></span></td>
                                <td style="<?php echo ($movimento->cod_situacao == MOVIMENTO_CANCELADO) ? 'text-decoration: line-through' : ''; ?>" class="text-right text-success"><?php echo $movimento->credito_str; ?></td>
                                <td style="<?php echo ($movimento->cod_situacao == MOVIMENTO_CANCELADO) ? 'text-decoration: line-through' : ''; ?>" class="text-right text-danger"><?php echo $movimento->debito_str; ?></td>
                                <td style="<?php echo ($movimento->cod_situacao == MOVIMENTO_CANCELADO) ? 'text-decoration: line-through' : ''; ?>" class="<?php 
                                if ($movimento->saldo > 0)  
                                    echo 'text-right text-success';
                                elseif ($movimento->saldo < 0) 
                                    echo 'text-right text-danger';
                                else
                                    echo 'text-right';
                                ?>"><?php echo $movimento->saldo_str; ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else : ?>
                            <tr>
                                <th colspan="5" class="text-center"><i class="icon icon-warning"></i> Nenhum movimento na data específicada.</th>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div><!--panel-body-->
            </div><!--panel-default-->
        </div><!--col-md-9-->
        <div class="col-md-3">
            <?php 
                $url  = 'movimentos?ini='.date('Y-m-d', $dataIni).'&fim='.date('Y-m-d', $dataFim);
                if ($id_tipo > 0)
                    $url .= '&tipo='.$id_tipo;
            ?>
            <div class="list-group">
              <a href="<?php echo "$url&situacao=".MOVIMENTO_EM_ABERTO; ?>" class="list-group-item <?php echo ($cod_situacao == MOVIMENTO_EM_ABERTO) ? 'active' : ''; ?>">
                  <i class="icon icon-clock-o"></i> Em aberto
              </a>
              <a href="<?php echo "$url&situacao=".MOVIMENTO_QUITADO; ?>" class="list-group-item <?php echo ($cod_situacao == MOVIMENTO_QUITADO) ? 'active' : ''; ?>">
                  <i class="icon icon-money"></i> Quitado
              </a>
              <a href="<?php echo "$url&situacao=".MOVIMENTO_CANCELADO; ?>" class="list-group-item <?php echo ($cod_situacao == MOVIMENTO_CANCELADO) ? 'active' : ''; ?>">
                  <i class="icon icon-ban"></i> Cancelado
              </a>
            </div>
            <?php 
                $url  = 'movimentos?ini='.date('Y-m-d', $dataIni);
                $url .= '&fim='.date('Y-m-d', $dataFim);
                $url .= '&situacao='.$cod_situacao;
                $tipos = $regraMovimento->listarTipo(); 
             ?>
            <div class="list-group">
                <a href="<?php echo $url; ?>" class="list-group-item <?php echo ($id_tipo == 0) ? 'active' : ''; ?>">
                    Todos os movimentos
                </a>
                <?php foreach ($tipos as $cod_tipo => $tipo) : ?>
                <a href="<?php echo "$url&tipo=$cod_tipo"; ?>" class="list-group-item <?php echo ($cod_tipo == $id_tipo) ? 'active' : ''; ?>">
                    <?php echo $tipo; ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div><!--row-->
</div>
<?php require("footer.inc.php"); ?>