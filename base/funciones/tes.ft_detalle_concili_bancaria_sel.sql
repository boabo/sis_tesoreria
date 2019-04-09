CREATE OR REPLACE FUNCTION tes.ft_detalle_concili_bancaria_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Tesoreria
 FUNCION: 		tes.ft_detalle_concili_bancaria_sel
 DESCRIPCION:   Funcion detalle conciliacion bancaria
 AUTOR: 		Breydi vasquez pacheco
 FECHA:	        19-02-2019
 COMENTARIOS:
***************************************************************************/

DECLARE

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
    v_filtro_saldo		varchar;
    v_fecha_anterior	date;
    v_cnx 				varchar;
BEGIN

	v_nombre_funcion = 'tes.ft_detalle_concili_bancaria_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'TES_DETCONCBAN_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		Breydi vasquez pacheco
 	#FECHA:		19-02-2019
	***********************************/

	if(p_transaccion='TES_DETCONCBAN_SEL')then

    	begin
    		--Sentencia de la consulta
			v_consulta:=' select detcon.id_detalle_conciliacion_bancaria,
                                 detcon.id_conciliacion_bancaria,
                                 detcon.fecha,
                                 detcon.concepto,
                                 detcon.nro_comprobante,
                                 detcon.importe,
	                             detcon.tipo,
                                 detcon.fecha_reg,
                                 usu1.cuenta as usr_reg,
                                 usu2.cuenta as usr_mod 
                          from tes.tdetalle_conciliacion_bancaria detcon
                          inner join segu.tusuario usu1 on usu1.id_usuario = detcon.id_usuario_reg
                          left join segu.tusuario usu2 on usu2.id_usuario = detcon.id_usuario_mod
                          where  ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
			raise notice '%',v_consulta;
			--Devuelve la respuesta
			return v_consulta;

		end;

	/*********************************
 	#TRANSACCION:  'TES_DETCONCBAN_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		Breydi vasquez pacheco
 	#FECHA:		19-02-2019
	***********************************/

	elsif(p_transaccion='TES_DETCONCBAN_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(detcon.id_detalle_conciliacion_bancaria)
                          from tes.tdetalle_conciliacion_bancaria detcon
                          inner join segu.tusuario usu1 on usu1.id_usuario = detcon.id_usuario_reg
                          left join segu.tusuario usu2 on usu2.id_usuario = detcon.id_usuario_mod
	           		      where ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

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