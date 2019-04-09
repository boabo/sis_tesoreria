<?php
/**
 *@package pXP
 *@file gen-SistemaDist.php
 *@author  (fprudencio)
 *@date 20-09-2011 10:22:05
 *@description Archivo con la interfaz de usuario que permite
 *dar el visto a solicitudes de compra
 *
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.ObligacionPagoAdq = {
        bedit:true,
        bnew:false,
        bsave:false,
        bdel:true,
        require:'../../../sis_tesoreria/vista/obligacion_pago/ObligacionPago.php',
        requireclase:'Phx.vista.ObligacionPago',
        title:'Obligacion de Pago(Adquisiciones)',
        nombreVista: 'obligacionPagoAdq',
        /*
         *  Interface heredada para el sistema de adquisiciones para que el reposnable
         *  de adqusiciones registro los planes de pago , y ase por los pasos configurados en el WF
         *  de validacion, aprobacion y registro contable
         * */

        constructor: function(config) {
            this.tbarItems = ['-',
                {xtype: 'label', text: 'Gestión:'},
                this.cmbGestion
            ];
            var fecha = new Date();
            Ext.Ajax.request({
                url: '../../sis_parametros/control/Gestion/obtenerGestionByFecha',
                params: {fecha: fecha.getDate() + '/' + (fecha.getMonth() + 1) + '/' + fecha.getFullYear()},
                success: function (resp) {
                    var reg = Ext.decode(Ext.util.Format.trim(resp.responseText));
                    this.cmbGestion.setValue(reg.ROOT.datos.id_gestion);
                    this.cmbGestion.setRawValue(fecha.getFullYear());
                    this.store.baseParams.id_gestion = reg.ROOT.datos.id_gestion;
                    this.load({params: {start: 0, limit: this.tam_pag}});
                },
                failure: this.conexionFailure,
                timeout: this.timeout,
                scope: this
            });

            Phx.vista.ObligacionPagoAdq.superclass.constructor.call(this,config);
            this.getBoton('ini_estado').setVisible(false);
            this.Cmp.id_contrato.allowBlank = true;
            this.cmbGestion.on('select', this.capturarEventos, this);



            this.addButton('btnConformidad', {
                text: 'Conformidad',
                grupo: [0, 1],
                iconCls: 'bok',
                disabled: true,
                handler: this.onButtonConformidad,
                tooltip: 'Generar Conformidad Final'
            });

        },
        cmbGestion: new Ext.form.ComboBox({
            name: 'gestion',
            fieldLabel: 'Gestion',
            allowBlank: true,
            emptyText: 'Gestion...',
            blankText: 'Año',
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
                    fields: ['id_gestion', 'gestion'],
                    // turn on remote sorting
                    remoteSort: true,
                    baseParams: {par_filtro: 'gestion'}
                }),
            valueField: 'id_gestion',
            triggerAction: 'all',
            displayField: 'gestion',
            hiddenName: 'id_gestion',
            mode: 'remote',
            pageSize: 50,
            queryDelay: 500,
            listWidth: '280',
            hidden: false,
            width: 80
        }),
        capturarEventos: function () {
            this.store.baseParams.id_gestion = this.cmbGestion.getValue();

            this.load({params: {start: 0, limit: this.tam_pag}});
        },

        creaFormularioConformidad: function () {

            this.formConformidad = new Ext.form.FormPanel({
                id: this.idContenedor + '_CONFOR',
                items: [
                    new Ext.form.DateField({
                        fieldLabel: 'Fecha Conformidad Final',
                        format: 'd/m/Y',
                        name: 'fecha_conformidad_final',
                        allowBlank: false,
                        width: '95%'
                    }),
                    new Ext.form.TextArea({
                        fieldLabel: 'Conformidad Final',
                        name: 'conformidad_final',
                        height: 60,
                        allowBlank: false,
                        width: '95%'
                    }),
                    new Ext.form.DateField({
                        fieldLabel: 'Fecha Inicio',
                        format: 'd/m/Y',
                        name: 'fecha_inicio',
                        //height: 150,
                        allowBlank: true,
                        width: '95%'
                    }),
                    new Ext.form.DateField({
                        fieldLabel: 'Fecha Fin',
                        format: 'd/m/Y',
                        name: 'fecha_fin',
                        //height: 150,
                        allowBlank: true,
                        width: '95%'
                    }),
                    new Ext.form.TextArea({
                        fieldLabel: 'Observaciones',
                        name: 'observaciones',
                        height: 50,
                        allowBlank: true,
                        width: '95%'
                    })

                ],
                autoScroll: false,
                //height: this.fheight,
                autoDestroy: true,
                autoScroll: true
            });


            // Definicion de la ventana que contiene al formulario
            this.windowConformidad = new Ext.Window({
                // id:this.idContenedor+'_W',
                title: 'Datos Acta Conformidad Final',
                modal: true,
                width: 400,
                height: 400,
                bodyStyle: 'padding:5px;',
                layout: 'fit',
                hidden: true,
                autoScroll: false,
                maximizable: true,
                buttons: [{
                    text: 'Guardar',
                    arrowAlign: 'bottom',
                    handler: this.onSubmitConformidad,
                    argument: {
                        'news': false
                    },
                    scope: this

                },
                    {
                        text: 'Declinar',
                        handler: this.onDeclinarConformidad,
                        scope: this
                    }],
                items: this.formConformidad,
                // autoShow:true,
                autoDestroy: true,
                closeAction: 'hide'
            });
        },

        onButtonConformidad: function () {
            var data = this.sm.getSelected().data;
            this.creaFormularioConformidad();
            console.log('data', data);
            if (data['fecha_conformidad_final'] == '' || data['fecha_conformidad_final'] == undefined || data['fecha_conformidad_final'] == null) {
                console.log('a');
                this.formConformidad.getForm().findField('fecha_inicio').setValue(data.fecha_inicio);
                this.formConformidad.getForm().findField('fecha_fin').setValue(data.fecha_fin);
                this.formConformidad.getForm().findField('fecha_conformidad_final').setValue(data.fecha_conformidad_final);
                this.formConformidad.getForm().findField('conformidad_final').setValue(data.conformidad_final);
                this.formConformidad.getForm().findField('observaciones').setValue(data.observaciones);
                this.windowConformidad.show();
            } else {
                console.log('b');
                Ext.Msg.show({
                    title: 'Alerta',
                    scope: this,
                    msg: 'El acta de Conformidad Final ya se encuentra firmada. Esta seguro de volver a firmar?',
                    buttons: Ext.Msg.YESNO,
                    fn: function (id, value, opt) {
                        if (id == 'yes') {
                            this.formConformidad.getForm().findField('fecha_inicio').setValue(data.fecha_inicio);
                            console.log('conformidad 1', this.formConformidad.getForm().findField('fecha_inicio').getValue());
                            this.formConformidad.getForm().findField('fecha_fin').setValue(data.fecha_fin);
                            console.log('conformidad 2', this.formConformidad.getForm().findField('fecha_fin').getValue());
                            this.formConformidad.getForm().findField('fecha_conformidad_final').setValue(data.fecha_conformidad_final);
                            this.formConformidad.getForm().findField('conformidad_final').setValue(data.conformidad_final);
                            this.formConformidad.getForm().findField('observaciones').setValue(data.observaciones);
                            this.windowConformidad.show();
                        }
                    },
                    animEl: 'elId',
                    icon: Ext.MessageBox.WARNING
                }, this);
            }

        },
        onSubmitConformidad: function () {

            var d = this.sm.getSelected().data;
            Phx.CP.loadingShow();
            Ext.Ajax.request({
                //url:'../../sis_tesoreria/control/PlanPago/generarConformidad',
                url: '../../sis_tesoreria/control/Conformidad/generarConformidadFinal',
                success: this.successConformidad,
                failure: this.failureConformidad,
                params: {
                    //'id_conformidad': d.id_conformidad,
                    'id_obligacion_pago': d.id_obligacion_pago,
                    'fecha_inicio': this.formConformidad.getForm().findField('fecha_inicio').getValue(),
                    'fecha_fin': this.formConformidad.getForm().findField('fecha_fin').getValue(),
                    'conformidad_final': this.formConformidad.getForm().findField('conformidad_final').getValue(),
                    'fecha_conformidad_final': this.formConformidad.getForm().findField('fecha_conformidad_final').getValue().dateFormat('d/m/Y'),
                    'observaciones': this.formConformidad.getForm().findField('observaciones').getValue()
                },
                timeout: this.timeout,
                scope: this

            });

        },

        successConformidad: function (resp) {
            this.windowConformidad.hide();
            Phx.vista.ObligacionPagoAdq.superclass.successDel.call(this, resp);
        },

        failureConformidad: function (resp) {
            Phx.CP.loadingHide();
            Phx.vista.ObligacionPagoAdq.superclass.conexionFailure.call(this, resp);
        },

        onDeclinarConformidad: function () {
            this.windowConformidad.hide();
        },

        tabsouth:[
            {
                url:'../../../sis_tesoreria/vista/obligacion_det/ObligacionDet.php',
                title:'Detalle',
                height:'50%',
                cls:'ObligacionDet'
            },
            {
                //carga la interface de registro inicial
                url:'../../../sis_tesoreria/vista/plan_pago/PlanPagoRegIni.php',
                title:'Plan de Pagos (Reg. Adq.)',
                height:'50%',
                cls:'PlanPagoRegIni'
            }

        ],
        onButtonEdit:function(){

            var data= this.sm.getSelected().data;
            this.cmpTipoObligacion.disable();
            this.cmpDepto.disable();
            this.cmpFecha.disable();
            this.cmpTipoCambioConv.disable();
            this.Cmp.id_contrato.enable();

            Phx.vista.ObligacionPagoAdq.superclass.onButtonEdit.call(this);

            if(data.tipo_obligacion=='adquisiciones'){
                this.mostrarComponente(this.cmpProveedor);
                this.ocultarComponente(this.cmpFuncionario);
                this.ocultarComponente(this.cmpFuncionarioProveedor);
                this.cmpFuncionario.reset();
                this.cmpProveedor.disable();
                this.cmpMoneda.disable();
            }

            this.cmpProveedor.disable();
            this.cmpMoneda.disable();

            //segun el total nro cuota cero, ocultamos los componentes
            if(data.total_nro_cuota=='0'){
                this.ocultarComponente(this.Cmp.id_plantilla);
                this.ocultarComponente(this.Cmp.fecha_pp_ini);
                this.ocultarComponente(this.Cmp.rotacion);
            }
            else{
                this.mostrarComponente(this.Cmp.id_plantilla);
                this.mostrarComponente(this.Cmp.fecha_pp_ini);
                this.mostrarComponente(this.Cmp.rotacion);
            }

            if(data.estado != 'borrador'){
                this.Cmp.tipo_anticipo.disable();
                this.Cmp.total_nro_cuota.disable();
            }
            else{
                this.Cmp.tipo_anticipo.enable();
                this.Cmp.total_nro_cuota.enable();

            }

            this.Cmp.id_contrato.store.baseParams.filter = "[{\"type\":\"numeric\",\"comparison\":\"eq\", \"value\":\""+ this.Cmp.id_proveedor.getValue()+"\",\"field\":\"CON.id_proveedor\"}]";
            this.Cmp.id_contrato.modificado = true;

        },

        onButtonNew:function(){
            Phx.vista.ObligacionPagoAdq.superclass.onButtonNew.call(this);
            //this.cmpPorcAnticipo.setValue(0);
            //this.cmpPorcRetgar.setValue(0);


            this.cmpTipoObligacion.enable();
            this.cmpDepto.enable();
            this.mostrarComponente(this.cmpProveedor);
            this.ocultarComponente(this.cmpFuncionario);
            this.ocultarComponente(this.cmpFuncionarioProveedor);
            this.cmpFuncionario.reset();
            this.cmpFecha.enable();
            this.cmpTipoCambioConv.enable();
            this.cmpProveedor.enable();
            this.cmpDepto.enable();
            this.cmpMoneda.enable();
            //defecto total nro cuota cero, entoces ocultamos los componentes
            this.ocultarComponente(this.Cmp.id_plantilla);
            this.ocultarComponente(this.Cmp.fecha_pp_ini);
            this.ocultarComponente(this.Cmp.rotacion);

        },
        preparaMenu:function(n){
            var data = this.getSelectedData();
            var tb =this.tbar;


            Phx.vista.ObligacionPagoAdq.superclass.preparaMenu.call(this,n);
            this.getBoton('btnConformidad').enable();


        },
        liberaMenu:function(){
            var tb = Phx.vista.ObligacionPagoAdq.superclass.liberaMenu.call(this);
            if(tb){
                this.getBoton('btnConformidad').disable();

            }
            return tb
        },

    };
</script>
