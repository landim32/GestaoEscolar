<?php
$pessoa = $GLOBALS['_pessoa'];
$regraMovimento = new Movimento();
if ($pessoa->tipo == TIPO_ALUNO)
    $movimentos = $regraMovimento->listar(null, $id_pessoa); 
else
    $movimentos = $regraMovimento->listar($id_pessoa); 
?>
<div class="panel panel-default">
    <div class="panel-body">
        <!--pre><?php //var_dump($movimentos); ?></pre-->
        <table class="table table-striped table-hover table-responsive">
            <thead>
                <tr>
                    <th><a href="#">Tipo</a></th>
                    <th class="text-right"><a href="#">Vencimento</a></th>
                    <th class="text-right"><a href="#">Crédito</a></th>
                    <th class="text-right"><a href="#">Débito</a></th>
                    <th><a href="#">Situação</a></th>
                    <th><a href="#">Opções</a></th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($movimentos) > 0) : ?>
                <?php foreach ($movimentos as $movimento) : ?>
                <tr>
                    <td><?php echo $movimento->tipo_str; ?></td>
                    <td class="text-right"><?php echo $movimento->data_vencimento_str; ?></td>
                    <td class="text-right text-success"><?php echo $movimento->credito_str; ?></td>
                    <td class="text-right text-danger"><?php echo $movimento->debito_str; ?></td>
                    <td><?php echo $movimento->situacao; ?></td>
                    <td>
                        <a href="#" class="pagar btn btn-xs btn-primary" data-movimento="<?php echo $movimento->id_movimento;  ?>" data-valor="<?php echo $movimento->valor_str;  ?>"><i class="icon icon-dollar"></i> Pagar</a>
                        <a href="#" class="cancelar btn btn-xs btn-warning" data-movimento="<?php echo $movimento->id_movimento;  ?>"><i class="icon icon-ban"></i> Cancelar</a>
                    </td>
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