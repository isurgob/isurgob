<?php

namespace app\models\ctacte;
use yii\data\SqlDataProvider;
use app\utils\db\Fecha;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use yii\web\Session;
use Yii;
use app\utils\db\utb;

/**
 * This is the model class for table "facilida".
 *
 * @property integer $faci_id
 * @property integer $trib_id
 * @property string $obj_id
 * @property string $nominal
 * @property string $accesor
 * @property string $multa
 * @property string $quita
 * @property string $monto
 * @property integer $est
 * @property string $fchalta
 * @property string $fchvenc
 * @property string $fchconsolida
 * @property string $fchimputa
 * @property string $fchbaja
 * @property integer $usrbaja
 * @property integer $baja_auto
 * @property string $obs
 * @property string $fchmod
 * @property integer $usrmod
 */
class Facilida extends \yii\db\ActiveRecord
{
	//Declaración de Variables
	public $num;
	public $num_nom;
	public $est;
	public $est_nom;
	public $tobj_nom;
	public $obj_id;
	public $obj_nom;
	public $trib_nom;
	public $baja;
	public $modif;
	
	public $nuevo_faci_id;
	public $nuevo_obj_tipo;
	public $nuevo_obj_id;
	public $nuevo_obj_nom;
	public $nuevo_trib_id;
	public $nuevo_fchalta;
	public $nuevo_fchvenc;
	public $nuevo_fchconsolida;
	public $nuevo_baja_automatica = true;
	public $nuevo_periodo_vencido = false;
	public $nuevo_periodo_exento = false;
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
	public $nuevo_quita;
	public $nuevo_multa;
	public $nuevo_total;
	public $nuevo_obs;
	
