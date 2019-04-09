<?php

namespace app\controllers\ctacte;

use Yii;
use app\models\ctacte\Ddjj;
use app\models\ctacte\DdjjAnual;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use app\utils\db\utb;
use app\models\ctacte\RetencionDetalle;
use app\models\ctacte\Retencion;
use app\utils\db\Fecha;
use app\models\ctacte\DdjjListado;
use yii\data\ActiveDataProvider;

/**
 * DdjjController implements the CRUD actions for Ddjj model.
 */
class DdjjController extends Controller
{
    const CONST_MENSAJE                 = 'const_ddjj_mensaje';
    const CONST_MENSAJE_ERROR           = 'const_ddjj_mensaje_error';
    const CONST_ID_ANTERIOR             = 'ddjj_id_anterior';
    const CONST_PJAX_CAMBIATRIBUTO      = '#ddjj_pjaxCambiaTributo';
    const CONST_PJAX_CARGARDATOS        = '#ddjj_pjaxCargarDatos';
    const CONST_MODALRETENCIONES        = "#ddjj_PjaxModalRete";
    const CONST_PJAX_CARGARRETE         = '#PjaxCargarRete';
    const CONST_PJAX_CALCULARDJ         = '#ddjj_pjaxCalcularDJ';
    const CONST_RUBROSYTRIBUTOS         = 'const_rubrosYTributos';
    const CONST_RETENCIONES             = 'const_ddjj_retenciones';
    const CONST_LIQUIDACIONES           = 'const_ddjj_liquidaciones';
    const CONST_DDJJ_ID                 = 'const_ddjj_id';
    const CONST_DDJJ_FISCALIZA          = 'const_ddjj_fiscaliza';
    const CONST_COMPARA_PJAX_DDJJ       = '#compara_manejadorGrillaDDJJ';
    const CONST_COMPARA_PJAX_RUBROS     = '#compara_manejadorGrillaRubros';
    const CONST_COMPARA_PJAX_CAMBIAOBJ  = '#compara_pjaxCambiaObjeto';

    public function beforeAction( $action )
    {
    	if (!parent::beforeAction($action)) {
	        return false;
	    }

        $operacion = str_replace("/", "-", Yii::$app->controller->route);

	    if (!utb::getExisteAccion($operacion)) {
			return true;
	        echo $this->render('//site/nopermitido');
	        return false;
	    }

	    // si alta de ddjj
	    if (isset($_GET['consulta']) and $_GET['consulta']==0 and !utb::getExisteProceso(3331)) {
			echo $this->render('//site/nopermitido');
        	return false;
		}

    	$id = $action->getUniqueId();

    	if ( $id != 'ctacte/ddjj/create' && $id != Yii::$app->session->getFlash( self::CONST_ID_ANTERIOR, '' ) )
    	{

            Yii::$app->session->remove( self::CONST_DDJJ_ID );
            $this->setRubros( [] );
            $this->setRetenciones( [] );
            $this->setLiquidaciones( [] );

    	}

        Yii::$app->session->setFlash( self::CONST_ID_ANTERIOR, $id );

    	return true;
    }

    /**
     * Función que se ejecuta cuando se realiza la búsqueda de una DDJJ
     */
    public function actionBuscar()
    {

        if( Yii::$app->request->isPost ){

            $id = Yii::$app->request->post('txNumDDJJ', 0 );

            $trib_id = Yii::$app->request->post( 'dlTrib', 0 );
            $obj_id = Yii::$app->request->post( 'txObj_Id', '' );

            if( $id != 0 ){

                #Verificar existencia DDJJ

                return $this->redirect(['view', 'id'=> $id ]);

            } else if ( $obj_id != '' && $trib_id != 0 )
            {

                $cont = utb::getCampo('v_dj',"obj_id='" . $obj_id . "' AND trib_id = " . $trib_id,'count(*)');

                if( $cont == 0 ){

                    Yii::$app->session->setFlash( self::CONST_MENSAJE_ERROR, 1002 );
                    return $this->redirect(['view']);
                }

                if ( $cont > 1 ){
                    					
				$model = new DdjjListado();
                $model->objeto_tipo = Yii::$app->request->post('dlTrib', null);
                $model->objeto_desde = Yii::$app->request->post('txObj_Id', null);
				$model->objeto_hasta = Yii::$app->request->post('txObj_Id', null);
				$res = $model->buscar();
                $datos = ListadoddjjController::datosResultado($model, $res);
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
				

                } else if( $cont == 1 )
                {
                    $plan = utb::getCampo( 'v_dj', "obj_id='". $obj_id . "' AND trib_id = " . $trib_id, 'dj_id' );

                    return $this->redirect(['view', 'id' => $plan]);
                }
            }
        }

        return $this->redirect(['view']);

    }

