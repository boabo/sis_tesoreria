CREATE OR REPLACE FUNCTION tes.f_obligacion_pp_prorrateo (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Tesoreria
 FUNCION: 		tes.f_obligacion_pp_prorrateo
 DESCRIPCION:   Funcion para reporte de consolidacion de fondos 'tes.f_obligacion_pp_prorrateo'
 AUTOR: 		 (admin)
 FECHA:	        10-12-2018 12:55:30
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:
 AUTOR:
 FECHA:
***************************************************************************/

DECLARE

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;


BEGIN

	v_nombre_funcion = 'tes.f_obligacion_pp_prorrateo';
    v_parametros = pxp.f_get_record(p_tabla);

	  /*********************************
 	#TRANSACCION:  'TES_ROPPPRO_SEL'
 	#DESCRIPCION:	Obtener reporte de Obligacion de pagos, plan de pagos, prorrateo
 	#AUTOR:		admin
 	#FECHA:		10-12-2018
	***********************************/

	if(p_transaccion='TES_ROPPPRO_SEL')then

    	begin

        	v_consulta = 'select
                                opp.desc_proveedor::varchar,
                                opp.ult_est_pp::varchar,
                                opp.num_tramite::varchar,
                                opp.nro_cuota::numeric,
                                opp.estado_pp::varchar,
                                opp.tipo_cuota::varchar,
                                opp.nro_cbte::varchar,
                                opp.c31::varchar,
                                opp.monto_ejecutar_mo::numeric,
                                opp.ret_garantia::numeric,
                                opp.liq_pagable::numeric,
                                opp.desc_ingas::varchar,
                                opp.codigo_cc::varchar,
                                opp.partida::varchar,
                                opp.codigo_categoria::varchar


                        from tes.vobligacion_pp_prorrateo opp



                  where opp.fecha BETWEEN '''||v_parametros.fecha_ini||''' and '''||v_parametros.fecha_fin ||'''

                  	';

       --raise notice '%', v_consulta;
            return v_consulta;

--raise notice '%', v_consulta;
     end;



	else

		raise exception 'Transaccion inexistente';

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