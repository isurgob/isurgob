<?php

namespace app\models\taux;

use Yii;


use app\utils\db\utb;

/**
 * This is the model class for table "sam.tabla_aux".
 *
 * @property integer $cod
 * @property string $nombre
 * @property integer $mod_id
 * @property string $titulo
 * @property string $frm
 * @property string $link
 * @property integer $autoinc
 * @property integer $accesocons
 * @property integer $accesoedita
 * @property string $tcod
 * @property string $web
 * @property string $fchmod
 * @property integer $usrmod
 */
class Localidad extends \yii\base\Model
{
	const TIPO_PAIS = 'pais';
	const TIPO_PROVINCIA = 'provincia';
	const TIPO_LOCALIDAD = 'localidad';
    
    private $tipo;
    private $nuevo;
    
    public $nombre_pais;
    public $nombre_prov;
    public $nombre_loc;
    public $pais_id;
    public $prov_id;
    public $loc_id;
    public $cp;
    

	public function __construct($tipo){
		parent::__construct();
		
		$this->nuevo = true;
		$this->tipo = $tipo;
		$this->cp = 0;
		
		
		$sql="select pais_id from domi_pais where UPPER(nombre) LIKE '%ARGENTINA%'";
		$this->pais_id=Yii::$app->db->createCommand($sql)->queryScalar();
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
    			'pais_id',
    			'required',
    			'on' => ['insertpais', 'deletepais', 'insertprovincia', 'updateprovincia', 'deleteprovincia'],
    			'message' => 'Elija un país'
    			];
    	
    	$ret[] = [
    			'nombre_pais',
    			'required',
    			'on' => ['insertpais', 'updatepais']
    			];
    			
    	$ret[] = [
    			'prov_id',
    			'required',
    			'on' => ['updateprovincia', 'deleteprovincia', 'insertlocalidad', 'updatelocalidad', 'deletelocalidad'],
    			'message' => 'Elija una provincia'
    			];
    	
    	$ret[] = [
    			'nombre_prov',
    			'required',
    			'on' => ['insertprovincia', 'updateprovincia']
    			];
    			
    	$ret[] = [
    			'loc_id',
    			'required',
    			'on' => ['updatelocalidad', 'deletelocalidad'],
    			'message' => 'Elija una localidad'
    			];
    			
    	$ret[] = [
    			['nombre_loc'],
    			'required',
    			'on' => ['insertlocalidad', 'updatelocalidad']
    			];
    	/**
    	 * FIN CAMPOS REQUERIDOS
    	 */
		
		/**
		 * TIPO Y RANGO DE DATOS
		 */
		$ret[] = [
				'pais_id',
				'integer',
				'min' => 1,
				'on' => ['updatepais', 'deletepais', 'insertprovincia', 'updateprovincia', 'deletelocalidad', 'insertlocalidad', 'updatelocalidad', 'deletelocalidad']
				];
				
		$ret[] = [
				'nombre_pais',
				'string',
				'max' => 20,
				'on' => ['insertpais', 'updatepais', 'insertprovincia', 'updateprovincia', 'insertlocalidad', 'updatelocalidad']
				];
				
		$ret[] = [
				'prov_id',
				'integer',
				'min' => 1,
				'on' => ['updateprovincia', 'deleteprovincia', 'insertlocalidad', 'updatelocalidad', 'deletelocalidad']
				];
				
		$ret [] = [
				'nombre_prov',
				'string',
				'max' => 30,
				'on' => ['insertprovincia', 'updateprovincia', 'insertlocalidad', 'updatelocalidad']
				];
				
		$ret[] = [
				'loc_id',
				'integer',
				'min' => 1,
				'on' => ['updatelocalidad', 'deletelocalidad']
				];
		
		$ret[] = [
				'nombre_loc',
				'string',
				'max' => 40,
				'on' => ['insertlocalidad', 'updatelocalidad']
				];
				
		$ret[] = [
				'cp',
				'integer',
				'min' => 0,
				'on' => ['insertlocalidad', 'updatelocalidad']
				];
		/**
		 * FIN TIPO Y RANGO DE DATOS
		 */

