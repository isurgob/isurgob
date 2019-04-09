<?php

namespace app\controllers\ctacte;

use Yii;

use app\controllers\ListadoController;
use app\utils\db\utb;
use app\models\ctacte\FacilidaListado;
use app\models\ctacte\Facilida;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class ListadofacilidaController extends ListadoController{

	/**
	 * Ingresar el modelo a utilizar en la búsqueda.
	 */
	public function modelo(){
		return new FacilidaListado();
	}

	public function datosOpciones($model){

		return [
			'breadcrumbs' => [
				['label' => 'Facilida', 'url' => [ '//ctacte/facilida/view' ] ],
				'Listado',
				'Opciones'
			]
		];
	}

	/**
	 * Función que se utiliza para generar los datos que se enviarán a la vista de resultados.
	 */
	public function datosResultado($model, $resultados){

		/**
		 * Los datos que deben/pueden ir son;
		 *
		 *	breadcrumbs 	=> Arreglo de breadcrumbs.
		 *	columnas		=> Arreglo con los datos que se visualizarán en la grilla y la forma en la que se verán.
		 *	urlOpciones		=> URL (string) a dónde retorna el botón Volver.
		 *	exportar		=> URL (string) a dónde se envía para exportar los resultados.
		 *	imprimir		=> URL (string) a dónde se envía para imprimir.
		 */

		return [
			'breadcrumbs' => [
				[ 'label' => 'Facilida', 'url' => [ '//ctacte/facilida/view' ] ],
				[ 'label' => 'Listado', 'url' => [ '//ctacte/listadofacilida/index' ] ],
				[ 'label' => 'Resultado' ],
			],

			//TODO colocar las columnas que se van a listar (sin las de opciones)
			'columnas' => [
				[ 'attribute' => 'faci_id', 'label' => 'Cod.', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'obj_id', 'label' => 'Objeto', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'num_nom', 'label' => 'Contribuyente', 'contentOptions' => [ 'style' => 'width: 200px; text-align: left' ] ],
				[ 'attribute' => 'trib_nomredu', 'label' => 'Tributo', 'contentOptions' => [ 'style' => 'width: 100px; text-align: left' ] ],
				[ 'attribute' => 'total', 'label' => 'Deuda', 'contentOptions' => [ 'style' => 'width: 1px;' ] ],
                [ 'attribute' => 'est_nom', 'label' => 'Est.', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'fchalta', 'label' => 'Alta', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ], 'format' => [ 'date', 'php:d/m/Y'] ],
				[ 'attribute' => 'fchvenc', 'label' => 'Venc.', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ], 'format' => [ 'date', 'php:d/m/Y'] ],
				[ 'attribute' => 'fchimputa', 'label' => 'Imputa.', 'contentOptions' => [ 'style' => 'width: 100px; text-align: center' ], 'format' => [ 'date', 'php:d/m/Y'] ],
				[
	                'class' => 'yii\grid\ActionColumn',
	                'contentOptions'=>['style'=>'width:40px;text-align: center'],
	                'template' => '{view}',
	                'buttons'=>[

	                    'view' => function($url, $model, $key)
	                                {
	                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['ctacte/facilida/view', 'id' => $model['faci_id']], ["data-pjax" => "0" ]);
	                                },

	                ],
	            ],
			],

			'urlOpciones' => '//ctacte/listadofacilida/index',
			
			'imprimir' => '//ctacte/listadofacilida/imprimir',
			
			'exportar' => [ 'Cod.', 'Objeto', 'Contribuyente', 'Tributo', 'Deuda', 'Est.', 'Alta', 'Venc.', 'Imputa.' ],

		];
	}

	/**
	 * Arreglo de campos que se mostrarán para buscar
	 */
	public function campos(){

        $arrayTributo   	= utb::getAux( 'trib', 'trib_id', 'nombre', 0, "trib_id not in (1,2,4,6,12)" );
		$arrayTipoObjeto	= utb::getAux( 'objeto_tipo', 'cod', 'nombre', 0, "est='A'" );
		$arrayEstado		= utb::getAux( 'plan_test', 'cod', 'nombre', 0, "cod in (1,2,5)" );

		return [

			[ 'tipo' => 'rangoNumero', 'label' => 'Nº Facilidad', 'desde' => 'numero_desde', 'hasta' => 'numero_hasta' ],
			[ 'tipo' => 'lista', 'label' => 'Tributo', 'atributo' => 'tributo', 'elementos' => $arrayTributo ],
			[
				'tipo' => 'rangoYLista', 'label' => 'Objeto', 'desde' => 'objeto_desde', 'hasta' => 'objeto_hasta',
				'atributoLista' => 'objeto_tipo', 'elementos' => $arrayTipoObjeto, 'listaLabel' => 'Objeto', 'listaColumnas' => 2,
			],
			[ 'tipo' => 'listachica', 'label' => 'Estado', 'atributo' => 'estado', 'elementos' => $arrayEstado ],
			[ 'tipo' => 'texto', 'label' => 'Contribuyente', 'atributo' => 'contribuyente', 'columnas' => 8, 'caracteres' => 50 ],
			[ 'tipo' => 'rangoNumero', 'label' => 'Monto Deuda', 'desde' => 'deuda_desde', 'hasta' => 'deuda_hasta' ],
			[ 'tipo' => 'rangoFecha', 'label' => 'Fecha Alta', 'desde' => 'fecha_alta_desde', 'hasta' => 'fecha_alta_hasta' ],
			[ 'tipo' => 'rangoFecha', 'label' => 'Fecha Venc.', 'desde' => 'fecha_venc_desde', 'hasta' => 'fecha_venc_hasta' ],
			[ 'tipo' => 'rangoFecha', 'label' => 'Fecha Imputa.', 'desde' => 'fecha_imputa_desde', 'hasta' => 'fecha_imputa_hasta' ],
			[ 'tipo' => 'rangoFecha', 'label' => 'Fecha Baja', 'desde' => 'fecha_baja_desde', 'hasta' => 'fecha_baja_hasta' ],
			[ 'tipo' => 'checkOpcion', 'atributo' => 'baja_automatica', 'columnas' => 4 ],

		];
	}

}
?>
