<?php
require(dirname(__DIR__).'/config.inc.php');
require(dirname(__DIR__).'/core/mysql-parser.inc.php');
require(dirname(__DIR__).'/core/function.inc.php');
require(dirname(__DIR__).'/core/Escola.inc.php');
require(dirname(__DIR__).'/core/Curso.inc.php');
require(dirname(__DIR__).'/core/Turma.inc.php');
require(dirname(__DIR__).'/core/Usuario.inc.php');
require(dirname(__DIR__).'/core/Pessoa.inc.php');
require(dirname(__DIR__).'/core/Movimento.inc.php');

$timeout = 60 * 60 * 24 * 30; // 30 dias
@ini_set('session.gc_maxlifetime', $timeout);
@ini_set('session.cookie_lifetime', $timeout);
session_cache_expire($timeout);
session_set_cookie_params($timeout);
session_start();

define('ID_ESCOLA', 1);

$regraUsuario = new Usuario();

if(basename($_SERVER["SCRIPT_FILENAME"], '.php') != 'login') {
    //var_dump(basename($_SERVER["SCRIPT_FILENAME"], '.php'));
    $usuario = $regraUsuario->pegarAtual();
    if (is_null($usuario)) {
        //var_dump($usuario);
        header('Location: '.WEB_PATH.'/admin/login');
        exit();
    }
}

if (count($_POST) > 0) {
    $regraMovimento = new Movimento();
    if ($_POST['acao'] == 'movimento-novo') {
        $movimento = $regraMovimento->pegarDoPost();
        $regraMovimento->inserir($movimento);
    }
    elseif ($_POST['acao'] == 'movimento-pagar') {
        $id_movimento = intval($_POST['id_movimento']);
        $valor = $_POST['valor_pago'];
        $observacao = $_POST['observacao'];
        $regraMovimento->pagar($id_movimento, $valor, $observacao);
        //$movimento = $regraMovimento->pegarDoPost();
        //$regraMovimento->pagar($movimento);
    }
    elseif ($_POST['acao'] == 'movimento-cancelar') {
        $id_movimento = intval($_POST['id_movimento']);
        $observacao = $_POST['observacao'];
        $regraMovimento->cancelar($id_movimento, $observacao);
        //$movimento = $regraMovimento->pegarDoPost();
        //$regraMovimento->pagar($movimento);
    }
}

//var_dump(basename($_SERVER["SCRIPT_FILENAME"], '.php'));
