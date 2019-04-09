<?php

namespace app\models\ctacte;
use app\utils\helpers\DBException;
use app\models\objeto\ComerRubro;
use yii\web\Session;
use yii\helpers\ArrayHelper;
use yii\data\SqlDataProvider;

use Yii;

/**
 * This is the model class for table "fiscaliza".
 *
 * @property integer $fisca_id
 * @property string $obj_id
 * @property string $expe
 * @property integer $inspector
 * @property string $fchalta
 * @property string $fchbaja
 * @property integer $est
 * @property string $obs
 * @property string $fchmod
 * @property integer $usrmod
 * 
 * @property array $arrayRubros
 */
class Fiscaliza extends \yii\db\ActiveRecord
{
	
	public $arrayRubros;	//Arreglo que almacenará la información de Rubros.
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fiscaliza';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fisca_id', 'inspector', 'est'], 'integer'],
            [['fchalta', 'fchbaja'], 'safe'],
            [['obj_id'], 'string', 'max' => 8],
            [['expe'], 'string', 'max' => 12],
            [['obs'], 'string', 'max' => 250]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fisca_id' => 'Identificador de fiscalizacion',
            'obj_id' => 'Codigo de objeto',
            'expe' => 'Expediente',
            'inspector' => 'Codigo de inspector',
            'fchalta' => 'Fecha de alta',
            'fchbaja' => 'Fecha de baja',
            'est' => 'Estado',
            'obs' => 'Observaciones',
            'fchmod' => 'Fecha de modificacion',
            'usrmod' => 'Codigo de usuario que modifico',
        ];
    }
    
    /**
     * Método que se utiliza para validar los datos que ingresarán en la BD.
     */
    private function validarDatos()
    {
    	$error = '';
    	
    	//Validar que se haya ingresado un expediente
    	if ($this->expe == '' || $this->expe == null)
    		$error .= '<li>Ingrese un expediente.</li>';
    	
    	//Validar que se ingrese un comercio
    	if ($this->obj_id == '' || $this->obj_id == null)
    		$error .= '<li>Ingrese un comercio.</li>'; 
    		
    	return $error;
    }
    
    /**
     * Método que se utiliza para dar de alta o actualizar una "Fiscalización".
     */
    public function grabarFiscalizacion()
    {
    	
    	$res = $this->validarDatos();
    	
    	if ($res != '')
    	{
			$arreglo = [
    			'return' => 0,
    			'mensaje' => $res,
    		];
	    		
	    	return $arreglo; 
    	}
	    
    	$transaction = Yii::$app->db->beginTransaction();
    	  
    	if ($this->isNewRecord)	// En el caso de una nueva "Fiscalización"
    	{
    		//SQL para insertar una nueva "Fiscalización"
    		
    		$this->fisca_id = $this->getFisca_id();
    		
			$sql = "Insert Into Fiscaliza Values (" . $this->fisca_id . ",'" . $this->obj_id . "','";
            $sql .= $this->expe . "'," . $this->inspector . ", current_timestamp, null," . $this->est;
            $sql .= ", '" . $this->obs . "', current_timestamp, " . Yii::$app->user->id . ")";
            
    	} else	//En el caso de modificar una "Fiscalización" 
    	{
    		//SQL para modificar una "Fiscalización"
    		$sql = "Update Fiscaliza Set Obj_id = '" . $this->obj_id . "', Expe = '";
            $sql .= $this->expe . "', Inspector = " . $this->inspector . ",Est = " . $this->est . ", Obs = '";
            $sql .= $this->obs . "', FchMod = current_timestamp, UsrMod=" . Yii::$app->user->id . " Where Fisca_Id = " . $this->fisca_id;
    	}
               
    	try 
    	{
    		$res = Yii::$app->db->createCommand($sql)->execute();
    		
    		//Grabar rubros
    		$res = $this->grabarRubros();
    		
    		if ($res['return'] == 1)	//Si los rubros se guardaron correctamente
    		{
    			$arreglo = [
	    			'return' => 1,
	    			'mensaje' => '',
	    			'id' => $this->fisca_id,
	    		];
	    		
	    		$transaction->commit();
	    		
    		} else 
    		{
    			$arreglo = [
	    			'return' => 0,
	    			'mensaje' => $res['mensaje'],
    			];
    			
    			$transaction->rollback();
    			
    		}
    		
    	} catch (\Exception $e)
    	{
    		$transaction->rollback();
    		
    		$arreglo = [
    			'return' => 0,
    			'mensaje' => DBException::getMensaje($e),
    		];
    	}
    	
    	return $arreglo;
    }
    
    /**
     * Método que se encarga de eliminar una "Fiscalización".
     */
    public function eliminarFiscalizacion()
    {
    	if ($this->est == 0 && ($this->fchbaja != null || $this->fchbaja != ''))
    	{
    		$arreglo = [
	    			'return' => 0,
	    			'mensaje' => 'La Fiscalización ya fue dada de Baja con anterioridad.',
	    		];
	    		
	    	return $arreglo;
    	}
	    	
        $sql = "Update Fiscaliza Set Est = 0, FchBaja = current_timestamp, UsrMod=";
        $sql .= Yii::$app->user->id . " Where Fisca_Id = " . $this->fisca_id;
        
        $sql2 = "UPDATE objeto_rubro SET est = 'B' WHERE fiscaliza = 1 AND obj_id = '" . $this->obj_id . "'";
        
        try 
    	{
    		$res = Yii::$app->db->createCommand($sql)->execute();
    		$res = Yii::$app->db->createCommand($sql2)->execute();
    		
    		$arreglo = [
    			'return' => 1,
    			'mensaje' => '',
    		];
    		
    	} catch (\Exception $e)
    	{
    		$arreglo = [
    			'return' => 0,
    			'mensaje' => DBException::getMensaje($e),
    		];
    	}
    	
    	return $arreglo;
    	 
    }
    
    /**
     * Función que se encarga de retornar el próximo ID para "Fiscalización".
     * @return integer ID de fiscalización para isnertar en la BD.
     */
    private function getFisca_id()
    {
    	return Yii::$app->db->createCommand("Select nextval('seq_fisca_id'::regclass)")->queryScalar();
    }
    
    /**
     * Método que determina si existe una fiscalización para el comercio.
     * @param integer $obj_id Identificador de comercio.
     * @return integer Existe una fiscalización.
     */
    public function existeFiscalizacionObjeto($obj_id)
    {
    	$sql = "SELECT EXISTS (SELECT 1 FROM fiscaliza WHERE obj_id = '" . $obj_id . "')";
    	
    	return Yii::$app->db->createCommand($sql)->queryScalar();
    }
    
    /**
     * Método que determina si existe más de una fiscalización para el comercio.
     * @param integer $obj_id Identificador de comercio.
     * @return integer Existen fiscalizaciones.
     */
    public function existenFiscalizacionesObjeto($obj_id)
    {
    	$sql = "SELECT EXISTS (SELECT 1 FROM fiscaliza WHERE (SELECT count(*) FROM fiscaliza WHERE obj_id = '" . $obj_id . "') > 1)";
    	
    	return Yii::$app->db->createCommand($sql)->queryScalar();
    }
    
