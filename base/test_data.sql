-------------------------------------------
-- DEPARTAMENTOS DE TESORERIA
-----------------------------------------------

-- INSERT INTO param.tdepto ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_depto", "id_subsistema", "codigo", "nombre", "nombre_corto")
-- VALUES (1, NULL, E'2013-04-05 00:00:00', E'2013-04-05 06:20:20.098', E'activo', 3, 11, E'TES-CEN', E'TEsoreria Central', E'');
--
-- INSERT INTO param.tdepto ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_depto", "id_subsistema", "codigo", "nombre", "nombre_corto")
-- VALUES (1, NULL, E'2013-04-05 00:00:00', E'2013-04-05 06:20:49.279', E'activo', 4, 11, E'TES-ARG', E'Tesoreria Argentina', E'');

-------------------------------------------
-- INICIO ROLES 
-- Autor Gonzalo Sarmiento Sejas
------------------------------------------

--roles--

-- select pxp.f_insert_trol ('responsable de dar curso a la obligacion de pago', 'Responsable Obligacion de Pago', 'TES');

--roles_gui

/*select pxp.f_insert_tgui_rol ('OBPG', 'Responsable Obligacion de Pago');
select pxp.f_insert_tgui_rol ('TES', 'Responsable Obligacion de Pago');
select pxp.f_insert_tgui_rol ('OBPG.1', 'Responsable Obligacion de Pago');*/

--procedimientos_gui

/*select pxp.f_insert_tprocedimiento_gui ('TES_OBPG_INS', 'OBPG', 'no');
select pxp.f_insert_tprocedimiento_gui ('TES_OBPG_MOD', 'OBPG', 'no');
select pxp.f_insert_tprocedimiento_gui ('TES_OBPG_ELI', 'OBPG', 'no');
select pxp.f_insert_tprocedimiento_gui ('TES_OBPG_SEL', 'OBPG', 'no');
select pxp.f_insert_tprocedimiento_gui ('TES_FINREG_IME', 'OBPG', 'no');
select pxp.f_insert_tprocedimiento_gui ('TES_ANTEOB_IME', 'OBPG', 'no');
select pxp.f_insert_tprocedimiento_gui ('PM_CONIG_SEL', 'OBPG.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('CONTA_CTA_SEL', 'OBPG.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('CONTA_CTA_ARB_SEL', 'OBPG.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('PRE_PAR_SEL', 'OBPG.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('PRE_PAR_ARB_SEL', 'OBPG.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('CONTA_AUXCTA_SEL', 'OBPG.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('TES_OBDET_INS', 'OBPG.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('TES_OBDET_MOD', 'OBPG.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('TES_OBDET_ELI', 'OBPG.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('TES_OBDET_SEL', 'OBPG.1', 'no');
select pxp.f_insert_tprocedimiento_gui ('PM_DEPPTO_SEL', 'OBPG', 'no');
select pxp.f_insert_tprocedimiento_gui ('PM_MONEDA_SEL', 'OBPG', 'no');
select pxp.f_insert_tprocedimiento_gui ('PM_PROVEEV_SEL', 'OBPG', 'no');
select pxp.f_insert_tprocedimiento_gui ('TES_PLAPA_SEL', 'OBPG', 'no');
select pxp.f_insert_tprocedimiento_gui ('TES_PAFPP_IME', 'OBPG', 'no');
select pxp.f_insert_tprocedimiento_gui ('PM_PLT_SEL', 'OBPG', 'no');
select pxp.f_insert_tprocedimiento_gui ('TES_PLAPA_INS', 'OBPG', 'no');
select pxp.f_insert_tprocedimiento_gui ('TES_PRO_SEL', 'OBPG', 'no');
select pxp.f_insert_tprocedimiento_gui ('TES_PLAPA_MOD', 'OBPG', 'no');
select pxp.f_insert_tprocedimiento_gui ('TES_PLAPA_ELI', 'OBPG', 'no');
select pxp.f_insert_tprocedimiento_gui ('TES_PRO_MOD', 'OBPG', 'no');
select pxp.f_insert_tprocedimiento_gui ('TES_CTABAN_INS', 'CTABAN', 'no');
select pxp.f_insert_tprocedimiento_gui ('TES_CTABAN_MOD', 'CTABAN', 'no');
select pxp.f_insert_tprocedimiento_gui ('TES_CTABAN_ELI', 'CTABAN', 'no');
select pxp.f_insert_tprocedimiento_gui ('TES_CTABAN_SEL', 'CTABAN', 'no');*/

