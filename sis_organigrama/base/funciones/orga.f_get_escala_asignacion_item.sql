CREATE OR REPLACE FUNCTION orga.f_get_escala_asignacion_item (
  p_id_funcionario integer,
  p_id_uo_funcionario integer,
  p_id_cargo integer,
  p_id_escala_salarial integer,
  p_fecha_asignacion date,
  p_fecha_finalizacion date
)
RETURNS void AS
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
    v_rec 				record;
    v_rec_escala_actual	record;
    v_contador      	integer;

BEGIN

	v_nombre_funcion = 'orga.f_get_escala_asignacion_item';

		select count(te.id_escala_salarial)
        into v_contador
        from orga.tescala_salarial te
        where te.id_escala_padre = p_id_escala_salarial;

   		if v_contador > 0 then

           select 	tes.id_escala_salarial, tes.haber_basico, tes.fecha_ini, tes.fecha_fin
                    into v_rec
                    from orga.tescala_salarial tes

                    where tes.id_escala_padre is not null and tes.id_escala_padre = p_id_escala_salarial and
                    p_fecha_asignacion between p_fecha_asignacion and tes.fecha_fin;


    	else
        	select te.id_escala_salarial, te.haber_basico, te.fecha_ini, te.fecha_fin
            into v_rec
            from orga.tescala_salarial te

            where te.id_escala_salarial = p_id_escala_salarial;
        end if;

        select tca.id_cargo, tes.id_escala_salarial, tes.haber_basico, tes.fecha_ini, tes.fecha_fin
            into v_rec_escala_actual
            from orga.tuo_funcionario tuf
            inner join  orga.tcargo tca on tca.id_cargo = tuf.id_cargo
            inner join orga.tescala_salarial tes on tes.id_escala_salarial = tca.id_escala_salarial
            where tuf.id_uo_funcionario = orga.f_get_ultima_asignacion(p_id_funcionario);

		if p_id_escala_salarial = v_rec_escala_actual.id_escala_salarial then

            insert into tt_escala_funcionario
        	select p_id_funcionario, p_id_cargo, p_id_uo_funcionario, te.id_escala_salarial, te.haber_basico, te.fecha_ini, te.fecha_fin
            from orga.tescala_salarial te

            where te.id_escala_padre = p_id_escala_salarial and te.fecha_fin = v_rec_escala_actual.fecha_ini-1 limit 1;

            insert into tt_escala_funcionario (id_funcionario,id_uo_funcionario, id_cargo, id_escala_salarial, haber_basico, fecha_ini, fecha_fin)
            values (
            	p_id_funcionario,
                p_id_uo_funcionario,
            	p_id_cargo,
            	v_rec_escala_actual.id_escala_salarial,
                v_rec_escala_actual.haber_basico,
                v_rec_escala_actual.fecha_ini,
                v_rec_escala_actual.fecha_fin
            ); return ;

        end if;


    insert into tt_escala_funcionario(id_funcionario,id_uo_funcionario, id_cargo, id_escala_salarial, haber_basico, fecha_ini, fecha_fin)
    values (
      p_id_funcionario,
      p_id_uo_funcionario,
      p_id_cargo,
      v_rec.id_escala_salarial,
      v_rec.haber_basico,
      v_rec.fecha_ini,
      v_rec.fecha_fin
    );

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