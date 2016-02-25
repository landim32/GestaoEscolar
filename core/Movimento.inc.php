<?php
/*
create table movimento (
id_movimento int not null auto_increment,
id_escola int not null,
id_pessoa int not null,
cod_tipo int not null,
id_aluno int,
tipo char(1) not null default 'c',
data_inclusao datetime not null,
ultima_alteracao datetime not null,
data_vencimento datetime not null,
credito double,
debito double,
cod_situacao tinyint not null default 1,
primary key(id_movimento),
foreign key (id_escola) REFERENCES escola(id_escola),
foreign key (id_pessoa) REFERENCES pessoa(id_pessoa),
foreign key (id_aluno) REFERENCES pessoa(id_pessoa)
);
 */

define('CREDITO', 'c');
define('DEBITO', 'd');

define('MOVIMENTO_MENSALIDADE', 1);

define('MOVIMENTO_EM_ABERTO', 1);
define('MOVIMENTO_QUITADO', 2);
define('MOVIMENTO_CANCELADO', 3);

class Movimento {
    
    public function listarTipo() {
        return array(
            MOVIMENTO_MENSALIDADE => 'Mensalidade'
        );
    }
    
    public function listarSituacao() {
        return array(
            MOVIMENTO_EM_ABERTO => 'Em aberto',
            MOVIMENTO_QUITADO => 'Quitado',
            MOVIMENTO_CANCELADO => 'Cancelado'
        );
    }

    private function query() {
        $query = "
            SELECT
                id_movimento,
                id_escola,
                id_pessoa,
                id_aluno,
                cod_tipo,                
                tipo,
                data_inclusao,
                ultima_alteracao,
                data_vencimento,
                data_pagamento,
                credito,
                debito,
                cod_situacao,
                observacao
            FROM movimento
        ";
        return $query;
    }
    
    private function atualizar($movimento) {
        if (!is_null($movimento)) {
            $tipos = $this->listarTipo();
            $situacoes = $this->listarSituacao();
            //$movimento->tipo_str = $movimento->data_vencimento;
            $movimento->data_vencimento_str = date('d/m/Y', strtotime($movimento->data_vencimento));
            $movimento->data_pagamento_str = date('d/m/Y', strtotime($movimento->data_pagamento));
            $movimento->credito_str = number_format($movimento->credito, 2, ',', '.');
            $movimento->debito_str = number_format($movimento->debito, 2, ',', '.');
            $movimento->tipo_str = $tipos[$movimento->cod_tipo];
            $movimento->situacao = $situacoes[$movimento->cod_situacao];
            if ($movimento->tipo == CREDITO)
                $movimento->valor = $movimento->credito;
            else
                $movimento->valor = $movimento->debito;
            $movimento->valor_str = number_format($movimento->valor, 2, ',', '.');
        }
        return $movimento;
    }
    
    public function listar($id_pessoa = null, $id_aluno = null, $cod_tipo = null, $cod_situacao = null) {
        $query = $this->query();
        if (is_null($cod_situacao))
            $cod_situacao = MOVIMENTO_EM_ABERTO;
        $query = $this->query()."
            WHERE movimento.id_escola = '".do_escape(ID_ESCOLA)."'
        ";
        if (!is_null($id_pessoa))
            $query .= " AND movimento.id_pessoa = '".do_escape($id_pessoa)."' ";
        if (!is_null($id_aluno))
            $query .= " AND movimento.id_aluno = '".do_escape($id_aluno)."' ";
        if (!is_null($id_tipo))
            $query .= " AND movimento.id_tipo = '".do_escape($id_tipo)."' ";
        if (!is_null($cod_situacao))
            $query .= " AND movimento.cod_situacao = '".do_escape($cod_situacao)."' ";
        $query .= " ORDER BY movimento.data_vencimento";
        $retorno = array();
        foreach (get_result($query) as $movimento)
            $retorno[] = $this->atualizar($movimento);
        return $retorno;
    }
    