--rol_procedimiento_gui

/*select pxp.f_insert_trol_procedimiento_gui ('Responsable Obligacion de Pago', 'TES_OBPG_SEL', 'OBPG');
select pxp.f_insert_trol_procedimiento_gui ('Responsable Obligacion de Pago', 'TES_OBDET_SEL', 'OBPG.1');
select pxp.f_insert_trol_procedimiento_gui ('Responsable Obligacion de Pago', 'PM_DEPPTO_SEL', 'OBPG');
select pxp.f_insert_trol_procedimiento_gui ('Responsable Obligacion de Pago', 'PM_MONEDA_SEL', 'OBPG');
select pxp.f_insert_trol_procedimiento_gui ('Responsable Obligacion de Pago', 'TES_OBPG_MOD', 'OBPG');
select pxp.f_insert_trol_procedimiento_gui ('Responsable Obligacion de Pago', 'TES_OBDET_MOD', 'OBPG.1');
select pxp.f_insert_trol_procedimiento_gui ('Responsable Obligacion de Pago', 'PM_PROVEEV_SEL', 'OBPG');
select pxp.f_insert_trol_procedimiento_gui ('Responsable Obligacion de Pago', 'CONTA_CTA_SEL', 'OBPG.1');
select pxp.f_insert_trol_procedimiento_gui ('Responsable Obligacion de Pago', 'PRE_PAR_SEL', 'OBPG.1');
select pxp.f_insert_trol_procedimiento_gui ('Responsable Obligacion de Pago', 'CONTA_AUXCTA_SEL', 'OBPG.1');
select pxp.f_insert_trol_procedimiento_gui ('Responsable Obligacion de Pago', 'TES_FINREG_IME', 'OBPG');
select pxp.f_insert_trol_procedimiento_gui ('Responsable Obligacion de Pago', 'TES_PLAPA_SEL', 'OBPG');
select pxp.f_delete_trol_procedimiento_gui ('Responsable Obligacion de Pago', 'TES_PAFPP_IME', 'OBPG');
select pxp.f_insert_trol_procedimiento_gui ('Responsable Obligacion de Pago', 'TES_PAFPP_IME', 'OBPG');
select pxp.f_insert_trol_procedimiento_gui ('Responsable Obligacion de Pago', 'TES_PLAPA_INS', 'OBPG');
select pxp.f_insert_trol_procedimiento_gui ('Responsable Obligacion de Pago', 'PM_PLT_SEL', 'OBPG');
select pxp.f_insert_trol_procedimiento_gui ('Responsable Obligacion de Pago', 'TES_PRO_SEL', 'OBPG');
select pxp.f_insert_trol_procedimiento_gui ('Responsable Obligacion de Pago', 'TES_PLAPA_MOD', 'OBPG');
select pxp.f_insert_trol_procedimiento_gui ('Responsable Obligacion de Pago', 'TES_PLAPA_ELI', 'OBPG');
select pxp.f_insert_trol_procedimiento_gui ('Responsable Obligacion de Pago', 'TES_PRO_MOD', 'OBPG');

select pxp.f_insert_trol_procedimiento_gui ('Responsable Obligacion de Pago', 'TES_ANTEOB_IME', 'OBPG');*/
-------------------------------------------
-- FIN ROLES 
-- Autor Gonzalo Sarmiento Sejas
------------------------------------------


/*=============================================================== DATOS BASE (f.e.a) =================================================================*/

/* Data for the 'tes.ttipo_plan_pago' table  (Records 1 - 11) */

INSERT INTO tes.ttipo_plan_pago ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_tipo_plan_pago", "codigo", "descripcion", "codigo_proceso_llave_wf", "codigo_plantilla_comprobante", "tipo_ejecucion")
VALUES (1, 1, E'2014-07-15 09:32:41.069', E'2015-05-14 10:52:41.545', E'activo', NULL, E'NULL', 1, E'pagado', E'Solo pagado, previo devengado', E'TPLPP,PUPLPP', E'PAGTESPROV', E'pagado');

