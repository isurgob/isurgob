<?php

namespace app\models;


abstract class Listado extends \yii\db\ActiveRecord{

	const SCENARIO_BUSCAR= 'listadoBuscar';

	/**
	* Realiza la busqueda en la base de datos y retorna los resultados
	*/
	abstract function buscar();

	/**
	*
	*/
	abstract function validar();

	/**
	* Claves primarias del modelo
	*/
	abstract function pk();

	public function sort(){

		return [];
	}
	
	public function titulo(){

		return "Listado";
	}

	public function permiso(){

		return 6500;
	}

	/**
	* Scenario a utilizar para validar los datos antes de buscar
	*/
	public function scenarioBuscar(){
		return self::SCENARIO_BUSCAR;
	}
}
?>
