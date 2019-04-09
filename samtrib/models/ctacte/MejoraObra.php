<?php

namespace app\models\ctacte;

use Yii;

use yii\data\ArrayDataProvider;

use app\utils\helpers\DBException;
use app\utils\db\Fecha;
use app\utils\db\utb;
use app\models\ctacte\MejoraTCalculo;
use app\models\ctacte\MejoraCuadra;
use app\models\ctacte\Frentistas;

class MejoraObra extends \yii\db\ActiveRecord{

	public $est_nom;
	public $modif;
	public $cuadras;
	public $tobra_nom;
	public $tcalculo_nom;
	public $cta_nom;
	public $texto_nom;
	
	public function __construct(){

		$this->getCuadras();
	
	}

	public static function tableName(){

		return 'mej_obra';
	}

	public function rules(){

		return [
			
			[['tobra', 'nombre', 'tcalculo', 'cta_id'], 'required', 'on' => ['nuevo', 'modificar'] ],
			
			[ ['nombre', 'tobra', 'decreto', 'obs'], 'string' ],
			[ ['fchini', 'fchfin'], 'safe' ],
			[ ['cta_id', 'tcalculo', 'texto_id'], 'integer' ],
			[ ['bonifobra', 'totalfrente', 'totalsupafec', 'valortotal', 'valormetro', 'fijo'], 'number' ],
			
			[ ['tcalculo', 'totalfrente', 'totalsupafec', 'valortotal', 'valormetro', 'fijo'], 'validarTCalculo', 'on' => ['nuevo', 'modificar'] ],
			
			[['fchini'], 'compare', 'compareAttribute'=>'fchfin', 'operator'=>'<', 'on' => ['nuevo', 'modificar']],
			
			[ ['obra_id'], 'validarExistenciaFrente', 'on' => ['eliminar'] ],
			
		];
		
	}

	public function scenarios(){

		return [
			
			'nuevo' => [
				'nombre', 'tobra', 'decreto', 'fchini', 'fchfin','cta_id', 'tcalculo', 'bonifobra', 'totalfrente', 'totalsupafec', 'valortotal', 'valormetro',
				'fijo','texto_id','obs', 'cuadras'
			],
			
			'modificar' => [
				'obra_id','nombre', 'tobra', 'decreto', 'fchini', 'fchfin','cta_id', 'tcalculo', 'bonifobra', 'totalfrente', 'totalsupafec', 'valortotal', 'valormetro',
				'fijo','texto_id','obs', 'cuadras'
			],
			
			'eliminar' => [
				'obra_id'
			],
			
		];
	}

	public function attributeLabels(){

		return [
			'cta_id' => 'Cuenta',
			'fchini' => 'Fecha Inicio',
			'fchfin' => 'Fecha Fin',
		];
	}

	public function beforeValidate(){

		$this->tcalculo = intVal( $this->tcalculo );
		$this->texto_id = intVal( $this->texto_id );
		$this->bonifobra = floatVal( $this->bonifobra );
		$this->totalfrente = floatVal( $this->totalfrente );
		$this->totalsupafec = floatVal( $this->totalsupafec );
		$this->valortotal = floatVal( $this->valortotal );
		$this->valormetro = floatVal( $this->valormetro );
		$this->fijo = floatVal( $this->fijo );
		
		/*if ( count($this->cuadras) == 0 )
			$this->addError( 'obra_id', "Debe ingresar al menos una cuadra" );	*/
			
		if($this->fchini !== null && trim($this->fchini) != '')
			$this->fchini = Fecha::usuarioToBD($this->fchini);
				
		if($this->fchfin !== null && trim($this->fchfin) != '')
			$this->fchfin = Fecha::usuarioToBD($this->fchfin);
			
			
		return true;
	}
	
