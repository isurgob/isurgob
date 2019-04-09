<?php

namespace app\models\ctacte;

use Yii;

use yii\data\ArrayDataProvider;

use app\utils\helpers\DBException;
use app\utils\db\Fecha;
use app\utils\db\utb;

class MejoraItem extends \yii\db\ActiveRecord{	

	public $item_nom;
	
	public $str_p1;
	public $str_p2;
	public $str_p3;
	public $str_p4;

	public function __construct(){
		
		parent::__construct();
	}

	public static function tableName(){
		
		return 'ctacte_liq';
	}
	
	public function rules(){
		
		$ret = [];
		
		/**
		 * CAMPOS REQUERIDOS
		 */
		$ret[] = [
				'item_id',
				'required',
				'on' => ['insert', 'calcular']
				];
		/**
		 * FIN CAMPOS REQUERIDOS
		 */

		/**
		 * TIPO Y RANGO DE DATOS
		 */
		$ret[] = [
				'item_id',
				'integer',
				'min' => 1,
				'on' => ['insert', 'calcular'],
				'message' => 'Elija un item'
				];
				
		$ret[] = [
				['param1', 'param2', 'param3', 'param4'],
				'number',
				'min' => 0,
				'on' => ['insert', 'calcular']
				];
		/**
		 * FIN TIPO Y RANGO DE DATOS
		 */

		/**
		 * VALORES POR DEFECTO
		 */
		$ret[] = [
				['param1', 'param2', 'param3', 'param4', 'monto'],
				'default',
				'value' => 0,
				'on' => ['insert', 'calcular']
				];
		/**
		* 
		*/
		

		
		return $ret;
	}
	
	public function scenarios(){
		
		return [
		
			'insert' => ['item_id', 'param1', 'param2', 'param3', 'param4', 'monto', 'item_nom'],
			'calcular' => ['item_id', 'param1', 'param2', 'param3', 'param4']
		];
	}
	
	public function attributeLabels(){
		
		return [
			'item_id' => 'Item',
			'param1' => 'Parametro 1',
			'param2' => 'Parametro 2',
			'param3' => 'Parametro 3',
			'param4' => 'Parametro 4',
			'monto' => 'Monto',
			'item_nom' => 'Nombre del item'
		];
	}
	
	public function beforeValidate(){
		
		
			
		return true;
	}
	
	public function afterValidate(){
		
		
	}
	
	public function afterFind(){
		
		//se obtiene el nombre del item
		$sql = "Select nombre From item Where item_id = $this->item_id";
		$this->item_nom = Yii::$app->db->createCommand($sql)->queryScalar();
		
		//se obtiene el nombre de cada parametro
		$sql = "Select paramnombre1 As p1, paramnombre2 As p2, paramnombre3 As p3, paramnombre4 As p4 From item_vigencia Where item_id = $this->item_id And (extract('year' from current_date) * 1000 + extract('month' from current_date)) Between perdesde And perhasta";
		$datos = Yii::$app->db->createCommand($sql)->queryOne();
		
		if($datos !== false){
			
			$this->str_p1 = $datos['p1'];
			$this->str_p2 = $datos['p2'];
			$this->str_p3 = $datos['p3'];
			$this->str_p4 = $datos['p4'];
		}
	}
	
	public function grabar(){
		
		
		$this->setScenario('insert');
		
		if(!$this->validate()) return false;
		
		$this->orden = $this->proximoOrden();
		
		
		$sql = "Insert Into ctacte_liq(ctacte_id, orden, item_id, param1, param2, param3, param4, monto)" .
				" Values($this->ctacte_id, $this->orden, $this->item_id, $this->param1, $this->param2, $this->param3, $this->param4, $this->monto)";
				
		return Yii::$app->db->createCommand($sql)->execute() > 0;
	}
	
	public function borrar(){
		
	
	}
	
	private function proximoOrden(){
		
		$sql = "Select Coalesce(Max(orden), 0) From ctacte_liq Where ctacte_id = $this->ctacte_id And item_id = $this->item_id";
		$res = Yii::$app->db->createCommand($sql)->queryScalar() + 1;
		
		return $res;
	}
	
	public function setCuentaCorriente($cuenta){
		$this->ctacte_id = $cuenta;
	}
	
	public function cargarDatos($item_id){
		
		if($item_id > 0){
			
			$this->item_id = $item_id;
			$this->afterFind();
			
			$this->param1 = 0;
			$this->param2 = 0;
			$this->param3 = 0;
			$this->param4 = 0;
			$this->monto = 0;
		}
	}
	
	public function calcular(){
		
		$this->setScenario('calcular');
		if(!$this->validate()) return false;
		
		//se obtiene el tributo del item
		$trib_id = utb::getCampo('item', "item_id = $this->item_id", 'trib_id');
		
		if($trib_id > 0){
			//preiodo actual del tributo
			$periodoActual = utb::PerActual($trib_id);
			
			//se obtiene el tipo de calculo
			$tcalculo = utb::getCampo('item_vigencia', "item_id = $this->item_id And $periodoActual between perdesde And perhasta", 'tcalculo');
			
			//se coloca el error en caso de que la formula de calculo no este definida
			if($tcalculo == 0){
				$this->addError($this->item_id, 'El tipo de cálculo es incorrecto');
				return 0;
			}
			
			//se realiza el calculo
			$sql = "Select sam.uf_item_calcular($this->item_id, Extract('year' From current_date)::integer, Extract('month' From current_date)::integer, $this->param1, $this->param2, $this->param3, $this->param4)";
			$this->monto = Yii::$app->db->createCommand($sql)->queryScalar();
		}
		
		return true;
	}
}
