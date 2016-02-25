function mascaraMutuario(o,f){
    v_obj=o
    v_fun=f
    setTimeout('execmascara()',1)
}
 
function execmascara(){
    v_obj.value=v_fun(v_obj.value)
}
 
function cpfCnpj(v){
    //Remove tudo o que não é dígito
    v=v.replace(/\D/g,"") 
    if (v.length <= 14) { //CPF
        //Coloca um ponto entre o terceiro e o quarto dígitos
        v=v.replace(/(\d{3})(\d)/,"$1.$2")
        //Coloca um ponto entre o terceiro e o quarto dígitos
        //de novo (para o segundo bloco de números)
        v=v.replace(/(\d{3})(\d)/,"$1.$2")
        //Coloca um hífen entre o terceiro e o quarto dígitos
        v=v.replace(/(\d{3})(\d{1,2})$/,"$1-$2")
    } else { //CNPJ
        //Coloca ponto entre o segundo e o terceiro dígitos
        v=v.replace(/^(\d{2})(\d)/,"$1.$2")
        //Coloca ponto entre o quinto e o sexto dígitos
        v=v.replace(/^(\d{2})\.(\d{3})(\d)/,"$1.$2.$3")
        //Coloca uma barra entre o oitavo e o nono dígitos
        v=v.replace(/\.(\d{3})(\d)/,".$1/$2")
        //Coloca um hífen depois do bloco de quatro dígitos
        v=v.replace(/(\d{4})(\d)/,"$1-$2")
    }
    return v
 
}
$(document).ready(function(){
    $('input[type=text].money').maskMoney({allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
    $('input[type=text].cpf,input[type=text].cnpj').bind('keypress', function (e) {
        mascaraMutuario(this, cpfCnpj);
    });
    $('a.movimento').bind('click', function (e) {
        var attr = $(this).attr('data-pessoa');
        if (typeof attr !== typeof undefined && attr !== false) {
            $('#movimento_pessoa').val($(this).attr('data-pessoa'));
            $('#nome_pessoa').val($(this).attr('data-pessoa-nome'));
            $('#movimento_aluno').val('0');
            $('#nome_aluno').val('');
            $('#responsavel-div').show();
            $('#aluno-div').hide();
        }
        
        var attr = $(this).attr('data-aluno');
        if (typeof attr !== typeof undefined && attr !== false) {
            $('#movimento_pessoa').val('0');
            $('#nome_pessoa').val('');
            $('#movimento_aluno').val($(this).attr('data-aluno'));
            $('#nome_aluno').val($(this).attr('data-aluno-nome'));
            $('#responsavel-div').hide();
            $('#aluno-div').show();
        }
        $('#movimentoNovoModal').modal('show');
        return false;
    });
    $('a.pagar').bind('click', function (e) {
        var attr = $(this).attr('data-movimento');
        if (typeof attr !== typeof undefined && attr !== false) {
            $('#movimento_pagar').val($(this).attr('data-movimento'));
        }
        var attr = $(this).attr('data-valor');
        if (typeof attr !== typeof undefined && attr !== false) {
            $('#pagamento_apagar').val($(this).attr('data-valor'));
            $('#pagamento_valor').val($(this).attr('data-valor'));
        }
        $('#movimentoPagoModal').modal('show');
        return false;
    });
    $('a.cancelar').bind('click', function (e) {
        var attr = $(this).attr('data-movimento');
        if (typeof attr !== typeof undefined && attr !== false) {
            $('#movimento_cancelar').val($(this).attr('data-movimento'));
        }
        $('#movimentoCanceladoModal').modal('show');
        return false;
    });
    //<a href="#" class="movimento" data-pessoa="0" data-pessoa-nome="Rodrigo" data-aluno="0" data-aluno-nome="Hiram">asas</a>
});