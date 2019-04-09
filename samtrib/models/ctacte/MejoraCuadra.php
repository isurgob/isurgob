<?php

namespace app\models\ctacte;

use Yii;

use yii\data\ArrayDataProvider;

use app\utils\helpers\DBException;
use app\utils\db\Fecha;
use app\utils\db\utb;


class MejoraCuadra extends \yii\db\ActiveRecord{	

	public $s1;
	public $s2;
	public $s3;
	public $manzana;

	public $str_s1;
	public $str_s2;
	public $str_s3;
	public $max_length_s1;
	public $max_length_s2;
	public $max_length_s3;
	public $max_length_manzana;
	public $numero_s1;
	public $numero_s2;
	public $numero_s3;
	public $numero_manzana;
	
	public $fchvenc;
	
	public $temp;

	public $obra_nom;

	public function __construct(){
		
		parent::__construct();
		
		$this->temp = false;
		
		$this->str_s1 = 's1';
		$this->str_s1 = 's2';
		$this->str_s1 = 's3';
		$this->max_length_s1 = $this->max_length_s2 = $this->max_length_s3 = $this->max_length_manzana = 1;
		$this->numero_s1 = $this->numero_s2 = $this->numero_s3 = $this->numero_manzana = false;
		
		//se obtienen los labels para s1, s2 y s3 y los maxlengths
		$sql = "Select campo, nombre, max_largo, solo_nro From sam.config_inm_nc";
		$datos = Yii::$app->db->createCommand($sql)->queryAll();
		
		if($datos !== false){
			
			foreach($datos as $d){
				
				if($d['campo'] == 's1'){
					$this->str_s1 = $d['nombre'];
					$this->max_length_s1 = intval($d['max_largo']);
					$this->numero_s1 = filter_var($d['solo_nro'], FILTER_VALIDATE_BOOLEAN);
				} 
				
				if($d['campo'] == 's2'){
					$this->str_s2 = $d['nombre'];
					$this->max_length_s2 = intval($d['max_largo']);
					$this->numero_s2 = filter_var($d['solo_nro'], FILTER_VALIDATE_BOOLEAN);
				} 
				
				if($d['campo'] == 's3'){
					$this->str_s3 = $d['nombre'];
					$this->max_length_s3 = intval($d['max_largo']);
					$this->numero_s3 = filter_var($d['solo_nro'], FILTER_VALIDATE_BOOLEAN);
				}
				
				if($d['campo'] == 'manz'){
					
					$this->max_length_manzana = $d['max_largo'];
					$this->numero_manzana = filter_var($d['solo_nro'], FILTER_VALIDATE_BOOLEAN);
				}  
			}
		}
	}

	public static function tableName(){
		
		return 'v_mej_cuadra';
	}
	
	public function rules(){
		
		$ret = [];
		
		/**
		 * CAMPOS REQUERIDOS
		 */
		$ret[] = [
				['s1', 's2', 's3', 'manzana'],
				'required',
				'on' => ['insert']
				];
		
		$ret[] = [
				['calle_id', 'calle_nom'],
				'required',
				'on' => ['insert'],
				'message' => 'Elija una calle'
				];
				
		$ret[] = [
				'obra_id',
				'required',
				'on' => ['generar', 'actualizarVencimiento']
				];
				
		$ret[] = [
				'cuadra_id',
				'required',
				'on' => ['generar', 'actualizarVencimiento']
				];
				
		$ret[] = [
				'fchvenc', 
				'required',
				'on' => ['actualizarVencimiento']
				];
		/**
		 * FIN CAMPOS REQUERIDOS
		 */

		/**
		 * TIPO Y RANGO DE DATOS
		 */
		if($this->numero_s1){
			
			$ret[] = [
				's1',
				'integer',
				'min' => 1,
				'max' => pow(10, $this->max_length_s1) - 1,
				'on' => ['insert']
			];
			
		} else {
			
			$ret[] = [
				's1',
				'string',
				'max' => $this->max_length_s1,
				'on' => ['insert']
				];
		}

		if($this->numero_s2){
			
			$ret[] = [
				's2',
				'integer',
				'min' => 1,
				'max' => pow(10, $this->max_length_s2) - 1,
				'on' => ['insert']
			];
			
		} else {
			
			$ret[] = [
				's2',
				'string',
				'max' => $this->max_length_s2,
				'on' => ['insert']
				];
		}
		
		if($this->numero_s3){
			
			$ret[] = [
				's3',
				'integer',
				'min' => 1,
				'max' => pow(10, $this->max_length_s3) - 1,
				'on' => ['insert']
			];
			
		} else {
			
			$ret[] = [
				's3',
				'string',
				'max' => $this->max_length_s3,
				'on' => ['insert']
				];
		}		
		
		if($this->numero_manzana){
			
			$ret[] = [
				'manzana',
				'integer',
				'min' => 1,
				'max' => pow(10, $this->max_length_manzana) - 1,
				'on' => ['insert']
			];
			
		} else {
			
			$ret[] = [
				'manzana',
				'string',
				'max' => $this->max_length_manzana,
				'on' => ['insert']
				];
		}
		
		$ret[] = [
				'calle_id',
				'integer',
				'min' => 1,
				'on' => ['insert']
				];
				
		$ret[] = [
				'obs',
				'string',
				'max' => 200,
				'on' => ['insert']
				];
				
		$ret[] = [
				['obra_id', 'cuadra_id'],
				'integer',
				'min' => 1,
				'on' => ['generar', 'actualizarVencimiento'],
				'message' => 'Elija una {attribute}'
				];
				
		$ret[] = [
				'fchvenc',
				'date',
				'format' => 'php:Y/m/d',
				'on' => ['actualizarVencimiento']
				];
		/**
		 * FIN TIPO Y RANGO DE DATOS
		 */

		/**
		 * VALORES POR DEFECTO
		 */
		$ret[] = [
				'obs',
				'default',
				'value' => '',
				'on' => ['insert']
				];
				
		$ret[] = [
				'cuadra_id',
				'default',
				'value' => 0,
				'on' => ['actualizarVencimiento']
				];

		/**
		* 
		*/
		

		
		return $ret;
	}
	
