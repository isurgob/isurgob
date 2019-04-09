<?php
namespace app\controllers\ctacte;

use Yii;

use app\controllers\ListadoController;
use app\utils\db\utb;
use app\models\ctacte\LibredeudaListado;
use app\models\ctacte\Libredeuda;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class ListadolibredeudaController extends ListadoController{

	/**
	 * Ingresar el modelo a utilizar en la búsqueda.
	 */
	public function modelo(){
		return new LibredeudaListado();
	}

	public function datosOpciones($model){

		return [
			'breadcrumbs' => [
				['label' => 'Libre Deuda', 'url' => [ '//ctacte/LibredeudaListado/index' ] ],
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
				[ 'label' => 'Libre Deuda', 'url' => [ '//ctacte/LibredeudaListado/index' ] ],
				[ 'label' => 'Listado', 'url' => [ '//ctacte/LibredeudaListado/index' ] ],
				[ 'label' => 'Resultado' ],
			],

			//TODO colocar las columnas que se van a listar (sin las de opciones)
			'columnas' => [
				['attribute'=>'ldeuda_id','label' => 'Nro', 'contentOptions'=>['style'=>'width:40px;text-align:center']],
				['attribute'=>'obj_id','label' => 'Objeto', 'contentOptions'=>['style'=>'width:40px']],
    			['attribute'=>'obj_nom','label' => 'Nombre','contentOptions'=>['style'=>'width:400px']],
    			['attribute'=>'num_nom','label' => 'Responsable','contentOptions'=>['style'=>'width:400px;']],
    			['attribute'=>'fchemi','label' => 'Emisión','contentOptions'=>['style' => 'width: 1px; text-align: center' ], 'format' => [ 'date', 'php:d/m/Y']],
    			['attribute'=>'modif','label' => 'Modificación', 'contentOptions'=>['style'=>'width:200px']],
				[
	             'class' => 'yii\grid\ActionColumn',
				 'options'=>['style'=>'width:20px'],
				 'template' => '{delete}',
				 'buttons'=>[
				 'delete' => function($url,$model,$key)
								{
	    							if ($model['est'] == 'A')
	    								return Html::a('<span class="glyphicon glyphicon-trash"></span>',['//ctacte/libredeuda/delete','id' => $model['ldeuda_id']]);
	    						  }

	                ],
	            ],
			],

			'urlOpciones' => '//ctacte/listadolibredeuda/index',   /* ******************************controller*/

			'imprimir' => ['//ctacte/listadolibredeuda/imprimir', 'format' => 'A4-L'],

			'exportar' => [ 'Nro', 'Objeto', 'Nombre', 'Responsable', 'Emisión', 'Modificación', 'Estado' ]

		];
	}
	/**
	 * Arreglo de campos que se mostraran para buscar
	 */
	public function campos(){

        $arrayTipoObjeto	= utb::getAux( 'objeto_tipo', 'cod', 'nombre', 0, "est='A'" );
		$arrayUsuario	= utb::getAux('sam.sis_usuario','usr_id','apenom',0,"est='A'");

		return [

			[ 'tipo' => 'rangoNumero', 'label' => 'Nº', 'desde' => 'numero_desde' , 'hasta' => 'numero_hasta' ],
            [
				'tipo' => 'rangoYLista', 'label' => 'Objeto', 'desde' => 'objeto_desde', 'hasta' => 'objeto_hasta',
				'atributoLista' => 'objeto_tipo', 'elementos' => $arrayTipoObjeto, 'listaLabel' => 'Objeto', 'listaColumnas' => 2,],
			['tipo' => 'texto', 'label' => 'Contrib.', 'atributo' => 'nombre', 'columnas' => 6, 'caracteres' => 50 ],
			['tipo' => 'texto', 'label' => 'Escribano', 'atributo' => 'escribano', 'columnas' => 6, 'caracteres' => 50 ],
			['tipo' => 'rangoFecha', 'label' => 'Emisión', 'desde' => 'fecha_emi_desde', 'hasta' => 'fecha_emi_hasta' ],
			['tipo' => 'rangoFecha', 'label' => 'Modific.', 'desde' => 'fecha_modif_desde', 'hasta' => 'fecha_modif_hasta' ],
			[ 'tipo' => 'listachica', 'label' => 'Estado', 'atributo' => 'estado', 'elementos' => ['A'=>"Activo",'B'=>"Baja"] ],
			[ 'tipo' => 'listachica', 'label' => 'Usuario', 'atributo' => 'usuario', 'elementos' => $arrayUsuario ],

			];
	}

	public function menu_derecho(){

		return '//ctacte/libredeuda/menu_derecho';
	}

}

?>
