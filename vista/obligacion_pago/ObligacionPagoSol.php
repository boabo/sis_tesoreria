<?php
/**
 * @package pXP
 * @file gen-SistemaDist.php
 * @author  (rarteaga)
 * @date 20-09-2011 10:22:05
 * @description Archivo con la interfaz de usuario que permite
 *dar el visto a solicitudes de compra
 *
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.ObligacionPagoSol = {
        require: '../../../sis_tesoreria/vista/obligacion_pago/ObligacionPago.php',
        requireclase: 'Phx.vista.ObligacionPago',
        title: 'Obligacion de Pago (Solicitudes individuales)',
        nombreVista: 'obligacionPagoSol',
        ActList: '../../sis_tesoreria/control/ObligacionPago/listarObligacionPagoSol',

        /*
         *  Interface heredada para solicitantes individuales
         *  de tesoreria
         * */

        constructor: function (config) {

            this.tbarItems = ['-',
                'Gestión:', this.cmbGestion, '-'
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

            this.Atributos[this.getIndAtributo('id_depto')].config.url = '../../sis_parametros/control/Depto/listarDeptoFiltradoXUsuario';
            this.Atributos[this.getIndAtributo('id_depto')].config.baseParams = {
                estado: 'activo',
                codigo_subsistema: 'TES',
                modulo: 'OP'
            },
                this.Atributos[this.getIndAtributo('id_funcionario')].grid = true;
            this.Atributos[this.getIndAtributo('id_funcionario')].form = true;

            Phx.vista.ObligacionPagoSol.superclass.constructor.call(this, config);
            this.getBoton('ini_estado').setVisible(false);

            this.cmbGestion.on('select', this.capturarEventos, this);


        },

        cmbGestion: new Ext.form.ComboBox({
            //name: 'gestion',
            // id: 'gestion_reg',
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
            pageSize: 5,
            queryDelay: 500,
            listWidth: '280',
            hidden: false,
            width: 80
        }),
        capturarEventos: function () {
            this.store.baseParams.id_gestion = this.cmbGestion.getValue();

            this.load({params: {start: 0, limit: this.tam_pag}});
        },

        tabsouth: [
            {
                url: '../../../sis_tesoreria/vista/obligacion_det/ObligacionDet.php',
                title: 'Detalle',
                height: '50%',
                cls: 'ObligacionDet'
            },
            {
                //carga la interface de registro inicial
                url: '../../../sis_tesoreria/vista/plan_pago/PlanPagoRegIni.php',
                title: 'Plan de Pagos (Reg. Adq.)',
                height: '50%',
                cls: 'PlanPagoRegIni'
            }

        ],

        iniciarEventos: function () {
            this.cmpProveedor = this.getComponente('id_proveedor');
            this.cmpFuncionario = this.getComponente('id_funcionario');
            this.cmpFuncionarioProveedor = this.getComponente('funcionario_proveedor');
            this.cmpFecha = this.getComponente('fecha');
            this.cmpTipoObligacion = this.getComponente('tipo_obligacion');
            this.cmpMoneda = this.getComponente('id_moneda');
            this.cmpDepto = this.getComponente('id_depto');
            this.cmpTipoCambioConv = this.getComponente('tipo_cambio_conv');


            this.cmpFecha.on('change', function (f) {
                Phx.CP.loadingShow();
                this.cmpFuncionario.reset();
                this.cmpFuncionario.enable();
                this.cmpFuncionario.store.baseParams.fecha = this.cmpFecha.getValue().dateFormat(this.cmpFecha.format);

                this.cmpFuncionario.store.load({
                    params: {start: 0, limit: this.tam_pag},
                    callback: function (r) {
                        Phx.CP.loadingHide();
                        if (r.length == 1) {
                            this.cmpFuncionario.setValue(r[0].data.id_funcionario);
                            this.cmpFuncionario.fireEvent('select', this.cmpFuncionario, r[0]);
                        }

                    }, scope: this
                });


            }, this);


            this.Cmp.id_funcionario.on('select', function (combo, record, index) {

                if (!record.data.id_lugar) {
                    alert('El funcionario no tiene oficina definida');
                    return
                }

                this.Cmp.id_depto.reset();
                this.Cmp.id_depto.store.baseParams.id_lugar = record.data.id_lugar;
                this.Cmp.id_depto.modificado = true;
                this.Cmp.id_depto.enable();

                this.Cmp.id_depto.store.load({
                    params: {start: 0, limit: this.tam_pag},
                    callback: function (r) {
                        if (r.length == 1) {
                            this.Cmp.id_depto.setValue(r[0].data.id_depto);
                        }

                    }, scope: this
                });


            }, this);

            this.ocultarComponente(this.cmpProveedor);
            this.mostrarComponente(this.cmpFuncionario);
            this.ocultarComponente(this.cmpFuncionarioProveedor);

            this.cmpMoneda.on('select', function (com, dat) {

                if (dat.data.tipo_moneda == 'base') {
                    this.cmpTipoCambioConv.disable();
                    this.cmpTipoCambioConv.setValue(1);

                }
                else {
                    this.cmpTipoCambioConv.enable()
                    this.obtenerTipoCambio();
                }


            }, this);

            this.cmpTipoObligacion.on('select', function (c, rec, ind) {

                n = rec.data.variable;

                if (n == 'adquisiciones' || n == 'pago_directo') {
                    this.cmpProveedor.enable();
                    this.mostrarComponente(this.cmpProveedor);
                    this.mostrarComponente(this.cmpFuncionario);
                    this.ocultarComponente(this.cmpFuncionarioProveedor);
                    this.cmpFuncionario.reset();
                } else {
                    if (n == 'viatico' || n == 'fondo_en_avance') {
                        this.cmpFuncionario.enable();
                        this.mostrarComponente(this.cmpFuncionario);
                        this.ocultarComponente(this.cmpProveedor);
                        this.ocultarComponente(this.cmpFuncionarioProveedor);
                        this.cmpProveedor.reset();
                    }
                    else {
                        this.cmpFuncionarioProveedor.reset();
                        this.cmpFuncionarioProveedor.enable();
                        this.mostrarComponente(this.cmpFuncionarioProveedor);
                        this.mostrarComponente(this.cmpFuncionario);
                        this.ocultarComponente(this.cmpProveedor);
                        this.cmpFuncionarioProveedor.on('change', function (groupRadio, radio) {
                            this.enableDisable(radio.inputValue);
                        }, this);
                    }
                }
            }, this);


            //validaciones para registro de plan de pagos por defecto
            //this.Cmp.total_nro_cuota.setValue(0);

            this.ocultarComponente(this.Cmp.id_plantilla);
            this.ocultarComponente(this.Cmp.fecha_pp_ini);
            this.ocultarComponente(this.Cmp.rotacion);

            this.Cmp.total_nro_cuota.on('change', function (cmp, newValue, oldValue) {

                if (newValue > 0) {
                    this.mostrarComponente(this.Cmp.id_plantilla);
                    this.mostrarComponente(this.Cmp.fecha_pp_ini);
                    this.mostrarComponente(this.Cmp.rotacion);
                }
                else {
                    this.ocultarComponente(this.Cmp.id_plantilla);
                    this.ocultarComponente(this.Cmp.fecha_pp_ini);
                    this.ocultarComponente(this.Cmp.rotacion);
                }

            }, this);


            this.Cmp.id_proveedor.on('select', function (cmb, rec, ind) {
                // var fecha = this.Cmp.fecha.getValue().toLocaleDateString();
                var fecha = this.Cmp.fecha.getValue();

                var dd = fecha.getDate();
                var mm = fecha.getMonth() + 1; //January is 0!
                var yyyy = fecha.getFullYear();
                if (dd < 10) {
                    dd = '0' + dd;
                }
                if (mm < 10) {
                    mm = '0' + mm;
                }

                var today = dd + '/' + mm + '/' + yyyy;


                var anio = this.Cmp.fecha.getValue();
                anio = anio.getFullYear();
                console.log(fecha, anio);
                this.Cmp.id_contrato.enable();
                this.Cmp.id_contrato.reset();
                this.Cmp.id_contrato.store.baseParams.filter = "[{\"type\":\"numeric\",\"comparison\":\"eq\", \"value\":\"" + cmb.getValue() + "\",\"field\":\"CON.id_proveedor\"}]";
                //this.Cmp.id_contrato.store.baseParams.filtro_directo = "((CON.fecha_fin is null) or ((con.fecha_fin + interval ''3 month'')::date >= now()::date))";
                // this.Cmp.id_contrato.store.baseParams.filtro_directo = "((CON.fecha_fin is null) or ((con.fecha_fin)::date >= now()::date))";
                this.Cmp.id_contrato.store.baseParams.filtro_directo = "(((CON.fecha_fin is null) or (con.fecha_fin + interval ''15 day'' )::date >= (''" + today + "''::date)) and (pw.nro_tramite LIKE ''LEGAL%'' or ((pw.nro_tramite LIKE ''CI%'' or pw.nro_tramite LIKE ''CN%'')  and  (ges.gestion < ''"+ anio +"''))))";
                this.Cmp.id_contrato.modificado = true;

            }, this);

            this.Cmp.id_funcionario.on('select', function (combo, record, index) {
                if (!record.data.id_lugar) {
                    alert('El funcionario no tiene oficina definida');
                }
                this.Cmp.id_depto.reset();
                this.Cmp.id_depto.store.baseParams.id_lugar = record.data.id_lugar;
                this.Cmp.id_depto.modificado = true;
                this.Cmp.id_depto.enable();
            }, this);

            this.Cmp.fecha.on('select', function (cmp, rec, ind) {
                this.Cmp.id_proveedor.reset();
                this.Cmp.id_proveedor.modificado = true;
            }, this);

        },

        onButtonEdit: function () {

            var data = this.sm.getSelected().data;
            this.cmpTipoObligacion.disable();
            this.cmpDepto.disable();
            this.cmpFecha.disable();
            this.cmpTipoCambioConv.disable();
            this.cmpMoneda.disable();
            this.Cmp.id_funcionario.disable()
            this.mostrarComponente(this.cmpProveedor);

            Phx.vista.ObligacionPagoSol.superclass.onButtonEdit.call(this);

            if (data.tipo_obligacion == 'adquisiciones') {

                this.mostrarComponente(this.cmpFuncionario);
                this.cmpFuncionario.store.baseParams.fecha = this.cmpFecha.getValue().dateFormat(this.cmpFecha.format);
                this.ocultarComponente(this.cmpFuncionarioProveedor);
                this.cmpProveedor.disable();

            }

            if (data.tipo_obligacion == 'pago_directo') {

                this.cmpProveedor.enable();
                this.mostrarComponente(this.cmpProveedor);
                this.cmpFuncionario.store.baseParams.fecha = this.cmpFecha.getValue().dateFormat(this.cmpFecha.format);

            }

            //segun el total nro cuota cero, ocultamos los componentes
            if (data.total_nro_cuota == '0') {
                this.ocultarComponente(this.Cmp.id_plantilla);
                this.ocultarComponente(this.Cmp.fecha_pp_ini);
                this.ocultarComponente(this.Cmp.rotacion);
            }


            this.Cmp.id_contrato.store.baseParams.filter = "[{\"type\":\"numeric\",\"comparison\":\"eq\", \"value\":\"" + this.Cmp.id_proveedor.getValue() + "\",\"field\":\"CON.id_proveedor\"}]";
            this.Cmp.id_contrato.modificado = true;

            if (data.estado != 'borrador') {
                this.Cmp.tipo_anticipo.disable();
                this.Cmp.total_nro_cuota.disable();
                this.Cmp.id_funcionario.disable();
                this.cmpProveedor.disable();


            }
            else {
                this.Cmp.total_nro_cuota.enable();
            }

        },

        onButtonNew: function () {

            Phx.vista.ObligacionPagoSol.superclass.onButtonNew.call(this);


            this.cmpTipoObligacion.enable();
            this.cmpDepto.disable();
            this.mostrarComponente(this.cmpProveedor);
            this.mostrarComponente(this.cmpFuncionario);
            this.ocultarComponente(this.cmpFuncionarioProveedor);
            this.cmpFuncionario.reset();
            this.cmpFecha.enable();
            this.cmpTipoCambioConv.enable();
            this.cmpProveedor.enable();
            this.cmpMoneda.enable();

            this.cmpFuncionario.disable();
            //defecto total nro cuota cero, entoces ocultamos los componentes
            this.ocultarComponente(this.Cmp.id_plantilla);
            this.ocultarComponente(this.Cmp.fecha_pp_ini);
            this.ocultarComponente(this.Cmp.rotacion);

            this.cmpFecha.setValue(new Date());
            this.cmpFecha.fireEvent('change');
            this.cmpTipoObligacion.setValue('pago_directo');


        },


    };
</script>
