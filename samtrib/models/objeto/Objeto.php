<?php
namespace app\models\objeto;

use Yii;
use app\utils\db\utb;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use app\models\objeto\Domi;
use yii\helpers\Html;
use yii\db\Query;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Session;
use yii\validators\EmailValidator;

/**
 * This is the model class for table "objeto".
 *
 * @property string $obj_id
 * @property integer $tobj
 * @property string $num
 * @property string $nombre
 * @property string $obj_dato
 * @property string $est
 * @property integer $distrib
 * @property integer $tdistrib
 * @property string $obs
 * @property string $objunifica
 * @property integer $vigencia
 * @property string $claveweb
 * @property string $fchalta
 * @property integer $usralta
 * @property string $fchbaja
 * @property integer $usrbaja
 * @property integer $tbaja
 * @property string $fchmod
 * @property integer $usrmod
 */

class Objeto extends \yii\db\ActiveRecord
{
    public $est_nom;
    public $alta;
    public $baja;
    public $modif;
    public $domi_postal;
    public $autoinc;
    public $letra;
    public $elimobjcondeuda;
    public $elimobjconadhe;
	public $existemisc;

    //Arreglo para manejar los datos de titulares de objetos
    public $arregloTitulares = [];

    //arreglo para manejar los titulares que se encuentran en la transferencia de objeto
    public $arregloTitularesTransferencia = [];

