<?php

namespace app\models\config;
use app\utils\helpers\DBException;

use Yii;

 /**
  * This is the model class for table "cuenta"
  *
  * @property integer $cta_id
  * @property integer $cta_part_id
  * @property string $cta_part_nom
  * @property string $cta_nombre
  * @property string $cta_nombre_redu
  * @property integer $cta_tcta
  * @property integer $cta_id_atras
  *
  */
class Cuenta extends \yii\db\ActiveRecord
{

	public $part_nom;


	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cuenta';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //Reglas para el modelo de "cuenta"
            [['cta_id','part_id','tcta','cta_id_atras'],'number'],
            [['nombre','nombre_redu','part_nom'],'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [

        ];
    }

    /**
     * Función que se encarga de cargar los datos de una cuenta.
     * @param integer $cuenta_id Identificador de la cuenta.
     */
    public function cargarCuenta($cuenta_id)
    {
    	$sql = "SELECT cta_id,nombre,nombre_redu,tcta,part_id,part_nom,cta_id_atras,est " .
    			"FROM v_cuenta " .
    			"WHERE cta_id = " . $cuenta_id;

    	$data = Yii::$app->db->createCommand($sql)->queryAll();

	 	$this->cta_id = $data[0]['cta_id'];
		$this->part_id = $data[0]['part_id'];
		$this->part_nom = $data[0]['part_nom'];
		$this->nombre = $data[0]['nombre'];
		$this->nombre_redu = $data[0]['nombre_redu'];
		$this->tcta = $data[0]['tcta'];
		$this->cta_id_atras = $data[0]['cta_id_atras'];

    }

	/**
     * Función que se utiliza para validar que una partida no sea padre de otra
     * @param integer $part_id Identificador de la partida.
     * @return integer 1 => Es padre - 0 => No es padre
     */
    public function validarEstadoPadre($part_id)
    {
    	$sql = "SELECT EXISTS (SELECT 1 FROM fin.part WHERE anio=extract(year from current_date) and padre = " . $part_id . ")";

		return Yii::$app->db->createCommand($sql)->queryScalar();
    }

    /**
     * Función que se utiliza para validar que los datos que se ingresarán en la BD
     * de cuenta son correctos.
     * @param integer $newDato Variable que indica si se trata de una nueva cuenta o de una cuenta existente
     */
    function validarCuenta( $newDato = 0 )
    {
    	$error = [];	//Variable que almacenará los errores

    	//Elimino espacios en blanco
		$this->nombre_redu = trim($this->nombre_redu);
    	$this->nombre = trim($this->nombre);
    	$this->nombre_redu = strtoupper($this->nombre_redu);
    	$this->nombre = strtoupper($this->nombre);

    	//Validaciones que se hacen sólo si es una nueva cuenta
    	if ( $newDato == 0 )
    	{
    		//Validar que la cuenta no tenga hijos
			$padre = $this->validarEstadoPadre( $this->part_id );

			if ( $padre == 1 )
				$error[] = 'No se puede agregar la cuenta. La partida tiene hijos.';


			//Validar que no exista el nombre reducido

			$sql = "SELECT EXISTS (SELECT 1 FROM cuenta WHERE est='A' and nombre_redu = '" . $this->nombre_redu . "')";

			$cant = Yii::$app->db->createCommand($sql)->queryScalar();

			if ($cant == 1)
				$error[] = 'El nombre reducido ya existe.';
    	}

    	//Validaciones que se hacen sólo si es una modificación a una cuenta
    	if (  $newDato == 1 )
    	{
    		$sql = "SELECT EXISTS (SELECT 1 FROM cuenta WHERE cta_id != " . $this->cta_id . " AND nombre_redu = '" . $this->nombre_redu . "')";

    		$cant = Yii::$app->db->createCommand($sql)->queryScalar();

			if ($cant == 1)
				$error[] = 'El nombre reducido ya existe.';

			if ($this->tcta == 2 || $this->tcta == 3)
			{
				//Verifico que la cuenta no posea Items Asociados si es de Bonificacion o Recargo
				$sql = "Select count(*) From Item Where cta_id=" . $this->cta_id;

				$cant = Yii::$app->db->createCommand($sql)->queryScalar();

				if ($cant > 0)
					$error[] = 'Cuentas de Bonificación o Recargo no pueden tener Items Asociados.';
			}
    	}

    	//Validaciones que son comunes para nueva cuenta como para modificar cuenta

    	//Validar que el nombre sea correcto
    	if ($this->nombre == '')
    		$error[] = 'Nombre incorrecto.';

    	if ($this->nombre_redu == '')
    		$error[] = 'Nombre Reducido incorrecto.';

    	if ($this->tcta < 0 || $this->tcta > 4)
    		$error[] = 'Tipo de Cuenta incorrecto';

    	if ( count( $error ) > 0 )
	    	$arreglo = [
	    		'return' => 0,
	    	];
	    else
	    	$arreglo = [
	    		'return' => 1,
	    	];

	    $this->addErrors( $error );
	    return $arreglo;
    }

    /**
     * Función que se ejecuta para grabar o actualizar los datos de una cuenta.
     */
    public function grabarCuenta()
    {
    	$error = [];

    	$transaction = Yii::$app->db->beginTransaction();

    	if ( $this->cta_id == '' )
    		$this->cta_id = Yii::$app->db->createCommand("SELECT nextval('public.seq_cuenta')")->queryScalar();

    	if ( $this->cta_id_atras == 0 )
    		$this->cta_id_atras = $this->cta_id;

    	//Corroborar si se trata de una nueva cuenta o si se actualiza una existente
    	$sql = "SELECT EXISTS (SELECT 1 FROM cuenta WHERE cta_id = " . $this->cta_id . ")";

    	/*
    	 * $newDato almacenará un integer que determinará si se trata de un nuevo dato o de una actualización.
    	 *
    	 * $newDato = 0 => Nueva Cuenta
    	 * $newDato = 1 => Actualiza Cuenta
    	 */
    	$newDato = Yii::$app->db->createCommand( $sql )->queryScalar();

    	$this->validarCuenta( $newDato );

    	if ( $this->hasErrors() )
    	{
    		$transaction->rollback();
    		return false;
    	}

    	//Genero el string sql
    	if ( $newDato == 0 )	//Si se trata de una nueva cuenta
    	{
    		$sql = "Insert Into Cuenta Values (" . $this->cta_id . ",'" . $this->nombre . "','" . $this->nombre_redu . "'," . $this->tcta . ",";
            $sql .= $this->part_id . "," . $this->cta_id_atras . ",'A',current_timestamp," . Yii::$app->user->id . ")";

    	} else	//Si se trata de una cuenta existente
    	{
    		$sql = "Update Cuenta Set Nombre = '" . $this->nombre . "',Nombre_Redu = '" . $this->nombre_redu;
	        $sql .= "',TCta = " . $this->tcta . ",Part_id= " . $this->part_id;
	        $sql .= ",Cta_Id_Atras =" . $this->cta_id_atras . ", FchMod=current_timestamp, UsrMod=" . Yii::$app->user->id . " Where Cta_Id = " . $this->cta_id;
    	}

    	try
    	{
    		$res = Yii::$app->db->createCommand($sql)->execute();

    	} catch (\Exception $e)
    	{

            $transaction->rollback();

    		$error[] = DBException::getMensaje( $e );

            $this->addErrors( $error );

            return false;
    	}

    	$transaction->commit();

    	return true;

    }

    /**
     * Función que se utiliza para relizar la baja lógica de una cuenta.
     * @param integer $id identificador de la cuenta.
     */
    public function eliminarCuenta($id)
    {
    	$error = [];

    	$sql = "SELECT EXISTS (" .
    				"SELECT 1 " .
    				"FROM plan_config " .
    				"WHERE cta_id=" . $id . " or cta_id_rec=" . $id . " or cta_id_sellado=" . $id . " or cta_id_multa=" . $id . ")";

    	$res = Yii::$app->db->createCommand($sql)->queryScalar();

    	if ( $res == 0 )
    	{
    		try
    		{
    			$sql = "Update Cuenta Set Est='B' Where Cta_Id=" . $id;

	    		Yii::$app->db->createCommand($sql)->execute();

	    		return true;

    		} catch (\Exception $e)
    		{
    			$error[] = 'No se pudo eliminar.';
    		}

    	} else
    	{
    		$error[] = 'La cuenta está siendo utilizada en convenios. No podrá eliminar.';
    	}

    	$this->addErrors( $error );

    	return false;
    }

    //////////////////////////////////CUENTAS/////////////////////////////

    /**
     * Función que obtiene las cuentas
     * @param string $nombre Nombre de la cuenta
     */
    public function getCuentasIngreso( $nombre = '' )
    {

        $sql = "SELECT formato_aux,part_id,part_nom,cta_id,nombre,tcta,tcta_nom,est " .
                "FROM v_cuenta ";

    	if ( $nombre != '' )
    	{
    		$sql .= "WHERE upper(nombre) LIKE '%" . strtoupper($nombre) . "%' ";
        }

        $sql .= "ORDER BY cta_id";

        return Yii::$app->db->createCommand($sql)->queryAll();

    }

    /**
     * Función que se utiliza para obtener los datos del banco al cual pertenece una cuenta bancaria.
     * @param integer $bcocta_id Identificador de la cuenta.
     * @return string Retorna un string con los datos del banco.
     */
    public function obtenerDatosCuentaBancaria($bcocta_id)
    {
    	$sql = "SELECT concat(e.bco_ent,' - ', e.nombre,' - ',s.bco_suc,' - ',s.nombre,' (',substr(b.cbu,9,13),')') " .
                "FROM banco_cuenta b " .
                "INNER JOIN banco s ON substr(b.cbu,1,3)::smallint = s.bco_ent AND substr(b.cbu,4,4)::smallint = s.bco_suc " .
                "INNER JOIN banco_entidad e ON s.bco_ent = e.bco_ent " .
                "WHERE b.bcocta_id = " . $bcocta_id;

    	return Yii::$app->db->createCommand($sql)->queryScalar();
    }
}
