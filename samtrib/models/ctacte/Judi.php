<?php

namespace app\models\ctacte;
use yii\data\SqlDataProvider;
use app\utils\db\Fecha;
use app\utils\helpers\DBException;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use yii\web\Session;
use Yii;
/**
 * This is the model class for table "judi".
 *
 * @property integer $judi_id
 * @property string $obj_id
 * @property string $obj_nom
 * @property string $rep
 * @property string $nro
 * @property integer $anio
 * @property string $expe
 * @property string $caratula
 * @property integer $perdesde
 * @property integer $perhasta
 * @property string $nominal
 * @property string $accesor
 * @property string $multa
 * @property string $multa_omi
 * @property string $hono_jud
 * @property string gasto_jud
 * @property integer $procurador
 * @property integer $procurador_nom
 * @property integer $juzgado
 * @property integer $juzgado_nom
 * @property string $fchalta
 * @property string $fchbaja
 * @property string $fchapremio
 * @property string $fchprocurador
 * @property string $fchjuicio
 * @property string $fchdev
 * @property integer $motivo_dev
 * @property string $motivo_dev_nom
 * @property integer $plan_id
 * @property string $est
 * @property string $est_nom
 * @property string $obs
 * @property string $fchmod
 * @property integer $usrmod
 */
class Judi extends \yii\db\ActiveRecord
{
	
	public $obj_nom;
	public $est_nom;
	public $motivo_dev_nom;
	public $procurador_nom;
	public $juzgado_nom;
	public $monto;
	
