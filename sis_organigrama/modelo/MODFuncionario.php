<?php
/***
Nombre: 	MODFuncionario.php
Proposito: Clase de Modelo, que contiene la definicion y llamada a funciones especificas relacionadas
a la tabla tfuncionario del esquema RHUM
Autor:		Kplian
Fecha:		04/06/2011
 */
class MODFuncionario extends MODbase{

    function __construct(CTParametro $pParam){
        parent::__construct($pParam);

    }

    function listarFuncionario(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='orga.ft_funcionario_sel';// nombre procedimiento almacenado
        $this->transaccion='RH_FUNCIO_SEL';//nombre de la transaccion
        $this->tipo_procedimiento='SEL';//tipo de transaccion

        $this->setParametro('tipo','tipo','varchar');
        $this->setParametro('fecha','fecha','date');
        $this->setParametro('id_uo','id_uo','integer');
        //$this->setParametro('estado_func','estado_func','varchar');
        //Definicion de la lista del resultado del query

        //defino varialbes que se captran como retornod e la funcion

        $this->captura('id_funcionario','integer');
        $this->captura('codigo','varchar');
        $this->captura('estado_reg','varchar');
        $this->captura('fecha_reg','timestamp');
        $this->captura('id_persona','integer');
        $this->captura('id_usuario_reg','integer');
        $this->captura('fecha_mod','timestamp');
        $this->captura('id_usuario_mod','integer');
        $this->captura('email_empresa','varchar');
        $this->captura('interno','varchar');
        $this->captura('fecha_ingreso','date');
        $this->captura('desc_person','text');
        $this->captura('usr_reg','varchar');
        $this->captura('usr_mod','varchar');
        $this->captura('ci','varchar');
        $this->captura('num_documento','integer');
        $this->captura('telefono1','varchar');
        $this->captura('celular1','varchar');
        $this->captura('correo','varchar');
        $this->captura('telefono_ofi','varchar');
        $this->captura('antiguedad_anterior','integer');

        $this->captura('estado_civil','varchar');
        $this->captura('genero','varchar');
        $this->captura('fecha_nacimiento','date');
        $this->captura('id_lugar','integer');
        $this->captura('nombre_lugar','varchar');
        $this->captura('nacionalidad','varchar');
        $this->captura('discapacitado','varchar');
        $this->captura('carnet_discapacitado','varchar');
        $this->captura('id_biometrico','int4');
        $this->captura('nombre_archivo','varchar');
        $this->captura('extension','varchar');
        $this->captura('telefono2','varchar');
        $this->captura('celular2','varchar');
        $this->captura('nombre','varchar');
        $this->captura('ap_materno','varchar');
        $this->captura('ap_paterno','varchar');
        $this->captura('tipo_documento','varchar');
        $this->captura('expedicion','varchar');
        $this->captura('direccion','varchar');
        $this->captura('es_tutor','varchar');
        $this->captura('fecha_asignacion','date');
        $this->captura('fecha_finalizacion','date');
        $this->captura('nombre_cargo','varchar');
        $this->captura('nombre_oficina','varchar');
        $this->captura('nombre_lugar_ofi','varchar');
        //$this->captura('oficina_pais','varchar');


        //Ejecuta la funcion
        $this->armarConsulta();
        //echo $this->getConsulta(); exit;
        $this->ejecutarConsulta();
        return $this->respuesta;

    }

