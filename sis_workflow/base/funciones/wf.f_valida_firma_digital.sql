CREATE OR REPLACE FUNCTION wf.f_valida_firma_digital (
  p_id_estado_wf integer,
  p_momento varchar = NULL::character varying,
  p_id_tipo_estado integer = NULL::integer,
  p_id_usuario integer = 1
)
RETURNS text AS
$body$
DECLARE
  v_sin_firma			record;
  v_res_array			text[];
  v_nombre_funcion		varchar;
  v_resp				varchar;
BEGIN
	v_nombre_funcion = 'wf.f_valida_firma_digital';
    
    for v_sin_firma in   
                select
                        dw.firma_digital,
                        dw.url,
                        td.nombre,
                        tpe.nombre_estado,
                        tdoe.momento
                  from wf.testado_wf es
                  inner join wf.ttipo_estado tpe on tpe.id_tipo_estado = es.id_tipo_estado
                  inner join wf.ttipo_documento_estado tdoe on tdoe.id_tipo_estado = tpe.id_tipo_estado and tdoe.estado_reg = 'activo'
                  inner join wf.ttipo_documento td on td.id_tipo_documento = tdoe.id_tipo_documento
                  inner join wf.tdocumento_wf dw on dw.id_proceso_wf = es.id_proceso_wf
                    and dw.id_tipo_documento =  tdoe.id_tipo_documento
                  where es.id_estado_wf = p_id_estado_wf
                  and tdoe.momento = 'firma_digital'
            
            loop 
              if (v_sin_firma.firma_digital is  null ) then
                  v_res_array = v_res_array || v_sin_firma.nombre::text;
              end if;
            end loop;
    
		return array_to_string(v_res_array,',');
        
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
PARALLEL UNSAFE
COST 100;
