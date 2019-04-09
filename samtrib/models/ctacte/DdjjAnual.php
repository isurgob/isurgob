<?php

namespace app\models\ctacte;

use Yii;
use yii\web\Session;
use yii\data\SqlDataProvider;
use app\utils\db\utb;
use app\utils\db\Fecha;

use app\utils\helpers\DBException;
 
class DdjjAnual extends \yii\db\ActiveRecord
{
	
	
	public $obj_dato;
	public $nombre;
	public $trib_id;
	public $sinanual;

	public function __construct(){
		
		parent::__construct();
		
		$this->base = 0;
		$this->fchpresenta = date('Y/m/d');
	}

	public static function tableName(){
		return 'comer_ddjj_anual';
	}

    /**
     * @inheritdoc
     */
    public function rules()
    {
		$ret = [];

		/**
		 * CAMPOS REQUERIDOS
		 */
		$ret[] = [
				'trib_id',
				'required',
				'on' => ['generar'],
				'message' => 'Seleccione un tributo'
				];
				
		$ret[] = [
				['obj_id'],
				'required',
				'on' => ['delete'],
				'message' => 'Seleccione un objeto'
				];

		$ret[] = [
				'anio',
				'required',
				'on' => ['agregar', 'generar', 'delete']
				];
				
		$ret[] = [
				'fchpresenta',
				'required',
				'on' => ['agregar']
				];
		/**
		 * FIN CAMPOS REQUERIDOS
		 */
		
		/**
		 * TIPO Y RANGO DE DATOS
		 */
		$ret[] = [
				'obj_id',
				'string',
				'min' => 8,
				'max' => 8,
				'on' => ['agregar', 'delete'],
				'message' => 'Seleccione un objeto válido'
				];
		
		//la fecha esta entre el año actual - 10 y el año actual - 1
		$ret[] = [
				'anio',
				'integer',
				'min' => (intval(date('Y')) - 10),
				'max' => (intval(date('Y')) - 1),
				'on' => ['agregar', 'delete', 'generar'],
				'tooSmall' => 'El año debe estar entre ' . (intval(date('Y')) - 10) .' y ' . (intval(date('Y')) - 1),
				'tooBig' => 'El año debe estar entre ' . (intval(date('Y')) - 10) .' y ' . (intval(date('Y')) - 1)
				];
		
		$ret[] = [
				'trib_id',
				'integer',
				'min' => 1,
				'on' => ['generar'],
				'message' => 'Seleccione un {aatribute}'
				];
				
		$ret[] = [
				'fchpresenta',
				'date',
				'format' => 'php:Y/m/d',
				'on' => ['agregar']
				];
				
		$ret[] = [
				'base',
				'number',
				'min' => 0,
				'on' => ['agregar']
				];
		
		$ret[] = [
				'sinfalta',
				'integer',
				'min' => 0,
				'max' => 1,
				'on' => 'generar'
				];
		
		$ret[]= [
				'sinanual',
				'integer',
				'min' => 0,
				'max' => 1,
				'on' => ['generarl']
				];
		/**
		 * FIN TIPO Y RANGO DE DATOS
		 */
		 
		/**
		 * VALORES POR DEFECTO
		 */
		$ret[] = [
				['base', 'auto'],
				'default',
				'value' => 0,
				'on' => ['agregar']
				];
				
		$ret[] = [
				'auto',
				'default',
				'value' => 1,
				'on' => ['generar']
				];
				
		$ret[] = [
				'sinfalta',
				'default',
				'value' => 0,
				'on' => ['generar']
				];
		/**
		 * FIN VALORES POR DEFECTO
		 */

		$ret[] = [
				'obj_id',
				function(){
					if(substr($this->obj_id, 0, 1) !== 'C')
						$this->addError($this->obj_id, 'El objeto no es un comercio');
				},
				'on' => ['agregar', 'delete']
				];
		
		$ret[] = [
				'obj_id',
				'existeObjeto',
				'on' => ['agregar', 'delete']
				];
				
		$ret[]= [
				'sinanual',
				'default',
				'value' => 0,
				'on' => 'generar'
				];
		
		return $ret;
    }
    
