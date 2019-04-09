<?php

namespace app\models\objeto\avanzada;

use Yii;

class CementerioBusquedaAvanzada extends BusquedaAvanzada{

	const OPCION_NOMENCLATURA= 1;
	const OPCION_NOMBRE_FALLECIDO= 2;
	const OPCION_NOMBRE_RESPONSABLE= 3;
	
	public $opcion;
	
	public $tipo;
	public $cuadro;
	public $cuerpo;
	public $fila;
	public $nume;
	public $nombre_fallecido;
	public $nombre_responsable;

	
	public function rules(){
		
		$ret= [];
		
		$ret[]= [
				'opcion',
				'required',
				'message' => 'Elija una opción de búsqueda'
				];
		
		$ret[]= [
				'nombre_fallecido',
				'required',
				'when' => function($model){return $model->opcion == self::OPCION_NOMBRE_FALLECIDO;}
				];
		
		$ret[]= [
				'nombre_responsable',
				'required',
				'when' => function($model){return $model->opcion == self::OPCION_NOMBRE_RESPONSABLE;}
				];
				
		$ret[]= [
				['nombre_fallecido', 'nombre_responsable'],
				'string',
				'max' => 50
				];
				
		$ret[]= [
				'opcion',
				'integer',
				'min' => 1,
				'max' => 3
				];
				
		$ret[]= [
				['cuadro', 'cuerpo'],
				'string',
				'max' => 3
				];
				
		$ret[]= [
				'tipo',
				'string',
				'max' => 2
				];
				
		$ret[]= [
				'nume',
				'integer',
				'min' => 0
				];		
		
		$ret[]= [
				'nume',
				'default',
				'value' => 0
				];
				
		$ret[]= [
				['tipo', 'cuadro', 'cuerpo', 'fila', 'nombre_fallecido', 'nombre_responsable'],
				'default',
				'value' => ''
				];
		
		return $ret;
	}
	
	protected function sql(){

		return "Select * From v_cem";
	}
	
	protected function armarCriterio(){
		
		switch($this->opcion){
			
			case self::OPCION_NOMENCLATURA: 
			
				$condicion= "";
			
				if($this->tipo != "") $condicion= "tipo = '$this->tipo'";
				
				if($this->cuadro != ""){
					$c= "cuadro_id = '$this->cuadro'";
					$condicion .= $condicion == "" ? $c : " And $c";
				}
				
				if($this->cuerpo != ""){
					$c= "cuerpo_id = '$this->cuerpo'";
					$condicion .= $condicion == "" ? $c : " And $c";
				}
				
				if($this->fila != ""){
					$c= "fila = '$this->fila'";
					$condicion .= $condicion == "" ? $c : " And $c";
				}
				
				if($this->nume > 0){
					$c= "nume = $this->nume";
					$condicion .= $condicion == "" ? $c : " And $c";
				}				
				
				return $condicion;
				
			case self::OPCION_NOMBRE_FALLECIDO: return "obj_id In (Select obj_id From v_cem_fall Where upper(apenom) Like upper('%$this->nombre_fallecido%'))";
			case self::OPCION_NOMBRE_RESPONSABLE: return "obj_id In (Select obj_id From v_cem_fall Where upper(apenom) Like upper('%$this->nombre_responsable%'))";
			
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
			
			case 'nomenclatura': return self::OPCION_NOMENCLATURA;
			case 'nombre_fallecido': return self::OPCION_NOMBRE_FALLECIDO;
			case 'nombre_responsable': return self::OPCION_NOMBRE_RESPONSABLE;
		}
		
		return -1;
	}
}

?>