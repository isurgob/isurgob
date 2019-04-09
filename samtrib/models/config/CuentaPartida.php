<?php

namespace app\models\config;
use app\utils\helpers\DBException;

use Yii;

/**
 * This is the model class for table "cuenta_partida".
 *
 * @property integer $part_id
 * @property integer $grupo
 * @property integer $subgrupo
 * @property integer $rubro
 * @property integer $cuenta
 * @property integer $subcuenta
 * @property integer $conc
 * @property integer $subconc
 * @property string $formato
 * @property string $formatoaux
 * @property string $nombre
 * @property integer $padre
 * @property integer $nivel
 * @property integer $bcocta_id
 * @property string $est
 * @property string $fchmod
 * @property integer $usrmod
 *
 * @property CuentaPartidaGrupo $grupo0
 */

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
class CuentaPartida extends \yii\db\ActiveRecord
{

	/*
	 * Declaro las variables para el modelo de la tabla "cuenta"
	 */
	 public $cta_id;
	 public $cta_part_id;
	 public $cta_part_nom;
	 public $cta_nombre;
	 public $cta_nombre_redu;
	 public $cta_tcta;
	 public $cta_id_atras;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fin.part';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['grupo', 'subgrupo', 'rubro', 'cuenta', 'subcuenta', 'conc', 'subconc', 'padre', 'nivel', 'bcocta_id','part_id'], 'integer'],
            [['formato', 'formatoaux', 'nombre'], 'string', 'max' => 50],
            [['est'], 'string', 'max' => 1],


