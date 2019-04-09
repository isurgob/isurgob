<?php

namespace app\models\ctacte;

use Yii;
use app\utils\db\utb;
use yii\data\ArrayDataProvider;
use app\utils\helpers\DBException;

/**
 * This is the model class for table "ctacte".
 *
 * @property integer $ctacte_id
 * @property integer $trib_id
 * @property integer $tobj
 * @property string $obj_id
 * @property integer $subcta
 * @property integer $anio
 * @property integer $cuota
 * @property string $ucm
 * @property string $nominal
 * @property string $nominalcub
 * @property string $multa
 * @property string $est
 * @property string $fchemi
 * @property string $fchvenc
 * @property string $fchpago
 * @property integer $caja_id
 * @property string $montovenc1
 * @property string $montovenc2
 * @property string $montoanual
 * @property integer $texto_id
 * @property string $expe
 * @property string $obs
 * @property string $fchmod
 * @property integer $usrmod
 */
class Ctacte extends \yii\db\ActiveRecord
{
    public $deuda;

    public $perdesde;
    public $perhasta;
    public $est;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ctacte';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['trib_id', 'tobj', 'obj_id', 'anio', 'cuota', 'nominal', 'nominalcub', 'multa', 'est', 'fchvenc', 'usrmod'], 'required'],
            [['trib_id', 'tobj', 'subcta', 'anio', 'cuota', 'caja_id', 'texto_id', 'usrmod'], 'integer'],
            [['ucm', 'nominal', 'nominalcub', 'multa', 'montovenc1', 'montovenc2', 'montoanual'], 'number'],
            [['fchemi', 'fchvenc', 'fchpago', 'fchmod'], 'safe'],
            [['obj_id'], 'string', 'max' => 8],
            [['est'], 'string', 'max' => 1],
            [['expe'], 'string', 'max' => 12],
            [['obs'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ctacte_id' => 'Identificador de cuenta corriente',
            'trib_id' => 'Codigo de tributo',
            'tobj' => 'Codigo de tipo de objeto',
            'obj_id' => 'Codigo de objeto',
            'subcta' => 'Numero de subcuenta',
            'anio' => 'A?o',
            'cuota' => 'Cuota',
            'ucm' => 'Valor de ucm',
            'nominal' => 'Monto nominal',
            'nominalcub' => 'Monto nominal cubierto',
            'multa' => 'Monto multa',
            'est' => 'Estado',
            'fchemi' => 'Fecha de emision',
            'fchvenc' => 'Fecha de vencimiento',
            'fchpago' => 'Fecha de pago',
            'caja_id' => 'Codigo de caja',
            'montovenc1' => 'Monto para vencimiento 1',
            'montovenc2' => 'Monto para vencimiento 2',
            'montoanual' => 'Monto anual',
            'texto_id' => 'Identificador de texto',
            'expe' => 'Expediente',
            'obs' => 'Observaciones',
            'fchmod' => 'Fecha de modificacion',
            'usrmod' => 'Codigo de usuario que modifico',
        ];
    }

    public function CtaCteResumen(&$deuda, $obj_id, $fecha,$perdesde,$perhasta,$est,$planvig = true,$tobjpersona = "",$estobj=false)
    {
    	$cond = "";

    	$perfiscaliza = utb::getExisteProceso(3370);

    	if ($fecha=="") $fecha= date("d/m/Y");

    	$sql = "Select *,coalesce(tobj_nom,'') as tobj_nom From sam.uf_ctacte_resumen('$obj_id', '$fecha', $perdesde, $perhasta, '$est')";
    	$sql2 = "Select sum(case when trib_id <> 4 then coalesce(saldo,0) end) AS deuda From sam.uf_ctacte_resumen('".$obj_id."','".$fecha."',".$perdesde.",".$perhasta.",'".$est."')";

    	if ($planvig == false)
    		$cond .= " Where (trib_id not in (1,3) or (trib_id in (1,3) and Saldo<> 0))";

    	if ($perfiscaliza == false)
    		$cond .= ($cond == "" ? " where " : " and ")."trib_id <> 4";

    	if ($tobjpersona !== ""){

    		if ($tobjpersona !== "Comercio/Ing.Brutos")
    			$cond .= ($cond == "" ? " where " : " and ")."tobj_nom= '".$tobjpersona."'";
    		else $cond .= ($cond == "" ? " where " : " and ")."tobj_nom in('Comercio','Ingresos Brutos')";
    	}

		if ($estobj) $cond .= ($cond == "" ? " where " : " and ")."obj_est='A'";

    	/**
    	 * agregar la condicion de solamente obtener los periodos vencidos cuando $pervenc=true
    	 */
    	if($cond !== ""){

    		$sql .= $cond;
    		$sql2 .= $cond;
    	}

    	$sql .= " Order By trib_id, obj_id, subcta";
    	$modelos= Yii::$app->db->createCommand($sql)->queryAll();

    	$deuda= Yii::$app->db->createCommand($sql2)->queryScalar();

        return $modelos;
    }

    public function banderas($obj_id, $perdesde, $perhasta){

    	$sql= "Select * From sam.uf_ctacte_banderas('$obj_id', $perdesde, $perhasta)";
    	return Yii::$app->db->createCommand($sql)->queryOne();
    }

