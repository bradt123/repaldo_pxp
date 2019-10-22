CREATE OR REPLACE FUNCTION firmdig.ft_documents_firm_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:   
 FUNCION:    
 DESCRIPCION:   
 AUTOR:      
 FECHA:         
 COMENTARIOS: 
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
 DESCRIPCION: 
 AUTOR:     
 FECHA:   
***************************************************************************/

DECLARE

  v_consulta        	varchar;
  v_parametros      	record;
  v_nombre_funcion    	text;
  v_resp        		varchar;
          
BEGIN

  v_nombre_funcion = 'firmdig.ft_documents_firm_sel';
    v_parametros = pxp.f_get_record(p_tabla);

  /*********************************    
  #TRANSACCION:  
  #DESCRIPCION: 
  #AUTOR:   
  #FECHA:   
  ***********************************/

  if(p_transaccion='FIRMDIG_DFIRDIG_SEL')then
            
      begin
      
           
        --Sentencia de la consulta
      v_consulta:='with documento_modificar as (
                    select td.id_tipo_documento, ''si''::varchar as modificar
                    from wf.ttipo_documento td
                    inner join wf.ttipo_documento_estado tde on tde.id_tipo_documento = td.id_tipo_documento
                    where tde.id_tipo_estado = 31 --etado de documento de acuerdo al id_proceso_wf
                    and
                    tde.estado_reg = ''activo'' and tde.momento = ''modificar''), documento_insertar as
                    (
                    select td.id_tipo_documento,
                    ''si''::varchar as insertar
                    from wf.ttipo_documento td
                    inner join wf.ttipo_documento_estado tde on tde.id_tipo_documento = td.id_tipo_documento
                    where tde.id_tipo_estado = 31 --etado de documento de acuerdo al id_proceso_wf
                    and
                    tde.estado_reg = ''activo'' and
                    tde.momento = ''insertar''), documento_eliminar as 
                    (
                    select td.id_tipo_documento,
                    ''si''::varchar as eliminar
                    from wf.ttipo_documento td
                    inner join wf.ttipo_documento_estado tde on tde.id_tipo_documento = td.id_tipo_documento
                    where tde.id_tipo_estado = 31 --etado de documento de acuerdo al id_proceso_wf
                    and tde.estado_reg = ''activo'' and tde.momento = ''eliminar'')

                    select
                    dwf.id_documento_wf, 
                    dwf.url,
                    dwf.nombre_doc,
                    dwf.extension,
                    dwf.chequeado as visor,
                    pw.nro_tramite,
                    pw.codigo_proceso,
                    dwf.id_proceso_wf,
                    td.id_tipo_documento,
                    pw.id_tipo_proceso,
                    td.nombre,
                    dwf.chequeado,
                    td.action
                    from wf.tdocumento_wf dwf
                    inner join wf.tproceso_wf pw on pw.id_proceso_wf = dwf.id_proceso_wf
                    inner join wf.ttipo_documento td on td.id_tipo_documento = dwf.id_tipo_documento
                    inner join wf.ttipo_proceso tp on tp.id_tipo_proceso = pw.id_tipo_proceso
                    inner join wf.testado_wf ewf on ewf.id_proceso_wf = dwf.id_proceso_wf and ewf.estado_reg = ''activo''
                    inner join wf.ttipo_estado tewf on tewf.id_tipo_estado = ewf.id_tipo_estado
                    where pw.nro_tramite = ''PD-000162-2019'' and
                    tewf.codigo not in (''anulada'', ''anulado'', ''cancelado'')
                    AND
                    (''proceso'' = ANY (td.categoria_documento) or
                    td.categoria_documento is NULL or
                    td.categoria_documento = ''{}'')
                    --and dwf.url is not null
                    and ';
                    
      --Definicion de la respuesta
      v_consulta:=v_consulta||v_parametros.filtro;
	  v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
            
      raise notice '>>>>>>>>  % <<<<<<<<<<<<<<',  v_consulta;
      --Devuelve la respuesta
      return v_consulta;
            
    end;

    /*********************************
 	#TRANSACCION:  'FIRMDIG_GETURL_SEL'
 	#DESCRIPCION:	
 	#AUTOR:		
 	#FECHA:		
	***********************************/

	elsif(p_transaccion='FIRMDIG_GETURL_SEL')then

		begin

        v_consulta:='select
                      fid.pdf_base64,
                      fid.id_documento_wf
				    from firmdig.tdocumento_firm_dig fid
                    where fid.estado_reg = ''activo''
                    and fid.codigo = '''||v_parametros.codigo||'''';
          
		--Devuelve la respuesta
        raise notice '%',v_consulta;
		return v_consulta;

	end;
        
    
