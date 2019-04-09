<?php

namespace app\controllers\caja;


use Yii;
use app\controllers\ListadoController;
use app\utils\db\utb;
use app\models\caja\PagosAntListado;
use app\models\caja\CajaTicket;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;


class ListadopagosantController extends ListadoController{

	/**
	 * Ingresar el modelo a utilizar en la búsqueda.
	 */
	public function modelo(){
		return new PagosantListado();
	}

	public function datosOpciones($model){

		return [
			'breadcrumbs' => [
				['label' => 'Registro Pago Anterior', 'url' => ['//caja/cajaticket/pagoant']],
				'Listado',
				'Opciones'
			]
		];
	}

	/**
	 * Función que se utiliza para generar los datos que se enviarón a la vista de resultados.
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
				[ 'label' => 'Opciones', 'url' => [ '//caja/listadopagosant/index' ] ],
				[ 'label' => 'Listado', 'url' => [ '//caja/listadopagosant/index' ] ],
				[ 'label' => 'Resultado' ],
			],

			//TODO colocar las columnas que se van a listar (sin las de opciones)
			'columnas' => [
							['attribute'=>'pago_id','label' => 'Cod', 'contentOptions'=>['style'=>'text-align:center']],
							['attribute'=>'obj_id','label' => 'Obejto','contentOptions'=>['style'=>'text-align:center']],
							['attribute'=>'obj_nom','label' => 'Nombre','contentOptions'=>['style'=>'text-align:center']],
							['attribute'=>'trib_nom','label' => 'Tributo','contentOptions'=>['style'=>'text-align:center']],
							['attribute'=>'anio','label' => 'Año','contentOptions'=>['style'=>'text-align:right']],
							['attribute'=>'cuota','label' => 'Cuota','contentOptions'=>['style'=>'text-align:center']],
							['attribute'=>'fchpago','label' => 'Fch Pago', 'contentOptions'=>['style'=>'text-align:center'],'format' => [ 'date', 'php:d/m/Y'] ],
							['attribute'=>'comprob','label' => 'Comprob.', 'contentOptions'=>['style'=>'text-align:center']],
							['attribute'=>'modif','label' => 'Modif.', 'contentOptions'=>['style'=>'text-align:center']],
							[
								'class' => 'yii\grid\ActionColumn',
								'contentOptions'=>['style'=>'width:40px;text-align: center'],
								'template' => '{view}',
								'buttons'=>[

								'view' => function($url, $model, $key)
	                                {
	                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['//caja/cajaticket/pagoant', 'id' => $model['pago_id']], ["data-pjax" => "0" ]);
	                                },

	                ],
	            ],
			],

			'urlOpciones' => '//caja/listadopagosant/index',   

			'imprimir' => ['//caja/listadopagosant/imprimir', 'format' => 'A4-L'],

			'exportar' => ['Cod','Obejto','Nombre','Tributo','Año','Cuota','Fch Pago','Comprobante','Modificación'],

		];
	}
	/**
	 * Arreglo de campos que se mostraran para buscar
	 */
	public function campos(){

        $arrayTipoObjeto	= utb::getAux( 'objeto_tipo', 'cod', 'nombre', 0, "est='A'" );	
		$arrayTributo		= utb::getAux('trib','trib_id');
		
		
		return [
			[ 'tipo' => 'lista', 'label' => 'Tributo', 'atributo' => 'tributo', 'elementos' => $arrayTributo ],	
			['tipo' => 'rangoYLista', 'label' => 'Objeto', 'desde' => 'objeto_desde', 'hasta' => 'objeto_hasta',
			'atributoLista' => 'objeto_tipo', 'elementos' => $arrayTipoObjeto, 'listaLabel' => 'Objeto', 'listaColumnas' => 2,],
			['tipo' => 'rangoFecha', 'label' => 'Fecha de Pago','desde' => 'fecha_desde_p','hasta'=> 'fecha_hasta_p'], 
			['tipo' => 'rangoFecha', 'label' => 'Fecha Carga','desde' => 'fecha_desde_c','hasta'=> 'fecha_hasta_c'], 
			];
	}

	

}

?>
