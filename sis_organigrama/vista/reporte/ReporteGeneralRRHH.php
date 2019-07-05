<?php
/**
 *@package pXP
 *@file    ReporteGlobalAF.php
 *@author  Franklin Espinoza Alvarez
 *@date    23-01-2018
 *@description Archivo con la interfaz para generación de reporte
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.ReporteGeneralRRHH = Ext.extend(Phx.frmInterfaz, {


        constructor : function(config) {
            Phx.vista.ReporteGeneralRRHH.superclass.constructor.call(this, config);
            this.init();
            this.iniciarEventos();
        },

        iniciarEventos : function(){
            this.Cmp.configuracion_reporte.on('select', function (cmb, rec, index) {
                /*if(rec.data.tipo == 'programatica' || rec.data.tipo == 'aguinaldo'){
                    this.Cmp.oficina.setVisible(false);
                    this.Cmp.oficina.reset();
                    this.Cmp.oficina.modificado = true;

                    this.Cmp.tipo_archivo.setVisible(false);
                    this.Cmp.tipo_archivo.reset();
                    this.Cmp.tipo_archivo.modificado = true;
                }else */
                if(rec.data.tipo == 'documentos'){
                    this.Cmp.tipo_archivo.setVisible(true);

                    this.Cmp.oficina.setVisible(false);
                    this.Cmp.oficina.reset();
                    this.Cmp.oficina.modificado = true;
                }
            }, this);
        },
        Atributos : [

            {
                config : {
                    name : 'configuracion_reporte',
                    fieldLabel : 'Tipo Reporte',
                    allowBlank : false,
                    triggerAction : 'all',
                    lazyRender : true,
                    mode : 'local',
                    store : new Ext.data.ArrayStore({
                        fields : ['tipo', 'valor'],
                        data : [['documentos', 'Documentos RRHH']]
                    }),
                    anchor : '70%',
                    valueField : 'tipo',
                    displayField : 'valor'
                },
                type : 'ComboBox',
                id_grupo : 0,
                form : true
            },

            {
                config : {
                    name : 'oficina',
                    fieldLabel : 'Estación',
                    allowBlank : true,
                    emptyText : 'Estación...',
                    hidden: true,
                    store: new Ext.data.JsonStore({
                        url: '../../sis_parametros/control/Lugar/listarLugar',
                        id: 'id_lugar',
                        root: 'datos',
                        fields: ['id_lugar','codigo','nombre'],
                        totalProperty: 'total',
                        sortInfo: {
                            field: 'codigo',
                            direction: 'ASC'
                        },
                        baseParams:{par_filtro:'lug.codigo#lug.nombre', es_regional: 'si', _adicionar:'si'}
                    }),
                    tpl: new Ext.XTemplate([
                        '<tpl for=".">',
                        '<div class="x-combo-list-item">',
                        '<div class="awesomecombo-item {checked}">',
                        '<p><b>Código: {codigo}</b></p>',
                        '</div><p><b>Nombre: </b> <span style="color: green;">{nombre}</span></p>',
                        '</div></tpl>'
                    ]),
                    valueField: 'id_lugar',
                    displayField: 'nombre',
                    forceSelection: false,
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'remote',
                    pageSize: 15,
                    queryDelay: 1000,
                    minChars: 2,
                    width : 408,
                    enableMultiSelect: true
                },

                type : 'AwesomeCombo',
                id_grupo : 0,
                grid : true,
                form : true
            },
            {
                config : {
                    name : 'tipo_archivo',
                    fieldLabel : 'Tipo Documento',
                    allowBlank : false,
                    emptyText : 'Documentos...',
                    hidden: true,
                    store: new Ext.data.JsonStore({
                        url: '../../sis_parametros/control/TipoArchivo/listarTipoArchivo',
                        id: 'id_lugar',
                        root: 'datos',
                        fields: ['id_tipo_archivo','codigo','nombre', 'tipo_archivo'],
                        totalProperty: 'total',
                        sortInfo: {
                            field: 'codigo',
                            direction: 'ASC'
                        },
                        baseParams:{par_filtro:'tipar.codigo#tipar.nombre', tabla: 'orga.tfuncionario'}
                    }),
                    tpl: new Ext.XTemplate([
                        '<tpl for=".">',
                        '<div class="x-combo-list-item">',
                        '<div class="awesomecombo-item {checked}">',
                        '<p><b>Código: {codigo}</b></p>',
                        '</div><p><b>Nombre: </b> <span style="color: green;">{nombre}</span></p>',
                        '<p><b>Tipo: </b> <span style="color: green;">{tipo_archivo}</span></p>',
                        '</div></tpl>'
                    ]),
                    valueField: 'id_tipo_archivo',
                    displayField: 'nombre',
                    forceSelection: false,
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'remote',
                    pageSize: 15,
                    queryDelay: 1000,
                    minChars: 2,
                    width : 408,
                    enableMultiSelect: true
                },

                type : 'AwesomeCombo',
                id_grupo : 0,
                grid : true,
                form : true
            }

        ],
        title : 'Reportes General RRHH',
        ActSave : '../../sis_organigrama/control/Reporte/reporteGeneralRRHH',
        timeout : 1500000,

        topBar : true,
        botones : false,
        labelSubmit : 'Imprimir',
        tooltipSubmit : '<b>Estimado usuario</b><br>Eliga los campos necesario e imprima su reporte.',

        onSubmit:function(o){
            Phx.vista.ReporteGeneralRRHH.superclass.onSubmit.call(this,o);
        },

        /*agregarArgsExtraSubmit: function() {
            this.argumentExtraSubmit.configuracion_reporte = this.Cmp.configuracion_reporte.getValue();
            this.argumentExtraSubmit.tipo_archivo = this.Cmp.tipo_archivo.getValue();
        },*/

        tipo : 'reporte',
        clsSubmit : 'bprint',

        Grupos : [{
            layout : 'column',
            labelAlign: 'top',
            border : false,
            autoScroll: true,
            items : [
                {
                    columnWidth: .5,
                    border: false,
                    //split: true,
                    layout: 'anchor',
                    autoScroll: true,
                    autoHeight: true,
                    collapseFirst : false,
                    collapsible: false,
                    anchor: '100%',
                    items:[
                        {
                            anchor: '100%',
                            bodyStyle: 'padding-right:5px;',
                            autoHeight: true,
                            border: false,
                            items:[
                                {
                                    xtype: 'fieldset',
                                    layout: 'form',
                                    border: true,
                                    title: 'Eliga un tipo de Reporte',
                                    //bodyStyle: 'padding: 5px 10px 10px 10px;',

                                    items: [],
                                    id_grupo: 0
                                }
                            ]
                        }
                    ]
                }
            ]
        }]
    });
</script>