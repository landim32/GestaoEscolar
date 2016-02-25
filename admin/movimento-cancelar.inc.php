<?php $regraMovimento = new Movimento(); ?>
<div class="modal modal fade" id="movimentoCanceladoModal">
    <div class="modal-dialog">
        <form method="POST" class="form-horizontal">
        <input type="hidden" name="acao" value="movimento-cancelar" />
        <input type="hidden" id="movimento_cancelar" name="id_movimento" value="0" />
        <div class="modal-content form-horizontal">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="icon icon-dollar"></i> Cancelar</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">Observação:</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="observacao" value="" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-success">Cancelar</button>
            </div>
        </div>
        </form>
    </div><!-- /.modal-dialog -->
</div>