            //Reglas para el modelo de "cuenta"
            [['cta_id','cta_part_id','cta_tcta','cta_id_atras'],'number'],
            [['cta_nombre','cta_nombre_redu','cta_part_nom'],'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'part_id' => 'Identificador de cuenta',
            'grupo' => 'Grupo',
            'subgrupo' => 'Subgrupo',
            'rubro' => 'Rubro',
            'cuenta' => 'Cuenta',
            'subcuenta' => 'Subcuenta',
            'conc' => 'Concepto',
            'subconc' => 'Subconcepto',
            'formato' => 'Formato para identificacion',
            'formatoaux' => 'Formato auxiliar para identificacion',
            'nombre' => 'Nombre',
            'padre' => 'Codigo del padre',
            'nivel' => 'Nivel',
            'bcocta_id' => 'Cuenta bancaria asociada (0==>ninguna)',
            'est' => 'Estado',
            'fchmod' => 'Fecha de modificacion',
            'usrmod' => 'Codigo de usuario que modifico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupo0()
    {
        return $this->hasOne(CuentaPartidaGrupo::className(), ['cod' => 'grupo']);
    }

    /**
     * Función que obtiene las partidas presupuestarias según subgrupo y rubro ingresado.
     * @param integer $subgrupo Subgrupo.
     * @param integer $rubro Rubro.
     * @return array Arreglo con las partidas presupuestarias.
     */
    public function getPartidasPresupuestaria( $subgrupo = 0, $rubro = 0 )
    {
		$sql = "Select d.part_id,d.formato,d.nombre,d.nivel,d.bcocta_cta,d.modif," .
				"(SELECT EXISTS (SELECT 1 FROM cuenta c WHERE c.part_id = d.part_id)) as posee " .
				"from v_cuenta_partida d ";

		if ( $subgrupo != 0 )
		{
			$sql .= "where d.subgrupo = " . $subgrupo;

			if ( $rubro != 0 )
				$sql .= " and rubro = " . $rubro;
		}

		$sql .= " ORDER BY formato";

		$data = Yii::$app->db->createCommand($sql)->queryAll();

		return $data;
    }

    /**
     * Función que obtiene las cuentas para una partida presupuestada dada.
     * @param integer $part_id Id de la partida presupuestaria.
     * @return array Arreglo con las cuentas.
     */
    public function getCuentas($part_id)
    {
    	$sql = "SELECT cta_id,nombre,nombre_redu,tcta,tcta_nom,part_id,padre,cta_id_atras,cta_id_atras_nom,est " .
    			"FROM v_cuenta " .
    			"WHERE part_id = " . $part_id .
    			" ORDER BY cta_id";

    	$data = Yii::$app->db->createCommand($sql)->queryAll();

		return $data;
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
		$this->cta_part_id = $data[0]['part_id'];
		$this->cta_part_nom = $data[0]['part_nom'];
		$this->cta_nombre = $data[0]['nombre'];
		$this->cta_nombre_redu = $data[0]['nombre_redu'];
		$this->cta_tcta = $data[0]['tcta'];
		$this->cta_id_atras = $data[0]['cta_id_atras'];

    }

    /**
     * Función que se utiliza para validar que una partida no sea padre de otra
     * @param integer $part_id Identificador de la partida.
     * @return integer 1 => Es padre - 0 => No es padre
     */
    public function validarEstadoPadre($part_id)
    {
    	$sql = "SELECT EXISTS (SELECT 1 FROM fin.part WHERE padre = " . $part_id . ")";

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
		$this->cta_nombre_redu = trim($this->cta_nombre_redu);
    	$this->cta_nombre = trim($this->cta_nombre);
    	$this->cta_nombre_redu = strtoupper($this->cta_nombre_redu);
    	$this->cta_nombre = strtoupper($this->cta_nombre);

    	//Validaciones que se hacen sólo si es una nueva cuenta
    	if ( $newDato == 0 )
    	{
    		//Validar que la cuenta no tenga hijos
			$padre = $this->validarEstadoPadre( $this->cta_part_id );

			if ( $padre == 1 )
				$error[] = 'No se puede agregar la cuenta. La partida tiene hijos.';

//    		//Validar Nivel
//    		$sql = "Select Nivel from cuenta_partida where part_id = " . $this->cta_part_id;
//
//    		$nivel = Yii::$app->db->createCommand($sql)->queryScalar();
//
//			if ( $nivel > 6 )
//				$error[] = 'No se puede agregar cuentas en partidas de Último Nivel.';

			//Validar que no exista el nombre reducido

			$sql = "SELECT EXISTS (SELECT 1 FROM cuenta WHERE nombre_redu = '" . $this->cta_nombre_redu . "')";

			$cant = Yii::$app->db->createCommand($sql)->queryScalar();

			if ($cant == 1)
				$error[] = 'El nombre reducido ya existe.';
    	}

    	//Validaciones que se hacen sólo si es una modificación a una cuenta
    	if (  $newDato == 1 )
    	{
    		$sql = "SELECT EXISTS (SELECT 1 FROM cuenta WHERE cta_id != " . $this->cta_id . " AND nombre_redu = '" . $this->cta_nombre_redu . "')";

    		$cant = Yii::$app->db->createCommand($sql)->queryScalar();

			if ($cant == 1)
				$error[] = 'El nombre reducido ya existe.';

			if ($this->cta_tcta == 2 || $this->cta_tcta == 3)
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
    	if ($this->cta_nombre == '')
    		$error[] = 'Nombre incorrecto.';

    	if ($this->cta_nombre_redu == '')
    		$error[] = 'Nombre Reducido incorrecto.';

    	if ($this->cta_tcta < 0 || $this->cta_tcta > 4)
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
    		$sql = "Insert Into Cuenta Values (" . $this->cta_id . ",'" . $this->cta_nombre . "','" . $this->cta_nombre_redu . "'," . $this->cta_tcta . ",";
            $sql .= $this->cta_part_id . "," . $this->cta_id_atras . ",'A',current_timestamp," . Yii::$app->user->id . ")";

    	} else	//Si se trata de una cuenta existente
    	{
    		$sql = "Update Cuenta Set Nombre = '" . $this->cta_nombre . "',Nombre_Redu = '" . $this->cta_nombre_redu;
	        $sql .= "',TCta = " . $this->cta_tcta . ",Part_id= " . $this->cta_part_id;
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

    /**
     * Función que valida los datos de partida anes de insertarlos en la BD.
     * @param integer $newDato Identifica si se trata de una nueva partida o de una modificación de una existente.
     */
    public function validarPartida($newDato = 0)
    {
    	$error = [];

    	//Validaciones en el caso de que se trate de una nueva partida
    	if ( $newDato == 0 || $newDato == 1)
    	{
    		//Validar que no exista una partida con el mismo formato
    		$sql = "SELECT EXISTS (" .
    					"SELECT 1 " .
    					"FROM fin.part " .
    					"WHERE est = 'A' AND grupo = " . $this->grupo . " AND subgrupo = " . $this->subgrupo . " AND " .
    					"rubro = " . $this->rubro . " AND cuenta = " . $this->cuenta . " AND subcuenta = " . $this->subcuenta . " AND " .
    					"conc = " . $this->conc . " AND subconc = " . $this->subconc . ")";

    		$cant = Yii::$app->db->createCommand($sql)->queryScalar();

    		if ($cant == 1)
    		{
    			$error[] = 'La partida ya existe.';

    		}

    	}

    	//Validaciones en el caso de que se trate de una modificación a una partida
    	if ($newDato == 1)
    	{
    		//NULL
    	}

    	//Validaciones comunes
    	//Validar que nombre no sea vacío
    	if ( trim( $this->nombre ) == '' )
    		$error[] = 'Ingrese nombre.';

    	$this->addErrors( $error );
    }

    /**
     * Función que se utiliza para dar de alta o modificar una partida.
     */
    public function grabarPartida()
    {
    	$error = [];

    	//Corroborar si se trata de una nueva cuenta o si se actualiza una existente
    	$sql = "SELECT EXISTS (SELECT 1 FROM fin.part WHERE part_id = " . ( $this->part_id != 0 || $this->part_id != '' ? $this->part_id : 0 ) . ")";

    	/*
    	 * $newDato almacenará un integer que determinará si se trata de un nuevo dato o de una actualización.
    	 *
    	 * $newDato = 0 => Nuevo Cuenta
    	 * $newDato = 1 => Actualiza Cuenta
    	 */
    	$newDato = Yii::$app->db->createCommand( $sql )->queryScalar();

    	$this->validarPartida( $newDato );

    	if ( $this->hasErrors() )
    	{
    		return false;
    	}

    	//Genero el string sql
    	if ( $newDato == 0 )	//Si se trata de una nueva partida
    	{

    		//Obtengo el próximo número de cuenta
    		$cta_id = $this->getMaxNroPartida();

    		$sql = "INSERT INTO cuenta_partida VALUES (" . $cta_id . "," . $this->grupo . "," . $this->subgrupo . "," . $this->rubro . "," .
    				$this->cuenta . "," . $this->subcuenta . "," . $this->conc . "," . $this->subconc . ",null,null,'" .
    				$this->nombre . "'," . $this->padre . "," . $this->nivel . "," . $this->bcocta_id . "," .
    				"'A',current_timestamp," . Yii::$app->user->id . ")";

    	} else	//Si se trata de una cuenta existente
    	{
	        $sql = "UPDATE cuenta_partida SET " .
	        		"Grupo= " . $this->grupo . ",SubGrupo= " . $this->subgrupo . ",Rubro= " . $this->rubro . ",Cuenta= " . $this->cuenta .
			        ",SubCuenta= " . $this->subcuenta  . ",Conc=" . $this->conc . ",SubConc=" . $this->subconc . ",Nombre='" . $this->nombre .
			        "',bcocta_id=" . $this->bcocta_id  . ",FchMod=current_timestamp, UsrMod=" . Yii::$app->user->id .
			        " Where part_id=" . $this->part_id;
    	}

    	try
    	{
    		$res = Yii::$app->db->createCommand($sql)->execute();

    	} catch (\Exception $e)
    	{
    		$error[] = DBException::getMensaje($e);
    		$this->addErrors( $error );

    		return false;
    	}

    	return true;
    }

    /**
     * Función que se utiliza para realizar la baja física de una partida.
     * @param integer $part_id Identificador de la partida.
     */
    public function eliminarPartida( $part_id )
    {
    	$error = [];

    	//Validar que la partida no sea padre
    	$res = $this->validarEstadoPadre( $part_id );

    	if ( intval( $res ) == 1 )
    	{
    		$error[] = 'La partida seleccionada tiene hijos.';
    	}

    	if ( count( $error ) == 0 )
    	{
    		//Validar que la partida no tenga cuentas asociadas
	    	$sql = "SELECT EXISTS (SELECT 1 FROM cuenta WHERE est='A' and part_id = " . $part_id . ")";

	    	$res = Yii::$app->db->createCommand($sql)->queryScalar();

	    	if ($res == 1)
	    	{
		    	$error[] = 'La partida seleccionada tiene cuentas asociadas.';
	    	}
    	}

    	if ( count( $error ) == 0 )
    	{
    		//Eliminar la partida
	    	$sql = "DELETE FROM cuenta_partida WHERE part_id = " . $part_id;

	    	try
	    	{
	    		Yii::$app->db->createCommand($sql)->execute();

	    		return true;

	    	} catch (\Exception $e)
	    	{
	    		$error[] = 'La partida no se pudo eliminar.';
	    	}
    	}

    	$this->addErrors( $error );

    	return false;
    }

    /**
     * Función que se utiliza para obtener el próximo númeor de cuenta.
     */
    private function getMaxNroPartida()
    {
    	$sql = "SELECT nextval('seq_partida')";

    	return Yii::$app->db->createCommand($sql)->queryScalar();
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
}/*

SELECT concat(e.bco_ent,' - ', e.nombre,' - ',s.bco_suc,' - ',s.nombre,' (',substr(b.cbu,9,13),')')
FROM banco_cuenta b
INNER JOIN banco s ON substr(b.cbu,1,3)::smallint = s.bco_ent AND substr(b.cbu,5,3)::smallint = s.bco_suc
INNER JOIN banco_entidad e ON s.bco_ent = e.bco_ent
WHERE b.bcocta_id = 0
*/
