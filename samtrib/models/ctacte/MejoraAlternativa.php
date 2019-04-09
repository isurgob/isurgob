<?php

namespace app\models\ctacte;

use Yii;

use yii\data\ArrayDataProvider;

use app\utils\helpers\DBException;
use app\utils\db\Fecha;
use app\utils\db\utb;
use app\models\ctacte\MejoraPlan;


class MejoraAlternativa extends \yii\db\ActiveRecord{


	public static function tableName(){

		return 'mej_alt';
	}

	public function rules(){
		
		return [
			
			[ ['plan_id', 'tplan1', 'cuotas1', 'tplan2', 'cuotas2', 'tplan3', 'cuotas3' ], 'integer' ],
			[ ['entrega1', 'montocuo1', 'financia1', 'sellado1', 'entrega2', 'montocuo2', 'financia2', 'sellado2', 'entrega3', 'montocuo3', 'financia3', 'sellado3' ], 'number' ],
			[ ['titulo1', 'titulo2', 'titulo3'], 'string' ],
			
			[ ['entrega1', 'entrega2', 'entrega3'], 'validarEntrega', 'on' => ['generar'] ],
			
			[ ['montocuo1', 'montocuo2', 'montocuo3', 'titulo1', 'titulo2', 'titulo3', 'tplan1', 'tplan2', 'tplan3'], 'validarIngresoDatos', 'on' => ['generar'] ],
			
		];
	}

	public function scenarios(){

		return [
			
			'generar' => [
				'plan_id', 'tplan1', 'cuotas1', 'tplan2', 'cuotas2', 'tplan3', 'cuotas3',
				'entrega1', 'montocuo1', 'financia1', 'sellado1', 'entrega2', 'montocuo2', 'financia2', 'sellado2', 'entrega3', 'montocuo3', 'financia3', 'sellado3',
				'titulo1', 'titulo2', 'titulo3'
			],
			
		];
	}

	public function beforeValidate(){

		$this->tplan1 = intVal( $this->tplan1 );
		$this->tplan2 = intVal( $this->tplan2 );
		$this->tplan3 = intVal( $this->tplan3 );
		
		$this->cuotas1 = intVal( $this->cuotas1 );
		$this->cuotas2 = intVal( $this->cuotas2 );
		$this->cuotas3 = intVal( $this->cuotas3 );
		
		$this->entrega1 = floatVal( $this->entrega1 );
		$this->entrega2 = floatVal( $this->entrega2 );
		$this->entrega3 = floatVal( $this->entrega3 );
		
		$this->montocuo1 = floatVal( $this->montocuo1 );
		$this->montocuo2 = floatVal( $this->montocuo2 );
		$this->montocuo3 = floatVal( $this->montocuo3 );
		
		$this->financia1 = floatVal( $this->financia1 );
		$this->financia2 = floatVal( $this->financia2 );
		$this->financia3 = floatVal( $this->financia3 );
		
		$this->sellado1 = floatVal( $this->sellado1 );
		$this->sellado2 = floatVal( $this->sellado2 );
		$this->sellado3 = floatVal( $this->sellado3 );
		
		if ( $this->titulo1 == '' and $this->tplan1 == 0 ){
			
			$this->cuotas1 = 0;
			$this->entrega1 = 0;
			$this->montocuo1 = 0;
			$this->financia1 = 0;
			$this->sellado1 = 0;
			
		}
		
		if ( $this->titulo2 == '' and $this->tplan2 == 0 ){
			
			$this->cuotas2 = 0;
			$this->entrega2 = 0;
			$this->montocuo2 = 0;
			$this->financia2 = 0;
			$this->sellado2 = 0;
			
		}
		
		if ( $this->titulo3 == '' and $this->tplan3 == 0 ){
			
			$this->cuotas3 = 0;
			$this->entrega3 = 0;
			$this->montocuo3 = 0;
			$this->financia3 = 0;
			$this->sellado3 = 0;
			
		}
		
		return true;
	}

