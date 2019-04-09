<?php

namespace app\models\usuario;
use app\utils\helpers\DBException;
use app\models\User;
use app\utils\db\Fecha;
use yii\helpers\ArrayHelper;

use Yii;

/**
 * This is the model class for table "sam.sis_usuario".
 *
 * @property integer $usr_id
 * @property string $nombre
 * @property string $clave
 * @property string $apenom
 * @property string $domi
 * @property integer $tdoc
 * @property string $ndoc
 * @property integer $oficina
 * @property string $cargo
 * @property integer $legajo
 * @property integer $matricula
 * @property integer $grupo
 * @property string $est
 * @property string $tel
 * @property string $cel
 * @property string $mail
 * @property integer $distrib
 * @property integer $inspec_inm
 * @property integer $inspec_comer
 * @property integer $inspec_op
 * @property integer $inspec_juz
 * @property integer $inspec_recl
 * @property integer $abogado
 * @property integer $cajero
 * @property string $fchalta
 * @property string $fchbaja
 * @property string $fchmod
 * @property integer $usrmod
 *
 *	*** TESORERÍA ***
 *
 * @property array $tesoreria
 *
 *  *** PERMISOS ***
 * @property array $permisos
 * @property integer $sistema
 * @property integer $modulo
 * @property integer $permiso_id
 * @property string $permiso_nombre
 * @property string $permiso_detalle
 * @property string $permiso_sistema_nombre
 * @property string $permiso_modulo_nombre
 *
 *  *** GRUPOS ***
 * @property integer $gru_id
 * @property string $gru_nombre
 * @property integer $gru_admin1
 * @property integer $gru_admin2
 */
class Usuario extends \yii\db\ActiveRecord
{

	/*
	 * Declaro la variable tesorería que almacenará un arreglo de arreglos respecto de las tesorerías
	 * y el estado de las mismas para el usuario.
	 *
	 * $tesoreria['id' => 'estado'];
	 */
	public $tesoreria;

	public $tributo;

	/*
	 * Declaro la variable permiso que almacenará un arreglo de arreglos respecto de los permisos
	 * y el estado de los mismos para el usuario.
	 *
	 * $tesoreria['id' => 'estado'];
	 *
	 */
	public $permisos;

	// La variable $sistema almacenará el sistema para el cuál se necesitan ver los permisos en la "Consulta de usuario"
	public $sistema;

	// La variable $modulo almacenará el módulo para el cuál se necesitan ver los permisos en la "Consulta de usuario"
	public $modulo;

	public $permiso_id;			//Variable que almacenará el ID de un proceso para información del mismo
	public $permiso_nombre;		//Variable que almacenará el nombre de un proceso para información del mismo
 	public $permiso_detalle;	//Variable que almacenará el detalle de un proceso para información del mismo
 	public $permiso_sistema_nombre;
 	public $permiso_modulo_nombre;

	//--------------- VARIABLES DE GRUPO ---------------------------//

	public $gru_id;	//Id de grupo
	public $gru_nombre;	//Nombre de grupo
	public $gru_admin1;	//Id del administrador 1 del grupo
	public $gru_admin2;	//Id del administrador 2 del grupo
	public $gru_procesos;

	public static function tableName()
    {
        return 'sam.sis_usuario';
    }

	public function rules()
	{
		return [
				//El número de documento es requerido
				['ndoc','required'],
				['ndoc','number'],
				[['usr_id', 'tdoc','inspec_op','inspec_juz','inspec_inm','distrib','inspec_comer','cajero','inspec_recl','abogado'],'integer'],
				[['domi'],'string','min' => 4],
				[['est',],'string','min' => 1,'max'=>1],
				['grupo','compare','compareValue' => 0, 'operator' => '>','message' => 'Debe seleccionar un grupo.'],
				[['oficina'],'integer'],
				['mail','email'],
				[['nombre','apenom','mail','tel','cel','matricula','legajo','cargo'],'string'],
				[['tesoreria','tributo','permisos','matricula','legajo','sistema','modulo'],'default','value' => 0],
				[['nombre','apenom'],'trim'],

				// Variables de Grupo
				[['gru_id','gru_admin1','gru_admin2'],'number'],
				['gru_nombre','string'],
				[['gru_procesos'],'default','value' => 0],

				//Variables de permiso
				['permiso_id','number'],
				[['permiso_nombre','permiso_detalle','permiso_sistema_nombre','permiso_modulo_nombre'],'string'],
			];
	}

	public function attributeLabels()
	{
		return[
				'usr_id' => 'Tributo',
				'ndoc' => 'Documento',
				'mail' => 'e-mail',
		];
	}

