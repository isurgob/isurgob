<?php

namespace app\models\ctacte;
use yii\data\SqlDataProvider;
use yii\data\ActiveDataProvider;
use app\utils\db\utb;
use Yii;

use app\models\ctacte\ResolLocal;

ini_set("display_errors", "on");
error_reporting(E_ALL);

/**
 * This is the model class for table "rubro".
 *
 * @property integer $resol_id
 * @property string $nombre
 * @property integer $trib_id
 * @property integer $ttrib
 * @property string $funcion
 * @property string $formula
 * @property string $filtro
 * @property integer $perdesde
 * @property integer $perhasta
 * @property integer $anual
 * @property string $fchmod
 * @property integer $usrmod
 */
class Resol extends \yii\db\ActiveRecord
{
	
	public $adesde;
	public $cdesde;
	public $ahasta;
	public $chasta;

	public function __construct(){
		
		parent::__construct();
		
		$this->ahasta= 9999;
		$this->chasta= 999;
	}
	
    /**
     * @inheritdoc
     */
     
    public static function tableName()
    {
        return 'v_resol';
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
				['nombre', 'adesde', 'cdesde', 'funcion', 'anual', 'cant_anio'],
				'required',
				'on' => ['insert', 'update']
				];
				
		$ret[]= [
				'trib_id',
				'required',
				'on' => ['insert', 'update'],
				'message' => 'Elija un tributo'
				];
				
		$ret[]= [
				'resol_id',
				'required',
				'on' => ['update']
				];
		/**
		 * FIN CAMPOS REQUERIDOS
		 */
	
		/**
		 * TIPO Y RANGO DE DATOS
		 */
		
		$ret[]= [
				'resol_id',
				'integer',
				'min' => 1,
				'on' => 'update'
				];
				
		$ret[]= [
				'nombre',
				'string',
				'max' => 50,
				'on' => ['insert', 'update']
				];
		
		$ret[]= [
				['adesde', 'ahasta'],
				'integer',
				'min' => 1900,
				'max' => 9999,
				'on' => ['insert', 'update']
				];
		
		$ret[]= [
				['cdesde', 'chasta'],
				'integer',
				'min' => 0,
				'max' => 999,
				'on' => ['insert', 'update']
				];
				
		$ret[]= [
				'funcion',
				'string',
				'max' => 35,
				'on' => ['insert', 'update']
				];
		
		$ret[]= [
				'filtro',
				'string',
				'max' => 250,
				'on' => ['insert', 'update']
				];
		
		$ret[]= [
				'anual',
				'boolean',
				'falseValue' => 0,
				'trueValue' => 1,
				'on' => ['insert', 'update']
				];
				
		$ret[]= [
				'cant_anio',
				'integer',
				'min' => 1,
				'max' => 12,
				'on' => ['insert', 'update']
				];	

		$ret[]= [
				'detalle',
				'string',
				'max' => 250,
				'on' => ['insert', 'update']
				];			
		
		/**
		 * FIN TIPO Y RANGO DE DATOS
		 */

		/**
		 * VALORES POR DEFECTO
		 */

		$ret[]= [
				'ahasta',
				'default',
				'value' => 9999,
				'on' => ['insert', 'update']
				];
		
		$ret[]= [
				'chasta',
				'default',
				'value' => 999,
				'on' => ['insert', 'update']
				];
				
		$ret[]= [
				'anual',
				'default',
				'value' => 0,
				'on' => ['insert', 'update']
				];

				
		//la formula es siempre vacia (no se implementa el campo en la BD)
		$ret[]= [
				'formula',
				'filter',
				'filter' => function(){return null;},
				'on' => ['insert', 'update']
				];
	
		/**
		 * VALIDACIONES VARIAS
		 */
		$ret[]= [
				'nombre',
				'validarNombre',
				'on' => ['insert', 'update']
				];
	
		$ret[]= [
				'funcion',
				'validarFuncion',
				'on' => ['insert', 'update']
				];
				
