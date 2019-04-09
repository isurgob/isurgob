<?php

namespace app\models\objeto;

use Yii;

use app\utils\db\Fecha;
use app\utils\db\utb;
use app\utils\helpers\DBException;
use app\models\objeto\Objeto;
use app\models\objeto\ComerRubro;
use yii\data\ArrayDataProvider;
use yii\db\Query;

/**
 * This is the model class for table "comer".
 *
 * @property string $obj_id
 * @property string $legajo
 * @property string $thab
 * @property integer $tipo
 * @property string $fchhab
 * @property string $fchvenchab
 * @property integer $pi
 * @property integer $cantemple
 * @property integer $supcub
 * @property integer $supsemi
 * @property integer $supdes
 * @property integer $alquila
 * @property integer $zona
 * @property string $inmueble
 * @property string $rodados
 * @property string $tel
 * @property string $mail
 */
class Comer extends \yii\db\ActiveRecord
{

	public $expe;
	public $obs;

	//Arreglo con objetos de tipo ComerRubro con los datos de los rubros a guardar
	public $rubros;

    //Arreglo con los titulares del Comercio
    public $titulares;

	//objetos Domi
	public $domicilioPostal;
	public $domicilioParcelario;

    //Responsable de la Habilitación -> Titular principal
    public $responsablePrincipal;

    public $obj_nom;
    public $inmueble_nom;
    public $rodados_nom;

	public $existenMiscelaneas;

	//Variables para las habilitaciones
	public $hab_fchhab;
	public $hab_fchvenchab;

	public $den_obj_nom;

	public function __construct(){

		$this->thab 	= 'C';
		$this->existenMiscelaneas   = false;

        $this->rubros = [];

        //La fecha se formatea asi para que se visualice correctamente en el DatePicker
        $this->fchhab = Fecha::usuarioToDatePicker( date('d/m/y') );
	}

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comer';
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
		$ret[] = [
			[ 'obj_id' ],
			'required',
			'on' => [ 'update', 'delete', 'habilitacion' ]
		];

		$ret[] = [
			[ 'obj_nom', 'tipo' ],
			'required',
			'on' => ['insert', 'update'],
			'message' => 'Ingrese un {attribute}.'
		];

		$ret[] = [
			[ 'thab' ],
			'required',
			'on' => ['insert', 'update'],
			'message' => 'Elija el {attribute}.'
		];

        $ret[] = [
            [ 'fchhab', 'fchvenchab' ],
            'required',
            'on' => ['insert', 'update'],
			'message' => 'Seleccione una {attribute}.',
        ];

        $ret[] = [
			[ 'zona' ],
			'required',
			'on' => ['insert', 'update'],
			'message' => 'Elija la {attribute}.'
		];

		$ret[] = [
            [ 'hab_fchhab', 'hab_fchvenchab' ],
            'required',
            'on' => ['habilitacion'],
			'message' => 'Seleccione una nueva {attribute}.',
        ];

		$ret[] = [
			['expe' ],
			'required',
			'on' => [ 'habilitacion', 'denominacion' ],
			'message' => 'Ingrese un {attribute}.'
		];

		$ret[] = [
			[ 'obs' ],
			'required',
			'on' => [ 'denominacion' ],
			'message' => 'Ingrese una {attribute}.'
		];

		$ret[] = [
			'den_obj_nom',
			'required',
			'on' => [ 'denominacion' ],
			'message' => 'Ingrese una {attribute}.'
		];

		/**
		 * FIN CAMPOS REQUERIDOS
		 */


		/**
		 * TIPO Y RANGO DE DATOS
		 */
		$ret[] = [
			[ 'obj_id' ],
			'string',
			'min' => 8,
			'max' => 8,
			'on' => [ 'update', 'delete', 'habilitacion', 'denominacion' ],
		];

		$ret[] = [
			[ 'inmueble', 'rodados' ],
			'string',
			'min' => 8,
			'max' => 8,
			'on' => [ 'update', 'delete' ],
		];