INSERT INTO tes.ttipo_plan_pago ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_tipo_plan_pago", "codigo", "descripcion", "codigo_proceso_llave_wf", "codigo_plantilla_comprobante", "tipo_ejecucion")
VALUES (1, 1, E'2014-07-15 09:30:50.178', E'2015-05-08 17:11:52.047', E'activo', NULL, E'NULL', 2, E'devengado_pagado', E'Devngado y pagado con dos comprobantes', E'TPLAP,PUPLAP', E'DEVTESPROV', E'devengado');

INSERT INTO tes.ttipo_plan_pago ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_tipo_plan_pago", "codigo", "descripcion", "codigo_proceso_llave_wf", "codigo_plantilla_comprobante", "tipo_ejecucion")
VALUES (1, 1, E'2014-07-15 09:32:02.788', E'2015-05-08 17:11:32.682', E'activo', NULL, E'NULL', 3, E'devengado', E'Solo devengado', E'TPLAP,PUPLAP', E'DEVTESPROV', E'devengado');

INSERT INTO tes.ttipo_plan_pago ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_tipo_plan_pago", "codigo", "descripcion", "codigo_proceso_llave_wf", "codigo_plantilla_comprobante", "tipo_ejecucion")
VALUES (1, 1, E'2014-07-15 10:06:28.057', E'2015-05-08 17:12:08.350', E'activo', NULL, E'NULL', 4, E'devengado_pagado_1c', E'Devengar y pagar con un solo comprobante', E'TPLAP,PUPLAP', E'DEVPAGTESPROV', E'devengado_pagado');

INSERT INTO tes.ttipo_plan_pago ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_tipo_plan_pago", "codigo", "descripcion", "codigo_proceso_llave_wf", "codigo_plantilla_comprobante", "tipo_ejecucion")
VALUES (1, 1, E'2014-07-17 17:38:26.901', E'2016-11-24 19:22:02.715', E'activo', NULL, E'NULL', 5, E'ant_parcial', E'Anticipo parcial', E'PD_ANT_PAR,PU_ANT_PAR', E'ANTICIPOPARCIAL', E'no_ejecuta');

INSERT INTO tes.ttipo_plan_pago ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_tipo_plan_pago", "codigo", "descripcion", "codigo_proceso_llave_wf", "codigo_plantilla_comprobante", "tipo_ejecucion")
VALUES (1, 1, E'2014-07-21 12:15:33.648', E'2016-11-24 19:22:24.130', E'activo', NULL, E'NULL', 6, E'anticipo', E'Anticipo contra factura o recibo', E'PD_ANT_PAR,PU_ANT_PAR', E'ANTICIPOTOTAL', E'no_ejecuta');

INSERT INTO tes.ttipo_plan_pago ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_tipo_plan_pago", "codigo", "descripcion", "codigo_proceso_llave_wf", "codigo_plantilla_comprobante", "tipo_ejecucion")
VALUES (1, 1, E'2014-07-22 10:41:13.580', E'2016-11-24 19:22:51.473', E'activo', NULL, E'NULL', 7, E'ant_aplicado', E'Aplicaciond e anticipo', E'PD_AP_ANT,PU_AP_ANT', E'APLIC_ANTI', E'devengado_pagado');

INSERT INTO tes.ttipo_plan_pago ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_tipo_plan_pago", "codigo", "descripcion", "codigo_proceso_llave_wf", "codigo_plantilla_comprobante", "tipo_ejecucion")
VALUES (1, 1, E'2014-07-23 12:23:47.391', E'2016-11-24 19:23:03.887', E'activo', NULL, E'NULL', 8, E'dev_garantia', E'Devolucion de garantia', E'PD_ANT_PAR,PU_ANT_PAR', E'DEVOLGAR', E'pagado');

INSERT INTO tes.ttipo_plan_pago ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_tipo_plan_pago", "codigo", "descripcion", "codigo_proceso_llave_wf", "codigo_plantilla_comprobante", "tipo_ejecucion")
VALUES (83, 83, E'2014-11-20 16:33:27', E'2015-07-21 11:50:03.594', E'activo', NULL, E'NULL', 9, E'devengado_rrhh', E'Devengado de RRHH', E'TPLAP', E'DEVPAGRRHH', E'devengado');

