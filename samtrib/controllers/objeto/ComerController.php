<?php

namespace app\controllers\objeto;

use Yii;
use app\models\objeto\Comer;
use app\models\objeto\ComerRubro;
use app\models\objeto\Objeto;
use app\models\objeto\Persona;
use app\models\objeto\Domi;
use app\models\objeto\ComerListado;

use yii\data\ArrayDataProvider;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Session;

use app\utils\db\utb;
use app\utils\db\Fecha;

/**
 * ComerController implements the CRUD actions for Comer model.
 */
class ComerController extends Controller
{

    private $cargarArreglosDesdeBD = false;   //Variable que indica si se deben cargar los arreglos de rubros desde BD.

    const CONST_MENSAJE                 = 'comer_mensaje';
    const CONST_MENSAJE_ERROR           = 'comer_mensaje_error';
    const CONST_LAST_ID                 = 'comer_last_id';
    const CONST_COMER_ARRAY_RUBROS      = 'comer_array_rubros';
    const CONST_COMER_ARRAY_TITULARES   = 'arregloTitulares';
	const CONST_COMER_ID				= 'comer_id';

    public function beforeAction( $action ){

        //Verificar que haya un usuario conectado
        if( count( Yii::$app->user->id ) == 0 ){

            return $this->redirect(['//site/index']);
        }

        $id = $action->getUniqueId();

        if( in_array( $id, [ 'objeto/rubro/sugerenciarubro', 'objeto/rubro/codigorubro', 'objeto/rubro/nombrerubro' ] ) ){
            return true;
        }

		$id_comer = isset($_GET['id']) ? $_GET['id'] : 0;

        if( $id != Yii::$app->session[ self::CONST_LAST_ID ] or $id_comer != Yii::$app->session[ self::CONST_COMER_ID ] ){

            $this->setRubros( [] );
            $this->setTitulares();
            $this->cargarArreglosDesdeBD = true;
        }

        Yii::$app->session[ self::CONST_LAST_ID ] = $id ;
		Yii::$app->session[ self::CONST_COMER_ID ] = $id_comer;

        return true;
    }

    /**
     * Función que se utiliza
     */
    public function actionBuscar(){

        /**
         * @param integer $_POST['opcion'] Opcion de busqueda:
         * 	1 = Busqueda por obj_id (codigo del objeto)
         * 	2 = Busqueda por cuit
         * 	3 = Busqueda por legajo
         * 	4 = Busqueda por ingresos brutos
         */

    	$cond = $descripcion = '';
		$objeto = null;
		$cant = false;

		$opcion = intval(Yii::$app->request->post( 'opcion', 0 ) );
		$vista = 'v_comer';

		switch( $opcion ){

			case 1 :
				$objeto = trim( Yii::$app->request->post('txCodigoComercio', '' ) );
				if (strlen($objeto) < 8) $objeto = utb::GetObjeto(2,(int)$objeto);

      			if ( utb::getNombObj( "'".$objeto."'" ) == '' ){

                    Yii::$app->session->setFlash( self::CONST_MENSAJE_ERROR, 1001 );
                }

      			return $this->redirect([ 'view', 'id' => $objeto ]);
				break;

			case 2 :
				$cuit = trim(Yii::$app->request->post('txCuit', ''));
				$cond = "cuit = '" . str_replace('-', '', $cuit) . "'";
				break;

			case 3 :
				$legajo= trim(Yii::$app->request->post('txLegajo', ''));

				$cond = "upper(legajo) Like upper('%" . $legajo . "%')";
				$descripcion= "Legajo contiene $legajo";

				break;

			default: return $this->render( 'view' );
		}

		if($cond != '') $cant = (int) utb::getCampo($vista, $cond, 'count(Distinct obj_id)');

		if($cant !== false){

			switch($cant){

				case 0 :

                    Yii::$app->session->setFlash( self::CONST_MENSAJE_ERROR, 11 );

                    return $this->redirect(['view', 'id' => '']);
                    break;

				case 1 :
                    return $this->redirect( [ 'view', 'id' => utb::getCampo( $vista, $cond, 'obj_id' ) ] ); break;

				default :
                    // Si son mas de uno los resultados,tengo que cargar el listado
                    $model = new ComerListado();
                    $model->obj_id_desde = Yii::$app->request->post('txCodigoComercio', null);
                    $model->obj_id_hasta = Yii::$app->request->post('txCodigoComercio', null);
                    $model->cuit = Yii::$app->request->post('txCuit', null);
                    $model->legajo = Yii::$app->request->post('txLegajo', null);

                    $res = $model->buscar();
                    $datos = ListadocomerController::datosResultado($model, $res);
                    $dataProviderResultados = new ActiveDataProvider([
                        'query' => $res,
                        'key' 		=> $model->pk(),
                        'pagination' => ['pageSize' => 60,],
                        'sort'	=> $model->sort(),
                    ]);
                    return $this->render('//listado/base_resultado', [
            									'breadcrumbs' => $datos['breadcrumbs'],
            									'descripcion' => '',
            									'model' => $model,
            									'dataProviderResultados' => $dataProviderResultados,
            									'columnas' => $datos['columnas'],
            									'urlOpciones' => $datos['urlOpciones'],
            								]);

			}
		}

        Yii::$app->session->setFlash( self::CONST_MENSAJE_ERROR, 11 );

		return $this->redirect(['view', 'id' => '']);
    }

