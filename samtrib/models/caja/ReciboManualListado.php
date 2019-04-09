<?php
namespace app\models\caja;

use Yii;

use yii\db\Query;
use app\utils\db\utb;
use app\models\Listado;
use app\utils\db\Fecha;
use app\models\presup\Ejer;

class RecibomanualListado extends Listado{
	
	
	
	//Recibo
	public $numero_desde_ref;
	public $numero_hasta_ref;
	public $numero_desde_rec;
	public $numero_hasta_rec;
	public $fecha_desde_rec;
	public $fecha_hasta_rec;
	public $fecha_desde_ing;
	public $fecha_hasta_ing;
	public $numero_desde_acta;
	public $numero_hasta_acta;
	public $item;
	public $area;
	public $estado;
	public $ticket;
	
	
	public $tipoListado;
	 
	public function __construct($tipoListado){
		parent::__construct();
		
		$this->tipoListado =  $tipoListado; 
		
	}



	public function rules(){

		$ret = [];

		$ret[] = [
			[
                'fecha_desde_rec','fecha_hasta_rec','fecha_desde_ing','fecha_hasta_ing','item','area','estado','ticket'
			],
			'string',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[
				 'numero_desde_ref','numero_hasta_ref','numero_desde_rec','numero_hasta_rec','numero_desde_acta','numero_hasta_acta','tipoListado' 
			],
			'integer',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		

		return $ret;
	}

	public function scenarios(){

		return [
			Listado::SCENARIO_BUSCAR => ['fecha_desde_rec','fecha_hasta_rec','fecha_desde_ing','fecha_hasta_ing','item','area','estado','ticket','numero_desde_ref',
										  'numero_hasta_ref','numero_desde_rec','numero_hasta_rec', 'numero_desde_acta','numero_hasta_acta','tipoListado']
		];
	}

	public function attributeLabels(){

		return [

			
		];
	}
	

	public function buscar(){

		$query = new Query();
		$queryObjeto = null;
		$queryFecha = null;
		
		if( $this->fecha_desde_ing != null ){
			$desde = $this->fecha_desde_ing;
			$hasta = $this->fecha_hasta_ing;
			$sql = 	"Select ctacte_id  FROM v_recibo WHERE ctacte_id in (SELECT ctacte_id FROM caja_ticket WHERE Fecha::date between $desde and $hasta) ";
			$queryFecha = Yii::$app->db->createCommand( $sql )->queryAll();

		}
				
		if ($this->tipoListado == 1 ){
			$select = "ctacte_id,
					   est,
					   recibo,
					   fecha,
					   acta,
					   item_nom,
					   area_nom,
					   monto,
					   ticket,
					   obj_id";
			$from = 'v_recibo';
		

			$query	->select($select)
					->from($from)
					->filterWhere(['between', 'recibo', $this->numero_desde_rec, $this->numero_hasta_rec ])
					->andFilterWhere(['between', 'fecha', $this->fecha_desde_rec, $this->fecha_hasta_rec ])
					->andFilterWhere(['between', 'ctacte_id', $this->numero_desde_ref, $this->numero_hasta_ref ])
					->andFilterWhere(['ctacte_id' => $queryFecha ])
					->andFilterWhere(['between', 'acta', $this->numero_desde_acta, $this->numero_hasta_acta])
					->andFilterWhere(['ilike','item_nom',$this->item ])
					->andFilterWhere(['ilike','area_nom',$this->area ])
					->andFilterWhere(['ilike','ticket',$this->ticket ])
					->andFilterWhere(['ilike','est',$this->estado ])
					->orderBy('ctacte_id')
						;} 
			else { $sql = [ 
							'r.ctacte_id',
							'(c.est) est',
							'ticket',
							'(c.obj_id) objeto',
							'count(r.*) cant',
							'sum(r.monto) total',
							'(c.obs) obs',
						  ];
			$from='ctacte_rec';			  
			$query	->select( $sql )
					->from($from.' r' )
					->join('left join','ctacte c','r.ctacte_id = c.ctacte_id')
					->join('left join','caja_ticket t','r.ctacte_id= t.ctacte_id')
					->filterWhere(['between', 'r.ctacte_id', $this->numero_desde_ref, $this->numero_hasta_ref])
					->andFilterWhere(['ilike','c.est',$this->estado ])					
					->groupBy(['r.ctacte_id', 'c.est', 't.ticket', 'c.obj_id','c.obs'])
					->orderBy('r.ctacte_id')
					;
				
				}

        return $query;

	}

	public function validar(){
		return $this->validate();
	}

	public function pk(){
		return 'ctacte_id';
	}

  
	
	public function titulo(){
		return "Listado de Recibos";
	}
	
	public function permiso(){
		return 3418;  
	}
}

?>
