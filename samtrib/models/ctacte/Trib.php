<?php

namespace app\models\ctacte;

use Yii;
use yii\helpers\ArrayHelper;

class Trib extends \yii\db\ActiveRecord
{
	/** codigo para tipo = sellado */
	const TIPO_SELLADO= 6;

	/** codigo para tipo = boleto */
	const TIPO_BOLETO= 7;

	/** codigo para tipo = emision */
	const TIPO_EMISION= 1;

	/** codigo para tipo = periodico */
	const TIPO_PERIODICO= 4;

	/** codigo para tipo = declarativo */
	const TIPO_DECLARATIVO= 2;

	/** codigo para tipo = eventual */
	const TIPO_EVENTUAL= 3;

	/** codigo para tipo = item por objeto */
	const TIPO_ITEM_POR_OBJETO= 5;

	/** codigo para tipo = interno */
	const TIPO_INTERNO= 0;

	/** Desde 0 hasta este numero, el tributo es de uso interno y alguno de sus valores no se podran modificar */
	const MAXIMO_MODIFICAR= 20;


	/** contiene la descripcion del tipo de tributo */
	public $tipo_descripcion;

	/** contiene el nombre completo del estado */
	public $est_nom;

	/** determina si el tributo actual es de tipo sellado */
	public $esSellado;

	/** determina si el tributo actual es de tipo boleto */
	public $esBoleto;

	/** determina si el tributo actual es de tipo emision */
	public $esEmision;

	/** determina si el tributo actual es de tipo periodico */
	public $esPeriodico;

	/** determina si el tributo actual es de tipo declarativo */
	public $esDeclarativo;

	/** determina si el tributo actual es de tipo interno */
	public $esInterno;