	public function findGrupo($id)
	{
		$sql = "SELECT gru_id,nombre,usradm1,usradm2 FROM sam.sis_grupo WHERE gru_id = " . $id;

		$data = Yii::$app->db->createCommand($sql)->queryAll();

		if (count($data) > 0)
		{
			$array = $data[0];

			$this->gru_id = $array['gru_id'];
			$this->gru_nombre = $array['nombre'];
			$this->gru_admin1 = $array['usradm1'];
			$this->gru_admin2 = $array['usradm2'];

			$this->setIsNewRecord(FALSE);

		}

	}

	/**
	 * Función que recibe el Id de un grupo de usuarios, y devuelve un arreglo con los usuarios que forman parte de ese grupo.
	 * @param integer $id_grupo Identificador del grupo
	 * @param integer $ofi_id Identificador de la oficina
	 * @param string $usr_nom Nombre del usuario
	 * @param stirng $est Estado del usuario
	 * @return array Usuarios
	 */
	public function getUsuarios( $id_grupo = 0, $ofi_id = 0, $usr_nom = '',$est = '' )
	{
		$sql = "Select Usr_Id,Nombre,Est,grupo From sam.Sis_Usuario";

		$cond = '';
		if ($id_grupo != 0)
			$cond .= " grupo = " . $id_grupo;

		if( $ofi_id != 0 ){
			if ($cond != '') $cond .= " and ";
			$cond .= " oficina = " . $ofi_id;
		}

		if ($usr_nom != ''){
			if ($cond != '') $cond .= " and ";
			$cond .= " upper(nombre) like upper('%" . $usr_nom . "%')";
		}

		if ($est != ''){
			if ($cond != '') $cond .= " and ";
			$cond .= " est = '" . $est . "'";
		}

		if ($cond != '')
			$sql .= " Where " . $cond;

		$sql .= " ORDER BY nombre";
		$data = Yii::$app->db->createCommand($sql)->queryAll();

		return $data;
	}

	/**
	 * Función que se utiliza para verificar si un grupo tiene usuarios asociados.
	 * @param integer $grupo_id Identificador del grupo
	 * @return integer 1 => Presenta usuarios asociados - 0 => No presenta usuarios asociados.
	 */
	public function existeUsuarioAsociado($grupo_id)
	{
		$sql = "SELECT EXISTS (SELECT 1 FROM sam.sis_usuario WHERE grupo = " . $grupo_id . ")";

		return Yii::$app->db->createCommand($sql)->queryScalar();
	}

	/**
	 * Función que obtiene las tesorerías asignadas a un usuario.
	 */
	public function getTesoreriasUsuario()
	{
		if ($this->usr_id == '' || $this->usr_id == null)
			$usr_id = -1;
		else
			$usr_id = $this->usr_id;

		$sql = "Select Case When s.Usr_Id is null Then 0 Else 1 End as Existe,t.Teso_Id, t.Nombre " .
				"From Caja_Tesoreria t " .
				"Left Join sam.Sis_Usuario_Tesoreria s On s.Teso_Id = t.Teso_Id and Usr_Id = " . $usr_id;

		$data = Yii::$app->db->createCommand($sql)->queryAll();

		return $data;
	}

	/**
	 * Función que obtiene los tributos asignados a un usuario.
	 */
	public function getTributoUsuario()
	{
		if ($this->usr_id == '' || $this->usr_id == null)
			$usr_id = -1;
		else
			$usr_id = $this->usr_id;

		$sql = "Select Case When s.Usr_Id is null Then 0 Else 1 End as Existe,t.Trib_Id, t.Nombre " .
				"From trib t " .
				"Left Join sam.Sis_Usuario_Trib s On s.Trib_Id = t.Trib_Id and Usr_Id = " . $usr_id . " where t.tipo in (3,4) order by t.nombre ";

		$data = Yii::$app->db->createCommand($sql)->queryAll();

		return $data;
	}

