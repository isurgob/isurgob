<?php

namespace app\controllers\ctacte;

use Yii;

use app\controllers\ListadoController;
use app\utils\db\utb;
use app\models\ctacte\CompListado;
use app\models\ctacte\Comp;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class ListadocompController extends ListadoController{

	/**
	 * Ingresar el modelo a utilizar en la búsqueda.
	 */
	public function modelo(){
		return new CompListado();
	}

	public function datosOpciones($model){

		return [
			'breadcrumbs' => [
				['label' => 'Compensación', 'url' => [ '//ctacte/comp/view' ] ],
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
				[ 'label' => 'Compensación', 'url' => [ '//ctacte/comp/view' ] ],
				[ 'label' => 'Listado de Compensaciones', 'url' => [ '//ctacte/listadocomp/index' ] ],
				[ 'label' => 'Resultado' ],
			],

			//TODO colocar las columnas que se van a listar (sin las de opciones)
			'columnas' => [
				[ 'attribute' => 'comp_id', 'label' => 'Cod.', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'tipo_nom', 'label' => 'Tipo', 'contentOptions' => [ 'style' => 'width: 100px; text-align: left' ] ],
				[ 'attribute' => 'trib_ori_nom', 'label' => 'Trib. Origen', 'contentOptions' => [ 'style' => 'width: 100px; text-align: left' ] ],
				[ 'attribute' => 'obj_ori', 'label' => 'Origen', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'trib_dest_nom', 'label' => 'Trib. Destino', 'contentOptions' => [ 'style' => 'width: 100px;' ] ],
                [ 'attribute' => 'obj_dest', 'label' => 'Destino', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'monto', 'label' => 'Monto', 'contentOptions' => [ 'style' => 'width: 1px; text-align: right' ] ],
				[ 'attribute' => 'monto_aplic', 'label' => 'Aplic.', 'contentOptions' => [ 'style' => 'width: 1px; text-align: right' ]],
				[ 'attribute' => 'saldo', 'label' => 'Saldo', 'contentOptions' => [ 'style' => 'width: 1px; text-align: right' ]],
				[ 'attribute' => 'est_nom', 'label' => 'Estado', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[
	                'class' => 'yii\grid\ActionColumn',
	                'contentOptions'=>['style'=>'width:40px;text-align: center'],
	                'template' => '{view}',
	                'buttons'=>[

	                    'view' => function($url, $model, $key)
	                                {
	                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['ctacte/comp/view', 'id' => $model['comp_id']], ["data-pjax" => "0" ]);
	                                },

	                ],
	            ],
			],

			'urlOpciones' => '//ctacte/listadocomp/index',

		];
	}

	/**
	 * Arreglo de campos que se mostrarán para buscar
	 */
	public function campos(){

		$arrayTipo 		  	= utb::getAux( 'comp_tipo' );
        $arrayTributo   	= utb::getAux( 'trib', 'trib_id', 'nombre', 0, "est='A' AND compensa = 1" );
		$arrayTipoObjeto	= utb::getAux( 'objeto_tipo', 'cod', 'nombre', 0, "est='A'" );
		$arrayEstado		= utb::getAux( 'comp_test' );

		return [

			[ 'tipo' => 'rangoNumero', 'label' => 'Código', 'desde' => 'numero_desde', 'hasta' => 'numero_hasta' ],
			[ 'tipo' => 'texto', 'label' => 'Expe.', 'atributo' => 'expediente', 'columnas' => 3, 'caracteres' => 12 ],
			[ 'tipo' => 'listachica', 'label' => 'Tipo', 'atributo' => 'tipo', 'elementos' => $arrayTipo ],
			[ 'tipo' => 'lista', 'label' => 'Tributo', 'atributo' => 'tributo', 'elementos' => $arrayTributo ],
			[
				'tipo' => 'rangoYLista', 'label' => 'Objeto', 'desde' => 'objeto_desde', 'hasta' => 'objeto_hasta',
				'atributoLista' => 'objeto_tipo', 'elementos' => $arrayTipoObjeto, 'listaLabel' => 'Objeto', 'listaColumnas' => 2,
			],
			[ 'tipo' => 'listachica', 'label' => 'Estado', 'atributo' => 'estado', 'elementos' => $arrayEstado ],
			[ 'tipo' => 'rangoNumero', 'label' => 'Monto', 'desde' => 'monto_desde', 'hasta' => 'monto_hasta' ],
			[ 'tipo' => 'rangoFecha', 'label' => 'Fecha Alta', 'desde' => 'fecha_alta_desde', 'hasta' => 'fecha_alta_hasta' ],
			[ 'tipo' => 'rangoFecha', 'label' => 'Fecha Aplica', 'desde' => 'fecha_aplica_desde', 'hasta' => 'fecha_aplica_hasta' ],
			[ 'tipo' => 'rangoFecha', 'label' => 'Fecha Baja', 'desde' => 'fecha_baja_desde', 'hasta' => 'fecha_baja_hasta' ],



		];
	}

}
?>