INSERT INTO tes.ttipo_plan_pago ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_tipo_plan_pago", "codigo", "descripcion", "codigo_proceso_llave_wf", "codigo_plantilla_comprobante", "tipo_ejecucion")
VALUES (83, 83, E'2014-11-20 16:34:48', E'2014-12-01 19:33:13', E'activo', NULL, E'NULL', 10, E'pagado_rrhh', E'Pago de Obligación RRHH', E'TPLPP', E'PAGRRHH', E'pagado');

INSERT INTO tes.ttipo_plan_pago ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_tipo_plan_pago", "codigo", "descripcion", "codigo_proceso_llave_wf", "codigo_plantilla_comprobante", "tipo_ejecucion")
VALUES (18, NULL, E'2015-08-21 10:36:59.306', NULL, E'activo', NULL, E'NULL', 13, E'especial', E'Pago Simple (Sin efecto presupuestario)', E'PD_ANT_PAR', E'PAGOESPECIAL', E'no_ejecuta');



/* Data for the 'tes.tfinalidad' table  (Records 1 - 13) */

INSERT INTO tes.tfinalidad ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_finalidad", "nombre_finalidad", "color", "estado", "tipo", "sw_tipo_interfaz")
VALUES (NULL, NULL, E'2014-06-02 12:43:26.746', E'2014-06-02 18:11:55.763', E'activo', NULL, NULL, 1, E'Trimestral', E'#808000', E'activo', NULL, E'{fondo_avance}');

INSERT INTO tes.tfinalidad ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_finalidad", "nombre_finalidad", "color", "estado", "tipo", "sw_tipo_interfaz")
VALUES (NULL, NULL, E'2014-06-02 17:15:04.166', NULL, E'activo', NULL, NULL, 2, E'Trasferencias al Exterior', E'#00FF00', E'activo', NULL, NULL);

INSERT INTO tes.tfinalidad ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_finalidad", "nombre_finalidad", "color", "estado", "tipo", "sw_tipo_interfaz")
VALUES (NULL, NULL, E'2014-06-02 17:16:34.785', E'2014-06-02 18:14:37.016', E'activo', NULL, NULL, 3, E'Gastos Financieros', E'#DC143C', E'activo', NULL, NULL);

INSERT INTO tes.tfinalidad ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_finalidad", "nombre_finalidad", "color", "estado", "tipo", "sw_tipo_interfaz")
VALUES (NULL, NULL, E'2014-06-02 17:30:28.077', E'2014-06-02 18:11:33.422', E'activo', NULL, NULL, 4, E'Devoluciones', E'#FFA500', E'activo', NULL, NULL);

INSERT INTO tes.tfinalidad ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_finalidad", "nombre_finalidad", "color", "estado", "tipo", "sw_tipo_interfaz")
VALUES (NULL, NULL, E'2014-06-02 17:31:41.160', E'2014-06-02 18:11:13.125', E'activo', NULL, NULL, 5, E'Proveedores', E'#800080', E'activo', NULL, NULL);

INSERT INTO tes.tfinalidad ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_finalidad", "nombre_finalidad", "color", "estado", "tipo", "sw_tipo_interfaz")
VALUES (NULL, NULL, E'2014-06-02 17:38:04.489', E'2014-06-02 18:13:19.169', E'activo', NULL, NULL, 6, E'Viáticos', E'#FF00FF', E'activo', NULL, NULL);

INSERT INTO tes.tfinalidad ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_finalidad", "nombre_finalidad", "color", "estado", "tipo", "sw_tipo_interfaz")
VALUES (NULL, NULL, E'2014-06-02 17:38:46.752', E'2014-06-02 18:10:38.423', E'activo', NULL, NULL, 7, E'Fondo Rotativo', E'#008080', E'activo', NULL, E'{fondo_avance,caja_chica}');

INSERT INTO tes.tfinalidad ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_finalidad", "nombre_finalidad", "color", "estado", "tipo", "sw_tipo_interfaz")
VALUES (NULL, NULL, E'2014-06-02 17:39:14.466', E'2014-06-02 18:10:18.525', E'activo', NULL, NULL, 8, E'Retenciones Judiciales', E'#800000', E'activo', NULL, NULL);

INSERT INTO tes.tfinalidad ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_finalidad", "nombre_finalidad", "color", "estado", "tipo", "sw_tipo_interfaz")
VALUES (NULL, NULL, E'2014-06-02 17:39:43.296', E'2014-06-02 18:10:00.705', E'activo', NULL, NULL, 9, E'Fondo Social', E'#808080', E'activo', NULL, NULL);

