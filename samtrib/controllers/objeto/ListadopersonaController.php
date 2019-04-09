<?php

namespace app\controllers\objeto;

use app\controllers\ListadoController;
use app\utils\db\utb;
use app\models\objeto\PersonaListado;
use app\models\objeto\Persona;
use yii\helpers\Html;

class ListadopersonaController extends ListadoController{

	/**
	 * Ingresar el modelo a utilizar en la búsqueda.
	 */
	public function modelo(){
		return new PersonaListado();
	}

	public function datosOpciones($model){

		return [
			'breadcrumbs' => [
				['label' => 'Persona', 'url' => [ '//objeto/persona/view' ] ],
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
		 *	exportar		=> URL (array) a dónde se envía para exportar.
		 *	imprimir		=> URL (array) a dónde se envía para imprimir.
		 */
		 
		
		return [
			'breadcrumbs' => [
				['label' => 'Persona', 'url' => [ '//objeto/persona/view' ] ],
				[ 'label' => 'Listado', 'url' => [ '//objeto/listadopersona/index' ] ],
				[ 'label' => 'Resultado' ],
			],

			//TODO colocar las columnas que se van a listar (sin las de opciones)
			'columnas' => [
				[ 'attribute' => 'obj_id', 'label' => 'Objeto', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'nombre', 'label' => 'Nombre', 'contentOptions' => [ 'style' => 'width: 200px; text-align: left' ] ],
				[ 'attribute' => 'est_nom', 'label' => 'Est.', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'est_ib', 'label' => 'Est.IB', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'documento', 'label' => 'DNI/CUIT', 'contentOptions' => [ 'style' => 'width: 100px; text-align: left' ] ],
				[ 'attribute' => 'dompos_dir', 'label' => 'Domicilio Postal', 'contentOptions' => [ 'style' => 'width: 350px; text-align: left' ] ],
				[ 'attribute' => 'fchnac', 'label' => 'FchNac', 'contentOptions' => [ 'style' => 'width: 10px; text-align: center' ]],
				[ 'attribute' => 'nacionalidad_nom', 'label' => 'Nacionalidad', 'contentOptions' => [ 'style' => 'width: 10px; text-align: center' ] ],
				[
	                'class' => 'yii\grid\ActionColumn',
	                'contentOptions'=>['style'=>'width:40px;text-align: center'],
	                'template' => '{view}',
	                'buttons'=>[

	                    'view' => function($url, $model, $key)
	                                {
	                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['objeto/persona/view', 'id' => $model['obj_id']], ["data-pjax" => "0" ]);
	                                },

	                ],
	            ],
			],

			'urlOpciones' => '//objeto/listadopersona/index',
			
			'imprimir' => ['//objeto/listadopersona/imprimir', 'format' => 'A4-L'],
			
			'exportar' => [ 'Objeto', 'Nombre', 'Est.','Est.IB','DNI/CUIT','Domicilio Postal','FchNac','Nacionalidad'],

		];
	}

	/**
	 * Arreglo de campos que se mostrarán para buscar
	 */
	public function campos(){

		$arrayEstado 			= utb::getAux( 'objeto_test', 'cod', 'nombre', 0, 'tobj = 3', '', true );
		$arrayEstadoIB 			= utb::getAux( 'persona_test_ib', 'cod', 'nombre');
		$arrayTipoLiquidacion 	= utb::getAux( 'comer_tliq', 'cod', 'nombre', 0 );
		$arrayContador 			= utb::getAux( 'sam.usuarioweb u inner join objeto o on u.obj_id=o.obj_id', 'u.usr_id', 'o.nombre', 0, "acc_dj='S' and u.est='A'" );
		$arrayGrupoRubro 		= utb::getAux( 'rubro_grupo' );
		$arrayNacionalidad 		= utb::getAux( 'persona_tnac' );
		$arrayEstadoCivil 		= utb::getAux( 'persona_testcivil' );
		$arrayIVA 				= utb::getAux( 'comer_tiva' );
		$arrayClasificacion 	= utb::getAux( 'persona_tclasif' );
		$arrayImponibles	 	= utb::getAux( 'objeto_tipo', 'cod', 'nombre', 0, " cod IN ( 1, 2, 5 ) ANd est='A' " );
		$arrayTBajaIB           = utb::getAux( 'persona_tbajaib' );
		$arrayTipo              = utb::getAux( 'persona_tipo' );

		return [
			['tipo' => 'rangoObjeto', 'tipoObjeto' => '1', 'label' => 'Objeto', 'desde' => 'obj_id_desde', 'hasta' => 'obj_id_hasta'],
			['tipo' => 'texto', 'label' => 'Nombre', 'atributo' => 'nombre', 'columnas' => 8, 'caracteres' => 50 ],
			['tipo' => 'texto', 'label' => 'Nom. Fantasía', 'atributo' => 'nombre_fantasia', 'columnas' => 8, 'caracteres' => 50 ],
			['tipo' => 'listachica', 'label' => 'Estado', 'atributo' => 'est', 'elementos' => $arrayEstado ],
			['tipo' => 'listachica', 'label' => 'Estado IB', 'atributo' => 'est_ib', 'elementos' => $arrayEstadoIB ],
			['tipo' => 'numero', 'label' => 'Nº Documento.', 'atributo' => 'ndoc', 'columnas' => 2, 'caracteres' => 8 ],
			['tipo' => 'mascara', 'label' => 'CUIT', 'atributo' => 'cuit', 'mascara' => '99-99999999-9', 'columnas' => 2, 'caracteres' => 11 ],
			['tipo' => 'texto', 'label' => 'Ing. Brutos', 'atributo' => 'ib', 'columnas' => 2, 'caracteres' => 11 ],
			['tipo' => 'numero', 'label' => 'Nº Inscrip.', 'atributo' => 'inscrip', 'columnas' => 2, 'caracteres' => 10 ],
			['tipo' => 'texto', 'label' => 'Teléfono', 'atributo' => 'telefono', 'columnas' => 3, 'caracteres' => 15 ],
			['tipo' => 'listachica', 'label' => 'Tipo Liq.', 'atributo' => 'tipoliq', 'elementos' => $arrayTipoLiquidacion ],
			//['tipo' => 'texto', 'label' => 'Calle', 'atributo' => 'calle', 'columnas' => 4, 'caracteres' => 50 ],
			['tipo' => 'listachica', 'label' => 'Tipo', 'atributo' => 'tipo', 'elementos' => $arrayTipo ],
			['tipo' => 'listachica', 'label' => 'Nacionalidad', 'atributo' => 'nacionalidad', 'elementos' => $arrayNacionalidad ],
			['tipo' => 'listachica', 'label' => 'Estado Civil', 'atributo' => 'estadoCivil', 'elementos' => $arrayEstadoCivil ],
			['tipo' => 'listachica', 'label' => 'IVA', 'atributo' => 'iva', 'elementos' => $arrayIVA ],
			['tipo' => 'listachica', 'label' => 'Clasificación', 'atributo' => 'clasificacion', 'elementos' => $arrayClasificacion ],
			['tipo' => 'lista', 'label' => 'Contador', 'atributo' => 'contador', 'elementos' => $arrayContador ],
			['tipo' => 'lista', 'label' => 'Grupo Rubro', 'atributo' => 'rubro_grupo', 'elementos' => $arrayGrupoRubro ],
			['tipo' => 'rangoFecha', 'label' => 'Fecha Nac.', 'desde' => 'fecha_nacimiento_desde', 'hasta' => 'fecha_nacimiento_hasta' ],
			['tipo' => 'rangoFecha', 'label' => 'Fecha Alta', 'desde' => 'fecha_alta_desde', 'hasta' => 'fecha_alta_hasta' ],
			['tipo' => 'rangoFecha', 'label' => 'Fecha Modif.', 'desde' => 'fecha_modif_desde', 'hasta' => 'fecha_modif_hasta' ],
			['tipo' => 'rangoFecha', 'label' => 'Fecha Baja', 'desde' => 'fecha_baja_desde', 'hasta' => 'fecha_baja_hasta' ],
			['tipo' => 'rangoFecha', 'label' => 'Fecha Alta IB', 'desde' => 'fecha_alta_ib_desde', 'hasta' => 'fecha_alta_ib_hasta' ],
			['tipo' => 'rangoFecha', 'label' => 'Fecha Baja IB', 'desde' => 'fecha_baja_ib_desde', 'hasta' => 'fecha_baja_ib_hasta' ],
			[
				'tipo' => 'rangoYLista', 'label' => 'Imponibles', 'desde' => 'imponibles_desde', 'hasta' => 'imponibles_hasta',
				'atributoLista' => 'imponibles_tipo_objeto', 'elementos' => $arrayImponibles, 'listaLabel' => 'Tipo:', 'listaColumnas' => 2,
			],
			['tipo' => 'checkOpcion', 'atributo' => 'agrete', 'columnas' => 3 ],
			['tipo' => 'checkOpcion', 'atributo' => 'rete_manual', 'columnas' => 4 ],
			['tipo' => 'listachica', 'label' => 'Tipo Baja IB', 'atributo' => 'tbaja_ib', 'elementos' => $arrayTBajaIB ],
		];
	}

}
?>
