<?php

namespace app\controllers\ctacte;

use Yii;

use app\controllers\ListadoController;
use app\utils\db\utb;
use app\models\ctacte\PlanListado;
use app\models\ctacte\Plan;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class ListadoconvenioController extends ListadoController{

	/**
	 * Ingresar el modelo a utilizar en la búsqueda.
	 */
	public function modelo(){
		return new PlanListado();
	}

	public function datosOpciones($model){

		return [
			'breadcrumbs' => [
				['label' => 'Pago a cuenta', 'url' => [ '//ctacte/convenio/plan' ] ],
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
				[ 'label' => 'Pago a cuenta', 'url' => [ '//ctacte/convenio/plan' ] ],
				[ 'label' => 'Listado', 'url' => [ '//ctacte/listadoconvenio/index' ] ],
				[ 'label' => 'Resultado' ],
			],

			'columnas' => [
				[ 'attribute' => 'plan_id', 'label' => 'Cod.', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'tplan_nom', 'label' => 'Tipo', 'contentOptions' => [ 'style' => 'width: 100px; text-align: left' ] ],
				[ 'attribute' => 'obj_id', 'label' => 'Objeto', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'resp', 'label' => 'Responsable', 'contentOptions' => [ 'style' => 'width: 300px; text-align: left' ] ],
				[ 'attribute' => 'nominal', 'label' => 'Nominal', 'contentOptions' => [ 'style' => 'width: 1px;' ] ],
                [ 'attribute' => 'accesor', 'label' => 'Accesor', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'multa', 'label' => 'Multa', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'financia', 'label' => 'Financia', 'contentOptions' => [ 'style' => 'width: 1px; text-align: right' ] ],
				[ 'attribute' => 'est_nom', 'label' => 'Estado', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[
	                'class' => 'yii\grid\ActionColumn',
	                'contentOptions'=>['style'=>'width:40px;text-align: center'],
	                'template' => '{view}',
	                'buttons'=>[

	                    'view' => function($url, $model, $key)
	                                {
	                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['ctacte/convenio/plan', 'id' => $model['plan_id']], ["data-pjax" => "0" ]);
	                                },

	                ],
	            ],
			],

			'urlOpciones' => '//ctacte/listadoconvenio/index',

		];
	}

	/**
	 * Arreglo de campos que se mostrarán para buscar
	 */
	public function campos(){

		$arrayTipoObjeto	= utb::getAux( 'objeto_tipo', 'cod', 'nombre', 0, "est='A'" );
        $arrayTipoConvenio 	= utb::getAux( 'plan_config' );
		$arrayTipoPago 		= utb::getAux( 'plan_tpago' );
		$arrayTipoOrigen	= utb::getAux( 'plan_torigen', 'cod', 'nombre', 0, ( utb::getExisteProceso( 3342 ) ? 'Cod <=3' : 'Cod <=2' ) );
		$arrayEstado		= utb::getAux( 'plan_test' );
		$arrayCaja 			= utb::getAux( 'caja', 'caja_id', 'nombre', 0, "Tipo > 2 and est='A'" );
		$arrayConTributo 	= utb::getAux( 'trib', 'trib_id', 'nombre_redu', 0, "est='A'" );

		return [

			[ 'tipo' => 'rangoNumero', 'label' => 'Nº Convenio', 'desde' => 'numero_desde', 'hasta' => 'numero_hasta' ],
			[ 'tipo' => 'rangoNumero', 'label' => 'Nº Conv. Ant.', 'desde' => 'numero_ant_desde', 'hasta' => 'numero_ant_hasta' ],
			[
				'tipo' => 'rangoYLista', 'label' => 'Objeto', 'desde' => 'objeto_desde', 'hasta' => 'objeto_hasta',
				'atributoLista' => 'objeto_tipo', 'elementos' => $arrayTipoObjeto, 'listaLabel' => 'Objeto', 'listaColumnas' => 2,
			],
			['tipo' => 'texto', 'label' => 'Contribuyente', 'atributo' => 'contribuyente', 'columnas' => 8, 'caracteres' => 50 ],
			['tipo' => 'texto', 'label' => 'Responsable', 'atributo' => 'responsable', 'columnas' => 8, 'caracteres' => 50 ],
			[ 'tipo' => 'lista', 'label' => 'Tipo Convenio', 'atributo' => 'tipo', 'elementos' => $arrayTipoConvenio ],
			[ 'tipo' => 'listachica', 'label' => 'Forma Pago', 'atributo' => 'forma_pago', 'elementos' => $arrayTipoPago ],
			[ 'tipo' => 'listachica', 'label' => 'Tipo Origen', 'atributo' => 'origen', 'elementos' => $arrayTipoOrigen ],
			[ 'tipo' => 'rangoFecha', 'label' => 'Fecha Alta', 'desde' => 'fecha_alta_desde', 'hasta' => 'fecha_alta_hasta' ],
			[ 'tipo' => 'rangoFecha', 'label' => 'Fecha Baja', 'desde' => 'fecha_baja_desde', 'hasta' => 'fecha_baja_hasta' ],
			[ 'tipo' => 'rangoFecha', 'label' => 'Fecha Imputa', 'desde' => 'fecha_imputa_desde', 'hasta' => 'fecha_imputa_hasta' ],
			[ 'tipo' => 'rangoFecha', 'label' => 'Fecha Decae', 'desde' => 'fecha_decae_desde', 'hasta' => 'fecha_decae_hasta' ],
			[ 'tipo' => 'rangoNumero', 'label' => 'Cuotas', 'desde' => 'cuotas_desde', 'hasta' => 'cuotas_hasta' ],
			[ 'tipo' => 'rango', 'label' => 'Monto Cuota', 'desde' => 'monto_cuotas_desde', 'hasta' => 'monto_cuotas_hasta' ],
			[ 'tipo' => 'rango', 'label' => 'Interés', 'desde' => 'interes_desde', 'hasta' => 'interes_hasta' ],
			[ 'tipo' => 'rango', 'label' => 'Cuota atrasada', 'desde' => 'cuota_atrasada_desde', 'hasta' => 'cuota_atrasada_hasta' ],
			[ 'tipo' => 'listachica', 'label' => 'Estado', 'atributo' => 'estado', 'elementos' => $arrayEstado ],
			[ 'tipo' => 'listachica', 'label' => 'Caja', 'atributo' => 'caja', 'elementos' => $arrayCaja ],
			[ 'tipo' => 'listachica', 'label' => 'Con el tributo', 'atributo' => 'con_tributo', 'elementos' => $arrayConTributo ],
			['tipo' => 'checkOpcion', 'atributo' => 'quita_especial', 'columnas' => 4 ],
			['tipo' => 'checkOpcion', 'atributo' => 'cambio_fecha', 'columnas' => 6 ],
			['tipo' => 'checkOpcion', 'atributo' => 'coincide_con_titular', 'columnas' => 6 ],


		];
	}

}
?>
