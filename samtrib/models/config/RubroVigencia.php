<?php

namespace app\models\config;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;


use Yii;

/**
 * This is the model class for table "rubro_vigencia".
 *
 * @property integer $rubro_id
 * @property integer $perdesde
 * @property integer $perhasta
 * @property integer $tcalculo
 * @property integer $tminimo
 * @property string $alicuota
 * @property string $alicuota_atras
 * @property string $minimo
 * @property string $minalta
 * @property string $fijo
 * @property integer $canthasta
 * @property string $porc
 * @property string $fchmod
 * @property integer $usrmod
 */
class RubroVigencia extends \yii\db\ActiveRecord
{
	public $adesde;
	public $cdesde;
	public $ahasta;
	public $chasta;
	
	
	public function __construct(){
		
		parent::__construct();
		
		$this->alicuota_atras= 0;
		$this->fijo= 0;
		$this->minimo= 0;
		$this->minalta= 0;
		$this->porc= 0;
		$this->canthasta= 0;
	}
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'v_rubro_vigencia';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
	
	$ret= [];
	
	/**
	 * CAMPOS REQUERIDOS
	 */
	$ret[]= [
			['rubro_id', 'adesde', 'cdesde'],
			'required',
			'on' => ['insert', 'update']
			];
			
	$ret[]= [
			['ahasta', 'chasta'],
			'required',
			'on' => ['update']
			];
			
	$ret[]= [
			['tcalculo', 'tminimo', 'alicuota'],
			'required',
			'on' => ['insert', 'update']
			];
	/**
	 * FIN CAMPOS REQUERIDOS
	 */
	
	/**
	 * TIPO Y RANGO DE DATOS
	 */
	$ret[]= [
			['rubro_id'],
			'string',
			'min' => 8,
			'on' => ['insert', 'update'],
			'message' => 'Elija un {attribute}'
			];
			
	$ret[]= [
			['tcalculo'],
			'integer',
			'min' => 1,
			'on' => ['insert', 'update'],
			'message' => 'Elija un {attribute}'
			];		
			
	$ret[]= [
			['adesde', 'ahasta'],
			'integer',
			'min' => 1900,
			'max' => 9999,
			'on' => ['insert', 'update', 'delete']
			];
			
	$ret[]= [
			['cdesde', 'chasta'],
			'integer',
			'min' => 1,
			'max' => 999,
			'on' => ['insert', 'update', 'delete']
			];
			
	$ret[]= [
			['alicuota', 'alicuota_atras', 'minimo', 'minalta', 'fijo'],
			'number',
			'min' => 0,
			'on' => ['insert', 'update']
			];
			
	$ret[]= [
			['canthasta', 'tminimo'],
			'integer',
			'min' => 0,
			'on' => ['insert', 'update']
			];
			
	$ret[]= [
			'porc',
			'number',
			'min' => 0,
			'max' => 100,
			'on' => ['insert', 'update']
			];
	/**
	 * FIN TIPO Y RANGO DE DATOS
	 */
	
	/**
	 * VALORES POR DEFECTO
	 */
	$ret[]= [
			['alicuota_atras', 'minimo', 'minalta', 'fijo', 'canthasta', 'porc'],
			'default',
			'value' => 0,
			'on' => ['insert', 'update']
			];
			
	$ret[]= [
			'ahasta',
			'default',
			'value' => 9999,
			'on' => ['insert', 'update']
			];
			
	$ret[]= [
			'chasta',
			'default',
			'value' => 999,
			'on' => ['insert', 'update']
			];
	/**
	 * FIN VALORES POR DEFECTO
	 */
	
	$ret[]= [
			'rubro_id',
			'existe',
			'on' => ['insert']
			];
			
	$ret[]= [
			['adesde', 'ahasta', 'cdesde', 'chasta'],
			'verificarVigencia',
			'on' => ['insert', 'update']
			];		
	