	public function __construct(){

		parent::__construct();

		$this->trib_id= 0;
		$this->tipo= 0;
		$this->est= 'N';
		$this->calc_rec= 1;

		$this->esSellado= false;
		$this->esBoleto= false;
		$this->esEmision= false;
		$this->esPeriodico= false;
		$this->esDeclarativo= false;
		$this->esInterno= false;
	}

	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'v_trib';
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
    			'trib_id',
    			'required',
    			'on' => ['update', 'delete'],
    			'message' => 'Elija un tributo'
    			];

    	$ret[]= [
    			['nombre', 'nombre_redu'],
    			'required',
    			'when' => function($model){return $model->trib_id > self::MAXIMO_MODIFICAR;},
    			'on' => ['update']
    			];

    	$ret[]= [
    			['nombre', 'nombre_redu'],
    			'required',
    			'on' => ['insert']
    			];

    	$ret[]= [
    			'tipo',
    			'required',
    			'on' => ['insert'],
    			'message' => 'Elija un tipo de tributo'
    			];

    	$ret[]= [
    			'tipo',
    			'required',
    			'when' => function($model){return $model->trib_id > self::MAXIMO_MODIFICAR;},
    			'on' => ['update'],
    			'message' => 'Elija un tipo de tributo'
    			];

    	$ret[]= [
    			'nombre_reduhbank',
    			'required',
    			'when' => function($model){return $model->esRequerido('nombre_reduhbank');},
    			'on' => ['insert', 'update']
    			];

    	$ret[]= [
    			'tobj',
    			'required',
    			'when' => function($model){return $model->esRequerido('tobj');},
    			'on' => ['insert'],
    			'message' => 'Elija un {attribute}'
    			];

    	$ret[]= [
    			'tobj',
    			'required',
    			'when' => function($model){return $model->trib_id > self::MAXIMO_MODIFICAR && $model->esRequerido('tobj');},
    			'on' => ['update'],
    			'message' => 'Elija un {attribute}'
    			];

    	$ret[]= [
    			'inscrip_incomp',
    			'required',
    			'when' => function($model){return $model->esRequerido('inscrip_incomp');},
    			'on' => ['insert', 'update']
    			];

    	$ret[]= [
    			['bol_domi', 'bol_tel', 'bol_mail'],
    			'required',
    			'when' => function($model){return $model->esRequerido('bol_domi');},//se requieren los 3 en el mismo caso
    			'on' => ['insert', 'update']
    			];

    	$ret[]= [
    			'cta_id_rec',
    			'required',
    			'when' => function($model){return $model->esRequerido('cta_id_rec') and $model->calc_rec==1;},
    			'on' => ['insert', 'update']
    			];
    	/**
    	 * FIN CAMPOS REQUERIDOS
    	 */

    	/**
    	 * TIPO Y RANGO DE DATOS
    	 */
    	$ret[]= [
    			['bol_domimuni', 'uso_subcta'],
    			'boolean',
    			'falseValue' => 0,
    			'trueValue' => 1,
    			'on' => ['insert', 'update']
    			];

    	/*$ret[]= [
    			['calc_rec_tasa', 'rec_venc2'],
    			'number',
    			'min' => 0,
    			'on' => ['insert', 'update']
    			];*/

		$ret[]= [
    			'quitafaci',
    			'number',
    			'min' => 0,
    			'max' => 99.99,
    			'on' => ['insert', 'update']
    			];

    	$ret[]= [
    			'trib_id',
    			'integer',
    			'min' => 1,
    			'on' => ['update']
    			];

    	$ret[]= [
    			'tobj',
    			'integer',
    			'min' => 1,
    			'when' => function($model){return $model->esRequerido('tobj');},
    			'on' => ['insert', 'update']
    			];

    	$ret[]= [
    			'tipo',
    			'integer',
    			'min' => 1,
    			'on' => ['insert'],
    			'message' => 'Elija un tipo de tributo'
    			];

    	$ret[]= [
    			'tipo',
    			'integer',
    			'min' => 0,
    			'on' => ['update'],
    			'message' => 'Elija un tipo de tributo'
    			];

    	$ret[]= [
    			'ucm',
    			'integer',
    			'min' => 0,
    			'max' => 3,
    			'on' => ['insert', 'update'],
    			'message' => 'Elija un valor de UCM válido'
    			];

    	$ret[]= [
    			'prescrip',
    			'integer',
    			'min' => 0,
    			'max' => 20,
    			'on' => ['insert', 'update']
    			];

    	/**
    	 * FIN TIPO Y RANGO DE DATOS
    	 */

		/**
		 * VALORES POR DEFECTO
		 */
		$ret[]= [
				'calc_rec',
				'default',
				'value' => 1,
				'on' => ['insert', 'update']
				];

		$ret[]= [
				'nombre_reduhbank',
				'trim',
				'filter' => function(){return '';},
				'when' => function($model){return !$model->esRequerido('nombre_reduhbank');},
				'on' => ['insert', 'update']
				];

		$ret[]= [
				'tobj',
				'trim',
				'filter' => function(){return 0;},
				'when' => function($model){return !$model->esRequerido('tobj');},
				'on' => ['insert', 'update']
				];

		$ret[]= [
				'dj_tribprinc',
				'trim',
				'filter' => function(){return 0;},
				'when' => function($model){return !$model->esRequerido('dj_tribprinc');},
				'on' => ['insert', 'update']
				];

		$ret[]= [
				'quitafaci',
				'trim',
				'filter' => function(){return 0;},
				'when' => function($model){return !$model->esRequerido('quitafaci');},
				'on' => ['insert', 'update']
				];

		$ret[]= [
				'calc_rec',
				'trim',
				'filter' => function(){return 0;},
				'when' => function($model){return !$model->esRequerido('calc_rec');},
				'on' => ['insert', 'update']
				];

		$ret[]= [
				'genestcta',
				'trim',
				'filter' => function(){return 0;},
				'when' => function($model){return !$model->esRequerido('genestcta');},
				'on' => ['insert', 'update']
				];

		$ret[]= [
				'compensa',
				'trim',
				'filter' => function(){return 0;},
				'when' => function($model){return !$model->esRequerido('compensa');},
				'on' => ['insert', 'update']
				];

		$ret[]= [
				'uso_subcta',
				'trim',
				'filter' => function(){return 0;},
				'when' => function($model){return !$model->esRequerido('uso_subcta');},
				'on' => ['insert', 'update']
				];

		$ret[]= [
				'inscrip_req',
				'trim',
				'filter' => function(){return 0;},
				'when' => function($model){return !$model->esRequerido('inscrip_req');},
				'on' => ['insert', 'update']
				];

		$ret[]= [
				'inscrip_auto',
				'trim',
				'filter' => function(){return 0;},
				'when' => function($model){return !$model->esRequerido('inscrip_auto');},
				'on' => ['insert', 'update']
				];

		$ret[]= [
				'inscrip_incomp',
				'trim',
				'filter' => function(){return 0;},
				'when' => function($model){return !$model->esRequerido('inscrip_incomp');},
				'on' => ['insert', 'update']
				];

		$ret[]= [
				['inscrip_auto', 'inscrip_incomp'],
				'default',
				'value' => 0,
				'on' => ['insert', 'update']
				];

		$ret[]= [
				['bol_domi', 'bol_tel', 'bol_mail'],
				'trim',
				'filter' => function(){return '';},
				'when' => function($model){return !$model->esRequerido('bol_domi');},//misma validacion para los 3
				'on' => ['insert', 'update']
				];

		$ret[]= [
				['calc_rec_tasa'],
				'trim',
				'filter' => function(){return 0;},
				//'when' => function($model){return !$model->esRequerido('calc_rec_tasa');},
				'on' => ['insert', 'update']
				];

		$ret[]= [
				'rec_venc2',
				'trim',
				'filter' => function(){return 0;},
				'when' => function($model){return !$model->rec_venc2 < 0;},
				'on' => ['insert', 'update']
				];

		/*$ret[]= [
				'prescrip',
				'trim',
				'filter' => function(){return 0;},
				'when' => function($model){return !$model->esRequerido('prescrip');},
				'on' => ['insert', 'update']
				];*/

		$ret[]= [
				'texto_id',
				'trim',
				'filter' => function(){return 0;},
				'when' => function($model){return !$model->esRequerido('texto_id');},
				'on' => ['insert', 'update']
				];

		$ret[]= [
				'dj_tribprinc',
				'default',
				'value' => function($model){return $model->trib_id;},
				'on' => ['insert', 'update']
				];

		$ret[]= [
				'dj_tribprinc',
				'existeTributo',
				'when' => function($model){return $model->trib_id !== $model->dj_tribprinc && $model->esRequerido('dj_tribprinc');},
				'on' => ['insert', 'update']
				];

		$ret[]= [
				'ucm',
				'default',
				'value' => 0,
				'on' => ['insert', 'update']
				];

		$ret[]= [
				['quitafaci', 'genestcta', 'compensa', 'uso_subcta', 'inscrip_req', 'inscrip_auto', 'inscrip_incomp', 'prescrip', 'calc_rec_tasa', 'cta_id_redon', 'cta_id_rec','cta_id_desc','oficina', 'uso_mm', 'texto_id'],
				'default',
				'value' => 0,
				'on' => ['insert', 'update']
				];
		/**
		 * FIN VALORES POR DEFECTO
		 */

    	/**
    	 * EXISTENCIA EN LA BD
    	 */
    	$ret[]= [
    			'oficina',
    			'existeOficina',
    			'when' => function($model){return $model->oficina > 0;},
    			'on' => ['insert', 'update']
    			];

    	$ret[]= [
    			'nombre',
    			'existeTributoPorNombre',
    			'on' => ['insert', 'update']
    			];
    	/**
    	 * FIN EXISTENCIA EN LA BD
    	 */
        return $ret;
    }

	public function scenarios(){

		return [
			'insert' => ['bol_domi', 'bol_domimuni', 'bol_mail', 'bol_tel', 'calc_rec', 'calc_rec_tasa', 'compensa', 'cta_id_rec', 'cta_id_redon','cta_id_desc', 'dj_tribprinc', 'genestcta', 'inscrip_auto',
						'inscrip_incomp', 'inscrip_req', 'nombre', 'nombre_redu', 'nombre_reduhbank', 'oficina', 'prescrip', 'quitafaci', 'rec_venc2', 'texto_id', 'tipo', 'tobj', 'ucm',
						'uso_subcta', 'uso_mm', 'cta_nom_rec', 'cta_nom_redon', 'cta_nom_desc', 'cta_id_act', 'cta_nom_act', 'calc_act', 'calc_act_faci'],
			'update' => ['bol_domi', 'bol_domimuni', 'bol_mail', 'bol_tel', 'calc_rec', 'calc_rec_tasa', 'compensa', 'cta_id_rec', 'cta_id_redon','cta_id_desc', 'dj_tribprinc', 'genestcta', 'inscrip_auto',
						'inscrip_incomp', 'inscrip_req', 'nombre', 'nombre_redu', 'nombre_reduhbank', 'oficina', 'prescrip', 'quitafaci', 'rec_venc2', 'texto_id', 'tipo', 'tobj', 'ucm',
						'uso_subcta', 'uso_mm', 'trib_id', 'cta_nom_rec', 'cta_nom_redon', 'cta_nom_desc', 'cta_id_act', 'cta_nom_act', 'calc_act', 'calc_act_faci'],
			'delete' => ['trib_id'],
			'activar' => ['trib_id']
		];
	}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'trib_id' => 'Código del tributo',
			'bol_domi' => 'Domicilio',
			'bol_domimuni' => 'Usar domicilio de la municipalidad',
			'bol_mail' => 'Correo',
			'bol_tel' => 'Teléfono',
			'calc_rec' => 'Cálcular intereses',
			'calc_rec_tasa' => 'Tasa de recargo',
			'compensa' => 'Permite compensar',
			'cta_id_rec' => 'Código de cuenta de recargo',
			'cta_id_redon' => 'Código de cuenta de redondeo',
			'cta_id_desc' => 'Código de cuenta de descuento',
			'fj_tribprinc' => 'Tributo principal',
			'genestcta' => 'Genera estado en cuenta corriente',
			'inscrip_auto' => 'Inscripción automática',
			'inscrip_incomp' => 'Inscripción incompatible',
			'inscrip_req' => 'Inscripción requerida',
			'nombre' => 'Nombre',
			'nombre_redu' => 'Nombre reducido',
			'nombre_reduhbank' => 'Nombre reducido del home banking',
			'oficina' => 'Oficina',
			'prescrip' => 'Años de prescripción',
			'quitafaci' => 'Porcentaje de quita de facilidad',
			'rec_venc2' => 'Recargo del segundo vencimiento',
			'texto_id' => 'Texto',
			'tipo' => 'Tipo de tributo',
			'tobj' => 'Tipo de objeto',
			'ucm' => 'Valor de UCM',
			'uso_subcta' => 'Usa sub cuenta',
			'uso_mm' => 'Usa módulos municipales',
			'cta_nom_rec' => 'Nombre de la cuenta de recargo',
			'cta_nom_redon' => 'Nombre de la cuenta de redondeo',
			'cta_nom_desc' => 'Nombre de la cuenta de descuento'
        ];
    }

    public function afterFind(){

    	$this->setTipoTributo($this->tipo);

		switch($this->est){

			case 'A': $this->est_nom= 'Activo'; break;
			case 'B': $this->est_nom= 'Baja'; break;
			default: $this->est_nom= '';
		}

		$this->quitafaci = $this->quitafaci * 100;
    }

    /**
     * Funcion que crea o modifica un tributo en la base de datos
     *
     * @return boolean - true si se ha creado correctamente, false de lo contrario
     */
    public function grabar() {

    	$scenario= $this->isNewRecord ? 'insert' : 'update';
    	$this->setScenario($scenario);

    	if(!$this->validate()) return false;

    	$trans= Yii::$app->db->beginTransaction();
    	$sql= "";

		$this->quitafaci = $this->quitafaci / 100;

    	if($this->isNewRecord){

    		//proximo codigo
    		$sql= "Select Coalesce(Max(trib_id), 0) + 1 From trib";
    		$this->trib_id= Yii::$app->db->createCommand($sql)->queryScalar();

    		$sql= "Insert Into trib(trib_id, nombre, nombre_redu, nombre_reduhbank, tobj, tipo, genestcta, texto_id, ucm, calc_rec, calc_rec_tasa, cta_id_rec, rec_venc2, cta_id_redon, prescrip" .
    				", uso_subcta, uso_mm, oficina, bol_domimuni, bol_domi, bol_tel, bol_mail, quitafaci, compensa, est, dj_tribprinc, inscrip_req, inscrip_auto, inscrip_incomp,
					cta_id_act, calc_act, calc_act_faci, fchmod, usrmod, cta_id_desc)" .
    				" Values($this->trib_id, '$this->nombre', '$this->nombre_redu', '$this->nombre_reduhbank', $this->tobj, $this->tipo, $this->genestcta, $this->texto_id, $this->ucm" .
    				", $this->calc_rec, $this->calc_rec_tasa, $this->cta_id_rec," . intVal($this->rec_venc2) . ", $this->cta_id_redon, $this->prescrip, $this->uso_subcta, $this->uso_mm, $this->oficina" .
    				", $this->bol_domimuni::boolean, '$this->bol_domi', '$this->bol_tel', '$this->bol_mail', $this->quitafaci, $this->compensa, 'A', $this->dj_tribprinc, $this->inscrip_req" .
    				", $this->inscrip_auto, $this->inscrip_incomp, $this->cta_id_act, $this->calc_act, $this->calc_act_faci, current_timestamp, " . Yii::$app->user->id . "," . $this->cta_id_desc . ")";

    	} else {

    		$sql= "Update trib Set nombre = '$this->nombre', nombre_redu = '$this->nombre_redu', nombre_reduhbank = '$this->nombre_reduhbank', tobj = $this->tobj, tipo = $this->tipo, genestcta = $this->genestcta" .
    				", texto_id = $this->texto_id, ucm = $this->ucm, calc_rec = $this->calc_rec, calc_rec_tasa = $this->calc_rec_tasa, cta_id_rec = $this->cta_id_rec" .
    				", rec_venc2 = $this->rec_venc2, cta_id_redon = $this->cta_id_redon, prescrip = $this->prescrip, uso_subcta = $this->uso_subcta, uso_mm = $this->uso_mm, oficina = $this->oficina, bol_domimuni = $this->bol_domimuni::boolean" .
    				", bol_domi = '$this->bol_domi', bol_tel = '$this->bol_tel', bol_mail = '$this->bol_mail', quitafaci = $this->quitafaci, compensa = $this->compensa, dj_tribprinc = $this->dj_tribprinc, inscrip_req = $this->inscrip_req" .
    				", inscrip_auto = $this->inscrip_auto, inscrip_incomp = $this->inscrip_incomp, fchmod = current_timestamp, usrmod = " . Yii::$app->user->id .
					", cta_id_act = " . intVal($this->cta_id_act) . ", calc_act=$this->calc_act, calc_act_faci = $this->calc_act_faci" . 
					", cta_id_desc=" . $this->cta_id_desc . " Where trib_id = $this->trib_id";
    	}

    	$res= Yii::$app->db->createCommand($sql)->execute() > 0;

    	if(!$res){
    		$this->addError($this->trib_id, 'Ocurrió un error al intentar realizar la acción');
    		$trnas->rollBack();
    		return false;
    	}

    	$trans->commit();
    	return true;
    }



    /**
     * Baja logica del tributo
     *
     * @return boolean - true si se ha dado de baja correctamente, false de lo contrario
     */
    public function borrar() {

    	try{

    	$sql= "Update trib Set est = 'B' Where trib_id = $this->trib_id";
    	Yii::$app->db->createCommand($sql)->execute() > 0;

    	} catch(\Exception $e){

    		$this->addError($this->trib_id, 'Ocurrió un error al intentar realizar la operación');
    		return false;
    	}

    	return true;
    }

    public function activar() {

    	try{

    	$sql= "Update trib Set est = 'A' Where trib_id = $this->trib_id";
    	Yii::$app->db->createCommand($sql)->execute() > 0;

    	} catch(\Exception $e){

    		$this->addError($this->trib_id, 'Ocurrió un error al intentar realizar la operación');
    		return false;
    	}

    	return true;
    }

    /**
     * Recupera los codigos, nombres reducidos y estados de los tributos existentes
     *
     * @return Array - Cada elemento es un arreglo de la forma ["trib_id" => integer, "nombre_redu" => string, "est" => char]
     */
    public function tributos($nombre= '', $tipoTributo= -1, $tipoObjeto= 0){

    	$sql = "Select trib_id, nombre_redu, est, Case When tipo = 0 Then true Else false End As interno From trib";
    	$condicion = '';

    	if($nombre != '') $condicion .= " upper(nombre) Like upper('%$nombre%')";

    	if($tipoTributo > -1){
    		$c= "tipo = $tipoTributo";
    		$condicion .= $condicion == '' ? $c : " And $c";
    	}

    	if($tipoObjeto > 0){
    		$c= "tobj = $tipoObjeto";
    		$condicion .= $condicion == '' ? $c : " And $c";
    	}



    	if($condicion != '') $sql .= " Where $condicion";

    	$sql .= " Order By nombre_redu";


    	return Yii::$app->db->createCommand($sql)->queryAll();
    }

    /**
     * Recupera los items que estan asociados al tributo
     *
     * @return Array - Cada elemento es un arreglo de la forma ["item_id" => integer, "nombre" => string]
     */
    public function items(){

    	$sql= "Select item_id, nombre From item Where trib_id = $this->trib_id";
    	return Yii::$app->db->createCommand($sql)->queryAll();
    }

    /**
     * Recupera los vencimientos asociados del tributo+
     *
     * @return Array - Cada elemento es un arreglo de la forma ["cuota" => integer, "fchvenc1" => date, "fchvenc2" => date]
     */
    public function vencimientos(){

    	$sql= "Select cuota, fchvenc1, fchvenc2 From trib_venc Where trib_id = $this->trib_id And anio = " . date('Y') . " Order by fchvenc2 ASC";
    	return Yii::$app->db->createCommand($sql)->queryAll();
    }

    /**
     * Determina si la oficina existe en la base de datos
     */
    public function existeOficina(){

    	$sql= "Select Exists (Select 1 From sam.muni_oficina Where ofi_id = $this->oficina)";
    	$res = Yii::$app->db->createCommand($sql)->queryScalar();

    	if(!$res) $this->addError($this->oficina, 'La oficina no existe');
    }

    /**
     * Determina si existe el tributo en la base de datos almacenado en el atributo pasado en $attr
     *
     * @param string $attr - Atributo que tiene almacenado el código de tributo a buscar
     */
    public function existeTributo($attr){

    	$sql= "Select Exists (Select 1 From trib Where trib_id = $this->dj_tribprinc)";
    	$res= Yii::$app->db->createCommand($sql)->queryScalar();

    	if(!$res) $this->addError($this->$attr, 'El tributo ' . $this->attributeLabels()[$attr] . ' no existe');

    }


    /**
     * Determina si un tributo existe a partir del nombre
     *
     */
    public function existeTributoPorNombre(){

    	$sql= "Select Exists (Select 1 From trib Where upper(nombre) = upper('$this->nombre')";

    	if(!$this->isNewRecord) $sql .= " And trib_id <> $this->trib_id";

    	$sql .= ")";

    	$res= Yii::$app->db->createCommand($sql)->queryScalar();

    	if($res) $this->addError($this->nombre, 'Ya existe un tributo con el mismo nombre');
    }

    /**
     * Realiza la acciones de cambio de tipo de tributo
     *
     * @param int $nuevotipo - Codigo del nuevo tipo del tributo
     */
    public function setTipoTributo($nuevoTipo){

    	$this->tipo= $nuevoTipo;
    	$this->tipo_descripcion= Trib::descripcionTipoTributo($this->tipo);

    	$this->esSellado= false;
    	$this->esBoleto= false;
    	$this->esEmision= false;
    	$this->esPeriodico= false;
    	$this->esDeclarativo= false;

    	switch($this->tipo){

    		case self::TIPO_INTERNO:
    			$this->esInterno= true;
    			break;

    		case self::TIPO_SELLADO:
    			$this->esSellado= true;
    			break;

    		case self::TIPO_BOLETO:
    			$this->esBoleto= true;
    			break;

    		case self::TIPO_EMISION:
    			$this->esEmision= true;
    			break;

    		case self::TIPO_PERIODICO:
    			$this->esPeriodico= true;
    			break;

    		case self::TIPO_DECLARATIVO:
    			$this->esDeclarativo= true;
    			break;

    		case self::TIPO_EVENTUAL:
    		case self::TIPO_ITEM_POR_OBJETO:
    			return true;

    		default:
    			$this->addError($this->tipo, 'Seleccione un tipo de tributo');
    			return false;
    	}

    	return true;
    }

    /**
     * Determina si un campo es requerido a partir del tipo de tributo del objeto
     *
     * @param string $atributo = '' - Atributo a determinar si es requerido
     * @param boolean $opcional
     *
     * @return boolean - true si el parametro es requerido, false de lo contrario
     */
    public function esRequerido($atributo = '', $opcional = true){

    	switch($atributo){

    		case 'tobj': return !$this->esSellado && !$this->esBoleto && $this->tipo > 0;
    		case 'nombre_reduhbank': return $this->esEmision && $this->tipo > 0;
    		case 'texto_id': return !$this->esSellado && !$this->esBoleto && $this->tipo > 0;
    		case 'dj_tribprinc': return $this->esDeclarativo && $this->tipo > 0;
    		case 'quitafaci': return !$this->esSellado && !$this->esBoleto && $this->tipo > 0;
    		case 'calc_rec': return !$this->esSellado && !$this->esBoleto;
    		case 'genestcta': return !$this->esEmision && !$this->esPeriodico && $this->tipo > 0;
    		case 'compensa': return !$this->esSellado && !$this->esBoleto && $this->tipo > 0;
    		case 'uso_subcta': return !$this->esSellado && !$this->esBoleto && $this->tipo > 0;
    		case 'inscrip_req': return !$this->esDeclarativo && !$this->esEmision && $this->tipo > 0;
    		case 'inscrip_auto': return $this->inscrip_req == 1 && $this->tipo > 0;

    		case 'bol_domi':
    		case 'bol_tel':
    		case 'bol_mail':
    			return $this->bol_domimuni == 0 && $this->tipo > 0;

    		case 'calc_rec_tasa':
    		case 'cta_id_rec':
    			return !$this->esSellado && !$this->esBoleto && $this->tipo > 0 && $opcional;

    		case 'rec_venc2':
    			return !$this->esSellado && !$this->esBoleto && $this->tipo > 0;

    		case 'oficina': return $this->oficina > 0;
    	}

    	return false;
    }

    /**
    * determina si el tributo usa subcuenta
    *
    * @param int $trib_id Código del tributo.
    *
    * @return bool Si usa subcuenta. En caso de que el tributo no exista, se retorna false por defecto.
    */
    public static function usaSubCta($trib_id){

    	$model= Trib::findOne(['trib_id' => $trib_id]);

    	return $model == null ? false : $model->uso_subcta;
    }

    /**
     * Obtiene la descripcion del tipo de tributo
     *
     * @param int = 0 - Codigo del tipo de tributo
     *
     * @return string - Descripcion del tipo de tributo
     */
    public static function descripcionTipoTributo($tipo= 0){

    	if($tipo == 0) return null;

    	//se obtiene la descripcion del tipo de tributo
    	$sql= "Select detalle from trib_tipo Where cod = $tipo";
    	return Yii::$app->db->createCommand($sql)->queryScalar();
    }

    public function Imprimir($id,&$sub1,&$sub2)
    {
    	$sql = 'select * from v_trib where trib_id='.$id;
    	$datos = Yii::$app->db->createCommand($sql)->queryAll();

    	$sql= "Select item_id, nombre From item Where trib_id = ".$id;
    	$sub1 = Yii::$app->db->createCommand($sql)->queryAll();

    	$sql= "Select cuota, to_char(fchvenc1,'dd/mm/yyyy') fchvenc1, to_char(fchvenc2,'dd/mm/yyyy') fchvenc2 From trib_venc Where trib_id = $id And anio = " . date('Y') . " Order by fchvenc2 ASC";
    	$sub2 = Yii::$app->db->createCommand($sql)->queryAll();

    	return $datos;
    }
}
