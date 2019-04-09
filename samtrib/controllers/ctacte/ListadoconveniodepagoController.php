<?php
namespace app\controllers\ctacte;

use Yii;

use app\controllers\ListadoController; 
use app\utils\db\utb;
use app\models\ctacte\ConveniodepagoListado; 
use app\models\ctacte\Plan; 
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class ListadoconveniodepagoController extends ListadoController{

	/**
	 * Ingresar el modelo a utilizar en la búsqueda.
	 */
	public function modelo(){
		return new ConveniodepagoListado();
	}

	public function datosOpciones($model){

		return [
			'breadcrumbs' => [
				['label' => 'Convenio de Pago', 'url' => [ '/ctacte/convenio/plan' ] ],
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
				[ 'label' => 'Convenio de Pago', 'url' => [ '//ctacte/convenio/plan' ] ],
				[ 'label' => 'Listado', 'url' => [ '//ctacte/ConveniodepagoListado/index' ] ],
				[ 'label' => 'Resultado' ],
			],

			//TODO colocar las columnas que se van a listar (sin las de opciones)
			'columnas' => [
				['attribute'=>'plan_id','label' => 'Nro','contentOptions'=>['style'=>'width:40px;text-align:center']],
        	['attribute'=>'tplan_nom','label' => 'Tipo','contentOptions'=>['style'=>'width:180px;']],
        	['attribute'=>'obj_id','label' => 'Objeto','contentOptions'=>['style'=>'width:60px;text-align:center']],
        	['attribute'=>'resp','label' => 'Responsable','contentOptions'=>['style'=>'width:350px']],
        	['attribute'=>'nominal','label' => 'Nominal','contentOptions'=>['style'=>'width:50px;text-align:right']],
        	['attribute'=>'accesor','label' => 'Accesor','contentOptions'=>['style'=>'width:50px;text-align:right']],
        	['attribute'=>'multa','label' => 'Multa','contentOptions'=>['style'=>'width:50px;text-align:right']],
        	['attribute'=>'financia','label' => 'Financia','contentOptions'=>['style'=>'width:50px;text-align:right']],
        	['attribute'=>'est_nom','label' => 'Estado','contentOptions'=>['style'=>'width:60px']],
				[
	             'class' => 'yii\grid\ActionColumn','options'=>['style'=>'width:20px'],'template' => '{view}',
            			'buttons'=>[
							'view' => function($url,$model,$key)
            						  {
            							return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',['//ctacte/convenio/plan','id' => $model['plan_id']]);
										
            						  }

	                ],
	            ],
			],

			'urlOpciones' => '//ctacte/listadoconveniodepago/index',   /* ******************************controller*/
			
			'imprimir' => ['//ctacte/listadoconveniodepago/imprimir', 'format' => 'A4-L'],
			
			'exportar' => [ 'Nro', 'Tipo', 'Objeto', 'Responsable', 'Nominal', 'Accesor', 'Multa', 'Financia', 'Estado', 'Modificación' ],

		];
	}
	/**
	 * Arreglo de campos que se mostraran para buscar
	 */
	public function campos(){

        $arrayTipoObjeto	= utb::getAux( 'objeto_tipo', 'cod', 'nombre', 0, "est='A'" );
		$TipoConvenio    	=utb::getAux( 'plan_config', 'cod', 'nombre', 0);
		$TipoPago    		=utb::getAux( 'plan_tpago', 'cod', 'nombre', 0);
		$TipoOrigen			=utb::getAux( 'plan_torigen','cod','nombre',0,(utb::getExisteProceso(3342) ? 'Cod <=3' : 'Cod <=2'));
		$Estado    			=utb::getAux( 'plan_test', 'cod', 'nombre', 0);
		$Caja    			=utb::getAux( 'caja','caja_id','nombre',0,"Tipo > 2 and est='A'");
		$Trib    			=utb::getAux( 'trib','trib_id','nombre_redu',0,"est='A'");
		
		
		return [

			[ 'tipo' => 'rangoNumero', 'label' => 'Nº de Convenio', 'desde' => 'numero_desde' , 'hasta' => 'numero_hasta' ],
			[ 'tipo' => 'rangoNumero', 'label' => 'Convenio Anterior', 'desde' => 'numero_desde_ant' , 'hasta' => 'numero_hasta_ant' ],
            [
				'tipo' => 'rangoYLista', 'label' => 'Objeto', 'desde' => 'objeto_desde', 'hasta' => 'objeto_hasta',
				'atributoLista' => 'objeto_tipo', 'elementos' => $arrayTipoObjeto, 'listaLabel' => 'Objeto', 'listaColumnas' => 2,],
			['tipo' => 'texto', 'label' => 'Contribuyente', 'atributo' => 'nombre', 'columnas' => 6, 'caracteres' => 50 ],
			['tipo' => 'texto', 'label' => 'Responsable', 'atributo' => 'responsable', 'columnas' => 6, 'caracteres' => 50 ],
			[ 'tipo' => 'lista', 'label' => 'Tipo Convenio', 'atributo' => 'tconvenio', 'elementos' => $TipoConvenio ],	
			[ 'tipo' => 'listachica', 'label' => 'Tipo de Pago', 'atributo' => 'tpago', 'elementos' => $TipoPago ],
			[ 'tipo' => 'listachica', 'label' => 'Tipo Origen', 'atributo' => 'torigen', 'elementos' => $TipoOrigen ],
			['tipo' => 'rangoFecha', 'label' => 'Fecha de Alta', 'desde' => 'fecha_alta_desde', 'hasta' => 'fecha_alta_hasta' ],
			['tipo' => 'rangoFecha', 'label' => 'Fecha de Baja', 'desde' => 'fecha_baja_desde', 'hasta' => 'fecha_baja_hasta' ],
			['tipo' => 'rangoFecha', 'label' => 'Fecha Imputa', 'desde' => 'fecha_imp_desde', 'hasta' => 'fecha_imp_hasta' ],
			['tipo' => 'rangoFecha', 'label' => 'Fecha Decae', 'desde' => 'fecha_decae_desde', 'hasta' => 'fecha_decae_hasta' ],
			[ 'tipo' => 'rangoNumero', 'label' => 'Cuotas', 'desde' => 'numero_desde_cuota' , 'hasta' => 'numero_hasta_cuota' ],
			[ 'tipo' => 'rangoNumero', 'label' => 'Monto Cuota', 'desde' => 'numero_desde_moncuota' , 'hasta' => 'numero_hasta_moncuota' ],
			[ 'tipo' => 'rangoNumero', 'label' => 'Interés', 'desde' => 'numero_desde_interes' , 'hasta' => 'numero_hasta_interes' ],
			[ 'tipo' => 'rangoNumero', 'label' => 'Cuota Atrasada', 'desde' => 'numero_desde_cuoatrasa' , 'hasta' => 'numero_hasta_cuoatrasa' ],
			[ 'tipo' => 'listachica', 'label' => 'Estado', 'atributo' => 'est', 'elementos' => $Estado ],
			[ 'tipo' => 'listachica', 'label' => 'Caja', 'atributo' => 'caja', 'elementos' => $Caja ],
			[ 'tipo' => 'listachica', 'label' => 'Con el Tributo', 'atributo' => 'tributo', 'elementos' => $Trib ],
			['tipo' => 'checkOpcion', 'atributo' => 'con_quitas_especiales', 'columnas' => 3 ],
			['tipo' => 'checkOpcion', 'atributo' => 'modifconsint', 'columnas' => 6 ],
			['tipo' => 'checkOpcion', 'atributo' => 'resp', 'columnas' => 6 ],
			['tipo' => 'checkOpcion', 'atributo' => 'plancuotasvenc', 'columnas' => 6 ],
			];
	}
	
	

}
	
?>
