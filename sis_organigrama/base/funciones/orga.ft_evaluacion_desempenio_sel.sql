CREATE OR REPLACE FUNCTION orga.ft_evaluacion_desempenio_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema Memos
 FUNCION: 		orga.ft_evaluacion_desempenio_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'orga.tevaluacion_desempenio'
 AUTOR: 		 (miguel.mamani)
 FECHA:	        24-02-2018 20:33:35
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				24-02-2018 20:33:35								Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'orga.tevaluacion_desempenio'
 #
 ***************************************************************************/

DECLARE

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
    v_iniciales			varchar;
    v_fun_emetido		varchar;
    v_where			varchar;
	v_from			varchar;
    v_record        record;
    v_bolea			boolean;

BEGIN

	v_nombre_funcion = 'orga.ft_evaluacion_desempenio_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'MEM_EVD_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		miguel.mamani
 	#FECHA:		24-02-2018 20:33:35
	***********************************/

	if(p_transaccion='MEM_EVD_SEL')then

    	begin
    		--Sentencia de la consulta
			v_consulta:='select	  ger.nombre_unidad,
                                  ger.id_uo,
                                  evd.id_evaluacion_desempenio,
                                  evd.nro_tramite,
                                  evd.id_proceso_wf,
                                  evd.id_funcionario,
                                  evd.codigo,
                                  evd.estado,
                                  evd.nota,
                                  evd.id_uo_funcionario,
                                  evd.id_estado_wf,
                                  evd.descripcion,
                                  evd.estado_reg,
                                  evd.id_usuario_ai,
                                  evd.id_usuario_reg,
                                  evd.fecha_reg,
                                  evd.usuario_ai,
                                  evd.fecha_mod,
                                  evd.id_usuario_mod,
                                  usu1.cuenta as usr_reg,
                                  usu2.cuenta as usr_mod,
                                  f.desc_funcionario1 as nombre_funcionario,
                                  upper (evd.cargo_evaluado)::varchar as nombre_cargo_evaluado,
                                  upper (evd.cargo_actual_memo)::varchar as nombre_cargo_actual_memo,
                                  evd.gestion,
                                  regexp_replace(regexp_replace(evd.recomendacion, E''<.*?>'', '''', ''g'' ), E''&nbsp;'', '''', ''g'')::varchar as recomendacion,
                                  evd.cite,
                                  evd.revisado,
                                  evd.estado_modificado,
                                  f.email_empresa                                  
                                  from orga.tevaluacion_desempenio evd
                                  inner join orga.tuo_funcionario fun on fun.id_uo_funcionario=evd.id_uo_funcionario
                                  inner join orga.tcargo ca on ca.id_temporal_cargo=evd.id_cargo_evaluado
                                  inner join orga.tuo ger on ger.id_uo=orga.f_get_uo_gerencia(ca.id_uo,null::integer,null::date)
                                  inner join segu.tusuario usu1 on usu1.id_usuario=evd.id_usuario_reg
                                  left join segu.tusuario usu2 on usu2.id_usuario=evd.id_usuario_mod
                                  inner join orga.vfuncionario_cargo f  on f.id_funcionario=fun.id_funcionario and ca.id_cargo=f.id_cargo
                                  where  ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;

		end;

	/*********************************
 	#TRANSACCION:  'MEM_EVD_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		miguel.mamani
 	#FECHA:		24-02-2018 20:33:35
	***********************************/

	elsif(p_transaccion='MEM_EVD_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_evaluacion_desempenio)
                      from orga.tevaluacion_desempenio evd
                                  inner join orga.tuo_funcionario fun on fun.id_uo_funcionario=evd.id_uo_funcionario
                                  inner join orga.tcargo ca on ca.id_temporal_cargo=evd.id_cargo_evaluado
                                  inner join orga.tuo ger on ger.id_uo=orga.f_get_uo_gerencia(ca.id_uo,null::integer,null::date)
                                  inner join segu.tusuario usu1 on usu1.id_usuario=evd.id_usuario_reg
                                  left join segu.tusuario usu2 on usu2.id_usuario=evd.id_usuario_mod
                                  inner join orga.vfuncionario_cargo f  on f.id_funcionario=fun.id_funcionario and ca.id_cargo=f.id_cargo
					    where ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
            

			--Devuelve la respuesta
			return v_consulta;

		end;
    	/*********************************
 	#TRANSACCION:  'MEM_EVD_REPO'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		miguel.mamani
 	#FECHA:		24-02-2018 20:33:35
	***********************************/

	elsif(p_transaccion='MEM_EVD_REPO')then

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



         if exists ( select 1
        from orga.tevaluacion_desempenio
        where id_proceso_wf = v_parametros.id_proceso_wf) then
           	v_from =  'from orga.tevaluacion_desempenio de';
          else

             v_from = 'from orga.tevaluacion_desempenio_historico de';
          end if;

			v_consulta:='select initcap(f.desc_funcionario1) as nombre_funcioario,
                                upper (de.cargo_actual_memo) as cargo_evaluado,
                                CASE
                              WHEN pe.genero::text = ANY (ARRAY[''varon''::character varying,''VARON''::character varying, ''Varon''::character varying]::text[]) THEN ''M''::text
                              WHEN pe.genero::text = ANY (ARRAY[''mujer''::character varying,''MUJER''::character varying, ''Mujer''::character varying]::text[]) THEN ''F''::text
                              ELSE ''''::text
                              END::character varying AS genero,
                                de.nro_tramite,
                                de.gestion,
                                de.nota,
                                de.descripcion,
                                de.fecha_solicitud,
                                '''||COALESCE (v_iniciales,'NA')||'''::varchar as iniciales,
                               '''||COALESCE (v_fun_emetido,'NA')||'''::varchar as fun_imitido,
                               COALESCE (orga.final_palabra(de.recomendacion), '''') as recomendacion,
                               de.cite,
                               de.correo,
                            	de.fecha_correo,
                            	initcap(u.desc_persona)::varchar as emisor,
                                de.plantilla,
                                de.ip,
                                de.fecha_receptor
                                '||v_from||'
                                inner join orga.vfuncionario_persona f on f.id_funcionario = de.id_funcionario
                                inner join segu.vpersona2 pe on pe.id_persona = f.id_persona
                                left join segu.vusuario u on u.id_usuario = de.id_usuario_mod
                                where de.id_proceso_wf = '||v_parametros.id_proceso_wf;
			--Devuelve la respuesta

            raise notice 'consulta %',v_consulta;
          	update orga.tevaluacion_desempenio set
            revisado = 'si'
            where id_proceso_wf = v_parametros.id_proceso_wf;

			return v_consulta;

		end;

        /*********************************
 	#TRANSACCION:  'MEM_EVD_REPG'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		miguel.mamani
 	#FECHA:		24-02-2018 20:33:35
	***********************************/

    elsif(p_transaccion='MEM_EVD_REPG')then

		begin
		--raise exception 'id %',v_parametros.id_uo;
        CREATE TEMPORARY TABLE temp_evaluacion (
                                      id_uo integer,
                                      nro_tramite varchar,
                                      nota integer,
                                      descripcion varchar,
                                      desc_funcionario1 text,
                                      nombre_cargo varchar,
                                      gestion integer,
                                      recomendacion  varchar,
                                      cite varchar,
                                      genero varchar
                                      )ON COMMIT DROP;


        insert  into temp_evaluacion (id_uo,
                                      nro_tramite,
                                      nota,
                                      descripcion,
                                      desc_funcionario1,
                                      nombre_cargo,
                                      gestion,
                                      recomendacion,
                                      cite,
                                      genero
                                      )select
                                evd.id_uo,
                                evd.nro_tramite,
                                evd.nota,
                                evd.descripcion,
                                initcap (f.desc_funcionario1 ) as desc_funcionario1,
                                upper( evd.cargo_evaluado)::varchar as nombre_cargo,
                                evd.gestion,
                                evd.recomendacion,
                                evd.cite,
                                CASE
                              	WHEN pe.genero::text = ANY (ARRAY['varon'::character varying,'VARON'::character varying, 'Varon'::character varying]::text[]) THEN 'M'::text
                              	WHEN pe.genero::text = ANY (ARRAY['mujer'::character varying,'MUJER'::character varying, 'Mujer'::character varying]::text[]) THEN 'F'::text
                              	ELSE ''::text
                              	END::character varying AS genero
                                from orga.tevaluacion_desempenio evd
                                inner join orga.vfuncionario_cargo f on f.id_funcionario = evd.id_funcionario and (f.fecha_finalizacion is null or f.fecha_asignacion>=now()::date)
                                inner join orga.vfuncionario_persona fu on fu.id_funcionario = evd.id_funcionario
 								inner join segu.vpersona2 pe on pe.id_persona = fu.id_persona
								where evd.id_uo = v_parametros.id_uo and evd.gestion = v_parametros.gestion and (case
       	  																		 when v_parametros.rango = '0_70' then
                                                                                  evd.nota >= 0 and evd.nota <= 70
                                                                                 when v_parametros.rango = '71_80' then
                                                                                  evd.nota >= 71 and evd.nota <= 80
                                                                                 when v_parametros.rango = '81_90' then
                                                                                  evd.nota >= 81 and evd.nota <= 90
                                                                                 else
                                                                                  evd.nota >= 91 and evd.nota <= 100
                                                                                 end ) and evd.revisado = 'no';
           update orga.tevaluacion_desempenio  set
           revisado = 'si'
           where id_uo = v_parametros.id_uo and gestion = v_parametros.gestion and (case
       	  																		 when v_parametros.rango = '0_70' then
                                                                                  nota >= 0 and nota <= 70
                                                                                 when v_parametros.rango = '71_80' then
                                                                                  nota >= 71 and nota <= 80
                                                                                 when v_parametros.rango = '81_90' then
                                                                                  nota >= 81 and nota <= 90
                                                                                 else
                                                                                  nota >= 91 and nota <= 100
                                                                                 end );

        	v_consulta:='select id_uo,
                                nro_tramite,
                                nota,
                                descripcion,
                                desc_funcionario1,
                                nombre_cargo,
                                gestion,
                                recomendacion,
                                cite,
                                genero
            					from temp_evaluacion';
            raise notice 'consulta %',v_consulta;
            return v_consulta;
        end;


    	/*********************************
 	#TRANSACCION:  'MEM_EVD_SER'
 	#DESCRIPCION:	Servicio consulta funcionario evaluacion
 	#AUTOR:		miguel.mamani
 	#FECHA:		24-02-2018 20:33:35
	***********************************/

	elsif(p_transaccion='MEM_EVD_SER')then

		begin
         v_consulta:='with gerencia as (select 	uo.id_uo,
                                                es.id_uo_hijo,
                                                uo.nombre_cargo,
                                                f.desc_funcionario1 as jefe
                                                from orga.tuo uo
                                                inner join orga.tuo_funcionario uf on uf.id_uo = uo.id_uo
                                                inner join orga.vfuncionario f on f.id_funcionario = uf.id_funcionario
                                                inner join orga.testructura_uo es on es.id_uo_padre = uo.id_uo
                                                where uo.estado_reg = ''activo''
                                                )select 	ev.id_funcionario,
                                                            f.id_cargo,
                                                            f.id_uo_funcionario,
                                                            ev.gestion,
                                                            ev.fecha_solicitud,
                                                            f.desc_funcionario1::varchar as nombre_funcionario,
                                                            f.nombre_cargo::varchar as cargo_funcionario,
                                                            ev.nota,
                                                            g.nombre_cargo::varchar as nombre_cargo ,
                                                            g.jefe::varchar as jefe
                                                    from orga.tevaluacion_desempenio ev
                                                    inner join orga.vfuncionario_cargo f on f.id_funcionario = ev.id_funcionario
                                                    inner join gerencia g on g.id_uo_hijo = f.id_uo
                                                    and (f.fecha_finalizacion is null or f.fecha_finalizacion >= now()::date)
                                                     ';
            raise notice 'consulta %',v_consulta;
			return v_consulta;
		end;
    /*********************************
 	#TRANSACCION:  'MEM_EVD_FUN'
 	#DESCRIPCION:	Listar funcionario por gerencia
 	#AUTOR:		miguel.mamani
 	#FECHA:		24-02-2018 20:33:35
	***********************************/

	elsif(p_transaccion='MEM_EVD_FUN')then
		
		begin

			--Sentencia de la consulta de conteo de registros
            --raise exception '%',v_parametros.filtro;
			v_consulta:='select 	fu.id_funcionario,
                                    ger.id_uo,
                                    ger.nombre_unidad,
                                    fu.desc_funcionario1,
                                    fu.fecha_finalizacion,
                                    fu.descripcion_cargo as nombre_cargo,
                                    fu.email_empresa
                                    from orga.vfuncionario_cargo fu
                                    inner join orga.tuo ger ON ger.id_uo = orga.f_get_uo_gerencia(fu.id_uo, NULL::integer, NULL::date)
                                    where (fu.fecha_finalizacion is null or fu.fecha_finalizacion >= now()::date)and ger.id_uo = '||v_parametros.gerencia||'and ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
            v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
 			raise notice 'consulta %',v_consulta;
			--Devuelve la respuesta
			return v_consulta;

		end;
               
    /*********************************    
 	#TRANSACCION:  'OR_CCO_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		miguel.mamani	
 	#FECHA:		02-07-2018 05:18:31
	***********************************/

	elsif(p_transaccion='OR_CCO_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select	    evd.id_evaluacion_desempenio,
                                    evd.cite,
                                    evd.estado,
                                    f.desc_funcionario1 as nombre_funcionario,
                                    upper (evd.cargo_evaluado) as nombre_cargo,
                                    f.email_empresa,	
                                    evd.gestion,
                                    ger.nombre_unidad,
                                    evd.nota,
                                    evd.descripcion,
                                    evd.nro_tramite
                                    from orga.tevaluacion_desempenio evd
                                    inner join orga.vfuncionario_cargo f on f.id_funcionario = evd.id_funcionario and (f.fecha_finalizacion is null or f.fecha_asignacion>=now()::date)
                                    --inner join orga.tcargo ca on ca.id_temporal_cargo = evd.id_cargo_evaluado --agregado                                            
                                    inner join orga.tuo ger ON ger.id_uo = orga.f_get_uo_gerencia(f.id_uo, NULL::integer, NULL::date)
                                    where';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;
			raise notice 'cosulta %',v_consulta;
			--Devuelve la respuesta
			return v_consulta;
						
		end;
    /*********************************    
 	#TRANSACCION:  'OR_CCO_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		miguel.mamani	
 	#FECHA:		02-07-2018 05:18:31
	***********************************/

	elsif(p_transaccion='OR_CCO_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select	  count( evd.id_evaluacion_desempenio)
                                  from orga.tevaluacion_desempenio evd
                                  inner join orga.vfuncionario_cargo f on f.id_funcionario = evd.id_funcionario and (f.fecha_finalizacion is null or f.fecha_asignacion>=now()::date)
                                  --inner join orga.tcargo ca on ca.id_temporal_cargo = evd.id_cargo_evaluado --agregado                                          
                                  inner join orga.tuo ger ON ger.id_uo = orga.f_get_uo_gerencia(f.id_uo, NULL::integer, NULL::date)
                                  where';
			
			--Definicion de la respuesta		    
			v_consulta:=v_consulta||v_parametros.filtro;

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
COST 100;