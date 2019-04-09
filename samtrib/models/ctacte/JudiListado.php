<?php
namespace app\models\ctacte;

use Yii;

use yii\db\Query;
use app\utils\db\utb;
use app\models\Listado;
use app\utils\db\Fecha;

class JudiListado extends Listado{

	// codigo
	public $numero_desde;
	public $numero_hasta;
	
	//Objeto
    public $objeto_desde;
    public $objeto_hasta;
    public $objeto_tipo;

	//Expediente
	public $expediente;
	
	public $caratula;
	
	public $deuda_desde;
	public $deuda_hasta;
	
	public $gastos_desde;
	public $gastos_hasta;
	
	public $honorario_desde;
	public $honorario_hasta;
	
	public $reparticion;
	
	public $estado;
	
	public $procurador;
	
	public $juzgado;
	
	public $etapa;
	
	public $motivo_devolucion;
	
	public $fchmov_desde;
	public $fchmov_hasta;
	
	public $fchmod_desde;
	public $fchmod_hasta;


	public function __construct(){
		parent::__construct();
	}

	public static function tableName(){

		return 'v_judi';
	}

	public function rules(){

		$ret = [];

		$ret[] = [
			[
				'expediente', 'objeto_desde', 'objeto_hasta', 'caratula', 'reparticion', 'estado',
				'fchmov_desde', 'fchmov_hasta', 'fchmod_desde', 'fchmod_hasta'
			],
			'string',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[
				'numero_desde', 'numero_hasta', 'objeto_tipo', 'procurador', 'juzgado', 'etapa', 'motivo_devolucion'
			],
			'integer',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[
				'deuda_desde', 'deuda_hasta', 'gastos_desde', 'gastos_hasta', 'honorario_desde', 'honorario_hasta'
			],
			'number',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		return $ret;
	}

	public function scenarios(){

		return [
			Listado::SCENARIO_BUSCAR => [

				'expediente', 'objeto_desde', 'objeto_hasta', 'caratula', 'reparticion', 'estado',
				'fchmov_desde', 'fchmov_hasta', 'fchmod_desde', 'fchmod_hasta',
				'numero_desde', 'numero_hasta', 'objeto_tipo', 'procurador', 'juzgado', 'etapa', 'motivo_devolucion',
				'deuda_desde', 'deuda_hasta', 'gastos_desde', 'gastos_hasta', 'honorario_desde', 'honorario_hasta'

			]
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

		$query			= new Query();
		$queryEtapa		= null;
		$queryFechaMov	= null;
		
		if ( $this->etapa != null ){
			
			$queryEtapa	= new Query();
			
			$queryEtapa	->select( 'judi_id' )
						->from( 'judi_etapa' )
						->filterWhere([ 'etapa' => $this->etapa ])
			;
		}
		
		if ( $this->fchmov_desde != null ){
			
			$queryFechaMov	= new Query();
			
			$queryFechaMov	->select( 'judi_id' )
							->from( 'judi_etapa' )
							->filterWhere([ 'between', 'fchmod', $this->fchmov_desde, $this->fchmov_hasta ])
			;
		}
		
		$sql = [

			'judi_id',
			'obj_id',
			'expe',
			'caratula',
			"(to_char(fchalta, 'dd/MM/yyyy')) as fchalta",
			'procurador_nom',
			'est_nom',
			'(nominal+accesor+multa+multa_omi+hono_jud+gasto_jud) as deuda'

		];

		$query	->select( $sql )
				->from( JudiListado::tableName() )
				->filterWhere([ 'between', 'judi_id', $this->numero_desde, $this->numero_hasta ])
				->filterWhere([ 'between', 'obj_id', $this->objeto_desde, $this->objeto_hasta ])
				->filterWhere([ 'expe' => $this->expediente ])
				->filterWhere([ 'ilike', 'caratula', $this->caratula ])
				->filterWhere([ 'between', '(nominal+accesor+multa+multa_omi)', $this->deuda_desde, $this->deuda_hasta ])
				->filterWhere([ 'between', 'gasto_jud', $this->gastos_desde, $this->gastos_hasta ])
				->filterWhere([ 'between', 'hono_jud', $this->honorario_desde, $this->honorario_hasta ])
				->filterWhere([ 'rep' => $this->reparticion ])
				->filterWhere([ 'procurador' => $this->procurador ])
				->filterWhere([ 'juzgado' => $this->juzgado ])
				->filterWhere([ 'judi_id' => $queryEtapa ])
				->filterWhere([ 'motivo_dev' => $this->motivo_devolucion ])
				->filterWhere([ 'judi_id' => $queryFechaMov ])
				->filterWhere([ 'between', 'fchmod', $this->fchmod_desde, $this->fchmod_hasta ])
		;

        return $query;

	}

	public function validar(){
		return $this->validate();
	}

	public function pk(){
		return 'judi_id';
	}

}

?>
