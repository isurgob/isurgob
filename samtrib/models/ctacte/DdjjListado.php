<?php
namespace app\models\ctacte;

use Yii;

use yii\db\Query;
use app\utils\db\utb;
use app\models\Listado;
use app\utils\db\Fecha;
use app\models\presup\Ejer;

class DdjjListado extends Listado{

	//Número de referencia
	public $numero_desde;
	public $numero_hasta;

    //Objeto
    public $objeto_desde;
    public $objeto_hasta;
    public $objeto_tipo;

	//Base imponible
	public $base_imponible_desde;
	public $base_imponible_hasta;

	//Monto
	public $monto_desde;
	public $monto_hasta;

	//Periodo
	public $desde_anio;
	public $desde_cuota;
	public $hasta_anio;
	public $hasta_cuota;

	//Nombre Comercio
	public $nombre;

	//Titular
	public $titular;

	//Tributo Principal
	public $tributo;

	//Grupo rubro
	public $grupo_rubro;

	//Nombre de rubro
	public $rubro_nom;

	//Contador
	public $contador;

	//Fecha Presentación
	public $fecha_presentacion_desde;
	public $fecha_presentacion_hasta;

	//Tipo
	public $tipo;

	//Estado DJ
	public $estado_dj;

	//Estado Cuenta Corriente
	public $estado_ctacte;

	//Fecha Pago
	public $fecha_pago_desde;
	public $fecha_pago_hasta;

	public $fiscalizada;
	public $presentacion_atrasada;
	public $con_bonificacion;
	public $bonificacion_con_deuda;
	public $tomaron_saldo_y_tenian;
	public $tomaron_saldo_y_no_tenian;

	public function __construct(){
		parent::__construct();
	}

	public static function tableName(){

		return 'v_dj';
	}

