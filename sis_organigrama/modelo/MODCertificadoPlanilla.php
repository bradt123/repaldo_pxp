<?php
/**
*@package pXP
*@file gen-MODCertificadoPlanilla.php
*@author  (miguel.mamani)
*@date 24-07-2017 14:48:34
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODCertificadoPlanilla extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarCertificadoPlanilla(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='orga.ft_certificado_planilla_sel';
		$this->transaccion='OR_PLANC_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
        $this->setParametro('tipo_interfaz','tipo_interfaz','varchar');
		//Definicion de la lista del resultado del query
		$this->captura('id_certificado_planilla','int4');
		$this->captura('tipo_certificado','varchar');
		$this->captura('fecha_solicitud','date');
		$this->captura('beneficiario','varchar');
		$this->captura('id_funcionario','int4');
		$this->captura('estado_reg','varchar');
		$this->captura('importe_viatico','numeric');
		$this->captura('id_usuario_ai','int4');
		$this->captura('fecha_reg','timestamp');
		$this->captura('usuario_ai','varchar');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('id_usuario_mod','int4');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		$this->captura('desc_funcionario1','text');
        $this->captura('nro_tramite','varchar');
        $this->captura('estado','varchar');
        $this->captura('id_proceso_wf','int4');
        $this->captura('id_estado_wf','int4');
        $this->captura('nombre_cargo','varchar');
        $this->captura('ci','varchar');
        $this->captura('haber_basico','numeric');
        $this->captura('expedicion','varchar');
        $this->captura('impreso','varchar');
        $this->captura('control','varchar');
        $this->captura('factura','varchar');
        $this->captura('url','varchar');
        $this->captura('action','varchar');        
        $this->captura('id_documento_wf','integer');
        $this->captura('firma_digital','varchar');
        $this->captura('nombre','varchar');
        $this->captura('extension','varchar');
        $this->captura('habilitado','text');
        $this->captura('chequeado','varchar');


		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		//var_dump($this->respuesta); exit;
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarCertificadoPlanilla(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='orga.ft_certificado_planilla_ime';
		$this->transaccion='OR_PLANC_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('tipo_certificado','tipo_certificado','varchar');
		$this->setParametro('fecha_solicitud','fecha_solicitud','date');
		//$this->setParametro('beneficiario','beneficiario','varchar');
		$this->setParametro('id_funcionario','id_funcionario','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('importe_viatico','importe_viatico','numeric');
		
		$this->setParametro('factura','factura','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarCertificadoPlanilla(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='orga.ft_certificado_planilla_ime';
		$this->transaccion='OR_PLANC_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_certificado_planilla','id_certificado_planilla','int4');
		$this->setParametro('tipo_certificado','tipo_certificado','varchar');
		$this->setParametro('fecha_solicitud','fecha_solicitud','date');
		$this->setParametro('beneficiario','beneficiario','varchar');
		$this->setParametro('id_funcionario','id_funcionario','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('importe_viatico','importe_viatico','numeric');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarCertificadoPlanilla(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='orga.ft_certificado_planilla_ime';
		$this->transaccion='OR_PLANC_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_certificado_planilla','id_certificado_planilla','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
    function siguienteEstado()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'orga.ft_certificado_planilla_ime';
        $this->transaccion = 'OR_SIGUE_EMI';
        $this->tipo_procedimiento = 'IME';

        //Define los parametros para la funcion
        $this->setParametro('id_proceso_wf_act', 'id_proceso_wf_act', 'int4');
        $this->setParametro('id_estado_wf_act', 'id_estado_wf_act', 'int4');
        $this->setParametro('id_tipo_estado', 'id_tipo_estado', 'int4');
        $this->setParametro('id_funcionario_wf', 'id_funcionario_wf', 'int4');
        $this->setParametro('id_depto_wf', 'id_depto_wf', 'int4');
        $this->setParametro('obs', 'obs', 'text');
        $this->setParametro('json_procesos', 'json_procesos', 'text');
		$this->setParametro('factura','factura','varchar');	
		$this->setParametro('tipo_certificado','tipo_certificado','varchar');		
		
        //Ejecuta la instruccion
        $this->armarConsulta();		
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }
    function anteriorEstado()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'orga.ft_certificado_planilla_ime';
        $this->transaccion = 'OR_ANTE_IME';
        $this->tipo_procedimiento = 'IME';

        //Define los parametros para la funcion
        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');
        $this->setParametro('id_estado_wf', 'id_estado_wf', 'int4');
        $this->setParametro('obs', 'obs', 'text');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }
    function reporteCertificadoHtml()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'orga.ft_certificado_planilla_sel';
        $this->transaccion = 'OR_CERT_HTM';
        $this->tipo_procedimiento = 'SEL';

        //Define los parametros para la funcion
        $this->setCount(false);
        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');
        $this->setParametro('id_usuario','id_usuario','int4');
        $this->setParametro('impreso','impreso','varchar');

        $this->captura('nombre_funcionario','text');
        $this->captura('nombre_cargo','varchar');
        $this->captura('fecha_contrato','date');
        $this->captura('haber_basico','numeric');
        $this->captura('ci','varchar');
        $this->captura('expedicion','varchar');
        $this->captura('genero','varchar');
        $this->captura('fecha_solicitud','date');
        $this->captura('nombre_unidad','varchar');
        $this->captura('haber_literal','varchar');
        $this->captura('jefa_recursos','text');
        $this->captura('tipo_certificado','varchar');
        $this->captura('importe_viatico','numeric');
        $this->captura('literal_importe_viatico','varchar');
        $this->captura('nro_tramite','varchar');
        $this->captura('iniciales','varchar');
        $this->captura('fun_imitido','varchar');
        $this->captura('estado','varchar');
        $this->captura('nro_item','varchar');
        $this->captura('id_proceso_wf','integer');
        $this->captura('id_documento_wf','integer');
        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
      //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }

    function reporteCertificado()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'orga.ft_certificado_planilla_sel';
        $this->transaccion = 'OR_CERT_REP';
        $this->tipo_procedimiento = 'SEL';

        //Define los parametros para la funcion
        $this->setCount(false);
        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'int4');
        $this->setParametro('id_usuario','id_usuario','int4');


        $this->captura('nombre_funcionario','text');
        $this->captura('nombre_cargo','varchar');
        $this->captura('fecha_contrato','date');
        $this->captura('haber_basico','numeric');
        $this->captura('ci','varchar');
        $this->captura('expedicion','varchar');
        $this->captura('genero','varchar');
        $this->captura('fecha_solicitud','date');
        $this->captura('nombre_unidad','varchar');
        $this->captura('haber_literal','varchar');
        $this->captura('jefa_recursos','text');
        $this->captura('tipo_certificado','varchar');
        $this->captura('importe_viatico','numeric');
        $this->captura('literal_importe_viatico','varchar');
        $this->captura('nro_tramite','varchar');
        $this->captura('iniciales','varchar');
        $this->captura('fun_imitido','varchar');
        $this->captura('estado','varchar');
		$this->captura('nro_item','varchar');
        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
      //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }
    function servicioConsultaDatosFuncionario()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'orga.ft_certificado_planilla_sel';
        $this->transaccion = 'OR_CERT_SER';
        $this->tipo_procedimiento = 'SEL';

        //Define los parametros para la funcion
        $this->setCount(false);
        $this->setParametro('id_funcionario','id_funcionario','int4');

        $this->captura('nro_tramite','varchar');
        $this->captura('nombre_funcionario','text');
        $this->captura('fecha_solicitud','text');
        $this->captura('tipo_certificado','varchar');
        $this->captura('estado','varchar');
        $this->captura('nombre_cargo','varchar');
        $this->captura('remuneracion','numeric');
        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //Devuelve la respuesta
        return $this->respuesta;
    }
    function controlImpreso()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'orga.ft_certificado_planilla_ime';
        $this->transaccion = 'OR_ANTE_CON';
        $this->tipo_procedimiento = 'IME';

        //Define los parametros para la funcion
        $this->setParametro('id_certificado_planilla', 'id_certificado_planilla', 'int4');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }
    function insertDocumentFirmaDigiOrga() {
        $conexion = new conexion();
        $link = $conexion->conectarpdo();
        $copiado = false;        
        try {
            $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);		
            $link->beginTransaction();
                                      
            $this->procedimiento='wf.ft_documento_wf_ime';
            $this->transaccion='WF_DOCWFAR_MOD';
            $this->tipo_procedimiento='IME';
    

            $pdf = $this->objParam->getParametro('pdf');
            $id_documento_wf = $this->objParam->getParametro('id_documento_wf');
            $boa_firm = $this->objParam->getParametro('boa_firm');
            $this->arreglo['extension'] = "pdf";            
                    
            $data = base64_decode($pdf);
            $docname = 'archivo_firmado_'.$id_documento_wf.'.pdf';             
            file_put_contents('/tmp/'.$docname,$data);
            $ruta1 ="/tmp/".$docname;

            $file_name = $this->getFileName2($docname, $id_documento_wf, '', false);            
            //var_dump($file_name);exit;

            $this->aParam->addParametro('id_documento_wf',$id_documento_wf);
            $this->arreglo['id_documento_wf'] = $id_documento_wf;
            $this->setParametro('id_documento_wf', 'id_documento_wf', 'integer');   

            $this->aParam->addParametro('firma_digital', $boa_firm);
            $this->arreglo['firma_digital'] = $boa_firm;
            $this->setParametro('firma_digital', 'firma_digital', 'varchar');
            
            $this->setParametro('extension','extension','varchar');

            $this->aParam->addParametro('name_file', $docname);
            $this->arreglo['name_file'] = $docname;
            $this->setParametro('name_file', 'name_file', 'varchar');            
	                        
            $this->arreglo['file_name'] = './../../../uploaded_files/sis_organigrama/CertificadoPlanilla/'.$file_name[3];
            $this->setParametro('file_name','file_name','varchar');

            $this->arreglo['folder'] = './../../../uploaded_files/sis_organigrama/CertificadoPlanilla/';
            $this->setParametro('folder','folder','varchar');
            
            $this->aParam->addParametro('only_file', $file_name[0]);
            $this->arreglo['only_file'] = $file_name[0];
            $this->setParametro('only_file','only_file','varchar'); 

            //Ejecuta la instruccion
            $this->armarConsulta();
            
            $stmt = $link->prepare($this->consulta);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);				
            $resp_procedimiento = $this->divRespuesta($result['f_intermediario_ime']);
            
            
            if ($resp_procedimiento['tipo_respuesta']=='ERROR') {
                throw new Exception("Error al ejecutar en la bd", 3);
            }
            if($resp_procedimiento['tipo_respuesta'] == 'EXITO'){

                $respuesta = $resp_procedimiento['datos'];                
                //var_dump($respuesta);
                if($respuesta['max_version'] != '0' && $respuesta['url_destino'] != ''){
                    $this->copyFile($respuesta['url_origen'], $respuesta['url_destino'],  $folder = 'historico');                    
                }                                
                $this->copyFile($ruta1, './../../../uploaded_files/sis_organigrama/CertificadoPlanilla/'.$file_name[3]);
             }
             
             $link->commit();
             $this->respuesta=new Mensaje();
             $this->respuesta->setMensaje($resp_procedimiento['tipo_respuesta'],$this->nombre_archivo,$resp_procedimiento['mensaje'],$resp_procedimiento['mensaje_tec'],'base',$this->procedimiento,$this->transaccion,$this->tipo_procedimiento,$this->consulta);
             $this->respuesta->setDatos($respuesta);
         } 
         
            catch (Exception $e) {			
                $link->rollBack();
                $this->respuesta=new Mensaje();
                if ($e->getCode() == 3) {//es un error de un procedimiento almacenado de pxp
                    $this->respuesta->setMensaje($resp_procedimiento['tipo_respuesta'],$this->nombre_archivo,$resp_procedimiento['mensaje'],$resp_procedimiento['mensaje_tec'],'base',$this->procedimiento,$this->transaccion,$this->tipo_procedimiento,$this->consulta);
                } else if ($e->getCode() == 2) {//es un error en bd de una consulta
                    $this->respuesta->setMensaje('ERROR',$this->nombre_archivo,$e->getMessage(),$e->getMessage(),'modelo','','','','');
                } else {//es un error lanzado con throw exception

                    throw new Exception($e->getMessage(), 2);
                }
        }    
        
        return $this->respuesta;        
    }
    function siguiEstadoFirmCertificado () {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'orga.ft_certificado_planilla_ime';
        $this->transaccion = 'OR_SIGUE_EMIFIRM';
        $this->tipo_procedimiento = 'IME';

        //Define los parametros para la funcion        
        $id_docu = $this->objParam->getParametro('id_documento_wf');
        
        $this->aParam->addParametro('id_doc_wf',$id_docu);
        $this->arreglo['id_doc_wf'] = $id_docu;
        $this->setParametro('id_doc_wf', 'id_doc_wf', 'integer');   
		
        //Ejecuta la instruccion
        $this->armarConsulta();        		
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;        
    }
    function getDocumentReview(){
        //Definicion de variables para ejecucion del procedimiento
        $this->setCount(false);
        $this->procedimiento = 'orga.ft_certificado_planilla_sel';
        $this->transaccion = 'OR_GET_URL_DOC';
        $this->tipo_procedimiento = 'SEL';

        //Define los parametros para la funcion
        $this->setParametro('id_proceso_wf', 'id_proceso_wf', 'integer');
        $this->setParametro('id_documento_wf', 'id_documento_wf', 'integer');        

        $this->captura('url','varchar');
        $this->captura('id_documento_wf', 'int4');
        $this->captura('extension','varchar');
        $this->captura('pdf', 'varchar');
        $this->captura('action', 'varchar');
        $this->captura('firma_digital', 'varchar');
        $this->captura('chequeado', 'varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();        
        $this->ejecutarConsulta();
        //Devuelve la respuesta
        return $this->respuesta; 
    }	
    function getDocument(){               
        $pdf = $this->objParam->getParametro('pdf');        
        $fsize = filesize($pdf);        
        $handle = fopen($pdf, "rb");
        $contents = fread($handle, $fsize);                
        header('content-type: application/pdf');
        header('Content-Length: '.$fsize);
        $resp = $contents;        
        return  $resp;  
    }    


}
?>