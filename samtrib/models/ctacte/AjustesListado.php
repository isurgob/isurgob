<?php
namespace app\models\ctacte;

use Yii;

use yii\db\Query;
use app\utils\db\utb;
use app\models\Listado;
use app\utils\db\Fecha;

class AjustesListado extends Listado{

	public $tributo;
	
	public $tipo_objeto;
	public $objeto;
	public $objeto_nombre;
  
	public $anio;
	public $cuota;
	
	public $expe;
	
	public $fecha_desde;
	public $fecha_hasta;
	
	public $usuario;
	
	
	public function __construct(){
		parent::__construct();
	}

	public static function tableName(){

		return 'v_ctacte_ajuste';
	}

	public function rules(){

		$ret = [];

		$ret[] = [
			[
                'objeto', 'objeto_nombre', 'expe', 'fecha_desde', 'fecha_hasta'

			],
			'string',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[
				 'tipo_objeto', 'tributo', 'anio', 'cuota', 'usuario'
			],
			'integer',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		

		return $ret;
	}

	public function scenarios(){

		return [
			Listado::SCENARIO_BUSCAR => [
				'objeto', 'objeto_nombre', 'expe', 'fecha_desde', 'fecha_hasta',
				'tipo_objeto', 'tributo', 'anio', 'cuota', 'usuario'
			]
		];
	}

	public function beforeValidate(){

		//Obtener los números de Objeto
		if( $this->objeto != null ){

			$this->objeto = utb::getObjeto( $this->tipo_objeto, $this->objeto );
			$this->objeto_nombre = utb::getNombObj( $this->objeto, false );
		}

		return true;
	}

	public function buscar(){

		$query = new Query();
		
		$sql = [

            'aju_id',
        	'trib_nom',
        	'obj_id',
			'obj_nom',
        	'anio',
        	'cuota',
        	'expe',
        	'fchmod',
        	'usrmod_nom'
		];

		$query	->select( $sql )
				->from( AjustesListado::tableName() )
				->filterWhere([ 'trib_id' => $this->tributo ])
				->andFilterWhere([ 'obj_id' => $this->objeto ])
				->andFilterWhere([ 'anio' => $this->anio ])
				->andFilterWhere([ 'cuota' => $this->cuota ])
				->andFilterWhere([ 'expe' => $this->expe ])
				->andFilterWhere([ 'between', 'fchmod', $this->fecha_desde, $this->fecha_hasta])
				->andFilterWhere([ 'usrmod'=> $this->usuario ])
		;

        return $query;

	}

	public function validar(){
		return $this->validate();
	}

	public function pk(){
		return 'aju_id';
	}

    public function titulo(){
		return "Listado de Ajustes de Cuenta Corriente";
	}
	public function permiso(){
		return 3303;  
	}
}

?>
