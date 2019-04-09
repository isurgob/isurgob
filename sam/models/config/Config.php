<?php

namespace app\models\config;

use Yii;

/**
 * This is the model class for table "sam.config".
 *
 * @property integer $ctarecargo
 * @property integer $ctarecargotc
 * @property integer $interes_min
 * @property integer $ctaredondeo
 * @property string $porcredondeo
 * @property integer $ctacte_anio_desde
 * @property string $texto_ucm
 * @property string $ucm1
 * @property string $ucm2
 * @property string $titulo_libredeuda
 * @property string $titulo2_libredeuda
 * @property string $mensaje_libredeuda
 * @property integer $proxvenc_libredeuda
 * @property integer $copias_recl
 * @property integer $calle_recl
 * @property string $path_recl
 * @property boolean $usar_codcalle_loc
 * @property boolean $usar_codcalle_noloc
 * @property boolean $inm_valida_nc
 * @property boolean $inm_valida_frente
 * @property boolean $inm_gen_osm
 * @property integer $trib_op_matric
 * @property integer $judi_item_gasto
 * @property integer $judi_item_hono
 * @property integer $ctadiferencia
 * @property integer $itemcobro
 * @property integer $itemcomision
 * @property integer $itemcomisionbco
 * @property integer $cajaverifdebito
 * @property boolean $repo_usu_nom
 * @property integer $djfaltantes
 * @property boolean $op_hab_plazas
 * @property boolean $per_plan_decaido
 * @property integer $comer_hab_vence
 * @property integer $juz_origentransito1
 * @property integer $juz_origentransito2
 * @property string $ib_modo
 */
class Config extends \yii\db\ActiveRecord
{

	public $usar_codcalle_noloc;

	public function __construct(){

		parent::__construct();

		$this->ctarecargo = 0;
		$this->ctarectc = 0;
		$this->ctaredondeo = 0;
		$this->ctadiferencia = 0;
		$this->judi_item_gasto = 0;
		$this->judi_item_hono = 0;
		$this->itemcobro = 0;
		$this->itemcomision = 0;
		$this->itemcomisionbco = 0;
		$this->comer_hab_vence = 0;
		$this->interes_min = 0;
		$this->ctacte_anio_desde = 0;
		$this->ucm1 = 0;
		$this->ucm2 = 0;
		$this->copias_recl = 1;
		$this->usar_codcalle_noloc = false;
		$this->juz_origentransito1 = 0;
		$this->juz_origentransito2 = 0;

		$this->cajaverifdebito= 0;
		$this->djfaltantes= 0;

		$this->inm_phmadre = 0;
	}

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sam.config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
    	$ret = [];

    	/**
    	 * VALORES REQUERIDOS
    	 */
    	$ret[] = [
    			['ctarecargo', 'interes_min', 'ctaredondeo', 'ctacte_anio_desde', 'proxvenc_libredeuda', 'ctadiferencia', 'comer_hab_vence', 'ucm1', 'ucm2'],
    			'required'
    			];

		/**
		 * FIN VALORES REQUERIDOS
		 */


    	/**
    	 * TIPO Y RANGO DE DATOS
    	 */
		$ret[] = [
				['usar_codcalle_loc', 'inm_valida_nc', 'inm_valida_frente', 'inm_gen_osm', 'repo_usu_nom', 'op_hab_plazas', 'per_plan_decaido', 'proxvenc_libredeuda', 'calle_recl','per_pedir_cuit','per_pedir_doc'],
				'boolean',
				'falseValue' => 0,
				'trueValue' => 1
				];

		$ret[] = [
				['porcredondeo', 'ucm1', 'ucm2'],
				'number',
				'min' => 0
				];

		$ret[] = [
				['ctarecargo', 'cta_id_act', 'ctaredondeo','ctarectc', 'ctadiferencia', 'juz_origentransito1', 'juz_origentransito2', 'bol_mail_port'],
				'integer',
				'min' => 0,
				'message' => 'Elija una {attribute}'
				];

		$ret[] = [
				['interes_min', 'ctacte_anio_desde', 'comer_hab_vence'],
				'integer',
				'min' => 0
				];

		$ret[] = [
				'copias_recl',
				'integer',
				'min' => 1
				];

		$ret[] = [
				['djfaltantes', 'cajaverifdebito'],
				'integer',
				'min' => 0,
				'max' => 2
				];

		$ret[] = [
				['ret_sin_aprob', 'inm_phmadre'],
				'integer',
				'min' => 0,
				'max' => 1
				];

