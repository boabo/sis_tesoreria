<?php
/**
 * @package pXP
 * @file gen-ACTProcesoCompra.php
 * @author  (admin)
 * @date 19-03-2013 12:55:30
 * @description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
 */

require_once(dirname(__FILE__) . '/../reportes/RObPlanProrra.php');



class ACTObPlanProrra extends ACTbase
{

    function reporteObPlanProrra()
    {
        //$this->objParam->addParametro('tipo', 'iniciados');
        $this->objFunc = $this->create('MODObPlanProrra');
        $this->res = $this->objFunc->reporteObPlanProrra($this->objParam);
        //$this->objParam->addParametro('iniciados', $this->res->datos);
        $this->objParam->addParametro('datos', $this->res->datos);


//        var_dump( $this->res->datos);exit;


        //obtener titulo del reporte
        $titulo = 'reporteObPlanProrra';

        //Genera el nombre del archivo (aleatorio + titulo)
        $nombreArchivo = uniqid(md5(session_id()) . $titulo);
        $nombreArchivo .= '.xls';
        $this->objParam->addParametro('nombre_archivo', $nombreArchivo);

        $this->objReporteFormato = new RObPlanProrra($this->objParam);
        $this->objReporteFormato->imprimeCabecera();
        $this->objReporteFormato->imprimeReporte();

        $this->objReporteFormato->generarReporte();

        $this->mensajeExito = new Mensaje();
        $this->mensajeExito->setMensaje('EXITO', 'Reporte.php', 'Reporte generado',
            'Se generó con éxito el reporte: ' . $nombreArchivo, 'control');

        $this->mensajeExito->setArchivoGenerado($nombreArchivo);
        $this->mensajeExito->imprimirRespuesta($this->mensajeExito->generarJson());
    }


}

?>