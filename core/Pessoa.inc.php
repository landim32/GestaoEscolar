<?php

/*
| id_pessoa        | int(11)      | NO   | PRI | NULL    | auto_increment |
| id_escola        | int(11)      | NO   | MUL | NULL    |                |
| data_inclusao    | datetime     | NO   |     | NULL    |                |
| ultima_alteracao | datetime     | NO   |     | NULL    |                |
| nome             | varchar(50)  | NO   |     | NULL    |                |
| data_nascimento  | datetime     | YES  |     | NULL    |                |
| genero           | char(1)      | YES  |     | NULL    |                |
| telefone1        | varchar(15)  | YES  |     | NULL    |                |
| telefone2        | varchar(15)  | YES  |     | NULL    |                |
| telefone3        | varchar(15)  | YES  |     | NULL    |                |
| telefone4        | varchar(15)  | YES  |     | NULL    |                |
| email1           | varchar(160) | YES  |     | NULL    |                |
| email2           | varchar(160) | YES  |     | NULL    |                |
| email3           | varchar(160) | YES  |     | NULL    |                |
| email4           | varchar(160) | YES  |     | NULL    |                |
| endereco         | varchar(60)  | YES  |     | NULL    |                |
| complemento      | varchar(30)  | YES  |     | NULL    |                |
| bairro           | varchar(30)  | YES  |     | NULL    |                |
| cidade           | varchar(30)  | YES  |     | NULL    |                |
| uf               | char(2)      | YES  |     | NULL    |                |
| cod_situacao     | int(11)      | NO   |     | 1       |                |

 */

define('TIPO_ALUNO', 'a');
define('TIPO_RESPONSAVEL', 'r');
define('PESSOA_ATIVO', 1);
define('PESSOA_INATIVO', 2);

class Pessoa {

    public function query() {
        return "
            SELECT
                pessoa.id_pessoa,
                pessoa.id_escola,
                pessoa.id_turma,
                pessoa.tipo,
                pessoa.data_inclusao,
                pessoa.ultima_alteracao,
                pessoa.nome,
                pessoa.data_nascimento,
                pessoa.genero,
                pessoa.cpf_cnpj,
                pessoa.valor_mensalidade,
                pessoa.telefone1,
                pessoa.telefone2,
                pessoa.telefone3,
                pessoa.telefone4,
                pessoa.email1,
                pessoa.email2,
                pessoa.email3,
                pessoa.email4,
                pessoa.endereco,
                pessoa.complemento,
                pessoa.bairro,
                pessoa.cidade,
                pessoa.uf,
                pessoa.cod_situacao,
                curso.nome as 'curso',
                turma.nome as 'turma',
                turma.turno
            FROM pessoa
            LEFT JOIN turma ON turma.id_turma = pessoa.id_turma
            LEFT JOIN curso ON curso.id_curso = turma.id_curso
        ";
    }
    
    private function atualizar($pessoa) {
        if (!is_null($pessoa)) {
            
        }
        return $pessoa;
    }
    
    public function listar($tipo = null, $cod_situacao = 1, $palavraChave = null) {
        $query = $this->query();
        $query .= " WHERE pessoa.cod_situacao = '".do_escape($cod_situacao)."' ";
        if ($tipo == TIPO_ALUNO)
            $query .= " AND pessoa.tipo = '".do_escape(TIPO_ALUNO)."' ";
        elseif ($tipo == TIPO_RESPONSAVEL)
            $query .= " AND pessoa.tipo = '".do_escape(TIPO_RESPONSAVEL)."' ";
        if (!isNullOrEmpty($palavraChave))
            $query .= " AND pessoa.nome LIKE '%".do_escape($palavraChave)."%' ";
        $pessoas = array();
        $result = get_result_db($query);
        while ($pessoa = get_object($result)) {
            $pessoas[] = $this->atualizar($pessoa);
        }
        free_result($result);
        return $pessoas;
    }
    
