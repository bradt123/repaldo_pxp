<?php
class REvaluacionDesempenioCorreo extends  ReportePDF{

    function Header(){

    }
    public function Footer(){
        $this->SetY(-15);
        $this->SetFont('times', '', 11);
        $this->Cell(60, 0, 'Para: '.$this->datos[0]['nombre_funcioario'], 0, 0, 'L', 0, '', 0);
        $this->Cell(30, 0, '', 0, 0, 'C', 0, '', 0);
        $this->Cell(0, 0, 'Correo: '.$this->datos[0]['correo'], 0, 0, 'L', 0, '', 0);
        $this->Ln(5);
        $this->Cell(60, 0, 'De : '.$this->datos[0]['emisor'], 0, 0, 'L', 0, '', 0);
        $this->Cell(30, 0, '', 0, 0, 'C', 0, '', 0);
        $this->Cell(0, 0, 'Fecha Envio: '.date_format(date_create($this->datos[0]["fecha_correo"]), 'd/m/Y'), 0, 0, 'L', 0, '', 0);
    }
    function setDatos($datos) {
        $this->datos = $datos;
    }
    function  reporteGeneralPrimer(){

        $this->writeHTML(''.$this->datos[0]['plantilla'].'',true);

    }
    function generarReporte() {

        $this->SetMargins(30,40,30);
        $this->setFontSubsetting(false);
        $this->SetMargins(30,40,30);
        $this->AddPage();
        $this->reporteGeneralPrimer();


    }

}
?>