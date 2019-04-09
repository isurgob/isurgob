<?php
namespace app\models\objeto;

use Yii;

use yii\data\SqlDataProvider;
use app\utils\db\Fecha;

/**
 * This is the model class for table "cem".
 *
 * @property string $obj_id
 * @property string $nc
 * @property string $cuadro_id
 * @property string $cuerpo_id
 * @property string $tipo
 * @property integer $piso
 * @property string $fila
 * @property integer $nume
 * @property integer $cat
 * @property integer $deleg
 * @property integer $sup
 * @property string $tomo
 * @property string $folio
 * @property string $fchcompra
 * @property string $fchingreso
 * @property string $fchvenc
 * @property integer $exenta
 * @property integer $edicto
 * @property string $cod_ant
 */
class CemFall extends \yii\db\ActiveRecord
{	
	//almacena el domicilio postal
	public $domicilio = null;
	
	
	//datos para servicio
	public $tserv;
	public $fecha;
	public $acta;
	public $resp;
	public $destino;
	
	public function __construct(){
		
		parent::__construct();
		
		$this->fchdef= $this->fchinh= date('Y/m/d');
	}
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cem_fall';
    }
    
    public function rules(){
    	
    	/*
    	 * CAMPOS REQUERIDOS
    	 */
		
		$ret[]= [
				'apenom',
				'required',
				'on' => ['insert', 'update']
				];
		
    	$ret[] = [
    			['nacionalidad'],
    			'required',
    			'on' => ['insert', 'update'],
    			'message' => 'Elija una nacionalidad'
    			];
    			
    	$ret[] = [
    			'fall_id',
    			'required',
    			'on' => ['select', 'update', 'delete', 'sevicio', 'cbioparcelagrabar']
    			]; 
				
		$ret[] = [
    			'obj_id',
    			'required',
    			'on' => ['cbioparcelagrabar']
    			]; 		
    			
    	$ret[] = [
    			['tservcicio', 'resp', 'fecha'],
    			'required',
    			'on' => ['servicio']
    			];
    	/*
    	 * FIN CAMPOS REQUERIDOS
    	 */
    	 
    	/*
    	 * TIPO Y RANGO DE DATOS
    	 */
    	$ret[] = [
    			['apenom', 'domi'],
    			'string',
    			'max' => 50,
    			'on' => ['insert', 'update']
    			]; 
    			
    	$ret[] = [
    			'actadef',
    			'string',
    			'max' => 15,
    			'on' => ['insert', 'update']
    			];

    			
    	$ret[] = [
    			'causamuerte',
    			'integer',
    			'min' => 1,
    			'on' => ['insert', 'update'],
    			'message' => 'Elija una causa de muerte'
    			];
    			
    	$ret[] = [
    			['emp_funebre', 'estcivil'],
    			'integer',
    			'min' => 0,
    			'on' => ['insert', 'update']
    			];
    			
    	$ret[] = [
    			'fall_id',
    			'integer',
    			'min' => 1,
    			'on' => ['select', 'update', 'delete']
    			];
    			
    	$ret[] = [
    			['fchdef', 'fchinh', 'fchnac'],
    			'date',
    			'format' => 'dd/mm/yyyy',
    			'skipOnEmpty' => true,
    			'isEmpty' => function($value){return ($value == null || trim($value) == '');},
    			'on' => ['insert', 'update', 'servicio']
    			];
    			
    	$ret[] = [
    			['fecha'],
    			'date',
    			'format' => 'yyyy/mm/dd',
    			'skipOnEmpty' => true,
    			'isEmpty' => function($value){return ($value == null || trim($value) == '');},
    			'on' => ['insert', 'update', 'servicio']
    			];
    			
    	$ret[] = [
    			['foliodef', 'med_matricula'],
    			'string',
    			'max' => 10,
    			'on' => ['insert', 'update']
    			];
    			
    	$ret[] = [
    			'med_nombre',
    			'string',
    			'max' => 35,
    			'on' => ['insert', 'update']
    			];
    			
    	$ret[] = [
    			'ndoc',
    			'integer',
    			'min' => 0,
    			'max' => 999999999,
    			'on' => ['insert', 'update']
    			];
    			
    	$ret[] = [
				'obj_id',
				'string',
				'min' => 1,
				'on' => ['update', 'delete']
				];
				
		$ret[] = [
				'obs',
				'string',
				'max' => 500,
				'on' => ['insert', 'update']
				];
				
		$ret[] = [
				'resp',
				'string',
				'max' => 8,
				'on' => ['insert', 'update', 'servicio']
				];
				
		$ret[] = [
				['obs', 'destino'],
				'string',
				'max' => 500,
				'on' => ['servicio']
				];
				
		$ret[]= [
				'tdoc',
				'integer',
				'min' => 0,
				'on' => ['insert', 'update'],
				'message' => 'Elija un tipo de documento'
				];
    	/*
    	 * FIN TIPO Y RANGO DE DATOS
    	 */    			
    	 
    	/*
    	 * VALORES POR DEFECTO
    	 */
    	$ret[] = [
    			['obj_id', 'apenom', 'actadef', 'foliodef', 'domi', 'med_nombre', 'med_matricula', 'resp', 'obs'],
    			'default',
    			'value' => '',
    			'on' => ['insert', 'delete']
    			];
    	
    	//sexo toma el valor I = indefinido
    	$ret[] = [
    			'sexo',
    			'default',
    			'value' => 'I',
    			'on' => ['insert', 'update']
    			];
    			
    	$ret[] = [
    			['emp_funebre', 'causamuerte', 'estcivil', 'ndoc', 'tdoc', 'procedencia', 'indigente'],
    			'default',
    			'value' => 0,
    			'on' => ['insert', 'update']
    			];
    			
    	$ret[] = [
    			['obs', 'destino'],
    			'default',
    			'value' => '',
    			'on' => ['servicio']
    			];
    	/*
    	 * FIN VALORES POR DEFECTO
    	 */
    	 
    	/*
    	 * FILTROS 
    	 */
		$ret[] = [
				'fecha',
				function(){
					
					if(Fecha::esFuturo($this->fecha))
						$this->addError($this->fecha, 'La fecha no puede ser futura');
				},
				'on' => ['servicio']
				];
    	 
    	 
    	$ret[] = [
    			'sexo',
    			'filter',
    			'filter' => function($value){return strtoupper($value);},
    			'on' => ['insert', 'update']
    			];
    	/*
    	 * FIN FILTROS
    	 */
    	
    	return $ret;
    }
    
    public function scenarios(){
    	return [
    		'insert' => ['actadef', 'apenom', 'causamuerte', 'domi', 'emp_funebre', 'estcivil', 'fchdef', 'fchinh', 'fchnac', 'foliodef', 'tdoc',
						'indigente', 'med_nombre', 'med_matricula', 'med_nombre', 'nacionalidad', 'ndoc', 'obs', 'procedencia', 'resp', 'sexo'],
    		'select' => ['fall_id'],
    		'update' => ['actadef', 'apenom', 'causamuerte', 'domi', 'emp_funebre', 'estcivil', 'fchdef', 'fchinh', 'fchnac', 'foliodef', 'tdoc',
    					'indigente', 'med_nombre', 'med_matricula', 'med_nombre', 'nacionalidad', 'ndoc', 'obs', 'procedencia', 'resp', 'sexo',
    					'fall_id'],
    		'delete' => ['fall_id'],
    		'servicio' => ['fall_id', 'tserv', 'fecha', 'acta', 'resp', 'obs', 'destino'],
			'cbioparcelagrabar' => ['fall_id', 'obj_id']
    	];
    }

	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'obj_id' => 'Código del objeto',
            'fall_id' => 'Código del fallecido',
            'apenom' => 'Nombre',
            'est' => 'Estado',
            'actadef' => 'Acta de defunción',
            'causamuerte' => 'Causa de muerte',
            'domi' => 'Domicilio',
            'emp_funebre' => 'Empresa funebre',
            'estcivil' => 'Estado civil',
            'fchdef' => 'Fecha de función',
            'fhcinh' => 'Fecha de inhumación',
            'fchnac' => 'Fecha de nacimiento',
            'foliodef' => 'Folio',
            'indigente' => 'Indigente',
            'med_matricula' => 'Matrícula del médico',
            'med_nombre' => 'Nombre del médico',
            'nacionalidad' => 'Nacionalidad',
            'ndoc' => 'Número de documento',
            'obs' => 'Observación',
            'procedencia' => 'Procedencia',
            'resp' => 'Responsable',
            'sexo' => 'Sexo',
            'tdoc' => 'Tipo de documento'
        ];
    }
    
    public function beforeValidate(){
    	
    	if($this->fecha !== null && trim($this->fecha) != '')
    		$this->fecha = Fecha::usuarioToBD($this->fecha);
    		
    		
    	return true;
    }
    
    public function grabar(){
    	
    	$this->scenario = $this->isNewRecord ? 'insert' : 'update';
    	
    	if(!$this->validate()) return false;
    		
    	if($this->isNewRecord){
    		
    		$codigo = Yii::$app->db->createCommand("Select nextval('seq_cem_fall_id')")->queryScalar() + 1;
    		
    		$sql = "Insert Into cem_fall(fall_id, obj_id, est, tdoc, ndoc, apenom, fchnac, nacionalidad, sexo, estcivil, domi, actadef, foliodef, fchdef, fchinh, causamuerte, procedencia, " .
    				"med_nombre, med_matricula, emp_funebre, resp, indigente, obs, usrmod, fchmod)";
    		$sql .= " Values($codigo, '', 'INH', $this->tdoc, $this->ndoc, '$this->apenom', " .
    				"" . (($this->fchnac == null || $this->fchnac == '') ? 'null' : "'$this->fchnac'") .
    				", $this->nacionalidad, upper('$this->sexo'), $this->estcivil, '$this->domi', '$this->actadef', '$this->foliodef'" .
    				", " . (($this->fchdef == null || $this->fchdef == '') ? 'null' : "'$this->fchdef'") .
    				", " . (($this->fchinh == null || $this->fchinh == '') ? 'null' : "'$this->fchinh'") .
    				", $this->causamuerte, $this->procedencia, '$this->med_nombre', '$this->med_matricula', $this->emp_funebre, '$this->resp', $this->indigente, '$this->obs', " . Yii::$app->user->id . ", current_timestamp" .
    				")";
    				
    		$res = Yii::$app->db->createCommand($sql)->execute() > 0;
    		
    		if($res)
    			$this->fall_id = $codigo;
    			
    		return $res;
    	}
    	else {

    		$sql = "Update cem_fall Set tdoc = $this->tdoc, ndoc = $this->ndoc, apenom = '$this->apenom'";
			$sql .= ", fchnac = " . (($this->fchnac == null || $this->fchnac == '') ? 'null' : "'$this->fchnac'");
    		$sql .= ", nacionalidad = $this->nacionalidad, sexo = '$this->sexo', estcivil = $this->estcivil, domi = '$this->domi'";
    		$sql .= ", actadef = '$this->actadef', foliodef = '$this->foliodef'";
    		$sql .= ", fchdef = " . (($this->fchdef == null || $this->fchdef == '') ? 'null' : "'$this->fchdef'");
    		$sql .= ", fchinh = " . (($this->fchinh == null || $this->fchinh == '') ? 'null' : "'$this->fchinh'");
    		$sql .= ", causamuerte = $this->causamuerte, procedencia = $this->procedencia, med_nombre = '$this->med_nombre'";
    		$sql .= ", med_matricula = '$this->med_matricula', emp_funebre = $this->emp_funebre, resp = '$this->resp', indigente = $this->indigente";
    		$sql .= ", obs = '$this->obs', fchmod = current_timestamp, usrmod = " . Yii::$app->user->id . " Where fall_id = $this->fall_id";
    		
    		return Yii::$app->db->createCommand($sql)->execute() > 0;
    	}
    	
    	return false;
    }   
    
    public function borrar(){
    	
    	$this->scenario = 'delete';
    	
    	if(!$this->validate())
    		return false;
    		
    		
    	//se comprueba si el fallecido esta asociado a una cuenta de cementerio
    	$sql = "Select obj_id From cem Where obj_id = '$this->obj_id'";
    	$obj_id = Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	if($obj_id !== false && ($obj_id != null && trim($obj_id) != '')){
    		$this->addError($this->obj_id, 'El fallecido está asociado a una cuenta. No podrá eliminarlo');
    		return false;
    	}
    		
    	//se termina de comprobar si el fallecido esta asociado a una cuenta de cementerio
    	
    	//para dar de baja se le cambia el estado a BA
    	$sql = "Update cem_fall Set est = 'BA', fchmod = current_timestamp, usrmod = " . Yii::$app->user->id . " Where fall_id = $this->fall_id";
    	$res = Yii::$app->db->createCommand($sql)->execute();
    	
    	if($res === false || count($res) == 0){
    		$this->addError($this->fall_id, 'Ocurrio un error al intentar dar de baja el fallecido');
    		return false;
    	}
    		
    	return true;
    } 
    
    public function grabarServicio($codigoObjetoDestino = ''){
    	
    	$this->setScenario('servicio');
    	
    	if(!$this->validate())
    		return false;
    	
    	$trans = Yii::$app->db->beginTransaction();
    	
    	//se comprueba el que responsable exista
    	$sql = "Select Exists (Select 1 From persona Where obj_id = '$this->resp')";
    	$existe = Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	if(!$existe){
    		$trans->rollBack();
    		$this->addError($this->fall_id, 'El responsable no existe');
    		return false;
    	}
    	
    	//se obtiene el estado final del servicio que se brinda
    	$sql = "Select est_fin From cem_fall_tserv Where cod = '$this->tserv'";
    	$estadoFinal = Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	//se obtiene el orden
    	$sql = "Select (Coalesce(Max(orden), 0)) + 1 From cem_fall_serv Where fall_id = $this->fall_id";
    	$orden = Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	//se inserta el registro del servicio
    	$sql = "Insert Into cem_fall_serv(fall_id, orden, tserv, fecha, acta, resp, obj_id_ori, obj_id_dest, destino, obs, fchmod, usrmod)" .
    			" Values($this->fall_id, $orden, '$this->tserv', '$this->fecha', '$this->acta', '$this->resp', '$this->obj_id', '$codigoObjetoDestino'," .
    			" '$this->destino', '$this->obs', current_timestamp, " . Yii::$app->user->id . ")";
    			
    	$res = Yii::$app->db->createCommand($sql)->execute();
    	
    	//se graba el servicio
    	if($res){
    		
    		//si hay estado final para el tipo de servicio, se actualiza el estado del fallecido
    		if($estadoFinal !== false && trim($estadoFinal) == ''){
    			
    			$sql = "Update cem_fall Set est = '$estadoFinal'";
    			if(in_array($estadoFinal, ['DE', 'OS', 'TRA'])) $sql .= ", obj_id = ''";
    			$sql .= "Where fall_id = $this->fall_id";
    			
    			$res = Yii::$app->db->createCommand($sql)->execute();
    			
    			if(!$res){
    				$trans->rollBack();
    				$this->addError($this->fall_id, 'Error al intentar actualizar el estado del fallecido');
    				return false;
    			}
    		}
    		
			//se ingreso un objeto de destino
    		if($codigoObjetoDestino != ''){
    			
    			//se verifica que el codigo del objeto de destino tenga la longitud correcta
		    	$codigoObjetoDestino = trim($codigoObjetoDestino);
		    	if(strlen($codigoObjetoDestino) < 8){
		    		$this->addError($this->fall_id, 'Código de objeto de destino incorrecto');
		    		return false;
		    	}
		    	
		    	
    			//se actualiza la cuenta y el responsable del fallecido en caso de que se haya ingresado un codigo de objeto destino    			
    			$sql = "Update cem_fall Set obj_id = '$codigoObjetoDestino', resp = '$this->resp' Where fall_id = $this->fall_id";
    			$res = Yii::$app->db->createCommand($sql)->execute();
    			
    			if(!$res){
    				$trans->rollBack();
    				$this->addError($this->fall_id, 'Error al intentar actualizar el objeto de destino');
    				return false;
    			}
    			
    			//se actualiza el estado de la cuenta de destino
    			$sql = "Update objeto Set est = 'O' Where obj_id = '$codigoObjetoDestino'";
    			$res = Yii::$app->db->createCommand($sql)->execute();
    			
    			if(!$res){
    				$trans->rollBack();
    				$this->addError($this->fall_id, 'Error al intentar actualizar el estado del objeto de destino');
    				return false;
    			}
    			
    			//se actualiza la fecha de ingreso en caso de que no exista
    			$sql = "Update cem Set fchingreso = '$this->fecha' Where obj_id = '$codigoObjetoDestino' And fchingreso Is Null";
    			$res = Yii::$app->db->createCommand($sql)->execute();
    			
    			if($res){
    				$trans->rollBack();
    				$this->addError($this->fall_id, 'Error al intentar actualizar la fecha de ingreso');
    				return false;
    			}
    			
    			//se actualiza el estado del objeto origen en caso de que no tenga mas fallecidos
				if ($this->obj_id != '') {
    			   $sql = "Select Exists( Select 1 From cem_fall Where obj_id = '$this->obj_id')";
    			   $cantidad = Yii::$app->db->createCommand($sql)->queryScalar();
    			
    			   if($cantidad == 0){
    				
    				$sql = "Update objeto Set est = 'D' Where obj_id = '$this->obj_id'";
    				$res = Yii::$app->db->createCommand($sql)->execute();
    				
    				if(!$res){
    					$trans->rollBack();
	    				$this->addError($this->fall_id, $sql.'Error al intentar actualizar el estado del objeto de origen');
	    				return false;
    				}
    			   }
				}  
    		}
    	} else{
    		
    		$trans->rollBack();
    		$this->addError($this->fall_id, 'Error al intentar grabar el servicio');
    		return false;
    	}
    	
    	
    	$trans->commit();
    	return true;
    }
	
	public function GrabarCbioParcela(){
	
		try{
    		$sql = "update cem_fall set obj_id='$this->obj_id' where fall_id=$this->fall_id";
			Yii::$app->db->createCommand($sql)->execute();
    	}
    	catch (\Exception $e){
    		$this->addError($this->fall_id, $e->getMessage());
    		return false;
    	}
    	
    	return true;
	}
    
    public static function getservicios($fall_id = 0){
    	
    	if(!isset($fall_id) || $fall_id == null)
    		$fall_id = 0;
    	
    	$sql = "Select * From v_cem_fall_serv Where fall_id = $fall_id";
    	
    	return Yii::$app->db->createCommand($sql)->queryAll();
    }
    
    public function Imprimir($id,&$sub1)
    {
    	$sql = "Select * From V_Cem_Fall Where Fall_id=".$id;
   		$array = Yii::$app->db->createCommand($sql)->queryAll();
   		
   		$sql = "Select * From v_cem_fall_serv where Fall_id=".$id." Order by Orden";
   		$sub1 = Yii::$app->db->createCommand($sql)->queryAll();
   		   		
   		return $array;
    }
}