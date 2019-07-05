<?php
/**
 *@package      pXP
 *@file         ACTReporte.php
 *@author       (fea)
 *@date         19-04-2018 16:05:34
 *@description  Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
 */
require_once(dirname(__FILE__).'/../reportes/RCorreosDeEmpleadosXls.php');
require_once(dirname(__FILE__).'/../reportes/RCumpleanosEmpleadosXls.php');
require_once(dirname(__FILE__).'/../reportes/RDocumentosRHDXls.php');

class ACTReporte extends ACTbase{

    //reporte formato excel de los correos de los empleados activos de planta o eventuales
    function reporteCorreosEmpleadosBoa(){

        $this->objFunc=$this->create('MODReporte');
        $this->res=$this->objFunc->reporteCorreosEmpleadosBoa($this->objParam);

        $this->datos=$this->res->getDatos();
        $titulo_archivo = 'Lista de Correos';
        $nombreArchivo = uniqid(md5(session_id()).$titulo_archivo).'.xls';
        $this->objParam->addParametro('nombre_archivo',$nombreArchivo);
        $this->objParam->addParametro('titulo_archivo',$titulo_archivo);
        $this->objParam->addParametro('datos',$this->datos);

        $this->objReporte = new RCorreosDeEmpleadosXls($this->objParam);
        $this->objReporte->generarReporte();


        $mensajeExito = new Mensaje();
        $mensajeExito->setMensaje('EXITO', 'Reporte.php', 'Reporte generado', 'Se generó con éxito el reporte: ' . $nombreArchivo, 'control');
        $mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->res = $mensajeExito;
        $this->res->imprimirRespuesta($this->res->generarJson());
    }


    //reporte formato excel de los cumpleañeros de los empleados activos de planta o eventuales
    function reporteCumpleEmpleadosBoa(){

        //var_dump($this->objParam->getParametro('id_gestion'));var_dump($this->objParam->getParametro('id_periodo'));exit;
        $this->objFunc=$this->create('MODReporte');
        $this->res=$this->objFunc->reporteCumpleEmpleadosBoa($this->objParam);

        $this->datos=$this->res->getDatos();
        $titulo_archivo = 'Lista de Cumpleañeros';
        $nombreArchivo = uniqid(md5(session_id()).$titulo_archivo).'.xls';
        $this->objParam->addParametro('nombre_archivo',$nombreArchivo);
        $this->objParam->addParametro('titulo_archivo',$titulo_archivo);
        $this->objParam->addParametro('datos',$this->datos);

        $this->objReporte = new RCumpleanosEmpleadosXls($this->objParam);
        $this->objReporte->generarReporte();


        $mensajeExito = new Mensaje();
        $mensajeExito->setMensaje('EXITO', 'Reporte.php', 'Reporte generado', 'Se generó con éxito el reporte: ' . $nombreArchivo, 'control');
        $mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->res = $mensajeExito;
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function reporteGeneralRRHH(){

        $this->objFunc=$this->create('MODReporte');
        if($this->objParam->getParametro('configuracion_reporte') == 'documentos'){
            $this->res=$this->objFunc->reporteListaDocumentos($this->objParam);
            $titulo_archivo = 'Lista de Documentos';
        }

        $this->datos=$this->res->getDatos();

        $nombreArchivo = uniqid(md5(session_id()).$titulo_archivo).'.xls';
        $this->objParam->addParametro('nombre_archivo',$nombreArchivo);
        $this->objParam->addParametro('titulo_archivo',$titulo_archivo);
        $this->objParam->addParametro('datos',$this->datos);

        if($this->objParam->getParametro('configuracion_reporte') == 'documentos'){
            $this->objReporte = new RDocumentosRHDXls($this->objParam);
        }

        $this->objReporte->generarReporte();


        $mensajeExito = new Mensaje();
        $mensajeExito->setMensaje('EXITO', 'Reporte.php', 'Reporte generado', 'Se generó con éxito el reporte: ' . $nombreArchivo, 'control');
        $mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->res = $mensajeExito;
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

}

?>