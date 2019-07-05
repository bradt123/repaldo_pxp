<?php
/**
*@package pXP
*@file gen-EvaluacionDesempenioHistorico.php
*@author  (miguel.mamani)
*@date 08-05-2018 20:39:49
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.EvaluacionDesempenioHistorico=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.EvaluacionDesempenioHistorico.superclass.constructor.call(this,config);
		this.init();

        this.addButton('btnChequeoDocumentosWf',{
            text: 'Documentos',
            grupo: [0,1,2,3,4,5,6,7],
            iconCls: 'bchecklist',
            disabled: true,
            handler: this.loadCheckDocumentosRecWf,
            tooltip: '<b>Documentos del Reclamo</b><br/>Subir los documetos requeridos en el Reclamo seleccionado.'
        });
	},
			
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_evaluacion_desempenio_historico'
			},
			type:'Field',
			form:true 
		},
        {
            //configuracion del componente
            config:{
                labelSeparator:'',
                inputType:'hidden',
                name: 'id_uo_funcionario'
            },
            type:'Field',
            form:true
        },
        {
            //configuracion del componente
            config:{
                labelSeparator:'',
                inputType:'hidden',
                name: 'id_evaluacion_desempenio_padre'
            },
            type:'Field',
            form:true
        },
        {
            //configuracion del componente
            config:{
                labelSeparator:'',
                inputType:'hidden',
                name: 'id_estado_wf'
            },
            type:'Field',
            form:true
        },
        {
            //configuracion del componente
            config:{
                labelSeparator:'',
                inputType:'hidden',
                name: 'id_proceso_wf'
            },
            type:'Field',
            form:true
        },
         {
            config:{
                name: 'cite',
                fieldLabel: 'Nro Cite',
                allowBlank: true,
                anchor: '80%',
                gwidth: 120,
                maxLength:100,
                renderer: function(value, p, record) {
                    return '<tpl for="."><div class="x-combo-list-item"><p><b>'+record.data['cite']+'</b></p></div></tpl>';
                }
            },
            type:'TextField',
            filters:{pfiltro:'hed.cite',type:'string'},
            id_grupo:1,
            grid:true,
            form:true
        },
		{
			config:{
				name: 'gestion',
				fieldLabel: 'Gestion',
				allowBlank: true,
				anchor: '80%',
				gwidth: 50,
				maxLength:10,
                renderer: function(value, p, record) {
                    return '<tpl for="."><div class="x-combo-list-item"><p><b>'+record.data['gestion']+'</b></p></div></tpl>';
                }
			},
				type:'TextField',
				filters:{pfiltro:'hed.gestion',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
        {
            config:{
                name: 'nombre_funcionario',
                fieldLabel: 'Funcionario',
                allowBlank: true,
                anchor: '80%',
                gwidth: 200,
                maxLength:500,
                renderer: function(value, p, record) {
                    return '<tpl for="."><div class="x-combo-list-item"><p><b>'+record.data['nombre_funcionario']+'</b></p></div></tpl>';
                }
            },
            type:'TextField',
            filters:{pfiltro:'fun.desc_funcionario1',type:'string'},
            id_grupo:1,
            grid:true,
            form:true
        },

		{
			config:{
				name: 'nota',
				fieldLabel: 'Nota',
				allowBlank: true,
				anchor: '80%',
				gwidth: 50,
				maxLength:4,
                renderer: function(value, p, record) {
                    return '<tpl for="."><div class="x-combo-list-item"><p><b>'+record.data['nota']+'</b></p></div></tpl>';
                }
			},
				type:'NumberField',
				filters:{pfiltro:'hed.nota',type:'numeric'},
				id_grupo:1,
				grid:true,
				form:true
		},
        {
            config:{
                name: 'cargo_evaluado',
                fieldLabel: 'Cargo',
                allowBlank: true,
                anchor: '80%',
                gwidth: 200,
                maxLength:500,
                renderer: function(value, p, record) {
                    return '<tpl for="."><div class="x-combo-list-item"><p><b>'+record.data['cargo_evaluado']+'</b></p></div></tpl>';
                }
            },
            type:'TextField',
            filters:{pfiltro:'hed.cargo_evaluado',type:'string'},
            id_grupo:1,
            grid:true,
            form:true
        },
        {
            config:{
                name: 'descripcion',
                fieldLabel: 'Descripcion',
                allowBlank: true,
                anchor: '80%',
                gwidth: 100,
                maxLength:500,
                renderer: function(value, p, record) {
                    return '<tpl for="."><div class="x-combo-list-item"><p><b>'+record.data['descripcion']+'</b></p></div></tpl>';
                }
            },
            type:'TextField',
            filters:{pfiltro:'hed.descripcion',type:'string'},
            id_grupo:1,
            grid:true,
            form:true
        },
        {
            config:{
                name: 'fecha_solicitud',
                fieldLabel: 'Fecha Solicitud',
                allowBlank: true,
                anchor: '80%',
                gwidth: 100,
                format: 'd/m/Y',
                renderer:function (value,p,record){
                    return '<tpl for="."><div class="x-combo-list-item"><p><b>'+record.data['fecha_solicitud'].dateFormat('d/m/Y')+'</b></p></div></tpl>';
                }
            },
            type:'DateField',
            filters:{pfiltro:'hed.fecha_solicitud',type:'date'},
            id_grupo:1,
            grid:true,
            form:true
        },
		{
			config:{
				name: 'codigo',
				fieldLabel: 'codigo',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:50
			},
				type:'TextField',
				filters:{pfiltro:'hed.codigo',type:'string'},
				id_grupo:1,
				grid:false,
				form:true
		},
		{
			config:{
				name: 'estado',
				fieldLabel: 'estado',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:100
			},
				type:'TextField',
				filters:{pfiltro:'hed.estado',type:'string'},
				id_grupo:1,
				grid:false,
				form:true
		},
        {
            config:{
                name: 'nro_tramite',
                fieldLabel: 'Nro Tramite',
                allowBlank: true,
                anchor: '80%',
                gwidth: 100,
                maxLength:100
            },
            type:'TextField',
            filters:{pfiltro:'hed.nro_tramite',type:'string'},
            id_grupo:1,
            grid:true,
            form:true
        },
        {
            config:{
                name: 'nombre_funcionaro_mod',
                fieldLabel: 'Modificado Por',
                allowBlank: true,
                anchor: '80%',
                gwidth: 150,
                maxLength:100
            },
            type:'TextField',
            filters:{pfiltro:'un.desc_persona',type:'string'},
            id_grupo:1,
            grid:true,
            form:true
        },
        {
            config:{
                name: 'fecha_modifica',
                fieldLabel: 'Fecha Modificaion',
                allowBlank: true,
                anchor: '80%',
                gwidth: 100,
                format: 'd/m/Y',
                renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
            },
            type:'DateField',
            id_grupo:1,
            grid:true,
            form:true
        },
		{
			config:{
				name: 'estado_reg',
				fieldLabel: 'Estado Reg.',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:10
			},
				type:'TextField',
				filters:{pfiltro:'hed.estado_reg',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'id_usuario_ai',
				fieldLabel: '',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'hed.id_usuario_ai',type:'numeric'},
				id_grupo:1,
				grid:false,
				form:false
		},
		{
			config:{
				name: 'usuario_ai',
				fieldLabel: 'Funcionaro AI',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:300
			},
				type:'TextField',
				filters:{pfiltro:'hed.usuario_ai',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'fecha_reg',
				fieldLabel: 'Fecha creación',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
				type:'DateField',
				filters:{pfiltro:'hed.fecha_reg',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'usr_reg',
				fieldLabel: 'Creado por',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'usu1.cuenta',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'usr_mod',
				fieldLabel: 'Modificado por',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'usu2.cuenta',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'fecha_mod',
				fieldLabel: 'Fecha Modif.',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
				type:'DateField',
				filters:{pfiltro:'hed.fecha_mod',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		}
	],
	tam_pag:50,	
	title:'Evaluación Desempenio Historico',
	ActSave:'../../sis_organigrama/control/EvaluacionDesempenioHistorico/insertarEvaluacionDesempenioHistorico',
	ActDel:'../../sis_organigrama/control/EvaluacionDesempenioHistorico/eliminarEvaluacionDesempenioHistorico',
	ActList:'../../sis_organigrama/control/EvaluacionDesempenioHistorico/listarEvaluacionDesempenioHistorico',
	id_store:'id_evaluacion_desempenio_historico',
	fields: [
		{name:'id_evaluacion_desempenio_historico', type: 'numeric'},
		{name:'gestion', type: 'string'},
		{name:'nota', type: 'numeric'},
		{name:'id_uo_funcionario', type: 'numeric'},
		{name:'codigo', type: 'string'},
		{name:'estado', type: 'string'},
		{name:'fecha_solicitud', type: 'date',dateFormat:'Y-m-d'},
		{name:'cargo_evaluado', type: 'string'},
		{name:'descripcion', type: 'string'},
		{name:'estado_reg', type: 'string'},
		{name:'id_evaluacion_desempenio_padre', type: 'numeric'},
		{name:'id_estado_wf', type: 'numeric'},
		{name:'id_funcionario', type: 'numeric'},
		{name:'id_proceso_wf', type: 'numeric'},
		{name:'nro_tramite', type: 'string'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'usuario_ai', type: 'string'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},

        {name:'nombre_funcionario', type: 'string'},
        {name:'nombre_funcionaro_mod', type: 'string'},
        {name:'fecha_modifica', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
        {name:'cite', type: 'string'}
		
	],
	sortInfo:{
		field: 'id_evaluacion_desempenio_historico',
		direction: 'DESC'
	},
	bdel:false,
	bsave:false,
    bedit:false,
    bnew:false,
    onReloadPage: function (m) {
        this.maestro = m;
        this.store.baseParams = {id_funcionario: this.maestro.id_funcionario,gestion:this.maestro.gestion};
        this.load({params: {start: 0, limit: 50}});

    },
    loadValoresIniciales: function () {
        Phx.vista.EvaluacionDesempenioHistorico.superclass.loadValoresIniciales.call(this);
        this.Cmp.id_funcionario.setValue(this.maestro.id_funcionario);
    },
    loadCheckDocumentosRecWf:function() {
        var rec=this.sm.getSelected();
        rec.data.nombreVista = this.nombreVista;
        Phx.CP.loadWindows('../../../sis_workflow/vista/documento_wf/DocumentoWf.php',
            'Chequear documento del WF',
            {
                width:'90%',
                height:500
            },
            rec.data,
            this.idContenedor,
            'DocumentoWf'
        )
    },
    preparaMenu: function(n)
    {	var rec = this.getSelectedData();
        var tb =this.tbar;

        this.getBoton('btnChequeoDocumentosWf').setDisabled(false);
        Phx.vista.EvaluacionDesempenioHistorico.superclass.preparaMenu.call(this,n);
    },

    liberaMenu:function(){
        var tb = Phx.vista.EvaluacionDesempenioHistorico.superclass.liberaMenu.call(this);
        if(tb){
            this.getBoton('btnChequeoDocumentosWf').setDisabled(true);
        }
        return tb
    }
	}
)
</script>
		
		