<?php
class REvaluacionGerencia extends  ReportePDF{

    var  $cite ;

    function Header(){
    }
    public function Footer()
    {
        $this->SetY(-40);
        $this->SetFont('helvetica', 'I', 6);
        $this->Cell(0, 0, 'RCC/gag', 0, 1, 'L');
        $this->Cell(0, 0, 'Cc:AH', 0, 0, 'L');
        $this->Image(dirname(__FILE__) . '/../reportes/firmavb1.jpg', 25, 250, 23);
        $html = $_SERVER['HTTP_HOST'].'/'.ltrim($_SESSION["_FOLDER"], '/').'sis_memos/control/Memo.php?proceso=';
        $style = array(
            'border' => 2,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );

        $this->write2DBarcode($html, 'QRCODE,M', 160, 240, 30, 30, $style, 'N');


    }
    function setDatos($datos) {
        $this->datos = $datos;
		var_dump($this->datos);exit;
    }
    function  reporteGeneralPrimer($genero,$nombre_funcioario,$cargo_evaluado,$gestion,$recomendacion,$nota,$cite){

        $url_imagen = dirname(__FILE__) . '/../../lib/images/Logo-BoA.png';

        $f_actual = date_format(date_create($this->datos[0]["fecha_solicitud"]), 'd/m/Y');
        $nro_cite_dce = $cite;



        $html = <<<EOF
		<style>
		table, th, td {
   			border: 1px solid black;
   			border-collapse: collapse;
   			font-family: "Calibri";
   			font-size: 10pt;
		}
		</style>
		<body>
		<table border="1" cellpadding="1">
        	<tr>
            	<th style="width: 30%" align="center" rowspan="3"><img src="$url_imagen" ></th>
            	<th style="width: 35%" align="center" rowspan="3"><br><h1>MEMORÁNDUM</h1></th>
            	<th style="width: 35%" align="center" colspan="2"><p>R-GG-08 <br>Rev.1-Sep/2012</p>
</th>
        	</tr>
        	<tr>
        	    <td style="width: 10%" align="center">CITE:</td>
        	    <td style="width: 25%" align="center"> $nro_cite_dce </td>
        	</tr>
        	<tr>
        	    <td>FECHA:</td>
        	    <td align="center" >$f_actual</td>
        	</tr>
        </table>
EOF;

        $this->writeHTML($html);

        // var_dump($genero);exit;
        if ($genero == 'M'){
            $gen = 'Señor';
        }else{
            $gen = 'Señora';
        }

        $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $this->SetFont('helvetica','B',11);
        $this->Ln(10);
        $this->Cell(10, 2,'De', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->Cell(30, 2,'       :', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('helvetica','',11);
        $this->Cell(100, 2,'Ronald Salvador Casso Casso', 0, 2, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('helvetica','B',11);
        $this->Cell(100, 2,'GERENTE GENERAL' , 0, 2, 'L', false, '', 0, false, 'T', 'C');
        $this->ln();
        $this->Cell(10, 2,'A', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->Cell(30, 2,'       :', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('helvetica','',11);
        $this->Cell(100, 2,$nombre_funcioario, 0, 2, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('helvetica','B',11);
        $this->Cell(100, 2,$cargo_evaluado , 0, 2, 'L', false, '', 0, false, 'T', 'C');
        $this->ln();
        $this->Cell(15, 2,'Asunto', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->Cell(25, 2,'  :', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('helvetica','B',11);
        $this->Cell(0, 2,'Evaluación del desempeño gestión '.$gestion, 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->ln();
        $style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black');
        $this->Line(30, 100, 185, 100, $style);
        $this->Ln(20);
        $this->SetFont('helvetica','',11);

        if ($nota >= 91 and $nota<= 100) {
            $this->writeHTML('<p>'.$gen.':</p>
       <p align="justica">De acuerdo a normativa vigente tengo a bien comunicar, que habiéndose realizado la Evaluación de <b>Desempeño de la Gestión '.$gestion.'</b>, se evidencia que usted ha obtenido una calificación favorable en los ejes de evaluación: Funciones, Habilidades y Actitudes.</p>
                            <p align="justica">En este sentido, a nombre de Boliviana de Aviación, expreso mis felicitaciones  por su trabajo en Boliviana de Aviación.</p>
                            <p>'.$recomendacion.'</p>
                            <p align="justica">Seguro de que estos resultados obtenidos serán replicados y mejorados en adelante, reciba usted mis consideraciones distinguidas.</p>
                            ',true);
            $this->firmas();
        } elseif ($nota >= 81 and $nota<=90) {
            $this->writeHTML('<p>'.$gen.':</p>
       <p align="justica">De acuerdo a normativa vigente tengo a bien comunicar, que habiéndose realizado la <b>Evaluación de Desempeño de la Gestión '.$gestion.'</b>, se evidencia que usted ha obtenido una calificación favorable en los ejes de evaluación: Funciones, Habilidades y Actitudes.</p>
                            <p align="justica">En este sentido, a nombre de Boliviana de Aviación, expreso mis felicitaciones  por su trabajo en Boliviana de Aviación.  </p>
                            <p>'.$recomendacion.'</p>
                            <p align="justica">Seguro de que estos resultados obtenidos serán replicados y mejorados en adelante, reciba usted mis consideraciones distinguidas.</p>
                            ',true);
            $this->firmas();

        } elseif ($nota >= 0 and $nota <= 80) {
            $this->writeHTML('<p>'.$gen.':</p>
       <p align="justica">De acuerdo a procedimientos establecidos tengo a bien comunicar, que habiéndose realizado la Evaluación de Desempeño de la Gestión '.$gestion.', se evidencia que usted ha obtenido una calificación de <b>suficiente</b> en los ejes de evaluación: Funciones, Habilidades y Actitudes.</p>
                            <p align="justica">En este sentido, se sugiere tomar en cuenta las siguientes observaciones que reflejan la evaluación del desempeño gestión '.$gestion.', a fin de mejorar su desempeño:  </p>
                            <p>'.$recomendacion.'</p>
                            <p align="justica">Seguro de que estas observaciones serán corregidas oportunamente, reciba usted mis consideraciones distinguidas.</p>
                            ',true);
            $this->firmas();
        }


    }
    function firmas (){

        $url_imagen2 = dirname(__FILE__) . '/../reportes/firmarGerencia.png';
        $this->Ln(20);
        $html = <<<EOF
            <table style="height: 140px;" width="407">
<tbody>
<tr>
<td style="width: 195px;"> <br></td>
<td style="width: 196px;">    <br>
	<img src="$url_imagen2" ></td>
</tr>
</tbody>
</table>
           
EOF;


        $this->writeHTML($html);
    }
    function generarReporte() {

        foreach ($this->datos as $value) {
            $this->SetMargins(30,20,30);
            $this->setFontSubsetting(false);
            $this->SetMargins(30,20,30);
            $this->AddPage();
            $this->reporteGeneralPrimer($value['genero'],$value['desc_funcionario1'],$value['nombre_cargo'],$value['gestion'],$value['recomendacion'],$value['nota'],$value['cite']);

        }
    }



}
?>