		$ret[] = [
				['itemcobro', 'itemcomision', 'itemcomisionbco', 'trib_op_matric', 'judi_item_gasto', 'judi_item_hono'],
				'integer',
				'min' => 0,
				'message' => 'Elija un {attribute}'
				];

		$ret[] = [
			'ib_modo',
			'string',
			'max'	=> 1,
		];

		$ret[] = [
				'texto_ucm',
				'string',
				'max' => 10
				];

		$ret[] = [
				['titulo_libredeuda', 'titulo2_libredeuda','agrete_path'],
				'string',
				'max' => 100
				];

		$ret[] = [
				['path_recl', 'bol_path', 'bol_mail', 'bol_mail_clave', 'bol_mail_host'],
				'string',
				'max' => 50
				];

		$ret[] = [
				'mensaje_libredeuda',
				'string',
				'max' => 500
				];
		/**
		 * FIN TIPO Y RANGO DE DATOS
		 */

		/**
		 * VALORES POR DEFECTO
		 */
    	$ret[] = [
    			['cajaverifdebito', 'calle_recl', 'comer_hab_vence', 'ctacte_anio_desde', 'ctadiferencia', 'ctarecargo', 'ctaredondeo','ctarectc', 'djfaltantes',
    			'inm_gen_osm', 'inm_valida_frente', 'inm_valida_nc', 'interes_min', 'itemcobro', 'itemcomision', 'itemcomisionbco', 'judi_item_gasto', 'judi_item_hono',
    			'juz_origentransito1', 'juz_origentransito2', 'op_hab_plazas', 'per_plan_decaido', 'proxvenc_libredeuda', 'repo_usu_nom', 'trib_op_matric', 'ucm1', 'ucm2',
    			'usar_codcalle_loc','per_pedir_cuit','per_pedir_doc', 'com_validar_ib', 'ret_sin_aprob', 'inm_phmadre','cta_id_act'],
    			'default',
    			'value' => 0
    			];

    	$ret[] = [
    			'copias_recl',
    			'default',
    			'value' => 1
    			];

    	$ret[] = [
    			['mensaje_libredeuda', 'texto_ucm', 'titulo_libredeuda', 'titulo2_libredeuda','agrete_path'],
    			'default',
    			'value' => ''
    			];

    	$ret[] = [
    			'path_recl',
    			'default',
    			'value' => './uploads/recl/'
    			];

		$ret[] = [
			'ib_modo',
			'default',
			'value'	=> 'A',
		];

