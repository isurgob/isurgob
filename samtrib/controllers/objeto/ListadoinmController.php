<?php

namespace app\controllers\objeto;

use app\controllers\ListadoController;
use app\utils\db\utb;
use app\models\objeto\InmListado;
use app\models\objeto\Inm;
use yii\helpers\Html;

class ListadoinmController extends ListadoController{

	/**
	 * Ingresar el modelo a utilizar en la búsqueda.
	 */
	public function modelo(){
		return new InmListado();
	}

	public function datosOpciones($model){

		return [
			'breadcrumbs' => [
				['label' => 'Inmueble', 'url' => [ '//objeto/inm/view' ] ],
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
				['label' => 'Inmueble', 'url' => [ '//objeto/inm/view' ] ],
				[ 'label' => 'Listado', 'url' => [ '//objeto/listadoinm/index' ] ],
				[ 'label' => 'Resultado' ],
			],

			//arporigen,nombre,dompar_dir,nc_guiones,est,regimen,urbsub_nom,tinm_nom,uso_nom,supt,supm,avalt,avalm,zonat_nom,zonav_nom
			//TODO colocar las columnas que se van a listar (sin las de opciones)
			'columnas' => [
				[ 'attribute' => 'obj_id', 'label' => 'Objeto', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'parp', 'label' => 'Par.P', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'parporigen', 'label' => 'Par.O', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'uf', 'label' => 'UF', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'nombre', 'label' => 'Nombre', 'contentOptions' => [ 'style' => 'width: 250px; text-align: left' ] ],
				[ 'attribute' => 'dompar_dir', 'label' => 'Domiciliio', 'contentOptions' => [ 'style' => 'width: 200px; text-align: left' ] ],
				[ 'attribute' => 'nc_guiones', 'label' => 'Nomenclatura', 'contentOptions' => [ 'style' => 'width: 120px; text-align: center' ] ],
				[ 'attribute' => 'nc_ant', 'label' => 'NC Ant.', 'contentOptions' => [ 'style' => 'width: 120px; text-align: center' ] ],
				[ 'attribute' => 'est_nom', 'label' => 'Estado', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[ 'attribute' => 'regimen', 'label' => 'Reg.', 'contentOptions' => [ 'style' => 'width: 1px; text-align: center' ] ],
				[
	                'class' => 'yii\grid\ActionColumn',
	                'contentOptions'=>['style'=>'width:40px;text-align: center'],
	                'template' => '{view}',
	                'buttons'=>[

	                    'view' => function($url, $model, $key)
	                                {
	                                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['objeto/inm/view', 'id' => $model['obj_id']], ["data-pjax" => "0" ]);
	                                },

	                ],
	            ],
			],

			'urlOpciones' => '//objeto/listadoinm/index',

			'imprimir' => ['//objeto/listadoinm/imprimir', 'format' => 'A4-L'],

			'exportar' => ['Objeto', 'Par.Prov', 'Par.Orig', 'UF', 'Nombre', 'Domiciliio', 'Nomenclatura', 'NC Ant.', 'Estado', 'Reg.'],

		];
	}

