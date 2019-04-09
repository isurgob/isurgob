<?php

namespace app\models\ctacte;

use Yii;

use yii\data\ArrayDataProvider;

use app\utils\helpers\DBException;
use app\utils\db\Fecha;
use app\utils\db\utb;
use app\models\ctacte\MejoraTCalculo;
use app\models\ctacte\PlanConfig;

class MejoraPlan extends \yii\db\ActiveRecord{


	public $ctacte_id;
	public $venccuota1;
	public $estadocc;
	public $inm_nc;
	public $inm_nc_ant;
	public $inm_titular;

	public function __construct(){

		parent::__construct();

		$this->obra_id = 0;
		$this->ctacte_id = 0;
		$this->est_nom = 'NUEVO';
		$this->venccuota1 = date('d/m/Y');

	}

	public static function tableName(){

		return 'v_mej_plan';
	}

	public function rules(){



		return [

			[['obra_id', 'obj_id', 'obj_nom'], 'required', 'on' => ['insert', 'update'] ],

			[['venccuota1'], 'required', 'on' => ['actacte'] ],

			[ ['plan_id', 'obra_id', 'cuadra_id', 'item_id', 'tplan', 'cuotas', 'tcalculo' ], 'integer' ],
			[ ['obj_id', 'obj_nom', 'obs'], 'string' ],
			[ ['frente', 'supafec', 'coef', 'bonifobra', 'valormetro', 'valortotal', 'fijo', 'monto',
				'item_monto', 'total', 'financia', 'sellado', 'anticipo','montocuo', 'descnominal', 'interes'], 'number' ],

			[ ['fchalta', 'fchbaja', 'fchdesaf'], 'string' ],

			[ ['frente', 'supafec', 'coef', 'monto'], 'validarTCalculo', 'on' => ['insert', 'update'] ],

			[ ['anticipo'], 'validarAnticipo', 'on' => ['actacte'] ],

			[ ['obra_id'], 'integer', 'min' => 1, 'on' => ['insert', 'update'] ]

		];
	}

	public function scenarios(){

		return [
			'insert' => [
				'obra_id', 'cuadra_id', 'item_id', 'tplan', 'cuotas', 'tcalculo',
				'obj_id', 'obj_nom', 'obs',
				'frente', 'supafec', 'coef', 'bonifobra', 'valormetro', 'valortotal', 'fijo', 'monto',
				'item_monto', 'total', 'financia', 'sellado', 'anticipo','montocuo', 'descnominal', 'interes',
				'fchalta', 'fchbaja', 'fchdesaf'
			],
			'update' => [
				'plan_id', 'obra_id', 'cuadra_id', 'item_id', 'tplan', 'cuotas', 'tcalculo',
				'obj_id', 'obj_nom', 'obs',
				'frente', 'supafec', 'coef', 'bonifobra', 'valormetro', 'valortotal', 'fijo', 'monto',
				'item_monto', 'total', 'financia', 'sellado', 'anticipo','montocuo', 'descnominal', 'interes',
				'fchalta', 'fchbaja', 'fchdesaf'
			],
			'delete' => ['plan_id', 'est'],
			'actacte' => ['plan_id', 'tplan', 'anticipo', 'cuotas', 'venccuota1'],
			'desafectar' => ['plan_id', 'obs'],
			'vencimiento' => ['plan_id', 'obra_id', 'cuadra_id', 'obj_id', 'venccuota1']

		];
	}

	public function attributeLabels(){

		return [
			'obra_id' => 'Obra',
			'cuadra_id' => 'Cuadra',
			'obj_id' => 'Objeto',
			'obj_nom' => 'Nombre del objeto',
			'frente' => 'Frente',
			'fchalta' => 'Fecha de alta',
			'fchbaja' => 'Fecha de baja',
			'fchdesaf' => 'Fecha de desaforación',
			'monto' => 'Monto',
			'obs' => 'Observaciones',
			'valormetro' => 'Valor metro',
			'bonifobra' => 'Bonificación Obra',
			'plan_id' => 'Plan',
			'est' => 'Estado'
		];
	}

