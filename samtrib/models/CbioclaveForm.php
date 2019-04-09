<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * CbioClaveForm es el modelo para el form de cambio de clave.
 */
class CbioclaveForm extends Model
{
    public $nombre;
    public $clave_old;
    public $clave_new;
	public $clave_newr;

    private $_user = false;

	
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username y las 3 password son requeridas
            [['clave_new', 'clave_newr'], 'required'],
            // password is validated by validatePassword()
            ['clave_old', 'validatePassword'],
            // password retry debe ser igual a password nueva, is validated by validatePasswordRetry()
            ['clave_newr', 'validatePasswordRetry'],
            // verificar largo minimo y otras restricciones
            ['clave_new', 'validatePasswordNew'],
        ];
    }


	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return [
		    'nombre' => 'Usuario',
            'clave_old' => 'Actual',	
			'clave_new' => 'Nueva',
			'clave_newr' => 'Repetir',
		];
	}


    /**
     * Valida el password anterior.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            
            if (!$user || !$user->validatePassword($this->clave_old)) {
                $this->addError($attribute, 'Incorrecto usuario o clave. ');
            }            
        }
    }


    /**
     * Valida que la nueva clave sea igual a su reiteracion.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePasswordRetry($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if ($this->clave_new != $this->clave_newr) {
                $this->addError($attribute, 'Repeticion de clave incorrecta. ');
            }
        }
    }

    /**
     * Valida que la nueva clave sea igual a su reiteracion.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePasswordNew($attribute, $params) {
		$pattern = '/(^(?=.*[a-z])(?=.*[A-Z])(?=.*\d){6,15}.+$)/';
		if (!$this->hasErrors()) {
		  if(!preg_match($pattern, $this->clave_new))
			  $this->addError($attribute, 'La nueva Clave tiene que tener entre 6 y 15 caracteres.
				- Debe contener al menos una letra mayuscula.
				- Debe contener al menos una letra minuscula.
				- Debe contener al menos un numero.');
		}
    }

	
    /**
     * Permite efectuar el cambio de clave del usuario.
     * @return string mensaje de error
     */
    public function cbioclave() {
		$this->nombre = Yii::$app->user->identity->nombre;
		$user = $this->getUser();
		if ($this->validate()) {
			$valido = $user->setNuevaPassword($this->nombre, $this->clave_new);
			if ($valido) {
				Yii::$app->session['user_sinclave'] = 0;
				if ($user->id > 0) {					
					$user->loadProcesos($user->id);
					$user->loadAcciones($user->id);
				}       				
			}
            return $valido;
        } else { 
            return '';
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
