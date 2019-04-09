<?php

namespace app\controllers\ctacte;

use Yii;

use app\controllers\ListadoController;
use app\utils\db\utb;
use app\models\ctacte\FiscalizacionListado;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class ListadofiscalizacionController extends ListadoController{

	/**
	 * Ingresar el modelo a utilizar en la búsqueda.
	 */
	public function modelo(){
		return new FiscalizacionListado();
	}

	public function datosOpciones($model){

		return [
			'breadcrumbs' => [
				['label' => 'Fiscalización', 'url' => [ '//ctacte/fiscaliza/view' ] ],
				'Listado',
				'Opciones'
			]
		];
	}

	/**
	 * Función que se utiliza para generar los datos que se enviarán a la vista de resultados.
	 */
	public function datosResultado($model,$resultados){

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
				[ 'label' => 'Fiscaliazación' ],
				[ 'label' => 'Listado', 'url' => [ '//ctacte/listadofiscalizacion/index' ] ],
				[ 'label' => 'Resultado' ],
			],

			'columnas' => [
				['attribute'=>'fisca_id','label' => 'ID','contentOptions'=>['style'=>'width:50px;']],
				['attribute'=>'obj_id','label' => 'Objeto', 'headerOptions' => ['style' => 'width:150px;']],
				['attribute'=>'obj_nom','label' => 'Nombre', 'headerOptions' => ['style' => 'width:80px;']],
				['attribute'=>'expe','label' => 'Expediente', 'headerOptions' => ['style' => 'width:200px;']],
				['attribute'=>'inspector_nom','label' => 'Inspector', 'contentOptions'=>['style'=>'width:50px']],
				['attribute'=>'fchalta','format' => ['date', 'php:d/m/Y'],'label' => 'Fch. Alta', 'contentOptions'=>['style'=>'width:80px']],
				['attribute'=>'fchbaja','format' => ['date', 'php:d/m/Y'],'label' => 'Fch. Baja', 'contentOptions'=>['style'=>'width:80px']],
				['attribute'=>'est_nom','label' => 'Estado', 'headerOptions' => ['style' => 'width:200px;']],
				['attribute'=>'modif','label' => 'Modificación', 'headerOptions' => ['style' => 'width:150px;']],
				['class' => 'yii\grid\ActionColumn','options'=>['style'=>'width:30px'],
				 'template' => '{view}',
				'buttons' => [
								'view' => function($url,$model){
									return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['//ctacte/fiscaliza/view','id'=>$model['fisca_id']]);
								},
								
							]
				]
			],

			'urlOpciones' => '//ctacte/listadofiscalizacion/index',
			
			'exportar' => [ 'ID', 'Objeto', 'Nombre', 'Expediente', 'Inspector', 'Fch. Alta', 'Fch. Baja', 'Estado', 'Modificación' ],
			
			'imprimir' => '//ctacte/listadofiscalizacion/imprimir',
			];
	}

	/**
	 * Arreglo de campos que se mostrarán para buscar
	 */
	public function campos(){

		$arrayEstado		= utb::getAux('fiscaliza_test');
		$arrayInspector	    = utb::getAux('sam.sis_usuario','usr_id','apenom',0,"inspec_comer <> 0");
        

		return [

			['tipo' => 'rangoNumero', 'label' => 'ID', 'desde' => 'id_desde' , 'hasta' => 'id_hasta' ],
			['tipo' => 'rangoNumero', 'label' => 'Objeto', 'desde' => 'obj_desde' , 'hasta' => 'obj_hasta' ],
			['tipo' => 'listachica', 'label' => 'Estado DJ', 'atributo' => 'estadodj', 'elementos' => $arrayEstado ],
			['tipo' => 'texto', 'label' => 'Expediente', 'atributo' => 'expediente', 'columnas' => 3, 'caracteres' => 50 ],
			['tipo' => 'lista', 'label' => 'Inspector Ppal', 'atributo' => 'inspec', 'elementos' => $arrayInspector ],
			['tipo' => 'rangoFecha', 'label' => 'Fecha Alta', 'desde' => 'fecha_desde_alta', 'hasta' => 'fecha_hasta_alta'],
			['tipo' => 'rangoFecha', 'label' => 'Fecha Baja', 'desde' => 'fecha_desde_baja', 'hasta' => 'fecha_hasta_baja'],
			

		];
	}

}
?>
