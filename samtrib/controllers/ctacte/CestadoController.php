<?php

namespace app\controllers\ctacte;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use app\utils\db\utb;

use app\models\ctacte\CEstado;

/**
 * CestadoController implements the CRUD actions for CalcDesc model.
 */
 
class CestadoController extends Controller
{

	public function beforeAction( $action ){
		
		$operacion = str_replace("/", "-", Yii::$app->controller->route);
	    
	    if ( !utb::getExisteAccion( $operacion ) ) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }
	    
		return true;
	}
	
	/**
	 * Función que se utiliza para generar cambios de estado.
	 * @param integer $consulta Determina si se habilitan los inputs para ingresar
	 * los datos.
	 */
	public function actionView( $consulta = 1 )
	{
    	$alert = '';
    	
    	$model = new CEstado();
    	
    	if ( $model->load( Yii::$app->request->post() ) )
    	{
    		/*
    		 * $aceptar es una variable que determina si se graban los datos o sólo se 
    		 * recupera el estado anterior.
    		 */
    		$aceptar = Yii::$app->request->post( 'txAceptar', 0 );
			
    		if ( $aceptar == 1 )	//Grabar
    		{
    			# Ejecuto el cambio de estado
    			$model->cambioEstado();
    			
    			if ( ! $model->hasErrors() )
    			{
    				$alert = 'El estado se modificó correctamente.';
    				
    				$model = new CEstado();
    				
    				$consulta = 1;
    				
    			} else
    			{
    				$model->recuperarEstado();
    			} 
    			
    		} 
			
			if ( $aceptar == 2 )	//Cargar los estados 
    		{
    			$model->recuperarEstado();
    		}
    		
    	}
    	
    	return $this->render('view', [
			'model' => $model,
			'consulta' => $consulta,
			'alert' => $alert,	
		]);
	}
	
}
