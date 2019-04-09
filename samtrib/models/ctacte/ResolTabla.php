<?php

namespace app\models\ctacte;

use Yii\helpers\ArrayMap;
use app\models\ctacte\ResolTablaCol;

use Yii;

/**
 * This is the model class for table "resol_tabla".
 *
 * @property integer $tabla_id
 * @property string $nombre
 * @property integer $resol_id
 * @property integer $cantcol
 * @property integer $cantcolfijas

 */
class ResolTabla extends \yii\db\ActiveRecord
{	
	//maxima cantidad de columnas que se pueden grabar
	private $maximoColumnas;
	
	//contiene el ordene en que se tienen que grabar las columnas
	private $orden;
	private $ordenSinParamStr;
	
	//determina que existen datos asociados a la tabla y no se puede modificar nada
	public $tieneDatos;

	public $columnas;

	public function __construct(){
		
		parent::__construct();
		
		$this->cantcol= 1;
		$this->cantcolfijas= 0;
		$this->maximoColumnas= 6;
		
		//$this->ordenSinParamStr= ['param1', 'param2', 'param3', 'param4', 'param5'];
		$this->orden= ['paramstr', 'param1', 'param2', 'param3', 'param4', 'param5'];
		$this->uso_paramstr= true;
		$this->columnas= $this->columnas(true);
		
	}

    public static function tableName()
    {
        return 'resol_tabla';
    }

    /**
     * @inheritdoc
     */
    public function rules(){

		$ret= [];
		
		/**
		 * CAMPOS REQUERIDOS
		 */
		$ret[]= [
				'resol_id',
				'required',
				'on' => ['insert', 'update', 'delete']
				];
				
		$ret[]= [
				'tabla_id',
				'required',
				'on' => ['update', 'delete']
				];
		
		$ret[]= [
				['nombre', 'cantcol', 'cantcolfijas', 'columnas', 'uso_paramstr'],
				'required',
				'on' => ['insert', 'update']
				];
		
		/**
		 * TIPO Y RANGO DE DATOS
		 */
		$ret[]= [
				'resol_id',
				'integer',
				'min' => 1,
				'on' => ['insert', 'update', 'delete']
				];
				
		$ret[]= [
				'tabla_id',
				'integer',
				'min' => 1,
				'on' => ['update', 'delete']
				];
				
		$ret[]= [
				'nombre',
				'string',
				'max' => 25,
				'on' => ['insert', 'update']
				];
				
		$ret[]= [
				'cantcol',
				'integer',
				'min' => 1,
				'max' => 6,
				'when' => function($model){return $model->uso_paramstr;},
				'on' => ['insert', 'update']
				];
				
		$ret[]= [
				'cantcolfijas',
				'integer',
				'min' => 0,
				'max' => 5,
				'when' => function($model){return $model->uso_paramstr;},
				'on' => ['insert', 'update']
				];
				
		$ret[]= [
				'cantcol',
				'integer',
				'min' => 1,
				'max' => 5,
				'when' => function($model){return !$model->uso_paramstr;},
				'on' => ['insert', 'update']
				];
				
		$ret[]= [
				'cantcolfijas',
				'integer',
				'min' => 0,
				'max' => 4,
				'when' => function($model){return !$model->uso_paramstr;},
				'on' => ['insert', 'update']
				];		
		
				
		$ret[]= [
				'uso_paramstr',
				'boolean',
				'trueValue' => 1,
				'falseValue' => 0,
				'on' => ['insert', 'update']
				];
		
		$ret[]= [
				'nombre',
				'existeNombre',
				'on' => ['insert', 'update']
				];
				
		$ret[]= [
				'cantcolfijas',
				function(){
					if($this->cantcolfijas >= $this->cantcol)
						$this->addError($this->cantcolfijas, 'La cantidad de columnas fijas no puede ser mayor o igual a la cantidad de columnas');
				},
				'on' => ['insert', 'update']
				];
				
		return $ret;
	}
	
