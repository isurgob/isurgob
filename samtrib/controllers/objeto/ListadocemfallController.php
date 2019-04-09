<?php

namespace app\controllers\objeto;

use app\controllers\ListadoController;
use app\utils\db\utb;
use app\models\objeto\CemFallListado;
use yii\helpers\Html;

class ListadocemfallController extends ListadoController{

	/**
	 * Ingresar el modelo a utilizar en la búsqueda.
	 */
	public function modelo(){
		return new CemFallListado();
	}

	public function datosOpciones($model){

		return [
			'breadcrumbs' => [
				['label' => 'Fallecido', 'url' => [ '//objeto/cem/viewfall' ] ],
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
				['label' => 'Fallecido', 'url' => [ '//objeto/cem/viewfall' ] ],
				[ 'label' => 'Listado', 'url' => [ '//objeto/listadocemfall/index' ] ],
				[ 'label' => 'Resultado' ],
			],

			//TODO colocar las columnas que se van a listar (sin las de opciones)
			'columnas' => [
				['attribute' => 'fall_id', 'label' => 'Cod'],
				['attribute' => 'obj_id', 'label' => 'Objeto'],
				['attribute' => 'cua_id', 'label' => 'Cuadro'],
				['attribute' => 'cue_id', 'label' => 'Cuerpo'],
				['attribute' => 'tipo_nom', 'label' => 'Tipo'],
				['attribute' => 'piso', 'label' => 'Piso'],
				['attribute' => 'fila', 'label' => 'Fila'],
				['attribute' => 'nume', 'label' => 'Nume'],
				['attribute' => 'apenom', 'label' => 'Nombre fallecido'],
				['attribute' => 'est', 'label' => 'Estado'],
				['attribute' => 'sexo', 'label' => 'Sexo'],
				['attribute' => 'fchinh', 'label' => 'Inhum.', 'format' => ['date', 'php:d/m/Y']],
						 
				[
	                'class' => 'yii\grid\ActionColumn',
	                'contentOptions'=>['style'=>'width:40px;text-align: center'],
	                'template' => '{view}',
	                'buttons'=>[

	                    'view' => function($url, $model, $key)
	                                {
	                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['objeto/cem/viewfall', 'id' => $model['fall_id']], ["data-pjax" => "0" ]);
	                                },

	                ],
	            ],
			],

			'urlOpciones' => '//objeto/listadocemfall/index',
			
			'imprimir' => ['//objeto/listadocemfall/imprimir'],
			
			'exportar' => [ 'Cod', 'Objeto', 'Cuadro', 'Cuerpo', 'Tipo', 'Piso', 'Fila', 'Nume','Nombre fallecido','Estado','Sexo','Inhum.'],

		];
	}

	/**
	 * Arreglo de campos que se mostrarán para buscar
	 */
	public function campos(){

		$arrayEstado 			= utb::getAux('cem_fall_test', 'cod', 'nombre');
		$arrayNacionalidad		= utb::getAux('persona_tnac');
		$arrayEstadoCivil		= utb::getAux('persona_testcivil');
		$arraySexo				= utb::getAux('persona_tsexo');
		$arrayEmpresaFunebre	= utb::getAux('cem_tfunebre');
		$arrayProcedencia		= utb::getAux('domi_localidad', 'loc_id');
		
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
		$nomenclatura["campo2"] = "cuerpo_id";
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
			['tipo' => 'rangoNumero', 'label' => 'Código', 'desde' => 'codigo_desde', 'hasta' => 'codigo_hasta' ],
			['tipo' => 'rangoObjeto', 'tipoObjeto' => '4', 'label' => 'Objeto', 'desde' => 'obj_id_desde', 'hasta' => 'obj_id_hasta'],
			$nomenclatura,
			['tipo' => 'listachica', 'label' => 'Estado', 'atributo' => 'est', 'elementos' => $arrayEstado ],
			['tipo' => 'texto', 'label' => 'Nº Documento', 'atributo' => 'documento', 'columnas' => 3, 'caracteres' => 15 ],
			['tipo' => 'texto', 'label' => 'Nombre Fallec.', 'atributo' => 'nombre_fallecido', 'columnas' => 8, 'caracteres' => 50 ],
			['tipo' => 'texto', 'label' => 'Nombre Titular', 'atributo' => 'nombre_titular', 'columnas' => 8, 'caracteres' => 50 ],
			['tipo' => 'listachica', 'label' => 'Nacionalidad', 'atributo' => 'nacionalidad', 'elementos' => $arrayNacionalidad ],
			['tipo' => 'listachica', 'label' => 'Estado Civil', 'atributo' => 'estadocivil', 'elementos' => $arrayEstadoCivil ],
			['tipo' => 'texto', 'label' => 'Acta Defun.', 'atributo' => 'actadefuncion', 'columnas' => 8, 'caracteres' => 15 ],
			['tipo' => 'listachica', 'label' => 'Sexo', 'atributo' => 'sexo', 'elementos' => $arraySexo ],
			['tipo' => 'rangoNumero', 'label' => 'Edad', 'desde' => 'edad_desde', 'hasta' => 'edad_hasta' ],
			['tipo' => 'rangoFecha', 'label' => 'Fecha Nac.', 'desde' => 'fchnac_desde', 'hasta' => 'fchnac_hasta' ],
			['tipo' => 'rangoFecha', 'label' => 'Fecha Inhuma.', 'desde' => 'fchinh_desde', 'hasta' => 'fchinh_hasta' ],
			['tipo' => 'listachica', 'label' => 'Emp. Fúnebre', 'atributo' => 'empresa_funebre', 'elementos' => $arrayEmpresaFunebre ],
			['tipo' => 'listachica', 'label' => 'Procedencia', 'atributo' => 'procedencia', 'elementos' => $arrayProcedencia ],
		];
	}

}
?>
