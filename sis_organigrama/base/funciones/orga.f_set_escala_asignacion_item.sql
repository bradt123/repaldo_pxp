CREATE OR REPLACE FUNCTION orga.f_set_escala_asignacion_item (
)
RETURNS boolean AS
$body$
/**************************************************************************
SISTEMA DE ORGANIGRAMA
***************************************************************************
 SCRIPT: 		kalm.f_get_escala_asignacion_item
 DESCRIPCIÓN: 	Función que devuelve las escalas de item de una determinada asignacion de cargo
 AUTOR: 		franklin.espinoza
 FECHA:			24/10/2018
 COMENTARIOS:
***************************************************************************
 HISTORIA DE MODIFICACIONES:

 DESCRIPCIÓN:
 AUTOR:
 FECHA:

***************************************************************************/
--------------------------
-- CUERPO DE LA FUNCIÓN --
--------------------------

DECLARE

	v_nombre_funcion   	text;
	v_resp				varchar;
	v_consulta 			varchar;
    v_record			record;
	v_id_funcionario 	integer;
    v_return			boolean;
    v_contador			integer;
    v_rec				record;
    v_rec_escala_actual	record;

    v_date_min			date;

BEGIN

	v_nombre_funcion = 'orga.f_set_escala_asignacion_item';

	for v_id_funcionario in select distinct tf.id_funcionario
     					 from orga.tfuncionario tf
                         inner join orga.tuo_funcionario tuo on tuo.id_funcionario = tf.id_funcionario AND
                         (tuo.fecha_finalizacion between current_date and coalesce (tuo.fecha_finalizacion, '31/12/9999'::date) or  tuo.fecha_finalizacion is null)
                         WHERE  tf.estado_reg = 'activo'  and tuo.tipo = 'oficial' and tuo.estado_reg = 'activo'
                         order by tf.id_funcionario asc loop
    	raise notice 'v_id_funcionario: %', v_id_funcionario;
    	for v_record in   select tuo.id_funcionario, tuo.id_uo_funcionario, tuo.id_cargo, tes.id_escala_salarial, tuo.fecha_asignacion, tuo.fecha_finalizacion
                          from orga.tescala_salarial  tes
                          inner join  orga.tcargo tca on tca.id_escala_salarial = tes.id_escala_salarial
                          inner join orga.tuo_funcionario tuo on tuo.id_cargo = tca.id_cargo
                          where tuo.tipo='oficial' and tuo.id_funcionario = v_id_funcionario and tuo.estado_reg = 'activo'
                          order by tuo.fecha_asignacion loop
      		raise notice 'cargo: %, escala: %, fecha_asignacion: %', v_record.id_cargo, v_record.id_escala_salarial, v_record.fecha_asignacion;
      		--select orga.f_get_escala_asignacion_item(v_record.id_funcionario, v_record.id_uo_funcionario, v_record.id_cargo, v_record.id_escala_salarial, v_record.fecha_asignacion, v_record.fecha_finalizacion);

            select count(te.id_escala_salarial)
            into v_contador
            from orga.tescala_salarial te
            where te.id_escala_padre = v_record.id_escala_salarial;



            if v_contador > 0 then

                /*select	min(tes.fecha_ini)
                into v_date_min
                from orga.tescala_salarial tes
                where tes.id_escala_padre is not null and tes.id_escala_padre = v_record.id_escala_salarial and
                v_record.fecha_asignacion between v_record.fecha_asignacion and tes.fecha_fin;*/

                --CONTINUE WHEN  v_record.fecha_asignacion < v_date_min;

               	select tes.id_escala_salarial, tes.haber_basico, tes.fecha_ini, tes.fecha_fin
                into v_rec
                from orga.tescala_salarial tes
                where tes.id_escala_padre is not null and tes.id_escala_padre = v_record.id_escala_salarial and
                case when v_record.fecha_finalizacion is null then tes.fecha_fin else v_record.fecha_finalizacion end  between tes.fecha_ini and coalesce(tes.fecha_fin, '31/12/9999'::date);
                --v_record.fecha_asignacion between tes.fecha_ini and tes.fecha_fin;

               /* if v_record.id_funcionario = 25 then
            		raise exception 'exception : %, %, %, %, %, %, %', v_rec, v_record.id_funcionario, v_record.id_escala_salarial, v_contador, v_date_min, v_record.fecha_asignacion, v_record.fecha_finalizacion;
            	end if;*/

            else
                select te.id_escala_salarial, te.haber_basico, te.fecha_ini, te.fecha_fin
                into v_rec
                from orga.tescala_salarial te
                where te.id_escala_salarial = v_record.id_escala_salarial;
            end if;

            select tca.id_cargo, tes.id_escala_salarial, tes.haber_basico, tes.fecha_ini, tes.fecha_fin
            into v_rec_escala_actual
            from orga.tuo_funcionario tuf
            inner join  orga.tcargo tca on tca.id_cargo = tuf.id_cargo
            inner join orga.tescala_salarial tes on tes.id_escala_salarial = tca.id_escala_salarial
            where tuf.id_uo_funcionario = orga.f_get_ultima_asignacion(v_record.id_funcionario) and tuf.estado_reg = 'activo';

            --if v_rec is not null then
              if v_record.id_escala_salarial = v_rec_escala_actual.id_escala_salarial then

                  insert into orga.tt_escala_funcionario
                  select v_record.id_funcionario,v_record.id_uo_funcionario, v_record.id_cargo, te.id_escala_salarial, te.haber_basico, te.fecha_ini, te.fecha_fin
                  from orga.tescala_salarial te
                  where te.id_escala_padre = v_record.id_escala_salarial and te.fecha_fin = v_rec_escala_actual.fecha_ini-1 limit 1;

                  insert into orga.tt_escala_funcionario (id_funcionario,id_uo_funcionario, id_cargo, id_escala_salarial, haber_basico, fecha_ini, fecha_fin)
                  values (
                      v_record.id_funcionario,
                      v_record.id_uo_funcionario,
                      v_record.id_cargo,
                      v_rec_escala_actual.id_escala_salarial,
                      v_rec_escala_actual.haber_basico,
                      v_rec_escala_actual.fecha_ini,
                      v_rec_escala_actual.fecha_fin
                  ); --return ;
              else
                insert into orga.tt_escala_funcionario(id_funcionario,id_uo_funcionario, id_cargo, id_escala_salarial, haber_basico, fecha_ini, fecha_fin)
                values (
                  v_record.id_funcionario,
                  v_record.id_uo_funcionario,
                  v_record.id_cargo,
                  v_rec.id_escala_salarial,
                  v_rec.haber_basico,
                  v_rec.fecha_ini,
                  v_rec.fecha_fin
                );
              end if;
            --end if;

    	end loop;
        raise notice '=======================================';
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