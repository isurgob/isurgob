<?php

namespace app\controllers\objeto;

use Yii;

use app\controllers\ListadoController;
use app\utils\db\utb;
use app\models\objeto\RodadoListado;
use app\models\objeto\Rodado;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class ListadorodadoController extends ListadoController{

	/**
	 * Ingresar el modelo a utilizar en la búsqueda.
	 */
	public function modelo(){
		return new RodadoListado();
	}

	public function datosOpciones($model){

		return [
			'breadcrumbs' => [
				['label' => 'Rodado', 'url' => [ '//objeto/rodado/view' ] ],
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
				[ 'label' => 'Rodado', 'url' => [ '//objeto/rodado/view' ] ],
				[ 'label' => 'Listado', 'url' => [ '//objeto/listadorodado/index' ] ],
				[ 'label' => 'Resultado' ],
			],

			//TODO colocar las columnas que se van a listar (sin las de opciones)
			'columnas' => [
				[ 'attribute' => 'obj_id', 'label' => 'Objeto', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'dominio', 'label' => 'Domino', 'contentOptions' => [ 'style' => 'width: 1px; text-align: left' ] ],
				[ 'attribute' => 'num_nom', 'label' => 'Titular', 'contentOptions' => [ 'style' => 'width: 200px; text-align: left' ] ],
				[ 'attribute' => 'cat_nom', 'label' => 'Categoría', 'contentOptions' => [ 'style' => 'width: 1px; text-align: left' ] ],
				[ 'attribute' => 'marca_nom', 'label' => 'Marca', 'contentOptions' => [ 'style' => 'width: 1px; text-align: left' ] ],
				[ 'attribute' => 'modelo_nom', 'label' => 'Modelo', 'contentOptions' => [ 'style' => 'width: 200px; text-align: left' ] ],
				[ 'attribute' => 'anio', 'label' => 'Año', 'contentOptions' => [ 'style' => 'width: 1px; text-align: left' ] ],
				[ 'attribute' => 'cilindrada', 'label' => 'Cilindrada', 'contentOptions' => [ 'style' => 'width: 1px; text-align: left' ] ],
				[ 'attribute' => 'est_nom', 'label' => 'Estado', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[
	                'class' => 'yii\grid\ActionColumn',
	                'contentOptions'=>['style'=>'width:40px;text-align: center'],
	                'template' => '{view}',
	                'buttons'=>[

	                    'view' => function($url, $model, $key)
	                                {
	                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['objeto/rodado/view', 'id' => $model['obj_id']], ["data-pjax" => "0" ]);
	                                },

	                ],
	            ],
			],

			'urlOpciones' => '//objeto/listadorodado/index',
			
			'exportar' => [ 
				'Objeto', 'Dominio', 'Titular', 'Categoría', 'Marca', 'Modelo', 'Año', 'Cilindrada', 'Estado'
			],

		];
	}

	/**
	 * Arreglo de campos que se mostrarán para buscar
	 */
	public function campos(){

		$arrayEstado 			= utb::getAux( 'objeto_test', 'cod', 'nombre', 0, 'tobj = 5', '', true );
		$arrayTipoLiquidacion 	= utb::getAux( 'rodado_tliq', 'cod', 'nombre', 0 );

		$arrayRNPA = Yii::$app->db->createCommand( "select distinct tipo_nom from rodado_aforo order by tipo_nom" )->queryAll();
		$arrayRNPA = ArrayHelper::map( $arrayRNPA, 'tipo_nom', 'tipo_nom' );

		$arrayMarca 			= utb::getAux( 'rodado_marca' );
		$arrayCategoria			= utb::getAux( 'rodado_tcat' );
		$arrayDelegacion 		= utb::getAux( 'rodado_tdeleg' );
		$arrayTipoCombustible 	= utb::getAux( 'rodado_tcombustible' );
		$arrayTipoUso 			= utb::getAux( 'rodado_tuso' );
		$arrayTipoDistribucion 	= utb::getAux( 'objeto_tdistrib' );
		$arrayTipoFormulario 	= utb::getAux( 'rodado_tform' );

		return [
			['tipo' => 'rangoObjeto', 'tipoObjeto' => '1', 'label' => 'Objeto', 'desde' => 'obj_id_desde', 'hasta' => 'obj_id_hasta'],
			['tipo' => 'texto', 'label' => 'Nombre', 'atributo' => 'nombre', 'columnas' => 8, 'caracteres' => 50 ],
			['tipo' => 'texto', 'label' => 'Nombre Titular', 'atributo' => 'nombre_tit', 'columnas' => 8, 'caracteres' => 50 ],
			['tipo' => 'listachica', 'label' => 'Estado', 'atributo' => 'est', 'elementos' => $arrayEstado ],
			['tipo' => 'listachica', 'label' => 'Tipo Liq.', 'atributo' => 'tipoliq', 'elementos' => $arrayTipoLiquidacion ],
			['tipo' => 'lista', 'label' => 'Tipo RNPA', 'atributo' => 'tipoRNPA', 'elementos' => $arrayRNPA ],
			['tipo' => 'rangoNumero', 'label' => 'Valor aforo', 'desde' => 'valor_aforo_desde', 'hasta' => 'valor_aforo_hasta' ],
			['tipo' => 'listachica', 'label' => 'Marca', 'atributo' => 'marca', 'elementos' => $arrayMarca ],
			['tipo' => 'listachica', 'label' => 'Categoría', 'atributo' => 'categoria', 'elementos' => $arrayCategoria ],
			['tipo' => 'texto', 'label' => 'Modelo', 'atributo' => 'modelo', 'columnas' => 8, 'caracteres' => 50 ],
			['tipo' => 'rangoNumero', 'label' => 'Año', 'desde' => 'anio_desde', 'hasta' => 'anio_hasta' ],
			['tipo' => 'texto', 'label' => 'Dominio', 'atributo' => 'dominio', 'columnas' => 2, 'caracteres' => 10 ],
			['tipo' => 'texto', 'label' => 'Dominio Ant.', 'atributo' => 'dominio_ant', 'columnas' => 2, 'caracteres' => 10 ],
			['tipo' => 'texto', 'label' => 'Motor', 'atributo' => 'motor', 'columnas' => 6, 'caracteres' => 30 ],
			['tipo' => 'texto', 'label' => 'Chasis', 'atributo' => 'chasis', 'columnas' => 6, 'caracteres' => 30 ],
			['tipo' => 'numero', 'label' => 'Cilindrada', 'atributo' => 'cilindrada', 'columnas' => 2, 'caracteres' => 10 ],
			['tipo' => 'lista', 'label' => 'Delegación', 'atributo' => 'delegacion', 'elementos' => $arrayDelegacion ],
			['tipo' => 'decimal', 'label' => 'Peso', 'atributo' => 'peso', 'columnas' => 2, 'caracteres' => 10 ],
			['tipo' => 'texto', 'label' => 'Color', 'atributo' => 'color', 'columnas' => 4, 'caracteres' => 15 ],
			['tipo' => 'listachica', 'label' => 'Combustible', 'atributo' => 'tipo_combustible', 'elementos' => $arrayTipoCombustible ],
			['tipo' => 'listachica', 'label' => 'Uso', 'atributo' => 'uso', 'elementos' => $arrayTipoUso ],
			['tipo' => 'listachica', 'label' => 'T. Distribución', 'atributo' => 'tipo_distribucion', 'elementos' => $arrayTipoDistribucion ],
			['tipo' => 'texto', 'label' => 'Conductor', 'atributo' => 'conductor', 'columnas' => 8, 'caracteres' => 50 ],
			['tipo' => 'rangoFecha', 'label' => 'Fecha Compra', 'desde' => 'fecha_compra_desde', 'hasta' => 'fecha_compra_hasta' ],
			['tipo' => 'listachica', 'label' => 'T. Formulario', 'atributo' => 'tipo_formulario', 'elementos' => $arrayTipoFormulario ],
			['tipo' => 'texto', 'label' => 'Remito', 'atributo' => 'remito', 'columnas' => 6, 'caracteres' => 10 ],
			['tipo' => 'numero', 'label' => 'Remito Año', 'atributo' => 'remito_anio', 'columnas' => 2, 'caracteres' => 4 ],
			['tipo' => 'checkOpcion', 'atributo' => 'vinculo_aforo', 'columnas' => 3 ],
			['tipo' => 'checkOpcion', 'atributo' => 'valuacion_aforo', 'columnas' => 3 ],
		];
	}

}
?>
