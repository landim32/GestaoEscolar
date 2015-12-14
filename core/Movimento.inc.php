<?php

/*
id_tipo int not null auto_increment,
id_especial tiny int not null,
id_conta int not null,
nome varchar(35) not null,
cod_situacao int not null default 1,
 */

/*
id_movimento int not null auto_increment,
id_conta int not null,
id_tipo int not null,
id_debito int,
id_cliente int,
id_usuario int,
tipo char not null default 'c',
data_previsao datetime,
data_movimento datetime not null,
valor double not null,
cod_situacao int not null default 1,
descricao varchar(50)
 */

define('MOVIMENTO_SALDO', 0);
define('MOVIMENTO_EXECUTADO', 1);
define('MOVIMENTO_AGENDADO', 2);
define('MOVIMENTO_EXTORNADO', 3);

define('MOVIMENTO_TIPO_REPASSE', 1);
define('MOVIMENTO_TIPO_COMISSAO_VENDA', 2);
define('MOVIMENTO_TIPO_TAXA_ADMIN', 3);
define('MOVIMENTO_TIPO_ALUGUEL', 4);

class Movimento {
    
    public function listarTipo() {
        $query = "
            SELECT 
                movimento_tipo.id_tipo,
                movimento_tipo.nome,
                COUNT(movimento.id_movimento) AS 'quantidade'
            FROM movimento_tipo
            LEFT JOIN movimento ON (
                movimento.id_tipo = movimento_tipo.id_tipo
                AND movimento.id_conta = '".  do_escape(ID_CONTA)."'
                AND movimento.cod_situacao = 1
            )
            WHERE movimento_tipo.id_conta = '".  do_escape(ID_CONTA)."'
            AND movimento_tipo.cod_situacao = 1
            GROUP BY 
                movimento_tipo.id_tipo,
                movimento_tipo.nome
            ORDER BY 
                movimento_tipo.nome
        ";
        return get_result($query);
    }
    
    public function inserirTipo($nome, $tipo = 0) {
        $query = "
            INSERT INTO movimento_tipo (
                tipo,
                id_conta,
                nome,
                cod_situacao
            ) VALUES (
                '".do_escape($tipo)."',
                '".do_escape(ID_CONTA)."',
                '".do_escape($nome)."',
                1
            )
        ";
        return do_insert($query);
    }
    
    public function alterarTipo($id_tipo, $nome) {
        $query = "
            UPDATE movimento_tipo SET
                nome = '".do_escape($nome)."'
            WHERE id_tipo = '".do_escape($id_tipo)."'
        ";
        do_update($query);
    }
    
    public function excluirTipo($id_tipo) {
        $query = "
            SELECT
                COUNT(*) AS 'quantidade'
            FROM movimento
            WHERE id_conta = '".do_escape(ID_CONTA)."'
            AND id_tipo = '".do_escape($id_tipo)."'
            AND cod_situacao = '".do_escape(MOVIMENTO_EXECUTADO)."'
        ";
        $quantidade = get_value($query, 'quantidade');
        if ($quantidade > 0)
            throw new Exception(sprintf('Existem %s movimento(s) ativo(s) com esse tipo.', $quantidade));
        $query = "
            UPDATE movimento_tipo SET
                cod_situacao = 0
            WHERE id_tipo = '".do_escape($id_tipo)."'
        ";
        do_delete($query);
    }
    
    private function configuracaoAutomatica() {
        $this->pegarIdRepasse();
        $this->pegarIdComissaoVenda();
        $this->pegarIdTaxaAdmin();
    }
    

    public function pegarIdRepasse() {
        $query = "
            SELECT id_tipo
            FROM movimento_tipo
            WHERE id_conta = '".do_escape(ID_CONTA)."'
            AND tipo = '".do_escape(MOVIMENTO_TIPO_REPASSE)."'
        ";
        $id_repasse = get_value($query, 'id_tipo');
        if (!($id_repasse > 0))
            $id_repasse = $this->inserirTipo('Repasse', MOVIMENTO_TIPO_REPASSE);
        return $id_repasse;
    }

