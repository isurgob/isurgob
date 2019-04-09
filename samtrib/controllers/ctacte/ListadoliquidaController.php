<?php

namespace app\controllers\ctacte;

use Yii;

use app\controllers\ListadoController;
use app\utils\db\utb;
use app\models\ctacte\LiquidaListado;
use app\models\ctacte\Liquida;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class ListadoliquidaController extends ListadoController{

	/**
	 * Ingresar el modelo a utilizar en la búsqueda.
	 */
	public function modelo(){
		return new LiquidaListado();
	}

	public function datosOpciones($model){

		return [
			'breadcrumbs' => [
				['label' => 'Liquida', 'url' => [ '//ctacte/liquida/view' ] ],
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
				[ 'label' => 'Liquida', 'url' => [ '//ctacte/liquida/view' ] ],
				[ 'label' => 'Listado', 'url' => [ '//ctacte/listadoliquida/index' ] ],
				[ 'label' => 'Resultado' ],
			],

			//TODO colocar las columnas que se van a listar (sin las de opciones)
			'columnas' => [
				[ 'attribute' => 'ctacte_id', 'label' => 'Cod', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'obj_id', 'label' => 'Objeto', 'contentOptions' => [ 'style' => 'width: 1px; text-align: left' ] ],
				[ 'attribute' => 'obj_nom', 'label' => 'Nombre', 'contentOptions' => [ 'style' => 'width: 200px; text-align: left' ] ],
				[ 'attribute' => 'trib_nom_red', 'label' => 'Tributo', 'contentOptions' => [ 'style' => 'width: 200px; text-align: left' ] ],
				[ 'attribute' => 'anio', 'label' => 'Año', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'cuota', 'label' => 'Cuota', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'monto', 'label' => 'Monto', 'contentOptions' => [ 'style' => 'width: 1px; text-align: right' ] ],
				[ 'attribute' => 'venc2', 'label' => 'Venc.', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'est_nom', 'label' => 'Estado', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
                [ 'attribute' => 'modif', 'label' => 'Modif.', 'contentOptions' => [ 'style' => 'width: 200px; text-align: left' ] ],
				[
	                'class' => 'yii\grid\ActionColumn',
	                'contentOptions'=>['style'=>'width:40px;text-align: center'],
	                'template' => '{view}',
	                'buttons'=>[

	                    'view' => function($url, $model, $key)
	                                {
	                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['ctacte/liquida/view', 'id' => $model['ctacte_id']], ["data-pjax" => "0" ]);
	                                },

	                ],
	            ],
			],

			'urlOpciones' => '//ctacte/listadoliquida/index',

		];
	}

	/**
	 * Arreglo de campos que se mostrarán para buscar
	 */
	public function campos(){

        /**
         * TODO:
         *
         * Averiguar de dónde viene el parámetro "Eventual".
         * $condtrib = ($eventual == 1 ? "Tipo in (3,4) and not Trib_Id in (7,8)" : "tipo<>0 and tipo<>2 and tipo<>6 and Est='A'");
         */
        $condtrib = "tipo<>0 and tipo<>2 and tipo<>6 and Est='A'";

        $arrayTributo   = utb::getAux( 'trib', 'trib_id', 'nombre', 3, $condtrib );
		$arrayObjeto 	= utb::getAux( 'objeto_tipo' );
		$arrayEstado	= utb::getAux('CtaCte_TEst');
		$arrayUsuario	= utb::getAux('sam.sis_usuario','usr_id','apenom',0,"est='A'");

		return [
			[ 'tipo' => 'rangoNumero', 'label' => 'Nº Referencia', 'desde' => 'numero_desde', 'hasta' => 'numero_hasta' ],
            [ 'tipo' => 'lista', 'label' => 'Tributo', 'atributo' => 'tributo', 'elementos' => $arrayTributo ],
			[
				'tipo' => 'tablaAuxiliar', 'label' => 'Item', 'codigo' => 'item', 'nombre' => 'item_nombre',
				'busqueda' => [
					'tabla' => 'item', 'condicion' => '', 'campoCodigo' => 'item_id', 'campoNombre' => 'nombre',
				],
			],
			[
				'tipo' => 'rangoYLista', 'label' => 'Objeto', 'desde' => 'objeto_desde', 'hasta' => 'objeto_hasta',
				'atributoLista' => 'objeto_tipo', 'elementos' => $arrayObjeto, 'listaLabel' => 'Objeto', 'listaColumnas' => 2,
			],
			[ 'tipo' => 'rangoObjeto', 'label' => 'Contribuyente', 'desde' => 'contribuyente_desde', 'hasta' => 'contribuyente_hasta' ],
			['tipo' => 'rangoFecha', 'label' => 'Fecha Emisión', 'desde' => 'fecha_emision_desde', 'hasta' => 'fecha_emision_hasta' ],
			['tipo' => 'rangoFecha', 'label' => 'Fecha Venc.', 'desde' => 'fecha_venc_desde', 'hasta' => 'fecha_venc_hasta' ],
			['tipo' => 'rangoFecha', 'label' => 'Fecha Pago', 'desde' => 'fecha_pago_desde', 'hasta' => 'fecha_pago_hasta' ],
			[ 'tipo' => 'periodo', 'label' => 'Periodos', 'adesde' => 'anio_desde', 'ahasta' => 'anio_hasta', 'cdesde' => 'cuota_desde', 'chasta' => 'cuota_hasta' ],
			[ 'tipo' => 'rangoNumero', 'label' => 'Monto', 'desde' => 'monto_desde', 'hasta' => 'monto_hasta' ],
			[ 'tipo' => 'lista', 'label' => 'Estado', 'atributo' => 'estado', 'elementos' => $arrayEstado ],
			['tipo' => 'texto', 'label' => 'Expediente', 'atributo' => 'expediente', 'caracteres' => 15, 'columnas' => 8 ],
			[ 'tipo' => 'lista', 'label' => 'Usuario', 'atributo' => 'usuario', 'elementos' => $arrayUsuario ],

		];
	}

}
?>
