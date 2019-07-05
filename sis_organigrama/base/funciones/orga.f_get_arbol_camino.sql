CREATE OR REPLACE FUNCTION orga.f_get_arbol_camino (
  p_id_uo integer,
  p_nivel integer,
  p_campo varchar
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
    v_cadena_ids			varchar = '';
    v_nivel					integer;
    v_campo					record;
BEGIN

    v_nombre_funcion = 'orga.f_get_arbol_camino';

    for v_record_ids in select teo.id_uo_padre, teo.id_uo_hijo
    					from orga.testructura_uo teo
                        where teo.id_uo_hijo = p_id_uo loop
    	select  tu.nombre_cargo, tno.numero_nivel
        into v_record
        from orga.tuo tu
		inner join orga.tnivel_organizacional tno on tno.id_nivel_organizacional = tu.id_nivel_organizacional
        where tu.id_uo = p_id_uo;

    	if p_nivel <= v_record.numero_nivel then
          if p_campo = 'nombre' then
              select tuo.nombre_cargo as campo, tno.numero_nivel as nivel
              into v_campo
              from orga.tuo tuo
              inner join orga.tnivel_organizacional tno on tno.id_nivel_organizacional = tuo.id_nivel_organizacional
              where tno.numero_nivel = p_nivel and tuo.id_uo = p_id_uo and tuo.estado_reg = 'activo';
          else
              select coalesce(tuo.codigo,'N/E') as campo, tno.numero_nivel as nivel
              into v_campo
              from orga.tuo tuo
              inner join orga.tnivel_organizacional tno on tno.id_nivel_organizacional = tuo.id_nivel_organizacional
              where tno.numero_nivel = p_nivel and tuo.id_uo = p_id_uo and tuo.estado_reg = 'activo';
          end if;

          if v_campo.nivel = p_nivel then
              return v_campo.campo;
          end if;
          v_cadena_ids = orga.f_get_arbol_camino(v_record_ids.id_uo_padre, p_nivel, p_campo);
    	else
        	return '';
        end if;

    end loop;

    RETURN v_cadena_ids;

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