<?php
/**
*@package pXP
*@file gen-MODEvaluacionDesempenioHistorico.php
*@author  (miguel.mamani)
*@date 08-05-2018 20:39:49
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODEvaluacionDesempenioHistorico extends MODbase{
	
	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarEvaluacionDesempenioHistorico(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='orga.ft_evaluacion_desempenio_historico_sel';
		$this->transaccion='MEM_HED_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query
		$this->captura('id_evaluacion_desempenio_historico','int4');
		$this->captura('gestion','int4');
		$this->captura('nota','int4');
		$this->captura('id_uo_funcionario','int4');
		$this->captura('codigo','varchar');
		$this->captura('estado','varchar');
		$this->captura('fecha_solicitud','date');
		$this->captura('cargo_evaluado','varchar');
		$this->captura('descripcion','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('id_evaluacion_desempenio_padre','int4');
		$this->captura('id_estado_wf','int4');
		$this->captura('id_funcionario','int4');
		$this->captura('id_proceso_wf','int4');
		$this->captura('nro_tramite','varchar');
		$this->captura('id_usuario_ai','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_reg','timestamp');
		$this->captura('id_usuario_reg','int4');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_mod','timestamp');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		$this->captura('nombre_funcionario','varchar');
		$this->captura('nombre_funcionaro_mod','varchar');
		$this->captura('fecha_modifica','timestamp');
		$this->captura('cite','varchar');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarEvaluacionDesempenioHistorico(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='orga.ft_evaluacion_desempenio_historico_ime';
		$this->transaccion='MEM_HED_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('gestion','gestion','varchar');
		$this->setParametro('nota','nota','int4');
		$this->setParametro('id_uo_funcionario','id_uo_funcionario','int4');
		$this->setParametro('codigo','codigo','varchar');
		$this->setParametro('estado','estado','varchar');
		$this->setParametro('fecha_solicitud','fecha_solicitud','date');
		$this->setParametro('cargo_memo','cargo_memo','varchar');
		$this->setParametro('descripcion','descripcion','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('id_evaluacion_desempenio_padre','id_evaluacion_desempenio_padre','int4');
		$this->setParametro('id_estado_wf','id_estado_wf','int4');
		$this->setParametro('id_funcionario','id_funcionario','int4');
		$this->setParametro('id_proceso_wf','id_proceso_wf','int4');
		$this->setParametro('nro_tramite','nro_tramite','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarEvaluacionDesempenioHistorico(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='orga.ft_evaluacion_desempenio_historico_ime';
		$this->transaccion='MEM_HED_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_evaluacion_desempenio_historico','id_evaluacion_desempenio_historico','int4');
		$this->setParametro('gestion','gestion','varchar');
		$this->setParametro('nota','nota','int4');
		$this->setParametro('id_uo_funcionario','id_uo_funcionario','int4');
		$this->setParametro('codigo','codigo','varchar');
		$this->setParametro('estado','estado','varchar');
		$this->setParametro('fecha_solicitud','fecha_solicitud','date');
		$this->setParametro('cargo_memo','cargo_memo','varchar');
		$this->setParametro('descripcion','descripcion','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('id_evaluacion_desempenio_padre','id_evaluacion_desempenio_padre','int4');
		$this->setParametro('id_estado_wf','id_estado_wf','int4');
		$this->setParametro('id_funcionario','id_funcionario','int4');
		$this->setParametro('id_proceso_wf','id_proceso_wf','int4');
		$this->setParametro('nro_tramite','nro_tramite','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarEvaluacionDesempenioHistorico(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='orga.ft_evaluacion_desempenio_historico_ime';
		$this->transaccion='MEM_HED_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_evaluacion_desempenio_historico','id_evaluacion_desempenio_historico','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
}
?>