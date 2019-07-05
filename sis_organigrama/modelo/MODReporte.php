<?php
/**
 *@package pXP
 *@file gen-MODOficinaCuenta.php
 *@author  (jrivera)
 *@date 31-07-2014 22:57:29
 *@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
 */

class MODReporte extends MODbase{

    function __construct(CTParametro $pParam){
        parent::__construct($pParam);
    }

    function reporteCorreosEmpleadosBoa(){
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento='orga.ft_reporte_sel';
        $this->transaccion='OR_R_MAIL_BOA_SEL';
        $this->tipo_procedimiento='SEL';//tipo de transaccion

        $this->setCount(false);
        $this->setParametro('oficina','oficina','varchar');
        //Definicion de la lista del resultado del query
        $this->captura('gerencia','varchar');
        $this->captura('contrato','varchar');
        $this->captura('desc_funcionario','varchar');
        $this->captura('cargo','varchar');
        $this->captura('lugar','varchar');
        $this->captura('codigo','varchar');
        $this->captura('email_empresa','varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        //echo($this->consulta);exit;
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function reporteCumpleEmpleadosBoa(){
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento='orga.ft_reporte_sel';
        $this->transaccion='OR_R_CUMPLE_BOA_SEL';
        $this->tipo_procedimiento='SEL';//tipo de transaccion

        $this->setCount(false);
        $this->setParametro('id_gestion','id_gestion','integer');
        $this->setParametro('id_periodo','id_periodo','integer');
        $this->setParametro('orden','orden','varchar');

        //Definicion de la lista del resultado del query
        $this->captura('nombre_unidad','varchar');
        $this->captura('desc_func','varchar');
        $this->captura('f_dia','varchar');
        $this->captura('fecha_nacimiento','date');
        $this->captura('nom_cargo','varchar');
        $this->captura('nom_oficina','varchar');
        $this->captura('fecha_contrato','date');

        //Ejecuta la instruccion
        $this->armarConsulta();
        //echo($this->consulta);exit;
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function reporteListaDocumentos(){
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento='orga.ft_reporte_sel';
        $this->transaccion='ORGA_REP_DOC_RH_SEL';
        $this->tipo_procedimiento='SEL';//tipo de transaccion

        $this->setParametro('configuracion_reporte','configuracion_reporte','varchar');
        $this->setParametro('tipo_archivo','tipo_archivo','varchar');

        $this->setCount(false);
        //Definicion de la lista del resultado del query
        $this->captura('gerencia','varchar');
        $this->captura('desc_funcionario','varchar');
        $this->captura('id_funcionario','int4');
        $this->captura('ci','varchar');
        $this->captura('cargo','varchar');
        $this->captura('fecha_ingreso','date');
        $this->captura('documento','varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        //echo($this->consulta);exit;
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }
}
?>