	/**
	 * Función que recibe el ID de un usuario y devuelve un arreglo con los procesos habilitados para el grupo al cual corresponde
	 * el usuario.
	 * @param integer $usuario_id Id del usuario
	 * @param integer $sistema_id Id del sistema
	 * @param integer $modulo_id Id del módulo
	 * @return array Procesos del grupo del usuario
	 */
	public function getProcesos($usuario_id,$sistema_id = 0,$modulo_id = 0)
	{
		$sql = "select case when p.Pro_Id in (select Pro_Id From sam.Sis_Usuario_Proceso " .
				"Where Usr_Id= " . $usuario_id . ") then 'SI' else 'NO' end Existe_Desc,case when p.Pro_Id in (select Pro_Id From sam.Sis_Usuario_Proceso " .
				"Where Usr_Id= " . $usuario_id . ") then 1 else 0 end Existe, p.Pro_Id, p.Nombre,s.Nombre as Sistema, m.Nombre as Modulo, p.Detalle " .
				"from sam.Sis_Proceso p inner join sam.Sis_Modulo m on p.mod_id=m.Mod_Id " .
				"inner join sam.Sis_Sistema s on m.sis_id=s.Sis_Id " .
				"inner join sam.Sis_Grupo_Proceso gp on p.Pro_Id=gp.Pro_Id " .
				"inner join sam.Sis_Usuario u on gp.Gru_Id = u.grupo " .
				"Where u.Usr_Id = " . $usuario_id;

		if ($sistema_id != 0)
			$sql .= " AND s.sis_id = " . $sistema_id;

		if ($modulo_id != 0)
			$sql .= " AND m.mod_id = " . $modulo_id;

		$sql .= " ORDER BY s.Nombre,m.Nombre,p.nombre";

		$data = Yii::$app->db->createCommand($sql)->queryAll();

		return $data;
	}
	
	public function getProcesosAsignados($usuario_id,$sistema_id = 0,$modulo_id = 0)
	{
		$sql = "select up.pro_id, p.nombre, s.nombre as sistema, m.nombre as modulo " .
				"from sam.sis_usuario_proceso up " . 
				"inner join sam.sis_proceso p on up.pro_id=p.pro_id " . 
				"inner join sam.Sis_Modulo m on p.mod_id=m.Mod_Id " .
				"inner join sam.Sis_Sistema s on m.sis_id=s.Sis_Id " .
				"Where up.Usr_Id = " . $usuario_id;

		if ($sistema_id != 0)
			$sql .= " AND s.sis_id = " . $sistema_id;

		if ($modulo_id != 0)
			$sql .= " AND m.mod_id = " . $modulo_id;

		$sql .= " ORDER BY s.Nombre,m.Nombre,p.nombre";

		$data = Yii::$app->db->createCommand($sql)->queryAll();

		return $data;
	}

	/**
	 * Función que obtiene los procesos pertenecientes a un grupo.
	 */
	public function getGrupos($sistema_id,$modulo_id)
	{
		$sql = "Select case when gp.gru_id is null then 0 else 1  end Existe, s.nombre as sistema, m.nombre as modulo,p.pro_id, p.nombre, p.detalle " .
				"from sam.sis_sistema s " .
				"inner join sam.sis_modulo m on s.sis_id=m.sis_id " . ($modulo_id != 0 ? "AND m.mod_id = " . $modulo_id . " " : "") .
				"inner join sam.sis_proceso p on m.mod_id=p.mod_id " .
				"Left join sam.Sis_Grupo_Proceso gp on gp.gru_id = " . $this->gru_id . " and p.pro_id = gp.pro_id ";

		if ($sistema_id != 0)
			$sql .= "WHERE s.sis_id = " . $sistema_id;

		$sql .= " ORDER BY s.nombre,m.nombre,p.nombre";

		$data = Yii::$app->db->createCommand($sql)->queryAll();

		return $data;
	}

	/**
	 * Función que retorna un arreglo con los procesos asignados a un sistema.
	 * @param integer $sistema_id ID del sistema.
	 * @return array Procesos
	 */
	public function getProcesoSistema( $sistema_id, $modulo_id, $nombre = '' )
	{
		$sql= "select s.nombre as sistema, m.nombre as modulo, p.nombre, p.detalle, p.pro_id " .
				"from sam.sis_sistema s " .
				"inner join sam.sis_modulo m on s.sis_id=m.sis_id " . ($modulo_id != 0 ? " AND m.mod_id = " . $modulo_id : "") .
				"inner join sam.sis_proceso p on m.mod_id=p.mod_id " .
				( $nombre != '' ? " AND ( UPPER(p.nombre) LIKE UPPER('%" . $nombre . "%') OR UPPER(p.detalle) LIKE UPPER('%" . $nombre . "%') )" : '' ).
				"where s.sis_id= " . $sistema_id .
				" order by s.sis_id,m.nombre,p.nombre";

    	$res = Yii::$app->db->createCommand($sql)->queryAll();

    	$data = ArrayHelper::map($res, 'pro_id', function($model){ return [
    												'pro_id' => $model['pro_id'],
    												'nombre' => $model['nombre'],
    												'detalle' => $model['detalle'],
    												'sistema' => $model['sistema'],
    												'modulo' => $model['modulo'],

    										];});

		return $data;
	}