	public function validarEntrega(){
	
		$model1 = PlanConfig::findOne([ 'cod' => $this->tplan1 ]);
		
		if ( $model1 != null and intVal($model1->anticipomanual) == 1 and floatVal($this->entrega1) == 0 )
			$this->addError( 'entrega1', "Debe ingresar una Entrega para el Tipo de Plan 1" );
		
		$model2 = PlanConfig::findOne([ 'cod' => $this->tplan2 ]);	
		if ( $model2 != null and intVal($model2->anticipomanual) == 1 and floatVal($this->entrega2) == 0 )
			$this->addError( 'entrega2', "Debe ingresar una Entrega para el Tipo de Plan 2" );	
			
		$model3 = PlanConfig::findOne([ 'cod' => $this->tplan3 ]);	
		if ( $model3 != null and intVal($model3->anticipomanual) == 1 and floatVal($this->entrega3) == 0 )
			$this->addError( 'entrega3', "Debe ingresar una Entrega para el Tipo de Plan 3" );		
	
	}
	
	public function validarIngresoDatos(){
	
		// valido ingreso de tplan
		if ( $this->titulo1 != '' and $this->tplan1 == 0 )
			$this->addError( 'tplan1', "Seleccione un Tipo de Plan para la Alternativa 1" );
		
		if ( $this->titulo2 != '' and $this->tplan2 == 0 )
			$this->addError( 'tplan1', "Seleccione un Tipo de Plan para la Alternativa 2" );
			
		if ( $this->titulo3 != '' and $this->tplan3 == 0 )
			$this->addError( 'tplan1', "Seleccione un Tipo de Plan para la Alternativa 3" );	
			
		// valido ingreso titulo
		if ( $this->titulo1 == '' and $this->tplan1 != 0 )
			$this->addError( 'titulo1', "Ingrese un Título para la Alternativa 1" );	
		
		if ( $this->titulo2 == '' and $this->tplan2 != 0 )
			$this->addError( 'titulo2', "Ingrese un Título para la Alternativa 2" );		
			
		if ( $this->titulo3 == '' and $this->tplan3 != 0 )
			$this->addError( 'titulo3', "Ingrese un Título para la Alternativa 3" );	

		// valido ingreso cuotas
		if ( $this->titulo1 != '' and $this->tplan1 != 0 and $this->cuotas1 == 0 )
			$this->addError( 'cuotas1', "Ingrese cuotas para la Alternativa 1" );			
		
		if ( $this->titulo2 != '' and $this->tplan2 != 0 and $this->cuotas2 == 0 )
			$this->addError( 'cuotas2', "Ingrese cuotas para la Alternativa 2" );				
			
		if ( $this->titulo3 != '' and $this->tplan3 != 0 and $this->cuotas3 == 0 )
			$this->addError( 'cuotas2', "Ingrese cuotas para la Alternativa 3" );					
	
	}
	
