<?php

namespace app\controllers\objeto;

use app\controllers\ListadoController;
use app\utils\db\utb;
use app\models\objeto\CemListado;
use yii\helpers\Html;

class ListadocemController extends ListadoController{

	/**
	 * Ingresar el modelo a utilizar en la búsqueda.
	 */
	public function modelo(){
		return new CemListado();
	}

	public function datosOpciones($model){

		return [
			'breadcrumbs' => [
				['label' => 'Cementerio', 'url' => [ '//objeto/cem/view' ] ],
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
				['label' => 'Cementerio', 'url' => [ '//objeto/cem/view' ] ],
				[ 'label' => 'Listado', 'url' => [ '//objeto/listadocem/index' ] ],
				[ 'label' => 'Resultado' ],
			],

			//TODO colocar las columnas que se van a listar (sin las de opciones)
			'columnas' => [
				['attribute'=>'obj_id','header' => Html::a('Objeto','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListCem",data:{order:"obj_id"},method:"POST"})']), 'contentOptions'=>['style'=>'width:40px', 'class' => 'grilla']],
				['attribute'=>'cod_ant','header' => Html::a('Cod. Ant.','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListCem",data:{order:"cod_ant"},method:"POST"})']), 'contentOptions'=>['style'=>'width:50px', 'class' => 'grilla']],
				['attribute'=>'fallecidos','header' => Html::a('Fallecidos','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListCem",data:{order:"nombre"},method:"POST"})']), 'contentOptions'=>['style'=>'width:160px;', 'class' => 'grilla']],
				['attribute'=>'cua_id','header' => Html::a('Cuadro','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListCem",data:{order:"cuadro_id"},method:"POST"})']), 'contentOptions'=>['style'=>'width:30px', 'class' => 'grilla']],
				['attribute'=>'cue_id','header' => Html::a('Cuerpo','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListCem",data:{order:"cuerpo_id"},method:"POST"})']), 'contentOptions'=>['style'=>'width:30px', 'class' => 'grilla']],
				['attribute'=>'tipo','header' => Html::a('Tipo','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListCem",data:{order:"tipo_nom"},method:"POST"})']), 'contentOptions'=>['style'=>'width:50px', 'class' => 'grilla']],
				['attribute'=>'piso','header' => Html::a('Piso','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListCem",data:{order:"piso"},method:"POST"})']), 'contentOptions'=>['style'=>'width:20px', 'class' => 'grilla']],
				['attribute'=>'fila','header' => Html::a('Fila','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListCem",data:{order:"fila"},method:"POST"})']), 'contentOptions'=>['style'=>'width:20px', 'class' => 'grilla']],
				['attribute'=>'nume','header' => Html::a('Nume','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListCem",data:{order:"nume"},method:"POST"})']), 'contentOptions'=>['style'=>'width:20px', 'class' => 'grilla']],
				['attribute'=>'cat','header' => Html::a('Cat','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListCem",data:{order:"cat"},method:"POST"})']), 'contentOptions'=>['style'=>'width:50px', 'class' => 'grilla']],
				['attribute'=>'fchingreso', 'format' => ['date', 'php:d/m/Y'], 'header' => Html::a('Ingreso','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListCem",data:{order:"fchingreso"},method:"POST"})']), 'contentOptions'=>['style'=>'width:50px', 'class' => 'grilla']],
				['attribute'=>'fchvenc', 'format' => ['date', 'php:d/m/Y'], 'header' => Html::a('Venc.','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListCem",data:{order:"fchvenc"},method:"POST"})']), 'contentOptions'=>['style'=>'width:50px', 'class' => 'grilla']],
				['attribute'=>'est','header' => Html::a('Est','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListCem",data:{order:"est"},method:"POST"})']), 'contentOptions'=>['style'=>'width:20px', 'class' => 'grilla']],

				[
	                'class' => 'yii\grid\ActionColumn',
	                'contentOptions'=>['style'=>'width:40px;text-align: center'],
	                'template' => '{view}',
	                'buttons'=>[

	                    'view' => function($url, $model, $key)
	                                {
	                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['objeto/cem/view', 'id' => $model['obj_id']], ["data-pjax" => "0" ]);
	                                },

	                ],
	            ],
			],

			'urlOpciones' => '//objeto/listadocem/index',

			'imprimir' => ['//objeto/listadocem/imprimir'],

			'exportar' => [ 'Objeto', 'Cod. Ant.', 'Fallecidos', 'Cuadro', 'Cuerpo', 'Tipo', 'Piso', 'Fila', 'Nume', 'Categoría', 'Ingreso', 'Vencimiento', 'Estado'],

		];
	}

	/**
	 * Arreglo de campos que se mostrarán para buscar
	 */
	public function campos(){

		$arrayEstado 			= utb::getAux('objeto_test', 'cod', 'nombre', 0, 'tobj=4');
		$arrayDelegacion		= utb::getAux('cem_tdeleg');
		$arrayCategoria			= utb::getAux('cem_tcat', 'cat');
		$arrayExenta			= utb::getAux('cem_texenta');
		$arrayDistribuidor		= utb::getAux('sam.sis_usuario', 'usr_id', 'apenom', 0, 'distrib<>0');
		$arrayTipo				= utb::getAux('cem_tipo');
		$arrayCuadros			= utb::getAux('cem_cuadro','cua_id','nombre');
		$arrayCuerpos			= utb::getAux('cem_cuerpo','cue_id','nombre');
		
		$nomenclatura = [
			'tipo' => 'listasYcamposVinculados',
			'label' => 'Nomenclatura',
			'cantidadCampos' => 7,
		];
		
		$nomenclatura["label1"] = "Cuadro";
		$nomenclatura["campo1"] = "cuadro_id";
		$nomenclatura["elementos1"] = $arrayCuadros;
		$nomenclatura["columnas1"] = 2;
		
		$nomenclatura["label2"] = "Cuerpo";
		$nomenclatura["campo2"] = "cue_id";
		$nomenclatura["elementos2"] = $arrayCuerpos;
		$nomenclatura["columnas2"] = 2;
		
		$nomenclatura["label3"] = "Tipo";
		$nomenclatura["campo3"] = "tipo";
		$nomenclatura["elementos3"] = $arrayTipo;
		$nomenclatura["columnas3"] = 2;
		
		$nomenclatura["label4"] = "Piso";
		$nomenclatura["campo4"] = "piso";
		$nomenclatura["columnas4"] = 1;
		
		$nomenclatura["label5"] = "Fila";
		$nomenclatura["campo5"] = "fila";
		$nomenclatura["columnas5"] = 1;
		
		$nomenclatura["label6"] = "Nume";
		$nomenclatura["campo6"] = "nume";
		$nomenclatura["columnas6"] = 1;
		
		$nomenclatura["label7"] = "BIS";
		$nomenclatura["campo7"] = "bis";
		$nomenclatura["columnas7"] = 1;

		return [
			['tipo' => 'rangoObjeto', 'tipoObjeto' => '4', 'label' => 'Objeto', 'desde' => 'obj_id_desde', 'hasta' => 'obj_id_hasta'],
			['tipo' => 'texto', 'label' => 'Nombre Titular', 'atributo' => 'nombre_titular', 'columnas' => 6, 'caracteres' => 50 ],
			['tipo' => 'texto', 'label' => 'Nom. Fallecido', 'atributo' => 'fall_nombre', 'columnas' => 6, 'caracteres' => 50 ],
			['tipo' => 'texto', 'label' => 'Doc. Fallecido', 'atributo' => 'fall_ndoc', 'columnas' => 3, 'caracteres' => 8 ],
			['tipo' => 'listachica', 'label' => 'Estado', 'atributo' => 'est', 'elementos' => $arrayEstado ],
			['tipo' => 'texto', 'label' => 'Cod.Ant.', 'atributo' => 'cod_ant', 'columnas' => 3, 'caracteres' => 15 ],
			$nomenclatura,
			['tipo' => 'listachica', 'label' => 'Delegación', 'atributo' => 'delegacion', 'elementos' => $arrayDelegacion ],
			['tipo' => 'listachica', 'label' => 'Categoría', 'atributo' => 'categoria', 'elementos' => $arrayCategoria ],
			['tipo' => 'listachica', 'label' => 'Exenta', 'atributo' => 'exenta', 'elementos' => $arrayExenta ],
			['tipo' => 'listachica', 'label' => 'Distribuidor', 'atributo' => 'distribuidor', 'elementos' => $arrayDistribuidor ],
			['tipo' => 'rangoFecha', 'label' => 'Fecha Ingreso', 'desde' => 'fchingreso_desde', 'hasta' => 'fchingreso_hasta' ],
			['tipo' => 'rangoFecha', 'label' => 'Fecha Venc.', 'desde' => 'fchvenc_desde', 'hasta' => 'fchvenc_hasta' ],
			['tipo' => 'rangoFecha', 'label' => 'Fecha Modif.', 'desde' => 'fchmodif_desde', 'hasta' => 'fchmodif_hasta' ],
			//['tipo' => 'listachica', 'label' => 'Tipo', 'atributo' => 'tipo', 'elementos' => $arrayTipo ],
			//['tipo' => 'listachica', 'label' => 'Cuadro', 'atributo' => 'cuadro_id', 'elementos' => $arrayCuadros ],
			
		];
	}

}
?>
