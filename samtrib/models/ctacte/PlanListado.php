<?php
namespace app\models\ctacte;

use Yii;

use yii\db\Query;
use app\utils\db\utb;
use app\models\Listado;
use app\utils\db\Fecha;
use app\models\presup\Ejer;

class PlanListado extends Listado{

	//Número de Convenio
	public $numero_desde;
	public $numero_hasta;

    //Número de Convenio Anterior
	public $numero_ant_desde;
	public $numero_ant_hasta;

    //Objeto
    public $objeto_desde;
    public $objeto_hasta;
    public $objeto_tipo;

    //Contribuyente
    public $contribuyente;

    //Responsable
    public $responsable;

    //Tipo Convenio
    public $tipo;

    //Forma de Pago
    public $forma_pago;

    //Tipo de Origen
    public $origen;

    //Fecha Alta
    public $fecha_alta_desde;
    public $fecha_alta_hasta;

    //Fecha Baja
    public $fecha_baja_desde;
    public $fecha_baja_hasta;

    //Fecha Imputación
    public $fecha_imputa_desde;
    public $fecha_imputa_hasta;

    //Fecha Decae
    public $fecha_decae_desde;
    public $fecha_decae_hasta;

    //Cuota
    public $cuotas_desde;
    public $cuotas_hasta;

    //Monto de Cuota
    public $monto_cuotas_desde;
    public $monto_cuotas_hasta;

    //Interés
    public $interes_desde;
    public $interes_hasta;

    //Cuota Atrasada
    public $cuota_atrasada_desde;
    public $cuota_atrasada_hasta;

    //Estado
    public $estado;

    //Caja
    public $caja;

    //Con el Tributo
    public $con_tributo;

    //Con quitas especiales
    public $quita_especial;

    //con cambio de fecha de consolidación de interés
    public $cambio_fecha;