//    /**
//     * Método que se utiliza para obtener un arreglo con la lista de sucursales de un comercio.
//     * @param integer $obj_id Identificador del comercio.
//     * @return $array Arreglo con las sucursales de un comercio
//     */
//    public function getSucursalesComercio($obj_id)
    
    
    
    
    //------------------------------------RUBROS-------------------------------------------------------------------//
    
    /**
     * Función que se encarga de obtener los rubros asociados a una fiscalización.
     * @param string $comer_id Identificador del comercio.
     */
    public function obtenerRubros()
    {
    	/*
    	 * Para identificar cada elemento del arreglo, uno la letra 'r' con el ID de rubro. 
    	 */
    	 
    	$sql = "SELECT subcta, obj_id, trib_id, trib_nom, trib_cantanio, cast(CONCAT('r',rubro_id) as VARCHAR(10)) as rubro,rubro_id, rubro_nom, perdesde, perhasta, nperdesde, nperhasta, cant," .
    			"tipo,fiscaliza,est,est_nom,expe,obs,porc,fchmod,usrmod,modif " .
    			"FROM v_objeto_rubro " .
    			"WHERE obj_id = '" . $this->obj_id. "' AND fiscaliza = 1";
    	
    	
    	$res = Yii::$app->db->createCommand($sql)->queryAll();
    	
    	$this->arrayRubros = ArrayHelper::map($res, 'rubro', function($model){ return [
			    												'subcta' => $model['subcta'],
			    												'obj_id' => $model['obj_id'],
			    												'trib_id' => $model['trib_id'],
			    												'trib_nom' => $model['trib_nom'],
			    												'trib_cantanio' => $model['trib_cantanio'],
			    												'rubro_id' => $model['rubro_id'],
			    												'rubro_nom' => $model['rubro_nom'],
			    												'nperdesde' => $model['nperdesde'],
			    												'perdesde' => $model['perdesde'],
			    												'adesde' => substr($model['perdesde'],0,4),
			    												'cdesde' =>  substr($model['perdesde'],5,3),
			    												'nperhasta' => $model['nperhasta'],
			    												'perhasta' => $model['perhasta'],
			    												'ahasta' => substr($model['perhasta'],0,4),
			    												'chasta' =>  substr($model['perhasta'],5,3),
			    												'cant' => $model['cant'],
			    												'tipo' => $model['tipo'],
			    												'porc' => $model['porc'],
			    												'expe' => $model['expe'],
			    												'obs' => $model['obs'],
    															'est' => $model['est'],
    										];});
    }
    
    /**
     * Función que carga en un objeto "ComerRubro" los datos de un rubro seleccionado
     */
    public function cargarUnicoRubro($rubro_id)
    {
    	$rubro = new ComerRubro();
    	
    	if ($rubro_id != '')
    	{
	    	$session = new Session;
	    	$session->open();
	    	
	    	$array = Yii::$app->session->get('arregloRubros');
	    	
	    	$session->close();
	    	
	    	$array = $array[$rubro_id]; 
	    	
	    	$array = ['ComerRubro' => $array];
	    	
	    	$rubro->setScenario('select');
	    	$rubro->load($array);
	    	
	    	$rubro->esPrincipal = ($rubro->tipo == 1 ? true : false);
    	}
    	
    	return $rubro;
    }
    
    /**
     * Función que se encarga de cargar un rubro en el arreglo de rubros.
     * @param model $rubro Modelo de rubro
     */
    public function cargarRubro($rubro)
    {
    	$session = new Session;
    	$session->open();
    	
    	$array = $session->get('arregloRubros');
    	
    	$arregloRubros = array_merge($arregloRubros, [$rubro->rubro_id => $rubro]);
    	
    	$session->set('arregloRubros',$arregloRubros);
    	
    	$session->close();
    }
    
    
    /**
     * Función que se encarga de insertar el arreglo de rubros en la BD.
     */
    public function grabarRubros()
    {
    	$session = new Session;
    	$session->open();
    	
    	//Obtener los rubros que estén en memoria
    	$arreglos = $session->get('arregloRubros', []);
    	
    	try
    	{
    		//Primero elimino todos los rubros ingresados para la fiscalización
			$sql = "DELETE FROM objeto_rubro WHERE fiscaliza = 1 AND obj_id = '" . $this->obj_id . "'";
			Yii::$app->db->createCommand($sql)->execute();
			
    	} catch (\Exception $e)
    	{
    		$arreglo = [
				'return' => 0,
    			'mensaje' => DBException::getMensaje($e),
    		];
	    	
	    	return $arreglo;
    	}
    	
    		
    	if (count($arreglos) > 0)
    	{
    		foreach ($arreglos as $array)
    		{
    			//Generar el SQL para grabar los rubros
                $sql = "Insert Into Objeto_Rubro Values ('" . $this->obj_id . "',";
                $sql .= $array['rubro_id'] . "," . $array['nperdesde'] . "," . $array['nperhasta'] . ",1," . $array['subcta'] . "," . $array['cant'];
                $sql .= ", 2, 'A', '" . $this->expe . "', '" . $array['obs'] . "'," . $array['porc'] . ", current_timestamp, " . Yii::$app->user->id . ")";
                
                try
                {
                	Yii::$app->db->createCommand($sql)->execute();
                
                } catch (\Exception $e)
                {
                	$arreglo = [
						'return' => 0,
		    			'mensaje' => DBException::getMensaje($e),
		    		];
			    	
			    	return $arreglo;
                }
                
    		}
    	}
    	
    	$arreglo = [
			'return' => 1,
			'mensaje' => '',
		];
    	
    	return $arreglo;
    }
    
    
    //------------------COPIAR CC------------------------//
    
    /**
     * Función que realiza la copia de una fiscalización a la CC.
     * @param integer $trib_id Identificador de tributo.
     * @param string $obj_id Identificador de comercio.
     * @param integer $perdesde Período desde.
     * @param integer $perhasta Período hasta.
     * 
     */
    public function fiscalizaCopiarCtaCte($trib_id,$obj_id,$perdesde,$perhasta)
    {	
    	$sql = "Select sam.Uf_Fiscaliza_Copiar(" . $trib_id . ",'" . $obj_id;
        $sql .= "'," . $perdesde . "," . $perhasta . "," . Yii::$app->user->id . ")";
        
        try
        {
        	Yii::$app->db->createCommand($sql)->execute();
        	
        	$arreglo = [
				'return' => 1,
    			'mensaje' => '',
    		];
	    	
        	
        } catch (\Exception $e)
        {
        	$arreglo = [
				'return' => 0,
    			'mensaje' => DBException::getMensaje($e),
    		];
	    	
        }
        
        return $arreglo;
    }
    
    /**
     * Función que mueve una DJ.
     * @param integer $trib_id Identificador de tributo.
     * @param string $obj_id Identificador de comercio.
     * @param integer $perdesde Período desde.
     * @param integer $perhasta Período hasta.
     * 
     */
    public function fiscalizaMoverDJ($trib_id,$obj_id,$perdesde,$perhasta)
    {
    	$sql = "SELECT dj_id " .
    			"FROM ddjj " .
    			"WHERE obj_id = '" . $obj_id . "' AND trib_id = " . $trib_id . " AND " .
    			"(anio*1000) + cuota BETWEEN " . $perdesde . " AND " . $perhasta;
		
		try
		{
			$data = Yii::$app->db->createCommand($sql)->queryAll();
			
			foreach ($data as $array)
			{
				$sql = "SELECT sam.uf_fiscaliza_mover(" . $array['dj_id'] . "," . Yii::$app->user->id . ")";
				
				Yii::$app->db->createCommand($sql)->execute();
				
				$arreglo = [
					'return' => 1,
	    			'mensaje' => '',
	    		];
			}
			
    	} catch (\Exception $e)
    	{
    		$arreglo = [
				'return' => 0,
    			'mensaje' => DBException::getMensaje($e),
    		];
    	} 
    	
    	return $arreglo;
    }
    
    /**
	 * Función que busca los datos de la BD para la lista avanzada de Fiscalización.
	 * @param string $cond Condición de búsqueda
	 * @param string $orden Orden de la búsqueda
	 * @return dataProvider DataProvider con el resultado de la búsqueda
	 */    
    public function buscaDatosListado($cond,$orden)
    {
    	$sql = "SELECT fisca_id, obj_id, obj_nom, expe, inspector_nom, to_char(fchalta,'dd/MM/yyyy') as fchalta, to_char(fchbaja,'dd/MM/yyyy') as fchbaja, est_nom, modif " .
    			"FROM v_fiscaliza WHERE " . $cond . " ORDER BY " . $orden;
    			
    	$count = Yii::$app->db->createCommand("SELECT COUNT(*) FROM v_fiscaliza WHERE " . $cond)->queryScalar();
    			
    	$dataProvider = new SqlDataProvider([
		 	'sql' => $sql,
		 	'key' => 'fisca_id',
		 	'totalCount' => (int)$count,
		 	'pagination'=> [
				'pageSize'=>20,
			],
        ]); 

        return $dataProvider;
    }
    
    public function Imprimir($id,&$sub1)
    {
    	$sql = "select * from v_fiscaliza where fisca_id=".$id;
    	$array = Yii::$app->db->createCommand($sql)->queryAll();
    	
    	$sql = "SELECT subcta, obj_id, trib_id, trib_nom, trib_cantanio, cast(CONCAT('r',rubro_id) as VARCHAR(10)) as rubro,rubro_id, rubro_nom, perdesde, perhasta, nperdesde, nperhasta, cant," .
    			"tipo,fiscaliza,est,est_nom,expe,obs,porc,fchmod,usrmod,modif " .
    			"FROM v_objeto_rubro " .
    			"WHERE obj_id = '" . $array[0]['obj_id']. "' AND fiscaliza = 1";
    	
    	
    	$sub1 = Yii::$app->db->createCommand($sql)->queryAll();
    	
    	return $array;
    }
    
    public function ImprimirReportes($fisc,$reporte,&$sub)
    {
    	$sql = "Select p.*,to_char(p.fchhab,'dd/mm/yyyy') fchhab,to_char(p.fchalta,'dd/mm/yyyy') fchalta, t.Titulo, t.Detalle From V_Fiscaliza_Print p ";
    	$sql .= "Left Join Texto t on Texto_id in (select texto_id from sam.rpt_fiscaliza where cod in (".$reporte."))";
    	$sql .= " Where fisca_id=" . $fisc . " and " . (date('Y') * 1000 + date('m')) . "  between PerDesde and PerHasta";
    	
    	$datos = Yii::$app->db->createCommand($sql)->queryAll();
    	
    	$sql = "select * from sam.rpt_fiscaliza where cod in (".$reporte.") order by cod";
    	$sub = Yii::$app->db->createCommand($sql)->queryAll();
    	return $datos;
    }
    
}