    public $arregloTitularesDenunciaImpositiva= [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'objeto';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

			[['tobj', 'vigencia', 'usralta', 'usrbaja', 'tbaja', 'usrmod','tdistrib','distrib'], 'integer'],
            [['fchalta', 'fchbaja', 'fchmod'], 'safe'],
            [['obj_id', 'num', 'objunifica'], 'string', 'max' => 8],
            [['nombre'], 'string', 'max' => 50],
            [['obj_dato'], 'string', 'max' => 25],
            [['est'], 'string', 'max' => 1],
            [['obs'], 'string', 'max' => 1000],
            [['claveweb'], 'string', 'max' => 10],
            [['domi_postal'], 'string'],
            [['elimobjcondeuda','elimobjconadhe'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'obj_id' => 'Objeto',
            'tobj' => 'tipo de objeto',
            'num' => 'Codigo de num - responsable',
            'nombre' => 'Nombre ',
            'obj_dato' => 'Datos alternativos del objeto',
            'est' => 'Estado',
            'est_nom' => 'Estado',
            'distrib' => 'Distribuidor',
            'tdistrib' => 'Tipo de distribuci�n',
            'obs' => '',
            'objunifica' => 'Codigo de objeto con el que esta unificado',
            'vigencia' => 'Año de vigencia',
            'claveweb' => 'Clave usada para web',
            'fchalta' => 'Fecha de alta',
            'usralta' => 'Usuario que dio el alta',
            'fchbaja' => 'Fecha de baja',
            'usrbaja' => 'Usuario que dio la baja',
            'tbaja' => 'Motivo de baja',
            'fchmod' => 'Fecha de modificacion',
            'usrmod' => 'Codigo de usuario que modifico',
            'domi_postal' => 'Domicilio Postal',
            'elimobjcondeuda' => 'Eliminar Objeto aún con Deuda o Saldo a favor',
			'elimobjconadhe' => 'Eliminar adhesión del Objeto'
        ];
    }

     /**
	 * Función que valida los datos antes de guardar datos en la BD
	 *
	 * @return string error Valor con los errores ocurridos al validar. Devuelve cadena vacia ("") en caso de no haber errores.
	 */
	public function validar()
	{
		$error = "";

		if ($this->est == 'B') {

			$error .= "<li>La Persona ya fue dada de Baja con anterioridad.</li>";
		}
		if ($this->nombre == '' && $this->tobj<>4) {

			$error .= "<li>El Nombre del Objeto es obligatorio.</li>";
		}

		if (utb::getCampo("objeto_tipo","cod=".$this->tobj) == '') {

			$error .= "<li>El Tipo de Objeto es obligatorio.</li>";
		}
		if ($this->domi_postal=='' and $this->est != 'M')
		{
			$error .= "<li>Debe ingresar el Domicilio Postal del Objeto</li>";
		}
		if ($this->isNewRecord and $this->autoinc == 0)
		{
			if (substr($this->obj_id,1,7) == '0000000')
			{
				$error .= "<li>Se requiere el código de Objeto</li>";
			}

			$sql = "Select count(*) From Objeto Where obj_id ='". $this->obj_id."'";
			$cantidad = Yii :: $app->db->createCommand($sql)->queryScalar();

			if ($cantidad > 0)
			{
				$error .= "<li>El código de Objeto está repetido</li>";
			}
		}

		return $error;

	}

		public function validarConErrorModels( $valDomi = true)
	{
		$error = [];

		if ($this->est == 'B') {

			array_push($error, "La Persona ya fue dada de Baja con anterioridad.");
		}
		if ($this->nombre == '' && $this->tobj<>4) {

			array_push($error, "El Nombre del Objeto es obligatorio.");
		}

		if (utb::getCampo("objeto_tipo","cod=".$this->tobj) == '') {

			array_push($error, "El Tipo de Objeto es obligatorio.");
		}
		if ($valDomi and $this->domi_postal=='' and $this->est != 'M')
		{
			array_push($error, "Debe ingresar el Domicilio Postal del Objeto.$valDomi");
		}
		if ($this->isNewRecord and $this->autoinc == 0)
		{
			if (substr($this->obj_id,1,7) == '0000000')
			{
				array_push($error, "Se requiere el código de Objeto.");
			}

			$sql = "Select count(*) From Objeto Where obj_id ='". $this->obj_id."'";
			$cantidad = Yii :: $app->db->createCommand($sql)->queryScalar();

			if ($cantidad > 0)
			{
				array_push($error, "El código de Objeto está repetido.");
			}
		}

		/* Validar mail si tipo de distribucion es 5-Mail */
        //echo $this->tdistrib;
        //exit;
		if ( $this->tdistrib == 5 and $this->tobj != 3){

			$sql = "select mail from persona where obj_id='$this->num' ";
			$mail =  Yii::$app->db->createCommand( $sql )->queryScalar();

			$validator = new EmailValidator();

			if (!($validator->validate($mail, $err)))
			{
				array_push($error, "El mail del contribuyente no es válido.");
			}
		}

        // Anulo la validacion del distribuidor (Chany - 15/09/2017 - pedido El Hoyo)
		// if ( $this->tdistrib != 3 and intVal($this->distrib) == 0 )
        //			array_push($error, "Seleccione un distribuidor.");

		return $error;

	}

	 /**
	 * Funcion que crea o modifica un objeto en la base de datos.
	 * @return string Valor con los errores ocurridos. "" en caso de no haber ocurrido ning�n error.
	 */
	public function grabar()
	{
		if ($this->distrib=="") $this->distrib=0;
		if ($this->tdistrib=="") $this->tdistrib=0;
		if ($this->vigencia=="") $this->vigencia=0;

        //Si es un nuevo registro
		if ($this->isNewRecord)
		{
			$this->obj_id = $this->GetMxObjeto();
			$this->est = $this->est == '' ? 'A' : $this->est;

			if ($this->tobj == 4) $this->est = 'D';
			if ($this->tobj == 3) $this->num = $this->obj_id;
			if ($this->fchalta == '') $this->fchalta = date_create("now")->format('Y-m-d');

			$sql = "insert into objeto values ('".$this->obj_id."',".$this->tobj.",'".$this->num;
			$sql .= "',UPPER('".$this->nombre. "'),'".$this->obj_dato."', '" . $this->est . "'";
			$sql .= ",".$this->distrib.",".$this->tdistrib.",'".$this->obs;
			$sql .= "','".$this->objunifica."',".$this->vigencia.",'','".$this->fchalta."',".Yii::$app->user->id;
			$sql .= ",null,0,0,current_timestamp,".Yii::$app->user->id.")";

			$cmd = Yii :: $app->db->createCommand($sql);
			$rowCount = $cmd->execute();

			if ($rowCount > 0)
			{
				return "";
			} else {
				return "Ocurrio un error al intentar grabar en la BD.";
			}

		} else {

			if ($this->fchalta == '') $this->fchalta = date_create("now")->format('Y-m-d');

			$sql = "update Objeto set ";
			$sql .= "NUM='".$this->num."',nombre=UPPER('".$this->nombre."'),Obj_Dato='".$this->obj_dato;
			$sql .= "',Est='".$this->est."',Distrib=".$this->distrib;
			$sql .= ",TDistrib=".$this->tdistrib.",Obs='".$this->obs."',ObjUnifica='".$this->objunifica;
			$sql .= "',Vigencia=".$this->vigencia.",fchalta='" . $this->fchalta ."',fchmod=current_timestamp,usrmod=".Yii::$app->user->id;
			$sql .= " where obj_id='".$this->obj_id."'";

			$cmd = Yii :: $app->db->createCommand($sql);
			$rowCount = $cmd->execute();

			if ($rowCount > 0) {
				return "";

			} else {

				return "Ocurrio un error al intentar actualizar los datos en la BD.";
			}

		}
	}

	/**
	 * Ejecuta un procedimiento que graba el campo computado
	 */
    public function GrabarComputados()
    {
    	//Obtener el tipo de objeto
    	$tObj = utb::getTObj($this->obj_id);

    	//Ejecuto la función de la BD.
    	Yii::$app->db->createCommand("Select sam.uf_objeto_computa(" . $tObj . ",'" . $this->obj_id . "')")->execute();
    }


	 /**
     * Funcion que realiza la baja de un objeto en la base de datos
     */
    public function borrar( $obs )
    {
    	$sql = "Select count(*) from Ctacte where obj_id='".$this->obj_id."' and Est='D'";
		$cantidad = Yii :: $app->db->createCommand($sql)->queryScalar();

    	if ($cantidad == 0 or $this->elimobjcondeuda == 1)
    	{
    		if (utb::getTObj($this->obj_id) == 3){
				$sql = "Select count(*) From objeto_persona v Inner Join Objeto o On v.obj_id = o.obj_id ";
				$sql .= "Left Join objeto_tipo t On v.TObj = t.Cod Left Join persona_tvinc tv On v.TVinc = tv.Cod ";
				$sql .= "Where v.NUM = '".$this->obj_id."' And o.Est = 'A' And v.Est = 'A'";

				$cantidad = Yii :: $app->db->createCommand($sql)->queryScalar();

				if ($cantidad > 0){
					$error = "<li>El objeto posee vinculos. No podrá eliminarlo</li>";
					return $error;
				}
			}

			if ($this->fchbaja == '' or $this->fchbaja == null) $this->fchbaja = date("d/m/Y");

    		$error = '';

    		if ($this->tbaja==0 or $this->tbaja=='')
    		{
    			$error = "<li>Seleccione un motivo de baja</li>";
    			return $error;
    		}

    		$transaction = Yii::$app->db->beginTransaction();

            try {
    			// si se quiere eliminar la adhesión a débito
				if ($this->elimobjconadhe == 1){
					$sql = "update debito_adhe set est='B',fchbaja=current_timestamp where obj_id='" . $this->obj_id . "'";
					Yii::$app->db->createCommand($sql)->execute();
				}

				// Registro una acción de baja
    			$error .= $this->NuevaAccion(2,date("d/m/Y"),"","","","","",$obs);

    			$sql = "Update Objeto Set Est='B' ,Obs='".$obs;
    			$sql = $sql."',FchBaja='".$this->fchbaja."', Usrbaja=".Yii::$app->user->id;
    			$sql = $sql.",tbaja=".$this->tbaja." where obj_id='".$this->obj_id."'";

    			$cmd = Yii::$app->db->createCommand($sql);
    			$rowCount = $cmd->execute();

    			if ($rowCount > 0 and $error == "") {
					$transaction->commit();
					return "";
				} else {
					$transaction->rollBack();
					return "<li>".$error."</li>";
				}
            }catch(Exception $e) {
    			$transaction->rollBack();
			    //throw $e;
			    return "<li>".$e."</li>";
			}
    	} else{
    		if ($this->elimobjcondeuda == 0)
    		{
    			return "<li>El objeto posee deuda, para eliminarlo seleccione la opción 'Eliminar Objeto aún con Deuda'</li>";
    		}else{
    			return "<li>No se pudo eliminar el objeto</li>";
    		}
    	}


    }


     /**
     *
     * Función que realiza la baja de un objeto en la base de datos.
     *
     * Funciona logicamente igual a la función "borrar()", diferenciándose en la
     * manera de devolver los errores.
     *
     * @return string Retorna un string con información acerca del error que ocurrió.
     * 				Retorna '' en caso de que no hayan ocurrido errores.
     *
     */
    public function borrarConErrorSummary($obs)
    {
    	$sql = "Select count(*) from Ctacte where obj_id='".$this->obj_id."' and Est='D'";
		$cantidad = Yii :: $app->db->createCommand($sql)->queryScalar();

    	if ($cantidad == 0 or $this->elimobjcondeuda == 1)
    	{
    		// si se quiere eliminar la adhesión a débito
			if ($this->elimobjconadhe == 1){
				$sql = "update debito_adhe set est='B',fchbaja=current_timestamp where obj_id='" . $this->obj_id . "'";
				Yii::$app->db->createCommand($sql)->execute();
			}

			if (utb::getTObj($this->obj_id) == 3){
				$sql = "Select count(*) From objeto_persona v Inner Join Objeto o On v.obj_id = o.obj_id ";
				$sql .= "Left Join objeto_tipo t On v.TObj = t.Cod Left Join persona_tvinc tv On v.TVinc = tv.Cod ";
				$sql .= "Where v.NUM = '".$this->obj_id."' And o.Est = 'A' And v.Est = 'A'";

				$cantidad = Yii :: $app->db->createCommand($sql)->queryScalar();

				if ($cantidad > 0){
					$error = "<li>El objeto posee vinculos. No podrá eliminarlo</li>";
					return $error;
				}
			}

			if ($this->fchbaja == '' or $this->fchbaja == null) $this->fchbaja = date("d/m/Y");

    		if ($this->tbaja==0 or $this->tbaja=='')
    		{
    			return "Seleccione un motivo de baja";

    		}

    		$transaction = Yii::$app->db->beginTransaction();

            try {
    			// Registro una acción de baja
    			$error = $this->NuevaAccion(2,date("d/m/Y"),"","","","","",$obs);

    			$sql = "Update Objeto Set Est='B' ,Obs='".$obs;
    			$sql = $sql."',FchBaja='".$this->fchbaja."', Usrbaja=".Yii::$app->user->id;
    			$sql = $sql.",tbaja=".$this->tbaja." where obj_id='".$this->obj_id."'";

    			$cmd = Yii::$app->db->createCommand($sql);
    			$rowCount = $cmd->execute();

    			if ($rowCount > 0 and $error == '') {
					$transaction->commit();
					return '';
				} else {
					$transaction->rollBack();
					return $error;
				}
            }catch(Exception $e) {
    			$transaction->rollBack();
			    return $e;
			}
    	} else{
    		if ($this->elimobjcondeuda == 0)
    		{
    			return "El objeto posee deuda, para eliminarlo seleccione la opción 'Eliminar Objeto aún con Deuda'";
    		}else{
    			return "No se pudo eliminar el objeto";
    		}
    	}


    }

    /**
     * Obtiene el Máximo Número del Objeto según el tipo
     */
    private function GetMxObjeto()
    {
    	$objeto = '';

    	if ($this->autoinc == "") $this->autoinc = utb::getCampo("objeto_tipo","Cod=".$this->tobj,"autoinc");
    	if ($this->letra == "") $this->letra = utb::getCampo("objeto_tipo","Cod=".$this->tobj,"letra");

    	if ($this->autoinc == 1)
    	{
    		$sql = "Select coalesce(max(obj_id),'X0000000') From objeto Where tobj=". $this->tobj;
			$objeto = Yii :: $app->db->createCommand($sql)->queryScalar();
			$objeto = (int)substr($objeto,1,7)+1;
    	}else {
    		if (strlen($this->obj_id) == 8)
    		{
    			$objeto = substr($this->obj_id,1,7);
    		}else {
    			$objeto = $this->obj_id;
    		}
    	}

    	$objeto = $this->letra.str_pad($objeto, 7, "0", STR_PAD_LEFT);

    	return $objeto;
    }

    public function cargarObjeto($id)
    {
    	$model = Objeto::findOne($id);

    	if($model == null) {

    		$model = new Objeto();
    		$model->obj_id = $id;
    		$model->tobj = 0;
    	}

    	$modeldomi = Domi::cargarDomi('OBJ', $id, 0);

    	if ($modeldomi == null) $modeldomi = new Domi;

    	$model->est_nom = utb::getCampo("v_objeto","obj_id='".$id."'","est_nom");
    	$model->alta = utb::getCampo("v_objeto","obj_id='".$id."'","alta");
    	$model->baja = utb::getCampo("v_objeto","obj_id='".$id."'","baja");
    	$model->modif = utb::getCampo("v_objeto","obj_id='".$id."'","modif");
    	$model->domi_postal =  $modeldomi->getDomicilio($modeldomi->torigen,$id,0);

    	$model->autoinc = utb::getCampo("objeto_tipo","Cod=".$model->tobj,"autoinc");
    	$model->letra = utb::getCampo("objeto_tipo","Cod=".$model->tobj,"letra");
    	$model->existemisc = Objeto::ExisteMisc($id);

    	return $model;
    }

    public function activarObjeto()
    {
    	$error = $this->NuevaAccion(1,date("d/m/Y"),"","","","","","");

    	if ($error == "")
    	{
			return "";
		} else {
			return "<li>".$error."</li>";
		}
    }

    /**
     *  Ejecuta un procedimiento para insertar en la tabla His_Inmueble_Cambio
     *  @param string obj_id = Código del Objeto
     *  @param integer old_Id = Código del Histórico de Inmuebles
     *  @param string tipo = Código de Tipo de cambio
     */

    public function GrabarInmueblesCambios($obj_id, $old_id, $tipo)
    {
    	$sql = "SELECT sam.uf_inm_cambio ('" . $obj_id . "', " . $old_id . ",'" . $tipo . "')";

    	$cmd = Yii :: $app->db->createCommand($sql);
		$rowCount = $cmd->execute();

    }

    //----------------------------------------------------------------------------------//
    //------------------------------------ASIGNACIONES----------------------------------//
    //----------------------------------------------------------------------------------//
    public function CargarAsignaciones($id, $vigente = 0)
    {
        $count = Yii::$app->db->createCommand("Select count(*) From v_objeto_Item where obj_id='".$id."' and item_tipo in (2,3,4)" . ($vigente == 1 ? " and vigente=1" : ""))->queryScalar();

        $sql = "Select * From v_objeto_Item where obj_id='".$id."' and item_tipo in (2,3,4)" . ($vigente == 1 ? " and vigente=1" : "");
        $sql = $sql." Order By trib_id,perdesde,item_id";

        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'key' => 'obj_id',
            'totalCount' => (int)$count,
        ]);

