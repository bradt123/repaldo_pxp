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
    Phx.vista.CumpleEmpBoa = Ext.extend(Phx.frmInterfaz, {


        constructor : function(config) {
            Phx.vista.CumpleEmpBoa.superclass.constructor.call(this, config);
            this.init();

            var fecha = new Date();
            Ext.Ajax.request({
                url:'../../sis_parametros/control/Gestion/obtenerGestionByFecha',
                params:{fecha:fecha.getDate()+'/'+(fecha.getMonth()+1)+'/'+fecha.getFullYear()},
                success:function(resp){
                    var reg =  Ext.decode(Ext.util.Format.trim(resp.responseText));
                    this.Cmp.id_gestion.setValue(reg.ROOT.datos.id_gestion);
                    this.Cmp.id_gestion.setRawValue(reg.ROOT.datos.anho);
                    this.Cmp.id_periodo.store.baseParams.id_gestion = reg.ROOT.datos.id_gestion;
                    //this.load({params:{start:0, limit:this.tam_pag}});
                },
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });

            this.iniciarEventos();
        },

        iniciarEventos: function(){

            /*this.Cmp.id_gestion.store.load({
                    params:{start:0, limit:this.tam_pag},
                    scope:this,
                    callback: function (arr,op,suc) {
                        console.log('arr[0].data', arr[0]);
                        //this.Cmp.id_gestion.setValue(arr[0].data);

                        //this.Cmp.id_gestion.fireEvent('select');
                    }
            });*/

            this.Cmp.id_gestion.on('select',function(c,r,i){
                this.Cmp.id_periodo.reset();
                this.Cmp.id_periodo.store.baseParams.id_gestion = r.data.id_gestion;
            },this);
        },

        Atributos : [

            {
                config:{
                    name : 'id_gestion',
                    origen : 'GESTION',
                    fieldLabel : 'Gestión',
                    allowBlank : false,
                    gdisplayField : 'gestion',//mapea al store del grid
                    gwidth : 100,
                    //listWidth: 150,
                    renderer : function (value, p, record){return String.format('{0}', record.data['gestion']);}
                },
                type : 'ComboRec',
                id_grupo : 0,
                filters : {
                    pfiltro : 'ges.gestion',
                    type : 'numeric'
                },

                grid : true,
                form : true
            },

            {
                config:{
                    name : 'id_periodo',
                    origen : 'PERIODO',
                    fieldLabel : 'Periodo',
                    allowBlank : false,
                    emptyText: 'Periodo...',
                    gdisplayField : 'periodo',//mapea al store del grid
                    gwidth : 100,
                    //listWidth: 150,
                    renderer : function (value, p, record){return String.format('{0}', record.data['periodo']);}
                },
                type : 'ComboRec',
                id_grupo : 0,
                filters : {
                    pfiltro : 'per.periodo',
                    type : 'numeric'
                },

                grid : true,
                form : true,
                bottom_filter : true
            },
            {
                config: {
                    name: 'orden',
                    fieldLabel: 'Ordenar Por',
                    allowBlank: true,
                    anchor: '40%',
                    gwidth: 100,
                    maxLength: 25,
                    typeAhead:true,
                    forceSelection: true,
                    triggerAction:'all',
                    mode:'local',
                    store:['gerencia', 'nombre_empleado', 'fecha_nacimiento'],
                    //style:'text-transform:uppercase;'
                },
                type: 'ComboBox',
                filters: {pfiltro: 'rec.transito', type: 'string'},
                id_grupo: 2,
                grid: true,
                form: true
            },

        ],
        title : 'Cumpleaños Funcionarios Boa',
        ActSave : '../../sis_organigrama/control/Reporte/reporteCumpleEmpleadosBoa',
        timeout : 1500000,

        topBar : true,
        botones : false,
        labelSubmit : 'Imprimir',
        tooltipSubmit : '<b>Estimado usuario</b><br>Eliga los campos necesario e imprima su reporte.',

        /*onSubmit:function(o){
            Phx.vista.CorreosEmpBoa.superclass.onSubmit.call(this,o);
        },*/

        tipo : 'reporte',
        clsSubmit : 'bprint',

        Grupos : [{
            layout : 'column',
            labelAlign: 'top',
            border : false,
            autoScroll: true,
            //frame:true,
            //bodyStyle: 'padding-right:5px;',
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
                    //bodyStyle: 'padding-right:20px;',
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
                                    title: 'Campos Reporte',
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