CREATE OR REPLACE FUNCTION tes.f_lista_funcionario_jefe_efectivo_wf_sel (
  p_id_usuario integer,
  p_id_tipo_estado integer,
  p_fecha date = now(),
  p_id_estado_wf integer = NULL::integer,
  p_count boolean = false,
  p_limit integer = 1,
  p_start integer = 0,
  p_filtro varchar = '0=0'::character varying
)
RETURNS SETOF record AS
$body$
/**************************************************************************
 SISTEMA ENDESIS - SISTEMA DE ...
***************************************************************************
 SCRIPT: 		tes.f_lista_funcionario_jefe_efectivo_wf_sel
 DESCRIPCI�N: 	Lista el funcionario que aprobo la solicitud de efectivo
 AUTOR: 		Gonzalo Sarmiento Sejas
 FECHA:			29/04/2016
 COMENTARIOS:	
***************************************************************************
 HISTORIA DE MODIFICACIONES:

 DESCRIPCI�N:
 AUTOR:       
 FECHA:      

***************************************************************************/

-------------------------
-- CUERPO DE LA FUNCI�N --
--------------------------

-- PAR�METROS FIJOS
/*


  p_id_usuario integer,                                identificador del actual usuario de sistema
  p_id_tipo_estado integer,                            idnetificador del tipo estado del que se quiere obtener el listado de funcionario  (se correponde con tipo_estado que le sigue a id_estado_wf proporcionado)                       
  p_fecha date = now(),                                fecha  --para verificar asginacion de cargo con organigrama
  p_id_estado_wf integer = NULL::integer,              identificador de estado_wf actual en el proceso_wf
  p_count boolean = false,                             si queremos obtener numero de funcionario = true por defecto false
  p_limit integer = 1,                                 los siguiente son parametros para filtrar en la consulta
  p_start integer = 0,
  p_filtro varchar = '0=0'::character varying




*/

DECLARE
	g_registros  		record;    
    v_consulta varchar;
    v_nombre_funcion varchar;
    v_resp varchar;        
    v_id_funcionario   integer;    

BEGIN
  v_nombre_funcion ='tes.f_lista_funcionario_jefe_efectivo_wf_sel';

    --recuperamos la solicitud de efectivo a partir del id_estado_wf en el proceso caja
    
    select 
      sol.id_funcionario_jefe_aprobador
    into
      v_id_funcionario
    from tes.tsolicitud_efectivo rend
    inner join tes.tsolicitud_efectivo sol on sol.id_solicitud_efectivo=rend.id_solicitud_efectivo_fk
    where rend.id_estado_wf = p_id_estado_wf;
    

    IF not p_count then
    
             v_consulta:='SELECT
                            fun.id_funcionario,
                            fun.desc_funcionario1 as desc_funcionario,
                            ''''::text  as desc_funcionario_cargo,
                            1 as prioridad
                         FROM orga.vfuncionario fun WHERE fun.id_funcionario ='||v_id_funcionario||'
                          and '||p_filtro||'
                         limit '|| p_limit::varchar||' offset '||p_start::varchar; 
                 raise notice 'id_funcionario %', v_consulta;
             FOR g_registros in execute (v_consulta)LOOP     
                     RETURN NEXT g_registros;
             END LOOP;
                      
      ELSE
                  v_consulta='select
                                  COUNT(fun.id_funcionario) as total
                                 FROM orga.vfuncionario fun WHERE fun.id_funcionario ='||v_id_funcionario||'
                                  and '||p_filtro;   
                                          
                   FOR g_registros in execute (v_consulta)LOOP     
                     RETURN NEXT g_registros;
                   END LOOP;
                       
                       
    END IF;
               
        
       
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
COST 100 ROWS 1000;