	return $ret;
    }
	
	public function scenarios(){
		
		return [
			'insert' => ['rubro_id', 'nomen_id', 'adesde', 'ahasta', 'cdesde', 'chasta', 'tcalculo', 'tminimo', 'alicuota', 'alicuota_atras', 'minimo', 'minalta', 'fijo', 'canthasta', 'porc'],
			'update' => ['rubro_id', 'nomen_id', 'adesde', 'ahasta', 'cdesde', 'chasta', 'tcalculo', 'tminimo', 'alicuota', 'alicuota_atras', 'minimo', 'minalta', 'fijo', 'canthasta', 'porc'],
			'delete' => []
		];
	}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rubro_id' => 'Rubro',
            'perdesde' => 'Desde',
            'perhasta' => 'Hasta',
            'tcalculo' => 'Tipo de cálculo',
            'tminimo' => 'Tipo de mínimo',
            'alicuota' => 'Alícuota',
            'alicuota_atras' => 'Alícuota atrasada',
            'minimo' => 'Mínimo',
            'minalta' => 'Mínimo temporada alta',
            'fijo' => 'Fijo',
            'canthasta' => 'Cantidad límite hasta',
            'porc' => 'Porcentaje'
        ];
    }
    
    public function afterFind(){
    	
    	$this->adesde= intval($this->perdesde / 1000);
    	$this->cdesde= intval($this->perdesde % 1000);
    	
    	if($this->perhasta > 0){
    		$this->ahasta= intval($this->perhasta / 1000);
    		$this->chasta= intval($this->perhasta % 1000);
    	}
    }
    
    public function afterValidate(){
    	
    	if(!$this->hasErrors()){
    		$this->perdesde= $this->adesde * 1000 + $this->cdesde;
    		$this->perhasta= $this->ahasta * 1000 + $this->chasta;
    	}
    }
    
	public function grabar(){
		
		$scenario= $this->isNewRecord ? 'insert' : 'update';
		$this->setScenario($scenario);
		
		if(!$this->validate()) return false;
		
		$sql= "";
		
		if($this->isNewRecord){
			
			$sql= "Insert Into rubro_vigencia(rubro_id, perdesde, perhasta, tcalculo, tminimo, alicuota, alicuota_atras, minimo, minalta, fijo, canthasta, porc, fchmod, usrmod)" .
					"Values('$this->rubro_id', $this->perdesde, $this->perhasta, $this->tcalculo, $this->tminimo, $this->alicuota, $this->alicuota_atras" .
					", $this->minimo, $this->minalta, $this->fijo, $this->canthasta, $this->porc, current_timestamp, " . Yii::$app->user->id . ")";
		} else {
			
			$sql= "Update rubro_vigencia Set perhasta = $this->perhasta, tcalculo = $this->tcalculo, tminimo = $this->tminimo, alicuota = $this->alicuota, alicuota_atras = $this->alicuota_atras" .
					", minimo = $this->minimo, minalta = $this->minalta, fijo = $this->fijo, canthasta = $this->canthasta, porc = $this->porc" .
					", fchmod = current_timestamp, usrmod = " . Yii::$app->user->id . " Where rubro_id = '$this->rubro_id' And perdesde = $this->perdesde";
					
					
		}
		
		
		
		try{
			$res= Yii::$app->db->createCommand($sql)->execute() > 0;
		} catch(\Exception $e){
			
			$this->addError($this->rubro_id, $e->getMessage());
			return false;
		}
		
		return true;
	}

    public function borrar(){
    
    	$this->setScenario('delete');
    	if(!$this->validate()) return false;
    	
    	$sql= "Delete From rubro_vigencia Where rubro_id = '$this->rubro_id' And perdesde = $this->perdesde And perhasta = $this->perhasta";
    	
    	try{
    		Yii::$app->db->createCommand($sql)->execute();
    	} catch(\Exception $e){
    		$this->addError($this->rubro_id, 'Ocurrió un error al intentar realizar la acción');
    		return false;
    	}
    	
    	return true;
    }
    
    public function existe(){
    	
    	$sql= "Select Exists (Select 1 From rubro_vigencia Where perdesde= $this->adesde * 1000 + $this->cdesde And perhasta = $this->ahasta * 1000 + $this->chasta And rubro_id = '$this->rubro_id')";
    	$existe= Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	if($existe) $this->addError($this->rubro_id, 'La vigencia ya existe');
    }
	
	public function verificarVigencia(){
	
		$this->perdesde= $this->adesde * 1000 + $this->cdesde;
    	$this->perhasta= $this->ahasta * 1000 + $this->chasta;
		$this->nomen_id = substr( $this->rubro_id, 0, 1);
		
		$sql = "select count(*) from rubro_tnomen t where '$this->perdesde' BETWEEN t.perdesde and t.perhasta and '$this->perhasta' BETWEEN t.perdesde and t.perhasta and nomen_id='$this->nomen_id'";
		
		if (  intVal(Yii::$app->db->createCommand($sql)->queryScalar()) == 0 )
			$this->addError($this->rubro_id, 'La vigencia ingresada no esta dentro de la vigencia del Nomeclador.');
	}
    				 
    public function obtenerRubroUltimoVigencia($rubro_id){  
     	
     	$sql = "select * " .
     			"from rubro_vigencia rv " .
     			"inner join (select rubro_id, max(perdesde) perdesde from rubro_vigencia group by rubro_id) m " .
     			"on rv.rubro_id=m.rubro_id and rv.perdesde=m.perdesde " .
     			"where rv.rubro_id = '".$rubro_id . "'"; 	
        
        $model = RubroVigencia::findBySql($sql)->one(); 
        return $model;
    }
    
    				
    public function listaRubroVigencia($rubro_id){
   			
     	$sql = "select * " .
     		   "from rubro_vigencia " .
     		   "where rubro_id='".$rubro_id . "'".
     		   " order by perdesde";		 	
        
        $dataProvider = new SqlDataProvider([
            'sql' => $sql
        ]);
        return $dataProvider;
    }
	
	public function getRubrosVigencia( $nomen_id = '', $grupo = '', $rubro_desde = '', $rubro_hasta = '' ){
   			
     	$cond = date('Y') * 1000 + date('m') . ' BETWEEN perdesde and perhasta '; // filtro para trae solo vigentes
		
		if ( $nomen_id != '' )
			$cond .= ( $cond != '' ? ' and ' : '' ) . "nomen_id='$nomen_id'";
		
		if ( $grupo != '' )
			$cond .= ( $cond != '' ? ' and ' : '' ) . "grupo='$grupo'";	
			
		if ( $rubro_desde != '' and $rubro_hasta != '' )
			$cond .= ( $cond != '' ? ' and ' : '' ) . "rubro_id BETWEEN '$rubro_desde' and '$rubro_hasta'";	
		
		$sql = "select * from v_rubro_vigencia ";		 	
		if ( $cond != '' )
			$sql .= " where $cond";
        
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
			'key' => 'rubro_id',
			'pagination' => false
        ]);
        return $dataProvider;
    }
	
	public function validarVigenciaMasiva( $datos ){
	
		if ( $datos['nomeclador'] == '' )
			$this->addError( '0', 'Seleccione un Nomeclador.' );
			
		if ( !isset($datos['rubrosselct']) or $datos['rubrosselct'] == '' )
			$this->addError( '1', 'Seleccione al menos un Rubro.' );	
			
		if ( intVal($datos['nuevaVigenciaAnioDesde']) == 0 or intVal($datos['nuevaVigenciaMesDesde']) == 0 )
			$this->addError( '2', 'Ingrese el Periodo Desde.' );		
		
		if ( intVal($datos['nuevaVigenciaAnioHasta']) == 0 or intVal($datos['nuevaVigenciaMesHasta']) == 0 )
			$this->addError( '3', 'Ingrese el Periodo Hasta.' );		

		if ( intVal($datos['nuevaVigenciaAnioDesde'])*1000+intVal($datos['nuevaVigenciaMesDesde']) > intVal($datos['nuevaVigenciaAnioHasta'])*1000+intVal($datos['nuevaVigenciaMesHasta']) )		
			$this->addError( '4', 'Rango de Periodo mal ingresado.' );		
			
		if ( isset($datos['eliminarVigencia']) and $datos['eliminarVigencia'] == 0 ){
			if ( $datos['nuevaFormula'] == 0 and intVal($datos['referenciaAnio'])*1000+intVal($datos['referenciaMes']) == 0 )
				$this->addError( '5', 'Rango de Periodo mal ingresado.' );		
			
			if ( $datos['nuevaFormula'] == 1 ){
				if ( intVal($datos['tFormula']) == 0 )
					$this->addError( '6', 'Seleccione una Fórmula de Cálculo.' );		
				if ( intVal($datos['tMinimo']) == 0 )
					$this->addError( '7', 'Seleccione un Mínimo.' );
				if ( intVal($datos['alicuota']) < 0 or intVal($datos['minimo']) < 0 or intVal($datos['fijo']) < 0 )
					$this->addError( '8', 'Los valores deben ser positivos.' );		
				if ( !isset($datos['minimoTA']) or intVal($datos['minimoTA']) == 0 )
					$this->addError( '9', 'Ingrese el valor del mínimo para la Temporada Alta.' );	
			}
		}	
			
		return !$this->hasErrors();
	}
	
	public function grabarVigenciaMasiva( $datos ){
	
		$transaction = Yii::$app->db->beginTransaction();

		try{
			
			if ( isset($datos['cortarVigencia']) and $datos['cortarVigencia'] ){
				$sql = "select * from sam.uf_rubro_cortar('" . $datos['nomeclador'] . "','" . implode(",", $datos['rubrosselct']) . "'," . (intVal($datos['nuevaVigenciaAnioDesde'])*1000+intVal($datos['nuevaVigenciaMesDesde'])) . ")";
				Yii::$app->db->createCommand( $sql )->execute();
			}

			if ( isset($datos['eliminarVigencia']) and !$datos['eliminarVigencia'] ){
				$sql = "select * from sam.uf_rubro_act('" . $datos['nomeclador'] . "','" . implode(",", $datos['rubrosselct']) . "',";
				$sql .= (intVal($datos['nuevaVigenciaAnioDesde'])*1000+intVal($datos['nuevaVigenciaMesDesde']));
				$sql .= "," . (intVal($datos['nuevaVigenciaAnioHasta'])*1000+intVal($datos['nuevaVigenciaMesHasta']));
                $sql .= "," . (intVal($datos['referenciaAnio'])*1000+intVal($datos['referenciaMes']));
				$sql .= "," . floatVal($datos['porcFijo']) . "," . floatVal($datos['porcMinimo']);
				$sql .= "," . intVal(isset($datos['conDecimales'])) . "," . intVal($datos['tFormula']) . "," . intVal($datos['tMinimo']);
                $sql .= "," . floatVal($datos['alicuota']) . "," . floatVal($datos['minimo']) . "," . floatVal($datos['minimoTA']);
				$sql .= "," . floatVal($datos['fijo']) . "," . floatVal($datos['cantH']) . "," . floatVal($datos['porcentaje']) . "," . Yii::$app->user->id . ")";
				
				Yii::$app->db->createCommand( $sql )->execute();
			}

			$transaction->commit();

		} catch(\Exception $e ){

			$this->addError( null, $e->getMessage() );
			
			$transaction->rollback();
			return false;
		}
		
		return true;
	}

}
