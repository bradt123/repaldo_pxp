CREATE OR REPLACE FUNCTION orga.f_tr_registra_movimiento_asignacion (
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema Organigrama
 FUNCION: 		param.f_get_arbol_lugar
 DESCRIPCION:   Funcion que registra los movimientos de asignación.
 AUTOR: 		(franklin.espinoza)
 FECHA:	        28-05-2019 15:15:26
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
    v_bandera				varchar;
    v_correo				varchar;
    v_plantilla				varchar;
    v_hora_saludo			varchar;
    v_rec_funcionario		record;
    v_record				record;
    v_record_fin			record;
    v_fechas_fin			date;
    v_id_alarma				integer;
	v_tipo_movimiento		varchar = '';
    v_complemento			varchar = '';
    v_titulo_correo			varchar = '';
BEGIN
	v_nombre_funcion = 'orga.f_registra_movimiento_asignacion';
	for v_record in select tuo.id_funcionario, tuo.id_cargo, tuo.id_uo, tuo.fecha_asignacion, tuo.fecha_finalizacion, tcon.codigo, tcon.nombre
    				from orga.tuo_funcionario tuo
                    inner join orga.tcargo tc on tc.id_cargo = tuo.id_cargo
                    inner join orga.ttipo_contrato tcon on tcon.id_tipo_contrato = tc.id_tipo_contrato
    				where tuo.fecha_reg::date = current_date loop

        SELECT tuo.fecha_finalizacion
        into v_fechas_fin
        FROM orga.tfuncionario func
        inner join orga.tuo_funcionario tuo on tuo.id_funcionario = func.id_funcionario
        WHERE func.id_funcionario = v_record.id_funcionario and (tuo.fecha_finalizacion between (date_trunc('month',v_record.fecha_asignacion::date) - interval '1 month')::date and (date_trunc('month', v_record.fecha_asignacion::date) - interval '1 day')::date);

        if v_fechas_fin is not null then
        	v_tipo_movimiento = 'RENOVACIÓN';
            v_complemento = ' indicarle que se realizó un movimiento ';
        	v_titulo_correo = 'Renovación Contrato';
        else
        	v_tipo_movimiento = 'ALTA';
            v_complemento = ' solicitar cuenta de correo ';
            v_titulo_correo = 'Alta Cuenta de Correo';
        end if;
        --raise exception 'llega: %',v_record.id_funcionario;
        if v_record.codigo in ('PLA', 'EVE', 'CONS', 'PEXTE', 'PEXT') then

          select tf.desc_funcionario1, tfu.email_empresa
          into v_rec_funcionario
          from orga.vfuncionario tf
          inner join orga.tfuncionario tfu on tfu.id_funcionario = tf.id_funcionario
          where tf.id_funcionario = v_record.id_funcionario;

          --if v_rec_funcionario.email_empresa = '' or v_rec_funcionario.email_empresa is null  then

          v_hora_saludo = case when current_time between '08:00:00'::time and '12:00:00'::time then '<b>Buenos dias' ::varchar
                               when current_time between '12:00:00'::time and '23:00:00'::time then '<b>Buenas tardes'::varchar end;

            v_plantilla = v_hora_saludo||' estimado Administrador:</b><br>
              <p>El motivo del presente es '||v_complemento||' para el funcionario <b>'||coalesce(v_rec_funcionario.desc_funcionario1, 'Comunicarse con Franklin Espinoza')||'</b>:<br>
              <b>Tipo de Contrato:</b> '||coalesce(v_record.nombre,'Por Definirse')||'<br>
              <b>Tipo Movimiento:</b> '||coalesce(v_tipo_movimiento,'Por Definirse')||'<br>
              <b>Fecha Asignación:</b> '||coalesce(to_char(v_record.fecha_asignacion, 'dd/mm/yyyy'),'')||'<br>
              <b>Fecha Finalización:</b> '||coalesce(to_char(v_record.fecha_finalizacion, 'dd/mm/yyyy'),'N/E')||'<br>
              <b>Correo Empresarial:</b> '||coalesce(v_rec_funcionario.email_empresa,'Por Definirse')||'
              </p>';

            v_id_alarma :=  param.f_inserta_alarma(
                                                null,
                                                v_plantilla,
                                                '../../../sis_organigrama/vista/reporte/CorreosEmpBoa.php',
                                                now()::date,
                                                'notificacion',
                                                'Ninguna',
                                                1,
                                                'CorreosEmpBoa',
                                                v_rec_funcionario.desc_funcionario1,--titulo
                                                '{filtro_directo:{campo:"id_funcionario",valor:"'||v_record.id_funcionario::varchar||'"}}',
                                                NULL::integer,
                                                v_titulo_correo,
                                                'correos@boa.bo,[franklin.espinoza@boa.bo;gvelasquez@boa.bo]',
                                                null,
                                                null
                                                );

    	end if;
    end loop;

    if current_date = ((date_trunc('month', current_date)  + interval '1 month') - interval '1 day')::date then
      for v_record_fin in select tuo.id_funcionario, tuo.id_cargo, tuo.id_uo, tuo.fecha_asignacion, tuo.fecha_finalizacion, tcon.codigo, tcon.nombre
                          from orga.tuo_funcionario tuo
                          inner join orga.tcargo tc on tc.id_cargo = tuo.id_cargo
                          inner join orga.ttipo_contrato tcon on tcon.id_tipo_contrato = tc.id_tipo_contrato
                          where tuo.fecha_finalizacion between date_trunc('month', current_date)::date and current_date loop

      	SELECT tuo.fecha_finalizacion
        into v_fechas_fin
        FROM orga.tfuncionario func
        inner join orga.tuo_funcionario tuo on tuo.id_funcionario = func.id_funcionario
        WHERE func.id_funcionario = v_record_fin.id_funcionario and (tuo.fecha_asignacion between (current_date + interval '1 day')::date and ((date_trunc('month',current_date + interval '2 month')) - interval '1 day')::date);

        if v_fechas_fin is null or v_fechas_fin = '' then

          v_tipo_movimiento = 'BAJA';
          v_complemento = ' indicarle que se realizó un movimiento ';

          if v_record_fin.codigo in ('PLA', 'EVE', 'CONS', 'PEXTE', 'PEXT') then

            select tf.desc_funcionario1, tfu.email_empresa
            into v_rec_funcionario
            from orga.vfuncionario tf
            inner join orga.tfuncionario tfu on tfu.id_funcionario = tf.id_funcionario
            where tf.id_funcionario = v_record_fin.id_funcionario;

            --if v_rec_funcionario.email_empresa = '' or v_rec_funcionario.email_empresa is null  then

            v_hora_saludo = case when current_time between '08:00:00'::time and '12:00:00'::time then '<b>Buenos dias' ::varchar
                                 when current_time between '12:00:00'::time and '19:00:00'::time then '<b>Buenas tardes'::varchar end;

              v_plantilla = v_hora_saludo||' estimado Administrador:</b><br>
                <p>El motivo del presente es '||v_complemento||' para el funcionario <b>'||coalesce(v_rec_funcionario.desc_funcionario1, 'Comunicarse con Franklin Espinoza')||'</b>.<br>
                <b>Tipo de Contrato:</b> '||coalesce(v_record_fin.nombre,'Por Definirse')||'<br>
                <b>Tipo Movimiento:</b> '||coalesce(v_tipo_movimiento,'Por Definirse')||'<br>
                <b>Fecha Asignación:</b> '||coalesce(to_char(v_record_fin.fecha_asignacion, 'dd/mm/yyyy'),'')||'<br>
                <b>Fecha Finalización:</b> '||coalesce(to_char(v_record_fin.fecha_finalizacion, 'dd/mm/yyyy'),'N/E')||'<br>
                <b>Correo Empresarial:</b> '||coalesce(v_rec_funcionario.email_empresa,'Por Definirse')||'
                </p>';

              v_id_alarma :=  param.f_inserta_alarma(
                                                  null,
                                                  v_plantilla,
                                                  '../../../sis_organigrama/vista/reporte/CorreosEmpBoa.php',
                                                  now()::date,
                                                  'notificacion',
                                                  'Ninguna',
                                                  1,
                                                  'CorreosEmpBoa',
                                                  v_rec_funcionario.desc_funcionario1,--titulo
                                                  '{filtro_directo:{campo:"id_funcionario",valor:"'||v_record_fin.id_funcionario::varchar||'"}}',
                                                  NULL::integer,
                                                  'Baja Cuenta de Correo',
                                                  'correos@boa.bo,[franklin.espinoza@boa.bo;gvelasquez@boa.bo]',
                                                  null,
                                                  null
                                                  );

          end if;
      	end if;
      end loop;
    end if;

    return 'EXITO';

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