<?php $regraMovimento = new Movimento(); ?>
<div class="modal modal fade" id="movimentoPagoModal">
    <div class="modal-dialog">
        <form method="POST" class="form-horizontal">
        <input type="hidden" name="acao" value="movimento-pagar" />
        <input type="hidden" id="movimento_pagar" name="id_movimento" value="0" />
        <div class="modal-content form-horizontal">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="icon icon-dollar"></i> Pagar</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">Valor:</label>
                    <div class="col-md-3">
                        <input type="text" id="pagamento_apagar" class="form-control money required" name="valor" value="0,00" disabled="disabled" />
                    </div>
                    <label class="col-md-3 control-label">Valor Pago:<span class="required">*</span></label>
                    <div class="col-md-3">
                        <input type="text" id="pagamento_valor" class="form-control money required" name="valor_pago" value="0,00" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Observação:</label>
                    <div class="col-md-9">
                        <input type="text" id="pagamento_observacao" class="form-control" name="observacao" value="" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success">Pagar</button>
            </div>
        </div>
        </form>
    </div><!-- /.modal-dialog -->
</div>