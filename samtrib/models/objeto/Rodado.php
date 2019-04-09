<?php

namespace app\models\objeto;

use Yii;

use yii\data\ArrayDataProvider;

use app\utils\db\utb;
use app\utils\db\Fecha;

/**
 * This is the model class for table "rodado".
 *
 * @property string $obj_id
 * @property integer $talta
 * @property integer $perini
 * @property integer $tliq
 * @property string $origen_id
 * @property string $marca_id
 * @property string $tipo_id
 * @property string $modelo_id
 * @property integer $marca
 * @property integer $cat
 * @property integer $modelo
 * @property string $modelo_nom
 * @property integer $anio
 * @property string $dominio
 * @property string $dominioant
 * @property integer $marcamotor
 * @property string $nromotor
 * @property integer $marcachasis
 * @property string $nrochasis
 * @property string $peso
 * @property integer $cilindrada
 * @property integer $deleg
 * @property string $color
 * @property integer $combustible
 * @property integer $uso
 * @property string $conductor
 * @property string $fchcompra
 * @property string $tform
 * @property string $remito
 * @property string $remito_anio
 */
class Rodado extends \yii\db\ActiveRecord
{
	public $nombreconductor;

	//contiene el nombre del modelo dado por el aforo
	public $modelonombre;

	//contiene el valor monetario del aforo
	public $valor;

	//expediente de acciones
	public $expe;

	//observaciones de acciones
	public $obs;

	//tipo_id de accion. 10 cambio de numero chasis, 11 cambio numero de motor
	public $taccion;

	//Año desde de perini
	public $adesde;

	//cuota desde de perini
	public $cdesde;

	//domicilio postal
	public $domicilioPostal;

	//valor del aforo obtenido de la concatenacion de datos
	public $aforo;
	public $origen_id;
	public $tipo_id;
	public $marca_id;
	public $modelo_id;

	public $tieneLiquidaciones;
	public $eliminarLiquidaciones;

