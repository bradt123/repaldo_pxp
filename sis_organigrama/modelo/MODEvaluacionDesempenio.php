<?php
/**
*@package pXP
*@file gen-MODEvaluacionDesempenio.php
*@author  (miguel.mamani)
*@date 24-02-2018 20:33:35
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODEvaluacionDesempenio extends MODbase{

	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}
			
	function listarEvaluacionDesempenio(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='orga.ft_evaluacion_desempenio_sel';
		$this->transaccion='MEM_EVD_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion
				
		//Definicion de la lista del resultado del query}
        $this->captura('nombre_unidad','varchar');
        $this->captura('id_uo','int4');
		$this->captura('id_evaluacion_desempenio','int4');
		$this->captura('nro_tramite','varchar');
		$this->captura('id_proceso_wf','int4');
		$this->captura('id_funcionario','int4');
		$this->captura('codigo','varchar');
		$this->captura('estado','varchar');
		$this->captura('nota','int4');
		$this->captura('id_uo_funcionario','int4');
		$this->captura('id_estado_wf','int4');
		$this->captura('descripcion','varchar');
		$this->captura('estado_reg','varchar');
		$this->captura('id_usuario_ai','int4');
		$this->captura('id_usuario_reg','int4');
		$this->captura('fecha_reg','timestamp');
		$this->captura('usuario_ai','varchar');
		$this->captura('fecha_mod','timestamp');
		$this->captura('id_usuario_mod','int4');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');
		$this->captura('nombre_funcionario','text');
		$this->captura('nombre_cargo_evaluado','varchar');
		$this->captura('nombre_cargo_actual_memo','varchar');
		$this->captura('gestion','int4');
		$this->captura('recomendacion','varchar');
		$this->captura('cite','varchar');
		$this->captura('revisado','varchar');
		$this->captura('estado_modificado','varchar');
		$this->captura('email_empresa','varchar');
		
		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		
		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function insertarEvaluacionDesempenio(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='orga.ft_evaluacion_desempenio_ime';
		$this->transaccion='MEM_EVD_INS';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('codigo','codigo','varchar');
		$this->setParametro('estado','estado','varchar');
		$this->setParametro('nota','nota','int4');
        $this->setParametro('id_funcionario','id_funcionario','int4');
		$this->setParametro('id_uo_funcionario','id_uo_funcionario','int4');
		$this->setParametro('id_estado_wf','id_estado_wf','int4');
		$this->setParametro('descripcion','descripcion','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('gestion','gestion','int4');
		$this->setParametro('cargo_memo','cargo_memo','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function modificarEvaluacionDesempenio(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='orga.ft_evaluacion_desempenio_ime';
		$this->transaccion='MEM_EVD_MOD';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_evaluacion_desempenio','id_evaluacion_desempenio','int4');
		$this->setParametro('nro_tramite','nro_tramite','varchar');
		$this->setParametro('id_proceso_wf','id_proceso_wf','int4');
		$this->setParametro('id_funcionario','id_funcionario','int4');
		$this->setParametro('codigo','codigo','varchar');
		$this->setParametro('estado','estado','varchar');
		$this->setParametro('nota','nota','int4');
		$this->setParametro('id_uo_funcionario','id_uo_funcionario','int4');
		$this->setParametro('id_estado_wf','id_estado_wf','int4');
		$this->setParametro('descripcion','descripcion','varchar');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('gestion','gestion','int4');
		$this->setParametro('recomendacion','recomendacion','codigo_html');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
			
	function eliminarEvaluacionDesempenio(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='orga.ft_evaluacion_desempenio_ime';
		$this->transaccion='MEM_EVD_ELI';
		$this->tipo_procedimiento='IME';
				
		//Define los parametros para la funcion
		$this->setParametro('id_evaluacion_desempenio','id_evaluacion_desempenio','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}
    function listaGetDatos(){

        $this->procedimiento ='orga.ft_evaluacion_desempenio_ime';
        $this->transaccion='MEM_EVD_EUG';
        $this->tipo_procedimiento='IME';

        $this->setParametro('id_funcionario','id_funcionario','int4');
        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }
    function listarEvaluacion(){

        $this->procedimiento ='orga.ft_evaluacion_desempenio_sel';
        $this->transaccion='MEM_EVD_REPO';
        $this->tipo_procedimiento='SEL';
        $this->setCount(false);

        $this->setParametro('id_proceso_wf','id_proceso_wf','int4');
        //$this->setParametro('historico','historico','varchar');

        $this->captura('nombre_funcioario','text');
        $this->captura('cargo_evaluado','text');
        $this->captura('genero','varchar');
        $this->captura('nro_tramite','varchar');
        $this->captura('gestion','int4');
        $this->captura('nota','int4');
        $this->captura('descripcion','varchar');
        $this->captura('fecha_solicitud','date');

        $this->captura('iniciales','varchar');
        $this->captura('fun_imitido','varchar');
        $this->captura('recomendacion','varchar');
        $this->captura('cite','varchar');
        $this->captura('correo','varchar');
        $this->captura('fecha_correo','date');
        $this->captura('emisor','varchar');
        $this->captura('plantilla','text');
        $this->captura('ip','varchar');
        $this->captura('fecha_receptor','timestamp');


        //Ejecuta la instruccion
        $this->armarConsulta();//echo ($this->consulta); exit;
        $this->ejecutarConsulta();
       // var_dump($this->respuesta); exit;
        //Devuelve la respuesta
        return $this->respuesta;
    }
    function siguienteEstado()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'orga.ft_evaluacion_desempenio_ime';
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

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }
    function anteriorEstado()
    {
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento = 'orga.ft_evaluacion_desempenio_ime';
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

    function getfuncionario()
    {

        $this->procedimiento ='orga.ft_evaluacion_desempenio_sel';
        $this->transaccion = 'MEM_EVD_SER';
        $this->tipo_procedimiento='SEL';
        $this->setCount(false);

        $this->captura('id_funcionario','int4');
        $this->captura('id_cargo','int4');
        $this->captura('id_uo_funcionario','int4');
        $this->captura('gestion','int4');
        $this->captura('fecha_solicitud','date');
        $this->captura('nombre_funcionario','varchar');
        $this->captura('cargo_funcionario','varchar');
        $this->captura('nota','int4');
        $this->captura('nombre_cargo','varchar');
        $this->captura('jefe','varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }
    function Listarfuncionario()
    {
        $this->procedimiento ='orga.ft_evaluacion_desempenio_sel';
        $this->transaccion = 'MEM_EVD_FUN';
        $this->tipo_procedimiento='SEL';
        $this->setCount(false);
        $this->setParametro('gerencia','gerencia','int4');

        $this->captura('id_funcionario','int4');
        $this->captura('id_uo','int4');
        $this->captura('nombre_unidad','varchar');
        $this->captura('desc_funcionario1','text');
        $this->captura('fecha_finalizacion','date');
        $this->captura('nombre_cargo','varchar');
        $this->captura('email_empresa','varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }
    function ReporteGeneral()
    {
        $this->procedimiento ='orga.ft_evaluacion_desempenio_sel';
        $this->transaccion = 'MEM_EVD_REPG';
        $this->tipo_procedimiento='SEL';
        $this->setCount(false);
        $this->setParametro('id_uo','id_uo','int4');
        $this->setParametro('gestion','gestion','int4');
        $this->setParametro('rango','rango','varchar');

      //  $this->captura('nombre_unidad','varchar');
        $this->captura('id_uo','int4');
        $this->captura('nro_tramite','varchar');
        $this->captura('nota','int4');
        $this->captura('descripcion','varchar');
        $this->captura('desc_funcionario1','text');
        $this->captura('nombre_cargo','varchar');
        $this->captura('gestion','int4');
        $this->captura('recomendacion','varchar');
        $this->captura('cite','varchar');
        $this->captura('genero','varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;

        //Devuelve la respuesta
        return $this->respuesta;
    }
    function cambiarRevision(){

        $this->procedimiento='orga.ft_evaluacion_desempenio_ime';
        $this->transaccion='EVD_REV_IME';
        $this->tipo_procedimiento='IME';

        //Define los parametros para la funcion
        $this->setParametro('id_evaluacion_desempenio','id_evaluacion_desempenio','int4');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }
    function actualizarEvaluacionDesempenio(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='orga.ft_evaluacion_desempenio_ime';
        $this->transaccion='EVD_REV_ACT';
        $this->tipo_procedimiento='IME';

        //Define los parametros para la funcion
        $this->setParametro('id_evaluacion_desempenio','id_evaluacion_desempenio','int4');
        $this->setParametro('id_funcionario','id_funcionario','int4');
        $this->setParametro('codigo','codigo','varchar');
        $this->setParametro('nota','nota','int4');
        $this->setParametro('id_uo_funcionario','id_uo_funcionario','int4');
        $this->setParametro('descripcion','descripcion','varchar');
        $this->setParametro('gestion','gestion','int4');
        $this->setParametro('cargo_memo','cargo_memo','varchar');
        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function traerEvaluaciones (){
        $this->procedimiento='orga.ft_evaluacion_desempenio_ime';
        $this->transaccion='EVD_GE_ACT';
        $this->tipo_procedimiento='IME';

        $this->setParametro('gestion','gestion','int4');
        $this->setParametro('id_uo','id_uo','int4');
        $this->setParametro('funcionarios','funcionarios','text');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;

    }

    function correoFuncoario(){
        $this->procedimiento ='orga.ft_evaluacion_desempenio_ime';
        $this->transaccion = 'EVD_MEM_COR';
        $this->tipo_procedimiento='IME';
        $this->setCount(false);
        $this->setParametro('id_uo','id_uo','int4');
        $this->setParametro('id_funcionario','id_funcionario','int4');
        $this->setParametro('gestion','gestion','int4');
        $this->setParametro('rango','rango','varchar');
        $this->setParametro('link','link','varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //var_dump($this->respuesta); exit;

        //Devuelve la respuesta
        return $this->respuesta;
    }
    function respuestaEmail (){
        $this->procedimiento='orga.ft_evaluacion_desempenio_ime';
        $this->transaccion='EVD_REE_IME';
        $this->tipo_procedimiento='IME';
        //Define los parametros para la funcion
        $this->setParametro('id_proceso_wf','id_proceso_wf','int4');
        $this->setParametro('ip','ip','varchar');
        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //Devuelve la respuesta
        return $this->respuesta;

    }
    function listarConsultaCorreo(){
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento='orga.ft_evaluacion_desempenio_sel';
        $this->transaccion='OR_CCO_SEL';
        $this->tipo_procedimiento='SEL';//tipo de transaccion

        //Definicion de la lista del resultado del query
        $this->captura('id_evaluacion_desempenio','int4');
        $this->captura('cite','varchar');
        $this->captura('estado','varchar');
        $this->captura('nombre_funcionario','text');
        $this->captura('nombre_cargo','text');
        $this->captura('email_empresa','varchar');
        $this->captura('gestion','integer');
        $this->captura('nombre_unidad','varchar');
        $this->captura('nota','int4');
        $this->captura('descripcion','varchar');
        $this->captura('nro_tramite','varchar');
        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //Devuelve la respuesta
        return $this->respuesta;
    }
			
}
?>