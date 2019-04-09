<?php

namespace app\controllers\ctacte;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\Session;
use yii\data\ArrayDataProvider;

use app\models\ctacte\Retencion;
use app\models\ctacte\AgenteRetencion;
use app\models\ctacte\RetencionDetalle;

use app\utils\db\utb;
use app\utils\db\Fecha;

/**
 * ReclamoController implements the CRUD actions for Reclamo model.
 */
class RetencionController extends Controller{

	const CONST_MENSAJE			= 'const_mensaje';
	const CONST_MENSAJE_ERROR	= 'const_mensaje_error';
	const CONST_LAST_ID			= 'const_last_id';
	const CONST_ARRAY_RETENCION	= 'const_array_retencion_detalle';
	const CONST_MODEL_RETENCION	= 'const_modal_retencion';
	const CONST_MODEL_RETENCION_ACTION	= 'const_modal_retencion_action';

	private $modelDetalle;
	private $consultaDetalle;
	private $cambioVista;

	private $cargarDetalleDesdeBD = false;

	public function beforeAction( $action )
    {

    	if( Yii::$app->session['accionesweb']["acc_agrete"] == 'N' ) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }

		$id = $action->getUniqueId();

		if( $id != Yii::$app->session->getFlash( self::CONST_LAST_ID, '' ) ){

			$this->cargarDetalleDesdeBD = true;
		}

		Yii::$app->session->setFlash( self::CONST_LAST_ID, $id );

