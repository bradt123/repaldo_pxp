<?php
/**
*@package pXP
*@file gen-ACTEvaluacionDesempenioHistorico.php
*@author  (miguel.mamani)
*@date 08-05-2018 20:39:49
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTEvaluacionDesempenioHistorico extends ACTbase{    
			
	function listarEvaluacionDesempenioHistorico(){
		$this->objParam->defecto('ordenacion','id_evaluacion_desempenio_historico');
		$this->objParam->defecto('dir_ordenacion','asc');
        if($this->objParam->getParametro('id_funcionario') != '') {
            $this->objParam->addFiltro(" hed.id_funcionario = " . $this->objParam->getParametro('id_funcionario')."and hed.gestion =". $this->objParam->getParametro('gestion'));
        }

		if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODEvaluacionDesempenioHistorico','listarEvaluacionDesempenioHistorico');
		} else{
			$this->objFunc=$this->create('MODEvaluacionDesempenioHistorico');
			
			$this->res=$this->objFunc->listarEvaluacionDesempenioHistorico($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
				
	function insertarEvaluacionDesempenioHistorico(){
		$this->objFunc=$this->create('MODEvaluacionDesempenioHistorico');	
		if($this->objParam->insertar('id_evaluacion_desempenio_historico')){
			$this->res=$this->objFunc->insertarEvaluacionDesempenioHistorico($this->objParam);			
		} else{			
			$this->res=$this->objFunc->modificarEvaluacionDesempenioHistorico($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
						
	function eliminarEvaluacionDesempenioHistorico(){
			$this->objFunc=$this->create('MODEvaluacionDesempenioHistorico');	
		$this->res=$this->objFunc->eliminarEvaluacionDesempenioHistorico($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}
			
}

?>