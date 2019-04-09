<?php

namespace app\models\ctacte;

use Yii;
use yii\data\SqlDataProvider;
use app\utils\db\utb;
use app\utils\db\Fecha;

/**
 * This is the model class for table "comp".
 *
 * @property integer $comp_id
 * @property string $expe
 * @property integer $tipo
 * @property integer $aplic_num
 * @property string $fchalta
 * @property string $fchaplic
 * @property string $fchconsolida
 * @property string $fchbaja
 * @property integer $trib_ori
 * @property string $obj_ori
 * @property integer $trib_dest
 * @property string $obj_dest
 * @property string $monto
 * @property string $monto_aplic
 * @property string $est
 * @property string $obs
 * @property string $fchmod
 * @property integer $usrmod
 */


 /**
  * Estos son los datos complementarios a los del modelo de comp
  * @property string $est_nom
  * @property string $tipo_nom
  * @property double $saldo
  * @property string $trib_ori_nom
  * @property string $trib_dest_nom
  * @property string $obj_ori_nom
  * @property string $obj_dest_nom
  * @property integer plan_origen
  * @property integer aniodesde_origen
  * @property integer cuotadesde_origen
  * @property integer aniohasta_origen
  * @property integer cuotahasta_origen
  * @property integer plan_destino
  * @property integer aniodesde_destino
  * @property integer cuotadesde_destino
  * @property integer aniohasta_destino
  * @property integer cuotahasta_destino
  */
class Comp extends \yii\db\ActiveRecord
{
	public $est_nom;
    public $tipo_nom;
    public $saldo;
 	public $trib_ori_nom;
    public $trib_dest_nom;
    public $obj_ori;
	public $obj_des;
	public $obj_ori_nom;
	public $obj_dest_nom;
	public $plan_origen;
	public $aniodesde_origen;
	public $cuotadesde_origen;
	public $aniohasta_origen;
	public $cuotahasta_origen;
	public $plan_destino;
	public $aniodesde_destino;
	public $cuotadesde_destino;
	public $aniohasta_destino;
	public $cuotahasta_destino;
 	public $dest_aplica;
 	public $dest_periodo;
 	public $dest_anual;
 	public $dest_semestral;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tipo', 'aplic_num', 'fchalta', 'monto', 'monto_aplic', 'est', 'obs'], 'required'],
            [['tipo', 'aplic_num', 'trib_ori', 'trib_dest'], 'integer'],
            [['fchalta', 'fchaplic', 'fchconsolida', 'fchbaja'], 'safe'],
            [['monto', 'monto_aplic'], 'number'],
            [['expe'], 'string', 'max' => 12],
            [['obj_ori', 'obj_dest'], 'string', 'max' => 8],
            [['est'], 'string', 'max' => 1],
            [['obs'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'comp_id' => 'Identificador de compensación',
            'expe' => 'Expediente',
            'tipo' => 'Codigo de tipo de retencion',
            'aplic_num' => 'Si se aplica al num - responsable ',
            'fchalta' => 'Fecha de alta',
            'fchaplic' => 'Fecha de aplicacion',
            'fchconsolida' => 'Fecha de consolidacion',
            'fchbaja' => 'Fecha de baja',
            'trib_ori' => 'Codigo de tributo origen',
            'obj_ori' => 'Codigo de objeto origen',
            'trib_dest' => 'Codigo de tributo destino',
            'obj_dest' => 'Codigo de objeto destino',
            'monto' => 'Monto',
            'monto_aplic' => 'Monto aplicado',
            'est' => 'Estado',
            'obs' => 'Observaciones',
            'fchmod' => 'Fecha de modificacion',
            'usrmod' => 'Codigo de usuario que modifico',
        ];
    }

    public function __construct()
    {
    	$this->dest_aplica = false;
	 	$this->dest_periodo = true;
	 	$this->dest_anual = false;
	 	$this->dest_semestral = false;
    }

