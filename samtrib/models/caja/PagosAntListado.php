<?php


namespace app\models\caja;

use Yii;

use yii\db\Query;
use app\utils\db\utb;
use app\models\Listado;
use app\utils\db\Fecha;
use app\models\presup\Ejer;

class PagosAntListado extends Listado{

		
	//Objeto
    public $objeto_desde;
    public $objeto_hasta;
    public $objeto_tipo;
	
		
	//Fecha
	public $fecha_desde_p;
	public $fecha_hasta_p;
	public $fecha_desde_c;
	public $fecha_hasta_c;
    
	//Caja
	public $tributo;

	
	
	 
	 
	public function __construct(){
		parent::__construct();
	}

	public static function tableName(){

		return 'caja_pagoold';
	}

	public function rules(){

		$ret = [];

		$ret[] = [
			[
                'objeto_desde', 'objeto_hasta','fecha_desde_p','fecha_hasta_p','fecha_desde_c','fecha_hasta_c'

			],
			'string',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[
				 'objeto_tipo','tributo',
		 
			],
			'integer',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		

		return $ret;
	}

	public function scenarios(){

		return [
			Listado::SCENARIO_BUSCAR => [ 'objeto_desde', 'objeto_hasta','fecha_desde_p','fecha_hasta_p','fecha_desde_c','fecha_hasta_c','objeto_tipo','tributo',
					  
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

				'pago_id',
				'p.obj_id',
				'(o.nombre) obj_nom',
				'(t.nombre ) trib_nom',
				'anio',
				'cuota',
				'fchpago',
				'comprob',
				"(u.nombre || ' - ' || to_char(p.fchmod,'dd/mm/yyyy')) modif",
			
		];

		$query	->select( $sql )
				->from( PagosAntListado::tableName().' p' )
				->join('inner join','trib t','t.trib_id =p.trib_id ')
				->join('inner join','objeto o','o.obj_id = p.obj_id')
				->join('left join','sam.sis_usuario u', 'p.usrmod = u.usr_id')
				->filterWhere([ 'p.trib_id'=>$this->tributo])
				->andFilterWhere(['between','p.obj_id',$this->objeto_desde,$this->objeto_hasta ])
				->andFilterWhere(['between','p.fchpago',$this->fecha_desde_p,$this->fecha_hasta_p ])
				->andFilterWhere(['between','p.fchmod',$this->fecha_desde_c,$this->fecha_hasta_c ])
				
				;

        return $query;

	}

	public function validar(){
		return $this->validate();
	}

	public function pk(){
		return 'pago_id';
	}

  
	
	public function titulo(){
		return "Listado Registro de Pagos Anteriores";
	}
	public function permiso(){
		return 3418;  
	}
}

?>
