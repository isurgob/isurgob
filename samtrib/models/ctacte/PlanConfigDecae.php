<?php

namespace app\models\ctacte;

use Yii;

/**
 * @property integer tplan
 * @property integer origen
 * @property integer tpago
 * @property integer caja_id
 * @property integer cant_atras
 * @property integer cant_const
 * @property string fchmod
 * @property integer usrmod
 */
class PlanConfigDecae extends \yii\db\ActiveRecord{
	
	public static function tableName()
	{
		return 'plan_config_decaer';
	}
	
	
	public function scenarios()
	{
		return [
			'select' => ['tplan', 'origen', 'tpago', 'caja_id'],
			'insert' => ['tplan', 'origen', 'tpago', 'caja_id', 'cant_atras', 'cant_cons'],
			'update' => ['tplan', 'origen', 'tpago', 'caja_id', 'cant_atras', 'cant_cons'],
			'delete' => ['tplan', 'origen', 'tpago', 'caja_id']
		];
	}
	
	public function rules()
	{
		$ret = [];
		$indice = 0;
		
		/*
		 * CAMPOR REQUERIDOS
		 */
		$ret[$indice++] = [
				['tplan', 'origen', 'tpago'],
				'required',
				'on' => ['select', 'insert', 'update', 'delete']
				];
				
		$ret[$indice++] = [
						'caja_id',
						'required',
						'on' => ['select', 'update', 'delete']
						];
				
		$ret[$indice++] = [
						'caja_id',
						'required',
						'when' => function($model){return $model->tpago == 3;},
						'on' => ['insert', 'update']
						];
				
		$ret[$indice++] = [
				['cant_atras', 'cant_cons'],
				'required',
				'on' => ['insert', 'update']
				];
		/*
		 * FIN CAMPOS REQUERIDOS
		 */
		 
		/*
		 * TIPO Y RANGO DE DATOS
		 */
		$ret[$indice++] = [
				['tplan', 'oigen', 'tpago', 'caja_id'],
				'integer',
				'min' => 0,
				'on' => ['select', 'insert', 'update', 'delete']
				];
				
		
		$ret[$indice++] = [
				['cant_atras', 'cant_cons'],
				'integer',
				'min' => 1,
				'max' => 99,
				'on' => ['insert', 'update']
				];
		/*
		 * FIN TIPO Y RANGO DE DATOS
		 */
				
		
		/*
		 * VALIDACION MUTUA
		 */
		 
		//cantidad de cuotas consecutivas no puede ser mayor a cantidad de cuotas atrasdas
		$ret[$indice++] = [
				'cant_cons',
				'esMenor',
				'on' => ['insert', 'update']
				];
		/*
		 * FIN VALIDACION MUTUA
		 */
		
		
		/*
		 * EXISTENCIA EN BASE DE DATOS DE CLAVES FORANEAS
		 */
		$ret[$indice++] = [
				'tplan',
				'existePlan',
				'when' => function($model){return $model->tplan > 0;},
				'on' => ['insert']
				]; 
				
		$ret[$indice++] = [
				'origen',
				'existeOrigen',
				'when' => function($model){return $model->origen > 0;},
				'on' => ['insert']
				];
		
		$ret[$indice++] = [
				'tpago',
				'existePago',
				'when' => function($model){return $model->tpago > 0;},
				'on' => ['insert']
				];
		
		$ret[$indice++] = [
				'caja_id',
				'existeCaja',
				'when' => function($model){return $model->caja_id > 0;},
				'on' => ['insert']
				];
		/*
		 * FIN DE EXISTENCIA EN BASE DE DATOS DE CLAVES FORANEAS
		 */
		
		
		/*
		 * VALORES POR DEFECTO
		 */
		$ret[$indice++] = [
						'caja_id',
						'default',
						'value' => 0,
						'on' => ['select', 'insert', 'update']
						];
						
		/*
		 * FIN VALORES POR DEFECTO
		 */
		
		/*
		 * FILTROS
		 */
		$ret[$indice++] = [
						'caja_id',
						'filter',
						'filter' => function(){return 0;},
						'when' => function($model){return $model->tpago != 3;},
						'on' => ['select', 'insert', 'update']
						];
		/*
		 * FIN FILTROS
		 */
		
		return $ret;
	}
	
	public function attributeLabels()
    {
        return [
            'tplan' => 'Tipo',
            'origen' => 'Origen',
            'tpago' => 'Forma de pago',
            'caja_id' => 'Caja',
            'cant_atras' => 'Cant. cuotas atrasadas',
            'cant_cons' => 'Cant. cuotas consecutivas'
            ];
    }   

	/**
	 * Determina que el plan de configuracion exista en la base de datos.
	 */
	public function existePlan()
	{
		$sql = "Select count(*) From plan_config Where cod = $this->tplan";
		
		$cmd = Yii::$app->db->createCommand($sql);
		
		if($cmd->queryScalar() <= 0)
			$this->addError($this->tplan, 'El plan de configuración de convenios no existe');
	}
	