	public $grabar_faci_id;
	
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'facilida';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['faci_id', 'trib_id', 'obj_id', 'nominal', 'accesor', 'multa', 'est', 'fchalta', 'fchvenc', 'usrmod'], 'required'],
            [['faci_id', 'trib_id', 'est', 'usrbaja', 'baja_auto', 'usrmod'], 'integer'],
            [['nominal', 'accesor', 'multa', 'quita', 'monto'], 'number'],
            [['fchalta', 'fchvenc', 'fchconsolida', 'fchimputa', 'fchbaja', 'fchmod'], 'safe'],
            [['obj_id','num'], 'string', 'max' => 8],
            [['obs','num_nom'], 'string', 'max' => 250]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'faci_id' => 'Identificador de facilidad',
            'trib_id' => 'Codigo de tributo',
            'obj_id' => 'Codigo de objeto',
            'nominal' => 'Monto nominal',
            'accesor' => 'Monto accesorio',
            'multa' => 'Monto multa',
            'quita' => 'Quita de accesorios',
            'monto' => 'Monto',
            'est' => 'Estado',
            'fchalta' => 'Fecha de alta',
            'fchvenc' => 'Fecha de vencimiento',
            'fchconsolida' => 'Fecha de consolidacion',
            'fchimputa' => 'Fecha de imputacion',
            'fchbaja' => 'Fecha de baja',
            'usrbaja' => 'Usuario que da de baja',
            'baja_auto' => 'Si se da de baja automaticamente',
            'obs' => 'Obs',
            'fchmod' => 'Fecha de modificacion',
            'usrmod' => 'Codigo de usuario que modifico',
        ];
    }
    
    public function cargarDatos($id)
    {
    	$sql = "SELECT num,num_nom,est,est_nom,tobj_nom,obj_id,obj_nom,trib_nom,baja,modif " .
    			"FROM v_facilida WHERE faci_id = " . $id;
    			
    	$data = Yii::$app->db->createCommand($sql)->queryAll();
    	
    	$this->num = $data[0]['num'];
    	$this->num_nom = $data[0]['num_nom'];
    	$this->est = $data[0]['est'];
    	$this->est_nom = $data[0]['est_nom']; 
    	$this->tobj_nom = $data[0]['tobj_nom'];
    	$this->obj_id = $data[0]['obj_id']; 
    	$this->obj_nom = $data[0]['obj_nom'];
    	$this->trib_nom = $data[0]['trib_nom'];
    	$this->baja = $data[0]['baja'];
    	$this->modif = $data[0]['modif'];
    	
    	
    	//Transformo las fehas de la BD a Usuario
    	$this->fchvenc = Fecha::bdToUsuario($this->fchvenc);
    	$this->fchconsolida = Fecha::bdToUsuario($this->fchconsolida);
    	$this->fchimputa = Fecha::bdToUsuario($this->fchimputa);
    }
    
    public function getDatosGrilla($faci_id)
    {
    	if ($faci_id == '') $faci_id = -1;
    	
   	 	$sql = "SELECT faci_id,ctacte_id,obj_id,subcta,trib_id,trib_nom,anio,cuota,nominal,accesor,multa,total,quita,fchvenc,periodo " .
    		"FROM v_facilida_periodo WHERE faci_id = " . $faci_id . " order by anio,cuota";
    	
    	$count = Yii::$app->db->createCommand("SELECT COUNT(*) FROM v_facilida_periodo WHERE faci_id = " . $faci_id)->queryScalar();
    	$dataProvider = new SqlDataProvider([
		 	'sql' => $sql,
		 	'totalCount' => (int)$count,
		 	'pagination'=> [
				'pageSize'=>50,
			],
        ]); 

        return $dataProvider;
    }
    
    public function buscaDatosListado($cond,$orden)
    {
    	$sql = "SELECT faci_id,obj_id,trib_nomredu,num_nom,total,est_nom,to_char(fchalta, 'dd/MM/yyyy') as fchalta,to_char(fchvenc, 'dd/MM/yyyy') as fchvenc,to_char(fchimputa, 'dd/MM/yyyy') as fchimputa " .
    			"FROM v_facilida WHERE " . $cond . " ORDER BY " . $orden;
    			
    	$count = Yii::$app->db->createCommand("SELECT COUNT(*) FROM v_facilida WHERE " . $cond)->queryScalar();
    			
    	$dataProvider = new SqlDataProvider([
		 	'sql' => $sql,
		 	'key' => 'faci_id',
		 	'totalCount' => (int)$count,
		 	'pagination'=> [
				'pageSize'=>20,
			],
        ]); 

        return $dataProvider;
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
     * @param boolean $guardarEnSession Si el resultado se debe guardar en session o retornarlo
     */
    public function cargarDeudaDetalle( $obj_id,$trib_id,$desde = 0,$hasta = 0,$noVencido,$fchvenc,$fchconsolida,$marca = false, $guardarEnSession= true )
    {
    	
    	if ($desde == 0) $desde = (((date('Y') - utb::getTribPrescrip($trib_id)) * 1000) + 1);
    	if ($hasta == 0) $hasta = ((date('Y') * 1000) + ($noVencido ? 99 : date('m')));
    	
        $sql = "SELECT ctacte_id,trib_id,obj_id,subcta,anio,cuota,cast(nominal-nominalcub as decimal(9,2)) as nominal,";
        $sql .= "accesor, multa, est, fchvenc ";
        $sql .= "From Sam.Uf_CtaCte_Objeto('" . $obj_id . "','";
        $sql .= Fecha::usuarioToBD($fchconsolida) . "'," . $desde . "," . $hasta . ") ";
        $sql .= "Where est in ('D','H') and trib_id = " . $trib_id;
        
        if ($noVencido == 1)
        	$sql .= " and fchvenc>='" . Fecha::usuarioToBD($fchvenc) . "'";
        $sql .= " Order By anio, cuota";
        
        $dataProvider = Yii::$app->db->createCommand($sql)->queryAll();

		reset($dataProvider);
		if (count($dataProvider) > 0)
		{
			$trib_nom = Yii::$app->db->createCommand("Select nombre_redu From Trib Where Trib_Id = " .$dataProvider[0]['trib_id'])->queryScalar();
		}
	
		$data = [];
		
		$arreglo = [];
		
		foreach ($dataProvider as $array)
		{
				$data['activo'] = true;
				$data['ctacte_id'] = $array['ctacte_id'];
				$data['marca'] = (!$marca ? '' : 'X');
				$data['trib_id'] = $array['trib_id'];
	            $data['trib_nom'] = $trib_nom;	
	            $data['obj_id'] = $array['obj_id'];
	            $data['subcta'] = $array['subcta'];
	            $data['anio'] = $array['anio'];
	            $data['cuota'] = $array['cuota'];
	            $data['nominal'] = $array['nominal'];
	            $data['accesor'] = $array['accesor'];
	            $data['multa'] = $array['multa'];
	            
	            $total = $array['nominal'] + $array['accesor'] + $array['multa'];
	            
	            $data['total'] = number_format( round($total, 2), 2, '.', '' );
	            $data['fchvenc'] = $array['fchvenc'];
	            $data['saldo'] = $data['total'];
	            $data['quita'] = number_format( 0, 2, '.', '' );
				
				$arreglo[$array['ctacte_id']] = $data;
				
				unset($data);		
		}
									
	    $this->nuevo_periodos = $arreglo;
	    
	    if(!$guardarEnSession) return $arreglo;
	    
	    $session = new Session;
    	$session->open();
    	$session['arregloPeriodos'] = $arreglo;
    	$session->close();
    }
    
    public function obtenerDeudaDetalle($array = null)
    {
    	
		$dataProvider = new ArrayDataProvider([
		 	'models' => ($array == null ? $this->nuevo_periodos : $array),
	    ]); 
	    
	    return $dataProvider;
    }
    
    /**
     * Función que efectua los cálculos de la Facilida.
     * @param array $arrayCheck Arreglo de check seleccionados
     * @param integer $trib_id Identificador del tributo
     * @param string $obj_id Identificador del objeto
     */
    public function calcularFacilida($arrayCheck,$trib_id,$obj_id,$masiva = false, $arregloPeriodos= null)
    {
    	// Verifico que no exista una facilidad para el objeto y el tributo
		/*$sql = "Select coalesce(count(*),0) From Facilida Where Obj_id= '" . $obj_id . "' and trib_id= " . $trib_id . " and Est=1";
		$cant = Yii::$app->db->createCommand($sql)->queryScalar();
		if ($cant > 0)
			return "Existen Facilidades vigentes para el Objeto. No podrá dar de alta otra.";*/
		
		$session = new Session;
    	$session->open();
    	
    	//Obtengo el arreglo en sesion
    	$arregloPeriodos = $arregloPeriodos != null ? $arregloPeriodos : $session['arregloPeriodos'];
    	
    	if (count($arrayCheck) == 0)
    		return "No hay Períodos para calcular la Facilidad";
    	
    	
    	//Pongo todos los check en false
    	foreach($arregloPeriodos as &$array)
    	{
    		$array['activo'] = false;
    	}
    	unset($array);

    	$cantPer = 0; //Almacenará la cantidad de períodos seleccionados
    	$capital = 0;	
    	
    	//Activo los check
    	foreach ($arrayCheck as $array)
    	{
    		$cantPer++;
    		$arregloPeriodos[$array]['activo'] = true;
    	}
    	
    	foreach ($arregloPeriodos as $per)
    	{
			$capital += $per['nominal'] + $per['accesor'] + $per['multa'];
    	}
    	
    	if ($cantPer == 0)
    		return "No hay Períodos para calcular la Facilidad";
    	
    	//Inicializo los valores cubiertos
    	$nominal = 0;
    	$accesor = 0;
    	$multa = 0;
    	$quita = 0;
    	
    	foreach ($arregloPeriodos as &$per)
    	{
    		$data['quita'] = number_format( 0, 2, '.', '' );
    		$per['saldo'] = $per['total'];
    	}
    	unset($per);
    	
    	//Selecciono el valor porcentual de la quita
    	$faciQuita = Yii::$app->db->createCommand("select quitafaci from trib where trib_id = " . $trib_id)->queryScalar();
    	
    	if ($faciQuita > 0)
    	{
    		 $sql = "select count(*) from ctacte where trib_id = " . $trib_id;
    		 if ($masiva)
    		 	$sql .= " and est in ('D','J') and anio < extract(year from current_date)";
            else
                $sql .= " and Obj_Id = '" . $obj_id . "' and est in ('D','J') and anio < extract(year from CURRENT_DATE)";
                
            $cant = Yii::$app->db->createCommand($sql)->queryScalar();
            
            if ($cant <= $cantPer)
            {
            	foreach ($arregloPeriodos as &$per)
            	{
            		if ($per['activo'])
            		{
            			if ($per['anio'] != date('Y'))
	    				{
		    				$per['quita'] = ($per['accesor'] * $faciQuita); //Monto de quita sobre los accesorios
		    				$quita += $per['quita'];
	    				}
            		}
	    		
            	}
            	unset($per);
            }
    	}
    	
    	//Limpio la lista con las cuentas
		foreach ($arregloPeriodos as &$per)
    	{
    		if ($per['activo'])
    		{
    			$per['saldo'] = $per['total'] - $per['quita'];
    			
    			$nominal += $per['nominal'];
    			$accesor += $per['accesor'];
    			$multa += $per['multa'];
    		}
    	}
    	unset($per);
    	
    	$capital = $nominal + $accesor + $multa - $quita;
    	
    	$session['facilida-nuevo_capital'] = $capital;
    	$session['facilida-nuevo_nominal'] = $nominal;
    	$session['facilida-nuevo_accesor'] = $accesor;
    	$session['facilida-nuevo_multa'] = $multa;
    	$session['facilida-nuevo_quita'] = $quita;
    	
    	$session['arregloPeriodos'] = $arregloPeriodos;
    	
    	$session->close();
    	
		return '';

    }
    
    /**
     * Función que se utiliza para grabar una facilidad
     * @param integer $trib_id Id de tributo
     * @param string $obj_id Id de objeto
     * @param string $fchvenc Fecha de vencimiento
     * @param string $fchconsolida Fecha de consolidación
     * @param strinf $obs Observaciones
     * @param boolean $baja_auto Si es baja automática
     * @param Array $arregloPeriodos = null Arreglo con los períodos que se deben grabar. Omitir si se deben usar los períodos guardados en session
     * @return string Vacio si ocurrió un error o el ID de la facilidad si se grabó correctamente
     */
    public function grabarFacilida($trib_id,$obj_id,$fchvenc,$fchconsolida,$obs,$baja_auto, $arregloPeriodos= null)
    {
    	if($arregloPeriodos == null)
    	{
    		$session = new Session;
    		$session->open();
    		
    		$arregloPeriodos= $session->get('arregloPeriodos', []);
    		$session->close();
    	}

    	$faci_id = $this->getFaciId();
    	$est = 1;
    	
    	$transaction = Yii::$app->db->beginTransaction(); 	
       //Grabar los datos Asociados
       $grabarPeriodos = $this->grabarPeriodos($faci_id, $arregloPeriodos);
       if ($grabarPeriodos == 1)
       {
       	    try
       	    {
       	    	$sql = "Select sam.uf_facilida_generar(" . $faci_id . "," . $trib_id . ",'" . $obj_id . "','";
            	$sql .= Fecha::usuarioToBD($fchvenc) . "','" . Fecha::usuarioToBD($fchconsolida) . "','" . $obs . "'," . Yii::$app->user->id . "," . $baja_auto . ")";
       	    	
       	    	Yii::$app->db->createCommand($sql)->execute();
       	    	
       	    	$transaction->commit();
       	    	
       	    	return $faci_id;
       	    	
       	    } catch (Exception $e)
       	    {
       	    	$transaction->rollback();
       	    	return $e->getMessage();
       	    }
       	    
       	    
       }
       
       return '';
    }
    
    /**
     * Función que graba los períodos
     * @return integer Devuelve 1 si los grabo o 0 en caso contrario
     */
    public function grabarPeriodos($faci_id, $arregloPeriodos= null)
    {
    	if($arregloPeriodos == null){
    		
    		$session = new Session;
    		$session->open();
    		
    		$arregloPeriodos= $session->get('arregloPeriodos', []);
    		$session->close();	
    	}

		try
		{
	    	foreach ($arregloPeriodos as $per)
	    	{
	    		if ($per['activo'] and ($per['multa'] + $per['accesor'] + $per['nominal']) > 0)
	    		{
    				//Inserto en Facilida_Periodos
                    $sql = "Insert Into facilida_periodo Values (" . $faci_id . "," . $per['ctacte_id'] . ",";
                    $sql .= $per['nominal'] . "," . $per['accesor'] . ",";
                    $sql .= $per['multa'] . "," . $per['quita'] . ")";
    				
    				Yii::$app->db->createCommand($sql)->execute();
	    		}
	    		
    		} 
		} catch (Exception $e)
		{
			$transaction->rollback();
			return 0;
		}

		return 1;
    }
    
    public function activarFacilidad($id)
    {
    	$sql = "Select sam.uf_facilida_activar(" . $id . ")";

		try
		{
			$count = Yii::$app->db->createCommand($sql)->execute();
			$alert = '';
			
		} catch (Exception $e)
		{
			$alert = 'No se pudo activar la facilidad.';
		}
		
		return $alert;
    }
    
    public function borrarFacilidad($id)
    {
	
		$sql = "Select sam.uf_facilida_borrar(" . $id . "," . Yii::$app->user->id . ")";

		try
		{
			$count = Yii::$app->db->createCommand($sql)->execute();
			$alert = '';

			
		} catch (Exception $e)
		{
			$alert = 'No se pudo borrar la facilidad.';
		}
	
		return $alert;
    }
    
    public function borrarFacilidadesVencidas()
    {
		$sql = "Select sam.uf_facilida_borrar_venc(" . Yii::$app->user->id . ")";
		
		$count = Yii::$app->db->createCommand($sql)->execute();
       
		if ($count > 0)
		{
			$alert = '';

		} else 
		{
			$alert = 'No se pudieron borrar las facilidades vencidad.';
		}
		
		return $alert;
    }
    
    public function ImprimirComprobante($id,&$emision,&$sub1,&$texto)
    {
    	$model = Facilida::findOne($id) ;
    	$model->cargarDatos($id);
    	if ($model->est != 1) return "La Facilidad no se encuentra Vigente";
    	$texto = $model->obs;
    	
    	$sql = "Select *,to_char(fchvenc,'dd/mm/yyyy') as fchvenc,to_char(fchalta,'dd/mm/yyyy') as fchalta From V_Emision_Print_Faci Where Faci_id=".$id;
    	$emision = Yii::$app->db->createCommand($sql)->queryAll();
    	
    	$sql = "Select *,to_char(fchvenc,'dd/mm/yyyy') as fchvenc From v_facilida_periodo where faci_id = ".$id. " Order by Anio, cuota";
    	$sub1 = Yii::$app->db->createCommand($sql)->queryAll();
    	
    	return "";
    }
    
    //---------------------------------AUXILIARES---------------------------------------------------------//
    
    private function getFaciId()
    {
    	$faci_id = Yii::$app->db->createCommand("Select nextval('seq_facilida_id')")->queryScalar();
    	
    	return $faci_id;
    }
    
    /**
     * Función que filtrará los tributos que están checkeados
     */
    public function filtrarPeriodos($arreglo)
    {
	
		foreach($this->nuevo_periodos as $per)
		{
			foreach($arreglo as $array)
    		{
	    		$anio = substr($array,0,4);
	    		$cuota = substr($array,4,1);
    			
    			if ($per['anio'] == $anio && $per['cuota'] == $cuota)
    			{
    				$this->nuevo_periodos_filtrado[] = $per;
    			}
    		}
    	} 
    	
    	return $this->nuevo_periodos;	
    }
        
   
}
