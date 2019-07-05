<?php
/**
 *@package pXP
 *@file gen-SistemaDist.php
 *@author  (fprudencio)
 *@date 20-09-2011 10:22:05
 *@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.ConsultaDocumento = Ext.extend(Phx.gridInterfaz, {
        bdel: false,
        bedit: false,
        bnew: false,
        btest: false,
        bexcel:true,
        ActList:'../../sis_organigrama/control/Funcionario/listarDocumentos',
        title:'Consulta Documentos',
        id_store:'id_funcionario',
        constructor: function(config) {
          this.initButtons=[this.cmbGerencia];
          this.maestro=config.maestro;


            Phx.vista.ConsultaDocumento.superclass.constructor.call(this,config);

            /*this.tbar.items.items[1].menu.items.items.splice(1,1);
            this.tbar.items.items[1].text = 'Exportar CSV';*/
            this.init();
            this.finCons = true;
            this.store.baseParams.estado_func = 'activo';


            this.addButton('archivo', {
                text: 'Adjuntar Archivo',
                iconCls: 'bfolder',
                disabled: false,
                handler: this.archivo,
                tooltip: '<b>Adjuntar Archivo</b><br>Nos permite adjuntar documentos de un funcionario.'
            });

            this.addButton('rep_archivo', {
                text: 'Rep. Archivos',
                iconCls: 'bprint_good',
                disabled: false,
                handler: this.repArchivo,
                tooltip: '<b>Imprimir Reporte</b><br>Genera reporte de los documentos de un funcionario.'
            });

          /*  this.cmbGerencia.on('select', function(){
                if( this.validarFiltros() ){
                          this.capturaFiltros();
                     }
            },this);*/

              this.cmbGerencia.on('select', function( combo, record, index){
                    this.capturaFiltros();
                },this);
          /*  this.store.baseParams.id_uo = -1;
          this.load({params: {start: 0, limit: this.tam_pag}});*/


        },

        gruposBarraTareas: [
            {name:  'activo', title: '<h1 style="text-align: center; color: green; font-weight: bold;"><i class="fa fa-user fa-2x" aria-hidden="true"></i> ACTIVOS</h1>',grupo: 0, height: 0} ,
            {name: 'inactivo', title: '<h1 style="text-align: center; color: red; font-weight: bold;"><i class="fa fa-user-times fa-2x" aria-hidden="true"></i> INACTIVOS</h1>', grupo: 0, height: 0}
        ],
        actualizarSegunTab: function(name, indice){
              if(this.finCons){

                  if(!this.validarFiltros()){
                      alert('Especifique la Gerencia a la que Pertenece');
                  }
                  else
                  {
                      this.store.baseParams.id_uo=this.cmbGerencia.getValue();
                      this.store.baseParams.estado_func = name;
                      console.log('entradita', name);
                      this.load({params: {start: 0, limit: this.tam_pag}});


                      /*if(this.store.baseParams.estado_func == 'activo'){

                        this.load({params: {start: 0, limit: this.tam_pag}});

                      }
                      else if(this.store.baseParams.estado_func == 'inactivo'){

                        this.load({params: {start: 0, limit: this.tam_pag}});
                      }*/
                    }
            }

          },
        /*actualizarSegunTab: function(name, indice){
            /*if(name == 'activo')
                this.store.baseParams.estado_func = 'activo';
            else*/


          /*  this.store.baseParams.estado_func = name;
            this.load({params: {start: 0, limit: 50}});
        },*/

        Atributos:[
            {
                //configuracion del componente
                config: {
                    labelSeparator: '',
                    inputType: 'hidden',
                    name: 'id_funcionario'
                },
                type: 'Field',
                form: true,
                id_grupo:0
            },

            {
                config:{
                    name: 'desc_funcionario',
                    fieldLabel: 'Funcionario',
                    allowBlank: true,
                    width: '100%',
                    gwidth: 250,
                    renderer: function(value, p, record){
                        return String.format('<b style="color:green;">{0}</b>', record.data['desc_funcionario']);
                    }
                },
                type: 'TextField',
                filters: {pfiltro:'tf.desc_funcionario2', type:'string'},
                id_grupo: 0,
                form:false,
                grid:true,
                bottom_filter : true
            },
            {
                config:{
                    fieldLabel: "Foto",
                    gwidth: 100,
                    inputType:'file',
                    name: 'url_foto',
                    //allowBlank:true,
                    buttonText: '',
                    maxLength:150,
                    anchor:'100%',
                    renderer:function (value, p, record){
                        return String.format('{0}', "<div style='text-align:center'><img src = './../../../uploaded_files/sis_parametros/Archivo/" +value+"' align='center' width='70' height='70'/></div>");
                    },
                    buttonCfg: {
                        iconCls: 'upload-icon'
                    }
                },
                type:'Field',
                sortable:false,
                id_grupo:0,
                grid:true,
                form:false
            },

            {
                config:{
                    name: 'cargo',
                    fieldLabel: 'Cargo',
                    allowBlank: true,
                    width: '100%',
                    gwidth: 250,
                    renderer: function(value, p, record){
                        return String.format('<b style="color:green;">{0}</b>', value);
                    }
                },
                type: 'TextField',
                filters: {pfiltro:'tc.nombre', type:'string'},
                id_grupo: 0,
                form:false,
                grid:true,
                bottom_filter : true
            },

            {
                config:{
                    name: 'ci',
                    fieldLabel: 'CI',
                    allowBlank: true,
                    width: '100%',
                    gwidth: 70,
                    renderer: function(value, p, record){
                        return String.format('<b style="color:green;">{0}</b>', value);
                    }
                },
                type: 'TextField',
                filters: {pfiltro:'tf.ci', type:'string'},
                id_grupo: 0,
                form:false,
                grid:true,
             bottom_filter : true
            },

            {
                config:{
                    name: 'id_biometrico',
                    fieldLabel: 'Biometrico',
                    allowBlank: true,
                    width: '100%',
                    gwidth: 70,
                    renderer: function(value, p, record){
                        return String.format('<b style="color:green;">{0}</b>', value);
                    }
                },
                type: 'TextField',
                filters: {pfiltro:'tf.id_biometrico', type:'string'},
                id_grupo: 0,
                form:false,
                grid:true,
                bottom_filter : true
            },
            {
                config:{
                    name: 'nombre_lugar_ofi',
                    fieldLabel: 'Lugar Oficina',
                    allowBlank: true,
                    width: '100%',
                    gwidth: 100,
                    renderer: function(value, p, record){
                        return String.format('<b style="color:blue;">{0}</b>', value);
                    }
                },
                type: 'TextField',
                filters: {pfiltro:'tlo.nombre', type:'string'},
                id_grupo: 0,
                form:false,
                grid:true,
                bottom_filter : true
            },

            {
                config:{
                    fieldLabel: "Fecha Ingreso",
                    gwidth: 90,
                    name: 'fecha_ingreso',
                    allowBlank:false,
                    maxLength:100,
                    minLength:1,
                    format:'d/m/Y',
                    anchor:'100%',
                    renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
                },
                type:'DateField',
                filters:{type:'date'},
                id_grupo:0,
                grid:true,
                form:false
            },

            {
                config:{
                    name:'fotografia',
                    fieldLabel:'Fotografia',
                    allowBlank: true,
                    anchor: '70%',
                    gwidth: 70,

                    renderer: function (value, p, record, rowIndex, colIndex) {
                        //console.log('value', value, 'record', record);
                        if (value!='X'/*record.data.lista_doc.includes('FOTO_FUNCIONARIO')*/) {//value!='X'
                            var checked = 'checked';
                        }
                        return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:20px;width:20px;" type="checkbox"  {0}></div>', checked);
                    }
                },
                type:'Checkbox',
                id_grupo: 0,
                grid: true,
                form:true
            },
            {
                config:{
                    name:'diploma_academico',
                    fieldLabel:'Diploma Academico',
                    allowBlank: true,
                    anchor: '70%',
                    gwidth: 110,

                    renderer: function (value, p, record, rowIndex, colIndex) {
                        if (value!='X'/*record.data.lista_doc.includes('DIAC')*/) {
                            var checked = 'checked';
                        }
                        return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:20px;width:20px;" type="checkbox"  {0}></div>', checked);
                    }
                },
                type:'Checkbox',
                id_grupo: 0,
                grid: true,
                form:true
            },
            {
                config:{
                    name:'titulo_bachiller',
                    fieldLabel:'Titulo Bachiller',
                    allowBlank: true,
                    anchor: '70%',
                    gwidth: 100,

                    renderer: function (value, p, record, rowIndex, colIndex) {
                        if (value!='X'/*record.data.lista_doc.includes('TIT_BACHILLER')*/) {
                            var checked = 'checked';
                        }

                        return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:20px;width:20px;" type="checkbox"  {0}></div>', checked);
                    }
                },
                type:'Checkbox',
                id_grupo: 0,
                grid: true,
                form:true
            },
            {
                config:{
                    name:'titulo_profesional',
                    fieldLabel:'Titulo Profesional',
                    allowBlank: true,
                    anchor: '70%',
                    gwidth: 100,

                    renderer: function (value, p, record, rowIndex, colIndex) {
                        if (value!='X'/*record.data.lista_doc.includes('TIT_PROF')*/) {
                            var checked = 'checked';
                        }

                        return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:20px;width:20px;" type="checkbox"  {0}></div>', checked);
                    }
                },
                type:'Checkbox',
                id_grupo: 0,
                grid: true,
                form:true
            },
            {
                config:{
                    name:'titulo_maestria',
                    fieldLabel:'Titulo Maestria',
                    allowBlank: true,
                    anchor: '70%',
                    gwidth: 100,

                    renderer: function (value, p, record, rowIndex, colIndex) {
                        if (value!='X'/*record.data.lista_doc.includes('TIT_MAES')*/) {
                            var checked = 'checked';
                        }

                        return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:20px;width:20px;" type="checkbox"  {0}></div>', checked);
                    }
                },
                type:'Checkbox',
                id_grupo: 0,
                grid: true,
                form:true
            },
            {
                config:{
                    name:'titulo_doctorado',
                    fieldLabel:'Titulo Doctorado',
                    allowBlank: true,
                    anchor: '70%',
                    gwidth: 100,

                    renderer: function (value, p, record, rowIndex, colIndex) {
                        if (value!='X'/*record.data.lista_doc.includes('TIT_DOC')*/) {
                            var checked = 'checked';
                        }

                        return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:20px;width:20px;" type="checkbox"  {0}></div>', checked);
                    }
                },
                type:'Checkbox',
                id_grupo: 0,
                grid: true,
                form:true
            },
            {
                config:{
                    name:'certificado_egreso',
                    fieldLabel:'Certificado Egreso',
                    allowBlank: true,
                    anchor: '70%',
                    gwidth: 110,

                    renderer: function (value, p, record, rowIndex, colIndex) {
                        if (value!='X'/*record.data.lista_doc.includes('CERT_EGRESO')*/) {
                            var checked = 'checked';
                        }

                        return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:20px;width:20px;" type="checkbox"  {0}></div>', checked);
                    }
                },
                type:'Checkbox',
                id_grupo: 0,
                grid: true,
                form:true
            },
            {
                config:{
                    name:'carnet_identidad',
                    fieldLabel:'Carnet Identidad',
                    allowBlank: true,
                    anchor: '70%',
                    gwidth: 100,

                    renderer: function (value, p, record, rowIndex, colIndex) {
                        if (value!='X'/*record.data.lista_doc.includes('CI_FUNCIONARIO')*/) {
                            var checked = 'checked';
                        }

                        return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:20px;width:20px;" type="checkbox"  {0}></div>', checked);
                    }
                },
                type:'Checkbox',
                id_grupo: 0,
                grid: true,
                form:true
            },
            {
                config:{
                    name:'certificado_nacimiento',
                    fieldLabel:'Certificado Nacimiento',
                    allowBlank: true,
                    anchor: '70%',
                    gwidth: 125,

                    renderer: function (value, p, record, rowIndex, colIndex) {
                        if (value!='X'/*record.data.lista_doc.includes('CER_NAC')*/) {
                            var checked = 'checked';
                        }

                        return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:20px;width:20px;" type="checkbox"  {0}></div>', checked);
                    }
                },
                type:'Checkbox',
                id_grupo: 0,
                grid: true,
                form:true
            },
            {
                config:{
                    name:'certificado_matrimonio',
                    fieldLabel:'Certificado Matrimonio',
                    allowBlank: true,
                    anchor: '70%',
                    gwidth: 130,

                    renderer: function (value, p, record, rowIndex, colIndex) {
                        if (value!='X'/*record.data.lista_doc.includes('CERT_MATR')*/) {
                            var checked = 'checked';
                        }

                        return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:20px;width:20px;" type="checkbox"  {0}></div>', checked);
                    }
                },
                type:'Checkbox',
                id_grupo: 0,
                grid: true,
                form:true
            },
            {
                config:{
                    name:'libreta_militar',
                    fieldLabel:'Libreta Militar',
                    allowBlank: true,
                    anchor: '70%',
                    gwidth: 100,

                    renderer: function (value, p, record, rowIndex, colIndex) {
                        if (value!='X'/*record.data.lista_doc.includes('LIB_MIL')*/) {
                            var checked = 'checked';
                        }

                        return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:20px;width:20px;" type="checkbox"  {0}></div>', checked);
                    }
                },
                type:'Checkbox',
                id_grupo: 0,
                grid: true,
                form:true
            },
            {
                config:{
                    name:'aviso_afiliacion',
                    fieldLabel:'Aviso Afiliación',
                    allowBlank: true,
                    anchor: '70%',
                    gwidth: 100,

                    renderer: function (value, p, record, rowIndex, colIndex) {
                        if (value!='X'/*record.data.lista_doc.includes('AVISO_FILIA')*/) {
                            var checked = 'checked';
                        }

                        return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:20px;width:20px;" type="checkbox"  {0}></div>', checked);
                    }
                },
                type:'Checkbox',
                id_grupo: 0,
                grid: true,
                form:true
            },
            {
                config:{
                    name:'examen_pre',
                    fieldLabel:'Ex. Preocupacional',
                    allowBlank: true,
                    anchor: '70%',
                    gwidth: 110,

                    renderer: function (value, p, record, rowIndex, colIndex) {
                        if (value!='X'/*record.data.lista_doc.includes('EXA_PREOC')*/) {
                            var checked = 'checked';
                        }

                        return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:20px;width:20px;" type="checkbox"  {0}></div>', checked);
                    }
                },
                type:'Checkbox',
                id_grupo: 0,
                grid: true,
                form:true
            },
            {
                config:{
                    name:'carnet_asegurado',
                    fieldLabel:'Carnet Asegurado',
                    allowBlank: true,
                    anchor: '70%',
                    gwidth: 100,

                    renderer: function (value, p, record, rowIndex, colIndex) {
                        if (value!='X'/*record.data.lista_doc.includes('CARN_ASEG')*/) {
                            var checked = 'checked';
                        }

                        return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:20px;width:20px;" type="checkbox"  {0}></div>', checked);
                    }
                },
                type:'Checkbox',
                id_grupo: 0,
                grid: true,
                form:true
            },
            {
                config:{
                    name:'carnet_discapacidad',
                    fieldLabel:'Carnet Cap. Diferente',
                    allowBlank: true,
                    anchor: '70%',
                    gwidth: 125,

                    renderer: function (value, p, record, rowIndex, colIndex) {
                        if (value!='X'/*record.data.lista_doc.includes('CAR_DIS')*/) {
                            var checked = 'checked';
                        }

                        return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:20px;width:20px;" type="checkbox"  {0}></div>', checked);
                    }
                },
                type:'Checkbox',
                id_grupo: 0,
                grid: true,
                form:true
            },
            {
                config:{
                    name:'felcc',
                    fieldLabel:'Felcc',
                    allowBlank: true,
                    anchor: '70%',
                    gwidth: 50,

                    renderer: function (value, p, record, rowIndex, colIndex) {
                        if (value!='X'/*record.data.lista_doc.includes('FELCC')*/) {
                            var checked = 'checked';
                        }

                        return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:20px;width:20px;" type="checkbox"  {0}></div>', checked);
                    }
                },
                type:'Checkbox',
                id_grupo: 0,
                grid: true,
                form:true
            },
            {
                config:{
                    name:'felcn',
                    fieldLabel:'Felcn',
                    allowBlank: true,
                    anchor: '70%',
                    gwidth: 50,

                    renderer: function (value, p, record, rowIndex, colIndex) {
                        if (value!='X'/*record.data.lista_doc.includes('FELCN')*/) {
                            var checked = 'checked';
                        }

                        return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:20px;width:20px;" type="checkbox"  {0}></div>', checked);
                    }
                },
                type:'Checkbox',
                id_grupo: 0,
                grid: true,
                form:true
            },
            {
                config:{
                    name:'declaracion_jurada',
                    fieldLabel:'1ra Declaración Jurada',
                    allowBlank: true,
                    anchor: '70%',
                    gwidth: 100,

                    renderer: function (value, p, record, rowIndex, colIndex) {
                        if (value!='X'/*record.data.lista_doc.includes('CONTRA')*/) {
                            var checked = 'checked';
                        }

                        return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:20px;width:20px;" type="checkbox"  {0}></div>', checked);
                    }
                },
                type:'Checkbox',
                id_grupo: 0,
                grid: true,
                form:true
            },
            {
                config:{
                    name:'sipasse',
                    fieldLabel:'Sipasse',
                    allowBlank: true,
                    anchor: '70%',
                    gwidth: 70,

                    renderer: function (value, p, record, rowIndex, colIndex) {
                        if (value!='X'/*record.data.lista_doc.includes('SIPASS')*/) {
                            var checked = 'checked';
                        }

                        return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:20px;width:20px;" type="checkbox"  {0}></div>', checked);
                    }
                },
                type:'Checkbox',
                id_grupo: 0,
                grid: true,
                form:true
            },
            {
                config:{
                    name:'dj_parentesco',
                    fieldLabel:'D.J. Parentesco',
                    allowBlank: true,
                    anchor: '70%',
                    gwidth: 100,

                    renderer: function (value, p, record, rowIndex, colIndex) {
                        if (value!='X'/*record.data.lista_doc.includes('DJ-PARENT')*/) {
                            var checked = 'checked';
                        }

                        return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:20px;width:20px;" type="checkbox"  {0}></div>', checked);
                    }
                },
                type:'Checkbox',
                id_grupo: 0,
                grid: true,
                form:true
            },
            {
                config:{
                    name:'dj_percepciones',
                    fieldLabel:'D.J. Percepciones',
                    allowBlank: true,
                    anchor: '70%',
                    gwidth: 100,

                    renderer: function (value, p, record, rowIndex, colIndex) {
                        if (value!='X'/*record.data.lista_doc.includes('DJ_PERS')*/) {
                            var checked = 'checked';
                        }

                        return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:20px;width:20px;" type="checkbox"  {0}></div>', checked);
                    }
                },
                type:'Checkbox',
                id_grupo: 0,
                grid: true,
                form:true
            },
            {
                config:{
                    name:'memorandum_designacion',
                    fieldLabel:'1er. Memo Designación',
                    allowBlank: true,
                    anchor: '70%',
                    gwidth: 100,

                    renderer: function (value, p, record, rowIndex, colIndex) {
                        if (value!='X'/*record.data.lista_doc.includes('DESIG')*/) {
                            var checked = 'checked';
                        }

                        return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:20px;width:20px;" type="checkbox"  {0}></div>', checked);
                    }
                },
                type:'Checkbox',
                id_grupo: 0,
                grid: true,
                form:true
            },
            {
                config:{
                    name:'memorandum_contrato',
                    fieldLabel:'1er. Memo Contrato',
                    allowBlank: true,
                    anchor: '70%',
                    gwidth: 110,

                    renderer: function (value, p, record, rowIndex, colIndex) {
                        if (value!='X'/*record.data.lista_doc.includes('CONTRATO')*/) {
                            var checked = 'checked';
                        }

                        return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:20px;width:20px;" type="checkbox"  {0}></div>', checked);
                    }
                },
                type:'Checkbox',
                id_grupo: 0,
                grid: true,
                form:true
            },
            {
                config:{
                    name:'declaracion_herederos',
                    fieldLabel:'Declaración Herederos',
                    allowBlank: true,
                    anchor: '70%',
                    gwidth: 120,

                    renderer: function (value, p, record, rowIndex, colIndex) {
                        if (value!='X'/*record.data.lista_doc.includes('DECL-HER')*/) {
                            var checked = 'checked';
                        }

                        return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:20px;width:20px;" type="checkbox"  {0}></div>', checked);
                    }
                },
                type:'Checkbox',
                id_grupo: 0,
                grid: true,
                form:true
            },

            {
                config:{
                    name:'finiquito',
                    fieldLabel:'Finiquito',
                    allowBlank: true,
                    anchor: '70%',
                    gwidth: 60,
                    renderer: function (value, p, record, rowIndex, colIndex) {
                        if (value!='X'/*record.data.lista_doc.includes('FINIQ')*/) {
                            var checked = 'checked';
                        }
                        return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:20px;width:20px;" type="checkbox"  {0}></div>', checked);
                    }
                },
                type:'Checkbox',
                id_grupo: 0,
                grid: true,
                form:true
            },
            {
                config:{
                    name:'carta_despido',
                    fieldLabel:'Carta Despido',
                    allowBlank: true,
                    anchor: '70%',
                    gwidth: 90,
                    renderer: function (value, p, record, rowIndex, colIndex) {
                        if (value!='X'/*record.data.lista_doc.includes('CART_DESP')*/) {
                            var checked = 'checked';
                        }
                        return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:20px;width:20px;" type="checkbox"  {0}></div>', checked);
                    }
                },
                type:'Checkbox',
                id_grupo: 0,
                grid: true,
                form:true
            },
            {
                config:{
                    name:'conclusion_contrato',
                    fieldLabel:'Conclusión Contrato',
                    allowBlank: true,
                    anchor: '70%',
                    gwidth: 110,
                    renderer: function (value, p, record, rowIndex, colIndex) {
                        if (value!='X'/*record.data.lista_doc.includes('CONC_CONTR')*/) {
                            var checked = 'checked';
                        }
                        return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:20px;width:20px;" type="checkbox"  {0}></div>', checked);
                    }
                },
                type:'Checkbox',
                id_grupo: 0,
                grid: true,
                form:true
            },
            {
                config:{
                    name:'desvinculacion_prueba',
                    fieldLabel:'Desvinculación Prueba',
                    allowBlank: true,
                    anchor: '70%',
                    gwidth: 130,
                    renderer: function (value, p, record, rowIndex, colIndex) {
                        if (value!='X'/*record.data.lista_doc.includes('DESV_PER_PRUE')*/) {
                            var checked = 'checked';
                        }
                        return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:20px;width:20px;" type="checkbox"  {0}></div>', checked);
                    }
                },
                type:'Checkbox',
                id_grupo: 0,
                grid: true,
                form:true
            },
            {
                config:{
                    name:'otro_retiro',
                    fieldLabel:'Otros Tip. Retiro',
                    allowBlank: true,
                    anchor: '70%',
                    gwidth: 100,
                    renderer: function (value, p, record, rowIndex, colIndex) {
                        if (value!='X'/*record.data.lista_doc.includes('OTR_RET')*/) {
                            var checked = 'checked';
                        }
                        return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:20px;width:20px;" type="checkbox"  {0}></div>', checked);
                    }
                },
                type:'Checkbox',
                id_grupo: 0,
                grid: true,
                form:true
            },
            {
                config:{
                    name:'aviso_bajaf',
                    fieldLabel:'Aviso Baja Filiacion',
                    allowBlank: true,
                    anchor: '70%',
                    gwidth: 110,
                    renderer: function (value, p, record, rowIndex, colIndex) {
                        if (value!='X'/*record.data.lista_doc.includes('BAJA')*/) {
                            var checked = 'checked';
                        }
                        return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:20px;width:20px;" type="checkbox"  {0}></div>', checked);
                    }
                },
                type:'Checkbox',
                id_grupo: 0,
                grid: true,
                form:true
            },
            {
                config:{
                    name:'sumario',
                    fieldLabel:'Sumario',
                    allowBlank: true,
                    anchor: '70%',
                    gwidth: 60,
                    renderer: function (value, p, record, rowIndex, colIndex) {
                        if (value!='X'/*record.data.lista_doc.includes('SUM')*/) {
                            var checked = 'checked';
                        }
                        return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:20px;width:20px;" type="checkbox"  {0}></div>', checked);
                    }
                },
                type:'Checkbox',
                id_grupo: 0,
                grid: true,
                form:true
            },
            {
                config:{
                    name:'pendientes_extrabajadores',
                    fieldLabel:'Pendientes Extrabajadores',
                    allowBlank: true,
                    anchor: '70%',
                    gwidth: 140,
                    renderer: function (value, p, record, rowIndex, colIndex) {
                        if (value!='X'/*record.data.lista_doc.includes('PEN-EX-TRA')*/) {
                            var checked = 'checked';
                        }
                        return String.format('<div style="vertical-align:middle;text-align:center;"><input style="height:20px;width:20px;" type="checkbox"  {0}></div>', checked);
                    }
                },
                type:'Checkbox',
                id_grupo: 0,
                grid: true,
                form:true
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
                    pfiltro:'tf.estado_reg',
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
                type:'NumberField',
                filters:{pfiltro:'usu1.cuenta',type:'string'},
                id_grupo:0,
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
                filters:{pfiltro:'conig.fecha_reg',type:'date'},
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
                    format: 'd/m/Y',
                    renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
                },
                type:'DateField',
                filters:{pfiltro:'conig.fecha_mod',type:'date'},
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
                type:'NumberField',
                filters:{pfiltro:'usu2.cuenta',type:'string'},
                id_grupo:0,
                grid:true,
                form:false
            }
        ],
        tam_pag: 50,
        arrayDefaultColumHidden:[
            'usr_reg',
            'fecha_reg','fecha_mod','usr_mod'
        ],
        fields: [
            {name:'id_funcionario', type: 'numeric'},
            {name:'id_biometrico', type: 'numeric'},
            {name:'ci', type: 'numeric'},
            //{name:'lista_doc', type: 'string'},
            {name:'fotografia', type: 'string'},
            {name:'diploma_academico', type: 'string'},
            {name:'titulo_bachiller', type: 'string'},
            {name:'titulo_profesional', type: 'string'},
            {name:'titulo_maestria', type: 'string'},
            {name:'titulo_doctorado', type: 'string'},
            {name:'certificado_egreso', type: 'string'},
            {name:'carnet_identidad', type: 'string'},
            {name:'certificado_nacimiento', type: 'string'},
            {name:'certificado_matrimonio', type: 'string'},
            {name:'libreta_militar', type: 'string'},
            {name:'aviso_afiliacion', type: 'string'},
            {name:'examen_pre', type: 'string'},
            {name:'carnet_asegurado', type: 'string'},
            {name:'carnet_discapacidad', type: 'string'},
            {name:'felcc', type: 'string'},
            {name:'felcn', type: 'string'},
            {name:'declaracion_jurada', type: 'string'},
            {name:'sipasse', type: 'string'},
            {name:'dj_parentesco', type: 'string'},
            {name:'dj_percepciones', type: 'string'},
            {name:'memorandum_designacion', type: 'string'},
            {name:'memorandum_contrato', type: 'string'},
            {name:'declaracion_herederos', type: 'string'},
            {name:'finiquito', type: 'string'},
            {name:'carta_despido', type: 'string'},
            {name:'conclusion_contrato', type: 'string'},
            {name:'desvinculacion_prueba', type: 'string'},
            {name:'otro_retiro', type: 'string'},
            {name:'aviso_bajaf', type: 'string'},
            {name:'sumario', type: 'string'},
            {name:'pendientes_extrabajadores', type: 'string'},
            {name:'desc_funcionario', type: 'string'},
            {name:'cargo', type: 'string'},
            {name:'url_foto', type: 'string'},
            {name:'estado_reg', type: 'string'},
            {name: 'fecha_ingreso', type: 'date', dateFormat: 'Y-m-d'},
            {name:'fecha_finalizacion', type: 'date', dateFormat:'Y-m-d'},
            {name:'nombre_cargo', type: 'string'},
            {name:'nombre_oficina', type: 'string'},
            {name:'nombre_lugar_ofi', type: 'string'},
            {name:'id_uo', type: 'numeric'},
            {name:'descripcion', type: 'string'},
            {name:'id_lugar', type: 'numeric'},


        ],
        sortInfo:{
            field: 'tf.desc_funcionario2',
            direction: 'ASC'

        },
        /*onReloadPage: function (m) {

        },*/

        validarFiltros : function() {
			if (this.cmbGerencia.getValue() ) {

				return true;
			} else {
				return false;
			}
		},

    capturaFiltros : function(combo, record, index) {
      if(this.validarFiltros()){

        this.store.baseParams.id_uo=this.cmbGerencia.getValue();

        console.log('ENTRADA',this.store.baseParams.estado_func);
        //this.Cmp.id_funcionario.store.baseParams ={par_filtro: 'fu.desc_funcionario1', gerencia:gerencias};
          this.load();
      }

      //  this.load({params: {start: 0, limit: this.tam_pag}});




		},

    cmbGerencia: new Ext.form.ComboBox({
        fieldLabel: 'Gerencia',
        grupo:[0,1,2],
        allowBlank: false,
        blankText:'Seleccione Gerencia',
        emptyText:'Seleccione Gerencia........',
        name:'id_uo',
        store:new Ext.data.JsonStore(
            {
                url: '../../sis_organigrama/control/Uo/listarUoIrva',
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
                baseParams:{par_filtro:'nombre_unidad',gerencia: 'si'/*, restringir: 'si'*/}
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
        preparaMenu: function(n)
        {	var rec = this.getSelectedData();
            var tb =this.tbar;

            this.getBoton('archivo').setDisabled(false);
            //this.getBoton('rep_archivo').setDisabled(false);
            Phx.vista.ConsultaDocumento.superclass.preparaMenu.call(this,n);

        },

        liberaMenu:function(){
            var tb = Phx.vista.ConsultaDocumento.superclass.liberaMenu.call(this);
            if(tb){

                this.getBoton('archivo').disable();
                //this.getBoton('rep_archivo').disable();
            }
            return tb
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

        repArchivo : function () {
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                url:'../../sis_organigrama/control/Funcionario/reporteDocumentos',
                params:{'id_usuario':0},
                success:this.successExport,
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });
        }
    });
</script>
