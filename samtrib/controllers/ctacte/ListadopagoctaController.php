<?php

namespace app\controllers\ctacte;

use Yii;

use app\controllers\ListadoController;
use app\utils\db\utb;
use app\models\ctacte\PagoctaListado;
use app\models\ctacte\Pagocta;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class ListadopagoctaController extends ListadoController{

	/**
	 * Ingresar el modelo a utilizar en la búsqueda.
	 */
	public function modelo(){
		return new PagoctaListado();
	}

	public function datosOpciones($model){

		return [
			'breadcrumbs' => [
				['label' => 'Pago a cuenta', 'url' => [ '//ctacte/pagocta/view' ] ],
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
				[ 'label' => 'Pago a cuenta', 'url' => [ '//ctacte/pagocta/view' ] ],
				[ 'label' => 'Listado', 'url' => [ '//ctacte/listadopagocta/index' ] ],
				[ 'label' => 'Resultado' ],
			],

			//TODO colocar las columnas que se van a listar (sin las de opciones)
			'columnas' => [
				[ 'attribute' => 'pago_id', 'label' => 'Cod.', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'trib_nom', 'label' => 'Tributo', 'contentOptions' => [ 'style' => 'width: 100px; text-align: left' ] ],
				[ 'attribute' => 'obj_id', 'label' => 'Objeto', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'obj_nom', 'label' => 'Contribuyente', 'contentOptions' => [ 'style' => 'width: 200px; text-align: left' ] ],
				[ 'attribute' => 'subcta', 'label' => 'Subcta', 'contentOptions' => [ 'style' => 'width: 1px;' ] ],
                [ 'attribute' => 'anio', 'label' => 'Año', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'cuota', 'label' => 'Cuota', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'monto', 'label' => 'Monto', 'contentOptions' => [ 'style' => 'width: 1px; text-align: right' ] ],
				[ 'attribute' => 'fchlimite', 'label' => 'Fecha Límite', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ], 'format' => [ 'date', 'php:d/m/Y'] ],
				[ 'attribute' => 'fchpago', 'label' => 'Fecha Pago', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ], 'format' => [ 'date', 'php:d/m/Y'] ],
				[ 'attribute' => 'est', 'label' => 'Est.', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[
	                'class' => 'yii\grid\ActionColumn',
	                'contentOptions'=>['style'=>'width:40px;text-align: center'],
	                'template' => '{view}',
	                'buttons'=>[

	                    'view' => function($url, $model, $key)
	                                {
	                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['ctacte/pagocta/view', 'id' => $model['pago_id']], ["data-pjax" => "0" ]);
	                                },

	                ],
	            ],
			],

			'urlOpciones' => '//ctacte/listadopagocta/index',

		];
	}

	/**
	 * Arreglo de campos que se mostrarán para buscar
	 */
	public function campos(){

        $arrayTributo   	= utb::getAux( 'trib', 'trib_id', 'nombre', 0, "trib_id not in (1,2,4,6,12)" );
		$arrayTipoObjeto	= utb::getAux( 'objeto_tipo', 'cod', 'nombre', 0, "est='A'" );
		$arrayEstado		= utb::getAux( 'ctacte_test', 'cod', 'nombre', 0, "cod IN ('D','B','P')" );

		return [

			[ 'tipo' => 'rangoNumero', 'label' => 'Código', 'desde' => 'numero_desde', 'hasta' => 'numero_hasta' ],
			[
				'tipo' => 'rangoYLista', 'label' => 'Objeto', 'desde' => 'objeto_desde', 'hasta' => 'objeto_hasta',
				'atributoLista' => 'objeto_tipo', 'elementos' => $arrayTipoObjeto, 'listaLabel' => 'Objeto', 'listaColumnas' => 2,
			],
			[ 'tipo' => 'rangoNumero', 'label' => 'Monto', 'desde' => 'monto_desde', 'hasta' => 'monto_hasta' ],
			[ 'tipo' => 'lista', 'label' => 'Tributo', 'atributo' => 'tributo', 'elementos' => $arrayTributo ],
			[ 'tipo' => 'periodoSimple', 'label' => 'Período', 'anio' => 'anio', 'cuota' => 'cuota' ],
			[ 'tipo' => 'rangoFecha', 'label' => 'Fecha Límite', 'desde' => 'fecha_limite_desde', 'hasta' => 'fecha_limite_hasta' ],
			[ 'tipo' => 'rangoFecha', 'label' => 'Fecha Pago', 'desde' => 'fecha_pago_desde', 'hasta' => 'fecha_pago_hasta' ],
			[ 'tipo' => 'rangoFecha', 'label' => 'Fecha Alta', 'desde' => 'fecha_alta_desde', 'hasta' => 'fecha_alta_hasta' ],
			[ 'tipo' => 'listachica', 'label' => 'Estado', 'atributo' => 'estado', 'elementos' => $arrayEstado ],

		];
	}

}
?>