		$ret[] = [
			'legajo',
			'string',
			'min' => 0,
			'max' => 10,
			'on' => ['insert', 'update' ]
		];

		$ret[] = [
			'thab',
			'string',
			'min' => 1,
			'max' => 1,
			'on' => ['insert', 'update' ]
		];

		$ret[] = [
			[ 'fchhab', 'fchvenchab' ],
			'string',
			'on' => ['insert', 'update', 'habilitacion' ]
		];

		$ret[] = [
			['pi', 'cantemple', 'alquila', 'zona'],
			'integer',
			'on' => ['insert', 'update']
		];

		$ret[] = [
			[ 'supcub', 'supsemi', 'supdes' ],
			'number',
			'max'	=> 999999999.99,
			'on' => ['insert', 'update']
		];

		$ret[] = [
			[ 'supcub', 'supsemi', 'supdes' ],
			'compare',
			'compareValue'	=> 0,
			'operator'		=> '>=',
			'on' => ['insert', 'update']
		];

		$ret[] = [
			'tel',
			'string',
			'min' => 1,
			'max' => 15,
			'on' => ['insert', 'update' ]
		];

		$ret[] = [
			'mail',
			'trim',
			'on' => ['insert', 'update' ]
		];

		$ret[] = [
			'mail',
			'default',
			'on' => ['insert', 'update' ]
		];

		$ret[] = [
			'mail',
			'email',
			'on' => ['insert', 'update' ]
		];

		$ret[] = [
			'expe',
			'string',
			'min' => 1,
			'max' => 20,
			'on' => [ 'habilitacion', 'denominacion' ],
		];

		$ret[] = [
			'obs',
			'string',
			'min' => 1,
			'max' => 500,
			'on' => [ 'habilitacion', 'denominacion' ],
		];

		$ret[] = [
			[ 'den_obj_nom' ],
			'string',
			'min' => 1,
			'max' => 50,
			'on' => [ 'denominacion' ],
		];

		/**
		 * FIN TIPO Y RANGO DE DATOS
		 */

		/**
		 * VALORES POR DEFECTO
		 */
        $ret[] = [
            'legajo',
            'default',
            'value' => '',
            'on' => [ 'insert', 'update' ],
        ];

		$ret[] = [
			['pi', 'cantemple','supcub', 'supsemi', 'supdes', 'alquila', 'tipo' ],
			'default',
			'value' => 0,
			'on' => ['insert', 'update']
		];

        $ret[] = [
			'zona',
			'default',
			'value' => 2,
			'on' => ['insert', 'update']
		];

        $ret[] = [
			['inmueble', 'rodados', 'tel', 'mail' ],
			'default',
			'value' => '',
			'on' => ['insert', 'update']
		];

		/**
		 * FIN VALORES POR DEFECTO
		 */

		/**
		 * VALIDACIONES ESPECÍFICAS
		 */

        $ret[] = [
            'thab',
            'segunTipoHabilitacion',
            'on' => [ 'insert', 'update' ],
        ];

		//Se debe tener como mínimo un rubro
		$ret[] = [
			'rubros',
            'validateRubros',
            'skipOnEmpty' => false,
            'skipOnError' => false,
			'on' => [ 'insert', 'update' ],
		];

        //Se debe tener como mínimo un titular
		$ret[] = [
			'titulares',
            'validateTitulares',
            'skipOnEmpty' => false,
            'skipOnError' => false,
			'on' => [ 'insert', 'update' ],
		];

        //Se debe haber ingresado un domicilio postal
        $ret[] = [
            [ 'domicilioPostal' ],
            'validateExistenciaDomicilioPostal',
            'on' => ['insert', 'update'],
            'skipOnEmpty' => false,
            'skipOnError' => false,
        ];

        //Se debe haber ingresado un domicilio parcelario
        $ret[] = [
            [ 'domicilioParcelario' ],
            'validateExistenciaDomicilioParcelario',
            'on' => ['insert', 'update'],
            'skipOnEmpty' => false,
            'skipOnError' => false,
        ];

