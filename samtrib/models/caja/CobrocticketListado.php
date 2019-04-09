<?php
namespace app\models\caja;

use Yii;

use yii\db\Query;
use app\utils\db\utb;
use app\models\Listado;
use app\utils\db\Fecha;
use app\models\presup\Ejer;

class CobrocticketListado extends Listado{

	
	//Números de referencia
	public $numero_desde;
	public $numero_hasta;
	public $numero_desde_op;
	public $numero_hasta_op;
	public $numero_desde_monto;
	public $numero_hasta_monto;
	
	//Objeto
    public $objeto_desde;
    public $objeto_hasta;
    public $objeto_tipo;
	
	//Objeto nombre
	public $nombre;
	public $resp;
	
	//Estado 
	public $estado;
	
	//Fecha
	public $fecha_desde;
	public $fecha_hasta;
    
	//Caja
	public $caja;
	public $tributo;
	public $tesoreria;
	
	
	 
	 
	public function __construct(){
		parent::__construct();
	}

	public static function tableName(){

		return 'v_caja_ticket';
	}

	public function rules(){

		$ret = [];

		$ret[] = [
			[
                'objeto_desde', 'objeto_hasta','resp','fecha_desde','fecha_hasta','caja','nombre','estado',

			],
			'string',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[
				 'objeto_tipo',	'numero_desde', 'numero_hasta','numero_desde_op', 'numero_hasta_op','tributo','numero_desde_monto', 'numero_hasta_monto',
				 'tesoreria', 
		 
			],
			'integer',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		

		return $ret;
	}

	public function scenarios(){

		return [
			Listado::SCENARIO_BUSCAR => [
				'objeto_desde', 'objeto_hasta','objeto_tipo','nombre','numero_desde', 'numero_hasta','numero_desde_op', 'numero_hasta_op','fecha_desde','fecha_hasta','caja','tributo','numero_desde_monto', 'numero_hasta_monto','estado','tesoreria',	  
			]
		];
	}

	public function attributeLabels(){

		return [

			
		];
	}

	public function beforeValidate(){

		//Obtener los números de Objeto
		if( $this->objeto_desde != null ){

			$this->objeto_desde = utb::getObjeto( $this->objeto_tipo, $this->objeto_desde );
			$this->objeto_hasta	= utb::getObjeto( $this->objeto_tipo, $this->objeto_hasta );
		}

		return true;
	}

	public function buscar(){

		$query = new Query();
		$queryObjeto = null;
		
	
	//	
		
		$sql = [

				'ticket',
				'opera',
				'fecha',
				'hora',
				'monto',
				'caja_id',
				'trib_nom',
				'obj_id',
				'obj_nom',
				'subcta',
				'anio',
		        'cuota',
		        'num',
				'mdps',
		        'est',
			
            
		];

		$query	->select( $sql )
				->from( CobrocticketListado::tableName() )
				->filterWhere([ 'between', 'ticket', $this->numero_desde, $this->numero_hasta ])
				->andFilterWhere([ 'between', 'opera', $this->numero_desde_op, $this->numero_hasta_op ])
				->andFilterWhere([ 'between', 'fecha', $this->fecha_desde, $this->fecha_hasta ])
				->andFilterWhere(['caja_id'=> $this->caja])
				->andFilterWhere(['trib_id'=> $this->tributo])
				->andFilterWhere([ 'between', 'obj_id', $this->objeto_desde, $this->objeto_hasta ])
				->andFilterWhere([ 'ilike', 'obj_nom', $this->nombre ])
				->filterWhere([ 'between', 'monto', $this->numero_desde_monto, $this->numero_hasta_monto ])
				->andFilterWhere(['est'=> $this->estado])
				->andFilterWhere([ 'teso_id'=> $this->tesoreria ])
				;

        return $query;

	}

	public function validar(){
		return $this->validate();
	}

	public function pk(){
		return 'ticket';
	}

  public function sort(){

        return [

            'attributes' => [
				'monto',
				'opera',
				'fecha',
				'ticket'
				  	=> [
					'asc'	=> [ 'ticket' => SORT_ASC, 'opera' => SORT_ASC, 'fecha' => SORT_ASC ],
					'desc'	=> [ 'ticket' => SORT_DESC, 'opera' => SORT_ASC, 'fecha' => SORT_ASC ],
				],
				
            ],

			'defaultOrder'	=> [
				'ticket' => SORT_ASC
			],
        ];
    }
	
	public function titulo(){
		return "Listado de Cobranza";
	}
	public function permiso(){
		return 3504;  
	}
}

?>