	/**
	 * Función que valida que el nombre que se ingresa para un usuario no se repita.
	 * @return integer Retorna 1 si el nombre se encuentra en la BD. 0 de lo contrario.
	 */
	public function validarNombreUsuario()
	{
		if ($this->isNewRecord)
			$sql = "SELECT EXISTS (SELECT 1 FROM sam.sis_usuario WHERE UPPER(nombre) = '" . strtoupper($this->nombre) . "' OR UPPER(apenom) = '" . strtoupper($this->apenom) ."')";
		else
			$sql = "SELECT EXISTS (SELECT 1 FROM sam.sis_usuario WHERE usr_id <> " . $this->usr_id . " AND " .
					"(UPPER(nombre) = '" . strtoupper($this->nombre) . "' OR UPPER(apenom) = '" . strtoupper($this->apenom) . "'))";

		return Yii::$app->db->createCommand($sql)->queryScalar();
	}

	/**
	 * Función que se utiliza para validar los datos que se ingresarán en la BD
	 * al ingresar un nuevo usuario o al actualizar uno existente.
	 */
	public function validarGrabarUsuario()
	{
		$error = [];

		if ($this->nombre == '')
			$error[] = 'El nombre es obligatorio.';

		if ($this->apenom == '')
			$error[] = 'El Apellido y Nombre es obligatorio.';

		if ($this->grupo == 0)
			$error[] = 'El grupo es obligatorio';

		//Si se ingresa un nuevo usuario, se debe validar que el nombre no exista en la BD.

		if ($this->validarNombreUsuario() == 1)
			$error[] = 'El nombre de usuario ya existe.';

		return $error;
	}

	/**
	 * Función que se utiliza para insertar o actualizar un usuario en la BD.
	 */
	public function grabarUsuario()
	{
		//Se procede a validar los datos ingresados por el usuario
		$validar = $this->validarGrabarUsuario();

		$user = new User();

		if (count($validar) != 0)
		{
			$this->addErrors($validar);

			$arreglo = [
				'return' => 0,
				'mensaje' => '',
			];

			return $arreglo;
		}

		//Verificar si se trata de un nuevo usuario o de una actualización
		if ($this->isNewRecord) //Nuevo Usuario
		{
	        $sql = "Insert into sam.Sis_Usuario(Nombre,Clave,ApeNom,Domi,TDoc,NDoc,Oficina,Cargo,";
	        $sql .= "Legajo,Matricula,Grupo,Est,Tel,cel,Mail,Distrib,Inspec_Inm,Inspec_Comer,Inspec_OP,Inspec_Juz,";
	        $sql .= "Inspec_Recl,Abogado,Cajero,FchAlta,FchBaja,FchMod,UsrMod) Values ('";
	        $sql .= $this->nombre . "','".$user->hashPassword('')."','" . $this->apenom . "','" . $this->domi . "'," . $this->tdoc . ",";
	        $sql .= $this->ndoc . "," . $this->oficina . ",'" . $this->cargo . "'," . $this->legajo . ",";
	        $sql .= $this->matricula . "," . $this->grupo . ",'" . $this->est . "','" . $this->tel . "','" . $this->cel . "','";
	        $sql .= $this->mail . "'," . $this->distrib . "," . $this->inspec_inm . "," . $this->inspec_comer . "," . $this->inspec_op . "," . $this->inspec_juz . ",";
	        $sql .= $this->inspec_recl . "," . $this->abogado . "," . $this->cajero . ",";
	        $sql .= "current_timestamp,null,current_timestamp," . Yii::$app->user->id . ")";

		} else //Actualización de usuario
		{

            $sql = "Update sam.Sis_Usuario Set Nombre='" . $this->nombre . "',ApeNom='" . $this->apenom . "',Domi='" . $this->domi . "',TDoc=";
            $sql .= $this->tdoc . ",NDoc=" . $this->ndoc . ",Oficina=" . $this->oficina . ",Cargo='" . $this->cargo . "',Legajo=";
            $sql .= $this->legajo . ",Matricula=" . $this->matricula . ",Grupo=" . $this->grupo . ",Est='" . $this->est . "',Tel='";
            $sql .= $this->tel . "',cel='" . $this->cel . "',Mail='" . $this->mail . "',Distrib=" . $this->distrib . ",Inspec_Inm=";
            $sql .= $this->inspec_inm . ",Inspec_Comer=" . $this->inspec_comer . ",Inspec_OP=" . $this->inspec_op . ",Inspec_Juz=" . $this->inspec_juz;
            $sql .= ",Inspec_Recl=" . $this->inspec_recl . ",Abogado=" . $this->abogado . ",Cajero=" . $this->cajero;

            //Si est == 'B' se de setear la fecha actual como fecha de baja.
            //Si est == 'A' se debe setear la fecha actual como "null"
            if ($this->est == 'A')
            	$sql .= ",fchbaja=null";
            else
            	$sql .= ",fchbaja=current_timestamp";

            $sql .= ",FchMod=current_timestamp,UsrMod=" . Yii::$app->user->id . " Where Usr_Id=" . $this->usr_id;

		}

		$transaction = Yii::$app->db->beginTransaction();

		try
		{
			//Ejecuto la consulta generada
			Yii::$app->db->createCommand($sql)->execute();


			//Elimino los permiso que no conrresponde al grupo del usuario, cuando se ha modificado el grupo del mismo
			if (!$this->isNewRecord){
				$sql = "Delete from sam.sis_usuario_proceso up Where up.usr_id=" . $this->usr_id . " and up.pro_id not in (Select gp.pro_id ";
				$sql .= "From sam.sis_usuario u inner join sam.sis_grupo_proceso gp on up.usr_id=u.usr_id and gp.gru_id=u.grupo)";
				Yii::$app->db->createCommand($sql)->execute();
			}

			//Obengo el ID del último usuario ingresado
			$usr_id = Yii::$app->db->createCommand("SELECT usr_id FROM sam.sis_usuario WHERE nombre = '" . $this->nombre . "'")->queryScalar();

			//Grabo los datos referidos a "Tesorería"
			$res = $this->grabarTesoreria( $usr_id );

			//Grabo los datos referidos a "Tributo"
			$res = $this->grabarTributo($usr_id);

			$arreglo = [
				'return' => 1,
				'mensaje' => '',
			];

			$transaction->commit();

		} catch (\Exception $e)
		{
			$transaction->rollback();

			//Si se produce un error, se retorna un entero (0).
			$error[] = DBException::getMensaje($e);

			$this->addErrors($error);

			$arreglo = ['return' => 0,
						'mensaje' => DBException::getMensaje($e)];
		}

		return $arreglo;

	}

