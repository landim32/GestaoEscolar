<?php
require(dirname(__DIR__).'/config.inc.php');
session_start();
unset($_SESSION['usuario_atual']);
session_destroy();
header('Location: '.WEB_PATH.'/admin/login');
exit();
