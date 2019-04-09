<?php

namespace app\models\config;
use yii\data\SqlDataProvider;

use app\models\config\Rubro;

use Yii;

/**
 * This is the model class for table "rubro".
 *
 * @property integer $rubro_id
 * @property string $nombre
 * @property string $grupo
 * @property string $tunidad
 * @property integer $osmreccons
 * @property integer $tipif_id
 * @property string $fchmod
 * @property integer $usrmod
 */
class Rubro extends \yii\db\ActiveRecord
{	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'v_rubro';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
       $ret= [];
       
       /**
        * CAMPOS REQUERIDOS
        */
       $ret[]= [
       			'rubro_id',
       			'required',
       			'on' => ['insert','update', 'delete']
       			];
       
       $ret[]= [
       			['nombre'],
       			'required',
       			'on' => ['insert', 'update']
       			];
				
		$ret[]= [
       			['nomen_id'],
       			'required',
       			'on' => ['insert', 'update']
       			];		
				
       /**
        * FIN CAMPOS REQUERIDOS
        */
		
		/**
		 * TIPO Y RANGO DE DATOS
		 */
				
		$ret[]= [
				'nombre',
				'string',
				'max' => 100,
				'on' => ['insert', 'update']
				];
				
		$ret[]= [
				'grupo',
				'string',
				'max' => 2,
				'on' => ['insert', 'update'],
				'message' => 'Elija un grupo'
				];
				
		$ret[]= [
				'tunidad',
				'string',
				'max' => 8,
				'on' => ['insert', 'update'],
				'message' => 'Elija un tipo de unidad'
				];
		/**
		 * FIN TIPO Y RANGO DE DATOS
		 */
		
		$ret[]= [
				'nombre',
				'nombreExiste',
				'on' => ['insert', 'update']
				];
				