	/**
	 * Determina que el origen exista en la base de datos.
	 */
	public function existeOrigen()
	{
		$sql = "Select count(*) From plan_torigen Where cod = $this->origen";
		
		$cmd = Yii::$app->db->createCommand($sql);
		
		if($cmd->queryScalar() <= 0)
			$this->addError($this->origen, 'Origen no existe');
	}
	
	/**
	 * Determina si existe el tipo de pago en la base de datos.
	 */
	public function existePago()
	{
		$sql = "Select count(*) From plan_tpago Where cod = $this->tpago";
		
		$cmd = Yii::$app->db->createCommand($sql);
		
		if($cmd->queryScalar() <= 0)
			$this->addError($this->tpago, 'El tipo de pago no existe');
	}
	
	/**
	 * Determina si existe la caja en la base de datos.
	 */
	public function existeCaja()
	{
		$sql = "Select count(*) From caja Where caja_id = $this->caja_id";
		
		$cmd = Yii::$app->db->createCommand($sql);
		
		if($cmd->queryScalar() <= 0)
			$this->addError($this->caja_id, 'La caja no existe');
	}

	/**
	 * Valida que la cantidad de cuotas consecutivas no sea mayor a la cantidad de cuotas atrasadas
	 */
	public function esMenor()
	{
		if($this->cant_cons > $this->cant_atras)
			$this->addError($this->cant_cons, 'La cantidad de cuotas consecutivas debe ser menor o igual a la cantidad de cuotas atrasadas');
	}
	
	/**
	 * Si es un registro nuevo, lo inserta en la base de datos. Si no es un registro nuevo, modifica las cantidades atrasadas y las cantidades consecutivas
	 * 
	 * @return boolean - true si se ha podido insertar o modificar el registro correctamente, false de lo contrario
	 */
	public function grabar()
	{
		
		$this->scenario = $this->isNewRecord ? 'insert' : 'update';
		
		if(!$this->validate())
			return false;
			
		$cmd = Yii::$app->db->createCommand();
			
		if($this->isNewRecord)
		{
			$actuales = "Select count(*) From plan_config_decaer Where tplan = $this->tplan And origen = $this->origen And tpago = $this->tpago And caja_id = $this->caja_id";
			
			$cmd->sql = $actuales;
			
			if($cmd->queryScalar() > 0)
			{
				$this->addError($this->tplan, 'Configuración existente. Verifique datos');
				return false;
			}
			
			//no existe otro registro igual
			$sql = "Insert Into plan_config_decaer(tplan, origen, tpago, caja_id, cant_atras, cant_cons, fchmod, usrmod)" .
					" Values($this->tplan, $this->origen, $this->tpago, $this->caja_id, $this->cant_atras, $this->cant_cons, current_timestamp, " . Yii::$app->user->id . ")";
			
			$cmd->sql = $sql;			
			
			if($cmd->execute() <= 0)
			{
				$this->addError($this->tplan, 'Ocurrio un error al intentar crear el registro');
				return false;
			}
			
			return true;
		}
		else
		{			
			$sql = "Update plan_config_decaer Set cant_atras = $this->cant_atras, cant_cons = $this->cant_cons, fchmod = current_timestamp, usrmod = " . Yii::$app->user->id . " Where " .
					" tplan = $this->tplan And origen = $this->origen And tpago = $this->tpago And caja_id = $this->caja_id";
					
			$cmd->sql = $sql;
			
			return $cmd->execute() > 0;
		}
		
		return false;
	}
	
	/**
	 * Borra el registro de la base de datos
	 * 
	 * @return boolean - true si se ha eliminado el registro correctamente, false de lo contrario
	 */
	public function borrar()
	{
		$sql = "Delete From plan_config_decaer Where tplan = $this->tplan And origen = $this->origen And tpago = $this->tpago And caja_id = $this->caja_id";
		
		$cmd = Yii::$app->db->createCommand($sql);		
		
		return $cmd->execute() > 0;
	}
	
	/**
	 * Busca un registro en la base de datos
	 * 
	 * @param string $scenario = 'select' - scenario en el cual validar los datos ya cargados en el modelo
	 * 
	 * @return PlanConfigDecae - modelo recuperado de la base de datos o este mismo modelo en caso de que tenga errores de validacion
	 */
	public function buscarUno($scenario = 'select')
	{
		$this->scenario = $scenario;
		
		if(!$this->validate())
			return $this;
			
		
		$ret = PlanConfigDecae::find()->where([
									'tplan' => $this->tplan, 
									'origen' => $this->origen, 
									'tpago' => $this->tpago, 
									'caja_id' => $this->caja_id
									])
									->one();
									
		if($ret == null)
		{
			$this->addError($this->tplan, 'Plan de configuración de decaimiento no encontrado');
			return $this;
		}
		
		return $ret;
			
	}
}
?>