	public function beforeValidate(){

		$this->cuadra_id = intVal( $this->cuadra_id );
		$this->tcalculo = intVal( $this->tcalculo );
		$this->frente = floatVal( $this->frente );
		$this->supafec = floatVal( $this->supafec );
		$this->coef = floatVal( $this->coef );
		$this->bonifobra = floatVal( $this->bonifobra );
		$this->valormetro = floatVal( $this->valormetro );
		$this->valortotal = floatVal( $this->valortotal );
		$this->fijo = floatVal( $this->fijo );
		$this->monto = floatVal( $this->monto );
		$this->item_id = intVal( $this->item_id );
		$this->item_monto = floatVal( $this->item_monto );
		$this->total = floatVal( $this->total );
		$this->tplan = intVal( $this->tplan );
		$this->financia = floatVal( $this->financia );
		$this->sellado = floatVal( $this->sellado );
		$this->anticipo = floatVal( $this->anticipo );
		$this->cuotas = intVal( $this->cuotas );
		$this->montocuo = floatVal( $this->montocuo );
		$this->descnominal = floatVal( $this->descnominal );
		$this->interes = floatVal( $this->interes );
		$this->frente = floatVal( $this->frente );

		return true;
	}

	public function afterFind(){

		$this->fchalta = date('d/m/Y', strtotime($this->fchalta));
		if ( $this->fchbaja != "" ) $this->fchbaja = date('d/m/Y', strtotime($this->fchbaja));
		if ( $this->fchdesaf != "" ) $this->fchdesaf = date('d/m/Y', strtotime($this->fchdesaf));

		$sql = "Select vest From sam.uf_mej_avance($this->plan_id)";
		$this->estadocc = Yii::$app->db->createCommand($sql)->queryScalar();

		if ( $this->est != 'A' ){
			$sql = "select to_char(fchvenc,'dd/mm/yyyy') venc from ctacte c where trib_id=3 and anio=" . intVal($this->plan_id) . " and cuota=1 ";
			$this->venccuota1 = Yii::$app->db->createCommand($sql)->queryScalar();
		}

		$this->inm_nc = utb::getCampo("v_inm", "obj_id='" . $this->obj_id . "'", "nc_guiones");
		$this->inm_nc_ant = utb::getCampo("v_inm", "obj_id='" . $this->obj_id . "'", "nc_ant");
		$num = utb::getCampo("v_inm", "obj_id='" . $this->obj_id . "'", "num");
		$this->inm_titular = utb::getCampo("objeto", "obj_id='" . $num . "'");

	}

	public function validarTCalculo(){

		$modelTCalculo = MejoraTCalculo::findOne( $this->tcalculo );

		if ( $modelTCalculo != null ) {
			if ( $modelTCalculo->ped_frente == 1 and $this->frente <= 0 )
				$this->addError( 'frente', "Debe ingresar Frente" );
			if ( $modelTCalculo->ped_supafec == 1 and $this->supafec <= 0 )
				$this->addError( 'supafec', "Debe ingresar Sup. Afectada" );
			if ( $modelTCalculo->ped_coef == 1 and $this->coef <= 0 )
				$this->addError( 'coef', "Debe ingresar Coeficiente" );
			if ( $modelTCalculo->ped_monto == 1 and $this->monto <= 0 )
				$this->addError( 'valortotal', "Debe ingresar Monto" );
		}

	}

	public function validarAnticipo(){

		$model = PlanConfig::findOne([ 'cod' => $this->tplan ]);

		if ( intVal($model->anticipomanual) == 1 and floatVal($this->anticipo) == 0 )
			$this->addError( 'anticipo', "Debe ingresar Anticipo" );

	}

