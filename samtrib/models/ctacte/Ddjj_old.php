<?php

namespace app\models\ctacte;

use Yii;
use yii\web\Session;
use yii\data\SqlDataProvider;
use app\utils\db\utb;
use app\utils\db\Fecha;
use app\utils\helpers\DBException;
use app\models\ctacte\RetencionDetalle;

/**
 * This is the model class for table "ddjj".
 *
 * @property integer $dj_id
 * @property integer $dj_id_web
 * @property integer $ctacte_id
 * @property integer $trib_id
 * @property string $obj_id
 * @property integer $subcta
 * @property integer $fiscaliza
 * @property integer $anio
 * @property integer $cuota
 * @property integer $orden
 * @property integer $tipo
 * @property string $base
 * @property string $anual
 * @property string $anticipos
 * @property string $monto
 * @property string $multa
 * @property string $fchpresenta
 * @property string $est
 * @property string $error_act
 * @property string $fchmod
 * @property integer $usrmod
 *
 *
 * Datos para la retención
 * @property string $rete_cuit
 * @property string $rete_obj_id
 * @property string $rete_denominacion
 * @property integer $rete_lugar
 * @property string $rete_fecha
 * @property integer $rete_numero
 * @property integer $rete_tcomprob
 * @property integer $rete_comprob
 * @property double $rete_base
 * @property double $rete_ali
 * @property double $rete_monto
 * @property integer $rete_agente
 */

class Ddjj extends \yii\db\ActiveRecord
{

	public $trib_nom; 	//Nombre del tributo
	public $obj_nom;	//Nombre del objeto
	public $fchvenc;	//Fecha de vencimiento
	public $orden_nom;
	public $tipo_nom;
	public $total_base;
	public $total_monto;
	public $total_multa;
	public $estado;

