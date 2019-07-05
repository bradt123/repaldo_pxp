<?php
/**
*@package pXP
*@file gen-ACTEvaluacionDesempenio.php
*@author  (miguel.mamani)
*@date 24-02-2018 20:33:35
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/
require_once(dirname(__FILE__).'/../reportes/REvaluacionDesempenio.php');
require_once(dirname(__FILE__).'/../reportes/REvaluacionGerencia.php');
require_once(dirname(__FILE__).'/../reportes/REvaluacionDesempenioCorreo.php');
require_once(dirname(__FILE__).'/../reportes/RCorreoRecepcion.php');
class ACTEvaluacionDesempenio extends ACTbase{
    var $ip;
	function listarEvaluacionDesempenio(){
		$this->objParam->defecto('ordenacion','id_evaluacion_desempenio');
        $this->objParam->defecto('dir_ordenacion','asc');
        if ($this->objParam->getParametro('id_gerencia') != ''){
            $this->objParam->addFiltro("ger.id_uo = ".$this->objParam->getParametro('id_gerencia')."and evd.gestion = ".$this->objParam->getParametro('id_gestion'));
        }
        if ($this->objParam->getParametro('pes_estado') == '0_70'){
            $this->objParam->addFiltro("evd.nota >= 0 and evd.nota <= 70");
        }elseif ($this->objParam->getParametro('pes_estado') == '71_80'){
            $this->objParam->addFiltro("evd.nota >= 71 and evd.nota <= 80");
        }elseif ($this->objParam->getParametro('pes_estado') == '81_90'){
            $this->objParam->addFiltro("evd.nota >= 81 and evd.nota <= 90");
        }elseif ($this->objParam->getParametro('pes_estado') == 'glu'){
        	$this->objParam->addFiltro("ger.id_uo = ".$this->objParam->getParametro('id_gerencia')."and evd.gestion = ".$this->objParam->getParametro('id_gestion'));
        }
        else{
            $this->objParam->addFiltro("evd.nota >= 91 and evd.nota <= 100");
        }


		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODEvaluacionDesempenio','listarEvaluacionDesempenio');
		} else{
			$this->objFunc=$this->create('MODEvaluacionDesempenio');
			
			$this->res=$this->objFunc->listarEvaluacionDesempenio($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarEvaluacionDesempenio(){
       // var_dump($this->objParam->getParametro('id_funcionario'));exit;
        $data = array(  "gestion" => $this->objParam->getParametro('gestion'),
                        "id_empleado_erp" => $this->objParam->getParametro('id_funcionario'),
                        "id_uo" => $this->objParam->getParametro('id_uo_funcionario'));
        $data_string = json_encode($data);

        $request = 'http://172.17.59.75/EvaluacionRest/api/EvaluacionRest';
        $ip ='127.0.0.1';
        $token ='871AD980-DFBE-4FAC-81F5-BEF028DF73F3';
        $id_empleado = $this->objParam->getParametro('id_funcionario');


        $session = curl_init($request);
        curl_setopt($session, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($session, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($session, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string),
                'ip: ' . $ip,
                'token: '.$token,
                'id_empleado: '.$id_empleado)
        );

        $result = curl_exec($session);
        curl_close($session);
        $respuesta = json_decode($result,true);
        //var_dump($respuesta["descripcion"]);exit;
        $this->objParam->addParametro('nota', $respuesta["nota"]);
        $this->objParam->addParametro('descripcion', $respuesta["descripcion"]);
        $this->objParam->addParametro('cargo_memo', $respuesta["Cargo"]);
        $this->objParam->addParametro('codigo', $respuesta["codigo"]);
        $this->objFunc=$this->create('MODEvaluacionDesempenio');
		if($this->objParam->insertar('id_evaluacion_desempenio')){
			$this->res=$this->objFunc->insertarEvaluacionDesempenio($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarEvaluacionDesempenio($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarEvaluacionDesempenio(){
			$this->objFunc=$this->create('MODEvaluacionDesempenio');	
		$this->res=$this->objFunc->eliminarEvaluacionDesempenio($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
    function getDatos(){

	    $this->objFunc=$this->create('MODEvaluacionDesempenio');
        $this->res=$this->objFunc->listaGetDatos($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    function ReporteEvaluacioDesempenio (){
        $this->objFunc=$this->create('MODEvaluacionDesempenio');
        $this->res=$this->objFunc->listarEvaluacion($this->objParam);
       
        //obtener titulo del reporte
        $titulo = 'Memo';
        //Genera el nombre del archivo (aleatorio + titulo)
        $nombreArchivo=uniqid(md5(session_id()).$titulo);
        $nombreArchivo.='.pdf';
        $this->objParam->addParametro('orientacion','P');
        $this->objParam->addParametro('tamano','LETTER');
        $this->objParam->addParametro('nombre_archivo',$nombreArchivo);

        $this->objReporteFormato=new REvaluacionDesempenio($this->objParam);
        $this->objReporteFormato->setDatos($this->res->datos);
        $this->objReporteFormato->generarReporte();
        $this->objReporteFormato->output($this->objReporteFormato->url_archivo,'F');
        $this->mensajeExito=new Mensaje();
        $this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado',
            'Se generó con éxito el reporte: '.$nombreArchivo,'control');
        $this->mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());

    }
    function ReporteEvaluacioCorreo (){

        $this->objFunc=$this->create('MODEvaluacionDesempenio');
        $this->res=$this->objFunc->listarEvaluacion($this->objParam);

        //obtener titulo del reporte
        $titulo = 'Correo';
        //Genera el nombre del archivo (aleatorio + titulo)
        $nombreArchivo=uniqid(md5(session_id()).$titulo);
        $nombreArchivo.='.pdf';
        $this->objParam->addParametro('orientacion','P');
        $this->objParam->addParametro('tamano','LETTER');
        $this->objParam->addParametro('nombre_archivo',$nombreArchivo);

        $this->objReporteFormato=new REvaluacionDesempenioCorreo($this->objParam);
        $this->objReporteFormato->setDatos($this->res->datos);
        $this->objReporteFormato->generarReporte();
        $this->objReporteFormato->output($this->objReporteFormato->url_archivo,'F');
        $this->mensajeExito=new Mensaje();
        $this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado',
            'Se generó con éxito el reporte: '.$nombreArchivo,'control');
        $this->mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());

    }
    function ReporteCorreoRecepcion (){

        $this->objFunc=$this->create('MODEvaluacionDesempenio');
        $this->res=$this->objFunc->listarEvaluacion($this->objParam);

        //obtener titulo del reporte
        $titulo = 'Correo';
        //Genera el nombre del archivo (aleatorio + titulo)
        $nombreArchivo=uniqid(md5(session_id()).$titulo);
        $nombreArchivo.='.pdf';
        $this->objParam->addParametro('orientacion','P');
        $this->objParam->addParametro('tamano','LETTER');
        $this->objParam->addParametro('nombre_archivo',$nombreArchivo);

        $this->objReporteFormato=new RCorreoRecepcion($this->objParam);
        $this->objReporteFormato->setDatos($this->res->datos);
        $this->objReporteFormato->generarReporte();
        $this->objReporteFormato->output($this->objReporteFormato->url_archivo,'F');
        $this->mensajeExito=new Mensaje();
        $this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado',
            'Se generó con éxito el reporte: '.$nombreArchivo,'control');
        $this->mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());

    }
    function siguienteEstado()
    {
        $this->objFunc=$this->create('MODEvaluacionDesempenio');
        $this->res=$this->objFunc->siguienteEstado($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    function anteriorEstado()
    {
        $this->objFunc=$this->create('MODEvaluacionDesempenio');
        $this->res=$this->objFunc->anteriorEstado($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    function cambiarRevision()
    {
        $this->objFunc=$this->create('MODEvaluacionDesempenio');
        $this->res=$this->objFunc->cambiarRevision($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    function actualizarEvaluacionDesempenio()
    {
       // var_dump($this->objParam->getParametro('gestion'));exit;

        $data = array(  "gestion" => $this->objParam->getParametro('gestion'),
            "id_empleado_erp" => $this->objParam->getParametro('id_funcionario'),
            "id_uo" => $this->objParam->getParametro('id_uo_funcionario'));
        $data_string = json_encode($data);

        $request = 'http://172.17.59.75/EvaluacionRest/api/EvaluacionRest';
        $ip ='127.0.0.1';
        $token ='871AD980-DFBE-4FAC-81F5-BEF028DF73F3';
        $id_empleado = $this->objParam->getParametro('id_funcionario');

        $session = curl_init($request);
        curl_setopt($session, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($session, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($session, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string),
                'ip: ' . $ip,
                'token: '.$token,
                'id_empleado: '.$id_empleado)
        );
        $result = curl_exec($session);
        curl_close($session);
        $respuesta = json_decode($result,true);
        //var_dump($respuesta["nota"]);exit;
        $this->objParam->addParametro('nota', $respuesta["nota"]);
        $this->objParam->addParametro('descripcion', $respuesta["descripcion"]);
        $this->objParam->addParametro('cargo_memo', $respuesta["Cargo"]);
        $this->objParam->addParametro('codigo', $respuesta["codigo"]);
        $this->objFunc=$this->create('MODEvaluacionDesempenio');
        $this->res=$this->objFunc->actualizarEvaluacionDesempenio($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    function getfuncionario()
    {
        $this->objFunc=$this->create('MODEvaluacionDesempenio');
        $this->res=$this->objFunc->getfuncionario($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    function listarFuncionario(){
        $this->objParam->defecto('ordenacion','desc_funcionario1');
        $this->objParam->defecto('dir_ordenacion','asc');
        $this->objFunc=$this->create('MODEvaluacionDesempenio');
        $this->res=$this->objFunc->Listarfuncionario($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    function ReporteGeneral (){

        $this->objFunc=$this->create('MODEvaluacionDesempenio');
        $this->res=$this->objFunc->ReporteGeneral($this->objParam);

        //obtener titulo del reporte
        $titulo = 'Evaluacion de Desempeño';
        //Genera el nombre del archivo (aleatorio + titulo)
        $nombreArchivo=uniqid(md5(session_id()).$titulo);
        $nombreArchivo.='.pdf';
        $this->objParam->addParametro('orientacion','P');
        $this->objParam->addParametro('tamano','LETTER');
        $this->objParam->addParametro('nombre_archivo',$nombreArchivo);
        //Instancia la clase de pdf

        $this->objReporteFormato=new REvaluacionGerencia($this->objParam);
        $this->objReporteFormato->setDatos($this->res->datos);
        $this->objReporteFormato->generarReporte();
        $this->objReporteFormato->output($this->objReporteFormato->url_archivo,'F');

        $this->mensajeExito=new Mensaje();
        $this->mensajeExito->setMensaje('EXITO','Reporte.php','Reporte generado','Se generó con éxito el reporte: '.$nombreArchivo,'control');
        $this->mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());
    }
    function traerEvaluaciones (){

        $data = array(  "gestion" => $this->objParam->getParametro('gestion'),
            "id_uo" => $this->objParam->getParametro('id_uo'));
        $data_string = json_encode($data);

        $request = 'http://172.17.59.75/EvaluacionRest/api/EvaluacionGerenciaRest';
        $ip ='127.0.0.1';
        $token ='871AD980-DFBE-4FAC-81F5-BEF028DF73F3';
        $id_empleado = '18';

        $session = curl_init($request);
        curl_setopt($session, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($session, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($session, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string),
                'ip: ' . $ip,
                'token: '.$token,
                'id_empleado: '.$id_empleado)
        );
        $result = curl_exec($session);
        curl_close($session);
        $this->objParam->addParametro('funcionarios',$result );
        $this->objFunc=$this->create('MODEvaluacionDesempenio');
        $this->res=$this->objFunc->traerEvaluaciones($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function  correoFuncoario(){
        $this->objParam->addParametro('link', $_SERVER['HTTP_HOST'].'/'.ltrim($_SESSION["_FOLDER"], '/'));
        $this->objFunc=$this->create('MODEvaluacionDesempenio');
        $this->res=$this->objFunc->correoFuncoario($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
    function respuestaEmail(){
        $actual_link = [];
        $this->ip = $this->getIP();
        $this->objParam->addParametro('ip', $this->ip);
        $this->objFunc=$this->create('MODEvaluacionDesempenio');
        $this->res=$this->objFunc->respuestaEmail($this->objParam);

        if ($this->res->getTipo() == 'ERROR') {
            $this->res->imprimirRespuesta($this->res->generarJson());
            exit;
        }
        //resetear parametro
        $this->objParam->parametros_consulta['ordenacion'] = 'prioridad';
        $this->objParam->parametros_consulta['dir_ordenacion'] = 'ASC';
        $this->objParam->parametros_consulta['puntero'] = 0;
        $this->objParam->parametros_consulta['cantidad'] = 100;
        $this->objParam->parametros_consulta['filtro'] = ' 0 = 0 ';

        $this->objFunc=$this->create('MODEvaluacionDesempenio');
        $this->res=$this->objFunc->listarEvaluacion($this->objParam);

        if ($this->res->getTipo() == 'ERROR') {
            $this->res->imprimirRespuesta($this->res->generarJson());
            exit;
        }
        $reporteEvaluacionDesempenio = $this->res->getDatos();
        $actual_link['memo'] = $reporteEvaluacionDesempenio;
        $this->res->setDatos($actual_link);
        $this->res->imprimirRespuesta($this->res->generarJson());

    }
    function getIP() {
        if (isset ( $_SERVER ['HTTP_X_FORWARDED_FOR'] )) {
            $ip = $_SERVER ['HTTP_X_FORWARDED_FOR'];
        } elseif (isset ( $_SERVER ['HTTP_VIA'] )) {
            $ip = $_SERVER ['HTTP_VIA'];
        } elseif (isset ( $_SERVER ['REMOTE_ADDR'] )) {
            $ip = $_SERVER ['REMOTE_ADDR'];
        } else {
            $ip = "desconocida";
        }
        return $ip;

    }
    function listarConsultaCorreo(){
        $this->objParam->defecto('ordenacion','id_evaluacion_desempenio');
        $this->objParam->defecto('dir_ordenacion','asc');

        if ($this->objParam->getParametro('pes_estado') == 'enviado') {
            $this->objParam->addFiltro("evd.estado in (''enviado'')");
        }
        if ($this->objParam->getParametro('pes_estado') == 'revisado') {
            $this->objParam->addFiltro("evd.estado in (''revisado'')");
        }
        if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
            $this->objReporte = new Reporte($this->objParam,$this);
            $this->res = $this->objReporte->generarReporteListado('MODEvaluacionDesempenio','listarConsultaCorreo');
        } else{
            $this->objFunc=$this->create('MODEvaluacionDesempenio');

            $this->res=$this->objFunc->listarConsultaCorreo($this->objParam);
        }
        $this->res->imprimirRespuesta($this->res->generarJson());
    }



}
?>