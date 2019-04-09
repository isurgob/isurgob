<?php

namespace app\controllers\ctacte;

use Yii;

use app\controllers\ListadoController;
use app\utils\db\utb;
use app\models\ctacte\JudiListado;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class ListadojudiController extends ListadoController{

	/**
	 * Ingresar el modelo a utilizar en la búsqueda.
	 */
	public function modelo(){
		return new JudiListado();
	}

	public function datosOpciones($model){

		return [
			'breadcrumbs' => [
				['label' => 'Administración de Apremio', 'url' => [ '//ctacte/judi/view' ] ],
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
				[ 'label' => 'Administración de Apremio', 'url' => [ '//ctacte/judi/view' ] ],
				[ 'label' => 'Listado', 'url' => [ '//ctacte/listadojudi/index' ] ],
				[ 'label' => 'Resultado' ],
			],

			//TODO colocar las columnas que se van a listar (sin las de opciones)
			'columnas' => [
				['attribute'=>'judi_id','label' => 'Judi','contentOptions'=>['style'=>'width:10px;text-align:center']],
				['attribute'=>'expe','label' => 'Expediente','contentOptions'=>['style'=>'width:80px;text-align:center']],
				['attribute'=>'obj_id','label' => 'Objeto','contentOptions'=>['style'=>'width:15px;text-align:center']],
				['attribute'=>'caratula','label' => 'Carátula','contentOptions'=>['style'=>'width:200px; text-align:left']],
				['attribute'=>'fchalta','label' => 'Alta', 'contentOptions'=>['style'=>'width:15px']],
				['attribute'=>'procurador_nom','label' => 'Procurador', 'contentOptions'=>['style'=>'width:15px']],
				['attribute'=>'deuda','label' => 'Deuda', 'contentOptions'=>['style'=>'width:15px;text-align:right']],
				['attribute'=>'est_nom','label' => 'Estado', 'contentOptions'=>['style'=>'width:100px;text-align:center']],	       
				[
	                'class' => 'yii\grid\ActionColumn',
	                'contentOptions'=>['style'=>'width:40px;text-align: center'],
	                'template' => '{view}',
	                'buttons'=>[

	                    'view' => function($url, $model, $key)
	                                {
	                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['ctacte/judi/view', 'id' => $model['judi_id']], ["data-pjax" => "0" ]);
	                                },

	                ],
	            ],
			],

			'urlOpciones' => '//ctacte/listadojudi/index',
			
			'imprimir' => '//ctacte/listadojudi/imprimir',
			
			'exportar' => [ 'Judi', 'Expediente', 'Objeto', 'Carátula', 'Alta', 'Procurador', 'Deuda', 'Estado' ]

		];
	}

	/**
	 * Arreglo de campos que se mostrarán para buscar
	 */
	public function campos(){

		$arrayTipoObjeto	= utb::getAux('objeto_tipo','cod','nombre',0,"est='A'");
		$arrayReparticion	= utb::getAux('judi_trep');
        $arrayEstado		= utb::getAux('judi_test');
		$arrayProcurador	= utb::getAux('sam.sis_usuario','usr_id','apenom', 0, 'abogado=1');
		$arrayJuzgado		= utb::getAux('judi_juzgado');
		$arrayEtapa			= utb::getAux('judi_tetapa');
		$arrayMotivoDev		= utb::getAux('judi_tdev');

		return [

			[ 'tipo' => 'rangoNumero', 'col_label' => 3, 'label' => 'Código', 'desde' => 'numero_desde', 'hasta' => 'numero_hasta' ],
			[
				'tipo' => 'rangoYLista', 'col_label' => 3, 'label' => 'Objeto', 'desde' => 'objeto_desde', 'hasta' => 'objeto_hasta',
				'atributoLista' => 'objeto_tipo', 'elementos' => $arrayTipoObjeto, 'listaLabel' => 'Objeto', 'listaColumnas' => 2,
			],
			[ 'tipo' => 'texto', 'col_label' => 3, 'label' => 'Expediente', 'atributo' => 'expediente', 'columnas' => 6, 'caracteres' => 12 ],
			[ 'tipo' => 'texto', 'col_label' => 3, 'label' => 'Carátula', 'atributo' => 'caratula', 'columnas' => 6, 'caracteres' => 12 ],
			[ 'tipo' => 'rangoNumero', 'col_label' => 3, 'label' => 'Deuda', 'desde' => 'deuda_desde', 'hasta' => 'deuda_hasta' ],
			[ 'tipo' => 'rangoNumero', 'col_label' => 3, 'label' => 'Gastos', 'desde' => 'gastos_desde', 'hasta' => 'gastos_hasta' ],
			[ 'tipo' => 'rangoNumero', 'col_label' => 3, 'label' => 'Honorarios', 'desde' => 'honorario_desde', 'hasta' => 'honorario_hasta' ],
			[ 'tipo' => 'lista', 'col_label' => 3, 'label' => 'Repartición', 'atributo' => 'reparticion', 'elementos' => $arrayReparticion ],
			[ 'tipo' => 'lista', 'col_label' => 3, 'label' => 'Estado', 'atributo' => 'estado', 'elementos' => $arrayEstado ],
			[ 'tipo' => 'lista', 'col_label' => 3, 'label' => 'Procurador', 'atributo' => 'procurador', 'elementos' => $arrayProcurador ],
			[ 'tipo' => 'lista', 'col_label' => 3, 'label' => 'Juzgado', 'atributo' => 'juzgado', 'elementos' => $arrayJuzgado ],
			[ 'tipo' => 'lista', 'col_label' => 3, 'label' => 'Etapa', 'atributo' => 'etapa', 'elementos' => $arrayEtapa ],
			[ 'tipo' => 'lista', 'col_label' => 3, 'label' => 'Motivo Devolución', 'atributo' => 'motivo_devolucion', 'elementos' => $arrayMotivoDev ],
			[ 'tipo' => 'rangoFecha', 'col_label' => 3, 'label' => 'Movimiento', 'desde' => 'fchmov_desde', 'hasta' => 'fchmov_hasta' ],
			[ 'tipo' => 'rangoFecha', 'col_label' => 3, 'label' => 'Modificación', 'desde' => 'fchmod_desde', 'hasta' => 'fchmod_hasta' ]

		];
	}

}
?>
