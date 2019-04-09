<?php

namespace app\controllers\caja;

use Yii;
use app\controllers\ListadoController;
use app\utils\db\utb;
use app\models\caja\ReciboManualListado;
use app\models\caja\CajaTicket;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class ListadorecibomanualController extends ListadoController{

	public $tipoListado;
	
	public function beforeAction($action){

		$this->tipoListado = Yii::$app->request->get('tipoListado');
		
		return true;
	}


	/**
	 * Ingresar el modelo a utilizar en la búsqueda.
	 */
	
	public function modelo(){
		return new RecibomanualListado($this->tipoListado);
	}

		
	public function datosOpciones($model){

		return [
			'breadcrumbs' => [
				['label' => 'Recibo Manual', 'url' => ['//caja/cajaticket/viewrecibomanual']],
				'Listado',
				'Opciones'
			]
		];
	}

	/**
	 * Función que se utiliza para generar los datos que se enviarón a la vista de resultados.
	 */
	public function datosResultado($model, $resultados){

		//echo $this->tipoListado; exit();
		/**
		 * Los datos que deben/pueden ir son;
		 *
		 *	breadcrumbs 	=> Arreglo de breadcrumbs.
		 *	columnas		=> Arreglo con los datos que se visualizarán en la grilla y la forma en la que se verán.
		 *	urlOpciones		=> URL (string) a dónde retorna el botón Volver.
		 *	exportar		=> URL (string) a dónde se envía para exportar los resultados.
		 *	imprimir		=> URL (string) a dónde se envía para imprimir.
		 */
			
			if ( $this->tipoListado == 1)
			{	
			

				//TODO colocar las columnas que se van a listar (sin las de opciones)
				$columnas = [
							['attribute'=>'ctacte_id','header' => 'Nro.Ref', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
							['attribute'=>'est','header' => 'Est','contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
							['attribute'=>'recibo','header' => 'Recibo', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
							['attribute'=>'fecha','header' => 'Fecha Recibo','contentOptions'=>['style'=>'text-align:center'],'format' => [ 'date', 'php:d/m/Y'] ],
							['attribute'=>'acta','header' => 'Acta', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
							['attribute'=>'item_nom','header' =>'Ítem','contentOptions'=>['style'=>'text-align:center','width'=>'150px']],
							['attribute'=>'area_nom','header' =>'Área','contentOptions'=>['style'=>'text-align:center','width'=>'120px']],
							['attribute'=>'monto','header' =>'Monto','contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
							['attribute'=>'ticket','header' =>'Ticket','contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
							['attribute'=>'obj_id','header' =>'Objeto','contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
							[
								'class' => 'yii\grid\ActionColumn',
								'contentOptions'=>['style'=>'width:6px'],
								'template' =>'{viewrecibomanual}',
								'buttons'=>[			
								'viewrecibomanual' =>  function($url, $model, $key)
												{
													if($model['est'] == 'P') 
														return null;
													else
													{
														$url .= '&accion=1&reiniciar=1';
														if ($model['est'] == 'B')
															$url .= '&baja=1';
														return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['//caja/cajaticket/viewrecibomanual', 'id' => $model['ctacte_id']], ["data-pjax" => "0" ]);
													}
												},
								],
							]
						];

			   	$exportar =['Nro.Ref','Est','Recibo','Fecha Recibo','Acta','Ítem','Área', 'Monto','Ticket','Objeto'];
			} else {
					

				//TODO colocar las columnas que se van a listar (sin las de opciones)
				$columnas =[
							['attribute'=>'ctacte_id','header' => 'Nro.Ref', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
							['attribute'=>'est','header' => 'Est', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
							['attribute'=>'ticket','header' => 'Ticket', 'contentOptions'=>['style'=>'text-align:center','width'=>'95px']],
							['attribute'=>'obj_id','header' => 'Objeto', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
							['attribute'=>'cant','header' => 'Can', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
							['attribute'=>'total','header' => 'Total', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
							['attribute'=>'obs','header' => 'Obs', 'contentOptions'=>['style'=>'text-align:center']],
							[
								'class' => 'yii\grid\ActionColumn',
								'contentOptions'=>['style'=>'width:6px'],
								'template' =>'{viewrecibomanual}',
								'buttons'=>[			
									'viewrecibomanual' =>  function($url, $model, $key)
												{
													if($model['est'] == 'P') 
														return null;
													else
													{
														$url .= '&accion=1';
														if ($model['est'] == 'B')
															$url .= '&baja=1';
														return Html::a('<span class="glyphicon glyphicon-pencil"></span>',['//caja/cajaticket/viewrecibomanual', 'id' => $model['ctacte_id']], ["data-pjax" => "0" ]);
													}
												},
								]
							]
							
		            	];

			   			
				$exportar =['Nro.Ref','Est','Ticket','Objeto','Can','Total','Obs'];	
			}
			
			return [   'breadcrumbs' => [
					
									[ 'label' => 'Opciones', 'url' => [ '//caja/listadorecibomanual/index' ] ],
									[ 'label' => 'Listado', 'url' => [ '//caja/listadorecibomanual/index'] ],
									[ 'label' => 'Resultado' ],
								     ],
				   		'columnas' => $columnas,
				   		'urlOpciones' => '//caja/listadorecibomanual/index',
				   		'imprimir' =>['//caja/listadorecibomanual/imprimir'],
				   		'exportar' => $exportar,
					];
		
		
	}
	/**
	 * Arreglo de campos que se mostraran para buscar
	 */
	public function campos(){

       
		$arrayItem		= utb::getAux('item','item_id','nombre',0,"trib_id = 12");
		$arrayArea		= utb::getAux('sam.muni_oficina','ofi_id','nombre');
		$arrayEst		= utb::getAux('ctacte_test','cod','nombre',0,"cod ='P' or cod='D' or cod='B'");
		
		
		return [
				['label' => 'Tipo de Listado', 'atributo' => 'tipoListado', 'elementos' => ['1' => 'Recibo', '2' => 'Comprobante'],
				'tipo' => 'listachica', 'sinCheck' => 1],
				['tipo' => 'rangoNumero', 'label' => 'Nº Referencia', 'desde' => 'numero_desde_ref' , 'hasta' => 'numero_hasta_ref'],
				['tipo' => 'rangoNumero', 'label' => 'Nº Recibo', 'desde' => 'numero_desde_rec' , 'hasta' => 'numero_hasta_rec'],
				['tipo' => 'rangoFecha', 'label' => 'Fecha Recibo','desde' => 'fecha_desde_rec','hasta'=> 'fecha_hasta_rec'], 
			    ['tipo' => 'rangoFecha', 'label' => 'Fecha Ingreso','desde' => 'fecha_desde_ing','hasta'=> 'fecha_hasta_ing'], 
				['tipo' => 'rangoNumero', 'label' => 'Acta', 'desde' => 'numero_desde_acta' , 'hasta' => 'numero_hasta_acta'],
				['tipo' => 'lista', 'label' => 'Ítem', 'atributo' => 'item', 'elementos' => $arrayItem ],
				['tipo' => 'lista', 'label' => 'Area', 'atributo' => 'area', 'elementos' => $arrayArea ],
				['tipo' => 'listachica', 'label' => 'Estado', 'atributo' => 'estado', 'elementos' => $arrayEst ],
				['tipo' => 'texto', 'label' => 'Ticket', 'atributo' => 'ticket', 'columnas' => 3, 'caracteres' => 50 ],
			 ];
	}

	

}

?>
