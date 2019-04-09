<?php
namespace app\models\ctacte;

use Yii;

use yii\db\Query;
use app\utils\db\utb;
use app\models\Listado;
use app\utils\db\Fecha;
use app\models\presup\Ejer;

class LibredeudaListado extends Listado{

	//Número de referencia
	public $numero_desde;
	public $numero_hasta;
  
  //Objeto
    public $objeto_desde;
    public $objeto_hasta;
    public $objeto_tipo;
	
	//Objeto nombre
	public $nombre;
    
	//Escribano
	public $escribano;
	
	//Fecha de Emisión
	public $fecha_emi_desde;
	public $fecha_emi_hasta;
	
	//Fecha de Modificación
	public $fecha_modif_desde;
	public $fecha_modif_hasta;
	
	//Usuario
	public $usuario;
	
	//Estado 
	public $estado;


	public function __construct(){
		parent::__construct();
	}

	public static function tableName(){

		return 'v_libredeuda';
	}

	public function rules(){

		$ret = [];

		$ret[] = [
			[
                'objeto_desde', 'objeto_hasta','nombre','escribano','fecha_emi_desde', 'fecha_emi_hasta', 'fecha_modif_desde','fecha_modif_hasta','estado',
			],
			'string',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[
				 'numero_desde', 'numero_hasta','objeto_tipo','usuario',
		 
			],
			'integer',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		

		return $ret;
	}

	public function scenarios(){

		return [
			Listado::SCENARIO_BUSCAR => [
				'objeto_desde', 'objeto_hasta','numero_desde', 'numero_hasta','objeto_tipo','nombre','escribano',
				'fecha_emi_desde', 'fecha_emi_hasta','usuario','estado','fecha_modif_desde','fecha_modif_hasta',
			]
		];
	}

	public function attributeLabels(){

		return [

			'numero_desde' 				=> 'Nº libre de Deuda Desde',
			'numero_hasta' 				=> 'Nº libre de Deuda Hasta',
			
		];
	}
/*
var perdesde = (parseInt($("#ddjj_txAnioDesde").val()) * 1000) + parseInt($("#ddjj_txCuotaDesde").val());
var perhasta = (parseInt($("#ddjj_txAnioHasta").val()) * 1000) + parseInt($("#ddjj_txCuotaHasta").val());

if (perdesde > perhasta)
{
	error.push( "Período mal ingresado" );
} else
{
	if (criterio!=="") criterio += " and ";
	criterio += " anio*1000+cuota >='"+perdesde+"' AND anio*1000+cuota <='"+perhasta+"'";
	descr += " -Período desde "+perdesde+" hasta "+perhasta;
}
*/
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
		$queryFechaEmision = null;
		$queryFechaModif = null;

		/*if( $this->desde_anio != null ){

			$desde = $this->desde_anio * 1000 + $this->desde_cuota;
			$hasta = $this->hasta_anio * 1000 + $this->hasta_cuota;

			$queryPeriodo = ( new Query() )->select( 'ldeuda_id' )
				->from( 'ctacte_libredeuda' )
				->where( "anio*1000+cuota >= $desde AND anio*1000+cuota <= $hasta" );
		}*/
		
		if( $this->fecha_emi_desde != null ){

			$queryFechaEmision	= ( new Query() )->select( 'ldeuda_id' )
				->from( LibredeudaListado::tableName() )
				->where( "fchemi::date BETWEEN '$this->fecha_emi_desde' AND '$this->fecha_emi_hasta'" );

		}
		if( $this->fecha_modif_desde != '' ){

			$queryFechaModif	= new Query();

			$queryFechaModif->select('ldeuda_id')
				->from( 'v_libredeuda')
				->where( "fchmod::date BETWEEN '$this->fecha_modif_desde' AND '$this->fecha_modif_hasta'" );

		}
		
		
		
		$sql = [

            'ldeuda_id',
			'obj_id',
			'obj_nom',
			'num_nom',
			'fchemi',
			'modif',
            'est',
			
			
            
		];

		$query	->select( $sql )
				->from( LibredeudaListado::tableName() )
				->filterWhere([ 'between', 'ldeuda_id', $this->numero_desde, $this->numero_hasta ])
				->andFilterWhere([ 'between', 'obj_id', $this->objeto_desde, $this->objeto_hasta ])
				->andFilterWhere([ 'ilike', 'obj_nom', $this->nombre ])
				->andFilterWhere([ 'ilike', 'escribano', $this->escribano ])
				->andFilterWhere([ 'ldeuda_id' => $queryFechaEmision ])
				->andFilterWhere([ 'in', 'ldeuda_id', $queryFechaModif ])
				->andFilterWhere([ 'est'=> $this->estado ])
		 		->andFilterWhere([ 'usrmod'=> $this->usuario ]);

        return $query;

	}

	public function validar(){
		return $this->validate();
	}

	public function pk(){
		return 'ldeuda_id';
	}

   public function sort(){

        return [

            'attributes' => [

                'ldeuda_id',
                'obj_id' 	=> [
					'asc'	=> [ 'obj_id' => SORT_ASC, 'obj_nom' => SORT_ASC, 'fchemi' => SORT_ASC ],
					'desc'	=> [ 'obj_id' => SORT_DESC, 'obj_nom' => SORT_ASC, 'fchemi' => SORT_ASC ],
				],
				
            ],

			'defaultOrder'	=> [
				'ldeuda_id' => SORT_ASC
			],
        ];
    }
	
	public function titulo(){
		return "Listado de Libre Deuda";
	}
	public function permiso(){
		return 3309;  
	}
}

?>
