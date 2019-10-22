<?php
/**
 *@package pXP
 *@file gen-CertificadoEmetido.php
 *@author  (MMV)
 *@date 24-07-2017 14:48:34
 *@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 *Correcciones-Adiciones: BVP
 */

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.CertificadoEmetido = {
        require: '../../../sis_organigrama/vista/certificado_planilla/Certificado.php',
        requireclase: 'Phx.vista.Certificado',
        title: 'CertificadoPlanilla',
        nombreVista: 'CertificadoEmitido',
        constructor: function (config) {        	   
       this.tbarItems = ['-',this.cmbGestion,'-'];
            Phx.vista.CertificadoEmetido.superclass.constructor.call(this, config);            
            this.cmbGestion.on('select',this.capturarEventos, this);
            console.log('atributos=> ',this.Atributos);
            
            this.Atributos[this.getIndAtributo('firma_digital')].grid=false;                                                       
            this.store.baseParams={tipo_interfaz:this.nombreVista};
            var date = new Date();
            this.store.baseParams.gestion = date.getFullYear();
            this.load({params:{start:0, limit:this.tam_pag}});

        },
        preparaMenu:function(n){
            var data = this.getSelectedData();
            var tb =this.tbar;            
            Phx.vista.CertificadoEmetido.superclass.preparaMenu.call(this,n);

            if( data['impreso'] ==  'no'){
                this.getBoton('btnImprimir').enable();
                this. enableTabDetalle();
            }
            return tb;
        },

        liberaMenu:function(){
            var tb = Phx.vista.CertificadoEmetido.superclass.liberaMenu.call(this);
            if(tb){
                this.getBoton('btnImprimir').disable();
                this.getBoton('btnChequeoDocumentosWf').setVisible(false);
                this.getBoton('ant_estado').setVisible(false);
                this.getBoton('sig_estado').setVisible(false);
                this.getBoton('FirmaDigital').setVisible(false);

            }
            return tb;
        },
        cmbGestion: new Ext.form.ComboBox({
                name: 'gestion',                
                fieldLabel: 'Gestion',
                allowBlank: true,
                emptyText: 'Gestion...',                
                blankText: 'Año',
                editable: false,
                store: new Ext.data.JsonStore(
                    {
                        url: '../../sis_parametros/control/Gestion/listarGestion',
                        id: 'id_gestion',
                        root: 'datos',
                        sortInfo: {
                            field: 'gestion',
                            direction: 'DESC'
                        },
                        totalProperty: 'total',
                        fields: ['gestion'],                        
                        remoteSort: true,
                        baseParams: {par_filtro: 'gestion'}
                    }),
                valueField: 'gestion',
                value: new Date().getFullYear(),
                triggerAction: 'all',
                displayField: 'gestion',
                hiddenName: 'gestion',
                mode: 'remote',
                pageSize: 50,
                queryDelay: 500,
                listWidth: '280',
                hidden: false,
                width: 80
            }),
    capturarEventos: function () {         	
        this.store.baseParams.gestion=this.cmbGestion.getValue();     
        this.load({params:{start:0, limit:this.tam_pag}});
    },        

        bnew:false,
        bedit:false,
        bdel:false
    }
</script>