    public function listarAluno($id_responsavel, $cod_situacao = 1) {
        $query = "
            SELECT
                pessoa.id_pessoa,
                pessoa.id_escola,
                pessoa.id_turma,
                pessoa.tipo,
                pessoa.data_inclusao,
                pessoa.ultima_alteracao,
                pessoa.nome,
                pessoa.data_nascimento,
                pessoa.genero,
                pessoa.cpf_cnpj,
                pessoa.valor_mensalidade,
                pessoa.telefone1,
                pessoa.telefone2,
                pessoa.telefone3,
                pessoa.telefone4,
                pessoa.email1,
                pessoa.email2,
                pessoa.email3,
                pessoa.email4,
                pessoa.endereco,
                pessoa.complemento,
                pessoa.bairro,
                pessoa.cidade,
                pessoa.uf,
                pessoa.cod_situacao,
                curso.nome as 'curso',
                turma.nome as 'turma',
                turma.turno
            FROM pessoa
            INNER JOIN aluno_responsavel ON aluno_responsavel.id_aluno = pessoa.id_pessoa
            LEFT JOIN turma ON turma.id_turma = pessoa.id_turma
            LEFT JOIN curso ON curso.id_curso = turma.id_curso
            WHERE aluno_responsavel.id_responsavel = '".do_escape($id_responsavel)."'
            AND pessoa.cod_situacao = '".do_escape($cod_situacao)."'
        ";
        $pessoas = array();
        $result = get_result_db($query);
        while ($pessoa = get_object($result)) {
            $pessoas[] = $this->atualizar($pessoa);
        }
        free_result($result);
        return $pessoas;
    }
    
    public function listarResponsavel($id_aluno, $cod_situacao = 1) {
        $query = "
            SELECT
                pessoa.id_pessoa,
                pessoa.id_escola,
                pessoa.id_turma,
                pessoa.tipo,
                pessoa.data_inclusao,
                pessoa.ultima_alteracao,
                pessoa.nome,
                pessoa.data_nascimento,
                pessoa.genero,
                pessoa.cpf_cnpj,
                pessoa.valor_mensalidade,
                pessoa.telefone1,
                pessoa.telefone2,
                pessoa.telefone3,
                pessoa.telefone4,
                pessoa.email1,
                pessoa.email2,
                pessoa.email3,
                pessoa.email4,
                pessoa.endereco,
                pessoa.complemento,
                pessoa.bairro,
                pessoa.cidade,
                pessoa.uf,
                pessoa.cod_situacao
            FROM pessoa
            INNER JOIN aluno_responsavel ON aluno_responsavel.id_responsavel = pessoa.id_pessoa
            WHERE aluno_responsavel.id_aluno = '".do_escape($id_aluno)."'
            AND pessoa.cod_situacao = '".do_escape($cod_situacao)."'
        ";
        $pessoas = array();
        $result = get_result_db($query);
        while ($pessoa = get_object($result)) {
            $pessoas[] = $this->atualizar($pessoa);
        }
        free_result($result);
        return $pessoas;
    }
    
    public function pegar($id_pessoa) {
        $query = $this->query()."
            WHERE pessoa.id_pessoa = '".do_escape($id_pessoa)."'
        ";
        //echo $query;
        return $this->atualizar(get_first_result($query));
    }
    
