CREATE OR REPLACE FUNCTION orga.f_replicar_presupuesto_cargo (
)
RETURNS boolean AS
$body$
/**************************************************************************
 SISTEMA:		Sistema Organigrama
 FUNCION: 		orga.f_replicar_presupuesto_cargo
 DESCRIPCION:   Funcion que replica presupuestos para cargos.
 AUTOR: 		(franklin.espinoza)
 FECHA:	        09-01-2019 15:15:26
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
	v_bono_antiguedad		integer;
    v_record 				record;
	v_gestion				integer;
    v_id_centro_actual		integer;
    v_presupuesto			record;
    v_contador				integer = 1;
BEGIN

    v_nombre_funcion = 'orga.f_replicar_presupuesto_cargo';

    select tg.gestion
    into v_gestion
    from param.tgestion tg
    where tg.gestion = date_part('year',current_date);

    for v_record in  select vf.id_funcionario,
                            cargo.id_cargo,
                            cargo.id_uo
                     from orga.tcargo cargo
                          inner join segu.tusuario usu1 on usu1.id_usuario = cargo.id_usuario_reg
                          left join segu.tusuario usu2 on usu2.id_usuario = cargo.id_usuario_mod
                          inner join orga.ttipo_contrato tipcon on tipcon.id_tipo_contrato =
                            cargo.id_tipo_contrato
                          inner join orga.tescala_salarial escsal on escsal.id_escala_salarial =
                            cargo.id_escala_salarial
                          LEFT join orga.toficina ofi on ofi.id_oficina = cargo.id_oficina
                          left join orga.tcargo_presupuesto tcp on tcp.id_cargo = cargo.id_cargo and
                            tcp.id_gestion = 17
                          LEFT join orga.tuo_funcionario tuo on tuo.id_cargo = cargo.id_cargo and (
                            tuo.fecha_finalizacion is null or current_date <= tuo.fecha_finalizacion
                            )
                          LEFT join orga.vfuncionario vf on vf.id_funcionario = tuo.id_funcionario
                     where cargo.estado_reg = 'activo' and
                           tipcon.codigo != 'PCP' and
                          (tcp.id_cargo_presupuesto is null and tcp.id_ot is null and tuo.id_uo_funcionario is null)
                           /*select tuo.id_funcionario, tcp.id_cargo, tcp.id_ot, tcp.id_centro_costo
                                        from orga.tuo_funcionario tuo
                                        inner join orga.tcargo_presupuesto tcp on tcp.id_cargo = tuo.id_cargo and tcp.id_gestion = 16
                                        and tcp.estado_reg = 'activo' and (tcp.fecha_fin >= '31/12/2018'::date or tcp.fecha_fin is NULL)
                                        where tuo.estado_reg::text = 'activo'::text AND
                                        (tuo.fecha_finalizacion IS NULL OR tuo.fecha_finalizacion >= current_date) AND
                                        tuo.fecha_asignacion <= current_date AND tuo.tipo='oficial' and tuo.fecha_asignacion != ('1/1/'||v_gestion)::date and tuo.fecha_asignacion != ('2/1/'||v_gestion)::date*/ loop

        /*select tp.id_presupuesto_dos
        into v_id_centro_actual
        from pre.tpresupuesto_ids tp
        where tp.id_presupuesto_uno = v_record.id_centro_costo;
        if v_id_centro_actual is not null then
          insert into orga.tcargo_presupuesto (
              id_centro_costo,
              id_cargo,
              porcentaje,
              fecha_ini,
              id_gestion,
              id_ot,
              fecha_fin
          ) values (
              v_id_centro_actual,
              v_record.id_cargo,
              100,
              '1/1/2019'::date,
              17,
              v_record.id_ot,
              '31/12/2019'::date
          );
        else
        	insert into rec.tpres_2019 (
              id_funcionario,
              id_centro_costo
          ) values (
              v_record.id_funcionario,
              v_id_centro_actual
          );
        end if;*/
        /*select
						carpre.id_cargo_presupuesto,
						carpre.id_cargo,
						carpre.id_gestion,
						carpre.id_centro_costo,
						carpre.porcentaje,
						carpre.fecha_ini,
                        carpre.fecha_fin,
						carpre.estado_reg,
						carpre.id_usuario_reg,
						carpre.fecha_reg,
						carpre.fecha_mod,
						carpre.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
						cc.codigo_cc,
                        carpre.id_ot,
                        ot.desc_orden
                        into v_presupuesto
						from orga.tcargo_presupuesto carpre
                        inner join orga.tcargo tca on tca.id_cargo = carpre.id_cargo
                        INNER JOIN param.tgestion tg on tg.id_gestion = carpre.id_gestion
                        left join orga.tuo_funcionario tuo on tuo.id_cargo = carpre.id_cargo and coalesce(tuo.fecha_finalizacion,('31/12/'||tg.gestion)::date)  between  carpre.fecha_ini and coalesce(carpre.fecha_fin,('31/12/'||tg.gestion)::date)
						inner join segu.tusuario usu1 on usu1.id_usuario = carpre.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = carpre.id_usuario_mod
						left join conta.torden_trabajo ot on ot.id_orden_trabajo = carpre.id_ot
                        inner join param.vcentro_costo cc on cc.id_centro_costo = carpre.id_centro_costo
				        where carpre.id_cargo = v_record.id_cargo AND carpre.id_gestion = 16 AND tca.id_uo = v_record.id_uo AND tuo.id_funcionario = v_record.id_funcionario;*/

                        select
						carpre.id_cargo_presupuesto,
						carpre.id_cargo,
						carpre.id_gestion,
						carpre.id_centro_costo,
						carpre.porcentaje,
						carpre.fecha_ini,
                        carpre.fecha_fin,
						carpre.estado_reg,
						carpre.id_usuario_reg,
						carpre.fecha_reg,
						carpre.fecha_mod,
						carpre.id_usuario_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod,
						cc.codigo_cc,
                        carpre.id_ot,
                        ot.desc_orden

                        into v_presupuesto
						from orga.tcargo_presupuesto carpre
                        inner join orga.tcargo tca on tca.id_cargo = carpre.id_cargo
                        INNER JOIN param.tgestion tg on tg.id_gestion = carpre.id_gestion
                        left join orga.tuo_funcionario tuo on tuo.id_cargo = carpre.id_cargo and coalesce(tuo.fecha_finalizacion,('31/12/'||tg.gestion)::date)  between  carpre.fecha_ini and coalesce(carpre.fecha_fin,('31/12/'||tg.gestion)::date)
						inner join segu.tusuario usu1 on usu1.id_usuario = carpre.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = carpre.id_usuario_mod
						left join conta.torden_trabajo ot on ot.id_orden_trabajo = carpre.id_ot
                        inner join param.vcentro_costo cc on cc.id_centro_costo = carpre.id_centro_costo
				        where  carpre.id_cargo = v_record.id_cargo AND carpre.id_gestion = 16 AND tca.id_uo = v_record.id_uo
                        order by id_cargo_presupuesto ASC;

                        if v_presupuesto.id_centro_costo is not null then
                        	raise notice '%.- funcionario: %, presupuesto: %',v_contador, v_record.id_funcionario, v_presupuesto;
                            v_contador = v_contador + 1;
                        end if;

                        select tp.id_presupuesto_dos
                        into v_id_centro_actual
                        from pre.tpresupuesto_ids tp
                        where tp.id_presupuesto_uno = v_presupuesto.id_centro_costo;

                        if v_id_centro_actual is not null then
                          insert into orga.tcargo_presupuesto (
                              id_centro_costo,
                              id_cargo,
                              porcentaje,
                              fecha_ini,
                              id_gestion,
                              id_ot,
                              fecha_fin
                          ) values (
                              v_id_centro_actual,
                              v_presupuesto.id_cargo,
                              100,
                              '1/1/2019'::date,
                              17,
                              v_presupuesto.id_ot,
                              '31/12/2019'::date
                          );
                        else
                            insert into rec.tpres_2019 (
                              id_funcionario,
                              id_centro_costo
                          ) values (
                              612,
                              v_id_centro_actual
                          );
                        end if;

    end loop;

    return true;

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