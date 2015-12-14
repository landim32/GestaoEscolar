<?php 
$regraEscola = new Escola();
$escola = $regraEscola->pegarAtual();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Rodrigo Landim" />
    <link rel="icon" href="<?php echo WEB_PATH; ?>/favicon.ico" />
    <title><?php echo $escola->nome; ?></title>
    <link href="<?php echo WEB_PATH; ?>/css/escola-basica.min.css" rel="stylesheet" />
    <link href="<?php echo WEB_PATH; ?>/css/tokenfield-typeahead.min.css" rel="stylesheet" />
    <link href="<?php echo WEB_PATH; ?>/css/bootstrap-tokenfield.min.css" rel="stylesheet" />
    <link href="<?php echo WEB_PATH; ?>/css/jquery-ui.min.css" rel="stylesheet" />
    <style type="text/css">
        .ui-autocomplete { z-index:2147483647; }
    </style>

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="<?php echo WEB_PATH; ?>/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>