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
set_error_handler('error_handler');
include_once(dirname(__FILE__).'/../../lib/lib_control/CTincludes.php');

//$pxpRestClient = PxpRestClient::connect('10.150.0.91',substr($_SESSION["_FOLDER"], 1) .'pxp/lib/rest/')->setCredentialsPxp($_GET['user'],$_GET['pw']);
//PxpRestClient::connect('erp.obairlines.bo', 'rest/',443,'https')->setCredentialsPxp('notificaciones','Mund0libre');
$pxpRestClient = PxpRestClient::connect('127.0.0.1','kerp_breydi/'.'pxp/lib/rest/')->setCredentialsPxp('notificaciones','Mund0libre');

$res = $pxpRestClient->doPost('organigrama/CertificadoPlanilla/getDocumentReview',
        array('id_proceso_wf' => $_GET['p'],
              'id_documento_wf' => $_GET['d'])
    );    

$res_json = json_decode($res);
$array = json_decode(json_encode($res_json->datos[0]), true);


$base64 = "";
$actSinFirm = array(
    'sinFirm' => array()
    );
$action;

if($array != NULL){
    if (($array['url'] == '' || $array['url'] == null) && ($array['firma_digital'] == '' || $array['firma_digital'] == null )){        
        $action = $pxpRestClient->doPost('organigrama/CertificadoPlanilla/certificadoTrabajoFirmDig');
    }else{
        $action = $actSinFirm;
        $file_pdf= $pxpRestClient->doPost('organigrama/CertificadoPlanilla/getDocument',
        array('pdf' => $array['url']));        

        $base64 = base64_encode($file_pdf);
    }
}
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Visor De Documentos BOA</title>
        <link rel="shortcut icon" href="./../../lib//imagenes/logos/icon.jpg" type="image/x-icon">
        <link rel="stylesheet" type="text/css" href="../src/css/style_pdf_img.css">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/fontawesome.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/brands.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    </head>
    <body>
    
        <div class="ui card" style="top:10px;left:5px;width:25%;">
            <img class="ui rounded image" src="./../../lib//imagenes/icono_awesome/title.jpg">
        </div>        
        <div class="itemh" id="ifram">
        </div>
        <div id="buttons"></div>
        <div id="name_doc">

        </div>
        <div class="item3" id="root-users">
        </div>        
    
     
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/js/fontawesome.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/js/fontawesome.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="//mozilla.github.io/pdf.js/build/pdf.js"></script>
    <?php    
    echo '<script>'
      .'let pdf = "'.$base64.'";'           
      .'let action_req = '.$action.';'      
      .'</script>'
      .'<script src="../src/js/pdf_to_img.js"></script>';
     ?>        
    </body>
    </html>