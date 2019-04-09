<?php

namespace app\models\caja;

use yii\data\SqlDataProvider;
use app\utils\db\utb;
use yii\validators\EmailValidator;
use yii\data\ArrayDataProvider;

use Yii;

/**
 * This is the model class for table "debito_adhe".
 *
 * @property integer $adh_id
 * @property integer $trib_id
 * @property string $obj_id
 * @property integer $subcta
 * @property string $resp
 * @property integer $resptdoc
 * @property string $respndoc
 * @property string $respsexo
 * @property integer $caja_id
 * @property integer $temple
 * @property string $temple_area
 * @property integer $bco_suc
 * @property integer $bco_tcta
 * @property string $tpago_nro
 * @property string $fchalta
 * @property string $fchbaja
 * @property string $est
 * @property integer $perdesde
 * @property integer $perhasta
 * @property string $obs
 * @property integer $texto_id
 * @property string $fchmod
 * @property integer $usrmod
 */

 /**
  * Estos son los atributos para el manejo de adhesión
  *
  * @property integer $adhesion_id
  * @property string $adhesion_est
  * @property string $adhesion_estNom
  * @property integer $adhesion_tobj
  * @property string $adhesion_obj_id
  * @property string $adhesion_obj_nom
  * @property integer $adhesion_subcta
  * @property integer $adhesion_trib_id
  * @property string $adhesion_trib_nom
  * @property string $adhesion_texto
  * @property integer $adhesion_plan_anioDesde
  * @property integer $adhesion_plan_cuotaDesde
  * @property integer $adhesion_plan_anioHasta
  * @property integer $adhesion_plan_cuotaHasta
  * @property string $adhesion_usrmod
  * @property string $adhesion_obs
  * @property integer $adhesion_responsable_tdoc
  * @property integer $adhesion_responsable_ndoc
  * @property string $adhesion_responsable_sexo
  * @property string $adhesion_responsable_nombre
  * @property integer $adhesion_pago_caja_tipo
  * @property integer $adhesion_pago_caja_id
  * @property integer $adhesion_pago_sucID
  * @property integer $adhesion_pago_sucNom
  * @property integer $adhesion_pago_tipo
  * @property integer $adhesion_pago_numCta
  * @property integer $adhesion_pago_numTarjeta
  * @property integer $adhesion_pago_templeado
  * @property integer $adhesion_pago_area
  * @property integer $adhesion_pago_legajo
  */
class Debito extends \yii\db\ActiveRecord
{

	/* Creo los atributos de la clase para adhesiones */
	  public $adhesion_id;
	  public $adhesion_est;
	  public $adhesion_estNom;
	  public $adhesion_tobj;
	  public $adhesion_obj_id;
	  public $adhesion_obj_nom;
	  public $adhesion_subcta;
	  public $adhesion_trib_id;
	  public $adhesion_trib_nom;
	  public $adhesion_texto;
	  public $adhesion_plan_anioDesde;
	  public $adhesion_plan_cuotaDesde;
	  public $adhesion_plan_anioHasta;
	  public $adhesion_plan_cuotaHasta;
	  public $adhesion_usrmod;
	  public $adhesion_obs;
	  public $adhesion_responsable_tdoc;
	  public $adhesion_responsable_ndoc;
	  public $adhesion_responsable_sexo;
	  public $adhesion_responsable_nombre;
	  public $adhesion_pago_caja_tipo;
	  public $adhesion_pago_caja_id;
	  public $adhesion_pago_sucID;
	  public $adhesion_pago_sucNom;
	  public $adhesion_pago_tipo;
	  public $adhesion_pago_numCta;
	  public $adhesion_pago_cbu;
	  public $adhesion_pago_numTarjeta;
      public $adhesion_pago_templeado;
  	  public $adhesion_pago_area;
  	  public $adhesion_pago_legajo;