    /**
     * Método que se ejecuta al llegar a DDJJ y que se encarga de setear variables.
     */
    public function actionIndex( $fiscaliza = 0 )
    {

        $this->setFiscaliza( $fiscaliza );
    	return $this->redirect(['view']);

    }

    /**
     * Función que se ejecuta cuando se muestran las opciones de listado
     * y cuando se obtienen los parámetros de busca.
     */
    public function actionListado(){

        

            return $this->redirect(['//ctacte/listadoddjj/index']);
            }

    /**
     * Función que muestra el resultado de la búsqueda
     */
	public function actionList_res()
    {
        $descr = Yii::$app->session->get( 'descr', '' );
        $cond = Yii::$app->session->get( 'cond', '' );

        $dataProvider = new ArrayDataProvider([
            'allModels' => Ddjj::buscaDatosListado( $cond ),
            'key'      => 'dj_id',
            'sort'    => [
                'attributes' => [
                    'dj_id',
                    'obj_id' => [
                        'asc'   => [ 'obj_id' => SORT_ASC, 'anio' => SORT_ASC, 'cuota' => SORT_DESC, 'dj_id' => SORT_DESC ],
                        'desc'  => [ 'obj_id' => SORT_DESC, 'anio' => SORT_ASC, 'cuota' => SORT_DESC, 'dj_id' => SORT_DESC ],
                    ],
                    'anio' => [
                        'asc'   => [ 'anio' => SORT_ASC, 'cuota' => SORT_DESC, 'dj_id' => SORT_DESC ],
                        'desc'  => [ 'anio' => SORT_DESC, 'cuota' => SORT_DESC, 'dj_id' => SORT_DESC ],
                    ],
                    'base',
                    'monto',
                    'fchpresenta',
					'cuota'
                ],
				'defaultOrder' => [ 'anio' => SORT_DESC, 'cuota' => SORT_DESC, 'dj_id' => SORT_DESC ],
            ],
        ]);

    	return $this->render('list_res', [
    		'descr' => $descr,
    		'cond' => $cond,
            'dataProvider' => $dataProvider,
		]);
    }

    /**
     * Función que se utiliza para mostrar un listado con las declaraciones juradas.
     * @param string $id Código del objeto.
     */
    public function actionListadj( $id = '' ){

        if( $id == '' ){

            return $this->redirect(['view']);
        }

        #INICIALIZA Variables
        $fiscaliza = Yii::$app->session->get('FiscalizaActiva',0);
        $trib_id = 0;
        $obj_id = $id;
        $est = '';

        if( Yii::$app->request->isPjax ){
            $trib_id = Yii::$app->request->get('trib',0);
        	$obj_id = Yii::$app->request->get('obj_id',$id);
        	$est = Yii::$app->request->get('est','');
        }

        $dataProviderDDJJ = new ArrayDataProvider([
            'allModels' => Ddjj::buscarListObj( $trib_id, $obj_id, $est, false, $fiscaliza ),
            'pagination'    => false,
            'key'           => 'dj_id',
        ]);

        return $this->render('listaDJ', [
            'obj_id'        => $id,
            'estadoObjeto'  => Ddjj::getEstadoObjeto( $id ),
            'externo'       => true,
            'dataProviderDDJJ' => $dataProviderDDJJ,
        ]);
    }

