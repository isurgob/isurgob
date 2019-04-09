<?php

namespace app\controllers\objeto;

use app\controllers\ListadoController;
use app\utils\db\utb;
use app\models\objeto\ComerListado;
use app\models\objeto\Comer;
use yii\helpers\Html;

class ListadocomerController extends ListadoController{

	/**
	 * Ingresar el modelo a utilizar en la búsqueda.
	 */
	public function modelo(){
		return new ComerListado();
	}

	public function datosOpciones($model){

		return [
			'breadcrumbs' => [
				['label' => 'Comercio', 'url' => [ '//objeto/comer/view' ] ],
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
				['label' => 'Comer', 'url' => [ '//objeto/comer/view' ] ],
				[ 'label' => 'Listado', 'url' => [ '//objeto/listadocomer/index' ] ],
				[ 'label' => 'Resultado' ],
			],

			//TODO colocar las columnas que se van a listar (sin las de opciones)
			'columnas' => [
				[ 'attribute' => 'obj_id', 'label' => 'Objeto', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'nombre_redu', 'label' => 'Nombre', 'contentOptions' => [ 'style' => 'width: 200px; text-align: left' ] ],
				[ 'attribute' => 'num_nom', 'label' => 'Titular', 'contentOptions' => [ 'style' => 'width: 200px; text-align: left' ] ],
				[ 'attribute' => 'cuit', 'label' => 'CUIT', 'contentOptions' => [ 'style' => 'width: 100px; text-align: left' ] ],
				[ 'attribute' => 'ib', 'label' => 'Ing. Brutos', 'contentOptions' => [ 'style' => 'width: 10px; text-align: left' ] ],
				[ 'attribute' => 'dompar_dir', 'label' => 'Domicilio', 'contentOptions' => [ 'style' => 'width: 200px; text-align: left' ]],
				[ 'attribute' => 'legajo', 'label' => 'Legajo', 'contentOptions' => [ 'style' => 'width: 10px; text-align: center' ] ],
				[ 'attribute' => 'est_nom', 'label' => 'Est.', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[
	                'class' => 'yii\grid\ActionColumn',
	                'contentOptions'=>['style'=>'width:40px;text-align: center'],
	                'template' => '{view}',
	                'buttons'=>[

	                    'view' => function($url, $model, $key)
	                                {
	                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['objeto/comer/view', 'id' => $model['obj_id']], ["data-pjax" => "0" ]);
	                                },

	                ],
	            ],
			],

			'urlOpciones' => '//objeto/listadocomer/index',

			'imprimir' => ['//objeto/listadocomer/imprimir', 'format' => 'A4-L'],

			'exportar' => [ 
				'Objeto', 'Nombre', 'Titular', 'CUIT', 'Ing. Brutos', 'Legajo', 'Domicilio Particular', 'Domicilio Postal', 'Teléfono', 
				'Rubro', 'Habilitación', 'Vencimiento Habilitación', 'Alta', 'Baja', 'Observación', 'Estado'
			],

		];
	}

