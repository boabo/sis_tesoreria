<?php

class MODObPlanProrra extends MODbase
{

    function __construct(CTParametro $pParam)
    {
        parent::__construct($pParam);
    }

    function reporteObPlanProrra()
    {
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento = 'tes.f_obligacion_pp_prorrateo';
        $this->transaccion = 'TES_ROPPPRO_SEL';
        $this->tipo_procedimiento = 'SEL';//tipo de transaccion

        $this->setParametro('fecha_ini', 'fecha_ini', 'date');
        $this->setParametro('fecha_fin', 'fecha_fin', 'date');
        //$this->setParametro('tipo', 'tipo', 'varchar');
        //$this->setParametro('monto_mayor', 'monto_mayor', 'varchar');

        //Definicion de la lista del resultado del query
        $this->captura('desc_proveedor', 'varchar');
        $this->captura('ult_est_pp', 'varchar');
        $this->captura('num_tramite', 'varchar');
        $this->captura('nro_cuota', 'numeric');
        $this->captura('estado_pp', 'varchar');
        $this->captura('tipo_cuota', 'varchar');
        $this->captura('nro_cbte', 'varchar');
        $this->captura('c31', 'varchar');
        $this->captura('monto_ejecutar_mo', 'numeric');
        $this->captura('ret_garantia', 'numeric');
        $this->captura('liq_pagable', 'numeric');
        $this->captura('desc_ingas', 'varchar');
        $this->captura('codigo_cc', 'varchar');
        $this->captura('partida', 'varchar');
        $this->captura('codigo_categoria', 'varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        //Devuelve la respuesta

//        var_dump($this->respuesta);exit;
        return $this->respuesta;
    }


}

?>