    public function pegarDoPost($pessoa = null) {
        if (is_null($pessoa))
            $pessoa = new stdClass();
        if (array_key_exists('id_pessoa', $_POST))
            $pessoa->id_pessoa = intval($_POST['id_pessoa']);
        if (array_key_exists('id_turma', $_POST))
            $pessoa->id_turma = intval($_POST['id_turma']);
        if (array_key_exists('id_aluno', $_POST))
            $pessoa->id_aluno = intval($_POST['id_aluno']);
        if (array_key_exists('id_responsavel', $_POST))
            $pessoa->id_responsavel = intval($_POST['id_responsavel']);
        if (array_key_exists('tipo', $_POST))
            $pessoa->tipo = $_POST['tipo'];
        if (array_key_exists('nome', $_POST))
            $pessoa->nome = $_POST['nome'];
        if (array_key_exists('data_nascimento', $_POST))
            $pessoa->data_nascimento = dateToSql($_POST['data_nascimento']);
        if (array_key_exists('genero', $_POST))
            $pessoa->genero = $_POST['genero'];
        if (array_key_exists('cpf_cnpj', $_POST))
            $pessoa->cpf_cnpj = $_POST['cpf_cnpj'];
        if (array_key_exists('valor_mensalidade', $_POST))
            $pessoa->valor_mensalidade = $_POST['valor_mensalidade'];
        if (array_key_exists('telefone1', $_POST))
            $pessoa->telefone1 = $_POST['telefone1'];
        if (array_key_exists('telefone2', $_POST))
            $pessoa->telefone2 = $_POST['telefone2'];
        if (array_key_exists('telefone3', $_POST))
            $pessoa->telefone3 = $_POST['telefone3'];
        if (array_key_exists('telefone4', $_POST))
            $pessoa->telefone4 = $_POST['telefone4'];
        if (array_key_exists('email1', $_POST))
            $pessoa->email1 = $_POST['email1'];
        if (array_key_exists('email2', $_POST))
            $pessoa->email2 = $_POST['email2'];
        if (array_key_exists('email3', $_POST))
            $pessoa->email3 = $_POST['email3'];
        if (array_key_exists('email4', $_POST))
            $pessoa->email4 = $_POST['email4'];
        if (array_key_exists('endereco', $_POST))
            $pessoa->endereco = $_POST['endereco'];
        if (array_key_exists('complemento', $_POST))
            $pessoa->complemento = $_POST['complemento'];
        if (array_key_exists('bairro', $_POST))
            $pessoa->bairro = $_POST['bairro'];
        if (array_key_exists('cidade', $_POST))
            $pessoa->cidade = $_POST['cidade'];
        if (array_key_exists('uf', $_POST))
            $pessoa->uf = $_POST['uf'];
        if (array_key_exists('cod_situacao', $_POST))
            $pessoa->cod_situacao = intval($_POST['cod_situacao']);
        return $pessoa;
    }
    
    private function validar($pessoa) {
        if (!is_object($pessoa))
            throw new Exception('Objeto inválido!');
        if (isNullOrEmpty($pessoa->nome))
            throw new Exception('Preencha o nome.');
        return $pessoa;
    }
    
    public function inserir($pessoa = null) {
        if (is_null($pessoa))
            $pessoa = $this->pegarDoPost();
        $pessoa = $this->validar($pessoa);
        $query = "
            INSERT INTO pessoa (
                id_escola,
                id_turma,
                tipo,
                data_inclusao,
                ultima_alteracao,
                nome,
                data_nascimento,
                genero,
                cpf_cnpj,
                valor_mensalidade,
                telefone1,
                telefone2,
                telefone3,
                telefone4,
                email1,
                email2,
                email3,
                email4,
                endereco,
                complemento,
                bairro,
                cidade,
                uf,
                cod_situacao
            ) VALUES (
                ".ID_ESCOLA.",
                ".do_escape_full($pessoa->id_turma).",
                '".do_escape($pessoa->tipo)."',
                NOW(),
                NOW(),
                '".do_escape($pessoa->nome)."',
                '".do_escape($pessoa->data_nascimento)."',
                ".do_escape_full($pessoa->genero).",
                ".do_escape_full($pessoa->cpf_cnpj).",
                '".do_escape(number_format($pessoa->valor_mensalidade, 2, '.', ''))."',
                ".do_escape_full($pessoa->telefone1).",
                ".do_escape_full($pessoa->telefone2).",
                ".do_escape_full($pessoa->telefone3).",
                ".do_escape_full($pessoa->telefone4).",
                ".do_escape_full($pessoa->email1).",
                ".do_escape_full($pessoa->email2).",
                ".do_escape_full($pessoa->email3).",
                ".do_escape_full($pessoa->email4).",
                ".do_escape_full($pessoa->endereco).",
                ".do_escape_full($pessoa->complemento).",
                ".do_escape_full($pessoa->bairro).",
                ".do_escape_full($pessoa->cidade).",
                ".do_escape_full($pessoa->uf).",
                1
            )
        ";
        $pessoa->id_pessoa = do_insert($query);
        if ($pessoa->id_aluno > 0) {
            $query = "
                INSERT INTO aluno_responsavel (
                    id_aluno,
                    id_responsavel,
                    principal
                ) VALUES (
                    '".do_escape($pessoa->id_aluno)."',
                    '".do_escape($pessoa->id_pessoa)."',
                    1
                )
            ";
            do_insert($query);
        }
        if ($pessoa->id_responsavel > 0) {
            $query = "
                INSERT INTO aluno_responsavel (
                    id_aluno,
                    id_responsavel,
                    principal
                ) VALUES (
                    '".do_escape($pessoa->id_pessoa)."',
                    '".do_escape($pessoa->id_responsavel)."',
                    1
                )
            ";
            do_insert($query);
        }
        return $pessoa->id_pessoa;
    }
    
