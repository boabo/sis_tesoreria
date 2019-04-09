CREATE OR REPLACE FUNCTION tes.ft_detalle_concil_bancaria_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Obligaciones de Pago
 FUNCION: 		tes.ft_detalle_concil_bancaria_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'tes.tconciliacion_bancaria'
 AUTOR: 		Breydi vasquez pacheco
 FECHA:	        19-02-2019
 COMENTARIOS:			
***************************************************************************/

DECLARE

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_detalle_conciliacion_bancaria	integer;
			    
BEGIN

    v_nombre_funcion = 'tes.ft_detalle_concil_bancaria_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'TES_DETCONCBAN_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		Breydi vasquez pacheco	
 	#FECHA:		19-02-2019
	***********************************/

	if(p_transaccion='TES_DETCONCBAN_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into tes.tdetalle_conciliacion_bancaria(
			estado_reg,
			id_conciliacion_bancaria,
            fecha,
            concepto,
            nro_comprobante,
            importe,
            tipo,            
			fecha_reg,
			usuario_ai,
			id_usuario_reg,
			id_usuario_ai,
			id_usuario_mod
          	) values(
			'activo',
			v_parametros.id_conciliacion_bancaria,
			v_parametros.fecha,
            v_parametros.concepto,
            v_parametros.nro_comprobante,
            v_parametros.importe,
            v_parametros.tipo,
			now(),
			v_parametros._nombre_usuario_ai,
			p_id_usuario,
			v_parametros._id_usuario_ai,
			null												
			)RETURNING id_detalle_conciliacion_bancaria into v_id_detalle_conciliacion_bancaria;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Cuenta Bancaria almacenado(a) con exito (id_detalle_conciliacion_bancaria'||v_id_detalle_conciliacion_bancaria||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_detalle_conciliacion_bancaria',v_id_detalle_conciliacion_bancaria::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'TES_DETCONCBAN_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		Breydi vasquez pacheco	
 	#FECHA:		19-02-2019
	***********************************/

	elsif(p_transaccion='TES_DETCONCBAN_MOD')then

		begin
			--Sentencia de la modificacion
			update tes.tdetalle_conciliacion_bancaria set
			fecha = v_parametros.fecha,
            concepto = v_parametros.concepto,
            nro_comprobante = v_parametros.nro_comprobante,
            importe = v_parametros.importe,
            tipo = v_parametros.tipo,
            fecha_mod = now(),
			id_usuario_mod = p_id_usuario
			where id_detalle_conciliacion_bancaria=v_parametros.id_detalle_conciliacion_bancaria;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Cuenta Bancaria modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_detalle_conciliacion_bancaria',v_parametros.id_detalle_conciliacion_bancaria::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'TES_DETCONCBAN_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		Breydi vasquez pacheco	
 	#FECHA:		19-02-2019
	***********************************/

	elsif(p_transaccion='TES_DETCONCBAN_ELI')then

		begin       
			--Sentencia de la eliminacion
			delete from tes.tdetalle_conciliacion_bancaria
            where id_detalle_conciliacion_bancaria=v_parametros.id_detalle_conciliacion_bancaria;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Cuenta Bancaria eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_detalle_conciliacion_bancaria',v_parametros.id_detalle_conciliacion_bancaria::varchar);
              
            --Devuelve la respuesta
            return v_resp;

		end;
         
	else
     
    	raise exception 'Transaccion inexistente: %',p_transaccion;

	end if;

EXCEPTION
				
	WHEN OTHERS THEN
		v_resp='';
		v_resp = pxp.f_agrega_clave(v_resp,'mensaje',SQLERRM);
		v_resp = pxp.f_agrega_clave(v_resp,'codigo_error',SQLSTATE);
		v_resp = pxp.f_agrega_clave(v_resp,'procedimientos',v_nombre_funcion);
		raise exception '%',v_resp;
				        
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;