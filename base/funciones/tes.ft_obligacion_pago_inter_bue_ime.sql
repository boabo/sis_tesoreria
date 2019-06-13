CREATE OR REPLACE FUNCTION tes.ft_obligacion_pago_inter_bue_ime (
  p_administrador integer,
  p_id_usuario integer,
  v_obligacion_pago_json json,
  v_obligacion_det_json json,
  v_obligacion_pp_json json,
  v_documentos json
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Tesoreria
 FUNCION: 		tes.ft_obligacion_pago_inter_bue_ime
 DESCRIPCION:   Funcion que gestiona los procesos central a las internacionales
 AUTOR: 		Maylee Perez Pastor
 FECHA:	        03-04-2019 16:01:32
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:
 AUTOR:
 FECHA:
***************************************************************************/

DECLARE

     v_nro_requerimiento    	integer;
     --v_parametros           	record;
     v_registros_op         	record;
     va_tipo_pago				varchar[];
     v_id_requerimiento     	integer;
     v_resp		            	varchar;
	 v_nombre_funcion        	text;
     --v_anho 					integer;
     v_id_obligacion_det 	    integer;
	 v_registros    			record;
     v_hstore_registros 		hstore;
     --v_date 					date;
     v_registros_det 			record;
     v_id_centro_costo_dos 		integer;
     v_id_obligacion_pago_sg 	varchar[];
     v_id_gestion_sg 			varchar[];
     v_preguntar 				varchar;
     v_pre_integrar_presupuestos	varchar;
     v_id_administrador			integer;
     v_id_partida  				integer;

     v_hstore_registros_pp		hstore;
     v_registros_pp				record;
     v_id_obligacion_pp 	    integer;

     v_resp_pp					varchar;
     v_id_obligacion_pago_sg_pp varchar[];
     v_id_gestion_sg_pp			varchar[];

     v_id_tipo_documento_ant	integer;

     v_id_estado_actual         integer;
     va_id_tipo_estado_pro 		integer[];
     va_codigo_estado_pro		varchar[];
     va_disparador_pro 			varchar[];
     va_regla_pro 				varchar[];
     va_prioridad_pro 			integer[];
     v_id_proceso_wf			integer;
     v_id_estado_wf 			integer;
     v_codigo_estado 			varchar;

     v_registros_pp_new			record;
     v_resp_doc  				boolean;

     v_registros_obligacion_pago_nuevo	RECORD;

     v_obligacion_json 			json;
     v_contador					integer = 1;
	 v_registros_det_json 		json;
     v_registros_pp_json		json;

     v_pago_variable		varchar;
	 --v_id_proceso_wf		integer;
     --v_id_estado_wf 		integer;
     v_id_documento_wf		integer;

     v_monto_pp 			numeric;
     v_tipo_cambio_conv		numeric;
     v_nro_cuota_pp			numeric;

     v_id_obligacion		integer;
     v_documentos_json		json;
     v_id_documento_wf_op 	integer;
     v_num_tramite			varchar;
     v_obs_monto_no_pagado	text;
	 v_id_documento_wf_n	integer;

     v_estado				varchar;

     v_acceso_directo  		varchar;
    v_clase   				varchar;
    v_parametros_ad 	  	varchar;
    v_tipo_noti  			varchar;
    v_titulo   				varchar;

     va_id_tipo_estado integer[];
     va_codigo_estado varchar[];
  /*   va_disparador varchar [];
     va_regla varchar [];
     va_prioridad integer [];
     v_num_estados integer;*/

     --v_id_estado_wf_sig		integer;

     v_adelanta_wf 			varchar;
     p_tabla				varchar;
     p_transaccion			varchar;

     v_estado_pago			 record;
     v_registros_proc		 record;
     v_codigo_tipo_pro  	 varchar;
     v_codigo_estado_siguiente  varchar;

BEGIN
 	v_nombre_funcion = 'tes.ft_obligacion_pago_inter_bue_ime';
    --v_pre_integrar_presupuestos = pxp.f_get_variable_global('pre_integrar_presupuestos');

  	--v_parametros = pxp.f_get_record(p_tabla);
    v_preguntar = 'no';



		   ---------------------------------------------------------------
           -- copiar obligacion de pago de la Central a una internacional
           ---------------------------------------------------------------
           --recupera dato del pp para copiar su importe de la cuota
             FOR  v_registros_pp_json in (select * from json_array_elements(v_obligacion_pp_json) LIMIT 1) LOOP
              v_monto_pp = (v_registros_pp_json->>'monto')::numeric;
              v_nro_cuota_pp = (v_registros_pp_json->>'nro_cuota')::numeric;

             END LOOP;

            for v_obligacion_json in select * from json_array_elements(v_obligacion_pago_json) loop
             v_tipo_cambio_conv = (v_obligacion_json->>'tipo_cambio_conv')::numeric;
            -- raise exception 'parametros: %',(v_obligacion_json->>'num_tramite')::varchar;
               if(v_obligacion_json is not null and v_contador = 1) then

                  v_hstore_registros =   hstore(ARRAY[
                                               'fecha',(v_obligacion_json->>'fecha')::varchar,
                                               'tipo_obligacion', 'spi'::varchar,
                                               --'estado', v_registros.estado::varchar,
                                               'estado', 'en_pago'::varchar,
                                               'id_funcionario',(v_obligacion_json->>'id_funcionario')::varchar,
                                               --'_id_usuario_ai',v_obligacion_json->'id_usuario_ai'::varchar,
                                               --'_nombre_usuario_ai',v_obligacion_json->'usuario_ai'::varchar,
                                               'id_depto',(v_obligacion_json->>'id_depto')::varchar,
                                               'obs','Extiende el trámite(BOL): '||(v_obligacion_json->>'num_tramite')::varchar||', Cuota :'||v_nro_cuota_pp||',  Obs:  '||(v_obligacion_json->'obs')::varchar,
                                               'id_proveedor',(v_obligacion_json->>'id_proveedor')::varchar,
                                               'id_moneda',(v_obligacion_json->>'id_moneda')::varchar,
                                               'tipo_cambio_conv',(v_obligacion_json->>'tipo_cambio_conv')::varchar,
                                               'pago_variable',(v_obligacion_json->>'pago_variable')::varchar,
                                               --'pago_variable',v_pago_variable,
                                               --'pago_variable','no'::varchar,
                                               'total_nro_cuota',(v_obligacion_json->>'total_nro_cuota')::varchar,
                                               'fecha_pp_ini',(v_obligacion_json->>'fecha_pp_ini')::varchar,
                                               --'rotacion',(v_obligacion_json->>'rotacion')::varchar,
                                               'id_plantilla',(v_obligacion_json->>'id_plantilla')::varchar,
                                               'tipo_anticipo',(v_obligacion_json->>'tipo_anticipo')::varchar,
                                               --'id_contrato',(v_obligacion_json->>'id_contrato')::varchar,
                                               'total_pago', v_monto_pp::varchar,
                                               'ultimo_estado_pp', (v_obligacion_json->>'ultimo_estado_pp')::varchar,
                                               --'ultima_cuota_pp', (v_obligacion_json->>'ultima_cuota_pp')::varchar,
                                               'porc_retgar', (v_obligacion_json->>'porc_retgar')::varchar,
                                               'id_subsistema', (v_obligacion_json->>'id_subsistema')::varchar,
                                               'porc_anticipo', (v_obligacion_json->>'porc_anticipo')::varchar,
                                               'numero', (v_obligacion_json->'numero')::varchar,
                                               'tipo_cambio_conv', (v_obligacion_json->>'tipo_cambio_conv')::varchar,
                                               'comprometido', (v_obligacion_json->>'comprometido')::varchar,
                                               'nro_cuota_vigente', (v_obligacion_json->>'nro_cuota_vigente')::varchar,
                                               'id_depto_conta', (v_obligacion_json->>'id_depto_conta')::varchar,
                                               'ajuste_anticipo', (v_obligacion_json->>'ajuste_anticipo')::varchar,
                                               'ajuste_aplicado',(v_obligacion_json->>'ajuste_aplicado')::varchar,
                                               'monto_estimado_sg', (v_obligacion_json->>'monto_estimado_sg')::varchar,
                                               'obs_presupuestos', (v_obligacion_json->>'obs_presupuestos')::varchar,
                                               'codigo_poa', (v_obligacion_json->>'codigo_poa')::varchar,
                                               'obs_poa', (v_obligacion_json->>'obs_poa')::varchar,
                                               'uo_ex', (v_obligacion_json->>'uo_ex')::varchar,
                                               'id_funcionario_responsable', (v_obligacion_json->>'id_funcionario_responsable')::varchar,
                                               'fecha_certificacion_pres', (v_obligacion_json->>'fecha_certificacion_pres')::varchar,
                                               'id_obligacion_pago_inter',(v_obligacion_json->>'id_obligacion_pago')::varchar
                                              ]);

                    end if;
            	v_contador = v_contador + 1;
            end loop;

            --bandera para ver si es un pago recurrente o unico=1, pga=2.
            if(p_administrador = 2)then
               v_id_administrador = 2;
            else
               v_id_administrador = p_administrador;
            end if;

             v_resp = tes.f_inserta_obligacion_pago(v_id_administrador, p_id_usuario, hstore(v_hstore_registros));
             v_id_obligacion_pago_sg =  pxp.f_recupera_clave(v_resp, 'id_obligacion_pago');
             v_id_gestion_sg =  pxp.f_recupera_clave(v_resp, 'id_gestion');


                SELECT op.id_proceso_wf, op.id_estado_wf, op.estado
                INTO v_id_proceso_wf, v_id_estado_wf, v_estado
                FROM tes.tobligacion_pago op
                WHERE op.id_obligacion_pago = v_id_obligacion_pago_sg[1]::integer;


			   	update tes.tobligacion_pago set
                estado = 'en_pago'::varchar,
                total_pago = v_monto_pp::numeric,
                ultima_cuota_pp = v_nro_cuota_pp,
                pago_variable = (v_obligacion_json->>'pago_variable')::varchar,
                id_contrato = (v_obligacion_json->>'id_contrato')::integer,
                fecha = (v_obligacion_json->>'fecha')::date,
                rotacion = (v_obligacion_json->>'rotacion')::integer
            	where id_obligacion_pago = v_id_obligacion_pago_sg[1]::integer;


                SELECT es.*
                INTO v_estado_pago
                FROM tes.tobligacion_pago op
                inner join wf.testado_wf es on es.id_estado_wf = op.id_estado_wf
                inner join wf.ttipo_estado ts on ts.id_tipo_estado= es.id_tipo_estado
                WHERE op.id_obligacion_pago = v_id_obligacion_pago_sg[1]::integer;

				---------------------------------------
                -- REGISTA EL SIGUIENTE ESTADO DEL WF.
                ---------------------------------------

				IF (v_obligacion_json->>'estado')::varchar = 'en_pago' THEN
                	IF v_estado = 'borrador' THEN

                       --   para un estado siguiente
                             SELECT  ps_id_tipo_estado,
                                     ps_codigo_estado
                                     --ps_disparador,
                                     --ps_regla,
                                     --ps_prioridad

                                 into
                                    va_id_tipo_estado,
                                    va_codigo_estado
                                    --va_disparador,
                                    --va_regla,
                                    --va_prioridad

                                FROM wf.f_obtener_estado_wf(
                                 v_id_proceso_wf,
                                  NULL,
                                 v_estado_pago.id_tipo_estado,
                                 'siguiente',
                                 p_id_usuario);

                       v_id_estado_actual =  wf.f_registra_estado_wf(  va_id_tipo_estado[1],
                                                                       v_estado_pago.id_funcionario,
                                                                       v_estado_pago.id_estado_wf,
                                                                       v_id_proceso_wf,
                                                                       p_id_usuario,
                                                                       v_estado_pago.id_usuario_ai,
                                                                       v_estado_pago.usuario_ai,
                                                                       v_estado_pago.id_depto,
                                                                       v_estado_pago.obs);

                       -- actualiza estado en la solicitud
                          update tes.tobligacion_pago   set
                             id_estado_wf =  v_id_estado_actual,
                             estado = va_codigo_estado[1],
                             id_usuario_mod = p_id_usuario,
                             --id_usuario_ai = p_id_usuario_ai,
                             --usuario_ai = p_usuario_ai,
                             fecha_mod = now()

                          where id_proceso_wf = v_id_proceso_wf;


                	END IF;
                END IF;

             ------------------------------------------------------------------------------------------------
             -- copiar detalle de obligacion , verifican la tabla id_presupuestos_ids si existe se copia....
             ------------------------------------------------------------------------------------------------

			FOR  v_registros_det_json in (select * from json_array_elements(v_obligacion_det_json)) LOOP

                    --recuperar centro de costos para la siguiente gestion  (los centro de costos y presupuestos tiene los mismo IDS)


                      select
                        pi.id_presupuesto_dos
                      into
                        v_id_centro_costo_dos
                      from pre.tpresupuesto_ids pi
                      where pi.id_presupuesto_uno = ((v_registros_det_json->>'id_centro_costo')::VARCHAR)::integer;--v_registros_det.id_centro_costo;

                      IF v_id_centro_costo_dos is not NULL THEN

 							insert into tes.tobligacion_det(
                                estado_reg,
                                --id_cuenta,
                                id_partida,
                                --id_auxiliar,
                                id_concepto_ingas,
                                monto_pago_mo,
                                id_obligacion_pago,
                                id_centro_costo,
                                monto_pago_mb,
                                descripcion,
                                fecha_reg,
                                id_usuario_reg,
                                fecha_mod,
                                id_usuario_mod,
                                id_orden_trabajo,
                                factor_porcentual,
                                id_partida_ejecucion_com
                              )
                              values
                              (
                                'activo',
                                --v_parametros.id_cuenta,
                                ((v_registros_det_json->>'id_partida')::varchar)::integer,
                                --v_parametros.id_auxiliar,
                               ((v_registros_det_json->>'id_concepto_ingas')::varchar)::integer,
                                v_monto_pp,
                                --((v_registros_det_json->'monto_pago_mo')::varchar)::numeric,
                                v_id_obligacion_pago_sg[1]::integer,
                                --'24033'::INTEGER,
                                v_id_centro_costo_dos,
                                v_monto_pp*v_tipo_cambio_conv,
                                --((v_registros_det_json->'monto_pago_mb')::varchar)::numeric,
                                ((v_registros_det_json->>'descripcion')::varchar)::text,
                                now(),
                                p_id_usuario,
                                null,
                                null,
                                ((v_registros_det_json->>'id_orden_trabajo')::varchar)::integer,
                                ((v_registros_det_json->>'factor_porcentual')::varchar)::numeric,
                                ((v_registros_det_json->>'id_partida_ejecucion_com')::varchar)::integer

                              )RETURNING id_obligacion_det into v_id_obligacion_det;




                      END IF;

              END LOOP;



             ------------------------------------------------------------------------------------------------
             -- copiar plan de pago de la obligacion
             ------------------------------------------------------------------------------------------------
           FOR  v_registros_pp_json in (select * from json_array_elements(v_obligacion_pp_json)LIMIT 1) LOOP
   		      v_obs_monto_no_pagado = ((v_registros_pp_json->>'obs_monto_no_pagado')::varchar )::text;

                v_hstore_registros_pp =   hstore(ARRAY[
               								 'nro_cuota',(v_registros_pp_json->>'nro_cuota')::varchar,
                                              'estado', 'borrador'::varchar,
                                             'tipo_pago',(v_registros_pp_json->>'tipo_pago')::varchar,
                                             'monto_ejecutar_total_mo',(v_registros_pp_json->>'monto_ejecutar_total_mo')::varchar,
                                             'id_plantilla',(v_registros_pp_json->>'id_plantilla')::varchar,
                                             'descuento_anticipo',(v_registros_pp_json->>'descuento_anticipo')::varchar,
                                             'otros_descuentos',(v_registros_pp_json->>'otros_descuentos')::varchar,
                                             'tipo',(v_registros_pp_json->>'tipo')::varchar,
                                             'monto',(v_registros_pp_json->>'monto')::varchar,
                                             'nombre_pago',(v_registros_pp_json->>'nombre_pago')::varchar,
                                             'forma_pago',(v_registros_pp_json->>'forma_pago')::varchar,
                                             'fecha_reg',(v_registros_pp_json->>'fecha_reg')::varchar,
                                             'id_usuario_reg',(v_registros_pp_json->>'id_usuario_reg')::varchar,
                                             --'fecha_tentativa',(v_registros_pp_json->>'fecha_tentativa')::varchar,
                                             --'fecha_costo_ini',(v_registros_pp_json->>'fecha_costo_ini')::varchar,
                                             --'fecha_costo_fin',(v_registros_pp_json->>'fecha_costo_fin')::varchar,
                                             'es_ultima_cuota',(v_registros_pp_json->>'es_ultima_cuota')::varchar,
                                             'id_usuario_ai',(v_registros_pp_json->>'id_usuario_ai')::varchar,
                                             'usuario_ai',(v_registros_pp_json->>'usuario_ai')::varchar,
                                             'monto_no_pagado',(v_registros_pp_json->>'monto_no_pagado')::varchar,
                                             'monto_retgar_mo',(v_registros_pp_json->>'monto_retgar_mo')::varchar,
                                             'descuento_ley',(v_registros_pp_json->>'descuento_ley')::varchar,
                                             'porc_descuento_ley',(v_registros_pp_json->>'porc_descuento_ley')::varchar,
                                             'id_obligacion_pago',v_id_obligacion_pago_sg[1]::varchar,
                                             'id_depto_lb',(v_registros_pp_json->>'id_depto_lb')::varchar,
                                             'obs_monto_no_pagado',(v_registros_pp_json->>'obs_monto_no_pagado')::varchar
                                            ]);

            END LOOP;

			v_resp = tes.f_inserta_plan_pago_dev(p_administrador, p_id_usuario,hstore(v_hstore_registros_pp));
            v_id_obligacion_pago_sg_pp =  pxp.f_recupera_clave(v_resp, 'id_plan_pago');
            v_id_gestion_sg_pp =  pxp.f_recupera_clave(v_resp, 'id_gestion');
            --v_id_obligacion_pago_sg_pp =  pxp.f_recupera_clave(v_resp, 'id_plan_pago');

     		   update tes.tplan_pago set
               id_depto_lb = (v_registros_pp_json->>'id_depto_lb')::numeric,
               obs_monto_no_pagado = v_obs_monto_no_pagado,
               obs_descuentos_ley = (v_registros_pp_json->>'obs_descuentos_ley')::text,
               obs_descuentos_anticipo= (v_registros_pp_json->>'obs_descuentos_anticipo')::text,
               fecha_tentativa = (v_registros_pp_json->>'fecha_tentativa')::date,
               fecha_costo_ini = (v_registros_pp_json->>'fecha_costo_ini')::date,
               fecha_costo_fin = (v_registros_pp_json->>'fecha_costo_fin')::date,
               nro_cuota = (v_registros_pp_json->>'nro_cuota')::numeric,
               fecha_conformidad = (v_registros_pp_json->>'fecha_conformidad')::date,
               conformidad = (v_registros_pp_json->>'conformidad')::text
               where id_plan_pago = v_id_obligacion_pago_sg_pp[1]::integer;


      --para recuperar los documentos de una cuota

            		SELECT op.num_tramite
                    INTO v_num_tramite
                    FROM tes.tobligacion_pago op
                    WHERE op.id_obligacion_pago = v_id_obligacion_pago_sg[1]::integer;

                    SELECT pp.id_proceso_wf, pp.id_estado_wf
                    INTO v_id_proceso_wf, v_id_estado_wf
                    FROM tes.tplan_pago pp
                    --inner join tes.tobligacion_pago op on op.id_obligacion_pago = pp.id_obligacion_pago
                    WHERE pp.id_plan_pago = v_id_obligacion_pago_sg_pp[1]::integer;
                    --op.id_obligacion_pago = v_id_obligacion_pago_sg[1]::integer;

                  /*  select dwf.id_documento_wf, dwf.id_documento_wf
                    into v_id_documento_wf_op, v_id_documento_wf_n
                    from wf.tdocumento_wf dwf
                    inner  join  wf.ttipo_documento td on td.id_tipo_documento = dwf.id_tipo_documento and td.estado_reg = 'activo'
                    where td.codigo ='factura' and dwf.id_proceso_wf =  ((v_documentos_json->>'id_proceso_wf')::varchar)::integer;
                   */


            --para copiar los documentos
              for v_documentos_json in (select * from json_array_elements(v_documentos)) loop
                --v_id_documento_wf_op = 30;
               		INSERT INTO
                                wf.tdocumento_wf
                              (
                                id_usuario_reg,
                                fecha_reg,
                                estado_reg,
                                id_tipo_documento,
                                id_proceso_wf,
                                num_tramite,
                                chequeado,
                                url,
                                extension,
                                obs,
                                chequeado_fisico,
                                id_usuario_upload,
                                fecha_upload,
                                id_documento_wf_ori,
                                id_estado_ini
                              )
                              VALUES (
                                p_id_usuario,
                                now(),
                               'activo',
                                ((v_documentos_json->>'id_tipo_documento')::varchar)::integer,
                                v_id_proceso_wf,
                                v_num_tramite,
                                (v_documentos_json->>'chequeado')::varchar,
                                (v_documentos_json->>'url')::varchar,
                                (v_documentos_json->>'extension')::varchar,
                                ((v_documentos_json->>'obs')::varchar)::text,
                                (v_documentos_json->>'chequeado_fisico')::varchar,
                                ((v_documentos_json->>'id_usuario_upload')::varchar)::integer,
                                ((v_documentos_json->>'fecha_upload')::varchar)::timestamp ,
                                ((v_documentos_json->>'id_documento_wf')::varchar)::integer,
                                v_id_estado_wf
                               );
                              -- )RETURNING id_documento_wf into v_id_documento_wf_n;

           END LOOP;
            -- Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Se extendio la obligacion de pago a un internacional SPI');
            v_resp = pxp.f_agrega_clave(v_resp,'id_obligacion_pago',(v_obligacion_json->>'id_obligacion_pago')::varchar);
			--v_resp = pxp.f_agrega_clave(v_resp,'id_obligacion_pago',(v_id_obligacion_pago_sg[1])::varchar);

          --Devuelve la respuesta
          return v_resp;

EXCEPTION

	WHEN OTHERS THEN
		v_resp='';
		v_resp = pxp.f_agrega_clave(v_resp,'mensaje',SQLERRM);
		v_resp = pxp.f_agrega_clave(v_resp,'codigo_error',SQLSTATE);
		v_resp = pxp.f_agrega_clave(v_resp,'procedimientos',v_nombre_funcion);
        v_resp = pxp.f_agrega_clave(v_resp,'foo','barr');
        v_resp = pxp.f_agrega_clave(v_resp,'preguntar',v_preguntar);

		raise exception '%',v_resp;

END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;

ALTER FUNCTION tes.ft_obligacion_pago_inter_bue_ime (p_administrador integer, p_id_usuario integer, v_obligacion_pago_json json, v_obligacion_det_json json, v_obligacion_pp_json json, v_documentos json)
  OWNER TO maylee;