    public function actionView( $id = '' ){

        return $this->view( $id, 1 );
    }

    public function actionCreate(){

        return $this->view( '', 0 );
    }

    public function actionUpdate( $id ){

        return $this->view( $id, 3 );
    }

    public function actionDelete( $id ){

        return $this->view( $id, 2 );

    }

    /**
     * Función que se utiliza para el ABM de comercio.
     * @param string $id Identificador de comercio.
     * @param integer $action Identificador del tipo de acción.
     */
    public function view( $id = '', $action = 1 ){

        $model = $this->findModel( $id );

        $modelObjeto                = $this->findModelObjeto( $id );

        $responsablePrincipal       = $this->findModelVPersona( $modelObjeto->num );
        $modelDomicilioPostal       = (new Domi())->cargarDomi('OBJ', $model->obj_id, 0);
		$modelDomicilioParcelario   = (new Domi())->cargarDomi('COM', $model->obj_id, 0);

        $modelRubroTemporal         = [];       //Almacenará el modelo de rubro que se modifica.
        $mostrarModalRubros         = false;    //Indica si se debe mostrar la ventana modal de rubros.

        $dadosDeBaja                = 0;    //Filtro que muestra los rubros "Dados de baja".
        $soloVigentes               = 0;    //Filtro que muestra los rubros "Vigentes".

        if( $this->cargarArreglosDesdeBD ){

            //Cargar los rubros en memoria
            $this->setRubros( ComerRubro::getRubros( $id ) );

            $modelObjeto->obtenerTitularesDeBD( $modelObjeto->obj_id );
            $this->setTitulares( $modelObjeto->arregloTitulares );
        }

        $tab = 1;

        if( Yii::$app->request->isPjax ){

            //Se copian los domicilios
            if( Yii::$app->request->get( '_pjax', '' ) == "#comer_form_pjaxCopiarDomicilio" ){

                $modelDomicilioParcelario   = unserialize(urldecode(stripslashes( Yii::$app->request->get( 'arrayDomicilioParcelario' ))));
                $modelDomicilioPostal       = $modelDomicilioParcelario;

            }

            if( Yii::$app->request->get( '_pjax', '' ) == "#comer_form_pjaxCambiaInmueble" ){

                $obj_id = Yii::$app->request->get( 'obj_id', '' );

                $model->inmueble = utb::getObjeto( 1, $obj_id );

                //Verificar que el objeto exista
                if( utb::verificarExistenciaObjeto( 1, "'" . $model->inmueble . "'" ) ){

                    $model->inmueble_nom = utb::getNombObj( "'" . $model->inmueble . "'" );

                } else {
                    $model->inmueble = '';
                    $model->inmueble_nom = '';
                }
            }

            if( Yii::$app->request->get( '_pjax', '' ) == "#comer_form_pjaxCambiaRodados" ){

                $obj_id = Yii::$app->request->get( 'obj_id', '' );

                $model->rodados = utb::getObjeto( 5, $obj_id );

                //Verificar que el objeto exista
                if( utb::verificarExistenciaObjeto( 5, "'" . $model->rodados . "'" ) ){

                    $model->rodados_nom = utb::getNombObj( "'" . $model->rodados . "'" );
                } else {
                    $model->rodados = '';
                    $model->rodados_nom = '';

                    Yii::$app->session->setFlash( self::CONST_MENSAJE_ERROR, 1001 );
                }
            }

            if( Yii::$app->request->get( '_pjax', '' ) == "#comer_rubro_pjaxGrilla" ){

                $dadosDeBaja    = intVal( Yii::$app->request->get( 'filtroBaja', 0 ) );
                $soloVigentes   = intVal( Yii::$app->request->get( 'filtroVigente', 0 ) );
                $nuevoRubro     = new ComerRubro();
                $txAction       = Yii::$app->request->get( 'txAction', 1 );

                switch( $txAction ){

                    case 0: $nuevoRubro->setScenario( 'insert' ); break;
                    case 2: $nuevoRubro->setScenario( 'delete' ); break;
                    case 3: $nuevoRubro->setScenario( 'update' ); break;
                }

                if( $nuevoRubro->load( Yii::$app->request->get() ) ){

                    $nuevoRubro->obj_id     = $modelObjeto->obj_id;
                    $rubros = $this->getRubros();

                    if( Comer::ABMRubrosEnArreglo( $rubros, $nuevoRubro, $txAction ) ){

                        $this->setRubros( $rubros );

                    } else {
                        $modelRubroTemporal = $nuevoRubro;
                        $mostrarModalRubros = true;

                    }

                }
            }
        }

        if( Yii::$app->request->isPost ){

            $action = Yii::$app->request->post( 'txActionForm', 1 );

            switch( $action ){

                case 0: $model->setScenario( 'insert' ); break;
                case 2: $model->setScenario( 'delete' ); break;
                case 3: $model->setScenario( 'update' ); break;
            }

    		if( $modelObjeto->load( Yii::$app->request->post() ) && $model->load(Yii::$app->request->post() ) ){

                //se obtienen los datos del domicilio postal y el parcelario
                $modelDomicilioPostal =  unserialize( urldecode( stripslashes( Yii::$app->request->post( 'arrayDomicilioPostal', '' ) ) ) );
                $modelDomicilioParcelario =  unserialize( urldecode( stripslashes( Yii::$app->request->post( 'arrayDomicilioParcelario', '' ) ) ) );

    			$model->domicilioPostal = $modelDomicilioPostal;
    			$model->domicilioParcelario = $modelDomicilioParcelario;
    			$model->rubros = $this->getRubros();
				$model->titulares = unserialize( urldecode( stripslashes( Yii::$app->request->post( 'arrayTitulares', '' ) ) ) );

    			$res = $model->grabar( $modelObjeto, $action );

    			if( $res ){
    				Yii::$app->session->setFlash( self::CONST_MENSAJE, 1 );
    				return $this->redirect(['view', 'id' => $model->obj_id]);
    			}

                //La fecha se formatea asi para que se visualice correctamente en el DatePicker
                $model->fchhab = Fecha::usuarioToDatePicker( $model->fchhab );
                $model->fchvenchab = Fecha::usuarioToDatePicker( $model->fchvenchab );
                $modelObjeto->fchbaja = Fecha::usuarioToDatePicker( $modelObjeto->fchbaja );
    		}
    	}

        $dataProviders = [

            'dpTitulares'   => new ArrayDataProvider([
                'allModels' => $model->titulares,
            ]),

            'dpRubros'      => new ArrayDataProvider([
                'allModels' => $this->getRubrosSegunFiltro( $this->getRubros(), $dadosDeBaja, $soloVigentes ),
            ]),
        ];

        return $this->render( 'view', [

            'mensaje'       => $this->getMensaje( Yii::$app->session->getFlash( self::CONST_MENSAJE, 0 ) ),
            'error'         => $this->getMensaje( Yii::$app->session->getFlash( self::CONST_MENSAJE_ERROR, 0 ) ),
            'model'         => $model,
            'modelObjeto'   => $modelObjeto,
            'responsablePrincipal'      => $responsablePrincipal,
            'modelDomicilioPostal'      => $modelDomicilioPostal,
            'modelDomicilioParcelario'  => $modelDomicilioParcelario,

            //Especiales
            'modelRubroTemporal'        => $modelRubroTemporal,
            'mostrarModalRubros'        => $mostrarModalRubros,

            'dadosDeBaja'               => $dadosDeBaja,
            'soloVigentes'              => $soloVigentes,

            'action'        => $action,

            'dataProviders'     => $dataProviders,
            'arrayRubros'       => $this->getRubros(),

            'tab'               => $tab,

            'tipoHabilitacion'  => Comer::getTipoHabilitacion(),
            'tipoComercio'      => Comer::getTipoComercio(),
            'arrayZonas'        => Comer::getZona(),
            'arrayTipoVinculo'  => Comer::getTipoVinculo(),
            'arrayIB'           => Comer::getIngresosBrutos(),

            //Menú Derecho
            'realizaDDJJ'       => Comer::realizaDDJJ(),
        ]);
    }