    function listarSinAsignacionFuncionario(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='orga.ft_funcionario_sel';// nombre procedimiento almacenado
        $this->transaccion='RH_FUN_ASIG_SEL';//nombre de la transaccion
        $this->tipo_procedimiento='SEL';//tipo de transaccion

        $this->setParametro('tipo','tipo','varchar');
        $this->setParametro('fecha','fecha','date');
        $this->setParametro('id_uo','id_uo','integer');

        //defino variables que se capturan como retorno de la funcion

        $this->captura('id_funcionario','integer');
        $this->captura('codigo','varchar');
        $this->captura('estado_reg','varchar');
        $this->captura('fecha_reg','timestamp');
        $this->captura('id_persona','integer');
        $this->captura('id_usuario_reg','integer');
        $this->captura('fecha_mod','timestamp');
        $this->captura('id_usuario_mod','integer');
        $this->captura('email_empresa','varchar');
        $this->captura('interno','varchar');
        $this->captura('fecha_ingreso','date');
        $this->captura('desc_person','text');
        $this->captura('usr_reg','varchar');
        $this->captura('usr_mod','varchar');
        $this->captura('ci','varchar');
        $this->captura('num_documento','integer');
        $this->captura('telefono1','varchar');
        $this->captura('celular1','varchar');
        $this->captura('correo','varchar');
        $this->captura('telefono_ofi','varchar');
        $this->captura('antiguedad_anterior','integer');

        $this->captura('estado_civil','varchar');
        $this->captura('genero','varchar');
        $this->captura('fecha_nacimiento','date');
        $this->captura('id_lugar','integer');
        $this->captura('nombre_lugar','varchar');
        $this->captura('nacionalidad','varchar');
        $this->captura('discapacitado','varchar');
        $this->captura('carnet_discapacitado','varchar');
        $this->captura('id_biometrico','int4');
        $this->captura('nombre_archivo','varchar');
        $this->captura('extension','varchar');

        //Ejecuta la funcion
        $this->armarConsulta();
        //echo $this->getConsulta(); exit;
        $this->ejecutarConsulta();
        return $this->respuesta;
    }

    function getDatosFuncionario(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='orga.ft_funcionario_sel';// nombre procedimiento almacenado
        $this->transaccion='RH_GETDAFUN_SEL';//nombre de la transaccion
        $this->tipo_procedimiento='SEL';//tipo de transaccion


        //defino varialbes que se captran como retornod e la funcion

        $this->captura('id_funcionario','integer');
        $this->captura('nombre_funcionario','varchar');
        $this->captura('cargo','varchar');
        $this->captura('telefonos_corporativos','varchar');
        $this->captura('correo_corporativo','varchar');
        $this->captura('nombre','varchar');
        $this->captura('apellido','varchar');
        $this->captura('oficina','varchar');
        $this->captura('lugar','varchar');
        $this->captura('gerencia','varchar');
        $this->captura('direccion','varchar');
        $this->captura('celular1','varchar');
        $this->captura('interno','varchar');


        //Ejecuta la funcion
        $this->armarConsulta();
        //echo $this->getConsulta(); exit;
        $this->ejecutarConsulta();
        return $this->respuesta;

    }

    function getCumpleaneros(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='orga.ft_funcionario_sel';// nombre procedimiento almacenado
        $this->transaccion='RH_GETCUMPLEA_SEL';//nombre de la transaccion
        $this->tipo_procedimiento='SEL';//tipo de transaccion


        //defino varialbes que se captran como retornod e la funcion

        $this->captura('id_funcionario','integer');
        $this->captura('nombre_funcionario','varchar');
        $this->captura('cargo','varchar');
        $this->captura('correo_corporativo','varchar');


        //Ejecuta la funcion
        $this->armarConsulta();
        //echo $this->getConsulta(); exit;
        $this->ejecutarConsulta();
        return $this->respuesta;

    }

