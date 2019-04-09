<?php

namespace app\models\ctacte;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\data\ArrayDataProvider;
use Yii;
use app\models\ctacte\PlanConfig;
use app\utils\db\utb;
use app\utils\helpers\DBException;
use app\models\ctacte\Liquida;

/**
 * This is the model class for table "plan".
 *
 * @property integer $plan_id
 * @property integer $tplan
 * @property string $obj_id
 * @property string $num
 * @property string $resp
 * @property integer $resptdoc
 * @property string $respndoc
 * @property string $resptel
 * @property string $nominal
 * @property string $accesor
 * @property string $multa
 * @property string $financia
 * @property string $sellado
 * @property string $anticipo
 * @property integer $origen
 * @property integer $tpago
 * @property integer $caja_id
 * @property integer $temple
 * @property string $temple_area
 * @property integer $bco_suc
 * @property integer $bco_tcta
 * @property string $tpago_nro
 * @property integer $cuotas
 * @property string $montocuo
 * @property string $descnominal
 * @property string $descinteres
 * @property string $descmulta
 * @property string $interes
 * @property string $obs
 * @property integer $est
 * @property string $fchalta
 * @property integer $usralta
 * @property string $fchbaja
 * @property string $fchimputa
 * @property string $fchdecae
 * @property string $fchconsolida
 * @property string $planant
 * @property integer $distrib
 * @property string $fchmod
 * @property integer $usrmod
 */
class Plan extends \yii\db\ActiveRecord
{
    public $capital;
    public $cuotaspagas;
    public $pagado;
    public $cuotasfalta;
    public $saldo;
    public $judi_id;
	public $contrib_mail;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'plan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['plan_id', 'tplan', 'obj_id', 'num', 'resp', 'nominal', 'accesor', 'multa', 'financia', 'sellado', 'anticipo', 'origen', 'tpago', 'cuotas', 'montocuo', 'est', 'fchalta', 'fchconsolida', 'usrmod'], 'required'],
            [['plan_id', 'tplan', 'resptdoc', 'respndoc', 'origen', 'tpago', 'caja_id', 'temple', 'bco_suc', 'bco_tcta', 'cuotas', 'est', 'usralta', 'tdistrib', 'distrib', 'usrmod'], 'integer'],
            [['nominal', 'accesor', 'multa', 'financia', 'sellado', 'anticipo', 'montocuo', 'descnominal', 'descinteres', 'descmulta', 'interes'], 'number'],
            [['fchalta', 'fchbaja', 'fchimputa', 'fchdecae', 'fchconsolida', 'fchmod'], 'safe'],
            [['obj_id', 'num'], 'string', 'max' => 8],
            [['resp'], 'string', 'max' => 50],
            [['resptel', 'planant'], 'string', 'max' => 15],
            [['temple_area', 'tpago_nro'], 'string', 'max' => 20],
            [['obs'], 'string', 'max' => 250],

			['distrib', 'required', 'when' => function($model) {
												return intVal($model->tdistrib) != 3 ;
											}
			],