    /**
     * Función que se utiliza para imprimir los datos de un comercio.
     */
    public function actionImprimir( $id = '' )
    {
        if ( $id == '' ){
            Yii::$app->session->setflash( self:: CONST_MENSAJE_ERROR, 1002 );

            return $this->redirect([ 'view' ]);
        }

    	$sub1 = null;
    	$sub2 = null;
    	$sub3 = null;
    	$sub4 = null;
    	$array = (new Comer)->Imprimir($id,$sub1,$sub2,$sub3,$sub4);

		$modelDomicilioPostal       = (new Domi())->cargarDomi('OBJ', $id, 0);
		$modelDomicilioParcelario   = (new Domi())->cargarDomi('COM', $id, 0);

    	$pdf = Yii::$app->pdf;
    	$pdf->marginTop = '30px';
		$pdf->content = $this->renderPartial('//reportes/comer',
							['datos' => $array,'sub1'=>$sub1,'sub2'=>$sub2,'sub3'=>$sub3,'sub4'=>$sub4,'ib'=>0,'modelDomicilioPostal' => $modelDomicilioPostal,'modelDomicilioParcelario' => $modelDomicilioParcelario]);

        return $pdf->render();
    }

    /**
     * Función que se utiliza para imprimir la constancia de habilitación de un comercio.
     */
    public function actionConstanciahabil( $id = '' )
    {

        if ( $id == '' ){
            Yii::$app->session->setflash( self:: CONST_MENSAJE_ERROR, 1002 );

            return $this->redirect([ 'view' ]);
        }

		$modelComer = $this->findModel( $id );
		$domicilioParcelario = (new Domi())->cargarDomi( 'COM', $id, 0 );
		$modelComer->rubros = ComerRubro::find()->where(['obj_id' => $id, 'est' => 'A'])->orderBy('subcta')->All();

		$modelObjeto = Objeto::findOne($id);
		$modelObjeto->obtenerTitularesDeBD( $id );

		$responsablePrincipal       = $this->findModelVPersona( $modelObjeto->num );

		$pdf = Yii::$app->pdf;
		//$pdf->methods["SetHeader"] = '';
		$pdf->marginTop = '30px';
		$pdf->content = $this->renderPartial('//reportes/constanciahabil',[
			'model' => $model,
            'modelComer' => $modelComer,
            'modelObjeto' => $modelObjeto,
            'domicilioParcelario' => $domicilioParcelario->domicilio,
			'responsablePrincipal'      => $responsablePrincipal,
		]);

		return $pdf->render();

    }