    public function CtaCteTributo($trib_id, $plan_id, $obj_id, $subcta, $fecha, $perdesde, $perhasta, $est, $baja, $pervenc, &$sql= '')
    {
    	$cond = "";

    	if ($fecha=="") $fecha= date("d/m/Y");

    	$tipoTributo= utb::getTTrib($trib_id);
    	$nombreObjeto= utb::getNombObj($obj_id, false);
    	if($nombreObjeto == false) $nombreObjeto= '';

    	$sql = "Select *, '$obj_id' As obj_id, $trib_id As trib_id, $tipoTributo as tipo_tributo, '".utb::ComillasSimples($nombreObjeto)."' as nombre_objeto, '$fecha' As fchcons, $subcta As subcta, $plan_id As plan_id, '$est' As estado, '$baja' As baja, " .
    			"case when obs <> '' then '*' else '' end as obs_asc From sam.uf_ctacte_tributo($trib_id, $plan_id, '$obj_id', $subcta, '$fecha',";
    	$sql .= $perdesde.",".$perhasta.")";

    	if (!$baja)
    		$cond .= " Where est <>'B'";
    	else if ($est !== "")
    		$cond .=  (($cond == '' ? ' Where ' : ' And ') . " est='".$est."'");

		if($pervenc)
			$cond .= (($cond == '' ? ' Where ' : ' And ') . " fchvenc < '".$fecha."'");


    	if ($cond !== "") $sql .= $cond;
    	//if ($cond !== "") $sql2 .= $cond;

    	if ($trib_id !== 1)
    	{
    		$sql .= " Order by anio desc, cuota desc";
    	}else {
    		$sql .= " Order by anio desc, cuota asc";
    	}

    	return Yii::$app->db->createCommand($sql)->queryAll();
    }

