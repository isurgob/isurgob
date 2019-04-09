<?php

namespace app\models\ctacte;

use Yii;

use yii\data\ArrayDataProvider;
use yii\db\Query;

use app\utils\helpers\DBException;
use app\utils\db\Fecha;
use app\utils\db\utb;
/**
 * @property string $obj_id
 */
class Tribacc extends \yii\base\Model
{
	const TIPO_ASIGNACION = 'asig';
	const TIPO_EXCEPCION = 'excep';
	const TIPO_INSCRIPCION = 'inscrip';
	const TIPO_CONDONACION = 'condona';
	const TIPO_PRESCRIPCION = 'prescrip';
	const TIPO_DJ_FALTANTE = 'djfalt';

	//tipo de accion de tributo. debe tomar los valores de las constantes
	public $tipoAcc;
	private $nuevo;

	//asignacion
	public $obj_id;
	public $item_id;
	public $perdesde;
	public $perhasta;
	public $orden;
	public $subcta;
	public $perd;
	public $param1;
	public $param2;
	public $expe;
	public $obs;
	public $exen_id;
	public $fchmod;
	public $usrmod;
	public $trib_id;

	//excepcion
	public $fchusar;
	public $fchlimite;
	public $ctacte_id;
	public $anio;
	public $cuota;
	public $poranio;
	public $excep_id;
	public $tipo_id;
	public $pornum;
	public $alvenc;
	public $cantResulExcep;

	//inscripcion
	public $cat;
	public $fchalta;
	public $base;
	public $cant;
	public $est;
	public $sup;

	//condona
	public $adesde;
	public $cdesde;
	public $ahasta;
	public $chasta;

	//prescripcion y dj faltantes
	public $tobj;
	public $accion;
	public $todos;



	public function __construct($tipoAcc){

		parent::__construct();

		$this->tipoAcc = $tipoAcc;
		$this->nuevo = true;
		$this->trib_id= 0;
	}