	public function afterFind(){
		
		$this->getCuadras();
		$this->est_nom = ( $this->est == 'A' ? 'Activo' : 'Baja' );
		$this->tobra_nom = utb::getCampo("mej_tobra", "cod='" . $this->tobra . "'");
		$this->modif = utb::getFormatoModif( $this->usrmod, $this->fchmod);
		$this->tcalculo_nom = utb::getCampo("mej_tcalculo", "cod=" . $this->tcalculo);
		$this->cta_nom = utb::getCampo("cuenta", "cta_id=" . $this->cta_id);
		$this->texto_nom = utb::getCampo("texto", "texto_id=" . $this->texto_id);
		
	}
	
	public function validarTCalculo(){
		
		if ( $this->tcalculo == 0 )
			$this->addError( 'tcalculo', "Seleccione una Fórmula de Cálculo" );	
			
		$modelTCalculo = MejoraTCalculo::findOne( $this->tcalculo );	
		
		if ( $modelTCalculo != null ) {
			if ( $modelTCalculo->ped_valormetro == 1 and $this->valormetro <= 0 )
				$this->addError( 'valormetro', "Debe ingresar Valor Metro" );	
				
			if ( $modelTCalculo->ped_valortotal == 1 and $this->valortotal <= 0 )
				$this->addError( 'valortotal', "Debe ingresar Valor Total" );		
				
			if ( $modelTCalculo->ped_fijo == 1 and $this->fijo <= 0 )
				$this->addError( 'fijo', "Debe ingresar Fijo" );		
				
			if ( $modelTCalculo->ped_supafec == 1 and $this->totalsupafec <= 0 )
				$this->addError( 'totalsupafec', "Debe ingresar Sup. Afectada" );		
				
			if ( $modelTCalculo->ped_frente == 1 and $this->totalfrente <= 0 )
				$this->addError( 'totalfrente', "Debe ingresar Total Frente" );		
		}		
	
	}
	
	public function validarExistenciaFrente() {
	
		$sql = "Select Exists (Select 1 From mej_plan Where obra_id = $this->obra_id)";
		$cant = Yii::$app->db->createCommand($sql)->queryScalar();
		
		if ( $cant > 0 )
			$this->addError( 'obra_id', "La obra no se puede eliminar mientras existan frentistas asociados" );		
	
	}
	
	private function getCuadras(){
	
		$sql = "select * from v_mej_cuadra where obra_id=" . intVal( $this->obra_id );
		
		$this->cuadras = Yii::$app->db->createCommand( $sql )->queryAll();
		
		$array = [];
		if (count($this->cuadras) > 0){
			foreach ( $this->cuadras as $c ){
				$modelCuadra = MejoraCuadra::findOne([ 'cuadra_id' => $c['cuadra_id'] ]);
				$array[] = [
							'cuadra_id' => $c['cuadra_id'],
							'obra_id' => $c['obra_id'],
							'obra_nom' => $c['obra_nom'],
							'calle_id' => $c['calle_id'],
							'calle_nom' => $c['calle_nom'],
							'ncm' => $c['ncm'],
							's1' => $modelCuadra->s1,
							's2' => $modelCuadra->s2,
							's3' => $modelCuadra->s3,
							'manz' => $modelCuadra->manzana,
							'obs' => $c['obs']
						];
			}
		}
		
		$this->cuadras = $array;
	
	}
	
	public function getCuadra( $cuadra_id ){
	
		$array = [];
		if (count($this->cuadras) > 0){
			foreach ( $this->cuadras as $c ){
				if ( intVal($c['cuadra_id']) == intVal($cuadra_id) ){
					$array = [
							'cuadra_id' => $c['cuadra_id'],
							'obra_id' => $c['obra_id'],
							'obra_nom' => $c['obra_nom'],
							'calle_id' => $c['calle_id'],
							'calle_nom' => $c['calle_nom'],
							'ncm' => $c['ncm'],
							's1' => $c['s1'],
							's2' => $c['s2'],
							's3' => $c['s3'],
							'manz' => $c['manz'],
							'obs' => $c['obs']
						];
				}
			}
		}
		
		if ( count($array) == 0 ){
			$array = [
					'cuadra_id' => 0,
					'obra_id' => $this->obra_id,
					'obra_nom' => $this->nombre,
					'calle_id' => 0,
					'calle_nom' => "",
					'ncm' => "",
					's1' => "",
					's2' => "",
					's3' => "",
					'manz' => "",
					'obs' => ""
				];
		}
		
		return $array;
	
	}
	
