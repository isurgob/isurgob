<?php

namespace app\controllers\ctacte;

use Yii;

use app\controllers\ListadoController;
use app\utils\db\utb;
use app\models\ctacte\TribaccListado;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class ListadotribaccController extends ListadoController{

	public $tipoListado;
	
	public function beforeAction($action){

		$this->tipoListado = Yii::$app->request->get('tipo','asignacion');

		return true;
	}
	
	/**
	 * Ingresar el modelo a utilizar en la búsqueda.
	 */
	public function modelo(){
		return new TribaccListado( $this->tipoListado );
	}

	public function datosOpciones($model){

		if ( $this->tipoListado == 'asignacion' )
			$breadcrumbs = [
								['label' => 'Tributos' ],
								'Asignaciones',
								'Opciones'
							];
		elseif ( $this->tipoListado == 'excepcion' )					
			$breadcrumbs = [
								['label' => 'Tributos' ],
								'Excepciones',
								'Opciones'
							];
		elseif ( $this->tipoListado == 'inscripcion' )					
			$breadcrumbs = [
								['label' => 'Tributos' ],
								'Inscripción a Tributos',
								'Opciones'
							];

		elseif ( $this->tipoListado == 'condona' )					
			$breadcrumbs = [
								['label' => 'Tributos' ],
								'Condonación',
								'Opciones'
							];						
		
		return [
			'breadcrumbs' => $breadcrumbs
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
		 *	exportar		=> Arreglo con la descripción de los datos a exportar.
		 *	imprimir		=> URL (string) a dónde se envía para imprimir.
		 */

		if ( $this->tipoListado == 'asignacion' ){
			$columnas = [
							['attribute'=>'trib_nom','label' => 'Tributo', 'contentOptions'=>['style'=>'width:80px']],
							['attribute'=>'obj_id','label' => 'Objeto', 'contentOptions'=>['style'=>'width:20px']],
							['attribute'=>'obj_nom','label' => 'Nombre', 'contentOptions'=>['style'=>'width:150px']],
							['attribute'=>'subcta','label' => 'Cta', 'contentOptions'=>['style'=>'width:10px;']],
							['attribute'=>'item_nom','label' => 'Ítem', 'contentOptions'=>['style'=>'width:130px']],
							['attribute'=>'perdesdeguion','label' => 'Per. desde', 'contentOptions'=>['style'=>'width:10px;']],
							['attribute'=>'perhastaguion','label' => 'Per. hasta', 'contentOptions'=>['style'=>'width:10px']],
							['attribute'=>'expe','label' => 'Expediente', 'contentOptions'=>['style'=>'width:50px']],
							[
								'class' => 'yii\grid\ActionColumn',
								'contentOptions'=>['style'=>'width:40px;text-align: center'],
								'template' => '{view}{update}{delete}',
								'buttons'=>[

									'view' => function($url, $model, $key)
												{
													return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', 
															[
																'ctacte/tribacc/asig', 
																'id' => 0,
																'listar' => false,
																'obj_id' => $model['obj_id'],
																'subcta' => $model['subcta'],
																'orden' => $model['orden'],
																'trib_id' => $model['trib_id'],
																'item_id' => $model['item_id'],
																'perdesde' => $model['perdesde'],
																'c' => 1
															], 
															["data-pjax" => "0" ]);
												},
									'update' => function($url, $model, $key)
												{
													return Html::a('<span class="glyphicon glyphicon-pencil"></span>', 
															[
																'ctacte/tribacc/asig', 
																'id' => 0,
																'listar' => false,
																'obj_id' => $model['obj_id'],
																'subcta' => $model['subcta'],
																'orden' => $model['orden'],
																'trib_id' => $model['trib_id'],
																'item_id' => $model['item_id'],
																'perdesde' => $model['perdesde'],
																'c' => 3
															], 
															["data-pjax" => "0" ]);
												},
									'delete' => function($url, $model, $key)
												{
													return Html::a('<span class="glyphicon glyphicon-trash"></span>', 
															[
																'ctacte/tribacc/asig', 
																'id' => 0,
																'listar' => false,
																'obj_id' => $model['obj_id'],
																'subcta' => $model['subcta'],
																'orden' => $model['orden'],
																'trib_id' => $model['trib_id'],
																'item_id' => $model['item_id'],
																'perdesde' => $model['perdesde'],
																'c' => 2
															], 
															["data-pjax" => "0" ]);
												},			

								],
							],
						];
			$exportar = [ 'Tributo', 'Objeto', 'Nombre', 'Cta', 'Ítem', 'Per. desde', 'Per. hasta', 'Expediente' ];			
			$breadcrumbs = 	[
							[ 'label' => 'Tributos' ],
							[ 'label' => 'Asignaciones', 'url' => [ '//ctacte/listadotribacc/index', 'tipo' => $this->tipoListado ] ],
							[ 'label' => 'Resultado' ],
						];		
			$nuevo = '//ctacte/tribacc/asig&listar=0&c=0';	
		}elseif ( $this->tipoListado == 'excepcion' ){
			$columnas = [
							['attribute'=>'trib_nom','label' => 'Tributo', 'contentOptions'=>['style'=>'width:80px']],
							['attribute'=>'obj_id','label' => 'Objeto', 'contentOptions'=>['style'=>'width:20px']],
							['attribute'=>'obj_nom','label' => 'Nombre', 'contentOptions'=>['style'=>'width:150px']],
							['attribute'=>'subcta','label' => 'Cta', 'contentOptions'=>['style'=>'width:10px;']],
							['attribute'=>'tipo_nom','label' => 'Tipo', 'contentOptions'=>['style'=>'width:130px']],
							['attribute'=>'anio','label' => 'Año', 'contentOptions'=>['style'=>'width:10px;']],
							['attribute'=>'cuota','label' => 'Cuota', 'contentOptions'=>['style'=>'width:10px']],
							['attribute'=>'expe','label' => 'Expediente', 'contentOptions'=>['style'=>'width:50px']],
							[
								'class' => 'yii\grid\ActionColumn',
								'contentOptions'=>['style'=>'width:40px;text-align: center'],
								'template' => '{view}{update}{delete}',
								'buttons'=>[

									'view' => function($url, $model, $key)
												{
													return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', 
															[
																'ctacte/tribacc/excep', 
																'id' => 0,
																'listar' => false,
																'excep_id' => $model['excep_id'],
																'c' => 1
															], 
															["data-pjax" => "0" ]);
												},
									'update' => function($url, $model, $key)
												{
													return Html::a('<span class="glyphicon glyphicon-pencil"></span>', 
															[
																'ctacte/tribacc/excep', 
																'id' => 0,
																'listar' => false,
																'excep_id' => $model['excep_id'],
																'c' => 3
															], 
															["data-pjax" => "0" ]);
												},
									'delete' => function($url, $model, $key)
												{
													return Html::a('<span class="glyphicon glyphicon-trash"></span>', 
															[
																'ctacte/tribacc/excep', 
																'id' => 0,
																'listar' => false,
																'excep_id' => $model['excep_id'],
																'c' => 2
															], 
															["data-pjax" => "0" ]);
												},			

								],
							],
						];
			$exportar = [ 'Tributo', 'Objeto', 'Nombre', 'Cta', 'Tipo', 'Año', 'Cuota', 'Expediente' ];			
			$breadcrumbs = 	[
							[ 'label' => 'Tributos' ],
							[ 'label' => 'Excepciones', 'url' => [ '//ctacte/listadotribacc/index', 'tipo' => $this->tipoListado ] ],
							[ 'label' => 'Resultado' ],
						];			
			$nuevo = '//ctacte/tribacc/excep&listar=0&c=0';	
		}elseif ( $this->tipoListado == 'inscripcion' ){
			$columnas = [
							['attribute'=>'trib_nom','label' => 'Tributo', 'contentOptions'=>['style'=>'width:80px']],
							['attribute'=>'obj_id','label' => 'Objeto', 'contentOptions'=>['style'=>'width:20px']],
							['attribute'=>'obj_nom','label' => 'Nombre', 'contentOptions'=>['style'=>'width:150px']],
							['attribute'=>'subcta','label' => 'Cta', 'contentOptions'=>['style'=>'width:10px;']],
							['attribute'=>'cat_nom','label' => 'Categoría', 'contentOptions'=>['style'=>'width:130px']],
							['attribute'=>'perdesdeguion','label' => 'Per. desde', 'contentOptions'=>['style'=>'width:10px;']],
							['attribute'=>'perhastaguion','label' => 'Per. hasta', 'contentOptions'=>['style'=>'width:10px']],
							['attribute'=>'expe','label' => 'Expediente', 'contentOptions'=>['style'=>'width:50px']],
							[
								'class' => 'yii\grid\ActionColumn',
								'contentOptions'=>['style'=>'width:40px;text-align: center'],
								'template' => '{view}{update}{delete}',
								'buttons'=>[

									'view' => function($url, $model, $key)
												{
													return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', 
															[
																'ctacte/tribacc/inscrip', 
																'id' => 0,
																'listar' => false,
																'obj_id' => $model['obj_id'],
																'trib_id' => $model['trib_id'],
																'perdesde' => $model['perdesde'],
																'c' => 1
															], 
															["data-pjax" => "0" ]);
												},
									'update' => function($url, $model, $key)
												{
													return Html::a('<span class="glyphicon glyphicon-pencil"></span>', 
															[
																'ctacte/tribacc/inscrip', 
																'id' => 0,
																'listar' => false,
																'obj_id' => $model['obj_id'],
																'trib_id' => $model['trib_id'],
																'perdesde' => $model['perdesde'],
																'c' => 3
															], 
															["data-pjax" => "0" ]);
												},
									'delete' => function($url, $model, $key)
												{
													return Html::a('<span class="glyphicon glyphicon-trash"></span>', 
															[
																'ctacte/tribacc/inscrip', 
																'id' => 0,
																'listar' => false,
																'obj_id' => $model['obj_id'],
																'trib_id' => $model['trib_id'],
																'perdesde' => $model['perdesde'],
																'c' => 2
															], 
															["data-pjax" => "0" ]);
												},			

								],
							],
						];
			$exportar = [ 'Tributo', 'Objeto', 'Nombre', 'Cta', 'Categoría', 'Per. desde', 'Per. hasta', 'Expediente' ];				
			$breadcrumbs = 	[
							[ 'label' => 'Tributos' ],
							[ 'label' => 'Inscripción a Tributo', 'url' => [ '//ctacte/listadotribacc/index', 'tipo' => $this->tipoListado ] ],
							[ 'label' => 'Resultado' ],
						];			
			$nuevo = '//ctacte/tribacc/inscrip&listar=0&c=0';	
		}elseif ( $this->tipoListado == 'condona' ){
			$columnas = [
							['attribute'=>'trib_nom','label' => 'Tributo', 'contentOptions'=>['style'=>'width:80px']],
							['attribute'=>'obj_id','label' => 'Objeto', 'contentOptions'=>['style'=>'width:20px']],
							['attribute'=>'obj_nom','label' => 'Nombre', 'contentOptions'=>['style'=>'width:150px']],
							['attribute'=>'subcta','label' => 'Cta', 'contentOptions'=>['style'=>'width:10px;']],
							['attribute'=>'perdesdeguion','label' => 'Per. desde', 'contentOptions'=>['style'=>'width:10px;']],
							['attribute'=>'perhastaguion','label' => 'Per. hasta', 'contentOptions'=>['style'=>'width:10px']],
							['attribute'=>'expe','label' => 'Expediente', 'contentOptions'=>['style'=>'width:50px']],
							[
								'class' => 'yii\grid\ActionColumn',
								'contentOptions'=>['style'=>'width:40px;text-align: center'],
								'template' => '{view}{delete}',
								'buttons'=>[

									'view' => function($url, $model, $key)
												{
													return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', 
															[
																'ctacte/tribacc/condona', 
																'id' => 0,
																'listar' => false,
																'obj_id' => $model['obj_id'],
																'trib_id' => $model['trib_id'],
																'perdesde' => $model['perdesde'],
																'c' => 1
															], 
															["data-pjax" => "0" ]);
												},
									'delete' => function($url, $model, $key)
												{
													return Html::a('<span class="glyphicon glyphicon-trash"></span>', 
															[
																'ctacte/tribacc/inscrip', 
																'id' => 0,
																'listar' => false,
																'obj_id' => $model['obj_id'],
																'trib_id' => $model['trib_id'],
																'perdesde' => $model['perdesde'],
																'c' => 2
															], 
															["data-pjax" => "0" ]);
												},			

								],
							],
						];
			$exportar = [ 'Tributo', 'Objeto', 'Nombre', 'Cta', 'Per. desde', 'Per. hasta', 'Expediente' ];			
			$breadcrumbs = 	[
							[ 'label' => 'Tributos' ],
							[ 'label' => 'Condonación', 'url' => [ '//ctacte/listadotribacc/index', 'tipo' => $this->tipoListado ] ],
							[ 'label' => 'Resultado' ],
						];			
			$nuevo = '//ctacte/tribacc/condona&listar=0&c=0';	
		}				
		
		return [
			'breadcrumbs' => $breadcrumbs,

			//TODO colocar las columnas que se van a listar (sin las de opciones)
			'columnas' => $columnas,

			'urlOpciones' => ['//ctacte/listadotribacc/index', 'tipo' => $this->tipoListado],

			'nuevo' => $nuevo,
			
			'imprimir' => ['//ctacte/listadotribacc/imprimir'],
			
			'exportar' => $exportar,

		];
	}

	/**
	 * Arreglo de campos que se mostrarán para buscar
	 */
	public function campos(){

        $condTrib = '';

		switch($this->tipoListado){

			case 'asignacion' : $condTrib = 'trib_id In (Select trib_id From item Where tipo In (2, 3))'; break;
			case 'excep' :
			case 'condona' : $condTrib = "est = 'A'"; break;
			case 'inscrip' : $condTrib = "est = 'A' And inscrip_req = 1"; break;
		}
		
		$arrayTipoObjeto	= utb::getAux( 'objeto_tipo', 'cod', 'nombre', 0, "est='A'" );
        $arrayTributo	= utb::getAux('trib', 'trib_id', 'nombre', 0, $condTrib);
		
		if ( $this->tipoListado == 'asignacion' ){
			$arrayItem	= utb::getAux('item', 'item_id', 'nombre', 0, 'tipo In (2, 3) And trib_id In (Select trib_id From item Where tipo In (2, 3))');
			$descItem = 'Item';
			$atrItem = 'item';
			
			$labelFecha = 'Modificación';
			$desdeFecha = 'fchmodif_desde';
			$hastaFecha = 'fchmodif_hasta';
			
			$nuevo = '//ctacte/tribacc/asig&listar=0&c=0';	
		}elseif ( $this->tipoListado == 'excepcion' ){
			$arrayItem	= utb::getAux('ctacte_tcta', 'cod', 'nombre', 0, 'cod In (2, 3, 4)', 'cod', false, "Union Select 5 As cod, 'Liquidación' As nombre");
			$descItem = 'Tipo';
			$atrItem = 'tipo_cuenta';
			
			$labelFecha = 'Modificación';
			$desdeFecha = 'fchmodif_desde';
			$hastaFecha = 'fchmodif_hasta';
			
			$nuevo = '//ctacte/tribacc/excep&listar=0&c=0';	
		}elseif ( $this->tipoListado == 'inscripcion' ){
			$arrayItem	= utb::getAux('objeto_trib_cat', 'trib_id');
			$descItem = 'Categoría';
			$atrItem = 'categoria';
			
			$labelFecha = 'Alta';
			$desdeFecha = 'fchalta_desde';
			$hastaFecha = 'fchalta_hasta';
			
			$nuevo = '//ctacte/tribacc/inscrip&listar=0&c=0';
		}elseif ( $this->tipoListado == 'condona' ){
			$labelFecha = 'Modificación';
			$desdeFecha = 'fchmodif_desde';
			$hastaFecha = 'fchmodif_hasta';
			
			$nuevo = '//ctacte/tribacc/condona&listar=0&c=0';	
		}	

		return [
			
			['tipo' => 'lista', 'label' => 'Tributo', 'atributo' => 'tributo', 'elementos' => $arrayTributo ],
			( $this->tipoListado !== 'condona' ) ? 
				['tipo' => 'lista', 'label' => $descItem, 'atributo' => $atrItem, 'elementos' => $arrayItem ]
			: 
				null
			,
			['tipo' => 'rangoYLista', 'label' => 'Objeto', 'desde' => 'objeto_desde', 'hasta' => 'objeto_hasta',
				'atributoLista' => 'tipo_objeto', 'elementos' => $arrayTipoObjeto, 'listaLabel' => 'Objeto', 'listaColumnas' => 2,],
			['tipo' => 'periodo', 'label' => 'Período', 'adesde' => 'anio_desde', 'cdesde' => 'mes_desde', 'ahasta' => 'anio_hasta', 'chasta' => 'mes_hasta', 'columnas' => 2, 'caracteres' => 8 ],
			['tipo' => 'rangoFecha', 'label' => $labelFecha, 'desde' => $desdeFecha, 'hasta' => $hastaFecha ],
			['tipo' => 'texto', 'label' => 'Expediente', 'atributo' => 'expediente', 'columnas' => 6, 'caracteres' => 50 ]

		];
	}
	
	public function botones(){

		if ( $this->tipoListado == 'asignacion' ){
			$nuevo = '//ctacte/tribacc/asig&listar=0&c=0';	
		}elseif ( $this->tipoListado == 'excepcion' ){
			$nuevo = '//ctacte/tribacc/excep&listar=0&c=0';	
		}elseif ( $this->tipoListado == 'inscripcion' ){
			$nuevo = '//ctacte/tribacc/inscrip&listar=0&c=0';
		}elseif ( $this->tipoListado == 'condona' ){
			$nuevo = '//ctacte/tribacc/condona&listar=0&c=0';	
		}
		
		return 	[
					[ 'id' => 'btNuevo', 'href' => $nuevo, 'label' => 'Nuevo', 'class' => 'btn btn-success' ]
				];
	}

}

?>
