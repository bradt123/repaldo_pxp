CREATE OR REPLACE FUNCTION orga.f_get_depto_arbol_uo (
  p_id_uo integer
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema Presupuestos
 FUNCION: 		orga.f_get_arbol_uo
 DESCRIPCION:   Funcion que recupera los hijos o nietos de un arbol.
 AUTOR: 		(franklin.espinoza)
 FECHA:	        28-07-2017 15:15:26
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:
 AUTOR:
 FECHA:
***************************************************************************/

DECLARE

	v_resp		            varchar='';
	v_nombre_funcion        text;
	v_record 				record;
    v_record_ids			record;
    v_id_uo_funcionario		integer;

    v_centro_costo			varchar;
    v_programa_codigo		varchar;
    v_departamento 			varchar;
BEGIN

    v_nombre_funcion = 'orga.f_get_depto_arbol_uo';
        select euo.id_uo_padre, tu.nombre_unidad as unidad_p, tu.nodo_base as nodo_p, tu.gerencia as gerencia_p,  tu.presupuesta as presupuesta_p, tu.prioridad as prioridad_p,
        	   tu.cargo_individual as cargo_individual_p, tu.correspondencia as correspondencia_p, nop.numero_nivel as nivel_p,

               euo.id_uo_hijo, uo.nombre_unidad as unidad_h, tu.nodo_base as nodo_h, uo.gerencia as gerencia_h, uo.presupuesta as presupuesta_h, uo.prioridad as prioridad_h,
         	   uo.cargo_individual as cargo_individual_h, uo.correspondencia as correspondencia_h, ni.numero_nivel as nivel_h
               into v_record
        from orga.tuo uo
        inner join orga.testructura_uo euo on euo.id_uo_hijo = uo.id_uo
        inner join orga.tuo tu on tu.id_uo = euo.id_uo_padre
        inner join orga.tnivel_organizacional ni on ni.id_nivel_organizacional = uo.id_nivel_organizacional
        inner join orga.tnivel_organizacional nop on nop.id_nivel_organizacional = tu.id_nivel_organizacional
        where euo.id_uo_hijo = p_id_uo;




        /*if (v_record.presupuesta_p = 'si' or v_record.cargo_individual_p = 'si' or  v_record.gerencia_p = 'si') and v_record.nodo_p != 'si' and p_id_uo != 9979 then
        	return v_record.unidad_p;
        else*/
            /*if v_record.nivel_p >= 5  and v_record.cargo_individual_p = 'no' then

                select tu.nombre_unidad
                into v_record
                from orga.tuo tu
                where tu.id_uo = orga.f_get_uo_departamento(p_id_uo, NULL::integer, NULL::date);

            	return v_record.nombre_unidad;
            else*/
            	/*if p_id_uo = 9774 then
                	return 'Responsable BoA Regionales';
            	els*/if p_id_uo = any (string_to_array(btrim(orga.f_get_arbol_uo(9427),','),',')::integer[]) or p_id_uo = 9427 then
                	--raise exception  'Regional COBIJA';
                	return 'Regional COBIJA';
                elsif p_id_uo = any (string_to_array(btrim(orga.f_get_arbol_uo(9774),','),',')::integer[]) or p_id_uo = 9774 then
                	return 'Responsable BoA Regionales >>> Regional UYUNI';
                elsif p_id_uo = any (string_to_array(btrim(orga.f_get_arbol_uo(9780),','),',')::integer[]) or p_id_uo = 9780 then
                	return 'Regional ORURO';
                elsif p_id_uo = any (string_to_array(btrim(orga.f_get_arbol_uo(9782),','),',')::integer[]) or p_id_uo = 9782 then
                	return 'Regional POTOSI';
                elsif p_id_uo = any (string_to_array(btrim(orga.f_get_arbol_uo(9786),','),',')::integer[]) or p_id_uo = 9786 then
                	return 'Regional YACUIBA';
                elsif p_id_uo = any (string_to_array(btrim(orga.f_get_arbol_uo(9788),','),',')::integer[]) or p_id_uo = 9788 then
                	return 'Regional CHIMORE';
                elsif p_id_uo = any (string_to_array(btrim(orga.f_get_arbol_uo(9924),','),',')::integer[]) or p_id_uo = 9924 then
                	return 'Regional MONTEAGUDO';
                elsif p_id_uo = any (string_to_array(btrim(orga.f_get_arbol_uo(9993),','),',')::integer[]) or p_id_uo = 9993 then
                	return 'Regional SAN IGNACIO';
                else
                	return v_record.unidad_h;
                end if;
            --end if;
        --end if;


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