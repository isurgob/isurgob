<?php

namespace app\controllers\usuarioweb;

use Yii;
use app\models\usuarioweb\UsuarioWeb;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\utils\db\utb;
use yii\data\ArrayDataProvider;

/**
 * UsuarioController implements the CRUD actions for Usuario model.
 */
class UsuariowebController extends Controller
{
    const CONST_MENSAJE         = 'const_mensaje';
    const CONST_MENSAJE_ERROR   = 'const_mensaje_error';

    const CONST_LAST_ID                 = 'const_last_id';
    const CONST_ARRAY_CONTRIBUYENTES    = 'const_array_contribuyentes';

    const MENSAJE_ID    = 'mensaje_id';
    const MENSAJE_ALERT = 'mensaje_alert';

    public $cargarContribuyentesDesdeBD = false;

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

    public function beforeAction( $action )
    {
    	$operacion = str_replace("/", "-", Yii::$app->controller->route);

	    if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('//site/nopermitido');

	        return false;
	    }

		if ($operacion == 'usuarioweb-usuarioweb-view' && $_GET['consulta']<>1){
			if (utb::getExisteProceso(3702) != 1){
				echo $this->render('//site/nopermitido');

				return false;
			}

		}

        $id = $action->getUniqueId();

        if( $id != Yii::$app->session->getFlash( self::CONST_LAST_ID, '' ) ){

            $this->cargarContribuyentesDesdeBD = true;
            $this->setContribuyentes( [] );

        }

        Yii::$app->session->setFlash( self::CONST_LAST_ID, $id );