/*
  /*********************************    
  #TRANSACCION:  'FIRMDIG_DFIRDIG_SEL'
  #DESCRIPCION: Conteo de registros
  #AUTOR:   
  #FECHA:   
  ***********************************/

  elsif(p_transaccion='FIRMDIG_DFIRDIG_CONT')then

    begin            
      --Sentencia de la consulta de conteo de registros
      v_consulta:='with documento_modificar as (
                    select td.id_tipo_documento, ''si''::varchar as modificar
                    from wf.ttipo_documento td
                    inner join wf.ttipo_documento_estado tde on tde.id_tipo_documento = td.id_tipo_documento
                    where tde.id_tipo_estado = 31 --etado de documento de acuerdo al id_proceso_wf
                    and
                    tde.estado_reg = ''activo'' and tde.momento = ''modificar''), documento_insertar as
                    (
                    select td.id_tipo_documento,
                    ''si''::varchar as insertar
                    from wf.ttipo_documento td
                    inner join wf.ttipo_documento_estado tde on tde.id_tipo_documento = td.id_tipo_documento
                    where tde.id_tipo_estado = 31 --etado de documento de acuerdo al id_proceso_wf
                    and
                    tde.estado_reg = ''activo'' and
                    tde.momento = ''insertar''), documento_eliminar as 
                    (
                    select td.id_tipo_documento,
                    ''si''::varchar as eliminar
                    from wf.ttipo_documento td
                    inner join wf.ttipo_documento_estado tde on tde.id_tipo_documento = td.id_tipo_documento
                    where tde.id_tipo_estado = 31 --etado de documento de acuerdo al id_proceso_wf
                    and tde.estado_reg = ''activo'' and tde.momento = ''eliminar'')

                    select 
                    count(dwf.id_documento_wf)
                    from wf.tdocumento_wf dwf
                    inner join wf.tproceso_wf pw on pw.id_proceso_wf = dwf.id_proceso_wf
                    inner join wf.ttipo_documento td on td.id_tipo_documento = dwf.id_tipo_documento
                    inner join wf.ttipo_proceso tp on tp.id_tipo_proceso = pw.id_tipo_proceso
                    inner join wf.testado_wf ewf on ewf.id_proceso_wf = dwf.id_proceso_wf and ewf.estado_reg = ''activo''
                    inner join wf.ttipo_estado tewf on tewf.id_tipo_estado = ewf.id_tipo_estado
                    where pw.nro_tramite = ''PD-000164-2019'' and
                    tewf.codigo not in (''anulada'', ''anulado'', ''cancelado'')
                    AND
                    (''proceso'' = ANY (td.categoria_documento) or
                    td.categoria_documento is NULL or
                    td.categoria_documento = ''{}'')
                    and ';
      
      --Definicion de la respuesta        
      v_consulta:=v_consulta||v_parametros.filtro;
      --Devuelve la respuesta
      return v_consulta;

    end;*/
    
/*******************************    
 #TRANSACCION:  SEG_FUNCIO_SEL
 #DESCRIPCION:	Listado de los subsistemas registradas del sistema
 #AUTOR:		KPLIAN (rac)	
 #FECHA:		
***********************************/
     elsif(par_transaccion='SEG_RESPSUB_SEL')then

          --consulta:=';
          BEGIN

               v_consulta:='SELECT 
                              subsis.id_subsistema,
                              subsis.codigo,
                              subsis.prefijo,
                              subsis.nombre,
                              subsis.fecha_reg,
                              subsis.estado_reg,
                              subsis.nombre_carpeta
                        FROM segu.tsubsistema subsis
                        WHERE subsis.estado_reg=''activo'' and ';
               v_consulta:=v_consulta||v_parametros.filtro;
               v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' OFFSET ' || v_parametros.puntero;

               --raise exception '%',v_consulta;

               return v_consulta;
              

         END;  
          
  else
               
    raise exception 'Transaccion inexistente';
                   
  end if;
          
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

ALTER FUNCTION firmdig.ft_documents_firm_sel (p_administrador integer, p_id_usuario integer, p_tabla varchar, p_transaccion varchar)
  OWNER TO postgres;