    public function cargarDatos($id)
    {
    	$sql = "SELECT est_nom,tipo_nom,saldo,trib_ori_nom,trib_dest_nom,obj_ori,obj_ori_nom,obj_dest_nom," .
    			"to_char(fchalta,'dd/MM/yyyy') as fchalta,to_char(fchaplic,'dd/MM/yyyy') as fchaplic," .
    			"to_char(fchconsolida,'dd/MM/yyyy') as fchconsolida,to_char(fchbaja,'dd/MM/yyyy') as fchbaja," .
    			"to_char(fchmod,'dd/MM/yyyy') as fchmod " .
    			"FROM v_comp " .
    			"WHERE comp_id = " . $id;

    	$data = Yii::$app->db->createCommand($sql)->queryAll();

    	$this->est_nom = $data[0]['est_nom'];
    	$this->tipo_nom = $data[0]['tipo_nom'];
    	$this->saldo = $data[0]['saldo'];
    	$this->trib_ori_nom = $data[0]['trib_ori_nom'];
    	$this->trib_dest_nom = $data[0]['trib_dest_nom'];
    	$this->obj_ori = $data[0]['obj_ori'];
    	$this->obj_ori_nom = $data[0]['obj_ori_nom'];
    	$this->obj_dest_nom = $data[0]['obj_dest_nom'];
    	$this->fchalta = $data[0]['fchalta'];
 		$this->fchaplic = $data[0]['fchaplic'];
 		$this->fchconsolida = $data[0]['fchconsolida'];
 		$this->fchbaja = $data[0]['fchbaja'];
 		$this->fchmod = $data[0]['fchmod'];
		$this->plan_origen = '';
		$this->aniodesde_origen = '';
		$this->cuotadesde_origen = '';
		$this->aniohasta_origen = '';
		$this->cuotahasta_origen = '';
		$this->plan_destino = '';
		$this->aniodesde_destino = '';
		$this->cuotadesde_destino = '';
		$this->aniohasta_destino = '';
		$this->cuotahasta_destino = '';
	 	$this->dest_aplica = false;
	 	$this->dest_periodo = true;
	 	$this->dest_anual = false;
	 	$this->dest_semestral = false;

    }

    /**
     * Función que obtiene los datos de Origen de una compensación
     * @param integer $id Identificador de la compensación
     * @return dataProvider con el resultado de la consulta
     */
    public function cargarDatosOrigen($id)
    {
    	$sql = "SELECT ctacte_id,trib_nom,obj_id,subcta,anio,cuota,saldo,saldo_cub " .
    			"FROM v_comp_saldo " .
    			"WHERE comp_id = " . $id . " " .
    			"ORDER BY obj_id,subcta,anio,cuota";

 		$dataProvider = new SqlDataProvider([
		 	'sql' => $sql,
        ]);

    	return $dataProvider;
    }

    /**
     * Función que obtiene los datos de Destino de una compensación.
     * @param integer $id Identificador de la compensación.
     * @return dataProvider con el resultado de la consulta.
     */
    public function cargarDatosDestino($id)
    {
    	$sql = "SELECT obj_id,subcta,trib_nom,anio,cuota,montoaplic,to_char(fecha,'dd/MM/yyyy') as fecha,ctacte_id " .
    			"FROM v_comp_aplic " .
    			"WHERE comp_id = " . $id . " " .
    			"ORDER BY obj_id,subcta,trib_nom,anio,cuota";

 		$dataProvider = new SqlDataProvider([
		 	'sql' => $sql,
        ]);
    	return $dataProvider;
    }

    /**
	 * Función que busca los datos de la BD para la lista avanzada de compensación.
	 * @param string $cond Condición de búsqueda
	 * @param string $orden Orden de la búsqueda
	 * @return dataProvider DataProvider con el resultado de la búsqueda
	 */
    public function buscaDatosListado($cond,$orden)
    {
    	$sql = "SELECT comp_id,tipo_nom,trib_ori_nom,obj_ori,trib_dest_nom,obj_dest,monto,monto_aplic,saldo,est_nom " .
    			"FROM v_comp " .
    			" WHERE " . $cond . " ORDER BY " . $orden;

    	$count = Yii::$app->db->createCommand("SELECT COUNT(*) FROM v_comp WHERE " . $cond)->queryScalar();

    	$dataProvider = new SqlDataProvider([
		 	'sql' => $sql,
		 	'key' => 'comp_id',
		 	'totalCount' => (int)$count,
		 	'pagination'=> [
				'pageSize'=>50,
			],
        ]);

        return $dataProvider;
    }

