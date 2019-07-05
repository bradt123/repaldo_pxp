CREATE OR REPLACE FUNCTION orga.f_get_documentos_func (
  p_id_funcionario integer
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Organigrama
 FUNCION: 		orga.f_get_documentos_func
 DESCRIPCION:   Funcion que retorna el json de los documentos de un funcionario
 AUTOR: 		Franklin Espinonza Alvarez
 FECHA:	        02-04-2018 15:45:34
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:	
 AUTOR:			
 FECHA:		
***************************************************************************/

DECLARE

	v_parametros           	record;
	v_archivo				record;
	v_resp		            varchar;
	v_nombre_funcion        text;
    v_valores				record;
	v_documentos			varchar = '[';
			    
BEGIN

    v_nombre_funcion = 'orga.f_get_documentos_func';
	
    for v_archivo in select distinct on (ta.id_tipo_archivo) ta.id_tipo_archivo,
    				 ta.id_archivo,
                     tta.codigo
                     from param.tarchivo ta 
                     inner join param.ttipo_archivo tta on tta.id_tipo_archivo = ta.id_tipo_archivo
                     where ta.id_tabla = p_id_funcionario and tta.codigo in ('TIT_BACHILLER', 'DIAC','TIT_PROF','CERT_EGRESO', 'TIT_MAES', 'TIT_DOC','LIB_MIL', 'DIAC', 'SUM', 'resolucion_administrativa') AND ta.id_archivo_fk is null loop
    	v_documentos = v_documentos||'{"'||v_archivo.codigo||'":{';
    	for v_valores in  select 
                          tfta.nombre as clave,
                          tfva.valor
                          from param.tfield_tipo_archivo tfta
                          inner join param.tfield_valor_archivo tfva on tfva.id_field_tipo_archivo = tfta.id_field_tipo_archivo and tfva.id_archivo = v_archivo.id_archivo
                          where tfta.id_tipo_archivo = v_archivo.id_tipo_archivo loop
            
            if(v_valores.valor != '') then
            	v_documentos = v_documentos||'"'||v_valores.clave||'":"'||regexp_replace(v_valores.valor, '"', '', 'g')||'",';
            end if;              
        	
        end loop;
        v_documentos = v_documentos||'}},';
    	                      
    end loop;
    
    v_documentos = v_documentos||']';
    v_documentos = regexp_replace(v_documentos, ',]', ']', 'g');
    v_documentos = regexp_replace(v_documentos, ',}', '}', 'g');
    --to_json(v_documentos);
    return v_documentos;
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