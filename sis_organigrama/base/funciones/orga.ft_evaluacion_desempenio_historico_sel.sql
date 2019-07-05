CREATE OR REPLACE FUNCTION orga.ft_evaluacion_desempenio_historico_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema Memos
 FUNCION: 		orga.ft_evaluacion_desempenio_historico_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'orga.tevaluacion_desempenio_historico'
 AUTOR: 		 (miguel.mamani)
 FECHA:	        08-05-2018 20:39:49
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				08-05-2018 20:39:49								Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'orga.tevaluacion_desempenio_historico'
 #
 ***************************************************************************/

DECLARE

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;

BEGIN

	v_nombre_funcion = 'orga.ft_evaluacion_desempenio_historico_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'MEM_HED_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		miguel.mamani
 	#FECHA:		08-05-2018 20:39:49
	***********************************/

	if(p_transaccion='MEM_HED_SEL')then

    	begin
    		--Sentencia de la consulta
			v_consulta:='select
            			hed.id_evaluacion_desempenio_historico,
						hed.gestion,
						hed.nota,
						hed.id_uo_funcionario,
						hed.codigo,
						hed.estado,
						hed.fecha_solicitud,
						hed.cargo_evaluado,
						hed.descripcion,
						hed.estado_reg,
						hed.id_evaluacion_desempenio_padre,
						hed.id_estado_wf,
						hed.id_funcionario,
						hed.id_proceso_wf,
						hed.nro_tramite,
						hed.id_usuario_ai,
						hed.usuario_ai,
						hed.fecha_reg,
						hed.id_usuario_reg,
						hed.id_usuario_mod,
						hed.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
                        fun.desc_funcionario1::varchar as nombre_funcionario,
                        un.desc_persona::varchar as nombre_funcionaro_mod,
                        hed.fecha_reg as fecha_modifica,
                        hed.cite
						from orga.tevaluacion_desempenio_historico hed
						inner join segu.tusuario usu1 on usu1.id_usuario = hed.id_usuario_reg
                        inner join segu.vusuario un on un.id_usuario = hed.id_usuario_reg
                        inner join orga.vfuncionario fun on fun.id_funcionario = hed.id_funcionario
						left join segu.tusuario usu2 on usu2.id_usuario = hed.id_usuario_mod
                        where  ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;

		end;

	/*********************************
 	#TRANSACCION:  'MEM_HED_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		miguel.mamani
 	#FECHA:		08-05-2018 20:39:49
	***********************************/

	elsif(p_transaccion='MEM_HED_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_evaluacion_desempenio_historico)
            			from orga.tevaluacion_desempenio_historico hed
					    inner join segu.tusuario usu1 on usu1.id_usuario = hed.id_usuario_reg
                        inner join segu.vusuario un on un.id_usuario = hed.id_usuario_reg
                        inner join orga.vfuncionario fun on fun.id_funcionario = hed.id_funcionario
						left join segu.tusuario usu2 on usu2.id_usuario = hed.id_usuario_mod
					    where ';

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