    /**
     * Función que se utiliza para realizar la habilitación de un comercio.
     * @param string $id Identificador de comercio.
     */
    public function actionHabilitacion( $id = '' ){

        if ( $id == '' ){
            Yii::$app->session->setflash( self:: CONST_MENSAJE_ERROR, 1002 );

            return $this->redirect([ 'view' ]);
        }

        $model          = $this->findModel( $id );
        $modelObjeto    = $this->findModelObjeto( $id );

        //Nueva fecha de habilitación debe ser igual a la última fecha de vencimiento
        $model->hab_fchhab = $model->fchvenchab;

        //Acción que se ejecuta cuando se graban los datos
        if( Yii::$app->request->isPost ){

            $model->setScenario( 'habilitacion' );

            if( $model->load( Yii::$app->request->post() ) && $model->habilitar( $modelObjeto ) ){

                Yii::$app->session->setFlash( self::CONST_MENSAJE, 2 );

                return $this->redirect([ 'view', 'id' => $model->obj_id ]);

            } else {

                //La fecha se formatea así para que se visualice correctamente en el DatePicker
                $model->hab_fchhab      = Fecha::usuarioToDatePicker( $model->hab_fchhab );
                $model->hab_fchvenchab  = Fecha::usuarioToDatePicker( $model->hab_fchvenchab );

            }

        }

        return $this->render('_form_habilitacion', [
            'model'         => $model,
            'modelObjeto'   => $modelObjeto,
        ]);
    }