    public function AccionesBanderas($obj_id,$perdesde,$perhasta){

    	$arrayAcciones = array_fill_keys(['emi', 'condona', 'comp', 'exerec', 'debito', 'djfalta', 'conv'], '');

    	//-------------------------------------------------------------------------------------------------------------
    	// Emisión: muestra períodos sin aprobar
    	$sql = "Select nombre_redu, anio, cuota From ctacte c left join trib t on c.trib_id=t.trib_id Where obj_id='".$obj_id."' and c.est = ''";
    	$array = Yii::$app->db->createCommand($sql)->queryAll();
		$cantidad= count($array);

    	if ($cantidad > 0){

    		$texto = "Período con emisión sin aprobar: \n";

    		for ($i = 0; $i < $cantidad; $i++)
    			$texto .= "- ".$array[$i]['nombre_redu']." ".$array[$i]['anio']."/".$array[$i]['cuota']."\n";

    		$arrayAcciones['emi']= $texto;
    	}

    	//-------------------------------------------------------------------------------------------------------------
    	// Condona: muestra períodos condonados
    	$texto= '';
    	$sql = "Select nombre_redu, perdesde, perhasta From ctacte_cambioest c left join trib t on c.trib_id=t.trib_id ";
    	$sql .= "Where obj_id='".$obj_id."' and c.tipo=2 and c.est_dest='O'";
    	$array = Yii::$app->db->createCommand($sql)->queryAll();
		$cantidad= count($array);

    	if ($cantidad > 0){

			$texto = "Período condonados: \n";
    		for ($i = 0; $i < count($array); $i++)
    			$texto .= "- ".$array[$i]['nombre_redu']." ".$array[$i]['perdesde']."/".$array[$i]['perhasta']."\n";

    		$arrayAcciones['condona'] = $texto;
    	}



    	//-------------------------------------------------------------------------------------------------------------
    	// Comp: muestra el saldo de Compesa
    	$texto= '';
		$tobj = utb::getTObj($obj_id);
		if ($tobj != 3){ // si no es una persona
			$sql = "Select saldo From sam.uf_comp_saldo (0,'".$obj_id."')";
    	}else {
			$num = Yii::$app->db->createCommand("select num from objeto where obj_id='" . $obj_id . "'")->queryScalar();
			$sql = "select sum(c.monto-c.monto_aplic) saldo from comp c inner join objeto_persona op on c.obj_ori=op.obj_id where c.est='D' and op.num='" . $num . "'";
		}
    	$saldo = Yii::$app->db->createCommand($sql)->queryScalar();

    	$texto = "Saldo de Compensa: ".$saldo;

    	// LLeno el array de banderas
    	$arrayAcciones['comp'] = $texto;

    	//-------------------------------------------------------------------------------------------------------------
    	// ExeRec: muestra períodos en excepción
    	$texto= '';
    	$sql = "Select nombre_redu,anio,cuota From ctacte_excep c Left Join trib t on c.trib_id=t.trib_id ";
    	$sql .= "Where obj_id='".$obj_id."' and anio*1000+cuota between ".$perdesde." and ".$perhasta;
    	$array = Yii::$app->db->createCommand($sql)->queryAll();
    	$cantidad= count($array);


    	if ($cantidad > 0){

    		$texto = "Períodos con Excepción: \n";
    		for ($i = 0; $i < $cantidad; $i++)
    			$texto .= "- ".$array[$i]['nombre_redu']." ".$array[$i]['anio']."/".$array[$i]['cuota']."\n";

    		$arrayAcciones['exerec'] = $texto;
    	}

    	//-------------------------------------------------------------------------------------------------------------
    	// debito: Detalle de Adhesión
    	$tobj = utb::getTObj($obj_id);
		if ($tobj != 3){ // si no es una persona
			$sql = "Select caja_id, caja_nom, trib_nom, obj_id, per_desde, per_hasta From v_debito_adhe ";
			$sql .= "Where obj_id='".$obj_id."' and (trib_id=".utb::getTribInt()['contmej']." or perdesde between ".$perdesde." and ".$perhasta.") and est='A'";
		}else {// si es una persona
			$sql = "Select caja_id, caja_nom, trib_nom, obj_id, per_desde, per_hasta From v_debito_adhe ";
			$sql .= "Where obj_id in (Select op.obj_id from objeto_persona op Where op.num='" . $obj_id . "' and op.est='A') ";
			$sql .= " and (trib_id=".utb::getTribInt()['contmej']." or perdesde between ".$perdesde." and ".$perhasta.") and est='A'";
		}
    	$array = Yii::$app->db->createCommand($sql)->queryAll();
		$cantidad= count($array);

    	if ($cantidad > 0){

    		$texto = "Adhesiones: \n";
    		for ($i = 0; $i < $cantidad; $i++)
    			$texto .= "- ".$array[$i]['caja_nom'].": ".$array[$i]['trib_nom']." - ".$array[$i]['obj_id']." - ".$array[$i]['per_desde']."/".$array[$i]['per_hasta']."\n";

    		$arrayAcciones['debito']= $texto;
    	}

    	if ($tobj != 3){ // si no es una persona
			$sql = "Select caja_id, caja_nom, 'Convenio' trib_nom, plan_id From v_plan ";
			$sql .= "Where obj_id='".$obj_id."' and est = 1 and tpago=3";
    	}else {// si es una persona
			$sql = "Select caja_id, caja_nom, 'Convenio' trib_nom, plan_id From v_plan ";
			$sql .= "Where (obj_id in (Select op.obj_id from objeto_persona op Where op.num='" . $obj_id . "' and op.est='A') ";
			$sql .= " or obj_id='".$obj_id."') and est = 1 and tpago=3";
		}
		$array = Yii::$app->db->createCommand($sql)->queryAll();
    	$cantidad= count($array);

    	if ($cantidad > 0){

    		$texto .= "Convenios: \n";
    		for ($i = 0; $i < $cantidad; $i++)
    			$texto .= "- ".$array[$i]['caja_nom'].": ".$array[$i]['trib_nom']." ".$array[$i]['plan_id']."\n";

    		$arrayAcciones['debito'] .= ($texto !== "" ? "Débito: \n".$texto : $texto);
    	}

    	//-------------------------------------------------------------------------------------------------------------
    	// djfalta: Muestra las dj faltantes
    	$sql = "Select anio, cuota From ctacte c inner join trib t on c.trib_id=t.trib_id ";
        $sql.= "Where obj_id='".$obj_id."' and ((t.prescrip=0 and anio*1000+cuota>=".$perdesde.") or (t.prescrip>0 and anio*1000+cuota>=(extract(year from current_date)-t.prescrip-1)*1000+1)) ";
        $sql.= "and anio*1000+cuota <= ".$perhasta." and c.est = 'X' order by anio, cuota";

    	$array = Yii::$app->db->createCommand($sql)->queryAll();
    	$cantidad= count($array);

    	$texto = 'Falta presentar DDJJ: ';
    	if ($cantidad > 0){
            $anio_ant = 0;
    		for ($i = 0; $i < $cantidad; $i++) {
                if ($anio_ant != $array[$i]['anio'])
    			   $texto .= "\n - ".$array[$i]['anio']."/".$array[$i]['cuota']."";
                else
                    $texto .= ", ".$array[$i]['cuota'];
                $anio_ant = $array[$i]['anio'];
            }
    		$arrayAcciones['djfalta'] = $texto;
    	}

		//-------------------------------------------------------------------------------------------------------------
    	// conv: Muestra la lista de planes
    	$sql = "select p.plan_id from plan p ";
    	$sql .= "Where obj_id='".$obj_id."' and est=1";
    	$array = Yii::$app->db->createCommand($sql)->queryAll();
		$cantidad= count($array);

    	$texto = "Listado de Planes: \n";
    	if ($cantidad > 0){

    		for ($i = 0; $i < $cantidad; $i++)
    			$texto .= "- Plan Nº ".$array[$i]['plan_id']."\n";

    		$arrayAcciones['conv'] = $texto;
    	}

		return $arrayAcciones;
    }

    public function CtaCteDetAgrupar($ctacte_id, $baja = 0, &$sql= '')
    {

		$sql = "Select *,to_char(fecha,'dd/mm/yyyy') as fecha_format From sam.uf_ctacte_det_agrupa(".$ctacte_id.") ";
		if ($baja == 0) $sql .= " where est<>'B'";
		$sql .= "  Order by Fecha, topera, comprob ";

		$modelos= Yii::$app->db->createCommand($sql)->queryAll();

		return $modelos;
    }

