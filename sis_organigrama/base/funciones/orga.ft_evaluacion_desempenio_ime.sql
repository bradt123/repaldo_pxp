CREATE OR REPLACE FUNCTION orga.ft_evaluacion_desempenio_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema Memos
 FUNCION: 		orga.ft_evaluacion_desempenio_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'orga.tevaluacion_desempenio'
 AUTOR: 		 (miguel.mamani)
 FECHA:	        24-02-2018 20:33:35
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				24-02-2018 20:33:35								Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'orga.tevaluacion_desempenio'
 #
 ***************************************************************************/

DECLARE

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_evaluacion_desempenio	integer;

    v_codigo_tipo_proceso 	varchar;
    v_id_proceso_macro    	integer;
    v_gestion 				integer;
    v_nro_tramite			varchar;
    v_id_proceso_wf			integer;
    v_id_estado_wf			integer;
    v_codigo_estado 		varchar;
    anho					integer;
    v_id_uo 				integer;
    v_registo				record;
     v_acceso_directo		varchar;
    v_clase					varchar;
    v_parametros_ad			varchar;
    v_tipo_noti				varchar;
    v_titulo				varchar;
    v_id_tipo_estado		integer;
    v_pedir_obs				varchar;
    v_codigo_estado_siguiente 	varchar;
    v_obs						varchar;
    v_id_estado_actual			integer;
    v_id_depto					integer;
    v_operacion					varchar;
	v_registros_cer  			record;
  	v_id_funcionario 			integer;
    v_id_usuario_reg 			integer;
    v_id_estado_wf_ant			integer;
    v_cite						varchar;
	v_revisado					varchar;
    v_funcionarios 				varchar;
    v_json						record;
    v_evaluado 					varchar;
    v_id_cargo 					integer;
    v_nota 						integer;
    v_descripcion 				varchar;
    v_datos 					record;
 	v_id_alarma 				integer;
    v_cargo_actual 				varchar;
    v_plantilla					varchar;
    v_emisor					record;
    v_codigo 					varchar;
    v_mensaje					varchar;
    v_mjs						varchar;
	v_registro_eva    			varchar;	