	public function rules(){

		$ret = [];

		$ret[] = [
			[
                'objeto_desde', 'objeto_hasta', 'nombre', 'titular', 'grupo_rubro',
				'rubro_nom', 'fecha_presentacion_desde', 'fecha_presentacion_hasta', 'estado_dj',
				'estado_ctacte', 'fecha_pago_desde', 'fecha_pago_hasta',
			],
			'string',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[
				'desde_anio', 'desde_cuota', 'hasta_anio', 'hasta_cuota', 'numero_desde', 'numero_hasta',
				'objeto_tipo', 'tributo', 'tipo', 'fiscalizada', 'presentacion_atrasada', 'con_bonificacion',
				'bonificacion_con_deuda', 'tomaron_saldo_y_tenian', 'tomaron_saldo_y_no_tenian',
			],
			'integer',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		$ret[] = [
			[
				'base_imponible_desde', 'base_imponible_hasta', 'monto_desde', 'monto_hasta',
			],
			'number',
			'on' => Listado::SCENARIO_BUSCAR,
		];

		return $ret;
	}

	public function scenarios(){

		return [
			Listado::SCENARIO_BUSCAR => [
				'numero_desde', 'numero_hasta', 'tributo', 'objeto_desde', 'objeto_hasta',
                'objeto_tipo', 'base_imponible_desde', 'base_imponible_hasta', 'monto_desde', 'monto_hasta',
				'desde_anio', 'desde_cuota', 'hasta_anio', 'hasta_cuota', 'numero_desde', 'nombre', 'titular',
				'grupo_rubro', 'rubro_nom', 'fecha_presentacion_desde', 'fecha_presentacion_hasta', 'tipo',
				'estado_dj', 'estado_ctacte', 'fecha_pago_desde', 'fecha_pago_hasta', 'fiscalizada',
				'presentacion_atrasada', 'con_bonificacion', 'bonificacion_con_deuda',
				'tomaron_saldo_y_tenian', 'tomaron_saldo_y_no_tenian',
			]
		];
	}

	public function attributeLabels(){

		return [

			'numero_desde' 				=> 'Nº de DDJJ',
			'numero_hasta' 				=> 'Nº de DDJJ',
			'presentacion_atrasada'		=> 'Presentación atrasada',
			'con_bonificacion'			=> 'Con bonificación',
			'bonificacion_con_deuda'	=> 'Bonificación con deuda',
			'tomaron_saldo_y_tenian'	=> 'Tomaron saldo y tenían',
			'tomaron_saldo_y_no_tenian'	=> 'Tomaron saldo y no tenían',



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

		$query		      	= new Query();
		$queryObjeto 		= null;
		$queryPeriodo 		= null;
		$queryGrupoRubro	= null;
		$queryRubroNombre 	= null;
		$queryContador		= null;
		$queryFechaPresenta	= null;
		$queryFechaPago 	= null;
		$queryAtrasada		= null;
		$queryBonificacion	= null;
		$queryConDeuda		= null;
		$queryTenianSaldo	= null;
		$queryNoTenianSaldo	= null;

		if( $this->desde_anio != null ){

			$desde = $this->desde_anio * 1000 + $this->desde_cuota;
			$hasta = $this->hasta_anio * 1000 + $this->hasta_cuota;

			$queryPeriodo = ( new Query() )->select( 'dj_id' )
				->from( 'ddjj' )
				->where( "anio*1000+cuota >= $desde AND anio*1000+cuota <= $hasta" );
		}

		if( $this->grupo_rubro != null ){

			$queryRubro = ( new Query() )->select( 'rubro_id')
				->from( 'rubro' )
				->filterWhere([ 'grupo' => $this->grupo_rubro ]);

			$queryGrupoRubro = ( new Query() )->select( 'obj_id' )
				->from( 'objeto_rubro' )
				->filterWhere([ 'rubro_id' => $queryRubro ]);

		}

		if( $this->rubro_nom != null ){

			$queryRubroNombre = ( new Query() )->select( 'dj_id' )
				->from( 'v_dj_rubro' )
				->filterWhere([ 'ilike', 'rubro_nom', $this->rubro_nom ]);
		}

		if( $this->contador != null ){

			$queryContador = ( new Query() )->select( 'obj_id' )
				->from( 'persona' )
				->filterWhere([ 'contador' => $this->contador ]);
		}

		if( $this->fecha_presentacion_desde != null ){

			$queryFechaPresenta	= ( new Query() )->select( 'dj_id' )
				->from( DdjjListado::tableName() )
				->where( "fchpresenta::date BETWEEN '$this->fecha_presentacion_desde' AND '$this->fecha_presentacion_hasta'" );

		}

		if( $this->fecha_pago_desde != null ){

			$queryFechaPago	= ( new Query() )->select( 'dj_id' )
				->from( DdjjListado::tableName() )
				->where( "fchpago::date BETWEEN '$this->fecha_pago_desde' AND '$this->fecha_pago_hasta'" );

		}

		if( $this->presentacion_atrasada > 0 ){

			$queryAtrasada = ( new Query() )->select( 'dj_id' )
				->from( 'v_dj' )
				->where( 'fchpresenta > fchvenc' );

		}

		if( $this->con_bonificacion > 0 ){

			$sql = 	"SELECT dj_id FROM ddjj WHERE ctacte_id IN (select ctacte_id from ctacte_det c inner join cuenta t ON t.cta_id = c.cta_id WHERE t.tcta = 2)";

			$queryBonificacion = Yii::$app->db->createCommand( $sql )->queryAll();

			if( $queryBonificacion == null ){

				$queryBonificacion = 0;
			}

		}

		if( $this->bonificacion_con_deuda > 0 ){

			$sql = 	"SELECT dj_id FROM ddjj WHERE dj_id in ( select l.dj_id from ddjj_liq l where l.item_id=339)" .
					" and obj_id in (select c.obj_id from ctacte c where c.trib_id=23 and c.est in('D','X','J') and c.fchvenc<c.fchemi)";

			$queryConDeuda = Yii::$app->db->createCommand( $sql )->queryAll();

			if( $queryConDeuda == null ){

				$queryConDeuda = 0;
			}

		}

		if( $this->tomaron_saldo_y_tenian > 0 ){

			$sql = 	"SELECT dj_id FROM ddjj WHERE dj_id in ( select l.dj_id from ddjj_liq l where l.item_id=444)" .
					" and obj_id in (select c.obj_id from ctacte c where c.trib_id=23 and c.est='P' and c.nominalcub>c.nominal)";

			$queryTenianSaldo = Yii::$app->db->createCommand( $sql )->queryAll();

			if( $queryTenianSaldo == null ){

				$queryTenianSaldo = 0;
			}

		}

		if( $this->tomaron_saldo_y_no_tenian > 0 ){

			$sql = 	"SELECT dj_id FROM ddjj WHERE dj_id in ( select l.dj_id from ddjj_liq l where l.item_id=444)" .
					" and obj_id not in (select c.obj_id from ctacte c where c.trib_id=23 and c.est='P' and c.nominalcub>c.nominal)";

			$queryNoTenianSaldo = Yii::$app->db->createCommand( $sql )->queryAll();

			if( $queryNoTenianSaldo == null ){

				$queryNoTenianSaldo = 0;
			}
		}

		$sql = [

            'dj_id',
            'trib_nom',
            'obj_id',
            'subcta',
            'obj_nom',
            'est',
            'anio',
            'cuota',
            'orden_nom',
            'base',
            'monto',
            'multa',
            'fchpresenta',
		];

		$query	->select( $sql )
				->from( DdjjListado::tableName() )
				->filterWhere([ 'between', 'dj_id', $this->numero_desde, $this->numero_hasta ])
		 		->andFilterWhere([ 'between', 'obj_id', $this->objeto_desde, $this->objeto_hasta ])
				->andFilterWhere([ 'dj_id' => $queryPeriodo ])
				->andFilterWhere([ 'between', 'base', $this->base_imponible_desde, $this->base_imponible_hasta ])
				->andFilterWhere([ 'between', 'monto', $this->monto_desde, $this->monto_hasta ])
				->andFilterWhere([ 'ilike', 'obj_nom', $this->nombre ])
				->andFilterWhere([ 'ilike', 'num_nom', $this->titular ])
				->andFilterWhere([ 'trib_id' => $this->tributo ])
				->andFilterWhere([ 'obj_id' => $queryGrupoRubro ])
				->andFilterWhere([ 'dj_id' => $queryRubroNombre ])
				->andFilterWhere([ 'obj_id' => $queryContador ])
				->andFilterWhere([ 'dj_id' => $queryFechaPresenta ])
				->andFilterWhere([ 'tipo' => $this->tipo ])
				->andFilterWhere([ 'est' => $this->estado_dj ])
				->andFilterWhere([ 'estctacte' => $this->estado_ctacte ])
				->andFilterWhere([ 'dj_id' => $queryFechaPago ])
				->andFilterWhere([ 'fiscaliza' => $this->fiscaliza ])
				->andFilterWhere([ 'dj_id' => $queryAtrasada ])
				->andFilterWhere([ 'dj_id' => $queryBonificacion ])
				->andFilterWhere([ 'dj_id' => $queryConDeuda ])
				->andFilterWhere([ 'dj_id' => $queryTenianSaldo ])
				->andFilterWhere([ 'dj_id' => $queryNoTenianSaldo ]);

        return $query;

	}

	public function validar(){
		return $this->validate();
	}

	public function pk(){
		return 'dj_id';
	}

    public function sort(){

        return [

            'attributes' => [

                'dj_id',
                'obj_id' 	=> [
					'asc'	=> [ 'obj_id' => SORT_ASC, 'anio' => SORT_ASC, 'cuota' => SORT_ASC ],
					'desc'	=> [ 'obj_id' => SORT_DESC, 'anio' => SORT_ASC, 'cuota' => SORT_ASC ],
				],
				'base',
				'monto',
            ],

			'defaultOrder'	=> [
				'dj_id' => SORT_ASC
			],
        ];
    }
	
	public function titulo(){
		return "Listado de DDJJ";
	}
	public function permiso(){
		return 3330;  
	}
}

?>