    public function CtaCteDetBuscar($ctacte_id, $baja = 0, &$sql= ''){

		$sql = "Select *,to_char(fecha,'dd/mm/yyyy') as fecha_format From sam.uf_ctacte_det(".$ctacte_id.")";
		if ($baja == 0) $sql .= " where est<>'B'";
		$sql .= "  Order by fecha, topera,operacion,comprob,cta_id ";

		$modelos= Yii::$app->db->createCommand($sql)->queryAll();
		return $modelos;
    }

    public function CtaCteLiq($trib_id, $ctacte_id)
    {
    	$ttrib = utb::getTTrib($trib_id);
    	$sql= '';

    	if ($ttrib == 2 || $ttrib == 4) //Tipo Declarativo o Fiscalización
    	{
    		$sql = "Select item_id,item_nom,item_monto,'' detalle From v_dj_liq ";
    		$sql .= "Where ctacte_id=" .$ctacte_id. " and est='A' Order By item_id";
    	}else {

    		$sql = "Select item_id,item_nom,item_monto,coalesce(detalle,'') detalle From v_emision_liq  ";
    		$sql .= "Where ctacte_id=" .$ctacte_id. " Order By item_id";
    	}

    	$modelos= Yii::$app->db->createCommand($sql)->queryAll();
    	return $modelos;
    }

    public function CtaCteCambioEst($ctacte_id){

    	$sql = "Select e.tipo_nom, e.perdesde, e.perhasta, e.expe, e.obs, e.fchmod, e.usrmod_nom From v_ctacte_cambioest e Left Join ctacte c ";
    	$sql .= "On e.trib_id=c.trib_id and e.obj_id=c.obj_id and e.subcta=c.subcta ";
    	$sql .= "and c.anio*100+c.cuota between e.perdesde and e.perhasta ";
    	$sql .= "Where c.ctacte_id=".$ctacte_id;

		$modelos= Yii::$app->db->createCommand($sql)->queryAll();
		return $modelos;
    }

    public function CtaCteExcep($ctacte_id){

    	$sql = "Select tipo_nom, fchusar, fchlimite, obs, fchmod, usrmod_nom From v_ctacte_excep Where ctacte_id=".$ctacte_id;

    	$modelos= Yii::$app->db->createCommand($sql)->queryAll();
    	return $modelos;
    }

    public function CtaCteBaja($ctacte_id){

    	$sql = "Select ctacte_id,orden, tipo, ucm, nominal, fchemi, expe, obs, baja From v_emision_baja Where ctacte_id=".$ctacte_id." Order By orden Desc ";

    	$modelos= Yii::$app->db->createCommand($sql)->queryAll();
    	return $modelos;
    }

    private function getAjuste($aju_id){

    	$sql = "Select 'Expediente: ' || expe || case when obs <> '' then ' - Observaciones: ' || obs else '' end ";
        $sql .= "from ctacte_ajuste Where aju_id=".$aju_id;

        return $sql;
    }

    private function getPago($ticket){

    	//el retorno de carro origina un error en el codigo javascript.
//    	$sql = "Select 'Ticket: ' || ticket || ' - Operación: ' || opera || ' - Caja: ' || caja_nom || chr(13) || chr(10) || 'Tesorería: '";
		$sql = "Select 'Ticket: ' || ticket || ' - Operación: ' || opera || ' - Caja: ' || caja_nom || '. Tesorería: '";
    	$sql .= "  || teso_nom || ' - Fecha: ' || to_char(fecha,'DD/MM/YYYY') || ' - Hora: ' || hora ";
        $sql .= " From v_caja_ticket Where ticket=".$ticket;

        return $sql;
    }

    private function getCompensaDestino($comp_id){

    	$sql = "Select coalesce(min('Tipo: ' || tipo_nom || case when trib_ori_nom <> null ";
    	$sql .= "then ' - Tributo Orig: ' || trib_ori_nom else '' end || ' - Objeto Orig: ' || obj_ori ";
    	$sql .= "|| ' - Expediente: ' || expe || case when obs <> '' then ' - Obs: ' || obs else '' end),'') ";
    	$sql .= "From v_comp c inner join comp_aplic a On c.comp_id = a.comp_id ";
        $sql .= " Where c.comp_id = ".$comp_id;

        return $sql;
    }

    private function getCompensaOrigen($comp_id){

    	$sql = "Select coalesce(min('Tipo: ' || tipo_nom || case when trib_dest_nom <> null ";
    	$sql .= "then ' - Tributo Dest: ' || trib_dest_nom else '' end || ' - Objeto Dest: ' || obj_dest ";
    	$sql .= "|| ' - Expediente: ' || expe || case when obs <> '' then ' - Obs: ' || obs else '' end),'') ";
    	$sql .= "From v_comp c inner join comp_saldo a On c.comp_id = a.comp_id ";
        $sql .= " Where c.comp_id = ".$comp_id;

        return $sql;
    }

