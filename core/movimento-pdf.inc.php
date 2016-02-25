<?php

class MovimentoCarne extends FPDF
{
    private $id_pessoa;
    private $mes;
    private $ano;
    
    public function __construct($id_pessoa, $mes, $ano) {
        $this->id_pessoa = $id_pessoa;
        $this->mes = $mes;
        $this->ano = $ano;
        parent::__construct();
    }
    
    // Page header
    function Header() {
        //
    }

    // Page footer
    function Footer() {
        //
    }
    
    public function gerar() {
        $regraMovimento = new Movimento();
        $movimentos = $regraMovimento->listarCarne($this->id_pessoa, $this->mes, $this->ano);
        //echo '<pre>';
        //var_dump($movimentos);
        //echo '</pre>';
        //exit();
        
        $indice = 0;
        
        //define('MARGEM_SUPERIOR', 10);
        //define('MARGEM_ESQUERDA', 10);
        
        foreach ($movimentos as $movimento) {
            if ($indice == 0) {
                $this->AddPage();
                $this->SetXY(0, 0);
            }
            
            $altura = 42;
            
            $y = ($indice * ($altura + 3)) + 10;
            $this->Rect(10, $y, 60, $altura);
            
            $this->SetXY(10, $y);
            $this->SetFont('Arial','B',10);
            $this->Cell(60, 8, "RECIBO DO SACADO", 0, 1, 'C');
            $this->Line(12, $y + 7, 68, $y + 7);
            
            $ny = $y + 8;
            
            $this->SetXY(11, $ny);
            $this->SetFont('Arial','', 8);
            $this->Cell(23, 4, utf8_decode("Referente à"), 0, 1, 'R');
            
            $this->Line(35, $ny - 1, 35, $ny + 4);
            
            $dataVencimento = strtotime($movimento->data_vencimento);
            $this->SetXY(30, $ny);
            $this->SetFont('Arial','', 8);
            $this->Cell(38, 4, utf8_decode(strtoupper(strftime("%B / %Y", $dataVencimento))), 0, 1, 'R');
            
            $this->Line(12, $ny + 4, 68, $ny + 4);
            
            
            $ny = $ny + 5;
            
            $this->SetXY(11, $ny);
            $this->SetFont('Arial','',8);
            $this->Cell(23, 4, utf8_decode("Vencimento"), 0, 1, 'R');
            
            $this->Line(35, $ny - 1, 35, $ny + 4);
            
            $this->SetXY(30, $ny);
            $this->SetFont('Arial','',8);
            $this->Cell(38, 4, utf8_decode(date('d/m/Y', $dataVencimento)), 0, 1, 'R');
            
            $this->Line(12, $ny + 4, 68, $ny + 4);
            

            $ny = $ny + 5;
            
            $this->SetXY(11, $ny);
            $this->SetFont('Arial','',8);
            $this->Cell(23, 4, utf8_decode("Valor"), 0, 1, 'R');
            
            $this->Line(35, $ny - 1, 35, $ny + 4);
            
            $this->SetXY(30, $ny);
            $this->SetFont('Arial','', 8);
            $this->Cell(38, 4, utf8_decode('R$ '.number_format($movimento->valor_total, 2, '.', ',')), 0, 1, 'R');
            
            $this->Line(12, $ny + 4, 68, $ny + 4);
            
            $ny = $ny + 5;
            
            $this->SetXY(11, $ny);
            $this->SetFont('Arial','',8);
            $this->Cell(23, 4, utf8_decode("Valor Pago"), 0, 1, 'R');
            
            $this->Line(35, $ny - 1, 35, $ny + 4);            
            $this->Line(12, $ny + 4, 68, $ny + 4);
            
            $ny = $ny + 5;
            
            $this->SetXY(11, $ny);
            $this->SetFont('Arial','',8);
            $this->Cell(23, 4, utf8_decode("Data Pgto"), 0, 1, 'R');
            
            $this->Line(35, $ny - 1, 35, $ny + 4);            
            
            $this->SetXY(30, $ny);
            $this->SetFont('Arial','', 8);
            $this->Cell(38, 4, utf8_decode('/           /           '), 0, 1, 'R');
            
            $this->Line(12, $ny + 4, 68, $ny + 4);
            
            $ny = $ny + 8;
            $this->Line(12, $ny + 4, 68, $ny + 4);
            
            
            $this->Rect(75, $y, 125, $altura);
            
            $this->Line(160, $y, 160, $y + $altura);
            
            $this->Line(75, $y + 8, 200, $y + 8);
            $this->Line(75, $y + 16, 200, $y + 16);
            $this->Line(125, $y + 8, 125, $y + 16);
            
            $this->Line(160, $y + 24, 200, $y + 24);
            $this->Line(160, $y + 32, 200, $y + 32);
            
            
            $ny = $y;
            
            $this->SetXY(75, $ny + 0.5);
            $this->SetFont('Arial','',7);
            $this->Cell(85, 3, "cedente", 0, 1, 'L');
            
            $this->SetXY(75, $ny + 3);
            $this->SetFont('Arial','B',10);
            $this->Cell(85, 5, "ESCOLA BEM ME QUER", 0, 1, 'L');
            
            $this->SetXY(160, $ny + 0.5);
            $this->SetFont('Arial','',7);
            $this->Cell(40, 3, "vencimento", 0, 1, 'R');
            
            $this->SetXY(160, $ny + 3);
            $this->SetFont('Arial','',10);
            $this->Cell(40, 5, utf8_decode(date('d/m/Y', $dataVencimento)), 0, 1, 'R');
            
            $ny = $ny + 8;
            
            $this->SetXY(75, $ny + 0.5);
            $this->SetFont('Arial','',7);
            $this->Cell(85, 3, "sacado", 0, 1, 'L');
            
            $this->SetXY(75, $ny + 3);
            $this->SetFont('Arial','',10);
            $this->Cell(85, 5, $movimento->nome, 0, 1, 'L');
            
            $this->SetXY(126, $ny + 0.5);
            $this->SetFont('Arial','',7);
            $this->Cell(85, 3, "cpf", 0, 1, 'L');
            
            $this->SetXY(126, $ny + 3);
            $this->SetFont('Arial','',10);
            $this->Cell(85, 5, $movimento->cpf_cnpj, 0, 1, 'L');
            
            $this->SetXY(160, $ny + 0.5);
            $this->SetFont('Arial','',7);
            $this->Cell(40, 3, "(=) valor documento", 0, 1, 'R');
            
            $this->SetXY(160, $ny + 3);
            $this->SetFont('Arial','',10);
            $this->Cell(40, 5, utf8_decode('R$ '.number_format($movimento->valor_total, 2, '.', ',')), 0, 1, 'R');
            
            $ny = $ny + 8;
            
            $this->SetXY(75, $ny + 0.5);
            $this->SetFont('Arial','',7);
            $this->Cell(40, 3, utf8_decode("instruções"), 0, 1, 'L');
            
            $texto = "Referente a parcela do mês ".strftime("%B/%Y", $dataVencimento)." ";
            if (count($movimento->movimentos) > 1)
                $texto .= "dos alunos:\n";
            else
                $texto .= "do aluno:\n";
            foreach ($movimento->movimentos as $aluno) {
                $texto .= $aluno->nome.' - Turma '.$aluno->turma."\n";
            }
            
            $this->SetXY(75, $ny + 4);
            $this->SetFont('Arial','',9);
            $this->MultiCell(85, 4, utf8_decode($texto), 0, 'L');
            
            $this->SetXY(160, $ny + 0.5);
            $this->SetFont('Arial','',7);
            $this->Cell(40, 3, "(-) descontos", 0, 1, 'R');
            
            $ny = $ny + 8;
            
            $this->SetXY(160, $ny + 0.5);
            $this->SetFont('Arial','',7);
            $this->Cell(40, 3, "(+) multa / juros", 0, 1, 'R');
            
            $ny = $ny + 8;
            
            $this->SetXY(160, $ny + 0.5);
            $this->SetFont('Arial','',7);
            $this->Cell(40, 3, "(=) total cobrado", 0, 1, 'R');
            
            
            $indice++;
            if ($indice >= 6)
                $indice = 0;
        }
    }
}

?>
