<?php
require_once(dirname(__FILE__) . '/../../lib/tcpdf/tcpdf_barcodes_2d.php');
class RCertificadoHtml{
    var $html;
    function generarHtml ($datos) {

        setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
        if ($datos['genero'] == 'Sr'){
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
        $proceso = $datos['id_proceso_wf']; 
        $documento = $datos['id_documento_wf'];
        //$link = $_SERVER['HTTP_HOST'].'/'.ltrim($_SESSION["_FOLDER"], '/').'sis_organigrama/control/LecturaQrFirmDigBoa.php?p='.$proceso.'&d='.$documento;
		$link = 'http://10.150.0.91/kerp_breydi/sis_organigrama/control/LecturaQrFirmDigBoa.php?p='.$proceso.'&d='.$documento.'';
        $cadena = 'Numero Tramite: '.$datos['nro_tramite']."\n".'Fecha Solicitud: '.$datos['fecha_solicitud']."\n".'Funcionario: '.$datos['nombre_funcionario']."\n".'Firmado Por: '.$datos['jefa_recursos']."\n".'Emitido Por: '.$datos['fun_imitido']."\n"."Enlace Verficacion Firma Digital: ".$link;
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
		<br>
		<br><br><br>
';
        if ($datos['tipo_certificado'] =='General') {
            $this->html .= '			
<br>
<br>
<br>';
        }
        $this->html.='<table style="width: 100%;" border="0" >
<tbody>
<tr>
<td style="width: 160px;">&nbsp;</td>
<td><p style="text-align: center;"> <FONT FACE="Century Gothic" SIZE=4 ><u><b>CERTIFICADO</b></u></FONT></p></td>
<td style="width: 50px;">&nbsp;</td>
</tr>
<tr>
<td >&nbsp;</td>
<td><p style="text-align: justify"> <FONT FACE="Century Gothic" style="font-size: 12pt;" >La suscrita Lic. '.$datos['jefa_recursos'].' <b>Jefe de Recursos Humanos</b> de la Empresa Pública Nacional Estratégica "Boliviana de Aviación - BoA", a solicitud '.$tipo.'</FONT></p>
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
<td><p style="text-align: justify"><FONT FACE="Century Gothic" style="font-size: 12pt;" >Que, de la revisión de la carpeta que cursa en el Área de Recursos Humanos, se evidencia que '.$gen.' <b>'.$datos['genero'].'. '.$datos['nombre_funcionario'].'</b> con C.I. '.$datos['ci'].' '.$datos['expedicion'].', ingresó a la Empresa Pública Nacional Estratégica "Boliviana de Aviación - BoA"
         el '.$this->obtenerFechaEnLetra($datos['fecha_contrato']).', y actualmente ejerce el cargo de <b>'.$datos['nombre_cargo'].', con Nº de item '.$datos['nro_item'].'</b>, dependiente de la '.$datos['nombre_unidad'].', con una remuneración mensual de Bs. '.number_format($datos['haber_basico'],2,",",".") .'.- ('.$datos['haber_literal'].' Bolivianos).</FONT></p>
</td>
<td>&nbsp;</td>
</tr>';
        if (($datos['tipo_certificado'] =='Con viáticos de los últimos tres meses') ||
		($datos['tipo_certificado'] =='Con viáticos de los últimos tres meses(Factura)')) {
            $this->html .= '<tr>
<td>&nbsp;</td>
<td align="justify">
<FONT FACE="Century Gothic" style="font-size: 12pt;">Asimismo a solicitud expresa se informa que '.$gen.' '.$tra.' ha percibido en los últimos tres meses por concepto de viáticos un promedio mensual de '.number_format($datos['importe_viatico'],2,",",".").'.- ('.$datos['literal_importe_viatico'].' Bolivianos) aclarándose que el <b>Viático</b> es la suma que reconoce la empresa a la persona comisionada, <b>para cubrir gastos del viaje.</b></FONT>
</td>
<td>&nbsp;</td>
</tr>';
        }

        $this->html.='<tr>
<td>&nbsp;</td>
<td align="justify"><FONT FACE="Century Gothic" style="font-size: 12pt;">Es cuando se certifica, para fines de derecho que convengan '.$tipol.'.<br><br>Cochabamba '.$this->obtenerFechaEnLetra($datos['fecha_solicitud']).'.</FONT>
</td>
<td>&nbsp;</td>
</tr>
</tbody>
</table>
<table style="width: 100%;" border="0">
<tbody>
<tr style="height: 80px;">

<td></td>
<td align="left"> <img src = "../../../reportes_generados/'.$this->codigoQr ($cadena,$datos['nro_tramite']).'" align= "right " width="90" height="90" title="impreso"/><br><br><FONT FACE="Century Gothic" SIZE=1 >GAG/'.$datos['iniciales'].'<br/>Cc/Arch</FONT></td>
<td align="center"  ><img src = "../../../sis_organigrama/media/firma.png" align= "right " width="160" height="120" title="impreso"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
</tr>
<tr style="height: 50px;">

<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td style="center: 38px; width: 20%;"></td>
<td align="right"> </td>
</tr>
</tbody>
</table>

<script language="VBScript">
						Sub Print()
						       OLECMDID_PRINT = 6
						       OLECMDEXECOPT_DONTPROMPTUSER = 2
						       OLECMDEXECOPT_PROMPTUSER = 1
						       call WB.ExecWB(OLECMDID_PRINT, OLECMDEXECOPT_DONTPROMPTUSER,1)
						End Sub
						document.write "<object ID="WB" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></object>"
</script>
						
						<script type="text/javascript"> 
						setTimeout(function(){
							 self.print();
							 
							}, 1000);					
						
						setTimeout(function(){
							 self.close();							 
							}, 2000);	
						</script> 
						
</body>
</html>';


            return  $this->html;
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
}
?>