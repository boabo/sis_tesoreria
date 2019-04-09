CREATE OR REPLACE FUNCTION tes.ft_conciliacion_bancaria_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Tesoreria
 FUNCION: 		tes.ft_conciliacion_bancaria_sel
 DESCRIPCION:   Funcion conciliacion bancaria
 AUTOR: 		Breydi vasquez pacheco
 FECHA:	        19-02-2019
 COMENTARIOS:
***************************************************************************/

DECLARE

	v_consulta    		varchar;
    v_con				varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
    v_filtro_saldo		varchar;
    v_fecha_anterior	date;
    v_cnx 				varchar;
    v_fecha_ini			date;
    v_fecha_fin 		date;
    v_periodo			integer;
    v_pe			    integer;
    v_fecha_repo		date;
    i 					integer;
    v_count				integer;    
BEGIN

	v_nombre_funcion = 'tes.ft_conciliacion_bancaria_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'TES_CONCBAN_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		Breydi vasquez pacheco
 	#FECHA:		19-02-2019
	***********************************/

	if(p_transaccion='TES_CONCBAN_SEL')then

    	begin
    		--Sentencia de la consulta
			v_consulta:=' select conci.id_conciliacion_bancaria,
                                 conci.id_cuenta_bancaria,
                                 conci.id_funcionario_elabo,
                                 conci.id_funcionario_vb,
                                 ges.id_gestion,
                                 per.id_periodo,
                                 conci.saldo_banco,
                                 conci.observaciones,
                                 conci.fecha,
                                 conci.fecha_reg,
                                 ges.gestion,
								 param.f_literal_periodo(per.id_periodo)  as literal,
                                 usu1.cuenta as usr_reg,
                                 usu2.cuenta as usr_mod,
                                 fun.desc_funcionario2 as fun_elabo,
                                 fu.desc_funcionario2  as fun_vb                                      
                          from tes.tconciliacion_bancaria conci
                          inner join segu.tusuario usu1 on usu1.id_usuario = conci.id_usuario_reg
                          left join segu.tusuario usu2 on usu2.id_usuario = conci.id_usuario_mod
                          inner join param.tgestion ges on ges.id_gestion = conci.id_gestion
                          inner join param.tperiodo per on per.id_periodo = conci.id_periodo
                          left join orga.vfuncionario fun on fun.id_funcionario = conci.id_funcionario_elabo
                          left join orga.vfuncionario fu on fu.id_funcionario = conci.id_funcionario_vb                          
                          where  ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;

		end;

	/*********************************
 	#TRANSACCION:  'TES_CONCBAN_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		Breydi vasquez pacheco
 	#FECHA:		19-02-2019
	***********************************/

	elsif(p_transaccion='TES_CONCBAN_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(conci.id_conciliacion_bancaria)
                          from tes.tconciliacion_bancaria conci
                          inner join segu.tusuario usu1 on usu1.id_usuario = conci.id_usuario_reg
                          left join segu.tusuario usu2 on usu2.id_usuario = conci.id_usuario_mod
                          inner join param.tgestion ges on ges.id_gestion = conci.id_gestion
                          inner join param.tperiodo per on per.id_periodo = conci.id_periodo
                          inner join orga.vfuncionario fun on fun.id_funcionario = conci.id_funcionario_elabo
                          inner join orga.vfuncionario fu on fu.id_funcionario = conci.id_funcionario_vb
					    where ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;

	/*********************************
 	#TRANSACCION:  'TES_RECONCBAN_SEL'
 	#DESCRIPCION:	Reporte Conciliaicion Bancaria
 	#AUTOR:		BVP
 	#FECHA:		19-02-2019
	***********************************/

	elsif(p_transaccion='TES_RECONCBAN_SEL')then

		begin                        
      --Sentencia de la consulta de conteo de registros  
    create temp table tt_conciliacion_t3 (
        nombre_institucion 	varchar,
        nro_cuenta 			varchar,
        denominacion		varchar,
        moneda				varchar,
        nro_cheque 			varchar,
        concepto			text,        
        total_haber 		numeric(18,2),
        indice 				numeric(18,2),
        fecha				date                
    ) on commit drop;       
	v_periodo = v_parametros.id_periodo;

        select per.fecha_fin
	           into v_fecha_repo                
        from param.tperiodo per 
        where per.id_periodo = v_periodo;
              
   for i in 1..3 loop
   
        select per.fecha_ini, per.fecha_fin
         into  v_fecha_ini, v_fecha_fin
        from param.tperiodo per 
        where per.id_periodo = v_periodo;  

        SELECT
           into v_count 
            count(lb.id_cuenta_bancaria)
            FROM tes.tts_libro_bancos LB
            LEFT JOIN tes.tts_libro_bancos lbp on lbp.id_libro_bancos=LB.id_libro_bancos_fk
            WHERE
            LB.id_cuenta_bancaria = v_parametros.id_cuenta_bancaria and
            LB.fecha BETWEEN v_fecha_ini and   v_fecha_fin 
            and LB.estado in ('impreso', 'entregado' )
            and
            LB.tipo in   ('cheque',
                                            'deposito',
                                            'debito_automatico',
                                            'transferencia_carta')
            and
            LB.id_finalidad in (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13);                  
	if v_count = 0 then v_count = 1; end if;
  insert into  tt_conciliacion_t3 (nombre_institucion,nro_cuenta,denominacion,moneda,nro_cheque,concepto,total_haber,indice,fecha)
         select
         ins.nombre,
         cuba.nro_cuenta,
         cuba.denominacion,
         mon.moneda,                
         case when v_count > 1 then
         'VARIOS'::varchar
         else                
         ''||LB.nro_cheque||''::varchar end,         
        ('CHEQUES EN CIRCULACION '||upper(tes.f_month(lb.fecha))||'/'||substr((''||extract(year from lb.fecha)||'')::text,3,4)),         
        (Select sum(lbr.importe_cheque)
                         From tes.tts_libro_bancos lbr
                         where lbr.fecha BETWEEN  v_fecha_ini and  LB.fecha
                         and lbr.id_cuenta_bancaria = LB.id_cuenta_bancaria
                         and 
                          lbr.estado in ('impreso', 'entregado' )
                          and
                         lbr.tipo in   ('cheque',
                                        'deposito',
                                        'debito_automatico',
                                        'transferencia_carta')
                          and
                          lbr.id_finalidad in (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13)
                          ) as total_haber,
        LB.indice,
        v_fecha_repo
        FROM tes.tts_libro_bancos LB
        left join tes.tts_libro_bancos lbp on lbp.id_libro_bancos=LB.id_libro_bancos_fk
        left join tes.tcuenta_bancaria cuba on cuba.id_cuenta_bancaria = lb.id_cuenta_bancaria
        left join param.tinstitucion ins on ins.id_institucion = cuba.id_institucion
        left join param.tmoneda mon on mon.id_moneda = cuba.id_moneda
        WHERE
        LB.id_cuenta_bancaria = v_parametros.id_cuenta_bancaria and
        LB.fecha BETWEEN  v_fecha_ini and   v_fecha_fin 
        and LB.estado in ('impreso', 'entregado' )
        and
        LB.tipo in   ('cheque',
                                        'deposito',
                                        'debito_automatico',
                                        'transferencia_carta')
        and
        LB.id_finalidad in (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13)              
          order by lb.fecha, lbp.indice, lb.nro_cheque asc
          offset v_count - 1;
                  
        v_periodo = v_periodo - 1;
	end loop;  
    
      v_consulta:='select
      			   t3.nombre_institucion,
                   t3.nro_cuenta,
                   t3.concepto,
                   to_char(t3.fecha,''dd/mm/yyyy'')::text as fecha,
                   t3.total_haber as saldo,
                   t3.moneda,
                   t3.denominacion,
                   t3.nro_cheque    
                 from tt_conciliacion_t3 t3
                 where ';
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;
	/*********************************
 	#TRANSACCION:  'TES_RECONCBANDET_SEL'
 	#DESCRIPCION:	Reporte Conciliaicion Bancaria
 	#AUTOR:		BVP
 	#FECHA:		19-02-2019
	***********************************/

	elsif(p_transaccion='TES_RECONCBANDET_SEL')then

		begin             
			v_consulta:='
                        select 
                        ins.nombre as nombre_institucion,
                        conba.fecha,
                        conba.saldo_banco as saldo,
                        conba.observaciones as observaciones,
                        fun.desc_funcionario2 as fun_elab,
                        fu.desc_funcionario2 as fun_vb,
                        mon.moneda,
                        to_char(deta.fecha, ''dd/mm/yyyy'')::text  as fecha_reg,
                        deta.concepto as concepto,
                        deta.importe,
                        deta.nro_comprobante,
                        deta.tipo,
                        tes.f_saldo_cuenta_bancaria(conba.id_cuenta_bancaria,conba.id_periodo) as sal_ext_ban,
                        param.f_literal_periodo(conba.id_periodo)as periodo,
                        ges.gestion,
                        cban.nro_cuenta,
                        cban.denominacion                        
                        from  tes.tconciliacion_bancaria conba 
                        left join tes.tdetalle_conciliacion_bancaria deta on deta.id_conciliacion_bancaria =conba.id_conciliacion_bancaria
                        inner join tes.tcuenta_bancaria cban on cban.id_cuenta_bancaria = conba.id_cuenta_bancaria
                        inner join param.tinstitucion ins on ins.id_institucion  = cban.id_institucion                        
                        left join orga.vfuncionario fun on fun.id_funcionario = conba.id_funcionario_elabo
                        left join orga.vfuncionario fu on fu.id_funcionario = conba.id_funcionario_vb 
                        inner join tes.tcuenta_bancaria cuba on cuba.id_cuenta_bancaria = conba.id_cuenta_bancaria               
                        inner join param.tmoneda mon on mon.id_moneda =  cuba.id_moneda
                        inner join param.tgestion ges on ges.id_gestion = conba.id_gestion
                        where conba.id_cuenta_bancaria = '||v_parametros.id_cuenta_bancaria||'
                        and conba.id_periodo = '||v_parametros.id_periodo ||' ';

			--Definicion de la respuesta
            raise notice '%',v_consulta;
--			v_consulta:=v_consulta||v_parametros.filtro;

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