    /**
     * Función que se ejecuta para ingresar una nueva DJ.
     * @param string $obj_id Identificador del objeto.
     * @param integer $n Indica si se puede modificar el objeto
     */
    public function actionCreate( $obj_id = '', $n = 1 ){

         $tributos              = Ddjj::getTributos();
         $datosCargados         = 0;    //Variable que determinará si los datos de la DJ están cargados.
         $djRectificativa       = 0;    //Variable que determinará si la dj es rectificativa. Obliga a mostrar una ventana modal.
         $verificaAdeuda        = 0;    //Variable que determinará si se debe mostrar una ventana modal indicando que se debe una dj.
         $verificaExistencia    = 0;    //Variable que determinará si se debe mostrar una ventana modal indicando la existencia de una dj.
         $aplicaBonificacion    = 0;    //Variable que determinará si el usuario desea aplicar bonificación.
         $saldoAFavor           = 0;    //Variable que determinará el saldo a favor ingresado por el usuario.
         $permiteRetenciones    = 1;

         $habilitarPresentar    = false;

         $permiteModificarObj   = $n;
         $objeto_id = '';

         $model = new Ddjj();
         $model->fiscaliza = $this->getFiscaliza();

         # INICIO Cargar datos por defecto

         if( $obj_id != '' ){
            $model->obj_id = $obj_id;
         }

         $model->setTributo( Ddjj::getTributoDeclarativo() );
         $model->cargarPeriodoADeclarar();

         #Cargo la variable en sesión para la búsqueda de objeto
         Yii::$app->session->set( 'busquedaAvTObj' , utb::getTObjTrib( $model->trib_id ) );

         # FIN Cargar datos por defecto

         # INICIO Ejecución por Pjax
         if( Yii::$app->request->isPjax ){

             if( Yii::$app->request->isGet ){

                 //Cambia el Tipo de Tributo
                 if( Yii::$app->request->get( '_pjax', '' ) == self::CONST_PJAX_CAMBIATRIBUTO ){

                     $trib  = Yii::$app->request->get( 'trib_id', 0 );
                     $obj   = Yii::$app->request->get( 'objeto_id', 0 );

                     //Cargar el tributo
                     $model->setTributo( $trib );

                     //Validar que exista el objeto y se encuentre en estado correcto
                     $model->validarObjetoYCargar( $trib, $obj );

                     $model->cargarPeriodoADeclarar();

                     #Cargo la variable en sesión para la búsqueda de objeto
                     Yii::$app->session->set( 'busquedaAvTObj' , utb::getTObjTrib( $model->trib_id ) );

                 }

                 //Se cargan los datos
                 if( Yii::$app->request->get( '_pjax', '' ) == self::CONST_PJAX_CARGARDATOS ){

                     $this->setRubros( [] );
                     $this->setRetenciones( [] );
                     $this->setLiquidaciones( [] );

                     $verificaPeriodo       = Yii::$app->request->get( 'verificaPeriodo', 0 );
                     $verificaAdeuda        = Yii::$app->request->get( 'verificaAdeuda', 0 );
                     $verificaExistencia    = Yii::$app->request->get( 'verificaExistencia', 0 );

                     if( $model->load( Yii::$app->request->get() ) ){

                         if( $model->validarDatosAlCargar() ){

                             $model->cargarDatosDJ( $verificaPeriodo, $verificaAdeuda, $verificaExistencia );

                             $this->setRubros( $model->getRubrosAndTributos() );

                         }

                         if( !$model->hasErrors() && $verificaAdeuda == 0 && $verificaExistencia == 0 ){

                             $datosCargados = 1;
                         }

                         $this->setRetenciones( $model->getRetenciones( $model->trib_id, $model->obj_id, $model->anio, $model->cuota ) );
                     }
                 }

                 //Luego de que se agrega una retención o al eliminar una retención
                 if( Yii::$app->request->get( '_pjax', '' ) == self::CONST_PJAX_CARGARRETE ){

                     $trib_id   = Yii::$app->request->get( 'trib_id', 0 );
                     $obj_id    = Yii::$app->request->get( 'obj_id', '' );
                     $anio      = Yii::$app->request->get( 'anio', 0 );
                     $cuota     = Yii::$app->request->get( 'cuota', 0 );

                     #INICIO Agregar Retenciones
                     $modelRete = new RetencionDetalle();

                     $modelRete->setScenario( 'insert' );

                     if( $modelRete->load( Yii::$app->request->get() ) ){

                         $modelRete->setIsNewRecord( true );

                         //Se validan los datos ingresados
                         if ( $modelRete->validate() && $modelRete->grabar( 0 ) ){

                            Yii::$app->session->setFlash( self::CONST_MENSAJE, 4 );

                        } else {

                            $model->addErrors( $modelRete->getErrors() );

                         }
                     }
                     #FIN Agregar Retenciones

                     $rete_id   = Yii::$app->request->get( 'rete_id', 0 );
                     $eliminar  = Yii::$app->request->get( 'eliminar', 0 );

                     if( $eliminar ){

                         if( RetencionDetalle::eliminarRetencion( $rete_id ) ){

                             Yii::$app->session->setFlash( self::CONST_MENSAJE, 6 );
                         } else {
                             Yii::$app->session->setFlash( self::CONST_MENSAJE_ERROR, 1005 );
                         }
                     }

                     //return var_dump( $model->getRetenciones( $model->trib_id, $model->obj_id, $model->anio, $model->cuota ) );die();
                     $this->actualizarRetenciones( $model->getRetenciones( $trib_id, $obj_id, $anio, $cuota ) );
					 
					 $model->trib_id = $trib_id;
                 }

                 //Cuando se calcula la DJ.
                 if( Yii::$app->request->get( '_pjax', '' ) == self::CONST_PJAX_CALCULARDJ ){

                     $aplicaBonificacion    = Yii::$app->request->get( 'aplicaBonificacion', 0 );
                     $saldoAFavor           = Yii::$app->request->get( 'saldoAFavor', 0 );
                     $retenciones           = Yii::$app->request->get( 'rete', [] );
                     $arrayCodigo           = Yii::$app->request->get( 'codigo', [] );
                     $arrayBase             = Yii::$app->request->get( 'base', [] );
                     $arrayCant             = Yii::$app->request->get( 'cant', [] );

                     if( $model->load( Yii::$app->request->get() ) ){

                         $arrayRubros = $model->getRubrosAndTributos( $arrayCodigo, $arrayBase, $arrayCant );
                         $arrayRetenciones  = $this->getRetenciones();
						 
						 $datosCalc = $model->calcularDJ( $this->getNextID(), $arrayRubros, $arrayRetenciones, $aplicaBonificacion, $saldoAFavor, $retenciones );
							
                         if ( count($datosCalc) > 0 ){
							 $this->setLiquidaciones( $datosCalc ); 
							 //return var_dump($model->calcularDJ( $this->getNextID(), $arrayRubros, $arrayRetenciones, $aplicaBonificacion, $saldoAFavor, $retenciones ) );
							 $this->setRubros( $arrayRubros );
							 $this->setRetenciones( $arrayRetenciones );

							 $model->obtenerMontosDJ( $this->getRubros(), $this->getLiquidaciones() );

							 $habilitarPresentar    = true;
						}	

                     }
                 }

                 if( Yii::$app->request->get( '_pjax', '' ) == self::CONST_MODALRETENCIONES ){

                     $objeto_id    = Yii::$app->request->get( 'obj_id', '' );
                 }

             }
         }

         if( Yii::$app->request->isPost ){

             if( $model->load( Yii::$app->request->post() ) ){

                 $aplicaBonificacion    = Yii::$app->request->post( 'ckBonificacion', 0 );
                 $saldoAFavor           = Yii::$app->request->post( 'txSaldo', 0 );
                 $retenciones           = Yii::$app->request->post( 'DDJJ_rete', [] );

                 $id_dj = 0;

                 if( $model->grabarDJ( $this->getNextID(), $aplicaBonificacion, $saldoAFavor, $retenciones, $id_dj ) ){

                     Yii::$app->session->setFlash( self::CONST_MENSAJE, 1 );

                     return $this->redirect([ 'view', 'id' => $id_dj ]);

                 }else {

                     Yii::$app->session->setFlash( self::CONST_MENSAJE_ERROR, 3 );
                     return $this->redirect(['view']);
                 }
             }

         }

         //Corrección de las fechas
         $model->fchpresenta = Fecha::usuarioToDatePicker( $model->fchpresenta );

         return $this->render( 'create', [

             'mensaje'              => $this->getMensaje( Yii::$app->session->getFlash( self::CONST_MENSAJE, 0 ) ),
             'error'                => $this->getMensaje( Yii::$app->session->getFlash( self::CONST_MENSAJE_ERROR, 0 ) ),

             'tributos'             => $tributos,

             'permiteModificarObj'  => $permiteModificarObj,
             'obj_id'               => $objeto_id,  //Utilizado para la ventana modal de retenciones
             'tiposDDJJ'            => utb::getAux('ddjj_tipo','cod','nombre',0,( !$model->fiscaliza ? "cod in (1,2)" : "cod in (1,2,3)" ) ),
             'model'                => $model,

             'permiteRetenciones'   => $permiteRetenciones,

             'fecha'                => date('d/m/Y'),
             'datosCargados'        => $datosCargados,
             'verificaAdeuda'       => $verificaAdeuda,
             'verificaExistencia'   => $verificaExistencia,
             'habilitarPresentar'   => $habilitarPresentar,
             'config_ddjj'          => Ddjj::getConfig_ddjj($model->trib_id),
             'dataProvRubros'       => new ArrayDataProvider([
                 'models' => $this->getRubros(),
             ]),
             'dataProviderRete'     =>  new ArrayDataProvider([
                 'allModels' => $this->getRetenciones(),
                 'key'  => 'ret_id',
                 'pagination'   => false,
                 'sort' => [
                     'attributes' => [
                         'fecha',
                     ],
                     'defaultOrder' => [ 'fecha' => SORT_ASC ],
                 ],
             ]),
             //'dataProviderAnt'    => new ArrayDataProvider([]),
             'dataProviderLiq'      => new ArrayDataProvider([
                 'models'   => $this->getLiquidaciones(),
             ]),
             'aplicaBonificacion'   => $aplicaBonificacion,
             'saldoAFavor'          => $saldoAFavor,
         ]);
    }