	public function Grabar(){
	
		$modelPlan = MejoraPlan::findOne([ 'plan_id' => $this->plan_id ]);
			
		try{
			// obtengo calculo del plan 1
			if ($this->tplan1 != 0) {
				$sql = "select sum(financia) financia, sum(total) total, sum(sellado) sellado from sam.uf_plan_previo($modelPlan->total, -1, $this->cuotas1, current_date, $this->tplan1, '$modelPlan->obj_id', $this->entrega1)";
				$res = Yii::$app->db->createCommand($sql)->queryAll();
				
				if ( count($res) > 0 ){
					$this->financia1 = $res[0]['financia'];
					$this->sellado1 = $res[0]['sellado'];
					$this->montocuo1 = $res[0]['total'];
				}
			}	
			
			// obtengo calculo del plan 2
			if ($this->tplan2 != 0) {
				$sql = "select sum(financia) financia, sum(total) total, sum(sellado) sellado from sam.uf_plan_previo($modelPlan->total, -1, $this->cuotas2, current_date, $this->tplan2, '$modelPlan->obj_id', $this->entrega2)";
				$res = Yii::$app->db->createCommand($sql)->queryAll();
				
				if ( count($res) > 0 ){
					$this->financia2 = $res[0]['financia'];
					$this->sellado2 = $res[0]['sellado'];
					$this->montocuo2 = $res[0]['total'];
				}
			}	
			
			// obtengo calculo del plan 3
			if ($this->tplan3 != 0) {
				$sql = "select sum(financia) financia, sum(total) total, sum(sellado) sellado from sam.uf_plan_previo($modelPlan->total, -1, $this->cuotas3, current_date, $this->tplan3, '$modelPlan->obj_id', $this->entrega3)";
				$res = Yii::$app->db->createCommand($sql)->queryAll();
				
				if ( count($res) > 0 ){
					$this->financia3 = $res[0]['financia'];
					$this->sellado3 = $res[0]['sellado'];
					$this->montocuo3 = $res[0]['total'];
				}
			}	
		} catch(\Exception $e ){

			$this->addError( 'plan_id', DBException::getMensaje($e) );
			
			return false;
		}	
		
		$sql = "select count(*) from mej_alt where plan_id=$this->plan_id";
		$cant = intVal( Yii::$app->db->createCommand($sql)->queryScalar() );
		
		if ( $cant > 0 ) {
		
			$sqlAlt = "update mej_alt set tplan1=$this->tplan1, cuotas1=$this->cuotas1, entrega1=$this->entrega1, montocuo1=$this->montocuo1, financia1=$this->financia1, sellado1=$this->sellado1, titulo1='$this->titulo1'," . 
					"tplan2=$this->tplan2, cuotas2=$this->cuotas2, entrega2=$this->entrega2, montocuo2=$this->montocuo2, financia2=$this->financia2, sellado2=$this->sellado2, titulo2='$this->titulo2', " . 
					"tplan3=$this->tplan3, cuotas3=$this->cuotas3, entrega3=$this->entrega3, montocuo3=$this->montocuo3, financia3=$this->financia3, sellado3=$this->sellado3, titulo3='$this->titulo3', " . 
					"fchmod = CURRENT_TIMESTAMP, usrmod = " . Yii::$app->user->id . " where plan_id=$this->plan_id";
		
		} else {
			
			$sqlAlt = "insert into mej_alt values($this->plan_id, $this->tplan1, $this->cuotas1, $this->entrega1, $this->montocuo1, $this->financia1, $this->sellado1, '$this->titulo1'," . 
					"$this->tplan2, $this->cuotas2, $this->entrega2, $this->montocuo2, $this->financia2, $this->sellado2, '$this->titulo2'," . 
					"$this->tplan3, $this->cuotas3, $this->entrega3, $this->montocuo3, $this->financia3, $this->sellado3, '$this->titulo3'," . 
					"CURRENT_TIMESTAMP," . Yii::$app->user->id . ")";
			
		}
		
		$transaction = Yii::$app->db->beginTransaction();
		try{
			
			// inserto o actualiZo datos de alternativa
			Yii::$app->db->createCommand( $sqlAlt )->execute();	
			$transaction->commit();
		
		} catch(\Exception $e ){

			$transaction->rollback();
			$this->addError( 'plan_id',  DBException::getMensaje($e) );
			
			return false;
		}
		
		return true;
	}
	
	public function Borrar ( $plan ) {
	
		$sql = "Delete from mej_alt where plan_id = $plan ";
		
		try{
			
			Yii::$app->db->createCommand( $sql )->execute();	
		
		} catch(\Exception $e ){

			$this->addError( 'plan_id',  DBException::getMensaje($e) );
			
			return false;
		}
		
		return true;
	
	}
	
}
