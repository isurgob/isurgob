<?php

namespace app\models\objeto\avanzada;

use Yii;
use yii\base\Model;

use app\utils\db\utb;

ini_set("display_errors", "on");
error_reporting(E_ALL);

class InmuebleBusquedaAvanzada extends BusquedaAvanzada{

	const OPCION_NOMBRE= 1;
	const OPCION_DOCUMENTO= 2;
	const OPCION_NOMENCLATURA= 3;
	const OPCION_PARTIDA_PROVINCIAL= 4;

	public $opcion;
	
	
	public $nombre;
	public $documento;
	public $s1;
	public $s2;
	public $s3;
	public $manzana;
	public $partida_provincial;
	
	public $s1_valor;
	public $s2_valor;
	public $s3_valor;
	public $manzana_valor;
	
	public function __construct(){
		
		parent::__construct();
		
		$datos= utb::getAuxVarios(['sam.config_inm_nc'], ['campo', 'aplica', 'nombre', 'max_largo'], [], 0, "campo in ('manz', 's1', 's2', 's3')");
	
		$this->s1= $this->s2= $this->s3= $this->manzana= ['campo' => '', 'aplica' => false, 'nombre' => '', 'max_largo' => 1];
		
		if($datos !== false){
			foreach($datos as $d){
				
				switch($d['campo']){
					
					case 's1': $this->s1= $d; break;
					case 's2': $this->s2= $d; break;
					case 's3': $this->s3= $d; break;
					case 'manz': $this->manzana= $d; break;
				}
			}
		}
		
	}
	
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
				['s1_valor', 's2_valor', 's3_valor', 'manzana_valor'],
				'required',
				'when' => function($model){return $model->opcion == self::OPCION_NOMENCLATURA;}
				];
				
		$ret[]= [
				'partida_provincial',
				'required',
				'when' => function($model){return $model->opcion == self::OPCION_PARTIDA_PROVINCIAL;}
				];
				
		$ret[]= [
				'opcion',
				'integer',
				'min' => 1,
				'max' => 4
				];
				
				
		$ret[]= [
				'nombre',
				'string',
				'max' => 50
				];
				
		$ret[]= [
				'documento',
				'string',
				'max' => 13
				];
				
		$ret[]= [
				's1_valor',
				'string',
				'max' => $this->s1['max_largo']
				];
				
		$ret[]= [
				's2_valor',
				'string',
				'max' => $this->s2['max_largo']
				];
				
		$ret[]= [
				's3_valor',
				'string',
				'max' => $this->s3['max_largo']
				];
				
		$ret[]= [
				'manzana_valor',
				'string',
				'max' => $this->manzana['max_largo']
				];
				
		$ret[]= [
				'partida_provincial',
				'string',
				'max' => 8
				];
				
				
		$ret[]= [
				['nombre', 'documento', 's1_valor', 's2_valor', 's3_valor', 'manzana_valor', 'partida_provincial'],
				'default',
				'value' => ''
				];
		
		return $ret;
	}
	
	
	protected function sql(){
		
		return 'SELECT obj_id, parp, nombre, dompar_dir, nc_guiones, est, est_nom, regimen, ndoc FROM v_inm ';
	}
	
	protected function armarCriterio(){
		
		switch($this->opcion){
			
			case self::OPCION_NOMBRE: return "upper(nombre) Like upper('%$this->nombre%')";
			case self::OPCION_DOCUMENTO: return "ndoc = $this->documento";
			case self::OPCION_NOMENCLATURA: return "substr(nc, 0, " . ($this->s1['max_largo'] + $this->s2['max_largo'] + $this->s3['max_largo'] + $this->manzana['max_largo']) . ")" .
					" Like sam.uf_inm_armar_nc('" . $this->s1['valor'] . "', '" . $this->s2['valor'] . "', '" . $this->s3['valor'] . "', '" . $this->manzana['valor'] . "', '')";
			case self::OPCION_PARTIDA_PROVINCIAL: return "parp = $this->partida_provincial";
		}
		
		return "";
	}
	
	protected function validar(){
		return $this->validate();
	}
	
	public function ordenamientoVisual(){
		
		return ['obj_id', 'nombre'];
	}
	
	public function obtenerValorOpcion($opcion){
		
		switch($opcion){
			
			case 'nombre': return self::OPCION_NOMBRE;
			case 'documento': return self::OPCION_DOCUMENTO;
			case 'nomenclatura': return self::OPCION_NOMENCLATURA;
			case 'partida_provincial': return self::OPCION_PARTIDA_PROVINCIAL;
		}
		
		return -1;
	}
}

?>