    public function alterar($pessoa = null) {
        if (is_null($pessoa))
            $pessoa = $this->pegarDoPost();
        $pessoa = $this->validar($pessoa);
        $query = "
            UPDATE pessoa SET
                ultima_alteracao = NOW(),
                id_turma = ".do_escape_full($pessoa->id_turma).",
                tipo = '".do_escape($pessoa->tipo)."',
                nome = '".do_escape($pessoa->nome)."',
                data_nascimento = '".do_escape($pessoa->data_nascimento)."',
                genero = ".do_escape_full($pessoa->genero).",
                cpf_cnpj = ".do_escape_full($pessoa->cpf_cnpj).",
                valor_mensalidade = '".do_escape(number_format($pessoa->valor_mensalidade, 2, '.', ''))."',
                telefone1 = ".do_escape_full($pessoa->telefone1).",
                telefone2 = ".do_escape_full($pessoa->telefone2).",
                telefone3 = ".do_escape_full($pessoa->telefone3).",
                telefone4 = ".do_escape_full($pessoa->telefone4).",
                email1 = ".do_escape_full($pessoa->email1).",
                email2 = ".do_escape_full($pessoa->email2).",
                email3 = ".do_escape_full($pessoa->email3).",
                email4 = ".do_escape_full($pessoa->email4).",
                endereco = ".do_escape_full($pessoa->endereco).",
                complemento = ".do_escape_full($pessoa->complemento).",
                bairro = ".do_escape_full($pessoa->bairro).",
                cidade = ".do_escape_full($pessoa->cidade).",
                uf = ".do_escape_full($pessoa->uf).",
                cod_situacao = ".do_escape_full($pessoa->cod_situacao)."
            WHERE id_pessoa = ".do_escape_full($pessoa->id_pessoa)."
        ";
        //echo '<pre>';
        //var_dump($query, $pessoa, $_POST);
        //echo '</pre>';
        //exit();
        do_update($query);
    }
    
    public function relacionar($id_responsavel, $id_aluno) {
        $query = "
            SELECT COUNT(*) AS 'quantidade'
            FROM aluno_responsavel
            WHERE id_aluno = '".do_escape($id_aluno)."'
            AND id_responsavel = '".do_escape($id_responsavel)."'
        ";
        $quantidade = get_value($query, 'quantidade');
        if ($quantidade <= 0) {
            $query = "
                INSERT INTO aluno_responsavel (
                    id_aluno,
                    id_responsavel,
                    principal
                ) VALUES (
                    '".do_escape($id_aluno)."',
                    '".do_escape($id_responsavel)."',
                    0
                )
            ";
            do_insert($query);
        }
    }
    
    public function excluirRelacionamento($id_responsavel, $id_aluno) {
        $query = "
            DELETE FROM aluno_responsavel
            WHERE id_responsavel = '".do_escape($id_responsavel)."'
            AND id_aluno = '".do_escape($id_aluno)."'
        ";
        do_delete($query);
    }
    
    public function excluir($id_pessoa) {
        $query = "
            UPDATE pessoa SET
                cod_situacao = '".PESSOA_INATIVO."'
            WHERE id_pessoa = '".do_escape($id_pessoa)."'
        ";
        do_update($query);
    }
    
}
