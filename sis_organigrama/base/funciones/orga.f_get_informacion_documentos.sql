CREATE OR REPLACE FUNCTION orga.f_get_informacion_documentos (
  p_id_funcionario integer
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema Organigrama
 FUNCION: 		orga.f_get_informacion_documentos
 DESCRIPCION:   Funcion que recupera la informacion de documentos de un funcionario.
 AUTOR: 		(franklin.espinoza)
 FECHA:	        16-10-2018 15:15:26
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

    v_profesion 			record;
BEGIN

    v_nombre_funcion = 'orga.f_get_informacion_documentos';

    for v_profesion in select coalesce(tfva.valor,'Z No tiene Registro de Documento') as profesion, tip.codigo
    					from orga.tfuncionario tf
                        left join param.tarchivo tar on tar.id_tabla = tf.id_funcionario
                        LEFT join param.ttipo_archivo tip on tip.id_tipo_archivo = tar.id_tipo_archivo and tip.codigo in ('TIT_PROF', 'DIAC', 'TIT_BACHILLER', 'CERT_EGRESO')
                        left join param.tfield_tipo_archivo tfta on case
                                                                     when tip.codigo = 'TIT_PROF' then tfta.id_tipo_archivo = 13
                                                                     when tip.codigo = 'DIAC' then tfta.id_tipo_archivo = 45
                                                                     when tip.codigo = 'CERT_EGRESO' then tfta.id_tipo_archivo = 37
                                                                     when tip.codigo = 'TIT_BACHILLER' then tfta.id_tipo_archivo = 12
                                                                     end and case
                                                                     when tfta.id_tipo_archivo = 13 then tfta.nombre = 'carrera'
                                                                     when tfta.id_tipo_archivo = 45 then tfta.nombre = 'carrera'
                                                                     when tfta.id_tipo_archivo = 37 then tfta.nombre = 'carrera'
                                                                     when tfta.id_tipo_archivo = 12  then tfta.nombre = 'entidad_emisora'
                                                                     end


                        left join param.tfield_valor_archivo tfva on tfva.id_field_tipo_archivo = tfta.id_field_tipo_archivo and tfva.id_archivo = tar.id_archivo
                        where tf.id_funcionario = p_id_funcionario
                        order by tip.orden_valoracion asc, profesion asc
                        loop

    	if v_profesion.codigo = 'TIT_PROF' and v_profesion.profesion != 'No tiene Registro de Documento' then
        	return v_profesion.profesion;
        elsif v_profesion.codigo = 'DIAC' and v_profesion.profesion != 'No tiene Registro de Documento' then
        	return v_profesion.profesion;
        elsif v_profesion.codigo = 'CERT_EGRESO' then
        	return v_profesion.profesion;
        elsif v_profesion.codigo = 'TIT_BACHILLER' then
        	return v_profesion.profesion;
        else
        	return 'No tiene Registro de Documento';
    	end if;
    end loop;

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