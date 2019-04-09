<?php

namespace app\models\caja;
use yii\helpers\ArrayHelper;
use yii\data\SqlDataProvider;
use app\utils\db\utb;

use Yii;

/**
 * This is the model class for table "caja".
 *
 * @property integer $caja_id
 * @property string $nombre
 * @property integer $usr_id
 * @property integer $teso_id
 * @property integer $sup1
 * @property integer $sup2
 * @property integer $sup3
 * @property integer $sup4
 * @property integer $tipo
 * @property integer $destino
 * @property integer $validar
 * @property integer $copia
 * @property integer $resumen
 * @property integer $editamonto
 * @property string $ext_num
 * @property integer $ext_bco_ent
 * @property integer $ext_tori
 * @property string $ext_host
 * @property string $ext_usr
 * @property string $ext_pwd
 * @property string $ext_recurso
 * @property integer $ext_tdisenio
 * @property string $ext_cod_ent
 * @property string $est
 * @property string $fchmod
 * @property integer $usrmod
 * @property sub_est $sub_est
 *
 * @property array $mediosPago
 */
class Caja extends \yii\db\ActiveRecord
{
	public $sub_est;
	public $mediosPago;	//Arreglo que contendrá los medios de pago habilitados para el usuario.
	public $arrayMediosPago;	//Arreglo que contendrá todos los medios de pago habilitados en el sistema.

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'caja';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['caja_id', 'nombre', 'usr_id', 'teso_id', 'sup1', 'tipo', 'destino','mediosPago'], 'required'],
            [['caja_id', 'usr_id', 'teso_id', 'sup1', 'sup2', 'sup3', 'sup4', 'tipo', 'destino', 'validar', 'copia', 'resumen', 'editamonto', 'ext_bco_ent', 'ext_tori', 'ext_tdisenio'], 'integer'],
            [['nombre'], 'string', 'max' => 20],
            [['tipo'],'compare', 'compareValue' => 0, 'operator' => '>', 'message' => 'Ingrese un tipo.'],
            [['usr_id'],'compare', 'compareValue' => 0, 'operator' => '>', 'message' => 'Ingrese un cajero.'],
            [['sup1'],'compare', 'compareValue' => 0, 'operator' => '>', 'message' => 'Ingrese un supervisor.'],
            [['ext_num'], 'string', 'max' => 8],
            [['ext_host', 'ext_usr', 'ext_pwd', 'ext_recurso'], 'string', 'max' => 30],
            [['ext_cod_ent'], 'string', 'max' => 20],
            [['est'], 'string', 'max' => 1],
            [['sub_est'], 'integer'],
			//[['mediosPago'],'each','rule' => ['boolean']], No se dispone de la refla "each" en la version 2.0.2 (Versión actual al 04/05/2016)
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'caja_id' => 'Código:',
            'nombre' => 'Nombre:',
            'usr_id' => 'Cajero',
            'teso_id' => 'Tesorería',
            'sup1' => 'Supervisor1:',
            'sup2' => 'Supervisor2:',
            'sup3' => 'Supervisor3:',
            'sup4' => 'Supervisor4:',
            'tipo' => 'Codigo de tipo',
            'destino' => 'Destino:',
            'validar' => 'Validar',
            'copia' => 'con Copia',
            'resumen' => 'Resumen',
            'editamonto' => 'Edita monto',
            'ext_num' => 'Contrib.',
            'ext_bco_ent' => 'Banco:',
            'ext_tori' => 'Tipo de origen',
            'ext_host' => 'Host',
            'ext_usr' => 'Usuario',
            'ext_pwd' => 'Contraseña',
            'ext_recurso' => 'Recurso',
            'ext_tdisenio' => 'Tipo de dise�o de los archivos de resumen',
            'ext_cod_ent' => 'Código asignado por la entidad:',
            'est' => 'Caja Activa',
            'sub_est' => 'Caja Activa',
            'fchmod' => 'Fecha de modificacion',
            'usrmod' => 'Codigo de usuario que modifico',
        ];
    }

    /*
	 * La variable $error es una variable que mantendrá los errores que fueron ocurriendo
	 */
	private $error = "";

	private function resetError(){

		$this->error = "";

	}

	public function afterFind(){

		//Obtener los medios de pago
		$this->arrayMediosPago = $this->obtenerMediosDePagoDelSistema();
	}

	public function __construct(){
		//Obtener los medios de pago
		$this->arrayMediosPago = $this->obtenerMediosDePagoDelSistema();
	}

	/**
	 * Función que se utiliza para obtener los medios de pago hablitados en el sistema.
	 */
	public function obtenerMediosDePagoDelSistema(){

		$sql = 	"SELECT m.mdp, m.nombre, 0 as activa FROM caja_mdp m " .
				"WHERE m.habilitado = 1 AND m.mdp NOT IN (select mdp FROM caja_caja_mdp WHERE caja_id = " . intVal( $this->caja_id ) . " ) UNION ".
				"SELECT t.mdp, t.nombre, 1 as activa FROM caja_mdp t " .
				"WHERE t.habilitado = 1 AND t.mdp IN (select mdp FROM caja_caja_mdp WHERE caja_id = " . intVal( $this->caja_id ) . " ) ".
				"ORDER BY nombre ASC";

		return Yii::$app->db->createCommand( $sql )->queryAll();

	}

	public function asignarMediosDePagoDeUsuario(){

		$this->arrayMediosPago = $this->obtenerMediosDePagoDelSistema();

		if( count( $this->mediosPago ) > 0 ){

			foreach( $this->mediosPago as $id => $valor ){

				foreach( $this->arrayMediosPago as &$array ){

					if( $array[ 'mdp' ] == $id ){

						$array[ 'activa' ] = $valor;
					}
				}
			}
		}
	}

	/**
	 * Función que se utiliza para obtener un arreglo con las tesorerías asignadas a un usuario.
	 * @param integer $user_id Identificador de usuario.
	 */
	public static function obtenerTesoreriasSegunUsuario( $user_id ){

		//$condTesoreria almacena la condición por la cual se buscarán las tesorerías en la BD.
		$condTesoreria = "est = 'A' and teso_id IN (SELECT teso_id FROM sam.sis_usuario_tesoreria WHERE usr_id = " . $user_id . ")";

		//$arrayTesoreria es el arreglo que contiene los nombres de las tesorerías
		$arrayTesoreria = utb::getAux('caja_tesoreria','teso_id','nombre',0, $condTesoreria);

		return $arrayTesoreria;

	}

	private function setError($mensajeError){

		$this->error = $this->error . $mensajeError;

	}

	public function getError () {

		return $this->error;

	}


    /**
	 * Función que retorna el usuario que realizá la última modificación
	 * @param integer id Variable que almacenará el valor del id del usuario
	 * @return string cmd Nombre del usuario que realizá la última modificación
	 */
	public function getUsuarioModifica ($id)
	{

		$cmd = "";

		if ($id != ""){

			$sql = "SELECT nombre FROM sam.sis_usuario WHERE usr_id = " . $id;
			$cmd = Yii :: $app->db->createCommand($sql)->queryScalar();

		}

		return $cmd;

	}

	/**
	 * Función que obtiene los supervisores para las cajas
	 * @param integer $teso_id Identificador de la tesorería
	 * @return array Arreglo con los supervisores
	 */
	public function getSupervisores($teso_id)
	{

		$sql = "Select Distinct u.Usr_Id as cod, ApeNom as Nombre From SAM.Sis_Usuario u Inner Join SAM.Sis_Usuario_Proceso up ";
        $sql .= "On u.Usr_Id = up.Usr_Id Where u.Est = 'A' and up.Pro_Id in (3414,3415,3416) ";
        $sql .= "And u.Usr_Id In (Select Usr_Id From Sam.Sis_Usuario_Tesoreria Where Teso_Id=" . $teso_id . ")";
        $sql .= " Order By ApeNom";

		try
		{
			$cmd = Yii::$app->db->createCommand($sql);
			$res = $cmd->queryAll();
			$res = array_merge([0 => ['cod' => 0, 'nombre' => '<ninguno>']], $res);

			$arreglo = ArrayHelper::map($res, 'cod', 'nombre');
			asort($arreglo, SORT_STRING);
		}
		catch(Exception $e){
			$arreglo = [];
		}

		return $arreglo;
	}

    private function validar()
    {
		$error = [];

    	//Inicio validación de elementos
    	if($this->teso_id == '')
    		$this->teso_id = 0;

    	if ($this->destino == '')
    		$this->destino = 0;

    	if ($this->sup3 == '')
    		$this->sup3 = 0;

    	if ($this->sup4 == '')
    		$this->sup4 = 0;

    	if ($this->sub_est == 0)
    		$this->est = 'B';
    	else
    		$this->est = 'A';
    	//Fin validación de elementos

    	if ($this->tipo == 0)
			$error[] = 'Debe ingresar un tipo de caja.';

    	if ($this->usr_id == 0)
    		$error[] = 'Debe ingresar un cajero.';

    	if ($this->sup1 == 0)
    		$error[] = 'Debe ingresar al menos un supervisor.';

		if ($this->tipo == 1 and $this->teso_id==0)
			$error[] = 'La Tesorería no puede ser Externa para caja On-Line.';

    	if(count($error) == 0){
	    	//Inicio Validar que los supervisores seleccionados no sean iguales entre sí, o al cajero
	    	if($this->sup1 > 0 and $this->sup2 > 0)
	    	{
	    		if ($this->sup1 == $this->sup2)
	    		{
	    			$error[] = 'Debe seleccionar distintos supervisores.';
	    			$this->sup2 = 0;
	    		}
	    	}

	    	if ($this->sup3 > 0)
	    	{
	    		if ($this->sup1 == $this->sup3 || $this->sup2 == $this->sup3)
	    		{
	    			$error[] = 'Debe seleccionar distintos supervisores.';
	    			$this->sup2 = 0;
	    			$this->sup3 = 0;
	    		}
	    	}

	    	if ( $this->sup4 > 0 )
	    	{
	    		if($this->sup1 == $this->sup4 || $this->sup2 == $this->sup4 || $this->sup3 == $this->sup4)
	    		{
					$error[] = 'Debe seleccionar distintos supervisores.';
	    			$this->sup2 = 0;
	    			$this->sup3 = 0;
	    			$this->sup4 = 0;
	    		}

	    	}
	    	//Fin Validar que los supervisores seleccionados no sean iguales entre sí, o al cajero
    	}

   		//Validar que se ingrese un contribuyente cuando tipo >= 2
   		if( $this->tipo >= 2 )
   		{
   			if ($this->ext_num == '')
				$error[] = 'Debe ingresar un contribuyente.';
   		}

   		//Validar que se ingrese host, usuario, contraseña y método/archivo cuando origen = 2 o origen = 3
   		if ( $this->ext_tori == 2 || $this->ext_tori == 3 )
   		{
   			if ($this->ext_host == '')
				$error[] = 'Debe ingresar un host.';

   			if ($this->ext_recurso == '')
				$error[] = 'Debe ingresar un recurso.';

   			if ($this->ext_usr == '')
				$error[] = 'Debe ingresar un usuario.';

   			if ($this->ext_pwd == '')
				$error[] = 'Debe ingresar una contraseña.';

   		}
   		//Fin validar host, usuario, contraseña, recurso.

   		//Validar que si tipo de origen = 1, se debe establecer un tipo de diseño y limpio host,recurso,usr y pwd
   		if ($this->ext_tori == 1)
   		{
   			$this->ext_host = '';
   			$this->ext_recurso = '';
			$this->ext_usr = '';
			$this->ext_pwd = '';

   			if ( $this->ext_tdisenio == 0 )
				$error[] = 'Debe ingresar un diseño.';

   		}

		$this->addErrors($error);

    }

	/**
	 * Función que se utiliza para grabar los medios de pago habilitados para una caja.
	 *	@param integer $caja_id Identificador de la caja.
	 */
	private function grabarMediosDePago( $caja_id, $mediosPago ){

		$this->eliminarMediosDePago( $caja_id );

		try{

			foreach( $mediosPago as $mdp => $activo ){

				if( $activo ){

					$sql = "INSERT INTO caja_caja_mdp VALUES ( " . $caja_id . "," . $mdp . ")";

					Yii::$app->db->createCommand( $sql )->execute();

				}
			}
		} catch(\Exception $e ){
			return false;
		}

		return true;

	}

	/**
	 * Función que se utiliza para eliminar los medios de pago habilitados para una caja.
	 *	@param integer $caja_id Identificador de la caja.
	 */
	private function eliminarMediosDePago( $caja_id ){

		$sql = "DELETE FROM caja_caja_mdp WHERE caja_id = " . $caja_id;

		try{
			Yii::$app->db->createCommand( $sql )->execute();
		} catch ( \Exception $e ){
			return false;
		}

		return true;
	}

    /**
     * Procedimiento que graba una nueva caja o modifica una existente, según sea la opción requerida.
     */
    public function grabar()
    {
    	$this->validar();

		//Si es un nuevo registro
		if ( $this->isNewRecord ) {

            $sql = "SELECT COUNT(*) FROM caja WHERE caja_id = " . $this->caja_id;
            $count = Yii :: $app->db->createCommand($sql)->queryScalar();

            if ($count > 0 )
            {
            	$this->addError( 'caja_caja_id', 'El código de caja ya existe.' );

            } else {

	            $sql = "INSERT INTO caja(caja_id,nombre,usr_id,teso_id,sup1,sup2,sup3,sup4,tipo,destino,validar,copia,resumen,editamonto,";
	            $sql .= "ext_num,ext_bco_ent,ext_tori,ext_host,ext_usr,ext_pwd,ext_recurso,ext_tdisenio,ext_cod_ent,est,fchmod,usrmod) VALUES (";
	            $sql .= $this->caja_id . ",'" . $this->nombre . "'," . $this->usr_id . "," . $this->teso_id . ",";
	            $sql .= $this->sup1 . "," . $this->sup2 . "," . $this->sup3 . "," . $this->sup4 . "," . $this->tipo . ",";
	            $sql .= $this->destino . "," . $this->validar . "," . $this->copia . "," . $this->resumen . ",";
	            $sql .= $this->editamonto . "," . ($this->ext_num != '' ? "'" . $this->ext_num . "'" : "''" ) . ",";
	            $sql .= ($this->ext_bco_ent != '' ? $this->ext_bco_ent : 0) . ",";
	            $sql .= ($this->ext_tori != '' ? $this->ext_tori : 0) . ",";
	            $sql .= ($this->ext_host != '' ? "'" . $this->ext_host . "'" : "''") . ",";
	            $sql .= ($this->ext_usr != '' ? "'" . $this->ext_usr . "'" : "''") . ",";
	            $sql .= ($this->ext_pwd != '' ? "'" . $this->ext_pwd . "'" : "''") . ",";
	            $sql .= ($this->ext_recurso != '' ? "'" . $this->ext_recurso . "'" : "''") . ",";
	            $sql .= ($this->ext_tdisenio != '' ? $this->ext_tdisenio : 0) . ",";
	            $sql .= ($this->ext_cod_ent != '' ? "'" . $this->ext_cod_ent . "'" : "''") . ",";
	            $sql .= "'" . $this->est . "',current_timestamp," .  Yii :: $app->user->id . ")";

			}

		} else {

			$sql = "UPDATE caja SET nombre= '" . $this->nombre . "',usr_id=" . $this->usr_id . ",teso_id=" . $this->teso_id;
            $sql .= ",sup1=" . $this->sup1 . ",sup2=" . $this->sup2 . ",sup3=" . $this->sup3 . ",sup4=" . $this->sup4;
            $sql .= ",tipo=" . $this->tipo . ",destino=" . $this->destino . ",validar=" . $this->validar;
            $sql .= ",copia=" . $this->copia . ",resumen=" . $this->resumen . ",est='" . $this->est . "',editamonto=" . $this->editamonto;
            $sql .= ",ext_num=" . ($this->ext_num != '' ? "'" . $this->ext_num . "'" : "''" ) . ",";
            $sql .= "ext_bco_ent=" . ($this->ext_bco_ent != '' ? $this->ext_bco_ent : 0) . ",";
			$sql .= "ext_tori=" . ($this->ext_tori != '' ? $this->ext_tori : 0) . ",";
            $sql .= "ext_host=" . ($this->ext_host != '' ? "'" . $this->ext_host . "'" : "''") . ",";
			$sql .= "ext_usr=" . ($this->ext_usr != '' ? "'" . $this->ext_usr . "'" : "''") . ",";
			$sql .= "ext_pwd=" . ($this->ext_pwd != '' ? "'" . $this->ext_pwd . "'" : "''") . ",";
			$sql .= "ext_recurso=" . ($this->ext_recurso != '' ? "'" . $this->ext_recurso . "'" : "''") . ",";
            $sql .= "ext_tdisenio=" . ($this->ext_tdisenio != '' ? $this->ext_tdisenio : 0) . ",";
			$sql .= "ext_cod_ent=" . ($this->ext_cod_ent != '' ? "'" . $this->ext_cod_ent . "'" : "''") . ",";
            $sql .= "fchmod=" . "current_timestamp,usrmod=" .  Yii :: $app->user->id;
            $sql .= " WHERE caja_id=" . $this->caja_id;

		}

		//Si no se produjo ningún error al validar
		if ( !$this->hasErrors() )
		{
			$transaction = Yii::$app->db->beginTransaction();

			try
			{
				if( !$this->grabarMediosDePago( $this->caja_id, $this->mediosPago ) ){
					$this->addError( 'caja_caja_id', 'Ocurrió un error.' );
				}

				$rowCount = Yii :: $app->db->createCommand($sql)->execute();

			} catch (Exception $e){

				$transaction->rollback();

				$this->addError( 'caja_caja_id', DBException::getMensaje($e) );

				return false;
			}

			$transaction->commit();

			return true;

		} else {
			return false;
		}
    }


    /**
     * Procedimiento que elimina una caja de la BD
     */
    public function borrar()
    {
    	$indice = 0;

		$error = [];

    	$sql = "SELECT COUNT(*) FROM caja_estado WHERE caja_id =" . $this->caja_id;
        $count = Yii :: $app->db->createCommand($sql)->queryScalar();


        if ($count > 0)
            $error[$indice++] = "La caja ha sido utilizada. No se puede eliminar";
		else {

	        $sql = "SELECT COUNT(*) FROM caja_ticket WHERE caja_id =" . $this->caja_id;
	        $count = Yii :: $app->db->createCommand($sql)->queryScalar();

	        if ($count > 0)
           		$error[$indice++] = "La caja ha sido utilizada. No se puede eliminar";
			else {

		        $sql = "DELETE FROM caja WHERE caja_id=" . $this->caja_id;

		        try
				{
					$cmd = Yii :: $app->db->createCommand($sql);
					$rowCount = $cmd->execute();
				} catch(Exception $e){
					//$this->setError(DBException::getMensaje($e));
					$rowCount = 0;
					$error[$indice++] = 'No se pudo eliminar la caja.';
					$this->addErrors($error);
				}

	           return $rowCount > 0;
			}
		}
		$this->addErrors($error);
    }


    /**
     * Función que obtiene los tipos de tesorería
     *
     * @param string $tesoreria String que contendrá las tesorerías que hay que mostrar
     *
     * @return ArrayHelper con el listado de las tesorerías
     */
    public function getTipoTesoreria ()
    {
    	$sql = "SELECT teso_id, nombre FROM caja_tesoreria WHERE teso_id IN (0,1) ORDER BY teso_id";

    	$cmd = Yii :: $app->db->createCommand($sql);

		return ArrayHelper :: map($cmd->queryAll(), 'teso_id', 'nombre');
    }

    /**
     * Función que se encarga de buscar las cajas
     *
     * @param string $tesoreria String que selecciona el tipo de tesoreria a buscar
     *
     * @params tring $soloactivas String que selecciona todas las cuentas o solo las activas
     */
    public function buscaCaja( $tesoreria = '', $soloactivas)
    {

		//Si no se ingresa ninguna tesorería, se retorna un arreglo vacío.
		if( $tesoreria == '' ){
			return [];
		}

    	if ( $soloactivas == 1 ){

    		$cond = "est='A' AND ";

    	} else {

    		$cond = "";
    	}

		$sql = 	"SELECT caja_id, nombre" .
				" FROM caja WHERE " . ( $cond != '' ?  $cond : '') . "teso_id IN (" . $tesoreria . ")" . " ORDER BY caja_id";

        return Yii::$app->db->createCommand( $sql )->queryAll();

    }


    /**
	 * Función que retorna el nombre de una cuenta
	 * @param string tabla
	 * @param string campocod
	 * @param string camponombre
	 * @param string cond
	 * @return string Value Nombre de la cuenta con identificación = id
	 */
	public function getNombre($tabla,$camponombre,$cond) {

		$cmd = "";

		$sql = 'SELECT ' . $camponombre . ' as nombre FROM ' . $tabla . ' WHERE ' . $cond;
		$cmd = Yii :: $app->db->createCommand($sql)->queryScalar();


		return $cmd;


	}


}