	public function abmCuadra($CuadraAction, $CuadraId, $CuadraS1, $CuadraS2,$CuadraS3,$CuadraManz,$CuadraCalleId,$CuadraCalleNom,$CuadraCalleNom,$CuadraObs){
		if ( $CuadraAction == 0 ){
			$existe = 0;
			$id = 0;
			if (count($this->cuadras) > 0){
				foreach ( $this->cuadras as $c ){
					if ($c['cuadra_id'] == $CuadraId){
						$existe = 1;
					}
					if ( $c['cuadra_id'] > $id ) $id = $c['cuadra_id'];
				}
			}

			if ($existe == 0){
				$sql = "Select sam.uf_inm_armar_ncm('$CuadraS1', '$CuadraS2', '$CuadraS3', '$CuadraManz')";
				$ncm = Yii::$app->db->createCommand($sql)->queryScalar();
				
				$this->cuadras[] = [
						'cuadra_id' => $id + 1,
						'obra_id' => $this->obra_id,
						'obra_nom' => $this->nombre,
						'calle_id' => $CuadraCalleId,
						'calle_nom' => $CuadraCalleNom,
						'ncm' => $ncm,
						's1' => $CuadraS1,
						's2' => $CuadraS2,
						's3' => $CuadraS3,
						'manz' => $CuadraManz,
						'obs' => $CuadraObs
					];
			}
		}else if ( intVal($CuadraAction) == 3 ) {
			foreach ( $this->cuadras as $k => $d ){
				if (intVal($d['cuadra_id']) == intVal($CuadraId)){
					$sql = "Select sam.uf_inm_armar_ncm('$CuadraS1', '$CuadraS2', '$CuadraS3', '$CuadraManz')";
					$ncm = Yii::$app->db->createCommand($sql)->queryScalar();
					
					$this->cuadras[$k]['calle_id'] = $CuadraCalleId;
					$this->cuadras[$k]['calle_nom'] = $CuadraCalleNom;
					$this->cuadras[$k]['ncm'] = $ncm;
					$this->cuadras[$k]['s1']	= $CuadraS1;
					$this->cuadras[$k]['s2'] = $CuadraS2;
					$this->cuadras[$k]['s3'] = $CuadraS3;
					$this->cuadras[$k]['manz'] = $CuadraManz;
					$this->cuadras[$k]['obs'] = $CuadraObs;
				}
			}
		}else if ( intVal($CuadraAction) == 2 ) {
			foreach ( $this->cuadras as $k => $d ){
				if (intVal($d['cuadra_id']) == intVal($CuadraId)){
					unset($this->cuadras[$k]);
				}
			}
		}
	}
	
	private function GrabarCuadras(){
	
		$modelCuadra = new MejoraCuadra();
		
		foreach ( $this->cuadras as $c ) {
		
			$modelCuadra->s1 = $c['s1'];
			$modelCuadra->s2 = $c['s2'];
			$modelCuadra->s3 = $c['s3'];
			$modelCuadra->manzana = $c['manz'];
			$modelCuadra->calle_id = $c['calle_id'];
			$modelCuadra->calle_nom = $c['calle_nom'];
			$modelCuadra->ncm = $c['ncm'];
			$modelCuadra->obs = $c['obs'];
			
			$res = $modelCuadra->grabar( $this->obra_id );
			
			if ( !$res ) {
				$this->addErrors( $modelCuadra->getErrors() );
				return false;
			}	
		
		}
		
		return true;	
	
	}
	