		return $ret;
	}
	
	public function scenarios(){

		return [
				'insert' => ['nombre', 'trib_id', 'adesde', 'cdesde', 'ahasta', 'chasta', 'funcion', 'filtro', 'anual', 'cant_anio', 'detalle'],
				'update' => ['resol_id', 'nombre', 'trib_id', 'adesde', 'cdesde', 'ahasta', 'chasta', 'funcion', 'filtro', 'anual', 'cant_anio', 'detalle']
				];		
	}
	
	public function afterValidate(){
		
		if(!$this->hasErrors()){
			$this->perdesde= $this->adesde * 1000 + $this->cdesde;
			$this->perhasta= $this->ahasta * 1000 + $this->chasta;

		}
	}
	
	public function afterFind(){
		
		$this->adesde= intval($this->perdesde / 1000);
		$this->cdesde = $this->perdesde % 1000;
		
		$this->ahasta= intval($this->perhasta / 1000);
		$this->chasta= $this->perhasta % 1000;
	}
	
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'resol_id' => 'Código',
            'nombre' => 'Nombre',
            'trib_id' => 'Tributo',
            'anual' => 'Anual',
            'funcion' => 'Función',
            'formula' => 'Formúla',
            'filtro' => 'Filtro',
            'adesde' => 'Año desde',
            'cdesde' => 'Cuota desde',
            'ahasta' => 'Año hasta',
            'chasta' => 'Cuota hasta',
            'fchmod' => 'Fecha de modificacion',
            'usrmod' => 'Codigo de usuario que modifico',
			'cant_anio' => 'Cantidad de Años'
        ];
    }
	
	public function grabar(){
		
		$scenario= $this->isNewRecord ? 'insert' : 'update';
		$this->setScenario($scenario);
		
		if(!$this->validate()) return false;
		
		$sql= "";
		$trans= Yii::$app->db->beginTransaction();
		
		if($this->isNewRecord){
			
			$sql= "Select Coalesce(Max(resol_id), 0) + 1 From resol";
			$codigo= Yii::$app->db->createCommand($sql)->queryScalar();
			
			$sql= "Insert Into resol(resol_id, nombre, trib_id, perdesde, perhasta, funcion, formula, filtro, anual, cant_anio, detalle, fchmod, usrmod)" .
					" Values($codigo, '$this->nombre', $this->trib_id, $this->perdesde, $this->perhasta, '$this->funcion'," .
					" '$this->formula', '$this->filtro', $this->anual, $this->cant_anio, '$this->detalle', current_timestamp, " . Yii::$app->user->id . ")";
					
				
			
		} else {
			
			$sql= "Update resol Set nombre = '$this->nombre', trib_id = $this->trib_id, perdesde = $this->perdesde, perhasta = $this->perhasta," .
					" funcion = '$this->funcion', formula = '$this->formula', filtro = '$this->filtro', anual = $this->anual," .
					" cant_anio = $this->cant_anio, detalle = '$this->detalle', " . 
					" fchmod = current_timestamp, usrmod = " . Yii::$app->user->id . " Where resol_id = $this->resol_id";
		}
		
		$res= Yii::$app->db->createCommand($sql)->execute() > 0;
			
		if(!$res){
			
			$this->addError($this->resol_id, 'Ocurrió un error al intentar realizar la acción');
			$trans->rollBack();
			return false;
		}	
		
		$trans->commit();
		return true;
	}
	
	/**
	 * Valida el nombre de la resolucion
	 */
    public function validarNombre(){
    	
    	$sql= "Select Exists (Select 1 From resol Where nombre = '$this->nombre'";
    	if(!$this->isNewRecord) $sql .= " And resol_id <> $this->resol_id";
    	$sql .= ")";
    	
    	$res= Yii::$app->db->createCommand($sql)->queryScalar();
    	if($res) $this->addError($this->nombre, 'Ya existe una resolución con el mismo nombre');
    }
    
    /**
     * Valida la funcion de la resolucion
     */
    public function validarFuncion(){
    	
    	$sql= "Select Exists (Select 1 From (SELECT format('%I', p.proname) As nombre" .
    			" FROM pg_proc p INNER JOIN pg_namespace ns ON (p.pronamespace = ns.oid)" .
    			" WHERE ns.nspname = 'sam') As subconsulta Where nombre = '$this->funcion')";
    			
    	$existe= Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	if(!$existe) $this->addError($this->funcion, 'La función ' . $this->funcion . ' no existe');
    	
    }
	
	/**
	 * Obtiene las variables asociadas a la resolucion
	 * 
	 * @return Array - Cada elemento del arreglo es un modelo ResolLocal
	 */
	public function variables(){
		
		if($this->resol_id <= 0) return [];
		
		return ResolLocal::find()->where(['resol_id' => $this->resol_id])->all();
	}
	
	/**
	 * Obtiene las tablas asociadas a la resolucion
	 * 
	 * @return Array - Cada elemento del arreglo es un modelo ResolTabla
	 */
	public function tablas(){
		
		if($this->resol_id <= 0) return [];
		
		return ResolTabla::find()->where(['resol_id' => $this->resol_id])->all();
	}
    
    public function Imprimir($id,&$sub1,&$sub2,&$sub3)
    {
    	$sql = "Select * from v_resol where resol_id=".$id;
    	$datos = Yii::$app->db->createCommand($sql)->queryAll();
    	
    	$peractual = utb::PerActual($datos[0]['trib_id']);
    	
    	$sql = "Select * from Resol_Tabla Where Resol_Id=".$id;
    	$sub1 = Yii::$app->db->createCommand($sql)->queryAll();
    	
    	$sql = "Select t.resol_id, t.nombre as Tabla, c.nombre as Columna From resol_tabla_col c";
        $sql .= " Left Join resol_tabla t on c.tabla_id = t.tabla_id Where resol_id = " . $id . " Order by t.nombre, Orden ";
    	$sub2 = Yii::$app->db->createCommand($sql)->queryAll();
    	
    	$sql = "Select t.resol_id, t.nombre, perdesde, perhasta, paramstr, param1, param2, param3, param4, param5,";
        $sql .= " ((substr(perhasta::text,1,4)) || '-' || (substr(perhasta::text,5,2))) as Per_Hasta,";
        $sql .= "((substr(perdesde::text,1,4)) || '-' || (substr(perdesde::text,5,2))) as Per_Desde";
        $sql .= " From resol_tabla_dato d Left Join resol_tabla t on d.tabla_id = t.tabla_id ";
        $sql .= " Where resol_id = " . $id . " and " . $peractual . " between perdesde and perhasta";
        $sql .= " Order by t.nombre, paramstr, param1, param2 ";
    	$sub3 = Yii::$app->db->createCommand($sql)->queryAll();
    	
    	return $datos;
    }
}
