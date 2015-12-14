<?php
/*
create table curso (
id_curso int not null auto_increment,
id_escola int not null,
data_inclusao datetime not null,
ultima_alteracao datetime not null,
nome varchar(30) not null,
cod_situacao tinyint not null default 1,
primary key(id_curso),
foreign key (id_escola) REFERENCES escola(id_escola)
);
 */

define('CURSO_ATIVO', 1);
define('CURSO_INATIVO', 2);

class Curso {
    
    private function query() {
        return "
            SELECT 
                id_curso,
                id_escola,
                data_inclusao,
                ultima_alteracao,
                nome,
                cod_situacao
            FROM curso
        ";
    }

    public function listarSituacao() {
        return array(
            CURSO_ATIVO => 'Ativo',
            CURSO_INATIVO => 'Inativo'
        );
    }
    
    public function listar($cod_situacao = null) {
        $query = $this->query();
        $query .= " WHERE id_escola = '".do_escape(ID_ESCOLA)."'";
        if (!is_null($cod_situacao))
            $query .= " AND cod_situacao = '".do_escape($cod_situacao)."'";
        $query .= " ORDER BY nome";
        return get_result($query);
    }
    
    public function pegar($id_curso) {
        $query  = $this->query();
        $query .= " WHERE id_curso = '".do_escape($id_curso)."'";
        return get_first_result($query);
    }
    
    public function pegarDoPost($curso = null) {
        if (is_null($curso))
            $curso = new stdClass();
        if (array_key_exists('id_curso', $_POST))
            $curso->id_curso = $_POST['id_curso'];
        if (array_key_exists('nome', $_POST))
            $curso->nome = $_POST['nome'];
        //if (array_key_exists('cod_situacao', $_POST))
        //    $curso->cod_situacao = $_POST['cod_situacao'];
        return $curso;
    }
    
    private function validar($curso) {
        if (!is_object($curso))
            throw new Exception('O curso não é um objeto.');
        if (isNullOrEmpty($curso->nome))
            throw new Exception('Nome do curso não pode estar vazio.');
        //if (!($curso->cod_situacao > 0))
        //    throw new Exception('Situação não informado.');
        return $curso;
    }
    
    public function inserir($curso) {
        $curso = $this->validar($curso);
        $query = "
            INSERT INTO curso (
                id_escola,
                data_inclusao,
                ultima_alteracao,
                nome,
                cod_situacao
            ) VALUES (
                '".do_escape(ID_ESCOLA)."',
                NOW(),
                NOW(),
                '".do_escape($curso->nome)."',
                1
            )
        ";
        return do_insert($query);
    }
    
    public function alterar($curso) {
        $curso = $this->validar($curso);
        $query = "
            UPDATE curso SET
                ultima_alteracao = NOW(),
                nome = '".do_escape($curso->nome)."'
            WHERE id_curso = '".do_escape($curso->id_curso)."'
        ";
        do_update($query);        
    }
    
    public function excluir($id_curso) {
        $query = "
            DELETE FROM curso
            WHERE id_curso = '".do_escape($id_curso)."'
        ";
        do_delete($query);
    }
    
}