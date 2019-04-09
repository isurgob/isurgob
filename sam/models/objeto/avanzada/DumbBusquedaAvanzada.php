<?php

namespace app\models\objeto\avanzada;

class DumbBusquedaAvanzada extends BusquedaAvanzada{
	
	public function armarCriterio(){return "";}
	public function sql(){return "";}
	public function obtenerValorOpcion($opcion){return -1;}
	public function validar(){return false;}
	public function ordenamientoVisual(){return [];}
}

?>