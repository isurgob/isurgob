<?php

namespace app\models\ctacte;

use Yii;

use Yii\db\Exception;
use yii\db\Connection;
use yii\db\Expression;
use yii\db\Query;
use yii\data\SqlDataProvider;

use app\utils\helpers\DBException;
use app\utils\db\Fecha;
use app\utils\db\utb;


use PDO;
use InvalidArgumentException;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "item".
 *
 * @property integer $item_id
 * @property string $nombre
 * @property integer $trib_id
 * @property integer $tipo
 * @property integer $cta_id
 * @property string $obs
 * @property string $fchmod
 * @property integer $usrmod
 */
class Item extends \yii\db\ActiveRecord
{	
	private $error= null;
	

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {    	
    	$indice = 0;
    	
    	$ret = [];
    	
    	/*
    	 * campos requeridos
    	 */
    	$ret[$indice++] = [
    						['nombre', 'trib_id', 'tipo', 'cta_id'],
    						'required',
    						'on' => ['insert', 'update']
    						];
    						

    	
    	$ret[$indice++] = [
    						'item_id',
    						'required',
    						'on' => ['update', 'delete', 'select']
    						];    	
    	/*
    	 * fin campos requeridos
    	 */
    	 
    	 /*
    	  * valores por defecto de los campos opcionales
    	  */
    	$ret[$indice++] = [
    						'obs',
    						'default',
    						'value' => '',
    						'on' => ['insert', 'update']
    						];

    	/*
    	 * fin valores por defecto de los campos opcionales
    	 */
    	 
    	 /*
    	  * tipos de datos y longitudes
    	  */
    	  $ret[$indice++] = [
    	  					'nombre',
    	  					'string',
    	  					'max' => 40,
    	  					'on' => ['insert', 'update']
    	  					];
    	  					
    	  $ret[$indice++] = [
    	  					'obs',
    	  					'string',
    	  					'max' => 500,
    	  					'min' => 0,
    	  					'on' => ['insert', 'update']
    	  					];
    	  					
    	  $ret[$indice++] = [
    	  					['trib_id', 'cta_id', 'tipo'],
    	  					'integer',
    	  					'min' => 1,
    	  					'on' => ['insert', 'update']
    	  					];
    	  					
    	  $ret[$indice++] = [
    	  					'item_id',
    	  					'integer',
    	  					'min' => 1,
    	  					'on' => ['update', 'delete', 'select']
    	  					];
    	  					
    	  //tipo esta entre 1 y 7
    	  $ret[$indice++] = [
    	  					'tipo',
    	  					'in',
    	  					'range' => [1, 2, 3, 4, 5, 6, 7],
    	  					'on' => ['insert', 'update']
    	  					];
    	  /*
    	   * fin tipos de datos y longitudes
    	   */
    	   
    	   /*
    	    * validaciones de existencia en base de datos
    	    */
    	    
    	  
    	  					
    	  //numero de cuenta
    	  $ret[$indice++] = [
    	  					'cta_id',
    	  					'existeCuenta',
    	  					'on' => ['insert', 'update']
    	  					];
    	  					
    	  //el tipo de item es valido
    	  $ret[$indice++] = [
    	  					'tipo',
    	  					'validarTipo',
    	  					'skipOnEmpty' => false,
    	  					'when' => function($model){return ($model->trib_id != null && $model->trib_id > 0);},
    	  					'on' => ['insert', 'update']
    	  					];
    	  					
    	  //el tributo es valido
    	  $ret[$indice++] = [
    	  					'trib_id',
    	  					'validarTributo',
    	  					'skipOnEmpty' => false,
    	  					'when' => function($model){return ($model->tipo != null && $model->tipo > 0);},
    	  					'on' => ['insert', 'update']
    	  					];
    	  					
    	 
    	 $ret[$indice++] = [
    	 					'nombre',
    	 					'existe',
    	 					'skipOnEmpty' => false,
    	 					'when' => function($model){return ($model->trib_id != null && $model->trib_id > 0);},
    	 					'on' => ['insert', 'update']
    	 					];
    	    /*
    	     * fin de validacion de existencia en base de datos
    	     */
    	     
    	     
    	 $ret[$indice++] = [
    	 					['nombre' , 'obs'],
    	 					'filter',
    	 					'filter' => 'trim',
    	 					'on' => ['insert', 'update']
    	 				];     	 
    	 
    	return $ret;
    }
    