    /**
     * Función que se ejecuta para realizar la baja de una DJ
     * @param integer $id Identificador de la DJ
     */
    public function actionBorrar($id)
    {
    	$model = new Ddjj();
    	$model = $this->findModel($id);

    	$res = $model->eliminarDJ(); //Elimino la DJ

    	if ( $res == 1 ){

            Yii::$app->session->setFlash( self::CONST_MENSAJE, 6 );
        } else {
            Yii::$app->session->setFlash( self::CONST_MENSAJE_ERROR, $res );
        }

        return $this->redirect(['view', 'id' => $id ]);

    }


    /**
     * Función que se utiliza para grabar los rubros en memoria.
     * @param array $tributos Arreglo con los tributos.
     */
    private function setRubros( $tributos ){

        Yii::$app->session->set( self::CONST_RUBROSYTRIBUTOS, $tributos );
    }

    /**
     * Función que se utiliza para obtener los rubros en memoria.
     */
    private function getRubros(){

        return Yii::$app->session->get( self::CONST_RUBROSYTRIBUTOS, [] );
    }

    /**
     * Función que se utiliza para grabar las retenciones en memoria.
     * @param array $retenciones Arreglo con las retenciones.
     */
    private function setRetenciones( $retenciones ){

        Yii::$app->session->set( self::CONST_RETENCIONES, $retenciones );
    }

