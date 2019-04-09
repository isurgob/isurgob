<?php

namespace app\controllers\ctacte;

use Yii;

use app\controllers\ListadoController;
use app\utils\db\utb;
use app\models\ctacte\DdjjListado;
use app\models\ctacte\Ddjj;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class ListadoddjjController extends ListadoController{

	/**
	 * Ingresar el modelo a utilizar en la búsqueda.
	 */
	public function modelo(){
		return new DdjjListado();
	}

	public function datosOpciones($model){

		return [
			'breadcrumbs' => [
				['label' => 'Ddjj', 'url' => [ '//ctacte/ddjj/view' ] ],
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
				[ 'label' => 'Ddjj', 'url' => [ '//ctacte/ddjj/view' ] ],
				[ 'label' => 'Listado', 'url' => [ '//ctacte/listadoddjj/index' ] ],
				[ 'label' => 'Resultado' ],
			],

			//TODO colocar las columnas que se van a listar (sin las de opciones)
			'columnas' => [
				[ 'attribute' => 'dj_id', 'label' => 'DJ', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'trib_nom', 'label' => 'Tributo', 'contentOptions' => [ 'style' => 'width: 100px; text-align: left' ] ],
                [ 'attribute' => 'obj_id', 'label' => 'Objeto', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
                [ 'attribute' => 'obj_nom', 'label' => 'Titular', 'contentOptions' => [ 'style' => 'width: 200px; text-align: left' ] ],
				[ 'attribute' => 'subcta', 'label' => 'Subcta', 'contentOptions' => [ 'style' => 'width: 1px;' ] ],
                [ 'attribute' => 'est', 'label' => 'Est.', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'anio', 'label' => 'Año', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'cuota', 'label' => 'Cuota', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
                [ 'attribute' => 'orden_nom', 'label' => 'Orden', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'base', 'label' => 'Base', 'contentOptions' => [ 'style' => 'width: 1px; text-align: right' ] ],
                [ 'attribute' => 'monto', 'label' => 'Monto', 'contentOptions' => [ 'style' => 'width: 1px; text-align: right' ] ],
                [ 'attribute' => 'multa', 'label' => 'Multa', 'contentOptions' => [ 'style' => 'width: 1px; text-align: right' ] ],
				[ 'attribute' => 'fchpresenta', 'label' => 'Venc.', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ], 'format' => [ 'date', 'php:d/m/Y'] ],
				[
	                'class' => 'yii\grid\ActionColumn',
	                'contentOptions'=>['style'=>'width:40px;text-align: center'],
	                'template' => '{view}',
	                'buttons'=>[

	                    'view' => function($url, $model, $key)
	                                {
	                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['ctacte/ddjj/view', 'id' => $model['dj_id']], ["data-pjax" => "0" ]);
	                                },

	                ],
	            ],
			],

			'urlOpciones' => '//ctacte/listadoddjj/index',
			
			'imprimir' => ['//ctacte/listadoddjj/imprimir', 'format' => 'A4-L'],
			
			'exportar' => [ 'DJ', 'Tributo', 'Objeto', 'Titular', 'Subcta', 'Est.', 'Año', 'Cuota', 'Orden', 'Base', 'Monto', 'Multa', 'Vencimiento' ]

		];
	}

	/**
	 * Arreglo de campos que se mostrarán para buscar
	 */
	public function campos(){

        $arrayTributo   	= utb::getAux( 'trib', 'trib_id', 'nombre', 0, "tipo = 2 AND dj_tribprinc = trib_id AND est = 'A'" );
		$arrayTipoObjeto	= utb::getAux( 'objeto_tipo', 'cod', 'nombre', 0, "est='A'" );
		$arrayGrupoRubro 	= utb::getAux( 'rubro_grupo' );
		$arrayContador		= utb::getAux( 'sam.usuarioweb u inner join objeto o on u.obj_id = o.obj_id', 'u.usr_id', 'o.nombre', 0, "acc_dj='S' and u.est='A'" );
		$arrayTipo 			= utb::getAux( 'ddjj_tipo' );
		$arrayEstadoDJ 		= utb::getAux( 'ddjj_test' );
		$arrayEstadoCtaCte	= utb::getAux( 'ctacte_test' );

		return [

			[ 'tipo' => 'rangoNumero', 'label' => 'Nº DDJJ', 'desde' => 'numero_desde', 'hasta' => 'numero_hasta' ],
            [
				'tipo' => 'rangoYLista', 'label' => 'Objeto', 'desde' => 'objeto_desde', 'hasta' => 'objeto_hasta',
				'atributoLista' => 'objeto_tipo', 'elementos' => $arrayTipoObjeto, 'listaLabel' => 'Objeto', 'listaColumnas' => 2,],
			['tipo' => 'rangoNumero', 'label' => 'Base Imp.', 'desde' => 'base_imponible_desde', 'hasta' => 'base_imponible_hasta' ],
			['tipo' => 'rangoNumero', 'label' => 'Monto', 'desde' => 'monto_desde', 'hasta' => 'monto_hasta' ],
			[
				'tipo' => 'periodo', 'label' => 'Período', 'adesde' => 'desde_anio','cdesde' => 'desde_cuota',
				'ahasta' => 'hasta_anio', 'chasta' => 'hasta_cuota',
			],
			['tipo' => 'texto', 'label' => 'Nombre', 'atributo' => 'nombre', 'columnas' => 8, 'caracteres' => 50 ],
			['tipo' => 'texto', 'label' => 'Titular', 'atributo' => 'titular', 'columnas' => 8, 'caracteres' => 50 ],
			['tipo' => 'listachica', 'label' => 'Tributo', 'atributo' => 'tributo', 'elementos' => $arrayTributo ],
			['tipo' => 'lista', 'label' => 'Grupo Rubro', 'atributo' => 'grupo_rubro', 'elementos' => $arrayGrupoRubro ],
			['tipo' => 'texto', 'label' => 'Rubro', 'atributo' => 'rubro_nom', 'columnas' => 8, 'caracteres' => 100 ],
			['tipo' => 'lista', 'label' => 'Contador', 'atributo' => 'contador', 'elementos' => $arrayContador ],
			['tipo' => 'rangoFecha', 'label' => 'Presentación', 'desde' => 'fecha_presentacion_desde', 'hasta' => 'fecha_presentacion_hasta' ],
			['tipo' => 'listachica', 'label' => 'Tipo', 'atributo' => 'tipo', 'elementos' => $arrayTipo ],
			['tipo' => 'listachica', 'label' => 'Estado DJ', 'atributo' => 'estado_dj', 'elementos' => $arrayEstadoDJ ],
			['tipo' => 'listachica', 'label' => 'Estado CtaCte', 'atributo' => 'estado_ctacte', 'elementos' => $arrayEstadoCtaCte ],
			['tipo' => 'rangoFecha', 'label' => 'Fecha Pago', 'desde' => 'fecha_pago_desde', 'hasta' => 'fecha_pago_hasta' ],
			['tipo' => 'checkOpcion', 'atributo' => 'fiscalizada', 'columnas' => 3 ],
			['tipo' => 'checkOpcion', 'atributo' => 'presentacion_atrasada', 'columnas' => 3 ],
			['tipo' => 'checkOpcion', 'atributo' => 'con_bonificacion', 'columnas' => 3 ],
			['tipo' => 'checkOpcion', 'atributo' => 'bonificacion_con_deuda', 'columnas' => 3 ],
			['tipo' => 'checkOpcion', 'atributo' => 'tomaron_saldo_y_tenian', 'columnas' => 3 ],
			['tipo' => 'checkOpcion', 'atributo' => 'tomaron_saldo_y_no_tenian', 'columnas' => 4 ],
		];
	}

}
?>
