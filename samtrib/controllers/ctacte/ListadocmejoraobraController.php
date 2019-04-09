<?php

namespace app\controllers\ctacte;

use app\controllers\ListadoController;
use app\utils\db\utb;
use app\models\ctacte\ListadoCMejoraObra;
use yii\helpers\Html;

class ListadocmejoraobraController extends ListadoController{

	/**
	 * Ingresar el modelo a utilizar en la búsqueda.
	 */
	public function modelo(){
		return new ListadoCMejoraObra();
	}

	public function datosOpciones($model){

		return [
			'breadcrumbs' => [
				['label' => 'Mejoras', 'url' => [ '//ctacte/cmejora/index' ] ],
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
				['label' => 'Mejoras', 'url' => [ '//ctacte/cmejora/index' ] ],
				[ 'label' => 'Listado', 'url' => [ '//ctacte/listadocmejoraobra/index' ] ],
				[ 'label' => 'Resultado' ],
			],

			//TODO colocar las columnas que se van a listar (sin las de opciones)
			'columnas' => [
				[ 'attribute' => 'obra_id', 'label' => 'Nº Obra', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'nombre', 'label' => 'Nombre', 'contentOptions' => [ 'style' => 'width: 200px; text-align: left' ] ],
				[ 'attribute' => 'tobra_nom', 'label' => 'Tipo', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'totalfrente', 'label' => 'Frente+', 'contentOptions' => [ 'style' => 'width: 1px; text-align: right' ] ],
				[ 'attribute' => 'totalsupafec', 'label' => 'Sup.Afec.', 'contentOptions' => [ 'style' => 'width: 1px; text-align: right' ]],
				[ 'attribute' => 'valortotal', 'label' => 'Total', 'contentOptions' => [ 'style' => 'width: 1px; text-align: right' ]],
				[ 'attribute' => 'valormetro', 'label' => 'Metros', 'contentOptions' => [ 'style' => 'width: 1px; text-align: right' ]],
				[ 'attribute' => 'fijo', 'label' => 'Fijo', 'contentOptions' => [ 'style' => 'width: 1px; text-align: right' ]],
				[ 'attribute' => 'bonifobra', 'label' => 'Bonif.', 'contentOptions' => [ 'style' => 'width: 1px; text-align: right' ]],
				[ 'attribute' => 'est_nom', 'label' => 'Estado', 'contentOptions' => [ 'style' => 'width: 1px; text-align: left' ] ],
				[
	                'class' => 'yii\grid\ActionColumn',
	                'contentOptions'=>['style'=>'width:40px;text-align: center'],
	                'template' => '{view}',
	                'buttons'=>[

	                    'view' => function($url, $model, $key)
	                                {
	                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['ctacte/mejoraobra/index', 'id' => $model['obra_id']], ["data-pjax" => "0" ]);
	                                },

	                ],
	            ],
			],

			'urlOpciones' => '//ctacte/listadocmejoraobra/index',
			
			'nuevo' => '//ctacte/mejoraobra/index&consulta=0',

		];
	}

	/**
	 * Arreglo de campos que se mostrarán para buscar
	 */
	public function campos(){

		$arrayEstado 			= utb::getAux( 'mej_test');
		$arrayTipo 				= utb::getAux( 'mej_tobra' );
		$arrayItemBasico 		= utb::getAux( 'item','item_id','nombre',0,'trib_id=3' );
		$arrayItemSellado 		= utb::getAux( 'item','item_id','nombre',0,'trib_id=3' );
		

		return [
			['tipo' => 'rangoNumero', 'label' => 'Nº Obra', 'desde' => 'numero_desde', 'hasta' => 'numero_hasta' ],
			['tipo' => 'texto', 'label' => 'Nombre', 'atributo' => 'nombre', 'columnas' => 6, 'caracteres' => 250 ],
			['tipo' => 'listachica', 'label' => 'Estado', 'atributo' => 'est', 'elementos' => $arrayEstado ],
			['tipo' => 'listachica', 'label' => 'Tipo', 'atributo' => 'tobra', 'elementos' => $arrayTipo ],
			['tipo' => 'listachica', 'label' => 'Item Básico', 'atributo' => 'item_basico', 'elementos' => $arrayItemBasico ],
			['tipo' => 'listachica', 'label' => 'Item Sellado', 'atributo' => 'item_sellado', 'elementos' => $arrayItemSellado ],
			['tipo' => 'rango', 'label' => 'Valor Metro', 'desde' => 'metros_desde', 'hasta' => 'metros_hasta' ],
			['tipo' => 'rango', 'label' => 'Total Frente', 'desde' => 'frente_desde', 'hasta' => 'frente_hasta' ],
			['tipo' => 'rango', 'label' => 'Valor Total', 'desde' => 'total_desde', 'hasta' => 'total_hasta' ],
			['tipo' => 'rango', 'label' => 'Sellado', 'desde' => 'sellado_desde', 'hasta' => 'sellado_hasta' ],
			['tipo' => 'rangoFecha', 'label' => 'Fecha Inicio', 'desde' => 'fecha_inicio_desde', 'hasta' => 'fecha_inicio_hasta' ],
			['tipo' => 'rangoFecha', 'label' => 'Fecha Fin', 'desde' => 'fecha_fin_desde', 'hasta' => 'fecha_fin_hasta' ],
		];
	}

}
?>
