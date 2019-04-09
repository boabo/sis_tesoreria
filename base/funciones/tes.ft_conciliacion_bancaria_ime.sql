CREATE OR REPLACE FUNCTION tes.ft_conciliacion_bancaria_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Obligaciones de Pago
 FUNCION: 		tes.ft_conciliacion_bancaria_ime
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
	v_id_conciliacion_bancaria	integer;
			    
BEGIN

    v_nombre_funcion = 'tes.ft_conciliacion_bancaria_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'TES_CONCBAN_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		Breydi vasquez pacheco	
 	#FECHA:		19-02-2019
	***********************************/

	if(p_transaccion='TES_CONCBAN_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into tes.tconciliacion_bancaria(
			estado_reg,
			id_cuenta_bancaria,
            id_gestion,
            id_periodo,
            id_funcionario_elabo,
            id_funcionario_vb,
            fecha,
            observaciones,
            saldo_banco,            
			fecha_reg,
			usuario_ai,
			id_usuario_reg,
			id_usuario_ai,
			id_usuario_mod
          	) values(
			'activo',
			v_parametros.id_cuenta_bancaria,
			v_parametros.id_gestion,
            v_parametros.id_periodo,
            v_parametros.id_funcionario_elabo,
            v_parametros.id_funcionario_vb,
            v_parametros.fecha,
            v_parametros.observaciones,
            v_parametros.saldo_banco,
			now(),
			v_parametros._nombre_usuario_ai,
			p_id_usuario,
			v_parametros._id_usuario_ai,
			null												
			)RETURNING id_conciliacion_bancaria into v_id_conciliacion_bancaria;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Cuenta Bancaria almacenado(a) con exito (id_conciliacion_bancaria'||v_id_conciliacion_bancaria||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_conciliacion_bancaria',v_id_conciliacion_bancaria::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'TES_CONCBAN_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		Breydi vasquez pacheco	
 	#FECHA:		19-02-2019
	***********************************/

	elsif(p_transaccion='TES_CONCBAN_MOD')then

		begin
			--Sentencia de la modificacion
			update tes.tconciliacion_bancaria set
			id_gestion = v_parametros.id_gestion,
            id_periodo = v_parametros.id_periodo,
            id_funcionario_elabo = v_parametros.id_funcionario_elabo,
            id_funcionario_vb = v_parametros.id_funcionario_vb,
            fecha = v_parametros.fecha,
            observaciones = v_parametros.observaciones,
            saldo_banco = v_parametros.saldo_banco,
            id_usuario_mod = p_id_usuario,
            fecha_mod = now()                       
			where id_conciliacion_bancaria=v_parametros.id_conciliacion_bancaria;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Cuenta Bancaria modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_conciliacion_bancaria',v_parametros.id_conciliacion_bancaria::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'TES_CONCBAN_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		Breydi vasquez pacheco	
 	#FECHA:		19-02-2019
	***********************************/

	elsif(p_transaccion='TES_CONCBAN_ELI')then

		begin
           if exists(select 1 from tes.tdetalle_conciliacion_bancaria
                where id_conciliacion_bancaria = v_parametros.id_conciliacion_bancaria) then
              raise exception 'Elimine el detalle previamente y vuelva a intentarlo';
            end if;         
			--Sentencia de la eliminacion
			delete from tes.tconciliacion_bancaria
            where id_conciliacion_bancaria=v_parametros.id_conciliacion_bancaria;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Cuenta Bancaria eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_conciliacion_bancaria',v_parametros.id_conciliacion_bancaria::varchar);
              
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