<?php

namespace app\models\ctacte;
use yii\data\SqlDataProvider;
use app\utils\db\Fecha;

use Yii;

/**
 * This is the model class for table "intima".
 *
 * @property integer $inti_id
 * @property integer $lote_id
 * @property string $obj_id
 * @property string $num
 * @property integer $plan_id
 * @property integer $periodos
 * @property string $nominal
 * @property string $accesor
 * @property string $multa
 * @property string $ucm
 * @property integer $distrib
 * @property integer $caracter
 * @property string $fchimpreso
 * @property string $fchplazo
 * @property string $resultado
 * @property integer $est
 * @property string $tomo
 * @property string $folio
 * @property string $fchmod
 * @property integer $usrmod
 */
 
 /**
  * @property string titulo
  * @property integer tipo
  * @property string tipo_nom
  * @property integer tobj
  * @property string tobj_nom
  * @property integer trib_id
  * @property string trib_nom
  * @property integer cant
  * @property integer deuda
  * @property string detalle
  * @property string perdesde
  * @property string perhasta
  * @property string fchvtodesde
  * @property string fchvtohasta
  * @property string alta
  * @property string aprobacion
  * @property string modif
  * @property string fecha
  * @property string obs
  * @property string est_nom
  */
 
 
class Intima extends \yii\db\ActiveRecord
{
	
	//Creo las variables extras que usará el modelo
	  public $titulo;
	  public $tipo;
	  public $tipo_nom;
	  public $tobj;
	  public $tobj_nom;
	  public $trib_id;
	  public $trib_nom;
	  public $cant;
	  public $deuda;
	  public $detalle;
	  public $perdesde;
	  public $perhasta;
	  public $fchvtodesde;
	  public $fchvtohasta;
	  public $alta;
	  public $aprobacion;
	  public $modif;
	  public $fecha;
	  public $obs;
	  public $est_nom;
	  
	
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'intima';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lote_id', 'obj_id', 'periodos', 'nominal', 'accesor', 'multa', 'distrib', 'caracter', 'resultado', 'est', 'usrmod'], 'required'],
            [['lote_id', 'plan_id', 'periodos', 'distrib', 'caracter', 'est', 'usrmod'], 'integer'],
            [['nominal', 'accesor', 'multa', 'ucm'], 'number'],
            [['fchimpreso', 'fchplazo', 'fchmod'], 'safe'],
            [['obj_id', 'num'], 'string', 'max' => 8],
            [['resultado'], 'string', 'max' => 2],
            [['tomo', 'folio'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'inti_id' => 'Identificador de intimaciones',
            'lote_id' => 'Numero de lote',
            'obj_id' => 'Codigo de objeto',
            'num' => 'Codigo de num - responsable',
            'plan_id' => 'Identificador de plan',
            'periodos' => 'Cantidad de periodos',
            'nominal' => 'Monto nominal',
            'accesor' => 'Monto accesorios',
            'multa' => 'Monto multa',
            'ucm' => 'Valor de ucm',
            'distrib' => 'Codigo de distribuidor',
            'caracter' => 'Codigo de caracter',
            'fchimpreso' => 'Fecha de impresion',
            'fchplazo' => 'Fecha de plazo para la presentacion',
            'resultado' => 'Codigo de resultado de la visita',
            'est' => 'Estado',
            'tomo' => 'Tomo ',
            'folio' => 'Folio',
            'fchmod' => 'Fecha de modificacion',
            'usrmod' => 'Codigo de usuario que modifico',
        ];
    }
    
