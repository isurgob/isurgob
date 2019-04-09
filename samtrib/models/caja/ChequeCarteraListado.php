<?php


namespace app\models\Caja;

use Yii;

use yii\db\Query;
use app\utils\db\utb;
use app\models\Listado;
use app\utils\db\Fecha;
use app\models\presup\Ejer;


class chequecarteraListado extends Listado{

	//Estado
	public $est;

	//Número de Cheque
	public $cheque;	

	//plan
	public $plan;
		
		
	//Fecha
	public $fecha_desde_a;
	public $fecha_hasta_a;
	public $fecha_desde_c;
	public $fecha_hasta_c;
    
	 
	 
	public function __construct(){
		parent::__construct();
	}

	public static function tableName(){

		return 'v_caja_cheque_cartera';
	}

	public function rules(){

		$ret = [];

		$ret[] = [
			[
                'fecha_desde_a','fecha_hasta_a','fecha_desde_c','fecha_hasta_c','cheque','est'

			],
			'string',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[
				 'plan'
		 
			],
			'integer',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		

		return $ret;
	}

	public function scenarios(){

		return [
			Listado::SCENARIO_BUSCAR => [ 'fecha_desde_a','fecha_hasta_a','fecha_desde_c','fecha_hasta_c','cheque','est','plan'
					  
			]
		];
	}

	public function attributeLabels(){

		return [

			
		];
	}

	

	public function buscar(){

		$query = new Query();
		
	
	
		
		$sql = [

			'cart_id',
	        'plan_id',
	        'plan_id2',
	        'nrocheque',
	        'monto',
	        'bco_ent_nom',
            'bco_cta',
            'titular',
            'fchalta',
            'fchcobro',
            'est',	
	
		];

		$query	->select( $sql )
				->from( ChequecarteraListado::tableName() )
				->filterWhere([ 'ilike','est',$this->est])
				->andFilterWhere(['plan_id'=>$this->plan_id])
				->andFilterWhere(['ilike','nrocheque',$this->cheque])
				->andFilterWhere(['between','fchalta',$this->fecha_desde_a,$this->fecha_hasta_a])
				->andFilterWhere(['between','fchcobro',$this->fecha_desde_c,$this->fecha_hasta_c])
				;

        return $query;
	}

	public function validar(){
		return $this->validate();
	}

	public function pk(){
		return 'cart_id';
	}

	public function titulo(){
		return "Listado Cheque Cartera";
	}

	public function permiso(){
		return 3577;  
	}
}

?>
