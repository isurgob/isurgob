<?php

namespace app\models\objeto\avanzada;

use Yii;

use app\utils\db\Fecha;

class RodadoBusquedaAvanzada extends BusquedaAvanzada{

	const OPCION_NOMBRE_RESPONSABLE= 1;
	const OPCION_NUMERO_MOTOR= 2;
	const OPCION_DOMINIO= 3;
	const OPCION_NUMERO_CHASIS= 4;
	const OPCION_FECHA_COMPRA= 5;

	
	public $opcion;
	
	
	public $nombre_responsable;
	public $numero_motor;
	public $dominio;
	public $numero_chasis;
	public $fecha_compra_desde;
	public $fecha_compra_hasta;
	
	public function rules(){
		
		$ret= [];
		
		$ret[]= [
				'opcion',
				'required',
				'message' => 'Elija una opción de búsqueda'
				];
		
		$ret[]= [
				'nombre_responsable',
				'required',
				'when' => function($model){return $model->opcion == self::OPCION_NOMBRE_RESPONSABLE;}
				];
				
		$ret[]= [
				'numero_motor',
				'required',
				'when' => function($model){return $model->opcion == self::OPCION_NUMERO_MOTOR;}
				];
				
		$ret[]= [
				'dominio',
				'required',
				'when' => function($model){return $model->opcion == self::OPCION_DOMINIO;}
				];
				
		$ret[]= [
				'numero_chasis',
				'required',
				'when' => function($model){return $model->opcion == self::OPCION_NUMERO_CHASIS;}
				];
				
		$ret[]= [
				['fecha_compra_desde', 'fecha_compra_hasta'],
				'required',
				'when' => function($model){return $model->opcion == self::OPCION_FECHA_COMPRA;}
				];
				
		$ret[]= [
				'opcion',
				'integer',
				'min' => 1,
				'max' => 5,
				'message' => 'Elija una opción de búsqueda'
				];
		
		$ret[]= [
				'nombre_responsable',
				'string',
				'max' => 50
				];
				
		$ret[]= [
				['numero_motor', 'numero_chasis'],
				'string',
				'max' => 30
				];
				
		$ret[]= [
				'dominio',
				'string',
				'max' => 9
				];
				
		$ret[]= [
				['fecha_compra_desde', 'fecha_compra_hasta'],
				'date',
				'format' => 'php:Y/m/d'
				];
				
		$ret[]= [
				['nombre_responsable', 'numero_motor', 'numero_chasis', 'dominio'],
				'default',
				'value' => ''
				];		
		
		return $ret;
	}
	
	public function beforeValidate(){
		
		if($this->opcion == self::OPCION_FECHA_COMPRA){
			
			if($this->fecha_compra_desde != null && $this->fecha_compra_desde != '')
				$this->fecha_compra_desde= Fecha::usuarioToBD($this->fecha_compra_desde);
				
			if($this->fecha_compra_hasta != null && $this->fecha_compra_hasta != '')
				$this->fecha_compra_hasta= Fecha::usuarioToBD($this->fecha_compra_hasta);
		}
		
		return true;
	}
	
	protected function sql(){

		return "Select obj_id, nombre, dominio, num_nom, cat_nom, marca_nom, modelo_nom, anio, cilindrada, est, cat_nom, marca_nom, modelo_nom, anio, dominioant, nromotor, nrochasis, cilindrada, deleg_nom From v_rodado";
	}
	
	protected function armarCriterio(){
		
		switch($this->opcion){
			
			case self::OPCION_NOMBRE_RESPONSABLE: return "upper(num_nom) Like '%$this->nombre_responsable%'";
			case self::OPCION_NUMERO_MOTOR: return "nromotor = '$this->numero_motor'";
			case self::OPCION_DOMINIO: return "dominio = '$this->dominio'";
			case self::OPCION_NUMERO_CHASIS: return "nrochasis = '$this->numero_chasis'";
			case self::OPCION_FECHA_COMPRA: return "fchcompra Between '$this->fecha_compra_desde' And '$this->fecha_compra_hasta'";
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
			case 'nombre_responsable': return self::OPCION_NOMBRE_RESPONSABLE;
			case 'numero_motor': return self::OPCION_NUMERO_MOTOR;
			case 'dominio': return self::OPCION_DOMINIO;
			case 'numero_chasis': return self::OPCION_NUMERO_CHASIS;
			case 'fecha_compra': return self::OPCION_FECHA_COMPRA;
		}
		
		return -1;
	}
}

?>