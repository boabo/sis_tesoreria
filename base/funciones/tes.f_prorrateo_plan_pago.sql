--------------- SQL ---------------

CREATE OR REPLACE FUNCTION tes.f_prorrateo_plan_pago (
  p_id_plan_pago integer,
  p_id_obligacion_pago integer,
  p_pago_variable varchar,
  p_monto_ejecutar_total_mo numeric,
  p_id_usuario integer
)
RETURNS boolean AS
$body$
DECLARE
v_registros record;
v_monto_total numeric;
v_cont numeric;
v_id_prorrateo integer;
v_monto  numeric;
v_resp varchar;
v_nombre_funcion varchar;
 
BEGIN

 v_nombre_funcion = 'tes.f_prorrateo_plan_pago';
     --------------------------------------------------
            -- Inserta prorrateo automatico
            ------------------------------------------------
            v_monto_total=0; 
            IF p_pago_variable = 'no' THEN
            
            --si los pagos no son variable puede hacerce un prorrateo automatico
            
                      v_cont = 0;
                     
                      FOR  v_registros in (
                                           select
                                            od.id_obligacion_det,
                                            od.factor_porcentual
                                           from tes.tobligacion_det od
                                           where  od.id_obligacion_pago = p_id_obligacion_pago) LOOP
                      
                        v_cont = v_cont +v_cont;
                        
                        --calcula el importe prorrateado segun factor
                        v_monto= round(p_monto_ejecutar_total_mo * v_registros.factor_porcentual,2);
                        v_monto_total=v_monto_total+v_monto;
                        
                        INSERT INTO 
                              tes.tprorrateo
                            (
                              id_usuario_reg,
                              fecha_reg,
                              estado_reg,
                              id_plan_pago,
                              id_obligacion_det,
                              monto_ejecutar_mo
                            ) 
                            VALUES (
                              p_id_usuario,
                              now(),
                              'activo',
                               p_id_plan_pago,
                              v_registros.id_obligacion_det,
                             v_monto
                            
                            )RETURNING id_prorrateo into v_id_prorrateo;
                        
                       
                      END LOOP;
                      
                      IF v_monto_total!=p_monto_ejecutar_total_mo  THEN
                        
                         update tes.tprorrateo p set
                         monto_ejecutar_mo =   p_monto_ejecutar_total_mo-(v_monto-monto_ejecutar_mo)
                         where p.id_prorrateo = v_id_prorrateo;
                      
                      END IF;
                     
            
                       --actualiza el monto prorrateado para alerta en la interface cuando no cuadre
                      update  tes.tplan_pago pp set
                      total_prorrateado=p_monto_ejecutar_total_mo
                      where pp.id_plan_pago = p_id_plan_pago;
                      
                     
            
                      
              ELSE
              --si los pagos no son automatico solo insertamos la base del prorrateo con valor cero
                
                    FOR  v_registros in (
                                               select
                                                od.id_obligacion_det,
                                                od.factor_porcentual
                                               from tes.tobligacion_det od
                                               where  od.id_obligacion_pago = p_id_obligacion_pago) LOOP
                          
                            INSERT INTO 
                                  tes.tprorrateo
                                (
                                  id_usuario_reg,
                                  fecha_reg,
                                  estado_reg,
                                  id_plan_pago,
                                  id_obligacion_det,
                                  monto_ejecutar_mo
                                ) 
                                VALUES (
                                  p_id_usuario,
                                  now(),
                                  'activo',
                                  p_id_plan_pago,
                                  v_registros.id_obligacion_det,
                                  0
                                )RETURNING id_prorrateo into v_id_prorrateo;
                  
                    
                    
                     END LOOP;
                   
                     
                     
              END IF;
              
              return TRUE;
                      
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