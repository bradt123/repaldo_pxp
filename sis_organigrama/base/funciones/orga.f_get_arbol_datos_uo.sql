CREATE OR REPLACE FUNCTION orga.f_get_arbol_datos_uo (
  p_id_uo integer,
  out ps_codigo varchar,
  out ps_nombre varchar
)
RETURNS record AS
$body$
/**************************************************************************
 SISTEMA:		Sistema Organigrama
 FUNCION: 		orga.f_get_arbol_datos_uo
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

	v_resp		            varchar = '';
	v_nombre_funcion        text;
	--p_nombre 				varchar = '';
    --p_codigo				varchar = '';

BEGIN

    v_nombre_funcion = 'orga.f_get_arbol_datos_uo';
    ps_codigo = '';
    ps_nombre = '';
    if p_id_uo is null then
    	return;
    end if;
    select coalesce(tuo.codigo,'N/E'), tuo.nombre_cargo
    into ps_codigo, ps_nombre
    from orga.tuo tuo
    where tuo.id_uo = p_id_uo and tuo.estado_reg = 'activo';
	--raise exception 'a: %, b :%', ps_codigo, ps_nombre;
    RETURN;

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