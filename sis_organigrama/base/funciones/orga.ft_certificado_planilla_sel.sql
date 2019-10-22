CREATE OR REPLACE FUNCTION orga.ft_certificado_planilla_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Organigrama
 FUNCION: 		orga.ft_certificado_planilla_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'orga.tcertificado_planilla'
 AUTOR: 		 (miguel.mamani)
 FECHA:	        24-07-2017 14:48:34
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:
 AUTOR:
 FECHA:
***************************************************************************/

DECLARE

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
    v_iniciales			varchar;
    v_fun_emetido		varchar;
    v_usuario			record;
    v_filtro			varchar;
    v_impreso			varchar;

BEGIN

	v_nombre_funcion = 'orga.ft_certificado_planilla_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'OR_PLANC_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		miguel.mamani
 	#FECHA:		24-07-2017 14:48:34
	***********************************/

	if(p_transaccion='OR_PLANC_SEL')then

    	begin

         SELECT		tf.id_funcionario,
 					fun.desc_funcionario1
                    INTO
                    v_usuario
                    FROM segu.tusuario tu
                    INNER JOIN orga.tfuncionario tf on tf.id_persona = tu.id_persona
                    INNER JOIN orga.vfuncionario fun on fun.id_funcionario = tf.id_funcionario
                    WHERE tu.id_usuario = p_id_usuario;
               IF  (p_administrador)  THEN
				v_filtro = ' 0=0 and ';
          elsif (v_parametros.tipo_interfaz ='CertificadoPlanilla')then
			v_filtro = ' 0=0 and ';
          elsif (v_parametros.tipo_interfaz = 'CertificadoEmitido') THEN
         v_filtro ='planc.estado in (''emitido'') and w.id_funcionario ='||v_usuario.id_funcionario||' and';
     END IF;
		--Sentencia de la consulta
			v_consulta:='select
							planc.id_certificado_planilla,
                            planc.tipo_certificado,
                            planc.fecha_solicitud,
                            planc.beneficiario,
                            planc.id_funcionario,
                            planc.estado_reg,
                            planc.importe_viatico,
                            planc.id_usuario_ai,
                            planc.fecha_reg,
                            planc.usuario_ai,
                            planc.id_usuario_reg,
                            planc.fecha_mod,
                            planc.id_usuario_mod,
                            usu1.cuenta as usr_reg,
                            usu2.cuenta as usr_mod,
                            fun.desc_funcionario1,
                            planc.nro_tramite,
                            planc.estado,
                            planc.id_proceso_wf,
                            planc.id_estado_wf,
                            planc.cargo_funcionario,
                            pe.ci,
                            planc.haber_basico,
                            pe.expedicion,
                            planc.impreso,        
                            planc.impreso as control,
                            planc.nro_factura as factura,
                            dw.url,
                            td.action,
                            dw.id_documento_wf,
                            dw.firma_digital,
                            td.nombre,
                            td.extension,
                            case when ( select h.estado_reg
                            from wf.tdocumento_historico_wf h
                            inner join wf.tdocumento_wf d on d.id_documento_wf=h.id_documento
                            where d.id_proceso_wf= planc.id_proceso_wf 
                            limit 1) = ''activo'' then 
                            ''si''
                            else 
                            ''no''
                            end as habilitado,
                            dw.chequeado
                            from orga.tcertificado_planilla planc
                            inner join segu.tusuario usu1 on usu1.id_usuario = planc.id_usuario_reg
                            inner join orga.tuo_funcionario fuo on fuo.id_uo_funcionario = planc.id_funcionario
            			    and fuo.tipo = ''oficial''
                            inner join orga.vfuncionario fun on fun.id_funcionario = fuo.id_funcionario
                            inner join segu.tpersona pe on pe .id_persona = fun.id_persona
                            inner join wf.testado_wf w on w.id_estado_wf = planc.id_estado_wf
                            left join segu.tusuario usu2 on usu2.id_usuario = planc.id_usuario_mod
                            inner join wf.tdocumento_wf dw on dw.id_proceso_wf = planc.id_proceso_wf and dw.id_estado_ini is not null 
	                        inner join wf.ttipo_documento td on td.id_tipo_documento = dw.id_tipo_documento and td.codigo = ''CERT''
                            where  '||v_filtro;

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
			raise notice 'cosulta...%',v_consulta;
			--Devuelve la respuesta
			return v_consulta;

		end;

	/*********************************
 	#TRANSACCION:  'OR_PLANC_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		miguel.mamani
 	#FECHA:		24-07-2017 14:48:34
	***********************************/

	elsif(p_transaccion='OR_PLANC_CONT')then

		begin
           SELECT		tf.id_funcionario,
 					fun.desc_funcionario1
                    INTO
                    v_usuario
                    FROM segu.tusuario tu
                    INNER JOIN orga.tfuncionario tf on tf.id_persona = tu.id_persona
                    INNER JOIN orga.vfuncionario fun on fun.id_funcionario = tf.id_funcionario
                    WHERE tu.id_usuario = p_id_usuario;
               IF  (p_administrador)  THEN
				v_filtro = ' 0=0 and ';
          elsif (v_parametros.tipo_interfaz ='CertificadoPlanilla')then
			v_filtro = ' 0=0 and ';
          elsif (v_parametros.tipo_interfaz = 'CertificadoEmitido') THEN
         v_filtro ='planc.estado in (''emitido'') and w.id_funcionario ='||v_usuario.id_funcionario||' and';
     END IF;
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_certificado_planilla)
                            from orga.tcertificado_planilla planc
                            inner join segu.tusuario usu1 on usu1.id_usuario = planc.id_usuario_reg
                            inner join orga.tuo_funcionario fuo on fuo.id_uo_funcionario = planc.id_funcionario
            			    and fuo.tipo = ''oficial''
                            inner join orga.vfuncionario fun on fun.id_funcionario = fuo.id_funcionario
                            inner join segu.tpersona pe on pe .id_persona = fun.id_persona
                            inner join wf.testado_wf w on w.id_estado_wf = planc.id_estado_wf
                            left join segu.tusuario usu2 on usu2.id_usuario = planc.id_usuario_mod
                            inner join wf.tdocumento_wf dw on dw.id_proceso_wf = planc.id_proceso_wf and dw.id_estado_ini is not null 
	                        inner join wf.ttipo_documento td on td.id_tipo_documento = dw.id_tipo_documento and td.codigo = ''CERT''
					    where '||v_filtro;

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;

        /*********************************
 	#TRANSACCION:  'OR_CERT_HTM'
 	#DESCRIPCION:	Reporte htnl
 	#AUTOR:		miguel.mamani
 	#FECHA:		24-07-2017 14:48:34
	***********************************/

	elsif(p_transaccion='OR_CERT_HTM')then

		begin

    	select impreso
            into
            v_impreso
            from  orga.tcertificado_planilla
            where id_proceso_wf = v_parametros.id_proceso_wf;
