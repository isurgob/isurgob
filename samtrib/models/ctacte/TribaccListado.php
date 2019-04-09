<?php
namespace app\models\ctacte;

use Yii;

use yii\db\Query;
use app\utils\db\utb;
use app\models\Listado;
use app\utils\db\Fecha;

class TribaccListado extends Listado{

	public $tributo;
	public $item;
	public $tipo_objeto;
	public $objeto_desde;
	public $objeto_hasta;
	public $anio_desde;
	public $mes_desde;
	public $anio_hasta;
	public $mes_hasta;
	public $fchmodif_desde;
	public $fchmodif_hasta;
	public $expediente;
	
	// excepciones
	public $tipo_cuenta;
	
	// inscripcion
	public $categoria;
	public $fchalta_desde;
	public $fchalta_hasta;
		
	public $tipoListado;
	
	public function __construct( $tipoListado ){
		parent::__construct();
		
		$this->tipoListado =  $tipoListado; 
	}

	public function rules(){

		$ret = [];

		$ret[] = [
			[
				'objeto_desde', 'objeto_hasta', 'fchmodif_desde', 'fchmodif_hasta', 'expediente', 'categoria', 'fchalta_desde', 'fchalta_hasta'
			],
			'string'
		];

		$ret[] = [
			[
				'tributo', 'item', 'tipo_objeto', 'anio_desde', 'mes_desde', 'anio_hasta', 'mes_hasta', 'tipo_cuenta'
			],
			'integer'
		];

		return $ret;
	}

	public function scenarios(){

		return [
			
			Listado::SCENARIO_BUSCAR => [
				'objeto_desde', 'objeto_hasta', 'fchmodif_desde', 'fchmodif_hasta','expediente',
				'tributo', 'item', 'tipo_objeto', 'anio_desde', 'mes_desde', 'anio_hasta', 'mes_hasta',
				'tipo_cuenta', 'categoria', 'fchalta_desde', 'fchalta_hasta'
			],
		];
	}




	public function buscar(){

		$query = new Query();
		$queryObjeto = null;
		$sql = [];
		$tabla = '';
		
		switch( $this->tipoListado ){

    		case 'asignacion': 
				$tabla = 'v_objeto_item';
				
				$sql = [ 
					'trib_nom', 'obj_id', 'obj_nom', 'subcta', 'item_nom', "(substr(perdesde::text, 1, 4) || '-' || substr(perdesde::text, 5, 3)) AS perdesdeguion, orden,trib_id,item_id,perdesde", 
					"(substr(perhasta::text, 1, 4) || '-' || substr(perhasta::text, 5, 3)) AS perhastaguion", 'expe' 
				];
								
				break;
				
			case 'excepcion': 
				$tabla = 'v_ctacte_excep';
				
				$sql = [ 
					'trib_nom', 'obj_id', 'obj_nom', 'subcta', 'tipo_nom', 'anio', 'cuota', 'expe', 'excep_id' 
				];
								
				break;	
			
			case 'inscripcion': 
				$tabla = 'v_objeto_trib';
				
				$sql = [ 
					'trib_nom', 'obj_id', '(nombre) as obj_nom', 'cat_nom', "(substr(perdesde::text, 1, 4) || '-' || substr(perdesde::text, 5, 3)) AS perdesdeguion,trib_id,perdesde", 
					"(substr(perhasta::text, 1, 4) || '-' || substr(perhasta::text, 5, 3)) AS perhastaguion", 'expe' 
				];
								
				break;	

			case 'condona': 
				$tabla = 'v_ctacte_cambioest';
				
				$sql = [ 
					'trib_nom', 'obj_id', 'obj_nom', "(substr(perdesde::text, 1, 4) || '-' || substr(perdesde::text, 5, 3)) AS perdesdeguion", 
					"(substr(perhasta::text, 1, 4) || '-' || substr(perhasta::text, 5, 3)) AS perhastaguion", 'expe', 'trib_id', 'perdesde' 
				];
								
				break;			
				

    	}


		if( $this->objeto_desde != null ){

			$this->objeto_desde = utb::getObjeto( $this->tipo_objeto, $this->objeto_desde );
			$this->objeto_hasta	= utb::getObjeto( $this->tipo_objeto, $this->objeto_hasta );

			$queryObjeto = ( new Query() )->select( 'obj_id' )
				->from( $tabla)
				->filterWhere([ 'between', 'obj_id', $this->objeto_desde, $this->objeto_hasta ]);
		}
		
		$perdesde = null;
		$perhasta = null;
		
		if ( $this->anio_desde != 0 )
			$perdesde = $this->anio_desde * 1000 + $this->mes_desde;
			
		if ( $this->anio_hasta != 0 )
			$perhasta = $this->anio_hasta * 1000 + $this->mes_hasta;	
		
		$query	->select( $sql )
				->from( $tabla )
				->filterWhere([ 'trib_id' => $this->tributo ])
                ->andFilterWhere(['item_id' => $this->item ])
				->andFilterWhere([ 'obj_id' => $queryObjeto ])
                ->andFilterWhere(['between', in_array($this->tipoListado, ['asignacion', 'inscripcion', 'condona']) ? 'perdesde' : "(anio*1000+cuota)", $perdesde, $perhasta ])
				->andFilterWhere(['between', 'fchmod', $this->fchmodif_desde, $this->fchmodif_hasta ])
				->andFilterWhere(['between', 'fchalta', $this->fchalta_desde, $this->fchalta_hasta ])
                ->andFilterWhere(['expe' => $this->expediente ])
				->andFilterWhere(['tipo' => $this->tipo_cuenta ])
				;

        return $query;

	}

	public function validar(){
		return $this->validate();
	}

	public function pk(){
		return 'obj_id';
	}

    /*public function sort(){

        return [

            'attributes' => [

                'plan_id',
                'obj_id',
            ],

			'defaultOrder'	=> [
				'plan_id' => SORT_ASC
			],
        ];
    }*/
	
	public function titulo(){
		
		return "Listado de " . ucwords($this->tipoListado);
	}

	public function permiso(){
		return 3420;
	}
}

?>
