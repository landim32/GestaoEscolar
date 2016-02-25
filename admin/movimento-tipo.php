<?php
require('common.inc.php');

$regraMovimento = new Movimento();

try {
    if (array_key_exists('acao', $_POST) && $_POST['acao'] == 'gravar-tipo') {
        if (array_key_exists('id_tipo', $_POST)) {
            $id_tipo = intval($_POST['id_tipo']);
            $regraMovimento->alterarTipo($id_tipo, $_POST['nome']);
            $msgSucesso = _('Successfully changed move type.');
            header('Location: movimento-tipo?success='.urlencode($msgSucesso));
            exit();
        }
        else {
            $regraMovimento->inserirTipo($_POST['nome']);
            $msgSucesso = _('Successfully included move type.');
            header('Location: movimento-tipo?success='.urlencode($msgSucesso));
            exit();
        }
    }
    elseif (array_key_exists('excluir', $_GET)) {
        $id_tipo = $_GET['excluir'];
        $regraMovimento->excluirTipo($id_tipo);
        $msgSucesso = _('Move type deleted successfully.');
        header('Location: movimento-tipo?success='.urlencode($msgSucesso));
        exit();
    }
}
catch (Exception $e) {
    $msgErro = $e->getMessage();
}
$tipos = $regraMovimento->listarTipo();

if (!function_exists('toolbar_breadcrump')) :
function toolbar_breadcrump() {
    echo '<li><a href="movimento-tipo?incluir=1"><i class="icon icon-plus"></i> <span>'._('New type').'</span></a></li>';
}
endif;

require("header.inc.php");

content_start();
breadcrumb_menu(array(
        array(
            'current' => false,
            'icon' => 'icon icon-home',
            'url' => 'index',
            'nome' => _('Home')
        ),
        array(
            'current' => true,
            'icon' => 'icon icon-building',
            'url' => 'movimento-tipo',
            'nome' => _('Financial\'s Move')
        ),
    )
); 
?>
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
<?php elseif (array_key_exists('success', $_GET)) : ?>
<div class="alert alert-success alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <i class="icon icon-thumbs-up"></i> 
    <?php echo $_GET['success']; ?>
</div>
<?php endif; ?>
<div class="panel panel-default">
    <div class="panel-body">
        <form method="POST">
        <input type="hidden" name="acao" value="gravar-tipo" />
        <?php if ($_GET['alterar'] > 0) : ?>
        <input type="hidden" name="id_tipo" value="<?php echo $_GET['alterar']; ?>" />
        <?php endif; ?>
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th><a href="#"><?php echo _('Name'); ?></a></th>
                <th class="text-right"><a href="#"><?php echo _('Options'); ?></a></th>
            </tr>
            </thead>
            <tbody>
            <?php if ($_GET['incluir'] == '1') : ?>
            <tr>
                <td><input class="form-control required" type="text" name="nome" /></td>
                <td class="text-right">
                    <a class="btn btn-default" href="movimento-tipo"><i class="icon icon-remove"></i> <?php echo _('Cancel'); ?></a>
                    <button class="btn btn-primary" type="submit"><i class="icon icon-plus"></i> <?php echo _('Insert'); ?></button>
                </td>
            </tr>
            <?php endif; ?>
            <?php if (count($tipos) > 0) : ?>
            <?php foreach ($tipos as $tipo) : ?>
            <?php if ($_GET['alterar'] == $tipo->id_tipo) : ?>
            <tr>
                <td><input class="form-control" type="text" name="nome" value="<?php echo $tipo->nome; ?>" /></td>
                <td class="text-right">
                    <a class="btn btn-default" href="movimento-tipo"><i class="icon icon-remove"></i> <?php echo _('Cancel'); ?></a>
                    <button class="btn btn-primary" type="submit"><i class="icon icon-pencil"></i> <?php echo _('Update'); ?></button>
                </td>
            </tr>
            <?php else : ?>
            <tr>
                <td><?php echo $tipo->nome; ?> <span class="badge"><?php echo $tipo->quantidade; ?></span></td>
                <td class="text-right">
                    <a class="bs-tooltip" data-placement="top" data-original-title="<?php echo _('Update Type'); ?>" href="movimento-tipo?alterar=<?php echo $tipo->id_tipo; ?>"><i class="icon icon-pencil"></i></a>
                    <a class="bs-tooltip confirm" data-placement="top" data-original-title="<?php echo _('Remove Type'); ?>" href="movimento-tipo?excluir=<?php echo $tipo->id_tipo; ?>"><i class="icon icon-remove"></i></a>
                </td>
            </tr>
            <?php endif; ?>
            <?php endforeach; ?>
            <?php else : ?>
            <tr>
                <td colspan="2" class="text-center"><i class="icon icon-warning"></i> Nenhum movimento financeiro cadastrado!</td>
            </tr>
            <?php endif; ?>
            </tbody>
        </table>
        </form>
    </div><!--panel-body-->
</div><!--panel-default-->
<?php 
content_end();
require("footer.inc.php"); 
?>