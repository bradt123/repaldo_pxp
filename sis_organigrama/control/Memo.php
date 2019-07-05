<?php
include_once(dirname(__FILE__)."/../../lib/lib_control/CTSesion.php");
session_start();
$_SESSION["_SESION"]= new CTSesion();
include(dirname(__FILE__).'/../../lib/DatosGenerales.php');
include_once(dirname(__FILE__).'/../../lib/lib_general/Errores.php');
include_once(dirname(__FILE__).'/../../lib/rest/PxpRestClient.php');
ob_start();
//estable aprametros ce la cookie de sesion
$_SESSION["_CANTIDAD_ERRORES"]=0;//inicia control
register_shutdown_function('fatalErrorShutdownHandler');
set_exception_handler('exception_handler');
set_error_handler('error_handler');;
include_once(dirname(__FILE__).'/../../lib/lib_control/CTincludes.php');
$pxpRestClient = PxpRestClient::connect('erpmobile.obairlines.bo', 'rest/',443,'https')->setCredentialsPxp('notificaciones','Mund0libre');
//$pxpRestClient = PxpRestClient::connect('127.0.0.1',substr($_SESSION["_FOLDER"], 1) .'pxp/lib/rest/')->setCredentialsPxp('notificaciones','Mund0libre');
$res = $pxpRestClient->doPost('organigrama/EvaluacionDesempenio/respuestaEmail',
    array(
        "id_proceso_wf"=>$_GET['proceso'],
        "historico"=>"no"

    ));
$res_json = json_decode($res);
$array = json_decode(json_encode($res_json->datos->memo[0]), true);
//var_dump($array);exit;


class Respuestas extends MYPDF{
    function Header(){
    }

