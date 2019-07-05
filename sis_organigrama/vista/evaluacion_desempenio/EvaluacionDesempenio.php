<?php
/**
*@package pXP
*@file gen-EvaluacionDesempenio.php
*@author  (miguel.mamani)
*@date 24-02-2018 20:33:35
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<style type="text/css" rel="stylesheet">
    .histo {
        background-color: #F1B178 !important;
        color: #090;

    }
</style>
<script>

Phx.vista.EvaluacionDesempenio=Ext.extend(Phx.gridInterfaz,{
    viewConfig: {
        stripeRows: false,
        getRowClass: function(record) {
        	var histori = record.data.estado_modificado;                   
            if(histori != ''){
                return 'histo';           
        	}
        }

    },	
    id_uo: 0,
	constructor:function(config){
        this.initButtons=[this.cmbGerencia, this.cmbGestion];
        this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.EvaluacionDesempenio.superclass.constructor.call(this,config);
		this.init();
		this.gerencias = this.cmbGerencia.getValue();
		this.iniciarEvento();
        this.cmbGestion.on('select', function( combo, record, index){
            this.capturaFiltros();
        },this);
        this.cmbGerencia.on('select', function( combo, record, index){
            this.capturaFiltros();
        },this);

        this.addButton('btnEvaluacionesTodos',
            {
                grupo: [0,1],
                text: 'Traer Evaluaciones',
                iconCls: 'breload2',
                disabled: false,
                handler: this.onEvaluacionesTodos,
                tooltip: 'Traer las evaluaciones por Gerencia'
            }
        );
        this.addButton('btnChequeoDocumentosWf',{
            text: 'Documentos',
            grupo: [0,1,2,3,4,5,6,7],
            iconCls: 'bchecklist',
            disabled: true,
            handler: this.loadCheckDocumentosRecWf,
            tooltip: '<b>Documentos del Reclamo</b><br/>Subir los documetos requeridos en el Reclamo seleccionado.'
        });
        /*this.addButton('ReporteFun',{
            grupo:[0,1],
            text :'Reporte',
            iconCls : 'bpdf32',
            disabled: true,
            handler : this.onButtonReporteFun,
            tooltip : '<b>Reporte de Evaluacion por Funcionario</b>'
        });*/
        /*this.addButton('Report',{
            grupo:[0,1],
            text :'Reporte',
            iconCls : 'bprint',
            disabled: false,
            handler : this.onButtonReporte,
            tooltip : '<b>Reporte Por Gerencia</b>'
        });*/
       this.addButton('CorreoCorreos',{
            grupo:[0,1],
            text :'Correo Gerencias',
            iconCls : 'bemail',
            disabled: false,
            handler : this.onButtonCorreos,
            tooltip : '<b>Enviar Correos por Gerencia</b>'
        });
        this.addButton('CorreoFuncionario',{
            grupo:[0,1],
            text :'Correo Funcionario',
            iconCls : 'bemail',
            disabled: true,
            handler : this.onButtonCorreoFunc,
            tooltip : '<b>Enviar Correos por Funcionario</b>'
        });
       /* this.addButton('btnActEvaluacion',{
            grupo: [0,1,7],
            text: 'Act. Evaluacion',
            iconCls: 'bdocuments',
            disabled: true,
            handler: this.onButtonAcTEvaluacion,
            tooltip: '<b>Actualizar Evaluacion</b>',
            scope:this
        });*/

	},

			
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_evaluacion_desempenio'
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
                name: 'gestion'
            },
            type:'Field',
            form:true
        },
        {
            //configuracion del componente
            config:{
                labelSeparator:'',
                inputType:'hidden',
                name: 'codigo'
            },
            type:'Field',
            form:true
        },
		{
			config:{
				name: 'cite',
				fieldLabel: 'Cite',
				allowBlank: true,
				anchor: '80%',
				gwidth: 120,
				maxLength:100,
                renderer: function(value, p, record) {
                    return '<tpl for="."><div><p><b>'+record.data['cite']+'</b></p><p><b>Estado: <font color="red">'+record.data['estado']+'<font></b></p></div></tpl>';
                }
			},
				type:'TextField',
				filters:{pfiltro:'evd.cite',type:'string'},
				id_grupo:1,
				grid:true,
				form:false,
				bottom_filter:true
		}, {
            config: {
                name: 'id_funcionario',
                fieldLabel: 'Funcionario',
                allowBlank: false,
                emptyText: 'Elija una opción...',
                store: new Ext.data.JsonStore({

                    url: '../../sis_organigrama/control/EvaluacionDesempenio/listarFuncionario',
                    id: 'id_funcionario',
                    root: 'datos',
                    sortInfo: {
                        field: 'desc_funcionario1',
                        direction: 'ASC'
                    },
                    totalProperty: 'total',
                    fields: ['id_funcionario','id_uo','nombre_unidad','desc_funcionario1','nombre_cargo','email_empresa'],
                    remoteSort: true,
                    baseParams: {par_filtro: 'fu.desc_funcionario1'}//#FUNCAR.nombre_cargo
                }),
                valueField: 'id_funcionario',
                displayField: 'desc_funcionario1',
                gdisplayField: 'nombre_funcionario',//corregit materiaesl
                tpl:'<tpl for="."><div class="x-combo-list-item" ><p><font color="green"><b>{desc_funcionario1}</b></font></p><p><b><font color="#4b0082">{nombre_cargo}</font></b><br><b><font color="#a52a2a">{nombre_unidad}</font></b><br><b>Correo: <font color="#b8860b">{email_empresa}</font></b></p></div></tpl>',
                hiddenName: 'id_funcionario',
                forceSelection: true,
                typeAhead: false,
                triggerAction: 'all',
                lazyRender: true,
                mode: 'remote',
                anchor: '60%',
                width: 260,
                gwidth: 210,
                minChars: 2,
                resizable:true,
                listWidth:'330',
                pageSize:50000,
                queryDelay:500,
                renderer: function(value, p, record) {
                    return '<tpl for="."><div><p><b>'+record.data['nombre_funcionario']+'</b><p><b> Correo: <font color="blue">'+record.data['email_empresa']+'</font></b></p><p><font color="#8a2be2"><b>'+record.data['estado_modificado']+'</b></font><p></div></tpl>';
                }

            },
            type: 'ComboBox',
            bottom_filter:true,
            id_grupo:1,
            filters:{
                pfiltro:'f.desc_funcionario1',
                type:'string'
            },
            grid: true,
            form: true
        },
        {
            config:{
                name: 'nombre_cargo_evaluado',
                fieldLabel: 'Cargo Evaluado',
                allowBlank: true,
                anchor: '80%',
                gwidth: 200,
                maxLength:50,
                renderer: function(value, p, record) {
                    return '<tpl for="."><div><p><b>'+record.data['nombre_cargo_evaluado']+'</b><p></div></tpl>';
                }
            },
            type:'TextField',
            filters:{pfiltro:'evd.nombre_cargo_evaluado',type:'string'},
            id_grupo:1,
            grid:true,
            form:false
        },
        {
            config:{
                name: 'nombre_cargo_actual_memo',
                fieldLabel: 'Cargo Actual (Memo)',
                allowBlank: true,
                anchor: '80%',
                gwidth: 200,
                maxLength:50,
                renderer: function(value, p, record) {
                    return '<tpl for="."><div><p><b>'+record.data['nombre_cargo_actual_memo']+'</b><p></div></tpl>';
                }
            },
            type:'TextField',
            filters:{pfiltro:'evd.nombre_cargo_actual_memo',type:'string'},
            id_grupo:1,
            grid:true,
            form:false
        },        
		{
			config:{
				name: 'nota',
				fieldLabel: 'Nota',
				allowBlank: true,
				anchor: '80%',
				gwidth: 60,
				maxLength:4,
                renderer: function(value, p, record) {
                    return '<tpl for="."><div><p><b>'+record.data['nota']+'</b><p></div></tpl>';
                }

			},
				type:'NumberField',
				filters:{pfiltro:'evd.nota',type:'numeric'},
				id_grupo:1,
				grid:true,
				form:false,
				bottom_filter:true
		},
        {
            config:{
                name: 'recomendacion',
                fieldLabel: 'Recomendacion',
                anchor: '90%',
                gwidth: 220,
                height: 300
            },
            type:'TextArea',
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
				gwidth: 200,
				maxLength:500,
                renderer: function(value, p, record) {
                    return '<tpl for="."><div><p><b>'+record.data['descripcion']+'</b><p></div></tpl>';
                }
			},
				type:'TextField',
				filters:{pfiltro:'evd.descripcion',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
        {
            config:{
                name: 'nro_tramite',
                fieldLabel: 'Nro Tramite',
                allowBlank: true,
                anchor: '80%',
                gwidth: 150,
                maxLength:100,
                renderer: function(value, p, record) {
                    return '<tpl for="."><div><p><b>'+record.data['nro_tramite']+'</b></p></div></tpl>';
                }
            },
            type:'TextField',
            filters:{pfiltro:'evd.nro_tramite',type:'string'},
            id_grupo:1,
            grid:true,
            form:false
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
				filters:{pfiltro:'evd.estado_reg',type:'string'},
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
				filters:{pfiltro:'evd.id_usuario_ai',type:'numeric'},
				id_grupo:1,
				grid:false,
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
				name: 'fecha_reg',
				fieldLabel: 'Fecha creación',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
				type:'DateField',
				filters:{pfiltro:'evd.fecha_reg',type:'date'},
				id_grupo:1,
				grid:true,
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
				filters:{pfiltro:'evd.usuario_ai',type:'string'},
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
				filters:{pfiltro:'evd.fecha_mod',type:'date'},
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
		}
	],
	tam_pag:50,	
	title:'Evaluacion Desempenio',
	ActSave:'../../sis_organigrama/control/EvaluacionDesempenio/insertarEvaluacionDesempenio',
	ActDel:'../../sis_organigrama/control/EvaluacionDesempenio/eliminarEvaluacionDesempenio',
	ActList:'../../sis_organigrama/control/EvaluacionDesempenio/listarEvaluacionDesempenio',
	id_store:'id_evaluacion_desempenio',
	fields: [
        {name:'nombre_unidad', type: 'string'},
        {name:'id_uo', type: 'numeric'},
		{name:'id_evaluacion_desempenio', type: 'numeric'},
		{name:'nro_tramite', type: 'string'},
		{name:'id_proceso_wf', type: 'numeric'},
		{name:'id_funcionario', type: 'numeric'},
		{name:'codigo', type: 'string'},
		{name:'estado', type: 'string'},
		{name:'nota', type: 'numeric'},
		{name:'id_uo_funcionario', type: 'numeric'},
		{name:'id_estado_wf', type: 'numeric'},
		{name:'descripcion', type: 'string'},
		{name:'estado_reg', type: 'string'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usuario_ai', type: 'string'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
        {name:'nombre_funcionario', type: 'string'},
        {name:'nombre_cargo_actual_memo',type:'string'},
        {name:'nombre_cargo_evaluado',type:'string'},
        //{name:'cargo_memo', type: 'string'},
        {name:'recomendacion', type: 'string'},
        {name:'cite', type: 'string'},
        {name:'gestion', type: 'numeric'},
        {name:'revisado', type: 'string'},
        {name:'estado_modificado', type: 'string'},
        {name:'email_empresa', type: 'string'}
		
	],
	sortInfo:{
		field: 'id_evaluacion_desempenio',
		direction: 'DESC'
	},
	bdel:false,
	bsave:true,
	bnew:false,
	bedit:true,
    iniciarEvento :function () {

        this.Cmp.id_funcionario.on('select',function(cmb,rec,i){
            Ext.Ajax.request({
                url:'../../sis_organigrama/control/EvaluacionDesempenio/getDatos',
                params:{id_funcionario: this.Cmp.id_funcionario.getValue()},
                success:function(resp){
                    var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
                    var m = this;
                    console.log('id_uo',reg.ROOT.datos.v_id_uo);
                    m.Cmp.id_uo_funcionario.setValue(reg.ROOT.datos.v_id_uo);
                },
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });
        },this);

    },
    preparaMenu: function(n) {
	    var rec = this.getSelectedData();
        var tb =this.tbar;

        //this.getBoton('ReporteFun').setDisabled(false);        
        this.getBoton('CorreoFuncionario').setDisabled(false);
        this.getBoton('btnChequeoDocumentosWf').setDisabled(false);        
        Phx.vista.EvaluacionDesempenio.superclass.preparaMenu.call(this,n);
    },

    liberaMenu:function(){
        var tb = Phx.vista.EvaluacionDesempenio.superclass.liberaMenu.call(this);
        console.log('data',tb);
        if(tb){
            //this.getBoton('ReporteFun').setDisabled(true);
            this.getBoton('CorreoFuncionario').setDisabled(true);
            this.getBoton('btnChequeoDocumentosWf').setDisabled(true);

        }
        return tb
    },

    sigEstado: function() {
        var rec = this.sm.getSelected();

        this.objWizard = Phx.CP.loadWindows('../../../sis_workflow/vista/estado_wf/FormEstadoWf.php',
            'Estado de Wf',
            {
                modal: true,
                width: 700,
                height: 450
            },
            {
                data: {
                    id_estado_wf: rec.data.id_estado_wf,
                    id_proceso_wf: rec.data.id_proceso_wf
                }
            }, this.idContenedor, 'FormEstadoWf',
            {
                config: [{
                    event: 'beforesave',
                    delegate: this.onSaveWizard
                }],
                scope: this
            }
        );
    },

    onSaveWizard:function(wizard,resp){
        var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
        Phx.CP.loadingShow();
        Ext.Ajax.request({
            url:'../../sis_organigrama/control/CertificadoPlanilla/siguienteEstado',
            params:{
                id_proceso_wf_act:  resp.id_proceso_wf_act,
                id_estado_wf_act:   resp.id_estado_wf_act,
                id_tipo_estado:     resp.id_tipo_estado,
                id_funcionario_wf:  resp.id_funcionario_wf,
                id_depto_wf:        resp.id_depto_wf,
                obs:                resp.obs,
                json_procesos:      Ext.util.JSON.encode(resp.procesos)
            },
            success:function (resp) {
                Phx.CP.loadingHide();
                resp.argument.wizard.panel.destroy();
                this.reload();
            },
            failure: this.conexionFailure,
            argument:{wizard:wizard},
            timeout:this.timeout,
            scope:this
        });
    },
    antEstado:function(res){
        var rec=this.sm.getSelected();
        Phx.CP.loadWindows('../../../sis_workflow/vista/estado_wf/AntFormEstadoWf.php',
            'Estado de Wf',
            {
                modal:true,
                width:450,
                height:250
            }, { data:rec.data, estado_destino: res.argument.estado }, this.idContenedor,'AntFormEstadoWf',
            {
                config:[{
                    event:'beforesave',
                    delegate: this.onAntEstado
                }
                ],
                scope:this
            })
    },
    onAntEstado: function(wizard,resp){
        Phx.CP.loadingShow();
        Ext.Ajax.request({
            url:'../../sis_organigrama/control/CertificadoPlanilla/anteriorEstado',
            params:{
                id_proceso_wf: resp.id_proceso_wf,
                id_estado_wf:  resp.id_estado_wf,
                obs: resp.obs,
                estado_destino: resp.estado_destino
            },
            argument:{wizard:wizard},
            success:function (resp) {
                Phx.CP.loadingHide();
                resp.argument.wizard.panel.destroy();
                this.reload();
            },
            failure: this.conexionFailure,
            timeout:this.timeout,
            scope:this
        });
    },
    onButtonNew:function(){
        if(!this.validarFiltros()){
            alert('Especifique la Gerencia y Año')
        }
        else{
            this.accionFormulario = 'NEW';
            Phx.vista.EvaluacionDesempenio.superclass.onButtonNew.call(this);//habilita el boton y se abre
            this.ocultarComponente(this.Cmp.recomendacion);
            this.Cmp.id_funcionario.lastQuery = null;
            this.Cmp.gestion.setValue(this.cmbGestion.getValue());

        }
    },
    onButtonEdit: function() {
        Phx.vista.EvaluacionDesempenio.superclass.onButtonEdit.call(this);
        this.mostrarComponente(this.Cmp.recomendacion);

    },
    capturaFiltros:function(combo, record, index){
        this.desbloquearOrdenamientoGrid();
        if(this.validarFiltros()){

            this.store.baseParams.id_gestion = this.cmbGestion.getValue();
            this.store.baseParams.id_gerencia = this.cmbGerencia.getValue();
            var gerencias;
            gerencias = this.cmbGerencia.getValue();
            console.log('gerencia'+gerencias);
            this.Cmp.id_funcionario.store.baseParams ={par_filtro: 'fu.desc_funcionario1', gerencia:gerencias};
            this.load();
        }

    },

    validarFiltros:function(){

        if(this.cmbGerencia.getValue() && this.cmbGestion.validate() ){

            return true;
        }
        else{

            return false;

        }
    },
    onButtonAct:function(){
        if(!this.validarFiltros()){
            alert('Especifique el Gerencia y Año')
        }
        else{
            this.store.baseParams.id_gestion=this.cmbGestion.getValue();
            this.store.baseParams.id_gerencia = this.cmbGerencia.getValue();
            Phx.vista.EvaluacionDesempenio.superclass.onButtonAct.call(this);
        }
    },
    cmbGerencia: new Ext.form.ComboBox({
        fieldLabel: 'Gerencia',
        grupo:[0,1,2],
        allowBlank: false,
        blankText:'... ?',
        emptyText:'Gerencia...',
        name:'id_uo',
        store:new Ext.data.JsonStore(
            {
                url: '../../sis_organigrama/control/Uo/listarUo',
                id: 'id_uo',
                root: 'datos',
                sortInfo:{
                    field: 'nombre_unidad',
                    direction: 'ASC'
                },
                totalProperty: 'total',
                fields: ['id_uo','codigo','nombre_unidad','nombre_cargo','presupuesta','correspondencia'],
                // turn on remote sorting
                remoteSort: true,
                baseParams:{par_filtro:'nombre_unidad',gerencia: 'si'}
            }),
        valueField: 'id_uo',
        tpl:'<tpl for="."><div class="x-combo-list-item"><p><font color="blue"><b>{nombre_unidad}</b></font></p><p><b>Codigo: <font color="red">{codigo}</font></b></p></div></tpl>',
        triggerAction: 'all',
        displayField: 'nombre_unidad',
        hiddenName: 'id_uo',
        mode:'remote',
        pageSize:50,
        queryDelay:500,
        listWidth:'280',
        width:220

    }),
        cmbGestion: new Ext.form.ComboBox({
            fieldLabel: 'Gestion',
            grupo:[0,1,2],
            allowBlank: false,
            blankText:'... ?',
            emptyText:'Gestion...',
            name:'id_gestion',
            store:new Ext.data.JsonStore(
                {
                    url: '../../sis_parametros/control/Gestion/listarGestion',
                    id: 'id_gestion',
                    root: 'datos',
                    sortInfo:{
                        field: 'gestion',
                        direction: 'DESC'
                    },
                    totalProperty: 'total',
                    fields: ['id_gestion','gestion'],
                    // turn on remote sorting
                    remoteSort: true,
                    baseParams:{par_filtro:'gestion'}
                }),
            valueField: 'gestion',
            triggerAction: 'all',
            displayField: 'gestion',
            hiddenName: 'id_gestion',
            mode:'remote',
            pageSize:50,
            queryDelay:500,
            listWidth:'280',
            width:80
        }),
    onButtonAcTEvaluacion: function(record){
        Phx.CP.loadingShow();
        var rec=this.sm.getSelected();
        Ext.Ajax.request({
            url:'../../sis_organigrama/control/EvaluacionDesempenio/actualizarEvaluacionDesempenio',
            params:{id_evaluacion_desempenio: rec.data.id_evaluacion_desempenio,
                id_funcionario:rec.data.id_funcionario,
                id_uo_funcionario: rec.data.id_uo_funcionario,
                gestion: rec.data.gestion
            },
            success: this.successSinc,
            failure: this.conexionFailure,
            timeout: this.timeout,
            scope: this
        });
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
        );
        this.reload();
    },
    onEvaluacionesTodos :function () {
        Phx.CP.loadingShow();
        Ext.Ajax.request({
            url:'../../sis_organigrama/control/EvaluacionDesempenio/traerEvaluaciones',
            params:{gestion: this.cmbGestion.getValue(),
                id_uo:this.cmbGerencia.getValue()
            },
            success: this.successSinc,
            failure: this.conexionFailure,
            timeout: this.timeout,
            scope: this
        });
    },
    successSinc: function(resp) {
        Phx.CP.loadingHide();
        var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
        this.reload();
    
    }
    }
)
</script>
		
		