<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;

use app\models\Listado;

use app\utils\db\utb;

abstract class ListadoController extends Controller{

	const VISTA_OPCION		= '//listado/base_opcion';
	const VISTA_RESULTADO	= '//listado/base_resultado';

	const LISTADO_RESULTADO	= 'listado_resultado';
	const LISTADO_MODELO	= 'listado_modelo';

	public $cantidadResultados = 60;

	/**
	* Modelo que se utiliza en la vista de opciones para validar datos
	*
	* @return app\models\Listado
	*/
	abstract function modelo();

	/**
	* Datos necesarios para mostrar la vista de opciones
	*
	* @return Array
	*
	*	'breadcrumbs' => Arreglo de breadcrumbs,
	*	'vista' => Vista que contiene el formulario de opciones,
	*
	*/
	abstract function datosOpciones($model);

	/**
	* Campos a mostrar para las opciones de busqueda.
	*
	* @return Array - Cada elemento del arreglo es a su vex un arreglo y representa un campo a mostrar.
	*	Los elementos deben tener los campos obligatorios:
	*		label (string)	=> 'Texto a mostrar al usuario para identificar el campo'.
	*		tipo (string) 	=> 'Tipo de campo a dibujar'.
	*
	* El campo 'tipo' soporta los siguientes valores:
	*
	*		- texto: Representa un texto. Se deben incluir los parámetros
	*			@atributo (string)	= atributo del modelo que representa el texto.
	*			@longitud (integer)	= largo máximo del campo.
	*
	*		- rango: Representa un rango de valores. Se deben incluir los parámetros
	*			@desde (string)= atributo del modelo que representa el inicio del rango.
	*			@hasta (string)= atributo del modelo que representa el final del rango.
	*
	*		- rangoFecha: Representa un rango de fechas. Se deben incluir los mismos campos que rango.
	*
	*		- lista: Representa una lista desplegable. Se deben incluir los parámetros
	*			@atributo (string)= atributo del modelo.
	*			@elementos (array)= Elementos a mostrar en la lista.
	*
	*		- tablaAuxiliar: Representa una busqueda a través de una tabla auxiliar en una ventana modal. Se deben incluir los parámetros
	*			@codigo (string)= Atributo del modelo que representa el codigo del registro.
	*			@nombre (string)= Atributo del modelo que representa el nombre del registro.
	*			@busqueda (array)= Representa los elementos relacionados con la tabla y la consulta a ejecutar. Debe contener:
	*				@tabla (string)= Tabla de la que se deben buscar los registros.
	*				@campoCodigo (string)= Columna de la tabla que representa el codigo del registro.
	*				@campoNombre (string)= Columna de la tabla que representa el nombre del registro.
	*				@condicion (string) (opciones = '')= Condicion a incluir en el 'where'.
	*
	*		- mascara: Representa un campo con una mascara establecida. Se deben incluir los parámetros
	*			@atributo (string)= Atributo del modelo que representa el campo.
	*			@mascara (string)= Mascara a aplicar.
	*
	*		- check: Representa un checkbox. Se deben incluir los parámetros
	*			@atributo (string)= Atributo del modelo que representa el campo.
	*/
	abstract function campos();
	
	public function menu_derecho(){return null;}
	
	public function botones(){return null;}

	/**
	* Datos necesarios para mostrar la vista de resultados
	*
	* @return Array
	*
	*	'breadcrumbs' => Arreglo de breadcrumbs,
	*	'columnas' => [
	*			'atributos' => Atributos que deben mostrarse,
	*			'acciones' => Actions que deben mostrarse (view, update y delete)
	*					]
	*/
	abstract function datosResultado($model, $resultados);

	/**
	*
	*/
	private function establecerScenario($model, $scenario, $defecto = 'default'){

		$existentes= $model->scenarios();

		if(array_key_exists($scenario, $existentes))
			$model->setScenario($scenario);
		else $model->setScenario($defecto);
	}

	public function actionIndex(){

		$model = isset($_POST['model']) ? unserialize(urldecode(stripslashes($_POST['model']))) : $this->modelo();

		if( Yii::$app->request->isPjax ){

			if( Yii::$app->request->get( '_pjax', '' ) == "#pjaxGrillaResultado" ){
				$model 	= Yii::$app->session->get( self::LISTADO_MODELO, [] );
				$res	= Yii::$app->session->get( self::LISTADO_RESULTADO, [] );

				if( $model == [] ){

					return $this->redirect([ 'index' ]);
				}

				return $this->dibujarResultado($model, $res);
			}
		}

		if( Yii::$app->request->isPost and !isset($_POST['model']) ){

			$this->establecerScenario( $model, Listado::SCENARIO_BUSCAR);

			if($model->load(Yii::$app->request->post()) && $model->validar()){

				$res = $model->buscar();

				Yii::$app->session->set( self::LISTADO_MODELO, $model );
				Yii::$app->session->set( self::LISTADO_RESULTADO, $res );

				return $this->dibujarResultado($model, $res);
			}
		}

		return $this->dibujarOpciones( $model );
	}

