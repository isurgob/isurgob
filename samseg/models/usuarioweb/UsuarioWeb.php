<?php

namespace app\models\usuarioweb;
use app\utils\helpers\DBException;
use app\models\User;
use app\utils\db\Fecha;
use app\utils\db\utb;
use yii\helpers\ArrayHelper;
use yii\validators\EmailValidator;

use Yii;

/**
 * This is the model class for table "sam.usuarioweb".
 *
 * @property integer $usr_id
 * @property string $nombre
 * @property string $clave
 * @property string $obj_id
 * @property string $acc_contrib
 * @property integer $acc_dj
 * @property string $acc_proveedor
 * @property integer $acc_agrete
 * @property string $est
 * @property string $fchmod
 * @property integer $usrmod
 *
 */
class UsuarioWeb extends \yii\db\ActiveRecord
{
	public $obj_nom;
	public $comer_asoc;

	public static function tableName()
    {
        return 'sam.usuarioweb';
    }

	public function rules()
	{
		return [
				['nombre','obj_id','mail','required'],
				[['usr_id'],'integer'],
				[['obj_id',],'string','min' => 8,'max'=>8],
				[['est','acc_contrib','acc_dj','acc_proveedor','acc_agrete','acc_escribano'],'string','min' => 1,'max'=>1],
				[['nombre','mail'],'string'],
				[['nombre'],'trim'],
			];
	}

	public function attributeLabels()
	{
		return[
				'usr_id' => 'Usuario',
				'obj_id' => 'Objeto',
				'nombre' => 'Nombre',
		];
	}

	public function Validar($consulta)
	{
		$error = "";

		if ($consulta != 2){
			if ($this->nombre == "") $error .= "<li>Ingrese un nombre de usuario</li>";
			if ($this->obj_id == "")
				$error .= "<li>Seleccione un Objeto</li>";
			else {
				if (utb::getNombObj($this->obj_id,false) == "") $error .= "<li>Seleccione un Objeto Válido</li>";
				if (utb::getTObj($this->obj_id) != 3) $error .= "<li>Tipo de Objeto Incorrecto</li>";
			}
			if ($consulta == 0){
				$cant = utb::getCampo('sam.usuarioweb',"obj_id='" . $this->obj_id . "' and est='A'","count(*)");
				if ($cant > 0) $error .= "<li>Ya existe un usuario para el objeto ingresado</li>";

				$cant = utb::getCampo('sam.usuarioweb',"upper(nombre)=upper('" . $this->nombre . "') and est='A'","count(*)");
				if ($cant > 0) $error .= "<li>Ya existe un usuario con ese nombre</li>";
			}
			if ($this->mail !== '')
			{
				$validator = new EmailValidator();

				if (!($validator->validate($this->mail, $err)))
				{
					$error .= "<li>".$err."</li>";
				}
			}

			if( $this->acc_agrete == 'S' ){	//Validar que el usuario disponga de un código de Ag. Retención

				$ag_rete = intVal( utb::getCampo( "persona", "obj_id = '$this->obj_id'", "ag_rete" ) );

				if( $ag_rete == 0 ){
					$error .= "<li>El usuario no está cargado como Agente de Retención.</li>";
				}
			}
			//if ($this->acc_dj == 'S' and count($this->comer_asoc) == 0) $error .= "<li>Ingrese comercios asociados</li>";
		}


		return $error;
	}

