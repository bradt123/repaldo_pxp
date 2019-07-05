CREATE OR REPLACE FUNCTION orga.f_get_arbol_uo (
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
    v_cadena_ids			varchar = '';
BEGIN

    v_nombre_funcion = 'orga.f_get_arbol_uo';

    for v_record_ids in select * from orga.testructura_uo tuo where tuo.id_uo_padre = p_id_uo order by tuo.id_uo_hijo asc  loop
        if v_record_ids.id_uo_hijo is null then
	       	return '';
        end if;
    	v_cadena_ids = v_cadena_ids||v_record_ids.id_uo_hijo||','||orga.f_get_arbol_uo(v_record_ids.id_uo_hijo);

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