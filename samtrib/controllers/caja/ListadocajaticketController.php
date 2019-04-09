<?php

namespace app\controllers\caja;

use Yii;

use app\controllers\ListadoController;
use app\utils\db\utb;
use app\models\caja\CajaTicketListado;
use app\models\caja\CajaTicket;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class ListadocajaticketController extends ListadoController{

	/**
	 * Ingresar el modelo a utilizar en la búsqueda.
	 */
	public function modelo(){
		return new CajaTicketListado();
	}

	public function datosOpciones($model){

		return [
			'breadcrumbs' => [
				['label' => 'Caja Ticket', 'url' => [ '//caja/cajaticket/view' ] ],
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
				[ 'label' => 'Caja Ticket', 'url' => [ '//caja/cajaticket/view' ] ],
				[ 'label' => 'Listado de Tickets', 'url' => [ '//caja/listadocajaticket/index' ] ],
				[ 'label' => 'Resultado' ],
			],

			//TODO colocar las columnas que se van a listar (sin las de opciones)
			'columnas' => [
				[
					'attribute' => 'ticket',
					'label' => 'Ticket',
					'content'=> function($model, $key, $index, $column) {

						return Html::a( $model[ 'ticket' ], [ 'caja/cajaticket/ticket', 'id' => $model[ 'ticket' ], 'list' => 1 ],
							['class' => 'profile-link']
						);
					},
					'contentOptions'=> [ 'style' => 'width: 1px; text-align: center' ],
				],
				[
					'attribute' => 'opera',
					'label' => 'Opera',
					'content'=> function($model, $key, $index, $column) {

						return Html::a( $model[ 'opera' ], [ 'caja/cajaticket/opera', 'id' => $model[ 'opera' ], 'list' => 1 ],
							['class' => 'profile-link']
						);
					},
					'contentOptions'=> [ 'style' => 'width: 1px; text-align: center' ],
				],
				[ 'attribute' => 'fecha', 'label' => 'Fecha', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ], 'format' => [ 'date', 'php:d/m/Y'] ],
				[ 'attribute' => 'monto', 'label' => 'Monto', 'contentOptions' => [ 'style' => 'width: 1px; text-align: right' ] ],
				[ 'attribute' => 'caja_id', 'label' => 'Caja', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'trib_nom', 'label' => 'Trib. Descr.', 'contentOptions' => [ 'style' => 'width: 1px;' ] ],
                [ 'attribute' => 'obj_id', 'label' => 'Objeto', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'obj_nom', 'label' => 'Nombre', 'contentOptions' => [ 'style' => 'width: 200px; text-align: left' ] ],
				[ 'attribute' => 'subcta', 'label' => 'Subcta', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'anio', 'label' => 'Año', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'cuota', 'label' => 'Cuota', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ]],
				[ 'attribute' => 'num', 'label' => 'Num.', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ]],
				[ 'attribute' => 'mdps', 'label' => 'MDP', 'contentOptions' => [ 'style' => 'width: 1px; text-align: left' ]],
				[ 'attribute' => 'est', 'label' => 'Est.', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],

			],

			'urlOpciones' => '//caja/listadocajaticket/index',

		];
	}

	/**
	 * Arreglo de campos que se mostrarán para buscar
	 */
	public function campos(){

		$arrayCaja			= utb::getAux( 'caja', 'caja_id', 'nombre', 0, "est='A'" );
		$arrayTributo		= utb::getAux( 'trib', 'trib_id' );
		$arrayTipoObjeto	= utb::getAux( 'objeto_tipo', 'cod', 'nombre', 0, "est='A'" );

		$arrayEstado		= [
			'A' => 'Activo',
			'B' => 'Anulado',
		];

		$arrayTesoreria 	= utb::getAux( 'caja_tesoreria', 'teso_id', 'nombre', 0 );

		return [

			[ 'tipo' => 'rangoNumero', 'label' => 'Ticket', 'desde' => 'ticket_desde', 'hasta' => 'ticket_hasta' ],
			[ 'tipo' => 'rangoNumero', 'label' => 'Operaciones', 'desde' => 'operacion_desde', 'hasta' => 'operacion_hasta' ],
			[ 'tipo' => 'rangoFecha', 'label' => 'Fecha', 'desde' => 'fecha_desde', 'hasta' => 'fecha_hasta' ],
			[ 'tipo' => 'lista', 'label' => 'Caja', 'atributo' => 'caja', 'elementos' => $arrayCaja ],
			[ 'tipo' => 'lista', 'label' => 'Tributo', 'atributo' => 'tributo', 'elementos' => $arrayTributo ],
			[
				'tipo' => 'rangoYLista', 'label' => 'Objeto', 'desde' => 'objeto_desde', 'hasta' => 'objeto_hasta',
				'atributoLista' => 'objeto_tipo', 'elementos' => $arrayTipoObjeto, 'listaLabel' => 'Objeto', 'listaColumnas' => 2,
			],
			[ 'tipo' => 'texto', 'label' => 'Contribuyente', 'atributo' => 'contribuyente', 'columnas' => 8, 'caracteres' => 50 ],
			[ 'tipo' => 'rangoNumero', 'label' => 'Monto', 'desde' => 'monto_desde', 'hasta' => 'monto_hasta' ],
			[ 'tipo' => 'listachica', 'label' => 'Estado', 'atributo' => 'estado', 'elementos' => $arrayEstado ],
			[ 'tipo' => 'listachica', 'label' => 'Tesorería', 'atributo' => 'tesoreria', 'elementos' => $arrayTesoreria ],

		];
	}

}
?>