	/**
	 * Función que se utiliza para obtener el listado de usuarios.
	 * @param string $usr_nom Nombre de usuario web.
	 * @param integer $usr_doc Documento de usuario web.
	 * @param string $usr_apenom Nombre del usuario web.
	 * @param string $usr_obj Código de objeto ( persona ) asociado al usuario web.
	 */
	public static function getUsuariosWeb( $usr_nom = '', $usr_doc = '', $usr_apenom = '', $usr_obj = '', $baja = 0 ){

		$cond = '';

		if ($usr_nom != ''){
			if ($cond <> '') $cond .= ' and ';
			$cond .= "upper(u.nombre) like upper('%" . trim($usr_nom) ."%')";
		}

		if ($usr_doc != 0){
			if ($cond <> '') $cond .= ' and ';
			$cond .= 'p.ndoc=' . $usr_doc;
		}

		if ($usr_apenom != ''){
			if ($cond <> '') $cond .= ' and ';
			$cond .= "upper(o.nombre) like upper('%" . $usr_apenom ."%')";
		}
		if ($usr_obj != ''){
			if (strlen($usr_obj) < 8)
				$usr_obj = utb::getObjeto(3,$usr_obj);
			elseif (utb::getTObj($usr_obj) != 3)
				$usr_obj = '';

			if ($cond <> '') $cond .= ' and ';
			$cond .= "u.obj_id='" . $usr_obj ."'";
		}
		if ( $baja == 0 ){
			if ($cond <> '') $cond .= ' and ';
			$cond .= "u.est='A'";
		}

		$sql = "Select u.usr_id,u.nombre,o.nombre apenom,p.ndoc,u.obj_id,u.acc_contrib,acc_dj,acc_proveedor,acc_agrete,acc_escribano,u.est";
		$sql .= " From sam.usuarioweb u left join objeto o on u.obj_id=o.obj_id left join persona p on u.obj_id = p.obj_id";
		if ($cond != '') $sql .= " where " . $cond;
		$sql .= " order by o.nombre";

		Yii::$app->session['sql'] = $sql;
		Yii::$app->session['columns'] = [
			['attribute'=>'usr_id','label' => 'Cod.', 'contentOptions'=>['style'=>'text-align:center','width'=>'30px']],
			['attribute'=>'nombre','label' => 'Nombre', 'contentOptions'=>['style'=>'text-align:left','width'=>'70px']],
			['attribute'=>'apenom','label' => 'Apellido y Nombre', 'contentOptions'=>['style'=>'text-align:left','width'=>'150px']],
			['attribute'=>'ndoc','label' => 'Documento', 'contentOptions'=>['style'=>'text-align:left','width'=>'50px']],
			['attribute'=>'obj_id','label' => 'Objeto', 'contentOptions'=>['style'=>'text-align:left','width'=>'50px']],
			['attribute'=>'acc_contrib','label' => 'Acc.Cont', 'contentOptions'=>['style'=>'text-align:center;width:30px']],
			['attribute'=>'acc_dj','label' => 'Acc.DJ', 'contentOptions'=>['style'=>'text-align:center;width:30px']],
			['attribute'=>'acc_proveedor','label' => 'Acc.Prov', 'contentOptions'=>['style'=>'text-align:center;width:30px']],
			['attribute'=>'acc_agrete','label' => 'Acc.Ag', 'contentOptions'=>['style'=>'text-align:center;width:30px']],
			['attribute'=>'acc_escribano','label' => 'Acc.Escrib', 'contentOptions'=>['style'=>'text-align:center;width:30px']],
			['attribute'=>'est','label' => 'Est.', 'contentOptions'=>['style'=>'text-align:center;width:30px']],

		];

		return Yii::$app->db->createCommand( $sql )->queryAll();

	}

	/**
	 * Función que se utiliza para obtener los contribuyentes asociados al usuario (en caso que sea contador).
	 */
	public static function getContribuyentesAsociadosAUsuario( $id = 0 ){

		$sql = 	"SELECT obj_id, nombre, iva, iva_nom, cuit, ib, est_ib, est_ib_nom, tipoliq, tipoliq_nom " .
				"FROM v_persona WHERE contador <> 0 AND contador = $id";

		return Yii::$app->db->createCommand( $sql )->queryAll();

	}

	/**
	 * Función que se utiliza para realizar el ABM de contribuyentes en el arreglo en memoria.
	 */
	public static function ABMContribuyentes( $obj_id, $action, $arrayContribuyentes ){

		switch( $action ){

			case 0:

				$existe = 0; //Variable que indica si el objeto ya se encuentra ingresado

				//Verificar si no se encuentra ingresado el objeto
				if( count( $arrayContribuyentes ) > 0 ){

					foreach( $arrayContribuyentes as $array ){

						if( $array['obj_id'] == $obj_id ){
							$existe = 1;
						}
					}
				}

				if( !$existe ){

					$sql = 	"SELECT obj_id, nombre, iva, iva_nom, cuit, ib, est_ib, est_ib_nom, tipoliq, tipoliq_nom " .
							"FROM v_persona WHERE obj_id = '$obj_id'";

					$arrayContribuyentes[] = Yii::$app->db->createCommand( $sql )->queryOne();
				}

				break;

			case 2:

				if( count( $arrayContribuyentes ) > 0 ){

					$aEliminar = -1;

					foreach( $arrayContribuyentes as $id => $array ){

						if( $array['obj_id'] == $obj_id ){
							$aEliminar = $id;
						}
					}

					if( $aEliminar > -1 ){
						unset( $arrayContribuyentes[ $aEliminar ] );
					}
				}
		}

		return $arrayContribuyentes;
	}

	public static function cargarObjeto( &$obj_id, &$obj_nom ){

		$obj_id = utb::getObjeto( 3, $obj_id );

		if( utb::verificarExistenciaObjeto( 3, "'" . $obj_id . "'" ) ){

			$obj_nom = utb::getCampo( 'objeto', "obj_id = '" . $obj_id ."'", "nombre" );
		} else {

			$obj_id 	= '';
			$obj_nom	= '';
		}

	}