	public function Grabar(){

		$transaction = Yii::$app->db->beginTransaction();

		try{

			switch( $this->scenario ){

				case 'insert':	//Alta

					$this->plan_id = Yii::$app->db->createCommand( "SELECT nextval( 'seq_mej_plan' )" )->queryScalar();

					$sql = 	"INSERT INTO mej_plan " .
							" VALUES ($this->plan_id, '$this->obj_id', $this->obra_id, $this->cuadra_id, $this->frente, $this->supafec, $this->coef, $this->bonifobra, $this->valormetro, $this->valortotal, " .
							"$this->fijo, $this->monto, $this->item_id, $this->item_monto, $this->total, $this->tplan, $this->financia, $this->sellado, $this->anticipo, $this->cuotas, $this->montocuo, " .
							"$this->descnominal, $this->interes, 'A', current_date, null, null, '$this->obs', CURRENT_TIMESTAMP," . Yii::$app->user->id . ")";

					break;

				case 'delete':	//Baja

					$sql = "Select sam.uf_mej_plan_baja($this->plan_id, 'B', '', " . Yii::$app->user->id . ")";

					break;

				case 'update':	//Modificación

					$sql = 	"UPDATE mej_plan SET obj_id = '$this->obj_id',obra_id = $this->obra_id,cuadra_id = $this->cuadra_id, frente=$this->frente, supafec=$this->supafec,coef=$this->coef," .
							"bonifobra = $this->bonifobra,valormetro = $this->valormetro, valortotal = $this->valortotal, fijo = $this->fijo, monto = $this->monto,item_id=$this->item_id," .
							"item_monto = $this->item_monto, total = $this->total, tplan = $this->tplan, financia=$this->financia, sellado=$this->sellado, anticipo=$this->anticipo,cuotas=$this->cuotas," .
							"montocuo=$this->montocuo,descnominal=$this->descnominal,interes=$this->interes,obs = '$this->obs', fchmod = CURRENT_TIMESTAMP, usrmod = " . Yii::$app->user->id .
							" WHERE plan_id = $this->plan_id";

					break;

				case 'actacte':

					$sql = "select * from sam.uf_mej_graba( $this->plan_id, $this->tplan, $this->anticipo, $this->cuotas, '$this->venccuota1'," . Yii::$app->user->id . ")";

					break;

				case 'desafectar':

					$sql = "Select sam.uf_mej_plan_baja($this->plan_id, 'D', '$this->obs', " . Yii::$app->user->id . ")";

					break;

				case 'vencimiento':

					$sql = "Select sam.uf_mej_plan_fecha($this->obra_id, $this->cuadra_id, '$this->venccuota1', '$this->obj_id', $this->plan_id)";

					break;
			}


			Yii::$app->db->createCommand( $sql )->execute();

			$transaction->commit();

		} catch(\Exception $e ){

			$transaction->rollback();
			$this->addError( 'plan_id',  DBException::getMensaje($e) );

			return false;
		}

		return true;

	}

	public function getCuotas(){

		$sql = "select * From sam.uf_mej_cuotas(" . intVal($this->plan_id) . ")";

		return Yii::$app->db->createCommand($sql)->queryAll();

	}

	public function CalcularFinancia( $total, $cuotas, $venc, $tplan, $obj_id, $anticipo ) {

		$error = "";
		$res = [];

		$model = PlanConfig::findOne([ 'cod' => $tplan ]);

		if ( intVal($model->anticipomanual) == 1 and floatVal($anticipo) == 0 )
			$error = "Debe ingresar Anticipo";

		if ( $venc == '' )
			$error = "Debe ingresar Fecha de Vencimiento";


		if ( $error == "" ) {
			try{
				$sql = "select *,to_char(venc,'dd/mm/yyyy') venc from sam.uf_plan_previo($total, -1, $cuotas, '$venc', $tplan, '$obj_id', $anticipo)";

				$res = Yii::$app->db->createCommand($sql)->queryAll();

			} catch(\Exception $e ){

				$error = DBException::getMensaje($e);

			}
		}

		$financia = 0;
		$sellado = 0;
		$capital = 0;
		$montocuota = 0;
		$descuento = 0;

		if ( count( $res ) > 0 ){
			foreach ( $res as $r ){
				$financia += $r['financia'];
				$sellado += $r['sellado'];
				$capital += $r['capital'];
				if ( $r['cuota'] == 1 )
					$montocuota = $r['total'];
			}
			if ( $total > $capital )
				$descuento = number_format(floatVal($total - $capital), 2);
		}

		$devolver = [
			'error'	=> $error,
			'cuotas' => ( count( $res ) > 0 ? json_encode($res) : json_encode([])),
			'financia' => $financia,
			'sellado' => $sellado,
			'capital' => $capital,
			'montocuota' => $montocuota,
			'descuento' => $descuento
		];

		return $devolver;

	}

	public function CalculaTotal( $obj_id, $obra, $frente, $supafec, $coef, $monto ){

		$sql = "SELECT * from sam.uf_mej_calcula('$obj_id',$obra, $frente,$supafec,$coef,$monto)";
		$total = Yii::$app->db->createCommand( $sql )->queryScalar();
		return $total;

	}