	/**
	 * Arreglo de campos que se mostrarán para buscar
	 */
	public function campos(){

		$arrayEstado 		= utb::getAux( 'objeto_test', 'cod', 'nombre', 0, 'tobj=1', '', true );
		$arrayRegimen		= utb::getAux('inm_tregimen');
		$arrayTipo			= utb::getAux('inm_tipo');
		$arrayTitularidad	= utb::getAux('inm_ttitularidad');
		$arrayUso 			= utb::getAux('inm_tuso');
		$arrayUrbSub 		= utb::getAux('inm_turbsub');
		$arrayBarrio 		= utb::getAux('domi_barrio','barr_id');
		$arrayZonaTrib		= utb::getAux('inm_tzonat');
		$arrayZonaVal 		= utb::getAux('inm_tzonav');
		$arrayZonaOP 		= utb::getAux('inm_tzonaop');
		$arrayDistribuidor	= utb::getAux('sam.sis_usuario','usr_id','apenom',0,'distrib<>0','',true);
		$arrayTipoDistrib	= utb::getAux('objeto_tdistrib');
		$arrayServ	 		= utb::getAux('inm_tserv');
		$arrayPav	 		= utb::getAux('domi_tpav');
		$arrayAlum	 		= utb::getAux('inm_talum');
		$arrayPatrimonio 	= utb::getAux('inm_tpatrimonio');
		$arrayUsuarios 		= utb::getAux('sam.sis_usuario', 'usr_id', 'apenom', 0, "est = 'A'");
		$arrayLabelsNomen	= ( new Inm() )->arregloLabelsSinMapeo();

		$nomenclatura = [
			'tipo' => 'campos',
			'label' => 'Nomenclatura',
			'cantidadCampos' => 6,
		];

		$i = 1;

		foreach( $arrayLabelsNomen as $array ){

			$nomenclatura["label$i"] = $array['nombre'];
			$nomenclatura["campo$i"] = $array['cod'];

			$i++;
		}

		$nomenclatura["label$i"] = "UF";
		$nomenclatura["campo$i"] = "uf";

		return [
			['tipo' => 'rangoObjeto', 'tipoObjeto' => '1', 'label' => 'Objeto', 'desde' => 'obj_id_desde', 'hasta' => 'obj_id_hasta'],
			['tipo' => 'texto', 'label' => 'Nombre', 'atributo' => 'nombre', 'columnas' => 8, 'caracteres' => 50 ],
			['tipo' => 'texto', 'label' => 'Comprador', 'atributo' => 'comprador', 'columnas' => 8, 'caracteres' => 50 ],
			['tipo' => 'busquedaObjeto', 'tipoObjeto' => '1', 'label' => 'Responsable', 'atributo' => 'responsable'],
			['tipo' => 'listachica', 'label' => 'Estado', 'atributo' => 'est', 'elementos' => $arrayEstado ],
			['tipo' => 'texto', 'label' => 'Matrícula', 'atributo' => 'matricula', 'columnas' => 2, 'caracteres' => 10 ],
			['tipo' => 'rangoNumero', 'label' => 'Part. Prov.', 'desde' => 'part_prov_desde', 'hasta' => 'part_prov_hasta'],
			['tipo' => 'rangoNumero', 'label' => 'Part. Origen', 'desde' => 'part_origen_desde', 'hasta' => 'part_origen_hasta'],
			['tipo' => 'rangoNumero', 'label' => 'Plano.', 'desde' => 'plano_desde', 'hasta' => 'plano_hasta'],
			$nomenclatura,
			['tipo' => 'texto', 'label' => 'NC Ant.', 'atributo' => 'nc_ant', 'columnas' => 3, 'caracteres' => 26 ],
			// [
			// 	'tipo' => 'textoConRango', 'label' => 'Dom. Parcel', 'atributo' => 'dom_parcel', 'columnas' => 6, 'caracteres' => 50,
			// 	'rangoLabel' => 'Puerta', 'rangoDesde' => 'dom_parcel_puerta_desde', 'rangoHasta' => 'dom_parcel_puerta_hasta',
			// ],
			// [
			// 	'tipo' => 'textoConRango', 'label' => 'Dom. Postal', 'atributo' => 'dom_postal', 'columnas' => 6, 'caracteres' => 50,
			// 	'rangoLabel' => 'Puerta', 'rangoDesde' => 'dom_postal_puerta_desde', 'rangoHasta' => 'dom_postal_puerta_hasta',
			// 	'rangoColumnas' => 1,
			// ],
			// ['tipo' => 'texto', 'label' => 'Frente Calle', 'atributo' => 'frente_calle', 'columnas' => 8, 'caracteres' => 50 ],

			['tipo' => 'listachica', 'label' => 'Régimen', 'atributo' => 'regimen', 'elementos' => $arrayRegimen ],
			['tipo' => 'lista', 'label' => 'Tipo', 'atributo' => 'tipo', 'elementos' => $arrayTipo ],
			['tipo' => 'lista', 'label' => 'Titularidad', 'atributo' => 'titularidad', 'elementos' => $arrayTitularidad ],
			['tipo' => 'lista', 'label' => 'Uso', 'atributo' => 'uso', 'elementos' => $arrayUso ],
			['tipo' => 'listachica', 'label' => 'Urb. Sub.', 'atributo' => 'urb_sub', 'elementos' => $arrayUrbSub ],
			['tipo' => 'listachica', 'label' => 'Barrio', 'atributo' => 'barrio', 'elementos' => $arrayBarrio ],
			['tipo' => 'lista', 'label' => 'Zona Trib.', 'atributo' => 'zona_trib', 'elementos' => $arrayZonaTrib ],
			['tipo' => 'listachica', 'label' => 'Zona Val.', 'atributo' => 'zona_val', 'elementos' => $arrayZonaVal ],
			['tipo' => 'listachica', 'label' => 'Zona OP', 'atributo' => 'zona_op', 'elementos' => $arrayZonaOP ],
			['tipo' => 'lista', 'label' => 'Distribuidor', 'atributo' => 'distribuidor', 'elementos' => $arrayDistribuidor ],
			['tipo' => 'listachica', 'label' => 'Tipo Distrib.', 'atributo' => 'tipo_distribucion', 'elementos' => $arrayTipoDistrib ],
			// Servicios
			['tipo' => 'listachica', 'label' => 'Agua', 'atributo' => 'agua', 'elementos' => $arrayServ ],
			['tipo' => 'listachica', 'label' => 'Cloaca', 'atributo' => 'cloaca', 'elementos' => $arrayServ ],
			['tipo' => 'listachica', 'label' => 'Gas', 'atributo' => 'gas', 'elementos' => $arrayServ ],
			['tipo' => 'listachica', 'label' => 'Alumbrado', 'atributo' => 'alum', 'elementos' => $arrayAlum ],
			['tipo' => 'listachica', 'label' => 'Pavimento', 'atributo' => 'pav', 'elementos' => $arrayPav ],

			['tipo' => 'listachica', 'label' => 'Patrimonio', 'atributo' => 'patrimonio', 'elementos' => $arrayPatrimonio ],
			['tipo' => 'listachica', 'label' => 'Usuario', 'atributo' => 'usuario', 'elementos' => $arrayUsuarios ],
			['tipo' => 'rangoNumero', 'label' => 'Sup. Terreno.', 'desde' => 'sup_terreno_desde', 'hasta' => 'sup_terreno_hasta'],
			['tipo' => 'rangoNumero', 'label' => 'Avalúo Terreno', 'desde' => 'aval_terreno_desde', 'hasta' => 'aval_terreno_hasta'],
			['tipo' => 'rangoNumero', 'label' => 'Sup. Mejora.', 'desde' => 'sup_mejora_desde', 'hasta' => 'sup_mejora_hasta'],
			['tipo' => 'rangoFecha', 'label' => 'Fecha Alta', 'desde' => 'fechaAlta_desde', 'hasta' => 'fechaAlta_hasta'],
		];
	}

}
?>