	/**
	 * Función que se utiliza para grabar un usuario.
	 */
	public function grabarUsuario( $consulta, $arrayContribuyentes )
	{
		$error = "";
		$sql = "";
		$user = new User();

		$clave = utb::getClaveAleatoria();
		Yii::$app->session->setFlash('clave', $clave);

		switch( $consulta ){

			case 0:	//Agregar

				$sql = "insert into sam.usuarioweb(nombre,clave,obj_id,acc_contrib,acc_dj,acc_proveedor,acc_agrete,acc_escribano,mail,est,fchmod,usrmod)";
				$sql .= " values ('" . $this->nombre . "','" . $user->hashPassword($clave) . "','" . $this->obj_id . "','" . $this->acc_contrib . "','" . $this->acc_dj . "','";
				$sql .= $this->acc_proveedor . "','" . $this->acc_agrete . "','" . $this->acc_escribano . "','" . $this->mail . "','A',current_timestamp,".Yii::$app->user->id.")";

				break;

			case 2:	//Eliminar

				$sql = "update sam.usuarioweb set est='B' where usr_id=".$this->usr_id;

				break;

			case 3:	//Modificar

				$sql = "update sam.usuarioweb set acc_contrib = '$this->acc_contrib', acc_dj = '$this->acc_dj', " .
						"acc_proveedor = '$this->acc_proveedor', acc_agrete = '$this->acc_agrete', acc_escribano = '$this->acc_escribano', mail = '$this->mail'," .
						"fchmod=current_timestamp,usrmod=".Yii::$app->user->id . " where usr_id = $this->usr_id";

				break;

			case 4:	//Activar

				$sql = "update sam.usuarioweb set est='A' where usr_id = ".$this->usr_id;

				break;
		}

		$transaction = Yii::$app->db->beginTransaction();

		try
		{
			//Ejecuto la consulta generada
			Yii::$app->db->createCommand($sql)->execute();

			//Obengo el ID del último usuario ingresado
			if ($consulta == 0) //Si es nuevo obtengo el id nuevo ingresado
				$usr_id = Yii::$app->db->createCommand("SELECT usr_id FROM sam.usuarioweb WHERE obj_id = '" . $this->obj_id . "'")->queryScalar();
			else
				$usr_id = $this->usr_id;

			$this->grabarContribuyentesAsociados( $usr_id, $this->acc_dj == 'S', $arrayContribuyentes );

			$transaction->commit();

		} catch (\Exception $e)
		{
			$transaction->rollback();
			$error = DBException::getMensaje($e);
		}

		return $error;
	}

	/**
	 * Función que se utiliza para grabar los contribuyentes asociados al usuario web.
	 * @param integer $user_id Identiifcador de usuario web.
	 * @param boolean $agregaContribuyentes Indica si se graban los contribuyentes.
	 * @param array $arrayContribuyentes
	 */
	public function grabarContribuyentesAsociados( $user_id = 0, $agregaContribuyentes, $arrayContribuyentes )
	{

		$transaction = Yii::$app->db->beginTransaction();

		try{

			$sql = 	"UPDATE persona SET contador = 0 WHERE contador <> 0 AND contador = $user_id";

			Yii::$app->db->createCommand( $sql )->execute();

			if( $agregaContribuyentes && count( $arrayContribuyentes ) > 0 ){

				foreach( $arrayContribuyentes as $array ){

					$sql = 	"UPDATE persona SET contador = $user_id WHERE obj_id = '" . $array["obj_id"] . "'";

					Yii::$app->db->createCommand( $sql )->execute();

				}
			}

		} catch( \Exception $e ){

			$transaction->rollback();
		}

		$transaction->commit();

	}

	public function claveLimpiar($usr_id)
	{
		$user = new User();

		$clave = utb::getClaveAleatoria();
		Yii::$app->session->setFlash('clave', $clave);

        $sql = "Update sam.usuarioweb Set Clave='".$user->hashPassword($clave)."' Where Usr_Id = " . $usr_id;

        try
        {
        	Yii::$app->db->createCommand($sql)->execute();

        } catch (\Exception $e)
        {
        	return false;
        }

		return true;

	}

	public function Texto($usr)
	{
		$texto_id = Yii :: $app->db->createCommand("select texto_id from texto where tuso=17")->queryScalar();

		$sql = "Select * From sam.uf_texto_usuarioweb(" . $usr . "," . ($texto_id == '' ? 0 : $texto_id) . ")";

		try
        {
        	$texto = Yii :: $app->db->createCommand($sql)->queryAll();
        } catch (\Exception $e)
        {
        	return null;
        }

		return $texto;
	}

}