    /**
     * Función que se utiliza para obtener las retenciones en memoria.
     */
    private function getRetenciones(){

        return Yii::$app->session->get( self::CONST_RETENCIONES, [] );
    }

    private function actualizarRetenciones( $arrayRetenciones ){

        $this->setRetenciones( Ddjj::actualizarListaRetenciones( $this->getRetenciones(), $arrayRetenciones ) );
    }

    /**
     * Función que se utiliza para grabar las liquidaciones en memoria.
     * @param array $liquidaciones Arreglo con las liquidaciones
     */
    private function setLiquidaciones( $liquidaciones ){

        Yii::$app->session->set( self::CONST_LIQUIDACIONES, $liquidaciones );
    }

    /**
     * Función que se utiliza para obtener las liquidaciones en memoria.
     */
    private function getLiquidaciones(){

        return Yii::$app->session->get( self::CONST_LIQUIDACIONES, [] );
    }

    /**
     * Función que se utiliza para setear la variable fiscaliza.
     * @param integer $fiscaliza
     */
    private function setFiscaliza( $fiscaliza ){

        Yii::$app->session->setFlash( self::CONST_DDJJ_FISCALIZA, intVal( $fiscaliza ) );
    }

    /**
     * Función que se utiliza para obtener el valor de la variables fiscaliza.
     */
    private function getFiscaliza(){

        $fiscaliza = Yii::$app->session->getFlash( self::CONST_DDJJ_FISCALIZA, 0 );

        $this->setFiscaliza( $fiscaliza );

        return $fiscaliza;
    }

    /**
     * Función que se utiliza para obtener el siguiente ID de DJ.
     */
    private function getNextID(){

        Yii::$app->session->set( self::CONST_DDJJ_ID, Yii::$app->session->get( self::CONST_DDJJ_ID, Ddjj::getNextID() ) );

        return Yii::$app->session->get( self::CONST_DDJJ_ID );
    }

    /**
    * Función que se ejecuta para mostrar los datos de una DDJJ o para ingresar una nueva DDJJ
    * @param integer $id Identificador de la DDJJ
    */
    public function actionView( $id = 0 )
    {
    	/**
    	 * $action == 0 => Formulario para inserción de datos
    	 * $action == 1 => Formulario para inserción de datos y procesar
    	 * $action == 2 => Formulario para inserción de datos y calcular
    	 * $action == 3 => Formulario para inserción de datos y grabar
    	 */

        $model = $this->findModel( $id ); //Obtengo los datos del modelo
        $model->fiscaliza = $this->getFiscaliza();

        echo $this->view( $id, $model );

    }

    private function view( $id, $model )
    {

        $rubros = $model->getDatosGrillaRubros( $id );
        $liquidaciones = $model->getDatosGrillaLiq( $id );
        $model->obtenerMontosDJ( $rubros, $liquidaciones );

        $datosret = $model->getRetencionesAsociadasADJ( intval( $model->dj_id ), $sql );

        // datos para exportar retenciones
        Yii::$app->session['sql'] = $sql;
        Yii::$app->session['columns'] = [
            ['attribute'=>'numero','contentOptions'=>['style'=>'width:45px;','class' => 'grilla'],'label' => 'Nro'],
            ['attribute'=>'fecha','contentOptions'=>['style'=>'width:60px;','class' => 'grilla'],'label' => 'Fecha'],
            ['attribute'=>'ag_rete','contentOptions'=>['style'=>'width:20px; text-align:center','class' => 'grilla'],'label' => 'Agente'],
            ['attribute'=>'ag_cuit','contentOptions'=>['style'=>'width:90px','class' => 'grilla'],'label' => 'AgR.CUIT'],
            ['attribute'=>'ag_nom_redu','contentOptions'=>['style'=>'width:90px','class' => 'grilla'],'label' => 'AgR.Nombre'],
            ['attribute'=>'tcomprob','contentOptions'=>['align'=>'center','style'=>'width:30px; text-align:center','class' => 'grilla'],'label' => 'T.Comprob.'],
            ['attribute'=>'comprob', 'contentOptions'=>['style'=>'width:45px;text-align:right','class' => 'grilla'],'label' => 'Comprob'],
            ['attribute'=>'base', 'format' => ['decimal', 2], 'contentOptions'=>['style'=>'width:65px;text-align:right','class' => 'grilla'],'label' => 'Base'],
            ['attribute'=>'monto', 'format' => ['decimal', 2], 'contentOptions'=>['style'=>'width:65px;text-align:right','class' => 'grilla'],'label' => 'Monto']

        ];

        return $this->render('view', [
            'model'             => $model,
            'id'                => $id,
            'mensaje'           => $this->getMensaje( Yii::$app->session->getFlash( self::CONST_MENSAJE, 0 ) ),
            'error'             => $this->getMensaje( Yii::$app->session->getFlash( self::CONST_MENSAJE_ERROR, 0 ) ),
            'dia'               => date('d/m/Y'),
            'error'             => $this->getMensaje( Yii::$app->session->getFlash( self::CONST_MENSAJE_ERROR, '' ) ),
            'mensaje'           => $this->getMensaje( Yii::$app->session->getFlash( self::CONST_MENSAJE, '' ) ),
            'config_ddjj'       => Ddjj::getConfig_ddjj(),
            'dataProviderRubros'=> new ArrayDataProvider([
                'allModels' => $rubros,
            ]),
            'dataProviderLiq'   => new ArrayDataProvider([
                'allModels' => $liquidaciones,
            ]),
            //'dataProviderAnt'   =>$dataProviderAnt,
            'dataProviderRete'  => new ArrayDataProvider([
                'allModels' => $datosret,
            ]),

        ]);
    }