    /**
     * Función que obtiene los datos para cargar la grilla
     */
     public function cargarDatosGrilla($lote)
     {
     	if ($lote == '') $lote = -1;
     	
     	$sql = "SELECT inti_id,lote_id,obj_id,num,obj_nom,cuit,substring(dompos_dir,0,25) as dompos_dir,plan_id,periodos,total " .
     			"FROM v_intima WHERE lote_id = " . $lote . " ORDER BY obj_id";
     			
     	$count = Yii::$app->db->createCommand("SELECT COUNT(*) FROM v_intima WHERE lote_id = " . $lote)->queryScalar();
     			
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
      * Función que carga datos para visualización en el modelo
      */
      public function cargarDatosIntima($lote)
      {
      	
      	if (Yii::$app->db->createCommand("SELECT EXISTS (SELECT 1 FROM v_intima_lote WHERE lote_id = " . $lote . ")")->queryScalar() == 1)
      	{
      		$sql = "SELECT l.lote_id,l.titulo,l.tipo,l.tipo_nom,l.tobj,l.tobj_nom,l.trib_id,l.trib_nom,l.cant,l." .
      			"deuda,l.detalle,l.perdesde,l.perhasta,l.fchvtodesde,l.fchvtohasta,l.alta,l.aprobacion,l.modif,l.fecha,l.obs " .
      			"FROM v_intima_lote l WHERE l.lote_id = " . $lote;
      			
	      	$data = Yii::$app->db->createCommand($sql)->queryAll();
	      	
		      $this->titulo = $data[0]['titulo'];
			  $this->tipo = $data[0]['tipo'];
			  $this->tipo_nom = $data[0]['tipo_nom'];
			  $this->tobj = $data[0]['tobj'];
			  $this->tobj_nom = $data[0]['tobj_nom'];
			  $this->trib_id = $data[0]['trib_id'];
			  $this->trib_nom = $data[0]['trib_nom'];
			  $this->cant = $data[0]['cant'];
			  $this->deuda = $data[0]['deuda'];
			  $this->detalle = $data[0]['detalle'];
			  $this->perdesde = Fecha::bdToUsuario($data[0]['perdesde']);
			  $this->perhasta = Fecha::bdToUsuario($data[0]['perhasta']);
			  $this->fchvtodesde = Fecha::bdToUsuario($data[0]['fchvtodesde']);
			  $this->fchvtohasta = Fecha::bdToUsuario($data[0]['fchvtohasta']);
			  $this->alta = $data[0]['alta'];
			  $this->aprobacion = $data[0]['aprobacion'];	  
			  $this->modif = $data[0]['modif'];
			  $this->fecha = $data[0]['fecha'];
			  $this->obs = $data[0]['obs'];
			  
			  if ($data[0]['aprobacion'] == null)
			  	$this->est_nom = 'No Aprobado';
			  else 
			  	$this->est_nom = 'Aprobado';
			  
      	} else 
      	{
      		return 0;
      	}
      	
      	
      	return 1;
      		 	
      }
      
      /**
       * Función que devuelve los datos para la Búsqueda de Lote
       */
      public function getBuscar()
      {
      	
      	$sql = "SELECT lote_id,titulo,tipo_nom,cant,deuda,alta,aprobacion " .
      			"FROM v_intima_lote ORDER BY lote_id DESC";
      			
     	$dataProvider = new SqlDataProvider([
		 	'sql' => $sql,
        ]); 

        return $dataProvider;
      	
      }
      
      /**
       * Función que se ejecuta cuando se realiza la actualización de una observación
       */
      public function updateObs($lote_id,$obs)
      {
      	$sql = "UPDATE intima_lote SET obs = '" . $obs . "', fchmod = CURRENT_TIMESTAMP, usrmod = " . Yii::$app->user->id . " " .
      			"WHERE lote_id = " . $lote_id;
      			
      	$count = Yii::$app->db->createCommand($sql)->execute();
      	
      	if ($count > 0 )
      		return 1;
      	else 
      		return 2;
      	
      }
      
      /**
       * Función que se ejecuta al eliminar un lote de la BD
       */
      public function borrarLote($lote_id)
      {
      	
      	//Verificar que el lote se encuentre en la BD
		if(!Yii::$app->db->createCommand("SELECT EXISTS (SELECT 1 FROM intima_lote WHERE lote_id = " . $lote_id . ")")->queryScalar())
      		return 10;	//Retorno 10, que se corresponde con el código de mensaje para lote inexistente
      	
      	$sql = "SELECT sam.uf_inti_borra_lote(" . $lote_id . ")";
      	
      	$count = Yii::$app->db->createCommand($sql)->execute();
      	
      	if ($count > 0 )
      		return 1; //Retorno 1, que se corresponde con el código de mensaje de datos grabados
      	else 
      		return 2; //Retorno 2, que se corresponde con el código de mensaje de datos no guardados
      }
      
      /**
       * Función que se encarga de realizar la aprobación de un lote de la BD
       */
      public function aprobarLote($lote_id)
      {
      	//Verificar que el lote se encuentre en la BD y no se encuentra aprobado
      	if(!Yii::$app->db->createCommand("SELECT EXISTS (SELECT 1 FROM intima_lote WHERE lote_id = " . $lote_id . " AND usraprob IS NULL)")->queryScalar())
      		return 11;	//Retorno 11, que se corresponde con el código de mensaje para lote aprobado
      	
      	$sql = "SELECT sam.uf_inti_aprob_lote(" . $lote_id . ")";
      	
      	$count = Yii::$app->db->createCommand($sql)->execute();
      	
      	if ($count > 0 )
      		return 1; //Retorno 1, que se corresponde con el código de mensaje de datos grabados
      	else 
      		return 2; //Retorno 2, que se corresponde con el código de mensaje de datos no guardados
      }
      
      /**
       * Función que se ejecuta para procesar un lote
       */
      public function procesoLote($lote_id)
      {
      	//Verificar que el lote se encuentre en la BD y esté aprobado
      	if(!Yii::$app->db->createCommand("SELECT EXISTS (SELECT 1 FROM intima_lote WHERE lote_id = " . $lote_id . " AND usraprob IS NOT NULL)")->queryScalar())
      		return 12;	//Retorno 12, que se corresponde con el código de mensaje para lote no aprobado
      	
      	$sql = "SELECT sam.uf_inti_proc(" . $lote_id . "," . Yii::$app->user->id . ")";
      	
      	try{
      		$count = Yii::$app->db->createCommand($sql)->execute();
      		
      		if ($count > 0 )
      			return 1; //Retorno 1, que se corresponde con el código de mensaje de datos grabados
      		else 
      			return 2; //Retorno 2, que se corresponde con el código de mensaje de datos no guardados
      		
      	} catch (\Exception $e)
      	{
      		return 2;
      	}
      		
      }
      
      /**
       * Fnción que se encarga de eliminar un objeto de un lote
       */
      public function deleteObjeto($lote_id,$obj_id)
      {
      	$sql = "SELECT sam.uf_inti_borra_obj(" . $lote_id . ",('" . $obj_id . "'))";
      	
      	$count = Yii::$app->db->createCommand($sql)->execute();
      	
      	if ($count > 0 )
      		return 1; //Retorno 1, que se corresponde con el código de mensaje de datos grabados
      	else 
      		return 2; //Retorno 2, que se corresponde con el código de mensaje de datos no guardados
      }
      
      /**
       * Obtiene todas las entregas de intimaciones para el lote dado
       * 
       * @param int $lote_id Código del lote del cual se quieren obtener las entregas de intimaciones
       * 
       * @return Array Arreglo asociativo con las entregas de intimaciones correspondientes al lote dado.
       * Cada elemento del arreglo es de la forma ['obj_id' => ###, 'num' => 'string', 'obj_nom' => 'string', 'dompos_id' => 'string']
       */
      public function getEntregas($lote_id = 0, $sinResultados = false){
      	
      	if($lote_id == 0) return [];
      	
      	//se comprueba si el lote está aprobado
      	$sql = "Select Exists (Select lote_id From intima_lote Where lote_id = $lote_id And fchaprob Is Null)";
      	$noAprobado = Yii::$app->db->createCommand($sql)->queryScalar();
      	
      	//el lote todavia no ha sido aprobado
      	if($noAprobado)
      		return false;
      	
      	//el lote está aprobado y se procede a obtener los datos necesarios
      	$sql = "Select lote_id, inti_id, obj_id, obj_nom, dompos_dir From v_intima Where lote_id = " . $lote_id;

      	if($sinResultados)
      		$sql .= " And resultado = ". "''";
      	
      	return Yii::$app->db->createCommand($sql)->queryAll();
      }
      
      /**
       * Graba una sertie de entregas en la base de datos
       * 
       * @param int $lote_id - Codigo de lote
       * @param string $fecha - Fecha en que se producen las entregas
       * @param int $resultado - Codigo de resultado de las entregas
       * @param int $distribuidor - Codigo de distribidor que realizo las entregas
       * @param Array $inti_id - Codigos de las intimaciones que se vieron afectadas en la entrega
       * 
       * @return boolean - true si se han grabado todas las entregas, false si alguna fallo
       */
      public function grabarEntregasMultiples($lote_id, $fecha, $resultado, $distribuidor, $inti_id){
      	
      	if(count($inti_id) == 0)
      		return true;
      		
      	$usuario = Yii::$app->user->id;
      	
      	$trans = Yii::$app->db->beginTransaction();
      	$cantidad= count($inti_id);
      		
		for($i = 0; $i < $cantidad; $i++){
		
			$actual = $inti_id[$i];
		
			$sql = "Insert Into intima_entrega(inti_id, fecha, resultado, distrib, fchmod, usrmod) Values($inti_id[$i], '$fecha', '$resultado', $distribuidor, current_timestamp, $usuario)";
	      	
	      	if(Yii::$app->db->createCommand($sql)->execute() <= 0){
	      		$trans->rollBack();
	      		$this->addError($this->inti_id, 'error al intentar insertar los datos');
	      		return false;	
	      	}
	      	
	  		$sql = "Update intima Set resultado = '$resultado', distrib = $distribuidor, fchmod = current_timestamp, usrmod = $usuario Where inti_id = $actual";
	  		
	  		if(Yii::$app->db->createCommand($sql)->execute() <= 0){
	  			$trans->rollBack();
	  			$this->addError($this->inti_id, 'error al intentar actualizar los datos');
	  			return false;
	  		}
      	}
      	
      	$trans->commit();
      	return true;
      }
      
      /**
       * CONSULTA DE DATOS DE SEGUIMIENTO
       */
      
  /**
   * Obtiene un seguimiento individual a partir de un código de intimación. En caso de no proveerse el código de intimación, se buscará por código de objeto y código de lote.
   * 
   * @param int $inti_id = null - Código de intimación
   * @param string $obj_id = '' - Código de objeto
   * @param int $lote_id = 0 - Código de lote
   */
  public static function getSeguimiento($inti_id = null, $obj_id = '', $lote_id = 0)
  {
	$sql = "Select * From v_intima Where ";
	
	$sql .= ($inti_id !== null) ? "inti_id = $inti_id" : "obj_id = '$obj_id' And lote_id = $lote_id";
	
	$ret= Yii::$app->db->createCommand($sql)->queryOne();
	return $ret != null ? $ret : array_fill_keys(['inti_id', 'lote_id', 'obj_id', 'obj_nom', 'num', 'cuit', 'dompos_dir', 'plan_id', 'nombre', 'tel',
								'periodos', 'nominal', 'accesor', 'multa', 'ucm', 'total', 'fchimpreso', 'fchplazo', 'caracter', 'caracter_nom', 'resultado', 'resultado_nom', 'est', 'est_nom', 'distrib'], null);
  }
  
  /**
   * Obtiene los períodos de la intimación dada
   * 
   * @param int $inti_id - Código de intimación
   * 
   * @return Array - Cada elemento del arreglo es a su vez un arreglo que contiene los datos trib_nom, obj_id, subcta, anio, cuota, nominal, accesor, multa y est
   */
  public static function getPeriodos($inti_id = 0)
  {
  	
  	$sql = "Select trib_nom, obj_id, subcta, anio, cuota, nominal, accesor, multa, est, (nominal+accesor+multa) as total From v_intima_periodo Where inti_id = $inti_id";
  	
  	return Yii::$app->db->createCommand($sql)->queryAll();
  }
  
  /**
   * Obtiene las entregas del seguimiento dado
   * 
   * @param int $inti_id - Código de intimación del seguimiento
   * 
   * @return Array - Cada elemento del arreglo es a su vez un arreglo que contiene los datos fecha, resultado, distrib_nom y modif 
   */
  public static function getEntregasSeguimiento($inti_id = 0){
  	
  	$sql = "Select fecha, resultado, resultado_nom, distrib_nom, modif From v_intima_entrega Where inti_id = $inti_id";
  	
  	return Yii::$app->db->createCommand($sql)->queryAll();
  }
      
  /**
   * Obtiene las etapas para un lote y objeto dados
   * 
   * @param int $lote_id = 0 - Código del lote
   * @param string $obj_id = '' - Código del objeto
   * 
   * @return Array - Cada elemento del arreglo es a su vez un arreglo que contiene los datos fecha, etapa_nom y detalle    
   * */
  public static function getEtapas($lote_id = 0, $obj_id = ''){
  	
  	$sql = "Select fecha, etapa_nom, detalle From v_intima_etapa Where lote_id = $lote_id And obj_id = '$obj_id'";
  	
  	return Yii::$app->db->createCommand($sql)->queryAll();
  }
  
  /**
   * Obtiene las esperas para el codigo de objeto dado
   * 
   * @param string $obj_id = '' - Codigo del objeto
   * 
   * @return Array - Cada elemento del arreglo es a su vez un arreglo que contiene los datos trib_id, trib_nom, perdesde, perhasta, fchdesde, fchhasta y obs
   */
  public static function getEsperas($obj_id = ''){
  	
  	$sql = "Select trib_id, trib_nom, perdesde, perhasta, fchdesde, fchhasta, obs From v_intima_espera Where obj_id = '$obj_id'";
  	
  	return Yii::$app->db->createCommand($sql)->queryAll();
  }
      
  /**
   * FIN DE CONSULTA DE DATOS DE SEGUIMIENTO
   */
  
  /**
   * CONSULTAS DE MODIFICACION E INSERCION DE DATOS DE SEGUIMIENTO
   */
      
  /**
   * Modifica los datos del seguimiento
   * Los errores de validacion se guardan en el modelo
   * 
   * @param string $tomo - Tomo
   * @param string $folio - Folio
   * @param string $plazo - Fecha de la forma 'dd/mm/yyyy'
   * 
   * @return boolean - true si se ha modificado el registro correctamente, false de lo contrario
   * 
   */
  public function modificarSeguimiento($tomo, $folio, $plazo){
  	
  	if(!$this->validarSeguimiento($tomo, $folio, $plazo))
  		return false;
  	
  	$sql = "Update intima Set tomo = '$tomo', folio = '$folio', fchplazo = '$plazo' Where inti_id = $this->inti_id";
  	
  	return Yii::$app->db->createCommand($sql)->execute() > 0;
  }
      
  /**
   * Inserta una nueva entrega en la base de datos
   * Los errores de validacion se guardan en el modelo
   * 
   * @param string $fecha - Fecha en que se produjo la entrega en formato 'dd/mm/yyyy'
   * @param int $resultado - Resultado de la entrega
   * @param int $distribuidor - Codigo del distribuidor que realizo la entrega
   * 
   * @return boolean - true si se ha grabado el registro correctamente, false de lo contrario 
   */
	public function nuevaEntrega($fecha, $resultado, $distribuidor){

		if(!$this->validarEntrega($fecha, $resultado, $distribuidor))
			return false;

		$sql = "Insert Into intima_entrega(inti_id, fecha, resultado, distrib, fchmod, usrmod) Values(" .
				"$this->inti_id, '$fecha', '$resultado', $distribuidor, current_timestamp, " . Yii::$app->user->id . ")";
				
		return Yii::$app->db->createCommand($sql)->execute() > 0;
	}
       
   /**
	* Inserta una nueva etapa en la base de datos
	* Los errores de validacion se guardan en el modelo
	* 
	* @param string $fecha - Fecha en formato 'dd/mm/yyyy'
	* @param int $etapa - Etapa
	* @param string $detalle - Detalle de la etapa
	* 
	* return boolean - true si se ha grabadao el registro correctamente, false de lo contrario
	*/
   public function nuevaEtapa($fecha, $etapa, $detalle){
    	
    	if(!$this->validarEtapa($fecha, $etapa, $detalle))
    		return false;
    	
    	$sql = "Insert Into intima_etapa(inti_id, fecha, obj_id, lote_id, etapa, detalle, genauto, fchmod, usrmod) Values(" .
    			"$this->inti_id, '$fecha', '$this->obj_id', $this->lote_id, $etapa, '$detalle', 0, current_timestamp, " . Yii::$app->user->id . ")";
    	
    	return Yii::$app->db->createCommand($sql)->execute() > 0;
    }
    
    
	/**
	 * Borra los de una tabla auxiliar de intimacion
	 * Los errores de validacion se guardan en el modelo
	 * 
	 * @param int $auxiliar - Dato auxiliar a borrar
	 * 	1= Entrega
	 * 	2= Etapa
	 * 	3= Espera
	 * 
	 * @param string $fecha - Fecha. Para espera es Fecha Desde
	 * @param int $trib_id = null - Codigo del tributo
	 * @param int $adesde = null - Año del periodo desde
	 * @param int $cdesde = null - Cuota del periodo desde
	 * 
	 * 
	 * @return boolean - true si se ha eliminado correctamente el registro, false de lo contrario
	 */
	public function borrarAuxiliarSeguimiento($auxiliar, $fecha, $trib_id = null, $adesde = null, $cdesde = null){
		
		$sql = "";
		
		switch($auxiliar){
			
			//entrega
			case 1 :
			
				$sql = "Delete From intima_entrega Where inti_id = $this->inti_id And fecha = '$fecha'";
				break;
				
			//etapa
			case 2 :
					
				$sql = "Delete From intima_etapa Where fecha = '$fecha' And inti_id = $this->inti_id";
				break;
			
			//espera. No se elimina, se le cambia el estado a 'B'
			case 3 : 
				
				$adesde = intval($adesde);
				$cdesde = intval($cdesde);
				$ahasta = $adesde + 1;
				$chasta = $cdesde;
			
				if($this->validarEspera($trib_id, $adesde, $cdesde, $ahasta, $chasta, $fecha, $fecha, ''))
					return false;
				
				$perdesde = $adesde * 1000 + $cdesde;
			
				$sql = "Update intima_espera set est = 'B' Where obj_id = '$this->obj_id' And fchdesde = '$fecha' And trib_id = $trib_id And perdesde = $perdesde";
				break;
			
			default :
				return false;
		}
		
		return Yii::$app->db->createCommand($sql)->execute() > 0;
	}
    
    
	
	/**
	 * Inserta una nueva espera en la base de datos
	 * Los errores de validacion se guardan en el modelo
	 * 
	 * @param int $trib_id - Codigo del tributo
	 * @param int $adesde - Año del periodo desde
	 * @param int $cdesde - Cuota del periodo desde
	 * @param int $ahasta - Año del periodo hasta
	 * @param int $chasta - Cuota del periodo hasta
	 * @param string $fecha - Fecha desde en formato 'dd/mm/yyyy'
	 * @param string $fechaHasta - Fecha hasta en formato 'dd/mm/yyyy'
	 * @param string $obs - Observaciones realizadas sobre la espera
	 * 
	 * @return boolean - true si se ha grabado correctamente la espera, false de lo contrario
	 */
   public function nuevaEspera($trib_id, $adesde, $cdesde, $ahasta, $chasta, $fecha, $fechaHasta, $obs){
   	
		if(!$this->validarEspera($trib_id, $adesde, $cdesde, $ahasta, $chasta, $fecha, $fechaHasta, $obs))
			return false;
			
		$adesde = intval($adesde);
		$cdesde = intval($cdesde);
		$ahasta = intval($ahasta);
		$chasta = intval($chasta);
			
		$sql = "Insert Into intima_espera(obj_id, trib_id, perdesde, perhasta, fchdesde, fchhasta, obs, est, fchmod, usrmod) Values(" .
				"'$this->obj_id', $trib_id, ($adesde * 1000 + $cdesde), ($ahasta * 1000 + $chasta), '$fecha'," .
				"" . ($fechaHasta == null ? 'null' : "'$fechaHasta'") .
				", '$obs', 'A', current_timestamp, " . Yii::$app->user->id .")";
		
		return Yii::$app->db->createCommand($sql)->execute() > 0;
   }
   /**
	 * FIN CONSULTAS DE MODIFICACION E INSERCION DE DATOS DE SEGUIMIENTO
	 */
   /**
	* VALIDACIONES DE SEGUIMIENTO
	*/
	    
  /**
   * Valida que los datos a modificar del seguimiento sean validos
   * Los errores de validacion se guardan en el modelo
   * 
   * @param string $tomo - Tomo
   * @param string $folio - Folio
   * @param string $plazo - Fecha de la forma 'dd/mm/yyyy'
   * 
   * return boolean - true si se han pasado las validaciones, false de lo contario
   * 
   */
  public function validarSeguimiento($tomo, $folio, $plazo){
  	
  	$res = true;
  	
  	if($this->inti_id <= 0){
  		$this->addError($this->inti_id, 'Debe cargar una intimación para poder realizar la acción');
	return false;
	}

	//existe intimacion en la base de datos
	$sql = "Select Exists(Select inti_id From intima Where inti_id = $this->inti_id)";
	
	if(!Yii::$app->db->createCommand($sql)->queryScalar()){
		$this->addError($this->inti_id, "La Intimación no existe");
		$res = false;
	}

	//tomo es valido
	if(strlen($tomo) > 10 || strlen($tomo) == 0){
		$this->addError($this->inti_id, "El Tomo es incorrecto");
		$res = false;
	}
	
	//folio es valido
	if(strlen($folio) > 10 || strlen($folio) == 0){
		$this->addError($this->inti_id, "El Folio es incorrecto");
		$res = false;
	}
	
	//plazo de presentacion es valido
	$errorPlazo = !Fecha::isFecha($plazo);
		
	if($errorPlazo)
		$this->addError($this->inti_id, "El Plazo de Presentación es incorrecto");
	  		  	
	return $res && !$errorPlazo;
	}
  
  /**
   * Valida que una entrega sea valida
   * Los errores de validacion se guardan en el modelo
   * 
   * @param string $fecha - Fecha en que se produjo la entrega en formato 'dd/mm/yyyy'
   * @param int $resultado - Resultado de la entrega
   * @param int $distribuidor - Distribuidor que realizo la entrega
   * 
   * return boolean - true en caso de que se haya pasado las validaciones, false de lo contario
   */
	public function validarEntrega($fecha, $resultado, $distribuidor){
	
		$res = true;
		
		//plazo de presentacion es valido
		$errorFecha = !Fecha::isFecha($fecha);
		
		if(!$errorFecha){
			
			//ya existe una entrega con esa fecha
			$sql = "Select Exists(Select 1 From v_intima_entrega Where fecha = '$fecha' And inti_id = $this->inti_id)";
			
			if(Yii::$app->db->createCommand($sql)->queryScalar()){
				$this->addError($this->inti_id, "Ya hay una entrega registrada para esa fecha");
				$res = false;	
			}			
		} else 	$this->addError($this->inti_id, "La Fecha es incorrecta");
		
		//resultado
		if(empty(trim($resultado))){
			$this->addError($this->inti_id, "Elija un resultado");
			$res = false;
		}
		
		//distribuidor
		if(intval($distribuidor) <= 0){
			$this->addError($this->inti_id, "Elija un distribuidor");
			$res = false;
		}
		
		return $res && !$errorFecha;
	}
	
	/**
	 * Valida que una etapa sea valida para ser guardada
	 * Los errores de validaciones se guardaran en el modelo
	 * 
	 * @param string $fecha - Fecha de la etapa en formato 'dd/mm/yyyy'
	 * @param int $etapa - Codigo de la etapa
	 * @param string $detalle - Detalle de la etapa
	 * 
	 * @return boolean - true si se ha pasado la validacion, false de lo contrario
	 */
	public function validarEtapa($fecha, $etapa, $detalle){
		
		$res = true;
		
		//fecha
		$errorFecha = !Fecha::isFecha($fecha);
		
		if($errorFecha)
			$this->addError($this->inti_id, "La Fecha es incorrecta");
		else{
			
			//la fecha es unica para el inti_id
			$sql = "Select Exists(Select 1 From intima_etapa Where fecha = '$fecha' And inti_id = $this->inti_id)";
			
			if(Yii::$app->db->createCommand($sql)->queryScalar()){
				$this->addError($this->inti_id, "Ya hay un Etapa registrada para esa Fecha");
				$res = false;
			}
		}
		
		//etapa	
		if(intval($etapa) <= 0){
			$this->addError($this->inti_id, "Elija una etapa");
			$res = false;
		}
		
		$detalle = trim($detalle);
		
		if(empty($detalle)){
			$this->addError($this->inti_id, "Ingrese un Detalle");
			$res = false;
		} else if(strlen($detalle) > 500){
			$this->addError($this->inti_id, "El Detalle ingresado es muy largo");
			$res = false;
		}
		
		return $res && !$errorFecha;
	}
	
	/**
	 * Valida que los datos de espera sean validos para guardar.
	 * Los errores de las validaciones seran guardados en el modelo
	 * 
	 * @param int $trib_id - Codigo del tributo
	 * @param int $adesde - Año del periodo desde
	 * @param int $cdesde - Cuota del periodo desde
	 * @param int $ahasta - Año del periodo hasta
	 * @param int $chasta - Cuota del periodo hasta
	 * @param string $fecha - Fecha desde en formato 'dd/mm/yyyy'
	 * @param string $fechaHasta - Fecha hasta en formato 'dd/mm/yyyy'
	 * @param string $obs - Observaciones
	 * 
	 * @return boolean - True en caso de que se hayan pasado las validaciones, false de lo contrario
	 */
	public function validarEspera($trib_id, $adesde, $cdesde, $ahasta, $chasta, $fecha, $fechaHasta, $obs){
		
		$res = true;
		
		$perdesde = $adesde * 1000 + $cdesde;
		$perhasta = $ahasta * 1000 + $chasta;
		
		//periodos no negativos
		if($adesde < 0 || $cdesde < 0  || $ahasta < 0 || $chasta < 0){
			$this->addError($this->inti_id, "Los periodos no pueden ser negativos");
			$res = false;
		}
		
		//periodo desde
		if($res && $perdesde > 10000000){
			$this->addError($this->inti_id, "El periodo desde es muy grande");
			$res = false;
		}
		
		//periodo hasta
		if($res && $perhasta > 10000000){
			$this->addError($this->inti_id, "El periodo hasta es muy grande");
			$res = false;
		}
		
		//rango de periodos. Se valida si los formatos son validos
		if($res && $perdesde > $perhasta){
			$this->addError($this->inti_id, "Rango de periodos invalidos");
			$res = false;
		}
		
		//fecha
		$errorFecha = !Fecha::isFecha($fecha);
		
		if($errorFecha){
			$this->addError($this->inti_id, "La Fecha no es valida");
			$res = false;
		}
		
		//fecha hasta. El valor a insertar puede ser null, se valida si no es nulo
		if($res && $fechaHasta !== null){
			$errorFechaHasta = !Fecha::isFecha($fechaHasta);
		
			if($errorFechaHasta){
				$this->addError($this->inti_id, "La Fecha Hasta no es valida");
				$res = false;
			}
			
			//fecha es anterior a fecha hasta
			if(!$errorFecha && !$errorFechaHasta && !Fecha::menor($fecha, $fechaHasta)){
				$this->addError($this->inti_id, "Fecha debe ser anterior a Fecha Hasta");
				$res = false;
			}
		}
		
		//obs
		$obs = trim($obs);
		
		if(strlen($obs) > 500){
			$this->addError($this->inti_id, "La Observación es demasiado larga");
			$res = false;
		}
		
		//no existe el mismo registro
		if($res){
			
			//verifica si hay un registro para la fecha dada y el periodo
			$sql = "Select Exists(Select 1 From v_intima_espera Where obj_id = '$this->obj_id' And trib_id = $trib_id And fchdesde = '$fecha' And perdesde = $adesde * 1000 + $cdesde)";
			
			if(Yii::$app->db->createCommand($sql)->queryScalar()){
				$this->addError($this->inti_id, "Ya hay un registro para esa Fecha y Periodo");
				$res = false;
			}
		}
		
		return $res;
	}
  /**
   * FIN VALIDACIONES DE SEGUIMIENTO
   */
     
   public function ImprimirPrevio($lote,$caract,$result,$est,$distrib,$orden,$mostrar,&$error,&$consusr)
   {
    	$sql = "Select count(*) From Intima_lote Where Lote_id =".$lote." and FchAprob is null";
   		$cant = Yii::$app->db->createCommand($sql)->queryScalar();
   		if ($cant > 0 and $mostrar==0) {
   			$error = 'El Lote No está Aprobado';
   			return null;
   		}
   		
   		$sql = "Select count(*) From Intima_lote Where Lote_id =".$lote." and FchProc is null and (cast(fchaprob as date ) <> current_date)";
   		$cant = Yii::$app->db->createCommand($sql)->queryScalar();
   		if ($cant > 0 and $mostrar==0) {
   			$consusr = 'Se recomienda correr el proceso de seguimiento antes de imprimir. Continua?';
   			return null;
   		}
    	
    	$cond = " Where Lote_id = ".$lote;
    	if ($caract != 0) $cond .= " and Caracter = ".$caract;
    	if ($result != 0) $cond .= " and Resultado = ".$result;
    	if ($est != 0) $cond .= " and Est = ".$est;
    	if ($distrib != 0) $cond .= " and Distrib = ".$distrib;
    	
    	$sql = "Select count(*) From v_intima ".$cond;
    	$count = Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	$sql = "Select obj_id,num,obj_nom,plan_id,periodos,nominal,accesor,multa,total From v_intima ".$cond;
	    	
    	if ($orden == 0){
    		 $sql .= " Order by Distrib";
    	}elseif ($orden == 1 or $orden == 2 or $orden == 2){
    		$sql .= " Order by dompos_dir";
    	}else {
    		$sql .= " Order by Obj_id";
    	}
    	
    	$dataProvider = new SqlDataProvider([
		 	'sql' => $sql,
		 	'key' => 'obj_id',
		 	'totalCount' => (int)$count,
		 	'pagination'=> [
				'pageSize'=>(int)$count,
			],
        ]); 
   		
   		return $dataProvider;
    }
    
    public function Imprimir($lote_id,$texto_id,$objetos,$orden,$sinmonto,$agrupado,$porcuota,&$sub1,&$sub2,&$sub3)
    {
    	$sql = "Delete From temp.Intima_Ctacte_Temp";
    	Yii::$app->db->createCommand($sql)->execute();
    	
    	$sql = "Delete From temp.Intima_Ctacte_cuota";
    	Yii::$app->db->createCommand($sql)->execute();
    	
    	$sql = "Delete From temp.Intima_Temp Where Usr_id =".Yii::$app->user->id;
    	Yii::$app->db->createCommand($sql)->execute();
    	
    	$obj = explode(',',$objetos);
    	for ($i=0; $i<count($obj);$i++)
    	{
    		$sql = "Insert into temp.Intima_Temp Values ('".$obj[$i]."',".Yii::$app->user->id.")";
    		Yii::$app->db->createCommand($sql)->execute();
    	}
    	
    	if ($orden == 0 or $orden == 1 or $orden == 4){
            $sql = "Select distinct i.*,to_char(fchalta,'dd/mm/yyyy') fchalta From v_intima_Reporte i";
    	}elseif ($orden == 2) {
            $sql = "Select i.*,to_char(fchalta,'dd/mm/yyyy') fchalta From v_intima_Reporte_Comercio i";
    	}elseif ($orden == 3){
            $sql = "Select i.* From v_intima_Reporte_Plan i";
    	}
        $sql .= " Left Join temp.Intima_Temp t on i.Obj_id=t.Obj_id ";
        $sql .= " Where Lote_id = " . $lote_id . " and t.Usr_id=".Yii::$app->user->id." and not t.Obj_id is null";
        if ($orden == 0) {
            $sql .= " Order by dompos_dir";
        }elseif ($orden == 1 or $orden == 2 or $orden == 3){
            $sql .= " Order by dompos_dir";
        }else {
            $sql .= " Order by i.Obj_id";
        }
        
        $array = Yii::$app->db->createCommand($sql)->queryAll();
        
        $sql = "Select Tipo From Intima_Lote where Lote_id = " . $lote_id;
        $tint = Yii::$app->db->createCommand($sql)->queryScalar();
        
        for ($i=0; $i<count($array); $i++){
        	$sql = "Select Detalle From sam.uf_texto_Intima(" . $array[$i]["inti_id"] . "," . $texto_id . ")";
            $array[$i]["mensaje"] = Yii::$app->db->createCommand($sql)->queryScalar();
            
            $sql = "Select Plan_id From Intima where inti_id = " . $array[$i]["inti_id"];
            $plan_id = Yii::$app->db->createCommand($sql)->queryScalar();
            
            if ($porcuenta == 0){
            	if ($tint != 6 and $tint != 7){
                    $sql = "Select * From sam.uf_inti_print_deuda (" . $array[$i]["inti_id"] . ")";
            	}else {
                    $sql = "Select * From sam.uf_inti_print_plan (".$array[$i]["inti_id"].",".$plan_id.")";
            	}
                $print = Yii::$app->db->createCommand($sql)->queryAll();
                for ($j=0; $j<count($print); $j++){
                	if ($print[$j]['detalle'] != ''){
                		// inserto en la tabla temporal 
                        $sql = "Insert into temp.Intima_CtaCte_Temp values(".$array[$i]["inti_id"].",'".$print[$j]["obj_id"]."',".$print[$j]["subcta"].",".$print[$j]["trib_id"].",'";
                        $sql .= $print[$j]["trib_nom"]."',".$print[$j]["anio"].",'".$print[$j]["detalle"]."',".($sinmonto == 1 ? 0 : $print[$j]["monto"]).",";
                        $sql .= ($sinmonto == 1 ? 0 : $print[$j]["accesor"]) . "," . ($sinmonto == 1 ? 0 : $print[$j]["multa"]) . "," . ($sinmonto == 1 ? 0 : $print[$j]["total"]).", '".$print[$j]["obj_dato"]."')";
                        Yii::$app->db->createCommand($sql)->execute();
                	}
                }
                
            }else {
            	$sql = "Select * From sam.uf_inti_print_cuota (".$array[$i]["inti_id"].")";
            	$print = Yii::$app->db->createCommand($sql)->queryAll();
            	
            	for ($j=0; $j<count($print); $j++){
            		$sql = "Insert into temp.Intima_CtaCte_Cuota values(".$array[$i]["inti_id"].",'".$print[$j]["obj_id"]."',".$print[$j]["subcta"].",".$print[$j]["trib_id"].",'";
                    $sql .= $print[$j]["trib_nom"]."',".$print[$j]["anio"].",".$print[$j]["cuota"].",".($sinmonto == 1 ? 0 : $print[$j]["monto"]).",";
                    $sql .= ($sinmonto == 1 ? 0 : $print[$j]["accesor"]).",".($sinmonto == 1 ? 0 : $print[$j]["multa"]).",".($sinmonto == 1 ? 0 : $print[$j]["total"]).",";
                    $sql .= ($sinmonto == 1 ? 0 : $print[$j]["ucm"]).",'".$print[$j]["expe"]."','".str_replace($print[$j]["obs"],"/Intimado en Lote:".$lote_id."/", "")."')";
                    Yii::$app->db->createCommand($sql)->execute();
            	}
            }
        }
        
        $sql = "Select * from temp.Intima_Ctacte_Temp  ";
        $sub1 = Yii::$app->db->createCommand($sql)->queryAll();
        
        if ($agrupado == 1){
            $sql = "Select inti_id, obj_id, obj_dato, trib_id, trib_descr, sum(monto) as monto, sum(accesor) as accesor, sum(multa) as multa, sum(total) as total ";
            $sql .= "from temp.Intima_Ctacte_Temp group by inti_id, obj_id, obj_dato, trib_id, trib_descr order by obj_id, trib_id";
            $sub2 = Yii::$app->db->createCommand($sql)->queryAll();
        }elseif ($porcuota == 1){
            $sql = "Select * from temp.Intima_Ctacte_cuota  ";
            $sub3 = Yii::$app->db->createCommand($sql)->queryAll();
        }
        
        return $array;
    }
    
    public function ImprimirResu($id)
    {
    	$sql = "Select *,to_char(fchinti,'dd/mm/yyyy') fchinti From v_intima_Print Where Lote_id=".$id;
        $array = Yii::$app->db->createCommand($sql)->queryAll();
   		   		
   		return $array;
    }
    
    public function ImprimirEsta($id,&$sub1,&$sub2)
    {
    	$sql = "Select * From v_intima_lote Where Lote_id=".$id." Order by Lote_id Desc";
        $array = Yii::$app->db->createCommand($sql)->queryAll();
        
        $sql = "Select lote_id, r.cod, r.nombre, count(*), cast(sum(nominal+accesor+multa) as varchar(15)) as monto ";
        $sql .= "From intima i left join intima_tresult r on coalesce(i.resultado,'')=r.cod ";
        $sql .= "Where(Lote_id = " .$id. ") ";
        $sql .= "Group by lote_id, r.cod, r.nombre order by lote_id, r.cod";
        $sub1 = Yii::$app->db->createCommand($sql)->queryAll();
        
        $sql = "Select lote_id, e.cod, e.nombre, count(*), cast(sum(nominal+accesor+multa) as varchar(15)) as monto ";
        $sql .= "From intima i left join intima_test e on coalesce(i.est,0)=e.cod ";
        $sql .= "Where(Lote_id = " .$id. ") ";
        $sql .= "Group by lote_id, e.cod, e.nombre order by lote_id, e.cod";
        $sub2 = Yii::$app->db->createCommand($sql)->queryAll();
   		   		
   		return $array;
    }
    
    public function ImprimirSeg($lote,$obj_id,&$sub1,&$sub2)
    {
    	$sql = "Select *,to_char(fchinti,'dd/mm/yyyy') fchinti From v_intima_Reporte Where Lote_id = " . $lote . " and Obj_id = '" . $obj_id . "'";
        $array = Yii::$app->db->createCommand($sql)->queryAll();
        
        $sql = "Select * From v_intima_Entrega e Left Join Intima i on e.inti_id = i.inti_id ";
        $sql .= "Where i.Lote_id = " . $lote . " and i.Obj_id = '" . $obj_id . "'";
        $sub1 = Yii::$app->db->createCommand($sql)->queryAll();
        
        $sql = "Select * From v_intima_Etapa Where Lote_id = " . $lote . " and Obj_id='" . $obj_id . "'";
        $sub2 = Yii::$app->db->createCommand($sql)->queryAll();
   		   		
   		return $array;
    }
}
