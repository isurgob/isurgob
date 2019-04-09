<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\utils\db\utb;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
    public $nombre;
    public $clave;
    public $rememberMe = false;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['nombre'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
			// verificar que el usuario exista
			['nombre', 'validateNombre'],
            // password is validated by validatePassword()
            ['clave', 'validatePassword'],
        ];
    }


	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return [
		    'nombre' => 'Usuario',
            'clave' => 'Clave:',
			'rememberMe'=>'Recordar mis datos',
		];
	}


    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params) {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->clave)) {
                $this->addError($attribute, 'Incorrecto usuario o clave. ');
            }
        }
    }

    /**
     * Validates the nombre.
     * Verificar si el nombre de Usuario ingresado existe en la BD
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateNombre($attribute, $params) {
        if (!$this->hasErrors()) {
			$usr_id = utb::getCampo('sam.sis_usuario',"nombre='".$this->nombre."'",'usr_id');
            if (is_null($usr_id) || $usr_id == 0) {
                $this->addError($attribute, 'Usuario Inexistente.');
            }
        }
    }


    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login() {

        if (!$this->ValidaSesion())
        {
			$this->addError('sesion', 'No se permite habilitar varias sesiones. Cualquier inconveniente consulte al Administrador del Sistema.');
			return false;
        }

        if ($this->validate())
        {
			$user = $this->getUser();
			$val = Yii::$app->user->login($user, $this->rememberMe ? 3600*24*30 : 0);

			// Verificar si la clave esta vacía
			if ($user->clave === 'd41d8cd98f00b204e9800998ecf8427e')
			{
				//$this->addError($attribute, 'Clave vacia. ');
				Yii::$app->session['user_sinclave'] = 1;

			} else if ( trim( $this->clave ) === "" )
			{
				$this->getGrabarAcceError($user->id);
				Yii::$app->user->logout();
				return false;

			} else
			{
				Yii::$app->session['user_sinclave'] = 0;
			}

			$this->getGrabarAcce( $user->id );

			// cargo los datos del usuario
			if ($user->id > 0 && Yii::$app->session['user_sinclave'] == 0) {
				$user->loadProcesos($user->id);
				$user->loadAcciones($user->id);
			}

			return $val;
        } else {
            $usr_id = utb::getCampo('sam.sis_usuario',"nombre='".$this->nombre."'",'usr_id');
			$this->getGrabarAcceError($usr_id);
			return false;
        }
    }


    /**
     * Validar que no tenga sesiones abiertas
     * */
     public function ValidaSesion()
     {
     	$usr_id = utb::getCampo('sam.sis_usuario',"nombre='".$this->nombre."'",'usr_id');

        /*
         * Verificar que existan sesiones abiertas para el usuario actual.
         */

        $sql = "SELECT EXISTS( SELECT 1 FROM sam.sis_usuario_acc WHERE " .
        		"fchsalida is null AND usr_id = " . ( $usr_id=='' ? 0 : $usr_id ) . ")";

        $cant = Yii::$app->db->createCommand( $sql )->queryScalar();

        if ( $cant == 1 )	// Existen sesiones abiertas
        {
        	/*
	         * Corroborar si la sesión abierta contiene la misma IP desde la cual se intenta acceder.
	         */

	        $sql = "SELECT ip FROM sam.sis_usuario_acc WHERE " .
	        		"fchsalida is null AND usr_id = " . ( $usr_id=='' ? 0 : $usr_id );

	        $ip = Yii::$app->db->createCommand( $sql )->queryScalar();

	        if ( $ip == Yii::$app->getRequest()->getUserIP() )
	        {
	        	//La sesión abierta tiene la misma IP desde la cual se intenta acceder.

	        	$sql = "Update sam.sis_usuario_acc Set FchSalida = current_timestamp, modo = 'A' Where usr_id = " . $usr_id . " and FchSalida is null";

        		Yii :: $app->db->createCommand($sql)->execute();

	        } else	//Si la sesión abierta tiene distinta IP
	        {
	        	/*
	        	 * Corroborar que la fecha de ingreso sea menor a la fecha actual
	        	 */

	        	$sql = "SELECT EXISTS ( SELECT 1 FROM sam.sis_usuario_acc WHERE " .
	        			"fchsalida is null AND usr_id = " . ( $usr_id=='' ? 0 : $usr_id ) .
	        			" AND fchingreso::date < current_date )";

	        	$cant = Yii::$app->db->createCommand( $sql )->queryScalar();

	        	if ( $cant == 1 )
	        	{
	        		$sql = "Update sam.sis_usuario_acc Set FchSalida = current_timestamp, modo = 'V' Where usr_id = " . $usr_id . " and FchSalida is null";

        			Yii :: $app->db->createCommand($sql)->execute();

	        	} else
	        	{
	        		/*
	        		 * Si la IP desde la cual se intenta acceder al sistema es distinta a la IP con la cual se
	        		 * mantiene la sesión abierta, y el día es el mismo, esto indica que se está intentando acceder
	        		 * al sistema desde dos equipos diferentes, por lo tanto el login falla
	        		 */
	        		return false;
	        	}
	        }
        }

        return true;
     }

    /**
     * Grabo acceso del usuario logueado
     * */
     public function getGrabarAcce($id){
    	$sql = "Insert Into sam.sis_usuario_acc (usr_id, FchIngreso, IP) Values (";
    	$sql .= $id . ", current_timestamp, '" . Yii::$app->getRequest()->getUserIP() . "')";
    	$cmd = Yii :: $app->db->createCommand($sql);
		$rowCount = $cmd->execute();
     }

	 /**
     * Grabo acceso del usuario logueado
     * */
     public function getGrabarAcceError($id){
	    if ((int)$id != 0) {
			$sql = "Insert Into sam.sis_usuario_acc_err (usr_id, FchIntento, IP) Values (";
			$sql .= $id . ", current_timestamp, '" . Yii::$app->getRequest()->getUserIP() . "')";
			$cmd = Yii :: $app->db->createCommand($sql);
			$rowCount = $cmd->execute();
		}
     }

     /**
      * Grabo la salida del usuario logueado
      * */
      public function getGrabarSalida(){
	    if (isset(Yii::$app->user->id)) {
			$sql = "Update sam.sis_usuario_acc Set FchSalida = current_timestamp, modo = 'N' ";
			$sql .= "Where usr_id = " . Yii::$app->user->id . " and FchSalida is null and IP='" . Yii::$app->getRequest()->getUserIP() . "'";
			$cmd = Yii :: $app->db->createCommand($sql);
			$rowCount = $cmd->execute();
		}
      }


    /**
     * Busqueda por nombre de usuario
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->nombre);
        }

        return $this->_user;
    }

}