	public function actionImprimir( $format = 'A4-P' ){

		$model = isset($_POST['model']) ? unserialize(urldecode(stripslashes($_POST['model']))) : [];

		$datos = $this->datosResultado($model, [] );

		$datos['dataProviderResultados'] = new ActiveDataProvider([
		    'query' => $model->buscar(),
			'pagination' => false
		]);

		$datos['descripcion'] = $this->armarDescripcion($model, $this->campos());

		$pdf = Yii::$app->pdf;
      	if (strtoupper($format) != 'A4-P') $pdf->format = strtoupper($format);
		$pdf->marginTop = '30px';
		$pdf->content = $this->renderPartial('//reportes/reportelistado', ['datos' => $datos, 'titulo' => $model->titulo() ]);

		return $pdf->render();
	}

	public function actionExportar(){
		$model = $this->modelo();
		//$this->establecerScenario( $model, Listado::SCENARIO_BUSCAR);
		
		foreach ( $_POST as $key => $value )
			$model->$key = $value;
		
		$datos = $model->buscar()->createCommand()->queryAll();
		
		$campos_desc = $this->datosResultado($model, [] )['exportar'];
		
		return json_encode([ 'datos' => $datos, 'campos_desc' => $campos_desc ]);
	}

	private function dibujarOpciones($model){

		$datos = $this->datosOpciones( $model );	//Arreglo debreadcrumbs

		if(!array_key_exists('extras', $datos)) $datos['extras']= [];

		$datos['campos']	= $this->campos();
		$datos['menu_derecho']	= $this->menu_derecho();
		$datos['botones']	= $this->botones();
		$datos['model']		= $model;

		return $this->render(self::VISTA_OPCION, $datos);
	}

	/**
	 * Función que se utiliza para dibujar los resultados.
	 */
	private function dibujarResultado($model, $resultados){

		$datos = $this->datosResultado($model, $resultados);

		// $datos['dataProviderResultados'] = new ArrayDataProvider([
		// 	'allModels' => $resultados,
		// 	'key' 		=> $model->pk(),
		// 	'pagination' => [
		// 		'pageSize' => $this->cantidadResultados,
		// 	],
		// ]);

		$datos['dataProviderResultados'] = new ActiveDataProvider([
		    'query' => $resultados,
		    //'params' => [':status' => 1],
			'key' 		=> $model->pk(),
		    'pagination' => [
		        'pageSize' => $this->cantidadResultados,
		    ],
			'sort'	=> $model->sort(),
		]);

		$datos['model']= $model;

		$datos['descripcion'] = $this->armarDescripcion($model, $this->campos());

		$datos['titulo'] = $model->titulo();

		return $this->render( self::VISTA_RESULTADO, $datos );
	}

	private function armarDescripcion($model, $campos){

		$ret = '';

		foreach( $campos as $datos ){

			$tipo = $datos['tipo'];

			switch( $tipo ){

				case 'check':	//Check

					if( $model->hasProperty($datos['atributo']) && $model[$datos['atributo']] == 1 && $model[$datos['atributo']] != null )
						$ret .= " - " . $datos['label'] . ".";

					break;

				case 'lista':
				case 'listachica':

					if($model->hasProperty( $datos['atributo']) && $model[$datos['atributo']] != null )
						// recorrer el array para determinar el nombre del atributo
						foreach( $datos['elementos'] as $key => $value){
							if( $model[$datos['atributo']] == $key ){
								$ret .= " - " . $datos['label'] . " = '" . $value . "'";
								break;
							}
						}
						//$ret .= " - " . $datos['label'] . " = '" . $model[ $datos['atributo']] . "'";

					break;

				case 'rango':
				case 'rangoObjeto':
				case 'rangoNumero':
				case 'rangoFecha':
					if($model->hasProperty($datos['desde']) && $model->hasProperty($datos['hasta']) && $model[$datos['desde']] != null && $model[$datos['hasta']] != null)
				 		$ret .= ' -' . $datos['label'] . ' entre ' . $model[$datos['desde']] . ' y ' . $model[$datos['hasta']] . '.';

				 	break;

				case 'texto':
					if($model->hasProperty($datos['atributo']) && $model[$datos['atributo']] != null)
						$ret .= ' -' . $datos['label'] . ' contiene "' . $model[$datos['atributo']] . '".';

					break;

				case 'mascara':
					if($model->hasProperty($datos['atributo']) && $model[$datos['atributo']] != null)
						$ret .= ' -' . $datos['label'] . '= ' . $model[$datos['atributo']] . '.';

					break;

				case 'tablaAuxiliar':

					if($model->hasProperty($datos['nombre']) && $model[$datos['nombre']] != null)
						$ret .= ' -' . $datos['label'] . "= '" . $model[$datos['nombre']] . "'.";

					break;

			}
		}
		return $ret;
	}

	public function actionObtenernombre() {

		$campoNombre = Yii::$app->request->post( 'campoNombre', '' );
		$tabla = Yii::$app->request->post( 'tabla', '' );
		$condicion = Yii::$app->request->post( 'condicion', '' );

		$nombre = utb::getCampo($tabla, $condicion, $campoNombre);

		$devolver = [
    			'nombre' => $nombre
    		];

    	return json_encode($devolver);

	}
}
?>