    //El responsable coincide con el titular del objeto
    public $coincide_con_titular;



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
				'objeto_desde', 'objeto_hasta', 'objeto_tipo', 'contribuyente', 'responsable',
                'contribuyente', 'fecha_alta_desde', 'fecha_alta_hasta', 'fecha_baja_desde',
                'fecha_baja_hasta', 'fecha_imputa_desde', 'fecha_imputa_hasta',
                'fecha_decae_desde', 'fecha_decae_hasta', 'estado',
			],
			'string',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[
				'numero_desde', 'numero_hasta', 'numero_ant_desde', 'numero_ant_hasta', 'tipo', 'forma_pago',
                'origen', 'cuotas_desde', 'cuotas_hasta', 'cuota_atrasada_desde', 'cuota_atrasada_hasta',
                'caja', 'con_tributo', 'quita_especial', 'cambio_fecha', 'coincide_con_titular',
			],
			'integer',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[
				'monto_cuotas_desde', 'monto_cuotas_hasta', 'interes_desde', 'interes_hasta',
			],
			'number',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		return $ret;
	}

	public function scenarios(){

		return [
			Listado::SCENARIO_BUSCAR => [

				'numero_desde', 'numero_hasta', 'numero_ant_desde', 'numero_ant_hasta', 'objeto_desde', 'objeto_hasta', 'objeto_tipo',
                'contribuyente', 'responsable', 'tipo', 'forma_pago', 'origen', 'fecha_alta_desde', 'fecha_alta_hasta', 'fecha_baja_desde',
                'fecha_baja_hasta', 'fecha_imputa_desde', 'fecha_imputa_hasta', 'fecha_decae_desde', 'fecha_decae_hasta', 'cuotas_desde',
                'cuotas_hasta', 'monto_cuotas_desde', 'monto_cuotas_hasta', 'interes_desde', 'interes_hasta', 'cuota_atrasada_desde',
                'cuota_atrasada_hasta', 'estado', 'caja', 'con_tributo',  'quita_especial', 'cambio_fecha', 'coincide_con_titular',

			]
		];
	}

	public function attributeLabels(){

		return [

            'quita_especial'        => 'Con Quitas Especiales',
            'cambio_fecha'          => 'Con Cambio de fecha de Consolidación de Intereses',
            'coincide_con_titular'  => 'El Responsable coincide con el titular del objeto',

		];
	}

	public function buscar(){

		$query		      	    = new Query();
		$queryObjeto 		    = null;
		$queryPeriodo 		    = null;
        $queryFechaAlta 	    = null;
        $queryFechaBaja         = null;
        $queryFechaImputa       = null;
        $queryFechaDecae        = null;
        $queryTributo           = null;
        $queryQuitaEspecial     = null;
        $queryFechaConsolida    = null;
        $queryRespTitular       = null;

		if( $this->objeto_desde != null ){

			$this->objeto_desde = utb::getObjeto( $this->objeto_tipo, $this->objeto_desde );
			$this->objeto_hasta	= utb::getObjeto( $this->objeto_tipo, $this->objeto_hasta );

			$queryObjeto = ( new Query() )->select( 'plan_id' )
				->from( PlanListado::tableName() )
				->filterWhere([ 'between', 'obj_id', $this->objeto_desde, $this->objeto_hasta ]);
		}

		if( $this->fecha_alta_desde != null ){

			$queryFechaAlta	= ( new Query() )->select( 'plan_id' )
				->from( PlanListado::tableName() )
				->where( "fchalta::date BETWEEN '$this->fecha_alta_desde' AND '$this->fecha_alta_hasta'" );

		}

        if( $this->fecha_baja_desde != null ){

			$queryFechaBaja	= ( new Query() )->select( 'plan_id' )
				->from( PlanListado::tableName() )
				->where( "fchbaja::date BETWEEN '$this->fecha_baja_desde' AND '$this->fecha_baja_hasta'" );

		}

        if( $this->fecha_imputa_desde != null ){

			$queryFechaImputa	= ( new Query() )->select( 'plan_id' )
				->from( PlanListado::tableName() )
				->where( "fchimputa::date BETWEEN '$this->fecha_imputa_desde' AND '$this->fecha_imputa_hasta'" );

		}

        if( $this->fecha_decae_desde != null ){

			$queryFechaDecae	= ( new Query() )->select( 'plan_id' )
				->from( PlanListado::tableName() )
				->where( "fchdecae::date BETWEEN '$this->fecha_decae_desde' AND '$this->fecha_decae_hasta'" );

		}

        if( $this->con_tributo != null ){

            $sql = "select distinct plan_id from plan_periodo p left join ctacte c on p.ctacte_id=c.ctacte_id where c.trib_id= $this->con_tributo";

            $queryTributo = Yii::$app->db->createCommand( $sql )->queryAll();
        }

		if( $this->quita_especial > 0 ){

			$cond =	"( ( DescNominal > 0 and DescNominal <> tDescNominal) or (DescInteres > 0 and DescInteres<>tDescInteres) " .
					" or (DescMulta > 0 and DescMulta<>tDescMulta) or (Interes > 0  and Interes <> tInteres))";

			$queryQuitaEspecial = ( new query() )->select( 'plan_id' )
				->from( PlanListado::tableName() )
				->where( $cond );

		}

		if( $this->cambio_fecha > 0 ){

			$cond =	"to_char (FchAlta,'dd/mm/YYYY')  <> to_char (FchMod,'dd/dm/YYYY')";

			$queryFechaConsolida = ( new query() )->select( 'plan_id' )
				->from( PlanListado::tableName() )
				->where( $cond );

		}

		if( $this->coincide_con_titular > 0 ){

			$cond =	"upper(num_nom)=upper(resp)";

			$queryRespTitular = ( new query() )->select( 'plan_id' )
				->from( PlanListado::tableName() )
				->where( $cond );

		}

		$sql = [

            'plan_id',
            'tplan',
            'tplan_nom',
            'obj_id',
            'resp',
            'nominal',
            'accesor',
            'multa',
            'financia',
            'est_nom',

		];

		$query	->select( $sql )
				->from( PlanListado::tableName() )
				->filterWhere([ 'between', 'plan_id', $this->numero_desde, $this->numero_hasta ])
                ->andFilterWhere([ 'between', 'planant', $this->numero_ant_desde, $this->numero_ant_hasta ])
				->andFilterWhere([ 'plan_id' => $queryObjeto ])
                ->andFilterWhere([ 'ilike', 'num_nom', $this->contribuyente ])
                ->andFilterWhere([ 'ilike', 'resp', $this->responsable ])
                ->andFilterWhere([ 'tplan' => $this->tipo ])
                ->andFilterWhere([ 'tpago' => $this->forma_pago ])
                ->andFilterWhere([ 'origen' => $this->origen ])
                ->andFilterWhere([ 'plan_id' => $queryFechaAlta ])
                ->andFilterWhere([ 'plan_id' => $queryFechaBaja ])
                ->andFilterWhere([ 'plan_id' => $queryFechaImputa ])
                ->andFilterWhere([ 'plan_id' => $queryFechaDecae ])
                ->andFilterWhere([ 'between', 'cuotas', $this->cuotas_desde, $this->cuotas_hasta ])
                ->andFilterWhere([ 'between', 'montocuo', $this->monto_cuotas_desde, $this->monto_cuotas_hasta ])
                ->andFilterWhere([ 'between', 'interes', $this->interes_desde, $this->interes_hasta ])
                ->andFilterWhere([ 'between', 'cuotasatrasadas', $this->cuota_atrasada_desde, $this->cuota_atrasada_hasta ])
				->andFilterWhere([ 'est' => $this->estado ])
                ->andFilterWhere([ 'caja_id' => $this->caja ])
                ->andFilterWhere([ 'plan_id' => $queryTributo ])
				->andFilterWhere([ 'plan_id' => $queryQuitaEspecial ])
				->andFilterWhere([ 'plan_id' => $queryFechaConsolida ])
				->andFilterWhere([ 'plan_id' => $queryRespTitular ]);

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

                'plan_id',
                'obj_id',
            ],

			'defaultOrder'	=> [
				'plan_id' => SORT_ASC
			],
        ];
    }
}

?>