	  public $adhesion_tdistrib;
	  public $adhesion_distrib;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'debito_adhe';
    }

	function __construct()
	{
		$this->adhesion_tdistrib = 3;
	}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['adh_id', 'trib_id', 'obj_id', 'resp', 'caja_id', 'fchalta'], 'required'],
            [['adh_id', 'trib_id', 'subcta', 'resptdoc', 'respndoc', 'caja_id', 'temple', 'bco_suc', 'bco_tcta', 'perdesde', 'perhasta', 'texto_id'], 'integer'],
            [['fchalta', 'fchbaja'], 'safe'],
            [['obj_id'], 'string', 'max' => 8],
            [['resp'], 'string', 'max' => 50],
            [['respsexo', 'est'], 'string', 'max' => 1],
            [['temple_area', 'tpago_nro'], 'string', 'max' => 20],
            [['obs'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'adh_id' => 'Identificador de adhesiones',
            'trib_id' => 'Codigo de tributo',
            'obj_id' => 'Codigo de objeto',
            'subcta' => 'Numero de subcuenta',
            'resp' => 'Nombre del responsable',
            'resptdoc' => 'Codigo de tipo de documento del responsable',
            'respndoc' => 'Numero de documento del responsable',
            'respsexo' => 'Sexo del responsable',
            'caja_id' => 'Codigo de caja vinculada',
            'temple' => 'Tipo de empleado',
            'temple_area' => 'Area de trabajo',
            'bco_suc' => 'Sucursal bancaria',
            'bco_tcta' => 'Tipo de cuenta',
            'tpago_nro' => 'Numero identificador',
            'fchalta' => 'Fecha de alta',
            'fchbaja' => 'Fecha de baja',
            'est' => 'Estado',
            'perdesde' => 'Periodo desde',
            'perhasta' => 'Periodo hasta',
            'obs' => 'Observaciones',
            'texto_id' => 'Identificador del texto de la adhesion',
            'fchmod' => 'Fecha de modificacion',
            'usrmod' => 'Codigo de usuario que modifico',
        ];
    }

    /**
     * Función que obtiene el listado de adhesiones
     * @param integer $caja Id de la caja
     * @param integer $trib_id Id de tributo
     * @param string $obj_id Id de objeto
     * @param integer $anio Año
     * @param integer $mes Mes
     * @param integer $baja Indica si se incluyen o no las adhesiones dadas de baja
     * @return dataProvider Resultado de la búsqueda
     */
    public function listarAdhe($caja,$trib_id = 0,$obj_id = '',$anio = 0,$mes = 0,$baja = 1,$num = '')
    {
    	$cond = "";

    	//Obtengo el período según tributo, año y mes y lo agrego a $cond
    	if ($trib_id != 0 && $anio != 0 && $mes != 0)
    	{
    		$per = utb::PerxMes($trib_id,$anio,$mes);

    		if ($per != 0)
    			$cond = $per . " BETWEEN perdesde AND perhasta ";
    	}

		//Si caja > 0
		if ($caja > 0)
			$cond .= ($cond == '' ? '' : ' and ') .  " caja_id = " . $caja;

		//Si tributo > 0
		if ($trib_id > 0)
			$cond .=  ($cond == '' ? '' : ' and ') .  " trib_id = " . $trib_id;

		//Si objeto <> ''
		if ($obj_id != '')
			$cond .= ($cond == '' ? '' : ' and ') .  " obj_id = '" . $obj_id . "'";

		//Si no se incluyen las adhesiones dadas de baja
		if ($baja == 0)
			$cond .= ($cond == '' ? '' : ' and ') .  " est = 'A'";

		if ($num != '')
			$cond .= ($cond == '' ? '' : ' and ') .  " obj_id in (select obj_id from objeto where num='" . $num . "')";

    	$sql = "SELECT adh_id,trib_nom,obj_id,subcta,resp,est,CONCAT(substring(perdesde::text,1,4),'-',substring(perdesde::text,5,3)) as perdesde, " .
				"CONCAT(substring(perhasta::text,1,4),'-',substring(perhasta::text,5,3)) as perhasta,fchalta,fchbaja,caja_id,caja_nom " .
    			"FROM v_debito_adhe ";

		if ($cond != '')
    		$sql .= " where " . $cond;

		$sql .= " ORDER BY trib_id,obj_id ASC";

		$dataProvider = new SqlDataProvider([
			'sql' => $sql,
            'key'=>'adh_id',
			'pagination'=> [
				'pageSize'=>count(Yii::$app->db->createCommand($sql)->queryAll()),

			],
		]);

		return $dataProvider;
    }

    /**
     * Función que obtiene una lista de Planes de una determinada Caja
     * @param integer $caja Id de la caja
     * @param string $obj_id Id de objeto
     * @param integer $anio Año
     * @param integer $mes Mes
     * @param integer $baja Indica si se incluyen o no las adhesiones dadas de baja
     * @return dataProvider Resultado de la búsqueda
     */
    public function listarPlanes($caja,$obj_id = '',$anio = 0,$mes = 0,$baja = 1, $num = '')
    {
    	$cond = "";

    	//Obtengo el período según tributo, año y mes y lo agrego a $cond
    	if ( $anio != 0 )
    		$cond .= " extract(year from fchalta) <= " . $anio . " AND extract(year from ultvenc) >= " . $anio;

    	if ( $mes != 0 )
    		$cond .= " AND extract(month from fchalta) <= " . $mes . " AND extract(month from ultvenc) >= " . $mes;

		//Si caja > 0
    	if ($caja > 0)
    		$cond .=  ($cond == '' ? '' : ' and ') .  " caja_id = " . $caja;

    	//Si objeto <> ''
    	if ( $obj_id != '' )
    		$cond .= ($cond == '' ? '' : ' and ') ." obj_id = '" . $obj_id . "'";

    	//Si no se incluyen las adhesiones dadas de baja
    	if ( $baja == 0 )
    		$cond .= ($cond == '' ? '' : ' and ') ." est = 1";

	if ( $num != "" )
    		$cond .= ($cond == '' ? '' : ' and ') .  " obj_id in (select obj_id from objeto where num='" . $num . "')";

    	$sql = "SELECT plan_id,obj_id,resp,est,est_nom,cuotas,to_char(fchalta,'dd/MM/yy') as fchalta,to_char(ultvenc, 'dd/MM/yy') as ultvenc " .
    			"FROM v_plan ";

		if ( $cond != '' )
    		$sql .= " where " . $cond;

		$sql .= " ORDER BY plan_id";

    	$dataProvider = new SqlDataProvider([

			'sql' => $sql,
            'key'=>'plan_id',
			'pagination'=> [
				'pageSize'=>count(Yii::$app->db->createCommand($sql)->queryAll()),

			],
		]);

		return $dataProvider;
    }

    /**
     * Función que se ejecuta junto al findModel.
     * Se encarga de setear valores por defecto a los atributos de adhesión.
     */
    public function cargarValoresPorDefectoAdhesion()
    {
      $this->adhesion_id = '';
	  $this->adhesion_est = '';
	  $this->adhesion_estNom = '';
	  $this->adhesion_tobj = 0;
	  $this->adhesion_obj_id = '';
	  $this->adhesion_obj_nom = '';
	  $this->adhesion_subcta = 0;
	  $this->adhesion_trib_id = 0;
	  $this->adhesion_trib_nom = '';
	  $this->adhesion_texto = '';
	  $this->adhesion_plan_anioDesde = '';
	  $this->adhesion_plan_cuotaDesde = '';
	  $this->adhesion_plan_anioHasta = '';
	  $this->adhesion_plan_cuotaHasta = '';
	  $this->adhesion_usrmod = '';
	  $this->adhesion_obs = '';
	  $this->adhesion_responsable_tdoc = 0;
	  $this->adhesion_responsable_ndoc = '';
	  $this->adhesion_responsable_sexo = '';
	  $this->adhesion_responsable_nombre = '';
	  $this->adhesion_pago_caja_tipo = '';
	  $this->adhesion_pago_caja_id = '';
	  $this->adhesion_pago_sucID = '';
	  $this->adhesion_pago_sucNom = '';
	  $this->adhesion_pago_tipo = 0;
	  $this->adhesion_pago_numCta = '';
	  $this->adhesion_pago_cbu = '';
	  $this->adhesion_pago_numTarjeta = '';
      $this->adhesion_pago_templeado = '';
  	  $this->adhesion_pago_area = '';
  	  $this->adhesion_pago_legajo = '';
	  $this->adhesion_tdistrib = 3;
	  $this->adhesion_distrib = 0;
    }

    /**
     * Función que se utiliza para validar los datos que serán ingresados en la BD
     */
    public function validarAdhesion()
    {
    	$error = '';

		/* Validar que se ingrese un objeto válido */
		if ($this->adhesion_obj_id == '')
			$error .= '<li>Ingrese un objeto.</li>';
		else if ($this->adhesion_obj_nom == '')
			$error .= '<li>Ingrese un objeto válido.</li>';

		/* Validar que se ingrese un tributo válido */
		if ($this->adhesion_trib_id == '' || $this->adhesion_trib_id == 0)
			$error .= '<li>Ingrese un tributo válido.</li>';

		/* Validar mail si tipo de distribucion es 5-Mail */
		if ( $this->adhesion_tdistrib == 5 ){

			$sql = "select num from objeto where obj_id='$this->adhesion_obj_id'";
			$num =  Yii::$app->db->createCommand( $sql )->queryScalar();

			$sql = "select mail from persona where obj_id='$num' ";
			$mail =  Yii::$app->db->createCommand( $sql )->queryScalar();

			$validator = new EmailValidator();

			if (!($validator->validate($mail, $err)))
			{
				$error .= "<li>El mail del contribuyente no es válido</li>";
			}
		}

		if ( $this->adhesion_tdistrib != 3 and intVal($this->adhesion_distrib) == 0 )
			$error .= '<li>Seleccione un distribuidor.</li>';

		/* Validar que se ingrese tipo y número de documento */
		/* Validar que se ingrese un nombre de responsable y sexo */

		/* Validar que se ingrese una caja */

		/* Validar según el tipo de caja */


		/* Validar que se ingrese una sucursal */
		/* Validar que se ingrese una sucursal válida */

		/* Validar que se seleccione un tipo de cuenta */


		/* Validar que se ingrese una cuenta y que se encuentre entre 4 y 11 caracteres */

		/* Validar que se ingrese el número de tarjeta */

		/* Validar que se ingrese el área del empleado */

		if ($this->adhesion_subcta == '')
			$this->adhesion_subcta = 0;

		if ($this->adhesion_pago_sucID == '')
			$this->adhesion_pago_sucID = 0;

		return $error;
    }

    /**
     * Función que se ejecuta para cargar los datos de una adhesión en caso de consulta de la misma.
     */
    public function cargarAdhesion($id)
    {

    	$sql = "SELECT adh_id,est,obj_id,obj_nom,subcta,trib_id,trib_nom,texto_id,perdesde,perhasta,modif,obs," .		//Datos de adhesión
    			"resp,resptdoc,respndoc,respsexo," .																	//Datos de responsable
    			"caja_tipo,caja_id,tpago_nro,cbu,bco_suc,bco_suc_nom,bco_tcta,bco_tcta_nom,temple,temple_nom,temple_area ";						//Datos de forma de pago

    	$sql .= " FROM v_debito_adhe ";
    	$sql .= "WHERE adh_id = " . $id;

    	$data = Yii::$app->db->createCommand($sql)->queryAll();	//Ejecuto la consulta

    	//INICIO Cargar datos al modelo
		$this->adhesion_id = $data[0]['adh_id'];
		$this->adhesion_est = $data[0]['est'];
		if ($this->adhesion_est == 'A')
			$this->adhesion_estNom = "Activo";
		if ($this->adhesion_est == 'B')
			$this->adhesion_estNom = "Baja";
		$this->adhesion_obj_id = $data[0]['obj_id'];
		$this->adhesion_tobj = utb::getTObj($this->adhesion_obj_id);
		$this->adhesion_obj_nom = $data[0]['obj_nom'];
		$this->adhesion_subcta = $data[0]['subcta'];
		$this->adhesion_trib_id = $data[0]['trib_id'];
		$this->adhesion_trib_nom = $data[0]['trib_nom'];
		$this->adhesion_texto = $data[0]['texto_id'];

		$this->adhesion_plan_anioDesde = substr($data[0]['perdesde'],0,4);
		$this->adhesion_plan_cuotaDesde = substr($data[0]['perdesde'],4,3);
		$this->adhesion_plan_anioHasta = substr($data[0]['perhasta'],0,4);
		$this->adhesion_plan_cuotaHasta = substr($data[0]['perhasta'],4,3);

		$this->adhesion_usrmod = $data[0]['modif'];
		$this->adhesion_obs = $data[0]['obs'];

		$this->adhesion_responsable_tdoc = $data[0]['resptdoc'];
		$this->adhesion_responsable_ndoc = $data[0]['respndoc'];
		$this->adhesion_responsable_sexo = $data[0]['respsexo'];
		$this->adhesion_responsable_nombre = $data[0]['resp'];
		$this->adhesion_pago_caja_tipo = $data[0]['caja_tipo'];
		$this->adhesion_pago_caja_id = $data[0]['caja_id'];

		//Según el tipo de caja obtengo los datos
		switch ($this->adhesion_pago_caja_tipo)
		{
			case 3:

				$this->adhesion_pago_sucID = $data[0]['bco_suc'];
				$this->adhesion_pago_sucNom = $data[0]['bco_suc_nom'];
				$this->adhesion_pago_tipo = $data[0]['bco_tcta'];
				$this->adhesion_pago_numCta = $data[0]['tpago_nro'];
				$this->adhesion_pago_cbu = $data[0]['cbu'];

				break;

			case 4:

				$this->adhesion_pago_numTarjeta = $data[0]['tpago_nro'];

				break;

			case 5:

				$this->adhesion_pago_templeado = $data[0]['temple'];
				$this->adhesion_pago_area = $data[0]['temple_area'];
				$this->adhesion_pago_legajo = $data[0]['tpago_nro'];

				break;
		}

    	//FIN Cargar datos al modelo

    }

    /**
     * Función que se ejecuta para dar de alta una adhesión.
     */
    public function nuevaAdhesion()
    {
    	$error = $this->validarAdhesion();
		if ( $error !== "" ) return $error;

    	//Validar que no exista la Adhesion
    	if ($this->existeAdhesion($this->adhesion_trib_id,$this->adhesion_obj_id,$this->adhesion_subcta) == 1)
    		return 'La Adhesión ingresada para el Tributo-Objeto ya existe.';

		$perdesde = intval( $this->adhesion_plan_anioDesde ) * 1000 + intval( $this->adhesion_plan_cuotaDesde );
		$perhasta = intval( $this->adhesion_plan_anioHasta * 1000) + intval( $this->adhesion_plan_cuotaHasta );

		switch ($this->adhesion_pago_caja_tipo)
		{
			case 3:

				$tpago_num = $this->adhesion_pago_numCta;
				break;

			case 4:

				$tpago_num = $this->adhesion_pago_numTarjeta;
				break;

			case 5:

				$tpago_num = $this->adhesion_pago_legajo;
				break;
		}

        $adh_id = Yii::$app->db->createCommand("Select nextval('seq_debito_adh')")->queryScalar();

        $sql = "INSERT INTO Debito_Adhe Values (" . $adh_id . "," . $this->adhesion_trib_id . ",'" . $this->adhesion_obj_id . "',";
        $sql .= $this->adhesion_subcta . ",'" . $this->adhesion_responsable_nombre . "'," . $this->adhesion_responsable_tdoc . "," . $this->adhesion_responsable_ndoc . ",";
        $sql .= "'" . $this->adhesion_responsable_sexo . "'," . $this->adhesion_pago_caja_id . "," . $this->adhesion_pago_templeado . ",'" . $this->adhesion_pago_area . "',";
        $sql .= $this->adhesion_pago_sucID . "," . $this->adhesion_pago_tipo . ",'" . $tpago_num . "','" . $this->adhesion_pago_cbu;
        $sql .= "', current_timestamp, null, 'A'," . $perdesde . "," . $perhasta . ",'" . $this->adhesion_obs . "'," . $this->adhesion_texto . ",current_timestamp, " . Yii::$app->user->id . ")";

        try
        {
        	$count = Yii::$app->db->createCommand($sql)->execute();

        	$sql = "Update objeto set distrib = $this->adhesion_distrib, tdistrib = $this->adhesion_tdistrib where obj_id='" . $this->adhesion_obj_id . "'";

        	$count = Yii::$app->db->createCommand($sql)->execute();

        	return '';
        }
        catch (Exception $e)
        {
        	return "Ocurrió un error al intentar grabar los datos en la BD.";
        }

    }

    /**
     * Función que se ejecuta para modificar los datos de una adhesión.
     */
    public function modificarAdhesion()
    {
    	$error = $this->validarAdhesion();
		if ( $error !== "" ) return $error;

        //Validar que exista la Adhesion
        if ($this->existeAdhesion($this->adhesion_trib_id,$this->adhesion_obj_id,$this->adhesion_subcta) == 0)
    		return 'La Adhesión ingresada para el Tributo-Objeto no existe.';

		$perdesde = intval( $this->adhesion_plan_anioDesde ) * 1000 + intval( $this->adhesion_plan_cuotaDesde );
		$perhasta = intval( $this->adhesion_plan_anioHasta * 1000) + intval( $this->adhesion_plan_cuotaHasta );

		switch ($this->adhesion_pago_caja_tipo)
		{
			case 3:

				$tpago_num = $this->adhesion_pago_numCta;
				break;

			case 4:

				$tpago_num = $this->adhesion_pago_numTarjeta;
				break;

			case 5:

				$tpago_num = $this->adhesion_pago_legajo;
				break;
		}

        $sql = "Update debito_adhe Set Resp='" . $this->adhesion_responsable_nombre . "',resptdoc=" . $this->adhesion_responsable_tdoc . ",respndoc=" . $this->adhesion_responsable_ndoc;
        $sql .= ",respsexo='" . $this->adhesion_responsable_sexo . "',caja_id=" . $this->adhesion_pago_caja_id . ",temple=" . $this->adhesion_pago_templeado . ",temple_area='" . $this->adhesion_pago_area;
        $sql .= "',bco_suc=" . $this->adhesion_pago_sucID . ",bco_tcta=" . $this->adhesion_pago_tipo . ",tpago_nro='" . $tpago_num . "',cbu='" . $this->adhesion_pago_cbu;
        $sql .= "',est='" . $this->adhesion_est . "',perdesde=" . $perdesde . ",perhasta=" . $perhasta . ",obs='" . $this->adhesion_obs;
        $sql .= "',texto_id=" . $this->adhesion_texto . ",fchmod=current_timestamp, usrmod=" . Yii::$app->user->id . " Where adh_id=" . $this->adhesion_id;

		try
        {
        	$count = Yii::$app->db->createCommand($sql)->execute();

			$sql = "Update objeto set distrib = $this->adhesion_distrib, tdistrib = $this->adhesion_tdistrib where obj_id='" . $this->adhesion_obj_id . "'";

        	$count = Yii::$app->db->createCommand($sql)->execute();

        	return '';
        }
        catch (Exception $e)
        {
        	return "Ocurrió un error al intentar modificar los datos en la BD.";
        }
    }

    /**
     * Función que se ejecuta para eliminar una adhesión.
     */
    public function eliminarAdhesion($id)
    {
    	$sql = "Update debito_adhe Set est='B', FchBaja=current_timestamp Where adh_id=" . $id;

    	try
    	{
    		Yii::$app->db->createCommand($sql)->execute();

    		$sql = "Update objeto set distrib = 0 where obj_id='" . $id . "'";

    		Yii::$app->db->createCommand($sql)->execute();

    		return '';

    	} catch (Exception $e)
    	{
    		return "Ocurrió un error al intentar eliminar los datos.";
    	}

    }

    /**
     * Función que se ejecuta para comprobar si existe una Adhesión
     * @param integer $trib_id Código de tributo
     * @param string $obj_id Identificador de Objeto
     * @param integer $subcta Subcuenta
     * @return integer Devuelve 1 en caso que exista o 0 en caso contrario
     */
    private function existeAdhesion($trib_id,$obj_id,$subcta)
    {

        $sql = "Select exists (SELECT 1 From debito_adhe WHERE Trib_Id=" . $trib_id;
        $sql .= " AND obj_id='" . $obj_id . "' AND SubCta = " . $subcta . " and Est = 'A')";

        $count = Yii::$app->db->createCommand($sql)->queryScalar();

        return $count;
    }

    /**
     * Función que obtiene una lista de liquidacions
     * @param integer $caja Id de la caja
     * @param integer $trib_id Id de tributo
     * @param string $obj_id Id de objeto
     * @param integer $anio Año
     * @param integer $mes Mes
     * @return dataProvider Resultado de la búsqueda
     */
    public function listarDebitoLiq($caja,$trib_id,$obj_id = '',$anio = 0,$mes = 0)
    {
    	$cond = "";

    	$sql = "SELECT deb_id,obj_id,subcta,resp,anio,mes,trib_nom,periodo,monto,montodeb,est,rechazo,trechazo,obs,fchdeb " .
    			"FROM v_debito_liq " .
    			"WHERE caja_id = " . $caja . " AND anio = " . $anio . " AND mes = " . $mes;

    	//Si tributo <> ''
    	if ($trib_id != '' && $trib_id != 0)
    		$sql .= " AND trib_id = " . $trib_id;

    	//Si objeto <> ''
    	if ($obj_id != '')
    		$sql .= " AND obj_id = '" . $obj_id . "'";

    	$sql .= " ORDER BY deb_id";
    	$dataProvider = new SqlDataProvider([

			'sql' => $sql,
            'key'=>'deb_id',
			'pagination' => [
				'pageSize' => count(Yii::$app->db->createCommand($sql)->queryAll()),
			]
		]);

		// cargo datos para exportar
		Yii::$app->session['titulo'] = "Debitos - Listado de Liquidaciones";
		Yii::$app->session['condicion'] = " -Caja: " . utb::getCampo("caja","caja_id=" . $caja,"nombre") .
											"<br> -Tributo: " . utb::getCampo("trib","trib_id=" . $trib_id,"nombre") .
											( $obj_id <> "" ? "<br> -Objeto: " . $obj_id . utb::getCampo("objeto","obj_id='" . $obj_id . "'","nombre") : "" ) .
											( $anio <> 0 ? "<br> -Año: " . $anio : "" ) .
											( $mes <> 0 ? "<br> -Mes: " . $mes : "" ) ;
		Yii::$app->session['sql'] = $sql;
		Yii::$app->session['proceso_asig'] = 3320;
		Yii::$app->session['columns'] = [
			['attribute'=>'obj_id','label' => 'Objeto', 'contentOptions'=>['style'=>'text-align:center','width'=>'40px']],
			['attribute'=>'subcta','label' => 'Cta', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
			['attribute'=>'resp','label' => 'Responsable', 'contentOptions'=>['style'=>'text-align:center','width'=>'150px']],
			['attribute'=>'anio','label' => 'Año', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
			['attribute'=>'mes','label' => 'Mes', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
			['attribute'=>'trib_nom','label' => 'Tributo', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
			['attribute'=>'periodo','label' => 'Período', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
			['attribute'=>'monto','label' => 'Monto', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
			['attribute'=>'montodeb','label' => 'Débito', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
			['attribute'=>'est','label' => 'Est', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],

        ];

		//Fechas de Generacion y Banco
        $sql = "Select to_char(fchgenerado,'dd/MM/yyyy') as fchgenerado,to_char(fchenvio,'dd/MM/yyyy') as fchenvio,to_char(fchrecep,'dd/MM/yyyy') as fchrecep,to_char(fchimputa,'dd/MM/yyyy') as fchimputa From debito_entidad ";
        $sql .= "Where caja_id=" . $caja . " and anio=" . $anio . " and mes=" . $mes;

       //Si tributo <> ''
    	if ($trib_id != '' && $trib_id != 0)
    		$sql .= " AND trib_id = " . $trib_id;

    	$fechas = Yii::$app->db->createCommand($sql)->queryAll();

		$datos = ['fechas' => $fechas, 'dataProvider' => $dataProvider];

		return $datos;
    }

    /**
     * Función que se encarga de eliminar una Liquidación de la Bd
     * @param integer $id identificador de la liquidación
     * @return string Resultado de la eliminación
     */
    public function eliminarLiquidacion($id)
    {
    	//Verificar Estado - si ya se Imputó mostrar mensaje de error
    	$est = Yii::$app->db->createCommand("SELECT est FROM debito_periodo WHERE deb_id = " . $id)->queryScalar();

    	if ($est == '4')
    		return 'La Liquidación ya fue Imputada.';
    	else
    	{
    		$sql = "DELETE FROM debito_periodo WHERE deb_id = " . $id;

    		try
    		{
    			Yii::$app->db->createCommand($sql)->execute();
    			return '';

    		} catch (Exception $e)
    		{
    			return "Ocurrió un error al eliminar la Liquidación.";
    		}

    	}

    }

    /**
     * Función que se ejecuta para obtener el listado de tributos para generar Débito Automático
     */
    public function listarTributosDebAut()
    {
    	$sql = "SELECT trib_id,nombre" .
    			" FROM trib " .
    			"WHERE est = 'A' AND " .
    				"(trib_id = 1 OR trib_id IN " .
    					"(SELECT distinct trib_id FROM debito_adhe WHERE est = 'A'))";

    	$dataProvider = new SqlDataProvider([

			'sql' => $sql,
            'key'=>'trib_id',
		]);

		return $dataProvider;
    }

    /**
     * Función que se encarga de ingresar en la BD los débitos automáticos
     */
    public function generarDebito($caja_id,$array,$anio,$mes)
    {
    	try
    	{
    		foreach($array as $arreglo)
			{
				Yii::$app->db->createCommand("SELECT sam.uf_caja_deb_generar(" .$caja_id. "," . $arreglo.",".$anio.",".$mes."," . Yii::$app->user->id.")")->execute();
			}

			return "";

    	} catch (Exception $e)
    	{
    		return "Ocurrió un error al inentar grabar los datos.";
    	}

    }

    public function BuscarLiq($ctacte_id)
    {
    	$sql = "Select * From V_Debito_Liq Where CtaCte_Id = " . $ctacte_id;
    	$liq = Yii::$app->db->createCommand($sql)->queryAll();

    	return $liq;
    }

    public function ImprimirAdhe($adhe,&$sub1,&$montoactual)
    {
    	$sql = "Select * From V_Debito_Adhe Where Adh_id=" . $adhe;
    	$liq = Yii::$app->db->createCommand($sql)->queryAll();

    	$sql = "Select * From v_debito_liq Where adh_id=" . $adhe." Order by caja_id,trib_id,obj_id,anio,mes";
    	$sub1 = Yii::$app->db->createCommand($sql)->queryAll();

    	$sql = "Select monto From sam.uf_caja_deb_montoactual(" . $adhe . ")";

    	$montoactual = Yii::$app->db->createCommand($sql)->queryScalar();

    	return $liq;
    }

	public function verifTextoCertificado( $uso ){

			$sql = "select count(*) cant from texto where tuso = " . $uso;
	    	$cant = Yii::$app->db->createCommand($sql)->queryScalar();

			return $cant;
	}

	public function getCertificado( $adhe, $texto ){

		$sql = "select * from sam.uf_texto_debito_adhe( $adhe, $texto )";
    	$resultado = Yii::$app->db->createCommand($sql)->queryAll();

		return $resultado;

	}
	
	public function camposGenerarReporte(){
	
		$resultado = [
			[ 'id' => 'caja_id', 'nombre' => 'Caja ID', 'orden' => '1' ],
		    [ 'id' => 'caja_tipo', 'nombre' => 'Tipo Caja', 'orden' => '2' ],
		    [ 'id' => 'caja_nom', 'nombre' => 'Caja Nombre', 'orden' => '3' ],
		    [ 'id' => 'resp', 'nombre' => 'Responsbale', 'orden' => '4' ],
		    [ 'id' => 'resp_tdoc_nom', 'nombre' => 'Tipo Doc. Responsable', 'orden' => '5' ],
		    [ 'id' => 'resp_ndoc', 'nombre' => 'Nro. Documento Responsable', 'orden' => '6' ],
		    [ 'id' => 'resp_sexo', 'nombre' => 'Sexo Responsable', 'orden' => '7' ],
		    [ 'id' => 'cbu', 'nombre' => 'CBU', 'orden' => '8' ],
		    [ 'id' => 'bancocta', 'nombre' => 'Banco Cta.', 'orden' => '9' ],
		    [ 'id' => 'nrotarjeta', 'nombre' => 'Nro. Tarjeta', 'orden' => '10' ],
		    [ 'id' => 'temple', 'nombre' => 'Tipo Empleado', 'orden' => '11' ],
		    [ 'id' => 'legajo', 'nombre' => 'Legajo', 'orden' => '12' ],
		    [ 'id' => 'trib_id', 'nombre' => 'Tributo ID', 'orden' => '13' ],
		    [ 'id' => 'trib_nom', 'nombre' => 'Tributo Nombre', 'orden' => '14' ],
		    [ 'id' => 'anio', 'nombre' => 'Año', 'orden' => '15' ],
		    [ 'id' => 'mes', 'nombre' => 'Mes', 'orden' => '16' ],
		    [ 'id' => 'objeto', 'nombre' => 'Objeto ID', 'orden' => '17' ],
		    [ 'id' => 'obj_nom', 'nombre' => 'Objeto Nombre', 'orden' => '18' ],
		    [ 'id' => 'subcta', 'nombre' => 'SubCta.', 'orden' => '19' ],
		    [ 'id' => 'periodo', 'nombre' => 'Periodo', 'orden' => '20' ],
		    [ 'id' => 'identificacion', 'nombre' => 'Identificación', 'orden' => '21' ],
		    [ 'id' => 'referencia', 'nombre' => 'Referencia', 'orden' => '22' ],
		    [ 'id' => 'ccest', 'nombre' => 'Estado Cta.Cte', 'orden' => '23' ],
		    [ 'id' => 'fchvenc', 'nombre' => 'Fecha Vencimiento', 'orden' => '24' ],
		    [ 'id' => 'monto', 'nombre' => 'Monto', 'orden' => '25' ],
		    [ 'id' => 'fchvenc2', 'nombre' => '2da. Fecha Vencimiento', 'orden' => '26' ],
		    [ 'id' => 'monto2', 'nombre' => '2do. Monto', 'orden' => '27' ] 
		];    

		$resultado = new ArrayDataProvider([ 
						'allModels' => $resultado, 
						'pagination' => false,
						'key' => 'id'
					]);	
		
		return $resultado;
		
	}

	public function GenerarReporte( $caja, $anio, $mes, $seleccionado ){

		// ordeno el array por el campo orden 
		usort($seleccionado, function($a, $b) {
		  return $a['orden'] - $b['orden'];
		});	
		
		// obtengo array de campos
		$arrayCampos = array_column($seleccionado, 'campo');
		
		$campos = implode(",", $arrayCampos);
		
		if ( $campos == '' ) $campos = '*';
		
		$sql = "select $campos from v_debito_entidad where caja_id=$caja and anio=$anio and mes=$mes";
		
		$resultado = Yii::$app->db->createCommand($sql)->queryAll();

		return $resultado;
	}
	
	public function detalleCamposGenerarReporte( $seleccionado, $desc ){
	
		// ordeno el array por el campo orden 
		usort($seleccionado, function($a, $b) {
		  return $a['orden'] - $b['orden'];
		});	
		
		// obtengo array de campos
		$arrayCampos = array_column($seleccionado, 'campo');
		
		$desc = array_filter($desc, function ($var) use ($arrayCampos) {
			return ( in_array($var['id'], $arrayCampos) );
		});
		
		// actualizo el orden a ·$desc
		foreach ( $desc as $k => $d ){
			foreach ( $seleccionado as $s ){
				if ( $d['id'] == $s['campo'] )
					$desc[$k]['orden'] = $s['orden'];
			}	
		}
		
		// ordeno el array de descripcion por el campo orden 
		usort($desc, function($a, $b) {
		  return $a['orden'] - $b['orden'];
		});	
		
		// obtengo array solo con la descripcion
		$desc = array_column($desc, 'nombre');
		
		return $desc;
	}
}
