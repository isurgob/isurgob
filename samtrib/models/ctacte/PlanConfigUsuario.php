<?php

namespace app\models\ctacte;

use Yii;

use yii\helpers\ArrayHelper;

use app\utils\db\utb;

/**
 * @property integer usr_id
 */
class PlanConfigUsuario extends \yii\db\ActiveRecord{
	
	public $nuevos = [];
	
	
	public static function tableName(){
		return 'plan_config_usuario';
	}
	
	public function scenarios(){
		
		return [
			'default' => ['usr_id']
		];
	}
	
	public function rules(){
		
		$ret = [];
		
		$ret[] = [
				'usr_id',
				'required',
				'on' => ['default']
				];
				
		$ret[] = [
				'usr_id',
				'integer',
				'min' => 1,
				'on' => 'default'
				];
		
		return $ret;
	}
	
	/**
	 * Elimina los planes asignados al usuario y luego graba los nuevos
	 * 
	 * @return boolean - true si se logran insertar los nuevos planes para el usuario, false de lo contrario
	 */
	public function grabar(){
			
		$this->scenario = 'default';
		
		if(!$this->validate())
			return false;
			
		$usuario = $this->usr_id;
		
		$transaccion = Yii::$app->db->beginTransaction();
		
		$cmd = Yii::$app->db->createCommand();
		
		//se eliminan los planes de configuracion asignados al usuario
		$cmd->sql = "Delete From plan_config_usuario Where usr_id = $usuario";
		
		$cmd->execute();
		//se terminan de eliminar los planes de configuracion asignados al usuario
		
		
		//hay nuevos planes a insertar
		if(count($this->nuevos) > 0){
			
			//se insertan nuevos planes asignados 	
		 	$cmd = Yii::$app->db->createcommand()
				->batchInsert('plan_config_usuario', ['usr_id', 'tplan'], array_map(function($plan) use($usuario){return [$usuario, $plan];}, $this->nuevos ) );
				
			$res = $cmd->execute();
			
			if($res == false || $res = 0){
				
				$this->addError($this->usr_id, 'Ocurrio un error cuando se queria dar de alta los planes de configuracion');
	    		$transaccion->rollBack();
				return false;
			}
		}
		
		
		$transaccion->commit();
		return true;
	}
	
	/**
	 * Obtiene los planes de configuracion que NO tiene asignado el usuario
	 * 
	 * @return Array - cada elemento es de la forma # => ['cod' => #, 'nombre' => 'nombre del plan de configuracion'], donde '#' es el codigo de plan de configuracion
	 */
	public function getPlanes($usr_id = -1){
		
		$sql = "Select cod As codigo, cod, nombre From plan_config Where (current_date Between vigenciadesde And vigenciahasta Or vigenciahasta Is Null) And cod Not In " .
				" (Select tplan From plan_config_usuario Where usr_id = $usr_id)";
				
		$cmd = Yii::$app->db->createCommand($sql);
		
		$res = $cmd->queryAll();
		
		return ArrayHelper::map( $res, 'codigo', function($model){return [ 'cod' => $model['cod'], 'nombre' => $model['nombre'] ];} );
	}
	
	/**
	 * Obtiene los planes de configuracion que tiene asignado el usuario
	 * 
	 * @return Array - cada elemento es de la forma # => ['cod' => #, 'nombre' => 'nombre del plan de configuracion'], donde '#' es el codigo de plan de configuracion
	 */
	public function getPlanesAsignados($usr_id = -1){
		
		$sql = "Select cod As codigo, cod, nombre From plan_config As c Inner Join plan_config_usuario As u On c.cod = u.tplan " .
				" Where (current_date Between vigenciadesde And vigenciahasta Or vigenciahasta Is Null) And usr_id = $usr_id";
		
		$cmd = Yii::$app->db->createCommand($sql);
		
		$res = $cmd->queryAll();
		
		return ArrayHelper::map( $res, 'codigo', function($model){return [ 'cod' => $model['cod'], 'nombre' => $model['nombre'] ];} );
	}
	
	/**
	 * Obtiene los grupos de usuarios a los que puede pertenecer un usuario para tener asignados planes de configuracion
	 * 
	 * @return Array - cada elemento es de la forma # => ['gru_id' => #, 'nombre' => 'nombre del grupo de usuarios'], donde '#' es el codigo del grupo de usuarios	 
	 */
	public function getGruposUsuario(){
		
		$sql = "Select gru_id, nombre From sam.sis_grupo Where gru_id In (Select p.gru_id From sam.sis_grupo_proceso p Where p.pro_id = 3341)";
		
		$res = Yii::$app->db->createCommand($sql)->queryAll();
		
		return ArrayHelper::map($res, 'gru_id', 'nombre');
	}
	
	/**
	 * Obtiene los usuarios que pueden ser asignados a planes de configuracion
	 * 
	 * @param int $grupo - Grupo al que deben pertenecer los usuarios
	 * 
	 * @return Array - cada elemento es de la forma # => ['usr_id' => #, 'nombre' => 'nombre del usuario'], donde '#' es el codigo del usuario
	 */
	public function getUsuarios($grupo = 0){
		
		$sql = 'Select u.usr_id As codigo, u.usr_id, u.apenom From sam.sis_usuario u Inner Join sam.sis_usuario_proceso p On u.usr_id = p.usr_id' .
				' Where p.pro_id = 3341 And u.usr_id Not In (101, 102)';
		
		if($grupo > 0)
			$sql .= ' And u.grupo = ' . $grupo;
			
		$sql .= ' Group By u.usr_id, u.apenom Order By u.apenom';
		
		$cmd = Yii::$app->db->createCommand($sql);
		$res = $cmd->queryAll();
		
		return ArrayHelper::map( $res, 'codigo', function($model){return [ 'usr_id' => $model['usr_id'], 'nombre' => $model['apenom'] ];} );
	}
}
?>