		return $ret;
    }

	public function scenarios(){

    	return [
    		'insert' => [
                'obj_id', 'obj_nom', 'legajo', 'tipo', 'thab', 'fchhab', 'fchvenchab', 'pi', 'cantemple', 'supcub', 'supsemi', 'supdes', 'alquila',
                'zona', 'inmueble', 'rodados','tel','mail', 'rubros', 'titulares', 'domicilioPostal', 'domicilioParcelario',
            ],

    		'update' => [
                'obj_id', 'obj_nom', 'legajo', 'tipo', 'thab', 'fchhab', 'fchvenchab', 'pi', 'cantemple', 'supcub', 'supsemi', 'supdes', 'alquila',
                'zona', 'inmueble', 'rodados','tel','mail', 'rubros', 'titulares', 'domicilioPostal', 'domicilioParcelario',
            ],

            'delete' => ['obj_id', 'obs'],

			'habilitacion' => [ 'obj_id', 'fchhab', 'fchvenchab', 'hab_fchhab', 'hab_fchvenchab', 'expe', 'obs' ],

			'denominacion' => [ 'obj_id', 'den_obj_nom', 'expe', 'obs' ],
    	];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'obj_id'    => 'Código de objeto',
            'thab'      => 'Tipo de Habilitación',
			'tipo'		=> 'Tipo de Comercio',
            'fchhab'    =>  'Fecha de habilitación',
            'fchvenchab'    => 'Fecha de vencimiento de habilitación',
            'tipo'      => 'Tipo de Comercio',
            'tel'       => 'Teléfono',
            'mail'      => 'Correo',
            'zona'      => 'Zona',
            'obj_nom'   => 'Nombre de Fantasía',
            'domicilioPostal'   => 'Domicilio Postal',
            'domicilioParcelario' => 'Domicilio Parcelario',

			'hab_fchhab'		=> 'Nueva fecha de habilitación',
			'hab_fchvenchab'	=> 'Nueva fecha de vencimiento de habilitación',
			'expe'			=> 'Expediente',
			'obs'			=> 'Observación',
        ];
    }

    public function segunTipoHabilitacion( $attribute, $params ){

        if( $this->$attribute == 'C' ){ //Tipo de habilitación comercial

            $this->rodados = '';

        } else {
            $this->supcub       = 0;
			$this->supsemi      = 0;
			$this->supdes       = 0;
            $this->cantemple    = 0;
            $this->alquila      = 0;
            $this->pi           = 0;
            $this->zona         = 2;
            $this->inmueble     = '';
        }
    }

	/**
	 * Función que se utiliza para validar que se haya ingresado algún rubro.
	 */
    public function validateRubros( $attribute, $params ){

        if( count( $this->$attribute ) == 0 ){
            $this->addError( $attribute, "Debe ingresar al menos un rubro.");
        } else {

            $hayPrincipal = false;

            foreach( $this->$attribute as $array ){

                if( $array[ 'tipo' ] == 1 ){

                    $hayPrincipal = true;

                }

            }

            if( !$hayPrincipal ){
                $this->addError( $attribute, "Debe ingresar al menos un rubro principal.");
            }
        }

    }

	/**
	 * Función que se utiliza para validar los titulares del comercio.
	 */
    public function validateTitulares( $attribute, $params ){

        if( count( $this->$attribute ) == 0 ){

            $this->addError( $attribute, "Debe ingresar al menos un titular.");

        } else {

            $hayPrincipal = false;
			$titPrincipal = '';

            foreach( $this->$attribute as $array ){

                if( $array[ 'princ' ] != '' ){

                    $hayPrincipal = true;
					$titPrincipal = $array[ 'num' ];

                }

            }

            if( !$hayPrincipal ){

                $this->addError( $attribute, "Debe ingresar al menos un titular principal.");

            } else {

				/**
				 * Verificar si se valida la inscripción a IB.
				 */
				if( $this->seDebeValidarInscripcionIB() ){

					/**
					 * Validar que el titular no tenga tipo de liquidación "Local", ó, en caso contrario,
					 * validar que el titular principal se encuentre inscripto en IB y activo.
					 */
					if( $this->titularTieneLiquidacionLocal( $titPrincipal ) ){	//Si tiene liquidación "Local".

						if( !$this->validarEstadoIBTitular( $titPrincipal ) ){

							$this->addError( $attribute, "El titular principal no se encuentra inscripto en IB o se encuentra dado de baja en IB." );
						};
					}
				}

			}
        }
    }

	private function titularTieneLiquidacionLocal( $obj_id ){

		$sql =	"SELECT EXISTS( SELECT 1 FROM persona WHERE tipoliq = 'LO' AND obj_id = '$obj_id' )::integer";

		return Yii::$app->db->createCommand( $sql )->queryScalar();
	}

	private function seDebeValidarInscripcionIB(){

		return utb::samConfig()['com_validar_ib'];
	}

	private function validarEstadoIBTitular( $obj_id ){

		$sql =	"SELECT EXISTS( SELECT 1 FROM persona WHERE ib <> '' AND est_ib not in ('N','B') AND obj_id = '$obj_id' )::integer";

		return Yii::$app->db->createCommand( $sql )->queryScalar();
	}

    public function validateExistenciaDomicilioPostal( $attribute, $params ){

        if( $this->$attribute->domicilio == '' ){
            $this->addError( $attribute, 'Ingrese un domicilio postal.' );
        }
    }

    public function validateExistenciaDomicilioParcelario( $attribute, $params ){

        if( $this->$attribute->domicilio == '' ){
            $this->addError( $attribute, 'Ingrese un domicilio parcelario.' );
        }
    }

    public function afterFind(){

    	//se determina si tiene miscelaneas cargadas
    	$sql= "Select Exists (Select 1 From objeto_misc Where obj_id = '$this->obj_id')";

    	$this->existenMiscelaneas= Yii::$app->db->createCommand($sql)->queryScalar();

        $this->rubros = ComerRubro::getRubros( $this->obj_id );

        //Obtener el nombre de inmueble o rodado
        $this->inmueble_nom = utb::getNombObj( "'" . $this->inmueble . "'" );
        $this->rodados_nom 	= utb::getNombObj( "'" . $this->rodados . "'" );

		$this->fchhab 		= $this->fchhab;
		$this->fchvenchab 	= $this->fchvenchab;

		//Fecha tentativa para habilitaciones
		$habilitacionVence = intval(utb::samConfig()['comer_hab_vence']);

		$this->hab_fchhab = date('Y/m/d');
		$this->hab_fchvenchab = date('Y/m/d', strtotime("+" . $habilitacionVence . " month", strtotime($this->hab_fchhab)));

    }

    /**
     * Función que se utiliza para obtener los datos del responsable.
     * @param string $obj_id Identificador de la persona.
     */
    public static function getDatosResponsable( $obj_id = '' ){

        $sql =  "SELECT obj_id, nombre, est_ib, est_ib_nom, cuit, ib, iva, iva_nom, tipoliq, tipoliq_nom, orgjuri, orgjuri_nom " .
                " FROM v_persona WHERE obj_id = '" . $obj_id . "'";

        return Yii::$app->db->createCommand( $sql )->queryOne();

    }

    /**
     * Función que se utiliza para obtener los tipos de habilitaciones.
     */
    public static function getTipoHabilitacion(){

        return utb::getAux( 'comer_thab', 'cod', 'nombre' );
    }

	/**
     * Función que se utiliza para obtener los tipos de habilitaciones.
     */
    public static function getTipoComercio(){

        return utb::getAux( 'comer_tipo', 'cod', 'nombre' );
    }

    /**
     * Función que se utiliza para obtener las zonas.
     */
    public static function getZona(){

        return utb::getAux( 'comer_tzona', 'cod', 'nombre' );
    }

    public static function getTipoVinculo(){

        return utb::getAux( 'persona_tvinc', 'cod', 'nombre' );
    }

    public static function getIngresosBrutos(){

        return utb::getAux( 'persona_test_ib', 'cod', 'nombre' );
    }

	/**
	 * Función que se utiliza en "Menú Derecho" para determinar si se realizan DDJJ.
	 */
	public function realizaDDJJ(){

        $sql = "SELECT EXISTS( SELECT 1 FROM trib WHERE tipo = 2 AND est = 'A' AND tobj = 2 )";

        return Yii::$app->db->createCommand( $sql )->queryScalar();
    }

	/**
     * Función que se utiliza para cargar los rubros desde la vista asociados a un comercio ("Habilitación").
	 *
	 * @param boolean $vigentes = true - Seleccionar los rubros que se encuentran vigentes
	 * @param boolean $activos = true - Seleccionar los rubros que se encuentran activos
	 *
	 * @return Array - Cada elemento del arreglo es a su vez un arreglo que representa un rubro
	 */
    public static function getRubrosVistas( $obj_id, $vigentes = true, $activos = true ){

		$sql = "SELECT * FROM v_objeto_rubro WHERE obj_id = '" . $obj_id . "'";

		if( $activos ){
			$sql .= " AND est = 'A'";
		}

		if( $vigentes ){

			$sql .= " AND extract(year From Now()) * 1000 + extract(month From Now())::integer BETWEEN nperdesde AND nperhasta";
		}

		return Yii::$app->db->createCommand( $sql )->queryAll();

    }

	/**
	 * Función que se utiliza para cargar los rubros desde la vista asociados a un comercio ("Habilitación").
	 *
	 * @param boolean $vigentes = true - Seleccionar los rubros que se encuentran vigentes
	 * @param boolean $activos = true - Seleccionar los rubros que se encuentran activos
	 *
	 * @return Array - Cada elemento del arreglo es a su vez un arreglo que representa un rubro
	 */
	public static function getRubrosVista( $obj_id, $vigentes = true, $activos = true ){

		$est = null;
		$res = null;

		if( $activos ){
			$est = 'A';
		}

		if( $vigentes ){

			$sql = " SELECT extract(year From Now()) * 1000 + extract(month From Now())::integer";

			$res = Yii::$app->db->createCommand( $sql )->queryScalar();
		}

		return ComerRubro::find()
			->filterWhere([ 'obj_id' => $obj_id ])
			->andFilterWhere([ 'in', 'est', $est ])
			->andFilterWhere([ '<=', 'nperdesde', $res ])
			->andFilterWhere([ '>=', 'nperhasta', $res ])
			->all();

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

                $rubros[] = $nuevoRubro;
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
     * Función que se utiliza para quitar el estado de "Principal" al rubro que haya sido principal.
     * @param array $arrayRubros Arreglo de rubros.
     */
    public static function cambiarRubrosPrincipales( $arrayRubros = [] ){

        if( count( $arrayRubros ) > 0 ){

            foreach( $arrayRubros as $array ){

                $array['esPrincipal']   = false;
                $array[ 'tipo' ]        = 2;

            }

        }

        return $arrayRubros;
    }

    // /**
    //  * Función que se utiliza para obtener el modelo de un rubro.
    //  * @param integer $id Identificador de rubro.
    //  */
    // public static function getRubroSegunID( $arrayRubros, $rubro_id ){
	//
    //     // $id = 0;
    //     // $aRetornar  = null;
	//
    //     foreach( $arrayRubros as $rubro ){
	//
    //         if( intVal( $rubro['rubro_id'] ) == intVal( $rubro_id ) ){
    //             return $rubro;
    //         }
	//
    //     }
	//
    //     return ( new ComerRubro() );
	//
    // }

    public function grabar( $modelObjeto, $action ){

        $aux = utb::getVariosCampos( 'objeto_tipo', 'cod=2', 'autoinc, letra' );
        $modelObjeto->autoinc	= $aux['autoinc'];
        $modelObjeto->letra 	= $aux['letra'];

        $this->obj_nom = $modelObjeto->nombre;

        switch( $action ){

            case 0:

                $this->setScenario( 'insert' );

                if(!$this->validate())
            		return false;

                break;

            case 2:

                $this->setScenario( 'delete' );

                break;

            case 3:

                $this->setScenario( 'update' );

                if(!$this->validate())
            		return false;

                break;
        }

    	$hayError = false;

    	$transaction = Yii::$app->db->beginTransaction();

        try{

			if( in_array( $action, [ 0, 3 ] ) ){

				//se graba el objeto
				if( !$this->grabarObjeto( $modelObjeto ) ){
					$transaction->rollBack();
					return false; //si el objeto no logra grabarse, no se puede continuar
				}

				//se graban los rubros
				if( !$this->grabarRubros( $modelObjeto->obj_id ) ){
					$hayError = true;
				}

				//se graba el domicilio postal
				if(!$this->grabarDomicilio(true, $modelObjeto->obj_id)) $hayError = true;

				//se graba el domicilio parcelario
				if(!$this->grabarDomicilio(false, $modelObjeto->obj_id)) $hayError = true;

				if( $hayError ){
					$transaction->rollBack();
					return false;
				}

				$this->obj_id = $modelObjeto->obj_id;

			}

			switch( intVal( $action ) ){

                case 0:

                    //Se inserta el comercio
                    $sql = "Insert Into comer(obj_id, legajo, tipo, thab, fchhab, fchvenchab, pi, cantemple, " .
							"supcub, supsemi, supdes, alquila, zona, inmueble, rodados, tel, mail) Values (" .
                            "'$this->obj_id','$this->legajo', $this->tipo, '$this->thab', '$this->fchhab', " .
							"'$this->fchvenchab', $this->pi, $this->cantemple, $this->supcub, $this->supsemi, " .
							"$this->supdes, $this->alquila, $this->zona, '$this->inmueble', '$this->rodados', '$this->tel', '$this->mail')";

                    $res = Yii::$app->db->createCommand($sql)->execute() > 0;

                    if(!$res){
                        $transaction->rollBack();
                        return false;
                    }

                    break;

				case 2:

					$error = $modelObjeto->borrar( $modelObjeto->obs );

                    if( $error != '' ){
						$this->addError( 'obj_id', $error );
                        $transaction->rollBack();
                        return false;
                    }

					break;

                case 3:

                    $sql = "Update comer Set legajo = '$this->legajo', tipo = $this->tipo, thab = '$this->thab', fchhab = '$this->fchhab'," .
                            "fchvenchab = '$this->fchvenchab', pi = $this->pi, cantemple = $this->cantemple, supcub = $this->supcub, " .
							"supsemi = $this->supsemi, supdes = $this->supdes, alquila = $this->alquila, " .
                            "zona = $this->zona, inmueble = '$this->inmueble', rodados = '$this->rodados', tel = '$this->tel', mail = '$this->mail' " .
                            "Where obj_id = '$this->obj_id'";

                    $res = Yii::$app->db->createCommand($sql)->execute() > 0;

                    if(!$res){
                        $transaction->rollBack();
                        return false;
                    }

                    break;
            }

        } catch (\Exception $e){

            $transaction->rollBack();

			$this->addError( 'obj_id', 'Ocurrió un error al grabar los datos.' );

            return false;
        }

    	$transaction->commit();
    	return true;
    }

    /**
     * Graba el objeto correspondiente al comercio.
     * @param Objeto $modelObjeto - Modelo de tipo Objeto a grabar.
     * @return boolean - true si el objeto se ha grabado, false de lo contrario.
     */
    private function grabarObjeto( $modelObjeto ){

    	$hayError = false;

    	$modelObjeto->tobj = 2;    //Se asigna el tipo de objeto
    	$modelObjeto->domi_postal = $this->domicilioPostal->domicilio;

    	//se validan los titulares
    	$tit = $modelObjeto->validarTitulares();

    	if($tit != ''){
    		$this->addError($this->obj_id, $tit);
    		$hayError = true;
    	}

    	//$modelObjeto->num = $modelObjeto->getTitularPrincipal()['num'];
    	$valido = $modelObjeto->validarConErrorModels();

    	if(count($valido) > 0){
    		for($i = 0; $i < count($valido) ; $i++)
    			$this->addError($this->obj_id, $valido[$i]);

    		$hayError = true;
    	}

    	if($hayError) return false;

    	//se asigna al parametro num del objeto el codigo del titular principal
    	$modelObjeto->num = $modelObjeto->getTitularPrincipal()['num'];

    	$res = $modelObjeto->grabar();

    	if($res != ''){
    		$this->addError($this->obj_id, $res);
    		return false;
    	}

    	$res = $modelObjeto->grabarTitulares();

    	if($res != ''){
    		$this->addError($this->obj_id, $res);
    		return false;
    	}

    	return true;
    }

    /**
     * Graba los rubros del comercio
     *
     * @param string $obj_id - Codigo del comercio
     *
     * @return boolean - true si se han grabado los rubros correctamente, false de lo contrario
     */
    public function grabarRubros( $obj_id ){

    	//se borran todos los rubros relacionados al objeto
		$sql = "DELETE FROM objeto_rubro WHERE obj_id = '$obj_id'";

		Yii::$app->db->createCommand($sql)->execute();

    	foreach($this->rubros as $rubro){

    		$rubro->subcta = 0;

    		if( !$rubro->grabar( $obj_id ) ){
                return false;
            }

    	}

        return true;
    }

    /**
     * Graba los domicilios del comercio.
     * @param boolean $postal - true indica que se debe grabar el domicilio postal, false para grabar el parcelario.
     * @param string $obj_id - Codigo del comercio.
     * @return boolean - true si el domicilio se graba correctamente, false de lo contrario.
     */
    private function grabarDomicilio( $postal, $obj_id ){

        $domi = $postal ? $this->domicilioPostal : $this->domicilioParcelario;

        if($domi->domicilio != ''){

            $domi->obj_id = $obj_id;
            $domi->torigen = $postal ? 'OBJ' : 'COM';
            $res = $domi->grabar();

            if($res != ''){
                $this->addError($this->obj_id, $res);
                return false;
            }
        }
        else if( !$postal ){

            //solamente se valida el domicilio parcelario si el comercio tiene local
            $this->addError($this->obj_id, "El domicilio Parcelario está vacio");
            return false;

        }

        return true;
    }

	/**
	* Función que se utiliza para realizar la habilitación de un comercio.
	*/
	public function habilitar( $modelObjeto ){

		$this->scenario = 'habilitacion';

		if(!$this->validate()){
			return false;
		}

		$transaction = Yii::$app->db->beginTransaction();

		$sql =	"UPDATE comer SET fchhab = " . Fecha::usuarioToBd( $this->hab_fchhab, 1 ) . "," .
				" fchvenchab = " . Fecha::usuarioToBD( $this->hab_fchvenchab, 1 ) .
				" Where obj_id = '" . $this->obj_id . "'";

		try{

			Yii::$app->db->createCommand($sql)->execute();

			$res = $modelObjeto->NuevaAccion( 17, $this->hab_fchhab, '', '', $this->expe, $this->fchvenchab, '', $this->obs, '', $this->hab_fchvenchab );

			if( $res != ''){

				$this->addError( $this->obj_id, $res );
				$transaction->rollback();
				return false;

			}

		} catch(\Exception $e){

			$this->addError( $this->obj_id, 'Ocurrió un error al grabar los datos.' );
			$transaction->rollback();
			return false;

		}

		$transaction->commit();
		return true;
	}

	/**
	 * Función que se utiliza para cambiar la denominacion de un objeto.
	 * @param Objeto $modelObjeto - Modelo de tipo Objeto al cual se le cambiara la denominación.
	 * @return boolean - true si se ha logrado cambiar la denominacion, false de lo contrario
	 */
	public function cambiarDenominacion( $modelObjeto ){

		$this->scenario = 'denominacion';

		if(!$this->validate())
			return false;

		if( $this->den_obj_nom === $modelObjeto->nombre ){
			$this->addError('nombre', 'La nueva denominación debe ser distinta a la anterior.');
			return false;
		}

		$transaction = Yii::$app->db->beginTransaction();

		$sql = "Update objeto set nombre = '$this->den_obj_nom' Where obj_id = '$modelObjeto->obj_id'";

		try{

			Yii::$app->db->createCommand( $sql )->execute();

			$res = $modelObjeto->NuevaAccion( 14, date('Y/m/d'), '', '', $this->expe, $modelObjeto->nombre, '', $this->obs );

			if($res != ''){
				$transaction->rollBack();
				$modelObjeto->addError( $this->obj_id, $res );
				return false;
			}

		}catch(\Exception $e){

			$transaction->rollBack();
			$this->addError( $this->obj_id, "Ocurrió un error al intentar cambiar la denominación." );
			return false;

		}

		$transaction->commit();
		return true;
	}

	/**
     * Busca comercios a partir de una condicion
     *
     * @param string $cond Condicion de busqueda
     * @param string $order Criterio de ordenamiento
     *
     * @return SqlDataProvider Con el resultado de busqueda
     */
    public static function buscarComerAv($cond = '', $order = 'obj_id Asc', $vista = 'v_comer', $cantidad = 40){

    	$vista = 'v_comer';

		$sql = "Select obj_id, nombre, num_nom, est, ib, dompar_dir,supcub" .
			", ((substr(cuit, 1, 2) || '-' || substr(cuit, 3, 8) || '-' || substr(cuit, 11, 1))) As cuit" .
			" From $vista";



    	$sqlCount = "Select count(obj_id) From $vista";

    	if($cond != ''){
    		$sql .= " Where " . $cond;
    		$sqlCount .= " Where " . $cond;
    	}

    	$sql .= " Order By " . $order;


    	$models = Yii::$app->db->createCommand($sql)->queryAll();
    	$count = count($models);

    	return new ArrayDataProvider([
    		'allModels' => $models,
    		'totalCount' => $count,
    		'key' => 'obj_id',
    		'pagination' => [
    			'pageSize' => $cantidad,
    			'totalCount' => $count
    		]
    	]);
    }

	/**
	 * Función que se utiliza para imprimir los datos de un comercio.
	 */
	public function Imprimir($id,&$sub1,&$sub2,&$sub3,&$sub4)
    {
    	$sql = "Select *,to_char(fchalta,'dd/mm/yyyy') fchalta,to_char(fchbaja,'dd/mm/yyyy') fchbaja From V_Comer Where obj_id='".$id."'";
   		$array = Yii::$app->db->createCommand($sql)->queryAll();

   		$sql = "Select * From V_Objeto_Tit where obj_id='".$id."' and est='A'";
   		$sub1 = Yii::$app->db->createCommand($sql)->queryAll();

   		$sql = "Select * From V_Objeto_Rubro where obj_id='".$id."' ";
		$sql .= "Order by SubCta, PerDesde Asc";
   		$sub2 = Yii::$app->db->createCommand($sql)->queryAll();

  //  		$sql = "Select *,to_char(fchhab,'dd/mm/yyyy') fchhab,to_char(fchvenchab,'dd/mm/yyyy') fchvenchab From V_Comer_Suc where obj_id='".$id."' Order by Subcta";
  //  		$sub3 = Yii::$app->db->createCommand($sql)->queryAll();
		$sub3 = [];

   		$sql = "Select *,to_char(fecha,'dd/mm/yyyy') fecha,to_char(fchdesde,'dd/mm/yyyy') fchdesde,to_char(fchhasta,'dd/mm/yyyy') fchhasta From V_Objeto_accion v where obj_id='".$id."' Order by v.Fecha Desc";
   		$sub4 = Yii::$app->db->createCommand($sql)->queryAll();

   		return $array;
    }

}
