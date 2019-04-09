<?php

namespace app\models\objeto\avanzada; 

use Yii;

use app\utils\db\Fecha;
use app\utils\db\utb;

class PersonaBusquedaAvanzada extends BusquedaAvanzada{

	const OPCION_NOMBRE= 1;
	const OPCION_DOCUMENTO= 2;
	const OPCION_FECHA_ALTA= 3;
	const OPCION_OBJETO= 4;
	
	public $opcion;
	
	public $nombre;
	public $documento;
	public $fecha_alta_desde;
	public $fecha_alta_hasta;
	public $obj_id;
	
	public function rules(){
		
		$ret= [];
		
		$ret[]= [
				'opcion',
				'required',
				'message' => 'Elija una opción de búsqueda'
				];
		
		$ret[]= [
				'nombre',
				'required',
				'when' => function($model){return $model->opcion == self::OPCION_NOMBRE;}
				];

		$ret[]= [
				'documento',
				'required',
				'when' => function($model){return $model->opcion == self::OPCION_DOCUMENTO;}
				];
				
		$ret[]= [
				['fecha_alta_desde', 'fecha_alta_hasta'],
				'required',
				'when' => function($model){return $model->opcion == self::OPCION_FECHA_ALTA;}
				];
				
		$ret[]= [
				'nombre',
				'string',
				'max' => 50
				];
				
		$ret[]= [
				'opcion',
				'integer',
				'min' => 1,
				'max' => 4,
				'message' => 'Elija una opción de búsqueda'
				];
				
		$ret[]= [
				'documento',
				'string',
				'max' => 11
				];
				
		$ret[]= [
				['fecha_alta_desde', 'fecha_alta_hasta'],
				'date',
				'format' => 'php:Y/m/d'
				];
				
		$ret[]= [
				'obj_id',
				'string',
				'max' => 8
				];		
		
		$ret[]= [
				'obj_id',
				'required',
				'when' => function($model){return $model->opcion == self::OPCION_OBJETO;}
				];	
		
		return $ret;
	}
	
	public function beforeValidate(){
		
		if($this->opcion == self::OPCION_OBJETO){
			if (strlen($this->obj_id) < 8 && strlen($this->obj_id) > 0){
				$this->obj_id = utb::GetObjeto(3,(int)$this->obj_id);
			}
		}
		if($this->opcion == self::OPCION_FECHA_ALTA){
			
			if($this->fecha_alta_desde != null && $this->fecha_alta_desde != '')
				$this->fecha_alta_desde= Fecha::usuarioToBD($this->fecha_alta_desde);
				
			if($this->fecha_alta_hasta != null && $this->fecha_alta_hasta != '')
				$this->fecha_alta_hasta= Fecha::usuarioToBD($this->fecha_alta_hasta);
		}
		
		return true;
	}
	
	protected function sql(){

		return "Select obj_id, nombre, dompos_dir , to_char(fchalta, 'dd/mm/yyyy')," .
        		" CASE WHEN cuit IS NULL OR trim(both ' ' from cuit) = '' THEN ndoc::text" .
        		" else (substr(cuit, 1, 2) || '-' || substr(cuit, 3, 8) || '-' || substr(cuit, 11, 1))" .
        		" END AS documento FROM v_persona";
	}
	
	protected function armarCriterio(){
		
		switch($this->opcion){
			
			case self::OPCION_NOMBRE: return "upper(nombre) Like upper('%$this->nombre%')";
			case self::OPCION_DOCUMENTO: return "ndoc = '$this->documento' Or cuit = '$this->documento'";
			case self::OPCION_FECHA_ALTA: return "fchalta Between '$this->fecha_alta_desde' And '$this->fecha_alta_hasta'";
			case self::OPCION_OBJETO: return "obj_id = '$this->obj_id'";
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
			case 'nombre': return self::OPCION_NOMBRE;
			case 'documento': return self::OPCION_DOCUMENTO;
			case 'fecha_alta': return self::OPCION_FECHA_ALTA;
			case 'obj_id': return self::OPCION_OBJETO;
		}
		
		return -1;
	}
}

?>