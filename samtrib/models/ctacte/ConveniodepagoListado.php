<?php
namespace app\models\ctacte;

use Yii;

use yii\db\Query;
use app\utils\db\utb;
use app\models\Listado;
use app\utils\db\Fecha;
use app\models\presup\Ejer;

class ConveniodepagoListado extends Listado{

	//Números de referencia
	public $numero_desde;
	public $numero_hasta;
	public $numero_desde_ant;
	public $numero_hasta_ant;
  
	//Numero de cuotas atrasadas
	public $numero_desde_cuoatrasa;
	public $numero_hasta_cuoatrasa;
	//Numero de cuotas
	public $numero_desde_cuota;
	public $numero_hasta_cuota;
	
	//Numero de monto de cuotas
	public $numero_desde_moncuota;
	public $numero_hasta_moncuota;
	
	//Numero de interés
	public $numero_desde_interes;
	public $numero_hasta_interes; 

	//Objeto
    public $objeto_desde;
    public $objeto_hasta;
    public $objeto_tipo;
	
	//Objeto nombre
	public $nombre;
	public $resp;
    
	//Responsable
	public $responsable;
	
	//Tipo de Convenio
	public $tconvenio;
	
	//Tipo de Pago
	public $tpago;
	public $plancuotasvenc;
	
	//Tipo de Origen
	public $torigen;
	
	//Fecha de Alta
	public $fecha_alta_desde;
	public $fecha_alta_hasta;

	//Fecha de Baja
	public $fecha_baja_desde;
	public $fecha_baja_hasta;
	
	//Fecha de Imputación
	public $fecha_imp_desde;
	public $fecha_imp_hasta;
	
	//Fecha Decae
	public $fecha_decae_desde;
	public $fecha_decae_hasta;
		
	//Estado 
	 public $est;
	 
	 //Caja
	 public $caja;
	 
	 // Tributo
	 public $tributo;
	 
	 //Quitas
	 public $con_quitas_especiales;
	 //Modif Consolidación de Intereses
	 public $modifconsint;
	 
	 
	public function __construct(){
		parent::__construct();
	}

	public static function tableName(){

		return 'v_plan';
	}

