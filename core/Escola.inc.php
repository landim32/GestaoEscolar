<?php

/*
create table escola (
id_escola int not null auto_increment,
nome varchar(50) not null,
cod_situacao int not null default 1,
primary key(id_escola)
);
 */

define('ESCOLA_ATIVO', 1);
define('ESCOLA_INATIVO', 2);

class Escola {
    
    private function query() {
        return "
            SELECT
                id_escola,
                nome,
                cod_situacao
            FROM escola
        ";
    }
    
    public function listar($cod_situacao = null) {
        $query = $this->query();
        if (!is_null($cod_situacao))
            $query .= " WHERE cod_situacao = '".do_escape($cod_situacao)."' ";
        $query .= " ORDER BY nome ";
        return get_result($query);
    }
    
    public function pegarAtual() {
        if (session_status() == PHP_SESSION_ACTIVE) {
            if (array_key_exists('escola_atual', $_SESSION)) {
                $escola = $_SESSION['escola_atual'];
                if ($escola->id_escola != ID_ESCOLA) {
                    $escola = $this->pegar( ID_ESCOLA );
                    $_SESSION['escola_atual'] = $conta;
                }
            }
            else {
                $escola = $this->pegar( ID_ESCOLA );
                $_SESSION['escola_atual'] = $conta;
            }
        }
        else {
            if (array_key_exists('escola_atual', $GLOBALS)) {
                $escola = $GLOBALS['escola_atual'];
            }
            else {
                $escola = $this->pegar( ID_ESCOLA );
		$GLOBALS['escola_atual'] = $conta;
            }
        }
        return $escola;
    }
    
    public function pegar($id_escola) {
        $query = $this->query()."
            WHERE id_escola = '".do_escape(ID_ESCOLA)."'
        ";
        return get_first_result($query);
    }
    
    public function inserir($escola) {
        
    }
    
    public function alterar($escola) {
        
    }
    
    public function excluir($id_escola) {
        
    }
}