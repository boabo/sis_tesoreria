CREATE OR REPLACE FUNCTION tes.f_fun_regreso_proceso_caja_wf (
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
*  Autor:   GSS
*  DESC:    funcion que actualiza los estados despues del registro de un retroceso en caja
*  Fecha:   28/12/2015
*
*/

DECLARE

	v_nombre_funcion   	text;
    v_resp    			varchar;
    v_mensaje varchar;
   
	
    
BEGIN

	v_nombre_funcion = 'tes.f_fun_regreso_proceso_caja_wf';
    
    -- actualiza estado en la solicitud
    update tes.tproceso_caja  se set 
         id_estado_wf =  p_id_estado_wf,
         estado = p_codigo_estado,
         id_usuario_mod=p_id_usuario,
         fecha_mod=now(),
         id_usuario_ai = p_id_usuario_ai,
         usuario_ai = p_usuario_ai
    where id_proceso_wf = p_id_proceso_wf;
   
      

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