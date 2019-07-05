<?php
/**
 *@package pXP
 *@file Funcionario.php
 *@author KPLIAN (admin)
 *@date 14-02-2011
 *@description  Vista para registrar los datos de un funcionario
 */

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.funcionario=Ext.extend(Phx.gridInterfaz,{

        constructor: function(config) {
            this.maestro = config;

            Phx.vista.funcionario.superclass.constructor.call(this,config);

            this.init();

            /*this.store.baseParams.estado_func = 'activo';
            this.load({params:{start:0, limit:50}});*/

            this.addButton('btnCuenta',
                {
                    text: 'Cuenta Bancaria',
                    iconCls: 'blist',
                    disabled: true,
                    handler: this.onBtnCuenta,
                    tooltip: 'Cuenta Bancaria del Empleado'
                });

            this.addButton('btnFunEspecialidad',
                {
                    text: 'Especialidad',
                    iconCls: 'blist',
                    disabled: true,
                    handler: this.onBtnFunEspe,
                    tooltip: 'Especialidad del Empleado'
                });


            this.addButton('archivo', {
                text: 'Adjuntar Archivo',
                iconCls: 'bfolder',
                disabled: false,
                handler: this.archivo,
                tooltip: '<b>Adjuntar Archivo</b><br><b>Nos permite adjuntar documentos de un funcionario.</b>',
                grupo: [0,1]
            });

            this.addButton('alta_baja', {
                text: 'Altas y Bajas',
                iconCls: 'bcargo',
                disabled: false,
                handler: this.altasBajas,
                tooltip: '<b>Altas y Bajas</b><br><b>Permite visualizar las altas y bajas de los Funcionarios.</b>',
                grupo: [0,1]
            });

            this.iniciarEventos();

        },
        bnewGroups:[0],
        beditGroups:[0,1],
        bdelGroups:[0],
        bactGroups:[0,1],
        bexcelGroups:[0,1],
        gruposBarraTareas: [
            {name:  'activo', title: '<h1 style="text-align: center; color: green;">ACTIVOS</h1>',grupo: 0, height: 0} ,
            {name: 'inactivo', title: '<h1 style="text-align: center; color: red;">INACTIVOS</h1>', grupo: 1, height: 1}
        ],
        actualizarSegunTab: function(name, indice){
            /*if(name == 'activo')
                this.store.baseParams.estado_func = 'activo';
            else*/
            //console.log('entradita', name);
            this.store.baseParams.estado_func = name;
            this.load({params: {start: 0, limit: 50}});
        },

        onButtonNew:function() {
            Phx.vista.funcionario.superclass.onButtonNew.call(this);
            this.getComponente('id_persona').enable();

            this.getComponente('codigo').setVisible(false);
            this.getComponente('id_biometrico').setVisible(false);
            this.getComponente('email_empresa').setVisible(false);

            this.getComponente('estado_reg').setVisible(false);

        },
        onButtonEdit:function() {

            Phx.vista.funcionario.superclass.onButtonEdit.call(this);
            //this.Atributos[this.getIndAtributo('estado_reg')].form=true;
            //this.getComponente('id_persona').disable();
            this.getComponente('codigo').setVisible(false);
            this.getComponente('id_biometrico').setVisible(false);
            this.getComponente('email_empresa').setVisible(false);
            this.getComponente('estado_reg').setVisible(true);

        },

        onSubmit: function (o,x, force) {

            Phx.vista.funcionario.superclass.onSubmit.call(this, o);
        },


        iniciarEventos: function(){
            //f.e.a(eventos recientes)
            //begin
            this.getComponente('genero').on('select',function (combo, record, index ) {
                if(combo.value == 'varon'){
                    this.getComponente('nacionalidad').setValue('BOLIVIANO');
                }else{
                    this.getComponente('nacionalidad').setValue('BOLIVIANA');
                }
            }, this);

            this.getComponente('fecha_nacimiento').on('beforerender',function (combo) {
                var fecha_actual = new Date();
                fecha_actual.setMonth(fecha_actual.getMonth() - 216);
                this.getComponente('fecha_nacimiento').setMaxValue(fecha_actual);
            }, this);

            this.getComponente('discapacitado').on('select',function (combo, record, index ) {
                if(combo.value == 'si'){
                    this.getComponente('carnet_discapacitado').setVisible(true);
                }else{
                    this.getComponente('carnet_discapacitado').setVisible(false);
                }
            }, this);

            this.getComponente('id_persona').on('select',function(c,record,n){
                //console.log('R',record.data);
                /*if (this.register != 'update') {
                    this.getComponente('rotulo_comercial').setValue(r.data.nombre_completo1);
                }*/
                //console.log('store grilla', this.store.data);
                /*var jsonData = Ext.encode(Ext.pluck(this.store.data.items, 'id'));
                console.log('jsonData', jsonData);*/
                /*this.registro = {};
                Ext.each(this.store.data.items, function(person, index) {

                    if(person.id == record.data.id_persona)
                        this.registro = person;

                });*/

                /*Ext.Ajax.request({
                    url:'../../sis_organigrama/control/Funcionario/getDatosFuncionario',
                    params:{
                        id_persona: record.data.id_persona
                    },
                    success:function(resp){
                        var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
                        console.log('respuesta solicitud', reg);
                        //this.Cmp.id_oficina_registro_incidente.setValue(reg.ROOT.datos.id_oficina);


                    },
                    failure: this.conexionFailure,
                    timeout:this.timeout,
                    scope:this
                });*/

                this.getComponente('nombre').setValue(record.data.nombre);
                this.getComponente('ap_paterno').setValue(record.data.ap_paterno);
                this.getComponente('ap_materno').setValue(record.data.ap_materno);
                this.getComponente('fecha_nacimiento').setValue(record.data.fecha_nacimiento);
                this.getComponente('genero').setValue(record.data.genero);
                this.getComponente('nacionalidad').setValue(record.data.nacionalidad);
                this.getComponente('id_lugar').setValue(record.data.id_lugar);
                this.getComponente('id_lugar').setRawValue(record.data.nombre_lugar);

                this.getComponente('ci').setValue(record.data.ci);
                this.getComponente('expedicion').setValue(record.data.expedicion);
                this.getComponente('estado_civil').setValue(record.data.estado_civil);
                this.getComponente('discapacitado').setValue(record.data.discapacitado);
                this.getComponente('carnet_discapacitado').setValue(record.data.carnet_discapacitado);

                this.getComponente('direccion').setValue(record.data.direccion);
                this.getComponente('correo').setValue(record.data.correo);
                this.getComponente('celular1').setValue(record.data.celular1);
                this.getComponente('celular2').setValue(record.data.celular2);
                this.getComponente('telefono1').setValue(record.data.telefono1);
                this.getComponente('telefono2').setValue(record.data.telefono2);
                //this.register='before_registered';

                //console.log('store antes',this.getComponente('id_lugar').getStore());
                //this.getComponente('id_lugar').enable();
                //this.getComponente('id_lugar').clearValue();
                //this.getComponente('id_lugar').store.load({baseParams:{par_filtro:'lug.nombre',es_regional:'si'}});
                //console.log('store despues',this.getComponente('id_lugar').getStore());
                //this.getComponente('id_lugar').select(record.data.id_lugar);


                /*this.getComponente('id_lugar').getStore().on('beforeload', function(combo, options){
                    console.log('cargando store', combo, options)
                    //this.getComponente('id_lugar').setValue(items[0].get(combo.valueField));
                });*/

            },this);



            //end

            if (this.maestro.fecha) {
                this.store.baseParams.fecha = config.fecha;
            }
            if (this.maestro.tipo) {
                this.store.baseParams.tipo = config.tipo;
            }

            if (this.maestro.id_uo) {
                this.store.baseParams.id_uo = config.id_uo;
            }

            /*var txt_ci=this.getComponente('ci');
            var txt_correo=this.getComponente('correo');
            var txt_telefono=this.getComponente('telefono');
            this.getComponente('id_persona').on('select',onPersona);*/
        },

        Grupos: [
            {
                layout: 'column',
                //bodyStyle: 'padding-right:10px;',
                labelWidth: 80,
                labelAlign: 'top',
                border: false,
                items: [
                    {
                        columnWidth: .25,
                        border: false,
                        layout: 'fit',
                        bodyStyle: 'padding-right:10px;',
                        items: [
                            {
                                xtype: 'fieldset',
                                title: 'DATOS FUNCIONARIO',

                                autoHeight: true,
                                items: [
                                    {
                                        layout: 'form',
                                        anchor: '100%',
                                        //bodyStyle: 'padding-right:10px;',
                                        border: false,
                                        padding: '0 5 0 5',
                                        //bodyStyle: 'padding-left:5px;',
                                        id_grupo: 1,
                                        items: []
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        xtype: 'fieldset',
                        title: 'PERSONA',
                        layout: 'column',
                        columnWidth: .75,
                        border: true,
                        //bodyStyle: 'padding-right:10px;',
                        padding: '0 10 0 10',
                        items: [
                            {
                                columnWidth: .33,
                                border: false,
                                layout: 'fit',
                                bodyStyle: 'padding-right:10px;',
                                items: [
                                    {
                                        xtype: 'fieldset',
                                        title: 'DATOS IDENTIFICACIÓN',

                                        autoHeight: true,
                                        items: [
                                            {
                                                layout: 'form',
                                                anchor: '100%',
                                                //bodyStyle: 'padding-right:10px;',
                                                border: false,
                                                padding: '0 5 0 5',
                                                //bodyStyle: 'padding-left:5px;',
                                                id_grupo: 2,
                                                items: []
                                            }
                                        ]
                                    }
                                ]
                            },
                            {
                                columnWidth: .33,
                                border: false,
                                layout: 'fit',
                                bodyStyle: 'padding-right:10px;',
                                items: [
                                    {
                                        xtype: 'fieldset',
                                        title: 'DATOS CIVILES',

                                        autoHeight: true,
                                        items: [
                                            {
                                                layout: 'form',
                                                anchor: '100%',
                                                //bodyStyle: 'padding-right:10px;',
                                                border: false,
                                                padding: '0 5 0 5',
                                                //bodyStyle: 'padding-left:5px;',
                                                id_grupo: 3,
                                                items: []
                                            }
                                        ]
                                    }
                                ]
                            },
                            {
                                columnWidth: .34,
                                border: false,
                                layout: 'fit',
                                bodyStyle: 'padding-right:10px;',
                                items: [
                                    {
                                        xtype: 'fieldset',
                                        title: 'DATOS DE UBICACIÓN',

                                        autoHeight: true,
                                        items: [
                                            {
                                                layout: 'form',
                                                anchor: '100%',
                                                //bodyStyle: 'padding-right:10px;',
                                                border: false,
                                                padding: '0 5 0 5',
                                                //bodyStyle: 'padding-left:5px;',
                                                id_grupo: 4,
                                                items: []
                                            }
                                        ]
                                    }
                                ]
                            }
                        ]
                    }
                ]
            }
        ],

        Atributos:[
            {
                // configuracion del componente
                config:{
                    labelSeparator:'',
                    inputType:'hidden',
                    name: 'id_funcionario'
                },
                type:'Field',
                form:true

            },

            {
                config:{
                    name:'id_persona',
                    origen:'PERSONA',
                    tinit:true,
                    allowBlank: true,
                    fieldLabel:'Nombre Persona',
                    gdisplayField:'desc_person',//mapea al store del grid
                    anchor: '100%',
                    gwidth:200,
                    store: new Ext.data.JsonStore({
                        url: '../../sis_seguridad/control/Persona/listarPersona',
                        id: 'id_persona',
                        root: 'datos',
                        sortInfo:{
                            field: 'nombre_completo1',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_persona','nombre_completo1','ci','tipo_documento','num_documento','expedicion','nombre','ap_paterno','ap_materno',
                            'correo','celular1','telefono1','telefono2','celular2',{name:'fecha_nacimiento', type: 'date', dateFormat:'Y-m-d'},
                            'genero','direccion','id_lugar', 'estado_civil', 'discapacitado', 'carnet_discapacitado','nacionalidad', 'nombre_lugar'],
                        // turn on remote sorting
                        remoteSort: true,
                        baseParams: {par_filtro:'p.nombre_completo1#p.ci', es_funcionario:'si'}
                    }),
                    renderer:function (value, p, record){return String.format('{0}', record.data['desc_person']);},
                    tpl: new Ext.XTemplate([
                        '<tpl for=".">',
                        '<div class="x-combo-list-item">',
                        '<div class="awesomecombo-item {checked}">',
                        '<p><b>{nombre_completo1}</b></p>',
                        '</div><p><b>CI:</b> <span style="color: green;">{ci} {expedicion}</span></p>',
                        '</div></tpl>'
                    ]),
                },
                type:'ComboRec',
                id_grupo:1,
                bottom_filter : true,
                filters:{
                    pfiltro:'PERSON.nombre_completo2',
                    type:'string'
                },

                grid:true,
                form:true
            },

            {
                config:{
                    fieldLabel: "Foto",
                    gwidth: 130,
                    inputType:'file',
                    name: 'foto',
                    //allowBlank:true,
                    buttonText: '',
                    maxLength:150,
                    anchor:'100%',
                    renderer:function (value, p, record){
                        if(record.data.nombre_archivo != '' || record.data.extension!='')
                            return String.format('{0}', "<div style='text-align:center'><img src = './../../../uploaded_files/sis_parametros/Archivo/" + record.data.nombre_archivo + "."+record.data.extension+"' align='center' width='70' height='70'/></div>");
                        else
                            return String.format('{0}', "<div style='text-align:center'><img src = '../../../lib/imagenes/NoPerfilImage.jpg' align='center' width='70' height='70'/></div>");
                    },
                    buttonCfg: {
                        iconCls: 'upload-icon'
                    }
                },
                type:'Field',
                sortable:false,
                id_grupo:1,
                grid:true,
                form:false
            },
            {
                config:{
                    fieldLabel: "Cargo",
                    gwidth: 200,
                    name: 'nombre_cargo',
                    allowBlank:true,
                    maxLength:100,
                    minLength:1,
                    anchor:'100%',
                    disabled: true,
                    style: 'color: blue; background-color: orange;',
                    renderer: function (value, p, record){
                        return String.format('<div style="color: green; font-weight: bold;">{0}</div>', value);
                    }
                },
                type:'TextField',
                filters:{pfiltro:'tca.nombre',type:'string'},
                bottom_filter : true,
                id_grupo:1,
                grid:true,
                form:true
            },

            {
                config:{
                    name: 'fecha_asignacion',
                    fieldLabel: 'Fecha Asignación',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
                },
                type:'DateField',
                filters:{pfiltro:'tuo.fecha_asignacion',type:'date'},
                id_grupo:0,
                grid:true,
                form:false
            },
            {
                config:{
                    name: 'fecha_finalizacion',
                    fieldLabel: 'Fecha Finalización.',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
                },
                type:'DateField',
                filters:{pfiltro:'tuo.fecha_finalizacion',type:'date'},
                id_grupo:0,
                grid:true,
                form:false
            },

            {
                config:{
                    fieldLabel: "Oficina",
                    gwidth: 200,
                    name: 'nombre_oficina',
                    allowBlank:true,
                    maxLength:100,
                    minLength:1,
                    anchor:'100%',
                    disabled: true,
                    style: 'color: blue; background-color: orange;',
                    renderer: function (value, p, record){
                        return String.format('<div style="color: green; font-weight: bold;">{0}</div>', value);
                    }
                },
                type:'TextField',
                filters:{pfiltro:'tof.nombre',type:'string'},
                bottom_filter : true,
                id_grupo:1,
                grid:true,
                form:false
            },

            {
                config:{
                    fieldLabel: "Lugar Oficina",
                    gwidth: 200,
                    name: 'nombre_lugar_ofi',
                    allowBlank:true,
                    maxLength:100,
                    minLength:1,
                    anchor:'100%',
                    disabled: true,
                    style: 'color: blue; background-color: orange;',
                    renderer: function (value, p, record){
                        return String.format('<div style="color: green; font-weight: bold;">{0}</div>', value);
                    }
                },
                type:'TextField',
                filters:{pfiltro:'tlo.nombre',type:'string'},
                bottom_filter : true,
                id_grupo:1,
                grid:true,
                form:false
            },

            {
                config:{
                    fieldLabel: "Código",
                    gwidth: 120,
                    name: 'codigo',
                    allowBlank:true,
                    maxLength:100,
                    minLength:1,
                    anchor:'100%',
                    disabled: true,
                    style: 'color: blue; background-color: orange;',
                    renderer: function (value, p, record){
                        return String.format('<div style="color: black; font-weight: bold;">{0}</div>', value);
                    }
                },
                type:'TextField',
                filters:{pfiltro:'FUNCIO.codigo',type:'string'},
                bottom_filter : true,
                id_grupo:1,
                grid:true,
                form:true
            },
            {
                config:{
                    name: 'id_biometrico',
                    fieldLabel: 'ID Biométrico',
                    allowBlank: true,
                    anchor: '100%',
                    disabled: true,
                    style: 'color: blue; background-color: orange;',
                    gwidth: 100,
                    maxLength:15,
                    renderer: function (value, p, record){
                        return String.format('<div style="color: black; font-weight: bold;">{0}</div>', value);
                    }
                },
                type:'NumberField',
                bottom_filter : true,
                filters:{pfiltro:'FUNCIO.id_biometrico',type:'string'},
                id_grupo:1,
                grid:true,
                form:true
            },


            {
                config:{
                    fieldLabel: "Correo Empresarial",
                    gwidth: 140,
                    name: 'email_empresa',
                    allowBlank:true,
                    maxLength:100,
                    minLength:1,
                    anchor:'100%',
                    renderer: function (value, p, record){
                        return String.format('<div style="color: green; font-weight: bold;">{0}</div>', value);
                    }
                },
                type:'TextField',
                filters:{type:'string'},
                id_grupo:1,
                bottom_filter : true,
                grid:true,
                form:true
            },


            //PERSONA
            {
                config:{
                    fieldLabel: "Nombre",
                    gwidth: 130,
                    name: 'nombre',
                    allowBlank:false,
                    maxLength:150,
                    minLength:2,
                    anchor:'100%'
                },
                type:'TextField',
                filters:{type:'string'},
                //bottom_filter : true,
                id_grupo:2,
                grid:false,
                form:true
            },
            {
                config:{
                    fieldLabel: "Apellido Paterno",
                    gwidth: 130,
                    name: 'ap_paterno',
                    allowBlank:false,
                    maxLength:150,

                    anchor:'100%'
                },
                type:'TextField',
                filters:{pfiltro:'p.apellido_paterno',type:'string'},
                //bottom_filter : true,
                id_grupo:2,
                grid:false,
                form:true
            },
            {
                config:{
                    fieldLabel: "Apellido Materno",
                    gwidth: 130,
                    name: 'ap_materno',
                    allowBlank:true,
                    maxLength:150,
                    anchor:'100%'
                },
                type:'TextField',
                filters:{pfiltro:'p.apellido_materno',type:'string'},//p.apellido_paterno
                //bottom_filter : true,
                id_grupo:2,
                grid:false,
                form:true
            },
            {
                config:{
                    fieldLabel: "Fecha de Nacimiento",
                    gwidth: 120,
                    name: 'fecha_nacimiento',
                    allowBlank:false,
                    maxLength:100,
                    minLength:1,
                    format:'d/m/Y',
                    anchor:'100%',
                    renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
                },
                type:'DateField',
                filters:{type:'date'},
                id_grupo:2,
                grid:true,
                form:true
            },
            {
                config:{
                    name:'genero',
                    fieldLabel:'Genero',
                    allowBlank:true,
                    emptyText:'Genero...',

                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender:true,
                    mode: 'local',
                    store:['varon','mujer']

                },
                type:'ComboBox',
                id_grupo:2,
                filters:{
                    type: 'list',
                    options: ['varon','mujer']
                },
                grid:true,
                form:true
            },
            {
                config:{
                    fieldLabel: "Nacionalidad",
                    gwidth: 120,
                    name: 'nacionalidad',
                    allowBlank:false,
                    maxLength:200,
                    minLength:1,
                    anchor:'100%'
                },
                type:'TextField',
                filters:{type:'string'},
                //bottom_filter : true,
                id_grupo:2,
                grid:true,
                form:true
            },
            {
                config:{
                    name: 'id_lugar',
                    fieldLabel: 'Lugar Nacimiento',
                    allowBlank: false,
                    emptyText:'Lugar...',
                    store:new Ext.data.JsonStore(
                        {
                            url: '../../sis_parametros/control/Lugar/listarLugar',
                            id: 'id_lugar',
                            root: 'datos',
                            sortInfo:{
                                field: 'nombre',
                                direction: 'ASC'
                            },
                            totalProperty: 'total',
                            fields: ['id_lugar','id_lugar_fk','codigo','nombre','tipo','sw_municipio','sw_impuesto','codigo_largo'],
                            // turn on remote sorting
                            remoteSort: true,
                            baseParams:{par_filtro:'lug.nombre',es_regional:'si'}
                        }),
                    valueField: 'id_lugar',
                    displayField: 'nombre',
                    gdisplayField:'nombre_lugar',
                    hiddenName: 'id_lugar',
                    triggerAction: 'all',
                    lazyRender:true,
                    mode:'remote',
                    pageSize:50,
                    queryDelay:500,
                    anchor:"100%",
                    gwidth:150,
                    minChars:2,
                    renderer:function (value, p, record){return String.format('{0}', record.data['nombre_lugar']);}
                },
                type:'ComboBox',
                filters:{pfiltro:'lug.nombre',type:'string'},
                id_grupo:2,
                grid:true,
                form:true
            },

            {
                config:{
                    name:'tipo_documento',
                    fieldLabel:'Tipo Documento',
                    allowBlank:true,
                    emptyText:'Tipo Doc...',

                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender:true,
                    mode: 'local',
                    store:['documento_identidad','pasaporte'],
                    renderer:function (value, p, record){return String.format('{0}', record.data['nombre_lugar']);}

                },
                type:'ComboBox',
                id_grupo:3,
                filters:{
                    type: 'list',
                    options: ['documento_identidad','pasaporte'],
                },
                grid:true,
                valorInicial:'documento_identidad',
                form:true
            },
            {
                config:{
                    fieldLabel: "Nro. Documento",
                    gwidth: 120,
                    name: 'ci',
                    allowBlank:false,
                    maxLength:20,
                    minLength:1,
                    anchor:'100%'
                },
                type:'TextField',
                filters:{pfiltro:'PERSON.ci',
                    type:'string'},
                id_grupo:3,
                bottom_filter : true,
                grid:true,
                form:true
            },

            {
                config:{
                    name:'expedicion',
                    fieldLabel:'Expedido en',
                    allowBlank:true,
                    emptyText:'Expedido en...',

                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender:true,
                    mode: 'local',
                    store:['CB','LP','BN','CJ','PT','CH','TJ','SC','OR','OTRO']

                },
                type:'ComboBox',
                id_grupo:3,
                filters:{
                    type: 'list',
                    options: ['CB','LP','BN','CJ','PT','CH','TJ','SC','OR','OTRO'],
                },
                grid:true,
                //valorInicial:'expedicion',
                form:true
            },
            {
                config:{
                    name:'estado_civil',
                    fieldLabel:'Estado Civil',
                    allowBlank:true,
                    emptyText:'Estado...',

                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender:true,
                    mode: 'local',
                    store:['soltero','casado','divorciado','viudo']

                },
                type:'ComboBox',
                id_grupo:3,
                filters:{
                    type: 'list',
                    options: ['soltero','casado','divorciado','viudo']
                },
                grid:true,
                form:true
            },


            {
                config:{
                    name:'discapacitado',
                    fieldLabel:'Discapacitado',
                    allowBlank:false,
                    emptyText:'Discapacitado...',

                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender:true,
                    mode: 'local',
                    store:['no','si']

                },
                type:'ComboBox',
                id_grupo:3,
                filters:{
                    type: 'list',
                    options:['no','si']
                },
                grid:true,
                form:true
            },

            {
                config:{
                    fieldLabel: "Carnet Discapacitado",
                    gwidth: 120,
                    name: 'carnet_discapacitado',
                    allowBlank:true,
                    maxLength:100,
                    minLength:1,
                    anchor:'100%',
                    hidden:true
                },
                type:'TextField',
                filters:{type:'string'},
                bottom_filter : true,
                id_grupo:3,
                grid:true,
                form:true
            },

            {
                config:{
                    fieldLabel: "Antiguedad Anterior (meses)",
                    gwidth: 120,
                    name: 'antiguedad_anterior',
                    allowBlank:true,
                    maxLength:3,
                    minLength:1,
                    anchor:'100%'
                },
                type:'NumberField',
                id_grupo:1,
                grid:true,
                form:true
            },

            {
                config:{
                    name: 'telefono_ofi',
                    fieldLabel: "Telf. Oficina",
                    gwidth: 120,
                    allowBlank:true,
                    maxLength:50,
                    minLength:1,
                    anchor:'100%'
                },
                type:'TextField',
                filters:{type:'string'},
                id_grupo:1,
                grid:true,
                form:true
            },
            {
                config:{
                    fieldLabel: "Interno",
                    gwidth: 120,
                    name: 'interno',
                    allowBlank:true,
                    maxLength:100,
                    minLength:1,
                    anchor:'100%'
                },
                type:'TextField',
                filters:{type:'string'},
                id_grupo:1,
                grid:true,
                form:true
            },

            {
                config:{
                    fieldLabel: "Fecha de Ingreso",
                    gwidth: 120,
                    name: 'fecha_ingreso',
                    allowBlank:true,
                    maxLength:100,
                    minLength:1,
                    format:'d/m/Y',
                    anchor:'100%',
                    renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
                },
                type:'DateField',
                filters:{type:'date'},
                id_grupo:1,
                grid:true,
                form:true
            },
            {
                config:{
                    name:'es_tutor',
                    fieldLabel:'Es Tutor',
                    allowBlank:true,
                    emptyText:'Es Tutor...',

                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender:true,
                    mode: 'local',
                    store:['si','no']

                },
                type:'ComboBox',
                id_grupo:1,
                filters:{
                    type: 'list',
                    options: ['si','no'],
                },
                grid:true,
                valorInicial:'no',
                form:true
            },

            {
                config:{
                    fieldLabel: "Teléfono 1",
                    gwidth: 120,
                    name: 'telefono1',
                    allowBlank:true,
                    maxLength:100,
                    minLength:1,
                    anchor:'100%'
                },
                type:'NumberField',
                filters:{type:'string'},
                id_grupo:4,
                grid:true,
                form:true
            },
            {
                config:{
                    fieldLabel: "Celular 1",
                    gwidth: 120,
                    name: 'celular1',
                    allowBlank:true,

                    maxLength:100,
                    minLength:1,
                    anchor:'100%'
                },
                type:'NumberField',
                filters:{type:'string'},
                id_grupo:4,
                grid:true,
                form:true
            },
            {
                config:{
                    fieldLabel: "Correo",
                    gwidth: 120,
                    name: 'correo',
                    allowBlank:true,
                    vtype:'email',
                    maxLength:50,
                    minLength:1,
                    anchor:'100%'
                },
                type:'TextField',
                filters:{
                    pfiltro: 'person.correo',
                    type:'string'
                },
                id_grupo:4,
                bottom_filter : true,
                grid:true,
                form:true
            },
            {
                config:{
                    fieldLabel: "Telefono 2",
                    gwidth: 120,
                    name: 'telefono2',
                    allowBlank:true,
                    maxLength:15,
                    minLength:5,
                    anchor:'100%'
                },
                type:'TextField',
                filters:{type:'string'},
                id_grupo:4,
                grid:true,
                form:true
            },
            {
                config:{
                    fieldLabel: "Celular 2",
                    gwidth: 120,
                    name: 'celular2',
                    allowBlank:true,
                    maxLength:15,
                    minLength:5,
                    anchor:'100%'
                },
                type:'TextField',
                filters:{type:'string'},
                id_grupo:4,
                grid:true,
                form:true
            },
            {
                config:{
                    fieldLabel: "Dirección",
                    gwidth: 80,
                    name: 'direccion',
                    allowBlank:true,
                    maxLength:10000,
                    minLength:5,
                    anchor:'100%'
                },
                type:'TextArea',
                filters:{type:'string'},
                id_grupo:4,
                grid:true,
                form:true
            },
            {
                config:{
                    name: 'fecha_reg',
                    fieldLabel: 'Fecha creación',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    format:'d/m/Y',
                    renderer:function (value,p,record){return value?value.dateFormat('d/m/Y h:i:s'):''}
                },
                type:'DateField',
                filters:{pfiltro:'ins.fecha_reg',type:'date'},
                id_grupo:0,
                grid:true,
                form:false
            },
            {
                config:{
                    name:'estado_reg',
                    fieldLabel:'Estado',
                    allowBlank:true,
                    emptyText:'Estado...',

                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender:true,
                    mode: 'local',
                    valueField: 'estado_reg',
                    store:['activo','inactivo']

                },
                type:'ComboBox',
                id_grupo:1,
                filters:{
                    type: 'list',
                    pfiltro:'FUNCIO.estado_reg',
                    dataIndex: 'size',
                    options: ['activo','inactivo'],
                },
                grid:true,
                form:true
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
                type:'TextField',
                filters:{pfiltro:'usu1.cuenta',type:'string'},
                id_grupo:0,
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
                    renderer:function (value,p,record){return value?value.dateFormat('d/m/Y h:i:s'):''}
                },
                type:'DateField',
                filters:{pfiltro:'ins.fecha_mod',type:'date'},
                id_grupo:0,
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
                type:'TextField',
                filters:{pfiltro:'usu2.cuenta',type:'string'},
                id_grupo:0,
                grid:true,
                form:false
            }


        ],

        /*onPersona: function(c,r,e){
            txt_ci.setValue(r.data.ci);
            txt_correo.setValue(r.data.correo);
            txt_telefono.setValue(r.data.telefono);
        },*/

        title:'Funcionarios',
        ActSave:'../../sis_organigrama/control/Funcionario/guardarFuncionario',
        ActDel:'../../sis_organigrama/control/Funcionario/eliminarFuncionario',
        ActList:'../../sis_organigrama/control/Funcionario/listarFuncionario',
        id_store:'id_funcionario',
        fields: [
            {name:'id_funcionario'},
            {name:'id_persona'},
            {name:'id_lugar', type: 'numeric'},
            {name:'desc_person',type:'string'},
            {name:'genero',type:'string'},
            {name:'estado_civil',type:'string'},
            {name:'nombre_lugar',type:'string'},
            {name:'nacionalidad',type:'string'},
            {name:'discapacitado',type:'string'},
            {name:'carnet_discapacitado',type:'string'},
            {name:'codigo',type:'string'},
            {name:'antiguedad_anterior',type:'numeric'},

            {name:'estado_reg', type: 'string'},

            {name:'ci', type:'string'},
            {name:'documento', type:'string'},
            {name:'correo', type:'string'},
            {name:'celular1'},
            {name:'telefono1'},
            {name:'email_empresa'},
            'interno',
            {name:'fecha_ingreso', type: 'date', dateFormat:'Y-m-d'},
            {name:'fecha_nacimiento', type: 'date', dateFormat:'Y-m-d'},
            {name:'fecha_reg', type: 'date', dateFormat:'Y-m-d'},
            {name:'id_usuario_reg', type: 'numeric'},
            {name:'fecha_mod', type: 'date', dateFormat:'Y-m-d'},
            {name:'id_usuario_mod', type: 'numeric'},
            {name:'usr_reg', type: 'string'},
            {name:'usr_mod', type: 'string'},
            'telefono_ofi',
            'horario1',
            'horario2',
            'horario3',
            'horario4',
            {name:'id_biometrico', type: 'numeric'},
            {name:'nombre_archivo', type: 'string'},
            {name:'extension', type: 'string'},
            'telefono2',
            'celular2',
            'nombre',
            'ap_paterno',
            'ap_materno',
            'tipo_documento',
            'expedicion',
            'direccion',
            'es_tutor',
            {name:'fecha_asignacion', type: 'date', dateFormat:'Y-m-d'},
            {name:'fecha_finalizacion', type: 'date', dateFormat:'Y-m-d'},
            'nombre_cargo',
            'nombre_oficina',
            'nombre_lugar_ofi'
        ],
        sortInfo:{
            field: 'PERSON.nombre_completo2',
            direction: 'ASC'
        },


        // para configurar el panel south para un hijo

        /*
         * south:{
         * url:'../../../sis_seguridad/vista/usuario_regional/usuario_regional.php',
         * title:'Regional', width:150
         *  },
         */
        bdel:true,
        bsave:false,
        fwidth: '90%',
        fheight: '95%',
        onBtnCuenta: function(){
            var rec = {maestro: this.sm.getSelected().data}

            Phx.CP.loadWindows('../../../sis_organigrama/vista/funcionario_cuenta_bancaria/FuncionarioCuentaBancaria.php',
                'Cuenta Bancaria del Empleado',
                {
                    width:700,
                    height:450
                },
                rec,
                this.idContenedor,
                'FuncionarioCuentaBancaria');
        },

        onBtnFunEspe: function(){
            var rec = {maestro: this.sm.getSelected().data}

            Phx.CP.loadWindows('../../../sis_organigrama/vista/funcionario_especialidad/FuncionarioEspecialidad.php',
                'Especialidad del Empleado',
                {
                    width:700,
                    height:450
                },
                rec,
                this.idContenedor,
                'FuncionarioEspecialidad');
        },

        preparaMenu:function() {
            this.getBoton('btnCuenta').enable();
            this.getBoton('btnFunEspecialidad').enable();
            this.getBoton('archivo').enable();
            Phx.vista.funcionario.superclass.preparaMenu.call(this);
        },
        liberaMenu:function() {
            this.getBoton('btnCuenta').disable();
            this.getBoton('btnFunEspecialidad').disable();
            this.getBoton('archivo').disable();
            Phx.vista.funcionario.superclass.liberaMenu.call(this);
        },

        archivo: function () {

            var rec = this.getSelectedData();
            //enviamos el id seleccionado para cual el archivo se deba subir
            rec.datos_extras_id = rec.id_funcionario;
            //enviamos el nombre de la tabla
            rec.datos_extras_tabla = 'orga.tfuncionario';
            //enviamos el codigo ya que una tabla puede tener varios archivos diferentes como ci,pasaporte,contrato,slider,fotos,etc
            rec.datos_extras_codigo = '';

            //esto es cuando queremos darle una ruta personalizada
            //rec.datos_extras_ruta_personalizada = './../../../uploaded_files/favioVideos/videos/';

            Phx.CP.loadWindows('../../../sis_parametros/vista/archivo/Archivo.php',
                'Archivo',
                {
                    width: '80%',
                    height: '100%'
                }, rec, this.idContenedor, 'Archivo');

        },

        altasBajas: function () {
            var rec = this.getSelectedData();
            Phx.CP.loadWindows('../../../sis_organigrama/vista/funcionario/AltasBajasFuncionario.php',
                'Altas y Bajas',
                {
                    width: '80%',
                    height: '100%'
                }, rec, this.idContenedor, 'AltasBajasFuncionario');

        }



    });
</script>