    public function pegarIdComissaoVenda() {
        $query = "
            SELECT id_tipo
            FROM movimento_tipo
            WHERE id_conta = '".do_escape(ID_CONTA)."'
            AND tipo = '".do_escape(MOVIMENTO_TIPO_COMISSAO_VENDA)."'
        ";
        $id_comissao = get_value($query, 'id_tipo');
        if (!($id_comissao > 0))
            $id_comissao = $this->inserirTipo('Comissão de venda', MOVIMENTO_TIPO_COMISSAO_VENDA);
        return $id_comissao;
    }
    
    public function pegarIdAluguel() {
        $query = "
            SELECT id_tipo
            FROM movimento_tipo
            WHERE id_conta = '".do_escape(ID_CONTA)."'
            AND tipo = '".do_escape(MOVIMENTO_TIPO_ALUGUEL)."'
        ";
        $id_comissao = get_value($query, 'id_tipo');
        if (!($id_comissao > 0))
            $id_comissao = $this->inserirTipo('Aluguel', MOVIMENTO_TIPO_ALUGUEL);
        return $id_comissao;
    }
    
    public function pegarIdTaxaAdmin() {
        $query = "
            SELECT id_tipo
            FROM movimento_tipo
            WHERE id_conta = '".do_escape(ID_CONTA)."'
            AND tipo = '".do_escape(MOVIMENTO_TIPO_TAXA_ADMIN)."'
        ";
        $id_comissao = get_value($query, 'id_tipo');
        if (!($id_comissao > 0))
            $id_comissao = $this->inserirTipo('Taxa de Administração', MOVIMENTO_TIPO_TAXA_ADMIN);
        return $id_comissao;
    }
    
    private function query() {
        $query = "
            SELECT
                id_movimento,
                id_conta,
                id_tipo,
                id_negocio,
                id_debito,
                id_cliente,
                id_usuario,
                data_previsao,
                data_movimento,
                valor,
                cod_situacao,
                descricao
            FROM movimento
        ";
        return $query;
    }

    public function saldoAnterior($cod_situacao = null, $id_tipo = null, $data = null) {
        $query = "
            SELECT 
                SUM(movimento.valor) AS 'total'
            FROM movimento
            INNER JOIN movimento_tipo ON movimento_tipo.id_tipo = movimento.id_tipo
            WHERE movimento.id_conta = '".do_escape(ID_CONTA)."'
        ";
        if ($cod_situacao > 0)
            $query .= " AND movimento.cod_situacao = '".do_escape($cod_situacao)."'";
        if ($id_tipo > 0)
            $query .= " AND movimento.id_tipo = ".$id_tipo;
        if (!is_null($data)) {
            //$dataSaldo = strtotime($data) - (24 * 60 * 60);
            //$dataSaldo = $data - (24 * 60 * 60);
            $query .= " AND movimento.data_movimento < '".date('Y-m-d', $data)." 23:59:59'";
        }
        //echo $query;
        return get_value($query, 'total');
    }
    
