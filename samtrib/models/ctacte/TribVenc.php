<?php

namespace app\models\ctacte;
use yii\helpers\ArrayHelper;
use yii\data\SqlDataProvider;
use app\utils\helpers;
use app\utils\db\Fecha;

use Yii;

/**
 * This is the model class for table "trib_venc".
 *
 * @property integer $trib_id
 * @property integer $anio
 * @property integer $cuota
 * @property string $fchvenc1
 * @property string $fchvenc2
 * @property string $fchvencanual
 * @property integer $segun_term
 * @property string $fchmod
 * @property integer $usrmod
 * @property integer $actualizactacte
 */
class TribVenc extends \yii\db\ActiveRecord
{
	public $habilitarpagoanual;
	public $fchvenc1seteada;
	public $fchvenc2seteada;

	public $actualizactacte; //Variable que determianrá si se modifican las fechas de vencimiento de las ctas ctes

	//Variable que almacenará los errores
	private $error = "";

	private $vencanual;	//Variable que almacenará la fecha del vencimiento anual

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'trib_venc';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['trib_id','anio', 'cuota', 'fchvenc1'], 'required'],
            [['trib_id', 'anio', 'cuota', 'segun_term', 'actualizactacte', 'habilitarpagoanual'], 'integer'],
            [['anio'],'integer','min' => 4],
            [['fchvenc1', 'fchvenc2', 'fchvencanual'], 'safe'],
            [['fchvenc1', 'fchvenc2', 'fchvencanual'], 'date', 'format' => 'DD/MM/YYYY']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'trib_id' => 'Código de tributo',
            'anio' => 'Año',
            'cuota' => 'Cuota',
            'fchvenc1' => 'Vencimiento 1',
            'fchvenc2' => 'Vencimiento 2',
            'fchvencanual' => 'Venc anual',
            'segun_term' => 'Si el vencimiento es según la terminación de cuit',
            'fchmod' => 'Fecha de modificación',
            'usrmod' => 'Código de usuario que modificó',
            'habilitarpagoanual' => 'Habilitar pago anual',
            'actualizactacte' => 'Actualizar vencimiento para tributo y período en la cta cte',
        ];
    }

    /**
     * Función que se encarga de limpiar el valor de la variable de error
     */
    private function resetError()
    {
    	$this->error = "";
    }

    /**
     * Función que agrega un string al error actual
     * @param string $error string que se agregará al error
     */
    private function setError($error)
    {
    	$this->error = $this->error . $error;
    }

    /**
     * Función que retorna el valor de la variable error
     * @return string $this->error
     */
    public function getError()
    {
    	return $this->error;
    }


    /**
     * Función que se encarga de validar los datos que se ingresarán a la BD
     */
    public function validar()
    {
		$error = [];

    	if ( $this->trib_id == 0 || $this->trib_id == '' || $this->trib_id == null )
    	{

    		$error[] = 'Ingrese un tributo.';

    	}

    	//Inicializo variables
    	$tipoTributo = 0;
    	$fechaVencimiento = ""; //Variable que almacenará la fecha de vencimiento
    	$maxcuota = 0;	//Almacenará la forma de pago del tributo

    	//Inicio validar año
    	$añoActual = $this->anio; //date('Y');

    	if ($this->anio > ($añoActual + 1) || $this->anio < ($añoActual - 1))
    	{
    		$error[] = "Año fuera de rango.";
    		$this->anio = '';
    	}
    	//Fin validar año

    	//Inicio validar cuota
    	if ($this->cuota > 12 || $this->cuota < 1)
    	{
			if($this->cuota <61 || $this->cuota > 64){
				$error[] = "Cuota fuera de rango.";
				$this->cuota = '';
			}
    	}
    	//Fin validar cuota

		//Si el año o las cuotas están fuera de rango, no evaluo lo siguiente
		if (count($error) == 0)
		{

	        $sql = "SELECT Tipo FROM Trib WHERE trib_id=" . $this->trib_id;
	        $tipoTributo = Yii :: $app->db->createCommand($sql)->queryScalar();

	        $maxcuota = $this->obtenerFormaPagoTributo();	//Obtengo la forma de pago del Tributo

			if ( $this->cuota > $maxcuota  and $this->cuota < 60)
				$error[] = "La cuota ingresada es mayor a la cantidad establecida en la resolución.";

			if ($this->cuota > 60){

				$cuo_fin = ($this->cuota - 60) * 3;

				//Obtener la fecha de vencimiento
				$sql = "SELECT to_char(fchvenc1,'dd/MM/yyyy') as vencimiento FROM trib_venc where trib_id = " . $this->trib_id . " and anio = " . $this->anio . " and cuota = " . $cuo_fin;

				$fechaVencimiento =  Yii :: $app->db->createCommand($sql)->queryScalar();

				if ($fechaVencimiento != ''){
					//Valido las fechas
					if ( !Fecha::menor(Fecha::usuarioToBD($this->fchvenc1), Fecha::usuarioToBD($fechaVencimiento)) )
						$error[] = "La fecha de vencimiento no es correcta.";
				}

				 if ($this->fchvenc2 != ""){	//En caso de que la fecha de 2do vencimiento sea vacía

					//Valido que las fechas sean correctas
					if (!Fecha::menor(Fecha::usuarioToBD($this->fchvenc1), Fecha::usuarioToBD($this->fchvenc2)))
						$error[] = "La Fecha del 2do Vencimiento no puede ser menor a la fecha del 1er Vencimiento.  ";

				 }

			} else {


				/*
				 * Comienzo validaciones de fechas
				 *
				 * 1- Valido que la fecha del 2do venc no sea menor a la fecha del 1er venc
				 * 2- Verifico con el periodo anterior
				 * 3- Vefirico con el periodo siguiente
				 *
				 */

				 if ($this->fchvenc2 != ""){	//En caso de que la fecha de 2do vencimiento sea vacía

					//Valido que las fechas sean correctas
					if (!Fecha::menor(Fecha::usuarioToBD($this->fchvenc1), Fecha::usuarioToBD($this->fchvenc2)))
						$error[] = "La Fecha del 2do Vencimiento no puede ser menor a la fecha del 1er Vencimiento.";

				 }


				//Inicio - Verificar con el periodo anterior
				$sql = "SELECT to_char(fchvenc1,'dd/MM/yyyy') as vencimiento FROM trib_venc where trib_id = " . $this->trib_id;

				if ($this->cuota == 1)
					$sql = $sql . " and anio = " . ($this->anio - 1) . " and cuota = " . $maxcuota;
				else
					$sql = $sql . " and anio = " . $this->anio . " and cuota = " . ($this->cuota - 1);

				/*$fechaVencimiento almacena la fecha del 2do vencimiento del periodo anterior
				 * Si no existe periodo anterior, fechaVencimiento = ""
				 */
				$fechaVencimiento =  Yii :: $app->db->createCommand($sql)->queryScalar();

				if ($fechaVencimiento != ""){

					//Valido que las fechas sean correctas. Fecha 2do venc periodo anterior < Fecha 1er venc periodo actual
					if (!Fecha::menor(Fecha::usuarioToBD($fechaVencimiento), Fecha::usuarioToBD($this->fchvenc1)))
						$error[] = "La fecha de vencimiento no puede ser menor respecto del vencimiento del período anterior.";

				}
				//Fin - Verificar con el periodo anterior

				//Inicio - Verificar con el periodo siguiente
				$sql = "SELECT to_char(fchvenc1,'dd/MM/yyyy') as vencimiento FROM trib_venc WHERE trib_id = " . $this->trib_id;

				if ($this->cuota == $maxcuota)
					$sql = $sql . " and anio = " . ($this->anio + 1) . " and cuota = 1";
				else
					$sql = $sql . " and anio = " . $this->anio . " and cuota = " . ($this->cuota + 1);

				/*$fechaVencimiento almacena la fecha del 1er vencimiento del periodo siguiente
				 * Si no existe periodo siguiente, fechaVencimiento = ""
				 */
				$fechaVencimiento =  Yii :: $app->db->createCommand($sql)->queryScalar();

				if ($fechaVencimiento != ""){

						//Valido que las fechas sean correctas. Fecha 2do venc periodo actual < Fecha 1er venc periodo sguiente
						if (!Fecha::menor(Fecha::usuarioToBD($this->fchvenc1), Fecha::usuarioToBD($fechaVencimiento)))
							$error[] =  "La fecha de vencimiento no puede ser mayor respecto del vencimiento del período siguiente.";


				}
				//Fin - Verificar con el periodo siguiente


				//Si se habilita el vencimiento anual, se debe validar que el año sea el correcto
				if ( intval( $this->habilitarpagoanual) )
				{
					if ( $this->fchvencanual == '' || $this->fchvencanual == null )
					{
						$error[] = 'Ingrese una fecha de vencimiento anual.';
					}

				}
				 //Finalizo verificaciones de fecha
			}

		}

		$this->addErrors($error);
	}



    /**
     * Función que devuelve el número máximo de cuotas que corresponden a un tributo
     * @return integer Números de cuotas
     */
    public function obtenerFormaPagoTributo()
    {

    	//Obtengo la forma de pago del tributo
        $sql = "SELECT cant_anio FROM resol WHERE trib_id = " . $this->trib_id . " and " . ($this->anio*1000+$this->cuota) . " BETWEEN perdesde and perhasta";

        //Ejecuto la consulta
     	$cuo = Yii :: $app->db->createCommand($sql)->queryScalar();
		if ($cuo == null) $cuo = 12;
		return $cuo;

    }

    /**
     * Función que permite grabar un nuevo vencimiento o modificarlo.
     *
     */
    public function grabar()
    {
		$error = [];

    	$this->validar();

    	if ( !$this->hasErrors() )
    	{

    		if ($this->isNewRecord)
    		{ //Código que se ejecuta para ingresar un nuevo registro en la BD

		    	$sql = "SELECT EXISTS(SELECT 1 FROM trib_venc WHERE trib_id=" . $this->trib_id;
		        $sql .= " and anio=" . $this->anio . " and cuota=" . $this->cuota . ")";

		        //Ejecuto la consulta
		        $cmd = Yii :: $app->db->createCommand($sql)->queryScalar();

		        if ( $cmd == 1 ) {

		        	$error[] = "Vencimiento repetido.";

		        } else {

    				$sql = "INSERT INTO trib_venc VALUES (" . $this->trib_id . "," . $this->anio . "," . $this->cuota . ", " .
    						Fecha::usuarioToBD( $this->fchvenc1, 1 ) . "," . Fecha::usuarioToBD( $this->fchvenc2, 1 ) . ", " .
							Fecha::usuarioToBD( $this->fchvencanual, 1 ) . ",0 , current_timestamp," . Yii :: $app->user->id . ")";
							//El 0 al inicio de la consulta corresponde al valor que se ingresa para la variable segun_term

					try
					{
						$cmd = Yii :: $app->db->createCommand($sql);
						$rowCount = $cmd->execute();

						if( $this->fchvencanual != "" ){
							$sql = 	"update ctacte c set montoanual = (nominal*r.cant_anio) - sam.uf_calc_desc( $this->trib_id, obj_id, $this->anio*1000+$this->cuota,c.nominal,'$this->fchvencanual',0)" .
									" from resol r where c.trib_id=r.trib_id and c.anio*1000+c.cuota between r.perdesde and r.perhasta " .
									" and c.trib_id=$this->trib_id and c.anio=$this->anio and c.cuota=1 and c.est='D'";

							Yii :: $app->db->createCommand($sql)->execute();
						}

					} catch (Exception $e)
					{

			    		$error[] = DBException::getMensaje($e);
			    		$rowCount = 0;
						$this->addErrors($error);
					}

				$this->addErrors( $error );

				return $rowCount > 0;

		        }

    		} else { //Código que se ejecuta para actualizar un dato en la BD

    			$sql = "UPDATE trib_venc set fchvenc1 = " . Fecha::usuarioToBD( $this->fchvenc1, 1) . ", fchvenc2 = " .
    					Fecha::usuarioToBD( $this->fchvenc2, 1 ) . ", fchvencanual = " . Fecha::usuarioToBD( $this->fchvencanual, 1 ) .
						",segun_term= 0, fchmod=current_timestamp, usrmod=" . Yii :: $app->user->id .
						"where trib_id=" . $this->trib_id . " and anio= " . $this->anio . " and cuota = " . $this->cuota;

		        if($this->actualizactacte == 1){	//En caso de tener que actualizar las cta ctes
			        $sql2 = "SELECT sam.uf_emision_cambiar_venc(" . $this->trib_id . "," . $this->anio . "," . $this->cuota . ",";

			        if($this->fchvenc2 != "")
			       		$sql2 .= Fecha::usuarioToBD( $this->fchvenc2 , 1) . ")";
			       	else
			       		$sql2 .= Fecha::usuarioToBD( $this->fchvenc1, 1 ) . ")";
		        }

				$transaction = Yii :: $app->db->beginTransaction(); //Inicializo una transacción

		        try
				{
					$cmd = Yii :: $app->db->createCommand($sql);
					$rowCount = $cmd->execute();

					//Código que se ejecuta si el usuario quiere modificar los vencimientos de la ctacte
					if ($this->actualizactacte == 1)
					{
						$cmd = Yii :: $app->db->createCommand($sql2);
						$rowCount2 = $cmd->execute();
					}

					if( $this->fchvencanual != "" ){
						$sql = 	"update ctacte c set montoanual = (nominal*r.cant_anio) - sam.uf_calc_desc( $this->trib_id, obj_id, $this->anio*1000+$this->cuota,c.nominal,'$this->fchvencanual',0)" .
								" from resol r where c.trib_id=r.trib_id and c.anio*1000+c.cuota between r.perdesde and r.perhasta " .
								" and c.trib_id=$this->trib_id and c.anio=$this->anio and c.cuota=1 and c.est='D'";

						Yii :: $app->db->createCommand($sql)->execute();
					}

					$transaction->commit();

				} catch (Exception $e)
				{
		    		$error[] = DBException::getMensaje($e);
		    		$rowCount = 0;
		    		$transaction->rollBack();
				}

				$this->addErrors( $error );

				return $rowCount > 0;

    		}

    	}

    	$this->addErrors( $error );

    	return false;

    }

    /**
     * Procedimiento que permite borrar un Vencimiento
     */
    public function borrar()
    {

    	$error = [];

    	$sql = "SELECT count(*) FROM ctacte ";
        $sql .= " WHERE trib_id = " . $this->trib_id . " and anio = " . $this->anio . " and cuota = " . $this->cuota;

	    try
		{
			$rowCount = Yii :: $app->db->createCommand( $sql )->queryScalar();

		} catch (Exception $e) {

    		$error[] = DBException::getMensaje( $e );

		}

		if ($rowCount > 0) {

			$error[] = "Existen registros en la cuenta corriente";

		} else {

			//Sentencia sql que realiza el borrado de los datos en la BD
			$sql = "delete from trib_venc where trib_id=" . $this->trib_id . " and anio= " . $this->anio . " and cuota = " . $this->cuota;
            $sql2 = "delete from trib_venc_item where trib_id=" . $this->trib_id . " and anio=" . $this->anio . " and cuota=" . $this->cuota . "";
            $sql3 = "delete from trib_venc_cuit where trib_id=" . $this->trib_id . " and anio=" . $this->anio . " and cuota=" . $this->cuota;


            $transaction = Yii :: $app->db->beginTransaction(); //Inicializo una transacción
            try
            {

            	Yii :: $app->db->createCommand( $sql )->execute();
            	Yii :: $app->db->createCommand( $sql2 )->execute();
          		Yii :: $app->db->createCommand( $sql3 )->execute();

				$transaction->commit();

            } catch (Exception $e){

            	$error[] = DBException::getMensaje( $e );

            	$transaction->rollBack();

            }

		}

		$this->addErrors( $error );

		return !$this->hasErrors();

    }

    /**
     * Función que se encarga de buscar un vencimiento en la BD
     * @param integer trib_id Código de tributo
     * @param integer anio Año
     * @param integer cuota Cuota
     *
     * @return boolean
     */
    public function buscaVencimiento()
    {

    	$sql = "";
    	$sql = "select * from trib_venc_cuit where trib_id=" . $this->trib_id . " and anio=" . $this->anio . " and cuota=" . $this->cuota;

    	try{

			$cmd = Yii :: $app->db->createCommand($sql);
			$rowCount = $cmd->execute();

    	} catch (Exception $e) {

			$this->setError(DBException::getMensaje($e));
    		$rowCount = 0;

    	}

    	return $rowCount > 0;

    }

    /**
     * Función que obtiene la forma de pago de un tributo
     * @param integer trib_id Id del tributo
     */
    public function obtenerFormaPago()
    {

    	$sql = "";
    	$sql = "select tpago from trib where trib_id=" . $this->trib_id;

    	try {

    		$cmd = Yii :: $app->db->createCommand($sql)->queryScalar();

    	} catch (Exception $e){

    		$this->setError($e);

    	}

    	return $cmd;

    }

     /**
	  * Función que recibe un string con una fecha y lo transforma en un arreglo.
	  * @param string fechaString Valor del string con formato de fecha
	  * @return array fecha con resultado:
	  * 	$fecha[0] => "Año"
	  * 	$fecha[1] => "Mes"
	  * 	$fecha[2] => "Día"
	  */
	 public function stringToDate($fechaString) {

		$fecha = explode('/' , $fechaString);

		return $fecha;

	 }

	 	public function getTribVenc() {

		$sql = "SELECT trib_id cod, nombre FROM Trib WHERE tipo IN (1,2,4,5) and trib_id in (select distinct trib_id from trib_venc) ORDER BY Nombre";
		$cmd = Yii :: $app->db->createCommand($sql);

		return ArrayHelper :: map($cmd->queryAll(), 'cod', 'nombre');

		}

		 public function getTrib() {

		$sql = "SELECT trib_id cod, nombre FROM Trib WHERE tipo IN (1,2,4) ORDER BY Nombre";
		$cmd = Yii :: $app->db->createCommand($sql);

		return ArrayHelper :: map($cmd->queryAll(), 'cod', 'nombre');

	}



	/**
	 * Función que se encarga de buscar los vencimientos
	 * @param integer $cond Valor del tributo por el que se filtrará en la BD
	 * @param string $orden Cadena de string con los datos para ordenar la búsqueda
	 * @param integer $page Número de página que se está mostrando en el widget
	 *
	 * @return dataProvider $dataProvider Retorna los datos obtenidos de la BD
	 */
	public function buscarVencimiento ( $trib = 0, $anio )
    {

		$condicion = "v.trib_id =" . $trib . " AND anio=" . $anio;

    	$sql = "SELECT v.trib_id, anio, cuota, to_char(fchvenc1,'dd/MM/yyyy') as fchvenc1, to_char(fchvenc2,'dd/MM/yyyy') as fchvenc2, ";
    	$sql .="to_char(fchvencanual, 'dd/MM/yyyy') as fchvencanual, u.nombre || ' - ' || to_char(v.fchmod, 'dd/MM/yyyy')  as modif, segun_term";
        $sql .= " FROM trib_venc v left join trib t on v.trib_id = t.trib_id left join sam.sis_usuario u on v.usrmod = u.usr_id";
		$sql .= ' WHERE ' . $condicion;

        if ( $trib != 0 )
			return Yii::$app->db->createCommand( $sql )->queryAll();
		else
			return [];

    }

    	/**
	 * Función que retorna el nombre de una cuenta
	 * @param integer id Variable que almacenará el valor del id que se buscará en la BD
	 * @return string Value Nombre de la cuenta con identificación = id
	 */
	public function getNombreCuenta($id) {

		$cmd = "";

		if ($id != ""){

		$sql = "SELECT nombre FROM Cuenta WHERE cta_id =" . $id;
		$cmd = Yii :: $app->db->createCommand($sql)->queryScalar();

		}

		return $cmd;


	}

	/**
	 * Función que se utiliza para obtener el año.
	 */
	public function getAnio($trib_id= 0){

		$cmd = "";

		$sql = "SELECT row_number() OVER(ORDER BY anio desc) as fila, anio FROM trib_venc WHERE trib_id = " . $trib_id . " GROUP BY anio";
		$cmd = Yii :: $app->db->createCommand($sql);

		return ArrayHelper :: map($cmd->queryAll(), 'fila', 'anio');
	}
}
