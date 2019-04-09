<?php

namespace app\models\objeto;

use Yii;
use app\utils\db\utb;
use app\models\ctacte\Trib;
use app\utils\helpers\DBException;

/**
 * This is the model class for table "objeto_rubro".
 *
 * @property string $obj_id
 * @property integer subcta
 * @property integer $rubro_id
 * @property integer $perdesde
 * @property integer $perhasta
 * @property integer $fiscaliza
 * @property integer $cant
 * @property integer $tipo
 * @property string $est
 * @property string $expe
 * @property string $obs
 * @property number $porc
 *
 * @property integer $adesde
 * @property integer $cdesde
 * @property integer $ahasta
 * @property integer $chasta
 *
 *
 */
class ComerRubro extends \yii\db\ActiveRecord
{
	//establece que el rubro esta creado en memoria
	public $temp;

	public $adesde;
	public $cdesde;
	public $ahasta;
	public $chasta;

	public $esPrincipal;

	const STR_PRINCIPAL 	= "Principal";
	const STR_SECUNDARIO 	= "Secundario";

	public function __construct(){
		parent::__construct();

		$this->temp = true;
		$this->esPrincipal = false;
		$this->tipo = 2;
		$this->porc = 100;
		$this->cant = 0;
		$this->nomen_id= '';
		$this->nomen_nom= '';
		$this->ahasta= 9999;
		$this->chasta= 999;
		$this->fiscaliza = 0;
	}

    /**
     * @inheritdoc
     *
     * se utiliza la vista v_objeto_rubro porque todos los datos necesarios estan ahi y de esta manera no es necesario hacer joins
     */
    public static function tableName()
    {
        return 'v_objeto_rubro';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $ret = [];

        /**
         * CAMPOS REQUERIDOS
         */

		/**
		 * no se requiere el codigo de objeto en:
		 *	+ insert -> se puede dar el caso de que se este creando el objeto y todavía no se cuenta con el codigo.
		 *	+ update -> se peude dar el caso de que se modifique el objeto y todavía no se haya haya grabado.
		 */

		//no se debe requerir
		$ret[] = [
				'obj_id',
				'required',
				'on' => [ 'delete' ]
				];


		$ret[] = [
				['rubro_id', 'adesde', 'cdesde'],
				'required',
				'on' => ['insert', 'update', 'delete']
				];

		$ret[]= [
				'fiscaliza',
				'required',
				'on' => ['insert', 'update']
				];

		$ret[] = [
				'nomen_id',
				'required',
				'on' => ['insert', 'update']
				];
		/**
		 * FIN CAMPOS REQUERIDOS
		 */

		/**
		 * TIPO Y RANGO DE DATOS
		 */
		$ret[] = [
				'nomen_id',
				'string',
				'min' => 1,
				'on' => ['insert', 'update']
				];

		$ret[] = [
				['adesde', 'ahasta'],
				'integer',
				'min' => 1000,
				'max' => 9999,
				'on' => ['insert', 'update', 'delete']
				];

		$ret[] = [
				['cdesde', 'chasta'],
				'integer',
				'min' => 0,
				'max' => 999,
				'on' => ['insert', 'update', 'delete']
				];

		$ret[] = [
				['cant', 'subcta'],
				'integer',
				'min' => 0,
				'on' => ['insert', 'update', 'delete']
				];

		$ret[] = [
				'expe',
				'string',
				'max' => 12,
				'on' => ['insert', 'update']
				];

		$ret[] = [
				'obs',
				'string',
				'max' => 100,
				'on' => ['insert', 'update']
				];

		$ret[] = [
				'porc',
				'number',
				'min' => 0,
				'max' => 100,
				'on' => ['insert', 'update']
				];

		$ret[] = [
				'rubro_id',
				'string',
				'min' => 8,
				'max' => 8,
				'on' => ['insert', 'update'],
				'message' => 'El rubro ingresado no existe'
				];

		$ret[] = [
			'nomen_nom',
			'string',
			'on' => ['insert','update'],
		];

		/**
		 * FIN TIPO Y RANGO DE DATOS
		 */

		/**
		 * VALORES POR DEFECTO
		 */
		$ret[] = [
				'obj_id',
				'default',
				'value' => 'C0000000',
				'on' => ['insert']
				];

		$ret[] = [
				'ahasta',
				'default',
				'value' => 9999,
				'on' => ['insert', 'update', 'delete']
				];

		$ret[] = [
				'chasta',
				'default',
				'value' => 999,
				'on' => ['insert', 'update', 'delete']
				];

		$ret[] = [
				'cant',
				'default',
				'value' => 0,
				'on' => ['insert', 'update']
				];
		//
		// $ret[] = [
		// 		'subcta',
		// 		'default',
		// 		'value' => 0,
		// 		'on' => ['insert', 'update', 'delete']
		// 		];

		$ret[] = [
				'est',
				'default',
				'value' => 'A',
				'on' => ['insert', 'update']
				];

		$ret[] = [
				'fiscaliza',
				'default',
				'value' => 0,
				'on' => ['insert', 'update']
				];

		$ret[] = [
				'tipo',
				'default',
				'value' => 0,
				'on' => ['insert', 'update']
				];

		$ret[] = [
				'porc',
				'default',
				'value' => 100,
				'on' => ['insert', 'update']
				];
		/**
		 * FIN VALORES POR DEFECTO
		 */

		$ret[] = [
				'rubro_id',
				'existeRubro',
				'skipOnError' => true,
				'on' => ['insert', 'update']
				];

		// $ret[] = [
		// 		'adesde',
		// 		'vigente',
		// 		'on' => ['insert', 'update' ],
		// 		];

		return $ret;
    }