	public function scenarios(){
		
		return [
		
			'insert' => ['obra_id', 'obra_nom', 's1', 's2', 's3', 'manzana', 'calle_id', 'calle_nom', 'obs'],
			'generar' => ['obra_id', 'cuadra_id'],
			'actualizarVencimiento' => ['obra_id', 'cuadra_id', 'fchvenc']
		];
	}
	
	public function attributeLabels(){
		
		return [
			'obra_id' => 'Obra',
			'obra_nom' => 'Nombre de la obra',
			's1' => $this->str_s1,
			's2' => $this->str_s2,
			's3' => $this->str_s3,
			'manzana' => 'Manzana',
			'calle_id' => 'Calle',
			'calle_nom' => 'Nombre de calle',
			'obs' => 'Observaciones',
			'fchvenc' => 'Fecha de vencimiento',
			'cuadra_id' => 'Cuadra'
		];
	}
	
	public function beforeValidate(){
		
		if($this->getScenario() === 'actualizarVencimiento' ){
			if($this->fchvenc !== null && !empty(trim($this->fchvenc)))
				$this->fchvenc = Fecha::usuarioToBD($this->fchvenc);
		}
			
		return true;
	}
	
	public function afterValidate(){
		
		if(!$this->hasErrors()){
			
			$sql = "Select sam.uf_inm_armar_ncm('$this->s1', '$this->s2', '$this->s3', '$this->manzana')";
			$this->ncm = Yii::$app->db->createCommand($sql)->queryScalar();
		}
	}
	
	public function afterFind(){
		
		if($this->ncm !== null && !empty($this->ncm)){
			
			$sql = "Select * From sam.uf_inm_desarmar_ncm('$this->ncm')";
			$datos = Yii::$app->db->createCommand($sql)->queryOne();
			
			if($datos !== false){
				
				$this->s1 = $datos['_s1'];
				$this->s2 = $datos['_s2'];
				$this->s3 = $datos['_s3'];
				$this->manzana = $datos['_manz'];
			}
		}
		
		$this->temp = false;
	}
	
	public function grabar($obra_id){
		
		$this->setScenario('insert');
		if(!$this->validate()) return false;
		
		$sql = "Insert Into mej_cuadra(obra_id, calle_id, ncm, obs, fchmod, usrmod)" .
				" Values($obra_id, $this->calle_id, '$this->ncm', '$this->obs', current_timestamp, " . Yii::$app->user->id . ")";

		$res = Yii::$app->db->createCommand($sql)->execute() > 0;		
		return $res;
	}
	
	public function borrar(){
		
	
	}
	
	public static function buscarAv($cond, $orden = 'aju_id'){
		
		$sql = "Select * From v_ctacte_ajuste Where $cond";
		
		if(!empty($orden)) $sql .= " Order By $orden";
		
		$models = Yii::$app->db->createCommand($sql)->queryAll();
		$count = count($models);
		
		return new ArrayDataProvider([
			'allModels' => $models,
			'totalCount' => $count,
			'pagination' => [
				'pageSize' => 40
			]
		]);
	}
	
