<?php

namespace app\models\objeto;

use Yii;
use app\utils\db\utb;
use app\models\objeto\Objeto;

/**
 * This is the model class for table "domi".
 *
 * @property string $torigen
 * @property string $obj_id
 * @property integer $id
 * @property integer $loc_id
 * @property string $cp
 * @property integer $barr_id
 * @property integer $calle_id
 * @property string $nomcalle
 * @property integer $puerta
 * @property string $det
 * @property string $piso
 * @property string $dpto
 * @property string $fchmod
 * @property integer $usrmod
 */
class Domi extends \yii\db\ActiveRecord
{
   public $pais_id; 
   public $prov_id; 
   public $domicilio;
      
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'domi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['torigen', 'obj_id', 'id'], 'required'],
            [['id', 'loc_id', 'barr_id', 'calle_id', 'puerta', 'usrmod'], 'integer'],
            [['fchmod'], 'safe'],
            [['torigen'], 'string', 'max' => 3],
            [['obj_id', 'cp'], 'string', 'max' => 8],
            [['nomcalle'], 'string', 'max' => 40],
            [['det'], 'string', 'max' => 50],
            [['piso', 'dpto'], 'string', 'max' => 5],
            [['domicilio'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'torigen' => 'Tipo de origen',
            'obj_id' => 'Origen del domicilio para objetos',
            'id' => 'Codigo del origen para planes,judiciales y demas',
            'loc_id' => 'Codigo de la localidad',
            'cp' => 'Codigo postal',
            'barr_id' => 'Barr ID',
            'calle_id' => 'Codigo de calle',
            'nomcalle' => 'Nombre de la calle',
            'puerta' => 'Numero de puerta',
            'det' => 'Detalle',
            'piso' => 'Numero de piso',
            'dpto' => 'Numero de departamento',
            'fchmod' => 'Fecha de modificacion',
            'usrmod' => 'Codigo de usuario que modifico',
            'domicilio' => 'Domicilio',
        ];
    }
    
     /**
	 * Funcion que crea o modifica el domicilio de un objeto en la base de datos.
	 * @param integer $expe Número de expediente
	 * @param string $obs Observación
	 * @return string Valor con los errores ocurridos. "" en caso de no haber ocurrido ningún error. 
	 */
	public function grabar($expe='',$obs='') 
	{
		if ($this->puerta=="") $this->puerta=0;
		if ($this->calle_id=="") $this->calle_id=0;
		if ($this->barr_id=="") $this->barr_id=0;
		
		$sql = "select count(*) from domi where torigen='".$this->torigen."' and obj_id='".$this->obj_id."' and id=".$this->id;
		$cantidad = Yii :: $app->db->createCommand($sql)->queryScalar();
		
		//Si cantidad es igual 0, no existe el domicilio y se dará de alta. Caso contrario se modificará.
		if ($cantidad == 0) 
		{
			$sql = "insert into domi values ('".$this->torigen."','".$this->obj_id."',".$this->id;
			$sql .= ",".$this->loc_id. ",'".$this->cp."',".$this->barr_id;
			$sql .= ",".$this->calle_id.",'".$this->nomcalle."',".$this->puerta;
			$sql .= ",'".$this->det."','".$this->piso."','".$this->dpto."',current_timestamp,".Yii::$app->user->id.")";

			$cmd = Yii :: $app->db->createCommand($sql);
			$rowCount = $cmd->execute();

			if ($rowCount > 0) 
			{
				return "";
			} else {
				return "Ocurrio un error al intentar grabar en la BD.";
			}
			
		} else {
			
			if ($this->torigen == 'OBJ')
    		{
    			$taccion = 7;
    		}else if ($this->torigen == 'PLE')
    		{
    			$taccion = 8;
    		}else if ($this->torigen == 'INM' or $this->torigen == 'PRE')
    		{
    			$taccion = 9;
    		}else if ($this->torigen == 'COM')
    		{
    			$taccion = 32;
    		}else {
    			$taccion = 0;
    		}
    			
    		$error = "";	
    			
    		$modelodomi = Domi::cargarDomi($this->torigen, $this->obj_id, $this->id);
    			
    		$cambiodomi = 0;
    		if ($modelodomi->loc_id !== $this->loc_id) $cambiodomi = 1;
    		if ($modelodomi->barr_id !== $this->barr_id) $cambiodomi = 1;
    		if ($modelodomi->calle_id !== $this->calle_id) $cambiodomi = 1;
    		if ($modelodomi->nomcalle !== $this->nomcalle) $cambiodomi = 1;
    		if ($modelodomi->puerta !== $this->puerta) $cambiodomi = 1;
    		if ($modelodomi->dpto !== $this->dpto) $cambiodomi = 1;
    		if ($modelodomi->piso !== $this->piso) $cambiodomi = 1; 
    			
    		// guardo accion si exite la accion y si hubo cambio de domicilio
    		if ($taccion !== 0 and $cambiodomi == 1)
    		{
    		 	$modelobjeto = (new Objeto())->cargarObjeto($this->obj_id);
    			 	
    		 	// Registro una acción de cambio de domicilio
    		 	//no se hace mas
    		 	//$error .= $modelobjeto->NuevaAccion($taccion,date("d/m/Y"),"","",$expe,$modelodomi->domicilio,"",$obs);
    		}
    			
			$sql = "UPDATE domi set ";
			$sql .= "loc_id=".$this->loc_id.",cp='".$this->cp."',barr_id=".$this->barr_id;
			$sql .= ",calle_id=".$this->calle_id.",nomcalle='".$this->nomcalle;
			$sql .= "',puerta=".$this->puerta.",det='".$this->det."',piso='".$this->piso;
			$sql .= "',dpto='".$this->dpto."',fchmod=current_timestamp,usrmod=".Yii::$app->user->id;
			$sql .= " where torigen='".$this->torigen."' and obj_id='".$this->obj_id."' and id=".$this->id;

			$cmd = Yii :: $app->db->createCommand($sql);
			$rowCount = $cmd->execute();

			if ($rowCount > 0 and $error == "") {
				return "";
			} else {
				return "Ocurrió un error al intentar actualizar los datos en la BD.";
			}
		}
	}
	