    	return true;
    }

    /**
     * Función que se utiliza para mostrar un listado con los usuarios web.
     */
    public function actionIndex()
    {

        $usuarios   = UsuarioWeb::getUsuariosWeb();
        $obj_id     = '';
        $obj_nom    = '';

        if( Yii::$app->request->isPjax ){

            //Cuando cambia el código de objeto
            if( Yii::$app->request->get( '_pjax', '' ) == '#usuarioweb_pjaxInputObjeto' ){

                $obj_id = Yii::$app->request->get( 'obj_id', '' );

                UsuarioWeb::cargarObjeto( $obj_id, $obj_nom );
            }

            //Cuando se aplique un filtro
            if( Yii::$app->request->get( '_pjax', '' ) == '#PjaxGrilla' ){

                $usr_nom    = Yii::$app->request->get( 'usr_nom', '' );
                $usr_doc    = Yii::$app->request->get( 'usr_doc', '' );
                $usr_apenom = Yii::$app->request->get( 'usr_apenom', '' );
                $usr_obj    = Yii::$app->request->get( 'usr_obj', '' );
				$baja    	= intVal(Yii::$app->request->get( 'baja', 0 ));
				
                $usuarios = UsuarioWeb::getUsuariosWeb( $usr_nom, $usr_doc, $usr_apenom, $usr_obj, $baja );
            }
        }

        if( Yii::$app->request->isPost ){

        }

		return $this->render( 'index',[

            'mensaje'       => $this->getMensaje( Yii::$app->session->getFlash( self::CONST_MENSAJE, 0 ) ),
            'error'         => $this->getMensaje( Yii::$app->session->getFlash( self::CONST_MENSAJE_ERROR, 0 ) ),

            'dataProvider' => new ArrayDataProvider([
                'allModels'=> $usuarios,
                'pagination' => [
                    'pageSize' => 50,
                ],
                'sort' => [
                    'attributes' => [
                        'usr_id',
                        'nombre',
                        'obj_id',
                        'apenom',
                        'ndoc',
                    ],
                    'defaultOrder' => [
                        'apenom' => SORT_ASC,
                    ],
                ],
			]),

			'model' => null,

            'obj_id'    => $obj_id,
            'obj_nom'   => $obj_nom,

		]);
    }

    public function actionView( $consulta = 1, $id = 0 )
    {
        /*
		* $consulta = 0 => Nuevo
		* $consulta = 1 => Consulta
		* $consulta = 2 => Eliminar
		* $consulta = 3 => Editar
		*/

        $model = $this->findModel( $id );

        if( $this->cargarContribuyentesDesdeBD ){   //Cargar contribuyentes

            $this->setContribuyentes( UsuarioWeb::getContribuyentesAsociadosAUsuario( $id ) );
        }

        if( Yii::$app->request->isPjax ){

            //Cuando cambia el código de objeto
            if( Yii::$app->request->get( '_pjax', '' ) == '#usuarioweb_form_pjaxInputObjeto' ){

                $obj_id = Yii::$app->request->get( 'obj_id', '' );

                UsuarioWeb::cargarObjeto( $obj_id, $obj_nom );

                $model->obj_id = $obj_id;
                $model->obj_nom = $obj_nom;
            }

            //Cuando se agrega o quita un contribuyente del arreglo
            if( Yii::$app->request->get( '_pjax', '' ) == '#usuarioweb_pjaxContribuyentes' ){

                $obj_id = Yii::$app->request->get( 'obj_id', '' );
                $action = Yii::$app->request->get( 'action', 1 );

                $this->setContribuyentes( UsuarioWeb::ABMContribuyentes( $obj_id, $action, $this->getContribuyentes() ) );

            }
        }

		if ( Yii::$app->request->isPost && $consulta != 1){

			if (!isset($_POST['pjax'])) {

				$model->usr_id = $_POST['txUsrId'];
				$model->nombre = trim($_POST['txUsrNom']);
				$model->obj_id = $_POST['txUsrObjeto'];
				$model->mail = $_POST['txMail'];
				$model->acc_contrib = isset($_POST['ckUsrWebAccContrib']) && $_POST['ckUsrWebAccContrib'] == 1 ? 'S' : 'N';
				$model->acc_dj = isset($_POST['ckUsrWebAccDJ']) && $_POST['ckUsrWebAccDJ'] == 1 ? 'S' : 'N';
				$model->acc_proveedor = isset($_POST['ckUsrWebAccProv']) && $_POST['ckUsrWebAccProv'] == 1 ? 'S' : 'N';
				$model->acc_agrete = isset($_POST['ckUsrWebAccAgRete']) && $_POST['ckUsrWebAccAgRete'] == 1 ? 'S' : 'N';
				$model->acc_escribano = isset($_POST['ckUsrWebAccEscrib']) && $_POST['ckUsrWebAccEscrib'] == 1 ? 'S' : 'N';
				$model->comer_asoc = unserialize(urldecode(stripslashes($_POST['arrayComerAsoc'])));

				$error = $model->Validar($consulta);

                if ($error != ""){
                    return $error;

                } else {

					$error = $model->grabarUsuario( $consulta, $this->getContribuyentes() );

					if ($error != "") return $error;

					if ($consulta == 0){

                        Yii::$app->session->setFlash( 'imprimir', '1' );
                    }

					Yii::$app->session->setFlash( self::CONST_MENSAJE, 1 );

					return $this->redirect(['index']);
				}
			}
		}

		return $this->render( 'view', [

            'model'      => $model,
			'consulta'   => $consulta,

			'dpContribuyentes' => new ArrayDataProvider([
                'allModels' => $this->getContribuyentes(),
            ]),
		]);
    }

    /**
     * Función que se utiliza para actualizar el arreglo en memoria de contribuyentes.
     */
    private function setContribuyentes( $contribuyentes = [] ){

        Yii::$app->session->set( self::CONST_ARRAY_CONTRIBUYENTES, $contribuyentes );
    }

    /**
     * Función que se utiliza para obtener el arreglo en memoria de contribuyentes.
     */
    private function getContribuyentes(){

        return Yii::$app->session->get( self::CONST_ARRAY_CONTRIBUYENTES, [] );
    }

    /**
     * Función que se utiliza para modificar la clave de un usuario por una aleatoria.
     * @param integer $usr Código de usuario
     */
	public function actionLimpiarclave($usr)
	{
		$cambioClave = ( new UsuarioWeb )->claveLimpiar($usr);

		$model = UsuarioWeb::findOne($usr);
		Yii::$app->session->setFlash('imprimir', '1' );
		Yii::$app->session->setFlash('id', $model->obj_id );

        if( $cambioClave ){

            Yii::$app->session->setFlash( self::CONST_MENSAJE, 1 );
        } else {

            Yii::$app->session->setFlash( self::CONST_MENSAJE_ERROR, 1001 );
        }

		return $this->redirect([ 'index' ]);
	}

	public function actionComprobanteusrweb($id)
	{
		$datos = UsuarioWeb::findOne(['obj_id' => $id,'est'=>'A']);
		$texto = UsuarioWeb::Texto($datos->usr_id);

		$pdf = Yii::$app->pdf;
		$pdf->marginTop = '30px';
		$pdf->content = $this->renderPartial('//reportes/comprobanteusrweb',['datos' => $datos,'texto' => $texto]);
		return $pdf->render();
	}

    private function getMensaje( $id ){

        switch( $id ){

            case 1:

                $title = 'Los datos se grabaron correctamente.';
                break;

            case 1000:

                $title = 'Ocurrió un error al grabar los datos.';
                break;

            case 1001:

                $title = 'No se pudo cambiar la clave.';
                break;

            default:

                $title = '';
                break;
        }

        return $title;
    }

    /**
     * Finds the Usuario model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Usuario the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UsuarioWeb::findOne($id)) !== null) {

			$model->obj_nom = utb::getNombObj($model->obj_id,false);


        } else {
            $model = new UsuarioWeb();
        }

        return $model;
    }
}