			['contrib_mail', 'email', 'when' => function($model) {
												return intVal($model->tdistrib) == 5 ;
											}
			],
        ];
    }

	public function beforeValidate(){

		$sql = "select mail from persona where obj_id = ' $this->num ' ";
		$this->contrib_mail = Yii::$app->db->createCommand( $sql )->queryScalar();

		return true;
	}

	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'plan_id' => 'N° Conv.',
            'tplan' => 'Tipo',
            'obj_id' => 'Objeto',
            'num' => 'Codigo de num - responsable',
            'resp' => 'Nombre del responsable',
            'resptdoc' => 'Codigo de tipo de documento del responsable',
            'respndoc' => 'Numero de documento del responsable',
            'resptel' => 'Telefono del responsable',
            'nominal' => 'Monto nominal',
            'accesor' => 'Monto accesorios',
            'multa' => 'Monto multa',
            'financia' => 'Monto financiacion',
            'sellado' => 'Monto sellado',
            'anticipo' => 'Monto anticipo',
            'origen' => 'Codigo de origen',
            'tpago' => 'Codigo de tipo de pago',
            'caja_id' => 'Codigo de caja vinculada',
            'temple' => 'Tipo de empleado',
            'temple_area' => 'Area de trabajo',
            'bco_suc' => 'Sucursal bancaria',
            'bco_tcta' => 'Tipo de cuenta',
            'tpago_nro' => 'Numero identificador',
            'cuotas' => 'Cantidad de cuotas',
            'montocuo' => 'Monto de cada cuota',
            'descnominal' => 'Descuento de nominal',
            'descinteres' => 'Descuento de interes',
            'descmulta' => 'Descuento de multa',
            'interes' => 'Descuento de interes',
            'obs' => 'Observaciones',
            'est' => 'Estado ',
            'fchalta' => 'Fecha de alta',
            'usralta' => 'Codigo de usuario que dio de alta',
            'fchbaja' => 'Fecha de baja',
            'fchimputa' => 'Fecha de imputacion',
            'fchdecae' => 'Fecha de decaimiento',
            'fchconsolida' => 'Fecha de consolidacion',
            'planant' => 'Conv. Ant.',
            'distrib' => 'Codigo de distribuidor',
            'fchmod' => 'Fecha de modificacion',
            'usrmod' => 'Codigo de usuario que modifico',
        ];
    }

    public function BorrarPlan()
    {
    	if ($this->est != 1)
    	{
    		return 'El estado del Convenio es incorrecto';
    	}

    	try {
    		$sql = 'Select sam.uf_Plan_Borra('.$this->plan_id.','.Yii::$app->user->id.')';
    		$cmd = Yii :: $app->db->createCommand($sql);
			$rowCount = $cmd->execute();
    	} catch(\Exception $e) {

    		$error = DBException::getMensaje($e);
    		return $error;
    	}

    	return "";
    }

    public function ImputarPlan()
    {
    	if ($this->est != 1)
    	{
    		return 'El estado del Convenio es incorrecto';
    	}

    	try {
    		$sql = 'Select sam.uf_Plan_Imputa('.$this->plan_id.','.Yii::$app->user->id.')';
    		$cmd = Yii :: $app->db->createCommand($sql);
			$rowCount = $cmd->execute();
    	} catch(\Exception $e) {
    		$error = DBException::getMensaje($e);
    		return $error;
    	}

    	return "";
    }

    public function DecaerPlan()
    {
    	if ($this->est != 1)
    	{
    		return 'El estado del Convenio es incorrecto';
    	}

    	try {
    		$sql = 'Select sam.uf_Plan_Decae('.$this->plan_id.','.Yii::$app->user->id.')';
    		$cmd = Yii :: $app->db->createCommand($sql);
			$rowCount = $cmd->execute();
    	} catch(\Exception $e) {
    		$error = DBException::getMensaje($e);
    		return $error;
    	}

    	return "";
    }

    public function AnulaImputaDecaePlan()
    {
    	try {
    		$sql = 'select sam.uf_Plan_Anula('.$this->plan_id.')';
    		$cmd = Yii :: $app->db->createCommand($sql);
			$rowCount = $cmd->execute();
    	} catch(\Exception $e) {
    		$error = DBException::getMensaje($e);
    		return $error;
    	}

    	return "";
    }

    public function GrabarModifPlan()
    {
    	$sql = "Update Plan Set Resp='".$this->resp."', RespTDoc = ".$this->resptdoc.",RespNDoc=".$this->respndoc;
    	$sql .= ",RespTel='".$this->resptel."',Distrib=".$this->distrib;
    	$sql .= ",tpago=".$this->tpago.",caja_id=".$this->caja_id.",temple=".$this->temple;
        $sql .= ",temple_area='".$this->temple_area."',bco_suc=".$this->bco_suc.",bco_tcta=".$this->bco_tcta;
        $sql .= ",tpago_nro='".$this->tpago_nro."',obs='".$this->obs;
    	$sql .="', FchMod=current_timestamp,UsrMod=".Yii::$app->user->id;
        $sql .= " Where Plan_id = ".$this->plan_id;

        $cmd = Yii :: $app->db->createCommand($sql);
		$rowCount = $cmd->execute();

		if ($rowCount > 0)
		{
			return "";
		} else {
			return "Ocurrio un error al intentar grabar en la BD.";
		}
    }

    public function AdelantaPlan($cantctas,$grabar,&$cuota,&$capital,$quitafinc)
    {
    	try {
    		$sql = "Select * From sam.UF_PLAN_ADELANTA (".$this->plan_id.",'".$this->obj_id."',";
        	$sql .= $cantctas.",current_date,".Yii::$app->user->id.",".$grabar.",".$quitafinc.")";

    		$cmd = Yii :: $app->db->createCommand($sql);
			$array = $cmd->queryAll();
    	} catch(\Exception $e) {
    		$error = DBException::getMensaje($e);
    		return trim($error);
    	}

        $array = $array[0];
        $cuota = $array['cuota'];
        $capital = $array['capital'];

        return "OK";
    }

    public function EliminarAdelantaCuota($cuota)
    {
    	try {
    		$sql = "Select sam.uf_plan_adelanta_baja(".$this->plan_id.",".$cuota.",".Yii::$app->user->id.")";

    		$cmd = Yii :: $app->db->createCommand($sql);
			$rowCount = $cmd->execute();
    	} catch(\Exception $e) {
    		$error = DBException::getMensaje($e);
    		return trim($error);
    	}

        return "";
    }

    public function AdelantaPlanVer()
    {
    	$plan = ($this->plan_id == '' ? 0 : $this->plan_id);
    	$cant = Yii::$app->db->createCommand("Select * From sam.UF_PLAN_ADELANTA_VER (".$plan.")")->queryScalar();
    	return $cant;
    }

    public function CargarCuotas($id)
    {
    	$count = Yii::$app->db->createCommand("Select count(*) From V_Plan_Cuota where Plan_Id=".$id)->queryScalar();

        $sql = "Select * From V_Plan_Cuota where plan_id=".$id;
        $sql = $sql." Order By Cuota ";

        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'key' => 'plan_id',
            'totalCount' => (int)$count,
			'pagination'=> [
				'pageSize'=>(int)$count,
			],
        ]);

        return $dataProvider;
    }

    public function CargarPeriodos($id)
    {
    	$count = Yii::$app->db->createCommand("Select count(*) From V_Plan_Periodo where Plan_Id=".$id)->queryScalar();

        $sql = "Select CtaCte_Id, 'X' Marca, Trib_id, Trib_nom, Obj_id, SubCta, Anio, Cuota, EstAnt Est,";
        $sql .= "cast(Nominal as decimal(12,2)), cast(Accesor as decimal(12,2)), cast(Multa as decimal(12,2)), cast(Total as decimal(12,2)),";
        $sql .= " NominalReal-Nominal QNom, AccesorReal-Accesor QAcc, MultaReal-Multa QMul,";
        $sql .= " NominalReal, AccesorReal, MultaReal,TotalReal, FchVenc, EstAnt, ";
        $sql .= " cast(NominalCub as decimal(12,2)), cast(AccesorCub as decimal(12,2)), cast(MultaCub as decimal(12,2)),";
        $sql .= " cast(TotalCub as decimal(12,2)), Saldo From V_Plan_Periodo where plan_id=".$id;
        $sql .= " Order By Trib_id, Anio, Cuota ";

        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'key' => 'ctacte_id',
            'totalCount' => (int)$count,
			'pagination'=> [
				'pageSize'=>(int)$count,
			],
        ]);

        return $dataProvider;
    }

    public function BuscarPeriodos($obj_id, $tplan,$desde,$hasta,$novencido,$origen,$judi,$solojuicio,$solojudiid,$fecha)
    {
    	$Tipo = new PlanConfig();
    	$Tipo->cod = $tplan;
    	$TipoPlan = $Tipo->buscarUno();

    	if ($TipoPlan->aplica == 1 and $desde < $TipoPlan->aplicadesde) {
            $mdesde = $TipoPlan->aplicadesde;
    	}else{
            $mdesde = ($desde == 0 ? 1990001 : $desde);
    	}

        if ($TipoPlan->aplica == 1 and $hasta < $TipoPlan->aplicahasta) {
            $mhasta = $TipoPlan->aplicahasta;
        }else{
            $mhasta = ($hasta == 0 ? date('Y') * 1000 + date('m') : $hasta);
		}

		$sql = "Select Obj_id, Trib_id, Trib_nom, Sum(Saldo) as Total,obj_id || '-' || trib_id as key From sam.Uf_CtaCte_Objeto('" . $obj_id . "',";
        $sql .= ($fecha == '' ? 'null' : "'".$fecha."'") . "," . $mdesde . "," . $mhasta . ") ";
        if ($origen == 3) {
            if ($solojuicio) {
                $sql .= "Where Est = 'J' ";
            }else {
                $sql .= "Where Est in ('J','D') ";
            }
            if ($solojudiid){
                $sql .= " And CtaCte_Id In (Select CtaCte_Id From Judi_Periodo Where Judi_Id = " . $judi_Id . ") ";
            }
        }else {
            $sql .= "Where Est = 'D' ";
        }
        $sql .= " and Trib_id In (Select Trib_id From Plan_Config_Trib Where TPlan=" . $tplan . ") ";
        if (!$novencido) $sql .= "and FchVenc<" . ($fecha == '' ? 'null' : "'".$fecha."'");
        $sql .= " Group By Obj_id, Trib_id, Trib_nom";

        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'key' => 'key'
        ]);

        return $dataProvider;
    }

    public function CuotasPagas($id)
    {
    	$sql = "select sum(total) from v_plan_cuota where est='P' and plan_id=".$id;
    	$this->pagado = Yii::$app->db->createCommand($sql)->queryScalar();

    	$sql = "select count(*) from v_plan_cuota where est='P' and plan_id=".$id;
    	$this->cuotaspagas = Yii::$app->db->createCommand($sql)->queryScalar();
    }

    public function CuotasFalta($id)
    {
    	$sql = "select sum(total) from v_plan_cuota where est='D' and plan_id=".$id;
    	$this->saldo = Yii::$app->db->createCommand($sql)->queryScalar();

    	$sql = "select count(*) from v_plan_cuota where est='D' and plan_id=".$id;
    	$this->cuotasfalta = Yii::$app->db->createCommand($sql)->queryScalar();
    }

    public function GetPersona($num,&$tdoc,&$ndoc,&$tel,&$domi,&$nombre)
    {
    	$sql = "Select TDoc, NDoc, Nombre, DomPos_Dir, Tel From V_Persona Where Obj_id='".$num."'";
    	$cmd = Yii :: $app->db->createCommand($sql);
		$array = $cmd->queryAll();
		if (count($array) > 0)
		{
			$array = $array[0];
			$tdoc = $array['tdoc'];
			$ndoc = $array['ndoc'];
			$tel = $array['tel'];
			$domi = $array['dompos_dir'];
			$nombre = $array['nombre'];
		}
    }

    public function DeudaDetalle(&$error,$TipoPlan,$obj_id, $tplan,$desde,$hasta,$novencido,$origen,$judi_id,$solojuicio,$solojudiid,$fecha,$cond,
    						$judideuda,$num,&$array,&$nominal,&$accesor,&$multa,&$capital,$judiitem = 0,$judiinteres = 0)
    {

    	$sql = "Select count(*) From Judi Where Judi_Id=".$judi_id." and Obj_id='".$obj_id."'";
    	$count = Yii::$app->db->createCommand($sql)->queryScalar();

    	if ($count == 0 and $origen == 3)
    	{
    		$error = "El Objeto del CDF no coincide con el del Convenio";
    		$provider = new ArrayDataProvider([
    			'allModels' => null,
			]);
			return $provider;
    	}

    	if ($TipoPlan == null)
    	{
       		$Tipo = new PlanConfig();
    		$Tipo->cod = $tplan;
   			$TipoPlan = $Tipo->buscarUno();
    	}
    	if ($fecha != "") $fecha = date("Y/m/d",strtotime($fecha));

    	if ($TipoPlan->aplica == 1 and $desde < $TipoPlan->aplicadesde) {
            $mdesde = $TipoPlan->aplicadesde;
    	}else{
            $mdesde = ($desde == 0 ? 1990001 : $desde);
    	}

        if ($TipoPlan->aplica == 1 and $hasta < $TipoPlan->aplicahasta) {
            $mhasta = $TipoPlan->aplicahasta;
        }else{
            $mhasta = ($hasta == 0 ? date('Y') * 1000 + 99 : $hasta);
		}

		$sql = "Select o.CtaCte_Id, Trib_id, Trib_nom, Obj_id, SubCta, Anio, Cuota, ";
        if ($origen == 3 and $judideuda) {
            $sql .= " j.Nominal, j.Accesor, j.Multa ";
        }else{
            $sql .= " cast(o.Nominal-NominalCub as decimal(12,2)) Nominal, case when o.Accesor>0 then o.Accesor else 0 end Accesor, o.Multa ";
        }
        $sql .= ", o.Est, FchVenc From sam.Uf_CtaCte_Objeto('" . $obj_id . "',".($fecha == '' ? 'null' : "'".$fecha."'"). "," . $mdesde . "," . $mhasta . ") o ";

        if ($origen == 3) {
            $sql .= " left join judi_periodo j on j.judi_id=" . $judi_id . " and j.ctacte_id=o.ctacte_id ";
            if ($solojuicio){
                $sql .= "Where o.Est='J' ";
            }else{
                $sql .= "Where o.Est in ('J','D') ";
        	}
            if ($solojudiid) {
                $sql .= " And o.CtaCte_Id In (Select CtaCte_Id From Judi_Periodo Where Judi_Id = " . $judi_id . ") ";
            }
        }else{
            $sql .= "Where o.Est='D' ";
        }
        $sql .= " and Trib_id In (Select Trib_id From Plan_Config_Trib Where TPlan=" . $tplan . ") ";
        if (!$novencido) $sql .= " and FchVenc<" . ($fecha == '' ? 'null' : "'".$fecha."'");
        if ($cond != "") $sql .= " and (".$cond.")";

        if ($desde > 0 and utb::samConfig()['per_plan_decaido']) {
            $sql .= " UNION ";
            $sql .= "Select o.CtaCte_Id, Trib_id, Trib_nom, Obj_id, SubCta, Anio, Cuota, ";
            if ($origen == 3 and $judideuda) {
                $sql .= " j.Nominal, j.Accesor, j.Multa ";
            }else{
                $sql .= " cast(o.Nominal-NominalCub as decimal(9,2)) Nominal, case when o.Accesor>0 then o.Accesor else 0 end Accesor, o.Multa ";
        	}
            $sql .= ", o.Est, FchVenc From sam.Uf_CtaCte_Objeto('" . $obj_id . "',".($fecha == '' ? 'null' : "'".$fecha."'").",0," . $mdesde . ") o ";
            if ($origen == 3) {
                $sql .= " left join judi_periodo j on j.judi_id=" . $judi_id . " and j.ctacte_id=o.ctacte_id ";
                if ($solojuicio) {
                    $sql .= "Where o.Est='J' ";
                }else{
                    $sql .= "Where o.Est in ('J','D') ";
            	}
                if ($solojudiid) {
                    $sql .= " And o.CtaCte_Id In (Select CtaCte_Id From Judi_Periodo Where Judi_Id = " . $judi_id . ") ";
                }
            }else{
                $sql .= "Where o.Est='D' ";
            }

            $sql .= " and Trib_id In (Select Trib_id From Plan_Config_Trib Where TPlan=" . $tplan . ") ";
            if (!$novencido) $sql .= "and FchVenc<" .($fecha == '' ? 'null' : "'".$fecha."'");
            if ($cond != "") $sql .= " and (" . $cond . ")";
            $sql .= " and o.ctacte_id in (select ctacte_id from plan_periodo)";
        }
        $sql .= " Order By Trib_id, Obj_id, Anio, Cuota";

        try {
        	$cmd = Yii :: $app->db->createCommand($sql);
			$array = $cmd->queryAll();
        }catch(\Exception $e) {
        	$error = DBException::getMensaje($e);

        	$provider = new ArrayDataProvider([
    			'allModels' => null,
			]);
			return $provider;
        }

        if (count($array) > 0) {
        	for ($i = 0; $i < count($array); $i++){
        		$array[$i]['nominalreal'] = $array[$i]['nominal'];
            	$array[$i]['accesorreal'] = $array[$i]['accesor'];
            	$array[$i]['multareal'] = $array[$i]['multa'];
				if ($TipoPlan->descnominal > 0)
                    $array[$i]['nominal'] = $array[$i]['nominal'] * (1 - $TipoPlan->descnominal/100);
                else {
                    $array[$i]['nominal'] = $array[$i]['nominal'];
                }
        		if ($TipoPlan->descinteres > 0)
                    $array[$i]['accesor'] = $array[$i]['accesor'] * (1 - $TipoPlan->descinteres/100);
                else {
                    $array[$i]['accesor'] = $array[$i]['accesor'];
                }    
				$array[$i]['multa'] = $array[$i]['multa'] * (1 - $TipoPlan->descmulta/100);
        		$array[$i]['total'] = $array[$i]['nominal'] + $array[$i]['accesor'] + $array[$i]['multa'];
        		$array[$i]['qnom'] = $array[$i]['nominal'] * ($TipoPlan->descnominal/100);
        		$array[$i]['qacc'] = $array[$i]['accesor'] * ($TipoPlan->descinteres/100);
        		$array[$i]['qmul'] = $array[$i]['multa'] * ($TipoPlan->descmulta/100);
        		$array[$i]['estant'] = $array[$i]['est'];
            	$array[$i]['fchvenc'] = $array[$i]['fchvenc'];
            	$array[$i]['saldo'] = $array[$i]['total'];
            	$array[$i]['marca'] = "X";

        		$nominal += $array[$i]['nominal'];
        		$accesor += $array[$i]['accesor'];
        		$multa += $array[$i]['multa'];
        	}
        	$capital = $nominal + $accesor + $multa;
        }

        if ($origen == 3 and $judiitem > 0 and $judiinteres > 0)
        {
        	Plan::DeudaDetalleJudi($array,$nominal, $accesor, $multa,$capital,$judi_id, $fecha, $num, $judiitem,$judiinteres);
        }

       $provider = new ArrayDataProvider([
    		'allModels' => $array,
    		'pagination'=> [
				'pageSize'=>count($array),
			],
		]);
        return $provider;
    }

    public function DeudaDetalleJudi(&$array,&$nominal, &$accesor, &$multa,&$capital,$judi_id, $fecha, $num, $judiitem = 0, $judiinteres = 0)
    {
    	$array["ctacte_id"] = 0;
        $array["marca"] = " ";
        $array["trib_id"] = 5;
        $array["trib_nom"] = "Gtos. Jud.";
        $array["obj_id"] = $num;
        $array["subcta"] = 0;
        $array["anio"] = date($fecha,'Y');
        $array["cuota"] = Liquida::GetPeriodo(5,$num,0,date($fecha,'Y'));
        $array["est"] = "D";
        $array["nominal"] = $judiinteres;
        $array["accesor"] = 0;
        $array["multa"] = 0;
        $array["total"] = $array["nominal"] + $array["accesor"] + $array["multa"];
        $array["qnom"] = 0;
        $array["qacc"] = 0;
        $array["qmul"] = 0;
        $array["nominalreal"] = $array["nominal"];
        $array["accesorreal"] = 0;
        $array["multareal"] = 0;
        $array["estant"] = "D";
        $array["fchvenc"] = $fecha;
        $array["saldo"] = $array["total"];

        $nominal += $array[$i]['nominal'];
        $accesor += $array[$i]['accesor'];
        $multa += $array[$i]['multa'];
        $capital += $nominal + $accesor + $multa;
    }

    public function CalcularPlan($totaldeuda,$cantper,$cantcuotas,$fchcons,$tplan,$obj_id,&$anticipo,&$montocuota,&$financia,$interestasa=0)
    {
    	$sql = "select cuota,cuota_nom,capital,financia,total,to_char(venc,'dd/MM/yyyy') as fchvenc, sellado, 'D' est ";
        $sql .= "from sam.uf_plan_previo(".str_replace(",", ".", $totaldeuda).",".$cantper.",";
        $sql .= $cantcuotas.",".($fchcons == '' ? 'null' : "'".$fchcons."'").",".$tplan.",'".$obj_id."',";
        $sql .= str_replace(",", ".", $anticipo) .",".str_replace(",", ".", $interestasa).")";

        try {
    		$cmd = Yii :: $app->db->createCommand($sql);
			$array = $cmd->queryAll();
			Yii::$app->session['PrelimPer'] = $sql;
    	} catch(\Exception $e) {

    		$error = DBException::getMensaje($e);
    		return $error;
    	}

        if ($cantcuotas > 0)
        {
        	if (count($array) > 0 and $array[0]['cuota'] == 1)
        	{
        		$montocuota = $array[0]['total'];
        	} else {
        		$montocuota = $array[1]['total'];
        	}
        	$financia = 0;

        	// Cuando la cuota es 1 la financiación es 0
			if (count($array) > 0){
        		for ($i = 0; $i < count($array); $i++){
        			if ($array[$i]['cuota'] > 0) $financia += $array[$i]['financia'];
        		}
        	}
        }

        $porcant = utb::getCampo("plan_config","cod=".$tplan,"anticipo");
		if ($porcant > 0) $anticipo = ($totaldeuda * $porcant)/100;

        return "";
    }

    public function Grabar()
    {
    	$this->plan_id = Plan::GetPlanId();

    	$sql = "Insert Into Plan(Plan_Id,TPlan,Obj_Id,Num,Resp,RespTDoc,RespNDoc,RespTel,Nominal,Accesor,Multa,";
        $sql .= "Financia,Sellado,Anticipo,Origen,TPago,Caja_id,TEmple,TEmple_Area,Bco_Suc,Bco_TCta,TPago_Nro,";
        $sql .= "Cuotas,MontoCuo,DescNominal,DescInteres,DescMulta,Interes,Obs,Est,FchAlta,UsrAlta,FchBaja,FchImputa,";
        $sql .= "FchDecae,FchConsolida,PlanAnt,Distrib,TDistrib,FchMod,UsrMod) ";
        $sql .= " Values (" . $this->plan_id . "," . $this->tplan . ",'" . $this->obj_id . "','" . $this->num . "','" . $this->resp . "',";
        $sql .= $this->resptdoc . "," . $this->respndoc . ",'" . $this->resptel . "'," . str_replace(",", ".", $this->nominal) . "," . str_replace(",", ".", $this->accesor) . ",";
        $sql .= str_replace(",", ".", $this->multa) . "," . str_replace(",", ".", $this->financia) . "," . str_replace(",", ".", $this->sellado) . "," . str_replace(",", ".", $this->anticipo) . ",";
        $sql .= $this->origen . "," . $this->tpago . "," . $this->caja_id . "," . $this->temple . ",'" . $this->temple_area . "',";
        $sql .= intVal( $this->bco_suc ) . "," . $this->bco_tcta . ",'" . $this->tpago_nro . "',";
        $sql .= $this->cuotas . "," . str_replace(",", ".", $this->montocuo) . "," . str_replace(",", ".", $this->descnominal) . ",";
        $sql .= str_replace(",", ".", $this->descinteres) . ", " . str_replace(",", ".", $this->descmulta) . ", " . str_replace(",", ".", $this->interes) . ", '" . $this->obs . "',";
        $sql .= $this->est . "," . ($this->fchalta == '' ? 'null' : "'".$this->fchalta."'") . "," . Yii::$app->user->id . ", null, null, null, " . ($this->fchconsolida == '' ? 'null' : "'".$this->fchconsolida ."'"). ",'";
        $sql .= $this->planant . "'," . intVal( $this->distrib ) . "," . intVal( $this->tdistrib ) . ", current_timestamp," . Yii::$app->user->id . ")";

        $cmd = Yii :: $app->db->createCommand($sql);
		$rowCount = $cmd->execute();

		if ($rowCount > 0)
		{
			return "";
		} else {
			return "Ocurrio un error al intentar grabar en la BD.";
		}
    }

    private function GetPlanId(){
        $sql = "Select nextval('seq_plan')";
        $plan_id = Yii::$app->db->createCommand($sql)->queryScalar();

        return ($plan_id);
    }

    public function grabarperiodo($judi_id,$plan_id,$arrayper)
    {
    	$error = "";
    	for ($i = 0; $i < count($arrayper); $i++){
    		if ($arrayper[$i]['marca'] == 'X')
    		{
    			if ($arrayper[$i]["trib_id"] == 5 and $arrayper[$i]["ctacte_id"] == 0) {
            		$arrayper[$i]["ctacte_Id"] = GenLiqJudi($error,$arrayper[$i],$judi_id);
    			}

    			if ($error != "") return $error;

    			$sql = "Insert Into Plan_Periodo Values (" . $plan_id . "," . $arrayper[$i]["ctacte_id"] . ",";
            	$sql .= str_replace(",", ".", $arrayper[$i]["nominalreal"]) . "," . str_replace(",", ".", $arrayper[$i]["nominal"]) . "," . str_replace(",", ".", $arrayper[$i]["accesorreal"]) . ",";
            	$sql .= str_replace(",", ".", $arrayper[$i]["accesor"]) . "," . str_replace(",", ".", $arrayper[$i]["multareal"]) . "," . str_replace(",", ".", $arrayper[$i]["multa"]);
            	$sql .= ", 0, 0, 0,'" . $arrayper[$i]["estant"] . "')";

            	$cmd = Yii :: $app->db->createCommand($sql);
				$rowCount = $cmd->execute();

				$sql = "Update CtaCte Set Est='C', Obs=Coalesce(Obs,'') || '/Plan:" . $plan_id . "/'";
            	$sql .= " Where CtaCte_Id=" . $arrayper[$i]["ctacte_id"];

            	$cmd = Yii :: $app->db->createCommand($sql);
				$rowCount = $cmd->execute();

				$sql = "Select sam.uf_ctacte_ucm(" . $arrayper[$i]["ctacte_id"] . ")";
				$cmd = Yii :: $app->db->createCommand($sql);
				$rowCount = $cmd->execute();
    		}
    	}
    }

    public function GenLiqJudi($error,$array,$judi_id)
    {
    	$ctacte = 0;
    	$liq_id = Liquida::GetLiqId();
    	$error = Liquida::GrabarLiquida($ctacte,'nuevo',$liq_id,5,$array['obj_id'],0,$array['anio'],$array['cuota'],$array['fchvenc'],
    				utb::getcampo('judi','judi_id='.$judi_id,'nro'),$array['obs'],0);

    	return $ctacte;
    }

    public function grabarcuotas($capital,$cantper,$cuotas,$fchconsolida,$tplan,$obj_id,$plan_id,$anticipo,$interestasa=0)
    {
    	$sql = "select sam.uf_plan_graba_cuotas(".str_replace(",", ".", $capital).",".$cantper.",".$cuotas.",".($fchconsolida == '' ? 'null' : "'".$fchconsolida."'");
    	$sql .= ",".$tplan.",'".$obj_id."',".$plan_id.",".Yii::$app->user->id.",".str_replace(",", ".", $anticipo).",".str_replace(",", ".", $interestasa).")";

    	$cmd = Yii :: $app->db->createCommand($sql);
		$rowCount = $cmd->execute();
    }

    public function grabarcuotasant($cuotas,$plan_id,$obj_id,$totalpagar,$descnominal)
    {
    	for ($i=0; $i<count($cuotas);$i++){

    		$sql = "select sam.uf_plan_graba_cuotas_old(".$plan_id.",'".$obj_id."',".utb::getTObj($obj_id).",".str_replace(",", ".", $totalpagar).",";
	        $sql .= str_replace(",", ".", $descnominal).",".$cuotas[$i]['cuota_nom'].",'".$cuotas[$i]['fchvenc']."',";
	        $sql .= ($cuotas[$i]['fchpago'] != '' ? "'".$cuotas[$i]['fchpago']."'" : "null").",'".$cuotas[$i]['est']."',".$cuotas[$i]['boleta'].",";
	        $sql .= $cuotas[$i]['capital'] . "," . $cuotas[$i]['financia'] . "," . $cuotas[$i]['total'] . "," . Yii::$app->user->id . ")";

	        $cmd = Yii :: $app->db->createCommand($sql);
			$rowCount = $cmd->execute();
    	}
    }

    public function ActualizarPlaJudi($plan_id,$judi_id)
    {
    	$sql = "Update Judi Set Est='C', Plan_Id=".$plan_id." Where Judi_Id=" . $judi_id;

    	$cmd = Yii :: $app->db->createCommand($sql);
		$rowCount = $cmd->execute();
    }

    public function BuscarPlan($cond, $order = '')
    {
        $count = 0;
        if ($cond !== "") $count = Yii::$app->db->createCommand("Select count(*) From V_Plan where ".$cond)->queryScalar();

        $sql = "Select * from V_Plan ";
        if ($cond !== "") $sql = $sql.' where '.$cond;
        if ($order !== "") $sql .= " order by ".$order;

        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'key' => 'plan_id',
            'totalCount' => (int)$count,
			'pagination'=> [
				'pageSize'=>40,
			],
        ]);

        return $dataProvider;
    }

    public function ImprimirContrato($plan_id,&$error)
    {
    	try {
    		$sql = "Select * From sam.Uf_Texto_Plan(" . $plan_id . ")";
    		$cmd = Yii :: $app->db->createCommand($sql);
			$array = $cmd->queryAll();
    	} catch(\Exception $e) {
    		$error .= "<li>" . DBException::getMensaje($e) . "</li>";

			return null;
    	}

    	return (count($array) > 0 ? $array[0] : $array);
    }

   public function ImprimirComprobanteValida($id, $cuotadesde,$cuotahasta)
   {
   		$model = Plan::findOne($id);
   		//Verificar Vigencia del Plan
        if ($model->est != 1) return "<li>El Convenio no se encuentra Vigente</li>";

        $sql = "Select count(*) From V_Emision_Print Where Trib_id = 1 and Anio=".$id." and Cuota>=".$cuotadesde." and Cuota<=".$cuotahasta;
        $count = Yii::$app->db->createCommand($sql)->queryScalar();
        if ($count <= 0) return "<li>No hay cuotas para imprimir</li>";

        $sql = "Select count(*) From V_Plan_Cuota Where Plan_id=".$id." and Cuota>=".$cuotadesde." and Cuota<=".$cuotahasta. " and est in ('B')";
        $count = Yii::$app->db->createCommand($sql)->queryScalar();
        if ($count > 0) return "<li>No podrá imprimir cuota dada de baja</li>";

        return "";
   }

   public function ImprimirComprobante($id, $cuotadesde,$cuotahasta,&$emision,&$sub1,&$sub2,&$sub3,&$sub4)
   {
   		$model = Plan::findOne($id);

        $sql = "Select *,to_char(fchemi,'dd/mm/yyyy') as fchemi,to_char(venc1,'dd/mm/yyyy') as venc1,to_char(venc2,'dd/mm/yyyy') as venc2,to_char(vencanual,'dd/mm/yyyy') as vencanual From V_Emision_Print Where Trib_id = 1 and Anio=".$id." and Cuota>=".$cuotadesde." and Cuota<=".$cuotahasta;
        $emision = Yii::$app->db->createCommand($sql)->queryAll();

        //Mensaje por Adelantamiento de Cuotas
        for ($i = 0; $i < count($emision); $i++)
        {
        	$cuotas = "";
        	if ($emision[$i]['cuota'] >= 900)
        	{
        		$sql = "Select sam.Uf_Plan_Adelanta_Cuotas(" . $id . "," . $emision[$i]['cuota'] . ")";
        		$cuotas = Yii::$app->db->createCommand($sql)->queryScalar();
        		$emision[$i]['mensaje'] = $cuotas;
        	}
        }

        //SubInforme 1 - Liquidación
        $sql = "Select * From V_Plan_Liq Where Plan_Id=" . $id;
        $sql .= " and cuota>=" . $cuotadesde . " and cuota<=" . $cuotahasta . " order by 5";
        $sub1 = Yii::$app->db->createCommand($sql)->queryAll();

        //SubInforme 2 - Firmante
        $sql = "Select distinct p.*, (Case when Cuota<900 then 0 else Cuota_Adelanta end) as Cuota_Adelanta,i.supt,i.supm,i.frente From V_Plan_Persona p";
        $sql .= " Left Join Plan_Cuota e on p.plan_id = e.plan_id ";
		$sql .= " Left Join inm i on p.obj_plan = i.obj_id ";
        $sql .= "Where p.Plan_Id=" . $id ." and cuota>=" . $cuotadesde . " and cuota<=" . $cuotahasta . " order by 5";
        $sub2 = Yii::$app->db->createCommand($sql)->queryAll();

        //SubInforme 3 - Listado de Periodos incluidos
        $sub3 = Yii::$app->db->createCommand("Select * From sam.uf_plan_periodos(".$id.")")->queryAll();

        //SubInforme 4 - Estado de cuenta
        $sub4 = Yii::$app->db->createCommand("Select * From sam.uf_CtaCte_Plan (1,".$id.")")->queryAll();
   }

   public function ImprimirResumen($plan_id)
    {
    	$sql = "Select *,to_char(fchalta,'dd/mm/yyyy') as fchalta,to_char(fchbaja,'dd/mm/yyyy') as fchbaja," .
    			"to_char(fchimputa,'dd/mm/yyyy') as fchimputa,to_char(fchdecae,'dd/mm/yyyy') as fchdecae From V_Plan Where plan_id=" . $plan_id;
    	$cmd = Yii :: $app->db->createCommand($sql);
		$array = $cmd->queryAll();

    	return (count($array) > 0 ? $array[0] : $array);
    }

    public function ModificarVencCuota($ctacte_id,$fchvenc)
    {
        $sql = "update ctacte set fchvenc='$fchvenc' where ctacte_id=$ctacte_id";

        $cmd = Yii :: $app->db->createCommand($sql);
        $rowCount = $cmd->execute();

        if ($rowCount > 0)
        {
            return "";
        } else {
            return "Ocurrio un error al intentar grabar en la BD.";
        }
    }
}