	/**
	 * Función que se encarga de grabar los datos de la tesorería.
	 */
	public function grabarTesoreria($usr_id)
	{
	   //Eliminar todas las tesorerías que tenga asignadas el usuario
        $sql = "Delete From sam.Sis_Usuario_Tesoreria Where Usr_Id= " . $usr_id;

        Yii::$app->db->createCommand($sql)->execute();

        //Insertar las tesorerías marcadas
        if (count($this->tesoreria) > 0)
        {
	        foreach ($this->tesoreria as $key=>$value)
	        {
	        	if ($value == 1)
	        	{
	        		$sql = "Insert into sam.Sis_Usuario_Tesoreria Values (" . $usr_id . "," . $key . ")";

	                Yii::$app->db->createCommand($sql)->execute();
	        	}
	        }
        }
	}

	/**
	* Función que se encarga de grabar los datos de los tributos
	*/
	public function grabarTributo($usr_id)
	{
		//Eliminar todas los tributos que tenga asignados el usuario
        $sql = "Delete From sam.Sis_Usuario_Trib Where Usr_Id= " . $usr_id;

        Yii::$app->db->createCommand($sql)->execute();

        //Insertar los tributos marcados
        if (count($this->tributo) > 0)
        {
	        foreach ($this->tributo as $key=>$value)
	        {
	        	if ($value == 1)
	        	{
	        		$sql = "Insert into sam.Sis_Usuario_Trib Values (" . $usr_id . "," . $key . ")";

	                Yii::$app->db->createCommand($sql)->execute();
	        	}
	        }
        }
	}

	/**
	 * Función que se encarga de grabar los permisos en la BD.
	 */
	public function grabarPermisos()
	{
        //Elimino todos los procesos del usuario
		$sql = "DELETE FROM sam.sis_usuario_proceso WHERE usr_id = " . $this->usr_id;
		Yii::$app->db->createCommand($sql)->execute();
				
		//Insertar los procesos
		if (count($this->permisos) > 0)
        {
	        foreach ($this->permisos as $key=>$value)
	        {
	        	//Ingreso en la BD el proceso asignado
	        	$sql = "Insert into sam.sis_usuario_proceso Values (" . $this->usr_id . "," . $value . ",CURRENT_TIMESTAMP," . Yii::$app->user->id . ")";
	            Yii::$app->db->createCommand($sql)->execute();
	        }
        }
	}

