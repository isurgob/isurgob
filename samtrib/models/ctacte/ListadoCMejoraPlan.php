<?php
namespace app\models\ctacte;

use Yii;

use yii\db\Query;
use app\utils\db\utb;
use app\models\Listado;
use app\utils\db\Fecha;

class ListadoCMejoraPlan extends Listado{

	//Número de plan
	public $numero_desde;
	public $numero_hasta;
	
	// Objeto 
	public $objeto_id_desde;
	public $objeto_id_hasta;
	
	//Nombre de Objeto
	public $nombre;

   //Tipo de Obra
	public $tobra;

    //Obra
    public $obra;

    //Cuadra
    public $cuadra;

	//Estado
	public $est;

	//Monto
	public $monto_desde;
	public $monto_hasta;

	//Fecha de Alta
	public $fchalta_desde;
	public $fchalta_hasta;

	//Fecha Baja
	public $fchbaja_desde;
	public $fchbaja_hasta;
	
	//Fecha Desafecta
	public $fchdesaf_desde;
	public $fchdesaf_hasta;
	
	//Fecha Vencimiento 1ra cuota
	public $fchvenc_desde;
	public $fchvenc_hasta;
	
	public function __construct(){
		parent::__construct();
	}

	public static function tableName(){

		return 'v_mej_plan';
	}

	public function rules(){

		$ret = [];

		$ret[] = [
			[
                'objeto_id_desde', 'objeto_id_hasta', 'nombre', 'est', 'tobra', 'fchalta_desde', 'fchalta_hasta',
				'fchbaja_desde', 'fchbaja_hasta', 'fchdesaf_desde', 'fchdesaf_hasta', 'fchvenc_desde', 'fchvenc_hasta'
			],
			'string',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[
				'numero_desde', 'numero_hasta', 'obra', 'cuadra'
			],
			'integer',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[
				'monto_desde','monto_hasta'
			],
			'number',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		return $ret;
	}

	public function scenarios(){

		return [
			Listado::SCENARIO_BUSCAR => [
				'objeto_id_desde', 'objeto_id_hasta', 'nombre', 'est', 'tobra', 'fchalta_desde', 'fchalta_hasta',
				'fchbaja_desde', 'fchbaja_hasta', 'fchdesaf_desde', 'fchdesaf_hasta', 'fchvenc_desde', 'fchvenc_hasta',
				'numero_desde', 'numero_hasta', 'obra', 'cuadra',
				'monto_desde','monto_hasta'
			]
		];
	}

	public function attributeLabels(){

		return [

			'numero_desde' 		=> 'Nº de plan desde',
			'numero_hasta' 		=> 'Nº de plan hasta',

		];
	}

	public function beforeValidate(){

		return true;
	}

	public function buscar(){

		$query		      	= new Query();
		
		$sql = [
            'plan_id',
            'obj_id',
            'obra_id',
            'nc_guiones',
            'obj_nom',
            'dompar',
            'monto',
            'est_nom'
		];

		if ( strtoupper(substr($this->objeto_id_desde,0,1)) == 'I' ) $this->objeto_id_desde = substr($this->objeto_id_desde,1);
		if ( strtoupper(substr($this->objeto_id_hasta,0,1)) == 'I' ) $this->objeto_id_hasta = substr($this->objeto_id_hasta,1);
		
		$this->objeto_id_desde = ereg_replace("[^0-9]", "", $this->objeto_id_desde);
		$this->objeto_id_hasta = ereg_replace("[^0-9]", "", $this->objeto_id_hasta);
		
		$query	->select( $sql )
				->from( ListadoCMejoraPlan::tableName() )
				->filterWhere([ 'between', 'plan_id', $this->numero_desde, $this->numero_hasta ])
				->andFilterWhere([ 'between', 'substr(obj_id,2)::integer', $this->objeto_id_desde, $this->objeto_id_hasta ])
				->andFilterWhere([ 'ilike', 'obj_nom', $this->nombre ])
				->andFilterWhere([ 'tobra' => $this->tobra ])
				->andFilterWhere([ 'obra_id' => $this->obra ])
				->andFilterWhere([ 'cuadra_id' => $this->cuadra ])
				->andFilterWhere([ 'est' => $this->est ])
				->andFilterWhere([ 'between', 'monto', $this->monto_desde, $this->monto_hasta ])
				->andFilterWhere([ 'between', 'fchalta', $this->fchalta_desde, $this->fchalta_hasta ])
				->andFilterWhere([ 'between', 'fchbaja', $this->fchbaja_desde, $this->fchbaja_hasta ])
				->andFilterWhere([ 'between', 'fchdesaf', $this->fchdesaf_desde, $this->fchdesaf_hasta ])
				->andFilterWhere([ 'between', 'fchvenc', $this->fchvenc_desde, $this->fchvenc_hasta ]);

		
        return $query;
	}

	public function validar(){
		return $this->validate();
	}

	public function pk(){
		return 'plan_id';
	}

    public function sort(){

        return [
            'attributes' => [
                'plan_id' => [ SORT_ASC ],
                'obj_nom' => [ SORT_ASC ],
            ],
        ];
    }
	
	public function permiso(){
		return 3390;
	}
}

?>