    /**
     * Función que obtiene una lista de saldos por períodos.
     * @param string $obj_id Identificador del objeto.
     * @param integer $trib_id Identificador del tributo.
     * @param integer $plan_id Identificador del plan.
     * @param integer $aniodesde Año para Período desde.
     * @param integer $cuotadesde Cuota para Período desde.
     * @param integer $aniohasta Año para Período hasta.
     * @param integer $cuotahasta Cuota para Período hasta.
     */
    public function buscarPerSaldos($obj_id,$trib_id,$plan_id = 0,$aniodesde,$cuotadesde,$aniohasta,$cuotahasta)
    {
    	$sql = '';

    	$this->aniodesde_origen = $aniodesde;
		$this->cuotadesde_origen = $cuotadesde;
		$this->aniohasta_origen = $aniohasta;
		$this->cuotahasta_origen = $cuotahasta;

		//Cargo variables para mantenerlas en el modelo
		$this->obj_ori = $obj_id;
		$this->obj_ori_nom =  utb::getNombObj("'".$this->obj_ori."'");
		$this->trib_ori = $trib_id;

		$this->plan_origen = $plan_id;

    	//Si no se ingresaron datos para período, los seteo
    	if ($this->aniodesde_origen == '' || $this->cuotadesde_origen == '' || $this->aniohasta_origen == '' || $this->cuotahasta_origen == '')
    	{
    		$config = utb::samConfig();
    		$this->aniodesde_origen = date('Y') - $config['ctacte_anio_desde'];
    		$this->cuotadesde_origen = 1;
    		$this->aniohasta_origen = date('Y');
    		$this->cuotahasta_origen = 999;
    	}

    	$perdesde = ($this->aniodesde_origen * 1000) + $this->cuotadesde_origen;
    	$perhasta = ($this->aniohasta_origen * 1000) + $this->cuotahasta_origen;

    	//Si $trib_id IN (1,2,3)
    	if ($trib_id == 1)
    		$sql = "Select obj_id from plan where plan_id=" . $this->plan_origen;
    	else if ($trib_id == 2)
    		$sql = "Select obj_id from facilida where faci_id=" . $this->plan_origen;
    	else if ($trib_id == 3)
    		$sql = "Select obj_id from mej_plan where plan_id=" . $this->plan_origen;

    	if ($sql != '')
    		$this->obj_ori = Yii::$app->db->createCommand($sql)->queryScalar();

        $sql = "Select t.ctacte_id, t.anio, t.cuota, t.nominal, abs(t.saldo) saldo From sam.uf_ctacte_tributo(";
        $sql .= $this->trib_ori . ", " . $this->plan_origen . ",'" . $this->obj_ori . "', 0, current_date," . $perdesde . ",";
        $sql .= $perhasta . ") t ";
        $sql .= "left join comp_saldo s on t.ctacte_id = s.ctacte_id ";
        $sql .= "left join comp r on s.comp_id = r.comp_id ";
        $sql .= "WHERE t.est in ('E','P','N') and t.saldo < 0 and (s.ctacte_id is null or r.est = 'B') group by t.ctacte_id, t.anio, t.cuota, t.nominal, t.saldo Order By anio, cuota";

    	$dataProvider = new SqlDataProvider([
		 	'sql' => $sql,
        ]);

        $monto = 0;

        $arreglo = $dataProvider->getModels();

        foreach ($arreglo as $array)
        {
        	$monto += intval($array['saldo']);
        }

        $datos = [	'dataProvider' => $dataProvider,
        			'monto' => $monto,
        		];

        return $datos;

    }

