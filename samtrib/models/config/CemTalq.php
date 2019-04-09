<?php

namespace app\models\config;

use Yii;

use app\utils\db\Fecha;
use app\utils\db\utb;
/**
 * This is the model class for table "cem_talq".
 *
 * @property integer $cod
 * @property string $desde
 * @property string $hasta
 * @property string $tipo
 * @property string $cuadrodesde
 * @property string $cuadrohasta
 * @property string $cuerpo_id
 * @property string $fila
 * @property integer $cat
 * @property integer $supdesde
 * @property integer $suphasta
 * @property integer $duracion
 * @property string $fchmod
 * @property integer $usrmod
 */
class CemTalq extends \yii\db\ActiveRecord
{
	
	public $tipo_nombre;
	public $cuadro_desde_nombre;
	public $cuadro_hasta_nombre;
	public $modif;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'v_cem_talq';
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
				'tipo',
				'required',
				'isEmpty' => function($model){return $this->tipo === '' || $this->tipo === '0';},
				'on' => ['insert', 'update'],
				'message' => 'Elija un tipo'
				];
		
		$ret[]= [
				['desde', 'hasta'],
				'required',
				'on' => ['insert', 'update']
				];
				
		$ret[]= [
				'cod',
				'required',
				'on' => ['update', 'delete']
				];
		
		/**
		 * RANGO Y TIPO DE DATOS
		 */
		$ret[]= [
				'cod',
				'integer',
				'min' => 1,
				'on' => ['update', 'delete']
				];
		
		$ret[]= [
				['desde', 'hasta'],
				'date',
				'format' => 'php:Y/m/d',
				'on' => ['insert', 'update']
				];

		$ret[]= [
				'fila',
				'string',
				'max' => 3,
				'on' => ['insert', 'update']
				];
				
		$ret[]= [
				['cat', 'supdesde', 'suphasta', 'duracion'],
				'integer',
				'min' => 0,
				'on' => ['insert', 'update']
				];


		//superposiciones de valores
		$ret[]= [
				'desde',
				'validarFechas',
				'on' => ['insert', 'update']
				];
				
		$ret[]= [
				'supdesde',
				'compare',
				'compareAttribute' => 'suphasta',
				'operator' => '<=',
				'type' => 'number',
				'on' => ['insert', 'update'],
				'message' => 'Rango de superficies incorrecto'
				];

		/**
		 * FILTRADO DE DATOS
		 */
		$ret[]= [
				['tipo', 'cuadrodesde', 'cuadrohasta', 'cuerpo_id'],
				'filter',
				'filter' => function($attr){return $attr == '0' ? '' : $attr;},
				'on' => ['insert', 'update']
				];
		
		/**
		 * EXISTENCIA
		 */
		$ret[]= [
				'tipo',
				'existeTipo',
				'on' => ['insert', 'update']
				];
				
		$ret[]= [
				['cuadrodesde', 'cuadrohasta'],
				'existeCuadro',
				'on' => ['insert', 'update']
				];
				
		$ret[]= [
				'cuerpo_id',
				'existeCuerpo',
				'on' => ['insert', 'udpate']
				];
				
		$ret[]= [
				'cuadrodesde',
				function(){
					
					if($this->cuadrodesde != '' && $this->cuadrodesde != '0' && ($this->cuadrohasta == '' || $this->cuadrohasta == '0'))
						$this->addError($this->cuadrohasta, 'Elija un cuadro hasta');
				},
				'on' => ['insert', 'update']
				];
				
		$ret[]= [
				'cuadrohasta',
				function(){
					
					if($this->cuadrohasta != '' && $this->cuadrohasta != '0' && ($this->cuadrodesde == '' || $this->cuadrodesde == '0'))
						$this->addError($this->cuadrodesde, 'Elija un cuadro desde');
				},
				'on' => ['insert', 'update']
				];
				
				
		/**
		 * VALORES POR DEFECTO
		 */
		$ret[]= [
				['cuadrodesde', 'cuadrohasta', 'cuerpo_id', 'fila'],
				'default',
				'value' => '',
				'on' => ['insert', 'update']
				];
				
		$ret[]= [
				['cat', 'supdesde', 'suphasta', 'duracion'],
				'default',
				'value' => 0,
				'on' => ['insert', 'update']
				];
		
		
		return $ret;
    }
    
    public function scenarios(){
    	
    	return [
    			'insert' => ['tipo', 'desde', 'hasta', 'cuadrodesde', 'cuadrohasta', 'cuerpo_id', 'fila', 'cat', 'supdesde', 'suphasta', 'duracion', 'modif'],
    			'update' => ['cod', 'tipo', 'desde', 'hasta', 'cuadrodesde', 'cuadrohasta', 'cuerpo_id', 'fila', 'cat', 'supdesde', 'suphasta', 'duracion', 'modif'],
    			'delete' => ['cod']
    	];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod' => 'Código',
            'desde' => 'Fecha desde',
            'hasta' => 'Fecha hasta',
            'tipo' => 'Tipo',
            'cuadrodesde' => 'Cuadro desde',
            'cuadrohasta' => 'Cuadro hasta',
            'cuerpo_id' => 'Cuerpo',
            'fila' => 'Fila',
            'cat' => 'Categoría',
            'supdesde' => 'Superficie desde',
            'suphasta' => 'Superficie hasta',
            'duracion' => 'Duración'
        ];
    }
    
	public function beforeValidate(){
   	
		if(in_array($this->getScenario(), ['insert', 'update'])){
			
			if($this->desde != null && $this->desde != '')
				$this->desde= Fecha::usuarioToBD($this->desde);
				
			if($this->hasta != null && $this->hasta != '')
				$this->hasta= Fecha::usuarioToBD($this->hasta);
		}
		
		return true;
	}
	
	public function afterFind(){
		
		$this->tipo_nombre= utb::getCampo('cem_tipo', "cod = '$this->tipo'", "nombre");
		$this->cuadro_desde_nombre= utb::getCampo('cem_cuadro', "cuadro_id = '$this->cuadrodesde'", "nombre");
		$this->cuadro_hasta_nombre= utb::getCampo('cem_cuadro', "cuadro_id = '$this->cuadrohasta'", "nombre");
		
		if($this->cuadro_desde_nombre == '') $this->cuadro_desde_nombre= null;
		if($this->cuadro_hasta_nombre == '') $this->cuadro_hasta_nombre= null;
	}
	
	public function grabar(){
		
		$scenario= $this->isNewRecord ? 'insert' : 'update';
		$this->setScenario($scenario);
		
		if(!$this->validate()) return false;
		
		$sql= '';
		if($this->isNewRecord){
			
			$sql= "Select Coalesce(Max(cod), 0) + 1 From cem_talq";
			$codigo= Yii::$app->db->createCommand($sql)->queryScalar();
			
			$sql= "Insert Into cem_talq(cod, desde, hasta, tipo, cuadrodesde, cuadrohasta, cuerpo_id, fila, cat, supdesde, suphasta, duracion, fchmod, usrmod)" .
					" Values($codigo, '$this->desde', '$this->hasta', '$this->tipo', '$this->cuadrodesde', '$this->cuadrohasta', '$this->cuerpo_id'," .
					" '$this->fila', $this->cat, $this->supdesde, $this->suphasta, $this->duracion, current_timestamp, " . Yii::$app->user->id . ")";

			
		} else {
			
			$sql= "Update cem_talq Set desde = '$this->desde', hasta = '$this->hasta', tipo = '$this->tipo', cuadrodesde = '$this->cuadrodesde', cuadrohasta = '$this->cuadrohasta'," .
					" cuerpo_id = '$this->cuerpo_id', fila = '$this->fila', cat = $this->cat, supdesde = $this->supdesde, suphasta = $this->suphasta," .
					" duracion = $this->duracion, fchmod = current_timestamp, usrmod = " . Yii::$app->user->id . " Where cod = $this->cod";
		}
		
		$res= Yii::$app->db->createCommand($sql)->execute() > 0;
			
		if(!$res){
			$this->addError($this->cod, 'Ocurrió un error al intentar realizar la operación');
			return false;
		}
		
		return true;
	}
	
	public function borrar(){
		
		$this->setScenario('delete');
		if(!$this->validate()) return false;
		
		$sql= "Delete From cem_talq Where cod = $this->cod";
		Yii::$app->db->createCommand($sql)->execute();
		
		return true;
	}
     
	public function todos(){

		$sql= "Select *, (usrmod_nom || ' - ' || to_char(fchmod, 'dd/mm/yyyy')) as modif From v_cem_talq";
		return CemTalq::findBySql($sql)->all();
//		$sql= "SELECT cem_talq.cod ,cem_talq.desde,cem_talq.hasta,cem_talq.tipo,cem_talq.cuadrodesde,cem_talq.cuadrohasta,cem_talq.cuerpo_id" .
//    		   ",cem_talq.fila ,cem_talq.cat,cem_talq.supdesde,cem_talq.suphasta,cem_talq.duracion" .
//    		   ",usr.nombre || ' - ' || to_char(cem_talq.fchmod,'dd/mm/yyyy') as modif ".
//    		   "FROM cem_talq,sam.sis_usuario usr " .
//    		   "WHERE cem_talq.usrmod = usr.usr_id " .
//    		   "ORDER BY cem_talq.cod ASC";
//    		   
//		return Yii::$app->db->createCommand($sql)->queryAll();
	}
	
	public function existeTipo(){
		
		$sql= "Select Exists (Select 1 From cem_tipo Where cod = '$this->tipo')";
		$existe= Yii::$app->db->createCommand($sql)->queryScalar();
		
		if(!$existe) $this->addError($this->tipo, 'El tipo no existe');
	}
	
	public function existeCuadro($atributo){
		
		$sql= "Select Exists (Select 1 From cem_cuadro Where cuadro_id = '" . $this->$atributo . "')";
		$existe= Yii::$app->db->createCommand($sql)->queryScalar();
		
		if(!$existe) $this->addError($this->$atributo, 'El ' . $this->attributeLabels()[$atributo] . ' no existe');
	}
	
	public function existeCuerpo(){
		
		$sql= "Select Exists (Select 1 From cem_cuerpo Where cuerpo_id = '$this->cuerpo_id')";
		$existe= Yii::$app->db->createCommand($sql)->queryScalar();
		
		if(!$existe) $this->addError($this->cuerpo_id, 'El cuerpo no existe');
	}
	
	public function validarFechas(){
		
		if(Fecha::menor($this->hasta, $this->desde)) $this->addError($this->hasta, 'La fecha desde no puede ser posterior a la fecha hasta');
	}
}