BEGIN

    v_nombre_funcion = 'orga.ft_evaluacion_desempenio_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'MEM_EVD_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		miguel.mamani
 	#FECHA:		24-02-2018 20:33:35
	***********************************/

	if(p_transaccion='MEM_EVD_INS')then

        begin
       -- raise exception 'id %',v_parametros.id_funcionario;

           select g.id_gestion, g.gestion
           into v_gestion, anho
           from param.tgestion g
           where g.gestion = EXTRACT(YEAR FROM current_date);

           select tp.codigo,pm.id_proceso_macro
          into v_codigo_tipo_proceso, v_id_proceso_macro
           from  wf.tproceso_macro pm
           inner join wf.ttipo_proceso tp on tp.id_proceso_macro = pm.id_proceso_macro
           where pm.codigo='EDF' and tp.estado_reg = 'activo' and tp.inicio = 'si' ;

                 -- inciar el tramite en el sistema de WF
           SELECT
                 ps_num_tramite ,
                 ps_id_proceso_wf ,
                 ps_id_estado_wf ,
                 ps_codigo_estado
              into
                 v_nro_tramite,
                 v_id_proceso_wf,
                 v_id_estado_wf,
                 v_codigo_estado

            FROM wf.f_inicia_tramite(
                 p_id_usuario,
                 v_parametros._id_usuario_ai,
                 v_parametros._nombre_usuario_ai,
                 v_gestion,
                 v_codigo_tipo_proceso,
                 NULL,
                 NULL,
                 'Evaluación de Desempeño',
                 v_codigo_tipo_proceso);

         	select 'OB.AH.MED.'||lpad(COALESCE(nextval('orga.cite'::regclass), 0)::varchar,1,'0')||'.'||anho
            into v_cite ;

            select ger.id_uo, ca.descripcion_cargo into v_id_uo,v_cargo_actual
            from orga.vfuncionario_cargo ca
            inner join orga.tuo ger ON ger.id_uo = orga.f_get_uo_gerencia(ca.id_uo, NULL::integer, NULL::date)
            where ca.id_funcionario = v_parametros.id_funcionario and (ca.fecha_finalizacion is null or ca.fecha_finalizacion >= now()::date);


            --Sentencia de la insercion
        	insert into orga.tevaluacion_desempenio(
			nro_tramite,
			id_proceso_wf,
			id_funcionario,
			codigo,
			estado,
			nota,
			id_uo_funcionario,
			id_estado_wf,
			descripcion,
			estado_reg,
			id_usuario_ai,
			id_usuario_reg,
			fecha_reg,
			usuario_ai,
			fecha_mod,
			id_usuario_mod,
            gestion,
            cargo_evaluado,
            cite,
            id_uo,
            cargo_actual_memo
          	) values(
			v_nro_tramite,
			v_id_proceso_wf,
			v_parametros.id_funcionario,
			v_parametros.codigo,
			v_codigo_estado,
			v_parametros.nota,
			v_parametros.id_uo_funcionario,
			v_id_estado_wf,
			v_parametros.descripcion,
			'activo',
			v_parametros._id_usuario_ai,
			p_id_usuario,
			now(),
			v_parametros._nombre_usuario_ai,
			null,
			null,
            v_parametros.gestion,
            v_parametros.nombre_cargo_evaluado,
            v_cite,
            v_id_uo,
            v_cargo_actual

			)RETURNING id_evaluacion_desempenio into v_id_evaluacion_desempenio;

			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Evaluacion Desempenio almacenado(a) con exito (id_evaluacion_desempenio'||v_id_evaluacion_desempenio||')');
            v_resp = pxp.f_agrega_clave(v_resp,'id_evaluacion_desempenio',v_id_evaluacion_desempenio::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'MEM_EVD_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		miguel.mamani
 	#FECHA:		24-02-2018 20:33:35
	***********************************/

	elsif(p_transaccion='MEM_EVD_MOD')then

		begin
			--Sentencia de la modificacion
			update orga.tevaluacion_desempenio set
			id_funcionario = v_parametros.id_funcionario,
			id_usuario_ai = v_parametros._id_usuario_ai,
			usuario_ai = v_parametros._nombre_usuario_ai,
            recomendacion = v_parametros.recomendacion
			where id_evaluacion_desempenio=v_parametros.id_evaluacion_desempenio;

			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Evaluacion Desempenio modificado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_evaluacion_desempenio',v_parametros.id_evaluacion_desempenio::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'MEM_EVD_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		miguel.mamani
 	#FECHA:		24-02-2018 20:33:35
	***********************************/

	elsif(p_transaccion='MEM_EVD_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from orga.tevaluacion_desempenio
            where id_evaluacion_desempenio=v_parametros.id_evaluacion_desempenio;

            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Evaluacion Desempenio eliminado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_evaluacion_desempenio',v_parametros.id_evaluacion_desempenio::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;
    /*********************************
 	#TRANSACCION:  'MEM_EVD_EUG'
 	#DESCRIPCION:	recuperar uo funcionario
 	#AUTOR:		miguel.mamani
 	#FECHA:		24-02-2018 20:33:35
	***********************************/

	elsif(p_transaccion='MEM_EVD_EUG')then

		begin

			select f.id_uo
            into
            v_id_uo
            from orga.vfuncionario_cargo f
            where (f.fecha_finalizacion is null or f.fecha_finalizacion >= now()::date)
            and f.id_funcionario = v_parametros.id_funcionario;

            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Exito');
            v_resp = pxp.f_agrega_clave(v_resp,'v_id_uo',v_id_uo::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;
      /*********************************
 	#TRANSACCION:  'EV_SIGUE_EMI'
 	#DESCRIPCION:	Siguiente e
 	#AUTOR:		MMV
 	#FECHA:		06-06-2017 17:32:59
	***********************************/
    elseif(p_transaccion='EV_SIGUE_EMI') then
    	begin

          --recupera el registro de la CVPN
          select *
          into v_registo
          from orga.tevaluacion_desempenio
          where id_proceso_wf = v_parametros.id_proceso_wf_act;

          SELECT
            ew.id_tipo_estado ,
            te.pedir_obs,
            ew.id_estado_wf
           into
            v_id_tipo_estado,
            v_pedir_obs,
            v_id_estado_wf
          FROM wf.testado_wf ew
          INNER JOIN wf.ttipo_estado te ON te.id_tipo_estado = ew.id_tipo_estado
          WHERE ew.id_estado_wf =  v_parametros.id_estado_wf_act;

           -- obtener datos tipo estado siguiente
           select te.codigo into
             v_codigo_estado_siguiente
           from wf.ttipo_estado te
           where te.id_tipo_estado = v_parametros.id_tipo_estado;


           IF  pxp.f_existe_parametro(p_tabla,'id_depto_wf') THEN
           	 v_id_depto = v_parametros.id_depto_wf;
           END IF;

           IF  pxp.f_existe_parametro(p_tabla,'obs') THEN
           	 v_obs = v_parametros.obs;
           ELSE
           	 v_obs='---';
           END IF;

             --configurar acceso directo para la alarma
             v_acceso_directo = '';
             v_clase = '';
             v_parametros_ad = '';
             v_tipo_noti = 'notificacion';
             v_titulo  = 'Emetido';


             IF   v_codigo_estado_siguiente not in('borrador')   THEN

                  v_acceso_directo = '../../../sis_organigrama/vista/certificado_planilla/CertificadoPlanilla.php';
             	  v_clase = 'CertificadoPlanilla';
                  v_parametros_ad = '{filtro_directo:{campo:"cvpn.id_proceso_wf",valor:"'||v_parametros.id_proceso_wf_act::varchar||'"}}';
                  v_tipo_noti = 'notificacion';
                  v_titulo  = 'Notificacion';
             END IF;


             -- hay que recuperar el supervidor que seria el estado inmediato...
            	v_id_estado_actual =  wf.f_registra_estado_wf(v_parametros.id_tipo_estado,
                                                             v_parametros.id_funcionario_wf,
                                                             v_parametros.id_estado_wf_act,
                                                             v_parametros.id_proceso_wf_act,
                                                             p_id_usuario,
                                                             v_parametros._id_usuario_ai,
                                                             v_parametros._nombre_usuario_ai,
                                                             v_id_depto,
                                                             COALESCE(v_registo.nro_tramite,'--')||' Obs:'||v_obs,
                                                             v_acceso_directo ,
                                                             v_clase,
                                                             v_parametros_ad,
                                                             v_tipo_noti,
                                                             v_titulo);



         		IF orga.f_procesar_estados_certificado(p_id_usuario,
           									v_parametros._id_usuario_ai,
                                            v_parametros._nombre_usuario_ai,
                                            v_id_estado_actual,
                                            v_parametros.id_proceso_wf_act,
                                            v_codigo_estado_siguiente) THEN

         			RAISE NOTICE 'PASANDO DE ESTADO';

          		END IF;


          -- si hay mas de un estado disponible  preguntamos al usuario
          v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Se realizo el cambio de estado del Reclamo)');
          v_resp = pxp.f_agrega_clave(v_resp,'operacion','cambio_exitoso');
          v_resp = pxp.f_agrega_clave(v_resp,'v_codigo_estado_siguiente',v_codigo_estado_siguiente);

          -- Devuelve la respuesta
          return v_resp;
        end;

    /*********************************
 	#TRANSACCION:  'EV_ANTE_IME'
 	#DESCRIPCION:	Anterior estado
 	#AUTOR:		MMV
 	#FECHA:		06-06-2017 17:32:59
	***********************************/
    elseif(p_transaccion='EV_ANTE_IME') then
    	begin

        	v_operacion = 'anterior';

            IF  pxp.f_existe_parametro(p_tabla , 'estado_destino')  THEN
               v_operacion = v_parametros.estado_destino;
            END IF;

            --obtenemos los datos del registro de solicitud VPN
            select
                tcv.id_evaluacion_desempenio,
                tcv.id_proceso_wf,
                tcv.estado
            into v_registros_cer
            from orga.tevaluacion_desempenio  tcv
            inner  join wf.tproceso_wf pwf  on  pwf.id_proceso_wf = tcv.id_proceso_wf
            where tcv.id_proceso_wf  = v_parametros.id_proceso_wf;

            --v_id_proceso_wf = v_registros_cvpn.id_proceso_wf;


            IF  v_operacion = 'anterior' THEN
                --------------------------------------------------
                --Retrocede al estado inmediatamente anterior
                -------------------------------------------------
               	--recuperaq estado anterior segun Log del WF
                  SELECT

                     ps_id_tipo_estado,
                     ps_id_funcionario,
                     ps_id_usuario_reg,
                     ps_id_depto,
                     ps_codigo_estado,
                     ps_id_estado_wf_ant
                  into
                     v_id_tipo_estado,
                     v_id_funcionario,
                     v_id_usuario_reg,
                     v_id_depto,
                     v_codigo_estado,
                     v_id_estado_wf_ant
                  FROM wf.f_obtener_estado_ant_log_wf(v_parametros.id_estado_wf);

                  select
                    ew.id_proceso_wf
                  into
                    v_id_proceso_wf
                  from wf.testado_wf ew
                  where ew.id_estado_wf= v_id_estado_wf_ant;
            END IF;


			 v_acceso_directo = '../../../sis_organigrama/vista/certificado_planilla/CertificadoPlanilla.php';
             v_clase = 'CertificadoPlanilla';
             v_parametros_ad = '{filtro_directo:{campo:"cvpn.id_proceso_wf",valor:"'||v_id_proceso_wf::varchar||'"}}';
             v_tipo_noti = 'notificacion';
             v_titulo  = 'Visto Bueno';

              -- registra nuevo estado

              v_id_estado_actual = wf.f_registra_estado_wf(
                    v_id_tipo_estado,                --  id_tipo_estado al que retrocede
                    v_id_funcionario,                --  funcionario del estado anterior
                    v_parametros.id_estado_wf,       --  estado actual ...
                    v_id_proceso_wf,                 --  id del proceso actual
                    p_id_usuario,                    -- usuario que registra
                    v_parametros._id_usuario_ai,
                    v_parametros._nombre_usuario_ai,
                    v_id_depto,                       --depto del estado anterior
                    '[RETROCESO] '|| v_parametros.obs,
                    v_acceso_directo,
                    v_clase,
                    v_parametros_ad,
                    v_tipo_noti,
                    v_titulo);

                IF  not orga.f_ant_estado_certificado (p_id_usuario,
                                                       v_parametros._id_usuario_ai,
                                                       v_parametros._nombre_usuario_ai,
                                                       v_id_estado_actual,
                                                       v_parametros.id_proceso_wf,
                                                       v_codigo_estado) THEN

                   raise exception 'Error al retroceder estado';

                END IF;

                v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Se realizo volvio al anterior estado)');
                v_resp = pxp.f_agrega_clave(v_resp,'operacion','cambio_exitoso');

              --Devuelve la respuesta
                return v_resp;

        end;
           /*********************************
          #TRANSACCION:  'EVD_REV_IME'
          #DESCRIPCION:	Control revision
          #AUTOR:		MMV
          #FECHA:		14-06-2017
          ***********************************/
        elsif (p_transaccion='EVD_REV_IME')then

            begin

    		select ev.revisado into v_revisado
            from orga.tevaluacion_desempenio ev
            where ev.id_evaluacion_desempenio = v_parametros.id_evaluacion_desempenio;

              if v_revisado = 'si' then

                       update orga.tevaluacion_desempenio set
                       revisado = 'no'
                       where id_evaluacion_desempenio = v_parametros.id_evaluacion_desempenio;

              end if;

        	  if v_revisado = 'no' then

                    update orga.tevaluacion_desempenio set
                    revisado = 'si'
                    where id_evaluacion_desempenio = v_parametros.id_evaluacion_desempenio;

         		end if;

                --Definicion de la respuesta
                v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Revision con exito (id_evaluacion_desempenio'||v_parametros.id_evaluacion_desempenio||')');
                v_resp = pxp.f_agrega_clave(v_resp,'id_evaluacion_desempenio',v_parametros.id_evaluacion_desempenio::varchar);
                --Devuelve la respuesta
                return v_resp;

            end;
     	 /*********************************
          #TRANSACCION:  'EVD_REV_ACT'
          #DESCRIPCION:	Control revision
          #AUTOR:		MMV
          #FECHA:		14-06-2017
          ***********************************/
        elsif (p_transaccion='EVD_REV_ACT')then

            begin

            select 	de.nro_tramite,
                    de.id_proceso_wf,
                    de.id_funcionario,
                    de.codigo,
                    de.estado,
                    de.nota,
                    de.id_uo_funcionario,
                    de.id_estado_wf,
                    de.descripcion,
                    de.estado_reg,
                    de.id_usuario_ai,
                    de.id_usuario_reg,
                    de.fecha_reg,
                   	de.fecha_mod,
                    de.id_usuario_mod,
                    de.gestion,
                    de.cargo_evaluado,
                    de.cite,
                    de.id_uo,
                    de.fecha_solicitud,
                    de.id_evaluacion_desempenio,
                    de.recomendacion,
                    de.correo,
                    de.fecha_correo,
                    de.plantilla,
                    de.ip,
                    de.fecha_receptor
                    into
                    v_registo
                    from orga.tevaluacion_desempenio de
                    where de.id_evaluacion_desempenio = v_parametros.id_evaluacion_desempenio;


        if v_registo.nota <> v_parametros.nota then

            INSERT INTO orga.tevaluacion_desempenio_historico ( 	id_usuario_reg,
                                                                id_usuario_mod,
                                                                fecha_reg,
                                                                fecha_mod,
                                                                estado_reg,
                                                                id_funcionario,
                                                                id_uo_funcionario,
                                                                codigo,
                                                                nota,
                                                                descripcion,
                                                                id_proceso_wf,
                                                                id_estado_wf,
                                                                nro_tramite,
                                                                estado,
                                                                gestion,
                                                                cargo_evaluado,
                                                                fecha_solicitud,
                                                                id_evaluacion_desempenio_padre,
                                                                cite,
                                                                recomendacion,
                                                                id_uo,
                                                                correo,
                                                                fecha_correo,
                                                                plantilla,
                                                                ip,
                                                                fecha_receptor
                                                              )
                                                              VALUES (
                                                                p_id_usuario,
                                                                v_registo.id_usuario_mod,
                                                                v_registo.fecha_reg,
                                                                v_registo.fecha_mod,
                                                                v_registo.estado_reg,
                                                                v_registo.id_funcionario,
                                                                v_registo.id_uo_funcionario,
                                                                v_registo.codigo,
                                                                v_registo.nota,
                                                                v_registo.descripcion,
                                                                v_registo.id_proceso_wf,
                                                                v_registo.id_estado_wf,
                                                                v_registo.nro_tramite,
                                                                v_registo.estado,
                                                                v_registo.gestion,
                                                                v_registo.cargo_evaluado,
                                                                v_registo.fecha_solicitud,
                                                              	v_registo.id_evaluacion_desempenio,
                                                                v_registo.cite,
                                                                v_registo.recomendacion,
                                                                v_registo.id_uo,
                                                                v_registo.correo,
                                                                v_registo.fecha_correo,
                                                                v_registo.plantilla,
                                                                v_registo.ip,
                                                                v_registo.fecha_receptor );

            delete from orga.tevaluacion_desempenio de
            where de.id_proceso_wf = v_registo.id_proceso_wf;

        	select g.id_gestion, g.gestion
           into v_gestion, anho
           from param.tgestion g
           where g.gestion = EXTRACT(YEAR FROM current_date);

           select tp.codigo,pm.id_proceso_macro
          into v_codigo_tipo_proceso, v_id_proceso_macro
           from  wf.tproceso_macro pm
           inner join wf.ttipo_proceso tp on tp.id_proceso_macro = pm.id_proceso_macro
           where pm.codigo='EDF' and tp.estado_reg = 'activo' and tp.inicio = 'si' ;

                 -- inciar el tramite en el sistema de WF
           SELECT
                 ps_num_tramite ,
                 ps_id_proceso_wf ,
                 ps_id_estado_wf ,
                 ps_codigo_estado
              into
                 v_nro_tramite,
                 v_id_proceso_wf,
                 v_id_estado_wf,
                 v_codigo_estado

            FROM wf.f_inicia_tramite(
                 p_id_usuario,
                 v_parametros._id_usuario_ai,
                 v_parametros._nombre_usuario_ai,
                 v_gestion,
                 v_codigo_tipo_proceso,
                 NULL,
                 NULL,
                 'Evaluación de Desempeño',
                 v_codigo_tipo_proceso);


            select ca.id_uo into v_id_uo
            from orga.vfuncionario_cargo ca
            where ca.id_funcionario = v_parametros.id_funcionario and (ca.fecha_finalizacion is null or ca.fecha_finalizacion >= now()::date);


            --Sentencia de la insercion
        	insert into orga.tevaluacion_desempenio(
			nro_tramite,
			id_proceso_wf,
			id_funcionario,
			codigo,
			estado,
			nota,
			id_uo_funcionario,
			id_estado_wf,
			descripcion,
			estado_reg,
			id_usuario_ai,
			id_usuario_reg,
			fecha_reg,
			usuario_ai,
			fecha_mod,
			id_usuario_mod,
            gestion,
            cargo_evaluado,
            cite,
            id_uo,
            estado_modificado
          	) values(
			v_nro_tramite,
			v_id_proceso_wf,
			v_parametros.id_funcionario,
			v_parametros.codigo,
			v_codigo_estado,
			v_parametros.nota,
			v_parametros.id_uo_funcionario,
			v_id_estado_wf,
			v_parametros.descripcion,
			'activo',
			v_parametros._id_usuario_ai,
			p_id_usuario,
			now(),
			v_parametros._nombre_usuario_ai,
			null,
			null,
            v_parametros.gestion,
            v_parametros.cargo_memo,
             v_registo.cite,
            v_id_uo,
            'modificado'
			)RETURNING id_evaluacion_desempenio into v_id_evaluacion_desempenio;
		end if;
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Evaluacion Desempenio almacenado(a) con exito (id_evaluacion_desempenio'||v_id_evaluacion_desempenio||')');
            v_resp = pxp.f_agrega_clave(v_resp,'id_evaluacion_desempenio',v_id_evaluacion_desempenio::varchar);


            --Devuelve la respuesta
            return v_resp;

    end;
    /*********************************
 	#TRANSACCION:  'EVD_GE_ACT'
 	#DESCRIPCION:	recuperar uo funcionario
 	#AUTOR:		miguel.mamani
 	#FECHA:		24-02-2018 20:33:35
	***********************************/

	elsif(p_transaccion='EVD_GE_ACT')then

		begin
              CREATE TEMPORARY TABLE temp_evaluacion (
                                      funcionaro varchar
                                      )ON COMMIT DROP;
         	v_codigo = v_parametros.funcionarios:: JSON->>'codigo';
            v_mensaje = v_parametros.funcionarios:: JSON->>'descripcion';
            v_funcionarios = v_parametros.funcionarios:: JSON->>'objeto';
            v_codigo = v_parametros.funcionarios:: JSON->>'codigo';
              IF v_codigo = '0' and  (v_funcionarios is null or v_funcionarios = '')  then
            	raise exception '%',v_mensaje;
            end if;
           
           
           IF v_codigo = '0' and v_mensaje = 'Falta evaluar' then
            	
                
    		for v_json IN (SELECT json_array_elements(v_funcionarios :: JSON))
            loop 
             v_evaluado = v_json.json_array_elements::json;
             v_id_funcionario = v_evaluado::json->>'id_funcionario';
             
             select f.desc_funcionario1 
              	into v_datos
             	from orga.vfuncionario f 
             	where f.id_funcionario = v_id_funcionario;
                insert  into temp_evaluacion (funcionaro)select v_datos.desc_funcionario1::varchar;
             end loop;
             
            select pxp.list(funcionaro)
             INTO v_mjs
             from temp_evaluacion ;
             raise exception 'Falta evaluar los Funcionaros: %',v_mjs;
             
           
          else -----

           for v_json IN (SELECT json_array_elements(v_funcionarios :: JSON))
            loop
             v_evaluado = v_json.json_array_elements::json;

             v_id_funcionario = v_evaluado::json->>'id_funcionario';
             v_id_cargo = v_evaluado::json->>'cargo_id';
             v_nota = v_evaluado::json->>'nota';
             v_descripcion = v_evaluado::json->>'descripcion';

            select g.id_gestion, g.gestion
                   into v_gestion, anho
                   from param.tgestion g
                   where g.gestion = EXTRACT(YEAR FROM current_date);

           select tp.codigo,pm.id_proceso_macro
          into v_codigo_tipo_proceso, v_id_proceso_macro
           from  wf.tproceso_macro pm
           inner join wf.ttipo_proceso tp on tp.id_proceso_macro = pm.id_proceso_macro
           where pm.codigo='MED' and tp.estado_reg = 'activo' and tp.inicio = 'si' ;

                 -- inciar el tramite en el sistema de WF
          SELECT
                 ps_num_tramite ,
                 ps_id_proceso_wf ,
                 ps_id_estado_wf ,
                 ps_codigo_estado
              into
                 v_nro_tramite,
                 v_id_proceso_wf,
                 v_id_estado_wf,
                 v_codigo_estado

            FROM wf.f_inicia_tramite(
                 p_id_usuario,
                 v_parametros._id_usuario_ai,
                 v_parametros._nombre_usuario_ai,
                 v_gestion,
                 v_codigo_tipo_proceso,
                 NULL,
                 NULL,
                 'Evaluación de Desempeño',
                 v_codigo_tipo_proceso);

            select ger.id_uo,
            	   ca.id_uo_funcionario,
                   ca.nombre_cargo

            into v_datos
            from orga.vfuncionario_cargo ca
            inner join orga.tuo ger ON ger.id_uo = orga.f_get_uo_gerencia(ca.id_uo, NULL::integer, NULL::date)
            where ca.id_funcionario = v_id_funcionario  and (ca.fecha_finalizacion is null or ca.fecha_finalizacion >= now()::date);

		if exists (select 1
            from orga.tevaluacion_desempenio de
            where de.id_funcionario = v_id_funcionario and de.gestion = v_parametros.gestion) then
 /*
			update orga.tevaluacion_desempenio  set
            cargo_actual_memo=v_datos.nombre_cargo,
            cargo_evaluado=(select c.nombre
                              from orga.ttemporal_cargo  c
                              where c.id_temporal_cargo =v_id_cargo),
            id_cargo_evaluado=v_id_cargo           
            where id_funcionario = v_id_funcionario and gestion = v_parametros.gestion;*/

             select 	de.nro_tramite,
                    de.id_proceso_wf,
                    de.id_funcionario,
                    de.codigo,
                    de.estado,
                    de.nota,
                    de.id_uo_funcionario,
                    de.id_estado_wf,
                    de.descripcion,
                    de.estado_reg,
                    de.id_usuario_ai,
                    de.id_usuario_reg,
                    de.fecha_reg,
                   	de.fecha_mod,
                    de.id_usuario_mod,
                    de.gestion,
                    de.cargo_evaluado,
                    de.cite,
                    de.id_uo,
                    de.fecha_solicitud,
                    de.id_evaluacion_desempenio,
                    de.recomendacion,
                    de.correo,
                    de.fecha_correo,
                    de.plantilla,
                    de.ip,
                    de.fecha_receptor,
                    de.cargo_actual_memo
                    into
                    v_registo
                    from orga.tevaluacion_desempenio de
                    where de.id_funcionario = v_id_funcionario and de.gestion = v_parametros.gestion;


        if v_registo.nota <> v_nota then

            INSERT INTO orga.tevaluacion_desempenio_historico ( 	id_usuario_reg,
                                                                id_usuario_mod,
                                                                fecha_reg,
                                                                fecha_mod,
                                                                estado_reg,
                                                                id_funcionario,
                                                                id_uo_funcionario,
                                                                codigo,
                                                                nota,
                                                                descripcion,
                                                                id_proceso_wf,
                                                                id_estado_wf,
                                                                nro_tramite,
                                                                estado,
                                                                gestion,
                                                                cargo_evaluado,
                                                                fecha_solicitud,
                                                                id_evaluacion_desempenio_padre,
                                                                cite,
                                                                recomendacion,
                                                                id_uo,
                                                                correo,
                                                                fecha_correo,
                                                                plantilla,
                                                                ip,
                                                                fecha_receptor
                                                              )
                                                              VALUES (
                                                                p_id_usuario,
                                                                v_registo.id_usuario_mod,
                                                                v_registo.fecha_reg,
                                                                v_registo.fecha_mod,
                                                                v_registo.estado_reg,
                                                                v_registo.id_funcionario,
                                                                v_registo.id_uo_funcionario,
                                                                v_registo.codigo,
                                                                v_registo.nota,
                                                                v_registo.descripcion,
                                                                v_registo.id_proceso_wf,
                                                                v_registo.id_estado_wf,
                                                                v_registo.nro_tramite,
                                                                v_registo.estado,
                                                                v_registo.gestion,
                                                                v_registo.cargo_evaluado,
                                                                v_registo.fecha_solicitud,
                                                              	v_registo.id_evaluacion_desempenio,
                                                                v_registo.cite,
                                                                v_registo.recomendacion,
                                                                v_registo.id_uo,
                                                                v_registo.correo,
                                                                v_registo.fecha_correo,
                                                                v_registo.plantilla,
                                                                v_registo.ip,
                                                                v_registo.fecha_receptor
                                                              );

            delete from orga.tevaluacion_desempenio de
            where de.id_proceso_wf = v_registo.id_proceso_wf;

        	select g.id_gestion, g.gestion
           into v_gestion, anho
           from param.tgestion g
           where g.gestion = EXTRACT(YEAR FROM current_date);

           select tp.codigo,pm.id_proceso_macro
          into v_codigo_tipo_proceso, v_id_proceso_macro
           from  wf.tproceso_macro pm
           inner join wf.ttipo_proceso tp on tp.id_proceso_macro = pm.id_proceso_macro
           where pm.codigo='MED' and tp.estado_reg = 'activo' and tp.inicio = 'si' ;

                 -- inciar el tramite en el sistema de WF
           SELECT
                 ps_num_tramite ,
                 ps_id_proceso_wf ,
                 ps_id_estado_wf ,
                 ps_codigo_estado
              into
                 v_nro_tramite,
                 v_id_proceso_wf,
                 v_id_estado_wf,
                 v_codigo_estado

            FROM wf.f_inicia_tramite(
                 p_id_usuario,
                 v_parametros._id_usuario_ai,
                 v_parametros._nombre_usuario_ai,
                 v_gestion,
                 v_codigo_tipo_proceso,
                 NULL,
                 NULL,
                 'Evaluación de Desempeño',
                 v_codigo_tipo_proceso);



            --Sentencia de la insercion
        	insert into orga.tevaluacion_desempenio(
			nro_tramite,
			id_proceso_wf,
			id_funcionario,
			codigo,
			estado,
			nota,
			id_uo_funcionario,
			id_estado_wf,
			descripcion,
			estado_reg,
			id_usuario_ai,
			id_usuario_reg,
			fecha_reg,
			usuario_ai,
			fecha_mod,
			id_usuario_mod,
            gestion,
            cargo_evaluado,
            cite,
            id_uo,
            estado_modificado,
            cargo_actual_memo,
            id_cargo_evaluado            
          	) values(
			v_nro_tramite,
			v_id_proceso_wf,
			v_id_funcionario,
			'0',
			v_codigo_estado,
			v_nota,
			v_datos.id_uo_funcionario,
			v_id_estado_wf,
			v_descripcion,
			'activo',
			v_parametros._id_usuario_ai,
			p_id_usuario,
			now(),
			v_parametros._nombre_usuario_ai,
			null,
			null,
            v_parametros.gestion,
            --v_datos.nombre_cargo,
            v_registo.cargo_evaluado,
             v_registo.cite,
            v_datos.id_uo,
            'modificado',
            v_registo.cargo_actual_memo,
            v_id_cargo            
			);
		end if;


        else
        --Sentencia de la insercion
        	insert into orga.tevaluacion_desempenio(
			nro_tramite,
			id_proceso_wf,
			id_funcionario,
			codigo,
			estado,
			nota,
			id_uo_funcionario,
			id_estado_wf,
			descripcion,
			estado_reg,
			id_usuario_ai,
			id_usuario_reg,
			fecha_reg,
			usuario_ai,
			fecha_mod,
			id_usuario_mod,
            gestion,
            cargo_actual_memo,
            cite,
            id_uo,
            cargo_evaluado,
            id_cargo_evaluado
          	) select
			v_nro_tramite,
			v_id_proceso_wf,
			v_id_funcionario,
			'0',
			v_codigo_estado,
			v_nota,
			v_datos.id_uo_funcionario,
			v_id_estado_wf,
			v_descripcion,
			'activo',
			v_parametros._id_usuario_ai,
			p_id_usuario,
			now(),
			v_parametros._nombre_usuario_ai,
			null,
			null,
            v_parametros.gestion,
            v_datos.nombre_cargo,
            'OB.AH.MED.'||lpad(COALESCE(nextval('orga.cite'::regclass), 0)::varchar,3,'0')||'.'||anho::varchar as cite,
	         v_datos.id_uo,
             (select c.nombre
        from orga.ttemporal_cargo  c --orga.tcargo c original
        --where c.id_cargo = v_id_cargo and c.estado_reg = 'activo'),
        where c.id_temporal_cargo =v_id_cargo and c.estado_reg='activo'),
        v_id_cargo;


          end if;

            end loop;
            end if;
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Exito');
            v_resp = pxp.f_agrega_clave(v_resp,'v_id_uo',v_id_uo::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;
     /*********************************
 	#TRANSACCION:  'EVD_MEM_COR'
 	#DESCRIPCION:	Enviar correos
 	#AUTOR:		miguel.mamani
 	#FECHA:		24-02-2018 20:33:35
	***********************************/

		elsif(p_transaccion='EVD_MEM_COR')then

            begin

    IF  pxp.f_existe_parametro(p_tabla,'id_uo') THEN
--solo los q ue no fueron enviado si estado es borrador
            for v_registros_cer in (select f.id_funcionario,
            								initcap(f.desc_funcionario1) as nombre_funionaro,
                                            evd.gestion,
            								evd.id_proceso_wf,
              								f.email_empresa	,
                                             (CASE
        WHEN pe.genero::text = ANY (ARRAY['varon'::character varying,'VARON'::character varying, 'Varon'::character varying]::text[]) THEN 'Estimado: '::text
        WHEN pe.genero::text = ANY (ARRAY['mujer'::character varying,'MUJER'::character varying, 'Mujer'::character varying]::text[]) THEN 'Estimada: '::text
        ELSE ''::text
        END::character varying) AS genero,evd.estado
            					from orga.tevaluacion_desempenio evd
                                inner join orga.vfuncionario_cargo f on f.id_funcionario = evd.id_funcionario and (f.fecha_finalizacion is null or f.fecha_asignacion>=now()::date)
								inner join orga.vfuncionario_persona p on p.id_funcionario = f.id_funcionario
        						inner join segu.vpersona2 pe on pe.id_persona = p.id_persona
                                where evd.id_uo = v_parametros.id_uo and evd.estado = 'borrador' and evd.gestion = v_parametros.gestion and (case
       	  																		 when v_parametros.rango = '0_70' then
                                                                                  evd.nota >= 0 and evd.nota <= 70
                                                                                 when v_parametros.rango = '71_80' then
                                                                                  evd.nota >= 71 and evd.nota <= 80
                                                                                 when v_parametros.rango = '81_90' then
                                                                                  evd.nota >= 81 and evd.nota <= 90
                                                                                 else
                                                                                  evd.nota >= 91 and evd.nota <= 100
                                                                                 end )  )loop


        select initcap (fun.desc_funcionario1) as nombre_funionaro,
        		initcap ( fun.descripcion_cargo) as nombre_cargo
                into
                v_emisor
        from segu.tusuario u
        inner join orga.vfuncionario_persona p on p.id_persona = u.id_persona
        inner join orga.vfuncionario_cargo fun on fun.id_funcionario = p.id_funcionario
        and (fun.fecha_finalizacion is null or fun.fecha_finalizacion >= now()::date)
        where u.id_usuario = p_id_usuario;

                v_plantilla='<p><span style="color: #3E3BF4;">' ||v_registros_cer.genero||''||regexp_replace (v_registros_cer.nombre_funionaro, '[^a-zA-Z0-9]', ' ', 'g')||'</span></p>
                            <p><span style="color: #3E3BF4;">Enviamos para su conocimiento el enlace para ver el Memorándum de <b>Evaluación de Desempeño</b> correspondiente a la <b>gestión '||v_registros_cer.gestion||'</b></span></p>
                            <p><span style="color: #3E3BF4;">La lectura y visualizaci&oacute;n de este mensaje tiene caracter obligatorio.</span></p>
                            <p><a href="http://'||v_parametros.link||'sis_organigrama/control/Memo.php?proceso='||v_registros_cer.id_proceso_wf||'"><b>Enlace para ver el Memorandum</b></a></p>
                           	<p><span style="color: #3E3BF4;">En caso de tener alguna duda  favor remitirse al Departamento de Recursos Humanos y/o a su inmediato superior.</span></p>
                			<p><span style="color: #3E3BF4;">Sin otro particular, un cordial saludo.</span></p>
                            <p><img src="../../../sis_organigrama/media/RRHH.jpeg">';


             v_id_alarma = (select param.f_inserta_alarma_dblink (p_id_usuario,'Evaluacion de Desempeño Gestión '||v_registros_cer.gestion::varchar,v_plantilla::text,v_registros_cer.email_empresa::varchar));


     --- (v_correo = null)then
    /*if exists(select 1
      			from wf.tdocumento_wf dw
     			where dw.id_proceso_wf = v_registros_cer.id_proceso_wf and
                 dw.id_tipo_documento = 408) THEN

     else*/
     
        INSERT INTO  wf.tdocumento_wf (
              id_usuario_reg,
              fecha_reg,
              estado_reg,
              id_tipo_documento,
              id_proceso_wf,
              demanda,
              obs,
              momento,
              chequeado

            )
          VALUES (
            p_id_usuario,
            now(),
            'activo',
            408::integer,
            v_registros_cer.id_proceso_wf,
            'si',
            'insertado manualmente',
            'exigir',
            'no'
          );
          
           update orga.tevaluacion_desempenio set
                estado = 'enviado',
                fecha_correo = now()::date,
                correo = v_registros_cer.email_empresa,
                id_usuario_mod = p_id_usuario,
                plantilla = v_plantilla
                where id_proceso_wf = v_registros_cer.id_proceso_wf;
     	--end if;
        
     end loop;
	ELSE
		if 	v_parametros.rango = '71_80' then 
					select evd.recomendacion
                     into v_registro_eva                    
                    from orga.tevaluacion_desempenio evd
                    where evd.id_funcionario = v_parametros.id_funcionario and evd.gestion=v_parametros.gestion;

         	if  v_registro_eva is null or v_registro_eva = ''then            
            	 raise exception 'El Funcionario no tiene recomendacion';                            
            end if; 
		end if;                   

                            select f.id_funcionario,
                                    initcap(f.desc_funcionario1) as nombre_funionaro,
                                    evd.gestion,
                                    evd.id_proceso_wf,
                                    f.email_empresa	,
                                     (CASE
                                WHEN pe.genero::text = ANY (ARRAY['varon'::character varying,'VARON'::character varying, 'Varon'::character varying]::text[]) THEN 'Estimado: '::text
                                WHEN pe.genero::text = ANY (ARRAY['mujer'::character varying,'MUJER'::character varying, 'Mujer'::character varying]::text[]) THEN 'Estimada: '::text
                                ELSE ''::text
                                END::character varying) AS genero,
                                evd.estado,
                                evd.recomendacion
                                into
                                v_registros_cer
            					from orga.tevaluacion_desempenio evd
                                inner join orga.vfuncionario_cargo f on f.id_uo_funcionario = evd.id_uo_funcionario --f.id_funcionario = evd.id_funcionario and (f.fecha_finalizacion is null or f.fecha_asignacion>=now()::date)
								inner join orga.vfuncionario_persona p on p.id_funcionario = f.id_funcionario
        						inner join segu.vpersona2 pe on pe.id_persona = p.id_persona
                                where evd.id_funcionario = v_parametros.id_funcionario and evd.gestion = v_parametros.gestion;
		
        if v_registros_cer.estado = 'enviado' then
        	raise exception 'El correo ya fue enviado no puede ser enviado nuevamente';
        elsif v_registros_cer.estado = 'revisado' then
			raise exception 'El correo ya fue revisado no puede ser enviado nuevamente';        	    
		end if;
        
        select initcap (fun.desc_funcionario1) as nombre_funionaro,
        		initcap ( fun.descripcion_cargo) as nombre_cargo
                into
                v_emisor
        from segu.tusuario u
        inner join orga.vfuncionario_persona p on p.id_persona = u.id_persona
        inner join orga.vfuncionario_cargo fun on fun.id_funcionario = p.id_funcionario
        and (fun.fecha_finalizacion is null or fun.fecha_finalizacion >= now()::date)
        where u.id_usuario = p_id_usuario;
 
                v_plantilla='<p><span style="color: #150296;">'||v_registros_cer.genero||''||regexp_replace (v_registros_cer.nombre_funionaro,'[^a-zA-Z0-9]', ' ', 'g')||'</span></p>
                            <p><span style="color: #150296;">Enviamos para su conocimiento el enlace para ver el Memorándum de <b>Evaluación de Desempeño</b> correspondiente a la <b>gestión '||v_registros_cer.gestion||'</b></span></p>
                            <p><span style="color: #150296;">La lectura y visualizaci&oacute;n de este mensaje tiene caracter obligatorio.</span></p>
                            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://'||v_parametros.link||'sis_organigrama/control/Memo.php?proceso='||v_registros_cer.id_proceso_wf||'"><span style="color: #150296;"><b>Enlace para ver el Memorandum</b></span></a></p>
                           	<p><span style="color: #150296;">En caso de tener alguna duda  favor remitirse al Departamento de Recursos Humanos y/o a su inmediato superior.</span></p>
                			<p><span style="color: #150296;">Sin otro particular, un cordial saludo.</span></p>
                            <p><img src="../../../sis_organigrama/media/RRHH.jpeg">';




             v_id_alarma = (select param.f_inserta_alarma_dblink (p_id_usuario,'Evaluacion de Desempeño Gestión '||v_registros_cer.gestion::varchar,v_plantilla::text,v_registros_cer.email_empresa::varchar));



     --if (v_correo = null)then
    /*if exists( 	select 1
      			from wf.tdocumento_wf dw
     			where dw.id_proceso_wf = v_registros_cer.id_proceso_wf and dw.id_tipo_documento = 408) THEN
     raise notice 'Ya existe';

     else*/

        INSERT INTO  wf.tdocumento_wf (
              id_usuario_reg,
              fecha_reg,
              estado_reg,
              id_tipo_documento,
              id_proceso_wf,
              demanda,
              obs,
              momento,
              chequeado

            )
          VALUES (
            p_id_usuario,
            now(),
            'activo',
            408::integer,
            v_registros_cer.id_proceso_wf,
            'si',
            'insertado manualmente',
            'exigir',
            'no'
          );
     		--end if;

         update orga.tevaluacion_desempenio set
                estado = 'enviado',
                fecha_correo = now()::date,
                correo = v_registros_cer.email_empresa,
                id_usuario_mod = p_id_usuario,
                plantilla = v_plantilla
                where id_proceso_wf = v_registros_cer.id_proceso_wf;
       --end if;                      
    END IF;

    --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Exito');
            v_resp = pxp.f_agrega_clave(v_resp,'v_id_uo',v_id_uo::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

       /*********************************
 	#TRANSACCION:  'EVD_REE_IME'
 	#DESCRIPCION:	Confirmacion de correo
 	#AUTOR:		miguel.mamani
 	#FECHA:		24-02-2018 20:33:35
	***********************************/

		elsif(p_transaccion='EVD_REE_IME')then

            begin
           -- raise exception 'holaa %',v_parametros.ip;
            update orga.tevaluacion_desempenio set
            estado = 'revisado',
            ip = v_parametros.ip,
            fecha_receptor = now()
            where id_proceso_wf = v_parametros.id_proceso_wf;

            if exists( 	select 1
      			from wf.tdocumento_wf dw
     			where dw.id_proceso_wf = v_parametros.id_proceso_wf and dw.id_tipo_documento = 409) THEN
     raise notice 'Ya existe';

     else

        INSERT INTO  wf.tdocumento_wf (
              id_usuario_reg,
              fecha_reg,
              estado_reg,
              id_tipo_documento,
              id_proceso_wf,
              demanda,
              obs,
              momento,
              chequeado

            )
          VALUES (
            p_id_usuario,
            now(),
            'activo',
            409::integer,
            v_parametros.id_proceso_wf,
            'si',
            'insertado manualmente',
            'exigir',
            'no'
          );
     end if;

    		--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Exito');
            v_resp = pxp.f_agrega_clave(v_resp,'id_proceso_wf',v_parametros.id_proceso_wf::varchar);

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
COST 100;