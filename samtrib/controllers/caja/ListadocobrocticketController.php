<?php
namespace app\controllers\caja;

use Yii;

use app\controllers\ListadoController;
use app\utils\db\utb;
use app\models\caja\CobrocticketListado;
use app\models\caja\CajaTicket;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;

class ListadocobrocticketController extends ListadoController{

	/**
	 * Ingresar el modelo a utilizar en la búsqueda.
	 */
	public function modelo(){
		return new CobrocticketListado();
	}

	public function datosOpciones($model){

		return [
			'breadcrumbs' => [
				['label' => 'Opciones', 'url' => [ '//caja/listadocobrocticket/index' ] ],
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
				[ 'label' => 'Opciones', 'url' => [ '//caja/listadocobrocticket/index' ] ],
				[ 'label' => 'Listado', 'url' => [ '//caja/listadocobrocticket/index' ] ],
				[ 'label' => 'Resultado' ],
			],

			//TODO colocar las columnas que se van a listar (sin las de opciones)
			'columnas' => [
							['attribute'=>'ticket','label' => 'Ticket', 'contentOptions'=>['style'=>'text-align:center;color:blue'],
								'format' => 'raw',
								'value' => function ($model, $key, $index, $column) {
												return Html::a($model['ticket'], ['//caja/cajaticket/ticket', 'id' => $model['ticket'],'lista'=>1], 
																['target' => '_black']
															);
											}
							],
							['attribute'=>'opera','label' => 'Opera', 'contentOptions'=>['style'=>'text-align:center;color:blue'],
								'format' => 'raw',
								'value' => function ($model, $key, $index, $column) {
											return Html::a($model['opera'], ['//caja/cajaticket/opera', 'id' => $model['opera'],'lista'=>1], 
																['target' => '_black']
															);
											}
							],
							['attribute'=>'fecha','label' => 'Fecha', 'contentOptions'=>['style'=>'text-align:center'],'format' => [ 'date', 'php:d/m/Y'] ],
							['attribute'=>'hora','label' => 'Hora', 'contentOptions'=>['style'=>'width:15px;text-align:center']],
							['attribute'=>'monto','label' => 'Monto', 'contentOptions'=>['style'=>'text-align:right']],
							['attribute'=>'caja_id','label' => 'Caja', 'contentOptions'=>['style'=>'text-align:center']],
							['attribute'=>'trib_nom','label' => 'Trib. Descr.', 'contentOptions'=>['style'=>'text-align:left']],
							['attribute'=>'obj_id','label' => 'Obj.', 'contentOptions'=>['style'=>'text-align:left']],
							['attribute'=>'obj_nom','label' => 'Obj. Nom.', 'contentOptions'=>['style'=>'width:100px;text-align:left']],
							['attribute'=>'subcta','label' => 'Cta', 'contentOptions'=>['style'=>'text-align:center']],
							['attribute'=>'anio','label' => 'Año', 'contentOptions'=>['style'=>'text-align:center']],
		            		['attribute'=>'cuota','label' => 'Cuo', 'contentOptions'=>['style'=>'text-align:center']],
		            		['attribute'=>'num','label' => 'Num', 'contentOptions'=>['style'=>'text-align:center']],
							['attribute' => 'mdps', 'label' => 'MDP', 'contentOptions' => [ 'style' => 'width: 1px; text-align: left' ]],
		            		['attribute'=>'est','label' => 'Est', 'contentOptions'=>['style'=>'text-align:center']],
			],

			'urlOpciones' => '//caja/listadocobrocticket/index',   

			'imprimir' => ['//caja/listadocobrocticket/imprimir', 'format' => 'A4-L'],

			'exportar' => [ 'Ticket', 'Opera', 'Fecha', 'Hora', 'Monto', 'Caja', 'Trib. Descr.', 'Obj.', 'Obj. Nom.', 'Cta', 'Año', 'Cuo', 'Num', 'MDP', 'Est' ],

		];
	}
	/**
	 * Arreglo de campos que se mostraran para buscar
	 */
	public function campos(){

        $arrayTipoObjeto	= utb::getAux( 'objeto_tipo', 'cod', 'nombre', 0, "est='A'" );
		$arrayUsuario		= utb::getAux('sam.sis_usuario','usr_id','apenom',0,"est='A'");
		$arrayCaja			= utb::getAux('caja','caja_id','nombre',0,"est='A'");
		$arrayTributo		= utb::getAux('trib','trib_id');
		$arrayTesoreria		= utb::getAux('caja_tesoreria','teso_id','nombre',0);
		
		return [
				
			[ 'tipo' => 'rangoNumero', 'label' => 'Ticket', 'desde' => 'numero_desde' , 'hasta' => 'numero_hasta' ],
			[ 'tipo' => 'rangoNumero', 'label' => 'Operaciones', 'desde' => 'numero_desde_op' , 'hasta' => 'numero_hasta_op' ],
            ['tipo' => 'rangoFecha', 'label' => 'Fecha','desde' => 'fecha_desde','hasta'=> 'fecha_hasta'], 
			[ 'tipo' => 'lista', 'label' => 'Caja', 'atributo' => 'caja', 'elementos' => $arrayCaja ],	
			[ 'tipo' => 'lista', 'label' => 'Tributo', 'atributo' => 'tributo', 'elementos' => $arrayTributo ],
			['tipo' => 'rangoYLista', 'label' => 'Objeto', 'desde' => 'objeto_desde', 'hasta' => 'objeto_hasta',
				'atributoLista' => 'objeto_tipo', 'elementos' => $arrayTipoObjeto, 'listaLabel' => 'Objeto', 'listaColumnas' => 2,],
			['tipo' => 'texto', 'label' => 'Contribuyente', 'atributo' => 'nombre', 'columnas' => 6, 'caracteres' => 50 ],
			[ 'tipo' => 'rangoNumero', 'label' => 'Monto', 'desde' => 'numero_desde_monto' , 'hasta' => 'numero_hasta_monto' ],
			[ 'tipo' => 'listachica', 'label' => 'Estado', 'atributo' => 'estado', 'elementos' => ['A'=>"Activo",'B'=>"Anulado"] ],
			[ 'tipo' => 'listachica', 'label' => 'Tesoreria', 'atributo' => 'tesoreria', 'elementos' => $arrayTesoreria ],
			
			];
	}