		$ret[] = [
				'cp',
				'default',
				'value' => 0,
				'on' => ['insertlocalidad', 'updatelocalidad']
				];
        return $ret;
    }

    public function scenarios(){
    	
    	return [
    		
    		'pais' => ['pais_id', 'nombre_pais'],
    		'provincia' => ['pais_id', 'prov_id', 'nombre_pais', 'nombre_prov'],
    		'localidad' => ['pais_id', 'prov_id', 'loc_id', 'nombre_pais', 'nombre_prov', 'nombre_loc', 'cp'],
    		
    		'insertpais' => ['nombre_pais'],
    		'insertprovincia' => ['pais_id', 'nombre_prov', 'nombre_pais'],
    		'insertlocalidad' => ['pais_id', 'prov_id', 'nombre_loc', 'cp', 'nombre_pais', 'nombre_prov'],
    		
    		'updatepais' => ['pais_id', 'nombre_pais'],
    		'updateprovincia' => ['pais_id', 'prov_id', 'nombre_prov', 'nombre_pais'],
    		'updatelocalidad' => ['prov_id', 'loc_id', 'nombre_loc', 'cp', 'nombre_pais', 'nombre_prov'],
    		
    		'deletepais' =>['pais_id'],
    		'deleteprovincia' => ['pais_id', 'prov_id'],
    		'deletelocalidad' => ['prov_id', 'loc_id']
    	];
    }
    

	public function setScenario($scenario, $concatenar = true){
		
		if($concatenar)
			parent::setScenario($scenario . $this->tipo);
		else
			parent::setScenario($scenario);	
		
	}
	
	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
        	'nombre_pais' => 'Nombre del país',
        	'nombre_prov' => 'Nombre de la provincia',
        	'nombre_loc' => 'Nombre de la localidad',
        	'cp' => 'Código postal'
        ];
    }
    
    public function grabar(){
    	
    	$scenario = $this->nuevo ? 'insert' : 'update';
    	$this->setScenario($scenario);
    	
    	if(!$this->validate()) return false;
    	
    	switch($this->tipo){
    		
    		case Localidad::TIPO_PAIS: return $this->grabarPais();
    		case Localidad::TIPO_PROVINCIA: return $this->grabarProvincia();
    		case Localidad::TIPO_LOCALIDAD: return $this->grabarLocalidad();
    		
    		default : $this->addError('Tipo de acción no reconocida');
    	}
    	
    	
    	return false;
    }
    
    public function borrar(){
    	
    	$this->setScenario('delete');
    	
    	if(!$this->validate()) return false;
    	
    	switch($this->tipo){
    		
    		case Localidad::TIPO_PAIS: return $this->borrarPais();
    		case Localidad::TIPO_PROVINCIA: return $this->borrarProvincia();
    		case Localidad::TIPO_LOCALIDAD: return $this->borrarLocalidad();
    		
    		default : $this->addError('Tipo de acción no reconocida');
    	}
    	
    	return false;
    }
    
    
    public function grabarPais(){
    	
    	$trans = Yii::$app->db->beginTransaction();
    	
    	//se comprueba que el nombre del pais no exista
    	$sql = "Select Exists( Select 1 From domi_pais Where upper(nombre) = upper('$this->nombre_pais')";
    	
    	if(!$this->nuevo) $sql .= " And pais_id <> $this->pais_id";
    	
    	$sql .= ")";
    	
    	$existe = Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	if($existe == true){
    		$this->addError($this->nombre_pais, 'El país ya existe');
    		$trans->rollBack();
    		return false;
    	}
    	
    	if($this->nuevo){
    		
    		$codigoPais = $this->proximaClave();
    		
    		$sql = "Insert Into domi_pais(pais_id, nombre, fchmod, usrmod) Values($codigoPais, upper('$this->nombre_pais'), current_timestamp, " . Yii::$app->user->id . ")";
    	}    		
    	else $sql = "Update domi_pais Set nombre = upper('$this->nombre_pais'), fchmod = current_timestamp, usrmod = " . Yii::$app->user->id . " Where pais_id = $this->pais_id";
    	
    	
    	$res = Yii::$app->db->createCommand($sql)->execute() > 0;
    	
    	if(!$res){
    		$this->addError($this->nombre_pais, 'Ocurrio un error al intentar grabar los datos');
    		$trans->rollBack();
    		return false;
    	}
    	
    	$sql = "Select Max(pais_id) From domi_pais";
    	$this->pais_id = Yii::$app->db->createCommand($sql)->queryScalar();	
    	
    	$trans->commit();
    	return true;
    }
    
    public function grabarProvincia(){
    	
    	$trans = Yii::$app->db->beginTransaction();
    	
    	//se comprueba si ya existe el nombre de la provincia
    	$sql = "Select Exists( Select 1 From domi_provincia Where upper(nombre) = upper('$this->nombre_prov') And pais_id = $this->pais_id";
    	
    	if(!$this->nuevo) $sql .= " And prov_id <> $this->prov_id";
    	
    	$sql .= ")";
    	
    	$existe = Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	if($existe){
    		$this->addError($this->nombre_prov, 'La provincia ya existe para el país elegido');
    		$trans->rollBack();
    		return false;
    	}
    	
    	//se agrega la provincia
    	if($this->nuevo){
    		
    		$codigo = $this->proximaClave();
    		
    		$sql = "Insert Into domi_provincia(prov_id, nombre, pais_id, fchmod, usrmod)" .
    				" Values($codigo, upper('$this->nombre_prov'), $this->pais_id, current_timestamp, " . Yii::$app->user->id . ")";
    		
    	} else $sql = "Update domi_provincia Set nombre = upper('$this->nombre_prov'), fchmod = current_timestamp, usrmod = " . Yii::$app->user->id . " Where prov_id = $this->prov_id And pais_id = $this->pais_id";
    	
    	$res = Yii::$app->db->createCommand($sql)->execute() > 0;
    	
    	if(!$res){
    		$this->addError($this->nombre_prov, 'Ocurrio un error al intentar grabar los datos');
    		$trans->rollBack();
    		return false;
    	}
    	
    	$sql = "Select Max(prov_id) From domi_provincia Where pais_id = $this->pais_id";
    	$this->prov_id = Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	$trans->commit();
    	return true;
    }
    
    public function grabarLocalidad(){
    	
    	$trans = Yii::$app->db->beginTransaction();
    	
    	//se comprueba si existe la localidad para la provincia y pais datos
    	$sql = "Select Exists( Select 1 From domi_localidad As l, domi_provincia As p Where upper(l.nombre) = upper('$this->nombre_loc') And l.prov_id = $this->prov_id" .
    			" And l.prov_id = p.prov_id And p.pais_id = $this->pais_id";
    			
    	if(!$this->nuevo) $sql .= " And loc_id <> $this->loc_id";
    	
    	$sql .= ")";
    	
    	$existe = Yii::$app->db->createcommand($sql)->queryScalar();
    	
    	if($existe){
    		$this->addError($this->loc_id, 'La localidad ya existe en la provincia');
    		$trans->rollBack();
    		return false;
    	}
    	
		if($this->nuevo){
			
			$codigo = $this->proximaClave();
			
			$sql = "Insert Into domi_localidad(loc_id, nombre, prov_id, cp, fchmod, usrmod) Values($codigo, upper('$this->nombre_loc'), $this->prov_id, $this->cp, current_timestamp, " . Yii::$app->user->id . ")";
			
		} else{
			$sql = "Update domi_localidad Set nombre = upper('$this->nombre_loc'), cp = $this->cp, fchmod = current_timestamp, usrmod = " . Yii::$app->user->id . " Where loc_id = $this->loc_id And prov_id = $this->prov_id";
		}
    	
    	$res = Yii::$app->db->createCommand($sql)->execute() > 0;
    	
    	if(!$res){
    		$this->addError($this->nombre_loc, 'Ocurrio un error al intentar grabar los datos');
    		$trans->rollBack();
    		return false;
    	}
    	
    	$sql = "Select Max(loc_id) From domi_localidad Where prov_id = $this->prov_id";
    	$this->loc_id = Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	$trans->commit();
    	return true;
    }
    
    public function borrarPais(){
    	
    	$trans = Yii::$app->db->beginTransaction();
    	
    	//se verifica si existen provincias asociadas al pais
    	$sql = "Select Exists( Select 1 From domi_provincia Where pais_id = $this->pais_id)";
    	
    	$res = Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	if($res){
    		$this->addError($this->nombre_pais, 'El país no se puede borrar porque existen provincias asociadas al mismo');
    		$trans->rollBack();
    		return false;
    	}
    	
    	$sql = "Delete From domi_pais Where pais_id = $this->pais_id";
    	Yii::$app->db->createCommand($sql)->execute();
    	
    	
    	$trans->commit();
    	return true;
    }
    
    public function borrarProvincia(){
    	
    	$trans = Yii::$app->db->beginTransaction();
    	
    	//se valida si existen localidades asociadas a la provincia
    	$sql = "Select Exists( Select 1 From domi_localidad Where prov_id = $this->prov_id)";
    	
    	$existe = Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	if($existe){
    		$this->addError($this->nombre_prov, 'La provincia no se puede borrar porque existen localidades asociadas a la misma');
    		$trans->rollBack();
    		return false;
    	}
    	
    	$sql = "Delete From domi_provincia Where prov_id = $this->prov_id";
    	Yii::$app->db->createCommand($sql)->execute();
    	
    	$trans->commit();
    	return true;
    }
    
    public function borrarLocalidad(){
    	
    	$trans = yii::$app->db->beginTransaction();
    	
    	//se comprueba si existen domicilio asociados a la localidad
    	$sql = "Select Exists( Select 1 From domi Where loc_id = $this->loc_id)";
    	
    	$existe = Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	if($existe){
    		$this->addError($this->nombre_loc, 'La localidad no se puede borrar porque existen domicilios asociados a la misma');
    		$trans->rollBack();
    		return false;
    	}
    	
    	$sql = "Delete From domi_localidad Where loc_id = $this->loc_id";
    	Yii::$app->db->createCommand($sql)->execute();
    	
    	$trans->commit();
    	return true;
    }
    
    /**
     * 
     */
	public function buscarUno($codigo, $foranea = 0){
		
		$sql = "";
		
		switch($this->tipo){
			case Localidad::TIPO_PAIS: $sql = "Select pais_id, nombre As nombre_pais From domi_pais Where pais_id = $codigo"; break;
			
			case Localidad::TIPO_PROVINCIA: $sql = "Select p.nombre As nombre_pais, pr.pais_id, pr.prov_id, pr.nombre As nombre_prov From domi_provincia As pr, domi_pais As p" .
					" Where pr.prov_id = $codigo And pr.pais_id = $foranea And pr.pais_id = p.pais_id"; break;
			
			case Localidad::TIPO_LOCALIDAD: $sql = "Select p.nombre As nombre_pais, p.pais_id, pr.nombre As nombre_prov, l.prov_id, l.prov_id, l.loc_id, l.nombre As nombre_loc, cp From domi_localidad As l, domi_provincia As pr, domi_pais As p" .
					" Where l.loc_id = $codigo And l.prov_id = $foranea And l.prov_id = pr.prov_id And pr.pais_id = p.pais_id"; break;
			
		}
		
		$res = Yii::$app->db->createCommand($sql)->queryOne();
		
		$this->setScenario($this->tipo, false);
		if($res !== false){
			$this->setAttributes($res);
			$this->nuevo = false;
			return $this;
		}
		
		$this->addError($this->pais_id, 'Datos no encontrados');
		return $this;
	}

	/**
	 * 
	 */
	public static function getPaises(){

		return utb::getAux('domi_pais', 'pais_id');
	}
   
   /**
    * 
    */
	public static function getProvincias($pais = 0){
	
		$sql = "Select pais_id, prov_id, nombre From domi_provincia Where pais_id = $pais";
		return Yii::$app->db->createCommand($sql)->queryAll();
	}
   
   /**
    * 
    */
	public static function getLocalidades($provincia = 0){
	
		$sql = "Select prov_id, loc_id, nombre, cp From domi_localidad Where prov_id = $provincia";
		return Yii::$app->db->createCommand($sql)->queryAll();
	}
	
	public static function buscar($nombrePais = '', $nombreProvincia = '', $nombreLocalidad = '', $codigoPostal = 0){
		
		$sql= "Select p.nombre As nombre_pais, p.pais_id As pais_id, pr.nombre As nombre_provincia, pr.prov_id As prov_id, l.nombre As nombre_localidad, l.cp as codigo_postal, l.loc_id As loc_id From" .
			" domi_pais As p, domi_provincia As pr, domi_localidad As l Where";
			
		$condicion= ' p.pais_id = pr.pais_id And l.prov_id = pr.prov_id';
		
		if(!empty($nombrePais)) $condicion .= " And upper(p.nombre) Like upper('%$nombrePais%')";
			
		if(!empty($nombreProvincia)) $condicion .= " And upper(pr.nombre) Like upper('%$nombreProvincia%')";
		
		if(!empty($nombreLocalidad)) $condicion .= " And upper(l.nombre) Like upper('%$nombreLocalidad%')";
		
		if($codigoPostal > 0) $condicion .= " And l.cp = $codigoPostal";

		$sql .= $condicion;
		
		$res= Yii::$app->db->createCommand($sql)->queryAll();
		
		if($res === false) return [];
		
		return $res;
	}
	
	private function proximaClave(){
		
		$sql = "";
		
		switch($this->tipo){
			
			case Localidad::TIPO_PAIS: $sql = "Select Coalesce(Max(pais_id), 0) + 1 From domi_pais"; break;
			case Localidad::TIPO_PROVINCIA: $sql = "Select Coalesce(Max(prov_id), 0) + 1 From domi_provincia"; break;
			case Localidad::TIPO_LOCALIDAD: $sql = "Select Coalesce(Max(loc_id), 0) + 1 From domi_localidad"; break;
		}
		
		return Yii::$app->db->createCommand($sql)->queryScalar();
	}
}