    function alertarCumpleaneroDia(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='orga.ft_funcionario_ime';
        $this->transaccion='RH_CUMPLECORR_INS';
        $this->tipo_procedimiento='IME';
        //definicion de variables
        $this->tipo_conexion='seguridad';
        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function listarFuncionarioCargo(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='orga.ft_funcionario_sel';// nombre procedimiento almacenado
        $this->transaccion='RH_FUNCIOCAR_SEL';//nombre de la transaccion
        $this->tipo_procedimiento='SEL';//tipo de transaccion

        //ENVIA ESTAS VARIALBES PARA EL FILTRO
        $this->setParametro('estado_reg_fun','estado_reg_fun','varchar');
        $this->setParametro('estado_reg_asi','estado_reg_asi','varchar');


        $this->captura('id_uo_funcionario','integer');
        $this->captura('id_funcionario','integer');
        $this->captura('desc_funcionario1','text');
        $this->captura('desc_funcionario2','text');
        $this->captura('id_uo','integer');
        $this->captura('nombre_cargo','varchar');
        $this->captura('fecha_asignacion','date');
        $this->captura('fecha_finalizacion','date');
        $this->captura('num_doc','integer');
        $this->captura('ci','varchar');
        $this->captura('codigo','varchar');
        $this->captura('email_empresa','varchar');
        $this->captura('estado_reg_fun','varchar');
        $this->captura('estado_reg_asi','varchar');

        $this->captura('id_cargo','integer');
        $this->captura('descripcion_cargo','varchar');
        $this->captura('cargo_codigo','varchar');


        $this->captura('id_lugar','integer');
        $this->captura('id_oficina','integer');
        $this->captura('lugar_nombre','varchar');
        $this->captura('oficina_nombre','varchar');








        $this->setParametro('antiguedad_anterior','antiguedad_anterior','varchar');
        //Ejecuta la funcion
        $this->armarConsulta();
        //echo $this->consulta;exit;
        $this->ejecutarConsulta();
        return $this->respuesta;
    }


    function insertarFuncionario(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='orga.ft_funcionario_ime';// nombre procedimiento almacenado
        $this->transaccion='RH_FUNCIO_INS';//nombre de la transaccion
        $this->tipo_procedimiento='IME';//tipo de transaccion

        //Define los parametros para la funcion


        $this->setParametro('id_funcionario','id_funcionario','integer');
        $this->setParametro('codigo','codigo','varchar');
        $this->setParametro('id_persona','id_persona','integer');
        $this->setParametro('correo','correo','varchar');
        //$this->setParametro('celular','celular','varchar');
        //$this->setParametro('telefono','telefono','varchar');
        $this->setParametro('documento','documento','integer');
        $this->setParametro('estado_reg','estado_reg','varchar');
        $this->setParametro('fecha_ingreso','fecha_ingreso','date');
        $this->setParametro('email_empresa','email_empresa','varchar');
        $this->setParametro('interno','interno','varchar');
        $this->setParametro('telefono_ofi','telefono_ofi','varchar');
        $this->setParametro('antiguedad_anterior','antiguedad_anterior','integer');

        $this->setParametro('estado_civil','estado_civil','varchar');
        $this->setParametro('genero','genero','varchar');
        $this->setParametro('fecha_nacimiento','fecha_nacimiento','date');
        $this->setParametro('id_lugar','id_lugar','integer');
        $this->setParametro('nacionalidad','nacionalidad','varchar');
        $this->setParametro('discapacitado','discapacitado','varchar');
        $this->setParametro('carnet_discapacitado','carnet_discapacitado','varchar');
        $this->setParametro('es_tutor','es_tutor','varchar');

        $this->setParametro('ap_materno','ap_materno','varchar');
        $this->setParametro('ap_paterno','ap_paterno','varchar');
        $this->setParametro('nombre','nombre','varchar');
        $this->setParametro('ci','ci','varchar');

        $this->setParametro('celular1','celular1','varchar');
        $this->setParametro('telefono1','telefono1','varchar');
        $this->setParametro('telefono2','telefono2','varchar');
        $this->setParametro('celular2','celular2','varchar');

        $this->setParametro('tipo_documento','tipo_documento','varchar');
        $this->setParametro('expedicion','expedicion','varchar');
        $this->setParametro('direccion','direccion','varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();
        return $this->respuesta;
    }


    function modificarFuncionario(){

        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='orga.ft_funcionario_ime';// nombre procedimiento almacenado
        $this->transaccion='RH_FUNCIO_MOD';//nombre de la transaccion
        $this->tipo_procedimiento='IME';//tipo de transaccion

        //Define los parametros para la funcion
        $this->setParametro('id_funcionario','id_funcionario','integer');
        $this->setParametro('codigo','codigo','varchar');
        $this->setParametro('id_persona','id_persona','integer');
        $this->setParametro('correo','correo','varchar');
        //$this->setParametro('celular','celular','varchar');
        //$this->setParametro('telefono','telefono','varchar');
        $this->setParametro('documento','documento','integer');
        $this->setParametro('estado_reg','estado_reg','varchar');
        $this->setParametro('fecha_ingreso','fecha_ingreso','date');
        $this->setParametro('email_empresa','email_empresa','varchar');
        $this->setParametro('interno','interno','varchar');
        $this->setParametro('telefono_ofi','telefono_ofi','varchar');
        $this->setParametro('antiguedad_anterior','antiguedad_anterior','integer');

        $this->setParametro('estado_civil','estado_civil','varchar');
        $this->setParametro('genero','genero','varchar');
        $this->setParametro('fecha_nacimiento','fecha_nacimiento','date');
        $this->setParametro('id_lugar','id_lugar','integer');
        $this->setParametro('nacionalidad','nacionalidad','varchar');
        $this->setParametro('discapacitado','discapacitado','varchar');
        $this->setParametro('carnet_discapacitado','carnet_discapacitado','varchar');
        $this->setParametro('es_tutor','es_tutor','varchar');

        $this->setParametro('ap_materno','ap_materno','varchar');
        $this->setParametro('ap_paterno','ap_paterno','varchar');
        $this->setParametro('nombre','nombre','varchar');
        $this->setParametro('ci','ci','varchar');

        $this->setParametro('celular1','celular1','varchar');
        $this->setParametro('telefono1','telefono1','varchar');
        $this->setParametro('telefono2','telefono2','varchar');
        $this->setParametro('celular2','celular2','varchar');

        $this->setParametro('tipo_documento','tipo_documento','varchar');
        $this->setParametro('expedicion','expedicion','varchar');
        $this->setParametro('direccion','direccion','varchar');

        $this->setParametro('estado_correo','estado_correo','varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();

        $this->ejecutarConsulta();
        return $this->respuesta;
    }


    function eliminarFuncionario(){
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento='orga.ft_funcionario_ime';
        $this->transaccion='RH_FUNCIO_ELI';
        $this->tipo_procedimiento='IME';

        //Define los parametros para la funcion
        $this->setParametro('id_funcionario','id_funcionario','integer');
        //Ejecuta la instruccion
        $this->armarConsulta();

        $this->ejecutarConsulta();
        return $this->respuesta;
    }

    function getEmailEmpresa(){
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento='orga.ft_funcionario_ime';
        $this->transaccion='RH_MAILFUN_GET';
        $this->tipo_procedimiento='IME';

        //Define los parametros para la funcion
        $this->setParametro('id_funcionario','id_funcionario','integer');
        //Ejecuta la instruccion
        $this->armarConsulta();

        $this->ejecutarConsulta();
        return $this->respuesta;
    }

    function listarDocumentos(){
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento='orga.ft_funcionario_sel';
        $this->transaccion='ORGA_FUN_DOC_SEL';
        $this->tipo_procedimiento='SEL';//tipo de transaccion

        //Definicion de la lista del resultado del query
        $this->captura('desc_funcionario','varchar');
        $this->captura('id_funcionario','int4');
        $this->captura('id_biometrico','int4');
        $this->captura('ci','varchar');
        $this->captura('fotografia','varchar');
        $this->captura('diploma_academico','varchar');
        $this->captura('titulo_bachiller','varchar');
        $this->captura('titulo_profesional','varchar');
        $this->captura('titulo_maestria','varchar');
        $this->captura('titulo_doctorado','varchar');
        $this->captura('certificado_egreso','varchar');
        $this->captura('carnet_identidad','varchar');
        $this->captura('certificado_nacimiento','varchar');
        $this->captura('certificado_matrimonio','varchar');
        $this->captura('libreta_militar','varchar');
        $this->captura('aviso_afiliacion','varchar');
        $this->captura('examen_pre','varchar');
        $this->captura('carnet_asegurado','varchar');
        $this->captura('carnet_discapacidad','varchar');
        $this->captura('felcc','varchar');
        $this->captura('felcn','varchar');
        $this->captura('declaracion_jurada','varchar');
        $this->captura('sipasse','varchar');
        $this->captura('dj_parentesco','varchar');
        $this->captura('dj_percepciones','varchar');
        $this->captura('memorandum_designacion','varchar');
        $this->captura('memorandum_contrato','varchar');
        $this->captura('declaracion_herederos','varchar');
        $this->captura('finiquito','varchar');
        $this->captura('carta_despido','varchar');
        $this->captura('conclusion_contrato','varchar');
        $this->captura('desvinculacion_prueba','varchar');
        $this->captura('otro_retiro','varchar');
        $this->captura('aviso_bajaf','varchar');
        $this->captura('sumario','varchar');
        $this->captura('pendientes_extrabajadores','varchar');

        $this->captura('cargo','varchar');
        $this->captura('url_foto','varchar');
        $this->captura('estado_reg','varchar');
        $this->captura('fecha_ingreso','date');
        $this->captura('fecha_finalizacion','date');
        $this->captura('nombre_cargo','varchar');
        $this->captura('nombre_oficina','varchar');
        $this->captura('nombre_lugar_ofi','varchar');

        $this->captura('descripcion','varchar');
        $this->captura('id_lugar','int4');
        $this->captura('id_uo','int4');





        //Ejecuta la instruccion
        $this->armarConsulta();
        //echo($this->consulta);exit;
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function reporteDocumentos(){
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento='orga.ft_funcionario_sel';
        $this->transaccion='ORGA_REP_DOC_SEL';
        $this->tipo_procedimiento='SEL';//tipo de transaccion

        //Definicion de la lista del resultado del query
        $this->captura('gerencia','varchar');
        $this->captura('desc_funcionario','varchar');
        $this->captura('id_funcionario','int4');
        $this->captura('ci','varchar');
        $this->captura('cargo','varchar');
        $this->captura('fecha_ingreso','date');
        $this->captura('documento','varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        //echo($this->consulta);exit;
        $this->ejecutarConsulta();

        //Devuelve la respuesta
        return $this->respuesta;
    }

    function urlFotoFuncionario(){
        $this->procedimiento='orga.ft_funcionario_sel';
        $this->transaccion='RH_URL_IMG_SEL';
        $this->tipo_procedimiento='SEL';
        $this->setCount(false);

        $this->setParametro('id_funcionario','id_funcionario','int4');

        $this->captura('url_image','varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta

        return $this->respuesta;
    }

    function urlFotoFuncionarioByUsuario(){
        $this->procedimiento='orga.ft_funcionario_sel';
        $this->transaccion='ORGA_URL_IMG_U_SEL';
        $this->tipo_procedimiento='SEL';
        $this->setCount(false);

        $this->setParametro('id_usuario','id_usuario','int4');

        $this->captura('url_image','varchar');

        //Ejecuta la instruccion
        $this->armarConsulta();
        $this->ejecutarConsulta();

        //Devuelve la respuesta

        return $this->respuesta;
    }

    function listarAltasBajas(){
        //Definicion de variables para ejecucion del procedimiento
        $this->procedimiento='orga.ft_funcionario_sel';// nombre procedimiento almacenado
        $this->transaccion='RH_FUN_ALT_BAJ_SEL';//nombre de la transaccion
        $this->tipo_procedimiento='SEL';//tipo de transaccion
        //$this->setCount(false);
        $this->setParametro('estado_func','estado_func','varchar');
        $this->setParametro('fecha_ini','fecha_ini','date');
        $this->setParametro('fecha_fin','fecha_fin','date');

        //Definicion de la lista del resultado del query

        //defino varialbes que se capturan como retorno de la funcion

        $this->captura('id_funcionario','integer');
        $this->captura('codigo','varchar');
        $this->captura('email_empresa','varchar');
        $this->captura('interno','varchar');
        $this->captura('fecha_ingreso','date');

        $this->captura('id_persona','integer');
        $this->captura('desc_person','text');
        $this->captura('ci','varchar');
        $this->captura('num_documento','integer');
        $this->captura('telefono1','varchar');
        $this->captura('celular1','varchar');
        $this->captura('correo','varchar');
        $this->captura('telefono_ofi','varchar');

        $this->captura('telefono2','varchar');
        $this->captura('celular2','varchar');
        $this->captura('nombre','varchar');
        $this->captura('ap_materno','varchar');
        $this->captura('ap_paterno','varchar');

        $this->captura('fecha_asignacion','date');
        $this->captura('fecha_finalizacion','date');
        $this->captura('nombre_cargo','varchar');
        $this->captura('nombre_oficina','varchar');
        $this->captura('nombre_lugar_ofi','varchar');

        $this->captura('id_usuario_reg','integer');
        $this->captura('id_usuario_mod','integer');
        $this->captura('usr_reg','varchar');
        $this->captura('usr_mod','varchar');
        $this->captura('estado_reg','varchar');
        $this->captura('fecha_reg','timestamp');
        $this->captura('fecha_mod','timestamp');

        //Ejecuta la funcion
        $this->armarConsulta();
        //echo $this->getConsulta(); exit;
        $this->ejecutarConsulta();
        return $this->respuesta;
    }

    function dispararControlAsignacionCargo(){
        //Definicion de variables para ejecucion del procedimientp
        $this->procedimiento='orga.ft_funcionario_ime';
        $this->transaccion='RH_TRI_CAR_ASIG_IME';
        $this->tipo_procedimiento='IME';

        //Define los parametros para la funcion
        //$this->setParametro('id_funcionario','id_funcionario','integer');

        $this->captura('envio','varchar');
        //Ejecuta la instruccion
        $this->armarConsulta();

        $this->ejecutarConsulta();
        return $this->respuesta;
    }

}
?>

