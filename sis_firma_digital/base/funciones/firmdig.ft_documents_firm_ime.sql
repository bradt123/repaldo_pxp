CREATE OR REPLACE FUNCTION firmdig.ft_documents_firm_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		
 FUNCION: 		firmdig.ft_documents_firm_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'kaf.tdeposito'
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

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_documento_dig		integer;
    v_dwf					record;
			    
BEGIN

    v_nombre_funcion = 'firmdig.ft_documents_firm_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'FIRM_INS_IME'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		
 	#FECHA:		
	***********************************/

	if(p_transaccion='FIRM_INS_IME')then
					
        begin
        
        --Sentencia de la insercion
          insert into firmdig.tdocumento_firm_dig(
          id_documento_wf,
          codigo,
          pdf_base64,
          estado_reg,
          id_usuario_ai,
          id_usuario_reg,
          fecha_reg,
          usuario_ai,
          id_usuario_mod,
          fecha_mod
          ) values(
          v_parametros.id_documento_wf,
          v_parametros.codigo,
          v_parametros.pdf,
          'activo',
          v_parametros._id_usuario_ai,
          p_id_usuario,
          now(),
          v_parametros._nombre_usuario_ai,
          null,
          null
          )RETURNING id_documento_dig into v_id_documento_dig;
          
          update orga.tcertificado_planilla set
          impreso = 'si'
          where id_certificado_planilla = v_parametros.id_certificado_planilla;
            
          --Definicion de la respuesta
          v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Documento almacenado(a) con exito (id_documento_dig'||v_id_documento_dig||')'); 
          v_resp = pxp.f_agrega_clave(v_resp,'id_documento_dig',v_id_documento_dig::varchar);
          --Devuelve la respuesta
          return v_resp;                        
		end;

    /*********************************
 	#TRANSACCION:  'FIRMDIG_VERIFY_IME'
 	#DESCRIPCION:	
 	#AUTOR:		
 	#FECHA:		
	***********************************/

	elsif(p_transaccion='FIRMDIG_VERIFY_IME')then

		begin
				select fid.id_documento_wf, fid.estado_reg
                    into 
	                    v_dwf
                from firmdig.tdocumento_firm_dig fid
                where fid.id_documento_wf = v_parametros.id_doc;
                
            if v_dwf.estado_reg = 'activo' then 
            
	         update firmdig.tdocumento_firm_dig set 
             estado_reg = 'inactivo'
             where id_documento_wf = v_dwf.id_documento_wf;
             
             update orga.tcertificado_planilla set 
             impreso = 'no'
             where id_certificado_planilla = v_parametros.id_cert_plan;
             
             /* delete from firmdig.tdocumento_firm_dig 
              where id_documento_wf = v_id_dwf.id_documento_wf;*/
              v_resp = pxp.f_agrega_clave(v_resp,'otro','no');              
              v_resp = pxp.f_agrega_clave(v_resp,'id_doc_pdf',v_dwf.id_documento_wf::varchar);              
			else 
            
	         update firmdig.tdocumento_firm_dig set 
             estado_reg = 'activo'
             where id_documento_wf = v_dwf.id_documento_wf;
             
             update orga.tcertificado_planilla set 
             impreso = 'si'
             where id_certificado_planilla = v_parametros.id_cert_plan;
                          
              v_resp = pxp.f_agrega_clave(v_resp,'otro','si');
           	  v_resp = pxp.f_agrega_clave(v_resp,'id_doc_pdf','void'); 
			end if;                              
          
		--Devuelve la respuesta
		return v_resp;
		end;         

	/*********************************    
 	#TRANSACCION:  'FIRM_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:			
 	#FECHA:		
	***********************************/

	elsif(p_transaccion='FIRM_MOD')then

		begin
			--Sentencia de la modificacion
            /*
			update firmdig.tdocumento_firm_dig set
			where id_documento_dig=v_parametros.id_documento_dig;*/
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Documento modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_documento_dig',v_parametros.id_documento_dig::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'FIRM_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		
 	#FECHA:		
	***********************************/

	elsif(p_transaccion='FIRM_ELI')then

		begin
			--Sentencia de la eliminacion
            /*
			delete from firmdig.tdocumento_firm_dig
            where id_documento_dig=v_parametros.id_documento_dig;*/
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Documento eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_documento_dig',v_parametros.id_documento_dig::varchar);
              
            --Devuelve la respuesta
            return v_resp;

		end;
         
	else
     
    	raise exception 'Transaccion inexistente: %',p_transaccion;

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

ALTER FUNCTION firmdig.ft_documents_firm_ime (p_administrador integer, p_id_usuario integer, p_tabla varchar, p_transaccion varchar)
  OWNER TO postgres;