		return $ret;
    }
    
    public function scenarios(){
    	
    	return [
    	
    		'insert' => ['rubro_id', 'nomen_id', 'nombre', 'grupo', 'tunidad'],
    		'update' => ['rubro_id', 'nomen_id', 'nombre', 'grupo', 'tunidad'],
    		'delete' => ['rubro_id']	
    	];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rubro_id' => 'Rubro',
            'nombre' => 'Nombre',
            'grupo' => 'Grupo',
            'tunidad' => 'Tipo de unidad',
            'osmreccons' => 'Tipo de recargo',
            'tipif_id' => 'Tipificacion',
            'fchmod' => 'Fecha de modificacion',
            'usrmod' => 'Codigo de usuario que modifico',
			'nomen_id' => 'Nomeclador'
        ];
    }
    
    public function validar($accion,$nombre,$rubro_id){
    	
    	$error="";
    	if($accion==0){
		$sql = "SELECT count(*) FROM rubro WHERE nombre='".$nombre."'";
    	$cantidad = Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	if($cantidad > 0) $error .= "<li>El nombre ingresado ya existe</li>";

    	}else if($accion==3){
    		
    		$sql = "SELECT nombre FROM rubro WHERE rubro_id='".$rubro_id."'";
	    	$nombresql = Yii::$app->db->createCommand($sql)->queryScalar();
	    	
	    	if($nombre != $nombresql){
				$sql = "SELECT count(*) FROM rubro WHERE nombre='".$nombre."'";
	    		$cantidad = Yii::$app->db->createCommand($sql)->queryScalar();
	    		
	    		if($cantidad > 0){
	    			$error .= "<li>El nombre ingresado ya existe</li>";
	    		}	    		
	    	}
    		
    	}
    	return $error;
    }


	public function grabar(){
		
		$scenario= $this->isNewRecord ? 'insert' : 'update';
		$this->setScenario($scenario);
		
		if(!$this->validate()) return false;
		
		$trans= Yii::$app->db->beginTransaction();
		
		if($this->isNewRecord){
			
			$this->rubro_id = $this->nomen_id . str_pad( $this->rubro_id, 7, "0", STR_PAD_LEFT);
						
			$sql= "Insert Into rubro(rubro_id, nombre, nomen_id, grupo, tunidad, osmreccons, tipif_id, fchmod, usrmod)" .
					"Values('$this->rubro_id', '$this->nombre', '$this->nomen_id', '$this->grupo', '$this->tunidad', 0, 0" .
					", current_timestamp, " . Yii::$app->user->id . ")";
			
			try{
				$cant= Yii::$app->db->createCommand($sql)->execute() > 0;
			
			} catch(\Exception $e){
				
				$this->rubro_id = intVal(substr( $this->rubro_id, 1));
				$this->addError($this->rubro_id, $e->getMessage());
				$trans->rollBack();
				return false;
			}
		} else {
			
			$sql= "Update rubro Set nombre = '$this->nombre', grupo = '$this->grupo', tunidad = '$this->tunidad'" .
					", osmreccons = $this->osmreccons, tipif_id = $this->tipif, fchmod = current_timestamp, usrmod = " . Yii::$app->user->id . 
					" Where rubro_id = '$this->rubro_id'";
					
			try{
				Yii::$app->db->createCommand($sql)->execute();	
			} catch(\Exception $e){
				
				$this->addError($this->rubro_id, 'Ocurrió un error al intentar realizar la acción');
				$trans->rollBack();
				return false;
			}
			
		}
		
		$trans->commit();
		return true;
	}
	
	public function borrar(){

		$this->setScenario('delete');
		if(!$this->validate()) return false;
		
		$sql = "DELETE FROM rubro WHERE rubro_id = '$this->rubro_id'";

		try{					
			$cmd = Yii::$app->db->createCommand($sql);
			$cmd->execute();
	 	}
	 	catch(\Exception $e){
	 		$this->addError($this->rubro_id, 'Ocurrió un error al intentar realizar la acción');
	 		return false;
	 	}    	
    	
    	return true;
    }
    
     public function buscarRubro($cond){  
     	
     	$sql = "select rubro_id,nombre,grupo,tunidad,osmreccons,tipif_id";
        $sql .= " from rubro";
        
        $pag = "select count(*)";
        $pag .= " from rubro"; 	
        
        if ($cond != ""){ $sql = $sql.' where '.$cond; $pag = $pag.' where '.$cond;}
        
        $sql .= " Order By nombre";
        
        $count = Yii::$app->db->createCommand($pag)->queryScalar();
         
       $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => (int)$count,
			'pagination'=> [
			'pageSize'=>20,
			],
        ]);
        
        return $dataProvider;
        
    }

	public function vigencias(){
		
		return RubroVigencia::find()->where(['rubro_id' => $this->rubro_id])->all();
	}
	
	public function vigencia($perdesde = 0, $perhasta = 0){
		
		if($this->rubro_id == '') return new RubroVigencia();
		
		$periodoActual= intval(date('Y')) * 1000 + intval(date('m'));
		
		if($perdesde === 0 && $perhasta === 0)
			return RubroVigencia::find()->where(['rubro_id' => $this->rubro_id])->andWhere(['<=', 'perdesde', $periodoActual])->andWhere(['>=', 'perhasta', $periodoActual])->one();
		else return RubroVigencia::find()->where(['rubro_id' => $this->rubro_id])->andWhere(['<=', 'perdesde', $perdesde])->andWhere(['>=', 'perhasta', $perhasta])->one();
	}
	
	public function nombreExiste(){
		
		$sql= "Select Exists (Select 1 From rubro Where nombre = '$this->nombre'";
		
		if(!$this->isNewRecord) $sql .= " And rubro_id <> '$this->rubro_id'";
		
		$sql .= ")";
		
		$existe= Yii::$app->db->createCommand($sql)->queryScalar();
		
		if($existe) $this->addError($this->nombre, 'Ya existe un rubro con el mismo nombre');
	}
	
	public static function todos($nomec = 0, $nombre = '', $grupo = '', $codigo = ''){
		
		$sql= "Select * From rubro";
		
		$condicion= '';
		
		if($nomec != '') $condicion= "SUBSTRING(rubro_id, 1, 1) = '$nomec'";
		
		if($nombre != ''){
			$c= " upper(nombre) Like upper('%$nombre%')";
			$condicion .= $condicion == '' ? $c : " And $c";
		}
		
		if($grupo != ''){
			$c= " grupo = '$grupo'";
			$condicion .= $condicion == '' ? $c : " And $c";
		}
		
		if($codigo != ''){
			$c= "rubro_id = '$codigo'";
			$condicion .= $condicion == '' ? $c : " And $c";
		}
		
		
		if($condicion != '') $sql .= " Where $condicion";
		
		return Yii::$app->db->createCommand($sql)->queryAll();
	}
}
