CREATE OR REPLACE FUNCTION orga.f_get_arbol_padre_uo (
  p_id_uo integer
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema Organigrama
 FUNCION: 		orga.f_get_arbol_padre_uo
 DESCRIPCION:   Funcion que recupera los padres de un Nodo.
 AUTOR: 		(franklin.espinoza)
 FECHA:	        17-06-2019 15:15:26
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
    v_arbol_padre			varchar = '';
BEGIN

    v_nombre_funcion = 'orga.f_get_arbol_padre_uo';

    for v_record_ids in select teo.id_uo_padre, teo.id_uo_hijo from orga.testructura_uo teo where teo.id_uo_hijo = p_id_uo loop

        if p_id_uo = 9419 then
        	return p_id_uo;
        end if;
    	v_cadena_ids = v_cadena_ids||orga.f_get_arbol_padre_uo(v_record_ids.id_uo_padre)||','||v_record_ids.id_uo_hijo;

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