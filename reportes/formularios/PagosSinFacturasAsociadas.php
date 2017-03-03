<?php
/**
 *@package pXP
 *@file    PagosSinFacturasAsociadas.php
 *@author  Gonzalo Sarmiento Sejas
 *@date    05-05-2016
 *@description Archivo con la interfaz para generaci�n de reporte
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
	Phx.vista.PagosSinFacturasAsociadas = Ext.extend(Phx.frmInterfaz, {
		
		Atributos : [
		{
			config:{
				name: 'fecha_ini',
				fieldLabel: 'Fecha Inicio',
				allowBlank: true,
				anchor: '100%',
				gwidth: 100,
						format: 'd/m/Y', 
						renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
			},
			type:'DateField',
			filters:{pfiltro:'fecha_ini',type:'date'},
			id_grupo:1,
			grid:true,
			form:true
		},
		{
			config:{
				name: 'fecha_fin',
				fieldLabel: 'Fecha Fin',
				allowBlank: true,
				anchor: '100%',
				gwidth: 100,
						format: 'd/m/Y', 
						renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
			},
			type:'DateField',
			filters:{pfiltro:'fecha_fin',type:'date'},
			id_grupo:1,
			grid:true,
			form:true
		}],
		title : 'Pagos Sin Facturas Asociadas',		
		ActSave : '../../sis_tesoreria/control/ObligacionPago/reportePagoSinDocumento',
		
		topBar : true,
		botones : false,
		labelSubmit : 'Imprimir',
		tooltipSubmit : '<b>Generar Reporte Pagos Sin Facturas Asociadas</b>',
		
		constructor : function(config) {
			Phx.vista.PagosSinFacturasAsociadas.superclass.constructor.call(this, config);
			this.init();			
			this.iniciarEventos();
		},
		
		iniciarEventos:function(){        
			this.cmpFechaIni = this.getComponente('fecha_ini');
			this.cmpFechaFin = this.getComponente('fecha_fin');
		},
		
		onSubmit:function(o){
			Phx.vista.PagosSinFacturasAsociadas.superclass.onSubmit.call(this,o);
		},
		
		tipo : 'reporte',
		clsSubmit : 'bprint',
		
		Grupos : [{
			layout : 'column',
			items : [{
				xtype : 'fieldset',
				layout : 'form',
				border : true,
				title : 'Generar Reporte',
				bodyStyle : 'padding:0 10px 0;',
				columnWidth : '500px',
				items : [],
				id_grupo : 0,
				collapsible : true
			}]
		}]
})
</script>