    private function getConvenio($plan_id){

    	$sql = "Select 'Tipo de Plan: ' || coalesce(tplan_nom,'Sin Tipo de Plan') || ' - Responsable: ' || resp || ' - Documento: '";
    	//$sql .= " || coalesce(respndoc,0) || chr(13) || chr(10) || 'Fecha alta: ' || to_char(FchAlta,'DD/MM/YYYY') || ' - Cantidad de Cuotas: ' || cuotas || case when obs <> '' then ' - Obs: ' || obs else '' end";
    	$sql .= " || coalesce(respndoc,0) || '. Fecha alta: ' || to_char(FchAlta,'DD/MM/YYYY') || ' - Cantidad de Cuotas: ' || cuotas || case when obs <> '' then ' - Obs: ' || obs else '' end";
    	$sql .= " From v_plan Where plan_id=".$plan_id;

    	return $sql;
    }

    private function getFacilidad($faci_id){

    	$sql = "Select 'Fecha Alta: ' || fchalta || ' - Total: ' || total";
    	$sql .= " From v_facilida Where faci_id=".$faci_id;

    	return $sql;
    }

    private function getDDJJ($dj_id){

    	$sql = "Select 'Base imponible: ' || base || ' - Monto Total: ' || monto || ' - Multa: ' || multa || ' - Fecha presentacion: ' || FchPresenta ";
    	$sql .= " From v_dj Where dj_id=".$dj_id;

    	return $sql;
    }

    public function obtenerDetalle($tipoOperacion, $comprobante, $caja_id= 0){

    	$sql= '';

    	switch($tipoOperacion){

    		case 11: $sql= $this->getAjuste($comprobante); break;
    		case 3:
    			if($caja_id == 60) return "Registro de Pagos Anteriores.";

    		case 5: $sql= $this->getPago($comprobante); break;
    		case 4:
    		case 16:
    				$sql= $this->getCompensaDestino($comprobante); break;

    		case 12: $sql= $this->getCompensaOrigen($comprobante); break;
    		case 6:
    		case 7:
    		case 8:
    				$sql= $this->getConvenio($comprobante); break;

    		case 9:
    		case 10:
    			$sql= $this->getFacilidad($comprobante); break;

    		case 2:
    		case 13:
    		case 15:
    			$sql= $this->getDDJJ($comprobante); break;
    	}

    	return $sql == '' ? '' : Yii::$app->db->createCommand($sql)->queryScalar();
    }

    public function CtaCteLiqBaja($ctacte_id, $orden){

    	$sql = "Select *,coalesce(detalle,'') as detalle From v_emision_baja_liq Where CtaCte_Id = ".$ctacte_id. " and orden=".$orden." Order By item_id";

    	$modelos= Yii::$app->db->createCommand($sql)->queryAll();
    	$cantidad= count($modelos);

    	$dataProvider = new ArrayDataProvider([
            'allModels' => $modelos,
            'totalCount' => $cantidad,
			'pagination'=> [
				'pageSize'=>10,
			],
        ]);

        return $dataProvider;
    }

    public function GetSubReporte($tobj, $obj_id)
    {
        switch ($tobj) {
    		case 1:
    			$sql = "Select obj_id, s1,s2,s3,Manz,Parc,ParP,Plano,ZonaT_nom,ZonaOP_nom, supt From v_inm Where obj_id='".$obj_id."'";
    			break;
    		case 2:
    			$sql = "Select obj_id,Legajo,Ib,CUIT,IVA_nom,FchHab From v_comer Where obj_id='".$obj_id."'";
    			break;
    		case 3:
    			$sql = "Select obj_id,TDoc_nom,NDoc,Iva_nom,CUIT,nacionalidad_nom,domleg_dir From v_persona Where obj_id='".$obj_id."'";
    			break;
    		case 4:
    			$sql = "Select obj_id,Tipo_nom,Cuadro_id,Cuerpo_id,Piso,Fila,Nume,FchVenc,cod_ant From v_cem Where obj_id='".$obj_id."'";
    			break;
    		case 5:
    			$sql = "Select * From v_rodado Where obj_id='".$obj_id."'";
    			break;
    		default:
    			return null;
        }
        $subreporte = Yii::$app->db->createCommand($sql)->queryAll();

        return (count($subreporte) > 0 ? $subreporte[0] : null);
    }

    public function CtacteObjeto($obj_id,$fecha,$perdesde,$perhasta,$est='',$order='', &$sql= '')
    {
    	$sql = "Select c.*, PlanAnt, t.nombre From sam.uf_ctacte_objeto('" . $obj_id . "','";
        $sql .= $fecha . "'," . $perdesde . "," . $perhasta . ") c ";
        $sql .= "left join plan p on c.anio =p.plan_id and c.Obj_id=p.obj_id ";
        $sql .= "left join mej_plan m on c.anio =m.plan_id and c.obj_id=m.obj_id ";
        $sql .= "left join mej_obra o on m.obra_id=o.obra_id ";
        $sql .= "left join mej_tobra t on o.tobra=t.cod ";
		$sql .= "inner join trib tr on c.trib_id=tr.trib_id ";
		$sql .= "Where c.anio >= extract(year from current_date) - tr.prescrip";
        if ($est != "") $sql .= " and c.Est in (" . $est . ")";
        if ($order != '') $sql .= " Order by ".$order;

        $array = Yii::$app->db->createCommand($sql)->queryAll();

        return $array;
    }