    /**
     * Función que obtiene una lista de Pagos por períodos.
     * @param string $obj_id Identificador del objeto.
     * @param integer $trib_id Identificador del tributo.
     * @param integer $plan_id Identificador del plan.
     * @param integer $aniodesde Año para Período desde.
     * @param integer $cuotadesde Cuota para Período desde.
     * @param integer $aniohasta Año para Período hasta.
     * @param integer $cuotahasta Cuota para Período hasta.
     */
    public function buscarPerPagos($obj_id,$trib_id,$plan_id = 0,$aniodesde,$cuotadesde,$aniohasta,$cuotahasta)
    {
    	$sql = '';

    	//Si no se ingresaron datos para período, los seteo
    	$this->aniodesde_origen = $aniodesde;
		$this->cuotadesde_origen = $cuotadesde;
		$this->aniohasta_origen = $aniohasta;
		$this->cuotahasta_origen = $cuotahasta;

		//Cargo variables para mantenerlas en el modelo
		$this->obj_ori = $obj_id;
		$this->obj_ori_nom =  utb::getNombObj("'".$this->obj_ori."'");
		$this->trib_ori = $trib_id;


		$this->plan_origen = $plan_id;

    	//Si no se ingresaron datos para período, los seteo
    	if ($this->aniodesde_origen == '' || $this->cuotadesde_origen == '' || $this->aniohasta_origen == '' || $this->cuotahasta_origen == '')
    	{
    		$config = utb::samConfig();
    		$this->aniodesde_origen = date('Y') - $config['ctacte_anio_desde'];
    		$this->cuotadesde_origen = 1;
    		$this->aniohasta_origen = date('Y');
    		$this->cuotahasta_origen = 999;
    	}

    	$perdesde = ($this->aniodesde_origen * 1000) + $this->cuotadesde_origen;
    	$perhasta = ($this->aniohasta_origen * 1000) + $this->cuotahasta_origen;

    	//Si $trib_id IN (1,2,3)
    	if ($trib_id == 1)
    		$sql = "Select obj_id from plan where plan_id=" . $this->plan_origen;
    	else if ($trib_id == 2)
    		$sql = "Select obj_id from facilida where faci_id=" . $this->plan_origen;
    	else if ($trib_id == 3)
    		$sql = "Select obj_id from mej_plan where plan_id=" . $this->plan_origen;

    	if ($sql != '')
    		$this->obj_ori = Yii::$app->db->createCommand($sql)->queryScalar();

    	$sql = "SELECT t.ctacte_id, anio, cuota, nominal, SUM(haber) saldo FROM sam.uf_ctacte_tributo(";
        $sql .= $this->trib_ori . ", " . $this->plan_origen . ",'" . $this->obj_ori . "', 0, current_date," . $perdesde . "," . $perhasta . ") t ";
        $sql .= "INNER JOIN ctacte_det d ON t.ctacte_id = d.ctacte_id AND d.topera IN (3,5,7,8,10,11,16,17) AND d.est = 'A' ";
        $sql .= "WHERE t.est IN ('P','E') GROUP BY t.ctacte_id, anio, cuota, nominal ORDER BY anio, cuota";

        $dataProvider = new SqlDataProvider([
		 	'sql' => $sql,
			'pagination' => ['pageSize' => 50000]
        ]);

        $monto = 0;

        $arreglo = $dataProvider->getModels();

        foreach ($arreglo as $array)
        {
        	$monto += intval($array['saldo']);
        }

        $datos = [	'dataProvider' => $dataProvider,
        			'monto' => $monto,
        		];

        return $datos;

    }

