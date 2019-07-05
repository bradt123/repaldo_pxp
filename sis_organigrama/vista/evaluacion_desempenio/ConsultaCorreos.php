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
<script>
    Phx.vista.ConsultaCorreos=Ext.extend(Phx.gridInterfaz,{

        constructor:function(config){
            this.maestro=config;
            Phx.vista.ConsultaCorreos.superclass.constructor.call(this,config);
            this.init();
            this.store.baseParams.pes_estado = 'enviado';
            this.load({params: {start: 0, limit: this.tam_pag}});
            this.finCons = true;
        },
        gruposBarraTareas:[
            {name:'enviado',title:'<font color="green"><H1 align="center"><i class="fa fa-list-ul"></i>Envidados</h1></font>',grupo:1,height:0},
            {name:'revisado',title:'<font color="orange"><H1 align="center"><i class="fa fa-list-ul"></i>Abiertos</h1></font>',grupo:1,height:0}
        ],
        actualizarSegunTab: function(name, indice){
            if(this.finCons){
                    this.store.baseParams.pes_estado = name;
                    this.load({params: {start: 0, limit: this.tam_pag}});
            }
        },
        beditGroups: [0,1],
        bdelGroups:  [0,1],
        bactGroups:  [2,1],
        bexcelGroups: [2,1],
        bnewGroups: [0,1],
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
                config:{
                    name: 'cite',
                    fieldLabel: 'cite',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 150,
                    maxLength:100
                },
                type:'TextField',
                filters:{pfiltro:'evd.cite',type:'string'},
                id_grupo:1,
                grid:true,
                form:true,
                bottom_filter:true
            },
            {
                config:{
                    name: 'estado',
                    fieldLabel: 'Estado',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength:100
                },
                type:'TextField',
                filters:{pfiltro:'evd.estado',type:'string'},
                id_grupo:1,
                grid:true,
                form:true,
                bottom_filter:true
            },
            {
                config:{
                    name: 'nombre_funcionario',
                    fieldLabel: 'Funcionario',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 200
                },
                type:'TextField',
                filters:{pfiltro:'f.desc_funcionario1',type:'string'},
                id_grupo:1,
                grid:true,
                form:true,
                bottom_filter:true
            },
            {
                config:{
                    name: 'nombre_cargo',
                    fieldLabel: 'Cargo Evaluado',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 150
                },
                type:'TextField',
                filters:{pfiltro:'evd.cargo_evaluado',type:'string'},
                id_grupo:1,
                grid:true,
                form:true
            },
            {
                config:{
                    name: 'email_empresa',
                    fieldLabel: 'Correo',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 120,
                    maxLength:100
                },
                type:'TextField',
                filters:{pfiltro:'f.email_empresa',type:'string'},
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
                    gwidth: 100,
                    maxLength:4
                },
                type:'NumberField',
                filters:{pfiltro:'evd.gestion',type:'numeric'},
                id_grupo:1,
                grid:true,
                form:true,
                bottom_filter:true
            },
            {
                config:{
                    name: 'nombre_unidad',
                    fieldLabel: 'Gerencia',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 150,
                    maxLength:100
                },
                type:'TextField',
                filters:{pfiltro:'ger.nombre_unidad',type:'string'},
                id_grupo:1,
                grid:true,
                form:true,
                bottom_filter:true
            },
            {
                config:{
                    name: 'nota',
                    fieldLabel: 'Nota',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength:4
                },
                type:'NumberField',
                filters:{pfiltro:'evd.nota',type:'numeric'},
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
                    maxLength:500
                },
                type:'TextField',
                filters:{pfiltro:'evd.descripcion',type:'string'},
                id_grupo:1,
                grid:true,
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
                filters:{pfiltro:'evd.nro_tramite',type:'string'},
                id_grupo:1,
                grid:true,
                form:true,
                bottom_filter:true
            }
        ],
        tam_pag:50,
        title:'Consulta Correo',
        ActList:'../../sis_organigrama/control/EvaluacionDesempenio/listarConsultaCorreo',
        id_store:'id_evaluacion_desempenio',
        fields: [
            {name:'cite', type: 'string'},
            {name:'descripcion', type: 'string'},
            {name:'nro_tramite', type: 'string'},
            {name:'nombre_cargo', type: 'string'},
            {name:'email_empresa', type: 'string'},
            {name:'nombre_unidad', type: 'string'},
            {name:'gestion', type: 'numeric'},
            {name:'nota', type: 'numeric'},
            {name:'estado', type: 'string'},
            {name:'id_evaluacion_desempenio', type: 'numeric'},
            {name:'nombre_funcionario', type: 'string'},
            {name:'usr_reg', type: 'string'},
            {name:'usr_mod', type: 'string'}

        ],
        sortInfo:{
            field: 'id_evaluacion_desempenio',
            direction: 'ASC'
        },
        bdel:false,
        bsave:false,
        bnew:false,
        bedit:false

    })
</script>