	//Variables para retención
	public $rete_cuit;
	public $rete_obj_id;
	public $rete_denominacion;
	public $rete_lugar;
	public $rete_fecha;
	public $rete_numero;
	public $rete_tcomprob;
	public $rete_comprob;
	public $rete_base;
	public $rete_ali;
	public $rete_monto;
	public $rete_agente;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ddjj';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dj_id_web', 'ctacte_id', 'trib_id', 'subcta', 'fiscaliza', 'anio', 'cuota', 'orden', 'tipo', 'usrmod'], 'integer'],
            [['ctacte_id', 'trib_id', 'obj_id', 'anio', 'cuota', 'orden', 'tipo', 'anual', 'anticipos', 'monto', 'multa', 'fchpresenta', 'est', 'usrmod'], 'required'],
            [['base', 'anual', 'anticipos', 'monto', 'multa'], 'number'],
            [['fchpresenta', 'fchmod'], 'safe'],
            [['obj_id'], 'string', 'max' => 8],
            [['est'], 'string', 'max' => 1],
            [['error_act'], 'string', 'max' => 20],

			//Variables de retención
			[['rete_cuit', 'rete_obj_id', 'rete_denominacion', 'rete_fecha'],'string'],
			[['rete_lugar', 'rete_numero', 'rete_tcomprob', 'rete_comprob','rete_agente'],'integer'],
			[['rete_base', 'rete_ali', 'rete_monto'],'double'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dj_id' => 'Identificador de declaracion jurada',
            'dj_id_web' => 'Identificador de dj de web',
            'ctacte_id' => 'Identificador de cuenta corriente',
            'trib_id' => 'Codigo de tributo',
            'obj_id' => 'Codigo de objeto',
            'subcta' => 'Subcta',
            'fiscaliza' => 'Si es de fiscalizacion',
            'anio' => 'A�o',
            'cuota' => 'Cuota',
            'orden' => 'Numero de orden',
            'tipo' => 'Codigo de tipo de dj',
            'base' => 'Monto de base imponible',
            'anual' => 'Monto anual',
            'anticipos' => 'Monto de anticipos',
            'monto' => 'Monto',
            'multa' => 'Monto de multa',
            'fchpresenta' => 'Fecha de presentacion',
            'est' => 'Estado',
            'error_act' => 'Error de actualizacion',
            'fchmod' => 'Fecha de modificacion',
            'usrmod' => 'Codigo de usuario que modifico',
        ];
    }

	/**
	 * Función que obtiene los datos de Rubros de una DDJJ
	 * @param integer $id Id de la DDJJ
	 * @return dataProvider Resultado
	 */
    public function getDatosGrillaRubros($id)
    {
    	if ($id == '') $id = 0;

    	$sql = "SELECT dj_id,trib_id,trib_nom,rubro_id,rubro_nom,tipo,tcalculo,tcalculo_nom,base,cant,tunidad,alicuota,minimo,monto,ctacte_id,est " .
    			"FROM v_dj_rubro WHERE dj_id = " . $id;

    	$dataProvider = new SqlDataProvider([

		 	'sql' => $sql,
		 	'key' => 'dj_id',

        ]);

        return $dataProvider;

    }

    /**
	 * Función que obtiene los datos de Liquidación de una DDJJ
	 * @param integer $id Id de la DDJJ
	 * @return dataProvider Resultado
	 */
	public function getDatosGrillaLiq($id)
	{
		if ($id == '') $id = 0;

    	$sql = "SELECT dj_id,item_id,item_nom,item_monto,ctacte_id,est " .
    			"FROM v_dj_liq WHERE dj_id = " . $id;

    	$dataProvider = new SqlDataProvider([
		 	'sql' => $sql,
		 	'key' => 'dj_id',

        ]);

        return $dataProvider;
	}

	/**
	 * Función que obtiene los datos de Anticipo de una DDJJ
	 * @param integer $id Id de la DDJJ
	 * @return dataProvider Resultado
	 */
	public function getDatosGrillaAnt($id)
	{
		if ($id == '') $id = 0;

    	$sql = "SELECT dj_id,cuota,base,monto " .
    			"FROM v_dj WHERE dj_id = " . $id;

    	$dataProvider = new SqlDataProvider([
		 	'sql' => $sql,
		 	'key' => 'dj_id',

        ]);

        return $dataProvider;
	}

	/**
	 * Función que se ejecuta cuando se cargan los datos del modelo.
	 * Carga los datos que se obtienen de la vista de DDJJ.
	 */
	public function obtenerDatosVistaDJ($id)
	{
		if ($id == '') $id = 0;

		$sql = "SELECT obj_nom,trib_nom,to_char(fchvenc,'dd/MM/yyyy') as fchvenc,to_char(fchpresenta,'dd/MM/yyyy') as fchpresenta,orden_nom,tipo_nom " .
			"FROM v_dj WHERE dj_id = " . $id;

		$data = Yii::$app->db->createCommand($sql)->queryAll();

		$this->trib_nom = $data[0]['trib_nom'];
		$this->obj_nom = $data[0]['obj_nom'];
		$this->fchvenc = $data[0]['fchvenc'];
		$this->fchpresenta = $data[0]['fchpresenta'];
		$this->orden_nom = $data[0]['orden_nom'];
		$this->tipo_nom = $data[0]['tipo_nom'];

		//Actualizar el valor de est
		if ($this->est == 'A')	$this->estado = 'Activa';
		if ($this->est == 'B')	$this->estado = 'Baja';
		if ($this->est == 'R')	$this->estado = 'Rectificada';


	}

	/**
	 * Función que se ejecuta para buscar la última DJ de un ID de objeto dado
	 * @param string $id Identificador del objeto
	 * @return integer Id de la última DJ para el objeto ingresado.
	 */
	public function getIDFromObjeto($obj_id)
	{
		$sql = "SELECT dj_id FROM ddjj WHERE obj_id = '" . $obj_id . "' ORDER BY trib_id, anio*1000+cuota DESC limit 1";

		$id = Yii::$app->db->createCommand($sql)->queryScalar();

		return $id;
	}

	/**
	 * Función que se utiliza para obtener el ctacte_id de un período.
	 */
	public function getCtacteID( $trib_id, $obj_id, $anio, $cuota ){

		$sql = 	"SELECT ctacte_id FROM ddjj WHERE trib_id = " . $trib_id . " AND obj_id = '" . $obj_id . "' AND " .
				"(anio*1000+cuota) = (" . $anio . "*1000+" . $cuota . ")";

		return Yii::$app->db->createCommand( $sql )->queryScalar();
	}

	/**
	 * Función que obtiene la fecha de vencimiento al cargar una DDJJ
	 * @param integer $trib_id Identificador del tributo
	 * @param integer $anio Año
	 * @param integer $cuota Cuota
	 * @param string $obj_id Identificador del objeto
	 * @return string Fecha de vencimiento calculada
	 */
	public function getFechaVenc($trib_id,$anio,$cuota,$obj_id)
	{
		//Busco el vencimiento
        $sql = "Select coalesce(FchVenc1,'1900/01/01') From sam.Uf_Trib_Venc(";
        $sql .= $trib_id . "," . $anio . "," . $cuota . ",'" . $obj_id . "')";

        $fecha = Yii::$app->db->createCommand($sql)->queryScalar();

        return Fecha::BDToUsuario($fecha);
	}

	/**
	 * Función que verifica si se adeudan DDJJ.
	 * @param integer $trib_id Identificador del tributo
	 * @param integer $anio Año
	 * @param integer $cuota Cuota
	 * @param string $obj_id Identificador del objeto
	 */
	public function adeudaDJ( $trib, $anio, $cuota, $obj_id )
	{
		$sql = 	"SELECT COUNT(*) FROM ctacte c WHERE " .
				"c.trib_id = " . $trib . " AND c.obj_id = '" . $obj_id . "' AND " .
				"(c.anio*1000+c.cuota) < (" . $anio . "*1000+" . $cuota . ") AND c.est = 'P'";

		return Yii::$app->db->createCommand($sql)->queryScalar();

	}

	/**
	 * Función que verifica si no existe una DDJJ en la BD con el obj_id y periodo ingresado
	 * @param integer $trib_id Identificador del tributo
	 * @param integer $anio Año
	 * @param integer $cuota Cuota
	 * @param string $obj_id Identificador del objeto
	 * @param integer $fiscaliza
	 * @return string Fecha de vencimiento calculada
	 */
	public function existePeriodo($trib,$anio,$cuota,$obj_id,$fiscaliza)
	{
		//Obtengo subcta
		$subcta = utb::getCampo('trib','trib_id = ' . $trib,'uso_subcta');

		$sql = "SELECT exists (Select 1 From DDJJ Where Trib_id = " . $trib . " AND obj_id = '" . $obj_id . "' ";

		//Si subcta es distinto a 0, pregunto por subcta
		if ($subcta != 0)
			$sql .= "AND subcta = " . $subcta . " ";

		$sql .= "AND anio = " . $anio . " AND cuota = " . $cuota . " AND fiscaliza = " . $fiscaliza . " AND est = 'A')";

		return Yii::$app->db->createCommand($sql)->queryScalar();

	}

    /**
     * Función que se utiliza para generar la grilla con los rubros y
     * tributos del comercio seleccionado.
	 * @param integer $trib_id Identificador de tributo
	 * @param integer $obj_id Identificador del objeto
     * @param integer $subcta
     * @param integer $fiscaliza
	 * @param integer $anio Año
	 * @param integer $cuota Cuota
	 * @param string $fchpresenta Fecha de presentación
	 * @return string Fecha de vencimiento calculada
     */
    public function getRubrosAndTributos( $trib_id, $obj_id, $subcta, $fiscaliza, $anio, $cuota, $fchpresenta, $fchvencimiento, $arrayCodigo = [], $arrayBase = [], $arrayCant = [] )
    {
		//Inicializar variables
		$base = 0;
		$cant = 0;

        //Genero el arreglo que mantendrá los datos
        $data = [];

        //Obtener el tipo de objeto
        $tobj = utb::getTObj( $obj_id );

		//Obtener el nombre de tributo
		$trib_nom = utb::getCampo( 'trib', 'trib_id = ' . $trib_id, 'nombre_reduhbank' );

        //Obtengo subcta
        $subcta = utb::getCampo('trib','trib_id = ' . $trib_id,'uso_subcta');

        $sql = "SELECT rubro_id as cod, rubro_nom as nombre FROM sam.uf_objeto_rubro(".$trib_id.",'".$obj_id."',".$subcta.",".$fiscaliza.",".$anio.",".$cuota.")";

        $rubros = Yii::$app->db->createCommand( $sql )->queryAll();

        //Generar un arreglo formado por tributo y rubro
        foreach( $rubros as $rub )
        {
			$base = 0;
			$cant = 0;

            /*
			 *	Base y Cantidad se obtienen del arreglo en caso de existir, sino se pasa 0
			 *	La posición del arreglo se obtiene según el arreglo $arrayCodigo que contiene los ID
			 */

			if ( count( $arrayCodigo ) > 0 )
			{
				$clave = $trib_id.'-'.$rub['cod'];

				foreach ( $arrayCodigo as $valor => $array )
				{
					if ( $array == $clave )
					{
						//Obtener base y cantidad
						$base = $arrayBase[$valor];
						$cant = $arrayCant[$valor];
					}
				}
			}

            //Realizo el cálculo del rubro - Base y Cantidad se cargan con 0
    		$array = $this->calculaRubro($rub['cod'],$base,$cant,$anio,$cuota,$fchpresenta,$obj_id,$subcta);

            //Obtengo los datos de la consulta
            $formCalculo = $array['dataRubro']['tcalculo_nom'];	//Fórmula de Cálculo
            $tminimo = $array['dataRubro']['tminimo'];
            $alicuota = ($array['dataRubro']['alicuota']) * 100;
            $minimo = $array['dataRubro']['minimo'];
            $monto = $array['dataMonto']['montorubro'];
            $monto = number_format( round($monto * 100) / 100 , 2, '.', '' );

            $data[$trib_id.'-'.$rub['cod']] = [
                'trib_id' => $trib_id,
                'trib_nom' => $trib_nom,
                'rubro_id' => $rub['cod'],
                'rubro_nom' => $rub['nombre'],
                'formCalculo' => $array['dataRubro']['tcalculo_nom'],	//Fórmula de Cálculo
                'base' => number_format( $base, 2, '.', '' ),
                'cant' => $cant,
                'tminimo' => $array['dataRubro']['tminimo'],
                'alicuota' => ($array['dataRubro']['alicuota']) * 100,
                'minimo' => $array['dataRubro']['minimo'],
                'monto' => $array['dataMonto']['montorubro'],
                'monto' => number_format( round($monto * 100) / 100 , 2, '.', '' ),
            ];
        }

        return $data;

    }

	/**
	 * Función que realiza el cálculo de alícuota, mínimo y monto cuando se necesita
	 * agregar un nuevo rubro.
	 * @param integer $rubro_id Id del rubro
	 * @param double $base
	 * @param integer $cant Cantidad
	 * @param integer $anio Año
	 * @param integer $cuota Cuota
	 * @param string $fchpresenta Fecha de presentación
	 * @param string $obj_id Id de objeto
	 * @param integer $subcta Sub cuenta
	 *
	 */
	public function calculaRubro($rubro_id,$base,$cant,$anio,$cuota,$fchpresenta,$obj_id,$subcta)
	{
		if ( $subcta > 0 )
		{
			//Buscar PI
			$pi = $this->getTipoRubro($obj_id,$subcta);

		} else
		{
			$pi = 0;
		}

		//Sentencia para calcular el rubro
		$sql = "Select * From sam.Uf_DJ_Calcular_Rubro(" . $rubro_id . "," . $anio . "," . $cuota .
    	       "," . $base . "," . $cant . "," . $pi . ",0,0,'" . $obj_id . "','" . $fchpresenta . "')";

    	$dataRubro = Yii::$app->db->createCommand($sql)->queryAll();

        $sql = "Select * From sam.Uf_DJ_Calcular_Monto('" . $obj_id . "'," . $subcta . "," . $rubro_id;
        $sql .= "," . $anio . "," . $cuota . "," . $dataRubro[0]['minimo'];
        $sql .= "," . $dataRubro[0]['fijo'] . "," . $dataRubro[0]['tminimo'] . "," . $dataRubro[0]['tcalculo'];
        $sql .= "," . $cant . "," . $dataRubro[0]['monto'] . ")";
		$dataMonto = Yii::$app->db->createCommand($sql)->queryAll();

		$array = [	'dataRubro' => $dataRubro[0],
					'dataMonto' => $dataMonto[0]
				];

		return $array;

	}

	/**
	 * Función que se encarga de insertar los datos de la DJ en la BD
	 * @param integer $trib_id Identificador de tributo
	 * @param string $obj_id Identificador de objeto
	 * @param integer $subcta Sub cuenta
	 * @param integer $fiscaliza
	 * @param integer $anio Año
	 * @param integer $cuota Cuota
	 * @param integer $tipo
	 * @param string $fchpresenta Fecha de presentación
	 * @param integer $DJRub_id
	 */
	public function grabarDJ($trib_id,$obj_id,$subcta,$fiscaliza,$anio,$cuota,$tipo,$fchpresenta,$DJRub_id)
	{

    	if ($subcta == null || $subcta < 0)
    		$subcta = 0;

    	//Rete = 0.
    	$orden = $this->getOrden($trib_id,$obj_id,$anio,$cuota);

        $sql = "Select sam.uf_DJ_Generar(" . $trib_id . ",'" . $obj_id . "'," . $subcta . ",";
        $sql .= $fiscaliza . "," . $anio . "," . $cuota . "," . $orden . "," . $tipo . ",'";
        $sql .= Fecha::usuarioToBD($fchpresenta) . "',false," . Yii::$app->user->id . "," . $DJRub_id . ",1)";

		$transaction = Yii::$app->db->beginTransaction();

        try
        {
			//Verificar si existe una DJ para ese período. En caso afirmativo, se debe quitar la retención asociada a la misma.
			if( $this->existePeriodo($trib_id,$anio,$cuota,$obj_id,$fiscaliza) ){

				$this->quitarRetenciones( $this->getCtacteID( $trib_id, $obj_id, $anio, $cuota ) );

			}

        	$resultado = Yii::$app->db->createCommand($sql)->queryScalar();

			$transaction->commit();

        	return $resultado;

        } catch (Exception $e)
        {
			$transaction->rollback();
        	return "";
        }

	}

	/**
	 * Función que se encarga de eliminar una DJ
	 */
	public function eliminarDJ()
	{
		if ( $this->est == 'B'){

			return "La Declaración Jurada ya fue anulada con anterioridad";
		}

		if ($this->est == 'A')
		{
				$sql = "Select count(*) From CtaCte Where CtaCte_Id = " . $this->ctacte_id . " and Est = 'P'";

				$count = Yii::$app->db->createCommand($sql)->queryScalar();

				if ($count > 0)
					return "La DJ se encuentra Paga.<br />Deberá efectuar un movimiento de Pago o DJ Rectificativa.";
				else
				{
					$transaction = Yii::$app->db->beginTransaction();

					//try{

						$sql = "Select sam.uf_DJ_Borrar(" . $this->dj_id . ")";

						$res = Yii::$app->db->createCommand($sql)->queryScalar();

						$this->quitarRetenciones( $this->ctacte_id );

						if ( $res == 'OK' ){
							$transaction->commit();
							return '';
						} else {
							$transaction->rollback();
							return 'Error';
						}

				//} catch(\Exception $e ){

					$transaction->rollback();
					return DBException::getMensaje( $e );
				//}
			}
		} else
		{
			return 'El estado de la DJ no es correcto.';
		}
	}

	public function getOrden($trib_id,$obj_id,$anio,$cuota)
	{
        //Busco el máximo orden correspondiente según parámetros
        $sql = "Select Coalesce(max(Orden),-1) From DDJJ Where Trib_id=" . $trib_id;
        $sql .= " and Obj_id='" . $obj_id . "' And Anio=" . $anio . " And Cuota=" . $cuota . " and Est<>'B'";

        $orden = Yii::$app->db->createCommand($sql)->queryScalar();
        $orden++;

        return $orden;
	}

	/**
	 * Función que se utiliza para obtener el siguiente ID de una DDJJ.
	 */
	public function getNextDjRub_id()
	{
		return Yii::$app->db->createCommand("select nextval('temp.seq_ddjj_rubros')")->queryScalar();
	}

	/**
	 * Función que se encarga de eliminar todos los datos que haya en la tabla TEMP correspondientes
	 * a la DJ que se está armando.
	 * @param integer
	 */
	public function borrarRubrosTemp($id)
	{
		if ($id != '')
		{
			$sql = "DELETE FROM temp.ddjj_rubros WHERE djrub_id = " . $id;

			Yii::$app->db->createCommand($sql)->execute();
		}

	}

	/**
	 * Función que se encarga de grabar en la tabla TEMP de rubros los datos que se encuentran en memoria
	 * @param integer $djRub_id Identificador que se usa en la tabla TEMP
	 */
	public function grabarRubrosTemp( $djRub_id, $arreglo )
	{

		try
		{
			foreach ($arreglo as $array)
			{
				$sql = "Insert Into temp.DDJJ_Rubros Values (" . $djRub_id . ",";
	            $sql .= $array['rubro_id'] . "," . $array['base'] . ",";
	            $sql .= $array['cant'] . "," . $array['minimo'] . ",";
	            $sql .= $array['alicuota'] . "," . $array['monto'] . ")";

	            Yii::$app->db->createCommand($sql)->execute();

			}

			return '';

		} catch (\Exception $e)
		{
			return 'No se pudieron grabar los datos';
		}

	}

	/**
	 * Función que realiza el cálculo de la DJ
	 * @param integer $trib_id Identificador del tributo
	 * @param string $obj_id Identificador del objeto
	 * @param integer $fiscaliza Fiscaliza
	 * @param integer $anio Año
	 * @param integer $cuota Cuota
	 * @param integer $fchpresenta Fecha de presentación
	 * @param integer $aplicaBonificacion Indica si se aplica bonificación al contributyente.
	 */
	public function calcularDJ($trib_id,$obj_id,$fiscaliza,$anio,$cuota,$fchpresenta,$djRub_id, $aplicaBonificacion = 0 )
	{
		 $orden = $this->getOrden($trib_id,$obj_id,$anio,$cuota);

	     $sql = "Select * From sam.uf_DJ_Calcular(" . $trib_id . ",'" . $obj_id . "'," . $fiscaliza . ",";
		 $sql .= $anio . "," . $cuota . "," . $orden . "," . Fecha::usuarioToBD( $fchpresenta, 2 ) . "::date," . $djRub_id . "," . intVal( $aplicaBonificacion ). ")";

		 return Yii::$app->db->createCommand($sql)->queryAll();
	}

	/**
	 * Función que se utiliza para buscar las DJ de un objeto para comparativa
	 * @param integer $trib_id Identificador del tributo
	 * @param integer $obj_id Identificador de objeto
	 * @param string $est Estado de la DJ
	 * @param boolean $oficio
	 * @param integer $fiscaliza Variable que determina si se cargan las declaraciones juradas de fiscalización.
	 */
	public function buscarListObj($trib_id = 0,$obj_id,$est,$oficio = false,$fiscaliza = 0)
	{

		//$est = substr($est,0,1);

		$sql = "Select dj_id,subcta,Anio,Cuota,Orden_nom,Fiscaliza,Est,Tipo_nom,Base,Monto,Multa,to_char(FchPresenta,'dd/MM/yyyy') as fchpresenta,EstCtaCte ";
		$sql .= "From V_DJ Where obj_id='" . $obj_id . "'";

		if($trib_id != 0)
			$sql .= " AND trib_id=" . $trib_id;

		if ($est != '0' && $est != '')
			$sql .= " AND est='" . $est . "'";

		//Buscar las DJ según tipo
		$sql .= " AND tipo IN (1";

		if ($oficio)
			$sql .= ",2";

		if ($fiscaliza == 1)
			$sql .= ",3";

		$sql .= ")";

        $sql .= " Order By Anio Desc, Cuota Desc, Orden Desc, Fiscaliza ";

        $dataProvider = new SqlDataProvider([
		 	'sql' => $sql,
		 	'key' => 'dj_id',


        ]);
        return $dataProvider;

	}

	/**
	 * Función que busca los datos de la BD para la lista avanzada de DJ.
	 * @param string $cond Condición de búsqueda
	 * @param string $orden Orden de la búsqueda
	 * @return dataProvider DataProvider con el resultado de la búsqueda
	 */
    public function buscaDatosListado($cond,$orden)
    {
    	$sql = "SELECT dj_id,trib_nom,obj_id,subcta,num_nom,est,anio,cuota,orden_nom,base,monto,multa,to_char(fchpresenta,'dd/MM/yyyy') as fchpresenta " .
    			"FROM v_dj WHERE " . $cond . " ORDER BY " . $orden;

    	$count = Yii::$app->db->createCommand("SELECT COUNT(*) FROM v_dj WHERE " . $cond)->queryScalar();

    	$dataProvider = new SqlDataProvider([
		 	'sql' => $sql,
		 	'key' => 'dj_id',
		 	'totalCount' => (int)$count,
		 	'pagination'=> [
				'pageSize'=>20,
			],
        ]);

        return $dataProvider;
    }

	public function getTipoRubro($obj_id,$subcta)
	{
		return Yii::$app->db->createCommand("Select PI from comer_suc where Obj_id='" . $obj_id . "' and SubCta=" . $subcta)->queryScalar();
	}

	/**
	 * Función que obtiene el tipo de rubro según el objeto
	 */
	public function getTipoRubroObjeto($obj_id,$rubro_id)
	{
		return Yii::$app->db->createCommand("Select tipo from objeto_rubro where Obj_id='" . $obj_id . "' and rubro_id=" . $rubro_id)->queryScalar();
	}

	public function Imprimir($id,&$sub1,&$sub2,&$sub3)
    {
    	$ctacte_id = Yii::$app->db->createCommand("Select ctacte_id From ddjj where dj_id=".$id)->queryScalar();

    	$sql = "Select *,to_char(fchemi,'dd/mm/yyyy') fchemi,to_char(venc1,'dd/mm/yyyy') venc1,to_char(venc2,'dd/mm/yyyy') venc2 From V_Emision_Print Where CtaCte_Id=".$ctacte_id." and Dj_Id=".$id;
   		$array = Yii::$app->db->createCommand($sql)->queryAll();

   		$sql = "Select * From V_DJ_Liq Where DJ_id=".$id;
   		$sub1 = Yii::$app->db->createCommand($sql)->queryAll();

   		if ($array[0]['tobj'] == 2){
   			$sql = "Select *,to_char(fchhab,'dd/MM/yyyy') fchhab From V_Comer_suc Where Obj_id='".$array[0]['obj_id']."'";
   			if ($array[0]['subcta'] != 0) $sql .= " and subcta=".$array[0]['subcta'];
   		}else {
   			$sql = "Select * From V_Persona Where Obj_id='".$array[0]['obj_id']."'";
   		}

   		$sub2 = Yii::$app->db->createCommand($sql)->queryAll();

   		if ($array[0]['trib_tipo'] == 2){
   			$sql = "Select * From V_DJ_Rubro Where DJ_id=".$id;
   			$sub3 = Yii::$app->db->createCommand($sql)->queryAll();
   		}else {
   			$sub3 = null;
   		}

   		return $array;
    }

	/**
	 * Función que se utiliza para obtener la base y el monto de una DJ.
	 * @param integer $dj_id Identificador de la DJ.
	 * @return array Arreglo con la base y el monto finales.
	 */
	public function getTotalesDJ( $dj_id )
	{
		//INICIO Calculo y Seteo los valores para base y monto
		$array = $this->getDatosGrillaRubros( $dj_id )->getModels();

		$baseTotal = 0;
		$montoTotal = 0;

		if (count($array > 0))
		{
			//Calculo Base
			foreach ($array as $rubros)
			{
				$baseTotal += $rubros['base'];
			}

			//Calculo Monto
			$array = $this->getDatosGrillaLiq( $dj_id )->getModels();
			foreach ($array as $rubros)
			{
				$montoTotal += $rubros['item_monto'];
			}
		}

		return [
			'base' => number_format( $baseTotal, 2, '.', '' ),
			'monto' => number_format( $montoTotal, 2, '.', '' ),
		];
	}

	/**
	 * Función que se utiliza para devolver los datos de configuración de DDJJ.
	 */
	public static function getConfig_ddjj(){

		return utb::samConfig_ddjj();
	}

	//---------------------------------------RETENCIONES------------------------------------------------//

	/**
	 * Función que obtiene las retenciones asociadas a un objeto.
	 * @param string $obj_id Identificador del objeto.
	 * @param integer $anio Año
	 * @param integer $mes Mes
	 */
	public function getRetenciones( $obj_id, $anio, $mes )
	{
		$periodo = intval( ( $anio * 1000 ) + $mes );

		$sql = 	"SELECT ret_id, numero, anio, mes, to_char(fecha, 'dd/MM/yyyy') as fecha, lugar, tcomprob, comprob, base, monto " .
				"FROM v_ret_det " .
				"WHERE obj_id = '" . $obj_id . "' AND (anio * 1000) + mes <= " . $periodo . " AND est = 'P' ";

		return Yii::$app->db->createCommand( $sql )->queryAll();
	}

	/**
	 * Función que obtiene las retenciones asociadas a una DJ.
	 */
	public function getRetencionesAsociadasADJ( $ctacte_id = 0 )
	{

		$sql = 	"SELECT ret_id, numero, anio, mes, to_char(fecha, 'dd/MM/yyyy') as fecha, lugar, tcomprob, comprob, base, monto " .
				"FROM v_ret_det " .
				"WHERE ctacte_id = " . $ctacte_id . " AND est = 'I' ";

		return Yii::$app->db->createCommand( $sql )->queryAll();
	}

	/**
	 * Función que se utiliza para asociar las retencioens a una DJ.
	 * @param array Retenciones Arreglo con el ID de las retenciones.
	 * @param integer $dj_id Identificador de la DJ.
	 */
	public function grabarRetenciones( $retenciones = [], $dj_id ){

		$montoRetenciones = 0;

		//Obtener la suma de los montos de las retenciones seleccionadas.
		foreach( $retenciones as $dato )
		{
			$sql = "SELECT monto FROM v_ret_det WHERE ret_id = " . $dato;

			$montoRetenciones += floatVal( Yii::$app->db->createCommand( $sql )->queryScalar() );
		}

		//Obtener la suma de las retenciones cargadas para el ctacte_id
		$sql = "SELECT sum(monto) FROM ret_det WHERE ctacte_id = " . $this->ctacte_id;

		$montoRetenciones += floatVal( Yii::$app->db->createCommand( $sql )->queryScalar() );

		//Obtener el monto de la DJ.
		$montoDJ = floatVal( $this->getTotalesDJ( $dj_id )['monto'] );

		//return $montoRetenciones;
		if( $montoRetenciones > $montoDJ )
			return 2;

		$transaction = Yii::$app->db->beginTransaction();

		try{

			//Actualizar los datos en la tabla ret_det
			foreach( $retenciones as $dato ){

				$sql = 	"UPDATE ret_det SET fchaplic = CURRENT_TIMESTAMP, est = 'I', ctacte_id = " . $this->ctacte_id .
						" WHERE ret_id = " . $dato;

				Yii::$app->db->createCommand( $sql )->execute();
			}

			$transaction->commit();

		} catch( \Exception $e ){

			$transaction->rollback();

			return 3;
		}

		return 1;

	}

	/**
	 * Función que se utiliza para desvincular las retenciones a una DJ.
	 * @param integer $ctacte_id Identificador de cuenta corriente.
	 */
	public function quitarRetenciones( $ctacte_id ){

		$sql = "UPDATE ret_det SET fchaplic = null, est = 'P', ctacte_id = 0 WHERE ctacte_id = " . $ctacte_id;

		Yii::$app->db->createCommand( $sql )->execute();
	}

	/**
	 * Función que se utiliza para grabar los datos de una nueva retención.
	 */
	public function grabarNuevaRetencion(){

		//$sql = "INSERT INTO ret_det ()"
	}

}