	public function scenarios(){
		
		return [
			'insert' => ['resol_id', 'nombre', 'cantcol', 'cantcolfijas', 'columnas', 'uso_paramstr'],
			'update' => ['resol_id', 'tabla_id', 'nombre', 'cantcol', 'cantcolfijas', 'columnas', 'uso_paramstr'],
			'delete' => ['resol_id', 'tabla_id']
		];
	}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tabla_id' => 'Código',
            'nombre' => 'Nombre',
            'resol_id' => 'Código de resolución',
            'cantcol' => 'Cantidad de columnas',
            'cantcolfijas'=>'Cantidad de columnas fijas'

        ];
    }
    
    public function afterValidate(){
    	
    	$cantidad= count($this->columnas);
    	
    	$this->columnas= ResolTablaCol::completarLista($this->columnas, $this->orden);
    }
    
    public function afterFind(){
    	
    	$sql= "Select Exists (Select 1 From resol_tabla_dato Where tabla_id = $this->tabla_id)";
    	$this->tieneDatos= Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	$this->columnas= $this->columnas(true);
    }
    
    public function grabar(){
    	
    	$scenario= $this->isNewRecord ? 'insert' : 'update';
    	$this->setScenario($scenario);
    	
    	if(!$this->validate()) return false;
    	
    	$trans= Yii::$app->db->beginTransaction();
    	
    	if($this->isNewRecord){
    		
    		//codigo
    		$sql= "Select Coalesce(Max(tabla_id), 0) + 1 From resol_tabla";
    		$codigo= Yii::$app->db->createCommand($sql)->queryScalar();
    		
    		//se graba la tabla
    		$sql= "Insert Into resol_tabla(tabla_id, resol_id, nombre, cantcol, cantcolfijas, uso_paramstr, fchmod, usrmod)" .
    				" Values($codigo, $this->resol_id, '$this->nombre', $this->cantcol, $this->cantcolfijas, $this->uso_paramstr, current_timestamp, " . Yii::$app->user->id . ")";
    		
    		$res= Yii::$app->db->createCommand($sql)->execute() > 0;
    		
    		if(!$res){
    			$this->addError($this->tabla_id, 'Ocurrió un error al intentar realizar la acción');
    			$trans->rollBack();
    			return false;
    		}

    		if(!$this->grabarColumnas($codigo)){
    			$trans->rollBack();
    			return false;
    		}
    		
    		$this->tabla_id= $codigo;
    		
    	} else {
    		
    		if($this->tieneDatos){
    			$this->addError($this->tabla_id, 'La tabla tiene datos y no se puede modificar');
    			$trans->rollBack();
    			return false;
    		}
    		
    		$sql= "Update resol_tabla Set nombre = '$this->nombre', cantcol = $this->cantcol, cantcolfijas = $this->cantcolfijas, uso_paramstr = $this->uso_paramstr Where resol_id= $this->resol_id And tabla_id = $this->tabla_id";
    		$res= Yii::$app->db->createCommand($sql)->execute() > 0;
    		
    		if(!$res){
    			$this->addError($this->tabla_id, 'Ocurrió un error al intentar realizar la acción');
    			$trans->rollBack();
    			return false;
    		}
    		
    		if(!$this->grabarColumnas($this->tabla_id)){
    			$trans->rollBack();
    			return false;
    		}
    	}
    	
    	
    	$trans->commit();
    	return true;
    }
    
    /**
     * Borra la tabla, las columnas y los datos asociados
     * 
     * @return boolean - Si se han eliminado los datos correctamente
     */
    public function borrar(){
    	
    	$this->setScenario('delete');
    	if(!$this->validate()) return false;
    	
    	$trans= Yii::$app->db->beginTransaction();
    	
    	//se borran los datos
    	$sql= "Delete From resol_tabla_dato Where tabla_id = $this->tabla_id";
    	Yii::$app->db->createCommand($sql)->execute();
    	
    	//se borran las columnas
    	$sql= "Delete From resol_tabla_col Where tabla_id = $this->tabla_id";
    	Yii::$app->db->createCommand($sql)->execute();
    	
    	//se borra la tabla
    	$sql= "Delete From resol_tabla Where tabla_id = $this->tabla_id And resol_id = $this->resol_id";
    	Yii::$app->db->createCommand($sql)->execute();
    	
    	$trans->commit();
    	return true;
    }
    
    /**
     * Borra las columnas asociadas a la tabla y luego graba las que esten almacenadas en la propiedad $columnas
     * 
     * @param int $tabla_id - Codigo de la tabla
     * 
     * @return boolean - Si se han grabado todas las columnas correctamente. False indica que al menos una ha fallado
     */
    private function grabarColumnas($tabla_id){

    	$orden= 0;//$this->uso_paramstr ? 0 : 1;
    	$hayError= false;
    	$grabadas= 0;
    	
		if(!ResolTablaCol::borrarTodas($tabla_id)){
			
			$this->addError($this->columnas, 'Ocurrió un error al intentar crear las columnas');
			return false;
		}
    		
		foreach($this->columnas as $clave => $datos){
			
			if($orden === 0 && !$this->uso_paramstr){
				
				$orden++;
				continue;
			}
			
			if(!array_key_exists('aplicable', $datos) || !$datos['aplicable']) continue;	
			
			if(!$this->validarColumna($datos, $tabla_id, $orden)){
			
				$orden++;
				$hayError= true;
//				$this->addError($this->columnas, 'Ocurrió un error al intentar crear la columna ' . $datos['nombre']);
				continue;
			}
			
			$sql= "Insert Into resol_tabla_col(tabla_id, orden, nombre, tipo, param, compara)" .
					" Values($tabla_id, $orden, '" . $datos['nombre'] . "', 0, 0, '" . $datos['compara'] . "')";			
			
			
			$res= Yii::$app->db->createCommand($sql)->execute() > 0;
			
			if(!$res){
				
				$this->addError($this->columnas, 'Ocurrió un error al intentar crear la columna ' . $datos['nombre']);
				return false;
			}
			
			$grabadas++;
			$orden++;
		}
		
		if($grabadas < $this->cantcol){
			
			if(!$this->hasErrors()) $this->addError($this->cantcol, 'La cantidad de columnas provistas no se corresponde con el parámetro columnas');
			
			return false;
		}
    	
    	return !$hayError;
    }
    
    /**
     * Valida que los datos de la columna a insertar sean corrector.
     * 
     * @param Array $columna - Datos de la columna. Arreglo asociativo.
     * @param int $tabla_id - Codigo de la tabla.
     * @param int $orden - Orden de la columna.
     * 
     * @return boolean - Si los datos de la columna son correctos.
     */
    private function validarColumna($columna, $tabla_id, $orden){
    	
    	$datosCompletos= array_merge(['tabla_id' => $tabla_id, 'orden' => $orden], $columna);
    	
    	$model= new ResolTablaCol();
    	$model->setScenario('insert');
    	
    	if($model->load(['ResolTablaCol' => $datosCompletos])){
    		
    		$model->setScenario('insert');
    		
    		if($model->validate()) return true;
    		
    		$errores= $model->getErrors();
    		$agregar= [];
    		
    		foreach($errores as $atributo => $arregloErrores)
    			foreach($arregloErrores as $error)
    				array_push($agregar, $error . ". Columna " . ($orden +1));
    			
    		$this->addErrors($agregar);
    	}
    	
    	return false;
    }
    
    /**
     * Obtiene los datos asociados a la tabla. Opcionalmente se filtran los datos por año y cuota.
     * El filtrado se da solamente cuando se provee año y cuota al mismo tiempo.
     * 
     * @param int $anio = 0 - Anio para el filtrado.
     * @param int $cuota = 0 - Cuota para el filtrado.
     * 
     * @return Array - Cada elemento del arreglo es un modelo ResolTablaDato.
     */
    public function datos($anio = 0, $cuota = 0){
    	
    	if($this->tabla_id <= 0) return [];
    	
    	$perdesde= null;
    	if($anio > 0 && $cuota > 0)
    		$perdesde= $anio * 1000 + $cuota;
    	
    	return ResolTablaDato::find()->where(['tabla_id' => $this->tabla_id])->andFilterWhere(['perdesde' => $perdesde])->all();
    }
    
    /**
     * Obtiene las columnas de la tabla
     * 
     * @param boolean $rellenar = false - Si se debe rellenas las columnas faltantes con columnas vacias.
     * 
     * @return Array - Cada elemento del arreglo es un modelo ResolTablaCol.
     */
    public function columnas($rellenar = false){
    	
    	$ret= $rellenar ? array_fill_keys($this->orden, new ResolTablaCol()) : [];
    	
    	if($this->tabla_id <= 0) return $ret;

    	$datos= ResolTablaCol::find()->where(['tabla_id' => $this->tabla_id])->orderBy(['orden' => SORT_ASC])->all();    	
    	
    	foreach($datos as $columna)
    		if(array_key_exists($columna->orden, $this->orden))
    			$ret[$this->orden[$columna->orden]]= $columna;
    	
    	if($rellenar && count($datos) < $this->maximoColumnas)
    		$ret= ResolTablaCol::completarLista($ret, $this->orden);
    		
    	return $ret;    	
    }
    
    /**
     * Valida si el nombre de la tabla ya existe.
     */
    public function existeNombre(){
    	
    	$sql= "Select Exists (Select 1 From resol_tabla Where nombre = '$this->nombre' And resol_id = $this->resol_id";
    	if(!$this->isNewRecord) $sql .= " And tabla_id <> $this->tabla_id";
    	$sql .= ")";
    	
    	$res= Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	if($res) $this->addError($this->nombre, 'El nombre de la tabla ya existe');
    }
}