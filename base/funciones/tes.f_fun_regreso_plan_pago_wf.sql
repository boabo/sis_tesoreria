CREATE OR REPLACE FUNCTION tes.f_fun_regreso_plan_pago_wf (
  p_id_usuario integer,
  p_id_usuario_ai integer,
  p_usuario_ai varchar,
  p_id_estado_wf integer,
  p_id_proceso_wf integer,
  p_codigo_estado varchar
)
RETURNS boolean AS
$body$
/*
*
*  Autor:   RAC
*  DESC:    funcion que actualiza los estados despues del registro de un retroceso en el plan de pago
*  Fecha:   10/06/2013
*
*/

DECLARE

	v_nombre_funcion   	text;
    v_resp    			varchar;
    v_mensaje varchar;
    v_obligacion		record;
   
	
    
BEGIN

	v_nombre_funcion = 'tes.f_fun_regreso_plan_pago_wf';
    
    
    -- actualiza estado en la solicitud
    update tes.tplan_pago  pp set 
         id_estado_wf =  p_id_estado_wf,
         estado = p_codigo_estado,
         id_usuario_mod=p_id_usuario,
         fecha_mod=now(),
         id_usuario_ai = p_id_usuario_ai,
         usuario_ai = p_usuario_ai
    where id_proceso_wf = p_id_proceso_wf;
    
    select pp.id_plan_pago,pp.estado as estado_pago,op.* into v_obligacion
    from tes.tplan_pago pp
    inner join tes.tobligacion_pago op 
    	on op.id_obligacion_pago = pp.id_obligacion_pago
    where pp.id_proceso_wf = p_id_proceso_wf;
    if (v_obligacion.tipo_obligacion = 'rrhh' and v_obligacion.estado_pago = 'borrador') then
    	v_resp = plani.f_anular_pago_tesoreria(p_id_usuario,p_id_usuario_ai,
                                            p_usuario_ai,v_obligacion.id_plan_pago);
    end if;
   
      

RETURN   TRUE;



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