	/**
	 * Función que se utiliza para cargar las retenciones a una DDJJ.
	 * @param integer $id Identificador de la DJ.
     * @param integer $action Identificador del tipo de acción.
	 */
	public function cargarrete( $id = 0 )
	{
		$model = DdjjController::findModel( $id ); //Obtengo los datos del modelo

        $dataProviderReteCargadas = new ArrayDataProvider([
			'allModels' => $model->getRetencionesAsociadasADJ( intval( $model->ctacte_id ) ),
		]);

		$totales = $model->getTotalesDJ( $id );

		return $this->render('rete', [
			'model' => $model,
            'action'    => $action,
            'config_ddjj'   => Ddjj::getConfig_ddjj(),
            'dataProviderReteCargadas'  => $dataProviderReteCargadas,
			'dataProviderRete' => $dataProviderRete,
			'totales' => $totales,
			'error' => $error,
		]);
	}

    /**
     * Función que se utiliza para agregar retenciones.
     * @param string $obj_id Identificador del objeto al cual se le agregarán las retenciones.
     */
    public function nuevaRete( $obj_id, $pjaxAActualizar, $idModal ){

        $model = new RetencionDetalle();
        $model->cargarDatosRetencion( $obj_id );

        return Yii::$app->controller->renderPartial('agregarRete',[
            'pjaxAActualizar'     => $pjaxAActualizar,
            'idModal'   => $idModal,
            'model'     => $model,
            'consulta'  => 0,
            'agentes'   => RetencionDetalle::agentes(),
            'tiposComprobantes' => RetencionDetalle::tiposComprobantes(),
        ]);
    }

    /**
     * Función que se utiliza para grabar nuevas retenciones y las retenciones seleccionadas.
     */
    public function actionRete( $dj_id = 0 ){

        $model = $this->findModel( $dj_id );
        $modelRete = new RetencionDetalle();
        $modelRete->setScenario( 'insert' );
        $alert = '';

        $anio   = utb::GetCampo( 'ddjj', 'dj_id = ' . $dj_id, 'anio' );
        $cuota  = utb::GetCampo( 'ddjj', 'dj_id = ' . $dj_id, 'cuota' );

        if( Yii::$app->request->isPost ){

            if( $modelRete->load( Yii::$app->request->post() )){

                if( $modelRete->grabar( 0, $anio, $cuota ) ){

                    Yii::$app->session->setFlash( self::CONST_CODIGO_MENSAJE, 1 );
                    Yii::$app->session->setFlash( self::CONST_MENSAJE, 4 );

                    return $this->redirect(['rete', 'dj_id' => $dj_id, ]);

                } else {

                    $model->addErrors( $modelRete->getErrors() );
                    Yii::$app->session->setFlash( self::CONST_CODIGO_MENSAJE, 2 );
                    Yii::$app->session->setFlash( self::CONST_MENSAJE, 1004 );
                }
            }

            $retenciones = Yii::$app->request->post( 'DDJJ_rete', [] );
            $dj_id = Yii::$app->request->post( 'dj_id', 0 );

            $model = $this->findModel( $dj_id );

            if( count( $retenciones ) > 0 ){

                $res = $model->grabarRetenciones( $retenciones, $dj_id );

                if( $res == 1 )
                {
                    Yii::$app->session->setFlash( self::CONST_CODIGO_MENSAJE, 1 );
                    Yii::$app->session->setFlash( self::CONST_MENSAJE, 5 );

                    return $this->redirect(['view', 'id' => intVal( $dj_id )]);
                }

                Yii::$app->session->setFlash( self::CONST_CODIGO_MENSAJE, 2 );
                Yii::$app->session->setFlash( self::CONST_MENSAJE, $res );

            }

        }

        echo $this->view( $dj_id, 4, 4, $model );
    }