    public function scnearios(){
    	
    	return [
			'agregar' => ['obj_id', 'anio', 'fchpresenta', 'base'],
			'generar' => ['obj_id', 'anio', 'trib_id', 'sinanual'],
			'delete' => ['obj_id', 'anio']
    	];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'obj_id' => 'Código de objeto',
            'anio' => 'Año',
            'fchpresenta' => 'Fecha de presentación',
            'base' => 'Base',
            'trib_id' => 'Tributo',
            'sinanual' => 'Sólo si no tiene DJ anual'
        ];
    }
    
    /**
     * se le da formato a la fecha de presentacion para que pueda ser validada y guardada correctamente
     */
    public function beforeValidate(){
    	
    	if($this->getScenario() === 'agregar' && $this->fchpresenta !== null && trim($this->fchpresenta) != '')
    		$this->fchpresenta = Fecha::usuarioToBD($this->fchpresenta);

    	return true;
    }
    
    /**
     * Se carga el nombre y el dato del objto
     */
    public function afterFind(){
    	
    	$datos = utb::getVariosCampos('objeto', "obj_id = '$this->obj_id'", 'nombre, obj_dato');
    	
    	if($datos !== false){
    		
    		$this->nombre = $datos['nombre'];
    		$this->obj_dato = $datos['obj_dato'];
    	}
    	
    	$this->fchpresenta = substr($this->fchpresenta, 0, 10);
    }

	/**
	 * Agrega o modifica una DDJJ anual para el objeto
	 * 
	 * @return boolean - true si se ha grabado correctamente la DDJJ, false de lo contrario
	 */
	public function agregar(){
		
		$this->setScenario('agregar');
		if(!$this->validate()) return false;
		
		$trans = Yii::$app->db->beginTransaction();
		
		if($this->isNewRecord){
			
			//se comprueba si existe
			$sql = "Select Exists (Select 1 From comer_ddjj_anual Where obj_id = '$this->obj_id' And anio = $this->anio)";
			$res = Yii::$app->db->createCommand($sql)->queryScalar();
			
			if($res){
				
				$this->addError($this->obj_id, 'Ya existe una declaración jurada para el año ' . $this->anio);
				$trans->rollBack();
				return false;
			}
			
			//se crea la declaracion anual
			$sql = "Insert Into comer_ddjj_anual(obj_id, anio, fchpresenta, base, auto, fchmod, usrmod)" .
					" Values('$this->obj_id', $this->anio, '$this->fchpresenta', $this->base, 0, current_timestamp, " . Yii::$app->user->id . ");";
			$res = Yii::$app->db->createCommand($sql)->execute() > 0;
			
			if(!$res){
				$this->addError($this->obj_id, 'Ocurrrió un error al intentar realizar la acción');
				$trans->rollBack();
				return false;
			}
			
		} else {
			
			$sql = "Update comer_ddjj_anual Set fchpresenta = '$this->fchpresenta', base = $this->base, auto = 0, fchmod = current_timestamp, usrmod = " . Yii::$app->user->id .
					" Where obj_id = '$this->obj_id' And anio = $this->anio";
			$res = Yii::$app->db->createCommand($sql)->execute() > 0;
			
			if(!$res){
				$this->addError($this->obj_id, 'Ocurrió un error al intentar realizar la acción');
				$trans->rollBack();
				return false;
			}
		}
		
		$trans->commit();
		return true;
	}
	
	/**
	 * Borra una declaracion jurada anual del objeto dado
	 * 
	 * @return boolean - true si se ha borrado correctamente, false de lo contrario
	 */
	public function borrar(){
		
		$this->setScenario('delete');
		if(!$this->validate()) return false;
		
		$sql = "Delete From comer_ddjj_anual Where obj_id = '$this->obj_id' And anio = $this->anio";
		Yii::$app->db->createCommand($sql)->execute();
		
		return true;
	}

	/**
	 * Genera automaticamente las DDJJ para el tributo dado y el año
	 * 
	 * @return boolean - true si se han generado correctamente, false de lo contrario
	 */
	public function generar(){
		
		$sql = "Select sam.uf_comer_djanual_gen($this->trib_id, $this->anio, $this->sinanual, " . Yii::$app->user->id . ")";
		
		$trans = Yii::$app->db->beginTransaction();
		
		try{
			Yii::$app->db->createCommand($sql)->execute();
		} catch(\Exception $e){
			
			$this->addError($this->trib_id, DBException::getMensaje($e));
			$trans->rollBack();
			return false;
		}
		
		$trans->commit();
		return true;
	}
	
	/**
	 * Determina si el objeto es un comercio existente y que se encuentre activo
	 */
	public function existeObjeto(){
		
		$sql = "Select Exists (Select 1 From objeto Where obj_id = '$this->obj_id' And est = 'A')";
		$res = Yii::$app->db->createCommand($sql)->queryScalar();
		
		if(!$res)
			$this->addError($this->obj_id, 'El comercio no existe o se encuentra dado de baja');
	}
}
