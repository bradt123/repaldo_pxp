<?php
/**
*@package pXP
*@file gen-ACTCertificadoPlanilla.php
*@author  (miguel.mamani)
*@date 24-07-2017 14:48:34
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/
require_once(dirname(__FILE__).'/../reportes/RCertificadoPDF.php');
require_once(dirname(__FILE__).'/../reportes/RCertificadoDOC.php');
require_once(dirname(__FILE__).'/../reportes/RCertificadoHtml.php');
require_once(dirname(__FILE__).'/../reportes/RCertificadoTrabajoPdf.php');
class ACTCertificadoPlanilla extends ACTbase{

	function listarCertificadoPlanilla(){
		$this->objParam->defecto('ordenacion','id_certificado_planilla');


        if($this->objParam->getParametro('tipo_interfaz') == 'CertificadoPlanilla') {

            if ($this->objParam->getParametro('pes_estado') == 'borrador') {            	
                $this->objParam->addFiltro("planc.estado in (''borrador'') and
                extract(year from planc.fecha_reg) = ". $this->objParam->getParametro('gestion'));
            }       
            if ($this->objParam->getParametro('pes_estado') == 'penfirma') {            	
                $this->objParam->addFiltro("planc.estado in (''pendiente_firma'') and
                extract(year from planc.fecha_reg) = ". $this->objParam->getParametro('gestion'));
            }            
            if ($this->objParam->getParametro('pes_estado') == 'emitido') {
                $this->objParam->addFiltro("planc.estado in (''emitido'') and 
				extract(year from planc.fecha_reg) = ". $this->objParam->getParametro('gestion'));                
            }
			if ($this->objParam->getParametro('pes_estado') == 'anulado') {
				$this->objParam->addFiltro("planc.estado in (''anulado'') and 
				extract(year from planc.fecha_reg) = ". $this->objParam->getParametro('gestion'));				
			}					
	        }
       if ($this->objParam->getParametro('tipo_interfaz') == 'CertificadoEmitido'){
			      	            
                $this->objParam->addFiltro("planc.estado in (''emitido'') and 
                extract(year from planc.fecha_reg)=".$this->objParam->getParametro('gestion'));						 			                          
        }
		$this->objParam->defecto('dir_ordenacion','asc');
		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODCertificadoPlanilla','listarCertificadoPlanilla');
		} else{
			$this->objFunc=$this->create('MODCertificadoPlanilla');

			$this->res=$this->objFunc->listarCertificadoPlanilla($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

	function insertarCertificadoPlanilla(){

	   /* if ($this->objParam->getParametro('tipo_certificado') != 'Con viáticos de los últimos tres meses'
            or $this->objParam->getParametro('tipo_certificado') != 'General'){
            throw new Exception('Error no existe el tipo de certificado.');
        }*/
       // var_dump('noe ',$this->objParam->getParametro('tipo_certificado'));exit;
               
	    if(($this->objParam->getParametro('tipo_certificado') == 'Con viáticos de los últimos tres meses')||
		($this->objParam->getParametro('tipo_certificado') == 'Con viáticos de los últimos tres meses(Factura)')) {

            $data = array("empleadoID" => $this->objParam->getParametro('id_funcionario'));
            $data_string = json_encode($data);
            $request = 'http://sms.obairlines.bo/BoAServiceItinerario/servServiceErp.svc/GetPromedioViaticos';
            $session = curl_init($request);
            curl_setopt($session, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($session, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($session, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data_string))
            );
//
            $result = curl_exec($session);
            curl_close($session);
            $respuesta = json_decode($result);
            $resDatos = json_decode($respuesta->GetPromedioViaticosResult, true);
            //var_dump($resDatos["Codigo"]);exit;
            if ($resDatos["Codigo"] == 0) {
                throw new Exception('Error en servicio viaticos.');
            }
            $this->objParam->addParametro('importe_viatico', $resDatos["Resultado"]);
            $this->objFunc = $this->create('MODCertificadoPlanilla');

            if ($this->objParam->insertar('id_certificado_planilla')) {
                $this->res = $this->objFunc->insertarCertificadoPlanilla($this->objParam);
            } else {
                $this->res = $this->objFunc->modificarCertificadoPlanilla($this->objParam);
            }
            $this->res->imprimirRespuesta($this->res->generarJson());
        }else{
            $this->objFunc = $this->create('MODCertificadoPlanilla');

            if ($this->objParam->insertar('id_certificado_planilla')) {
                $this->res = $this->objFunc->insertarCertificadoPlanilla($this->objParam);
            } else {
                $this->res = $this->objFunc->modificarCertificadoPlanilla($this->objParam);
            }
            $this->res->imprimirRespuesta($this->res->generarJson());
        }
	}

	function eliminarCertificadoPlanilla(){
			$this->objFunc=$this->create('MODCertificadoPlanilla');
		$this->res=$this->objFunc->eliminarCertificadoPlanilla($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
    function siguienteEstado()
    {
        $this->objFunc=$this->create('MODCertificadoPlanilla');
        $this->res=$this->objFunc->siguienteEstado($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    function anteriorEstado()
    {
        $this->objFunc=$this->create('MODCertificadoPlanilla');
        $this->res=$this->objFunc->anteriorEstado($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    function reporteCertificado(){


        $this->objFunc=$this->create('MODCertificadoPlanilla');
        $this->res=$this->objFunc->reporteCertificado($this->objParam);

        $nombreArchivo = uniqid(md5(session_id()).'[Reporte.Certificado]').'.pdf';
        $this->objParam->addParametro('orientacion','P');
        $this->objParam->addParametro('tamano','LETTER');
        $this->objParam->addParametro('nombre_archivo',$nombreArchivo);

        //llamar servicio recuperar viaticos


        $this->objReporte = new RCertificadoPDF($this->objParam);
        $this->objReporte->setDatos($this->res->datos);
        $this->objReporte->generarReporte();
        $this->objReporte->output($this->objReporte->url_archivo,'F');


        $this->mensajeExito=new Mensaje();
        $this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado','Se generó con éxito el reporte: '.$nombreArchivo,'control');
        $this->mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());
    }

    function reporteCertificadoDoc(){

        $this->objFunc=$this->create('MODCertificadoPlanilla');
        $dataSource = $this->objFunc->reporteCertificado();
        $this->dataSource=$dataSource->getDatos();
        $nombreArchivo = uniqid(md5(session_id()).'[Certificado-'.$this->dataSource[0]['nro_tramite'].']').'.docx';
       /* $reporte = new RCertificadoDOC($this->objParam);
        $reporte->datosHeader($dataSource->getDatos());
        $reporte->write(dirname(__FILE__).'/../../../reportes_generados/'.$nombreArchivo);*/

        $reporte = new RCertificadoDOC($this->objParam);
        $reporte->datosHeader($this->dataSource);
        $reporte->write(dirname(__FILE__).'/../../../reportes_generados/'.$nombreArchivo);

        $this->mensajeExito=new Mensaje();
        $this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado','Se generó con éxito el reporte: '.$nombreArchivo,'control');
        $this->mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());
    }
    function consultaDatosFuncionario()
    {

        //$this->objParam->addParametro('id_funcionario',$this->objParam->getParametro('id_funcionario'));
        $this->objFunc=$this->create('MODCertificadoPlanilla');
        $this->res=$this->objFunc->servicioConsultaDatosFuncionario($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    function reporteCertificadoHtml(){
        $this->objFunc=$this->create('MODCertificadoPlanilla');
        $this->res=$this->objFunc->reporteCertificadoHtml($this->objParam);
        $datos = $this->res->getDatos();
        $datos = $datos[0];
        $reporte = new RCertificadoHtml();
        $temp = array();
        $temp['html'] = $reporte->generarHtml($datos);
        $this->res->setDatos($temp);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    function controlImpreso()
    {
        $this->objFunc=$this->create('MODCertificadoPlanilla');
        $this->res=$this->objFunc->controlImpreso($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    function certificadoTrabajoFirmDig (){
        $this->objFunc=$this->create('MODCertificadoPlanilla');
        $this->res=$this->objFunc->reporteCertificadoHtml($this->objParam);

        $nombreArchivo = uniqid(md5(session_id()).'[Reporte.Certificado_de_Trabajo]').'.pdf';        
        $this->objParam->addParametro('orientacion','P');
        $this->objParam->addParametro('tamano','LETTER');
        $this->objParam->addParametro('nombre_archivo',$nombreArchivo);

        $this->objReporte = new RCertificadoTrabajoPdf($this->objParam);        
        $this->objReporte->setDatos($this->res->datos);
        $this->objReporte->generarReporte();
        $this->objReporte->output($this->objReporte->url_archivo,'F');


        $this->mensajeExito=new Mensaje();
        $this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado','Se generó con éxito el reporte: '.$nombreArchivo,'control');
        $this->mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());        
    }
	function insertDocumentFirmaDigiOrga(){        
        $this->objFunc=$this->create('MODCertificadoPlanilla');
        $this->res=$this->objFunc->insertDocumentFirmaDigiOrga($this->objParam);        
        $this->res1 = $this->objFunc->siguiEstadoFirmCertificado($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
    }
    function getDocumentReview() {                               
        $this->objFunc=$this->create('MODCertificadoPlanilla');
        $this->res=$this->objFunc->getDocumentReview($this->objParam);        
		$this->res->imprimirRespuesta($this->res->generarJson());
    }
    function getDocument(){               
        $this->objFunc=$this->create('MODCertificadoPlanilla');
        $this->res=$this->objFunc->getDocument($this->objParam);
        echo $this->res;
		//$this->res->imprimirRespuesta($this->res->generarJson());
    } 
    function consulDocument() {                               
        $this->objFunc=$this->create('MODCertificadoPlanilla');
        $this->res=$this->objFunc->consulDocument($this->objParam);        
		$this->res->imprimirRespuesta($this->res->generarJson());
    }           
    function saveDocumentoToSing(){
        $this->objFunc=$this->create('MODCertificadoPlanilla');
        $this->res=$this->objFunc->saveDocumentoToSing($this->objParam);        
		$this->res->imprimirRespuesta($this->res->generarJson());
    }
    function getUrlFirm(){
        $this->objFunc=$this->create('MODCertificadoPlanilla');
        $this->res=$this->objFunc->getUrlFirm($this->objParam);        
		$this->res->imprimirRespuesta($this->res->generarJson());        
    }
}

?>