	/**
	 * Función que valida que el nombre que se ingresa para un grupo no se repita.
	 * @return integer Retorna 1 si el nombre se encuentra en la BD. 0 de lo contrario.
	 */
	public function validarNombreGrupo()
	{
		if ($this->isNewRecord)
			$sql = "SELECT EXISTS (SELECT 1 FROM sam.sis_grupo WHERE UPPER(nombre) = '" . strtoupper($this->gru_nombre) . "')";
		else
			$sql = "SELECT EXISTS (SELECT 1 FROM sam.sis_grupo WHERE gru_id <> " . $this->gru_id . " AND " .
					"UPPER(nombre) = '" . strtoupper($this->gru_nombre) . "')";

		return Yii::$app->db->createCommand($sql)->queryScalar();
	}

	/**
	 * Función que se utiliza para validar los datos que se insertarán de grupo
	 */
	public function validarGrabarGrupo()
	{
		$error = [];

		if ($this->gru_nombre == '')
			$error []= 'Debe ingresar un nombre de grupo.';

		if ($this->validarNombreGrupo() == 1)
			$error[] = 'El nombre de grupo ya existe.';

		return $error;
	}

	/**
	 * Función que se encarga de grabar los procesos asociados a los grupos en la BD.
	 */
	public function grabarProcesoGrupo()
	{
        //Insertar los procesos
        if ( count($this->gru_procesos) > 0 )
        {
	        foreach ( $this->gru_procesos as $key=>$value )
	        {
    			//Elimino el proceso asociados al grupo
		        $sql = "DELETE FROM sam.sis_grupo_proceso WHERE gru_id = " . $this->gru_id . " AND pro_id = " . $key;

		        Yii::$app->db->createCommand($sql)->execute();
	        	//Ingreso en la BD el proceso activo
	        	if ( $value == 1 )
	        	{
	        		$sql = "Insert into sam.sis_grupo_proceso Values (" . $this->gru_id . "," . $key . ",CURRENT_TIMESTAMP," . Yii::$app->user->id . ")";

	                Yii::$app->db->createCommand($sql)->execute();
	        	}
	        }
        }
	}

	/**
	 * Función que se utiliza para insertar o modificar los datos de un grupo.
	 */
	public function grabarGrupo()
	{
		//Se procede a validar los datos ingresados por el usuario
		$validar = $this->validarGrabarGrupo();

		if ( count($validar) != 0 )
		{
			$this->addErrors( $validar );

			$arreglo = [
				'return' => 0,
				'mensaje' => '',
			];

			return $arreglo;
		}

		//Verificar si se trata de un nuevo grupo o de una actualización
		if ($this->isNewRecord) //Nuevo Grupo
		{
	        $sql = "Insert into sam.Sis_Grupo (Nombre,usradm1,usradm2,FchMod,UsrMod) Values (UPPER('";
        	$sql .= $this->gru_nombre . "')," . $this->gru_admin1 . "," . $this->gru_admin2 . ",current_timestamp," . Yii::$app->user->id . ")";

		} else //Acualización de grupo
		{
            $sql = "Update sam.Sis_Grupo Set Nombre=UPPER('" . $this->gru_nombre . "'), UsrAdm1 = " . $this->gru_admin1 . ",UsrAdm2 = " . $this->gru_admin2;
            $sql .= ",FchMod=current_timestamp,UsrMod=" . Yii::$app->user->id . " Where Gru_Id='" . $this->gru_id . "'";
		}

		$transaction = Yii::$app->db->beginTransaction();

		try
		{
			//Ejecuto la consulta generada
			Yii::$app->db->createCommand( $sql )->execute();

			if (!$this->isNewRecord)
			{
				//Grabar los procesos asociados al grupo
				$this->grabarProcesoGrupo();

				//Cambia los permisos de usuario del grupo
				$sql = "Delete from sam.sis_usuario_proceso up Using sam.sis_usuario u Where up.usr_id=u.usr_id and u.grupo= " . $this->gru_id;
				$sql .= " and up.pro_id not in (Select gp.pro_id From sam.sis_grupo_proceso gp where gp.gru_id=" . $this->gru_id . ")";
			}

			$arreglo = [
				'return' => 1,
				'mensaje' => '',
			];

			$transaction->commit();

		} catch (\Exception $e)
		{
			$transaction->rollback();

			//Si se produce un error, se retorna un entero (0).
			$error[] = DBException::getMensaje($e);

			$this->addErrors( $error );

			$arreglo = ['return' => 0,
						'mensaje' => ''];
		}

		return $arreglo;
	}

	/**
	 * Función que valida los datos de grupo antes de eliminarlos
	 */
	public function validarEliminarGrupo()
	{
		$error = [];

		if ($this->existeUsuarioAsociado($this->gru_id) == 1)
			$error[] = 'El grupo tiene usuarios asignados.';

		return $error;
	}