	public $nuevo_judi_id;
	public $nuevo_obj_tipo;
	public $nuevo_obj_id;
	public $nuevo_obj_nom;
	public $nuevo_reparticion;
	public $nuevo_numero = 0;
	public $nuevo_anio;
	public $nuevo_expe;
	public $nuevo_fchconsolida;
	public $nuevo_todos_periodos = true;
	public $nuevo_rango = false;
	public $nuevo_desdeanio;
	public $nuevo_desdecuota;
	public $nuevo_hastaanio;
	public $nuevo_hastacuota;
	public $nuevo_periodos = []; //Arreglo que almacenará todos los periodos
	public $nuevo_periodos_filtrado = []; //Arreglo que almacenará todos los periodos checkeados
	public $nuevo_nominal;
	public $nuevo_accesor;
	public $nuevo_multa;
	public $nuevo_multa_omis;
	public $nuevo_total;
	public $nuevo_obs;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'judi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['obj_id', 'rep', 'nro', 'anio', 'perdesde', 'perhasta', 'nominal', 'accesor', 'multa', 'multa_omi', 'fchalta', 'est'], 'required'],
            [['anio', 'perdesde', 'perhasta', 'procurador', 'juzgado', 'motivo_dev', 'plan_id'], 'integer'],
            [['nominal', 'accesor', 'multa', 'multa_omi', 'hono_jud', 'gasto_jud'], 'number'],
            [['fchalta', 'fchbaja', 'fchapremio', 'fchprocurador', 'fchjuicio', 'fchdev'], 'safe'],
            [['obj_id'], 'string', 'max' => 8],
            [['rep'], 'string', 'max' => 3],
            [['nro'], 'string', 'max' => 10],
            [['expe'], 'string', 'max' => 30],
            [['caratula'], 'string', 'max' => 50],
            [['est'], 'string', 'max' => 1],
            [['obs'], 'string', 'max' => 250]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'judi_id' => 'Identificador de planilla judicial',
            'obj_id' => 'Codigo de objeto',
            'rep' => 'Reparticion origen de la deuda',
            'nro' => 'Numero de expediente',
            'anio' => 'Año del expediente',
            'expe' => 'Expediente',
            'caratula' => 'Caratula del expediente',
            'perdesde' => 'Periodo inicio de la deuda',
            'perhasta' => 'Periodo final de la deuda',
            'nominal' => 'Monto nominal',
            'accesor' => 'Monto accesorios',
            'multa' => 'Monto multa',
            'multa_omi' => 'Monto multa por omision',
            'hono_jud' => 'Honorarios judiciales',
            'gasto_jud' => 'Gastos judiciales',
            'procurador' => 'Codigo de abogado',
            'juzgado' => 'Codigo de juzgado',
            'fchalta' => 'Fecha de alta',
            'fchbaja' => 'Fecha de baja',
            'fchapremio' => 'Fecha de apremio',
            'fchprocurador' => 'Fecha de abogado',
            'fchjuicio' => 'Fecha de juicio',
            'fchdev' => 'Fecha de devolucion',
            'motivo_dev' => 'Codigo de motivo de devolucion',
            'plan_id' => 'Identificador de plan',
            'est' => 'Estado',
            'obs' => 'Observaciones',
            'fchmod' => 'Fecha de modificacion',
            'usrmod' => 'Codigo de usuario que modifico',
        ];
    }
    
    /**
     * Función que obtiene los datos de períodos para el ID ingresado.
     * @param integer $id Identificador del apremio judicial.
     * @return dataProvider DataProvider con los datos obtenidos de la tabla.
     */
    public function getDatosGrillaPeriodo($id)
    {
    	if ($id == '') $id = -1;
    	
   	 	$sql = 	"SELECT p.trib_id,t.nombre as trib_nom,p.obj_id,p.ctacte_id,p.anio,p.cuota,p.estant,p.nominal,p.accesor,p.multa,p.total " .
    			"FROM v_judi_periodo p " .
    			"left join trib t ON t.trib_id = p.trib_id " .
    			"WHERE judi_id = " . $id . " " .
				"ORDER BY p.trib_id,p.anio,p.cuota";
    	
    	$count = Yii::$app->db->createCommand("SELECT COUNT(*) FROM judi_periodo WHERE judi_id = " . $id)->queryScalar();
    		
    	$dataProvider = new SqlDataProvider([
		 	'sql' => $sql,
		 	'totalCount' => (int)$count,
		 	'pagination'=> [
				'pageSize'=>50,
			],
        ]); 

        return $dataProvider;
    }
    
    /**
     * Función que obtiene los datos de etapas para el ID ingresado.
     * @param integer $id Identificador del apremio judicial.
     * @return dataProvider DataProvider con los datos obtenidos de la tabla.
     */
    public function getDatosGrillaEtapa($id)
    {
    	if ($id == '') $id = -1;
    	
   	 	$sql = 	"SELECT to_char(e.fecha, 'dd/mm/yyyy') as fecha,e.etapa,f.etapa_nom,e.detalle,e.hono_jud,e.gasto_jud,u.nombre || ' - ' || to_char(e.fchmod,'dd/mm/yyyy') as modif " .
   	 			"FROM judi_etapa e " .
   	 			"left join v_judi_etapa f on e.judi_id = f.judi_id  AND e.etapa = f.etapa " .
   	 			"left join sam.sis_usuario u on e.usrmod = u.usr_id " .
   	 			"WHERE e.judi_id = " . $id;
   	 			
   	 	$count = Yii::$app->db->createCommand("SELECT COUNT(*) FROM judi_etapa WHERE judi_id = " . $id)->queryScalar();
    		
    	$dataProvider = new SqlDataProvider([
		 	'sql' => $sql,
		 	'totalCount' => (int)$count,
		 	'pagination'=> [
				'pageSize'=>50,
			],
        ]); 

        return $dataProvider;
    }
    
    /**
     * Función que se ejecuta en conjunto al findModel y su función
     * es la de cargar datos que se obtienen fuera del modelo de judi.
     * @param integer $id Identificador del apremio del que se deben buscar los datos.
     */
    public function cargarDatos($id)
    {
    	$sql = "SELECT v.procurador_nom,v.juzgado_nom,v.est_nom,d.nombre as motivo_dev_nom " .
    			"FROM v_judi v " .
    			"left join judi_tdev d on d.cod = v.motivo_dev " .
    			"WHERE judi_id = " . $id;
    			
    			
    	$data = Yii::$app->db->createCommand($sql)->queryAll();

    	$this->motivo_dev_nom = $data[0]['motivo_dev_nom'];
    	$this->procurador_nom = $data[0]['procurador_nom'];   	
    	$this->juzgado_nom = $data[0]['juzgado_nom'];
    	$this->est_nom = $data[0]['est_nom'];
    	$this->monto = $this->nominal + $this->accesor + $this->multa + $this->multa_omi;
    	
    	//Transformo las fehas de la BD a Usuario
    	$this->fchalta = Fecha::bdToUsuario($this->fchalta);
    	$this->fchbaja = Fecha::bdToUsuario($this->fchbaja);
    	$this->fchapremio = Fecha::bdToUsuario($this->fchapremio);
    	$this->fchprocurador = Fecha::bdToUsuario($this->fchprocurador);
    	$this->fchjuicio = Fecha::bdToUsuario($this->fchjuicio);
    	$this->fchdev = Fecha::bdToUsuario($this->fchdev);

    }
    
    /**
     * Función que se encarga de actualizar la observación de un apremio.
     * @param integer $id Identificador del apremio.
     * @param string $obs Observación del apremio.
     * @return integer 1 => Datos grabados --- 2 => Datos no grabados.
     */
    public function updateObs($id,$obs)
    {
    	$sql = "UPDATE judi SET obs = '" . $obs . "' WHERE judi_id = " . $id;
    	
    	$count = Yii::$app->db->createCommand($sql)->execute();
    	
    	if ($count > 0)
      		return '';
      	else 
      		return 2;
    	
    }
    
    /**
     * Función que se ejecuta para realizar la carga del detalle de deuda
     * @param string $obj_id Código de Objeto
     * @param integer $trib_id
     * @param string $desde Fecha Desde
     * @param string $hasta Fecha Hasta
     * @param boolean $vencido Si está vencido
     * @param boolean $perExento
     * @param string $fchvenc
     * @param string $fchconsolida
     * @param boolean $marca
     */
    public function cargarDeudaDetalle($obj_id,$desde = 0,$hasta = 0,$fchconsolida,$cond = '')
    {
    	
    	if ($desde == 0) $desde = 1990001;
    	if ($hasta == 0) $hasta = (date('Y') * 1000) + date('m');
    	
        $sql = "SELECT ctacte_id,trib_id,obj_id,trib_nom,subcta,anio,cuota,cast(nominal-nominalcub as decimal(9,2)) as nominal,";
        $sql .= "accesor, multa, est, fchvenc ";
        $sql .= "From Sam.Uf_CtaCte_Objeto('" . $obj_id . "','";
        $sql .= Fecha::usuarioToBD($fchconsolida) . "'," . $desde . "," . $hasta . ") ";
		$sql .= "Where Est='D' and cast(Nominal-NominalCub as decimal(9,2)) > 0 ";
		
		if ($cond != '')
			$sql .= "AND (" . $cond . ")";
        $sql .= " ORDER BY Trib_id, Obj_id,Anio, Cuota";
        
        $dataProvider = Yii::$app->db->createCommand($sql)->queryAll();

		reset($dataProvider);

		$data = [];
		
		$arreglo = [];
		
		foreach ($dataProvider as $array)
		{
				$data['activo'] = true;
				$data['ctacte_id'] = $array['ctacte_id'];
				$data['marca'] = 'X';
				$data['trib_id'] = $array['trib_id'];
	            $data['trib_nom'] = $array['trib_nom'];	
	            $data['obj_id'] = $array['obj_id'];
	            $data['subcta'] = $array['subcta'];
	            $data['anio'] = $array['anio'];
	            $data['cuota'] = $array['cuota'];
	            $data['nominal'] = $array['nominal'];
	            $data['accesor'] = $array['accesor'];
	            $data['multa'] = $array['multa'];
	            $data['est'] = $array['est'];
	            
	            $total = $array['nominal'] + $array['accesor'] + $array['multa'];
	            
	            $data['total'] = round($total, 2);
	            $data['fchvenc'] = $array['fchvenc'];
	            $data['saldo'] = $data['total'];
	            $data['quita'] = 0;
				
				$arreglo[$array['ctacte_id']] = $data;
				
				unset($data);		
		}
									
	    $this->nuevo_periodos = $arreglo;
	    $session = new Session;
    	$session->open();
    	$session['arregloPeriodosApremioApremio'] = $arreglo;
    	$session->close();

    }
    
     /**
     * Función que efectua los cálculos del Apremio.
     * @param array $arrayCheck Arreglo de check seleccionados
     * @param string $obj_id Identificador del objeto
     */
    public function calcularApremio($arrayCheck,$obj_id,$masiva = false)
    {
    	$session = new Session;
    	$session->open();
    	
    	//Obtengo el arreglo en sesion
    	$arregloPeriodosApremio = $session['arregloPeriodosApremio'];
    	
    	//Pongo todos los check en false
    	foreach($arregloPeriodosApremio as &$array)
    	{
    		$array['activo'] = false;
    	}
    	unset($array);
   	
    	if (count($arrayCheck) == 0)
    		return "No hay Períodos para calcular la Facilidad";
    		
    	$cantPer = 0; //Almacenará la cantidad de períodos seleccionados
    	$capital = 0;	
    	
    	//Activo los check
    	foreach ($arrayCheck as $array)
    	{
    		$cantPer++;
    		$arregloPeriodosApremio[$array]['activo'] = true;
    	}
    	
    	foreach ($arregloPeriodosApremio as $per)
    	{
			$capital += $per['nominal'] + $per['accesor'] + $per['multa'];
    	}
    	
    	if ($cantPer == 0)
    		return "No hay Períodos para calcular la Facilidad";
    	
    	//Inicializo los valores cubiertos
    	$nominal = 0;
    	$accesor = 0;
    	$multa = 0;

    	//Limpio la lista con las cuentas
		foreach ($arregloPeriodosApremio as &$per)
    	{
    		if ($per['activo'])
    		{
    			$per['saldo'] = $per['total'];
    			
    			$nominal += $per['nominal'];
    			$accesor += $per['accesor'];
    			$multa += $per['multa'];
    		}
    	}
    	unset($per);
    	
    	$capital = $nominal + $accesor + $multa;
    	
    	$session['Apremio-nuevo_capital'] = $capital;
    	$session['Apremio-nuevo_total'] = $capital;
    	$session['Apremio-nuevo_nominal'] = $nominal;
    	$session['Apremio-nuevo_accesor'] = $accesor;
    	$session['Apremio-nuevo_multa'] = $multa;
    	
    	$session['arregloPeriodosApremio'] = $arregloPeriodosApremio;
    	
    	$session->close();
    	
		return '';

    }
    
    /**
     * Función que graba un apremio en la BD
     * @param string $expe Expediente
     * @param string $obj_id Id del objeto
     */
    public function grabar()
    {
    	$dia = date('d') . '/' . date('m') . '/' . date('Y');
    	
    	$expe = $this->nuevo_reparticion . "-" . $this->nuevo_numero . "-" . $this->nuevo_anio;
    	
    	//Validar que el expediente y el objeto sea único si es distinto de 0
    	if ($this->nuevo_numero != 0)
    	{
    		$sql = "Select count(*) From Judi Where Expe ='". $expe . "' and est<>'0'";
    		$count = Yii::$app->db->createCommand($sql)->queryScalar();
    	
    		if ($count > 0)
    			return "El expediente ya existe.";
    	}
    	
    	$this->nuevo_judi_id = $this->getJudi_id();
    	
    	if ($this->nuevo_multa == '') $this->nuevo_multa = 0;
    	if ($this->nuevo_multa_omis == '') $this->nuevo_multa_omis = 0;
    	if ($this->nuevo_nominal == '') $this->nuevo_nominal = 0;
    	if ($this->nuevo_accesor == '') $this->nuevo_accesor = 0;
    	
    	$perdesde = (($this->nuevo_desdeanio * 1000) + $this->nuevo_desdecuota);
    	$perhasta = (($this->nuevo_hastaanio * 1000) + $this->nuevo_hastacuota);
    	
	    $sql = "INSERT INTO judi VALUES (" . $this->nuevo_judi_id . ",'" . $this->nuevo_obj_id . "','" . $this->nuevo_reparticion . "','" . $this->nuevo_numero . "'," . $this->nuevo_anio . ",'" . $expe . "','";
        $sql .= $this->nuevo_obj_nom . "'," . $perdesde . "," . $perhasta . "," . $this->nuevo_nominal . "," . $this->nuevo_accesor . ",";
        $sql .= $this->nuevo_multa . "," . $this->nuevo_multa_omis . ",0,0,0,0,'" . Fecha::usuarioToBD($dia) . "',null,null,null,null,null,0,1,'R','" . $this->nuevo_obs . "',current_timestamp," . Yii::$app->user->id . ")";
    	
    	$transaction = Yii::$app->db->beginTransaction(); 
    	try
    	{
    		$count = Yii::$app->db->createCommand($sql)->execute();
    		$this->grabarPeriodo();
    		    		
    	} catch (Exception $e)
    	{
    		$transaction->rollback();
    		return 0;
    	}
    	
    	$transaction->commit();
    	
    	return $count;
       
    }
    
    /**
     * Función que graba los períodos seleccionados.
     * @return Devuelve 1 si se llevaron a cabo los cambios y 0 en caso contrario
     */
    public function grabarPeriodo()
    {
    	$session = new Session;
    	$session->open();
    	$arreglo = $session['arregloPeriodosApremio'];
        $session->close();
        
       
    	try
    	{
    		 foreach ($arreglo as $array)
       		 {
       		 	//Inserto en planes_periodos
       		 	$sql = "INSERT Into judi_periodo Values (" . $this->nuevo_judi_id . "," . $array["ctacte_id"] . ",";
                $sql .= $array["nominal"] . "," . $array["accesor"] . "," . $array["multa"] . ",'";
                $sql .= $array["est"] . "')";
                
                Yii::$app->db->createCommand($sql)->execute();
                
                //Actualizo el estado en la CtaCte
                $sql = "Update CtaCte Set Est='J', Obs=Coalesce(Obs,'') || '/Judi:" . $this->nuevo_judi_id . "/'";
                $sql .= " Where CtaCte_Id=" . $array["ctacte_id"];
                
                Yii::$app->db->createCommand($sql)->execute(); 
                
       		 }	
    	}
    	catch (Exception $e)
    	{
    		$transaction->rollback();
    		return 0;
    	}
    	
    	return 1;

    }
    
    /**
     * Realiza una búsqueda por períodos.
     * @param string $obj_id Código de objeto.
     * @param string $perdesde Período desde.
     * @param string $perhasta Período hasta.
     * @param string $fecha Fecha
     * @return Devuelve un dataset con los datos obtenidos.
     */
    public function buscarPeriodo($obj_id,$perdesde,$perhasta,$fecha)
    {
		if ($perdesde == 0) $perdesde = 1990001;
		if ($perhasta == 0) $perhasta = (date('Y') * 1000) + date('m');

        $sql = "Select Obj_id, Trib_id, Trib_nom, Sum(Saldo) as Total From sam.Uf_CtaCte_Objeto('" . $obj_id . "','";
        $sql .= $fecha . "'," . $perdesde . "," . $perhasta . ") ";
        $sql .= "Where Est = 'D' and fchvenc < current_date ";
        $sql .= " Group By Obj_id, Trib_id, Trib_nom";
        $sql .= " Order By Obj_id ";
		
		$dataProvider = new SqlDataProvider([
		 	'sql' => $sql,
        ]); 

        return $dataProvider;
		
    }
    
    /**
     * Función que se utiliza para realizar la búsqueda de Apremio para el lsitado
     * @param string $cond Condición de búsqueda
     * @param string $orden Ordenamiento de la consulta
     * @return DataProvider Resultado de la búsqueda
     */
    public function buscaDatosListado($cond,$orden)
    {
    	$sql = "SELECT judi_id,obj_id,expe,caratula,to_char(fchalta, 'dd/MM/yyyy') as fchalta,procurador_nom,est_nom,(nominal+accesor+multa+multa_omi+hono_jud+gasto_jud) as deuda " .
    			"FROM v_judi " .
    			"WHERE " . $cond . " ORDER BY " . $orden;
    			
    	$count = Yii::$app->db->createCommand("SELECT COUNT(*) FROM v_judi WHERE " . $cond)->queryScalar();
    			
    	$dataProvider = new SqlDataProvider([
		 	'sql' => $sql,
		 	'key' => 'judi_id',
		 	'totalCount' => (int)$count,
		 	'pagination'=> [
				'pageSize'=>20,
			],
        ]); 

        return $dataProvider;
    }
    
    /**
     * Función que se encarga de agregar una nueva etapa a un apremio
     * @param integer $judi_id Identificador del apremio
     * @param integer $etapa Identificador de la nueva etapa
     * @param string $fecha Fecha en la que se agrega la etapa
     * @param integer $procurador Id del procurador
     * @param integer $juzgado Id del juzgado
     * @param integer $motivo Id del motivo
     * @param double $hono_jud Honorarios
     * @param double $gasto_jud Gastos
     * @param string $detalle Observación 
     * @return string Resultado de la consulta
     */
    public function agregarEtapa($judi_id,$etapa,$fecha,$procurador,$juzgado,$motivo,$hono_jud,$gasto_jud,$detalle)
    {
    	
    	if ($etapa == 0 || $etapa == '')
    		return "Ingrese una etapa válida.";
    		
    	//Validar que la fecha no sea anterior a la fecha de alta
    	$sql = "SELECT EXISTS (SELECT 1 FROM judi WHERE judi_id = " . $judi_id . " AND " . Fecha::usuarioToBD( $fecha, 1 ) . " < fchalta::date )";
    	$res = Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	if ($res == 1)
    		return "La fecha ingresada es menor a la fecha de alta";
    		
    	//Validar que la fecha no sea menor a la etapa anterior
    	$sql = "SELECT EXISTS (SELECT 1 FROM judi_etapa WHERE judi_id = " . $judi_id . " AND '".Fecha::usuarioToBD($fecha)."' < fecha::date )";
    	$res = Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	if ($res == 1)
    		return "La fecha ingresada es menor a la fecha de la etapa anterior.";
    	
    	$sql = "SELECT sam.uf_judi_etapa_nueva (".$judi_id.",".$etapa.",'".Fecha::usuarioToBD($fecha)."',".$procurador.
    			",".$juzgado.",".$motivo.",".$hono_jud.",".$gasto_jud.",'".$detalle."',".Yii::$app->user->id.")";
    	
    	try
    	{
    		$result = Yii::$app->db->createCommand($sql)->queryScalar();
    		
    	} catch (Exception $e)
    	{
    		$result = DBException::getMensaje($e);
    	}
    	
    	return $result;
    	
    }
    
    /**
     * Función que se encarga de eliminar la última etapa de un apremio
     * @param integer $judi_id ID del apremio
     * @return string Resultado de la consulta
     */
    public function eliminarEtapa($judi_id)
    {
    	$sql = "SELECT sam.uf_judi_etapa_baja (".$judi_id.",".Yii::$app->user->id.")";
    	
    	try
    	{
    		$result = Yii::$app->db->createCommand($sql)->queryScalar();
    		
    	} catch (Exception $e)
    	{
    		$result = DBException::getMensaje($e);
    	}
    	
    	return $result;
    }
    
    /**
     * Función que obtiene parámetros de la BD según el tipo de etapa.
     * Obtiene un boolean para saber si se deben ingresar honorarios, gastos o procurador.
     * @param string $etapa Tipo de etapa
     * @return array Arreglo con los valores obtenidos
     */
    public function getParametrosEtapa( $etapa = 0 )
    {
    	
    	if ( $etapa != 0 )
    	{
    		$sql = "SELECT pedir_proc,pedir_dev,pedir_hono " .
	    			"FROM judi_tetapa WHERE cod = " . $etapa;
	    			
	    	$data = Yii::$app->db->createCommand($sql)->queryAll();
	    	
	    	$arreglo = [
						'procurador' => $data[0]['pedir_proc'],
	    				'motivo' => $data[0]['pedir_dev'],
	    				'honorarios' => $data[0]['pedir_hono'],
	    			   ];
    	} else
    	{
    		$arreglo = [
						'procurador' => 0,
	    				'motivo' => 0,
	    				'honorarios' => 0,
	    			   ];
    	}
	    	
    			   
    	return $arreglo;

    }
    
    /**
     * Función que obtiene un nuevo Id para poder ingresar datos en la BD
     */
    public function getJudi_id()
    {
    	return Yii::$app->db->createCommand("SELECT nextval('seq_judi')")->queryScalar();
    }
    
    public function obtenerDeudaDetalle($array = null)
    {
    	
		$dataProvider = new ArrayDataProvider([
		 	'models' => ($array == null ? $this->nuevo_periodos : $array),
	    ]); 
	    
	    return $dataProvider;
    }
    
    public function ImprimirExpe($id,&$sub1)
    {
    	$sql = "Select *,to_char(fchalta,'dd/mm/yyyy') fchalta,to_char(fchbaja,'dd/mm/yyyy') fchbaja, " .
    			"to_char(fchapremio,'dd/mm/yyyy') fchapremio,to_char(fchprocurador,'dd/mm/yyyy') fchprocurador, " .
    			"to_char(fchjuicio,'dd/mm/yyyy') fchjuicio,to_char(fchdev,'dd/mm/yyyy') fchdev From V_Judi Where judi_id=".$id;
        $array = Yii::$app->db->createCommand($sql)->queryAll();
        
        $sql = "Select *,to_char(fecha,'dd/mm/yyyy') fecha From V_Judi_Etapa v Where Judi_Id=" . $id . " Order By v.Fecha";
        $sub1 = Yii::$app->db->createCommand($sql)->queryAll();
        
        return $array;
    }
    
    public function ImprimirCert($id,$texto,&$sub1)
    {
    	$sql = "Select j.*,t.detalle as mensaje From V_Judi j Left Join texto t On t.texto_id= ".$texto;
        $sql .= " Where j.Judi_id = " .$id." Order by judi_id";
        $array = Yii::$app->db->createCommand($sql)->queryAll();
        
        $sql = "Select CtaCte_Id, 'X' Marca, Trib_id, Trib_nom, Obj_id, SubCta, Obj_Dato, Anio, Cuota, EstAnt Est,";
        $sql .= "cast(Nominal as decimal(12,2)), cast(Accesor as decimal(12,2)), cast(Multa as decimal(12,2)), cast(Total as decimal(12,2)), ";
        $sql .= "FchVenc, EstAnt From v_Judi_Periodo ";
        $sql .= "Where Judi_Id=" .$id. " Order By Trib_id, Obj_id, Anio, Cuota";
        $sub1 = Yii::$app->db->createCommand($sql)->queryAll();
        
        return $array;
    }
}
