<?php

namespace app\controllers\ctacte;

use Yii;
use app\models\ctacte\Ctacte;
use app\models\ctacte\Liquida;
use app\models\ctacte\Plan;
use app\models\ctacte\Facilida;
use app\models\ctacte\Ddjj;
use app\models\objeto\Objeto;
use app\models\objeto\Domi;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Session;
use yii\data\ArrayDataProvider;

use app\controllers\SiteController;

use app\utils\db\utb;
use app\utils\db\Fecha;

/**
 * CtacteController implements the CRUD actions for Ctacte model.
 */
class CtacteController extends Controller{

	const IMPRESION_RESUMEN= 'resumen';
	const IMPRESION_TODOS= 'todos';

	const ERROR= 'cuentaCorrienteError';

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    private function extrasIndex(){

    	$dpDumb= $this->armarDP();//new ArrayDataProvider(['allModels' => []]);

    	return [
            'dataProvider' => $dpDumb,
            'dataProviderMovimientos' => $dpDumb,
            'deuda' => 0,
			'banderas' => [],
            'arrayAccBand' => [],
            'error' => '',
            'fchcons' => '',
            'pervenc' => 0,
            'bajas' => false,
            'planvig' => false,
            'est' => '',
            'tobjpersona' => '',
            'cuotahasta' => 0,
            'aniohasta' => 0,
            'cuotadesde' => 0,
            'aniodesde' => 0,
            'obj_id' => '',
            'perdesde' => 0,
            'perhasta' => 0,
            'trib_id' => 0,
            'plan_id' => 0,
            'subcta' => 0,
			'estobj' => false,
			'existectacte' => false,
			'liquidaposteriorbaja' => false,

            'aplicar' => false,

            'mensajeError' => '',
            'descripcionExportarResumen' => '',
            'descripcionExportarCompleto' => ''
        ];
    }

    private function armarDP($models= [], $maximo= 5, $pagina= 0){

    	$ret= new ArrayDataProvider([
    		'allModels' => $models,
    		'totalCount' => count($models),
    		'pagination' => [
    			'pageSize' => $maximo,
    			'page' => $pagina
    		]
    	]);

    	if($maximo == 0) $ret->setPagination(false);

    	return $ret;
    }

    public function beforeAction($action){

		$operacion = str_replace("/", "-", Yii::$app->controller->route);

	    if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }

		return true;
	}

    /**
     * Lists all Ctacte models.
     * @return mixed
     */
    public function actionIndex($aplic = 0, $obj_id = '', $e= 0, $aplica= 0){

		$aplica= $obj_id != '';
    	$samConfig = utb::samConfig();
    	$fchcons= date('m/d/Y');
    	$perdesde= (date('Y') - $samConfig['ctacte_anio_desde']) * 1000 + 1;
    	$perhasta= (date('Y')+1)*1000+999;
    	$est= '';
    	$planvig= 0;
    	$bajas= 0;
    	$pervenc= 0;
    	$tobjpersona= '';
    	$aniodesde= intval(date('Y')) - $samConfig['ctacte_anio_desde'];
    	$cuotadesde= 1;
    	$aniohasta= (intval(date('Y')) + 1);
    	$cuotahasta= 999;
    	$trib_id=0;
    	$plan_id= 0;
    	$subcta= 0;
    	$descripcionExportarResumen= ''; $descripcionExportarCompleto= '';
    	$banderas= [];
    	$arrayAccBand= [];
		$existectacte = true;
		$liquidaposteriorbaja = false;
		$liqBorrar = null;
		$estobj = false;


		$cargarDatosPrincipales= filter_var(Yii::$app->request->post('cargarDatosPrincipales', false), FILTER_VALIDATE_BOOLEAN);
		$cargarMovimientos= filter_var(Yii::$app->request->post('cargarMovimientos', false), FILTER_VALIDATE_BOOLEAN);
		$cargarBanderas= filter_var(Yii::$app->request->get('cargarBanderas', false), FILTER_VALIDATE_BOOLEAN);

    	$deuda = 0;
    	$model= new Ctacte();

    	$dataProvider= $this->armarDP();
    	$dataProviderMovimientos= $dataProvider;

		if (isset($_POST['pjelimliq']))
		{
			$ultimoError = $model->BorrarLiquidaPosteriorBaja($_POST['objeto_id']);
		}

        if($cargarDatosPrincipales || (Yii::$app->request->isPost and !isset($_POST['soloobj']))){

        	$obj_id= trim(Yii::$app->request->post('obj_id', ''));
	        $fchcons= Fecha::usuarioToBD(Yii::$app->request->post('fchcons', date('d/m/Y')));
	        $perdesde= intval(Yii::$app->request->post('perdesde', (date('Y') - $samConfig['ctacte_anio_desde']) * 1000 + 1));
	        $perhasta= Yii::$app->request->post('perhasta', date('Y')*1000+999);
	        $est= trim(Yii::$app->request->post('est', ''));
	        $planvig= filter_var(Yii::$app->request->post('planvig', false), FILTER_VALIDATE_BOOLEAN);
	        $bajas= filter_var(Yii::$app->request->post('bajas', 0), FILTER_VALIDATE_BOOLEAN);
	        $tobjpersona= trim(Yii::$app->request->post('tobjpersona', ''));
	        $pervenc= filter_var(Yii::$app->request->post('pervenc', false), FILTER_VALIDATE_BOOLEAN);
	        $aniodesde = substr($perdesde, 0, 4);
	        $cuotadesde = substr($perdesde, 4, 3);
	    	$aniohasta = substr($perhasta, 0, 4);
	    	$cuotahasta = substr($perhasta, 4, 3);
	    	$estobj = filter_var(Yii::$app->request->post('estobj', false), FILTER_VALIDATE_BOOLEAN);

	    	$resultado= $model->CtaCteResumen($deuda, $obj_id, $fchcons, $perdesde, $perhasta, $est, $planvig, $tobjpersona,$estobj);

	    	$cantidadRegistros= count($resultado);


	    	if($cantidadRegistros == 1){

	    		$cargarMovimientos= false;
	    		$registro= $resultado[0];
	    		$datosMovimientos= $model->CtaCteTributo($registro['trib_id'], $registro['plan_id'], $obj_id, $registro['subcta'], $fchcons, $perdesde, $perhasta, $est, $bajas, $pervenc);

	    		$dataProviderMovimientos= new ArrayDataProvider([
	    								'allModels' => $datosMovimientos,
	    								'totalCount' => count($datosMovimientos),
	    								'pagination' => false
	    								]);
	    	}

			$nombreTipoObjeto= utb::getTObjNom($obj_id);


			$descripcionExportarResumen= "Objeto: $nombreTipoObjeto $obj_id";
	        $descripcionExportarResumen .= ' Fecha: ' . $fchcons . ' -  Período desde: ' . $aniodesde . '/' . $cuotadesde;
	        $descripcionExportarResumen .= ' hasta: ' . $aniohasta . '/' . $cuotahasta;

			$pagina= intval(Yii::$app->request->get('page', 0));
			if($pagina < 0) $pagina= 0;

	        $dataProvider= $this->armarDP($resultado, 10, $pagina);



	        //informacion para exportar resumen
	        $sql = "select * from sam.uf_ctacte_resumen('".$obj_id."','".$fchcons."',".$perdesde.",".$perhasta.",'".$est."')";
			if ($estobj) $sql .= " where obj_est='A'";

			Yii::$app->session['sql'] = $sql;
			Yii::$app->session['columns'] = [
				['attribute'=>'tobj_nom','contentOptions'=>['style'=>'width:30px;','class' => 'grilla'],'label' => 'Tipo'],
				['attribute'=>'obj_id','contentOptions'=>['style'=>'width:60px;','class' => 'grilla'],'label' => 'Objeto'],
	    		['attribute'=>'subcta','contentOptions'=>['style'=>'width:20px; text-align:center','class' => 'grilla'],'label' => 'SCta'],
	    		['attribute'=>'obj_dato','contentOptions'=>['style'=>'width:90px','class' => 'grilla'],'label' => 'Dato'],
	    		['attribute'=>'trib_nom','contentOptions'=>['style'=>'width:90px','class' => 'grilla'],'label' => 'Tributo'],
	    		['attribute'=>'plan_id','contentOptions'=>['align'=>'center','style'=>'width:30px; text-align:center','class' => 'grilla'],'label' => 'Conv'],
	    		['attribute'=>'saldo_n', 'format' => ['decimal', 2], 'contentOptions'=>['style'=>'width:45px;text-align:right','class' => 'grilla'],'label' => 'Neg'],
	    		['attribute'=>'saldo', 'format' => ['decimal', 2], 'contentOptions'=>['style'=>'width:65px;text-align:right','class' => 'grilla'],'label' => 'Saldo']

	        ];
	        Yii::$app->session['proceso_asig'] = 3301;
        }

		if($cargarBanderas){

			$banderas = $model->banderas($obj_id, $perdesde, $perhasta);
        	$arrayAccBand = $model->AccionesBanderas($obj_id, $perdesde, $perhasta);
		}


        if($cargarMovimientos or isset($_POST['cargarMovimientos2'])){

        	$trib_id= intval(Yii::$app->request->post('trib_id', 0));
        	$plan_id= intval(Yii::$app->request->post('plan_id', 0));
        	$obj_id= trim(Yii::$app->request->post('obj_id'));
        	$subcta= intval(Yii::$app->request->post('subcta', 0));
        	$fecha= Fecha::usuarioToBD(Yii::$app->request->post('fecha', date('m/d/Y')));
        	$perdesde= intval(Yii::$app->request->post('perdesde', 0));
        	$perhasta= intval(Yii::$app->request->post('perhasta', 0));
        	$est= trim(Yii::$app->request->post('est', ''));
        	$baja= filter_var(Yii::$app->request->post('baja', false), FILTER_VALIDATE_BOOLEAN);
        	$pervenc= filter_var(Yii::$app->request->post('pervenc', 0), FILTER_VALIDATE_BOOLEAN);

        	$sql= '';
        	$datosMovimientos= $model->CtaCteTributo($trib_id,$plan_id,$obj_id,$subcta,$fecha,$perdesde,$perhasta,$est,$baja, $pervenc, $sql);

        	$dataProviderMovimientos= new ArrayDataProvider([
        								'allModels' => $datosMovimientos,
        								'totalCount' => count($datosMovimientos),
        								'pagination' => false
        							]);


        	$nombreObjeto= str_replace('"','',utb::getNombObj("'$obj_id'"));
        	$nombreTipoObjeto= utb::getTObjNom($obj_id);
			$nombreTributo= utb::getNombTrib($trib_id);

	        $descripcionExportarCompleto= "Objeto: $nombreTipoObjeto $obj_id";
	        $descripcionExportarCompleto= " $nombreObjeto - Tributo: $nombreTributo - Fecha: $fchcons - Período desde: $aniodesde/$cuotadesde hasta: $aniohasta/cuotahasta";

	        //informacion para exportar completo
//	        $sql = "select * from sam.uf_ctacte_tributo(".$trib_id.",".$plan_id.",'".$obj_id."',".$subcta.",'".$fchcons."',".$perdesde.",".$perhasta.")";
        	Yii::$app->session['sql'] = $sql;
			Yii::$app->session['columns'] = [

				['attribute'=>'anio','label' => 'Año'],
				['attribute'=>'cuota','contentOptions'=>['style'=>'width:40px;text-align:center'],'label' => 'Cuota'],
        		['attribute'=>'est','contentOptions'=>['style'=>'width:35px;text-align:center'],'label' => 'Est'],
        		['attribute'=>'nominal', 'format' => ['decimal', 2], 'contentOptions'=>['style'=>'text-align:right'],'label' => 'Nominal'],
        		['attribute'=>'nominalcub', 'format' => ['decimal', 2], 'contentOptions'=>['style'=>'text-align:right'],'label' => 'Cubierto'],
        		['attribute'=>'multa', 'format' => ['decimal', 2], 'contentOptions'=>['style'=>'width:50px;text-align:right'],'label' => 'Multa'],
        		['attribute'=>'accesor','format' => ['decimal', 2], 'contentOptions'=>['style'=>'width:40px;text-align:right'],'label' => 'Accesor'],
        		['attribute'=>'pagado', 'format' => ['decimal', 2], 'contentOptions'=>['style'=>'width:40px;text-align:right'],'label' => 'Pagado'],
        		['attribute'=>'saldo', 'format' => ['decimal', 2], 'contentOptions'=>['style'=>'width:40px;text-align:right'],'label' => 'Saldo'],
        		['attribute'=>'fchemi', 'format' => ['date', 'php:d/m/Y'], 'contentOptions'=>['style'=>'width:80px;text-align:center'],'label' => 'FchEmi'],
        		['attribute'=>'fchvenc', 'format' => ['date', 'php:d/m/Y'],'contentOptions'=>['style'=>'width:80px;text-align:center'],'label' => 'FchVenc'],
        		['attribute'=>'fchpago', 'format' => ['date', 'php:d/m/Y'],'contentOptions'=>['style'=>'width:80px;text-align:center'],'label' => 'FchPago'],
        		['attribute'=>'caja_id','contentOptions'=>['style'=>'width:50px;text-align:center'],'label' => 'Caja'],
        		['attribute'=>'baja','contentOptions'=>['style'=>'width:20px'],'label' => '*'],
        		['attribute'=>'expe','contentOptions'=>['style'=>'width:60px'],'label' => 'Expte'],
        		['attribute'=>'obs_asc','contentOptions'=>['style'=>'width:20px;text-align:center'],'label' => 'Ob'],

	        ];
	        Yii::$app->session['proceso_asig'] = 3301;
        }

        //manejo de errores
        $ultimoError= Yii::$app->session->getFlash(self::ERROR, null, true);

		if ($obj_id != '') {
			$existectacte = $model->ExisteCtacte($obj_id);
			$liquidaposteriorbaja = $model->LiquidacionesPosterioresBaja($obj_id);

			if ($liquidaposteriorbaja and utb::getExisteProceso(3436)) $liqBorrar = $model->LiquidacionesPosterioresBajaLista($obj_id);
		}

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'dataProviderMovimientos' => $dataProviderMovimientos,
             'deuda' => $deuda,'banderas' => $banderas,
            'arrayAccBand' => $arrayAccBand, 'mensajeError' => $ultimoError,

            'fchcons' => $fchcons,
            'pervenc' => $pervenc,
            'bajas' => $bajas,
            'planvig' => $planvig,
            'est' => $est,
            'tobjpersona' => $tobjpersona,
            'cuotahasta' => $cuotahasta,
            'aniohasta' => $aniohasta,
            'cuotadesde' => $cuotadesde,
            'aniodesde' => $aniodesde,
            'obj_id' => $obj_id,
            'perdesde' => $perdesde,
            'perhasta' => $perhasta,
            'trib_id' => $trib_id,
            'plan_id' => $plan_id,
            'subcta' => $subcta,
			'estobj' => $estobj,

            'aplicar' => $aplica,

            'descripcionExportarResumen' => $descripcionExportarResumen,
            'descripcionExportarCompleto' => $descripcionExportarCompleto,

			'existectacte' => $existectacte,
			'liquidaposteriorbaja' => $liquidaposteriorbaja,
			'liqBorrar' => $liqBorrar
        ]);
    }

    public function actionExportarresumen($o, $f, $d, $h, $e,$eo){

    	$sql = "select * from sam.uf_ctacte_resumen('$o', '$f', $d, $h, '$e')";
		if ($eo) $sql .= " where obj_est='A'";

		Yii::$app->session['sql'] = $sql;
		Yii::$app->session['columns'] = [
			['attribute'=>'tobj_nom','contentOptions'=>['style'=>'width:30px;','class' => 'grilla'],'label' => 'Tipo'],
			['attribute'=>'obj_id','contentOptions'=>['style'=>'width:60px;','class' => 'grilla'],'label' => 'Objeto'],
    		['attribute'=>'subcta','contentOptions'=>['style'=>'width:20px; text-align:center','class' => 'grilla'],'label' => 'SCta'],
    		['attribute'=>'obj_dato','contentOptions'=>['style'=>'width:90px','class' => 'grilla'],'label' => 'Dato'],
    		['attribute'=>'trib_nom','contentOptions'=>['style'=>'width:90px','class' => 'grilla'],'label' => 'Tributo'],
    		['attribute'=>'plan_id','contentOptions'=>['align'=>'center','style'=>'width:30px; text-align:center','class' => 'grilla'],'label' => 'Conv'],
    		['attribute'=>'saldo_n', 'format' => ['decimal', 2], 'contentOptions'=>['style'=>'width:45px;text-align:right','class' => 'grilla'],'label' => 'Neg'],
    		['attribute'=>'saldo', 'format' => ['decimal', 2], 'contentOptions'=>['style'=>'width:65px;text-align:right','class' => 'grilla'],'label' => 'Saldo']

        ];
        Yii::$app->session['proceso_asig'] = 3301;

        return $this->render('index', $this->extrasIndex());
    }

    /**
     * $o Codigo de objeto
     * $f Fecha de consolidacion
     * $d Periodo desde
     * $h Periodo hasta
     * $e Estado
     */
    public function actionExportarcompleto($o, $f, $d, $h, $e){

    	//$sql = "select * from sam.uf_ctacte_tributo($t, $p, '$o', $sc, '$f', $d, $h)";
    	$sql= '';
    	$model= new CtaCte();

    	$model->CtacteObjeto($o, $f, $d, $h, $e, 'trib_id, obj_id, anio, cuota', $sql);
    	Yii::$app->session['sql'] = $sql;
		Yii::$app->session['columns'] = [
			['attribute'=>'trib_nom','label' => 'Tributo'],
				['attribute'=>'obj_id','label' => 'Objeto'],
			['attribute'=>'anio','label' => 'Año'],
			['attribute'=>'cuota','contentOptions'=>['style'=>'width:40px;text-align:center'],'label' => 'Cuota'],
    		['attribute'=>'est','contentOptions'=>['style'=>'width:35px;text-align:center'],'label' => 'Est'],
    		['attribute'=>'nominal', 'format' => ['decimal', 2], 'contentOptions'=>['style'=>'text-align:right'],'label' => 'Nominal'],
    		['attribute'=>'nominalcub', 'format' => ['decimal', 2], 'contentOptions'=>['style'=>'text-align:right'],'label' => 'Cubierto'],
    		['attribute'=>'multa', 'format' => ['decimal', 2], 'contentOptions'=>['style'=>'width:50px;text-align:right'],'label' => 'Multa'],
    		['attribute'=>'accesor','format' => ['decimal', 2], 'contentOptions'=>['style'=>'width:40px;text-align:right'],'label' => 'Accesor'],
    		['attribute'=>'pagado', 'format' => ['decimal', 2], 'contentOptions'=>['style'=>'width:40px;text-align:right'],'label' => 'Pagado'],
    		['attribute'=>'saldo', 'format' => ['decimal', 2], 'contentOptions'=>['style'=>'width:40px;text-align:right'],'label' => 'Saldo'],
    		['attribute'=>'fchemi', 'format' => ['date', 'php:d/m/Y'], 'contentOptions'=>['style'=>'width:80px;text-align:center'],'label' => 'FchEmi'],
    		['attribute'=>'fchvenc', 'format' => ['date', 'php:d/m/Y'],'contentOptions'=>['style'=>'width:80px;text-align:center'],'label' => 'FchVenc'],
    		['attribute'=>'fchpago', 'format' => ['date', 'php:d/m/Y'],'contentOptions'=>['style'=>'width:80px;text-align:center'],'label' => 'FchPago'],
    		['attribute'=>'caja_id','contentOptions'=>['style'=>'width:50px;text-align:center'],'label' => 'Caja'],
    		['attribute'=>'baja','contentOptions'=>['style'=>'width:20px'],'label' => '*'],
    		['attribute'=>'expe','contentOptions'=>['style'=>'width:60px'],'label' => 'Expte'],
    		['attribute'=>'obs_asc','contentOptions'=>['style'=>'width:20px;text-align:center'],'label' => 'Ob'],

        ];
        Yii::$app->session['proceso_asig'] = 3301;

        return $this->render('index', $this->extrasIndex());
    }

    public function actionCtactedet($ctacte_id, $p= 0, $f= null, $a= null, $c= null, $sc= 0, $b= false, $e= '', $imprimir= false){

    	$accion= Yii::$app->request->get('accion', 'Agrupar');

    	$imprimir= filter_var($imprimir, FILTER_VALIDATE_BOOLEAN);
		if($imprimir) return $this->imprimirDetalle($ctacte_id, $b, $accion);

    	$accesor= 0; $saldo= 0; $fecha; $obs= 0;
    	$obj_id= $trib_id= $anio= $cuota= null;

		if($f == null) $f= date('d/m/Y');
		if($a == null) $a= intval(date('Y'));
		if($c == null) $c= intval(date('m'));

		$b= filter_var($b, FILTER_VALIDATE_BOOLEAN);

		$fecha= Fecha::usuarioToBD($f);
    	$perdesde= intval($a) * 1000 + intval($c);

		$model= CtaCte::findOne(['ctacte_id' => $ctacte_id]);

		if($model == null) $model= new CtaCte();
		else{

			$datos= $model->CtaCteTributo($model->trib_id, $p, $model->obj_id, $sc, $fecha, $perdesde, $perdesde, $e, $b, false);

			if(count($datos) > 0){

				$accesor= $datos[0]['accesor'];
				$saldo= $datos[0]['saldo'];
				$obs= $datos[0]['obs'];
			}
		}


		$sql= '';

		$dataProviderDetalle= $this->armarDP();

		if($accion == 'Agrupar')
			$dataProviderDetalle= $this->armarDP($model->CtaCteDetAgrupar($model->ctacte_id, $b), 0);
		 else
			$dataProviderDetalle= $this->armarDP($model->CtaCteDetBuscar($model->ctacte_id, $b), 0);


		$dataProviderLiquidacion = $this->armarDP($model->CtaCteLiq($model->trib_id, $model->ctacte_id), 0);
		$dataProviderCambioEstado = $this->armarDP($model->CtaCteCambioEst($model->ctacte_id), 10);
		$dataProviderCtaCteExcepcion = $this->armarDP($model->CtaCteExcep($model->ctacte_id), 10);
		$dataProviderCtaCteBaja = $this->armarDP($model->CtaCteBaja($model->ctacte_id), 10);


		$cargarDescripcion= filter_var(Yii::$app->request->get('cargarDescripcion', FILTER_VALIDATE_BOOLEAN));
		$descripcionDetalle= '';
		$descripcionTipoOperacion= '';
		$descripcionTipoOperacion= '';
		$descripcionComprobante= '';

		if($cargarDescripcion){

			$tipoOperacion= intval(Yii::$app->request->get('topera', 0));
			$descripcionComprobante= intval(Yii::$app->request->get('comprob', 0));
			$operacion= trim(Yii::$app->request->get('operacion'), '');

			$descripcionDetalle= $model->obtenerDetalle($tipoOperacion, $descripcionComprobante, $model->ctacte_id);

			if(trim($descripcionDetalle) !== '') $descripcionTipoOperacion= "Operación $operacion";
		}


		$arrayCtaCteDet= [
			"ctacte_id" => $model->ctacte_id,
			"obj_id" => $model->obj_id,
			"fecha" => $f,
			"trib_id" => $model->trib_id,
			"anio" => $model->anio,
			"cuota" => $model->cuota,
			"accesor" => $accesor,
			"saldo" => $saldo,
			"obs" => $obs,
			"caja_id" => $model->caja_id,
			'baja' => $b,
			'descripcionDetalle' => $descripcionDetalle,
			'descripcionTipoOperacion' => $descripcionTipoOperacion,
			'descripcionTipoOperacion' => $descripcionTipoOperacion,
			'descripcionComprobante' => $descripcionComprobante
		];

    	return $this->render('ctactedet', [
    		'model' => $model,
    		'accion' => $accion,
            'arrayCtaCteDet' => $arrayCtaCteDet,
            'dataProviderDetalle' => $dataProviderDetalle,
            'dataProviderLiquidacion' => $dataProviderLiquidacion,
            'dataProviderCambioEstado' => $dataProviderCambioEstado,
            'dataProviderCtaCteExcepcion' => $dataProviderCtaCteExcepcion,
            'dataProviderCtaCteBaja' => $dataProviderCtaCteBaja
        ]);
    }

	private function imprimirDetalle($ctacte_id, $b= false, $accion= 'Agrupar'){

		$b= filter_var($b, FILTER_VALIDATE_BOOLEAN);
		$model= CtaCte::findOne(['ctacte_id' => $ctacte_id]);
		$sql= '';

		if($accion == 'Agrupar')
			$dataProviderDetalle= $this->armarDP($model->CtaCteDetAgrupar($model->ctacte_id, $b, $sql), 0);
		else
			$dataProviderDetalle= $this->armarDP($model->CtaCteDetBuscar($model->ctacte_id, $b, $sql), 0);


		$titulo= 'Detalle de Cuenta Corriente';

		$condicion = "Objeto: ".utb::getTObjNom($model->obj_id)." - ".$model->obj_id." - ".utb::getNombObj($model->obj_id, false);
		$condicion .= "<br>Tributo: ".utb::getNombTrib($model->trib_id);
		$condicion .= "<br>Año: ".$model->anio." - Cuota: ".$model->cuota;

		$columnas= [
			['attribute'=>'fecha_format','contentOptions'=>['style'=>'width:40px;','class' => 'grilla'],'header' => 'Fecha'],
			['attribute'=>'operacion','contentOptions'=>['style'=>'width:150px;','class' => 'grilla'],'header' => 'Operacion'],
	   		['attribute'=>'cta_nom','contentOptions'=>['style'=>'width:250px;','class' => 'grilla'],'header' => 'Cuenta'],
	   		['attribute'=>'comprob','contentOptions'=>['style'=>'width:80px; text-align:center','class' => 'grilla'],'header' => 'Comprob'],
	   		['attribute'=>'debe', 'format' => ['decimal', 2], 'contentOptions'=>['style'=>'width:80px; text-align:right','class' => 'grilla'],'header' => 'Debe'],
	   		['attribute'=>'haber', 'format' => ['decimal', 2], 'contentOptions'=>['style'=>'width:80px; text-align:right','class' => 'grilla'],'header' => 'Haber'],
	   		['attribute'=>'est','contentOptions'=>['align'=>'center','style'=>'width:30px; text-align:center','class' => 'grilla'],'header' => 'Est'],
	   		['attribute'=>'modif','contentOptions'=>['style'=>'width:200px;','class' => 'grilla'],'header' => 'Modif'],
	    ];

		return SiteController::imprimirReporte($columnas, $sql, $titulo, $condicion);
	}

	public function actionTopera($top, $comp){

		$top= intval($top);
    	if ($top == 2 || $top == 13) {
    		return $this->redirect(['//ctacte/ddjj/view','id' => $comp]);
    	}elseif ($top == 3) {
    		return $this->redirect(['//caja/cajaticket/ticket','id' => $comp]);
    	}elseif ($top == 4 || $top == 12 || $top == 16){
    		return $this->redirect(['//ctacte/comp/view','id' => $comp]);
    	}elseif ($top == 5) {
    		return $this->redirect(['//ctacte/debito/debitocons','id' => $comp]);
    	}elseif ($top == 6 || $top == 7 || $top == 8) {
    		return $this->redirect(['//ctacte/convenio/plan','id' => $comp]);
    	}elseif ($top == 9 || $top == 10) {
			return $this->redirect(['//ctacte/facilida/view','id' => $comp]);
    	}elseif ($top == 11) {

    	}else {
    		echo "<script>history.go(-1)</script>";
    	}
    }

    /**
     *
     * Genera un archivo PDF con el resumen de la cuenta corriente del objeto dado.
     *
     *
     * $o codigo de objeto
     * $GET['f']= [fecha actual] - Fecha de consolidacion
     * $GET['d']= ([año actual] - [configuracion -> ctacte_anio_desde] * 1000 + 1) - Periodo desde,
     * $GET['h']= [año actual * 1000 + 999] - Periodo hasta,
     * $GET['p']= false - Planes vigentes
     * $GET['b']= false - Bajas
     * $GET['v']= false - Periodos vencidos
     * $GET['t']= '' - Tipo de objeto persona
     */
    public function actionImprimirresumen($o){

    	$samConfig = utb::samConfig();
    	$model= new CtaCte();
    	$deuda= 0;

    	//datos opcionales
    	$fchcons= Fecha::usuarioToBD(Yii::$app->request->get('f',  date('d/m/Y')));
    	$perdesde= Yii::$app->request->get('d', (date('Y') - $samConfig['ctacte_anio_desde']) * 1000 + 1);
    	$perhasta= Yii::$app->request->get('h', date('Y')*1000+999);
    	$est= trim(Yii::$app->request->get('e', ''));
    	$planvig= filter_var(Yii::$app->request->get('p', false), FILTER_VALIDATE_BOOLEAN);
    	$bajas= filter_var(Yii::$app->request->get('b', false), FILTER_VALIDATE_BOOLEAN);
    	$pervenc= filter_var(Yii::$app->request->get('v', false), FILTER_VALIDATE_BOOLEAN);
    	$tobjpersona= trim(Yii::$app->request->get('t', ''));

        $aniodesde = substr($perdesde, 0, 4);
        $cuotadesde = substr($perdesde, 4, 3);
    	$aniohasta = substr($perhasta, 0, 4);
    	$cuotahasta = substr($perhasta, 4, 3);


	    $resultado= $model->CtaCteResumen($deuda, $o, $fchcons, $perdesde, $perhasta, $est, $planvig, $tobjpersona, $pervenc);

		$provider= new ArrayDataProvider([
			'allModels' => $resultado,
			'totalCount' => count($resultado),
			'pagination' => false
		]);

		$tobj = utb::getTObj($o);
		$subreporte =  $model->GetSubReporte($tobj, $o);

		$nombreObjeto= utb::getNombObj("'$o'");
		$nombreTipoObjeto= utb::getTObjNom($o);

		$condicion= $this->armarCriterio(self::IMPRESION_RESUMEN,
										['codigoObjeto' => $o,
										'fechaConsolidacion' => $fchcons,
										'estado' => $est,
										'nombreObjeto' => $nombreObjeto,
										'nombreTipoObjeto' => $nombreTipoObjeto,
										'aniodesde' => $aniodesde,
										'cuotadesde' => $cuotadesde,
										'aniohasta' => $aniohasta,
										'cuotahasta' => $cuotahasta
										]);

		$pdf = Yii::$app->pdf;

      	$pdf->content = $this->renderPartial('//reportes/ctacteresumen',
      						['provider' => $resultado,'titulo' => 'Resumen de Cuenta Corriente',
								'condicion' => $condicion, 'subreporte' => $subreporte,'tobj' => $tobj]);

        return $pdf->render();
    }

    /**
     * $t Codigo tributo
     * $p Codigo plan
     * $o Codigo de objeto
     * $sc Sub cuenta
     * $f Fecha de consolidacion
     * $d Periodo desde
     * $h Periodo hasta
     * $e Estado
     * $b Baja
     * $v periodos vencidos
     */
    public function actionImprimirlistper($t, $p, $o, $sc, $f, $d, $h, $e, $b, $v){

    	$model= new CtaCte();

    	$totalnom= 0;
    	$totalnomcub= 0;
    	$totalmulta= 0;
    	$totalacc= 0;
    	$totalpag= 0;
    	$totalsaldo= 0;

    	$f= Fecha::usuarioToBD($f);

    	//para los tributos distintos a 1 y 3 el codigo de plan es siempre 0
    	if($t != 1 && $t != 3) {$p= 0;}

		$sql= '';
    	$datos= $model->CtaCteTributo($t, $p, $o, $sc, $f, $d, $h, $e, $b, $v, $sql);

    	$titulo = "Listado de Períodos";

    	$dataProvider= new ArrayDataProvider([
    		'allModels' => $datos,
    		'pagination' => false
    	]);

		$nombreObjeto= utb::getNombObj("'$o'");
		$nombreTipoObjeto= utb::getTObjNom($o);

		$d= intval($d);
		$h= intval($h);

		$aniodesde= intval($d / 1000);
		$cuotadesde= $d % 1000;
		$aniohasta= intval($h / 1000);
		$cuotahasta= $h % 1000;


		$condicion= $this->armarCriterio(self::IMPRESION_TODOS,
										['codigoObjeto' => $o,
										'nombreObjeto' => $nombreObjeto,
										'nombreTipoObjeto' => $nombreTipoObjeto,
										'fechaConsolidacion' => $f,
										'estado' => $e,
										'aniodesde' => $aniodesde,
										'cuotadesde' => $cuotadesde,
										'aniohasta' => $aniohasta,
										'cuotahasta' => $cuotahasta
										]);

		for ($i= 0; $i < count($datos); $i++){

			$totalnom += $datos[$i]['nominal'];
			$totalnomcub += $datos[$i]['nominalcub'];
			$totalmulta += $datos[$i]['multa'];
			$totalacc += $datos[$i]['accesor'];
			$totalpag += $datos[$i]['pagado'];
			$totalsaldo += $datos[$i]['saldo'];
		}


		$columnas= [
			['attribute'=>'anio','label' => 'Año','contentOptions'=>['style'=>'width:40px;text-align:center']],
        	['attribute'=>'cuota','label' => 'Cuota','contentOptions'=>['style'=>'width:40px;text-align:center']],
        	['attribute'=>'est','label' => 'Est','contentOptions'=>['style'=>'width:50px;text-align:center']],
        	['attribute'=>'nominal', 'format' => ['decimal', 2],'label' => 'Nominal','contentOptions'=>['style'=>'width:80px;text-align:right'],'footer' => Yii::$app->formatter->asDecimal($totalnom, 2),'footerOptions' => ['style' =>'border-top:1px solid;text-align:right;font-weight:bold']],
        	['attribute'=>'nominalcub', 'format' => ['decimal', 2],'label' => 'Nominal Cub.','contentOptions'=>['style'=>'width:80px;text-align:right'],'footer' => Yii::$app->formatter->asDecimal($totalnomcub, 2),'footerOptions' => ['style' =>'border-top:1px solid;text-align:right;font-weight:bold']],
        	['attribute'=>'multa', 'format' => ['decimal', 2],'label' => 'Multa','contentOptions'=>['style'=>'width:80px;text-align:right'],'footer' => Yii::$app->formatter->asDecimal($totalmulta, 2),'footerOptions' => ['style' =>'border-top:1px solid;text-align:right;font-weight:bold']],
        	['attribute'=>'accesor', 'format' => ['decimal', 2],'label' => 'Accesor','contentOptions'=>['style'=>'width:80px;text-align:right'],'footer' => Yii::$app->formatter->asDecimal($totalacc, 2),'footerOptions' => ['style' =>'border-top:1px solid;text-align:right;font-weight:bold']],
        	['attribute'=>'saldo', 'format' => ['decimal', 2], 'label' => 'Saldo','contentOptions'=>['style'=>'width:80px;text-align:right'],'footer' => Yii::$app->formatter->asDecimal($totalsaldo, 2),'footerOptions' => ['style' =>'border-top:1px solid;text-align:right;font-weight:bold']],
        	['attribute'=>'fchemi', 'format' => ['date', 'php:d/m/Y'],'label' => 'Fch.Emi','contentOptions'=>['style'=>'width:70px;']],
        	['attribute'=>'fchvenc', 'format' => ['date', 'php:d/m/Y'],'label' => 'Fch.Venc','contentOptions'=>['style'=>'width:70px;']],
        	['attribute'=>'fchpago', 'format' => ['date', 'php:d/m/Y'],'label' => 'Fch.Pago','contentOptions'=>['style'=>'width:70px']],
        	['attribute'=>'pagado', 'format' => ['decimal', 2],'label' => 'Pagado','contentOptions'=>['style'=>'width:80px;text-align:right'],'footer' => Yii::$app->formatter->asDecimal($totalpag, 2),'footerOptions' => ['style' =>'border-top:1px solid;text-align:right;font-weight:bold']],
        ];

		/*$totales= [
			'nominal' => Yii::$app->formatter->asDecimal($totalnom, 2),
			'nominalcub' => Yii::$app->formatter->asDecimal($totalnomcub, 2),
			'multa' => Yii::$app->formatter->asDecimal($totalmulta, 2),
			'accesor' => Yii::$app->formatter->asDecimal($totalacc, 2),
			'saldo' => Yii::$app->formatter->asDecimal($totalsaldo, 2),
			'pagado' => Yii::$app->formatter->asDecimal($totalpag, 2)
        ];*/

    	return SiteController::imprimirReporte($columnas, $sql, $titulo, $condicion);
    }

    /**
     * $o Codigo de objeto
     * $f Fecha de consolidacion
     * $d Periodo desde
     * $h Periodo hasta
     */
    public function actionImprimirperimpagos($o, $f, $d, $h){

    	$model= new CtaCte();
		$f= Fecha::usuarioToBD($f);

    	$provider = $model->CtacteObjeto($o, $f, $d, $h, "'D','C','J'", "trib_id,obj_id, Anio, Cuota");

		$nombreObjeto= utb::getNombObj("'$o'");
		$nombreTipoObjeto= utb::getTObjNom($o);

		$d= intval($d);
		$h= intval($h);
		$aniodesde= intval($d / 1000);
		$cuotadesde= $d % 1000;
		$aniohasta= intval($h / 1000);
		$cuotahasta= $h % 1000;

		$criterio= $this->armarCriterio(self::IMPRESION_TODOS,
										['codigoObjeto' => $o,
										'nombreObjeto' => $nombreObjeto,
										'nombreTipoObjeto' => $nombreTipoObjeto,
										'fechaConsolidacion' => $f,
										'aniodesde' => $aniodesde,
										'cuotadesde' => $cuotadesde,
										'aniohasta' => $aniohasta,
										'cuotahasta' => $cuotahasta
										],
										 ' - Períodos Impagos');

		$pdf = Yii::$app->pdf;
		$pdf->marginTop = '30px';
      	$pdf->content = $this->renderPartial('//reportes/ctactetodostrib',
      						['provider' => $provider,'titulo' => 'Detalle de Períodos de Cuenta Corriente <br> Todos los Tributos',
								'condicion' => $criterio,
								'text' => 'Cuenta Corriente - Períodos Impagos para todos los Tributos','tipo' => 'impagos']);

        return $pdf->render();
    }

    /**
     * $o Codigo de objeto
     * $f Fecha de consolidacion
     * $d Periodo desde
     * $h Periodo hasta
     * $e Estado
     */
    public function actionImprimircompleto($o, $f, $d, $h, $e){

    	$model= new CtaCte();
    	$f= Fecha::usuarioToBD($f);

    	$provider = $model->CtacteObjeto($o, $f, $d, $h, $e, "trib_id,obj_id, Anio, Cuota");

		$nombreObjeto= utb::getNombObj("'$o'");
		$nombreTipoObjeto= utb::getTObjNom($o);

		$d= intval($d);
		$h= intval($h);
		$aniodesde= intval($d / 1000);
		$cuotadesde= $d % 1000;
		$aniohasta= intval($h / 1000);
		$cuotahasta= $h % 1000;

		$condicion= $this->armarCriterio(self::IMPRESION_TODOS,
										['codigoObjeto' => $o,
										'nombreObjeto' => $nombreObjeto,
										'nombreTipoObjeto' => $nombreTipoObjeto,
										'fechaConsolidacion' => $f,
										'estado' => $e,
										'aniodesde' => $aniodesde,
										'cuotadesde' => $cuotadesde,
										'aniohasta' => $aniohasta,
										'cuotahasta' => $cuotahasta]);



		$pdf = Yii::$app->pdf;
		$pdf->marginTop = '30px';
      	$pdf->content = $this->renderPartial('//reportes/ctactetodostrib',
      						['provider' => $provider,'titulo' => 'Detalle de Períodos de Cuenta Corriente <br> Todos los Tributos',
								'condicion' => $condicion,
								'text' => 'Cuenta Corriente - Períodos Impagos para todos los Tributos']);

        return $pdf->render();
    }

    /**
     * $o Codigo de objeto
     * $t Codigo tributo
     * $c Cuota
     * $a Anio
     * $cc Codigo de cuenta corriente
     * $sc sub cuenta
     */
    public function actionImprimircomprobante($t, $o= '', $c= '', $a= '', $cc= '', $sc= '',$sem=0){

    	$codigoError= 0;

    	if ($t == 1){
    		 return $this->redirect(['//ctacte/convenio/imprimircomprobantevalida', 'id' => $a, 'desde' => $c,
						'hasta' => $c,'ext'=>1]);
    	}
    	// si $t == 3 contrib por mejoras
    	if ($t == 3){
    		 return $this->redirect(['//ctacte/mejoraplan/imprimircomprobantevalida', 'id' => $a, 'desde' => $c,
						'hasta' => $c,'ext'=>1]);
    	}
    	// si es Ingreso Bruto
		if (utb::getTTrib($t) == 2){
			$dj = ddjj::findOne(['ctacte_id' => $cc]);
			if ($dj !== null)
				return $this->redirect(['//ctacte/ddjj/imprimir', 'id' => $dj->dj_id,
						'hasta' => $c,'ext'=>1]);
		}

    	switch ( intval(utb::getTTrib($t)) ){

    		case 0:
			case 1:
			case 3:
			case 4:
			case 5:
			case 7:
				if($cc == 0){
    				Yii::$app->session->setFlash(self::ERROR, $this->errores(1));
    				break;
    			}

    			Liquida::GenerarEstCta($t, $o, $sc);
				return $this->redirect(['//ctacte/liquida/imprimircomprobante', 'id' => $cc,'sem'=>$sem]);
				break;

    		case 2: // Tipo Declarativo
    			// Muestro DDJJ
                /*    DJ_id = objDJ.GetDJ_id(dgLista.Item(dgLista.CurrentRowIndex, 0))
                    If DJ_id <> 0 Then
                        objDJ.Imprimir(DJ_id)
                    Else
                        MsgBox("No se encontró Declaración Jurada", MsgBoxStyle.Information, "Impresión de Comprobante")
                        Emi_id = Val(dgLista.Item(dgLista.CurrentRowIndex, 0))
                        objLiq.ImprimirLiquida(Emi_id)
                    End If	*/
               return $this->redirect(['//ctacte/liquida/imprimircomprobante', 'id' => $cc,'sem'=>$sem]);
               break;
    	}

    	return $this->redirect(['index', 'obj_id' => $o]);
    }

	/**
     * $cc Codigo cuenta corriente
     * $o Codigo objeto
     */
    public function actionConstanciapago($cc, $o){

    	$model= new Liquida();
		$mdp = "";
    	$datos = $model->ConstanciaPago($cc,$mdp);
    	$codigoError= 0;

		if (count($datos) > 0)
			if ($datos[0]['est'] != 'P'){

				Yii::$app->session->setFlash(self::ERROR, $this->errores(2));
				return $this->redirect(['index', 'obj_id' => $o]);
			}

		$pdf = Yii::$app->pdf;
		$pdf->marginTop = '30px';
      	$pdf->content = $this->renderPartial('//reportes/ctacteconstanciapago',
      						['datos' => $datos,'titulo' => 'Constancia de Pago','mdp' => $mdp]);

        return $pdf->render();
    }

    /**
     * $o string Codigo de objeto
     */
    public function actionPlannuevo($o){
    	return $this->redirect(['ctacte/convenio/plannuevo', 'o' => $o]);
   	}

   	/**
   	 * $o Codigo de objeto
   	 * $t Codigo de tributo
   	 * $d Periodo desde
   	 * $h Periodo hasta
   	 *
   	 * TODO REVISAR QUE FUNCIONA CORRECTAMENTE
   	 */
   	public function actionGeneraracilida()
   	{
   		$o = $_POST['codigoObjetoGenerarFacilidad'];
		$t = $_POST['codigoTributoGenerarFacilidad'];
		$d = $_POST['periododesdeGenerarFacilidad'];
		$h = $_POST['periodohastaGenerarFacilidad'];
		$fv = $_POST['fchvencGenerarFacilidad'];

		$model = new Facilida();
   		$codigoError= 0;
   		$codigoFacilidad= 0;
   		$array= array();

   		$periodos= $model->cargarDeudaDetalle($o, $t, $d, $h, false, $fv, $fv, true, false);
   		$i=0;

   		foreach ($periodos as $per)
   			$array[$i++] = $per['ctacte_id'];

   		$calculo = $model->calcularFacilida($array, $t, $o, false, $periodos);

   		if ($calculo != ''){

   			Yii::$app->session->setFlash(self::ERROR, $calculo);
   			return $this->redirect(['index', 'obj_id' => $o]);
   		}

   		if ($codigoError == 0) $codigoFacilidad = $model->grabarFacilida($t, $o, $fv, $fv, '', 1);
   		if ($codigoFacilidad == 0) $codigoError = 3;

		//if ($error == '') Yii::$app->session['msn_ctacte'] = "La Facilidad fue generada";
		if ($codigoError == 0) return $this->redirect(['//ctacte/facilida/view', 'id' => $codigoFacilidad]);

		return $this->redirect(['index', 'e' => $codigoError, 'obj_id' => $o]);
   	}


    public function actionReliquidar($o, $t= 0, $pn= false){

    	$model= new CtaCte();
    	$error = "";
    	$pagos= false;
    	$anioDesde= 0;
    	$cuotaDesde= 0;
    	$anioHasta= 0;
    	$cuotaHasta= 999;

    	if (Yii::$app->request->isPost){

    		$trib_id= intval(Yii::$app->request->post('codigoTributo', 0));
    		$t= $trib_id;

    		$anioDesde= intval(Yii::$app->request->post('anioDesde', 0));
    		$cuotaDesde= intval(Yii::$app->request->post('cuotaDesde', 0));
    		$perdesde= $anioDesde * 1000 + $cuotaDesde;

    		$anioHasta= intval(Yii::$app->request->post('anioHasta', 0));
    		$cuotaHasta= intval(Yii::$app->request->post('cuotaHasta', 999));
    		$perhasta= $anioHasta * 1000 + $cuotaHasta;

    		$obj_id= trim(Yii::$app->request->post('codigoObjeto', ''));
    		$subcta= intval(Yii::$app->request->post('subCuenta', 0));
    		$pagos= filter_var(Yii::$app->request->post('reliquidarPeriodosPagos', false), FILTER_VALIDATE_BOOLEAN);

    		$num=  filter_var(Yii::$app->request->post('porNum', false), FILTER_VALIDATE_BOOLEAN);
    		$pn= $num;

    		$error = $model->EmitirRango($trib_id,$perdesde,$perhasta,$obj_id,$subcta,$pagos,$num);

    		if($error == '') return $this->redirect(['index', 'obj_id' => $obj_id, 'm' => 7]);
    	}

		$parametros= array_merge($this->extrasIndex(),
								['obj_id' => $o,
								'subcta' => 0,
								'codigoTributo' => $t,
								'porNum' => $pn,
								'errorReliquidar' => $error,
								'anioDesde' => $anioDesde,
								'cuotaDesde' => $cuotaDesde,
								'anioHasta' => $anioHasta,
								'cuotaHasta' => $cuotaHasta,
								'reliquidarPeriodosPagos' => $pagos
								]);

    	return $this->render('emisionliq', $parametros);
    }

    public function actionEliminarliq(){

    	$model= new CtaCte();

    	$trib = intval(Yii::$app->request->post('codigoTributoEliminarLiquidacion', 0));
    	$cc= intval(Yii::$app->request->post('cuentaCorrienteEliminarLiquidacion', 0));
    	$motivo= trim(Yii::$app->request->post('motivoEliminarLiquidacion', ''));
    	$obj_id= trim(Yii::$app->request->post('obj_id', ''));
    	$error= '';

    	$tipoTributo= utb::getTTrib($trib);

    	//Si el tributo es Gtos. Jud. o Cem. Alq. o si es tipo emisión, eventual o periódico
        if (!($trib == 5 || $trib == 8 || $tipoTributo == 1 || $tipoTributo == 3 || $tipoTributo == 4)){

			if ( $tipoTributo == 2) { // Declarativo
				// Verifico si existe DJ asociada con el período
				$anio = Yii::$app->db->createCommand("Select anio from ctacte where ctacte_id =".$cc)->queryScalar();
				$cuota = Yii::$app->db->createCommand("Select cuota from ctacte where ctacte_id =".$cc)->queryScalar();

				$sql = "Select count(*) from ddjj where trib_id=".$trib." and obj_id='".$obj_id."' and anio=".$anio." and cuota=".$cuota;
				$cant = Yii::$app->db->createCommand($sql)->queryScalar();
				if ($cant > 0) {
					Yii::$app->session->setFlash(self::ERROR, $this->errores(5));
					return $this->redirect(['index', 'obj_id' => $obj_id]);
				}
			} else {
        	  Yii::$app->session->setFlash(self::ERROR, $this->errores(5));
        	  return $this->redirect(['index', 'obj_id' => $obj_id]);
			}
       	}

        $estadoCuentaCorriente= utb::getCampo('ctacte', "ctacte_id = $cc", 'est');
        if ($estadoCuentaCorriente != 'D'){

        	Yii::$app->session->setFlash(self::ERROR, $this->errores(6));
        	return $this->redirect(['index', 'obj_id' => $obj_id]);
        }

        $error = $model->BorrarLiquida($cc, $motivo);

        if($error != ''){

        	Yii::$app->session->setFlash(self::ERROR, $error);
        	return $this->redirect(['index', 'obj_id' => $obj_id]);
        }

		return $this->redirect(['index', 'm' => 1, 'obj_id' => $obj_id]);
    }
	
	public function actionEliminarreliq(){

    	$model= new CtaCte();

    	$trib = intval(Yii::$app->request->post('codigoTributoEliminarReLiquidacion', 0));
    	$cc= intval(Yii::$app->request->post('cuentaCorrienteEliminarReLiquidacion', 0));
    	$obj_id= trim(Yii::$app->request->post('obj_id', ''));
    	$error= '';

    	$tipoTributo= utb::getTTrib($trib);

    	//Si el tributo es Gtos. Jud. o Cem. Alq. o si es tipo emisión, eventual o periódico
        if (!($trib == 5 || $trib == 8 || $tipoTributo == 1 || $tipoTributo == 3 || $tipoTributo == 4)){

			if ( $tipoTributo == 2) { // Declarativo
				// Verifico si existe DJ asociada con el período
				$anio = Yii::$app->db->createCommand("Select anio from ctacte where ctacte_id =".$cc)->queryScalar();
				$cuota = Yii::$app->db->createCommand("Select cuota from ctacte where ctacte_id =".$cc)->queryScalar();

				$sql = "Select count(*) from ddjj where trib_id=".$trib." and obj_id='".$obj_id."' and anio=".$anio." and cuota=".$cuota;
				$cant = Yii::$app->db->createCommand($sql)->queryScalar();
				if ($cant > 0) {
					Yii::$app->session->setFlash(self::ERROR, $this->errores(5));
					return $this->redirect(['index', 'obj_id' => $obj_id]);
				}
			} else {
        	  Yii::$app->session->setFlash(self::ERROR, $this->errores(5));
        	  return $this->redirect(['index', 'obj_id' => $obj_id]);
			}
       	}

        $estadoCuentaCorriente= utb::getCampo('ctacte', "ctacte_id = $cc", 'est');
        if ($estadoCuentaCorriente != 'D'){

        	Yii::$app->session->setFlash(self::ERROR, $this->errores(6));
        	return $this->redirect(['index', 'obj_id' => $obj_id]);
        }

        $error = $model->BorrarReLiquida($cc);

        if($error != ''){

        	Yii::$app->session->setFlash(self::ERROR, $error);
        	return $this->redirect(['index', 'obj_id' => $obj_id]);
        }

		return $this->redirect(['index', 'm' => 1, 'obj_id' => $obj_id]);
    }

	public function actionEditarperiodo(){

    	$model= new CtaCte();

    	$cc= intval(Yii::$app->request->post('cuentaCorrienteEditarPeriodo', 0));
    	$anio = intval(Yii::$app->request->post('periodoAnio', 0));
		$cuota = intval(Yii::$app->request->post('periodoCuota', 0));
		$obj_id= trim(Yii::$app->request->post('obj_id', ''));

    	$error= '';

        $error = $model->EditarPeriodo($cc, $anio,$cuota);

        if($error != ''){

        	Yii::$app->session->setFlash(self::ERROR, $error);
        	return $this->redirect(['index', 'obj_id' => $obj_id]);
        }

		return $this->redirect(['index', 'm' => 1, 'obj_id' => $obj_id]);
    }

    public function actionIrplan($id,$trib){
    	/*
    	 * Si trib = 1 => 'Plan' (Proceso = 3340)
    	 * Si trib = 2 => 'Facilidad' (Proceso = 3440)
    	 * Si trib = 3 => 'Contrib. Mejoras' (Proceso = 3390)
    	 * */

    	$proceso= 0;
    	$url= ['//site/nopermitido'];

		$trib= intval($trib);
		switch($trib){

			case 1:
				$proceso= 3340;
				$url= ['//ctacte/convenio/plan', 'id' => $id];
				break;

			case 2:
				$proceso= 3340;
				$url= ['//ctacte/facilidad/view', 'id' => $id];
				break;

			case 3:
				$proceso= 3390;
				$url= ['//ctacte/mejoraplan/index', 'id' => $id];
				break;
		}

		if($proceso == 0 || !utb::getExisteProceso($proceso)) $url= ['//site/nopermitido'];

		return $this->redirect($url);
	}

	public function actionCuponpago()
	{
		$obj = trim(Yii::$app->request->post('objetoCuponPago', ''));
		$trib= intval(Yii::$app->request->post('tributoCuponPago', 0));
		$fecha = trim(Yii::$app->request->post('fechaCuponPago', ''));

		$pdf = Yii::$app->pdf;
		$pdf->marginTop = '30px';
      	$pdf->content = $this->renderPartial('//reportes/cuponpago',['datos' => Ctacte::CuponPago($obj,$trib,$fecha),'fchvenc'=>$fecha]);

        return $pdf->render();
	}

	public function actionDdjjfaltante($o, $t= 0){

    	$model= new Ddjj();
    	$error = "";
    	$pagos= false;
    	$anioDesde= 0;
    	$cuotaDesde= 0;
    	$anioHasta= 0;
    	$cuotaHasta= 999;

    	if (Yii::$app->request->isPost){

    		$trib_id= intval(Yii::$app->request->post('codigoTributo', 0));
    		$t= $trib_id;

    		$anioDesde= intval(Yii::$app->request->post('anioDesde', 0));
    		$cuotaDesde= intval(Yii::$app->request->post('cuotaDesde', 0));
    		$perdesde= $anioDesde * 1000 + $cuotaDesde;

    		$anioHasta= intval(Yii::$app->request->post('anioHasta', 0));
    		$cuotaHasta= intval(Yii::$app->request->post('cuotaHasta', 999));
    		$perhasta= $anioHasta * 1000 + $cuotaHasta;

    		$obj_id= trim(Yii::$app->request->post('codigoObjeto', ''));
    		$subcta= intval(Yii::$app->request->post('subCuenta', 0));
    		$pagos= filter_var(Yii::$app->request->post('reliquidarPeriodosPagos', false), FILTER_VALIDATE_BOOLEAN);

    		$error = $model->EliminarDDJJFaltante($trib_id,$obj_id,$subcta,$perdesde,$perhasta);

    		if($error == '') return $this->redirect(['index', 'obj_id' => $obj_id, 'm' => 7]);
    	}

		$parametros= array_merge($this->extrasIndex(),
								['obj_id' => $o,
								'subcta' => 0,
								'codigoTributo' => $t,
								'errorDJFaltante' => $error,
								'anioDesde' => $anioDesde,
								'cuotaDesde' => $cuotaDesde,
								'anioHasta' => $anioHasta,
								'cuotaHasta' => $cuotaHasta
								]);

    	return $this->render('ddjjfaltante', $parametros);
    }


    /**
     * TODO Comentar
     */
    private function armarCriterio($origen= self::IMPRESION_RESUMEN, $datos= [], $concatenar= ''){

    	$completo= 'Objeto: ' . $datos['nombreTipoObjeto'] . ' ' . $datos['codigoObjeto'] . ' - ' . $datos['nombreObjeto'];

		if (utb::getTObj($datos['codigoObjeto']) == 1){
			$completo .= "<br> -Manz.: " . utb::getCampo("inm","obj_id='" . $datos['codigoObjeto'] . "'","manz");
			$completo .= " -Parc: " . utb::getCampo("inm","obj_id='" . $datos['codigoObjeto'] . "'","parc");
			$completo .= " -UF: " . utb::getCampo("inm","obj_id='" . $datos['codigoObjeto'] . "'","uf") . "<br>";
		}
		if (utb::getTObj($datos['codigoObjeto']) == 5){
			$completo .= "<br> -Dominio: " . utb::getCampo("rodado","obj_id='" . $datos['codigoObjeto'] . "'","dominio");
		}

    	switch($origen){

    		case self::IMPRESION_RESUMEN:
    		case self::IMPRESION_TODOS:

    			$completo .= (in_array('estado', $datos) && $datos['estado'] != '' ? ". "."<br>Estado: ".$datos['estado'] : "");
    			break;

    		default:

    			if($datos['codigoTipoObjeto'] == 1) $completo .= '. Nomenclatura: ' . $datos['datoObjeto'];
    			if($datos['codigoTipoObjeto'] == 5) $completo .= '. Dominio: ' . $datos['datoObjeto'];

    			$completo .= '<br>Tributo: ' . $datos['nombreTributo'];
    	}

    	if (utb::getTObj($datos['codigoObjeto']) != 1) $completo .= ",";

		if($origen != 'selec')
    		$completo .= ' Período Desde: ' . $datos['aniodesde'] . '/' . $datos['cuotadesde'] . ', Hasta: ' . $datos['aniohasta'] . '/' . $datos['cuotahasta'];
    	else $completo .= ' Sólo Períodos Seleccionados';

    	$completo .= '<br>Fecha: ' . $datos['fechaConsolidacion'] . ', Sólo Períodos Seleccionados';

    	return $completo . $concatenar;
    }

    private function mensajes($codigo){

    	switch($codigo){

    		case 1: return 'La liquidación fue eliminada';
    		case 7: return 'El Objeto fue reliquidado';
    	}

    	return null;
    }

    private function errores($codigo){


    	switch($codigo){

    		case 1: return 'No se puede mostrar el comprobante. Consulte si está emitido';
    		case 2: return 'El comprobante no se encuentra Pago';
//    		case 3: return 'Hubo en error al grabar la facilidad';
//    		case 4: return 'No hay Períodos para calcular la Facilidad'; //este codigo esta sujeto al modelo Facilidad. No deberia estar aca, pero es la solucion momentanea encontrada
    		case 5: return 'Por el tipo de tributo no podrá eliminar la liquidación';
    		case 6: return 'El comprobante no se encuentra en deuda';
    	}

    	return null;
    }
}
