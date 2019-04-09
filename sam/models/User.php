<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "sam.sis_usuario".
 *
 * The followings are the available columns in table 'sam.sis_usuario':
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
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_ACTIVE = 'A';
    const STATUS_DELETED = 'B';

	const GRUPO_ADMIN = 1;
	
	public $apenom;
	public static $procesos = [];   // Array con los procesos del usuario
	public static $acciones = [];   // Array con las acciones del usuario

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sam.sis_usuario';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
			['nombre, clave, apenom, est, distrib, inspec_inm, inspec_comer, abogado, cajero, fchalta, fchmod', 'required'],
			['tdoc, oficina, legajo, matricula, grupo, distrib, inspec_inm, inspec_comer, inspec_op, inspec_juz, inspec_recl, abogado, cajero, usrmod', 'numerical', 'integerOnly'=>true],
			['nombre', 'length', 'max'=>10],
			['clave', 'length', 'max'=>50],
			['apenom, domi', 'length', 'max'=>40],
			['cargo', 'length', 'max'=>30],
			['est', 'length', 'max'=>1],
			['tel', 'length', 'max'=>20],
			['mail', 'length', 'max'=>50],
			['ndoc, fchbaja', 'safe'],
        ];
    }

	public static function grupoInArray($arr_grupo)
	{
		return in_array(Yii::$app->user->identity->grupo, $arr_grupo);
	}

	public static function isActive()
	{
		return Yii::$app->user->identity->status == self::STATUS_ACTIVE;
	}


   	public function esAdmin() {
        return $this->grupo == self::GRUPO_ADMIN;
    }


	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'usr_id' => 'Código',
			'nombre' => 'Nombre',
			'clave' => 'Clave',
			'apenom' => 'Apellido y Nombre',
			'domi' => 'Domicilio',
			'tdoc' => 'Tipo Documento',
			'ndoc' => 'Nro. Documento',
			'oficina' => 'Oficina',
			'cargo' => 'Detalle del Cargo',
			'legajo' => 'Legajo',
			'matricula' => 'Matrícula',
			'grupo' => 'Grupo al que pertenece',
			'est' => 'Estado',
			'tel' => 'Teléfono',
			'mail' => 'Correo eléctronico',
			'distrib' => 'Es Distribuidor',
			'inspec_inm' => 'Es Censista',
			'inspec_comer' => 'Es Inspector de Comercio',
			'inspec_op' => 'Es Inspector de Obras Part.',
			'inspec_juz' => 'Es Inspector Juzg.de Faltas',
			'inspec_recl' => 'Es Inspector de Reclamos',
			'abogado' => 'Es abogado',
			'cajero' => 'Es Cajero',
			'fchalta' => 'Fecha de Alta',
			'fchbaja' => 'Fecha de Baja',
			'fchmod' => 'Fecha de Modificación',
			'usrmod' => 'Usuario que modificó',
		);
	}


    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['usr_id' => $id, 'est' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Busqueda poor nombre de usuario
     *
     * @param string $nombre
     * @return static|null
     */
    public static function findByUsername($nombre)
    {
        return static::findOne(['nombre' => $nombre, 'est' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'est' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

	
/************************************************   METODOS PROPIOS   ************************************************************/	

    /**
	 * Cargar los procesos habilitados para el usuario
	 */
	public function loadProcesos($usr) {
		$sql = "select pro_id proceso from sam.sis_usuario_proceso ";
        $sql .= "Where usr_id = ".$usr." Order By pro_id";
		$per=Yii::$app->db->createCommand($sql)->queryColumn();
		Yii::$app->session['procesos'] = $per;
		//$this->procesos = $per;			
	}


    /**
	 * Cargar las acciones habilitadas para el usuario
	 */
	public function loadAcciones($usr) {
		$sql = "select distinct p.accion from sam.sis_usuario_proceso up inner join sam.sis_proceso_accion p on up.pro_id = p.pro_id ";
        $sql .= "Where up.usr_id = ".$usr." Order By p.accion"; 
		$acc=Yii::$app->db->createCommand($sql)->queryColumn();
		Yii::$app->session['acciones'] = ArrayHelper::toArray($acc);
		//$this->acciones = $acc;			
	}


	/**
	 * Funcion que verifica si el usuario tiene determinado permiso
	 * @param smallint $proceso Codigo del Proceso 
	 */
	public function existePermiso($proceso) {
		if (Yii::$app->user->isGuest) 
			return false;
		else
			return in_array($proceso, Yii::$app->session['permisos']);
			//return in_array($proceso, $this->getPermisos());
	}



/**************************************************   PASSWORD   ************************************************************/	

    /**
     * Validates password
     *
     * @param string $pass_usuario password to validate
     * @return boolean if password provided is valid for current user
     */
	public function validatePassword($pass_usuario) {
	 	return $this->hashPassword($pass_usuario)===$this->clave;
	}


	/**
	 * Encripta clave
	 * @param string $pass_usuario password a encriptar
	 */
	public function hashPassword($pass_usuario) {
		// Encriptacion con BCrypt
		//password_hash($this->clave, PASSWORD_BCRYPT)
	 	return md5($pass_usuario);
	}


	 /**
	 * Cambiar clave de usuario
	 */
	public function setNuevaPassword($usrnom, $new_password) {
		$sql = "Update sam.sis_usuario set clave = '".$this->hashPassword($new_password)."' Where nombre = '".$usrnom."'";
		return Yii::$app->db->createCommand($sql)->execute() > 0;
	}

	
    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
	
	
/************************************************   PROPIEDADES   ************************************************************/	
	
    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }


    /**
     * Devuelve array de permisos
     */
    public function getPermisos()
    {
        return $this->permisos;
    }




}