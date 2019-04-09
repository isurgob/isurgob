<?php

namespace app\models\objeto\avanzada;

use Yii;

class ComerBusquedaAvanzada extends BusquedaAvanzada{

	const OPCION_NOMBRE_FANTASIA= 1;
	const OPCION_CUIT= 2;
	const OPCION_NOMBRE_RESPONSABLE= 3;
	const OPCION_INGRESOS_BRUTOS= 4;

	
	public $opcion;
	
	
	public $nombre_fantasia;
	public $cuit;
	public $nombre_responsable;
	public $ingresos_brutos;
	
	public function rules(){
		
		$ret= [];
		
		$ret[]= [
				'nombre_fantasia',
				'required',
				'when' => function($model){return $model->opcion == self::OPCION_NOMBRE_FANTASIA;}
				];
				
		$ret[]= [
				'cuit',
				'required',
				'when' => function($model){return $model->opcion == self::OPCION_CUIT;}
				];
				
		$ret[]= [
				'nombre_responsable',
				'required',
				'when' => function($model){return $model->opcion == self::OPCION_NOMBRE_RESPONSABLE;}
				];
				
		$ret[]= [
				'ingresos_brutos',
				'required',
				'when' => function($model){return $model->opcion == self::OPCION_INGRESOS_BRUTOS;}
				];
				
		$ret[]= [
				'opcion',
				'required',
				'message' => 'Elija una opción de búsqueda'
				];
				
		$ret[]= [
				'opcion',
				'in',
				'range' => [1, 2, 3, 4],
				'message' => 'Elija una opción valida'
				];

		$ret[]= [
				['nombre_fantasia', 'nombre_responsable'],
				'string',
				'max' => 50
				];
		
		$ret[]= [
				'cuit',
				'string',
				'max' => 13
				];
				
		$ret[]= [
				'ingresos_brutos',
				'string',
				'max' => 11
				];
				
				
		$ret[]= [
				['nombre_fantasia', 'nombre_responsable', 'cuit'],
				'default',
				'value' => ''
				];
		
		return $ret;
	}
	
	protected function sql(){

		return "Select obj_id, nombre, num_nom, est, ib, dompar_dir" .
			", ((substr(cuit, 1, 2) || '-' || substr(cuit, 3, 8) || '-' || substr(cuit, 11, 1))) As cuit" .
			" From v_comer";
	}
	
	protected function armarCriterio(){
		
		switch($this->opcion){
			
			case self::OPCION_NOMBRE_FANTASIA: return "upper(nombre) Like upper('%$this->nombre_fantasia%')";
			case self::OPCION_CUIT: return "cuit = '$this->cuit'";
			case self::OPCION_NOMBRE_RESPONSABLE: return "upper(num_nom) Like upper('%$this->nombre_responsable%')";
			case self::OPCION_INGRESOS_BRUTOS: return "ib = $this->ingresos_brutos";
		}
		
		return "";
	}
	
	protected function validar(){
		return $this->validate();
	}
	
	public function ordenamientoVisual(){
		return ['obj_id'];
	}
	
	public function obtenerValorOpcion($opcion){
		
		switch($opcion){
			case 'nombre_fantasia': return self::OPCION_NOMBRE_FANTASIA;
			case 'cuit': return self::OPCION_CUIT;
			case 'nombre_responsable': return self::OPCION_NOMBRE_RESPONSABLE;
			case 'ingresos_brutos': return self::OPCION_INGRESOS_BRUTOS;
		}
		
		return -1;
	}
}

?>