	public function ImprimirContrato($id,&$error)
    {
    	$sql = "Select coalesce(Texto_Id,0) From Mej_Plan p Inner Join Mej_Obra o ";
        $sql .= "On p.Obra_Id = o.Obra_Id Where p.Plan_Id = ".$id;
        $texto = Yii::$app->db->createCommand($sql)->queryScalar();
        if ($texto == 0){
        	 $error = '<li>No se ha definido ningún Texto en la Obra para el Contrato</li>';
        	 return null;
        }

    	try {
    		$sql = "Select * From sam.Uf_Texto_Mej(" . $id . ',' . $texto . ")";
    		$cmd = Yii :: $app->db->createCommand($sql);
			$array = $cmd->queryAll();
    	} catch(\Exception $e) {
    		$error .= DBException::getMensaje($e);

			return null;
    	}

    	return (count($array) > 0 ? $array[0] : $array);
    }

	public function ImprimirResumen($id,&$sub1)
    {
    	$sql = "Select *,to_char(fchalta,'dd/mm/yyyy') fchalta,to_char(fchbaja,'dd/mm/yyyy') fchbaja," .
    			"to_char(fchdesaf,'dd/mm/yyyy') fchdesaf,to_char(fchvenc,'dd/mm/yyyy') fchvenc," .
    			"to_char(fchmod,'dd/mm/yyyy') fchmod From V_Mej_Plan Where plan_id=".$id;
    	$array = Yii::$app->db->createCommand($sql)->queryAll();

    	$sql = "select case when cuota = 0 then 'Anticipo' else cuota::text end cuota_nom, nominal total, to_char(fchvenc,'dd/mm/yyyy') venc,0 capital,0 financia,0 sellado, " .
			"est, fchpago, caja_id " .
			" from ctacte c where trib_id=3 and anio=" . intVal($id);

		$sub1 = Yii::$app->db->createCommand($sql)->queryAll();

        return $array;
    }

	public function ImprimirComprobanteValida($id, $cuotadesde,$cuotahasta)
   {
   		$model = MejoraPlan::findOne(['plan_id' => $id]);
   		//Verificar Vigencia del Plan
        if ($model->est != 'A' and $model->est != 'L') return "La Liquidación no se encuentra Activa o Liquidada";

        $sql = "Select count(*) From V_Emision_Print Where Trib_id = 3 and Anio=".$id." and Cuota>=".$cuotadesde." and Cuota<=".$cuotahasta;
        $count = Yii::$app->db->createCommand($sql)->queryScalar();
        if ($count <= 0) return "No hay cuotas para imprimir";

        return "";
   }

   public function ImprimirComprobante($id, $cuotadesde,$cuotahasta,&$emision,&$sub1,&$sub2,&$sub3,&$sub4)
   {
   		$sql = "Select *,to_char(fchemi,'dd/mm/yyyy') as fchemi,to_char(venc1,'dd/mm/yyyy') as venc1,to_char(venc2,'dd/mm/yyyy') as venc2," .
        		"to_char(vencanual,'dd/mm/yyyy') as vencanual From V_Emision_Print " .
        		"Where Trib_id = 3 and Anio=".$id." and Cuota>=".$cuotadesde." and Cuota<=".$cuotahasta;
        $emision = Yii::$app->db->createCommand($sql)->queryAll();

        //SubInforme 1 - Liquidación
        $sql = "select d.ctacte_id, d.debe item_monto, c.nombre item_nom from ctacte_det d inner join cuenta c on d.cta_id=c.cta_id where d.Ctacte_id=" . $emision[0]['ctacte_id'];
        $sub1 = Yii::$app->db->createCommand($sql)->queryAll();

        //SubInforme 2 - Datos de la Obra
        $sql = "Select *,obj_nom nombre,dompar dompar_dir From V_Mej_Plan Where Plan_Id=" . $id;
        $sub2 = Yii::$app->db->createCommand($sql)->queryAll();

        //SubInforme 3 - Titulares
        $sql = "Select * From v_objeto_tit Where obj_id in (select obj_id from mej_plan where plan_id=" . $id . ")";
        $sub3 = Yii::$app->db->createCommand($sql)->queryAll();

        //SubInforme 4 - Estado de cuenta
        $sub4 = Yii::$app->db->createCommand("Select * From sam.uf_CtaCte_Plan (3,".$id.")")->queryAll();
   }

   public static function buscarAv($cond, $orden = 'aju_id'){

		$sql = "Select * From v_mej_plan Where $cond";

		if($orden != '') $sql .= " Order By $orden";

		$models = Yii::$app->db->createCommand($sql)->queryAll();
		$count = count($models);

		return new ArrayDataProvider([
			'allModels' => $models,
			'key' => 'plan_id',
			'totalCount' => $count,
			'pagination' => [
				'pageSize' => 40
			]
		]);
	}
}