INSERT INTO tes.tfinalidad ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_finalidad", "nombre_finalidad", "color", "estado", "tipo", "sw_tipo_interfaz")
VALUES (NULL, NULL, E'2014-06-02 18:37:04.053', NULL, E'activo', NULL, NULL, 10, E'Ingresos', E'#0000FF', E'activo', NULL, NULL);

INSERT INTO tes.tfinalidad ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_finalidad", "nombre_finalidad", "color", "estado", "tipo", "sw_tipo_interfaz")
VALUES (NULL, NULL, E'2014-06-02 18:38:37.282', E'2014-06-02 18:38:51.950', E'activo', NULL, NULL, 11, E'Otros ingreso o depositos', E'#2E8B57', E'activo', NULL, NULL);

INSERT INTO tes.tfinalidad ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_finalidad", "nombre_finalidad", "color", "estado", "tipo", "sw_tipo_interfaz")
VALUES (NULL, NULL, E'2014-09-03 09:20:08.553', E'2014-09-03 09:20:46.581', E'activo', NULL, NULL, 12, E'Servicio de Impuestos Nacionales ', E'#9ACD32', E'activo', NULL, NULL);

INSERT INTO tes.tfinalidad ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_finalidad", "nombre_finalidad", "color", "estado", "tipo", "sw_tipo_interfaz")
VALUES (NULL, NULL, E'2014-09-03 09:21:12.493', NULL, E'activo', NULL, NULL, 13, E'HOTELES', E'#000000', E'activo', NULL, NULL);


/* Data for the 'tes.ttipo_proceso_caja' table  (Records 1 - 4) */

INSERT INTO tes.ttipo_proceso_caja ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_tipo_proceso_caja", "codigo", "nombre", "codigo_plantilla_cbte", "codigo_wf", "visible_en")
VALUES (175, NULL, E'2016-06-30 16:50:53.656', NULL, E'activo', NULL, E'NULL', 1, E'REPO', E'Apertura (Reposición inicial)', E'REPOCAJA', E'REPO', E'cerrado');

INSERT INTO tes.ttipo_proceso_caja ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_tipo_proceso_caja", "codigo", "nombre", "codigo_plantilla_cbte", "codigo_wf", "visible_en")
VALUES (175, NULL, E'2016-06-30 16:53:16.482', NULL, E'activo', NULL, E'NULL', 2, E'CIERRE', E'Cierre de Caja', E'CIERRECAJA', E'CIERRE', E'abierto');

INSERT INTO tes.ttipo_proceso_caja ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_tipo_proceso_caja", "codigo", "nombre", "codigo_plantilla_cbte", "codigo_wf", "visible_en")
VALUES (175, NULL, E'2016-06-30 16:53:53.863', NULL, E'activo', NULL, E'NULL', 3, E'SOLREN', E'Solo Rendir', E'RENDICIONCAJA', E'REN', E'abierto');

INSERT INTO tes.ttipo_proceso_caja ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_tipo_proceso_caja", "codigo", "nombre", "codigo_plantilla_cbte", "codigo_wf", "visible_en")
VALUES (175, NULL, E'2016-06-30 16:54:17.700', NULL, E'activo', NULL, E'NULL', 4, E'SOLREP', E'Solo Reponer', E'REPOCAJA', E'REPO', E'abierto');


/* Data for the 'tes.ttipo_prorrateo' table  (Records 1 - 5) */

INSERT INTO tes.ttipo_prorrateo ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_tipo_prorrateo", "codigo", "nombre", "descripcion", "es_plantilla", "tiene_cuenta", "tiene_lugar")
VALUES (83, 83, E'2014-08-01 17:45:26', E'2014-08-05 11:05:36', E'activo', NULL, E'NULL', 1, E'POFI', E'Prorrateo por Oficina', NULL, E'no', E'si', E'no');

INSERT INTO tes.ttipo_prorrateo ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_tipo_prorrateo", "codigo", "nombre", "descripcion", "es_plantilla", "tiene_cuenta", "tiene_lugar")
VALUES (83, NULL, E'2014-08-01 17:45:52', E'2014-09-24 15:06:54', E'activo', NULL, E'NULL', 2, E'PGLOBAL', E'Prorrateo por empleado global', NULL, E'no', E'si', E'no');