    /**
     * Función que obtiene una lista de periodos aplicados.
     * @param string $obj_id Identificador del objeto.
     * @param integer $trib_id Identificador del tributo.
     * @param integer $plan_id Identificador del plan.
     * @param string $fchconsolidacion Fecha de consolidación.
     * @param integer $aniodesde Año para Período desde.
     * @param integer $cuotadesde Cuota para Período desde.
     * @param integer $aniohasta Año para Período hasta.
     * @param integer $cuotahasta Cuota para Período hasta.
     * @param integer $anual Variable que determina si se obtienen períodos anuales.
     * @param integer $semestre Variable que determina si se obtienen períodos por semestre.
     * @param integer $aplica Aplica contribuyente
     */
    public function buscarPerAplica($obj_id,$trib_id,$plan_id,$fchconsolidacion,$aniodesde,$cuotadesde,$aniohasta,$cuotahasta,$anual,$semestre,$aplica)
    {
    	$sql = '';
    	$error = [];

    	//Si no se ingresaron datos para período, los seteo
    	$this->aniodesde_destino = $aniodesde;
		$this->cuotadesde_destino = $cuotadesde;
		$this->aniohasta_destino = $aniohasta;
		$this->cuotahasta_destino = $cuotahasta;

	 	$this->dest_aplica = $aplica == 1;
	 	$this->dest_periodo = $anual == 0 && $semestre == 0;
	 	$this->dest_anual = $anual == 1;
	 	$this->dest_semestral = $semestre == 1;

		//Cargo variables para mantenerlas en el modelo
		$this->obj_dest = $obj_id;
		$this->obj_dest_nom =  utb::getNombObj("'".$this->obj_dest."'");
		$this->trib_dest = $trib_id;


		$this->plan_destino = $plan_id;

    	//Si no se ingresaron datos para período, los seteo
    	if ( $this->aniodesde_destino == '' || $this->cuotadesde_destino == '' || $this->aniohasta_destino == '' || $this->cuotahasta_destino == '' )
    	{
    		$config = utb::samConfig();

    		$this->aniodesde_destino = date('Y') - $config['ctacte_anio_desde'];
    		$this->cuotadesde_destino = 1;
    		$this->aniohasta_destino = date('Y');
    		$this->cuotahasta_destino = 999;
    	}

    	$perdesde = ($this->aniodesde_destino * 1000) + $this->cuotadesde_destino;
    	$perhasta = ($this->aniohasta_destino * 1000) + $this->cuotahasta_destino;

    	//Si $this->trib_dest IN (1,2,3)
    	if ($this->trib_dest == 1)	//Si tributo == Plan
    	{
    		$sql = "Select obj_id from plan where plan_id=" . $this->plan_destino;

    		$this->obj_dest = Yii::$app->db->createCommand($sql)->queryScalar();

    		$sql = "Select ctacte_id, obj_id, subcta, trib_nom, trib_id, anio, cuota, saldo, to_char(FchVenc,'dd/mm/YYYY') venc ";
            $sql .= "From sam.uf_ctacte_objeto('" . $this->obj_dest . "','" . $fchconsolidacion . "'";
            $sql .= "," . $perdesde . ", " . $perhasta . ") Where est='D' and saldo>0 ";
            $sql .= "and trib_id=" . $this->trib_dest . " and anio=" . $this->plan_destino;
            $sql .= " Order By obj_id,anio,cuota ";

    	} else if ($this->trib_dest == 2)	//Si tributo == Facilidad
    	{
    		$sql = "Select obj_id from facilida where faci_id=" . $this->plan_destino;

    		$this->obj_dest = Yii::$app->db->createCommand($sql)->queryScalar();

    		$sql = "Select ctacte_id, obj_id, subcta, trib_nom, trib_id, anio, cuota, saldo, to_char(FchVenc,'dd/mm/YYYY') venc ";
            $sql .= "From sam.uf_ctacte_objeto('" . $this->obj_dest . "','" . $fchconsolidacion . "'";
            $sql .= "," . $perdesde . ", " . $perhasta . ") Where est='D' and saldo>0 ";
            $sql .= "and trib_id=" . $this->trib_dest . " and anio=" . $this->plan_destino;
            $sql .= " Order By obj_id,anio,cuota ";

    	} else if ($this->trib_dest == 3)	//Si tributo == Mejoras
    	{
    		$sql = "Select obj_id from mej_plan where plan_id=" . $this->plan_destino;

    		$this->obj_dest = Yii::$app->db->createCommand($sql)->queryScalar();

	        $sql = "Select ctacte_id, obj_id, subcta, trib_nom, trib_id, anio, cuota, saldo, to_char(FchVenc,'dd/mm/YYYY') venc ";
            $sql .= "From sam.uf_ctacte_objeto('" . $this->obj_dest . "','" . $fchconsolidacion . "'";
            $sql .= "," . $perdesde . ", " . $perhasta . ") Where est='D' and saldo>0 ";
            $sql .= "and trib_id=" . $this->trib_dest . " and anio=" . $this->plan_destino;
            $sql .= " Order By obj_id,anio,cuota ";

    	} else if ($semestre == 1)
    	{
    		if ($this->trib_dest == 0)
    			$error[] = "Seleccione el tributo para poder traer el monto del trimestre/semestre.";

    		$sql = "Select s.ctacte_id, c.obj_id, c.subcta, t.nombre_redu as trib_nom, c.trib_id, c.anio, s.cuota, s.montosem as saldo, to_char(s.FchVenc,'dd/mm/YYYY') venc ";
            $sql .= "from ctacte_sem s ";
            $sql .= "left join ctacte c on s.ctacte_id=c.ctacte_id ";
            $sql .= "left join trib t on c.trib_id=t.trib_id ";
            $sql .= "where c.trib_id=" . $this->trib_dest . " and c.obj_id='" . $this->obj_dest . "' and c.est='D' ";
            $sql .= "Order By c.obj_id,c.anio,c.cuota ";

    	} else if ($anual == 1)
    	{
    		if ($this->trib_dest == 0)
    			$error[] = "Seleccione el tributo para poder traer el monto anual.";

    		//Valido si no está vencido respecto de la fecha de consolidación
            $sql = "Select v.fchvencanual from trib_venc v ";
            $sql .= "where v.trib_id=" . $this->trib_dest . " and v.anio=" . date('Y',strtotime(Fecha::usuarioToBD($fchconsolidacion))) . " and v.cuota=1 ";

            $vencAnual = Yii::$app->db->createCommand($sql)->queryScalar();

            if (Fecha::menor($vencAnual,$fchconsolidacion,false))
               $error[] = "El Pago anual se encuentra vencido.";

            //Valido si están generados todos los períodos del año
            $sql = "Select iif(count(*)=coalesce(r.cant_anio,0), 1, 0) from ctacte c ";
            $sql .= "left join trib t on c.trib_id=t.trib_id left join resol r on t.trib_id=r.trib_id and c.anio*1000+c.cuota between r.perdesde and r.perhasta ";
            $sql .= "where c.trib_id=" . $this->trib_dest . " and c.obj_id='" . $this->obj_dest . "' and c.anio=" . date('Y',strtotime(Fecha::usuarioToBD($fchconsolidacion))) . " and c.est='D' ";
            $sql .= "group by r.cant_anio";

            $cantPer = Yii::$app->db->createCommand($sql)->queryScalar();

            if ($cantPer = 0)
               $error[] = "Falta generar períodos en el año: " . date('Y',$fchconsolidacion);

            $sql = "Select c.ctacte_id, c.obj_id, c.subcta, t.nombre_redu trib_nom, c.trib_id, c.anio, 0 cuota, montoanual saldo, to_char(v.fchvencanual,'dd/mm/YYYY') venc ";
            $sql .= "From ctacte c ";
            $sql .= "left join trib t on c.trib_id=t.trib_id ";
            $sql .= "left join trib_venc v on c.trib_id=v.trib_id and v.anio=" . date('Y',strtotime(Fecha::usuarioToBD($fchconsolidacion))) . " and v.cuota=1 ";
            $sql .= "Where c.trib_id=" . $this->trib_dest . " and c.obj_id='" . $this->obj_dest . "' and c.anio=" . date('Y',strtotime(Fecha::usuarioToBD($fchconsolidacion))) . " and c.cuota=1 ";

    	} else
    	{
            $sql = "Select ctacte_id, obj_id, subcta, trib_nom, trib_id, anio, cuota, saldo, to_char(FchVenc,'dd/mm/YYYY') venc ";
            $sql .= "From sam.uf_ctacte_objeto('" . $this->obj_dest . "','" . $fchconsolidacion . "'";
            $sql .= "," . $perdesde . ", " . $perhasta . ") Where est='D' and saldo>0 ";

            if ($this->trib_dest > 0)
            	$sql .= "and trib_id=" . $this->trib_dest;

            $sql .= " Order By obj_id,anio,cuota ";

    	}

    	$dataProvider = new SqlDataProvider([
		 	'sql' => $sql,
			'pagination' => false
        ]);

        //Armo un arreglo para devolver $dataProvider y error en caso de que lo haya
        $datos = [
			'dataProvider' => $dataProvider,
        	'error' => $error,
		];

    	//Retorno el arreglo con $dataProvider y $error
    	return $datos;
    }