/*
    	if (v_impreso = 'si') then
        raise exception 'El Certificado ya fue impreso';
        else
      	update orga.tcertificado_planilla  set
		impreso = v_parametros.impreso
        where id_proceso_wf = v_parametros.id_proceso_wf;
       	end if; */

        select orga.f_iniciales_funcionarios(p.desc_funcionario1)
        into
        v_iniciales
        from segu.tusuario u
        inner join orga.vfuncionario_persona p on p.id_persona = u.id_persona
        where u.id_usuario = p_id_usuario;


        select f.desc_funcionario1
        into
        v_fun_emetido
        from wf.testado_wf w
        inner join wf.ttipo_estado e on e.id_tipo_estado = w.id_tipo_estado
        inner join orga.vfuncionario f on f.id_funcionario = w.id_funcionario
        where e.codigo = 'emitido' and w.id_proceso_wf = v_parametros.id_proceso_wf;


        v_consulta:='select  
                        initcap (fun.desc_funcionario1) as  nombre_funcionario,
                        cpla.cargo_funcionario as nombre_cargo,
                        (select min(car.fecha_asignacion)
                        from orga.vfuncionario_cargo car
                        where car.id_funcionario = fun.id_funcionario ) as fecha_contrato,
                        cpla.haber_basico,
                        per.ci,
                        per.expedicion,
                        CASE
                        WHEN per.genero::text = ANY (ARRAY[''varon''::character varying,''VARON''::character varying, ''Varon''::character varying]::text[]) THEN ''Sr''::text
                        WHEN per.genero::text = ANY (ARRAY[''mujer''::character varying,''MUJER''::character varying, ''Mujer''::character varying]::text[]) THEN ''Sra''::text
                        ELSE ''''::text
                        END::character varying AS genero,
                        cpla.fecha_solicitud,
                        ger.nombre_unidad,
                        initcap(mat.f_primer_letra_mayuscula( pxp.f_convertir_num_a_letra(cpla.haber_basico)))::varchar as haber_literal,
                        (select initcap( cart.desc_funcionario1)
                        from orga.vfuncionario_cargo cart
                        where cart.nombre_cargo = ''Jefe Recursos Humanos'') as jefa_recursos,
                        cpla.tipo_certificado,
                        cpla.importe_viatico,
                        initcap  (mat.f_primer_letra_mayuscula( pxp.f_convertir_num_a_letra(cpla.importe_viatico)))::varchar as literal_importe_viatico,
                        cpla.nro_tramite,
                        '''||COALESCE (v_iniciales,'NA')||'''::varchar as iniciales,
                        '''||COALESCE (v_fun_emetido,'NA')||'''::varchar as fun_imitido,
                        cpla.estado,
                        ca.codigo as nro_item,
        				cpla.id_proceso_wf,
        				dw.id_documento_wf
                        from orga.tcertificado_planilla cpla
                        inner join segu.tusuario usu1 on usu1.id_usuario = cpla.id_usuario_reg 
                        inner join orga.tuo_funcionario fuo on fuo.id_uo_funcionario = cpla.id_funcionario
                        inner join orga.vfuncionario fun on fun.id_funcionario = fuo.id_funcionario
                        inner join segu.tpersona per on per.id_persona = fun.id_persona
                        inner join orga.vfuncionario_cargo fca on fca.id_uo_funcionario = cpla.id_funcionario
                        inner join orga.tcargo ca on ca.id_cargo = fca.id_cargo 
                        inner join wf.testado_wf esw on esw.id_estado_wf = cpla.id_estado_wf 
                        inner join orga.tuo ger ON ger.id_uo = orga.f_get_uo_gerencia(fuo.id_uo, NULL::integer, NULL::date)
                        inner join wf.tdocumento_wf dw on dw.id_proceso_wf = cpla.id_proceso_wf and dw.id_estado_ini is not null 
                        inner join wf.ttipo_documento td on td.id_tipo_documento = dw.id_tipo_documento and td.codigo = ''CERT''                        
                        where cpla.id_proceso_wf ='||v_parametros.id_proceso_wf;
                        
			--Devuelve la respuesta
            raise notice '%',v_consulta;
			return v_consulta;

		end;

       /*********************************
 	#TRANSACCION:  'OR_CERT_REP'
 	#DESCRIPCION:	Reporte htnl
 	#AUTOR:		miguel.mamani
 	#FECHA:		24-07-2017 14:48:34
	***********************************/

	elsif(p_transaccion='OR_CERT_REP')then

		begin

        select orga.f_iniciales_funcionarios(p.desc_funcionario1)
        into
        v_iniciales
        from segu.tusuario u
        inner join orga.vfuncionario_persona p on p.id_persona = u.id_persona
        where u.id_usuario = p_id_usuario;

        select f.desc_funcionario1
        into
        v_fun_emetido
        from wf.testado_wf w
        inner join wf.ttipo_estado e on e.id_tipo_estado = w.id_tipo_estado
        inner join orga.vfuncionario f on f.id_funcionario = w.id_funcionario
        where e.codigo = 'emitido' and w.id_proceso_wf = v_parametros.id_proceso_wf;


        v_consulta:='select  
                        initcap (fun.desc_funcionario1) as  nombre_funcionario,
                        cpla.cargo_funcionario as nombre_cargo,
                        (select min(car.fecha_asignacion)
                        from orga.vfuncionario_cargo car
                        where car.id_funcionario = fun.id_funcionario) as fecha_contrato,
                        cpla.haber_basico,
                        per.ci,
                        per.expedicion,
                        CASE
                        WHEN per.genero::text = ANY (ARRAY[''varon''::character varying,''VARON''::character varying, ''Varon''::character varying]::text[]) THEN ''Sr''::text
                        WHEN per.genero::text = ANY (ARRAY[''mujer''::character varying,''MUJER''::character varying, ''Mujer''::character varying]::text[]) THEN ''Sra''::text
                        ELSE ''''::text
                        END::character varying AS genero,
                        cpla.fecha_solicitud,
                        ger.nombre_unidad,
                        initcap(mat.f_primer_letra_mayuscula( pxp.f_convertir_num_a_letra(cpla.haber_basico)))::varchar as haber_literal,
                        (select initcap( cart.desc_funcionario1)
                        from orga.vfuncionario_cargo cart
                        where cart.nombre_cargo = ''Jefe Recursos Humanos'') as jefa_recursos,
                        cpla.tipo_certificado,
                        cpla.importe_viatico,
                        initcap  (mat.f_primer_letra_mayuscula( pxp.f_convertir_num_a_letra(cpla.importe_viatico)))::varchar as literal_importe_viatico,
                        cpla.nro_tramite,
                        '''||COALESCE (v_iniciales,'NA')||'''::varchar as iniciales,
                        '''||COALESCE (v_fun_emetido,'NA')||'''::varchar as fun_imitido,
                        cpla.estado,
                        ca.codigo as nro_item,
        				cpla.id_proceso_wf,
        				dw.id_documento_wf
                        from orga.tcertificado_planilla cpla
                        inner join segu.tusuario usu1 on usu1.id_usuario = cpla.id_usuario_reg 
                        inner join orga.tuo_funcionario fuo on fuo.id_uo_funcionario = cpla.id_funcionario
                        inner join orga.vfuncionario fun on fun.id_funcionario = fuo.id_funcionario
                        inner join segu.tpersona per on per.id_persona = fun.id_persona
                        inner join orga.vfuncionario_cargo fca on fca.id_uo_funcionario = cpla.id_funcionario 
                        inner join orga.tcargo ca on ca.id_cargo = fca.id_cargo 
                        inner join wf.testado_wf esw on esw.id_estado_wf = cpla.id_estado_wf 
                        inner join orga.tuo ger ON ger.id_uo = orga.f_get_uo_gerencia(fuo.id_uo, NULL::integer, NULL::date)
                        inner join wf.tdocumento_wf dw on dw.id_proceso_wf = cpla.id_proceso_wf and dw.id_estado_ini is not null 
                        inner join wf.ttipo_documento td on td.id_tipo_documento = dw.id_tipo_documento and td.codigo = ''CERT''                        
                        where cpla.id_proceso_wf ='||v_parametros.id_proceso_wf;
			--Devuelve la respuesta
            raise notice '%',v_consulta;
			return v_consulta;

		end;
    /*********************************
 	#TRANSACCION:  'OR_CERT_SER'
 	#DESCRIPCION:	Servicio consulta datos funcionario
 	#AUTOR:		miguel.mamani
 	#FECHA:		24-07-2017 14:48:34
	***********************************/


    elsif(p_transaccion='OR_CERT_SER')then

		begin
			v_consulta:='  select planc.nro_tramite,
                            f.desc_funcionario1 as nombre_funcionario,
                            to_char(  planc.fecha_solicitud,''DD/MM/YYYY'')::text as fecha_solicitud,
                            planc.tipo_certificado,
                            planc.estado,
                            f.nombre_cargo,
                            --round(es.haber_basico + round(plani.f_evaluar_antiguedad(plani.f_get_fecha_primer_contrato_empleado(f.id_uo_funcionario, f.id_funcionario, f.fecha_asignacion), planc.fecha_solicitud::date, fon.antiguedad_anterior), 2)) as remuneracion
                           (select
                                      round(colval.valor,2)
                                      from plani.tcolumna_valor colval
                                      inner join plani.tfuncionario_planilla funpla on funpla.id_funcionario_planilla = colval.id_funcionario_planilla
                                      inner join plani.tplanilla pla on pla.id_planilla = funpla.id_planilla and pla.id_tipo_planilla = 1
                                      where  funpla.id_funcionario = planc.id_funcionario
                                      and colval.codigo_columna = ''COTIZABLE''
                                      and pla.fecha_planilla = 
                                                        (select p.fecha_planilla
                                                        from plani.tplanilla p
                                                        where 
                                                        p.id_tipo_planilla = 1
                                                        and p.id_periodo = (select p.id_periodo
                                                                              from param.tperiodo p
                                                                              where  planc.fecha_solicitud between p.fecha_ini and p.fecha_fin
                                                                              ) - 1)
                                                                               
                                          ) as  remuneracion                           
                            from orga.tcertificado_planilla planc
                            inner join orga.vfuncionario_cargo f on f.id_funcionario = planc.id_funcionario and (f.fecha_finalizacion is null or f.fecha_finalizacion >= now())
                            inner join orga.tcargo car on car.id_cargo = f.id_cargo and (car.fecha_fin is null or car.fecha_fin >= now()) and car.estado_reg = ''activo''
                            inner join orga.tescala_salarial es on es.id_escala_salarial =car.id_escala_salarial
                            inner join orga.tfuncionario fon on fon.id_funcionario = planc.id_funcionario
                            where planc.id_funcionario ='||v_parametros.id_funcionario||'order by planc.nro_tramite desc';


			--Devuelve la respuesta
            --raise exception 'llega ';
            raise notice 'CONSULTA %',v_consulta;
			return v_consulta;


		end;
        
    /*********************************
 	#TRANSACCION:  'OR_GET_URL_DOC'
 	#DESCRIPCION:	recupera el la url del documento
 	#AUTOR:	
 	#FECHA:	
	***********************************/
    elseif(p_transaccion='OR_GET_URL_DOC') then
    	begin
		v_consulta:='    
        select dw.url,
               dw.id_documento_wf,
               dw.extension,
               substr(dw.url, 52)::varchar as pdf,
               td.action,
               dw.firma_digital,
               dw.chequeado
        from wf.tdocumento_wf dw 
        inner join wf.ttipo_documento td on td.id_tipo_documento = dw.id_tipo_documento
        where dw.id_proceso_wf = '|| v_parametros.id_proceso_wf||'
        and dw.id_documento_wf = '||v_parametros.id_documento_wf;
        
         raise notice '%',v_consulta;
        --and dw.id_documento_wf = 632750';
		--Devuelve la respuesta
        return v_consulta;

	end;               

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

ALTER FUNCTION orga.ft_certificado_planilla_sel (p_administrador integer, p_id_usuario integer, p_tabla varchar, p_transaccion varchar)
  OWNER TO postgres;