    public function actionImprimir($id, $tipo = 1)
    {
    	$sub1 = null;
    	$sub2 = null;
    	$sub3 = null;
    	$array = ( new Ddjj )->Imprimir($id,$sub1,$sub2,$sub3);

    	$pdf = Yii::$app->pdf;
    	$pdf->marginTop = '5px';
    	$pdf->marginBottom = '5px';
    	$pdf->marginLeft = '7px';
    	$pdf->marginRight = '7px';
		if ( $tipo == 1 )
			$pdf->content = $this->renderPartial('//reportes/boletaddjj',['emision' => $array,'sub1'=>$sub1,'sub2'=>$sub2,'sub3'=>$sub3]);
		else
			$pdf->content = $this->renderPartial('//reportes/boleta',['emision' => $array,'sub1'=>$sub1,'sub2'=>$sub2,'sub3'=>$sub3]);
        $pdf->methods['SetHeader'] = '';
  		$pdf->methods['SetFooter'] = '';
  		$pdf->filename = 'BoletaDDJJ';
        return $pdf->render();
    }

    public function actionCompara()
    {
		$model          = new Ddjj();
        $arrayDDJJ      = [];
        $arrayRubros    = [];
        $trib        = 0;
        $objeto_id      = '';
        $objeto_nom     = '';
        $objeto_tipo    = 0;

        //Si se ejecuta un Pjax
        if( Yii::$app->request->isPjax ){

            //Si se reciben datos por GET
            if( Yii::$app->request->isGet ){

                //Si se cambia de objeto desde la búsqueda modal
                if( Yii::$app->request->get( '_pjax', '' ) == self::CONST_COMPARA_PJAX_CAMBIAOBJ ){

                    $trib          = Yii::$app->request->get( 'trib', 0 );
                	$objeto_id     = Yii::$app->request->get( 'objeto_id', '' );

                    if( $trib == 0 ){
                        $objeto_id = '';
                    } else {

                		$objeto_tipo = utb::getTTrib( $trib );

                		//Actualiza el tipo de objeto para la búsqueda de objeto
                		//echo '<script>$.pjax.reload({container:"#PjaxObjBusAv",data:{tobjeto:'.$objeto_tipo.'},method:"POST"});</script>';

                		if ( strlen( $objeto_id ) < 8 && $objeto_id != '' )
                		{
                			$objeto_id = utb::GetObjeto((int)$objeto_tipo,(int)$objeto_id);

                			if ( utb::verificarExistenciaObjeto( (int)$objeto_tipo, "'" . $objeto_id . "'") != 1 )
                				$objeto_id = '';
                		}

                		if ( utb::GetTObj( $objeto_id ) == $objeto_tipo )
                		{
                			$objeto_nom = utb::getNombObj("'".$objeto_id."'");

                		}

                	}
                }

                if( Yii::$app->request->get( '_pjax', '' ) == self::CONST_COMPARA_PJAX_DDJJ ){

                    $trib_id = Yii::$app->request->get( 'trib' , 0 );
            		$obj_id = Yii::$app->request->get( 'obj_id' , '' );
            		$est = Yii::$app->request->get( 'est' , '' );

            		$arrayDDJJ = $model->buscarListObj( $trib_id, $obj_id, $est );

                }

                if( Yii::$app->request->get( '_pjax', '' ) == self::CONST_COMPARA_PJAX_RUBROS ){

                    $dj_id = Yii::$app->request->get( 'dj_id' , 0 );

            		$arrayRubros = $model->getDatosGrillaRubros( $dj_id );

                }

            }

        }


        $dataProviderDDJJ = new ArrayDataPRovider([
            'allModels' => $arrayDDJJ,
            'key'       => 'dj_id',
        ]);

        $dataProviderRubros = new ArrayDataPRovider([
            'allModels' => $arrayRubros,
            'key'       => 'dj_id',
        ]);

    	return $this->render('compara',[
            'trib'                  => $trib,
            'objeto_id'             => $objeto_id,
            'objeto_nom'            => $objeto_nom,
            'objeto_tipo'           => $objeto_tipo,
            'dataProviderDDJJ'      => $dataProviderDDJJ,
            'dataProviderRubros'    => $dataProviderRubros,
            'model'                 => $model,
        ]);
    }