INSERT INTO tes.ttipo_prorrateo ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_tipo_prorrateo", "codigo", "nombre", "descripcion", "es_plantilla", "tiene_cuenta", "tiene_lugar")
VALUES (83, NULL, E'2014-08-01 18:15:59', E'2014-09-24 15:06:54', E'activo', NULL, E'NULL', 3, E'PCELULAR', E'Prorrateo por Consumo en Celulares Corporativos', NULL, E'no', E'no', E'no');

INSERT INTO tes.ttipo_prorrateo ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_tipo_prorrateo", "codigo", "nombre", "descripcion", "es_plantilla", "tiene_cuenta", "tiene_lugar")
VALUES (83, NULL, E'2014-09-24 15:08:40.380', NULL, E'activo', NULL, E'NULL', 4, E'P4G', E'Prorrateo por Consumo en redes de datos 4G', NULL, E'no', E'no', E'no');

INSERT INTO tes.ttipo_prorrateo ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_tipo_prorrateo", "codigo", "nombre", "descripcion", "es_plantilla", "tiene_cuenta", "tiene_lugar")
VALUES (83, NULL, E'2014-10-01 10:22:41.311', NULL, E'activo', NULL, E'NULL', 5, E'PFIJO', E'Prorrateo por Consumo en Telefonos Fijos', NULL, E'no', E'no', E'no');


/* Data for the 'tes.ttipo_solicitud' table  (Records 1 - 7) */

INSERT INTO tes.ttipo_solicitud ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_tipo_solicitud", "codigo", "nombre", "codigo_proceso_llave_wf", "codigo_plantilla_comprobante")
VALUES (175, NULL, E'2016-07-01 09:13:04.801', NULL, E'activo', NULL, E'NULL', 1, E'SOLEFE', E'solicitud', E'SOLICITUD EFECTIVO', NULL);

INSERT INTO tes.ttipo_solicitud ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_tipo_solicitud", "codigo", "nombre", "codigo_proceso_llave_wf", "codigo_plantilla_comprobante")
VALUES (175, NULL, E'2016-07-01 09:13:28.630', NULL, E'activo', NULL, E'NULL', 2, E'RENEFE', E'rendicion', E'RENDICION EFECTIVO', NULL);

INSERT INTO tes.ttipo_solicitud ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_tipo_solicitud", "codigo", "nombre", "codigo_proceso_llave_wf", "codigo_plantilla_comprobante")
VALUES (175, NULL, E'2016-07-01 09:17:28.359', NULL, E'activo', NULL, E'NULL', 3, E'DEVEFE', E'devolucion', E'DEVOLUCION EFECTIVO', NULL);

INSERT INTO tes.ttipo_solicitud ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_tipo_solicitud", "codigo", "nombre", "codigo_proceso_llave_wf", "codigo_plantilla_comprobante")
VALUES (175, NULL, E'2016-07-01 09:17:58.138', NULL, E'activo', NULL, E'NULL', 4, E'REPEFE', E'reposicion', E'REPOSICION EFECTIVO', NULL);

INSERT INTO tes.ttipo_solicitud ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_tipo_solicitud", "codigo", "nombre", "codigo_proceso_llave_wf", "codigo_plantilla_comprobante")
VALUES (175, NULL, E'2016-07-01 09:18:17.633', NULL, E'activo', NULL, E'NULL', 5, E'INGEFE', E'ingreso', E'INGRESO EFECTIVO', NULL);

INSERT INTO tes.ttipo_solicitud ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_tipo_solicitud", "codigo", "nombre", "codigo_proceso_llave_wf", "codigo_plantilla_comprobante")
VALUES (175, NULL, E'2016-07-01 09:18:38.045', NULL, E'activo', NULL, E'NULL', 6, E'SALEFE', E'salida', E'SALIDA EFECTIVO', NULL);

INSERT INTO tes.ttipo_solicitud ("id_usuario_reg", "id_usuario_mod", "fecha_reg", "fecha_mod", "estado_reg", "id_usuario_ai", "usuario_ai", "id_tipo_solicitud", "codigo", "nombre", "codigo_proceso_llave_wf", "codigo_plantilla_comprobante")
VALUES (175, NULL, E'2016-07-01 09:18:59.089', NULL, E'activo', NULL, E'NULL', 7, E'APECAJ', E'apertura', E'APERTURA CAJA', NULL);