    public function EmitirRango($trib_id,$perdesde,$perhasta,$obj_id,$subcta,$pagos = false,$num = false){

    	if ($this->VerificarPagoRango($trib_id,$perdesde,$perhasta,$obj_id,$subcta) && !$pagos) return "El Período se encuentra Pago. No podrá reliquidar.";
		if ($this->VerificarPagoCuotaCero($trib_id,intVal(substr($perdesde,0,4)),$obj_id,$subcta) && !$pagos) return "Se encuentra paga la cuota 0. No podrá reliquidar.";
    	$error = '';
    	if (!$num){
            $error = $this->AuxEmitirRango($trib_id,$perdesde,$perhasta,$obj_id,$subcta, $pagos);
    	}else{
            $sql = "Select obj_id from objeto where num='" . $obj_id . "' and est='A'";
            $array = Yii::$app->db->createCommand($sql)->queryAll();
            if (count($array)==0) return "No se encontraron Objetos para el NUM";

            for ($i=0;$i<count($array);$i++)
            {
            	$error = $this->AuxEmitirRango($trib_id,$perdesde,$perhasta,$array[$i]['obj_id'],$subcta, $pagos);
            }
    	}
    	return $error;
    }

    private function VerificarPagoRango($trib_id,$perdesde,$perhasta,$obj_id,$subcta)
    {
    	$sql = "Select count(*) From ctacte Where trib_id=" . $trib_id . " and obj_id='" . $obj_id;
        $sql .= "' and subcta = " . $subcta . " and (anio*1000+cuota) BETWEEN ".$perdesde." and ".$perhasta."  and nominalcub>0";

        $count = Yii::$app->db->createCommand($sql)->queryScalar();

        return ($count > 0);
    }

	private function VerificarPagoCuotaCero($trib_id,$anio,$obj_id,$subcta)
    {
    	$sql = "Select count(*) From ctacte Where trib_id=" . $trib_id . " and obj_id='" . $obj_id;
        $sql .= "' and subcta = " . $subcta . " and anio = ".$anio." and cuota=0 and est='P'";

        $count = Yii::$app->db->createCommand($sql)->queryScalar();

        return ($count > 0);
    }

    private function AuxEmitirRango($trib_id,$perdesde,$perhasta,$obj_id,$subcta,$pagos = false)
    {
    	$cuota= 0;

    	//Verifico que exista la SubCuenta
    	if ($subcta > 0)
        {
        	$sql = "Select tobj from Trib Where trib_id=" . $trib_id;
            $tobj = Yii::$app->db->createCommand($sql)->queryScalar();

            if ($tobj == 1)
            {
                $sql = "Select count(*) From osm Where obj_id='" . $obj_id . "' and subcta=" . $subcta;
            }elseif ($tobj == 2){
                $sql = "Select count(*) From comer Where obj_id='" . $obj_id . "'";
        	}
            $cant = Yii::$app->db->createCommand($sql)->queryScalar();
            if ($cant == 0) return "No existe el número de cuenta en el Objeto";
        }

        // Verifico que el objeto no esté dado de Baja
        $sql = "Select est, fchbaja From objeto Where obj_id='" . $obj_id . "'";
        $array = Yii::$app->db->createCommand($sql)->queryAll();
        if (count($array) > 0)
        {
            for ($i=0;$i < count($array);$i++)
            {
                if ($array[$i]['est'] == 'B')
                {
                    // Verificar segun fecha de baja y periodo
                    if ($perdesde > utb::PerActualFch($trib_id, $array[$i]["fchbaja"]) or $perhasta < utb::PerActualFch($trib_id, $array[$i]["fchbaja"])) return "El Objeto fue dado de Baja";
                }
            }
        }

        if (substr($perdesde, 4, 3) != 0)
        {
        	//Verifico si se definieron los vencimientos
            $sql = "Select anio,cuota,fchvenc2 From trib_venc Where trib_id=" . $trib_id;
            $sql .= " and (anio*1000 + cuota) between " . $perdesde . " and " . $perhasta;
            $array = Yii::$app->db->createCommand($sql)->queryAll();
            if (count($array) <= 0) return  "No se han definido Vencimientos para el Período";
        }

        try {
    		for ($i=0;$i < count($array);$i++)
            {
            	$cant = 0;
                if (substr($perdesde, 4, 3) != 0)
                {
                	$anio = $array[$i]["anio"];
                    $cuota = $array[$i]["cuota"];
                }else {
                    $anio = substr($perdesde, 0, 4);
                }

                if ($cuota < 13)
                {
                	//Verifico que no este en Convenio, Facilidad o Juicio
                    $sql = "Select coalesce(count(*),0) From ctacte ";
                    $sql .= "Where trib_id=" . $trib_id . " and obj_id='" . $obj_id . "' and subcta=" . $subcta;
                    $sql .= " and anio=" . $anio . " and cuota=" . $cuota . " and est in ('C','J','F')";
                    $cant = Yii::$app->db->createCommand($sql)->queryScalar();

                    if ($cant == 0)
                    {
                    	if (!$pagos)
                    	{
                    		//Verifico que no este Pago
                            $sql = "Select coalesce(count(*),0) From ctacte ";
                            $sql .= "Where trib_id = " . $trib_id . " and obj_id='" . $obj_id . "' and subcta=" . $subcta;
                            $sql .= " and anio=" . $anio . " and cuota=" . $cuota . " and est ='P'";
                            $cant = Yii::$app->db->createCommand($sql)->queryScalar();
                    	}
                    	if ($cant == 0)
                    	{
                    		$sql = "Select sam.uf_emision(" . $trib_id . "," . $anio . ",";
                            $sql .= $cuota . ",'" . $obj_id . "'," . $subcta . "," . Yii::$app->user->id . ")";
                            $CtaCte_id = Yii::$app->db->createCommand($sql)->queryScalar();

                            $sql = "Select sam.uf_emision_aprobar(" . $CtaCte_id . "," . $trib_id . ",'" . $obj_id . "'," . $subcta . ",current_date," . Yii::$app->user->id . ")";
                            $cmd = Yii :: $app->db->createCommand($sql);
							$rowCount = $cmd->execute();

                            $sql = "Select sam.uf_emision_esta(" . $trib_id . ", " . $anio . ", " . $cuota . "," . Yii::$app->user->id . ")";
                            $cmd = Yii :: $app->db->createCommand($sql);
							$rowCount = $cmd->execute();
                    	}
                    }
                }
            }
    	} catch(\Exception $e) {

    		$error = DBException::getMensaje($e);

    		$contieneError= stripos($error, 'error') !== false;

    		$codigoError= $contieneError ? substr($error, 6, 4) : -1;

    		$sql = "Select count(*) from Emision_Err where Trib_id = " . $trib_id . " and Obj_Id = '" . $obj_id . "'";
            $sql .= " and SubCta = " . $subcta . " and Anio = " . $anio . " and Cuota = " . $cuota;
            $count = Yii::$app->db->createCommand($sql)->queryScalar();

            if ($count == 0 && $codigoError !== -1)
            {
            	$sql = "Insert Into Emision_Err Values (" . $trib_id . ",'" . $obj_id . "',";
                $sql .= $subcta . "," . $anio . "," . $cuota . "," . $codigoError . ")";
                $cmd = Yii :: $app->db->createCommand($sql);
				$rowCount = $cmd->execute();
            }

    		return $codigoError !== -1 ? utb::getCampo("emision_terr","cod=".$codigoError) : $error;
    	}

    	return "";
    }