    public function listarCarne($id_pessoa = null, $mes = null, $ano = null) {
        $query = "
            SELECT
                movimento.id_pessoa AS 'id_responsavel',
                responsavel.nome AS 'responsavel_nome',
                responsavel.cpf_cnpj,
                movimento.id_aluno AS 'id_aluno',
                aluno.nome AS 'aluno_nome',
                movimento.cod_tipo,
                movimento.data_vencimento,
                movimento.tipo,
                movimento.credito,
                movimento.debito,
                CONCAT(curso.nome, ' ', turma.nome) AS 'turma'
            FROM movimento
            INNER JOIN pessoa AS responsavel ON responsavel.id_pessoa = movimento.id_pessoa
            LEFT JOIN pessoa AS aluno ON aluno.id_pessoa = movimento.id_aluno
            LEFT JOIN turma ON turma.id_turma = aluno.id_turma
            LEFT JOIN curso ON curso.id_curso = turma.id_curso
            WHERE movimento.id_escola = ".do_escape(ID_ESCOLA)."
            AND movimento.cod_situacao = ".do_escape(MOVIMENTO_EM_ABERTO)."
               
        ";
        if (!is_null($id_pessoa))
            $query .= " AND movimento.id_pessoa = ".do_escape($id_pessoa);
        if (!is_null($mes))
            $query .= " AND MONTH(movimento.data_vencimento) = ".do_escape($mes);
        if (!is_null($ano))
            $query .= " AND YEAR(movimento.data_vencimento) = ".do_escape($ano);
        $query .= " 
            ORDER BY 
                movimento.id_pessoa,
                movimento.data_vencimento
        ";
        $movimentos = array();
        foreach (get_result($query) as $dados) {
            if (array_key_exists($dados->id_pessoa, $movimentos)) {
                $pessoa = $movimentos[$dados->id_pessoa];
            }
            else {
                $pessoa = new stdClass();
                $pessoa->id_pessoa = $dados->id_pessoa;
                $pessoa->nome = $dados->responsavel_nome;
                $pessoa->cpf_cnpj = $dados->cpf_cnpj;
                $pessoa->data_vencimento = $dados->data_vencimento;
                $pessoa->valor_total = 0;
                $pessoa->movimentos = array();
                $movimentos[] = $pessoa;
            }
            $movimento = new stdClass();
            $movimento->id_aluno = $dados->id_aluno;
            $movimento->nome = $dados->aluno_nome;
            $movimento->turma = $dados->turma;
            $movimento->cod_tipo = $dados->cod_tipo;
            $movimento->data_vencimento = $dados->data_vencimento;
            $movimento->tipo = $dados->tipo;
            $movimento->credito = $dados->credito;
            $movimento->debito = $dados->debito;
            $pessoa->valor_total += $movimento->credito;
            $pessoa->valor_total -= $movimento->debito;
            
            $pessoa->movimentos[] = $movimento;
        }
        return $movimentos;
    }
    
    public function pegar($id_movimento) {
        $query = $this->query()."
            WHERE movimento.id_movimento = '".do_escape($id_movimento)."'
        ";
        return $this->atualizar(get_first_result($query));
    }
    
    public function pegarDoPost($movimento = null) {
        if (is_null($movimento))
            $movimento = new stdClass();
        if (array_key_exists('id_pessoa', $_POST))
            $movimento->id_pessoa = $_POST['id_pessoa'];
        if (array_key_exists('cod_tipo', $_POST))
            $movimento->cod_tipo = $_POST['cod_tipo'];
        if (array_key_exists('id_aluno', $_POST))
            $movimento->id_aluno = $_POST['id_aluno'];
        if (array_key_exists('tipo', $_POST))
            $movimento->tipo = $_POST['tipo'];
        if (array_key_exists('data_vencimento', $_POST))
            $movimento->data_vencimento = dateToSql($_POST['data_vencimento']);
        if (array_key_exists('valor', $_POST)) {
            if ($movimento->tipo == CREDITO) {
                $movimento->credito = $_POST['valor'];
                $movimento->debito = 0;
            }
            elseif ($movimento->tipo == DEBITO) {
                $movimento->credito = 0;
                $movimento->debito = $_POST['valor'];
            }
        }
        if (array_key_exists('credito', $_POST))
            $movimento->credito = $_POST['credito'];
        if (array_key_exists('debito', $_POST))
            $movimento->debito = $_POST['debito'];
        if (array_key_exists('cod_situacao', $_POST))
            $movimento->cod_situacao = $_POST['cod_situacao'];
        if (array_key_exists('observacao', $_POST))
            $movimento->observacao = $_POST['observacao'];
        return $movimento;
    }

    private function validar($movimento) {
        if (!is_object($movimento))
            throw new Exception('Objeto inválido!');
        if (!in_array($movimento->tipo, array(CREDITO, DEBITO)))
            throw new Exception(sprintf('Tipo "%s" inválido!', $movimento->tipo));
        
        $tipos = array_keys($this->listarTipo());
        if (!in_array($movimento->cod_tipo, $tipos))
            throw new Exception(sprintf('Tipo "%s" inválida!', $movimento->cod_tipo));
        
        $situacoes = array_keys($this->listarSituacao());
        if (!in_array($movimento->cod_situacao, $situacoes))
            throw new Exception(sprintf('Situação "%s" inválida!', $movimento->cod_situacao));
        if ($movimento->tipo === CREDITO) {
            if (!($movimento->credito > 0))
                throw new Exception('Crédito não pode ser menor que 0.');
            $movimento->debito = 0;
        }
        if ($movimento->tipo === DEBITO) {
            if (!($movimento->debito > 0))
                throw new Exception('Débito não pode ser menor que 0.');
            $movimento->credito = 0;
        }
        return $movimento;
    }
    