	public function Grabar(){
	
		if($this->fchini !== null && trim($this->fchini) != '')
			$this->fchini = "'" . Fecha::usuarioToBD($this->fchini) . "'";
		else 
			$this->fchini = 'null';
		
		if($this->fchfin !== null && trim($this->fchfin) != '')
			$this->fchfin = "'" . Fecha::usuarioToBD($this->fchfin) . "'";	
		else 	
			$this->fchfin = 'null';
			
		$transaction = Yii::$app->db->beginTransaction();

		try{
		
			switch( $this->scenario ){

				case 'nuevo':	//Alta 
					
					$this->obra_id = Yii::$app->db->createCommand( "SELECT nextval( 'mej_obra_obra_id_seq' )" )->queryScalar();
					
					$sql = 	"INSERT INTO mej_obra (obra_id, nombre, tobra, decreto, fchini, fchfin, cta_id,tcalculo, bonifobra,totalfrente,totalsupafec,valortotal,valormetro,fijo,texto_id,est,obs,fchmod,usrmod) " . 
							" VALUES ($this->obra_id, '$this->nombre', '$this->tobra', '$this->decreto', $this->fchini, $this->fchfin, $this->cta_id, $this->tcalculo, $this->bonifobra, $this->totalfrente, " . 
							"$this->totalsupafec, $this->valortotal, $this->valormetro, $this->fijo, $this->texto_id, 'A', '$this->obs', CURRENT_TIMESTAMP," . Yii::$app->user->id . ")";

					break;

				case 'eliminar':	//Baja 

					$sql = "Update mej_obra set est='B' Where obra_id = $this->obra_id";

					break;

				case 'modificar':	//Modificación 

					$sql = 	"UPDATE mej_obra SET nombre = '$this->nombre',tobra = '$this->tobra',decreto = '$this->decreto', fchfin=$this->fchfin, fchini=$this->fchini,cta_id=$this->cta_id," .
							"tcalculo = $this->tcalculo,bonifobra = $this->bonifobra, totalfrente = $this->totalfrente, totalsupafec = $this->totalsupafec, valortotal = $this->valortotal," .
							"valormetro = $this->valormetro, fijo = $this->fijo, texto_id = $this->texto_id, obs = '$this->obs', fchmod = CURRENT_TIMESTAMP, usrmod = " . Yii::$app->user->id .
							" WHERE obra_id = $this->obra_id";

					break;
			}
			
			
					
			Yii::$app->db->createCommand( $sql )->execute();	
			
			if ( $this->scenario != 'eliminar' ) {
				//se borran las mejoras existentes
				$sql = "Delete From mej_cuadra Where obra_id = $this->obra_id";
				Yii::$app->db->createCommand($sql)->execute();
				
				if ( !$this->GrabarCuadras() ){
					$transaction->rollback();
					return false;
				}
			}
						
			$transaction->commit();

		} catch(\Exception $e ){

			$transaction->rollback();
			$this->addError( 'obra_id', $e->getMessage() );
			
			return false;
		}
		
		return true;
	
	}
	
	public static function todas($nombre = '', $tipo = ''){
		
		
		
		$sql = "Select obra_id, nombre, tobra_nom, est_nom From v_mej_obra";
		
		if($nombre != '') $sql .= " Where upper(nombre) Like upper('%$nombre%')";
		if($tipo != '') $sql .= ($nombre != '' ? " And upper(tobra) Like ('%$tipo%')" : " Where upper(tobra) Like ('%$tipo%')");
		return Yii::$app->db->createCommand($sql)->queryAll();
	}
	
	public function frentistas($cuadra_id = null){
		
		if($cuadra_id !== null)
			return Frentistas::findAll(['obra_id' => $this->obra_id, 'cuadra_id' => $cuadra_id]);
		
		return Frentistas::findAll(['obra_id' => $this->obra_id]);
	}
	
	public function textoObra ( $plan ) {
	
		$sql = "Select * From sam.Uf_Texto_Mej( $plan, $this->texto_id )";
		$texto = Yii::$app->db->createCommand($sql)->queryAll()[0];
		
		if ( count($texto) == 0 ) {
		
			$texto = ['titulo' => '', 'detalle' => ''];
		}
		return $texto;
	}

}