    /**
     * Función que ingresa una nueva compensación
     * @param integer $nuevo Indica si se ingresa una nueva compensación o se modifica una existente. 0 => Nueva. 1 => Modifica
     * @param integer $codigo Código de la compensación
     * @param array $objOri	Objetos origen
     * @param array $objDes	Objetos destino
     * @param ineger $act_ctacte Tipo de destino (1 -> Período - 12 -> Anual - 6 -> Sem/Trim)
     * @param integer $expe Expediente
     * @param integer $tipo Tipo
     * @param integer $aplicoNum Aplica num
     * @param string $fchconsolida Fecha consolidación
     * @param integer $trib_ori Tributo origen
     * @param string $obj_ori Objeto origen
     * @param integer $trib_des Tributo destino
     * @param string $obj_des Objeto destino
     * @param double $monto Monto
     * @param string $obs Observación
     */
    public function grabarComp($nuevo,$codigo,$objOri,$objDes,$act_ctacte,$expe,$tipo,$aplicoNum,$fchconsolida,$trib_ori,$obj_ori,$trib_des,$obj_des,$monto,$obs)
    {
    	/* En $objOri y $objDes tengo un arreglo con los valores de ctacte_id y saldo para los valores checkeados. */
    	//Recupero una clave para la Compensación
    	if ($nuevo == 1)
    		$comp_id = Yii::$app->db->createCommand("SELECT nextval('seq_comp_id')")->queryScalar();
    	else
    		$comp_id = $codigo;

    	//Si la compensación es de tipo 3 o 4, se deben sumar los montos de los períodos
    	if ($tipo == 3 || $tipo == 4)
        {
        	$monto = 0;

        	foreach($objOri as $array)
    		{
    			$array = explode('-', $array);

                $monto += round($array[1],2);
    		}
        }

    	$transaction = Yii::$app->db->beginTransaction();
    	try
    	{
    		if ($nuevo == 1)
    		{
    			$sql = "Insert Into Comp (Comp_Id, Expe,Tipo,Aplic_NUM,FchAlta,fchconsolida,Trib_Ori,Obj_Ori,";
	         	$sql .= "Trib_Dest,Obj_Dest,Monto,Monto_Aplic,Est,Obs,UsrMod) Values (" . $comp_id . ",'";
	         	$sql .= $expe . "'," . $tipo . "," . $aplicoNum . ",current_date,'" . Fecha::usuarioToBD($fchconsolida) . "',";
	         	$sql .= $trib_ori . ",'" . $obj_ori . "'," . $trib_des . ",'" . $obj_des . "',";
	          	$sql .= $monto . ",0,'D','" . $obs . "'," . Yii::$app->user->id . ")";
    		}
			else
			{
				$sql = "Update Comp Set Expe='" . $expe . "',Tipo=" . $tipo . ",Aplic_Num=" . $aplicoNum;
                $sql .= ",FchConsolida='" . Fecha::usuarioToBD($fchconsolida) . "',Trib_Ori=" . $trib_ori . ",Obj_Ori='" . $obj_ori;
                $sql .= "',Trib_Dest=" . $trib_des . ",Obj_Dest='" . $obj_des;
                $sql .= "',Monto=" . $monto;
                $sql .= ",Obs='" . $obs . "',UsrMod=" . Yii::$app->user->id . " Where Comp_Id=" . $comp_id;
			}


          	Yii::$app->db->createCommand($sql)->execute();

          	//Si se definieron los periodos de Origen, se graban como Saldos
            if ($tipo == 3 || $tipo == 4)
            {
            	if (count($objOri) > 0)
            	{
            		//Si se modifica, elimino los datos anteriormente ingresados
            		if ($nuevo == 0)
            		{
            			$sql = "Delete From Comp_Saldo Where Comp_Id = " . $comp_id;
            			Yii::$app->db->createCommand($sql)->execute();
            		}

            		$monto = 0;

            		foreach($objOri as $array)
            		{
            			$array = explode('-', $array);

            			$sql = "Insert Into Comp_Saldo Values (" . $comp_id . ",";
                        $sql .= $array[0] . "," . round($array[1],2) . ",0)";

                        Yii::$app->db->createCommand($sql)->execute();

                        $sql = "Select sam.uf_comp_saldos_ajuste(" . $comp_id . "," . Yii::$app->user->id . ")";

                        Yii::$app->db->createCommand($sql)->execute();

                        $monto += round($array[1],2);
            		}
            	}
            }

            //Si existen periodos del Destino con Deuda, se aplican enseguida
            if (count($objDes) > 0)
            {
                $saldo = $monto;	//Saldo Total a Aplicar

				foreach($objDes as $array)
        		{
        			//$datos .= $saldo . 'SALDO</br>';
                    if ($saldo == 0)
                    	break;	//Si ya no queda saldo, salgo del bucle

                        $array = explode('-', $array);
                        if ($saldo > round($array[1],2))	//Comparo con monto del periodo
                        {
                        	$monto = round($array[1],2);
                            $saldo -= $monto;

                        }
                        else
                        {
                        	$monto = $saldo;
                            $saldo = 0;
                        }

                    $sql = "Select sam.uf_comp_aplicar('" . $obj_des . "'," . $array[0] . "," . $monto . "," . $act_ctacte . "," . Yii::$app->user->id . "," . $comp_id . ")";
                    Yii::$app->db->createCommand($sql)->execute();
            	}
            }
            $transaction->commit();

            return $comp_id;
    	} catch (Exception $e)
    	{
    		$transaction->rollback();
    		return "";
    	}
    }

