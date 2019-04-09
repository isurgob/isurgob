<?php

namespace app\controllers\objeto\listado;

use app\controllers\ListadoController;
use app\models\objeto\listado\LocalComercial;


class LocalcomercialController extends ListadoController{
	

	public function modelo(){
		return new LocalComercial();
	}

	public function datosOpciones($model){

		return [
			'breadcrumbs' => ['Local']
		];
	}

	public function datosResultado($model, $resultados){

		$urlView= '//objeto/comer/view';

		return [
			'breadcrumbs' => ['Local'],
			'acciones' => ['view' => $urlView]
		];
	}

	public function campos(){

		return [

			['label' => 'Objeto', 'tipo' => 'rango', 'desde' => 'codigo_comercio_desde', 'hasta' => 'codigo_comercio_hasta'],
			['label' => 'Nombre de fantasia', 'tipo' => 'texto', 'atributo' => 'nombre_fantasia', 'longitud' => 100],
			['label' => 'Responsable', 'tipo' => 'texto', 'atributo' => 'nombre_titular', 'longitud' => 100],
			['label' => 'Rubro', 'tipo' => 'tablaAuxiliar', 'codigo' => 'rubro_codigo', 'nombre' => 'rubro_nombre',
																 'busqueda' => [
																'tabla' => 'rubro',
																'campoCodigo' => 'rubro_id',
																'campoNombre' => 'nombre',
																'condicion' => '1 = 1'
																	]]
		];
	}
}
?>