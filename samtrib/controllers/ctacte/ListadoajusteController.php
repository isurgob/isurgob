<?php

namespace app\controllers\ctacte;

use Yii;

use app\controllers\ListadoController;
use app\utils\db\utb;
use app\models\ctacte\AjustesListado;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class ListadoajusteController extends ListadoController{

	/**
	 * Ingresar el modelo a utilizar en la búsqueda.
	 */
	public function modelo(){
		return new AjustesListado();
	}

	public function datosOpciones($model){

		return [
			'breadcrumbs' => [
				['label' => 'Ajustes de Cuenta Corriente'],
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
				[ 'label' => 'Ajustes de Cuenta Corriente' ],
				[ 'label' => 'Listado', 'url' => [ '//ctacte/listadoajuste/index' ] ],
				[ 'label' => 'Resultado' ],
			],

			'columnas' => [
				['attribute'=>'aju_id','label' => 'Cód', 'contentOptions'=>['style'=>'width:50px;']],
				['attribute'=>'trib_nom','label' => 'Tributo', 'headerOptions' => ['style' => 'width:150px;']],
				['attribute'=>'obj_id','label' => 'Objeto', 'headerOptions' => ['style' => 'width:80px;']],
				['attribute'=>'obj_nom','label' => 'Nombre Objeto', 'headerOptions' => ['style' => 'width:200px;']],
				['attribute'=>'anio','label' => 'Año', 'contentOptions'=>['style'=>'width:50px']],
				['attribute'=>'cuota','label' => 'Cuota', 'contentOptions'=>['style'=>'width:50px']],
				['attribute'=>'expe', 'label' => 'Expediente', 'headerOptions' => ['style' => 'width:80px;']],
				['attribute'=>'fchmod', 'format' => ['date', 'php:d/m/Y'],'label' => 'Fecha', 'contentOptions'=>['style'=>'width:80px']],
				['attribute'=>'usrmod_nom','label' => 'Usuario', 'headerOptions' => ['style' => 'width:150px;']],
				
				['class' => 'yii\grid\ActionColumn','options'=>['style'=>'width:30px'],'template' => '{view} '.(utb::getExisteProceso(3430) ? '{delete}' : ''),
							'buttons' => [
								'view' => function($url, $model){
									return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['//ctacte/ajustes/view','aju_id'=>$model['aju_id']]);
								},
								'delete' => function($url, $model){
									return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['//ctacte/ajustes/delete','aju_id'=>$model['aju_id']]);
								}
							]
				]
			],

			'urlOpciones' => '//ctacte/listadoajuste/index',
			
			'exportar' => [ 'Cód', 'Tributo', 'Objeto', 'Nombre Objeto', 'Año', 'Cuota', 'Expediente', 'Fecha', 'Usuario' ],
			
			'imprimir' => '//ctacte/listadoajuste/imprimir',
			
			'nuevo' => '//ctacte/ajustes/create'

		];
	}

	/**
	 * Arreglo de campos que se mostrarán para buscar
	 */
	public function campos(){

		$arrayTributo		= utb::getAux('v_trib', 'trib_id', 'nombre', 0, "est = 'A' And tipo Not In (6, 7)");
		$arrayTipoObjeto	= utb::getAux( 'objeto_tipo', 'cod', 'nombre', 0, "est='A'" );
        $arrayUsuario 		= utb::getAux('sam.sis_usuario', 'usr_id', 'apenom');

		return [

			[ 'tipo' => 'lista', 'label' => 'Tributo', 'atributo' => 'tributo', 'elementos' => $arrayTributo ],
			[
				'tipo' => 'listaConCampo', 'label' => 'Objeto', 'labelLista' => 'Tipo:', 'elementosLista' => $arrayTipoObjeto, 'atributoLista' => 'tipo_objeto',
				'labelCampo' => 'Objeto:', 'atributoCampo' => 'objeto', 
			],
			['tipo' => 'numero', 'label' => 'Año', 'atributo' => 'anio', 'columnas' => 2, 'caracteres' => 4 ],
			['tipo' => 'numero', 'label' => 'Cuota', 'atributo' => 'cuota', 'columnas' => 2, 'caracteres' => 3 ],
			['tipo' => 'texto', 'label' => 'Expediente', 'atributo' => 'expe', 'columnas' => 6, 'caracteres' => 50 ],
			[ 'tipo' => 'rangoFecha', 'label' => 'Fecha', 'desde' => 'fecha_desde', 'hasta' => 'fecha_hasta' ],
			[ 'tipo' => 'lista', 'label' => 'Usuario', 'atributo' => 'usuario', 'elementos' => $arrayUsuario ]
		];
	}
	
	public function botones(){

		return 	[
					[ 'id' => 'btNuevo', 'href' => '//ctacte/ajustes/create', 'label' => 'Nuevo', 'class' => 'btn btn-success' ]
				];
	}

}
?>