    /**
     * Función que se utiliza para realizar el cambio de denominación.
     */
    public function actionDenominacion( $id = '' ){

        if ( $id == '' ){
            Yii::$app->session->setflash( self:: CONST_MENSAJE_ERROR, 1002 );

            return $this->redirect([ 'view' ]);
        }

        $model          = $this->findModel( $id );
        $modelObjeto    = $this->findModelObjeto( $id );

        $model->den_obj_nom = $modelObjeto->nombre;

        //Acción que se ejecuta cuando se graban los datos
        if( Yii::$app->request->isPost ){

            $model->setScenario( 'denominacion' );

            if( $model->load( Yii::$app->request->post() ) && $model->cambiarDenominacion( $modelObjeto ) ){

                Yii::$app->session->setFlash( self::CONST_MENSAJE, 1 );

                return $this->redirect([ 'view', 'id' => $model->obj_id ]);

            }

        }

        return $this->render('_form_denominacion', [
            'model'         => $model,
            'modelObjeto'   => $modelObjeto,
        ]);
    }

    /**
	 * Función que se utiliza para realizar cambios respecto a los rubros del comercio.
     * @param string $id Identificador de objeto.
     * @param integer $taccion Identificador del tipo de acción.
     *      + 12 -> Anexo de rubro.
     *      + 13 -> Cambio de rubro.
	 */
	public function actionRubro( $id = '', $taccion ){

        if ( $id == '' ){

            Yii::$app->session->setflash( self:: CONST_MENSAJE_ERROR, 1002 );

            return $this->redirect([ 'view' ]);

        }

        $model          = $this->findModel( $id );
        $modelObjeto    = $this->findModelObjeto( $id );

        $mostrarModalRubros = false;    //Indica si se debe mostrar la ventana modal de rubros.
        $modelRubroTemporal = [];

        if( $this->cargarArreglosDesdeBD ){

            //Cargar los rubros en memoria
            $this->setRubros( Comer::getRubrosVista( $id ) );

        }

		if(Yii::$app->request->isPost){

			$model->load(Yii::$app->request->post());
			$model->rubros = $this->getRubros();

			$transaction = Yii::$app->db->beginTransaction();

			try{
				$model->grabarRubros ( $model->obj_id );
				$transaction->commit();

				Yii::$app->session->setFlash( self::CONST_MENSAJE, 1 );

                return $this->redirect([ 'view', 'id' => $model->obj_id ]);

			} catch (\Exception $e){

				$transaction->rollBack();

				$this->addError( 'obj_id', 'Ocurrió un error al grabar los datos.' );
			}
		}

        if( Yii::$app->request->isPjax ){

            if( Yii::$app->request->get( '_pjax', '' ) == "#comer_actionRubro_pjaxRubrosNuevos" ){

                $nuevoRubro     = new ComerRubro();
                $txAction       = Yii::$app->request->get( 'txAction', 1 );

                switch( $txAction ){

                    case 0: $nuevoRubro->setScenario( 'insert' ); break;
                    case 2: $nuevoRubro->setScenario( 'delete' ); break;
                    case 3: $nuevoRubro->setScenario( 'update' ); break;
                }

                if( $nuevoRubro->load( Yii::$app->request->get() ) ){

                    $nuevoRubro->obj_id     = $modelObjeto->obj_id;
                    $rubros = $this->getRubros();

                    if( Comer::ABMRubrosEnArreglo( $rubros, $nuevoRubro, $txAction ) ){

                        $this->setRubros( $rubros );

                    } else {
                        $modelRubroTemporal = $nuevoRubro;
                        $mostrarModalRubros = true;

                    }

                }
            }

        }

		return $this->render( 'cambio_rubro', [

            'model'             => $model,
            'modelObjeto'       => $modelObjeto,

            'arrayRubros'       => $this->getRubros(),

            'mostrarModalRubros'    => $mostrarModalRubros,
            'modelRubroTemporal'    => $modelRubroTemporal,

            'dpRubrosActuales'  => new ArrayDataProvider([
                'allModels' => Comer::getRubrosVista( $id ),
            ]),

            'dpRubros'  => new ArrayDataProvider([
                'allModels' => $this->getRubrosSegunFiltro( $this->getRubros(), false, false ),
            ]),

			'taccion' => $taccion,
            'idDivError' => 'rubro_form_errorSummary'
        ]);
	}