    public function BorrarLiquida($ctacte_id, $motivo){

    	if(trim($motivo == '')) return 'Ingrese un motivo de baja';

    	try {
    		$sql = "Select sam.uf_emision_borrar(" . $ctacte_id . "," . Yii::$app->user->id . ",'B','" . $motivo . "')";
    		$cmd = Yii :: $app->db->createCommand($sql);
			$rowCount = $cmd->execute();
    	} catch(\Exception $e) {

    		$error = DBException::getMensaje($e);
    		return $error;
    	}

    	return "";
    }
	
	public function BorrarReLiquida($ctacte_id){

    	try {
    		$sql = "Select sam.uf_emision_borrar_reliq(" . $ctacte_id . ")";
    		$cmd = Yii :: $app->db->createCommand($sql);
			$rowCount = $cmd->execute();
    	} catch(\Exception $e) {

    		$error = DBException::getMensaje($e);
    		return $error;
    	}

    	return "";
    }

	public function EditarPeriodo($ctacte_id, $anio, $cuota){

    	$model = Ctacte::findOne($ctacte_id);

		$sql = "select count(*) from ctacte where anio=" . $anio . " and cuota=" . $cuota . " and trib_id=" . $model->trib_id . " and obj_id='" .$model->obj_id."'";
		$sql .= " and subcta=".$model->subcta;
		$cant = Yii::$app->db->createCommand($sql)->queryScalar();

		if ($cant > 0) return "La cuota ya existe";

		$sql = "select EXISTS(select 1 from trib_venc where anio=".$anio." and cuota=".$cuota." and trib_id=".$model->trib_id.")";

		$cant = Yii::$app->db->createCommand($sql)->queryScalar();

		if (!$cant) return "La cuota no tiene vencimiento";

		try {
    		$sql = "update ctacte set anio=" . $anio . ",cuota=" . $cuota . ",usrmod=" . Yii::$app->user->id . ",fchmod=current_timestamp";
			$sql .= " where ctacte_id=" . $ctacte_id;
    		$cmd = Yii :: $app->db->createCommand($sql);
			$rowCount = $cmd->execute();
    	} catch(\Exception $e) {

    		$error = DBException::getMensaje($e);
    		return $error;
    	}

    	return "";
    }

	public function ExisteCtacte($obj_id,$subcta=0)
	{
		$tobj = utb::getTObj($obj_id);

		$sql = "select exists(select 1 from ctacte c inner join trib t on c.trib_id=t.trib_id where t.tobj=" . $tobj .
		 " and c.est not in ('B','') and t.tipo=1 and c.obj_id='" . $obj_id . "' and c.anio>=extract(year from current_date)-10)";
		return Yii::$app->db->createCommand($sql)->queryScalar();
	}

