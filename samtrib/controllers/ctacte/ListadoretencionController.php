<?php

namespace app\controllers\ctacte;

use Yii;

use app\controllers\ListadoController;
use app\utils\db\utb;
use app\models\ctacte\RetencionListado;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class ListadoretencionController extends ListadoController{

	/**
	 * Ingresar el modelo a utilizar en la búsqueda.
	 */
	public function modelo(){
		return new RetencionListado();
	}

	public function datosOpciones($model){

		return [
			'breadcrumbs' => [
				['label' => 'Retencion', 'url' => [ '//ctacte/retencion/index' ] ],
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
				[ 'label' => 'Retencion', 'url' => [ '//ctacte/retencion/index' ] ],
				[ 'label' => 'Listado', 'url' => [ '//ctacte/listadoretencion/index' ] ],
				[ 'label' => 'Resultado' ],
			],

			//TODO colocar las columnas que se van a listar (sin las de opciones)
			'columnas' => [
				['attribute' => 'ret_id', 'label' => 'ID', 'contentOptions' => ['class' => 'grillaGrande']],
				['attribute' => 'retdj_id', 'label' => 'RetDJ', 'contentOptions' => ['class' => 'grillaGrande']],
				['attribute' => 'ag_rete', 'label' => 'Agente', 'contentOptions' => ['class' => 'grillaGrande']],
				['attribute' => 'cuit', 'label' => 'CUIT', 'contentOptions' => ['class' => 'grillaGrande']],
				['attribute' => 'anio', 'label' => 'Año', 'contentOptions' => ['class' => 'grillaGrande']],
				['attribute' => 'mes', 'label' => 'Mes', 'contentOptions' => ['class' => 'grillaGrande']],
				['attribute' => 'obj_id', 'label' => 'Objeto', 'contentOptions' => ['class' => 'grillaGrande']],
				['attribute' => 'numero', 'label' => 'Núm.', 'contentOptions' => ['class' => 'grillaGrande']],
				['attribute' => 'comprob', 'label' => 'Comprobante', 'contentOptions' => ['class' => 'grillaGrande']],
				['attribute' => 'fecha', 'label' => 'Fecha', 'format' => ['date', 'php:d/m/Y'], 'contentOptions' => ['class' => 'grillaGrande']],
				['attribute' => 'lugar', 'label' => 'Lugar', 'contentOptions' => ['class' => 'grillaGrande']],
				['attribute' => 'base', 'label' => 'Base', 'format' => ['decimal', 2], 'contentOptions' => ['class' => 'grillaGrande', 'style' => 'text-align:right;']],
				['attribute' => 'ali', 'label' => 'Alícuota', 'format' => ['decimal', 2], 'contentOptions' => ['class' => 'grillaGrande', 'style' => 'text-align:right;']],
				['attribute' => 'monto', 'label' => 'Monto', 'format' => ['decimal', 2], 'contentOptions' => ['class' => 'grillaGrande', 'style' => 'text-align:right;']],
				['attribute' => 'est', 'label' => 'Est.', 'contentOptions' => ['class' => 'grillaGrande']],
				[
	                'class' => 'yii\grid\ActionColumn',
	                'contentOptions'=>['style'=>'width:40px;text-align: center'],
	                'template' => '{view}',
	                'buttons'=>[

	                    'view' => function($url, $model, $key)
	                                {
	                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['ctacte/retencion/view', 'id' => $model['retdj_id']], ["data-pjax" => "0" ]);
	                                },

	                ],
	            ],
			],

			'urlOpciones' => '//ctacte/listadoretencion/index',

			'imprimir' => ['//ctacte/listadoretencion/imprimir'],
			
			'exportar' => [ 'ID', 'RetDJ', 'Agente', 'CUIT', 'Año', 'Mes', 'Objeto', 'Número', 'Comprobante', 'Fecha', 'Lugar', 'Base', 'Alícuota', 'Monto', 'Estado' ]

		];
	}

	/**
	 * Arreglo de campos que se mostrarán para buscar
	 */
	public function campos(){

        $arrayTipoObjeto	= utb::getAux( 'objeto_tipo', 'cod', 'nombre', 0, "est='A'" );
        $arrayAgente	= utb::getAux('v_persona', 'ag_rete', 'ag_rete', 0, "est = 'A' And ag_rete IS NOT NULL AND length(ag_rete) > 0");
		$arrayEstado 	= utb::getAux('ret_test');
		$arrayComprob   = utb::getAux('ret_tcomprob');

		return [
			['tipo' => 'rangoNumero', 'label' => 'ID.', 'desde' => 'ret_id_desde', 'hasta' => 'ret_id_hasta' ],
            ['tipo' => 'rangoYLista', 'label' => 'Objeto', 'desde' => 'objeto_desde', 'hasta' => 'objeto_hasta',
				'atributoLista' => 'objeto_tipo', 'elementos' => $arrayTipoObjeto, 'listaLabel' => 'Objeto', 'listaColumnas' => 2,],
            ['tipo' => 'mascara', 'label' => 'CUIT', 'atributo' => 'cuit', 'mascara' => '99-99999999-9', 'columnas' => 3, 'caracteres' => 11 ],
            ['tipo' => 'texto', 'label' => 'Contribuyente', 'atributo' => 'contribuyente', 'columnas' => 6, 'caracteres' => 50 ],
			['tipo' => 'texto', 'label' => 'Lugar', 'atributo' => 'lugar', 'columnas' => 6, 'caracteres' => 30 ],
			['tipo' => 'periodoSimple', 'label' => 'Período', 'anio' => 'anio', 'cuota' => 'mes', 'columnas' => 2, 'caracteres' => 8 ],
            ['tipo' => 'rangoFecha', 'label' => 'Fecha', 'desde' => 'fecha_desde', 'hasta' => 'fecha_hasta' ],
            ['tipo' => 'rangoNumero', 'label' => 'Base', 'desde' => 'base_desde', 'hasta' => 'base_hasta' ],
            ['tipo' => 'rangoNumero', 'label' => 'Monto', 'desde' => 'monto_desde', 'hasta' => 'monto_hasta' ],
            ['tipo' => 'lista', 'label' => 'Agente', 'atributo' => 'agente', 'elementos' => $arrayAgente ],
            ['tipo' => 'lista', 'label' => 'Estado', 'atributo' => 'estado', 'elementos' => $arrayEstado ],
            ['tipo' => 'texto', 'label' => 'Comprobante', 'atributo' => 'comprobante', 'columnas' => 6, 'caracteres' => 30 ],
			['tipo' => 'lista', 'label' => 'Tipo Comprob.', 'atributo' => 'tcomprobante', 'elementos' => $arrayComprob ],
			['tipo' => 'texto', 'label' => 'Número', 'atributo' => 'num', 'columnas' => 6, 'caracteres' => 30 ],
		    ['tipo' => 'checkOpcion', 'atributo' => 'sin_objeto_vinculado', 'columnas' => 3 ],

		];
	}

}
?>
