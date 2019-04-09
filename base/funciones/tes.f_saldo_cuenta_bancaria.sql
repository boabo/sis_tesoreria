CREATE OR REPLACE FUNCTION tes.f_saldo_cuenta_bancaria (
  p_id_cuenta_bancaria integer,
  p_id_periodo integer
)
RETURNS numeric AS
$body$
DECLARE
	v_resp 					numeric;
    v_fecha_ini				date;
    v_fecha_fin				date;
	v_offset				integer; 
    v_max					integer;   
    
BEGIN

	select per.fecha_ini, per.fecha_fin
     into  v_fecha_ini, v_fecha_fin
    from param.tperiodo per 
    where per.id_periodo = p_id_periodo;

        SELECT	
            count(lb.id_cuenta_bancaria)
            into v_offset
            FROM tes.tts_libro_bancos LB
            LEFT JOIN tes.tts_libro_bancos lbp on lbp.id_libro_bancos=LB.id_libro_bancos_fk
            WHERE
            LB.id_cuenta_bancaria = p_id_cuenta_bancaria and
            LB.fecha BETWEEN  v_fecha_ini and   v_fecha_fin
            and   LB.estado in ('impreso',
                                     'entregado','cobrado',
                                     'anulado','reingresado',
                                     'depositado','transferido',
                                     'sigep_swift' )
            and
               LB.tipo in   ('cheque',
                                            'deposito',
                                            'debito_automatico',
                                            'transferencia_carta')
            and
               LB.id_finalidad in (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13); 
    if v_offset = 0 then 
    	v_max = 0;
    else                           
		v_max = v_offset::integer - 1;
    end if;

    SELECT
        (Select sum(lbr.importe_deposito) - sum(lbr.importe_cheque)
                         From tes.tts_libro_bancos lbr
                         where
                         lbr.id_cuenta_bancaria = LB.id_cuenta_bancaria
                         and lbr.estado not in ('anulado','borrador')
                         and ((lbr.fecha < LB.fecha) or (lbr.fecha = LB.fecha and lbr.indice <= LB.indice))

                          )as saldo
        into v_resp                      
        FROM tes.tts_libro_bancos LB
        LEFT JOIN tes.tts_libro_bancos lbp on lbp.id_libro_bancos=LB.id_libro_bancos_fk
        WHERE
        LB.id_cuenta_bancaria = p_id_cuenta_bancaria and
        LB.fecha BETWEEN v_fecha_ini and  v_fecha_fin
         and
           LB.estado in ('impreso',
                                 'entregado','cobrado',
                                 'anulado','reingresado',
                                 'depositado','transferido',
                                 'sigep_swift' )
        and
           LB.tipo in   ('cheque',
                                        'deposito',
                                        'debito_automatico',
                                        'transferencia_carta')
        and
           LB.id_finalidad in (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13)    
          order by lb.fecha, lb.indice asc
          offset v_max;   
        
      
return v_resp;      

END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;