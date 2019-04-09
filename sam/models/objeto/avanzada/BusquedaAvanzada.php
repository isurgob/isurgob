<?php

namespace app\models\objeto\avanzada;

use Yii;
use yii\base\Model;

abstract class BusquedaAvanzada extends Model{
	
	abstract protected function armarCriterio();
	abstract protected function sql();
	abstract protected function validar();
	abstract function obtenerValorOpcion($opcion);
	abstract function ordenamientoVisual();
	
	protected function orden(){
		
		return "obj_id";
	}

	public function buscar(){
		
		if(!$this->validar()) return false;
		
		$sql= $this->sql();
		$condicion= $this->armarCriterio();
		
		if($condicion != null && $condicion != '') $sql .= " Where $condicion";

		$sql .= " Order By " . $this->orden();
		$modelos= Yii::$app->db->createCommand($sql)->queryAll();
		
		return $modelos === false ? [] : $modelos; 
	}
}
?>