	/**
	 * Función que permite realizar la modificación de un domicilio
	 * @param integer $taccion Código de Acción
	 * @param string $expe Número de expediente
	 * @param string $obs Observaciones
	 * @param string $distrib
	 * @param string $tdistrib
	 * 
	 */
	public function cbioDomicilio($taccion, $expe, $obs, $distrib, $tdistrib)
	{
		
		//Actualizar domicilio Postal
		//Actualizar domicilio Parcelario
		$error = "";
				
		//se comprueba que el domicilio sea distinto
		if(
			$this->loc_id == $this->getOldAttribute('loc_id') &&
			$this->cp == $this->getOldAttribute('cp') &&
			$this->barr_id == $this->getOldAttribute('barr_id') &&
			$this->calle_id == $this->getOldAttribute('calle_id') &&
			$this->nomcalle == $this->getOldAttribute('nomcalle') &&
			$this->puerta == $this->getOldAttribute('puerta') &&
			$this->piso == $this->getOldAttribute('piso') &&
			$this->dpto == $this->getOldAttribute('dpto')
		) 
		return 'El nuevo domicilio debe ser distinto al anterior';

		$error = Domi::grabar($expe, $obs);
		
		//Actualizo distribuidor cuando es Domicilio Postal
		if ($taccion == 7 && $error == '')
		{
			$rowCount = Yii :: $app->db->createCommand("UPDATE objeto SET distrib=" . $distrib . ",tdistrib=" . $tdistrib . " WHERE obj_id='" . $this->obj_id . "'")->execute();
			if ($rowCount > 0)
			{
				return '';
			}	else {
				$error .= "Ocurrió un error al intentar grabar en la BD";
			} 
		}
				
		return $error;
		
	}
    
    public static function cargarDomi($origen,$obj_id,$id)
    {
    	$model = Domi::findOne(['torigen' => $origen,'obj_id' => $obj_id, 'id' => $id]);
    	
    	if ($model !== null)    	
    	{
    		$model->prov_id = utb::getCampo("domi_localidad","loc_id=".$model->loc_id,"prov_id");
    		$model->pais_id = utb::getCampo("domi_provincia","prov_id=".$model->prov_id,"pais_id");
    		$model->domicilio = Domi::getDomicilio($origen,$obj_id,$id);
    	}
    	
    	return $model;
    }
    
    public static function getDomicilio($origen,$obj_id,$id)
    {
    	$domi = '';
    	
    	$sql = "Select coalesce(direccion,'') From v_domi Where TOrigen='".$origen."' and obj_id='".$obj_id."' and id=".$id;
    	 	    	   	
    	$domi = Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	return $domi;
    }
    
    
    public function armarDescripcion(){
    	
    	$localidadActual= utb::getCodLocalidad();
    	
    	$sql= "Select pais_id From domi_provincia Where prov_id In (Select prov_id From domi_localidad Where loc_id = $this->loc_id)";
    	$paisElegido= Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	$sql= "Select pais_id From domi_provincia Where prov_id In (Select prov_id From domi_localidad Where loc_id = $localidadActual)";
    	$paisActual= Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	
    	$this->domicilio= strtoupper($this->nomcalle);
    	$this->domicilio .= ($this->puerta != '' && $this->puerta != '0' && $this->puerta != 0 ? " $this->puerta" : '');
    	$this->domicilio .= ($this->piso != '' ? ' -Piso: '.$this->piso : '');
		$this->domicilio .= ($this->dpto !='' ? ' -Dpto: '.$this->dpto : '');
		
		//localidad y provincia se muestran cuando no se trata de la localidad actual
		if($localidadActual != $this->loc_id){
			$this->domicilio .= ' -' . utb::getCampo("domi_localidad","loc_id=".$this->loc_id,"nombre");
			$this->domicilio .=	' - '.utb::getCampo("domi_provincia","prov_id=".$this->prov_id,"nombre");
			
			if($paisActual != $paisElegido)
				$this->domicilio .= ' - ' . utb::getCampo('domi_pais', "pais_id = $paisElegido", 'nombre');
		}
		
		$this->domicilio .= ($this->det != '' ? " -($this->det)" : '');
		$this->domicilio .= ($this->barr_id > 0 ? ' -Bº ' . utb::getCampo('domi_barrio', "barr_id = $this->barr_id", 'nombre') : '');
		
    	
    	return $this->domicilio;
    }
}