    public function inserir($movimento) {
        $movimento = $this->validar($movimento);
        
        if (!($movimento->id_pessoa > 0)) {
            $query = "
                SELECT id_responsavel
                FROM aluno_responsavel
                WHERE id_aluno = '".do_escape($movimento->id_aluno)."'
            ";
            $movimento->id_pessoa = get_value($query, 'id_responsavel');
        }
        if (!($movimento->id_aluno > 0))
            $movimento->id_aluno = null;
        
        $query = " 
            INSERT INTO movimento (
                id_escola,
                id_pessoa,
                id_aluno,
                cod_tipo,
                tipo,
                data_inclusao,
                ultima_alteracao,
                data_vencimento,
                data_pagamento,
                credito,
                debito,
                cod_situacao,
                observacao
            ) VALUES (
                '".do_escape(ID_ESCOLA)."',
                '".do_escape($movimento->id_pessoa)."',
                ".do_escape_full($movimento->id_aluno).",
                '".do_escape($movimento->cod_tipo)."',
                '".do_escape($movimento->tipo)."',
                NOW(),
                NOW(),
                '".do_escape(date('Y-m-d 00:00:00', strtotime($movimento->data_vencimento)))."',
                NULL,    
                '".do_escape(number_format($movimento->credito, 2, '.', ''))."',
                '".do_escape(number_format($movimento->debito, 2, '.', ''))."',
                '".do_escape($movimento->cod_situacao)."',
                '".do_escape($movimento->observacao)."'
            )
        ";
        //var_dump($query);
        //exit();
        return do_insert($query);
    }
    
    public function alterar($movimento) {
        $movimento = $this->validar($movimento);
        $query = " 
            UPDATE movimento  SET
                cod_tipo = '".do_escape($movimento->cod_tipo)."',
                tipo = '".do_escape($movimento->tipo)."',
                ultima_alteracao = NOW(),
                data_vencimento = '".do_escape(date('Y-m-d 00:00:00', strtotime($movimento->data_vencimento)))."',
                data_pagamento = NULL,
                credito = '".do_escape(number_format($movimento->credito, 2, '.', ''))."',
                debito = '".do_escape(number_format($movimento->debito, 2, '.', ''))."',
                cod_situacao = '".do_escape($movimento->cod_situacao)."',
                observacao = '".do_escape($movimento->observacao)."'
            WHERE id_movimento = '".do_escape($movimento->id_movimento)."'
        ";
        do_update($query);
    }
    
    public function pagar($id_movimento, $valorPago, $observacao) {
        $query = "
            SELECT tipo
            FROM movimento
            WHERE id_movimento = '".do_escape($id_movimento)."'
        ";
        $tipo = get_value($query, 'tipo');
        $query = " 
            UPDATE movimento  SET
                ultima_alteracao = NOW(),
                data_pagamento = NOW(),
                ".(($tipo == CREDITO) ? 'credito' : 'debito')." = '".do_escape(number_format($valorPago, 2, '.', ''))."',
                observacao = '".do_escape($observacao)."',
                cod_situacao = '".do_escape(MOVIMENTO_QUITADO)."'
            WHERE id_movimento = '".do_escape($id_movimento)."'
        ";
        do_update($query);
    }
    
    public function cancelar($id_movimento, $observacao) {
        $query = " 
            UPDATE movimento  SET
                ultima_alteracao = NOW(),
                data_pagamento = NULL,
                observacao = '".do_escape($observacao)."',
                cod_situacao = '".do_escape(MOVIMENTO_CANCELADO)."'
            WHERE id_movimento = '".do_escape($id_movimento)."'
        ";
        do_update($query);
    }
    
    public function excluir($id_movimento) {
        $query = "
            UPDATE movimento SET
                cod_situacao = '".do_escape(MOVIMENTO_CANCELADO)."'
            WHERE id_movimento = '".do_escape($id_movimento)."'
        ";
        do_delete($query);
    }
    
    private function existeMensalidade($ano, $mes, $id_pessoa, $valor) {
        $query = "
            SELECT id_movimento
            FROM movimento
            WHERE YEAR(data_vencimento) = '".do_escape($ano)."'
            AND MONTH(data_vencimento) = '".do_escape($mes)."'
            AND id_aluno = '".do_escape($id_pessoa)."'
        ";
        //var_dump($query, get_value($query, 'id_movimento'));
        //exit();
        return (get_value($query, 'id_movimento') > 0);
    }
    
