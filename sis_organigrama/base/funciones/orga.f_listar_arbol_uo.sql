CREATE OR REPLACE FUNCTION orga.f_listar_arbol_uo (
)
RETURNS TABLE (
  nit varchar,
  empresa varchar,
  codigo_cc varchar,
  nombre_cc varchar,
  codigo_emp varchar,
  nombre_emp varchar,
  correo_emp varchar,
  interno varchar,
  id_uo integer,
  codigo_1 varchar,
  nombre_1 varchar,
  codigo_2 varchar,
  nombre_2 varchar,
  codigo_3 varchar,
  nombre_3 varchar,
  codigo_4 varchar,
  nombre_4 varchar,
  codigo_5 varchar,
  nombre_5 varchar,
  codigo_6 varchar,
  nombre_6 varchar,
  codigo_7 varchar,
  nombre_7 varchar,
  codigo_8 varchar,
  nombre_8 varchar,
  codigo_9 varchar,
  nombre_9 varchar
) AS
$body$
/**************************************************************************
 SISTEMA:		Sistema Organigrama
 FUNCION: 		public.f_listar_arbol_uo
 DESCRIPCION:   Funcion que recupera la estructura de el arbol unidad organizacional.
 AUTOR: 		(franklin.espinoza)
 FECHA:	        14-06-2019 15:15:26
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
    v_id_uo					integer;
    v_niveles				integer[];
    v_codigo_1 varchar = '';
    v_nombre_1 varchar = '';
    v_codigo_2 varchar = '';
    v_nombre_2 varchar = '';
    v_codigo_3 varchar = '';
    v_nombre_3 varchar = '';
    v_codigo_4 varchar = '';
    v_nombre_4 varchar = '';
    v_codigo_5 varchar = '';
    v_nombre_5 varchar = '';
    v_codigo_6 varchar = '';
    v_nombre_6 varchar = '';
    v_codigo_7 varchar = '';
    v_nombre_7 varchar = '';
    v_codigo_8 varchar = '';
    v_nombre_8 varchar = '';
    v_codigo_9 varchar = '';
    v_nombre_9 varchar = '';
BEGIN

    v_nombre_funcion = 'orga.f_listar_arbol_uo';

    for v_id_uo in select unnest(string_to_array(9419||','||rtrim(orga.f_get_arbol_uo(9419),','), ',')) as id_unida_o  loop
	  v_niveles = string_to_array(orga.f_get_arbol_padre_uo(v_id_uo),',');
      --nivel 1
      select coalesce(ps_codigo,''), coalesce(ps_nombre,'')
      into v_codigo_1, v_nombre_1
      from orga.f_get_arbol_datos_uo(v_niveles[1]::integer);
      --nivel 2
      select coalesce(ps_codigo,''), coalesce(ps_nombre,'')
      into v_codigo_2, v_nombre_2
      from orga.f_get_arbol_datos_uo(v_niveles[2]::integer);
      --nivel 3
      select coalesce(ps_codigo,''), coalesce(ps_nombre,'')
      into v_codigo_3, v_nombre_3
      from orga.f_get_arbol_datos_uo(v_niveles[3]::integer);
      --nivel 4
      select coalesce(ps_codigo,''), coalesce(ps_nombre,'')
      into v_codigo_4, v_nombre_4
      from orga.f_get_arbol_datos_uo(v_niveles[4]::integer);
      --nivel 5
      select coalesce(ps_codigo,''), coalesce(ps_nombre,'')
      into v_codigo_5, v_nombre_5
      from orga.f_get_arbol_datos_uo(v_niveles[5]::integer);
      --nivel 6
      select coalesce(ps_codigo,''), coalesce(ps_nombre,'')
      into v_codigo_6, v_nombre_6
      from orga.f_get_arbol_datos_uo(v_niveles[6]::integer);
      --nivel 7
      select coalesce(ps_codigo,''), coalesce(ps_nombre,'')
      into v_codigo_7, v_nombre_7
      from orga.f_get_arbol_datos_uo(v_niveles[7]::integer);
      --nivel 8
      select coalesce(ps_codigo,''), coalesce(ps_nombre,'')
      into v_codigo_8, v_nombre_8
      from orga.f_get_arbol_datos_uo(v_niveles[8]::integer);
      --nivel 9
      select coalesce(ps_codigo,''), coalesce(ps_nombre,'')
      into v_codigo_9, v_nombre_9
      from orga.f_get_arbol_datos_uo(v_niveles[9]::integer);

      for v_record in select DISTINCT ON (tf.desc_funcionario2) tf.desc_funcionario2,tuo.id_uo, ttcc.codigo as codigo_ccc, ttcc.descripcion as nombre_ccc,
        					 tf.codigo as codigo_empc, tf.desc_funcionario2 as nombre_empc, vf.email_empresa as correo_empc,
                             (CASE WHEN tnc.tipo = 'interno' THEN coalesce(tnc.numero,'') ELSE '' END) as internoc
                      from orga.tuo tu
                      inner join orga.tuo_funcionario tuo on tuo.id_uo = tu.id_uo
                      inner join orga.vfuncionario tf on tf.id_funcionario = tuo.id_funcionario
                      inner join orga.tfuncionario vf on vf.id_funcionario = tf.id_funcionario
                      LEFT join gecom.tfuncionario_celular tfc on tfc.id_funcionario =  tf.id_funcionario
                      LEFT join gecom.tnumero_celular tnc on tnc.id_numero_celular = tfc.id_numero_celular and tnc.tipo = 'interno'
                      inner join orga.tcargo tc on tc.id_cargo = tuo.id_cargo
                      inner join orga.ttipo_contrato ttc on ttc.id_tipo_contrato = tc.id_tipo_contrato
                      inner join orga.tcargo_presupuesto tcp on tcp.id_cargo = tc.id_cargo and tcp.id_gestion = (SELECT tg.id_gestion
                                                                                                                 FROM param.tgestion tg
                                                                                                                 WHERE tg.gestion = date_part('year', current_date))
                      inner join param.tcentro_costo tcc on tcc.id_centro_costo = tcp.id_centro_costo
                      inner join param.ttipo_cc ttcc on ttcc.id_tipo_cc = tcc.id_tipo_cc
                      where tuo.estado_reg = 'activo' and ttc.codigo in ('PLA','EVE','PEXT','PEXTE') and tuo.id_uo = v_id_uo  and (tuo.fecha_finalizacion is null or tuo.fecha_finalizacion >= current_date) loop


        nit = '154422029';
        empresa = 'Boliviana de Aviación - BoA';
        codigo_cc = v_record.codigo_ccc;
        nombre_cc = v_record.nombre_ccc;
        codigo_emp = v_record.codigo_empc;
        nombre_emp = v_record.nombre_empc;
        correo_emp = v_record.correo_empc;
        interno = v_record.internoc;
        id_uo = v_record.id_uo;
        codigo_1 = v_codigo_1;
        nombre_1 = v_nombre_1;
        codigo_2 = v_codigo_2;
        nombre_2 = v_nombre_2;
        codigo_3 = v_codigo_3;
        nombre_3 = v_nombre_3;
        codigo_4 = v_codigo_4;
        nombre_4 = v_nombre_4;
        codigo_5 = v_codigo_5;
        nombre_5 = v_nombre_5;
        codigo_6 = v_codigo_6;
        nombre_6 = v_nombre_6;
        codigo_7 = v_codigo_7;
        nombre_7 = v_nombre_7;
        codigo_8 = v_codigo_8;
        nombre_8 = v_nombre_8;
        codigo_9 = v_codigo_9;
        nombre_9 = v_nombre_9;

        --if v_record is not null then
            return next;
        --end if;
      end loop;

    end loop;

    return;

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
COST 100 ROWS 1000;