    public function extrato($cod_situacao = null, $id_tipo = null, $dataIni = null, $dataFim = null) {
        $query = "
            SELECT 
                movimento.id_movimento,
                movimento.data_movimento,
                CASE
                    WHEN movimento.id_usuario IS NOT NULL THEN
                        (SELECT nome FROM usuario WHERE id_usuario = movimento.id_usuario)
                    WHEN movimento.id_cliente IS NOT NULL THEN
                        (SELECT nome FROM cliente WHERE id_cliente = movimento.id_cliente)
                END as 'nome',
                CASE 
                    WHEN valor >= 0 THEN
                        valor
                    ELSE
                        0
                END AS 'credito',
                CASE 
                    WHEN valor < 0 THEN
                        -(valor)
                    ELSE
                        0
                END AS 'debito',
                movimento_tipo.nome as 'tipo',
                movimento.cod_situacao,
                movimento.descricao
            FROM movimento
            INNER JOIN movimento_tipo ON movimento_tipo.id_tipo = movimento.id_tipo
            WHERE movimento.id_conta = '".do_escape(ID_CONTA)."'
        ";
        
        if ($cod_situacao == MOVIMENTO_EXTORNADO) {
            $situacoes = array(MOVIMENTO_EXECUTADO, MOVIMENTO_EXTORNADO);
            $query .= " AND movimento.cod_situacao IN (".implode(', ', $situacoes).")";
        }
        elseif ($cod_situacao == MOVIMENTO_EXECUTADO)
            $query .= " AND movimento.cod_situacao = ".MOVIMENTO_EXECUTADO;
        elseif ($cod_situacao == MOVIMENTO_AGENDADO)
            $query .= " AND movimento.cod_situacao = ".MOVIMENTO_AGENDADO;
        
        if ($id_tipo > 0)
            $query .= " AND movimento.id_tipo = ".$id_tipo;
        
        if ($dataIni > 0 && $dataFim > 0)
            $query .= " AND movimento.data_movimento BETWEEN '".date('Y-m-d', $dataIni)." 00:00:00' AND '".date('Y-m-d', $dataFim)." 23:59:59'";
        
        $query .= " ORDER BY movimento.data_movimento ";
        //echo $query;
        $retorno = array();
        $movimentos = get_result($query);
        if (count($movimentos) > 0) {
            
            reset($movimentos);
            list($key, $movimento) = each($movimentos);
            reset($movimentos);
            $dataAtual = date('Y-m-d', strtotime($movimento->data_movimento));
            $retorno = array();
            $dataSaldo = strtotime($movimento->data_movimento) - (24 * 60 * 60);
            $dataSaldo = strtotime(date('Y-m-d', $dataSaldo).' 23:59:59');
            //var_dump($movimento, $dataSaldo);
            $saldo = $this->saldoAnterior($cod_situacao, $id_tipo, $dataSaldo);
            $movimento = new stdClass();
            $movimento->id_movimento = 0;
            $movimento->data_movimento = date('Y-m-d', $dataSaldo);
            $movimento->data_str = date('d/m/Y', strtotime($movimento->data_movimento));
            $movimento->cod_situacao = 0;
            $movimento->texto = "Saldo Anterior";
            $movimento->saldo = $saldo;
            $movimento->saldo_str = number_format($movimento->saldo, 2, ',', '.');
            $retorno[] = $movimento;
            foreach ($movimentos as $movimento) {
                
                $movimento->data_str = date('d/m/Y', strtotime($movimento->data_movimento));
                if ($movimento->cod_situacao != MOVIMENTO_EXTORNADO) {
                    $saldo += $movimento->credito;
                    $saldo -= $movimento->debito;
                }
                $texto = array($movimento->tipo);
                if (!isNullOrEmpty($movimento->descricao))
                    $texto[] = $movimento->descricao;
                if (!isNullOrEmpty($movimento->nome))
                    $texto[] = $movimento->nome;
                $movimento->texto = implode(', ', $texto);
                if ($movimento->credito > 0)
                    $movimento->credito_str = number_format($movimento->credito, 2, ',', '.');
                else
                    $movimento->credito_str = '';
                if ($movimento->debito > 0)
                    $movimento->debito_str = '-'.number_format($movimento->debito, 2, ',', '.');
                else
                    $movimento->debito_str = '';
                $movimento->saldo = $saldo;
                //var_dump($movimento->saldo);
                $movimento->saldo_str = number_format($movimento->saldo, 2, ',', '.');
                $dataMovimento = date('Y-m-d', strtotime($movimento->data_movimento));
                if ($dataMovimento != $dataAtual) {
                    $saldoParcial = new stdClass();
                    $saldoParcial->id_movimento = 0;
                    $saldoParcial->data_movimento = $dataAtual;
                    $saldoParcial->data_str = date('d/m/Y', strtotime($saldoParcial->data_movimento));
                    $saldoParcial->cod_situacao = 0;
                    $saldoParcial->texto = "Saldo Parcial";
                    $saldoParcial->saldo = $saldo;
                    $saldoParcial->saldo_str = number_format($saldoParcial->saldo, 2, ',', '.');
                    $dataAtual = $dataMovimento;
                    $retorno[] = $saldoParcial;
                }
                $retorno[] = $movimento;
            }
            $movimento = new stdClass();
            $movimento->id_movimento = 0;
            $movimento->data_movimento = date('Y-m-d', time());
            $movimento->data_str = date('d/m/Y', strtotime($movimento->data_movimento));
            $movimento->cod_situacao = 0;
            $movimento->texto = "Saldo Atual";
            $movimento->saldo = $saldo;
            $movimento->saldo_str = number_format($movimento->saldo, 2, ',', '.');
            $retorno[] = $movimento;
        }
        return $retorno;
    }
    
