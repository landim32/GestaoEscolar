<?php

class Turma {
    
    private function query() {
        return "
            SELECT 
                turma.id_turma,
                turma.id_curso,
                turma.data_inclusao,
                turma.ultima_alteracao,
                turma.turno,
                turma.nome,
                turma.cod_situacao,
                curso.nome as 'curso',
                CASE turma.turno
                    WHEN 'm' THEN 'Matutino'
                    WHEN 'v' THEN 'Vespertino'
                    WHEN 'n' THEN 'Noturno'
                END as 'turno_nome'
            FROM turma
            INNER JOIN curso ON curso.id_curso = turma.id_curso
        ";
    }

    public function listarTurno() {
        return array(
            'm' => 'Matutino',
            'v' => 'Vespertino',
            'n' => 'Noturno'
        );
    }
    
    public function listarSituacao() {
        return array(
            1 => 'Ativo',
            2 => 'Inativo'
        );
    }
    
    public function listar($cod_situacao = null) {
        $query = $this->query();
        $query .= " WHERE curso.id_escola = '".do_escape(ID_ESCOLA)."'";
        if (!is_null($cod_situacao))
            $query .= " AND turma.cod_situacao = '".do_escape($cod_situacao)."'";
        $query .= " ORDER BY turma.nome";
        return get_result($query);
    }
    
    public function pegar($id_curso) {
        $query  = $this->query();
        $query .= " WHERE turma.id_curso = '".do_escape($id_curso)."'";
        return get_first_result($query);
    }
    
    public function pegarDoPost($turma = null) {
        if (is_null($turma))
            $turma = new stdClass();
        if (array_key_exists('id_turma', $_POST))
            $turma->id_turma = $_POST['id_turma'];
        if (array_key_exists('id_curso', $_POST))
            $turma->id_curso = $_POST['id_curso'];
        if (array_key_exists('nome', $_POST))
            $turma->nome = $_POST['nome'];
        if (array_key_exists('turno', $_POST))
            $turma->turno = $_POST['turno'];
        return $turma;
    }
    
    private function validar($turma) {
        if (!is_object($turma))
            throw new Exception('A turma não é um objeto.');
        if (isNullOrEmpty($turma->nome))
            throw new Exception('Nome da turma não pode estar vazio.');
        if (isNullOrEmpty($turma->turno))
            throw new Exception('O turno não pode estar vazio.');
        return $turma;
    }
    
    public function inserir($turma) {
        $turma = $this->validar($turma);
        $query = "
            INSERT INTO turma (
                id_curso,
                data_inclusao,
                ultima_alteracao,
                turno,
                nome,
                cod_situacao
            ) VALUES (
                '".do_escape($turma->id_curso)."',
                NOW(),
                NOW(),
                '".do_escape($turma->turno)."',
                '".do_escape($turma->nome)."',
                1
            )
        ";
        return do_insert($query);
    }
    
    public function alterar($turma) {
        $turma = $this->validar($turma);
        $query = "
            UPDATE turma SET
                ultima_alteracao = NOW(),
                nome = '".do_escape($turma->nome)."',
                turno = '".do_escape($turma->turno)."'
            WHERE id_turma = '".do_escape($turma->id_turma)."'
        ";
        do_update($query);        
    }
    
    public function excluir($id_turma) {
        $query = "
            DELETE FROM turma
            WHERE id_turma = '".do_escape($id_turma)."'
        ";
        do_delete($query);
    }
    
}