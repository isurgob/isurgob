<?php

namespace app\controllers\ctacte;

use app\controllers\ListadoController;
use app\utils\db\utb;
use app\models\ctacte\ListadoCMejoraPlan;
use yii\helpers\Html;

class ListadocmejoraplanController extends ListadoController{

	/**
	 * Ingresar el modelo a utilizar en la búsqueda.
	 */
	public function modelo(){
		return new ListadoCMejoraPlan();
	}

	public function datosOpciones($model){

		return [
			'breadcrumbs' => [
				['label' => 'Plan de Mejoras', 'url' => [ '//ctacte/mejoraplan/index' ] ],
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
				['label' => 'Plan de Mejoras', 'url' => [ '//ctacte/mejoraplan/index' ] ],
				[ 'label' => 'Listado', 'url' => [ '//ctacte/listadocmejoraplan/index' ] ],
				[ 'label' => 'Resultado' ],
			],

			//TODO colocar las columnas que se van a listar (sin las de opciones)
			'columnas' => [
				[ 'attribute' => 'plan_id', 'label' => 'Nº Plan', 'contentOptions' => [ 'style' => 'text-align: center' ] ],
				[ 'attribute' => 'obj_id', 'label' => 'Objeto', 'contentOptions' => [ 'style' => 'text-align: left' ] ],
				[ 'attribute' => 'obra_id', 'label' => 'Obra', 'contentOptions' => [ 'style' => 'text-align: center' ] ],
				[ 'attribute' => 'nc_guiones', 'label' => 'NC', 'contentOptions' => [ 'style' => 'text-align: center' ] ],
				[ 'attribute' => 'obj_nom', 'label' => 'Nombre Objeto', 'contentOptions' => [ 'style' => 'text-align: left' ]],
				[ 'attribute' => 'dompar', 'label' => 'Domicilio', 'contentOptions' => [ 'style' => 'text-align: left' ]],
				[ 'attribute' => 'monto', 'label' => 'Monto', 'contentOptions' => [ 'style' => 'text-align: right' ]],
				[ 'attribute' => 'est_nom', 'label' => 'Estado', 'contentOptions' => [ 'style' => 'text-align: left' ] ],
				[
	                'class' => 'yii\grid\ActionColumn',
	                'contentOptions'=>['style'=>'width:40px;text-align: center'],
	                'template' => '{view}',
	                'buttons'=>[

	                    'view' => function($url, $model, $key)
	                                {
	                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['ctacte/mejoraplan/index', 'id' => $model['plan_id']], ["data-pjax" => "0" ]);
	                                },

	                ],
	            ],
			],

			'urlOpciones' => '//ctacte/listadocmejoraplan/index',
			
			'nuevo' => '//ctacte/mejoraplan/index&consulta=0',
			
			'imprimir' => ['//ctacte/listadocmejoraplan/imprimir'],
			
			'exportar' => [ 'Nº Plan', 'Objeto', 'Obra', 'NC', 'Nombre Objeto', 'Domicilio', 'Monto', 'Estado' ],

		];
	}

	/**
	 * Arreglo de campos que se mostrarán para buscar
	 */
	public function campos(){

		$arrayEstado 	= utb::getAux( 'mej_test');
		$arrayTipo 		= utb::getAux( 'mej_tobra' );
		$arrayObra 		= utb::getAux( 'mej_obra', 'obra_id' );
		$arrayCuadra	= utb::getAux('v_mej_cuadra', 'cuadra_id', "calle_nom || ' - ' || ncm");
		

		return [
			['tipo' => 'rangoNumero', 'label' => 'Nº Plan', 'desde' => 'numero_desde', 'hasta' => 'numero_hasta' ],
			['tipo' => 'rangoObjeto', 'tipoObjeto' => '1', 'label' => 'Objeto', 'desde' => 'objeto_id_desde', 'hasta' => 'objeto_id_hasta'],
			['tipo' => 'texto', 'label' => 'Nombre', 'atributo' => 'nombre', 'columnas' => 6, 'caracteres' => 250 ],
			['tipo' => 'listachica', 'label' => 'Tipo Obra', 'atributo' => 'tobra', 'elementos' => $arrayTipo ],
			['tipo' => 'listachica', 'label' => 'Obra', 'atributo' => 'obra', 'elementos' => $arrayObra ],
			['tipo' => 'listachica', 'label' => 'Cuadra', 'atributo' => 'cuadra', 'elementos' => $arrayCuadra ],
			['tipo' => 'listachica', 'label' => 'Estado', 'atributo' => 'est', 'elementos' => $arrayEstado ],
			['tipo' => 'rango', 'label' => 'Monto', 'desde' => 'monto_desde', 'hasta' => 'monto_hasta' ],
			['tipo' => 'rangoFecha', 'label' => 'Alta', 'desde' => 'fchalta_desde', 'hasta' => 'fchalta_hasta' ],
			['tipo' => 'rangoFecha', 'label' => 'Baja', 'desde' => 'fchbaja_desde', 'hasta' => 'fchbaja_hasta' ],
			['tipo' => 'rangoFecha', 'label' => 'Desafectación', 'desde' => 'fchdesaf_desde', 'hasta' => 'fchdesaf_hasta' ],
			['tipo' => 'rangoFecha', 'label' => 'Vencimiento', 'desde' => 'fchvenc_desde', 'hasta' => 'fchvenc_hasta' ]
		];
	}

}
?>