    private function criarMensalidade($ano, $mes, $id_aluno, $valor) {
        
        $query = "
            SELECT id_responsavel
            FROM aluno_responsavel
            WHERE id_aluno = '".do_escape($id_aluno)."'
            ORDER BY principal DESC
            LIMIT 1
        ";
        $id_responsavel = get_value($query, 'id_responsavel');
        
        if (!$this->existeMensalidade($ano, $mes, $id_aluno, $valor)) {
            $movimento = new stdClass();
            $movimento->tipo = CREDITO;
            $movimento->cod_tipo = MOVIMENTO_MENSALIDADE;
            $movimento->id_pessoa = $id_responsavel;
            $movimento->id_aluno = $id_aluno;
            $movimento->credito = $valor;
            $movimento->data_vencimento = "$ano-$mes-05 00:00:00";
            $movimento->cod_situacao = MOVIMENTO_EM_ABERTO;
            //var_dump($movimento);
            //exit();
            $this->inserir($movimento);
        }
    }
    
    public function gerarMensalidade($ano, $mes = null, $id_pessoa = null) {
        if (!($ano > 2015))
            throw new Exception('O ano não pode ser inferior à 2015.');
        $query = "
            SELECT
                id_pessoa,
                valor_mensalidade
            FROM pessoa
            WHERE tipo = '".do_escape(TIPO_ALUNO)."'
            AND cod_situacao = '".do_escape(PESSOA_ATIVO)."'
            AND id_escola = '".do_escape(ID_ESCOLA)."'
        ";
        if (!is_null($id_pessoa))
            $query .= " AND id_pessoa = '".do_escape($id_pessoa)."'";
        $pessoas = get_result($query);
        //var_dump($query, $pessoas);
        //exit();
        foreach ($pessoas as $pessoa) {
            if (!($pessoa->valor_mensalidade > 0))
                continue;
            if (is_null($mes)) {
                for ($i = 1; $i <= 12; $i++)
                    $this->criarMensalidade($ano, $i, $pessoa->id_pessoa, $pessoa->valor_mensalidade);
            }
            else
                $this->criarMensalidade($ano, $mes, $pessoa->id_pessoa, $pessoa->valor_mensalidade);
        }
    }

    public function saldoAnterior($cod_situacao = null, $cod_tipo = null, $data = null) {
        $query = "
            SELECT 
                SUM(movimento.credito - movimento.debito) AS 'total'
            FROM movimento
            WHERE movimento.id_escola = '".do_escape(ID_ESCOLA)."'
        ";
        if ($cod_situacao > 0)
            $query .= " AND movimento.cod_situacao = '".do_escape($cod_situacao)."'";
        if ($cod_tipo > 0)
            $query .= " AND movimento.cod_tipo = '".do_escape($cod_tipo)."'";
        if (!is_null($data))
            $query .= " AND movimento.data_inclusao < '".date('Y-m-d', $data)." 23:59:59'";
        return get_value($query, 'total');
    }
    
    public function extrato($cod_situacao = null, $cod_tipo = null, $dataIni = null, $dataFim = null) {
        $query = "
            SELECT 
                movimento.id_movimento,
                movimento.data_vencimento AS 'data_movimento',
                responsavel.nome AS 'responsavel',
                aluno.nome AS 'aluno',
                movimento.observacao,
                movimento.credito,
                movimento.debito,
                movimento.cod_tipo,
                movimento.cod_situacao
            FROM movimento
            LEFT JOIN pessoa AS responsavel ON responsavel.id_pessoa = movimento.id_pessoa
            LEFT JOIN pessoa AS aluno ON aluno.id_pessoa = movimento.id_aluno
            WHERE movimento.id_escola = '".do_escape(ID_ESCOLA)."'
        ";
        
        if ($cod_situacao == MOVIMENTO_EM_ABERTO)
            $query .= " AND movimento.cod_situacao = ".MOVIMENTO_EM_ABERTO;
        elseif ($cod_situacao == MOVIMENTO_QUITADO)
            $query .= " AND movimento.cod_situacao = ".MOVIMENTO_QUITADO;
        elseif ($cod_situacao == MOVIMENTO_CANCELADO)
            $query .= " AND movimento.cod_situacao = ".MOVIMENTO_CANCELADO;
        
        if ($cod_tipo > 0)
            $query .= " AND movimento.cod_tipo = ".$cod_tipo;
        
        if ($dataIni > 0 && $dataFim > 0)
            $query .= " AND movimento.data_vencimento BETWEEN '".date('Y-m-d', $dataIni)." 00:00:00' AND '".date('Y-m-d', $dataFim)." 23:59:59'";
        
        $query .= " ORDER BY movimento.data_vencimento ";
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
            $saldo = $this->saldoAnterior($cod_situacao, $cod_tipo, $dataSaldo);
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
                
                //$movimento->data_str = date('d/m/Y', strtotime($movimento->data_movimento));
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
    
    
    /*
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
    */
    
}
