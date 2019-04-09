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
    Phx.vista.ObligacionPagoVbExtranjero = {
        bedit:false,
        bnew:false,
        bsave:false,
        bdel:true,
        require:'../../../sis_tesoreria/vista/obligacion_pago/ObligacionPago.php',
        requireclase:'Phx.vista.ObligacionPago',
        title:'Obligacion de Pago (Vistos buenos Moneda Extranjera)',
        nombreVista: 'ObligacionPagoVb',

        gruposBarraTareas:[{name:'pago_unico',title:'<H1 align="center"><i class="fa fa-paper-plane"></i> Pago excepcional</h1>',grupo:0,height:0},
            {name:'pago_directo',title:'<H1 align="center"><i class="fa fa-paper-plane-o"></i> Pago Recurrentes</h1>',grupo:1,height:0},
            {name:'otros',title:'<H1 align="center"><i class="fa fa-plus-circle"></i> Otros</h1>',grupo:2,height:0}],


        actualizarSegunTab: function(name, indice){
            if(this.finCons){
                this.store.baseParams.pes_estado = name;
                this.store.baseParams.moneda_base = 'extranjero';
                this.load({params:{start:0, limit:this.tam_pag}});
            }
        },
        beditGroups: [0,1,2],
        bdelGroups:   [0,1,2],
        bactGroups:  [0,1,2],
        btestGroups:  [0,1,2],
        bexcelGroups: [0,1,2],
        /*
         *  Interface heredada para el sistema de adquisiciones para que el reposnable
         *  de adqusiciones registro los planes de pago , y ase por los pasos configurados en el WF
         *  de validacion, aprobacion y registro contable
         * */

        constructor: function(config) {


            //funcionalidad para listado de historicos
            this.historico = 'no';
            this.tbarItems = ['-',{
                text: 'Histórico',
                enableToggle: true,
                pressed: false,
                toggleHandler: function(btn, pressed) {

                    if(pressed){
                        this.historico = 'si';
                        this.desBotoneshistorico();
                    }
                    else{
                        this.historico = 'no'
                    }

                    this.store.baseParams.historico = this.historico;
                    this.reload();
                },
                scope: this
            },'-',
                {xtype: 'label',text: 'Gestión:'},
                this.cmbGestion
            ];

            var fecha = new Date();
            Ext.Ajax.request({
                url:'../../sis_parametros/control/Gestion/obtenerGestionByFecha',
                params:{fecha:fecha.getDate()+'/'+(fecha.getMonth()+1)+'/'+fecha.getFullYear()},
                success:function(resp){
                    var reg =  Ext.decode(Ext.util.Format.trim(resp.responseText));
                    this.cmbGestion.setValue(reg.ROOT.datos.id_gestion);
                    this.cmbGestion.setRawValue(fecha.getFullYear());
                    this.store.baseParams.id_gestion=reg.ROOT.datos.id_gestion;
                    this.load({params:{start:0, limit:this.tam_pag}});
                },
                failure: this.conexionFailure,
                timeout:this.timeout,
                scope:this
            });


            Phx.vista.ObligacionPagoVbExtranjero.superclass.constructor.call(this,config);

            this.cmbGestion.on('select', this.capturarEventos, this);
            this.crearFormObs();
            //this.addBotonesPresupuestos();
            this.addButton('obs_presu',{
                grupo:[0,1,2],
                iconCls: 'bedit',
                text:'Inf. Presu.',
                disabled:true,
                handler: this.initObs,
                tooltip: '<b>Observacioens del área de presupuesto</b>'
            });


            this.store.baseParams.pes_estado = 'pago_unico';
            this.store.baseParams.moneda_base = 'extranjero';
            this.load({params:{start:0, limit:this.tam_pag}});
            this.finCons = true;

        },

        /*addBotonesPresupuestos: function () {
            this.menuPres = new Ext.Toolbar.SplitButton({
                text: 'Presupuesto',
                disabled: true,
                grupo: [0, 1, 2],
                iconCls: 'bedit',
                handler: this.initObs,
                scope: this,
                menu: {
                    items: [{
                        id: 'fecha_presu',
                        text: 'Gantt Imagen',
                        tooltip: '<b>Muestra formulario para fecha Certificación Presupuestaria</b>',
                        handler: this.initObs,
                        scope: this
                    }, {
                        id: 'obs_presu',
                        text: 'Obs. Pres.',
                        tooltip: '<b>Observacioens del área de presupuesto</b>',
                        handler: this.initObs,
                        scope: this
                    }
                    ]
                }
            });
            this.tbar.add(this.menuPres);
        },*/
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
        antEstado:function(res){
            var rec=this.sm.getSelected();
            Phx.CP.loadWindows('../../../sis_workflow/vista/estado_wf/AntFormEstadoWf.php',
                'Estado de Wf',
                {
                    modal:true,
                    width:450,
                    height:250

                },
                {
                    data:rec.data,
                    estado_destino: res.argument.estado
                },
                this.idContenedor,
                'AntFormEstadoWf',
                {
                    config:[{
                        event:'beforesave',
                        delegate: this.onAntEstado,
                    }
                    ],
                    scope:this
                }
            );
        },
        onAntEstado: function(wizard,resp){
            Phx.CP.loadingShow();


            Ext.Ajax.request({
                url:'../../sis_tesoreria/control/ObligacionPago/anteriorEstadoObligacionPago',
                params:{
                    id_proceso_wf: resp.id_proceso_wf,
                    id_estado_wf:  resp.id_estado_wf,
                    obs: resp.obs,
                    operacion: resp.estado_destino,
                    id_obligacion_pago: resp.data.id_obligacion_pago
                },
                argument:{wizard:wizard},
                success: function (resp) {
                    Phx.CP.loadingHide();
                    resp.argument.wizard.panel.destroy();
                    this.reload();
                },
                failure: this.conexionFailure,
                timeout: this.timeout,
                scope: this
            });

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

        crearFormObs:function(){

            this.formObs = new Ext.form.FormPanel({
                baseCls: 'x-plain',
                autoDestroy: true,
                border: false,
                layout: 'form',
                autoHeight: true,
                labelAlign: 'top',
                items: [
                    {
                        name: 'fecha_certificacion',
                        xtype: 'datefield',
                        fieldLabel: 'Fecha Certificación Presupuestaria',
                        allowBlank: true,
                        anchor: '50%',
                        msgTarget: 'side'
                    },
                    {
                        name: 'obs',
                        xtype: 'textarea',
                        fieldLabel: 'Obs',
                        grow: true,
                        growMin : '60%',
                        allowBlank: true,
                        value:'',
                        anchor: '100%',
                        maxLength:500
                    }
                ]
            });


            this.wObs = new Ext.Window({
                title: 'Información Presupuestos',
                collapsible: true,
                maximizable: true,
                autoDestroy: true,
                width: 380,
                height: 290,
                layout: 'fit',
                plain: true,
                bodyStyle: 'padding:5px;',
                buttonAlign: 'center',
                items: this.formObs,
                modal:true,
                closeAction: 'hide',
                buttons: [{
                    text: 'Guardar',
                    handler: this.submitObsPresupuestos,
                    scope: this

                },
                    {
                        text: 'Cancelar',
                        handler:function(){this.wObs.hide()},
                        scope:this
                    }]
            });

            this.cmbObsPres = this.formObs.getForm().findField('obs');
            this.cmbFechaPres = this.formObs.getForm().findField('fecha_certificacion');
        },

        initObs:function(){
            var d= this.sm.getSelected().data;
            this.cmbObsPres.setValue(d.obs_presupuestos);
            if(d.tipo_obligacion == 'pbr'){
                this.cmbFechaPres.setVisible(true);
                this.cmbFechaPres.setValue(d.fecha_certificacion_pres);
            }else{
                this.cmbFechaPres.setVisible(false);
            }

            this.wObs.show()
        },

        submitObsPresupuestos:function(){
            Phx.CP.loadingShow();
            var d= this.sm.getSelected().data;
            Ext.Ajax.request({
                url: '../../sis_tesoreria/control/ObligacionPago/modificarObsPresupuestos',
                params: {
                    id_obligacion_pago: d.id_obligacion_pago,
                    obs: this.cmbObsPres.getValue(),
                    fecha_cer_pres: this.cmbFechaPres.getValue()
                },
                success: function(resp){
                    Phx.CP.loadingHide();
                    var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
                    if(!reg.ROOT.error){
                        this.reload();
                        this.wObs.hide();
                    }
                },
                failure: function(resp1,resp2,resp3){

                    this.conexionFailure(resp1,resp2,resp3);
                    var d= this.sm.getSelected().data;


                },
                timeout:this.timeout,
                scope:this
            });

        },

        preparaMenu:function(n){
            var data = this.getSelectedData();
            var tb =this.tbar;
            Phx.vista.ObligacionPagoVbExtranjero.superclass.preparaMenu.call(this,n);
            this.getBoton('ini_estado').enable();
            if(this.historico == 'no'){
                this.getBoton('obs_presu').enable();
            }
            else{
                this.desBotoneshistorico()
            }

        },
        liberaMenu:function(){

            var tb = Phx.vista.ObligacionPagoVbExtranjero.superclass.liberaMenu.call(this);
            this.getBoton('ini_estado').disable();
            if(tb){
                this.getBoton('obs_presu').disable();
            }

            return tb
        },
        desBotoneshistorico:function(){

            this.getBoton('fin_registro').disable();
            this.getBoton('ant_estado').disable();
            this.getBoton('reporte_com_ejec_pag').enable();
            this.getBoton('reporte_plan_pago').enable();
            this.getBoton('diagrama_gantt').enable();
            this.getBoton('btnChequeoDocumentosWf').enable();
            this.getBoton('ajustes').disable();
            this.getBoton('est_anticipo').disable();
            this.getBoton('extenderop').disable();
            this.getBoton('btnCheckPresupeusto').enable();

        },
        tabsouth:[
            {
                url:'../../../sis_tesoreria/vista/obligacion_det/ObligacionDet.php',
                title:'Detalle',
                height:'50%',
                cls:'ObligacionDet'
            }

        ],

    };
</script>