	/**
	 * Función que se utiliza para eliminar un grupo de la BD.
	 */
	public function eliminarGrupo()
	{

		$validar = $this->validarEliminarGrupo();

		if (count($validar) != 0)
		{
			$this->addErrors($validar);

			$arreglo = [
				'return' => 0,
				'mensaje' => '',
			];

			return $arreglo;
		}

		$sql = "DELETE FROM sam.sis_grupo WHERE gru_id = " . $this->gru_id;

		try
		{
			Yii::$app->db->createCommand($sql)->execute();

			$arreglo = [
				'return' => 1,
				'mensaje' => '',
			];

		} catch (\Exception $e)
		{

			//Si se produce un error, se retorna un entero (0).
			$error[] = DBException::getMensaje($e);

			$this->addErrors($error);

			$arreglo = ['return' => 0,
						'mensaje' => DBException::getMensaje($e)];
		}

		return $arreglo;
	}

	/**
	 * Función que se utiliza para eliminar el último acceso de un usuario a la BD.
	 * @param integer $usr_id Identificador del usuario.
	 */
	public function limpiarUltimoAcceso($usr_id)
	{
		$sql = "UPDATE sam.Sis_Usuario_Acc SET fchsalida = current_timestamp, modo = 'M' Where Usr_Id = " . $usr_id . " and FchSalida is null";

		Yii::$app->db->createCommand($sql)->execute();
	}

	/**
	 * Función que se utiliza para obtener los accesos de un usuario al sistema.
	 * @param integer $usr_id Identificador del usuario.
	 * @param string $desde Fecha desde.
	 * @param string $hasta Fecha hasta.
	 * @return array Accesos
	 */
	public function getAccesos($usr_id,$desde,$hasta)
	{
		$sql = "select to_char(a.fchingreso,'dd/MM/yyyy HH24:MI:SS') as fchingreso,to_char(a.fchsalida,'dd/MM/yyyy HH24:MI:SS') as fchsalida,a.ip,m.nombre from sam.sis_usuario_acc a " .
				"JOIN sam.sis_usuario_tmodo m ON m.cod = a.modo " .
				"where a.usr_id = " . $usr_id . " AND a.fchingreso::date between '" . Fecha::usuarioToBD($desde) . "' and '" . Fecha::usuarioToBD($hasta) . "' " .
				" ORDER BY a.fchingreso DESC";

		return Yii::$app->db->createCommand($sql)->queryAll();
	}

	/**
	 * Función que se utiliza para obtener los accesos fallidos de un usuario al sistema.
	 * @param integer $usr_id Identificador del usuario.
	 * @param string $desde Fecha desde.
	 * @param string $hasta Fecha hasta.
	 * @return array Accesos fallidos.
	 */
	public function getAccesosFallidos($usr_id,$desde,$hasta)
	{
		$sql = "select to_char(fchintento,'dd/MM/yyyy HH24:MI:SS') as fchintento, ip from sam.sis_usuario_acc_err " .
				"where usr_id = " . $usr_id . " AND fchintento::date between '" . Fecha::usuarioToBD($desde) . "' and '" . Fecha::usuarioToBD($hasta) . "'" .
				" ORDER BY fchintento::date DESC";

		return Yii::$app->db->createCommand($sql)->queryAll();
	}

	/**
	 * Función que carga valores en las variables para el detalle de proceso.
	 * @param integet $id Id de proceso.
	 */
	public function cargarVariablesProceso($id)
	{
		$sql = "SELECT sis_id " .
				"FROM sam.sis_sistema " .
				"WHERE sis_id IN (" .
					"SELECT sis_id " .
					"FROM sam.sis_modulo " .
					"WHERE mod_id IN (" .
						"SELECT mod_id " .
						"FROM sam.sis_proceso " .
						"WHERE pro_id = " . $id . ")) ";

		$sistema_id = Yii::$app->db->createCommand($sql)->queryScalar();

		$data = $this->getProcesoSistema($sistema_id,0);

		$this->permiso_id = $data[$id]['pro_id'];
		$this->permiso_nombre = $data[$id]['nombre'];
		$this->permiso_detalle = $data[$id]['detalle'];
		$this->permiso_sistema_nombre = $data[$id]['sistema'];
		$this->permiso_modulo_nombre = $data[$id]['modulo'];
	}

