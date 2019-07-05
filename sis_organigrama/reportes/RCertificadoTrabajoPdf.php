<?php
class RCertificadoTrabajoPdf extends  ReportePDF{
    function Header() {
        $this->ln(35);
        $img_file = dirname(__FILE__).'/../media/direcciones.jpg';
        $img_agua = dirname(__FILE__).'/../media/marcaAgua.jpg';

        $this->Image($img_file, 7, 10, 90, 500, '', '', '', false, 300, '', false, false, 0);
        $this->Image($img_agua, 130, 150, 80, 80, '', '', '', false, 300, '', false, false, 0);
    }
    function setDatos($datos) {
        $this->datos = $datos;    
    }
    function reporteGeneral(){
        setlocale(LC_ALL,"es_ES@euro","es_ES","esp");        
        if ($this->datos[0]['genero'] == 'Sr'){
            $tipo = 'del interesado';
            $gen = 'el';
            $tra = 'trabajor';
            $tipol = 'al interesado';
        }else{
            $tipo = 'de la interesada';
            $gen = 'la';
            $tra = 'trabajadora';
            $tipol = 'a la interesada';
        }
        $proceso = $this->datos[0]['id_proceso_wf']; 
        $documento = $this->datos[0]['id_documento_wf'];
        //$link = $_SERVER['HTTP_HOST'].'/'.ltrim($_SESSION["_FOLDER"], '/').'sis_organigrama/control/LecturaQrFirmDigBoa.php?p='.$proceso.'&d='.$documento;
		$link = 'http://10.150.0.91/kerp_breydi/sis_organigrama/control/LecturaQrFirmDigBoa.php?p='.$proceso.'&d='.$documento.'';
        $cadena = 'Numero Tramite: '.$this->datos[0]['nro_tramite']."\n".'Fecha Solicitud: '.$this->datos[0]['fecha_solicitud']."\n".'Funcionario: '.$this->datos[0]['nombre_funcionario']."\n".'Firmado Por: '.$this->datos[0]['jefa_recursos']."\n".'Emitido Por: '.$this->datos[0]['fun_imitido']."\n"."Enlace Verficacion Firma Digital:".$link;
        $barcodeobj = new TCPDF2DBarcode($cadena, 'QRCODE,M');
            $this->html.='<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
					   "http://www.w3.org/TR/html4/strict.dtd">
					<html>
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
						<title>&nbsp;&nbsp;</title>
						<meta name="author" content="kplian">
					  <link rel="stylesheet" href="../../../sis_ventas_facturacion/control/print.css" type="text/css" media="print" charset="utf-8">
					</head>
					<body>

';
        if ($this->datos[0]['tipo_certificado'] =='General') {
            $this->html .= '';
        }
        $this->html.='<table style="width: 300%;position:relative; top:-40px;" border="0" >
<tbody>
<tr>
<td style="width: 10px;">&nbsp;</td>
<td><p style="text-align: center;"> <FONT FACE="Century Gothic" SIZE=4 ><u><b>CERTIFICADO</b></u></FONT></p></td>
<td style="width: 50px;">&nbsp;</td>
</tr>
<tr>
<td >&nbsp;</td>
<td><p style="text-align: justify"> <FONT FACE="Century Gothic" style="font-size: 12pt;" >La suscrita Lic. '.$this->datos[0]['jefa_recursos'].' <b>Jefe de Recursos Humanos</b> de la Empresa Pública Nacional Estratégica "Boliviana de Aviación - BoA", a solicitud '.$tipo.'</FONT></p>
</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td><FONT FACE="Century Gothic" SIZE=3><b>CERTIFICA:</b></FONT></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td><p style="text-align: justify"><FONT FACE="Century Gothic" style="font-size: 12pt;" >Que, de la revisión de la carpeta que cursa en el Área de Recursos Humanos, se evidencia que '.$gen.' <b>'.$this->datos[0]['genero'].'. '.$this->datos[0]['nombre_funcionario'].'</b> con C.I. '.$this->datos[0]['ci'].' '.$this->datos[0]['expedicion'].', ingresó a la Empresa Pública Nacional Estratégica "Boliviana de Aviación - BoA"
         el '.$this->obtenerFechaEnLetra($this->datos[0]['fecha_contrato']).', y actualmente ejerce el cargo de <b>'.$this->datos[0]['nombre_cargo'].', con Nº de item '.$this->datos[0]['nro_item'].'</b>, dependiente de la '.$this->datos[0]['nombre_unidad'].', con una remuneración mensual de Bs. '.number_format($this->datos[0]['haber_basico'],2,",",".") .'.- ('.$this->datos[0]['haber_literal'].' Bolivianos).</FONT></p>
</td>
<td>&nbsp;</td>
</tr>';
        if (($this->datos[0]['tipo_certificado'] =='Con viáticos de los últimos tres meses') ||
		($this->datos[0]['tipo_certificado'] =='Con viáticos de los últimos tres meses(Factura)')) {
            $this->html .= '<tr>
<td>&nbsp;</td>
<td align="justify">
<FONT FACE="Century Gothic" style="font-size: 12pt;">Asimismo a solicitud expresa se informa que '.$gen.' '.$tra.' ha percibido en los últimos tres meses por concepto de viáticos un promedio mensual de '.number_format($this->datos[0]['importe_viatico'],2,",",".").'.- ('.$this->datos[0]['literal_importe_viatico'].' Bolivianos) aclarándose que el <b>Viático</b> es la suma que reconoce la empresa a la persona comisionada, <b>para cubrir gastos del viaje.</b></FONT>
</td>
<td>&nbsp;</td>
</tr>';
        }
        $this->html.='<tr>
<td>&nbsp;</td>
<td align="justify"><FONT FACE="Century Gothic" style="font-size: 12pt;">Es cuando se certifica, para fines de derecho que convengan '.$tipol.'.<br><br>Cochabamba '.$this->obtenerFechaEnLetra($this->datos[0]['fecha_solicitud']).'.</FONT>
</td>
<td>&nbsp;</td>
</tr>
</tbody>
</table>
<table style="width: 100%;" border="0">
<tbody>
<tr style="height: 80px;">
<br>
<td align="center"> <img src = "../../../reportes_generados/'.$this->codigoQr($cadena,$this->datos[0]['nro_tramite']).'" align= "right " width="90" height="90" title="impreso"/><br><br><FONT FACE="Century Gothic" SIZE=1 >GAG/'.$this->datos[0]['iniciales'].'<br/>Cc/Arch</FONT></td>
<td align="center"  ><img src = "../../../sis_organigrama/media/firma.png" align= "right " width="160" height="120" title="impreso"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
</tr>
<tr style="height: 50px;">
<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td style="center: 38px; width: 20%;"></td>
<td align="right"> </td>
</tr>
</tbody>
</table>	
</body>
</html>';
    $this->writeHTML($this->html);

    }
    function fechaLiteral($va){
        setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
        $fecha = strftime("%d de %B de %Y", strtotime($va));
        return $fecha;
    }
    function obtenerFechaEnLetra($fecha){
        setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
        $dia= date("d", strtotime($fecha));
        $anno = date("Y", strtotime($fecha));
        $mes = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        $mes = $mes[(date('m', strtotime($fecha))*1)-1];
        return $dia.' de '.$mes.' del '.$anno;
    }
    function codigoQr ($cadena,$ruta){
        
        $barcodeobj = new TCPDF2DBarcode($cadena, 'QRCODE,M');        
        $png = $barcodeobj->getBarcodePngData($w = 8, $h = 8, $color = array(0, 0, 0));
        $im = imagecreatefromstring($png);
        if ($im !== false) {
            header('Content-Type: image/png');
            imagepng($im, dirname(__FILE__) . "/../../../reportes_generados/".$ruta.".png");
            imagedestroy($im);
        } else {
            echo 'An error occurred.';
        }
        $url = $ruta.".png";
        return $url;
    }
    public function Footer() {
        $this->SetFontSize(5);
    			$this->setY(-10);
    			$ormargins = $this->getOriginalMargins();
    			$this->SetTextColor(0, 0, 0);
    			//set style for cell border
    			$line_width = 0.85 / $this->getScaleFactor();
    			$this->SetLineStyle(array('width' => $line_width, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
    			$ancho = round(($this->getPageWidth() - $ormargins['left'] - $ormargins['right']) / 3);
    			$this->Ln(2);
    			$cur_y = $this->GetY();    			
    			//$this->Cell($ancho, 0, 'Usuario: '.$_SESSION['_LOGIN'], '', 0, 'L');
    			//$pagenumtxt = 'Página'.' '.$this->getAliasNumPage().' de '.$this->getAliasNbPages();
    			$this->Cell($ancho, 0, $pagenumtxt, '', 0, 'C');
    			$this->Ln($line_width);
    }
    function generarReporte() {
        $this->SetMargins(50,40,25);        
        $this->AddPage();        
        $this->reporteGeneral();
    }
}
?>