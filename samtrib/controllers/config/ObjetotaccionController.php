<?php

namespace app\controllers\config;

use Yii;
use app\models\config\ObjetoTaccion;
use yii\web\Controller;
use app\utils\db\utb;

/**
 * ObjetotaccionController implements the CRUD actions for ObjetoTaccion model.
 */
class ObjetotaccionController extends Controller
{
    
    public function beforeAction($action)
    {
    	$operacion = str_replace("/", "-", Yii::$app->controller->route);
	    
	    if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }
    	
    	return true;
    } 

    /**
     * Lists all ObjetoTaccion models.
     * @return mixed
     */
    public function actionIndex(){
    	
		// al seleccionar una fila de la grilla
        $cod = intval(Yii::$app->request->post( 'cod', 0 ));

        $model = ObjetoTaccion::findOne($cod);
        if ( $model == null )
            $model = new ObjetoTaccion();

        $model->setScenario( Yii::$app->request->post( 'scenario', "consulta" ) ); 
        $model->tobj = intval(Yii::$app->request->post( 'tobj', $model->tobj )); // al seleccionar tipo de objeto del formulario de edicion
        
        // filtro de tipo de objeto del index
        $filtroTObj = Yii::$app->request->post( 'filtroTObj', 0 );
        $criterio = $filtroTObj == 0 ? '' : 'tobj=' . $filtroTObj;
        // fin filtro de tipo de objeto

        if( $model->load(Yii::$app->request->post()) and $model->validate() ){
            
            if ( $model->Grabar() ){
                Yii::$app->session->setFlash( "mensaje", "Los datos se grabaron correctamente" );
                return $this->redirect([ 'index' ]);
            }
        }

        return $this->render('index', [
            'model' => $model,
            'tipoObjeto' => utb::getAux('objeto_tipo','cod','nombre',1),
            'dpTAccion' => $model->buscarObjetoTaccion( $criterio ),
            'listaEstado' => utb::getAux('objeto_test', 'cod', 'nombre', 0, ( $model->tobj != 0 ? "tobj=" . $model->tobj : '' ) ),
            'mensaje'   => Yii::$app->session->getFlash( "mensaje", "" )
        ]);
    }

}
