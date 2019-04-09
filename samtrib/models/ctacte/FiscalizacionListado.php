<?php
namespace app\models\ctacte;

use Yii;

use yii\db\Query;
use app\utils\db\utb;
use app\models\Listado;
use app\utils\db\Fecha;

class FiscalizacionListado extends Listado{
	
	//Id Fiscaliza
	public $id_desde;
	public $id_hasta;
	
	//Número de Objeto
	public $obj_desde;
	public $obj_hasta;
	
	//Estado de DJ
	public $estadodj;
	
	//Número de expediente
	public $expediente;
	
	//Nombre del inspector
	public $inspec;
	
	//Fechas de alta y baja
	public $fecha_desde_alta;
	public $fecha_hasta_alta;
	public $fecha_desde_baja;
	public $fecha_hasta_baja;
	
	
	
	public function __construct(){
		parent::__construct();
	}

	public static function tableName(){

		return 'v_fiscaliza';
	}

	public function rules(){

		$ret = [];

		$ret[] = [
			[
                'obj_desde','obj_hasta','expediente','fecha_desde_alta','fecha_hasta_alta','fecha_desde_baja','fecha_hasta_baja','inspec','estadodj'

			],
			'string',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[
				 'id_desde','id_hasta','usrmod'
			],
			'integer',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		

		return $ret;
	}

	public function scenarios(){

		return [
			Listado::SCENARIO_BUSCAR => [ 'obj_desde','obj_hasta','expediente','fecha_desde_alta','fecha_hasta_alta','fecha_desde_baja','fecha_hasta_baja','id_desde','id_hasta','inspec','estadodj'
				
			]
		];
	}
	
	public function attributeLabels(){
	
	return [
			'id_hasta'					=> 'ID fiscalización',
			'id_desde'					=> 'ID fiscalización',
			'obj_desde' 				=> 'Número de Objeto Desde',
			'obj_hasta' 				=> 'Número de Objeto Hasta',
			'fecha_desde_alta'			=> 'Fecha de Alta Desde',
			'fecha_hasta_alta'			=> 'Fecha de Alta Hasta',
			'fecha_desde_baja'			=> 'Fecha de Baja Desde',
			'fecha_hasta_baja'			=> 'Fecha de Baja Desde',
			'expediente'				=> 'Expediente',
			
		
		];
	}
	

	public function beforeValidate(){

		//Obtener el número de Objeto
		
		if( $this->obj_desde != '' && $this->obj_hasta != '' )
		{

			$this->obj_desde = utb::getObjeto( 2, $this->obj_desde );
			$this->obj_hasta = utb::getObjeto( 2, $this->obj_hasta );
		}

		return true;
		
		}
	public function buscar(){

		$query = new Query();
		
		$sql = [

            'fisca_id',
        	'obj_id',
        	'obj_nom',
			'expe',
        	'inspector_nom',
        	'fchalta',
        	'fchbaja',
        	'est_nom',
        	'modif'
		];

		$query	->select( $sql )
				->from( FiscalizacionListado::tableName() )
				->filterWhere([ 'between', 'fisca_id', $this->id_desde, $this->id_hasta ])
				->andFilterWhere(['between', 'obj_id', $this->obj_desde, $this->obj_hasta])
				->andFilterWhere(['est'=>$this->estadodj])
				->andFilterWhere(['ilike','inspector_nom',$this->inspec ])
				->andFilterWhere(['ilike','expe',$this->expediente ])
				->andFilterWhere(['between','fchalta', $this->fecha_desde_alta, $this->fecha_hasta_alta])
				->andFilterWhere(['between','fchbaja', $this->fecha_desde_baja, $this->fecha_hasta_baja])
				
		;

        return $query;

	}

	public function validar(){
		return $this->validate();
	}

	public function pk(){
		return 'fisca_id';
	}

    public function titulo(){
		return "Listado de Fiscalización";
	}
	
	public function sort(){

        return [

            'attributes' => [
					'fisca_id',
					'obj_id',
					'obj_nom',
					'expe',
					'inspector_nom',
					'fchalta',
					'fchbaja',
					'est_nom',
					'modif'	
                
				
            ],

			'defaultOrder'	=> [
				'fisca_id' => SORT_ASC
			],
        ];
    }
	public function permiso(){
		return 3370;  
	}
}

?>
