<?php

/*
create table usuario (
id_usuario int not null auto_increment,
id_escola int not null,
nome varchar(50) not null,
email varchar(150) not null,
senha varchar(30) not null,
foto varchar(40),
cod_situacao tinyint not null default 1,
primary key(id_usuario),
foreign key (id_escola) REFERENCES escola(id_escola)
);
 */

class Usuario {
    
    private function query() {
        $query = "
            SELECT 
                usuario.id_usuario,
                usuario.id_escola,
                usuario.cod_tipo,
                usuario.data_inclusao,
                usuario.ultima_alteracao,
                usuario.email,
                usuario.nome,
                usuario.senha,
                usuario.cod_situacao
            FROM usuario
        ";
        return $query;
    }
    
    public function listarTipo() {
        return array(
            1 => 'Administrador',
            2 => 'Diretor',
            3 => 'Coordenador',
            4 => 'Professor'
        );
    }
    
    public function listarSituacao() {
        return array(
            1 => 'Ativo',
            2 => 'Bloqueado',
            3 => 'Inativo'
        );
    }

    private function atualizar($usuario) {
        if (!is_null($usuario)) {
            $tipos = $this->listarTipo();
            $situacoes = $this->listarSituacao();
            $usuario->tipo = $tipos[$usuario->cod_tipo];
            $usuario->situacao = $situacoes[$usuario->cod_situacao];
        }
        return $usuario;
    }
    
    public function listar($cod_situacao = null) {
        $query = $this->query()."
            WHERE usuario.id_escola = '".  do_escape(ID_ESCOLA)."'
        ";
        if (!is_null($cod_situacao))
            $query .= " AND usuario.cod_situacao = '".do_escape($cod_situacao)."' ";
        $query .= " ORDER BY usuario.nome ";
        $usuarios = array();
        $result = get_result_db($query);
        while ($usuario = get_object($result)) {
            $usuarios[] = $this->atualizar($usuario);
        }
        free_result($result);
        return $usuarios;
    }
    
    public function pegar($id_usuario) {
        $query = $this->query()."
            WHERE usuario.id_usuario = '".  do_escape($id_usuario)."'
        ";
        return $this->atualizar(get_first_result($query));
    }
    
    public function pegarDoPost($usuario = null){
        if (is_null($usuario))
            $usuario = new stdClass();
        if (array_key_exists('email', $_POST))
            $usuario->email = $_POST['email'];
        if (array_key_exists('nome', $_POST))
            $usuario->nome = $_POST['nome'];
        if (array_key_exists('senha', $_POST))
            $usuario->senha = $_POST['senha'];
        if (array_key_exists('senha_confirma', $_POST))
            $usuario->senha_confirma = $_POST['senha_confirma'];
        if (array_key_exists('cod_tipo', $_POST))
            $usuario->cod_tipo = $_POST['cod_tipo'];
        if (array_key_exists('cod_situacao', $_POST))
            $usuario->cod_situacao = $_POST['cod_situacao'];
        return $usuario;
    }
    
    private function validar($usuario) {
        if (!is_object($usuario))
            throw new Exception('Objeto não pode ser nulo.');
        if (isNullOrEmpty($usuario->email))
            throw new Exception('Email não pode ser vazio.');
        if (!validarEmail($usuario->email))
            throw new Exception('Email não é válido.');
        if (isNullOrEmpty($usuario->nome))
            throw new Exception('Nome não pode ser vazio.');
        if (!($usuario->id_usuario > 0) && isNullOrEmpty($usuario->senha))
            throw new Exception('Senha não pode ser vazio.');
        if (!isNullOrEmpty($usuario->senha) && $usuario->senha != $usuario->senha_confirma)
            throw new Exception('Senha não bate com a confirmação.');
        return $usuario;
    }
    
    public function inserir($usuario) {
        $usuario = $this->validar($usuario);
        $query = "
            INSERT INTO usuario (
                id_escola,
                data_inclusao,
                ultima_alteracao,
                email,
                nome,
                senha,
                cod_tipo,
                cod_situacao
            ) VALUES (
                '".do_escape(ID_ESCOLA)."',
                NOW(),
                NOW(),
                '".do_escape($usuario->email)."',
                '".do_escape($usuario->nome)."',
                '".do_escape($usuario->senha)."',
                '".do_escape($usuario->cod_tipo)."',
                1
            )
        ";
        return do_insert($query);
    }
    
    public function alterar($usuario) {
        $usuario = $this->validar($usuario);
        $query = "
            UPDATE usuario SET
                ultima_alteracao = NOW(),
                email = '".do_escape($usuario->email)."',
                nome = '".do_escape($usuario->nome)."',
                cod_tipo = '".do_escape($usuario->cod_tipo)."',
                cod_situacao = '".do_escape($usuario->cod_situacao)."'
        ";
        if (!isNullOrEmpty($usuario->senha))
            $query .= ", senha = '".do_escape($usuario->senha)."' ";
        $query .= " WHERE id_usuario = '".do_escape($usuario->id_usuario)."'";
        do_update($query);
    }
    
    public function logar($email, $senha) {
        $query = $this->query()."
            WHERE usuario.email = '".do_escape($email)."'
            AND usuario.senha = '".do_escape($senha)."'
            AND usuario.cod_situacao = 1
        ";
        $usuario = get_first_result($query);
        if (!is_null($usuario)) {
            $_SESSION['usuario_atual'] = $usuario;
            return true;
        }
        else
            return false;
    }
    
    public function gravarSessao($usuario) {
        if (session_status() == PHP_SESSION_ACTIVE)
           $_SESSION['usuario_atual'] = $usuario; 
    }
    
    public function pegarAtual() {
        if (session_status() == PHP_SESSION_ACTIVE && array_key_exists('usuario_atual', $_SESSION)) {
           return $_SESSION['usuario_atual']; 
        }
        else 
            return null;
    }
}
