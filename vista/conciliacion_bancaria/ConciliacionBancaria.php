<?php
/**
 *@package pXP
 *@file    ConciliacionBancaria.php
 *@author  BVP
 *@date    
 *@description Archivo con la interfaz para generación de conciliacion bancaria
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
	Phx.vista.ConciliacionBancaria = Ext.extend(Phx.gridInterfaz, {
		
		constructor : function(config) {			
			this.maestro = config.maestro;			
			Phx.vista.ConciliacionBancaria.superclass.constructor.call(this, config);				                        
			this.init();
			var that = this;		
			this.iniciarEventos();	
			//this.store.baseParams.id_cuenta_bancaria = that.maestro.id_cuenta_bancaria;		
			this.store.baseParams={
					id_cuenta_bancaria : this.id_cuenta_bancaria,
					mycls:this.mycls};
								
			this.load({params:{start:0, limit:this.tam_pag}});			
			this.Atributos[1].valorInicial = this.id_cuenta_bancaria;															
			this.addButton('btnReporteConcBan',
				{
					text: 'Reporte Conciliacion',
					iconCls: 'bpdf',
					disabled: false,
					handler: this.reporteConcilBan,
					tooltip: '<b>Reporte Conciliacion</b>'
				}
			);


		},
   		
		Atributos : [{
			config : {
				labelSeparator : '',
				inputType : 'hidden',
				name : 'id_conciliacion_bancaria'
			},
			type : 'Field',
			form : true
		}, {
			config : {
				labelSeparator : '',
				inputType : 'hidden',
				name : 'id_cuenta_bancaria'
			},
			type : 'Field',
			form : true
		},
        {
            config:{
                name:'id_gestion',
                fieldLabel:'Gestión',
                allowBlank:false,
                emptyText:'Gestión...',
                store: new Ext.data.JsonStore({
                    url: '../../sis_parametros/control/Gestion/listarGestion',
                    id: 'id_gestion',
                    root: 'datos',
                    sortInfo:{
                        field: 'gestion',
                        direction: 'DESC'
                    },
                    totalProperty: 'total',
                    fields: ['id_gestion','gestion','moneda','codigo_moneda'],                    
                    remoteSort: true,
                    baseParams:{par_filtro:'gestion'}
                }),
                valueField: 'id_gestion',
                displayField: 'gestion',                
                hiddenName: 'id_gestion',
                forceSelection:true,
                typeAhead: false,
                triggerAction: 'all',
                lazyRender:true,
                mode:'remote',
                pageSize:10,
                queryDelay:1000,
                listWidth:200,
                resizable:true,
                anchor:'50%',
                gwidth: 50,
				renderer : function(value, p, record) {					
					return String.format('{0}', record.data['gestion']);
				}                
            },
            type:'ComboBox',
            id_grupo:0,
            filters:{
                pfiltro:'gestion',
                type:'string'
            },
            grid:true,
            form:true
        },
        {
            config:{
                name:'id_periodo',
                fieldLabel:'Periodo',
                allowBlank:false,
                emptyText:'Periodo...',
                store: new Ext.data.JsonStore({
                    url: '../../sis_parametros/control/Periodo/listarPeriodo',
                    id: 'id_periodo',
                    root: 'datos',
                    sortInfo:{
                        field: 'id_periodo',
                        direction: 'ASC'
                    },
                    totalProperty: 'total',
                    fields: ['id_periodo','literal','periodo','fecha_ini','fecha_fin'],                    
                    remoteSort: true,
                    baseParams:{par_filtro:'periodo#literal'}
                }),
                valueField: 'id_periodo',
                displayField: 'literal',                
                hiddenName: 'id_periodo',
                forceSelection:true,
                typeAhead: false,
                triggerAction: 'all',
                lazyRender:true,
                mode:'remote',
                pageSize:12,
                queryDelay:1000,
                listWidth:200,
                resizable:true,
                anchor:'50%',
                gwidth: 50,
				renderer : function(value, p, record) {					
					return String.format('{0}', record.data['literal']);
				}                

            },
            type:'ComboBox',
            id_grupo:0,
            filters:{
                pfiltro:'literal',
                type:'string'
            },
            grid:true,
            form:true
        },
		{
			config:{
				name: 'fecha',
				fieldLabel: 'Fecha Elaboración',
				allowBlank: false,
				anchor: '50%',
				gwidth: 90,
				format: 'd/m/Y',
				renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
			},
				type:'DateField',
				filters:{pfiltro:'conci.fecha',type:'date'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'saldo_banco',
				fieldLabel: 'Saldo Según Extracto Bancario',
				allowBlank: false,
				anchor: '50%',
				gwidth: 90				
			},
				type:'NumberField',				
				id_grupo:1,
				grid:true,
				form:true
		},		        		
		{
			config:{
				name: 'observaciones',
				fieldLabel: 'Observaciones',
				allowBlank: false,
				anchor: '80%',
				gwidth: 150
			},
			type:'TextArea',
			filters:{pfiltro:'conci.observaciones',type:'string'},			
			id_grupo:1,
			grid:true,
			form:true
		},				
		{
   			config:{
       		    name:'id_funcionario_elabo',
       		    hiddenName: 'id_funcionario',
   				origen:'FUNCIONARIOCAR',
   				fieldLabel:'Elaborado por',
   				allowBlank:true,
                gwidth:200,
   				valueField: 'id_funcionario',
   			    gdisplayField: 'fun_elabo',   			    
   			    baseParams: { es_combo_solicitud : 'si' },
      			renderer:function(value, p, record){return String.format('{0}', record.data['fun_elabo']);}
       	     },
   			type:'ComboRec',//ComboRec
   			id_grupo:0,
   			filters:{pfiltro:'fun.desc_funcionario2',type:'string'},
   			bottom_filter:true,
   		    grid:true,
   			form:true
		 },
		{
   			config:{
       		    name:'id_funcionario_vb',
       		    hiddenName: 'id_funcionario',
   				origen:'FUNCIONARIOCAR',
   				fieldLabel:'Vo.Bo',
   				allowBlank:true,
                gwidth:200,
   				valueField: 'id_funcionario',
   			    gdisplayField: 'fun_vb',
   			    baseParams: { es_combo_solicitud : 'si' },
      			renderer:function(value, p, record){return String.format('{0}', record.data['fun_vb']);}
       	     },
   			type:'ComboRec',//ComboRec
   			id_grupo:0,
   			filters:{pfiltro:'fu.desc_funcionario2',type:'string'},
   			bottom_filter:true,
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
							format: 'd/m/Y', 
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
				type:'DateField',
				filters:{pfiltro:'conci.fecha_reg',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
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
				filters:{pfiltro:'usr_reg',type:'string'},
				id_grupo:1,
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
				filters:{pfiltro:'conci.fecha_mod',type:'date'},
				id_grupo:1,
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
				id_grupo:1,
				grid:true,
				form:false
		}],
	tam_pag:50,	
	title : 'Conciliacion Bancatia',
	ActSave:'../../sis_tesoreria/control/ConciliacionBancaria/insertarConciliacionBancaria',
	ActDel:'../../sis_tesoreria/control/ConciliacionBancaria/eliminarConciliacionBancaria',
	ActList:'../../sis_tesoreria/control/ConciliacionBancaria/listarConciliacionBancaria',
	id_store : 'id_conciliacion_bancaria',
		fields: [
		{name:'id_conciliacion_bancaria', type: 'numeric'},
		{name:'id_cuenta_bancaria', type: 'numeric'},
		{name:'id_funcionario_elabo', type: 'numeric'},
		{name:'id_funcionario_vb', type: 'numeric'},
		{name:'id_gestion', type: 'numeric'},
		{name:'id_periodo', type: 'numeric'},
		{name:'id_usuario_mod', type: 'numeric'},				
		{name:'saldo_banco', type: 'numeric'},		
		{name:'observaciones', type: 'string'},
		{name:'fecha', type: 'date',dateFormat:'Y-m-d'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'gestion',type:'numeric'},
		{name:'literal',type:'string'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		{name:'fun_elabo', type: 'string'},
		{name:'fun_vb', type: 'string'}
	],
		sortInfo : {			
			field : 'id_conciliacion_bancaria',
			direction : 'ASC'
		},
		bdel : true,
		bsave : false,
		btest: false,
		fheight:'60%',
				
		onButtonNew:function(){
			Phx.vista.ConciliacionBancaria.superclass.onButtonNew.call(this);			
		},
		
		onButtonEdit:function(){
			Phx.vista.ConciliacionBancaria.superclass.onButtonEdit.call(this);			
			var data = this.getSelectedData();
		},
		
        iniciarEventos:function(){

            this.Cmp.id_gestion.on('select',function(c,r,n){

                this.Cmp.id_periodo.reset();
                this.Cmp.id_periodo.store.baseParams={id_gestion:c.value, vista: 'reporte'};
                this.Cmp.id_periodo.modificado=true;

            },this);
        },		
		
		reporteConcilBan : function(){					
		var data = this.getSelectedData();
		var NumSelect=this.sm.getCount();
		console.log('valeus',data);		

			if(NumSelect != 0)
			{	
				Phx.CP.loadingShow();					 				
				Ext.Ajax.request({
								url:'../../sis_tesoreria/control/ConciliacionBancaria/reporteConciliacionBancaria',
								params:{id_cuenta_bancaria:data.id_cuenta_bancaria,
										id_periodo:data.id_periodo,
										id_conciliacion_bancaria:data.id_conciliacion_bancaria},
								success: this.successExport,
								failure: this.conexionFailure,
								timeout:this.timeout,
								scope:this
				});				
			}
			else
			{
				Ext.MessageBox.alert('Alerta', 'Antes debe seleccionar un item.');
			}
		},

		tabsouth:[{
			url : '../../../sis_tesoreria/vista/conciliacion_bancaria/DetalleConcilBancaria.php',
			title : 'Detalle Conciliacion',
			height: '50%',
			cls : 'DetalleConcilBancaria'
		}]
	}); 
</script>