        return $dataProvider;
    }

    //----------------------------------------------------------------------------------//
    //------------------------------------Miscelaneas----------------------------------//
    //----------------------------------------------------------------------------------//
    public function CargarMisc($id)
    {
        $count = Yii::$app->db->createCommand( "Select count(*) From v_objeto_misc where obj_id='".$id."'" )->queryScalar();

        $sql =  "SELECT obj_id, orden, to_char(fecha,'dd/mm/YYYY') as fecha_format, titulo, left( detalle, 40 ) as detalle, usrmod, modif " .
                "FROM v_objeto_misc  WHERE obj_id='".$id."'";

        //$sql = "Select *,to_char(fecha,'dd/mm/YYYY') as fecha_format From v_objeto_misc where obj_id='".$id."'";
        $sql = $sql." Order By fecha";

        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'key' => 'obj_id',
            'totalCount' => (int)$count,
			'pagination'=> [
				'pageSize'=>10,
			],
        ]);

        return $dataProvider;
    }

    public function NuevaMisc($obj_id, $titulo, $detalle)
    {
    	$orden = Yii::$app->db->createCommand("Select coalesce(max(Orden),0)+1 from objeto_misc where obj_id='".$obj_id."'")->queryScalar();

    	$sql = "Insert Into objeto_misc Values ('".$obj_id."',".$orden.",now(),'".Html::encode($titulo)."','".Html::encode($detalle)."',";
    	$sql .= "current_timestamp,".Yii::$app->user->id.")";

    	$cmd = Yii :: $app->db->createCommand($sql);
		$rowCount = $cmd->execute();

		if ($rowCount > 0)
		{
			return "";
		} else {
			return "Ocurrio un error al intentar grabar en la BD.";
		}
    }

    public function ModificarMisc($obj_id, $orden, $titulo, $detalle)
    {
    	$sql = 'select count(*) from objeto_misc ';
    	$sql .= " where obj_id='".$obj_id."' and orden=".$orden." and usrmod=".Yii::$app->user->id;
    	$count = Yii::$app->db->createCommand($sql)->queryScalar();
    	if ($count == 0) return "No tiene permiso para editar la Miscelanea";

    	$sql = "Update objeto_misc Set Titulo='".Html::encode($titulo)."',detalle='".Html::encode($detalle)."',";
    	$sql .= "fchmod=current_timestamp,usrmod=".Yii::$app->user->id;
    	$sql .= " where obj_id='".$obj_id."' and orden=".$orden." and usrmod=".Yii::$app->user->id;

    	$cmd = Yii :: $app->db->createCommand($sql);
		$rowCount = $cmd->execute();

		if ($rowCount > 0)
		{
			return "";
		} else {
			return "Ocurrio un error al intentar grabar en la BD.";
		}
    }

    public static function ExisteMisc($obj_id)
    {
    	$sql = "select count(*) from objeto_misc where obj_id='".$obj_id."'";
    	$count = Yii::$app->db->createCommand($sql)->queryScalar();

    	return $count;
    }

    //----------------------------------------------------------------------------------//
    //------------------------------------Vínculos--------------------------------------//
    //----------------------------------------------------------------------------------//
    public function CargarVinculos($id,$baja=false)
    {
        $tobj = utb::GetTObj($id);

        if ($tobj == 3)
        {
        	$select = "Select v.tobj,t.nombre tobj_nom, v.obj_id, o.nombre,o.obj_dato, tv.nombre tvinc_nom, Porc, " .
        			"CASE WHEN o.est = 'B' THEN 'Baja' WHEN v.est = 'A' THEN 'Activo' else 'Baja' END as est  ";

        	$from = "From objeto_persona v Inner Join Objeto o On v.obj_id = o.obj_id ";
        	$from .= "Left Join objeto_tipo t On v.TObj = t.Cod Left Join persona_tvinc tv On v.TVinc = tv.Cod ";
        	$from .= "Where v.NUM = '".$id."'" . (!$baja ? " and v.est='A'" : "");

        	$order = " Order by t.Nombre, v.obj_id";

        } else {
        	$select = "Select 3 tobj, 'Persona' tobj_nom, v.NUM obj_id, o.nombre, o.obj_dato, tv.Nombre tvinc_nom, Porc,o.est est_obj, " .
					"CASE WHEN v.est = 'A' THEN 'Activo' WHEN v.est = 'B' THEN 'Baja' END as est ";
        	$from = "From objeto_persona v Inner Join Objeto o On v.NUM = o.obj_id ";
        	$from .= "Left Join persona_tvinc tv On v.TVinc = tv.Cod ";
        	$from .= "Where v.obj_id = '".$id."'" . (!$baja ? " and v.est='A'" : "");

        	$order = " Order by v.NUM ";
        }

        $count = Yii::$app->db->createCommand("Select count(*) " .$from)->queryScalar();

        $sql = $select.$from.$order;

        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'key' => 'obj_id',
            'totalCount' => (int)$count,
			'pagination'=> [
				'pageSize'=>(int)$count,
			],
        ]);

        return $dataProvider;
    }

    //----------------------------------------------------------------------------------//
    //------------------------------------Historico-------------------------------------//
    //----------------------------------------------------------------------------------//
    public function CargarHistorico($id, $tipo)
    {
        if ($tipo == 'DOM')
        {
        	$count = Yii::$app->db->createCommand("Select count(*) from v_his_domi where obj_id='".$id."' and torigen<>'PLA'")->queryScalar();

        	$sql = "Select * from v_his_domi where obj_id='".$id."' and torigen<>'PLA'";

        	//$clave =
        }
        if ($tipo == 'TIT')
        {
        	$sql = "select count(v.obj_id) From objeto_persona v Inner Join objeto o On v.num=o.obj_id ";
        	$sql .= "Left Join persona_tvinc t On v.tvinc = t.cod ";
        	$sql .= "Where v.obj_id='".$id."' and v.Est='B'";

        	$count = Yii::$app->db->createCommand($sql)->queryScalar();

        	$sql = "select v.obj_id,v.num As num, o.nombre As apenom, t.nombre As tvinc_nom, v.porc As porc, v.fchmod As fchmod From objeto_persona v Inner Join objeto o On v.num=o.obj_id ";
        	$sql .= " Left Join persona_tvinc t On v.tvinc = t.cod ";
        	$sql .= " Where v.obj_id='".$id."' and v.Est='B'";
        	$sql .= " Order by v.FchMod DESC";
        }
        if ($tipo == 'AVAL')
        {
        	$sql = "select count(*) From Inm_Avaluo a Left Join Inm_TZonaT t On a.ZonaT = t.Cod ";
        	$sql .= "Left Join Inm_TZonaOP o On a.ZonaOP = o.Cod ";
        	$sql .= "Left Join Inm_Talum s On a.alum = s.Cod ";
        	$sql .= "Where a.obj_id='".$id."'";

        	$count = Yii::$app->db->createCommand($sql)->queryScalar();

        	$sql = "select *,(substr(perdesde::text,0,5) || '-' || substr(perdesde::text,6,7)) as _perdesde,";
    			$sql .= "(substr(perhasta::text,0,5) || '-' || substr(perhasta::text,6,7)) as _perhasta";
    			$sql .=" From Inm_Avaluo a Left Join Inm_TZonaT t On a.ZonaT = t.Cod ";
        	$sql .= "Left Join Inm_TZonaOP o On a.ZonaOP = o.Cod ";
        	$sql .= "Left Join Inm_Talum s On a.alum = s.Cod ";
        	$sql .= "Where a.obj_id='".$id."'";
        	$sql .= " Order by Perdesde DESC";
        }
        if ($tipo == 'FALL')
        {
        	$sql = "select count(*) From v_cem_fall_serv Where obj_id_ori='".$id."'";

        	$count = Yii::$app->db->createCommand($sql)->queryScalar();

        	$sql = "select *,to_char(fecha,'dd/mm/yyyy') as fecha From v_cem_fall_serv Where obj_id_ori='".$id."'";
        	$sql .= " Order by apenom ";
        }


        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'key' => 'obj_id',
            'totalCount' => (int)$count,
			'pagination'=> [
				'pageSize'=>(int)$count,
			],
        ]);

        return $dataProvider;
    }

    //----------------------------------------------------------------------------------//
    //------------------------------------ACCIONES--------------------------------------//
    //----------------------------------------------------------------------------------//

    public function NuevaAccion($taccion,$fecha,$fchdesde,$fchhasta,$expe,$dato_ant,$dato_ins,$obs,$obj_unif="",$fchvenc="")
    {
    	$error = "";

    	$aux = utb::getVariosCampos('objeto_taccion', "cod = $taccion", 'estactual, estnuevo, interno');

    	$estactual = $aux['estactual'];//utb::getCampo("objeto_taccion","Cod=".$taccion,"estactual");
    	$estnuevo = $aux['estnuevo'];//utb::getCampo("objeto_taccion","Cod=".$taccion,"estnuevo");
    	$interno = $aux['interno'];//utb::getCampo("objeto_taccion","Cod=".$taccion,"interno");


    	// verifico que el estado actual sea correcto para la acción
    	$sql = "Select count(*) From objeto_taccion Where cod =".$taccion." and estactual like '%".$this->est."%'";
		$cantidad = Yii :: $app->db->createCommand($sql)->queryScalar();


		if ($cantidad == 0) {
			$error = "Verificar el Estado Actual del Objeto para realizar la acción";
		}

		if ($error == "") {
			$orden = Yii::$app->db->createCommand("Select coalesce(Max(Orden),0)+1 From objeto_accion where obj_id='$this->obj_id'")->queryScalar();

			$sql = "Insert Into objeto_accion Values ('$this->obj_id', $orden, $taccion, '$fecha', ".($fchdesde != '' ? "'$fchdesde'" : "Null").",";
			$sql .= ($fchhasta != '' ? "'$fchhasta'" : "Null").", '$expe', '$dato_ant', '$dato_ins', '$obs', 'A', current_timestamp,".Yii::$app->user->id.")";

			$cmd = Yii :: $app->db->createCommand($sql);
			$rowCount = $cmd->execute();

			if ($rowCount == 0) return "Ocurrio un error al intentar grabar en la BD.";

			if ($estnuevo == 'U') {
				// realizar unificacion de objeto

				 //Validar el estado de objeto con el cual se unifica
				 $estado =  Yii :: $app->db->createCommand("Select est FROM Objeto WHERE obj_id = '" . $obj_unif . "'")->queryScalar();

				 if ($estado !== 'A' && $estado !== 'D' && $estado !== 'O' && $estado !== 'R')
				 	return $obj_unif . 'Objeto unifica' . $estado;//"El Objeto con el cual se Unifica debe estar activo";

				 //Validar que no posea unificaciones
				 $count = Yii :: $app->db->createCommand("SELECT COUNT(*) FROM objeto WHERE objunifica='" . $this->obj_id . "'")->queryScalar();

				 if ($count > 0)
				 	return "El Objeto no debe tener Unificaciones";

				//Validar que el titulars de ambos objetos sean la misma persona
				$sql =
				$tit1 = Yii :: $app->db->createCommand("SELECT num FROM objeto WHERE obj_id = '" . $this->obj_id . "'")->queryScalar();
				$tit2 = Yii :: $app->db->createCommand("SELECT num FROM objeto WHERE obj_id = '" . $obj_unif . "'")->queryScalar();

				if ($tit1 !== $tit2)
					return "Los Titulares no coinciden.";

                $sql = "UPDATE Objeto SET est = 'U', objunifica = '" . $obj_unif . "'," .
                		"fchmod = CURRENT_TIMESTAMP, usrmod=" . Yii::$app->user->id . " WHERE obj_id = '" . $this->obj_id . "'";

                try
                {
                	$cmd = Yii :: $app->db->createCommand($sql);
					$rowCount = $cmd->execute();

					if (utb::getTObj($this->obj_id) == 1)
						$this->GrabarInmueblesCambios($this->obj_id,0,'U');


                } catch (Exception $e) {

                	return "Ocurrio un error al intentar grabar en la BD.";

                }

			} else {

				if (utb::GetTObj($this->obj_id) == 4 and ($this->est == "A" || $this->est == "E")) $estnuevo = "D";
				if (utb::GetTObj($this->obj_id) == 4 and ($this->est == "B" || $this->est == "U")) $estnuevo = "D";

				$sql = "Update Objeto Set Est = '".($estnuevo == "" ? $this->est : $estnuevo)."'";
				if ($estnuevo == 'A')
				{
					$sql .= ", ObjUnifica='', FchMod=CURRENT_TIMESTAMP, UsrMod=".Yii::$app->user->id;
					$sql .= ",FchBaja=null";
				}
				$sql .= " Where obj_id = '".$this->obj_id."'";
			}
			$cmd = Yii :: $app->db->createCommand($sql);
			$rowCount = $cmd->execute();

			if ($rowCount > 0)
			{
				return "";
			} else {
				return "Ocurrio un error al intentar grabar en la BD.";
			}
		} else
		{
			return $error;
		}
    }

    public function CargarAcciones($id)
    {
        $count = Yii::$app->db->createCommand("Select count(*) From v_objeto_accion where obj_id='".$id."'")->queryScalar();

        $sql = "Select * From v_objeto_accion where obj_id='".$id."'";
        $sql = $sql." Order By Fecha desc, orden desc";

        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'key' => 'obj_id',
            'totalCount' => (int)$count,
			'pagination'=> [
				'pageSize'=>(int)$count,
			],
        ]);

        return $dataProvider;
    }

    /**
     * Determina si el objeto posee deudas
     *
     * @param string $obj_id Codigo de objeto.
     *
     * return boolean Si posee deuda o no.
     */
    public function poseeDeuda($obj_id){

    	$sql = "Select count(*) from Ctacte where obj_id='".$this->obj_id."' and Est='D'";
		$cantidad = Yii :: $app->db->createCommand($sql)->queryScalar();

		return $cantidad > 0;
    }

	//----------------------------------------------------------------------------------//
    //------------------------------------TITULAR---------------------------------------//
    //----------------------------------------------------------------------------------//

	/**
	 * Función que me devuelve los datos de una persona
	 */
	public function obtenerDatosTitular($id)
	{
		$sql = "SELECT obj_id,nombre,tdoc_nom as tdoc,ndoc FROM v_persona where obj_id = '" . $id . "'";

		$res = Yii :: $app->db->createCommand($sql)->queryAll();

		return ArrayHelper::map($res, 'obj_id', function($model){return [
																'obj_id' => $model['obj_id'],
																'nombre' => $model['nombre'],
																'tdoc' => $model['tdoc'],
																'ndoc' => $model['ndoc'],
																]; });
	}

	/**
	 * Función que se encarga de obtener los datos de titulares
	 *
	 * @param $id Código del objeto del que se desea obtener los titulares
	 *
	 * @return ArrayHelper con los datos de los titulares
	 */
	public function obtenerTitularesDeBD ($id) {
		$sql = "select num,apenom,tdoc,ndoc,tvinc,tvinc_nom,porc,est,princ " .
				"from v_objeto_tit where obj_id = '".$id."' and est = 'A' order by princ desc, porc desc ";

		$res = Yii :: $app->db->createCommand($sql)->queryAll();

		$this->arregloTitulares = ArrayHelper::map($res, 'num', function($model){return [
																'num' => $model['num'],
																'apenom'=> $model['apenom'],
																'tdoc' => $model['tdoc'],
																'ndoc' => $model['ndoc'],
																'tvinc' => $model['tvinc'],
																'tvinc_nom' => $model['tvinc_nom'],
																'porc' => $model['porc'],
																'est' => $model['est'],
																'princ' => $model['princ'],
																'BD' => '1',	//Uso BD para diferenciar los elementos que ya se encuentran en la BD de los que no.
																]; });
	}

	/**
	 * Función que se encarga de agrear un elemento al arreglo de titulares
	 * Sólo los mantiene en memoria
	 */
	public function addTitular ($cod, $arreglo)
	{

		$this->arregloTitulares[$cod] = $arreglo;

	}

	/**
	 * Función que se encarga de devolver los datos de una persona
	 */
	public function setTitular($idPersona)
	{


		//$sql = "select p.obj_id, p.nombre as apenom, p.ndoc, d.nombre as tdoc from v_persona p , persona_tdoc d WHERE p.tdoc = d.cod and p.obj_id = '" . $idPersona . "'";

		$sql = new Query();

		$sql->select(['persona.obj_id, persona.nombre as apenom,persona_tdoc.nombre as tdoc,persona.ndoc']);
		$sql->from(['persona, persona_tdoc']);
		$sql->where(['=','p.obj_id',$idPersona]);

		$res = $sql->all();

		return ArrayHelper::map($res, 'num', function($model){return [
																//'cod' => $model['cod'],
																'num' => $model['obj_id'],
																'apenom'=> $model['apenom'],
																'tdoc' => $model['tdoc'],
																'ndoc' => $model['ndoc'],
																//'tvinc_nom' => $model['tvinc_nom'],
																//'porc' => $model['porc'],
																//'est' => $model['est'],
																]; });
	}




	/**
	 * Función que se encarga de generar un ArrayDataProvider con los datos de los titulares
	 *
	 * @param $id Código del inmueble del que se desea obtener los titulares
	 *
	 * @return $dataProvider ArrayDataProvider con los datos de los titulares
	 */
	public function CargarTitulares ($arreglo = [])
	{
		if (count($arreglo == 0))
			$arreglo = $this->arregloTitulares;

	    $dataProvider = new ArrayDataProvider([
			 	'models' => $arreglo,
	            'key' => 'num',
				'totalCount' => (int)count($arreglo),
				'pagination' =>
					['pageSize' => 3,
					],
	        ]);

	    return $dataProvider;
	}

	/**
	 * Función que valida los datos que se ingresarán de titulares
     *
     * @param string $strArreglo= 'arregloTitulares'. Arreglo que se debe usar para validar los titulares
     * @param boolean $principalEsTitular= true. Si el tipo  vinculo del titular principal debe ser 'titular' (1)
	 */
	public function validarTitulares( $strArreglo = 'arregloTitulares', $principalEsTitular = true )
	{

		$porc     = 0; //Variable que almacenará el porcentaje de titularidad ingresado
		$titular  = 0; //Variable que almacenará 1 sihay un titular como titular principal
		$errores  = '';
		$session  = new Session;
		$session->open();
		$arregloSession   = $session->get($strArreglo, []);

		$session->close();

		//Validar que la sumatoria del porcentaje de titulares se encuentre entre 99 y 101 %
		foreach ($arregloSession as $array)
		{
			//($array['tvinc_nom'] == 'Responsable') && $this->tobj == 4) validacion para cementerio. Cuando el tipo de relacion es 4 == 'Responsable'

			//validacion anterior. Validaba por el nombre de tvinc
			//if (($array['tvinc_nom'] == 'Titular' || ($array['tvinc_nom'] == 'Responsable') && $this->tobj == 4) && $array['est'] == 'A') $porc = $porc + $array['porc'];

			//valida por el codigo de tvinc
			if (($array['tvinc'] == 6 || $array['tvinc'] == 7) && $array['est'] == 'A') $porc = $porc == 0 ? 100 : $porc;
			if (($array['tvinc'] == 1 || $array['tvinc'] == 5 || ($array['tvinc'] == 4 && $this->tobj == 4)) && $array['est'] == 'A') $porc = $porc + $array['porc'];
			if ($array['princ'] != '') $titular++;
			if ( $array['est'] == 'B' ) $errores = 'No puede haber titulares con estado Baja. Realice transferencia.';
		}

		if ($porc == 0)
			$errores = 'Debe ingresar los titulares de la cuenta.';
		else if ($porc >= 99 && $porc <= 101)
		{
			//Caso positivos
			//$errores .= '';
		} else {
			$errores = 'Los porcentajes de titularidad son incorrectos.';
		}

		if ($titular != 1 && $errores == '') $errores = 'Debe seleccionar un titular principal.';

		return $errores;

	}

     /**
     * Función que se encarga de guardar en la BD los datos de los titulares.
     */
    public function grabarTitulares($strArreglo = 'arregloTitulares')
    {
    	/**
    	 * Para grabar los titulares, primero se deben eliminar los datos de titulares que haya en la BD,
    	 * para luego agregar los datos que se encuentren en memoria.
    	 */

    	 $trans = Yii::$app->db->beginTransaction();

    	 //Eliminar los datos de la BD. En el caso de transeferencia, se colocan los estados en baja
    	 if($strArreglo === 'arregloTitularesTransferencia')
    	 	$sqlDelete = "Update objeto_persona Set est = 'B' Where obj_id = '$this->obj_id' And est = 'A'";
            else $sqlDelete = "UPDATE objeto_persona set est = 'B' WHERE obj_id = '" . $this->obj_id . "'";
    	 //else $sqlDelete = "DELETE FROM objeto_persona WHERE obj_id = '" . $this->obj_id . "'";

    	 /* Insertar los datos en la BD
    	 * 1. Obtener las keys delos arreglos actuales en memoria
    	 * 2. Realizar el INSERT en la BD con esos datos
    	 */

    	 $session = new Session;
    	 $session->open();

    	 $arreglo = $session[$strArreglo];
    	 $arrayKey = array_keys($arreglo);

    	 $session->close();

    	try{

    	 	//Ejecuto la sentencia que elimina los datos de la BD.
    	 	Yii :: $app->db->createCommand($sqlDelete)->execute();

			 foreach($arrayKey as $clave)
			 {
			 	//Sentencia que se ejecuta una vez por cada valor que se ingrese a la BD
			 	$sqlInsert = "Insert Into objeto_persona(obj_id, num, tobj, tvinc, porc, est, fchmod, usrmod) " .
			 			"Values('$this->obj_id', '" . $arreglo[$clave]['num'] . "', $this->tobj, " . $arreglo[$clave]['tvinc'] . ", " . $arreglo[$clave]['porc'] . ", '" . $arreglo[$clave]['est'] . "', " .
			 					"current_timestamp, " . Yii::$app->user->id . ")";

		    	Yii :: $app->db->createCommand($sqlInsert)->execute();

			 }

    	 } catch (Exception $e)
    	 {
    	 	$trans->rollBack();
    	 	$error = 'Ocurrió un error al intentar grabar en la BD.';
    	 	return $error;
    	 }

    	 $trans->commit();
    	 return "";

    }

    /**
     * Función que se utiliz para validar los datos que se ingresarán al cambiar titulares
     * @param string $expe Número de expediente
     * @param string $obs Observación
	 * @param Array $extras = [] Parametros extras dependiendo del tipo de objeto
	 * 	inmueble (tipo de objeto = 1):
	 * 		- @param string $extras['tmatric'] = ''. Tipo de matricula
	 * 		- @param string $extras['matric'] = ''. Matricula
	 * 		- @param string $extras['fchmatric'] = null. Fecha de la matricula
	 * 		- @param integer $extras['anio'] = 0. Año
     */
    public function validarDatosCambiarTitulares( $expe = '', $obs = '', $extras = [] )
    {

    }

    /**
     * Función que permite realizar la actualización de titulares en transferencia
     * @param integer $taccion Tipo de acción
     * @param array $arreglo Arreglo con los titularea a grabar en la BD.
     * @param string $expe Número de expediente
     * @param string $obs Observación
	 * @param Array $extras = [] Parametros extras dependiendo del tipo de objeto
	 * 	inmueble (tipo de objeto = 1):
	 * 		- @param string $extras['tmatric'] = ''. Tipo de matricula
	 * 		- @param string $extras['matric'] = ''. Matricula
	 * 		- @param string $extras['fchmatric'] = null. Fecha de la matricula
	 * 		- @param integer $extras['anio'] = 0. Año
     */
    public function cambiarTitulares($taccion, $arreglo, $expe, $obs, $extras = [])//, $tmatric,$matric,$fchmatric,$anio)
    {

		$sqlExtras = '';
		$datoIns = '';

		//dependiendo el tipo de objeto los datos que vienen en $extras y el sql que se debe ejecutar
		switch($this->tobj){

			//inmueble
			case 1:
				if(!array_key_exists('tmatric', $extras)) $extras['tmatric'] = '';
				if(!array_key_exists('matric', $extras)) $extras['matric'] = '';
				if(!array_key_exists('fchmatric', $extras)) $extras['fchmatric'] = null;
				if(!array_key_exists('anio', $extras)) $extras['anio'] = 0;

				$datoIns = '';
		    	$datoIns .= ($extras['tmatric'] != '' ? 'TMº' . $extras['tmatric'] : '');
		    	$datoIns .=  ($extras['tmatric'] != '' ? '/Mº' . $extras['matric'] : '');
		    	$datoIns .= ($extras['fchmatric'] != '' ? '/Fº' . $extras['fchmatric'] : '');
		    	$datoIns .= ($extras['anio'] != '' ? '/Aº' . $extras['anio'] : '');

				//Actualiza tmatric, matric, fchmatric y año del inmueble
				$sqlExtras = "UPDATE inm set tmatric='" . $extras['tmatric'] . "',matric='" . $extras['matric'] . "'," .
						"fchmatric=" . ($extras['fchmatric'] == null || $extras['fchmatric'] == '' ?  'null' : "'" . $extras['fchmatric'] . "'") . ",anio=" .$extras['anio'] . " WHERE obj_id = '" . $this->obj_id . "'";

				break;

			//comercio
			case 2:
			//persona
			case 3:
			//cementerio
			case 4:
			//rodado
			case 5:
		}

    	//Selecciona el número anterior
    	$numAnt = Yii::$app->db->createCommand("SELECT num FROM objeto WHERE obj_id='" . $this->obj_id . "'")->queryScalar();

    	//se validan los titulares
    	$error = $this->validarTitulares('arregloTitularesTransferencia');

   		//error que se produzca en la validacion de titulares
		if($error != '') return $error;

    	//se obtiene el titular principal
    	$principal = $this->getTitularPrincipal('arregloTitularesTransferencia');

		//se le asigna el nombre del titular principal al objeto en caso de que no se haya ingresado
		if($this->nombre == null || trim($this->nombre)=='')
			$this->nombre = $principal['apenom'];

		//Actualiza nombre y número del objeto
		$sqlObjeto = "UPDATE objeto set num = '" . $principal['num'] . "',nombre = '" . $this->nombre . "' WHERE obj_id = '" . $this->obj_id . "'";


    	$transaction = Yii::$app->db->beginTransaction();

		try{
	    	//se graban los titulares
	    	$error = $this->grabarTitulares('arregloTitularesTransferencia');

	    	if($error != ''){
	    		$transaction->rollBack();
   				return $error;
	    	}

			//se graban los cambios en el objeto
			$rowCount = Yii :: $app->db->createCommand($sqlObjeto)->execute();

			if ($rowCount === 0){
				$transaction->rollBack();
   				return 'Ocurrió un error al intentar grabar en la BD.';
			}

			//se ejecuta el sql extra que se formo dependiendo del tipo de objeto, siempre y cuando se haya seteado un sql
			if($sqlExtras != ''){

				$rowCount = Yii :: $app->db->createCommand($sqlExtras)->execute();

				if ($rowCount === 0){
					$transaction->rollBack();
   					return 'Ocurrió un error al intentar grabar en la BD.';
				}
			}

			//se graba la accion de transferencia
			$error = $this->NuevaAccion($taccion,date("d/m/Y"), "", "", $expe, $numAnt, $datoIns, $obs);

			if($error != ''){
				$transaction->rollBack();
   				return $error;
			}

		} catch (Exception $e)
		{
			$transaction->rollback();
			return DBException::getMensaje($e);
		}

		$transaction->commit();

		return "";
    }


    public function grabarDenunciaImpositiva(){

        $taccion= 36;

        //se validan los titulares
        $error= $this->validarTitulares('arregloTitularesDenunciaImpositiva');

        if($error != '') return $error;

        $transaction= Yii::$app->db->beginTransaction();

        try{
            $error= $this->grabarTitulares('arregloTitularesDenunciaImpositiva');

            if($error != ''){
                $transaction->rollBack();
                return $error;
            }

            /*
            Descomentar si se quiere actualizar el nombre
            //se graba el nuevo nombre
            $sql= "Update objeto set nombre = '$this->nombre' Where obj_id = '$this->obj_id'";
            Yii::$app->db->createCommand($sql)->execute();
            */

            //se actualiza el campo num del objeto
            $titularPrincipal= $this->getTitularPrincipal('arregloTitularesDenunciaImpositiva');

            $sql= "Update objeto Set nombre = '$this->nombre', num = '" . $titularPrincipal['num'] . "' Where obj_id = '$this->obj_id'";
            Yii::$app->db->createCommand($sql)->execute();

            //se graba la accion
            $error = $this->NuevaAccion($taccion,date("d/m/Y"), "", "", '', $this->num, '', 'Denuncia impositiva');

            if($error != ''){
                $transaction->rollBack();
                return $error;
            }

        } catch(Exception $e){
            $transaction->rollBack();
            return DBException::getMensaje($e);
        }

        $transaction->commit();
        return '';
    }

    /**
     * Obtiene un arreglo con los datos del titular principal guardado en session.
     *
     * Un arreglo vacio significa que no hay titular principal
     */
    public function getTitularPrincipal($strArreglo = 'arregloTitulares'){

    	$ret = [];

    	$cerrarSession = false;
    	$session = new Session();

    	if(!$session->getIsActive()){
    		$session->open();
            $cerrarSession= true;
    	}

    	$titulares = $session->get($strArreglo, []);

    	if($cerrarSession)
    		$session->close();

    	foreach($titulares as $clave => $valor)
    		if($valor['princ'] != '')
    			return $valor;

    	return $ret;
    }


   	//----------------------------------------------------------------------------------//
    //------------------------------------DOMICILIO-------------------------------------//
    //----------------------------------------------------------------------------------//

    /**
     * Realiza un cambio de domicilio
     * @param integer $taccion Tipo de acción que se ejecutará
     * @param string $expe Expediente
     * @param string $obs Observación
     * @param string $domiAnt Domicilio anterior
     */
    public function cbioDomicilio($taccion,$expe,$obs,$domiAnt)
    {


    	if ($taccion == 7) //Cambio de domicilio postal
    	{


    	} else if ($taccion == 8) //Cambio de domicilio legal
    	{

    	}

    }

    public function getDPTributos(){

    	$sql = "Select obj_id,trib_id,perdesde,trib_nom_redu, per_desde, per_hasta, cat_nom, fchalta, base, cant, sup  From v_objeto_trib Where obj_id = '$this->obj_id'";
    	$modelos = Yii::$app->db->createCommand($sql)->queryAll();

    	return new ArrayDataProvider([
    		'allModels' => $modelos,
    		'pagination' => [
    			'pageSize' => 5
    		]
    	]);
    }
}
