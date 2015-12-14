<?php
require(dirname(__DIR__).'/config.inc.php');
require(dirname(__DIR__).'/core/mysql-parser.inc.php');
require(dirname(__DIR__).'/core/function.inc.php');
require(dirname(__DIR__).'/core/Escola.inc.php');
require(dirname(__DIR__).'/core/Curso.inc.php');
require(dirname(__DIR__).'/core/Turma.inc.php');
require(dirname(__DIR__).'/core/Usuario.inc.php');
require(dirname(__DIR__).'/core/Pessoa.inc.php');

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
//var_dump(basename($_SERVER["SCRIPT_FILENAME"], '.php'));
