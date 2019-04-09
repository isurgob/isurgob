<?php

namespace app\controllers\caja;

use Yii;
use app\controllers\ListadoController;
use app\utils\db\utb;
use app\models\caja\ChequeCarteraListado;
use app\models\caja\CajaTicket;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;


class ListadochequecarteraController extends ListadoController{

	/**
	 * Ingresar el modelo a utilizar en la búsqueda.
	 */
	
	public function modelo(){
		return new chequecarteraListado();
	}

	public function datosOpciones($model){

		return [
			'breadcrumbs' => [
				['label' => 'Cheque Cartera', 'url' => [ '//caja/cajaticket/chequecartera' ] ],
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
		 *	columnas		=> Arreglo con los datos que se visualizarón en la grilla y la forma en la que se vern.
		 *	urlOpciones		=> URL (string) a dónde retorna el botón Volver.
		 *	exportar		=> URL (string) a dónde se envía para exportar los resultados.
		 *	imprimir		=> URL (string) a dónde se envía para imprimir.
		 */

		return [
				
			'breadcrumbs' => [
				[ 'label' => 'Opciones', 'url' => [ '//caja/listadochequecartera/index' ] ],
				[ 'label' => 'Listado', 'url' => [ '//caja/listadochequecartera/index' ] ],
				[ 'label' => 'Resultado' ],
			],

			//TODO colocar las columnas que se van a listar (sin las de opciones)
			'columnas' => [
							['attribute'=>'cart_id','label' => 'Cod', 'contentOptions'=>['style'=>'text-align:center']],
							['attribute'=>'plan_id','label' => 'Conv.','contentOptions'=>['style'=>'text-align:center']],
							['attribute'=>'plan_id2','label' => 'Conv. 2','contentOptions'=>['style'=>'text-align:center']],
							['attribute'=>'nrocheque','label' => 'Cheque','contentOptions'=>['style'=>'text-align:center']],
							['attribute'=>'monto','label' => 'Monto','contentOptions'=>['style'=>'text-align:right']],
							['attribute'=>'bco_ent_nom','label' => 'Banco','contentOptions'=>['style'=>'text-align:center']],
							['attribute'=>'bco_cta','label' => 'Cuenta','contentOptions'=>['style'=>'text-align:center']],
							['attribute'=>'titular','label' => 'Titular','contentOptions'=>['style'=>'text-align:center']],	
							['attribute'=>'fchalta','label' => 'Alta', 'contentOptions'=>['style'=>'text-align:center'],'format' => [ 'date', 'php:d/m/Y'] ],
							['attribute'=>'fchcobro','label' => 'Cobro', 'contentOptions'=>['style'=>'text-align:center'],'format' => [ 'date', 'php:d/m/Y'] ],
							['attribute'=>'est','label' => 'Est.', 'contentOptions'=>['style'=>'text-align:center']],
							[
								'class' => 'yii\grid\ActionColumn',
								'contentOptions'=>['style'=>'width:40px;text-align: center'],
								'template' => '{view}',
								'buttons'=>[
								'view' => function($url, $model, $key)
	                                {
	                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['//caja/cajaticket/chequecartera', 'id' => $model['cart_id']], ["data-pjax" => "0" ]);
	                                },

	                ],
	            ],
			],

			'urlOpciones' => '//caja/listadochequecartera/index',   

			'imprimir' => ['//caja/listadochequecartera/imprimir','format' => 'A4-L'],

			'exportar' => ['Cod','Conv.','Conv. 2','Cheque','Monto','Banco','Cuenta','Titular','Alta','Cobro','Est.'],

		];
	}

	/**
	 * Arreglo de campos que se mostraran para buscar
	 */
	public function campos(){

       return [
			
			['label' => 'Estado', 'atributo' => 'est', 'elementos' => ['C' => 'Cartera', 'P' => 'Pagado','B' =>'Baja'],
				'tipo' => 'listachica'],
			['tipo' => 'texto', 'label' => 'Plan', 'atributo' => 'plan', 'columnas' => 3, 'caracteres' => 50 ],	
			['tipo' => 'texto', 'label' => 'Nro. Cheque', 'atributo' => 'cheque', 'columnas' => 3, 'caracteres' => 50 ],
			['tipo' => 'rangoFecha', 'label' => 'Fecha de Alta','desde' => 'fecha_desde_a','hasta'=> 'fecha_hasta_a'], 
			['tipo' => 'rangoFecha', 'label' => 'Fecha Cobro','desde' => 'fecha_desde_c','hasta'=> 'fecha_hasta_c'], 
			
			];
	}

	

}

?>
