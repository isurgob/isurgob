<?php
namespace app\models\ctacte;

use Yii;

use yii\db\Query;
use app\utils\db\utb;
use app\models\Listado;
use app\utils\db\Fecha;

class ListadoCMejoraObra extends Listado{

	//Número de obra
	public $numero_desde;
	public $numero_hasta;
	
	//Nombre
	public $nombre;

   //Estado
	public $est;

    //Tipo de Obra
    public $tobra;

    //Item Básico
    public $item_basico;

	//Item Sellado
	public $item_sellado;

	//Valor Metro
	public $metros_desde;
	public $metros_hasta;

	//Total Frente
	public $frente_desde;
	public $frente_hasta;

	//Valor Total
	public $total_desde;
	public $total_hasta;
	
	//Sellado 
	public $sellado_desde;
	public $sellado_hasta;
	
	// Fecha Inicio
	public $fecha_inicio_desde;
	public $fecha_inicio_hasta;
	
	// Fecha Fin
	public $fecha_fin_desde;
	public $fecha_fin_hasta;

	public function __construct(){
		parent::__construct();
	}

	public static function tableName(){

		return 'v_mej_obra';
	}

	public function rules(){

		$ret = [];

		$ret[] = [
			[
                'nombre', 'est', 'tobra', 'fecha_inicio_desde', 'fecha_inicio_hasta',
				'fecha_fin_desde', 'fecha_fin_hasta'
			],
			'string',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[
				'numero_desde', 'numero_hasta', 'item_basico', 'item_sellado'
			],
			'integer',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[
				'metros_desde','metros_hasta','frente_desde','frente_hasta','total_desde','total_hasta','sellado_desde','sellado_hasta'
			],
			'number',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		return $ret;
	}

	public function scenarios(){

		return [
			Listado::SCENARIO_BUSCAR => [
				'nombre', 'est', 'tobra', 'fecha_inicio_desde', 'fecha_inicio_hasta','fecha_fin_desde', 'fecha_fin_hasta',
				'numero_desde', 'numero_hasta', 'item_basico', 'item_sellado',
				'metros_desde','metros_hasta','frente_desde','frente_hasta','total_desde','total_hasta','sellado_desde','sellado_hasta'
			]
		];
	}

	public function attributeLabels(){

		return [

			'numero_desde' 		=> 'Nº de obra desde',
			'numero_hasta' 		=> 'Nº de obra hasta',

		];
	}

	public function beforeValidate(){

		return true;
	}

	public function buscar(){

		$query		      	= new Query();
		
		$sql = [
            'obra_id',
            'nombre',
            'tobra_nom',
            'valormetro',
            'totalfrente',
            'valortotal',
            'totalsupafec',
            'fijo',
			'bonifobra',
            "est_nom"
		];

		$query	->select( $sql )
				->from( ListadoCMejoraObra::tableName() )
				->filterWhere([ 'between', 'obra_id', $this->numero_desde, $this->numero_hasta ])
				->andFilterWhere([ 'ilike', 'nombre', $this->nombre ])
				->andFilterWhere([ 'est' => $this->est ])
				->andFilterWhere([ 'tobra' => $this->tobra ])
				->andFilterWhere([ 'itembasico' => $this->item_basico ])
				->andFilterWhere([ 'itemsellado' => $this->item_sellado ])
				->andFilterWhere([ 'between', 'valormetro', $this->metros_desde, $this->metros_hasta ])
				->andFilterWhere([ 'between', 'totalfrente', $this->frente_desde, $this->frente_hasta ])
				->andFilterWhere([ 'between', 'valortotal', $this->total_desde, $this->total_hasta ])
				->andFilterWhere([ 'between', 'sellado', $this->sellado_desde, $this->sellado_hasta ])
				->andFilterWhere([ 'between', 'fchini', $this->fecha_inicio_desde, $this->fecha_inicio_hasta ])
				->andFilterWhere([ 'between', 'fchfin', $this->fecha_fin_desde, $this->fecha_fin_hasta ]);


        return $query;
	}

	public function validar(){
		return $this->validate();
	}

	public function pk(){
		return 'obra_id';
	}

    public function sort(){

        return [
            'attributes' => [
                'obra_id' => [ SORT_ASC ],
                'nombre' => [ SORT_ASC ],
            ],
        ];
    }
}

?>