	public function actionImprimir(){
	
		$model = isset($_POST['model']) ? unserialize(urldecode(stripslashes($_POST['model']))) : [];

		$datos = $this->datosResultado($model, [] );
		
		$resultados = $model->buscar()->createCommand()->queryAll();
		$total = 0;
		
		foreach ( $resultados as $r ){
			$total += $r['monto'];
		}
		
		$datos['columnas'] =	[
									['attribute'=>'ticket','label' => 'Ticket', 'contentOptions'=>['style'=>'text-align:center;'] ],
									['attribute'=>'opera','label' => 'Opera', 'contentOptions'=>['style'=>'text-align:center;']],
									['attribute'=>'fecha','label' => 'Fecha', 'contentOptions'=>['style'=>'text-align:center'],'format' => [ 'date', 'php:d/m/Y'] ],
									['attribute'=>'hora','label' => 'Hora', 'contentOptions'=>['style'=>'width:15px;text-align:center']],
									[
										'attribute' => 'monto','label' => 'Monto', 'contentOptions'=>['style'=>'text-align:right'],
										'footer' => number_format( $total, 2 ), 'footerOptions' => [ 'style' => 'border-top: 2px solid #000; text-align:right;font-weight:bold' ]
									],
									['attribute'=>'caja_id','label' => 'Caja', 'contentOptions'=>['style'=>'text-align:center']],
									['attribute'=>'trib_nom','label' => 'Trib. Descr.', 'contentOptions'=>['style'=>'text-align:left']],
									['attribute'=>'obj_id','label' => 'Obj.', 'contentOptions'=>['style'=>'text-align:left']],
									['attribute'=>'obj_nom','label' => 'Obj. Nom.', 'contentOptions'=>['style'=>'width:100px;text-align:left']],
									['attribute'=>'subcta','label' => 'Cta', 'contentOptions'=>['style'=>'text-align:center']],
									['attribute'=>'anio','label' => 'Año', 'contentOptions'=>['style'=>'text-align:center']],
									['attribute'=>'cuota','label' => 'Cuo', 'contentOptions'=>['style'=>'text-align:center']],
									['attribute'=>'num','label' => 'Num', 'contentOptions'=>['style'=>'text-align:center']],
									['attribute' => 'mdps', 'label' => 'MDP', 'contentOptions' => [ 'style' => 'width: 1px; text-align: left' ]],
									['attribute'=>'est','label' => 'Est', 'contentOptions'=>['style'=>'text-align:center']],
								];
		
		$datos['dataProviderResultados'] = new ActiveDataProvider([
		    'query' => $model->buscar(),
			'pagination' => false
		]);

		$datos['descripcion'] = '';

		$pdf = Yii::$app->pdf;
      	if (strtoupper($format) != 'A4-P') $pdf->format = strtoupper($format);
		$pdf->marginTop = '30px';
		$pdf->content = $this->renderPartial('//reportes/reportelistado', ['datos' => $datos, 'titulo' => $model->titulo() ]);

		return $pdf->render();
	}

}

?>
