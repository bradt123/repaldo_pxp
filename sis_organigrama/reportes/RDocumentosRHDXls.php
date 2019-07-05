<?php

class RDocumentosRHDXls
{
    private $docexcel;
    private $objWriter;
    private $nombre_archivo;
    private $hoja;
    private $columnas=array();
    private $fila;
    private $equivalencias=array();

    private $indice, $m_fila, $titulo;
    private $swEncabezado=0; //variable que define si ya se imprimi� el encabezado
    private $objParam;
    public  $url_archivo;

    var $datos_titulo;
    var $datos_detalle;
    var $ancho_hoja;
    var $gerencia;
    var $numeracion;
    var $ancho_sin_totales;
    var $cantidad_columnas_estaticas;
    var $s1;
    var $t1;
    var $tg1;
    var $total;
    var $datos_entidad;
    var $datos_periodo;
    var $ult_codigo_partida;
    var $ult_concepto;



    function __construct(CTParametro $objParam){
        $this->objParam = $objParam;
        $this->url_archivo = "../../../reportes_generados/".$this->objParam->getParametro('nombre_archivo');
        //ini_set('memory_limit','512M');
        set_time_limit(400);
        $cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
        $cacheSettings = array('memoryCacheSize'  => '10MB');
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

        $this->docexcel = new PHPExcel();
        $this->docexcel->getProperties()->setCreator("PXP")
            ->setLastModifiedBy("PXP")
            ->setTitle($this->objParam->getParametro('titulo_archivo'))
            ->setSubject($this->objParam->getParametro('titulo_archivo'))
            ->setDescription('Reporte "'.$this->objParam->getParametro('titulo_archivo').'", generado por el framework PXP')
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Report File");

        $this->docexcel->setActiveSheetIndex(0);

        $this->docexcel->getActiveSheet()->setTitle($this->objParam->getParametro('titulo_archivo'));

        $this->equivalencias=array(0=>'A',1=>'B',2=>'C',3=>'D',4=>'E',5=>'F',6=>'G',7=>'H',8=>'I',
            9=>'J',10=>'K',11=>'L',12=>'M',13=>'N',14=>'O',15=>'P',16=>'Q',17=>'R',
            18=>'S',19=>'T',20=>'U',21=>'V',22=>'W',23=>'X',24=>'Y',25=>'Z',
            26=>'AA',27=>'AB',28=>'AC',29=>'AD',30=>'AE',31=>'AF',32=>'AG',33=>'AH',
            34=>'AI',35=>'AJ',36=>'AK',37=>'AL',38=>'AM',39=>'AN',40=>'AO',41=>'AP',
            42=>'AQ',43=>'AR',44=>'AS',45=>'AT',46=>'AU',47=>'AV',48=>'AW',49=>'AX',
            50=>'AY',51=>'AZ',
            52=>'BA',53=>'BB',54=>'BC',55=>'BD',56=>'BE',57=>'BF',58=>'BG',59=>'BH',
            60=>'BI',61=>'BJ',62=>'BK',63=>'BL',64=>'BM',65=>'BN',66=>'BO',67=>'BP',
            68=>'BQ',69=>'BR',70=>'BS',71=>'BT',72=>'BU',73=>'BV',74=>'BW',75=>'BX',
            76=>'BY',77=>'BZ');

    }

