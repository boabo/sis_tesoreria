CREATE OR REPLACE FUNCTION tes.f_month (
  p_fecha date
)
RETURNS varchar AS
$body$
/**************************************************************************
 documento: 	tes.f_month
 DESCRIPCION:   Funcion que obtiene el literal de un id_periodo (mes)
 AUTOR: 	    	
 FECHA:	        
 COMENTARIOS:	
***************************************************************************/
DECLARE
  v_literal varchar;
BEGIN

    
    
    v_literal:= to_char(p_fecha , 'Month');
    
    v_literal:= TRIM(v_literal);
    
    IF v_literal = 'January' THEN
        v_literal := 'Enero';
    ELSIF v_literal = 'February' THEN
    	v_literal := 'Febrero';
    ELSIF v_literal = 'March' THEN
    	v_literal := 'Marzo';
    ELSIF v_literal = 'April' THEN
    	v_literal := 'Abril';
    ELSIF v_literal = 'May' THEN
    	v_literal := 'Mayo';
    ELSIF v_literal = 'June' THEN
    	v_literal := 'Junio';
	ELSIF v_literal = 'July' THEN
    	v_literal := 'Julio';
	ELSIF v_literal = 'August' THEN
    	v_literal := 'Agosto';
    ELSIF v_literal = 'September' THEN
    	v_literal := 'Septiembre';
    ELSIF v_literal = 'October' THEN
    	v_literal := 'Octubre';
    ELSIF v_literal = 'November' THEN
    	v_literal := 'Noviembre';
   ELSIF v_literal = 'December' THEN
    	v_literal := 'Diciembre';
   END IF;
   
    
   

   return v_literal;
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;