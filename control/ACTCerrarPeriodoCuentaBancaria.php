<?php
/*
**********************************************************
Nombre de archivo:         		 ACTCerrarCuentaBancaria.php
Proposito:                       Permite cambiar el estado a cerrado del susbistema Obligaciones de Pago      

Valores de Retorno:         
Fecha de Creacion:                18/02/2016
Version:                          1.0.0
Autor:                            Gonzalo Sarmiento Sejas
**********************************************************
*/

function CerrarPeriodoCuentaBancaria($db){
        
        $rows = 0; // Number of rows
        $qid = 0; // Query result resource
        
        // Get catalog data from system tables.
        $sql = 'SELECT tes.f_cerrar_periodo_cuentas_bancarias()';
        $qid = pg_Exec($db, $sql);

        // Check error
        if (!is_resource($qid)) {
                print('Error al cerrar el periodo cuenta bancaria');
                return null;
        }

        $rows = pg_NumRows($qid);
		
        // Store meta data
        for ($i = 0; $i < $rows; $i++) {
                $res = pg_Result($qid,$i,0); // Field Name
        }
        
        echo 'Periodo cuentas bancarias cerrado - '.$res.'  - '.date("m-d-Y H:i:s").'<BR>';
    return $res;
}

function restar($h1,$h2)
{
        $h2h = date('H', strtotime($h2));
        $h2m = date('i', strtotime($h2));
        $h2s = date('s', strtotime($h2));
        $hora2 =$h2h." hour ". $h2m ." min ".$h2s ." second";
        
        $horas_sumadas= $h1." - ". $hora2;
        $text=date('Y/m/d H:i:s', strtotime($horas_sumadas)) ;
        return $text;
 
}

  //para no llenar la casilla de correo del administrador
        $hora_actual = date("H:i:s");
        $date = date("Y/m/d H:i:s");
        $hora_dif = date('5:00:00');
        
        $date_filtro= restar($hora_actual,$hora_dif);
        
        
        echo "<BR>".$date_filtro." --- ".$date."<BR>";     
       

        //// Test code ////
        $dbName = 'dbkerp_capacitacion'; // desarrollo
		//$dbName = 'dbkerp'; // produccion
        $dbUser = 'tesoreria'; // Change this to your db user name
        $pass = 'tesoreria.boa.2015';
				
		$db = pg_connect("host=172.17.45.229 dbname=".$dbName.' user='.$dbUser." password=".$pass." port=5432");
		//cambio a nuevo servidor de base de datos
		//$db = pg_connect("host=10.150.0.21 dbname=".$dbName.' user='.$dbUser." password=".$pass." port=5432");
		
CerrarPeriodoCuentaBancaria($db);