    function imprimeDatos(){


        $datos = $this->objParam->getParametro('datos');
        $columnas = 0;

        $styleTitulos = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 8,
                'name'  => 'Arial'
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'c5d9f1')
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ));

        $this->docexcel->getActiveSheet()->getStyle('A1:AH2')->applyFromArray($styleTitulos);

        $this->docexcel->getActiveSheet()->mergeCells('A1:A2');
        $this->docexcel->getActiveSheet()->mergeCells('B1:B2');
        $this->docexcel->getActiveSheet()->mergeCells('C1:C2');
        $this->docexcel->getActiveSheet()->mergeCells('D1:D2');
        $this->docexcel->getActiveSheet()->mergeCells('E1:G1');
        $this->docexcel->getActiveSheet()->mergeCells('H1:L1');
        $this->docexcel->getActiveSheet()->mergeCells('M1:Q1');
        $this->docexcel->getActiveSheet()->mergeCells('R1:U1');
        $this->docexcel->getActiveSheet()->mergeCells('V1:X1');
        $this->docexcel->getActiveSheet()->mergeCells('Y1:AB1');
        $this->docexcel->getActiveSheet()->mergeCells('AC1:AE1');
        $this->docexcel->getActiveSheet()->mergeCells('AF1:AH1');

        //*************************************Cabecera*****************************************
        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[0])->setWidth(7);
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0,1,'Nro');
        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[1])->setWidth(40);
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(1,1,'GERENCIA');
        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[2])->setWidth(40);
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(2,1,'NOMBRE Y APELLIDO');
        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[3])->setWidth(20);
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(3,1,'CI');


        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[4])->setWidth(20);
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(4,1,'TITULO DE BACHILLER');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(4,2,'FECHA DE EMISION');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(5,2,'ENTIDAD EMISORA');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(6,2,'NUMERO DE SERIE');
        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[5])->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[6])->setWidth(20);


        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[7])->setWidth(20);
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(7,1,'TITULO PROFESIONAL');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(7,2,'FECHA DE EMISION');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(8,2,'ENTIDAD EMISORA');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(9,2,'NUMERO DE SERIE');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(10,2,'CARRERA ');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(11,2,'NIVEL ACADEMICO');
        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[8])->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[9])->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[10])->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[11])->setWidth(20);


        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[12])->setWidth(20);
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(12,1,'CERTIFICADO EGRESO');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(12,2,'FECHA DE EMISION');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(13,2,'ENTIDAD EMISORA');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(14,2,'CARRERA ');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(15,2,'NIVEL ACADEMICO');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(16,2,'OBSERVACIONES');
        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[13])->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[14])->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[15])->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[16])->setWidth(20);


        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[17])->setWidth(20);
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(17,1,'TITULO MAESTRIA');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(17,2,'NOMBRE DE LA MAESTRIA');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(18,2,'NUMERO DE SERIE');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(19,2,'FECHA DE EMISION');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(20,2,'ENTIDAD EMISORA');
        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[18])->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[19])->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[20])->setWidth(20);


        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[20])->setWidth(20);
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(21,1,'LIBRETA DE SERVICIO MILITAR');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(21,2,'NUMERO DE MATRICULA');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(22,2,'NUMERO DE SERIE');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(23,2,'ESCALA');
        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[21])->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[22])->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[23])->setWidth(20);


        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[24])->setWidth(20);
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(24,1,'DIPLOMA ACADEMICO');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(24,2,'CARRERA');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(25,2,'NIVEL ACAD.');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(26,2,'ENTIDAD EMISORA');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(27,2,'FECHA DE EMISION');
        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[24])->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[25])->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[26])->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[27])->setWidth(20);

        //SUM
        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[28])->setWidth(20);
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(28,1,'SUMARIO');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(28,2,'FECHA SUMARIO');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(29,2,'NUM. DE SUMARIO');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(30,2,'OBSERVACIONES');
        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[28])->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[29])->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[30])->setWidth(20);

        //RESOLUCION
        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[31])->setWidth(20);
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(31,1,'RESOLUCIÓN ADMINISTRATIVA');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(31,2,'FECHA');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(32,2,'NUMERO');
        $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(33,2,'SANCIÓN');
        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[31])->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[32])->setWidth(20);
        $this->docexcel->getActiveSheet()->getColumnDimension($this->equivalencias[33])->setWidth(20);
        //*************************************Fin Cabecera*****************************************
        $fila = 3;
        $contador = 1;
        $tamano = count($datos)+2;
        $this->docexcel->getActiveSheet()->freezePaneByColumnAndRow(0,3);

        $this->docexcel->getActiveSheet()->getStyle('E1:G2')->getFill()->getStartColor()->setRGB('4682b4');
        $this->docexcel->getActiveSheet()->getStyle('H1:L2')->getFill()->getStartColor()->setRGB('6f9dc4');
        $this->docexcel->getActiveSheet()->getStyle('M1:Q2')->getFill()->getStartColor()->setRGB('98B9D5');
        $this->docexcel->getActiveSheet()->getStyle('R1:U2')->getFill()->getStartColor()->setRGB('C1D5E6');
        $this->docexcel->getActiveSheet()->getStyle('V1:X2')->getFill()->getStartColor()->setRGB('EAF1F6');
        $this->docexcel->getActiveSheet()->getStyle('Y1:AB2')->getFill()->getStartColor()->setRGB('EAF1A9');

        $this->docexcel->getActiveSheet()->getStyle('AC1:AE2')->getFill()->getStartColor()->setRGB('4682b4');
        $this->docexcel->getActiveSheet()->getStyle('AF1:AH2')->getFill()->getStartColor()->setRGB('6f9dc4');
        $this->docexcel->getActiveSheet()->getStyle('E3:AB'.$tamano)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        /////////////////////***********************************Detalle***********************************************
        foreach($datos as $value) {


            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(0,$fila,$contador);
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(1,$fila,$value['gerencia']);
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(2,$fila,$value['desc_funcionario']);
            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(3,$fila,$value['ci']);

            if($value['documento'] == '[]'){

                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(4, $fila, "sin registrar");
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(5, $fila, "sin registrar");
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(6, $fila, "sin registrar");
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(7, $fila, "sin registrar");
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(8, $fila, "sin registrar");
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(9, $fila, "sin registrar");
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(10, $fila, "sin registrar");
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(11, $fila, "sin registrar");
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(12, $fila, "sin registrar");
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(13, $fila, "sin registrar");
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(14, $fila, "sin registrar");
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(15, $fila, "sin registrar");
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(16, $fila, "sin registrar");
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(17, $fila, "sin registrar");
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(18, $fila, "sin registrar");
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(19, $fila, "sin registrar");
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(20, $fila, "sin registrar");
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(21, $fila, "sin registrar");
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(22, $fila, "sin registrar");
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(23, $fila, "sin registrar");
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(24, $fila, "sin registrar");
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(25, $fila, "sin registrar");
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(26, $fila, "sin registrar");
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(27, $fila, "sin registrar");
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(28, $fila, "sin registrar");
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(29, $fila, "sin registrar");
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(30, $fila, "sin registrar");
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(31, $fila, "sin registrar");
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(32, $fila, "sin registrar");
                $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(33, $fila, "sin registrar");
            }else {

                $this->docexcel->getActiveSheet()->getStyle('E'.$fila.':AB'.$fila)->getAlignment()->setWrapText(true);


                $valores = json_decode(preg_replace('[\n|\r|\n\r]', '', $value['documento']));

                foreach ($valores as $obj) {

                    if (key($obj) == 'TIT_BACHILLER') {
                        if($obj->TIT_BACHILLER->fecha != '' || $obj->TIT_BACHILLER->entidad_emisora != '' || $obj->TIT_BACHILLER->numero != '' ) {
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(4, $fila, date_format(date_create($obj->TIT_BACHILLER->fecha), 'd/m/Y'));
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(5, $fila, $obj->TIT_BACHILLER->entidad_emisora);
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(6, $fila, $obj->TIT_BACHILLER->numero);
                        }else{
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(4, $fila, "sin registrar");
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(5, $fila, "sin registrar");
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(6, $fila, "sin registrar");
                        }

                    }

                    if(key($obj) == 'TIT_PROF'){
                        if($obj->TIT_PROF->fecha_emision != '' || $obj->TIT_PROF->entidad_emisora != '' || $obj->TIT_PROF->numero != '' || $obj->TIT_PROF->carrera != '' || $obj->TIT_PROF->nivel_academico != '') {
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(7, $fila, date_format(date_create($obj->TIT_PROF->fecha_emision), 'd/m/Y'));
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(8, $fila, $obj->TIT_PROF->entidad_emisora);
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(9, $fila, $obj->TIT_PROF->numero);
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(10, $fila, $obj->TIT_PROF->carrera);
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(11, $fila, $obj->TIT_PROF->nivel_academico);
                        }else{
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(7, $fila, "sin registrar");
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(8, $fila, "sin registrar");
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(9, $fila, "sin registrar");
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(10, $fila, "sin registrar");
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(11, $fila, "sin registrar");
                        }
                    }

                    if(key($obj) =='CERT_EGRESO'){
                        if($obj->CERT_EGRESO->fecha_emision != '' || $obj->CERT_EGRESO->entidad_emisora != '' || $obj->CERT_EGRESO->carrera != '' || $obj->CERT_EGRESO->nivel_academico != '' || $obj->CERT_EGRESO->observaciones != '') {
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(12, $fila, date_format(date_create($obj->CERT_EGRESO->fecha_emision), 'd/m/Y'));
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(13, $fila, $obj->CERT_EGRESO->entidad_emisora);
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(14, $fila, $obj->CERT_EGRESO->carrera);
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(15, $fila, $obj->CERT_EGRESO->nivel_academico);
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(16, $fila, $obj->CERT_EGRESO->observaciones);
                        }else{
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(12, $fila, "sin registrar");
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(13, $fila, "sin registrar");
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(14, $fila, "sin registrar");
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(15, $fila, "sin registrar");
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(16, $fila, "sin registrar");
                        }
                    }

                    if(key($obj) == 'TIT_MAES'){
                        if($obj->TIT_MAES->nombre_maestria != '' || $obj->TIT_MAES->nro_serie != '' || $obj->TIT_MAES->fecha_emision != '' || $obj->TIT_MAES->entidad_emisora != '') {
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(17, $fila, $obj->TIT_MAES->nombre_maestria);
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(18, $fila, $obj->TIT_MAES->nro_serie);
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(19, $fila, date_format(date_create($obj->TIT_MAES->fecha_emision), 'd/m/Y'));
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(20, $fila, $obj->TIT_MAES->entidad_emisora);
                        }else{
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(17, $fila, "sin registrar");
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(18, $fila, "sin registrar");
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(19, $fila, "sin registrar");
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(20, $fila, "sin registrar");
                        }
                    }

                    if(key($obj) == 'LIB_MIL'){
                        if($obj->LIB_MIL->numero_matricula != '' || $obj->LIB_MIL->numero_serie != '' || $obj->LIB_MIL->escala != '') {
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(21, $fila, $obj->LIB_MIL->numero_matricula);
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(22, $fila, $obj->LIB_MIL->numero_serie);
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(23, $fila, $obj->LIB_MIL->escala);
                        }else{
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(21, $fila, "sin registrar");
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(22, $fila, "sin registrar");
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(23, $fila, "sin registrar");
                        }
                    }

                    if(key($obj) == 'DIAC'){
                        if($obj->DIAC->fecha != '' || $obj->DIAC->entidad_emisora != '' || $obj->DIAC->carrera != '' || $obj->DIAC->nivel_academico != '') {
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(24, $fila, $obj->DIAC->carrera);
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(25, $fila, $obj->DIAC->nivel_academico);
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(26, $fila, $obj->DIAC->entidad_emisora);
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(27, $fila, $obj->DIAC->fecha);
                        }else{
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(24, $fila, "sin registrar");
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(25, $fila, "sin registrar");
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(26, $fila, "sin registrar");
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(27, $fila, "sin registrar");
                        }
                    }

                    if(key($obj) == 'SUM'){
                        if($obj->SUM->fecha != '' || $obj->SUM->numero_sumario != '' || $obj->SUM->observaciones != '') {
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(28, $fila, date_format(date_create($obj->SUM->fecha), 'd/m/Y'));
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(29, $fila, $obj->SUM->numero_sumario);
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(30, $fila, $obj->SUM->observaciones);
                        }else{
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(28, $fila, "sin registrar");
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(29, $fila, "sin registrar");
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(30, $fila, "sin registrar");
                        }
                    }

                    if(key($obj) == 'resolucion_administrativa'){
                        if($obj->resolucion_administrativa->fecha != '' || $obj->resolucion_administrativa->numero != '' || $obj->resolucion_administrativa->sancion != '') {
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(31, $fila, date_format(date_create($obj->resolucion_administrativa->fecha), 'd/m/Y'));
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(32, $fila, $obj->resolucion_administrativa->numero);
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(33, $fila, $obj->resolucion_administrativa->sancion);
                        }else{
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(31, $fila, "sin registrar");
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(32, $fila, "sin registrar");
                            $this->docexcel->getActiveSheet()->setCellValueByColumnAndRow(33, $fila, "sin registrar");
                        }
                    }

                }
            }
            $fila++;
            $contador++;
        }
        //************************************************Fin Detalle***********************************************

    }



    function generarReporte(){

        $this->imprimeDatos();

        //echo $this->nombre_archivo; exit;
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $this->docexcel->setActiveSheetIndex(0);
        $this->objWriter = PHPExcel_IOFactory::createWriter($this->docexcel, 'Excel5');
        $this->objWriter->save($this->url_archivo);


    }


}

?>