    public function scenarios(){
    	return [
    		'insert' => ['obj_id', 'rubro_id', 'rubro_nom', 'perdesde', 'perhasta', 'fiscaliza', 'cant', 'tipo', 'est', 'expe', 'nomen_id',
						'obs', 'porc', 'adesde', 'cdesde', 'ahasta', 'chasta', 'subcta', 'nomen_nom', 'esPrincipal', 'nperdesde', 'nperhasta'],
    		'update' => ['obj_id', 'rubro_id', 'rubro_nom', 'perdesde', 'perhasta', 'fiscaliza', 'cant', 'tipo', 'est', 'expe', 'nomen_id',
						'obs', 'porc', 'adesde', 'cdesde', 'ahasta', 'chasta', 'subcta', 'nomen_nom', 'esPrincipal', 'nperdesde', 'nperhasta'],
    		'delete' => ['obj_id', 'rubro_id', 'perdesde', 'perhasta', 'fiscaliza', 'subcta', 'adesde', 'cdesde', 'ahasta', 'chasta', 'nperdesde', 'nperhasta'],

    		// 'select' => ['subcta','obj_id' ,'trib_id','trib_nom','trib_cantanio','rubro_id','rubro_nom','perdesde','adesde','cdesde','perhasta',
    		// 			 'ahasta','chasta' ,'cant','tipo','expe','obs','est'],
    	];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'obj_id' => 'Código de objeto',
            'subcta' => 'Nº Suc.',
            'rubro_id' => 'Rubro',
            'cant' => 'Cant.',
            'adesde' => 'Año desde',
            'cdesde' => 'Cuota desde',
            'ahasta' => 'Año hasta',
            'chasta' => 'Cuota hasta',
            'porc' => 'Porc.',
            'expe' => 'Expe.',
            'obs' => 'Obs.',
            'est' => 'Est.',
            'Tipo' => 'Rubro Principal',
            'nomen_id' => 'Nomeclador'
        ];
    }

	public function afterFind(){

		$this->adesde = substr( $this->nperdesde, 0, 4 );
		$this->cdesde = substr( $this->nperdesde, 4, 3 );
		$this->ahasta = substr( $this->nperhasta, 0, 4 );
		$this->chasta = substr( $this->nperhasta, 4, 3 );

		$this->esPrincipal = $this->tipo == 1;

		$this->cargarNombre();

        $this->fiscaliza            = 0;

		$this->temp = false;	//Los rubros que se cargar de la BD, se deben volver a grabar.
	}

	/**
	 * Función que se utiliza obtener los tributos.
	 * @param integer $ipoObjeto Identificador del tipo de objeto.
	 */
	public static function getNomecladores( $tipoObjeto = 3 ){

		return utb::getAux( 'rubro_tnomen', 'nomen_id', 'nombre', 3, "tobj = $tipoObjeto");
	}

	private function cargarNombre(){

		$sql= "Select nombre From rubro_tnomen Where nomen_id = '$this->nomen_id'";

		$this->nomen_nom = Yii::$app->db->createCommand($sql)->queryScalar();
	}

	public function beforeValidate(){

		if($this->adesde != null && $this->cdesde != null)
			$this->nperdesde = intval($this->adesde) * 1000 + intval($this->cdesde);

		if($this->ahasta != null && $this->chasta != null)
			$this->nperhasta = intval($this->ahasta) * 1000 + intval($this->chasta);
		else $this->nperhasta = 9999999;

		if(intval($this->tipo) == 1)
			$this->tipo_nom = self::STR_PRINCIPAL;
		else $this->tipo_nom = self::STR_SECUNDARIO;

		$this->perdesde = intval($this->nperdesde / 1000) . '-' . substr($this->nperdesde, 4);

		if($this->nperhasta > 0)
			$this->perhasta = intval($this->nperhasta / 1000) . '-' . substr($this->nperhasta, 4);

		$this->rubro_id = $this->rubro_id;
		$this->tipo = intval($this->tipo);

		$this->cargarNombre();

		return true;
	}

	/**
	 * Función que se utiliza para validar que el rubro ingresado exista.
	 */
	public function existeRubro(){

		if( $this->rubro_id == null ){
			$this->rubro_id = '';
		}

		$this->rubro_id = $this->rubro_id;

		$sql = "Select Exists (Select 1 From v_rubro Where rubro_id = '$this->rubro_id' And nomen_id = '$this->nomen_id')";
		$existe = Yii::$app->db->createCommand($sql)->queryScalar();

		if( !$existe ){
			$this->addError( $this->rubro_id, 'El rubro ingresado no existe.' );
		}
	}

	/**
	 * Obtiene el periodo vigente.
	 *
	 * @return int El periodo vigente
	 */
	public static function getPeriodoVigente(){

		$aactual = date( 'Y' );
		$mactual = str_pad( date('m'), 3, '0', STR_PAD_LEFT );

		$periodoActual = intval( $aactual . $mactual );

		return $periodoActual;
	}

	public function vigente(){

		$periodoActual = ComerRubro::getPeriodoVigente();

		if( $periodoActual < $this->nperdesde || $periodoActual > $this->nperhasta ){
			$this->addError( $this->adesde, 'El período no es válido.' );
		}

	}

	/**
	 * Función que se utiliza para dar de baja un rubro.
	 */
	public function darBaja( $periodoActual = 0 ){

		if($this->nperhasta > $periodoActual)
			$this->nperhasta = $periodoActual;

		$this->ahasta = intval($this->nperhasta / 1000);
		$this->cdesde = $this->nperhasta % 1000;
		$this->perhasta = intval($this->nperhasta / 1000) . '-' . substr($this->nperhasta, 4);

		$this->est = 'B';

	}

	public function grabar( $obj_id ){

		//se insertan el rubro
		$sql = "Insert Into objeto_rubro( obj_id, rubro_id, perdesde, perhasta, fiscaliza, subcta, cant, tipo, est, expe, obs, fchmod, usrmod) Values(" .
				"'$obj_id', '$this->rubro_id', $this->nperdesde, $this->nperhasta, $this->fiscaliza, 0, $this->cant, $this->tipo, '$this->est', '$this->expe'," .
				"'$this->obs', current_timestamp, " . Yii::$app->user->id . ")";

		try{
			Yii::$app->db->createCommand($sql)->execute();

		} catch(\Exception $e){

			$this->addError($this->obj_id, DBException::getMensaje($e));
			return false;
		}

		return true;
	}

	public function getRubros( $obj_id ){

		return ComerRubro::findAll([ 'obj_id' => $obj_id ]);
	}

	/**
	 * Función que se utiliza para obtener el modelo de un rubro.
	 * @param array $arrayRubros Arreglo de rubros.
	 * @param integer $rubro_id Identificador de rubro.
	 * @param integer $perdesde Período desde
	 */
	public static function getRubroSegunID( $arrayRubros = [], $rubro_id, $perdesde = 0 ){

		if( count( $arrayRubros ) > 0 ){

			foreach( $arrayRubros as $rubro ){

				if( $rubro['rubro_id'] == $rubro_id && $rubro[ 'nperdesde' ] == $perdesde && $rubro['est'] == 'A' ){
					return $rubro;
				}

			}
		}

		return ( new ComerRubro() );

	}

	/**
	 * Función que se utiliza para realizar el ABM de rubros.
	 * @param array $arregloRubros Arreglo de rubros.
	 * @param array $nuevoRubro Arreglo con el nuevo rubro.
	 * @param integer $action Identificador del tipo de acción.
	 */
	public static function ABMRubrosEnArreglo( &$arregloRubros, &$nuevoRubro, $action ){

		$rubros = $arregloRubros;

		switch( intVal( $action ) ){

			case 0:

				$nuevoRubro->setScenario( 'insert' );

				if( !$nuevoRubro->validate() ){
					return false;
				}

				if( $nuevoRubro->tipo == 1 ){

					$nuevoRubro->esPrincipal = true;

					$rubros = Comer::cambiarRubrosPrincipales( $rubros );

				} else {
					$nuevoRubro->esPrincipal = false;
				}

				//Verificar que no exista un rubro con el mismo ID que se encuentre vigente
				if( ComerRubro::verificarVigencia( $arregloRubros, $nuevoRubro ) ){

					$rubros[] = $nuevoRubro;

				} else {

					return false;
				}

				break;

			case 2:

				$aEliminar = -1;

				foreach( $rubros as $id => $array ){

					if( $array[ 'rubro_id' ] == $nuevoRubro[ 'rubro_id' ] ){
						$aEliminar = $id;

					}
				}

				if( $aEliminar != -1 ){

					/**
					 * Si el rubro se encuentra en la BD, se debe cambiar el estado a 'B'.
					 * Si el rubro está sólo cargado en memoria, se elimina.
					 */
					if( !$rubros[ $aEliminar ]->temp ){
						$rubros[ $aEliminar ]->darBaja( $rubros[ $aEliminar ]->nperhasta );

					} else {
						unset( $rubros[ $aEliminar ] );
					}

				}

				break;

			case 3:

				$nuevoRubro->setScenario( 'update' );

				if( !$nuevoRubro->validate() ){
					return false;
				}

				$aModificar = -1;

				foreach( $rubros as $id => $array ){

					if( $array[ 'rubro_id' ] == $nuevoRubro[ 'rubro_id' ] ){
						$aModificar = $id;
					}

				}

				if( $aModificar != -1 ){

					if( $nuevoRubro->tipo == 1 ){

						$nuevoRubro->esPrincipal = true;

						$rubros = Comer::cambiarRubrosPrincipales( $rubros );

					} else {
						$nuevoRubro->esPrincipal = false;
					}

					$rubros[ $aModificar ] = $nuevoRubro;
				}

				break;
		}

		$arregloRubros = $rubros;

		return true;
	}

	/**
	 * Función que se utiliza para validar que no se superpongan períodos en vigencia.
	 * @param array $arregloRubros Arreglo de rubros.
	 * @param array $nuevoRubro Arreglo con el nuevo rubro.
	 */
	public function verificarVigencia( $arregloRubros, $nuevoRubro ){

		if( count( $arregloRubros ) > 0 ){

			$periodoDesdeNuevoRubro = intVal( $nuevoRubro[ 'adesde' ] . str_pad( $nuevoRubro[ 'cdesde' ], 3, '0', STR_PAD_LEFT ) );
			$periodoHastaNuevoRubro = intVal( $nuevoRubro[ 'ahasta' ] . str_pad( $nuevoRubro[ 'chasta' ], 3, '0', STR_PAD_LEFT ) );

			foreach( $arregloRubros as $array ){

				if( $array[ 'rubro_id' ] == $nuevoRubro[ 'rubro_id' ] ){

					$periodoDesdeArray = intVal( $array[ 'adesde' ] . str_pad( $array[ 'cdesde' ], 3, '0', STR_PAD_LEFT ) );
					$periodoHastaArray = intVal( $array[ 'ahasta' ] . str_pad( $array[ 'chasta' ], 3, '0', STR_PAD_LEFT ) );

					if( $periodoDesdeNuevoRubro < $periodoHastaArray ){

						$nuevoRubro->addError( 'rubro_id', 'El período ingresado es menor a una vigencia ingresada.' );
						return false;

					} else if ( $periodoDesdeNuevoRubro < $periodoDesdeArray && $periodoHastaNuevoRubro > $periodoDesdeArray ){

						$nuevoRubro->addError( 'rubro_id', 'El período ingresado se superpone con otra vigencia.' );
						return false;
					}
				}
				
				$sql = "select count(*) from rubro_tnomen t where '$periodoDesdeNuevoRubro' BETWEEN t.perdesde and t.perhasta and '$periodoHastaNuevoRubro' BETWEEN t.perdesde and t.perhasta and nomen_id='".$nuevoRubro[ 'nomen_id' ]."'";
				if (  intVal(Yii::$app->db->createCommand($sql)->queryScalar()) == 0 ){
					$nuevoRubro->addError( 'rubro_id', 'La vigencia ingresada no esta dentro de la vigencia del Nomeclador. En el rubro ' . $nuevoRubro[ 'rubro_id' ] );
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Función que se utiliza para obtener los rubros según filtros.
	 * @param array $arrayRubros Arreglo de rubros.
	 * @param integer $dadosDeBaja Filtro para rubros "Dados de Baja".
	 * @param integer $soloVigentes Filtro para rubros "Solo Vigentes".
	 */
	public static function getRubrosSegunFiltro( $arrayRubros, $dadosDeBaja, $soloVigentes ){

		if( count( $arrayRubros ) > 0 ){

			$arrayTemporal = [];
			//$i = 0;

			foreach( $arrayRubros as $array ){

				if( $array['est'] == 'A' ){

					$arrayTemporal[] = $array;
				} else {
					if( $dadosDeBaja ){
						$arrayTemporal[] = $array;
					}
				}

			}

			$arrayRubros = $arrayTemporal;
		}

		return $arrayRubros;
	}

}