    /**
     * Función que se utiliza para actualizar el arreglo en memoria para "Rubros".
     * @param array $rubros Arreglo de rubros
     */
    public function setRubros( $rubros = [] ){

        Yii::$app->session->set( self::CONST_COMER_ARRAY_RUBROS, $rubros );
    }

    /**
     * Funcion que se utiliza para obtener el arreglo en memoria de "Rubros".
     */
    public function getRubros(){

        return Yii::$app->session->get( self::CONST_COMER_ARRAY_RUBROS, [] );
    }

    private function getRubrosSegunFiltro( $arrayRubros, $dadosDeBaja, $soloVigentes ){

        return ComerRubro::getRubrosSegunFiltro( $arrayRubros, $dadosDeBaja, $soloVigentes );
    }

    /**
     * Función que se utiliza para actualizar el arreglo en memoria para "Titulares".
     * @param array $rubros Arreglo de rubros
     */
    public function setTitulares( $titulares = [] ){

        Yii::$app->session->set( self::CONST_COMER_ARRAY_TITULARES, $titulares );
    }

    /**
     * Funcion que se utiliza para obtener el arreglo en memoria de "Titulares".
     */
    public function getTitulares(){

        return Yii::$app->session->get( self::CONST_COMER_ARRAY_TITULARES, [] );
    }

    public function findModel( $id = '' ){

        $model = Comer::findOne( $id );

        if (( $model == null ) ){

            $model = new Comer();
        }

        return $model;

    }

    public function findModelObjeto( $id = '' ){

        $model = ( new Objeto() )->cargarObjeto( $id );

        if (( $model == null ) ){

            $model = new Objeto();
        }

        return $model;

    }

    public function findModelVPersona( $id = '' ){

        return Comer::getDatosResponsable( $id );

    }

    public function findModelRubro( $objeto, $rubro_id ){

        //$model = ComerRubro::findOne( $id );
        $model = new ComerRubro();
        if (( $model == null ) ){

            $model = new ComerRubro();
        }

        return $model;

    }

    public function getMensaje( $id ){

        $title = '';

        switch( $id ){

            case 1:

                $title = 'Los datos se grabaron correctamente.';
                break;

            case 2:

                $title = 'El comercio se habilitó correctamente.';
                break;

            case 1001:

                $title = 'El objeto ingresado no existe.';
                break;

            case 1002:

                $title = 'El objeto ingresado no existe.';
                break;
        }

        return $title;
    }
}