	/**
	 * Función que obtiene los grupos que contienen al proceso.
	 * @param integer $proceso_id Id de proceso
	 * @return array Grupos
	 */
	public function getProceso_grupos($proceso_id)
	{
		$sql = "SELECT gru_id, nombre " .
				"FROM sam.sis_grupo " .
				"WHERE gru_id IN " .
					"(SELECT gru_id " .
					"FROM sam.sis_grupo_proceso " .
					"WHERE pro_id = " . $proceso_id . ") " .
				" ORDER BY nombre";

		return Yii::$app->db->createCommand($sql)->queryAll();
	}

	/**
	 * Función que obtiene los usuarios que disponen del proceso.
	 * @param integer $proceso_id Id de proceso
	 * @return array Usuarios
	 */
	public function getProceso_usuarios($proceso_id)
	{
		$sql = "SELECT usr_id, nombre, est " .
				"FROM sam.sis_usuario " .
				"WHERE usr_id IN " .
					"(SELECT usr_id " .
					"FROM sam.sis_usuario_proceso " .
					"WHERE pro_id = " . $proceso_id . ") " .
				" ORDER BY nombre";

		return Yii::$app->db->createCommand($sql)->queryAll();
	}

	/**
	 * Función que borra la asociación entre grupo y proceso
	 * @param integer $grupo_id
	 */
	public function eliminarProcesoDeGrupo($grupo_id,$permiso_id)
	{
		$sqlGrupo = "Delete from sam.sis_grupo_proceso where gru_id= " . $grupo_id . " and pro_id= " . $permiso_id;

		$sqlUsuario = "Delete from sam.sis_usuario_proceso where pro_id = " . $permiso_id . " and usr_id IN " .
				"(select usr_id from sam.sis_usuario where grupo = " . $grupo_id . ")";

		$transaction = Yii::$app->db->beginTransaction();

		try
		{
			Yii::$app->db->createCommand($sqlGrupo)->execute();
			Yii::$app->db->createCommand($sqlUsuario)->execute();

			$transaction->commit();

			$arreglo = ['return' => 1,
						'mensaje' => ''];

		} catch (\Exception $e)
		{
			$transaction->rollback();


			//Si se produce un error, se retorna un entero (0).
			$error[] = DBException::getMensaje($e);

			$this->addErrors($error);

			$arreglo = ['return' => 0,
						'mensaje' => DBException::getMensaje($e)];
		}

		return $arreglo;

	}

	/**
	 * Función que borra la asociación entre usuario y proceso
	 * @param integer $usuario_id
	 */
	public function eliminarProcesoDeUsuario($usuario_id,$permiso_id)
	{
		$sql = "Delete from sam.sis_usuario_proceso where usr_id= " . $usuario_id . " and pro_id= " . $permiso_id;

		$transaction = Yii::$app->db->beginTransaction();

		try
		{
			Yii::$app->db->createCommand($sql)->execute();

			$transaction->commit();

			$arreglo = ['return' => 1,
						'mensaje' => ''];

		} catch (\Exception $e)
		{
			$transaction->rollback();

			$arreglo = ['return' => 0,
						'mensaje' => DBException::getMensaje($e)];
		}

		return $arreglo;
	}

	/**
	 * Verificar si el Usuario tiene función de administrador en un grupo.
	 * @param integer $usr_id Id del usuario
	 * @return integer 1. Afirmativo - 0.Falso
	 */
	public function usrAdminGrupo( $usr_id )
	{
		$gru_id = 0;

		// Recupero el grupo del usuario
		$sql = "select grupo from sam.sis_usuario where usr_id=".$usr_id;

		$gru_id = Yii::$app->db->createCommand($sql)->queryScalar();

		if ( !isset($gru_id) || $gru_id == '' )
			$gru_id = 0;

		$sql = "SELECT EXISTS (Select 1 From sam.sis_grupo Where gru_id = ".$gru_id." and ( UsrAdm1 = ".Yii::$app->user->id." OR UsrAdm2 = ".Yii::$app->user->id.") )";

		return Yii::$app->db->createCommand($sql)->queryScalar();
	}

	/**
	 * Función que limpia la clave de un usuario.
	 * @param integer $usr_id Id del usuario
	 */
	public function claveLimpiar($usr_id)
	{
		$user = new User();

        try
        {
        	$sql = "Update sam.Sis_Usuario Set Clave='".$user->hashPassword('')."',fchmod=current_timestamp,usrmod=" . Yii::$app->user->id . " Where Usr_Id = " . $usr_id;
			Yii::$app->db->createCommand($sql)->execute();
			
			$sql = "insert into sam.sis_clave_blanqueo values( $usr_id, current_timestamp, " . Yii::$app->user->id . ")";
			Yii::$app->db->createCommand($sql)->execute();

        	return 1;
        } catch (\Exception $e)
        {
        	return 0;
        }

	}
}