    	return true;
    }

	public function setDetalleRetencion( $detalle = [] ){

		Yii::$app->session->set( self::CONST_ARRAY_RETENCION, $detalle );

		// $file = fopen( "uploads/temp/rete" . Yii::$app->user->id . ".bin", "w" );
		// fwrite( $file, $detalle );
		// fclose( $file );
	}

	public function getDetalleRetencion(){

		// $archivo = "uploads/temp/rete" . Yii::$app->user->id . ".bin";
		//
		// $file = fopen( $archivo, "r" );
		//
		// if( filesize( $archivo ) > 0 ){
		//
		// 	$data = fread( $file, filesize( $archivo ));
		//
		// 	$detalle = explode( ';', $data );
		//
		// } else {
		//
		// 	$detalle = [];
		// }
		//
		// fclose( $file );
		//
		// return $detalle;
		return Yii::$app->session->get( self::CONST_ARRAY_RETENCION, [] );
	}

	/**
	* Función que se utiliza para obtener las retenciones asociadas a una DJ.
	*/
	private function cargarDetallesModelo( $model ){

		//$this->setDetalleRetencion( RetencionDetalle::find()->where([ 'and', "retdj_id = $model->retdj_id", "retdj_id > 0"])->all());
		$this->setDetalleRetencion( RetencionDetalle::find()->where([ 'and', "retdj_id = $model->retdj_id", "retdj_id > 0"])->all() );

	}

	public function actionIndex( $id = 0 ){

		$model= new Retencion($id);

		$anio = '';

		if( Yii::$app->request->isPjax ){

			if( Yii::$app->request->get( '_pjax', '' ) == "#pjaxGrilla" ){

				$anio	= Yii::$app->request->get( "anio", "" );
			}
		}

		$models= Retencion::find()->where(['ag_rete' => $id])->andFilterWhere(['anio' => $anio])->all();

		$dp= new ArrayDataProvider([
			'allModels' => $models,
			'key' => 'retdj_id'
		]);

		//return var_dump($models);
		$agentes= Retencion::agentes();

		return $this->render('index', [
			'mensaje'	=> $this->getMensaje( Yii::$app->session->getFlash( self::CONST_MENSAJE, 0 ) ),
			'model' => $model,
			'dataProviderDDJJPresentadas' => $dp,
			'agentesExistentes' => $agentes,
			]);
	}

	public function actionCreate( $idusr = 0, $importar = 0 ){

		return $this->view( 0, $idusr, 0, $importar );
	}

	public function actionUpdate( $id = 0 ){

		return $this->view( $id, 0, 3, 0 );
	}

	public function actionDelete( $id = 0 ){

		return $this->view( $id, 0, 2, 0 );
	}

	public function actionView( $id = 0 ){

		return $this->view( $id, 0, 1, 0 );
	}

	private function view( $id = 0, $idusr = 0, $action = 1, $importar = 0 ){

		$rete_id		= 0;
		$rete_action	= 0;

		$model 		= $this->findModel( $id, $idusr );

		if( $this->cargarDetalleDesdeBD ){
			$this->cargarDetallesModelo( $model );
			$model->CargarDatosAExportar();
		}

		$mostrarDivDatos 	= intVal( $action != 0 );   //Variable que indica si se muestran los datos.
        $dibujarModal       = 0;    //Variable que indica si se debe mostrar la ventana modal.

		if( Yii::$app->request->isPjax ){

			if( Yii::$app->request->get( '_pjax', '' ) == "#usuarioweb_retencion_form_pjaxDatos" ){

				$model->anio		= Yii::$app->request->get( 'anio', 0 );
				$model->mes			= Yii::$app->request->get( 'mes', 0 );

				//Verificar que no exista una DJ presentada para ese período
				if( !Retencion::verificarExistenciaDJ( $model->ag_rete, $model->anio, $model->mes ) ){

					$mostrarDivDatos	= 1;

                    $this->setDetalleRetencion( [] ); //Se eliminan los datos del arreglo.

				} else {

					Yii::$app->session->setFlash( self::CONST_MENSAJE_ERROR, 1001 );
				}
			}

			if( Yii::$app->request->get( '_pjax', '' ) == "#usuarioweb_retencion_form_PjaxModalRete" ){

				$rete_id		= Yii::$app->request->get( 'rete_id', 0 );
				$rete_action	= Yii::$app->request->get( 'rete_action', 0 );
				$model->anio	= Yii::$app->request->get( 'anio', 0 );
				$model->mes		= Yii::$app->request->get( 'mes', 0 );

			}

			/**
			 * Cuando se agrega, modifica o elimina una retención.
			 */
			if( Yii::$app->request->get( '_pjax', '' ) == '#usuarioweb_retencion_form_pjaxRetencion' ){

				$actionRete = 	Yii::$app->request->get( 'txAction', 1 );
                $modelRetencion = new RetencionDetalle();

				switch( $actionRete ){

					case 0:

						$modelRetencion->setScenario( 'insert' );

						break;

					case 2:

						$modelRetencion->setScenario( 'delete' );

						break;

					case 3:

						$modelRetencion->setScenario( 'update' );

						break;
				}

				$model->setDetalles( $this->getDetalleRetencion() );

				if( $modelRetencion->load( Yii::$app->request->get() ) && $modelRetencion->validate() ){

					//Agregar el modelo al array de retenciones

					$retenciones = $this->getDetalleRetencion();

					$res = Retencion::ABMDetalleAlArreglo( $retenciones, $modelRetencion, $actionRete );

					Yii::$app->session->setFlash( self::CONST_MENSAJE_ERROR, $res );

					$this->setDetalleRetencion( $retenciones );

					//Se agregan las retenciones cargadas
					$model->setDetalles( $this->getDetalleRetencion() );

				} else {

                    //Cargar el modelo y el action para redibujar el modal.
                    Yii::$app->session->setFlash( self::CONST_MODEL_RETENCION, $modelRetencion );
                    Yii::$app->session->setFlash( self::CONST_MODEL_RETENCION_ACTION, $actionRete );

                    $dibujarModal = 1;
                }

			}

		}

		if( Yii::$app->request->isPost ){	//Grabar u obtener archivo

			if( $importar ){	//Leer Archivo

				//return var_dump( Yii::$app->request->post() );
				$model= new Retencion();
				$model->setScenario( 'importar' );

				$model->load( Yii::$app->request->post() );
				$model->archivo	= UploadedFile::getInstance( $model, 'archivo' );

				$model->setDetalles( $this->getDetalleRetencion() );

				if( $model->importarArchivo() ){	//Si se importa correctamente el archivo

					$this->setDetalleRetencion( $model->detalles );
					$model->setDetalles( $model->detalles );
				}

				$mostrarDivDatos	= 1;

			} else {	//Grabar

				switch( $action ){

					case 0:

						$model->setScenario( 'insert' );
						break;

					case 1:

						$model->setScenario( 'aprobar' );
						break;

					case 2:

						$model->setScenario( 'delete' );
						break;

					case 3:

						$model->setScenario( 'update' );
						break;

				}

	            if( $model->load( Yii::$app->request->post() ) ) {

					//Se agregar las retenciones cargadas antes de grabar
					$model->setDetalles( $this->getDetalleRetencion() );

					if( $model->validate() ){

						switch( $action ){

							case 0:
							case 3:

								if( $model->grabar() ){

									Yii::$app->session->setFlash( self::CONST_MENSAJE, 1 );
									return $this->redirect([ 'index', 'id' => $model->ag_rete ]);
								}

								break;

							case 1:

								$ctacte_id = 0;

								if( $model->aprobar( $ctacte_id ) ){	//Aprobar la DJ e imprimir el cupón de pago.

									if( $ctacte_id != 0 ){

										//Código para imprimir el cupón
									}

									Yii::$app->session->setFlash( self::CONST_MENSAJE, 1 );
									return $this->redirect([ 'index', 'id' => $model->ag_rete ]);

								}

								break;

							case 2:

								if( $model->borrar() ){

									Yii::$app->session->setFlash( self::CONST_MENSAJE, 1 );
									return $this->redirect([ 'index', 'id' => $model->ag_rete ]);
								}

								break;

						}

					} else {

						$mostrarDivDatos	= 1;
					}

	            }
			}

        }
		
		return $this->render( '_form', [

			'mensaje'	=> $this->getMensaje( Yii::$app->session->getFlash( self::CONST_MENSAJE, 0 ) ),
			'error'		=> $this->getMensaje( Yii::$app->session->getFlash( self::CONST_MENSAJE_ERROR, 0 ) ),

			'action'	=> $action,
			'idusr'		=> $idusr,

			'model'		=> $model,

			'mostrarDivDatos'	=> $mostrarDivDatos,

            'dibujarModal'      => $dibujarModal,

			'dataProviderDetalleRetenciones'	=> new ArrayDataProvider([
				'allModels' => $this->getDetalleRetencion(),
				'pagination' => [
			        'pageSize' => 10000,
			    ],
			]),

			//Variables para el manejo de las retenciones.
			'rete_id'		=> $rete_id,
			'rete_action'	=> $rete_action,
            'detalles'      => $this->getDetalleRetencion(),

		]);
	}
	
	public function actionImportarfinanciero(){
	
		$model = new Retencion();
		
		if( Yii::$app->request->isPost ){
			
			if ($model->ImportarReteFinanciero() )
				Yii::$app->session->setFlash( self::CONST_MENSAJE, 1 );
		}
		
		$dataProvider = $model->RetencionesImportadas();
		
		return $this->render('retefinanciero', [
					'dataProvider' 	=> $dataProvider,
					'model'			=> $model,
					'mensaje'		=> $this->getMensaje( Yii::$app->session->getFlash( self::CONST_MENSAJE, 0 ) ),
				]);
	}

	/**
	 * Función que se utiliza para realizr el ABM de retenciones.
	 * @param integer $ag_rete Identificador del agente de retenciones.
	 * @param integer $rete_id Identificador de retención.
	 * @param integer $action Tipo de acción.
	 * @param integer $anio
	 * @param integer $mes
	 * @param string $pjaxAActualizar Identificador del pjax que se debe actualizar.
	 * @param string $idModal Identificador de la ventana modal.
	 */
	public function ABMRete( $rete_id = 0, $idusr = 0, $action, $anio, $mes, $pjaxAActualizar, $idModal ){

		$model = new RetencionDetalle( $idusr );

        if( $rete_id != 0 ){    //Se debe cargar un elemento del arreglo

            $detalle = Retencion::obtenerDetalleDeArreglo( RetencionController::getDetalleRetencion(), $rete_id );

            if( $detalle != [] ){

                $model = $detalle;
            }
        }

		$modelRetencion = new Retencion( $idusr );

		$cerrarModalDetalle	= 0;

		if( Yii::$app->request->isPjax ){

			//Se ingresa CUIT u objeto
			if( Yii::$app->request->get( '_pjax', '' ) == '#usuarioweb_agregarRete_pjaxCambiaDatos' ){

				$dato	= Yii::$app->request->get( 'dato', '' );

				$model->cambiaDato( $dato );
			}

		}

		return $this->render( 'agregarRete',[

			'pjaxAActualizar'    => $pjaxAActualizar,
			'idModal'            => $idModal,
			'model'              => Yii::$app->session->getFlash( self::CONST_MODEL_RETENCION, $model ),
			'modelRete'          => $modelRetencion,
			'action'  	         => Yii::$app->session->getFlash( self::CONST_MODEL_RETENCION_ACTION, $action ),

			'anio'		=> $anio,
			'mes'		=> $mes,

			'agentes'   => RetencionDetalle::agentes(),
			'tiposComprobantes' => RetencionDetalle::tiposComprobantes(),

		]);
	}

	public function actionPendientes(){

		$model = new Retencion();
		$condicion = '';
		
		if( Yii::$app->request->post( '_pjax', '' ) == '#pjaxGrilla' ){
			if ( Yii::$app->request->post( 'grabar', '' ) == '' ) // es consulta
				$condicion = $model->criterioPendiente( Yii::$app->request->post() ); 
			else {
				$rete = Yii::$app->request->post( 'rete', '' );
				if ( $model->marcarAplicada( $rete ) ){
					Yii::$app->session->setFlash( self::CONST_MENSAJE, 1 );
					return $this->redirect([ 'pendientes' ]);
				}
			}
		}

		return $this->render( 'pendientes', [
				'dataProvider' => $model->getPendientes( $condicion ),
				'agentesExistentes' => Retencion::agentes(),
				'model' => $model,
				'mensaje' => $this->getMensaje( Yii::$app->session->getFlash( self::CONST_MENSAJE, 0 ) )
			]);
	}
 
	public function actionSugerencialugar($term = ''){

		Yii::$app->response->format= \yii\web\Response::FORMAT_JSON;

		return RetencionDetalle::sugerenciaLugar($term);
	}

	public function actionObtenerobjeto(){

		$objeto	= Yii::$app->request->post( 'objeto', '' );	

		if ( strlen($objeto) < 8 )
			$objeto = utb::getObjeto(3, $objeto);

		if ( utb::getTObj($objeto) != 3 )
			$objeto = '';

		return json_encode(['objeto' => $objeto]);
	}
	
	public function verRetencion( $ret_id, $idModal ){
	
		$modelReteDet = RetencionDetalle::findOne([ 'ret_id' => $ret_id ]);
		if ( $modelReteDet == null )
			$modelReteDet = new RetencionDetalle();
			
		if ( $modelReteDet->cuit != '' )	
			$modelReteDet->cuit = utb::cuitGuiones( $modelReteDet->cuit );
			
		if ( $modelReteDet->fecha != '' )	
			$modelReteDet->fecha = Fecha::bdToDatePicker( $modelReteDet->fecha );	
			
		return $this->render('verRete', [
					'model' => $modelReteDet
				]);
	}

	public function getMensaje( $id ){

		switch( $id ){

			case 1:

				$title = 'Los datos se grabaron correctamente.';
				break;

			case 1001:

				$title = 'Ya existe una DJ para el período ingresado.';
				break;

			case 1002:

				$title = 'La retención ingresada ya existe.';
				break;

			default:

				$title = '';
				break;
		}

		return $title;
	}

	private function findModel( $id, $idusr ){

		$model = Retencion::findOne([ 'retdj_id' =>  $id ]);

		if( $model == null ){
			$model = new Retencion( $idusr );
		}

		return $model;
	}

}
