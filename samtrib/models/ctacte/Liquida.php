<?php

namespace app\models\ctacte;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\data\SqlDataProvider;
use app\utils\db\utb;
use app\utils\db\Fecha;
use app\models\ctacte\Ctacte;
use app\utils\helpers\DBException;

use Yii;

/**
 * This is the model class for table "ctacte_liq".
 *
 * @property integer $ctacte_id
 * @property integer $orden
 * @property integer $item_id
 * @property string $param1
 * @property string $param2
 * @property string $param3
 * @property string $param4
 * @property string $monto
 */
class Liquida extends \yii\db\ActiveRecord
{
    
	
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ctacte_liq';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ctacte_id', 'orden', 'item_id', 'monto'], 'required'],
            [['ctacte_id', 'orden', 'item_id'], 'integer'],
            [['monto'], 'number'],
            [['param1', 'param2', 'param3', 'param4'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ctacte_id' => 'Identificador de cuenta corriente',
            'orden' => 'Orden',
            'item_id' => 'Codigo de item',
            'param1' => 'Parametro 1',
            'param2' => 'Parametro 2',
            'param3' => 'Parametro 3',
            'param4' => 'Parametro 4',
            'monto' => 'Monto',
        ];
    }
    
    public function GrabarLiquida(&$ctacte,$accion,$liq_id,$trib,$obj,$subcta,$anio,$cuota,$fchvenc,$expe,$obs,$ucm)
    {
    	$sql = "Select count(*) from temp.ctacte_liq_manual where Liq_Id = ".$liq_id;
    	$count = Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	if ($count == 0) return "Ingrese al menos un Item";
    	
		$ttrib = utb::getTTrib($trib);
    	
    	if ($accion == 'nuevo')
    	{
    		if ($this->GetExistePerdiodo($trib,$obj,$subcta,$anio,$cuota)) return "Ya existe el Período en la Cuenta Corriente. Ingrese otro Número de Cuota";
    		
    		if ($ttrib == 3 and $cuota == 0)
    		{
    			$maxcuota = $this->GetPeriodo($trib,$obj,$subcta,$anio);
    		}
    		
    		if ($ttrib == 4 and $cuota == 0)
    		{
    			return "Para Tributos Periódicos la cuota es obligatoria";
    		}elseif ($ttrib == 4)
    		{
    			$sql = "Select count(*) from ctacte Where trib_id=".$trib." and obj_id='".$obj;
                $sql .= "' and subcta=".$subcta." and anio=".$anio." and cuota=".$cuota." and est<> 'B'";
                $cant = Yii::$app->db->createCommand($sql)->queryScalar();
                
                if ($cant > 0) return "La Liquidación ya existe";
    		}
    	}
    	$sql = "Select sam.uf_emision(".$trib.",".$anio.",".$cuota.",'".$obj."',".$subcta.",";
        $sql .= Yii::$app->user->id.",".$liq_id.",1,'".Fecha::bdToUsuario($fchvenc)."','".$expe."','".$obs."',".$ucm.")";
		
        try {
    		$cmd = Yii :: $app->db->createCommand($sql);
        	$ctacte = $cmd->queryScalar();
    	} catch(\Exception $e) {
    		
    		$error = DBException::getMensaje($e);
    		
    		if(stripos($error, 'error') > -1){
    			
    			$codigoError= intval(substr($e->errorInfo[2], -1, 1));
    			
    			if($codigoError > 0){
    				
    				$sql= "Select nombre From emision_terr Where cod = $codigoError";
    				$mensaje= Yii::$app->db->createCommand($sql)->queryScalar();
    				
    				return $mensaje !== false ? $mensaje : 'Ocurrió un error desconocido';
    			}
    		}

    		return $error;
    	}
        return "";
    }
    
    public function BorrarItemLiquida($liq)
    {
    	$sql = "Delete from temp.ctacte_liq_manual where liq_id = ".$liq;
    	$cmd = Yii :: $app->db->createCommand($sql)->execute();
    }
    
    public function BorrarLiquida($ctacte, $motivo)
    {
    	$sql = "Select sam.uf_emision_borrar(".$ctacte.",".Yii::$app->user->id.",'B','".$motivo."')";
    	$cmd = Yii::$app->db->createCommand($sql);
    	$rowCount = $cmd->execute();
    	return $rowCount > 0; 
    }
    
    public function BuscarLiquida($cond="", $orden="", $eventual=1)
    {
    	$sql = "Select ctacte_id,trib_id,trib_nom_red,trib_tipo,obj_id,subcta,obj_nom,obj_dato," .
    			"num,num_nom,anio,cuota,monto,montoanual,to_char(venc2, 'dd/MM/yyyy') as venc2,est,est_nom,expe,modif From V_Emision c where ";
    	$sql2 = "Select count(*) From V_Emision c where ";
    	
    	if ($eventual == 1) 
    	{
    		$sql .= "c.trib_tipo in (3,4) ";
    		$sql2 .= "c.trib_tipo in (3,4) ";
    	}
    	
    	if ($cond != "")
    	{
    		if ($eventual == 1) 
    		{
    			$sql .= " and ";
    			$sql2 .= " and ";
    		}
    		$sql .= $cond;
    		$sql2 .= $cond;
    	}
    	
    	$count = Yii::$app->db->createCommand($sql2)->queryScalar();
    	
    	if ($orden != "") $sql .= " Order By ".$orden;
    	
    	$dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'key' => 'ctacte_id',
            'totalCount' => (int)$count,
			'pagination'=> [
				'pageSize'=>40,
			],
        ]); 
        
        return $dataProvider;	
    }
    
    public function BuscarItem($ctacte_id)
    {
    	$model = CtaCte::findOne($ctacte_id);
		$valor_mm = Liquida::CalcularMM($model == null ? '' : $model->fchvenc,$model == null ? 0 : $model->trib_id);
		
		$sql = "Select count(*) From V_Emision_Liq Where CtaCte_id=" . $ctacte_id;
        $count = Yii::$app->db->createCommand($sql)->queryScalar();
        
        $sql = "Select *,item_monto/$valor_mm as modulo,item_monto,$valor_mm valor_mm From V_Emision_Liq Where CtaCte_id=" . $ctacte_id . " order by orden ";
        
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => (int)$count,
			'pagination'=> [
				'pageSize'=>(int)$count,
			],
        ]); 
        
        return $dataProvider;	  
      
    }
    
     public function CargarItem($item_id, $trib_id,$anio,$cuota)
    {
    	$sql = "Select i.Item_Id, i.Nombre, v.TCalculo, v.Monto, v.Porc, v.Minimo,";
    	$sql .= " ParamNombre1, ParamComp1, ParamNombre2, ParamComp2,";
    	$sql .= "ParamNombre3, ParamComp3, ParamNombre4, ParamComp4, Obs ";
    	$sql .= "From Item i Inner Join Item_Vigencia v On i.Item_Id = v.Item_id ";
    	$sql .= "Where i.Item_Id = ".$item_id." and ";
    	
    	if ($anio !== 0)
    	{
    		$sql .= ($anio*1000+$cuota);
    	}else {
    		$sql .= utb::PerActual($trib_id);	
    	}
        
        $sql .= " between PerDesde and PerHasta";
      
        $array = Yii::$app->db->createCommand($sql)->queryAll();
      
        return (count($array) > 0 ? $array[0] : $array);
    }
	
	public function CargarItemModif($item_id)
    {
    	$sql = "Select i.Item_Id, i.Nombre, v.TCalculo, v.Monto, v.Porc, v.Minimo,";
    	$sql .= " ParamNombre1, ParamComp1, ParamNombre2, ParamComp2,";
    	$sql .= "ParamNombre3, ParamComp3, ParamNombre4, ParamComp4, Obs ";
    	$sql .= "From Item i Inner Join Item_Vigencia v On i.Item_Id = v.Item_id ";
    	$sql .= "Where i.Item_Id = ".$item_id;
    	
    	$array = Yii::$app->db->createCommand($sql)->queryAll();
      
        return (count($array) > 0 ? $array[0] : $array);
    }
    
    public function CargarItemLiquida($ctacte_id, $liq_id)
    {
    	$sql = "Select * From V_Emision_Liq Where CtaCte_id=" . $ctacte_id . " order by orden ";
    	$array = Yii::$app->db->createCommand($sql)->queryAll();
    	
    	if (count($array) > 0)
    	{
    		$cc = Ctacte::findOne($ctacte_id) ;
			$valor_mm = ($cc->fchvenc != '' ? Liquida::CalcularMM($cc->fchvenc,$cc->trib_id) : 1);
			
    		for($i = 0; $i < count($array); $i++)
    		{
    			if ($array[$i]['item_tipo'] !== 6 and $array[$i]['item_tipo'] !== 7)
    			{
    				 $sql = "select count(*) from temp.ctacte_liq_manual where liq_id=".$liq_id." and orden=".$array[$i]['orden']." and item_id=".$array[$i]['item_id'];
    				 
    				 $count = Yii::$app->db->createCommand($sql)->queryScalar();
    				 
    				 if ($count == 0)
    				 {
    				 	$sql = "Insert into temp.ctacte_liq_manual values(".$liq_id.",".$array[$i]['orden'].",".$array[$i]['item_id'];
                     	$sql .= ",'".$array[$i]['param1']."','".$array[$i]['param2']."','".$array[$i]['param3']."','".$array[$i]['param4']."',".$array[$i]['item_monto']/$valor_mm.")";
                     
                     	$cmd = Yii :: $app->db->createCommand($sql);
                     	$rowCount = $cmd->execute();
    				 }
    			}
    		}
    	}
    }
    
    public function NuevoItem($liq_id,$item_id,$param1,$param2,$param3,$param4,$monto,$trib_id,$anio,$cuota,$obj_id,$subcta)
    {
    	$sql = "Select count(*) from temp.ctacte_liq_manual where Liq_Id = ".$liq_id;
    	$count = Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	if ($count >= 10) return "No puede ingresar mas de 10 item a la liquidación";
    	
    	$sql = "Select count(*) from temp.ctacte_liq_manual where Liq_Id = ".$liq_id. " and item_id=".$item_id;
    	
    	$count = Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	if ($count > 0) return "El ítem ya fue ingresado";
    	
    	$sql = "Select coalesce(max(orden),0)+1 from temp.ctacte_liq_manual where liq_Id = ".$liq_id;
    	$orden = Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	$sql = "Insert into temp.ctacte_liq_manual values(".$liq_id.",".$orden.",".$item_id.",".str_replace(",", ".", $param1);
    	$sql .= ",".str_replace(",", ".", $param2).",".str_replace(",", ".", $param3).",".str_replace(",", ".", $param4).",".str_replace(",", ".", $monto).")";

    	$cmd = Yii :: $app->db->createCommand($sql);
		$rowCount = $cmd->execute();
		
		$sql = "Select item_id,item_nom,param1,param2,param3,param4,item_monto From sam.uf_emision_calcular(";
    	$sql .= $trib_id.",".$anio.",".$cuota.",'".$obj_id."',".$subcta.",".$liq_id.")";
    	
    	$cmd = Yii :: $app->db->createCommand($sql);
		$rowCount = $cmd->execute();
    }
    
    public function ModificarItem($liq_id,$item_id,$orden,$param1,$param2,$param3,$param4,$monto,$trib_id,$anio,$cuota,$obj_id,$subcta)
    {
    	$sql = "Update temp.ctacte_liq_manual set Param1=".str_replace(",", ".", $param1);
    	$sql .= ",Param2=".str_replace(",", ".", $param2).",Param3=".str_replace(",", ".", $param3);
    	$sql .= ",Param4=".str_replace(",", ".", $param4).",Monto=".str_replace(",", ".", $monto);
    	$sql .= " where liq_id=".$liq_id." and orden=".$orden . " and item_id=".$item_id;
    	
    	$cmd = Yii :: $app->db->createCommand($sql);
		$rowCount = $cmd->execute();
		
		$sql = "Select item_id,item_nom,param1,param2,param3,param4,item_monto ";
    	$sql .= " from sam.uf_emision_calcular(".$trib_id.",".$anio.",".$cuota.",'".$obj_id."',".$subcta.",".$liq_id.")";
    	    	
    	$cmd = Yii :: $app->db->createCommand($sql);
		$rowCount = $cmd->execute();
    }
    
    public function BorrarItem($liq_id,$item_id,$orden,$trib_id,$anio,$cuota,$obj_id,$subcta)
    {
    	$sql = "Delete from temp.ctacte_liq_manual where liq_id=".$liq_id." and Item_Id=".$item_id;
    	
    	$cmd = Yii :: $app->db->createCommand($sql);
		$rowCount = $cmd->execute();
		
		$sql = "Select item_id,item_nom,param1,param2,param3,param4,item_monto ";
    	$sql .= " from sam.uf_emision_calcular(".$trib_id.",".$anio.",".$cuota.",'".$obj_id."',".$subcta.",".$liq_id.")";
    	    	
    	$cmd = Yii :: $app->db->createCommand($sql);
		$rowCount = $cmd->execute();
    }
    
    public function ConsultarItemUno($liq_id,$orden,$venc)
	{
		$sql = "select * from temp.ctacte_liq_manual where liq_id=".$liq_id." and orden=".$orden;
		$array = Yii::$app->db->createCommand($sql)->queryAll();
		
		if (count($array) == 0){
			$array = [
				'orden' => '',
				'param1' => '',
				'param2' => '',
				'param3' => '',
				'param4' => ''
			];
			
			return $array;
		}else {
			return $array[0];
		}
		
	}
	
	public function ConsultarItem($liq_id,$fchvenc='',$temp=0)
    {
    	$sql = "select count(*) from temp.ctacte_liq_manual t inner join item i on t.item_id=i.item_id where t.liq_id=".$liq_id;
        $count = Yii::$app->db->createCommand($sql)->queryScalar();
		
		$sql = "select i.trib_id from temp.ctacte_liq_manual t inner join item i on t.item_id=i.item_id where t.liq_id=".$liq_id;
        $trib = Yii::$app->db->createCommand($sql)->queryScalar();
		
		$valor_mm = ($fchvenc != '' ? $this->CalcularMM($fchvenc,$trib == '' ? 0 : $trib) : 1);
		
		if ($temp == 1){
			$sql = "Select t.item_id,i.nombre as item_nom,t.param1,t.param2,t.param3,t.param4,t.monto as modulo,orden,";
			$sql .= $valor_mm . " * t.monto item_monto,$valor_mm valor_mm ";
			$sql .= " from temp.ctacte_liq_manual t inner join item i on t.item_id=i.item_id where t.liq_id=".$liq_id . " order by t.orden ";
		}else {
			$sql = "Select t.item_id,i.nombre as item_nom,t.param1,t.param2,t.param3,t.param4,t.monto item_monto,orden,";
			$sql .= "t.monto/" . $valor_mm . " modulo,$valor_mm valor_mm ";
			$sql .= " from temp.ctacte_liq_manual t inner join item i on t.item_id=i.item_id where t.liq_id=".$liq_id . " order by t.orden " ;
		}	
		
		/*$sql = "Select t.item_id,i.nombre as item_nom,t.param1,t.param2,t.param3,t.param4,t.monto as item_monto,orden,";
		$sql .= $valor_mm . " modulo ";
		$sql .= " from temp.ctacte_liq_manual t inner join item i on t.item_id=i.item_id where t.liq_id=".$liq_id;*/
		
		$dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => (int)$count,
			'pagination'=> [
				'pageSize'=>(int)$count,
			],
        ]);  
        
        return $dataProvider;	
    }
    
    public function VarSistema($nombre)
    {
    	$sql = "Select Defecto From Var Where Uso = 1 and Nombre = '".$nombre."'";
    	$varsis = Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	return $varsis;
    }
    
    public function GetTotalItems_Temp($liq_id)
    {
    	$sql = "Select coalesce(sum(monto),0) from temp.ctacte_liq_manual c ";
    	$sql .= "left join item i on c.item_id =i.item_id where liq_id = ".$liq_id." and i.tipo<> 7 ";
        
        $monto = Yii::$app->db->createCommand($sql)->queryScalar();
        
        return $monto;
    }
    
    public function CalcularItem(&$error,$item_id,$anio,$cuota,$param1,$param2,$param3,$param4)
    {
    	$array = Liquida::CargarItem($item_id,0,$anio,$cuota);
    	
    	if (count($array) > 0 and $array['tcalculo'] == 0) 
    	{
    		$error = "Forma de cálculo No definida!";
    		return 0;
    	}
    	
    	if ($param1 == '') $param1=0;
    	if ($param2 == '') $param2=0;
    	if ($param3 == '') $param3=0;
    	if ($param4 == '') $param4=0;
    	
    	$sql = "Select sam.uf_Item_calcular(".$item_id.",".$anio.",".$cuota.",".str_replace(",", ".", $param1);
    	$sql .= ",".str_replace(",", ".", $param2).",".str_replace(",", ".", $param3).",".str_replace(",", ".", $param4).")";
    	
    	$monto = Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	return $monto;
    }
    
    public function GetPeriodo($trib,$obj,$subcta,$anio)
    {
    	$sql = "Select coalesce(Max(Cuota),0) + 1 From CtaCte where Trib_Id = ".$trib;
        $sql .= " and Obj_Id ='".$obj."' and SubCta = ".$subcta." and Anio = ".$anio;
        
        $cuota = Yii::$app->db->createCommand($sql)->queryScalar();
        
        if ($cuota == 0) $cuota = 1;
        
        return $cuota;
    }
    
    public function GetFechaVenc($trib,$anio,$cuota)
    {
    	$sql = "Select FchVenc1 From Trib_Venc ";
        $sql .= " Where Trib_Id = ".$trib." and Anio = ".$anio." and Cuota = ".$cuota;
        
        $fecha = Yii::$app->db->createCommand($sql)->queryScalar();
        
        return $fecha;
    }
    
    public function GetExistePerdiodo($trib,$obj,$subcta,$anio,$cuota)
    {
    	$sql = "Select count(*) From CtaCte Where trib_id=".$trib." and obj_id='".$obj."' and subcta=".$subcta." and anio=".$anio." and cuota=".$cuota." and est<> 'B'";
    	$count = Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	return $count > 0;
    }
    
    public function GetLiqId()
    {
    	$sql = "Select nextval('temp.seq_ctacte_liq_manual'::regclass)";
    	$liq = Yii::$app->db->createCommand($sql)->queryScalar();
    		
    	$liq += 1;	
    	
    	return $liq;
    }
    
    public function GetUCM($trib_id)
    {
    	$sql = "Select UCM From Trib Where Trib_Id = ".$trib_id;
    	
    	$ucm = Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	return $ucm;
    }
    
    public function GetMontoUCM($trib_id,$ucm)
    {
    	$sql = "Select t.ucm from trib t where t.trib_id=" . $trib_id;
        $ucm_usar = Yii::$app->db->createCommand($sql)->queryScalar();
        if ($ucm_usar == 1) {
            $sql = "Select c.ucm1 From sam.config c";
        }else {
            $sql = "Select c.ucm2 From sam.config c";
    	}
        
        $valorucm = Yii::$app->db->createCommand($sql)->queryScalar();

        return $ucm * $valorucm;
    }
    
    public function GetTextoUCM()
    {
    	$sql = "Select texto_UCM From sam.config";
    	
    	$texto = Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	return $texto;
    }
    
    public function GetTotalItems($liq_id,$ctacte_id)
    {
    	if ($liq_id != 0)
    	{
    		$sql = "Select sum(monto) From temp.ctacte_liq_manual where liq_id=".$liq_id;	
    	}else {
    		$sql = "Select sum(monto) From ctacte_liq where ctacte_id=".($ctacte_id == '' ? 0 : $ctacte_id);
    	}
    	    	
    	$total = Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	return $total;
    }
    
   public function ImprimirComprobante($id,&$emision,&$sub1,&$sub2,&$sub3,&$sub4,$sem=0)
   {
   		$model = Ctacte::findOne(['ctacte_id' => $id]);
   		
   		$sql = 'Delete from temp.emi where usr_id='.Yii::$app->user->id;
   		$cmd = Yii :: $app->db->createCommand($sql);
		$rowCount = $cmd->execute();
        
        if ($sem == 1)
        {
        	$sql = "select cuota,anio,obj_id from ctacte where ctacte_id=".$id;
        	$array = Yii::$app->db->createCommand($sql)->queryAll();
        	
        	if (count($array) > 0)
        	{
        		$anio = $array[0]['anio'];
        		$cuota = $array[0]['cuota'];
        		$obj_id = $array[0]['obj_id'];
        		
        		$sql = "Insert into temp.emi ";
            	$sql .= "select row_number() over (order by trib_id,obj_id,anio,cuota) as fila, ";
            	$sql .= "ctacte_id,trib_id,obj_id,subcta,num_nom,anio,cuota,monto,montoanual,venc2 as venc,";
            	$sql .= "domi, codpos, est_nom, " . Yii::$app->user->id . " as usr_id,inm_barr_nom,barr_nom,obj_dato ";
            	$sql .= "From v_emision where anio=" .$anio . " and cuota between " . $cuota . " and " . ($cuota + 2);
            	$sql .= " and obj_id='" . $obj_id . "'";
            	$sql .= " Order by trib_id,obj_id,anio,cuota";
            	
            	$cmd = Yii :: $app->db->createCommand($sql);
				$rowCount = $cmd->execute();
        	}
        }else {
        	$sql = "Insert into temp.emi ";
            $sql .= "select row_number() over (order by trib_id,obj_id,anio,cuota) as fila, ";
            $sql .= "ctacte_id,trib_id,obj_id,subcta,num_nom,anio,cuota,monto,montoanual,venc2 as venc,";
            $sql .= "domi, codpos, est_nom, " . Yii::$app->user->id . " as usr_id,inm_barr_nom,barr_nom,obj_dato ";
            $sql .= "From v_emision where CtaCte_id=" . $id;
            $sql .= " Order by trib_id,obj_id,anio,cuota";
            	
            $cmd = Yii :: $app->db->createCommand($sql);
			$rowCount = $cmd->execute();
        }
        
        if ($sem == 1)
        {
        	$sql = "Select distinct * From V_Emision_Print_Sem Where usr_id=" . Yii::$app->user->id . " and CtaCte_id=" . $id;
        }else{
        	$sql = "Select distinct *,to_char(fchemi,'dd/mm/yyyy') fchemi,to_char(venc1,'dd/mm/yyyy') venc1,to_char(venc2,'dd/mm/yyyy') venc2 From V_Emision_Print Where usr_id=" . Yii::$app->user->id . " and CtaCte_id=" . $id;
        }
        
		$emision = Yii::$app->db->createCommand($sql)->queryAll();
        
		if ($emision[0]['tobj'] == 1) $emision[0]['mensaje'] = "";
		
        $ttrib = $emision[0]['trib_tipo'];
        
        if ($ttrib == 3 or $ttrib == 4) // se trata de TTrib = 3, 4 (Eventual, Periódico - Busco la Obs)
        {
        	$trib = $emision[0]['trib_id'];
        	$mensaje = Yii::$app->db->createCommand("select obs from ctacte where ctacte_id=".$id)->queryScalar();
        	
        	if (Liquida::GetUCM($emision[0]['trib_id']) > 0) //si el tributo tiene UCM, obtengo el monto a la fecha de impresión
        	{
        		$mensaje .= "<br><br>MONTO A LA FECHA DE IMPRESIÓN:  $ ";
        		if ($emision[0]['nominalcub'] == 0)
        		{
        			$mensaje .= Liquida::GetMontoUCM($emision[0]['trib_id'],$emision[0]['monto']);
        		}else {
        			$mensaje .= $emision[0]['monto'] - $emision[0]['nominalcub'];
        		}
        	}
        	$emision[0]['mensaje'] = $mensaje;
        }else {
        	$sql = "Select texto_id From CtaCte Where CtaCte_Id = " . $id;
            
            $texto_id = Yii::$app->db->createCommand($sql)->queryScalar();
            if ($texto_id > 0) {
                $sql = "Select Detalle From sam.Uf_Texto_Boleta(" . $id . "," . $texto_id . ")";
                $emision[0]['mensaje'] =  Yii::$app->db->createCommand($sql)->queryScalar();
            }
        }
        
        //SubInforme 1 - Liquidación
        $sql = "Select * From V_Emision_Liq Where CtaCte_id=" . $id . " order by ctacte_id, item_id";
        $sub1 = Yii::$app->db->createCommand($sql)->queryAll();
        
        //SubInforme2 Depende del tipo de datos
        switch ($emision[0]['tobj']) {
    		case 1: 
    			$sql = "Select * From V_Inm Where obj_id = '" . $emision[0]["obj_id"] . "'"; 
    			$sub2 = Yii::$app->db->createCommand($sql)->queryAll();
    			break;
    		case 2: 
    			$sql = "Select *,to_char(fchhab,'dd/mm/yyyy') fchhab From V_Comer Where obj_id = '" . $emision[0]["obj_id"] . "'";
    			$sub2 = Yii::$app->db->createCommand($sql)->queryAll();
    			break;
    		case 3: 
    			$sql = "Select * From V_Persona Where obj_id = '" . $emision[0]["obj_id"] . "'";
    			$sub2 = Yii::$app->db->createCommand($sql)->queryAll();
    			break;
    		case 4: 
    			$sql = "Select * From V_Cem Where obj_id = '" . $emision[0]["obj_id"] . "'";
    			$sub2 = Yii::$app->db->createCommand($sql)->queryAll();
    			break;
    		case 5: 
    			$sql = "Select * From V_Rodado Where obj_id = '" . $emision[0]["obj_id"] . "'";
    			$sub2 = Yii::$app->db->createCommand($sql)->queryAll();
    			break;
    		case 6:
    			$sql = "Select * From V_Transporte Where obj_id = '" . $emision[0]["obj_id"] . "'";
    			$sub2 = Yii::$app->db->createCommand($sql)->queryAll();
    			break;
    		default: 
    			$sub2 = null;
    	} 
        
        if ($sem == 1)
        {
        	$sub4 = null;
        	
        	$sql = "select * From v_emision_print_sem o where usr_id=" . Yii::$app->user->id . " and obj_id='" . $emision[0]['obj_id'];
            $sql .= "' and anio=" . $emision[0]["anio"] . " and cuota between " . $emision[0]["cuota"] . " and " . ($emision[0]["cuota"] + 2);
            $sql .= " order by obj_id, cuota desc ";
            
            //SubInforme 3 
        	$sub3 = Yii::$app->db->createCommand($sql)->queryAll();
        }else {
        	if ($ttrib == 3 or $ttrib == 4) // SubInforme3 para los TTrib Eventual y Periódica. Tambien para ItemxObjeto
        	{
        		$sql = "Select * From V_Emision_Liq Where CtaCte_id =" . $id . " order by ctacte_id, item_id";
        		$sub3 = Yii::$app->db->createCommand($sql)->queryAll();
        	}else {
        		
				/*switch ($emision[0]['tobj']) {
    				case 1: 
    					if ($emision[0]['trib_tipo'] == 5)
    					{
    						$sql = "Select o.obj_id, o.SubCta, o.CtaOSM, o.TLiq, o.NumMedidor, c.FchLect, c.TLect, c.Lect_Ant, c.Lect_Act, coalesce(c.Consumo,0) as Consumo ";
                            $sql .= "From V_Inm_OSM o Inner Join V_Emision_Print e On e.Trib_Id =" . $emision[0]['trib_id'] . " and o.Obj_Id=e.Obj_Id and o.SubCta = e.SubCta ";
                            $sql .= "Left Join OSM_Consumo c On o.Obj_Id = c.Obj_Id and o.SubCta = c.SubCta and e.Anio = c.Anio and e.Cuota = c.Cuota ";
                            $sql .= "Where e.Obj_Id  = '" . $emision[0]["obj_id"] . "' and e.SubCta = " . $emision[0]["subcta"] . " and e.Anio = " . $emision[0]["anio"] . " and e.Cuota=" . $emision[0]["cuota"]; 
    						$sub3 = Yii::$app->db->createCommand($sql)->queryAll();	
    					}else {
    						$sql = "Select * From V_Inm_Mej Where Obj_Id='" . $emision[0]["obj_id"] . "'";
    						$sub3 = Yii::$app->db->createCommand($sql)->queryAll();	
    					}
    					
    					break;
    				case 2: 
    					$sql = "Select * From V_Objeto_Rubro Where Obj_Id='" . $emision[0]["obj_id"] . "' and " . ($emision[0]["anio"] * 1000 + $emision[0]["cuota"]) . " between nperdesde and nperHasta";
    					$sub3 = Yii::$app->db->createCommand($sql)->queryAll();
    					break;
    				default: 
    					$sub3 = null;
    			} */
				
				if ($emision[0]['tobj'] == 1 and $emision[0]['trib_tipo'] == 5){
					$sql = "Select o.obj_id, o.SubCta, o.CtaOSM, o.TLiq, o.NumMedidor, c.FchLect, c.TLect, c.Lect_Ant, c.Lect_Act, coalesce(c.Consumo,0) as Consumo ";
					$sql .= "From V_Inm_OSM o Inner Join V_Emision_Print e On e.Trib_Id =" . $emision[0]['trib_id'] . " and o.Obj_Id=e.Obj_Id and o.SubCta = e.SubCta ";
					$sql .= "Left Join OSM_Consumo c On o.Obj_Id = c.Obj_Id and o.SubCta = c.SubCta and e.Anio = c.Anio and e.Cuota = c.Cuota ";
					$sql .= "Where e.Obj_Id  = '" . $emision[0]["obj_id"] . "' and e.SubCta = " . $emision[0]["subcta"] . " and e.Anio = " . $emision[0]["anio"] . " and e.Cuota=" . $emision[0]["cuota"]; 
					$sub3 = Yii::$app->db->createCommand($sql)->queryAll();
				}else {
					$sql = "select * from v_objeto_tit where est='A' and obj_id='" . $emision[0]["obj_id"] . "'";
					$sub3 = Yii::$app->db->createCommand($sql)->queryAll();
				}
        	}	
        	
        	$sql = "Select * From temp.ctacte_est_deuda where trib_id = " . $emision[0]["trib_id"] . " and Obj_ID = '" . $emision[0]["obj_id"] . "' and SubCta = " . $emision[0]["subcta"];
        	$sub4 = Yii::$app->db->createCommand($sql)->queryAll();
        }
        
        return ""; 
   } 
   
   public function GetTributoDomi($trib_id,&$domi,&$tel,&$mail)
   {
   		$sql = "Select bol_domimuni, bol_domi, bol_tel, bol_mail from trib where Trib_Id = " . $trib_id;
   		$array = Yii::$app->db->createCommand($sql)->queryAll();
   		
   		if (count($array) > 0)
   		{
   			$domimuni = $array[0]['bol_domimuni'];
   			$domi = $array[0]['bol_domi'];
   			$tel = $array[0]['bol_tel'];
   			$mail = $array[0]['bol_mail'];
   		}	
   		return $domimuni;
   }
   
   public function GenerarEstCta($trib_id, $obj_id, $subcta)
   {
   		$sql = "Select genestcta From trib Where trib_id=".$trib_id;
        $genestcta = Yii::$app->db->createCommand($sql)->queryScalar();

        // Generar Estado de Deuda si corresponde
        if ($genestcta == 1)
        { 
            $sql = "Delete From temp.ctacte_est_deuda e Where e.trib_id=" . $trib_id;
            $sql .= "and e.obj_id='" . $obj_id . "' and e.subcta=" . $subcta;
            $cmd = Yii :: $app->db->createCommand($sql);
			$rowCount = $cmd->execute();

            //Genero la información actualizada
            $sql = "Insert Into temp.ctacte_est_deuda Select " . $trib_id . ", '" . $obj_id . "', " . $subcta . ",";
            $sql .= "e.anio, e.detalle, e.monto, e.accesor, e.multa, e.total, current_date ";
            $sql .= "From sam.uf_ctacte_est_deuda(" . $trib_id . ", '" . $obj_id . "', " . $subcta . ") e";
            $cmd = Yii :: $app->db->createCommand($sql);
			$rowCount = $cmd->execute();
        }
   }
   
   public function ConstanciaPago($ctacte_id,&$mdp)
   {
   		//Efectúo la búsqueda
        //$sql = "Select distinct *,to_char(venc1,'dd/mm/yyyy') as venc1,to_char(fchpago,'dd/mm/yyyy') as fchpago From V_Emision_Print Where CtaCte_id=" . $ctacte_id;
		
		$sql = "Select c.est,c.ctacte_id, t.nombre as trib_nom,c.anio, c.cuota, c.obj_id, o.nombre as obj_nom,sum(case when d.topera in (3,4,5,7,8,10,12,16,17) then haber else 0 end) as pagado,";
		$sql .= "o.obj_dato,to_char(c.fchvenc,'dd/mm/yyyy') as venc1,o2.nombre as num_nom,to_char(c.fchpago,'dd/mm/yyyy') fchpago From ctacte c inner join trib t on c.trib_id=t.trib_id ";
		$sql .= "left join ctacte_det d on c.ctacte_id = d.ctacte_id and d.est='A' left join objeto o on c.obj_id = o.obj_id left join objeto o2 on o.num = o2.obj_id ";
        $sql .= " where c.ctacte_id = " . $ctacte_id; 
        $sql .= " Group by c.ctacte_id, t.nombre,c.anio, c.cuota, c.obj_id, o.nombre,o.obj_dato,c.fchvenc,o2.nombre,c.fchpago"; 
		
        $array = Yii::$app->db->createCommand($sql)->queryAll();
		
		$sql = "select cm.nombre from caja_ticket t inner join caja_opera_mdp m on t.opera = m.opera inner join caja_mdp cm on m.mdp = cm.mdp where t.ctacte_id =" . $ctacte_id . " group by cm.nombre ";
		$mdp = Yii::$app->db->createCommand($sql)->queryScalar();

        return $array;
   }
   
   public function CalcularMM($fchvenc,$trib=0)
   {
		if ($fchvenc == null or $fchvenc == '') return 1;
		
		$sql = "select coalesce(uso_mm,1) from trib where trib_id=$trib";
		$uso_mm = Yii::$app->db->createCommand($sql)->queryScalar();
		if ($uso_mm <= 0) return 1;
		
		
		$sql = "select coalesce(valor,1) from calc_mm where '$fchvenc' between fchdesde and fchhasta";
		$valor = Yii::$app->db->createCommand($sql)->queryScalar();
		
		return $valor > 0 ? $valor : 1;
   }
           
}