	public function LiquidacionesPosterioresBajaLista($obj_id)
	{
		$sql = "select c.anio,c.cuota,t.nombre trib_nom,c.est from ctacte c inner join objeto o on c.obj_id=o.obj_id and c.fchvenc > o.fchbaja and c.est='D' and o.est='B' inner join trib t on c.trib_id=t.trib_id where c.obj_id='" . $obj_id . "'";
		// if (utb::getTObj($obj_id) == 3){
			// $sql .= " unio ";
			// $sql = "select c.anio,c.cuota,t.nombre trib_nom,c.est from ctacte c inner join persona p on c.obj_id=p.obj_id and c.fchvenc > p.fchbaja_ib and c.est='D' and p.est_ib='B' inner join trib t on c.trib_id=t.trib_id where c.obj_id='" . $obj_id . "'";
		// }
		$datos = Yii::$app->db->createCommand($sql)->queryAll();

		$dataProvider = new ArrayDataProvider([
            'allModels' => $datos,
			'totalCount' => count($datos),
			'pagination' => [
				'pageSize' => count($datos),
			],
        ]);

        return $dataProvider;
	}

	public function LiquidacionesPosterioresBaja($obj_id,$subcta=0)
	{
		$sql = "select exists(select 1 from ctacte c inner join objeto o on c.obj_id=o.obj_id and c.fchvenc > o.fchbaja and c.est='D' and o.est='B' where c.obj_id='" . $obj_id . "')";
		$existe = Yii::$app->db->createCommand($sql)->queryScalar();

		// if (utb::getTObj($obj_id) == 3){
			// $sql = "select exists(select 1 from ctacte c inner join persona p on c.obj_id=p.obj_id and c.fchvenc > p.fchbaja_ib and c.est='D' and p.est_ib='B' where c.obj_id='" . $obj_id . "')";
			// $existe = Yii::$app->db->createCommand($sql)->queryScalar();
		// }

		return $existe;
	}

	public function BorrarLiquidaPosteriorBaja($obj_id)
	{
		$sql = "select c.ctacte_id from ctacte c inner join objeto o on c.obj_id=o.obj_id and c.fchvenc > o.fchbaja and c.est='D' and o.est='B' where c.obj_id='" . $obj_id . "'";
		$datos = Yii::$app->db->createCommand($sql)->queryAll();

		try {
    		foreach ($datos as $cc){
				$sql = "Select sam.uf_emision_borrar(" . $cc['ctacte_id'] . "," . Yii::$app->user->id . ",'B','Liquidación posterior a baja del objeto')";
				Yii :: $app->db->createCommand($sql)->execute();
			}
    	} catch(\Exception $e) {
    		$error = DBException::getMensaje($e);
    		return $error;
    	}

		return "";
	}

	public function CuponPago($id,$trib,$fecha)
	{
		$sql = '';
		$tobj = utb::getTObj($id);

		if ($tobj == 1){
			$sql = "select ".$tobj." tobj,obj_id,nombre num_nom,cuit,'" . utb::getNombTrib($trib) . "' trib_nom,sam.uf_caja_cuponpago(" . $trib . ",obj_id,'".$fecha."'::date) codcuponpago ";
			$sql .= "from v_inm where obj_id='" . $id . "'";
		}
		if ($tobj == 2){
			$sql = "select ".$tobj." tobj,c.obj_id,c.num_nom,c.cuit,c.ib,'" . utb::getNombTrib($trib) . "' trib_nom,sam.uf_caja_cuponpago(" . $trib . ",c.obj_id,'".$fecha."',".$fecha.") codcuponpago ";
			$sql .= "from v_comer c where c.obj_id='" . $id . "'";
		}
		if ($tobj == 3){
			$sql = "select ".$tobj." tobj,obj_id,nombre num_nom,ndoc cuit,'" . utb::getNombTrib($trib) . "' trib_nom,sam.uf_caja_cuponpago(" . $trib . ",obj_id,'".$fecha."',".$fecha.") codcuponpago ";
			$sql .= "from v_persona where obj_id='" . $id . "'";
		}
		if ($tobj == 4){
			$sql = "select ".$tobj." tobj,obj_id,num_nom,'' cuit,'" . utb::getNombTrib($trib) . "' trib_nom,sam.uf_caja_cuponpago(" . $trib . ",obj_id,'".$fecha."',".$fecha.") codcuponpago ";
			$sql .= "from v_cem where obj_id='" . $id . "'";
		}
		if ($tobj == 5){
			$sql = "select ".$tobj." tobj,obj_id,num_nom,num_ndoc cuit,'" . utb::getNombTrib($trib) . "' trib_nom,sam.uf_caja_cuponpago(" . $trib . ",obj_id,'".$fecha."',".$fecha.") codcuponpago ";
			$sql .= "from v_rodado where obj_id='" . $id . "'";
		}

		if ($sql != '')
			return Yii::$app->db->createCommand($sql)->queryAll();
		else
			return null;
	}
}