    /**
     * @inheritdoc
     */
    public function rules()
    {
    	/**
    	 * CAMPOS REQUERIDOS
    	 */
		$ret[] = [
    			'trib_id',
    			'required',
    			'on' => ['asig', 'insertasig', 'updateasig', 'deleteasig',
    					'excep', 'insertexcep', 'updateexcep',
    					'inscrip', 'insertinscrip', 'updateinscrip', 'deleteinscrip',
    					'condona', 'insertcondona', 'deletecondona',
    					'insertprescrip', 'deleteprescrip',
    					'insertdjfalt', 'deletedjfalt'
    					],
    			'message' => 'Seleccione un tributo'
    			];

    	$ret[] = [
    			['adesde', 'cdesde'],
    			'required',
    			'on' => ['asig', 'insertasig', 'updateasig', 'deleteasig',
    					'inscrip', 'insertinscrip', 'updateinscrip', 'deleteinscrip',
    					'condona', 'insertcondona', 'deletecondona',
    					'insertprescrip', 'deleteprescrip',
    					'insertdjfalt', 'deletedjfalt'
    					]
    			];

    	$ret[] = [
    			['ahasta', 'chasta'],
    			'required',
    			'on' => ['deletecondona']
    			];

    	$ret[] = [
    			['anio'],
    			'required',
    			'on' => ['excep', 'insertexcep', 'updateexcep']
    			];

    	$ret[] = [
    			['cuota'],
    			'required',
    			'when' => function($model){

    				return intval($model->poranio) === 0 && intval($model->pornum) === 0;
    			},
    			'on' => ['excep', 'insertexcep', 'updateexcep']
    			];

    	$ret[] = [
    			'tipo_id',
    			'required',
    			'on' => ['excep', 'insertexcep', 'updateexcep'],
    			'message' => 'Elija un tipo'
    			];

    	$ret[] = [
    			'fchlimite',
    			'required',
    			'on' => ['insertexcep']
    			];

    	$ret[] = [
    			'fchlimite',
    			'required',
    			'on' => ['updateexcep']
    			];

    	$ret[] = [
    			'fchusar',
    			'required',
    			'when' => function($model){

    				return intval($model->poranio) === 0 && (intval($model->pornum) === 0 || intval($model->cuota) > 0) && $model->alvenc == 0;
    			},
    			'on' => ['excep', 'insertexcep']
    			];

    	$ret[] = [
    			'fchusar',
    			'required',
    			'when' => function($model){
    				return intval($model->tipo_id) === 5 && $model->alvenc == 0;
    			},
    			'on' => ['updateexcep']
    			];

    	$ret[] = [
    			'accion',
    			'required',
    			'on' => ['insertprescrip', 'deleteprescrip', 'insertdjfalt', 'deletedjfalt'],
    			'message' => 'Seleccione una acción'
    			];

    	$ret[] = [
    			'obj_id',
    			'required',
    			'on' => ['asig', 'insertasig', 'updateasig', 'deleteasig',
    					'inscrip', 'insertinscrip', 'updateinscrip', 'deleteinscrip',
    					'condona', 'insertcondona', 'deletecondona'
    					],
    			'message' => 'Seleccione un objeto'
    			];

		$ret[] = [
				'obj_id',
				'required',
				'when' => function($model){
					return intval($model->todos) === 0;
				},
				'on' => ['insertprescrip', 'deleteprescrip', 'insertdjfalt', 'deletedjfalt']
				];

    	$ret[] = [
    			'item_id',
    			'required',
    			'on' => ['asig', 'insertasig', 'updateasig', 'deleteasig'],
    			'message' => 'Seleccione un ítem'
    			];

    	$ret[] = [
    			'excep_id',
    			'required',
    			'on' => ['excep', 'updateexcep', 'deletexcep']
    			];

		/**
		 * FIN CAMPOS REQUERIDOS
		 */

		/**
		 * TIPO Y RANGO DE DATOS
		 */
		//claves foraneas
    	$ret[] = [
    			['item_id', 'trib_id', 'tipo_id'],
    			'integer',
    			'min' => 1,
    			'on' => ['asig', 'insertasig', 'updateasig', 'deleteasig',
    					'excep', 'insertexcep', 'updateexcep',
    					'inscrip', 'insertinscrip', 'updateinscrip', 'deleteinscrip',
    					'condona', 'insertcondona', 'deletecondona',
    					'insertprescrip', 'deleteprescrip',
    					'insertdjfalt', 'deletedjfalt'
    					],
    			'message' => 'Selecciones un {attribute}'
    			];

    	$ret[] = [
    			['exen_id', 'orden'],
    			'integer',
    			'min' => 0,
    			'on' => ['asig', 'insertasig', 'updateasig', 'deleteasig',]
    			];

    	$ret[] = [
    			['cat'],
    			'string',
    			'min' => 0,
    			'on' => ['inscrip', 'insertinscrip', 'updateinscrip', 'deleteinscrip',
    					'condona', 'insertcondona'
    					]
    			];

		$ret[] = [
    			['cant'],
    			'integer',
    			'min' => 0,
    			'on' => ['inscrip', 'insertinscrip', 'updateinscrip', 'deleteinscrip',
    					'condona', 'insertcondona'
    					]
    			];

    	$ret[] = [
    			'subcta',
    			'integer',
    			'min' => 0,
    			'on' => ['asig', 'insertasig', 'updateasig', 'deleteasig',
    					'excep', 'insertexcep', 'updateexcep',
    					'condona', 'insertcondona', 'deletecondona',
    					'insertprescrip', 'deleteprescrip',
    					'insertdjfalt', 'deletedjfalt'
    					]
    			];

    	$ret[] = [
    			['adesde', 'ahasta'],
    			'integer',
    			'min' => 0,
    			'max' => 9999,
    			'on' => ['asig', 'insertasig', 'updateasig', 'deleteasig',
    					'excep', 'insertexcep', 'updateexcep',
    					'inscrip', 'insertinscrip', 'updateinscrip', 'deleteinscrip',
    					'condona', 'insertcondona', 'deletecondona',
    					'insertprescrip', 'deleteprescrip',
    					'insertdjfalt', 'deletedjfalt'
    					]
    			];

    	$ret[] = [
    			['cdesde', 'chasta', 'cuota'],
    			'integer',
    			'min' => 0,
    			'max' => 999,
    			'on' => ['asig', 'insertasig', 'updateasig', 'deleteasig',
    					'excep', 'insertexcep', 'updateexcep',
    					'inscrip', 'insertinscrip', 'updateinscrip', 'deleteinscrip',
    					'condona', 'insertcondona', 'deletecondona',
    					'insertprescrip', 'deleteprescrip',
    					'insertdjfalt', 'deletedjfalt'
    					]
    			];

    	$ret[] = [
    			['obj_id'],
    			'string',
    			'min' => 8,
    			'max' => 8,
    			'on' => ['asig', 'insertasig', 'updateasig', 'deleteasig',
    					'excep', 'insertexcep', 'updateexcep',
    					'inscrip', 'insertinscrip', 'updateinscrip', 'deleteinscrip',
    					'condona', 'insertcondona', 'deletecondona',
    					'insertprescrip', 'deleteprescrip',
    					'insertdjfalt', 'deletedjfalt'
    					]
    			];

    	$ret[] = [
    			['param1', 'param2'],
    			'number',
    			'min' => 0,
    			'max' => 999999,
    			'on' => ['asig', 'insertasig']
    			];

    	$ret[] = [
    			'expe',
    			'string',
    			'max' => 12,
    			'on' => ['asig', 'insertasig', 'updateasig', 'deleteasig',
    					'excep', 'insertexcep', 'updateexcep', 'deleteexcep',
    					'inscrip', 'insertinscrip', 'updateinscrip', 'deleteincrip',
    					'condona', 'insertcondona', 'deletecondona',
    					'insertprescrip',
    					'insertdjfalt', 'deletedjfalt'
    					]
    			];

    	$ret[] = [
    			'obs',
    			'string',
    			'max' => 250,
    			'on' => ['asig', 'insertasig', 'updateasig', 'deleteasig',
    					'excep', 'insertexcep', 'updateexcep', 'deleteexcep',
    					'inscrip', 'insertinscrip', 'updateinscrip', 'deleteinscrip',
    					'condona', 'insertcondona', 'deletecondona',
    					'insertprescrip',
    					'insertdjfalt', 'deletedjfalt'
    					]
    			];

    	$ret[] = [
    			['base', 'sup'],
    			'number',
    			'min' => 0,
    			'max' => 999999999999,
    			'on' => ['inscrip', 'insertinscrip', 'updateinscrip', 'deleteinscrip'
    					]
    			];

    	$ret[] = [
    			['fchalta', 'fchusar', 'fchlimite'],
    			'date',
    			'format' => 'php:Y/m/d',
    			'on' => ['inscrip', 'insertinscrip', 'updateinscrip', 'deleteinscrip']
    			];

		$ret[] = [
    			['fchalta', 'fchusar', 'fchlimite'],
    			'safe',
    			'on' => ['excep','insertexcep', 'updateexcep']
    			];

    	$ret[] = [
    			['poranio', 'pornum', 'todos','alvenc'],
    			'integer',
    			'min' => 0,
    			'max' => 1,
    			'on' => ['excep', 'insertexcep', 'updateexcep',
    					'insertprescrip',
    					'insertdjfalt', 'deletedjfalt'
    					]
    			];

    	$ret[] = [
    			'accion',
    			'integer',
    			'min' => 1,
    			'max' => 2,
    			'on' => ['insertprescrip']
    			];

    	$ret[] = [
    			'accion',
    			'integer',
    			'min' => 3,
    			'max' => 4,
    			'on' => ['insertdjfalt', 'deletedjfalt']
    			];
		/**
		 * FIN TIPO Y RANGO DE DATOS
		 */

		/**
		 * VALORES POR DEFECTO
		 */
		$ret[] = [
				'ahasta',
				'default',
				'value' => 9999,
				'on' => ['asig', 'insertasig', 'updateasig',
						'inscrip', 'insertinscrip', 'updateinscrip',
						'condona', 'insertcondona',
						'insertprescrip', 'deleteprescrip',
						'insertdjfalt', 'deletedjfalt'
						]
				];

		$ret[] = [
				'chasta',
				'default',
				'value' => 999,
				'on' => ['asig', 'insertasig', 'updateasig',
						'inscrip', 'insertinscrip', 'updateinscrip',
						'condona', 'insertcondona',
						'insertprescrip', 'deleteprescrip',
						'insertdjfalt', 'deletedjfalt'
						]
				];

		$ret[] = [
				['param1', 'param2'],
				'default',
				'value' => 0,
				'on' => ['asig', 'insertasig', 'updateasig']
				];

		$ret[] = [
				['cat', 'cant'],
				'default',
				'value' => 0,
				'on' => ['inscrip', 'insertinscrip', 'updateinscrip', 'deleteinscrip'
						]
				];

		$ret[] = [
				'fchalta',
				'default',
				'value' => '',
				'on' => ['inscrip', 'insertinscrip', 'updateinscrip', 'deleteinscrip'
						]
				];

		$ret[] = [
				'expe',
				'default',
				'value' => '',
				'on' => [
						'asig', 'insertasig', 'updateasig', 'deleteasig',
						'excep', 'insertexcep', 'updateexcep', 'deleteexcep',
						'inscrip', 'insertinscrip', 'updateinscrip', 'deleteinscrip',
						'condona', 'insertcondona', 'deletepcondona,',
						'insertprescrip',
						'insertdjfalt', 'deletedjfalt'
						]
				];

		$ret[] = [
				'obs',
				'default',
				'value' => '',
				'on' => [
						'asig', 'insertasig', 'updateasig', 'deleteasig',
						'excep', 'insertexcep', 'updateexcep', 'deleteexcep',
						'inscrip', 'insertinscrip', 'updateinscrip', 'deleteinscrip',
						'condona', 'insertcondona', 'deletecondona',
						'insertprescrip',
						'insertdjfalt', 'deletedjfalt'
						]
				];

		//obj_id es vacio por defecto cuando se selecciona todos los objetos
		$ret[] = [
				'obj_id',
				'default',
				'value' => '',
				'when' => function($model){
					return intval($model->todos) === 1;
				},
				'on' => ['insertprescrip', 'deleteprescrip', 'insertdjfalt', 'deletedjfalt']
				];

		$ret[] = [
				['exen_id', 'orden'],
				'default',
				'value' => 0,
				'on' => ['asig', 'insertasig', 'updateasig', 'deleteasig']
				];

		$ret[] = [
				['base', 'sup'],
				'default',
				'value' => 0,
				'on' => ['inscrip', 'insertinscrip', 'updateinscrip'
						]
				];

		$ret[] = [
				'subcta',
				'default',
				'value' => 0,
				'when' => function(){
					return !$this->usaSubcta();
				},
				'on' => [
						'insertasig', 'updateasig',
						'insertexcep', 'updateexcep',
						'insertinscrip', 'updateinscrip',
						'insertcondona',
						'insertprescrip', 'deleteprescrip',
						'insertdjfalt', 'deletedjfalt'
						]
				];

		$ret[] = [
				['poranio', 'pornum','alvenc'],
				'default',
				'value' => 0,
				'on' => ['excep', 'insertexcep', 'updateexcep']
				];

		$ret[] = [
				'fchusar',
				'default',
				'value' => null,
				'on' => ['excep', 'insertexcep', 'updateexcep']
				];

		$ret[] = [
				'cuota',
				'default',
				'value' => 0,
				'when' => function($model){
					return intval($model->poranio) === 0 && intval($model->pornum) === 1;
				},
				'on' => ['excep', 'insertexcep', 'updateexcep']
				];

		$ret[] = [
				'todos',
				'default',
				'value' => 0,
				'on' => ['insertprescrip', 'deleteprescrip', 'insertdjfalt', 'deletedjfalt']
				];
		/**
		 * FIN VALORES POR DEFECTO
		 */
		$ret[] = [
    			['subcta'],
				'required',
				'when' => function($model){

					return $model->usaSubcta();
				},
				'on' => ['insertasig', 'updateasig', 'deleteasig',
						'insertexcep', 'updateexcep',
						'insertcondona', 'deletecondona',
						'insertprescrip', 'deleteprescript',
						'insertdjfalt', 'deletedjfalt'
						]
    			];

		$ret[] = [
				'adesde',
				function($attr){
					if($this->$attr > $this->ahasta)
						$this->addError($this->$attr, 'Rango de período inválido');
				},
				'when' => function($model){ return $model->ahasta !== null;},
				'on' => ['insertasig', 'updateasig', 'insertinscrip', 'updateinscrip', 'insertcondona', 'insertprescrip', 'deleteprescrip', 'insertdjfalt', 'deletedjfalt']
				];

		$ret[] = [
				'cdesde',
				function($attr){

					if($this->$attr > $this->chasta && $this->adesde === $this->ahasta)
						$this->addError($this->$attr, 'Rango de período inválido');
				},
				'skipOnError' => true,
				'when' => function($model){return $model->cdesde !== null;},
				'on' => ['insertasig', 'updateasig', 'insertinscrip', 'updateinscrip', 'insertcondona', 'insertprescrip', 'deleteprescrip', 'insertdjfalt', 'deletedjfalt']
				];

		$ret[] = [
				'obj_id',
				'objetoExiste',
				'on' => [
						'insertasig', 'updateasig',
						'insertexcep', 'updateexcep',
						'insertinscrip', 'updateinscrip',
						'insertcondona'
						]
				];

		$ret[] = [
				'obj_id',
				'objetoExiste',
				'when' => function($model){return intval($model->todos) == 0;},
				'on' => [
						'insertprescip', 'deleteprescrip',
						'insertdjfalt', 'deletedjfalt'
						]
				];
        return $ret;
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
        	'asig' => ['obj_id', 'item_id', 'subcta', 'orden', 'trib_id', 'perdesde', 'perhasta', 'param1', 'param2', 'expe', 'obs', 'exen_id', 'fchmod', 'usrmod', 'adesde', 'cdesde', 'ahasta', 'chasta'],
        	'excep' => ['excep_id', 'trib_id', 'obj_id', 'ctacte_id', 'tipo_id', 'anio', 'cuota', 'fchusar', 'fchlimite', 'expe', 'obs', 'poranio', 'pornum','alvenc', 'subcta'],
        	'inscrip' => ['obj_id', 'trib_id', 'perdesde', 'perhasta', 'cat', 'fchalta', 'expe', 'base', 'cant', 'sup', 'obs', 'fchmod', 'usrmod', 'adesde', 'cdesde', 'ahasta', 'chasta', 'orden'],
        	'condona' => ['obj_id', 'trib_id', 'perdesde', 'perhasta', 'cat', 'fchalta', 'expe', 'base', 'cant', 'sup', 'est', 'fchmod', 'usrmod', 'adesde', 'cdesde', 'ahasta', 'chasta', 'subcta'],
			'prescrip' => ['obj_id', 'subcta', 'trib_id', 'perdesde', 'perhasta', 'fchmod', 'usrmod', 'adesde', 'cdesde', 'ahasta', 'chasta'],

			'insertasig' => ['obj_id', 'item_id', 'subcta', 'orden', 'trib_id', 'perdesde', 'perhasta', 'param1', 'param2', 'expe', 'obs', 'exen_id', 'fchmod', 'usrmod', 'adesde', 'cdesde', 'ahasta', 'chasta'],
        	'insertexcep' => ['excep_id', 'ctacte_id', 'trib_id', 'obj_id', 'subcta', 'anio', 'cuota', 'tipo_id', 'fchusar', 'fchlimite', 'obs', 'fchmod', 'usrmod', 'poranio', 'pornum','alvenc', 'tipo_id', 'expe'],
        	'insertinscrip' => ['obj_id', 'trib_id', 'perdesde', 'perhasta', 'cat', 'fchalta', 'expe', 'base', 'cant', 'sup', 'obs', 'fchmod', 'usrmod', 'adesde', 'cdesde', 'ahasta', 'chasta'],
        	'insertcondona' => ['obj_id', 'trib_id', 'expe', 'obs', 'adesde', 'cdesde', 'ahasta', 'chasta', 'subcta'],
        	'insertprescrip' => ['trib_id', 'obj_id', 'subcta', 'adesde', 'cdesde', 'ahasta', 'chasta', 'expe', 'obs', 'accion', 'todos'],
        	'insertdjfalt' => ['trib_id', 'obj_id', 'subcta', 'adesde', 'cdesde', 'ahasta', 'chasta', 'expe', 'obs', 'accion', 'todos'],

        	'updateasig' => ['obj_id', 'item_id', 'subcta', 'orden', 'trib_id', 'perdesde', 'perhasta', 'param1', 'param2', 'expe', 'obs', 'exen_id', 'fchmod', 'usrmod', 'adesde', 'cdesde', 'ahasta', 'chasta'],
        	'updateexcep' => ['excep_id', 'ctacte_id', 'trib_id', 'obj_id', 'subcta', 'anio', 'cuota', 'tipo_id', 'fchusar', 'fchlimite', 'obs', 'fchmod', 'usrmod', 'poranio', 'pornum','alvenc', 'tipo_id', 'expe'],
        	'updateinscrip' => ['obj_id', 'trib_id', 'perdesde', 'perhasta', 'cat', 'fchalta', 'expe', 'base', 'cant', 'sup', 'obs', 'fchmod', 'usrmod', 'adesde', 'cdesde', 'ahasta', 'chasta'],


        	'deleteasig' => ['obj_id', 'subcta', 'trib_id', 'item_id', 'adesde', 'cdesde', 'orden'],
        	'deleteexcep' => ['excep_id'],
        	'deleteinscrip' => ['trib_id', 'obj_id', 'adesde', 'cdesde', 'subcta', 'ahasta', 'chasta'],
        	'deletecondona' => ['trib_id', 'obj_id', 'subcta', 'cdesde', 'adesde', 'chasta', 'ahasta'],
        	'deleteprescrip' => ['trib_id', 'obj_id', 'subcta', 'adesde', 'cdesde', 'ahasta', 'chasta', 'expe', 'obs', 'accion', 'todos'],
        	'deletedjfalt' => ['trib_id', 'obj_id', 'subcta', 'adesde', 'cdesde', 'ahasta', 'chasta', 'expe', 'obs', 'accion', 'todos']
        ];
    }

    public function attributeLabels(){

    	return [

    		'obj_id' => 'Código de objeto',
    		'cta' => 'Cuenta',
    		'subcta' => 'Cuenta',
    		'adesde' => 'Año desde',
    		'cdesde' => 'Cuota desde',
    		'ahasta' => 'Año hasta',
    		'chasta' => 'Cuota hasta',
    		'param1' => 'Parámetro 1',
    		'param2' => 'Parámetro 2',
    		'expe' => 'Expediente',
    		'obs' => 'Observaciones',
    		'cat' => 'Categoria',
    		'anio' => 'Año',
    		'cuota' => 'Cuota',
    		'tipo_id' => 'Tipo',
    		'fchusar' => 'Fecha usar',
    		'fchlimite' => 'Fecha límite'

    	];
    }

    private function afterFind(){

    	switch($this->tipoAcc){

    		case Tribacc::TIPO_ASIGNACION:
    		case Tribacc::TIPO_INSCRIPCION:
    		case Tribacc::TIPO_CONDONACION:

    			$this->adesde = intval($this->perdesde / 1000);
    			$this->cdesde = $this->perdesde % 1000;

    			$this->ahasta = intval($this->perhasta / 1000);
    			$this->chasta = $this->perhasta % 1000;

    			break;
    	}

    	$this->nuevo = false;
    }

    public function beforeValidate(){

    	switch($this->tipoAcc){

    		case Tribacc::TIPO_INSCRIPCION:

    			if($this->fchalta !== null && trim($this->fchalta) != '') $this->fchalta = Fecha::usuarioToBD($this->fchalta);

    		case Tribacc::TIPO_ASIGNACION:
    		case Tribacc::TIPO_CONDONACION:
    		case Tribacc::TIPO_PRESCRIPCION:
    		case Tribacc::TIPO_DJ_FALTANTE:

    			if($this->adesde !== null && $this->cdesde !== null)
    				$this->perdesde = intval($this->adesde) * 1000 + intval($this->cdesde);
    			else $this->perdesde = null;

    			if(intval($this->ahasta) !== 0 && intval($this->chasta) !== 0)
    				$this->perhasta = intval($this->ahasta) * 1000 + intval($this->chasta);
    			else $this->perhasta = 9999999;

    			break;

    		case Tribacc::TIPO_EXCEPCION:

    			/*if($this->fchusar !== null && trim($this->fchusar) != '') $this->fchusar = Fecha::usuarioToBD($this->fchusar);
    			if($this->fchlimite !== null && trim($this->fchlimite) != '') $this->fchlimite = Fecha::usuarioToBD($this->fchlimite); */
    	}

    	return true;
    }

    public function afterValidate(){

    	if($this->hasErrors()){

    		if($this->fchusar !== null && trim($this->fchusar) != '')
    			$this->fchusar = Fecha::usuarioToDatePicker($this->fchusar);

    		if($this->fchlimite !== null && trim($this->fchlimite) != '')
    			$this->fchlimite = Fecha::usuarioToDatePicker($this->fchlimite);
    	}
    }

    public function setScenario($scenario, $concatenar = true){

    	if($concatenar)
    		parent::setScenario($scenario . $this->tipoAcc);
    	else parent::setScenario($scenario);
    }

    public function grabar(){

    	$this->nuevo ? $this->setScenario('insert') : $this->setScenario('update');

		if(!$this->validate()) return false;

    	switch($this->tipoAcc){

    		case Tribacc::TIPO_ASIGNACION: return $this->grabarAsignacion();
    		case Tribacc::TIPO_EXCEPCION: return $this->grabarExcepcion();
    		case Tribacc::TIPO_INSCRIPCION: return $this->grabarInscripcion();
    		case Tribacc::TIPO_CONDONACION: return $this->grabarCondonacion();
    		case Tribacc::TIPO_PRESCRIPCION: return $this->grabarPrescripcion();
    		case Tribacc::TIPO_DJ_FALTANTE: return $this->grabarDjFaltante();

    	}

    	$this->addError('obj_id', 'tipo no encontrado');
    	return false;
    }

    public function borrar(){

    	$this->setScenario('delete');

		if(!$this->validate()) return false;

    	switch($this->tipoAcc){

    		case Tribacc::TIPO_ASIGNACION: return $this->borrarAsignacion();
    		case Tribacc::TIPO_EXCEPCION: return $this->borrarExcepcion();
    		case Tribacc::TIPO_INSCRIPCION: return $this->borrarInscripcion();
    		case Tribacc::TIPO_CONDONACION: return $this->borrarCondonacion();
    		case Tribacc::TIPO_DJ_FALTANTE: return $this->borrarDjFaltante();

    	}

    	$this->addError('obj_id', 'tipo no encontrado');
    	return false;
    }

    private function grabarAsignacion(){


    	$this->nuevo ? $this->setScenario('insert') : $this->setScenario('update');

    	if(!$this->validate()) return false;


    	if($this->nuevo){

	    	$orden = $this->getOrdenAsignacion($this->obj_id, $this->subcta);

	    	//se verifica que la asignacion no existe
	    	$sql = "Select Exists (Select 1 From objeto_item Where obj_id = '$this->obj_id' And subcta = $this->subcta And orden = $orden And trib_id = $this->trib_id And item_id = $this->item_id And perdesde = $this->perdesde)";
	    	$existe = Yii::$app->db->createCommand($sql)->queryScalar();

	    	if($existe){
	    		$this->addError($this->obj_id, 'La Asignación ya existe');
	    		return false;
	    	}

	    	//se verifica que no exista superposicion de periodos
	    	$sql = "Select Exists(Select 1 From objeto_item Where obj_id = '$this->obj_id' And subcta = $this->subcta And trib_id = $this->trib_id And item_id = $this->item_id And " .
	    			"($this->perdesde Between perdesde And perhasta Or $this->perhasta Between perdesde And perhasta))";

	    	$existe = Yii::$app->db->createCommand($sql)->queryScalar();

	    	if($existe){
	    		$this->addError('adesde', 'Hay superposición de Períodos');
	    		return false;
	    	}

	    	$this->orden = $orden;
	    	//se inserta en la base de datos
	    	$sql = "Insert Into objeto_item(obj_id, subcta, orden, trib_id, item_id, perdesde, perhasta, param1, param2, expe, obs, exen_id, fchmod, usrmod) " .
	    			"Values('$this->obj_id', $this->subcta, $this->orden, $this->trib_id, $this->item_id, $this->perdesde, $this->perhasta, $this->param1, $this->param2," .
	    			"'$this->expe', '$this->obs', $this->exen_id, current_timestamp, " . Yii::$app->user->id . ")";

	    	return Yii::$app->db->createCommand($sql)->execute() > 0;
    	} else{

    		//no existe superposicion de fechas
    		$sql = "Select Exists(Select 1 From objeto_item Where obj_id = '$this->obj_id' And subcta = $this->subcta And trib_id = $this->trib_id And item_id = $this->item_id " .
    				" And ($this->perdesde between perdesde And perhasta Or $this->perhasta between perdesde And perhasta) And perdesde <> $this->perdesde)";

    		$existe = Yii::$app->db->createCommand($sql)->queryScalar();

    		if($existe){
    			$this->addError('adesde', 'Hay superposición de períodos');
    			return false;
    		}


    		$sql = "Update objeto_item Set perhasta = $this->perhasta, param1 = $this->param1, param2 = $this->param2, expe = '$this->expe', obs = '$this->obs', fchmod = current_timestamp, usrmod = " . Yii::$app->user->id;
    		$sql .= " Where obj_id = '$this->obj_id' And subcta = $this->subcta And orden = $this->orden And trib_id = $this->trib_id And item_id = $this->item_id And perdesde = $this->perdesde";

    		return Yii::$app->db->createCommand($sql)->execute() > 0;
    	}

    	return false;
    }

    /**
     * TODO REVISAR QUE ANDE
     */
    private function grabarExcepcion(){


    	if ($this->cuota == null) $this->cuota = 0;

		$trans = Yii::$app->db->beginTransaction();

		if ($this->trib_id == 1 and $this->pornum == 0) $this->obj_id = utb::getCampo("ctacte","anio=$this->anio and cuota=$this->cuota","obj_id");

		if($this->nuevo){

			$sql = "Select sam.uf_ctacte_excep($this->trib_id, $this->pornum,'$this->obj_id',$this->tipo_id,$this->poranio,$this->anio,$this->cuota," .
							"" . ($this->fchusar == null ? 'null' : "'$this->fchusar'") .
							", '$this->fchlimite','$this->expe', '$this->obs')";

			try{
				$this->cantResulExcep = Yii::$app->db->createCommand($sql)->execute();
				$trans->commit();
				Yii::$app->session->setFlash( "msnExcep", "Se grabaron $this->cantResulExcep registros" );

			}catch(\Exception $e){
				$this->addError($this->trib_id, DBException::getMensaje($e));
				$trans->rollBack();
				return false;
			}

			return true;

		//modificar
		} else {

			if($this->tipo_id != 5){
				if($this->trib_id == 1) $this->ctacte_id = $this->getCtacteId($this->trib_id, $this->anio, $this->cuota);
				else $this->ctacte_id = $this->getCtacteId($this->trib_id, $this->anio, $this->cuota, $this->obj_id, $this->subcta);
			}
			else $this->ctacte_id = 0;


			if($this->ctacte_id == 0){

				$sql = "Select Exists( Select 1 From ctacte_excep Where trib_id = $this->trib_id And anio = $this->anio And cuota = $this->cuota And" .
						" obj_id = '$this->obj_id' And subcta = $this->subcta And tipo = $this->tipo_id And excep_id <> $this->excep_id)";
			}
			else {
				$sql = "Select Exists( Select 1 From ctacte_excep Where ctacte_id = $this->ctacte_id And tipo = $this->tipo_id And excep_id <> $this->excep_id)";
			}

			$res = Yii::$app->db->createCommand($sql)->queryScalar();

			if($res){
				$this->addError($this->obj_id, 'Ya existe una excepción de ese tipo para el período');
				$trans->tollBack();
				return false;
			}


			$sql = "Update ctacte_excep Set ctacte_id = $this->ctacte_id, trib_id = $this->trib_id, obj_id = '$this->obj_id'," .
					" subcta = $this->subcta, anio = $this->anio, cuota = $this->cuota, tipo = $this->tipo_id," .
					" fchusar = " . ($this->tipo_id === 5 ? 'null' : "'$this->fchusar'") . "," .
					" fchlimite = '$this->fchlimite', expe = '$this->expe', obs = '$this->obs', fchmod = current_timestamp, usrmod = " . Yii::$app->user->id . "" .
					" Where excep_id = $this->excep_id";

			$res = Yii::$app->db->createCommand($sql)->execute() > 0;

			if(!$res){

				$tans->rollBack();
				$this->addError($this->obj_id, 'Error al intentar modificar los datos');
				return false;
			}
		}

		$trans->commit();
    	return true;
    }

    private function grabarInscripcion(){

		if($this->nuevo){

			//se verifica que el tributo sea unico para el objeto en el periodo
			$sql = "Select Exists(Select 1 From objeto_trib Where obj_id = '$this->obj_id' And trib_id = $this->trib_id And perdesde = $this->perdesde and est='A')";
			$existe = Yii::$app->db->createCommand($sql)->queryScalar();

			if($existe){
				$this->addError($this->trib_id, 'Ya existe la inscripción del tributo en el objeto');
				return false;
			}

			//se comprueba si existe una inscripcion incompatible
			if($this->validarTributoIncompatible($this->obj_id, $this->trib_id, $this->perdesde, $this->perhasta)){
				$this->addError($this->trib_id, 'Existe una inscripción a otro tributo que es incompatible. Modifique para poder grabar');
				return false;
			}

			//si existe pero esta dado de baja, se elimina
			$sql = "Select Exists(Select 1 From objeto_trib Where obj_id = '$this->obj_id' And trib_id = $this->trib_id And perdesde = $this->perdesde and est='B')";
			$existe = Yii::$app->db->createCommand($sql)->queryScalar();

			if($existe){
				$sql = "delete From objeto_trib Where obj_id = '$this->obj_id' And trib_id = $this->trib_id And perdesde = $this->perdesde and est='B'";
				Yii::$app->db->createCommand($sql)->execute();
			}

			$sql = "Insert Into objeto_trib(obj_id, trib_id, perdesde, perhasta, cat, fchalta, expe, obs, base, cant, sup, est, fchmod, usrmod) " .
					"Values('$this->obj_id', $this->trib_id, $this->perdesde, $this->perhasta, '$this->cat', " .
					"" . ($this->fchalta == null || trim($this->fchalta) == '' ? 'null' : "'$this->fchalta'") .
					", '$this->expe', '$this->obs', $this->base, $this->cant, " .
					"$this->sup, 'A', current_timestamp, " . Yii::$app->user->id . ")";

			return Yii::$app->db->createCommand($sql)->execute() > 0;

		}else {
			$sql = "update objeto_trib set perhasta=$this->perhasta,cat='$this->cat',fchalta=".($this->fchalta == null || trim($this->fchalta) == '' ? 'null' : "'$this->fchalta'");
			$sql .= ",expe='$this->expe',obs='$this->obs',base=$this->base,cant=$this->cant,sup=$this->sup,fchmod=current_timestamp,usrmod=".Yii::$app->user->id;
			$sql .= " where obj_id='$this->obj_id' and trib_id=$this->trib_id and perdesde=$this->perdesde";

			return Yii::$app->db->createCommand($sql)->execute() > 0;
		}

    	return false;
    }

    private function grabarCondonacion(){


		$sql = "Select sam.uf_ctacte_condona($this->trib_id, '$this->obj_id', $this->subcta, $this->adesde, $this->cdesde, $this->ahasta, $this->chasta, '$this->expe', '$this->obs', " . Yii::$app->user->id . ")";

		try{
			Yii::$app->db->createCommand($sql)->execute();

		} catch(\Exception $e){

			$this->addError($this->trib_id, DBException::getMensaje($e));
			return false;
		}

		return true;
    }

    private function grabarPrescripcion(){

		$this->accion = intval($this->accion);

    	$strAccion = $this->accion === 1 ? 'T' : 'D';

    	if($this->accion === 1){

    		$perPlanDecaido = filter_var(intval(utb::samConfig()['per_plan_decaido']), FILTER_VALIDATE_BOOLEAN);

    		$sql = "Select Exists (Select 1 From plan_periodo p Left Join ctacte c On p.ctacte_id = c.ctacte_id Where" .
    				" c.trib_id = $this->trib_id And c.obj_id = '$this->obj_id' And c.anio*1000+c.cuota between $this->perdesde And $this->perhasta" .
    				" And c.est = 'D')";

    		$existenPlanes = filter_var(intval(Yii::$app->db->createCommand($sql)->queryScalar()), FILTER_VALIDATE_BOOLEAN);


    		if($perPlanDecaido && $existenPlanes){
    			$this->addError('obj_id', 'Los datos no pueden ser grabados porque existen convenios decaídos para el período');
    			return false;
    		}
    	}

		$sql = "Select sam.uf_ctacte_prescriptos('$strAccion', $this->trib_id, '$this->obj_id', $this->subcta, $this->perdesde, $this->perhasta, " . Yii::$app->user->id . ", '$this->expe', '$this->obs')";

		try{

    		Yii::$app->db->createCommand($sql)->execute();
    	} catch(Exception $e){
    		$this->addError($this->trib_id, DBException::getMensaje($e));
    		return false;
    	}

    	return true;
    }

    private function grabarDjFaltante(){

    	if($this->nuevo){

			//se verifica que exista el comercio
			$tobj = intval(utb::getTObj($this->obj_id));

			if($tobj === 2){

				$sql = "Select Exists(Select 1 From objeto Where obj_id = '$this->obj_id')";
				$res = Yii::$app->db->createCommand($sql)->queryScalar();

				if($res === false){
					$this->addError($this->obj_id, 'El objeto no existe');
					return false;
				}
			}

			$sql = "Select * from Sam.uf_ctacte_djfaltantes($this->trib_id, '$this->obj_id', $this->adesde, $this->cdesde, current_date, " . Yii::$app->user->id .");";

			try{

	    		Yii::$app->db->createCommand($sql)->execute();
	    	} catch(\Exception $e){
	    		$this->addError($this->trib_id, DBException::getMensaje($e));
	    		return false;
	    	}

		}

    	return true;
    }

    private function borrarAsignacion(){

    	$sql = "Delete From objeto_item Where obj_id = '$this->obj_id' And subcta = $this->subcta And orden = $this->orden And perdesde = $this->perdesde And item_id = $this->item_id";
    	return Yii::$app->db->createCommand($sql)->execute() > 0;
    }

    private function borrarExcepcion(){

    	$this->ctacte_id= $this->getCtacteId($this->trib_id, $this->anio, $this->cuota, $this->obj_id, $this->subcta);

    	//se verfica que el periodo no se encuentre pagado
    	$sql = "Select Exists(Select 1 From ctacte Where ctacte_id = $this->ctacte_id And est = 'P')";
    	$existe = Yii::$app->db->createCommand($sql)->queryScalar();

    	if($existe){
    		$this->addError($this->ctacte_id, 'El período se encuentra pago');
    		return false;
    	}

    	//se elimina
    	$sql = "Delete From ctacte_excep Where excep_id = $this->excep_id";

    	$res = Yii::$app->db->createCommand($sql)->execute() > 0;

    	if(!$res) $this->addError($this->obj_id, 'No se pudo eliminar la excepción');

    	return $res;
    }

    private function borrarInscripcion(){

    	$sql = "Update objeto_trib Set est = 'B' Where obj_id = '$this->obj_id' And trib_id = $this->trib_id And perdesde = $this->perdesde";
    	return Yii::$app->db->createCommand($sql)->execute() > 0;
    }

    private function borrarCondonacion(){

    	$sql = "Select sam.uf_ctacte_condona_borrar($this->trib_id, '$this->obj_id', $this->subcta, $this->perdesde, $this->perhasta, " . Yii::$app->user->id . ")";

    	try{

    		Yii::$app->db->createCommand($sql)->execute();
    	} catch(Exception $e){
    		$this->addError($this->trib_id, DBException::getMensaje($e));
    		return false;
    	}

    	return true;
    }

    private function borrarDjFaltante(){

    	$sql = "Delete From ctacte Where ctacte_id In(Select c.ctacte_id From ctacte c Left Join ddjj d On c.ctacte_id = d.ctacte_id" .
    			" Left Join comer co On c.obj_id = co.obj_id" .
    			" Where c.trib_id = $this->trib_id " . ($this->obj_id != '' ? " And c.obj_id = '$this->obj_id' And c.subcta = $this->subcta" : '') .
    			" And c.anio * 1000 + c.cuota between $this->perdesde And $this->perhasta And " .
    			" c.est In ('X', 'D') And (d.dj_id Is Null Or d.est = 'B'))";

    	try{

    		Yii::$app->db->createCommand($sql)->execute();
    	} catch(Exception $e){
    		$this->addError($this->trib_id, DBException::getMensaje($e));
    		return false;
    	}

    	return true;
    }

    public static function buscarAv($tipoAcc, $cond, $order = ''){

    	$tabla = (new Tribacc($tipoAcc))->getRelacion($tipoAcc, true);
    	$columnas = '*';

    	if($tipoAcc == TribAcc::TIPO_ASIGNACION || $tipoAcc == TribAcc::TIPO_INSCRIPCION || $tipoAcc == TribAcc::TIPO_CONDONACION)
    		$columnas = "*, (substr(perdesde::text, 1, 4) || '-' || substr(perdesde::text, 5, 3)) AS perdesdeguion, (substr(perhasta::text, 1, 4) || '-' || substr(perhasta::text, 5, 3)) AS perhastaguion";

    	$sql = "Select $columnas From $tabla Where $cond";

    	if($tipoAcc === Tribacc::TIPO_CONDONACION)
    		$sql .= " And tipo = 2";

    	if($order != '') $sql .= " Order By $order";

    	$modelos = Yii::$app->db->createCommand($sql)->queryAll();
    	$count = count($modelos);

    	return new ArrayDataProvider([
    		'allModels' => $modelos,
    		'pagination' => [
    			'totalCount' => $count,
    			'pageSize' => 50
    		]
    	]);
    }

    /**
     *
     */
    public function getRelacion($tipoAcc, $esVista = false){

    	switch($tipoAcc){

    		case Tribacc::TIPO_ASIGNACION: return $esVista ? 'v_objeto_item' : 'objeto_item';
    		case Tribacc::TIPO_EXCEPCION: return $esVista ? 'v_ctacte_excep' : 'ctacte_excep';
    		case Tribacc::TIPO_INSCRIPCION: return $esVista ? 'v_objeto_trib' : 'objeto_trib';
    		case Tribacc::TIPO_CONDONACION: return $esVista ? 'v_ctacte_cambioest' : 'objeto_trib';
    		case Tribacc::TIPO_PRESCRIPCION: return $esVista ? 'a' : 'ctacte';
    		case Tribacc::TIPO_DJ_FALTANTE: return $esVista ? 'b' : 'ctacte';
    	}
    }

    /**
     *
     */
    public function getColumnas($tipoAcc, $esVista){

    	switch($tipoAcc){

    		case Tribacc::TIPO_ASIGNACION: return $esVista ? ['trib_id', 'obj_id', 'subcta', 'item_id', 'perdesde', 'perhasta', 'param1', 'param2', 'expe', 'obs', 'orden'] : [];
    		case Tribacc::TIPO_EXCEPCION: return $esVista ? ['excep_id', 'trib_id', 'obj_id', 'subcta', 'tipo', 'anio', 'cuota', 'fchusar', 'fchlimite', 'expe', 'obs', 'ctacte_id'] : [];
    		case Tribacc::TIPO_INSCRIPCION: return $esVista ? ['trib_id', 'obj_id', 'cat', 'perdesde', 'perhasta', 'fchalta', 'base', 'expe', 'obs'] : [];
    		case Tribacc::TIPO_CONDONACION: return $esVista ? ['trib_id', 'obj_id', 'subcta', 'perdesde', 'perhasta', 'expe', 'obs'] : [];
    		case Tribacc::TIPO_PRESCRIPCION: return $esVista ? [] : [];
    		case Tribacc::TIPO_DJ_FALTANTE: return $esVista ? [] : [];
    	}
    }

    public static function buscarUno($tipoAcc, $condiciones = []){

    	$ret = new Tribacc($tipoAcc);

    	$vista = ($tipoAcc !== Tribacc::TIPO_PRESCRIPCION && $tipoAcc !== Tribacc::TIPO_DJ_FALTANTE) ? true : false;
    	$tabla = $ret->getRelacion($tipoAcc, $vista);
    	$columnas = $ret->getColumnas($tipoAcc, $vista);

    	$condicion = '';

    	foreach($condiciones as $columna => $valor)
    		$condicion = $condicion != '' ? ($condicion . " And $columna = $valor") : "$columna = $valor";

    	$query = new Query();
    	$query->select($columnas)->from($tabla)->where($condicion);

    	$resultado = $query->one();

    	$ret->setScenario($tipoAcc, false);

    	if($resultado !== false && $resultado !== null){
    		$ret->setAttributes($resultado);

    		if($tipoAcc === Tribacc::TIPO_EXCEPCION && in_array('tipo', $resultado))
    			$ret->tipo_id = $resultado['tipo'];

    		$ret->afterFind();
    	}

    	return $ret;
    }

    private function usaSubcta(){

		$res = false;

    	if($this->trib_id !== null && $this->trib_id > 0){

	    	$sql = "Select uso_subcta From trib Where trib_id = $this->trib_id";
			$res = filter_var(Yii::$app->db->createCommand($sql)->queryScalar(), FILTER_VALIDATE_BOOLEAN);
		}

		return $res;
    }

    private function getOrdenAsignacion($obj_id, $subcta){

    	$sql = "Select Coalesce(Max(orden)+1, 0) From objeto_item Where obj_id = '$obj_id' And subcta = $subcta";
    	return intval(Yii::$app->db->createCommand($sql)->queryScalar());
    }

    private function validarTributoIncompatible($obj_id, $trib_id, $perdesde, $perhasta){

    	$sql = "Select Exists(Select 1 From objeto_trib Where obj_id = '$obj_id' And trib_id <> $trib_id And perdesde between $perdesde And $perhasta)";
    	return Yii::$app->db->createCommand($sql)->queryScalar();
    }

    private function getCtacteId($trib_id, $anio, $cuota, $obj_id = '', $subcta = 0){

    	if($trib_id == 1)
    		$sql = "Select ctacte_id From ctacte Where trib_id = $trib_id And anio = $anio And cuota = $cuota";
		else
			$sql = "Select ctacte_id From ctacte Where trib_id = $trib_id And obj_id = '$obj_id' And subcta = $subcta And anio = $anio And cuota = $cuota";

    	$res = Yii::$app->db->createCommand($sql)->queryScalar();

    	if($res === false || intval($res) < 0) return 0;

    	return intval($res);
    }

    public function objetoExiste(){

    	$sql = "Select Exists( Select 1 From objeto Where obj_id = '$this->obj_id')";
    	$res = Yii::$app->db->createCommand($sql)->queryScalar();

    	if(!$res) $this->addError($this->obj_id, 'El objeto no existe');
    }

	public function obtenerVencimiento($trib,$anio,$cuota,$obj="")
	{
		$sql = "Select to_char(fchvenc,'dd/mm/yyyy') fchvenc from ctacte where trib_id=$trib and anio=$anio and cuota=$cuota";
		if ($obj != "") $sql .= " and obj_id='" . $obj ."'";

    	$venc = Yii::$app->db->createCommand($sql)->queryScalar();

		return $venc;
	}

    //--------------------------------- IMPRESIONES ------------------------------------------
    public function ImprimirAsig(&$sub,&$tobj)
    {
    	$sql = "Select * from v_objeto_item Where " . Yii::$app->session['CondImpAsig'];
    	$datos = Yii::$app->db->createCommand($sql)->queryAll();

    	$tobj = utb::getTObj($datos[0]['obj_id']);

    	switch ($tobj) {
	    	case 1:
	    		$sql = "Select * From v_inm Where obj_id='".$datos[0]['obj_id']."'";
	    		break;
	    	case 2:
	    		$sql = "Select * From v_comer_suc Where obj_id='".$datos[0]['obj_id']."'";
	    		break;
	    	case 3:
	    		$sql = "Select * From v_persona Where obj_id='".$datos[0]['obj_id']."'";
	    		break;
	    	case 4:
	    		$sql = "Select * From v_cem Where obj_id='".$datos[0]['obj_id']."'";
	    		break;
	    	case 5:
	    		$sql = "Select * From v_rodado Where obj_id='".$datos[0]['obj_id']."'";
	    		break;
	    }

	    $sub = Yii::$app->db->createCommand($sql)->queryAll();

    	return $datos;
    }
}