	private function proximoCodigo(){
		
		$sql = "";
		$res = Yii::$app->db->createCommand($sql)->queryScalar() + 1;
		
		return $res;
	}
	
	public function generar(){
		
		$this->setScenario('generar');
		if(!$this->validate()) return false;
		
		try{
		$sql = "Select * From sam.uf_mej_frentista_gen($this->obra_id, $this->cuadra_id, " . Yii::$app->user->id . ")";
		Yii::$app->db->createCommand()->execute();

		} catch (\Exception $e){
			
			$this->addError($this->obra_id, DBException::getMensaje($e));
			return false;
		}
		
		return true;
	}
	
	public function agregarItem($modelItem){
		
		$tributoContribucionMejora = utb::getTribInt()['contmej']; 
		
		$trans = Yii::$app->db->beginTransaction();
		
		$modelItem->calcular();
		
		//se obtienen los planes de la obra y la cuadra
		$sql = "Select Plan_Id, CtaCte_Id From Mej_Plan p Inner Join CtaCte c ";
        $sql .= "On c.Trib_Id= $tributoContribucionMejora and c.Anio=p.Plan_Id Where Obra_Id = $this->obra_id and p.Est = 'L'";
        if($this->cuadra_id > 0) $sql .= " And cuadra_id = $this->cuadra_id";

		$liquidados = Yii::$app->db->createCommand($sql)->queryAll();
		
		if(count($liquidados) === 0){
			
			$this->addError($this->obra_id, 'No hay planes liquidados en la obra/cuadra para agregar el item');
			$trans->rollBack();
			return false;
		}
		
		foreach($liquidados as $l){
			
			//se comprueba si el item ya existe en el plan
			$sql = "Select Exists (Select 1 From ctacte_liq Where ctacte_id = " . $l['ctacte_id'] . " And item_id = $modelItem->item_id)";
			$hay = Yii::$app->db->createCommand($sql)->queryScalar();
			
			if(!$hay){
				
				//se obtiene el proximo orden
				$sql = "Select Coalesce(Max(orden), 0) + 1 From ctacte_liq Where ctacte_id = " . $l['ctacte_id'] . " And item_id = $modelItem->item_id";
				$orden = Yii::$app->db->createCommand($sql)->queryScalar();
				
				//se inserta el item en la liquidacion
				$sql = "Insert Into ctacte_liq(ctacte_id, orden, item_id, param1, param2, param3, param4, monto)" .
						" Values(" . $l['ctacte_id'] . ", $orden, $modelItem->item_id, $modelItem->param1, $modelItem->param2, $modelItem->param3, $modelItem->param4, $modelItem->monto)";
						
				$res = Yii::$app->db->createCommand($sql)->execute() > 0;
				
				if(!$res){
					
					$this->addError($this->obra_id, 'Error al intentar realizar la operación');
					$trans->rollBack();
					return false;
				}
			}
		}
		
		$sql = "Select sam.uf_emision_aprobar($this->ctacte_id, $contribucionMejora, '$this->obj_id', 0, '$this->fchalta', " . Yii::$app->user->id . ")";
		Yii::$app->db->createCommand($sql)->execute();
		
		$trans->commit();
		return true;
	}
	
	public function actualizarVencimiento(){
		
		$this->setScenario('actualizarVencimiento');
		if(!$this->validate()) return false;
		
		$trans = Yii::$app->db->beginTransaction();
		
		//se obtienen todas las liquidaciones que correspondan a la obra y la cuadra
		$sql = "Select plan_id, obj_id From v_mej_plan Where obra_id = $this->obra_id And cuadra_id = $this->cuadra_id And est In ('A', 'L')";

		$modelos = Yii::$app->db->createCommand($sql)->queryAll();

		//error al no existir planes de liquidacion
		if($modelos === false || count($modelos) === 0){
			
			$this->addError($this->obra_id, 'No existen planes de liquidación para la obra y cuadra dadas');
			$trans->rollBack();
			return false;
		}
		
		//se actualiza el vencimiento de cada plan			
		$sql = "Select sam.uf_mej_plan_fecha($this->obra_id, $this->cuadra_id, '$this->fchvenc', '', 0)";
		Yii::$app->db->createCommand($sql)->execute();


		$trans->commit();
		return true;
	}
}