    public function Footer()
    {
        $this->SetY(-55);
        $this->SetFont('helvetica', 'I', 6);
        $this->Cell(0, 0, 'RCC/gag', 0, 1, 'L');
        $this->Cell(0, 0, 'Cc:AH', 0, 0, 'L');
        $this->Image(dirname(__FILE__) . '/../reportes/firmavb1.jpg', 25, 250, 23);

        $html = $_SERVER['HTTP_HOST'].'/'.ltrim($_SESSION["_FOLDER"], '/').'sis_organigrama/control/Memo.php?proceso='.$_GET['proceso'];

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
    function  reporteGeneralPrimer($data){


        //$this->Ln(15);
        $array = $data;
        $url_imagen = dirname(__FILE__) . '/../../lib/imagenes/logos/logo.jpg';
        $f_actual = date_format(date_create($array["fecha_solicitud"]), 'd/m/Y');
        $nro_cite_dce = $array["cite"];


        $html = <<<EOF
		<style>
		table, th, td {
   			border: 1px solid black;
   			border-collapse: collapse;
   			font-family: "Calibri";
   			font-size: 9pt;
		}
		</style>
		<body>
		<table border="1" cellpadding="1">
        	<tr>
            	<th style="width: 25%" align="center" rowspan="3"><img src="$url_imagen" ></th>
            	<th style="width: 44%" align="center" rowspan="3"><br><h1>MEMORÁNDUM</h1></th>
            	<th style="width: 35%" align="center" colspan="2"><p>R-GG-08 <br>Rev.1-Sep/2012</p>
</th>
        	</tr>
        	<tr>
        	    <td style="width: 10%" align="center">CITE:</td>
        	    <td style="width: 25%" align="center"> $nro_cite_dce </td>
        	</tr>
        	<tr>
        	    <td align="center" >Fecha:</td>
        	    <td align="center" >$f_actual</td>
        	</tr>
        </table>
EOF;

        $this->writeHTML($html);



        if ($array["genero"] == 'M'){
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
        $this->Cell(100, 2,$array["nombre_funcioario"], 0, 2, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('helvetica','B',11);
        $this->Cell(100, 2,$array["cargo_evaluado"] , 0, 2, 'L', false, '', 0, false, 'T', 'C');
        $this->ln();
        $this->Cell(15, 2,'Asunto', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->Cell(25, 2,'  :', 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->SetFont('helvetica','B',11);
        $this->Cell(0, 2,'Evaluación del desempeño gestión '.$array["gestion"], 0, 0, 'L', false, '', 0, false, 'T', 'C');
        $this->ln();
        $style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black');
        $this->Line(30, 100, 185, 100, $style);
        $this->Ln(20);
        $this->SetFont('helvetica','',11);

        if ($array["recomendacion"] != ""){
            $recomendacion = ' <p>'.$array["recomendacion"].'</p>';
        }else{
            $recomendacion ='';
        }
		$string = $array['recomendacion'];			
		if($string!=null or $string  != ""){	
			$tmp_arr1= explode("\n\n",trim($string));		 		
			$final_arr=array();
			foreach($tmp_arr1 as $section){					
			    $final_arr[]= explode("\n",$section);		
			}	
			$cont=count($final_arr[0]);	
			if($cont>1){			
			$htmlR="";	
			    $htmlR.="<ol><li>";
				$htmlR.=implode("</li><li>",$final_arr[0]);			
				$htmlR.="</li></ol>";			
			}else if($cont==1){
			$htmlR="";
				foreach($final_arr as $section){
			    $htmlR.="<ol><li>";
			    $htmlR.=implode("</li><li>",$section);
			    $htmlR.="</li></ol>";
			  }		
			}
		}else{
			$htmlR='';
		}		

        if ($array["nota"] >= 91 and $array["nota"]<= 100) {
            $this->writeHTML('<p>'.$gen.':</p>
       <p align="justica">De acuerdo a normativa vigente tengo a bien comunicar, que habiéndose realizado la Evaluación de <b>Desempeño de la Gestión '.$array["gestion"].'</b>, se evidencia que usted ha obtenido una calificación favorable en los ejes de evaluación: Funciones, Habilidades y Actitudes.</p>
                            <p align="justica">En este sentido, a nombre de Boliviana de Aviación, expreso el agradecimiento y felicitación;  por su trabajo tesonero y compromiso con nuestros valores empresariales.</p>
                            <p align = "justica">'.$htmlR.'</p>
                            <p align="justica">Seguro de que estos resultados obtenidos serán replicados y mejorados en adelante, reciba usted mis consideraciones distinguidas.</p>
                            ',true);
            $this->firmas();

        } elseif ($array["nota"] >= 81 and $array["nota"]<=90) {
            $this->writeHTML('<p>'.$gen.':</p>
       <p align="justica">De acuerdo a normativa vigente tengo a bien comunicar, que habiéndose realizado la <b>Evaluación de Desempeño de la Gestión '.$array["gestion"].'</b>, se evidencia que usted ha obtenido una calificación favorable en los ejes de evaluación: Funciones, Habilidades y Actitudes.</p>
                            <p align="justica">En este sentido, a nombre de Boliviana de Aviación, expreso mis felicitaciones  por su trabajo en Boliviana de Aviación.  </p>
                            <p align = "justica">'.$htmlR.'</p>
                            <p align="justica">Seguro de que estos resultados obtenidos serán replicados y mejorados en adelante, reciba usted mis consideraciones distinguidas.</p>
                            ',true);
            $this->firmas();

        } elseif ($array["nota"] >= 0 and $array["nota"] <= 80) {
            $this->writeHTML('<p>'.$gen.':</p>
       <p align="justica">De acuerdo a procedimientos establecidos tengo a bien comunicar, que habiéndose realizado la Evaluación de Desempeño de la Gestión '.$array["gestion"].', se evidencia que usted ha obtenido una calificación de <b>suficiente</b> en los ejes de evaluación: Funciones, Habilidades y Actitudes.</p>
                            <p align="justica">En este sentido, se sugiere tomar en cuenta las siguientes observaciones que reflejan la evaluación del desempeño gestión '.$array["gestion"].', a fin de mejorar su desempeño:  </p>
                            <p align = "justica">'.$htmlR.'</p>
                            <p align="justica">Seguro de que estas observaciones serán corregidas oportunamente, reciba usted mis consideraciones distinguidas.</p>
                            ',true);

            $this->firmas();
        }



    }
    function firmas (){

        $url_imagen2 = dirname(__FILE__) . '/../reportes/firmarGerencia.png';
        $this->Ln(20);
        $html = <<<EOF
            <table border="0">
<tbody>
<tr>
<td style="width: 160px;"> 
</td>
<td style="width: 110px;"> 
	<img src="$url_imagen2" >
</td>

</tr>
</tbody>
</table>
           
EOF;


        $this->writeHTML($html);
    }
    function generarReporte($data) {

        $this->SetMargins(30,20,30);
        $this->setFontSubsetting(false);
        $this->AddPage('P','mm',array(216,279));
        $this->SetMargins(30,20,30);
        $this->reporteGeneralPrimer($data);


    }
}
$b  = new Respuestas();
$b -> generarReporte($array);
$b -> Output('memo.pdf', 'D');
?>

