<?php

namespace app\models\objeto\listado;


use app\models\Listado;

class LocalComercial extends Listado {
	
	public $codigo_comercio_desde;
	public $codigo_comercio_hasta;
	public $nombre_fantasia;
	public $rubro_codigo;
	public $rubro_nombre;
	public $nombre_titular;


	public function rules(){

		return [];
	}

	public function attributeLabels(){

		return [
			'codigo_comercio_desde' => 'Código de comercio desde',
			'codigo_comercio_hasta' => 'Código de comercio hasta',
			'nombre_fantasia' => 'Nombre de fantasia',
			'rubro_codigo' => 'Código de rubro',
			'rubro_nombre' => 'Nombre de rubro',
			'nombre_titular' => 'Responsable'
		];
	}

	public function buscar(){

	}

	public function validar(){
		return $this->validate();
	}

	public function pk(){

		return 'obj_id';
	}
}
?>