    public function listar($cod_situacao = null) {
        if (is_null($cod_situacao))
            $cod_situacao = MOVIMENTO_EXECUTADO;
        $query = $this->query()."
            AND movimento.cod_situacao = '".do_escape($cod_situacao)."'
        ";
        $query .= " ORDER BY movimento.data_movimento";
        return get_result($query);
    }
    
    public function pegar($id_movimento) {
        $query = $this->query()."
            WHERE id_movimento = '".do_escape($id_movimento)."'
        ";
        return get_first_result($query);
    }
    
    public function pegarDoPost($movimento = null) {
        if (is_null($movimento))
            $movimento = new stdClass();
        if (array_key_exists('id_movimento', $_POST))
            $movimento->id_movimento = $_POST['id_movimento'];
        if (array_key_exists('id_tipo', $_POST))
            $movimento->id_tipo = $_POST['id_tipo'];
        if (array_key_exists('id_debito', $_POST))
            $movimento->id_debito = $_POST['id_debito'];
        if (array_key_exists('id_cliente', $_POST))
            $movimento->id_cliente = $_POST['id_cliente'];
        if (array_key_exists('id_usuario', $_POST))
            $movimento->id_usuario = $_POST['id_usuario'];
        if (array_key_exists('data_previsao', $_POST))
            $movimento->data_previsao = dateToSql($_POST['data_previsao']);
        if (array_key_exists('data_movimento', $_POST))
            $movimento->data_movimento = dateToSql($_POST['data_movimento']);
        if (array_key_exists('valor', $_POST)) {
            $movimento->valor = abs(numberToSql($_POST['valor']));
            if (array_key_exists('tipo', $_POST) && $_POST['tipo'] == 'd')
                $movimento->valor = -$movimento->valor;
        }
        if (array_key_exists('cod_situacao', $_POST))
            $movimento->cod_situacao = $_POST['cod_situacao'];
        if (array_key_exists('descricao', $_POST))
            $movimento->descricao = $_POST['descricao'];
        return $movimento;
    }
    
    private function validar($movimento) {
        if (is_null($movimento))
            throw new Exception(_('Financial move is null.'));
        if (!($movimento->id_tipo > 0))
            throw new Exception(_('Type is empty.'));
        if ($movimento->valor == 0)
            throw new Exception(_('Value is empty.'));
        if (is_null($movimento->data_movimento)) {
            if (is_null($movimento->data_previsao)) 
                throw new Exception('Data está vazia.');
            $movimento->data_movimento = $movimento->data_previsao;
        }
        if (!($movimento->cod_situacao > 0))
            $movimento->cod_situacao = MOVIMENTO_EXECUTADO;
        return $movimento;
    }
    