    /**
     * Función que se utiliza para dar de baja una compensación.
     * @param integer $id Identificador de la compensación.
     */
    public function borrarComp($id)
    {
    	try
    	{
    		$sql = "Select sam.Uf_Comp_Borrar(" . $id . ")";
    		Yii::$app->db->createCommand($sql)->execute();
    		return '';
    	}
    	catch (Exception $e)
    	{
    		return 'Error';
    	}
    }

    public function Imprimir($id,&$sub1,&$sub2)
    {
    	$sql = "Select *,to_char(fchalta,'dd/mm/yyyy') fchalta,to_char(fchconsolida,'dd/mm/yyyy') fchconsolida From V_Comp Where Comp_Id = ".$id;
   		$array = Yii::$app->db->createCommand($sql)->queryAll();

   		$sql = "Select * From V_Comp_Saldo Where Comp_Id = ".$id."  Order By Trib_Nom, Obj_Id, SubCta, Anio, Cuota";
   		$sub1 = Yii::$app->db->createCommand($sql)->queryAll();

   		$sql = "Select *,to_char(fecha,'dd/mm/yyyy') fecha From V_Comp_Aplic Where Comp_Id = ".$id." Order By Trib_Nom, Obj_Id, SubCta, Anio, Cuota";
   		$sub2 = Yii::$app->db->createCommand($sql)->queryAll();

   		return $array;
    }

}