    public function scenarios()
    {
    	return [
    		'insert' => ['nombre', 'cta_id', 'trib_id', 'obs', 'tipo'],
    		'update' => ['item_id', 'nombre', 'cta_id', 'trib_id', 'obs', 'tipo'],
    		'delete' => ['item_id'],
    		'select' => ['item_id']
    	];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_id' => 'Código',
            'nombre' => 'Nombre',
            'trib_id' => 'Tributo',
            'tipo' => 'Tipo de item',
            'cta_id' => 'Cuenta',
            'obs' => 'Observaciones',
        ];
    }
    
    
	
	/**
	 * Determina si ya existe en la base de datos el Item con el mismo nombre y el mismo trib_id
	 *
	 */
	public function existe()
	{	
		//$sql = "Select count(item_id) From item Where lower(nombre) = '" . strtolower($this->nombre) . "' And trib_id = $this->trib_id ";
		
		$sql = "Select count(item_id) From item Where lower(nombre) = lower(:_nombre) And trib_id = :_trib_id ";
		$params = [':_nombre' => $this->nombre, ':_trib_id' => $this->trib_id];
		
		if(!$this->isNewRecord)
			{
				$params = array_merge($params, [':_item_id' => $this->item_id]);
				$sql .= " And item_id <> :_item_id";	
			}
			//$sql .= " And item_id <> $this->item_id";
		
		
		$res = Yii::$app->db->createCommand($sql, $params)->queryScalar();
		
		if($res > 0)
			$this->addError($this->nombre, 'El nombre de item ya existe para el tributo dado.');			
	}
	
	/**
	 * Se comprueba si en la base de datos ya existe un Item de tipo redondeo para el tributo provisto en $idTributo y que sea distinto al item provisto en $idItem.
	 * 
	 * @param int $idTributo - id del tributo
	 * @param int|null $idItem - id del item..
	 * 
	 * @return boolean - false si no existe item de tipo redondeo para el tributo dado en $idTributo, true de lo contrario.
	 */
	private function existeRedondeo($idTributo, $idItem = null)
	{	
		$sql = "Select count(item_id) From item Where trib_id = :_trib_id And tipo = 6 ";
		$params = [':_trib_id' => $idTributo];
		//$sql = "Select count(item_id) From item Where trib_id = $this->trib_id And tipo = 6 ";
		
		if($idItem != null)
		{
			$sql .= " And item_id <> :_item_id";
			$params = array_merge($params, [':_item_id' => $idItem]);
		}
			
			
		$res = Yii::$app->db->createCommand($sql, $params)->queryScalar();
		
		return $res > 0;
	}
	
	/**
	 * Se comprueba si en la base de datos ya existe un Item de tipo retencion para el tributo provisto en $idTributo y que sea distinto al item provisto en $idItem
	 * 
	 * 
	 * @param int $idTributo - id del tributo.
	 * @param int|null $idItem - id del item.
	 * 
	 * @return boolean -  false si no xiste el item de tipo retencion para el tributo dado en $idTributo, true de lo contrario.
	 */
	private function existeFondo($idTributo, $idItem = null)
	{				
		//$sql = "Select count(item_id) From item Where trib_id = $this->trib_id And tipo = 7 ";
		
		$sql = "Select count(item_id) From item Where trib_id = :_trib_id And tipo = 7 ";
		$params = [':_trib_id' => $idTributo];
		
		if($idItem != null)
		{
			$sql .= " And item_id <> :_item_id";
			$params = array_merge($params, [':_item_id' => $idItem]);	
		}
		
		$res = Yii::$app->db->createCommand($sql, $params)->queryScalar();
		
		return $res > 0;
	}
	
	public function validarTipo()
	{
		switch($this->tipo)
		{
			case 7 : if($this->existeFondo($this->trib_id, $this->item_id))
						$this->addError($this->tipo, 'Ya existe un item de tipo fondo para el tributo dado.');
					
					break;
						
			case 6 : if($this->existeRedondeo($this->trib_id, $this->item_id))
						$this->addError($this->tipo, 'Ya existe un item de tipo redondeo para el tributo dado.');
						
					break;
						
			case 2:
			case 3:
					$sql = "Select count(trib_id) From trib Where trib_id = :_trib_id And (tipo = 4 Or tipo = 6)";
					//$sql = "Select count(trib_id) From trib Where trib_id = $this->trib_id And (tipo = 4 Or tipo = 6)";
					$res = Yii::$app->db->createCommand($sql, [':_trib_id' => $this->trib_id])->queryScalar();
					
					if($res > 0)
						$this->addError($this->tipo, 'Para los tributos "Eventual" y "Sellado", no pueden existir items de tipo "Recargo por objeto" o "Descuento por objecto"');
						
					break;					
		}
	}
	
	public function validarTributo()
	{
		switch($this->trib_id)
		{
			case 12:
					if($this->tipo != 1)
						$this->addError($this->trib_id, 'Para el tributo "Recibo" solo se pueden crear items de tipo "Emisión"');
			
		}
	}
	

	
	/**
	 * Graba o modifica el registro en la base de datos. La axxion dependera de si el registro es nuevo o no.
	 * 
	 * @return boolean - true si se ha insertado o modificado el registro. false de lo contrario.
	 */
	public function grabar()
	{		
		//no existe registro y se procede a crearlo
		if($this->isNewRecord)
		{
			$this->scenario = 'insert';
			
			if(!$this->validate())
				return false;				
			
			$proximo = $this->getProximoId();
			
			$columnas = "item_id, nombre, trib_id, tipo, cta_id, obs, fchmod, usrmod";
			
			$sql = "Insert Into item($columnas) Values(:_item_id, :_nombre, :_trib_id, :_tipo, :_cta_id, :_obs, current_timestamp, " . Yii::$app->user->id . ")";
			
			
			$cmd = Yii::$app->db->createCommand($sql, [
													':_item_id' => $proximo,
													':_nombre' => $this->nombre,
													':_trib_id' => $this->trib_id,
													':_tipo' => $this->tipo,
													':_cta_id' => $this->cta_id,
													':_obs' => $this->obs
													]);
						
			$res = 0;
			
			try{			
				$res = $cmd->execute();
			}
			catch (Exception $e)
			{
				$this->addError($this->item_id, 'Ocurrio un error al intentar crear el registro.');
				return false;
			}
			
			return $res > 0;
		}
		//se procede a modificar el registro existente
		else
		{
			$this->scenario = 'update';
			
			if(!$this->validate())
				return false;	
		
			
			
			$sql = "Update item set nombre = :_nombre, tipo = :_tipo, trib_id = :_trib_id, cta_id = :_cta_id, obs = :_obs, fchmod = current_timestamp, usrmod = " . Yii::$app->user->id;
			$sql .= " Where item_id = :_item_id";
			
			$cmd = Yii::$app->db->createCommand($sql, [
														':_nombre' => $this->nombre, 
														':_tipo' => $this->tipo, 
														':_trib_id' => $this->trib_id, 
														':_cta_id' => $this->cta_id, 
														':_obs' => $this->obs, 
														':_item_id' => $this->item_id
														]);
							
			$res = $cmd->execute();				
			return $res >= 0;
		}
		
		//retorno por defecto en caso de que alguno de los caminos no haya retornado nada
		return false;
	}
	
	/**
	 * Borra el registro de la base de datos
	 * 
	 * @return boolean - true si la accion se ha realizado con exito. false si hubo algun error.
	 */
	public function borrar()
	{
			
		$trans = Yii::$app->db->beginTransaction();
			
		
		//se borran los valores asociados que tienen las vigencias de tipo s/tabla asoc del item
		try{
			$sql = "Delete From item_asoc Where item_id = :_item_id";
			
			$cmd = Yii::$app->db->createCommand($sql);
			
			$cmd->bindValue(':_item_id', $this->item_id, PDO::PARAM_INT);
			
			$cmd->execute();
		}
		catch (Exceptio $e)
		{
			$trans->rollBack();
			$this->addError($this->item_id, 'Ocurrio un error al intentar borrar el item');
			return false;
		}
		
		
		//se borran las vigencias asociadas al item
		try
		{
			$cmd = Yii::$app->db->createCommand("Delete From item_vigencia Where item_id = :_item_id", [':_item_id' => $this->item_id]);
			
			$cmd->execute();			
		}
		catch (Exception $e)
		{
			$trans->rollBack();
			$this->addError($this->item_id, 'Ocurrio un error al intentar borrar los registros de vigencias del item dado.');//DBException::getMensaje($e));
			return false;
		}
		
		
		try
		{
			
			$sql = "Delete From item Where item_id = :_item_id";
			
			$cmd = Yii::$app->db->createCommand($sql, [':_item_id' => $this->item_id]);
			
			$res =  $cmd->execute();
		}
		catch (Exception $e)
		{
			$trans->rollBack();
			$this->addError($this->item_id, 'Ocurrio un error al intentar borrar el registro.');//DBException::getMensaje($e));
			return false;
		}
		
		$trans->commit();
		
		return $res > 0;
	}
	
    /**
     * Determina si el numero de cuenta existe
     * 
     * @param $cuenta int - Numero de cuenta a comprobar. Debe ser mayor a 0.
     */
    public function existeCuenta($cuenta)
    {    	    		
    	    	    		
    	try{
    		$sql = "Select count(cta_id) From cuenta Where cta_id = :_cta_id";
    		$cmd = Yii::$app->db->createCommand($sql, [':_cta_id' => $this->cta_id]);
    		
    		$res = $cmd->queryScalar();
    		
    		if($res <= 0)
    			$this->addError($cuenta, 'El número de cuenta no existe');
    	}
    	catch (Exception $e) {
    		$this->addError($cuenta, 'El número de cuenta no existe');
    	}
    	
    	
    }
    
    /**
     * Obtiene el proximo id a utilizar en la base de datos.
     * 
     * @return int - Proximo item_id a utilizar para insertar un dato
     */
    private function getProximoId()
    {
    	$sql = "Select nextval('seq_item')";
        	
    	return Yii::$app->db->createCommand($sql)->queryScalar() + 1;
    }
    
    /**
     * Obtiene un arreglo asociativo con los nombres de todos los tributos disponibles.
     * 
     * @return Array - Arreglo con los nombres de todos los tributos disponibles.
     */
    public function getNombreTributos()
    {
    	return utb::getAux('trib', 'trib_id', 'nombre', 0, "Upper(est) = 'A'");
    }
    
    /**
     * Obtiene un arreglo asociativo con los nombres de los tipo de item disponibles.
     * 
     * @return Array - Arreglo con los nombres de los tipo de item disponibles.
     */
    public function getNombreTipoItem()
    {    	
    	return utb::getAux('item_tipo');
    }
    
    /**
     *Obtiene el nombre de la cuenta  partir de su codigo
     *
     */
    public function getNombreCuenta($cta_id)
    {
    	
    	
    	if($cta_id == null)
    		return '';
    	
    	$cond = 'cta_id = ' . $cta_id;
    	
    	$cuentas = utb::getAux('cuenta', 'cta_id', 'nombre', 0, $cond);
    	
    	if(array_key_exists($cta_id, $cuentas))
    		return $cuentas[$cta_id];
    	else return '';
    	
    	return '';
    }
    
    /**
     * Retorna un SqlDataProvider con las cta_id y los nombres de las cuentas
     * 
     * @return SqlDataProvider - Con las cta_id y nombre de la tabla cuenta
     */
     public function getDPCuentas($nombre = '', $pagina = 0)
     {  
     	$dp = utb::DataProviderAux('cuenta', "lower(nombre) Like ( '%' || '$nombre' || '%')", 'cta_id');
     	
     	$pag = $dp->getPagination();
     	     	
     	$pag->params = ['nombreCuenta' => $nombre, $pag->pageParam => $pagina];

     	return $dp;
     }
     
     public function buscarUno()
     {     	
     	$this->scenario = 'select';
     	
     	if(!$this->validate())
     		return $this;
     		
     	$ret = Item::findOne($this->item_id);
     	
     	if($ret == null)
     	{
     		$this->addError($this->item_id, 'Código de item invalido.');
     		return $this;
     	}
     	
     	return $ret;
     }
}
