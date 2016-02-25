<?php $regraMovimento = new Movimento(); ?>
<div class="modal modal fade" id="movimentoNovoModal">
    <div class="modal-dialog">
        <form method="POST" class="form-horizontal">
        <input type="hidden" name="acao" value="movimento-novo" />
        <input type="hidden" id="movimento_pessoa" name="id_pessoa" value="0" />
        <input type="hidden" id="movimento_aluno" name="id_aluno" value="0" />
        <div class="modal-content form-horizontal">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="icon icon-dollar"></i> Novo movimento</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-5">
                        <div class="btn-group" data-toggle="buttons">
                            <label class="btn active">
                                <input type="radio" name="cod_situacao" value="<?php echo MOVIMENTO_EM_ABERTO; ?>" checked="checked"><i class="icon icon-dollar"></i> Em aberto
                            </label>
                            <label class="btn">
                                <input type="radio" name="cod_situacao" value="<?php echo MOVIMENTO_QUITADO; ?>"><i class="icon icon-clock-o"></i> Quitado
                            </label>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="btn-group" data-toggle="buttons">
                            <label class="btn active">
                                <input type="radio" name="tipo" value="c" checked="checked"><i class="icon icon-plus"></i> À receber
                            </label>
                            <label class="btn">
                                <input type="radio" name="tipo" value="d"><i class="icon icon-minus"></i> À pagar
                            </label>
                        </div>
                    </div>
                </div>
                <div id="responsavel-div" class="form-group">
                    <label class="col-md-3 control-label">Responsável:<span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="nome_pessoa" name="nome_pessoa" value="" disabled="disabled" />
                    </div>
                </div>
                <div id="aluno-div" class="form-group">
                    <label class="col-md-3 control-label">Aluno:<span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="nome_aluno" name="nome_aluno" value="" disabled="disabled" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Tipo:<span class="required">*</span></label>
                    <div class="col-md-9">
                        <?php //echo $regraMovimento->DropDownListTipo(0, 'debito_id_tipo'); ?>
                        <select class="form-control required" name="cod_tipo">
                            <?php $tipos = $regraMovimento->listarTipo(); ?>
                            <?php foreach ($tipos as $cod_tipo => $nome_tipo) : ?>
                            <option value="<?php echo $cod_tipo; ?>"><?php echo $nome_tipo; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Valor:<span class="required">*</span></label>
                    <div class="col-md-3">
                        <input type="text" class="form-control money required" name="valor" value="0,00" />
                    </div>
                    <label class="col-md-3 control-label">Data:<span class="required">*</span></label>
                    <div class="col-md-3">
                        <input type="text" class="form-control dateITA datepicker required" name="data_vencimento" value="<?php echo date('d/m/Y'); ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Observação:</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="observacao" value="" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success">Salvar</button>
            </div>
        </div>
        </form>
    </div><!-- /.modal-dialog -->
</div>