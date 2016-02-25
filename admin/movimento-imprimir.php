<?php 
require('common.inc.php');
require(dirname(__DIR__).'/fpdf/fpdf.php');
require(dirname(__DIR__).'/core/movimento-pdf.inc.php');

$regraMovimento = new Movimento();

$id_pessoa = intval($_GET['pessoa']);
if (!($id_pessoa > 0))
    $id_pessoa = null;
$mes = intval($_GET['mes']);
if (!($mes > 0))
    $mes = null;
$ano = intval($_GET['ano']);
if (!($ano > 0))
    $ano = null;

$pdf = new MovimentoCarne($id_pessoa, $mes, $ano);
$pdf->gerar();
$pdf->Output();