	public function rules(){

		$ret = [];

		$ret[] = [
			[
                'objeto_desde', 'objeto_hasta','nombre','responsable','tconvenio','tpago','torigen','fecha_alta_desde','fecha_alta_hasta','fecha_baja_desde','fecha_baja_hasta','fecha_imp_desde','fecha_imp_hasta','fecha_decae_desde','fecha_decae_hasta','est','caja','tributo','resp','plancuotasvenc'			  

			],
			'string',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[
				 'numero_desde', 'numero_hasta','objeto_tipo','numero_desde_ant','numero_hasta_ant','numero_desde_cuoatrasa','numero_hasta_cuoatrasa',
				  'numero_desde_interes','numero_hasta_interes','numero_hasta_moncuota','numero_desde_moncuota','numero_hasta_cuota','numero_desde_cuota',
				  'con_quitas_especiales','modifconsint'	
		 
			],
			'integer',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		

		return $ret;
	}

	public function scenarios(){

		return [
			Listado::SCENARIO_BUSCAR => [
				'objeto_desde', 'objeto_hasta','numero_desde', 'numero_hasta','objeto_tipo','nombre','numero_desde_ant','numero_hasta_ant',
				'responsable','tconvenio','tpago','torigen','fecha_alta_desde','fecha_alta_hasta','fecha_baja_desde','fecha_baja_hasta','fecha_imp_desde','fecha_imp_hasta','fecha_decae_desde','fecha_decae_hasta','numero_desde_cuoatrasa','numero_hasta_cuoatrasa',
				  'numero_desde_interes','numero_hasta_interes','numero_hasta_moncuota','numero_desde_moncuota','numero_hasta_cuota','numero_desde_cuota',
				  'est','caja','tributo','con_quitas_especiales','modifconsint','resp','plancuotasvenc'
			]
		];
	}

	public function attributeLabels(){

		return [

			'numero_desde' 				=> 'Nº de Convenio de Pago H',
			'numero_hasta' 				=> 'Nº de Convenio de Pago D',
			'numero_desde_ant' 			=> 'Convenio Anterior Desde',
			'numero_hasta_ant'			=> 'Convenio Anterior Hasta',
			'con_quitas_especiales'     => 'Con Quitas Especiales',
			'modifconsint'              =>'Con Cambio de Fecha de Consolidación de Intereses',
			'resp'						=>'El Reponsable Coincide con el Responsable del Objeto',
			'plancuotasvenc'            =>'Planes Vigentes con más de 3 cuotas vencidas'
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
		$queryTrib=null;
		$queryQuitas=null;
		$queryModif=null;
		$queryResp=null;
		$queryPlan=null;

		
		
		if( $this->tributo != '' ){

			$queryTrib	= new Query();

			$sql = 	"select distinct plan_id from plan_periodo p left join ctacte c on p.ctacte_id=c.ctacte_id where
			c.trib_id='$this->tributo'";

			$queryTrib = Yii::$app->db->createCommand( $sql )->queryAll();
		}
		
		If($this->con_quitas_especiales>0){
			
			$queryQuitas =( new Query())->select ('plan_id')
			
			->from( 'v_plan' )
			->where('(DescNominal > 0 and DescNominal<> tDescNominal) or (DescInteres > 0 and DescInteres<>tDescInteres)
					or (DescMulta > 0 and DescMulta<>tDescMulta) or (Interes > 0  and Interes <> tInteres)');
						
		}
		
		If($this->modifconsint>0){
			
			$queryModif =( new Query())->select ('plan_id')
			
			->from( 'v_plan' )
			->where("to_char (fchalta,'dd/mm/YYYY')  <> to_char (fchmod,'dd/dm/YYYY') ");
						
		}
		
		If($this->resp>0){
			
			$queryResp =( new Query())->select ('plan_id')
			
			->from( 'v_plan' )
			->where("upper(num_nom)=upper(resp) ");
						
		}
		
		if( $this->plancuotasvenc >0 ){

			$queryPlan	= new Query();

			$sql="select p.plan_id from ctacte c inner join plan p on c.anio=p.plan_id and c.trib_id=1 
			where c.est='D' and c.fchvenc<CURRENT_DATE and p.est=1 group by p.plan_id having count(c.cuota) > 2";
			
			$queryPlan = Yii::$app->db->createCommand( $sql )->queryAll();
		}
		
		
		
		$sql = [

            'plan_id',
        	'tplan_nom',
        	'obj_id',
			'resp',
        	'nominal',
        	'accesor',
        	'multa',
        	'financia',
        	'est_nom',
			'fchmod',
			
            
		];

		$query	->select( $sql )
				->from( ConveniodepagoListado::tableName() )
				->filterWhere([ 'between', 'plan_id', $this->numero_desde, $this->numero_hasta ])
				->andFilterWhere([ 'between', 'plan_id', $this->numero_desde_ant, $this->numero_hasta_ant ])
				->andFilterWhere([ 'between', 'obj_id', $this->objeto_desde, $this->objeto_hasta ])
				->andFilterWhere([ 'ilike', 'obj_nom', $this->nombre ])
				->andFilterWhere([ 'ilike','resp', $this->responsable ])
				->andFilterWhere([ 'tplan'=> $this->tconvenio ])
				->andFilterWhere([ 'tpago'=> $this->tpago ])
				->andFilterWhere([ 'origen'=> $this->torigen ])
				->andFilterWhere([ 'between', 'fchalta', $this->fecha_alta_desde, $this->fecha_alta_hasta])
				->andFilterWhere([ 'between', 'fchbaja', $this->fecha_baja_desde, $this->fecha_baja_hasta ])
				->andFilterWhere([ 'between', 'fchimputa', $this->fecha_imp_desde, $this->fecha_imp_hasta])
				->andFilterWhere([ 'between', 'fchdecae', $this->fecha_decae_desde, $this->fecha_decae_hasta ])
				->andFilterWhere([ 'between', 'cuotas', $this->numero_desde_cuota, $this->numero_hasta_cuota ])
				->andFilterWhere([ 'between', 'montocuo', $this->numero_desde_moncuota, $this->numero_hasta_moncuota ])
				->andFilterWhere([ 'between', 'interes', $this->numero_desde_interes, $this->numero_hasta_interes ])
				->andFilterWhere([ 'between', 'cuotasatrasadas', $this->numero_desde_cuoatrasa, $this->numero_hasta_cuoatrasa ])
				->andFilterWhere([ 'est'=> $this->est ])
				->andFilterWhere(['caja_id'=> $this->caja])
				->andFilterWhere([ 'plan_id' => $queryTrib ])
				->andFilterWhere([ 'plan_id' => $queryQuitas ])
				->andFilterWhere([ 'plan_id' => $queryModif ])
				->andFilterWhere([ 'plan_id' => $queryResp ])
				->andFilterWhere([ 'plan_id' => $queryPlan ])
				;

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
				'resp',
				'nominal',
				'accesor',
				'multa',
				'financia',
				'est_nom',
				'tplan_nom',	
                'plan_id',
                'obj_id' 	=> [
					'asc'	=> [ 'obj_id' => SORT_ASC, 'obj_nom' => SORT_ASC, 'tplan_nom' => SORT_ASC ],
					'desc'	=> [ 'obj_id' => SORT_DESC, 'obj_nom' => SORT_ASC, 'tplan_nom' => SORT_ASC ],
				],
				
            ],

			'defaultOrder'	=> [
				'plan_id' => SORT_ASC
			],
        ];
    }
	
	public function titulo(){
		return "Listado de Convenio de Pago";
	}
	public function permiso(){
		return 3340;  
	}
}

?>