	/**
	 * Arreglo de campos que se mostrarán para buscar
	 */
	public function campos(){

		$arrayEstado 			= utb::getAux( 'objeto_test', 'cod', 'nombre', 0, 'tobj = 2', '', true );
		$arrayZona				= utb::getAux( 'comer_tzona' );
		$arrayIVA 				= utb::getAux( 'comer_tiva' );
		$arrayTipo 				= utb::getAux( 'comer_tipo' );
		$arrayTipoLiq 			= utb::getAux( 'comer_tliq' );
		$arrayContador 			= utb::getAux( 'sam.usuarioweb u inner join objeto o on u.obj_id = o.obj_id', 'u.usr_id', 'o.nombre', 0, "acc_dj='S' and u.est='A'" );
		$arrayRubro				= utb::getAux( 'rubro', 'rubro_id', 'nombre', 1, "trib_id in (select trib_id from trib where est='A' and tobj=2)" );
		$arrayGrupoRubro		= utb::getAux( 'rubro_grupo' );
		$arrayDistribuidor 		= utb::getAux( 'sam.sis_usuario', 'usr_id', 'apenom', 1, 'distrib <> 0' );
		$arrayTipoDistribuidor	= utb::getAux( 'objeto_tdistrib' );
		$arrayPromoIndustrial 	= [ '0' => 'No', '1' => 'Si' ];

		return [
			['tipo' => 'rangoObjeto', 'tipoObjeto' => '2', 'label' => 'Objeto', 'desde' => 'obj_id_desde', 'hasta' => 'obj_id_hasta'],
			['tipo' => 'texto', 'label' => 'Nombre', 'atributo' => 'nombre', 'columnas' => 8, 'caracteres' => 50 ],
			['tipo' => 'texto', 'label' => 'Nombre Titular', 'atributo' => 'nombre_titular', 'columnas' => 8, 'caracteres' => 50 ],
			['tipo' => 'listachica', 'label' => 'Estado', 'atributo' => 'est', 'elementos' => $arrayEstado ],
			['tipo' => 'texto', 'label' => 'Legajo', 'atributo' => 'legajo', 'columnas' => 2, 'caracteres' => 10 ],
			['tipo' => 'texto', 'label' => 'Domicilio Parc.', 'atributo' => 'dompar_dir', 'columnas' => 8, 'caracteres' => 50 ],
			['tipo' => 'listachica', 'label' => 'Zona', 'atributo' => 'zona', 'elementos' => $arrayZona ],
			['tipo' => 'mascara', 'label' => 'CUIT', 'atributo' => 'cuit', 'mascara' => '99-99999999-9', 'columnas' => 2, 'caracteres' => 11 ],
			['tipo' => 'texto', 'label' => 'Ing. Brutos', 'atributo' => 'ib', 'columnas' => 2, 'caracteres' => 11 ],
			['tipo' => 'listachica', 'label' => 'Situac. IVA', 'atributo' => 'iva', 'elementos' => $arrayIVA ],
			['tipo' => 'listachica', 'label' => 'Tipo', 'atributo' => 'tipo', 'elementos' => $arrayTipo ],
			['tipo' => 'listachica', 'label' => 'Tipo Liq.', 'atributo' => 'tipoliq', 'elementos' => $arrayTipoLiq ],
			['tipo' => 'lista', 'label' => 'Contador', 'atributo' => 'contador', 'elementos' => $arrayContador ],
			['tipo' => 'lista', 'label' => 'Rubro', 'atributo' => 'rubro', 'elementos' => $arrayRubro ],
			['tipo' => 'lista', 'label' => 'Grupo Rubro', 'atributo' => 'rubro_grupo', 'elementos' => $arrayGrupoRubro ],
			['tipo' => 'lista', 'label' => 'Distribuidor', 'atributo' => 'distrib', 'elementos' => $arrayDistribuidor ],
			['tipo' => 'lista', 'label' => 'Tipo Distrib.', 'atributo' => 'tipo_distribucion', 'elementos' => $arrayTipoDistribuidor ],
			// [
			// 	'tipo' => 'textoConRango', 'label' => 'Dom. Postal', 'atributo' => 'dom_postal', 'columnas' => 6, 'caracteres' => 50,
			// 	'rangoLabel' => 'Puerta', 'rangoDesde' => 'dom_postal_puerta_desde', 'rangoHasta' => 'dom_postal_puerta_hasta',
			// 	'rangoColumnas' => 1,
			// ],
			['tipo' => 'rangoFecha', 'label' => 'Fecha Hab.', 'desde' => 'fecha_habilitacion_desde', 'hasta' => 'fecha_habilitacion_hasta' ],
			['tipo' => 'rangoFecha', 'label' => 'Fecha Venc.', 'desde' => 'fecha_venc_habilitacion_desde', 'hasta' => 'fecha_venc_habilitacion_hasta' ],
			['tipo' => 'rangoFecha', 'label' => 'Fecha Alta', 'desde' => 'fecha_alta_desde', 'hasta' => 'fecha_alta_hasta' ],
			['tipo' => 'rangoFecha', 'label' => 'Fecha Baja', 'desde' => 'fecha_baja_desde', 'hasta' => 'fecha_baja_hasta' ],
			['tipo' => 'listachica', 'label' => 'Promo Indust.', 'atributo' => 'promo_industrial', 'elementos' => $arrayPromoIndustrial ],
		];
	}

}
?>
