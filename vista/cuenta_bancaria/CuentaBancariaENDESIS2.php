<?php
/**
*@package pXP
*@file CuentaBancaria.php
*@author  Gonzalo Sarmiento Sejas
*@date 16-10-2014 15:19:30
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.CuentaBancariaENDESIS=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.CuentaBancariaENDESIS.superclass.constructor.call(this,config);
		this.init();
		this.load({params:{start:0, limit:this.tam_pag}});
		this.addButton('btnDepositosCheques',
            {
                text: 'Depositos y Cheques',
                iconCls: 'bmoney',
                disabled: true,
                handler: this.loadDepositosCheques,
                tooltip: '<b>Depositos y Cheques</b><br/>Registrar Depositos y Cheques de la Cuenta Bancaria'
            }
        );
	},
	tam_pag:50,
			
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_cuenta_bancaria'
			},
			type:'Field',
			form:true 
		},
		{
			config: {
				name: 'id_institucion',
				fieldLabel: 'Institucion',
				tinit: true,
				allowBlank: false,
				origen: 'INSTITUCION',
				baseParams:{es_banco:'si'},
				gdisplayField: 'nombre_institucion',
				gwidth: 200,
				renderer:function (value, p, record){return String.format('{0}', record.data['nombre_institucion']);}
			},
			type: 'ComboRec',
			id_grupo: 0,
			filters:{pfiltro:'inst.nombre',type:'string'},
			grid: true,
			form: true
		},
		
		{
			config:{
				name: 'nro_cuenta',
				fieldLabel: 'Nro Cuenta',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:50
			},
			type:'TextField',
			filters:{pfiltro:'ctaban.nro_cuenta',type:'string'},
			id_grupo:1,
			grid:true,
			form:true
		},
		
		{
			config:{
				name: 'denominacion',
				fieldLabel: 'Denominación',
				allowBlank: true,
				anchor: '80%',
				gwidth: 150,
				maxLength:100
			},
			type:'TextField',
			filters:{pfiltro:'ctaban.denominacion',type:'string'},
			id_grupo:1,
			grid:true,
			form:true
		},
		{
			config:{
				name: 'centro',
				fieldLabel: 'Central',
				allowBlank: false,
				anchor: '60%',
				gwidth: 100,
				maxLength:25,
				typeAhead:true,
				triggerAction:'all',
				mode:'local',
				store:['si','no']
			},
			valorInicial:'no',
			type:'ComboBox',
			filters:{pfiltro:'ctaban.centro',type:'string'},
			id_grupo:1,
			grid:true,
			form:true
		},
		{
			config:{
				name: 'fecha_alta',
				fieldLabel: 'Fecha Alta',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
						format: 'd/m/Y', 
						renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
			},
			type:'DateField',
			filters:{pfiltro:'ctaban.fecha_alta',type:'date'},
			id_grupo:1,
			grid:true,
			form:true
		},
		{
            config:{
                name:'id_moneda',
                origen:'MONEDA',
                allowBlank:true,
                fieldLabel:'Moneda',
                gdisplayField:'codigo_moneda',//mapea al store del grid
                gwidth:50,
              //   renderer:function (value, p, record){return String.format('{0}', record.data['codigo_moenda']);}
             },
            type:'ComboRec',
            id_grupo:1,
            filters:{   
                pfiltro:'mon.codigo',
                type:'string'
            },
            grid:true,
            form:true
          },
		{
			config:{
				name: 'fecha_baja',
				fieldLabel: 'Fecha Baja',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
						format: 'd/m/Y', 
						renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
			},
			type:'DateField',
			filters:{pfiltro:'ctaban.fecha_baja',type:'date'},
			id_grupo:1,
			grid:true,
			form:true
		},
		{
			config:{
				name: 'estado_reg',
				fieldLabel: 'Estado Reg.',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:10
			},
			type:'TextField',
			filters:{pfiltro:'ctaban.estado_reg',type:'string'},
			id_grupo:1,
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
			filters:{pfiltro:'ctaban.fecha_reg',type:'date'},
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
			filters:{pfiltro:'usu1.cuenta',type:'string'},
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
			filters:{pfiltro:'ctaban.fecha_mod',type:'date'},
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
		}
	],
	
	title:'Cuenta Bancaria',
	ActSave:'../../sis_tesoreria/control/CuentaBancaria/insertarCuentaBancaria',
	ActDel:'../../sis_tesoreria/control/CuentaBancaria/eliminarCuentaBancaria',
	ActList:'../../sis_tesoreria/control/CuentaBancaria/listarCuentaBancaria',
	id_store:'id_cuenta_bancaria',
	fields: [
		{name:'id_cuenta_bancaria', type: 'numeric'},
		{name:'estado_reg', type: 'string'},
		{name:'fecha_baja', type: 'date',dateFormat:'Y-m-d'},
		{name:'nro_cuenta', type: 'string'},
		{name:'denominacion', type: 'string'},
		{name:'centro', type: 'string'},
		{name:'fecha_alta', type: 'date',dateFormat:'Y-m-d'},
		{name:'id_institucion', type: 'numeric'},
		{name:'nombre_institucion', type: 'string'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},'id_moneda','codigo_moneda'
		
	],
	sortInfo:{
		field: 'id_cuenta_bancaria',
		direction: 'ASC'
	},
	bdel:false,
	bsave:false,
	bnew:false,
	bedit:false,
	
	loadDepositosCheques:function() {
            var rec=this.sm.getSelected();
            rec.data.nombreVista = this.nombreVista;
            Phx.CP.loadWindows('../../../sis_migracion/vista/ts_libro_bancos_depositos/TsLibroBancosDeposito.php',
                    'Depositos',
                    {
                        width:'90%',
                        height:500
                    },
                    rec.data,
                    this.idContenedor,
                    'TsLibroBancosDeposito'
        )
    },
	
	preparaMenu:function(n){
          var data = this.getSelectedData();
          var tb =this.tbar;
		  
          Phx.vista.CuentaBancariaENDESIS.superclass.preparaMenu.call(this,n); 
		  if (data['id_moneda']==null){
            this.getBoton('btnDepositosCheques').disable();
          }else{
			this.getBoton('btnDepositosCheques').enable();
		  }
          /*if (data['estado']== 'borrador'){
              this.getBoton('edit').enable();
              //this.TabPanelSouth.get(1).disable();
          }
          else{
              
               if (data['estado']== 'registrado'){   
                  this.getBoton('fin_registro').disable();
                  this.TabPanelSouth.get(1).enable();
                }                                            
          }          
          
          if(data.tipo_obligacion=='adquisiciones'){
              //RCM: menú de reportes de adquisiciones
              this.menuAdq.enable();
              //Inhabilita el reporte de disponibilidad
              this.getBoton('btnVerifPresup').disable();              
          }*/          
     },
     
     
     liberaMenu:function(){
        var tb = Phx.vista.CuentaBancariaENDESIS.superclass.liberaMenu.call(this);
        /*if(tb){
			//Inhabilita el reporte de disponibilidad
            this.getBoton('btnVerifPresup').disable();
        }
       this.TabPanelSouth.get(1).disable();
       
       //RCM: menú de reportes de adquisiciones
       this.menuAdq.disable();
        */
		
       return tb
    },
	/*south:{	   
        url:'../../../sis_tesoreria/vista/chequera/Chequera.php',
        title:'Chequeras', 
        height : '50%',
        cls:'Chequera'
   },*/

	onButtonEdit: function(){
		Phx.vista.CuentaBancariaENDESIS.superclass.onButtonEdit.call(this);
		this.Cmp.nro_cuenta.disable();
	},
	onButtonNew: function(){
		Phx.vista.CuentaBancariaENDESIS.superclass.onButtonNew.call(this);
		this.Cmp.nro_cuenta.enable();
	}
})	
</script>