	/**
     * Carga el modelo con la ultima declaracion jurada anual para el objeto dado y un data provider con todas las declaraciones juradas anuales.
     * Si se pasa el año como parametro, el modelo se carga con la DDJJ de ese año
     */
    public function actionDdjjanual($obj_id = '', $anio = 0){

    	$obj_id = trim($obj_id);
    	$anio = intval($anio);
    	$m = intval(Yii::$app->request->get('m', -1));
    	$mensaje = $m === 1 ? 'Datos grabados correctamente' : null;

    	$model = new DdjjAnual();

    	if($anio > 0)
    		$model = DdjjAnual::findOne(['obj_id' => $obj_id, 'anio' => $anio]);
    	else $model = DdjjAnual::findOne(['obj_id' => $obj_id]);


    	$dp = new ArrayDataProvider(['allModels' => []]);

    	if($model !== null) $dp = new ArrayDataProvider(['allModels' => DdjjAnual::findAll(['obj_id' => $obj_id]), 'pagination' => false, 'key' => 'anio']);
    	else{

    		$model = new DdjjAnual();

    		$model->obj_id= $obj_id;

    		$datosObjeto= utb::getVariosCampos('objeto', "obj_id = '$obj_id'", 'obj_dato, nombre');
    		if($datosObjeto !== false){
    			$model->obj_dato= $datosObjeto['obj_dato'];
    			$model->nombre= $datosObjeto['nombre'];
    		}
    	}

    	return $this->render('//ctacte/ddjj_old/anual', ['model' => $model, 'dpAnual' => $dp, 'mensaje' => $mensaje]);
    }

    /**
     * Agrega una declaración jurada anual para el objeto dado
     */
    public function actionAgregaranual( $obj_id = ''){

    	$model = new DdjjAnual();

    	if(Yii::$app->request->isPost){

    		$modificar = filter_var(Yii::$app->request->post('modificar', false), FILTER_VALIDATE_BOOLEAN);
			$borrar = filter_var(Yii::$app->request->post('borrar', false), FILTER_VALIDATE_BOOLEAN);

			if(!$borrar){

				$model->setScenario('agregar');
				if($model->load(Yii::$app->request->post())){

	    			$res = false;
	    			$grabar = true;

	    			if($modificar){

	    				$model = DdjjAnual::findOne(['obj_id' => $model->obj_id, 'anio' => $model->anio]);

	    				if($model === null){
	    					$model = new DdjjAnual();
	    					$model->setScenario('agregar');
	    					$model->load(Yii::$app->request->post());
	    					$model->addError($model->obj_id, 'No hay declaración jurada correspondiente al año ' . $model->anio . ' para modificar');
	    					$grabar = false;

	    				} else{
	    					$model->setScenario('agregar');
	    					$model->load(Yii::$app->request->post());
	    				}
	    			}

	    			if($model !== null && $grabar){
	    				$res = $model->agregar($modificar);

	    				if($res) return $this->redirect(['ddjjanual', 'obj_id' => $model->obj_id, 'm' => 1]);
	    			}
	    		}
			} else {

				$model->setScenario('delete');
				if($model->load(Yii::$app->request->post())){

					$model = DdjjAnual::findOne(['obj_id' => $model->obj_id, 'anio' => $model->anio]);

					if($model !== null){

						$res = $model->borrar();

						if($res) return $this->redirect(['ddjjanual', 'obj_id' => $model->obj_id, 'm' => 1]);

					} else{
						$model = new DdjjAnual();
						$model->addError( $model->obj_id, 'La declaración jurada anual no existe');
					}
				}
			}
    	}

    	if($model === null){
    		$model = new DdjjAnual();
    		$model->obj_id = $obj_id;
    	}

    	return $this->render('//ctacte/ddjj_old/_agregar_anual', ['model' => $model]);
    }

    /**
     * Finds the Ddjj model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ddjj the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel( $id = 0 )
    {
        if (( $model = Ddjj::findOne( intval( $id ) ) ) !== null)
        {
        	$model->obtenerDatosVistaDJ( $id );

        } else {
            $model = new Ddjj();
        }

        return $model;

    }

	private function getMensaje( $id )
	{
		switch( $id ){

            case 1:

                $title = "Los datos se grabaron correctamente.";
                break;

			case 2:

				$title = 'El monto acumulado de las retenciones seleccionadas es mayor al monto de la DJ.';
				break;

			case 3:

				$title = 'Ocurrió un error al grabar los datos.';
				break;

            case 4:

                $title = "La retención se grabó correctamente.";
                break;

            case 5:

                $title = "Las retenciones se cargaron correctamente.";
                break;

            case 6:

                $title = 'La DJ se eliminó correctamente.';
                break;

            case 1001:

                $title = "La DJ no se grabó.";
                break;


            case 1002:

                $title = "No se encontraron DDJJ.";
                break;

            case 1003:

                $title = "No se encontró ninguna DJ.";
                break;

            case 1004;

                $title = "Ocurrió un error al grabar la retención.";
                break;

            case 1005:

                $title = 'La Declaración Jurada ya fue anulada con anterioridad.';
                break;

            case 1006:

                $title = 'La DJ se encuentra Paga.<br />Deberá efectuar un movimiento de Pago o DJ Rectificativa.';
                break;

            case 1007:

                $title = 'El estado de la DJ no es correcto.';
                break;

			default:

				$title = '';
		}

		return $title;
	}
}