    public function inserir($movimento = null) {
        if (is_null($movimento))
            $movimento = $this->pegarDoPost();
        $movimento = $this->validar($movimento);
        $query = "
            INSERT INTO movimento (
                id_conta,
                id_tipo,
                id_negocio,
                id_debito,
                id_cliente,
                id_usuario,
                data_previsao,
                data_movimento,
                valor,
                cod_situacao,
                descricao
            ) VALUES (
                '".do_escape(ID_CONTA)."',
                '".do_escape($movimento->id_tipo)."',
                ".do_escape_full($movimento->id_negocio).",
                ".do_escape_full($movimento->id_debito).",
                ".do_escape_full($movimento->id_cliente).",
                ".do_escape_full($movimento->id_usuario).",
                ".do_escape_date($movimento->data_previsao).",
                ".do_escape_date($movimento->data_movimento).",
                '".do_escape_number($movimento->valor)."',
                '".do_escape($movimento->cod_situacao)."',
                ".do_escape_full($movimento->descricao)."
            )
        ";
        //echo $query;
        //exit();
        return do_insert($query);
    }
    
    public function alterar($movimento = null) {
        if (is_null($movimento))
            $movimento = $this->pegarDoPost();
        $movimento = $this->validar($movimento);
        $query = "
            UPDATE movimento SET
                id_tipo = '".do_escape($movimento->id_tipo)."',
                id_negocio = ".do_escape_full($movimento->id_tipo).",
                id_debito = ".do_escape_full($movimento->id_debito).",
                id_cliente = ".do_escape_full($movimento->id_cliente).",
                id_usuario = ".do_escape_full($movimento->id_usuario).",
                data_previsao = ".do_escape_date($movimento->data_previsao).",
                data_movimento = ".do_escape_date($movimento->data_movimento).",
                valor = '".do_escape($movimento->valor)."',
                cod_situacao = '".do_escape($movimento->cod_situacao)."',
                descricao = '".do_escape($movimento->descricao)."'
            WHERE id_movimento = '".do_escape($movimento->id_movimento)."'
        ";
        do_update($query);        
    }
    
    public function excluir($id_movimento) {
        $query = "
            UPDATE movimento SET
                cod_situacao = '".do_escape(MOVIMENTO_EXTORNADO)."'
            WHERE id_movimento = '".do_escape($id_movimento)."'
        ";
        do_delete($query);
    }
    
    public function listarPorFatura($id_fatura) {
        $idRepasse = $this->pegarIdRepasse();
        $query = "
            SELECT
                movimento.id_movimento,
                cliente.id_cliente,
                cliente.nome,
                cliente.cpf_cnpj,
                cliente.banco,
                cliente.agencia,
                cliente.conta,
                movimento_tipo.nome AS 'tipo',
                movimento.data_previsao AS 'data',
                (-movimento.valor) AS 'valor',
                movimento.cod_situacao
            FROM movimento
            INNER JOIN movimento_tipo ON movimento_tipo.id_tipo = movimento.id_tipo
            INNER JOIN debito ON debito.id_debito = movimento.id_debito
            INNER JOIN cliente ON cliente.id_cliente = movimento.id_cliente
            WHERE debito.id_fatura = '".do_escape($id_fatura)."'
            AND movimento.id_tipo = '".do_escape($idRepasse)."'
            AND movimento.cod_situacao = '".do_escape(MOVIMENTO_AGENDADO)."'
            ORDER BY movimento.data_previsao
        ";
        $result = get_result_db($query);
        $movimentos = array();
        while ($movimento = get_object($result)) {
            $movimento->data_str = date('d/m/Y', strtotime($movimento->data));
            $movimento->valor_str = number_format($movimento->valor, 2, ',', '.');
            $movimentos[] = $movimento;
        }
        free_result($result);
        return $movimentos;
    }
    
}