		/**
		 * FIN VALORES POR DEFECTO
		 */
    	return $ret;
    }

    /**
     * @inheritdoc
     */

    public function attributeLabels()
    {
        return [
            'ctarecargo' => '',
			'cta_id_act' => '',
			'ctarectc' => '',
            'interes_min' => '',
            'ctaredondeo' => '',
            'porcredondeo' => '',
            'ctacte_anio_desde' => '',
            'texto_ucm' => '',
            'ucm1' => '',
            'ucm2' => '',
            'titulo_libredeuda' => '',
            'titulo2_libredeuda' => '',
            'mensaje_libredeuda' => '',
            'proxvenc_libredeuda' => '',
            'copias_recl' => 'Cantidad de copias para reclamos',
            'calle_recl' => '',
            'usar_codcalle_loc' => '',
            'usar_codcalle_noloc' => 'Indica si se utiliza codificacion de calles para las calles que no son de la localidad',
            'inm_valida_nc' => '',
            'inm_valida_frente' => '',
            'inm_gen_osm' => '',
            'trib_op_matric' => '',
            'judi_item_gasto' => '',
            'judi_item_hono' => '',
            'ctadiferencia' => '',
            'itemcobro' => '',
            'itemcomision' => '',
            'itemcomisionbco' => '',
            'cajaverifdebito' => 'Accion a efectuar en caja cuando existe adhesion a debito automatico',
            'repo_usu_nom' => '',
            'djfaltantes' => '',
            'op_hab_plazas' => '',
            'per_plan_decaido' => '',
            'comer_hab_vence' => '',
            'path_recl'=>'',
            'juz_origentransito1'=>'',
            'juz_origentransito2'=>'',
			'com_validar_ib'	=> '',
			'ret_sin_aprob' => '',
			'inm_phmadre' => ''
        ];
    }

    public function validar(){

    	$error = "";

    	if(($this->interes_min < 0) || ($this->interes_min > 100)){

    		$error .= "Monto Minimo fuera de Rango, debe estar dentro del rango (0,100)";

    	}

    	if(($this->ctacte_anio_desde < 5) || ($this->ctacte_anio_desde > 20)){

    		$error .= "'Anio desde' de cuenta corriete fuera de rango, debe estar dentro del rango (5,20)";

    	}

    	if(($this->comer_hab_vence < 0) || ($this->comer_hab_vence > 12)){

    		$error .= "'Cantidad de meses de duracion de habilitacion fuera de rango, debe estar dentro del rango (0,12)";

    	}

    }


    public function grabar(){

    	if(!$this->validate()) return false;
    	$rouwCount = 0;

		if($this->porcredondeo==0){
			$redondeo=0;
		}else if($this->porcredondeo==1){
			$redondeo=0.1;
		}else if($this->porcredondeo==2){
			$redondeo=0.25;
		}else if($this->porcredondeo==3){
			$redondeo=0.5;
		}else if($this->porcredondeo==4){
			$redondeo=1;
		}
		//return $this->juz_origentransito1;
    	$sql = "UPDATE sam.config SET";
		$sql .= " ctarecargo=".$this->ctarecargo.", interes_min=".$this->interes_min.", ctaredondeo=".$this->ctaredondeo.", porcredondeo=".$redondeo;
		$sql .= ", ctacte_anio_desde=".$this->ctacte_anio_desde.", texto_ucm='".$this->texto_ucm."', ucm1=".$this->ucm1.", ucm2=".$this->ucm2;
		$sql .= ", titulo_libredeuda='".$this->titulo_libredeuda."', titulo2_libredeuda='".$this->titulo2_libredeuda."', mensaje_libredeuda='".$this->mensaje_libredeuda."'";
		$sql .= ", proxvenc_libredeuda=".$this->proxvenc_libredeuda.", copias_recl=".$this->copias_recl.", calle_recl=".$this->calle_recl.", path_recl='".$this->path_recl."'";
		$sql .= ", usar_codcalle_loc=".($this->usar_codcalle_loc==1 ? 'true' : 'false').", inm_valida_nc=".($this->inm_valida_nc==1 ? 'true' : 'false').", inm_valida_frente=".($this->inm_valida_frente==1 ? 'true' : 'false').", inm_gen_osm=".($this->inm_gen_osm==1 ? 'true' : 'false');
		$sql .= ", trib_op_matric=".$this->trib_op_matric.", judi_item_gasto=".$this->judi_item_gasto.", judi_item_hono=".$this->judi_item_hono;
		$sql .= ", ctadiferencia=".$this->ctadiferencia.", itemcobro=".$this->itemcobro.", itemcomision=".$this->itemcomision.", itemcomisionbco=".$this->itemcomisionbco;
		$sql .= ", cajaverifdebito=".$this->cajaverifdebito.", repo_usu_nom=".($this->repo_usu_nom==1 ? 'true' : 'false').", djfaltantes=".$this->djfaltantes.", op_hab_plazas=".($this->op_hab_plazas==1 ? "'true'" : "'false'");
		$sql .= ", per_plan_decaido=".($this->per_plan_decaido==1 ? 'true' : 'false').", comer_hab_vence=".$this->comer_hab_vence.", juz_origentransito1=".$this->juz_origentransito1.", juz_origentransito2=".$this->juz_origentransito2;
		$sql .= ", com_validar_ib = " . $this->com_validar_ib . ", per_pedir_cuit=".($this->per_pedir_cuit==1 ? 'true' : 'false').",per_pedir_doc=".($this->per_pedir_doc==1 ? 'true' : 'false');
		$sql .= ", ib_modo = '" . $this->ib_modo . "',ctarectc=".$this->ctarectc . ",agrete_path='" . $this->agrete_path . "',ret_sin_aprob=" . $this->ret_sin_aprob;
		$sql .= ", inm_phmadre = " . $this->inm_phmadre;
		$sql .= ", bol_path = '" . $this->bol_path . "', bol_mail = '" . $this->bol_mail . "', bol_mail_clave='" . $this->bol_mail_clave . "', bol_mail_host = '" . $this->bol_mail_host;
		$sql .= "', bol_mail_port=" . intval($this->bol_mail_port) . ",cta_id_act=" . intVal($this->cta_id_act);

		try{
			$cmd = Yii :: $app->db->createCommand($sql);
			$rowCount = $cmd->execute();
	 	}
	 	catch(\Exception $e){

	 		$this->addError($this->ctarecargo, $e);
	 		return false;
	 	}

	 	if($rowCount <= 0){
	 		$this->addError($this->ctarecargo, 'Ocurrió un error al intentar realizar la acción');
	 		return false;
	 	}


    	return true;
    }


}