	public function __construct(){

		parent::__construct();

		//tipo_id de liquidacion = aforo/valuacion
		$this->tieneLiquidaciones = 0;
		$this->eliminarLiquidaciones = 0;
		$this->tliq= 1;
	}

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rodado';
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
				['anio', 'dominio'],
				'required',
				'on' => ['insert', 'update']
				];

		$ret[] = [
				['tliq', 'uso', 'talta'],
				'required',
				'on' => ['insert', 'update'],
				'message' => 'Seleccione un {attribute}'
				];

		$ret[] = [
				['adesde', 'cdesde'],
				'required',
				'on' => ['insert', 'update'],
				'message' => 'El periodo incial no puede estar vacío'
				];

		$ret[] = [
				'aforo_id',
				'required',
				'when' => function($model){return $model->tliq == 1;},
				'on' => ['insert', 'update'],
				'message' => 'El código de aforo no existe'
				];

		$ret[] = [
				['taccion'],
				'required',
				'on' => ['cambio']
				];

		$ret[] = [
				['nrochasis', 'marcachasis'],
				'required',
				'when' => function($model){return $model->taccion == 10;},
				'on' => ['cambio']
				];

		$ret[] = [
				['nromotor', 'marcamotor'],
				'required',
				'when' => function($model){return $model->taccion == 11;},
				'on' => ['cambio']
				];

		$ret[] = [
				'cat',
				'required',
				//'when' => function($model){return $model->tliq == 2;},
				'on' => ['insert', 'update'],
				'message' => 'Elija una categoria'
				];
		/**
		 * FIN CAMPOS REQUERIDOS
		 */

		/**
		 * tipo_id Y RANGO DE DATOS
		 */
		$ret[] = [
				'fchcompra',
				'date',
				'format' => 'dd/mm/yyyy',
				'on' => ['insert', 'update']
				];

		$ret[] = [
				'adesde',
				'integer',
				'min' => 0,
				'max' => 9999,
				'on' => ['insert', 'update']
				];

		$ret[] = [
				'cdesde',
				'integer',
				'min' => 0,
				'max' => 999,
				'on' => ['insert', 'update']
				];

		//claves foraneas
		$ret[] = [
				['tliq', 'uso', 'talta'],
				'integer',
				'min' => 1,
				'on' => ['insert', 'update'],
				'message' => 'Elija un {attribute}'
				];

		$ret[] = [
				['marcamotor', 'marcachasis', 'cilindrada', 'deleg', 'cat', 'combustible', 'marca', 'modelo'],
				'integer',
				'min' => 0,
				'on' => ['insert', 'update', 'cambio']
				];

		$ret[] = [
				'anio',
				'integer',
				'min' => 1914,
				'max' => 9999,
				'on' => ['insert', 'update']
				];

		$ret[] = [
			[ 'peso' ],
			'double',
			'on' => [ 'insert', 'update' ],
		];

		$ret[] = [
				'origen_id',
				'string',
				'max' => 1,
				'on' => ['insert', 'update']
				];

		$ret[] = [
				'color',
				'string',
				'max' => 15,
				'on' => ['insert', 'update']
				];

		$ret[] = [
				['marca_id', 'tipo_id', 'modelo_id'],
				'string',
				'max' => 3,
				'on' => ['insert', 'update']
				];

		$ret[] = [
				['nromotor', 'nrochasis'],
				'string',
				'max' => 30,
				'on' => ['insert', 'update', 'cambio']
				];

		$ret[] = [
				'modelo_nom',
				'string',
				'max' => 50,
				'on' => ['insert', 'update']
				];

		$ret[] = [
				['obj_id'],
				'string',
				'min' => 8,
				'max' => 8,
				'on' => ['insert', 'update']
				];

		$ret[] = [
				'aforo_id',
				'string',
				'min' => 7,
				'max' => 8,
				'on' => ['insert', 'update'],
				'message' => 'El código de aforo no existe'
				];

		$ret[] = [
				'conductor',
				'string',
				'max' => 8,
				'on' => ['insert', 'update']
				];

		$ret[] = [
				['dominio'],
				'string',
				'min' => 6,
				'max' => 9,
				'on' => ['insert', 'update']
				];

		$ret[] = [
				['dominioant'],
				'string',
				'min' => 0,
				'max' => 9,
				'on' => ['insert', 'update']
				];

		$ret[] = [
				'expe',
				'string',
				'max' => 20,
				'on' => ['cambio']
				];

		$ret[] = [
				'obs',
				'string',
				'max' => 500,
				'on' => ['cambio']
				];

		$ret[] = [
				'taccion',
				'integer',
				'on' => ['cambio']
				];

		$ret[] = [
				'taccion',
				'in',
				'range' => [10, 11],
				'on' => ['cambio']
				];

		$ret[] = [
				['tieneLiquidaciones', 'eliminarLiquidaciones'],
				'boolean',
				'falseValue' => 0,
				'trueValue' => 1,
				'on' => ['update']
				];

		$ret[] = [
				['tform'],
				'string',
				'max' => 1,
				'on' => ['insert', 'update']
				];

		$ret[] = [
				['remito'],
				'string',
				'max' => 10,
				'on' => ['insert', 'update']
				];

		$ret[] = [
				['remito_anio'],
				'integer',
				'min' => 0,
				'on' => ['insert', 'update']
				];
		/**
		 * FIN tipo_id Y RANGO DE DATOS
		 */

        /**
         * VALORES POR DEFECTO
         */
        $ret[] = [
        		['deleg', 'cat', 'combustible', 'marca', 'modelo', 'marcamotor', 'marcachasis', 'adesde', 'cdesde', 'cilindrada', 'peso', 'remito_anio'],
        		'default',
        		'value' => 0,
        		'on' => ['insert', 'update']
        		];

        $ret[] = [
        		'modelo_nom',
        		'default',
        		'value' => '',
        		'when' => function($model){return $model->modelo > 0;},
        		'on' => 'insert'
        		];

        $ret[] = [
        		['origen_id','tform','remito'],
        		'default',
        		'value' => '',
        		'on' => ['insert', 'update']
        		];
        /**
         * FIN VALORES POR DEFECTO
         */

        /**
         * VARIOS
         */
        $ret[] = [
        		'dominio',
        		'validarDominio',
        		'on' => ['insert', 'update']
        		];

        $ret[] = [
        		'anio',
        		'validarAnio',
        		'on' => ['insert', 'update']
        		];

        $ret[] = [
        		'fchcompra',
        		'validarFecha',
        		'on' => ['insert', 'update']
        		];

        $ret[] = [
        		'aforo_id',
        		'existeAforo',
        		'when' => function($model){return $model->tliq == 1;},
        		'on' => ['insert', 'update']
        		];

        return $ret;
    }

	public function scenarios(){
		return [
			'insert' => ['anio', 'dominio', 'nromotor', 'uso', 'talta', 'tliq', 'dominioant', 'cat', 'deleg', 'modelo', 'aforo_id',
						'marcamotor', 'marcachasis', 'nromotor', 'nrochasis', 'peso', 'cilindrada', 'color', 'modelo_nom', 'marca',
						'combustible', 'uso', 'conductor', 'fchcompra', 'adesde', 'cdesde', 'aforo', 'origen_id', 'marca_id', 'tipo_id', 'modelo_id',
						'tieneLiquidaciones', 'eliminarLiquidaciones','tform','remito','remito_anio'],
			'update' => ['anio', 'dominio', 'nromotor', 'uso', 'talta', 'tliq', 'dominioant', 'cat', 'deleg', 'aforo_id',
						'marcamotor', 'marcachasis', 'nromotor', 'nrochasis', 'peso', 'cilindrada', 'color', 'modelo_nom', 'marca', 'modelo',
						'combustible', 'uso', 'conductor', 'fchcompra', 'adesde', 'cdesde', 'aforo', 'origen_id', 'marca_id', 'tipo_id', 'modelo_id',
						'tieneLiquidaciones', 'eliminarLiquidaciones','tform','remito','remito_anio'],
			'delete' => [],
			'cambio' => ['taccion', 'marcachasis', 'marcamotor', 'nrochasis', 'nromotor', 'expe', 'obs', 'aforo']
			];
	}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'obj_id' => 'Codigo de objeto',
            'talta' => 'tipo_id de alta',
            'perini' => 'Periodo inicial',
            'tliq' => 'tipo_id de liquidación',
            'origen_id' => 'Procedencia',
            'marca_id' => 'Marca del vehiculo',
            'tipo_id' => 'Código de tipo_id de rodado',
            'modelo_id' => 'Modelo del vehiculo',
            'marca' => 'Código de marca',
            'cat' => 'Categoria',
            'modelo' => 'Modelo del vehiculo',
            'modelo_nom' => 'Nombre del modelo del vehiculo',
            'anio' => 'Año de fabricación',
            'dominio' => 'Dominio automotor',
            'dominioant' => 'Dominio automotor anterior',
            'marcamotor' => 'Marca de motor',
            'nromotor' => 'Número de motor',
            'marcachasis' => 'Marca de chasis',
            'nrochasis' => 'Número de chasis',
            'peso' => 'Peso',
            'cilindrada' => 'Cilindrada',
            'deleg' => 'Delegación',
            'color' => 'Color',
            'combustible' => 'tipo_id de combustible',
            'uso' => 'Uso',
            'conductor' => 'Conductor',
            'fchcompra' => 'Fecha de compra',
            'adesde' => 'Año desde',
            'cdesde' => 'Cuota desde',
            'aforo_id' => 'Aforo',
			'tform' => 'Tipo Formulario',
			'remito' => 'Remito',
			'remito_anio' => 'Año Remito'
        ];
    }

    public function beforeValidate(){

    	if($this->adesde != null && $this->cdesde != null)
    		$this->perini = intval($this->adesde) * 1000 + intval($this->cdesde);
    	else $this->perini = 0;

    	return true;
    }

    public function afterFind(){

    	if($this->perini != null){
    		$this->adesde = intval($this->perini / 1000);
    		$this->cdesde = $this->perini % 1000;
    	}

    	//formatea el aforo = 'origen_id-marca_id-tipo_id-modelo_id'
    	if($this->aforo_id != null){

    		$datos = utb::getVariosCampos('rodado_aforo', "aforo_id = '$this->aforo_id'", 'origen, marca, tipo, modelo');

    		if($datos !== false){
    			$this->marca_id = $datos['marca'];
    			$this->tipo_id = $datos['tipo'];
    			$this->origen_id = $datos['origen'];
    			$this->modelo_id = $datos['modelo'];
    		}else{
    			$this->marca_id = '';
    			$this->tipo_id = '';
    			$this->origen_id = '';
    			$this->modelo_id = '';
    		}

    	}

//    	$this->aforo = "";
//    	$this->aforo = $this->aforo.str_pad($this->marca_id, 3, '0', STR_PAD_LEFT);
//    	$this->aforo = $this->aforo.str_pad($this->tipo_id, 2, '0', STR_PAD_LEFT);
//    	$this->aforo = $this->aforo.str_pad($this->modelo_id, 3, '0', STR_PAD_LEFT);
//    	$this->aforo = $this->origen_id . $this->aforo;

    	if($this->aforo_id != ''){

    		$condicion = "aforo_id = '$this->aforo_id'";
    		$this->modelo_nom = utb::getCampo('rodado_aforo', $condicion, 'modelo_nom');

    		if($this->anio !== null)
    			$this->valor = utb::getCampo('v_rodado', $condicion . " And anio = $this->anio", 'aforo_valor');
    	}

    	//se obtiene el nombre del conductor
    	if($this->conductor !== null && trim($this->conductor) != '')
    		$this->nombreconductor = utb::getCampo('v_persona', "obj_id = '$this->conductor'", 'nombre');

    	//por defecto no tiene liquidaciones y no se ha confirmado eliminar las liquidaciones
    	$this->tieneLiquidaciones = 0;
    	$this->eliminarLiquidaciones = 0;
    }

    /**
     * Graba o modifica los datos del Objeto, del Rodado y del domicilio postal del rodado.
     *
     * @param Objeto $modelObjeto - Objeto correspondiente al rodado,
     *
     * @return boolean - true si se ha grabado todo con exito, false de lo contraio
     */
    public function grabar($modelObjeto){

    	$this->scenario = $this->isNewRecord ? 'insert' : 'update';

    	if(!$this->validate())
    		return false;

		$trans = Yii::$app->db->beginTransaction();

		if(!$this->grabarObjeto($modelObjeto) || !$this->grabarDomicilio( $modelObjeto->obj_id ) ){
			$trans->rollBack();
			return false;
		}

    	if($this->isNewRecord){

    		if($this->conductor == null || trim($this->conductor) == ''){

    			$titularPrincipal = $modelObjeto->getTitularPrincipal();

    			if(count($titularPrincipal) > 0 && array_key_exists('num', $titularPrincipal))
    				$this->conductor = $titularPrincipal['num'];
    			else $this->conductor = '';
    		}


    		$sql = "Insert Into rodado(obj_id, talta, perini, tliq, aforo_id, marca, cat, modelo, modelo_nom, anio, dominio, dominioant, marcamotor, " .
    				"nromotor, marcachasis, nrochasis, peso, cilindrada, deleg, color, combustible, uso, conductor, fchcompra, tform, remito, remito_anio) " .
    				"Values('$modelObjeto->obj_id', $this->talta, $this->perini, $this->tliq, '$this->aforo_id', $this->marca, $this->cat, $this->modelo, '$this->modelo_nom', " .
    				"$this->anio, upper('$this->dominio'), upper('$this->dominioant'), $this->marcamotor, '$this->nromotor', $this->marcachasis, '$this->nrochasis', $this->peso, $this->cilindrada, $this->deleg, '$this->color', " .
    				"$this->combustible, $this->uso, '$this->conductor', " .
    				"" . (($this->fchcompra == null || trim($this->fchcompra) == '') ? 'null' : "'$this->fchcompra'") .
					",'$this->tform','$this->remito',$this->remito_anio" .
    				")";


    		$res = Yii::$app->db->createCommand($sql)->execute() > 0;

    		if(!$res){
    			$this->addError($this->obj_id, "Error al intentar grabar el rodado");
    			$trans->rollBack();
    			return false;
    		}
    	}
    	else {
    		$this->eliminarLiquidaciones = intval($this->eliminarLiquidaciones);

    		if($this->isAttributeChanged('dominio')) $this->dominioant = $this->getOldAttribute('dominio');

    		$sql = "Update rodado set aforo_id = '$this->aforo_id', talta = $this->talta, perini = $this->perini, tliq = $this->tliq," .
    				" uso = $this->uso, marca = $this->marca, cat = $this->cat, modelo = $this->modelo, modelo_nom = '$this->modelo_nom', anio = $this->anio, " .
    				"dominio = upper('$this->dominio'), dominioant = upper('$this->dominioant'), marcamotor = $this->marcamotor, nromotor = '$this->nromotor', marcachasis = $this->marcachasis, nrochasis = '$this->nrochasis', " .
    				"peso = $this->peso, cilindrada = $this->cilindrada, deleg = $this->deleg, color = '$this->color', combustible = $this->combustible, conductor = '$this->conductor', " .
    				"fchcompra = " . (($this->fchcompra == null || trim($this->fchcompra) == '') ? 'null' : "'$this->fchcompra'") .
					",tform='$this->tform',remito='$this->remito',remito_anio=$this->remito_anio" .
    				" Where obj_id = '$modelObjeto->obj_id'";

    		$res = Yii::$app->db->createCommand($sql)->execute() > 0;

    		if(!$res){
    			$this->addError($this->obj_id, "Error al intentar modificar el rodado");
    			$trans->rollBack();
    			return false;
    		}

    		//se comprueba si se ha modificaco del perini y si el periodo inicial actual es mayor al anterior
    		if($this->perini !== $this->getOldAttribute('perini') && $this->perini > $this->getOldAttribute('perini')){

    			$sql = "Select ctacte_id From ctacte Where obj_id = '$this->obj_id' And est = 'D' And anio * 1000 + cuota < $this->perini";
    			$codigos = Yii::$app->db->createCommand($sql)->queryAll();

    			if($codigos !== false && count($codigos) > 0){

    				//todavia no se ha confirmado que se eliminen las liquidaciones anteriores
    				if(!$this->eliminarLiquidaciones){
    					$this->tieneLiquidaciones = 1;
    					$trans->rollBack();
    					return false;
    				}

    				//se da de baja cada codigo encontrado anteriormente si se ha confirmado la accion
    				foreach($codigos as $ctacte){
    					$sql = "Select sam.uf_emision_borrar(" . $ctacte['ctacte_id'] . ", " . Yii::$app->user->id . ", 'B', 'Periodo Inicial Anterior')";
    					Yii::$app->db->createCommand($sql)->execute();
    				}
    			}
    		}
    	}

    	$trans->commit();
    	return true;
    }

    /**
     * Graba el objeto correspondiente al rodado
     *
     * @param Objeto $modelObjeto - Objeto correspondiente al rodado
     *
     * @return boolean - true si el objeto se ha grabado con exito, false de lo contrario
     */
    private function grabarObjeto($modelObjeto){

    	$hayError = false;

    	$modelObjeto->obj_dato = $this->dominio;
    	$modelObjeto->tobj = 5;
    	$modelObjeto->domi_postal = $this->domicilioPostal;

    	//se validan los titulares
    	$tit = $modelObjeto->validarTitulares();

    	if($tit != ''){
    		$this->addError($this->obj_id, $tit);
    		$hayError = true;
    	}


    	if(!$hayError && ($modelObjeto->nombre === null || trim($modelObjeto->nombre) == ''))
    		$modelObjeto->nombre = $modelObjeto->getTitularPrincipal()['apenom'];

    	//se asigna al parametro num del objeto el codigo del titular principal
    	$modelObjeto->num = $modelObjeto->getTitularPrincipal()['num'];

    	$valido = $modelObjeto->validarConErrorModels();

    	if(count($valido) > 0){
    		for($i = 0; $i < count($valido) ; $i++)
    			$this->addError($this->obj_id, $valido[$i]);

    		$hayError = true;
    	}

    	if($hayError) return false;

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
     * Graba el domicilio postal del rodado
     *
     * @param string $obj_id - Codigo del rodado
     *
     * @return boolean - true si el domicilio se graba correctamente, false de lo contrario
     */
    private function grabarDomicilio($obj_id){

    	if($this->domicilioPostal->domicilio != ''){

    		$this->domicilioPostal->obj_id = $obj_id;
    		$this->domicilioPostal->torigen = 'OBJ';
    		$res = $this->domicilioPostal->grabar();

    		if($res != ''){
    			$this->addError($this->obj_id, $res);
    			return false;
    		}
    	} else{
    		$this->addError($this->obj_id, "el domicilio esta vacio");
    		return false;
    	}

    	return true;
    }

    /**
     * Graba el cambio de chasis o el cambio de motor del rodado, depende del tipo_id de accion que se haya colocado en taccion
     *
     * 10 para cambio de chasis, 11 para cambio de motor.
     *
     * @param Objeto $modelObjeto - Objeto correspondiente al rodado
     *
     * @return boolean - true si el cambio se ha realizado con eixto, false de lo contrario
     */
    public function grabarCambio($modelObjeto){

    	$this->scenario = 'cambio';

    	if(!$this->validate())
    		return false;

    	$trans = Yii::$app->db->beginTransaction();
    	$sql = '';
    	$valorAnterior = '';
    	$nuevoValor = '';

    	switch($this->taccion){

    		case 10 :

    			$sql = "Update rodado set marcachasis = $this->marcachasis, nrochasis = '$this->nrochasis' Where obj_id = '$modelObjeto->obj_id'";
    			$valorAnterior = $this->getOldAttribute('marcachasis');
    			$nuevoValor = $this->marcachasis;
    			break;

    		case 11 :

    			$sql = "Update rodado set marcamotor = $this->marcamotor, nromotor = '$this->nromotor' Where obj_id = '$modelObjeto->obj_id'";
    			$valorAnterior = $this->getOldAttribute('marcamotor');
    			$nuevoValor = $this->marcamotor;
    			break;

    		default:

    			$this->addError($this->taccion, 'tipo_id de acción incorrecta');
    			return false;
    	}

    	$res = Yii::$app->db->createCommand($sql)->execute() > 0;

    	if(!$res){
    		$trans->rollBack();
    		$this->addError($this->taccion, 'Error al intentar grabar el cambio en la tabla rodado');
    		return false;
    	}


    	$res = $modelObjeto->NuevaAccion($this->taccion, date('Y/m/d'), '', '', $this->expe, $valorAnterior, $nuevoValor, $this->obs);

    	if($res != ''){
    		$this->addError($this->taccion, $res);
    		$trans->rollBack();
    		return false;
    	}

    	$trans->commit();
    	return true;
    }

    /**
     * Da de baja un rodado
     *
     * @param Objeto $modelObjeto - Modelo de tipo_id objeto correspondiente al rodado
     *
     * @return boolean - true si se ha logrado dar de baja correctamenta, false de lo contrario
     */
    public function borrar($modelObjeto){

    	if($modelObjeto->est == 'B') return true;

    	//se borra solamente el objeto
    	$res = $modelObjeto->borrarConErrorSummary($modelObjeto->obs);

		if($res == '')
			return true;

		$modelObjeto->addError('obj_id', $res);
		return false;
    }

    /**
     * Valida los datos del dominio
     *
     */
	public function validarDominio(){

		//solo compara con rodados activos
		$sql = "Select count(r.obj_id) From rodado r, objeto o Where upper(r.dominio) = upper('$this->dominio') And r.obj_id = o.obj_id And o.est = 'A'";

		if(!$this->isNewRecord)
			$sql .= " And r.obj_id <> '$this->obj_id'";

		if(Yii::$app->db->createCommand($sql)->queryScalar() > 0)
			$this->addError($this->dominio, "Ya existe un Rodado con el Dominio " . $this->dominio);
	}

	public function validarAnio(){

		$actual = intval(date('Y'));

		if($actual + 1 < $this->anio)
			$this->addError($this->anio, 'El año de Fabricación no puede ser mayor en más de un año al actual');
	}

	public function validarFecha(){

		$res = Fecha::esFuturo(Fecha::usuarioToBD($this->fchcompra));

		if($res)
			$this->addError($this->fchcompra, 'La fecha de compra no puede ser posterior a la fecha actual');
	}

    public function existeAforo(){

    	$sql = "Select Exists (Select 1 From rodado_aforo Where aforo_id = '$this->aforo_id')";
    	$res = Yii::$app->db->createCommand($sql)->queryScalar();

    	if(!$res) $this->addError($this->aforo_id, 'El código de aforo no existe');
    }
    /**
     * Busca rodados a partir de una condicion
     *
     * @param string $cond Condicion de busqueda
     * @param string $order Criterio de ordenamiento
     *
     * @return SqlDataProvider Con el resultado de busqueda
     */
    public static function buscarRodadoAv($cond = '', $order = 'obj_id Asc', $cantidad = 40){

    	$sql = "Select obj_id, dominio, num_nom, cat_nom, marca_nom, modelo_nom, anio, cilindrada, est, cat_nom, marca_nom, modelo_nom, anio, dominioant, nromotor, nrochasis, cilindrada, deleg_nom From v_rodado";

    	if($cond != '')
    		$sql .= " Where $cond";

    	$sql .= " Order By $order";

    	$modelos = Yii::$app->db->createCommand($sql)->queryAll();
    	$count = count($modelos);

    	return new ArrayDataProvider([
    		'allModels' => $modelos,
    		'totalCount' => $count,
    		'key' => 'obj_id',
    		'pagination' => [
    			'pageSize' => $cantidad,
    			'totalCount' => $count
    		]
    	]);
    }

    public function Imprimir($id,&$sub1)
    {
    	$sql = "Select *,to_char(fchalta,'dd/mm/yyyy') fchalta,to_char(fchbaja,'dd/mm/yyyy') fchbaja,to_char(fchcompra,'dd/mm/yyyy') fchcompra From V_Rodado Where obj_id='".$id."'";
   		$array = Yii::$app->db->createCommand($sql)->queryAll();

   		$sql = "Select * From v_objeto_tit where obj_id='".$id."'";
   		$sub1 = Yii::$app->db->createCommand($sql)->queryAll();

   		return $array;
    }

	public function RevisionTecnica( $obj_id, $texto ){

		$sql = "Select * From sam.uf_texto_rodado('$obj_id', $texto)";
   		$array = Yii::$app->db->createCommand($sql)->queryAll();

   		return $array;

	}
}
