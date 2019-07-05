<?php
/**
 *@package pXP
 *@file gen-CertificadoPlanilla.php
 *@author  (MMV)
 *@date 24-07-2017 14:48:34
 *@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.RegistroEvaluacion = {
        require: '../../../sis_organigrama/vista/evaluacion_desempenio/EvaluacionDesempenio.php',
        requireclase: 'Phx.vista.EvaluacionDesempenio',
        title: 'EvaluacionDesempenio',
        nombreVista: 'RegistroEvaluacion',
        fwidth: '55%',
        fheight: '60%',
        rango:'',
        constructor: function (config) {
            //this.Atributos[this.getIndAtributo('recomendacion')].form=true;
            this.Atributos.unshift({
                config: {
                    name: 'revisado',
                    fieldLabel: 'Impreso',
                    allowBlank: true,
                    anchor: '50%',
                    gwidth: 80,
                    maxLength: 3,
                    renderer: function (value, p, record, rowIndex, colIndex) {

                        //check or un check row
                        var checked = '',
                            momento = 'no';
                        if (value == 'si') {
                            checked = 'checked';
                        }
                        return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:40px;width:40px;" type="checkbox"  {0}></div>', checked);

                    }
                },
                type: 'TextField',
                filters: {pfiltro: 'sol.revisado', type: 'string'},
                id_grupo: 0,
                grid: true,
                form: false
            }, {
                    config: {
                        name: 'estado',
                        fieldLabel: 'Correo',
                        allowBlank: true,
                        anchor: '50%',
                        gwidth: 80,
                        maxLength: 3,
                        renderer: function (value, p, record) {                        	
                            var result;
                            if(record.data['estado'] == 'borrador') {
                                result = String.format('{0}', "<div style='text-align:center'><img src = '../../../lib/imagenes/icono_dibu/dibu_email.png' align='center' width='35' height='35' title='borrador'/></div>");

                            }else if(record.data['estado'] == 'enviado'){
                                result =  String.format('{0}', "<div style='text-align:center'><img src = '../../../lib/imagenes/icono_dibu/dibu_mail.png' align='center' width='35' height='35' title='enviado'/></div>");
                            }
                            else{
                                result = String.format('{0}', "<div style='text-align:center'><img src = '../../../lib/imagenes/icono_dibu/dibu_end.png' align='center' width='35' height='35' title='recibido'/></div>");

                            }
                            return result;
                        }
                    },
                    type: 'TextField',
                    filters: {pfiltro: 'sol.revisado', type: 'string'},
                    id_grupo: 0,
                    grid: true,
                    form: false
                });
            Phx.vista.RegistroEvaluacion.superclass.constructor.call(this, config);
            this.grid.addListener('cellclick', this.oncellclick,this);
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            this.store.baseParams.pes_estado = '0_70';
            this.finCons = true;
            this.getBoton('CorreoCorreos').setVisible(false);
            this.getBoton('CorreoFuncionario').setVisible(false);
            this.getBoton('edit').setVisible(false);                                              
        },
        gruposBarraTareas:[
            {name:'0_70',title:'<font color="red"><H1 align="center"><i class="fa fa-list-ul"> Malo</i> 0 - 70 </h1></font>',grupo:1,height:0},
            {name:'71_80',title:'<font color="orange"><H1 align="center"><i class="fa fa-list-ul"> Regular</i> 71 - 80 </h1></font>',grupo:1,height:0},
            {name:'81_90',title:'<font color="black"><H1 align="center"><i class="fa fa-list-ul"> Bueno</i> 81 - 90 </h1></font>',grupo:1,height:0},
            {name:'91_100',title:'<font color="green"><H1 align="center"><i class="fa fa-list-ul"> Excelente</i> 91 -100 </h1></font>',grupo:1,height:0},
            {name:'glu',title:'<font color="blue"><H1 align="center"><i class="fa fa-list-ul"> Gerencia</i> Total </h1></font>',grupo:1,height:0},
            
        ],
        actualizarSegunTab: function(name, indice){
              if(this.finCons){
                  if(!this.validarFiltros()){
                      alert('Especifique la Gerencia y Año');
                      this.getBoton('CorreoCorreos').setVisible(false);
                      this.getBoton('CorreoFuncionario').setVisible(false);
                      this.getBoton('edit').setVisible(false);
                  }
                  else {                  	  
                      this.store.baseParams.pes_estado = name;                      
                      if(this.store.baseParams.pes_estado == '0_70'){                      	
                      	this.getBoton('CorreoCorreos').setVisible(false);
                      	this.getBoton('edit').setVisible(false);
                      	this.getBoton('CorreoFuncionario').setVisible(false);                      	                      	                      	
                      }
                      else if(this.store.baseParams.pes_estado == '71_80'){
                      	this.getBoton('edit').setVisible(true);
                      	this.getBoton('CorreoCorreos').setVisible(false);
                      }
                      else if(this.store.baseParams.pes_estado == 'glu'){
                      	this.getBoton('CorreoCorreos').setVisible(false);
                      	this.getBoton('edit').setVisible(false);
                      	this.getBoton('CorreoFuncionario').setVisible(false);
                      	this.getBoton('btnEvaluacionesTodos').setVisible(false);                      	
                      }
                      else{
                      	this.getBoton('edit').setVisible(false);
                      }   
                      this.rango = name;
                      this.load({params: {start: 0, limit: this.tam_pag}});
                  }
              }
          },
          beditGroups: [0,1],
          bdelGroups:  [0,1],
          bactGroups:  [2,1],
          bexcelGroups: [2,1],
          bnewGroups: [0,1],

        preparaMenu:function(n){        	        	
        	var rec = this.getSelectedData();        	
        	var tb =this.tbar;    	            
            Phx.vista.RegistroEvaluacion.superclass.preparaMenu.call(this,n);
            if(rec.nota >= 71 && rec.nota <= 80){
            	if(rec.estado == 'enviado' || rec.estado == 'revisado'){
            		this.getBoton('edit').disable();            		
            	}
            }
            
            return tb;
        },

        liberaMenu:function(){        	        	
            var tb = Phx.vista.RegistroEvaluacion.superclass.liberaMenu.call(this);
            return tb;
        },
        south:{
            url:'../../../sis_organigrama/vista/evaluacion_desempenio_historico/EvaluacionDesempenioHistorico.php',
            title:'Historico',
            height:'50%',
            cls:'EvaluacionDesempenioHistorico',
            collapsed:true
        },
        onButtonReporte:function(){
            var ran= this.rango;
            console.log(ran);
            Ext.Ajax.request({
                url:'../../sis_organigrama/control/EvaluacionDesempenio/ReporteGeneral',
                params:{'id_uo':this.cmbGerencia.getValue(),'gestion':this.cmbGestion.getValue(),rango:ran},
                success: this.successExport,
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });
            this.reload();
        },
        onButtonCorreos : function () {
            Phx.CP.loadingShow();
            var ran= this.rango;
            console.log(ran);
            Ext.Ajax.request({
                url:'../../sis_organigrama/control/EvaluacionDesempenio/correoFuncoario',
                params:{'id_uo':this.cmbGerencia.getValue(),'gestion':this.cmbGestion.getValue(),rango:ran},
                success: this.successSinc,
                failure: this.conexionFailure,
                timeout: this.timeout,
                scope: this
            });
        },
        onButtonCorreoFunc : function () {        	     
        	var ran= this.rango;
        	var rec=this.sm.getSelected(); 
        	       	
        	if(rec.data.nota <=80){
        	 if(rec.data.recomendacion == '' || rec.data.recomendacion.length <= 4){
        		alert(' El usuario: '+rec.data.nombre_funcionario+'\n Tiene nota de: '+rec.data.nota+'\n Es nesecario registrar una recomendacion \n antes de enviar la evaluacion por correo electronico');
        	  }else{
				Phx.CP.loadingShow();                        
	            console.log(ran);            
	            Ext.Ajax.request({
	                url:'../../sis_organigrama/control/EvaluacionDesempenio/correoFuncoario',
	                params:{'id_funcionario':rec.data.id_funcionario,'gestion':this.cmbGestion.getValue(),rango:ran},
	                success: this.successSinc,
	                failure: this.conexionFailure,
	                timeout: this.timeout,
	                scope: this
	            });        	  	        	  	        	  	
        	  }
        	}else{               	 
            Phx.CP.loadingShow();                        
            console.log(ran);            
            Ext.Ajax.request({
                url:'../../sis_organigrama/control/EvaluacionDesempenio/correoFuncoario',
                params:{'id_funcionario':rec.data.id_funcionario,'gestion':this.cmbGestion.getValue(),rango:ran},
                success: this.successSinc,
                failure: this.conexionFailure,
                timeout: this.timeout,
                scope: this
            });
           } 
        },
        onButtonReporteFun:function(){
          //  Phx.CP.loadingShow();
            var rec=this.sm.getSelected();
            Ext.Ajax.request({
                url:'../../sis_organigrama/control/EvaluacionDesempenio/ReporteEvaluacioDesempenio',
                params:{'id_proceso_wf':rec.data.id_proceso_wf,historico:'no'},
                success: this.successExport,
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });
            this.reload();
        },
        oncellclick : function(grid, rowIndex, columnIndex, e) {

            var record = this.store.getAt(rowIndex),
                fieldName = grid.getColumnModel().getDataIndex(columnIndex); // Get field name

            if(fieldName == 'revisado') {
                this.cambiarRevision(record);
            }
        },
        cambiarRevision: function(record){
            Phx.CP.loadingShow();
            var d = record.data;
            Ext.Ajax.request({
                url:'../../sis_organigrama/control/EvaluacionDesempenio/cambiarRevision',
                params:{ id_evaluacion_desempenio: d.id_evaluacion_desempenio},
                success: this.successRevision,
                failure: this.conexionFailure,
                timeout: this.timeout,
                scope: this
            });
        },
        successRevision: function(resp){
            Phx.CP.loadingHide();
            var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
            this.reload();
        }       
    }
</script>

