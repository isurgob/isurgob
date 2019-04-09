<?php

namespace app\models\ctacte;

use Yii;
use PDO;

use yii\data\ArrayDataProvider;

use yii\helpers\ArrayHelper;

use yii\db\Query;
use yii\db\Expression;

use app\utils\db\utb;
use app\utils\db\Fecha;

/**
 * This is the model class for table "plan_config".
 *
 * @property integer $cod
 * @property string $nombre
 * @property integer $sistema
 * @property integer $aldia
 * @property integer $aplica
 * @property integer $deuda
 * @property integer $cantcuotas
 * @property integer $importecuota
 * @property integer $sinplan
 * @property integer $aldiadesde
 * @property integer $aldiahasta
 * 
 * @property integer $aldiadesdea
 * @property integer $aldiadesdec
 * @property integer $aldiahastaa
 * @property integer $aldiahastac
 * 
 * @property integer $aplicadesde
 * @property integer $aplicahasta
 * 
 * @property integer $aplicadesdea
 * @property integer $aplicadesdec
 * @property integer $aplicahasta
 * @property integer $aplicadesdec
 * 
 * @property number $mindeuda
 * @property number $maxdeuda
 * @property integer $mincantcuo
 * @property integer $maxcantcuo
 * @property string $minmontocuo
 * @property string $maxmontocuo
 * @property integer $diavenc
 * @property string $descnominal
 * @property float $descinteres
 * @property float $descmulta
 * @property string $vigenciadesde
 * @property string $vigenciahasta
 * @property integer $tactiva
 * @property string $tactivaporc
 * @property string $interes
 * @property float $sellado
 * @property string $anticipo
 * @property integer $anticipocuota
 * @property integer $anticipomanual
 * @property string $multa
 * @property integer $usarctaper
 * @property integer $cta_id
 * @property integer $cta_id_rec
 * @property integer $cta_id_sellado
 * @property integer $cta_id_multa
 * @property integer $texto_id
 * @property string $fchmod
 * @property integer $usrmod
 * 
 * 
 * @property array $cambiosUsuario
 * @property array $cambiosTributo
 */
class PlanConfig extends \yii\db\ActiveRecord
{
	
	//aldiadesde
	public $aldiadesdea = null;
	public $aldiadesdec = null;
	
	//aldiahasta
	public $aldiahastaa = null;
	public $aldiahastac = null;
	
	//aplicadesde
	public $aplicadesdea = null;
	public $aplicadesdec = null;
	
	//aplicahasta
	public $aplicahastaa = null;
	public $aplicahastac = null;
	
	
	//usuarios a insertar o eliminar
	public $cambiosUsuario = [];
	
	//tributos a insertar o eliminar
	public $cambiosTributo = [];
	
	public $conanticipo;
	
	public function __construct()
	{
		parent::__construct();
		$this->interes = 100;
	}
	
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'plan_config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
    	$indice = 0;
    	$ret = [];
    	
    	/*
    	 * CAMPOS REQUERIDOS
    	 */
    	$ret[$indice++] = 
    			[
				'cod',//atributos 
				'required',//tipo validador
    			'on' => ['select', 'update', 'delete'],//scenario
    			];

		$ret[$indice++] =
    					[
    					['nombre', 'sistema', 'vigenciadesde', 'cta_id', 'cta_id_rec', 'texto_id'],
    					'required',
    					'on' => ['insert', 'update']
    					];
		
		

		/*
		 * FIN CAMPOS REQUERIDOS
		 */
    			
    			
    	/*
    	 * TIPOS Y RANGO DE DATOS
    	 */
    	$ret[$indice++] =
    					[
    					'cod',
    					'integer',
    					'min' => 0,
    					'max' => 65535,
    					'on' => ['select', 'update', 'delete']
    					];
    					
    	$ret[$indice++] = 
						[
						['sistema', 'cta_id', 'cta_id_rec', 'texto_id'],
						'integer',
						'min' => 1,
						'max' => 65535,
						'on' => ['insert', 'update']
						];
						
		$ret[$indice++] = [
						'cta_id_sellado',
						'integer',
						'min' => 1,
						'max' => 65535,
						'when' => function($model){return $model->sellado > 0;},
						'on' => ['insert', 'update']
						];
						
		$ret[$indice++] = [
						'cta_id_multa',
						'integer',
						'min' => 1,
						'max' => 65535,
						'when' => function($model){return $model->multa > 0;},
						'on' => ['insert', 'update']
						];
						
		$ret[$indice++] =
    					[
    					['vigenciadesde', 'vigenciahasta'],
    					'date',
    					'format' => 'dd/MM/yyyy',
    					'on' => ['insert', 'update', 'select']
    					];
    					
    	 
    					
    					
    	$ret[$indice++] =
    					[
    					['aldiadesdea', 'aldiahastaa', 'aplicadesdea', 'aplicahastaa'],
    					'integer',
    					'min' => 0,
    					'max' => 9999,
    					'on' => ['insert', 'update']
    					];
    					
    	$ret[$indice++] = [
    					['aldiadesdec', 'aldiahastac', 'aplicadesdec', 'aplicahastac'],
    					'integer',
    					'min' => 0,
    					'max' => 999,
    					'on' => ['insert', 'update']
    					];
    					
    	//enteros
    	$ret[$indice++] = [
    					['mincantcuo', 'maxcantcuo'],
    					'integer',
    					'min' => 0,
    					'max' => 65535,
    					'on' => ['insert', 'update']
    					];
    					
    	$ret[$indice++] = [
    					'diavenc',
    					'integer',
    					'min' => 0,
    					'max' => 31,
    					'on' => ['insert', 'update']
    					];
    	
    	//porcentajes
    	$ret[$indice++] = 
    					[
    					['descnominal', 'descinteres', 'descmulta', 'anticipo'],
    					'number',
    					'min' => 0,
    					'max' => 100,
    					'on' => ['insert', 'update'],
    	    			'tooSmall' => '{attribute} no debe ser negativo',
    	    			'tooBig' => '{attribute} no debe ser mayor a {max}'
    					];
    					
    					
    					
    	$ret[$indice++] = [
    					'interes',
    					'number',
    					'min' => 0,
    					'max' => 100,
    					'when' => function($model){return $model->tactiva == 0;},
    					'on' => ['insert', 'update']
    					];
    	
    	$ret[$indice++] = [
    					'tactivaporc',
    					'number',
    					'min' => 0,
    					'max' => 100,
    					'when' => function($model){return $model->tactiva == 1;},
    					'on' => ['insert', 'update']
    					];				
    					
		
		//flotantes
		$ret[$indice++] = [
						['sellado', 'mindeuda', 'maxdeuda', 'minmontocuo', 'maxmontocuo', 'multa'],
						'number',
						'min' => 0,
						'on' => ['insert', 'update']
						];  
  
    	//booleanos que indican la existencia de aldia, aplica, deuda, tasa activa (tactiva), sinplan, anticipocuota, anticipomanual, usarctaper, importecuota
    	$ret[$indice++] =
    					[
    					['aldia', 'aplica', 'deuda', 'tactiva', 'sinplan', 'anticipocuota', 'anticipomanual', 'usarctaper', 'importecuota', 'conanticipo'],
    					'integer',
    					'min' => 0,
    					'max' => 1,
    					'on' => ['insert', 'update']
    					];
    					
    	$ret[$indice++] = [
    					'cantcuotas',
    					'integer', 
    					'min' => 0,
    					'max' => 2,
    					'on' => ['insert', 'update']
    					];
    					
    	//si vigenciahasta no es nula, se comprueba de que sea mayor a vigenciadesde
    	$ret[$indice++] =
    					[
    					'vigenciahasta',
    					function(){
    						
    						$desde = explode('/', $this->vigenciadesde);
    						$hasta = explode('/', $this->vigenciahasta);
    						
    						if($desde[2] > $hasta[2])
    							$this->addError($this->vigenciahasta, "El final de la vigencia debe ser posterior al inicio de la vigencia.");
    						else if($desde[2] == $hasta[2])
    							{
    								if($desde[1] > $hasta[1])
    									$this->addError($this->vigenciahasta, "El final de la vigencia debe ser posterior al inicio de la vigencia.");
    								else if($desde[1] == $hasta[1])
    								{
    									if($desde[0] >= $hasta[0])
    										$this->addError($this->vigenciahasta, "El final de la vigencia debe ser posterior al inicio de la vigencia.");
    								}
    							}
    							
    					},
    						
    						 
    					'when' => function($model){return $model->vigenciahasta !== null;},
    					'on' => ['insert', 'update']
    					];    	
 
    	 /*
    	  * FIN TIPO Y RANGO DE DATOS
    	  */
    	
    	
    	/*
    	 * EXISTENCIA MUTUA
    	 */    				


		$ret[$indice++] = [
							['aldiadesdea', 'aldiadesdec', 'aldiahastaa', 'aldiahastac'],
							'required',
							'when' => function($model){return $model->aldia == 1;},
							'on' => ['insert', 'update'],
							];
							
		//si se indica aplicable con limites, debe existir aplicadesde y aplicahasta
		$ret[$indice++] = [
							['aplicadesdea', 'aplicadesdec', 'aplicahastaa', 'aplicahastac'],
							'required',
							'when' => function($model){return $model->aplica == 1;},
							'on' => ['insert', 'update']
							//'message' => 'Aplica desde año no puede estar vacio.'
							];
							
							
		//si se indica deuda, debe existir mindeuda y maxdeuda
		$ret[$indice++] = [
							['mindeuda', 'maxdeuda'],
							'required',
							'when' => function($model){return $model->deuda == 1;},
							'on' => ['insert', 'update']
							];

		//si se indica cancuotas, debe existir mincantcuo y maxcantcuo
		$ret[$indice++] = [
							['mincantcuo', 'maxcantcuo'],
							'required',
							'when' => function($model){return $model->cantcuotas == 1;},
							'on' => ['insert', 'update']
							];
							
		//si se indica importecuota, debe existir minmontocuo y maxmontocuo
		$ret[$indice++] = [
							['minmontocuo', 'maxmontocuo'],
							'required',
							'when' => function($model){return $model->importecuota == 1;},
							'on' => ['insert', 'update']
							];

    	//si se indica monto de sellado (sellado), se debe indicar la cuenta de sellado (cta_id_sellado)
    	$ret[$indice++] =
    					[
    					'cta_id_sellado',
    					'required',
    					'when' => function($model){return $model->sellado > 0;},
    					'on' => ['insert', 'update']
    					];
    					
    	//si se indica multa por decaimiento (multa), se de indicar la cuenta de multa (cta_id_multa)
    	$ret[$indice++] = 
    					[
    					'cta_id_multa',
    					'required',
    					'when' => function($model){return $model->multa > 0;},
    					'on' => ['insert', 'update']
    					];
    					
    	//si se indica tasa activa (tactiva), se debe ingresar el porcentaje de interes (interes)
    	$ret[$indice++] = [
    					'interes',
    					'required',
    					'when' => function($model){return $model->tactiva == 0;},
    					'on' => ['insert', 'update']
    					];
    					
    	//si no se indica tasa activa (tactiva), se debe ingresar tactivaporc
    	$ret[$indice++] = [
    					'tactivaporc',
    					'required',
    					'when' => function($model){return $model->tactiva == 1;},
    					'on' => ['insert', 'update']
    					];
    						
    	//el minimo de canidad de cuotas no puede ser mayor al maximo de cantidad de cuotas
    	$ret[$indice++] = [
    					'mincantcuo',
    					'esMenor',
    					'when' => function($model){return $model->cantcuotas == 1;},
    					'on' => ['insert', 'update']
    					];
    					
    	//el minimo del valor de la cuota no puede ser mayor al mayimo del valor de la cuota
    	$ret[$indice++] = [
    					'minmontocuo',
    					'esMenor',
    					'when' => function($model){return $model->importecuota == 1;},
    					'on' => ['insert', 'update']
    					];
    					
    					
    	//el minimo de deuda no puede ser mayor al maximo de deuda
    	$ret[$indice++] = [
    					'mindeuda',
    					'esMenor',
    					'when' => function($model){return $model->deuda == 1;},
    					'on' => ['insert', 'update']
    					];
    					
    	$ret[$indice++] = [
    					'anticipo',
    					'required',
    					'when' => function($model){return ($model->conanticipo != null && $model->conanticipo == 1);},
    					'on' => ['insert', 'update']
    					];
    	
    	 /*
    	  * FIN EXISTENCIA MUTUA
    	  */	
    	
    	/*
    	 * EXISTENCIA EN BASE DE DATOS
    	 */				

    	//determina que la cuenta exista    					
    	$ret[$indice++] = [
    					'cta_id',
    					function(){
    						
    						$sql = "Select count(cta_id) From cuenta Where cta_id = :_cta_id";
    						$cmd = Yii::$app->db->createCommand($sql);
    	
    						$cmd->bindValue(':_cta_id', $this->cta_id, PDO::PARAM_INT);
    	
    						if($cmd->queryScalar() <= 0)
    						{
    							if($this->usarctaper)
    								$this->addError($this->cta_id, 'La cuenta de financiación no existe');
    							else $this->addError($this->cta_id, 'La cuenta principal no existe');
    						}
    							
    					},
    					'on' => ['insert', 'update'],
    					];
    	
    	$ret[$indice++] = [
    					'cta_id_rec',
    					'existeCuenta',
    					'on' => ['insert', 'update']
    					];				
    	
    	$ret[$indice++] = [
    					'cta_id_sellado',
    					'existeCuenta',
    					'when' => function($model){return ($model->cta_id_sellado != null && $model->cta_id_sellado > 0); },
    					'on' => ['insert', 'update']
    					];
    	
    	
		$ret[$indice++] = [
    					'cta_id_multa',
    					'existeCuenta',
    					'when' => function($model){return ($model->cta_id_multa != null && $model->cta_id_multa > 0); },
    					'on' => ['insert', 'update']
    					];
    					
    					
    	$ret[$indice++] = [
    					'texto_id',
    					'existeTexto',
    					'on' => ['insert', 'update']
    					];
    					
    					
    					
    	$ret[$indice++] = [
    					'sistema',
    					'existeSistema',
    					'on' => ['insert', 'update']
    					];
    	/*
    	 * FIN EXISTENCIA EN BASE DE DATOS
    	 */
    	

		/*
		 * VALORES POR DEFECTO
		 */    					    					
    	$ret[$indice++] =
    					[
    					'tactivaporc',
    					'default',
    					'value' => 100,
    					'when' => function($model){return $model->tactiva == 0;},
    					'on' => ['insert', 'update']
    					];
    					
		
    						
    	//aldia se establece con limite, aldiadesde = aldiadesdeA * 1000 + aldiadesdeC
    	$ret[$indice++] = [
    						'aldiadesde',
    						'filter',
    						'filter' => function(){return $this->aldiadesdea * 1000 + $this->aldiadesdec;},
    						'when' => function($model){return $model->aldia == 1;},
    						'on' => ['insert', 'update']
    						];


		$ret[$indice++] = [
						['mindeuda', 'maxdeuda'],
						'default',
						'value' => 0,
						'when' => function($model){return $this->deuda == 0;},
						'on' => ['insert', 'update']
						];
						
		$ret[$indice++] = [
						['mincantcuo', 'maxcantcuo'],
						'default',
						'value' => 0,
						'on' => ['insert', 'update']
						];
						
		$ret[$indice++] = [
						['minmontocuo', 'maxmontocuo'],
						'default',
						'value' => 0,
						'when' => function($model){return $this->importecuota == 0;},
						'on' => ['insert', 'update']
						];
						
						
		$ret[$indice++] = [
						['diavenc', 'sellado', 'multa', 'anticipocuota'],
						'default',
						'value' => 0,
						'on' => ['insert', 'update']
						];
						
		$ret[$indice++] = [
						'anticipo',
						'default',
						'value' => 0,
						'on' => ['insert', 'update']
						];
						
		$ret[$indice++] = [
						['descnominal', 'descinteres', 'descmulta'],
						'default',
						'value' => 0,
						'on' => ['insert', 'update']
						];
						
		$ret[$indice++] = [
						'interes',
						'default',
						'value' => 0,
						'when' => function($model){return $model->tactiva == 1;},
						'on' => ['insert', 'update']
						];
						
		$ret[$indice++] = [
						'tactivaporc',
						'default',
						'value' => 0,
						'when' => function($model){return $model->tactiva == 0;},
						'on' => ['insert', 'update']
						];
						

		$ret[$indice++] = [
						'usarctaper',
						'default',
						'value' => 0,
						'on' => ['insert', 'update']
						];
						
		$ret[$indice++] = [
						'cta_id_sellado',
						'default',
						'value' => 0,
						'when' => function($model){return $model->sellado <= 0;},
						'on' => ['insert', 'update']
						];
						
		$ret[$indice++] = [
						'cta_id_multa',
						'default',
						'value' => 0,
						'when' => function($model){return $model->multa <= 0;},
						'on' => ['insert', 'update']
						];
						
		$ret[$indice++] = [
						'cambiosUsuario',
						'default',
						'value' => [],
						'on' => ['insert', 'update']
						];
						
		$ret[$indice++] = [
						'cambiosTributo',
						'default',
						'value' => [],
						'on' => ['insert', 'update']
						];
						
		$ret[$indice++] = [
						'vigenciahasta',
						'default',
						'value' => null,
						'on' => ['insert', 'update']
						];
						
		$ret[$indice++] = [
						'conanticipo',
						'default',
						'value' => 0,
						'on' => ['insert', 'update']
						];
						
		$ret[$indice++] = [
						'anticipocuota',
						'default',
						'value' => 0,
						'on' => ['select', 'insert', 'update']
						];
						
		$ret[$indice++] = [
						'cantcuotas',
						'default',
						'value' => 0,
						'on' => ['insert', 'update']
						];

		 /*
		  * FIN VALORES POR DEFECTO
		  */

		/*
		 * PERIODOS VALIDOS
		 */
		$ret[$indice++] = [
						'aldia',
						'periodoValido',
						'when' => function($model){return ($model->aldia == 1 && $model->aldiadesdea != null && $model->aldiadesdec != null && $model->aldiahastaa != null && $model->aldiahastac != null);},
						'on' => ['insert', 'update']
						];
						
		$ret[$indice++] = [
						'aplica',
						'periodoValido',
						'when' => function($model){return ($model->aplica == 1 && $model->aplicadesdea != null && $model->aplicadesdec != null && $model->aplicahastaa != null && $model->aplicahastac != null);},
						'on' => ['insert', 'update']
						];
		/*
		 * FIN PERIODOS VALIDOS
		 */
		 
		 
		/*
		 * MISC
		 */
		$ret[$indice++] = [
						'cambiosTributo',
						function($attribute){
							
							if(count($this->$attribute) < 1)
								$this->addError($this->cod, 'Debe elegir al menos un tributo asociado');
						},
						'skipOnEmpty' => false,
						'skipOnError' => false,
						'on' => ['insert']
						];
						
						
		/*$ret[$indice++] = [
						'interes',
						'filter',
						'filter' => function(){return 0;},
						'when' => function($model){return $model->tactiva == 1;},
						'on' => ['insert', 'update']
						];
						
		$ret[$indice++] = [
						'interes',
						'filter',
						'filter' => function(){return 0;},
						'when' => function($model){return $model->tactivaporc == 0;},
						'on' => ['insert', 'update']
						];*/
						
		//se coloca cta_id_sellado en 0 cuando se ingresa una cuenta pero sellado es menor o igual a 0				
		$ret[$indice++] = [
						'cta_id_sellado',
						'filter',
						'filter' => function(){return  0;},
						'when' => function($model){return $model->sellado <= 0;},
						'on' => ['insert', 'update']
						];
		
		//se coloca cta_id_multa 0 cuando se ingresa una cuenta pero multa es menor o igual a 0	
		$ret[$indice++] = [
						'cta_id_multa',
						'filter',
						'filter' => function(){return  0;},
						'when' => function($model){return $model->multa <= 0;},
						'on' => ['insert', 'update']
						];
						
		$ret[$indice++] = [
						'anticipo',
						'filter',
						'filter' => function(){return ($this->conanticipo == 0) ? 0 : $this->anticipo;},
						'on' => ['insert', 'update', 'select']
						];
						
		$ret[$indice++] = [
						'anticipocuota',
						'filter',
						'filter' => function(){return $this->conanticipo == 1 ? $this->anticipocuota : 0;},
						'on' => ['insert', 'update']
						];
						
						
		$ret[$indice++] = [
						['mincantcuo', 'maxcantcuo'],
						'filter',
						'filter' => function($valor){return ($this->cantcuotas == 0 || $this->cantcuotas == 2) ? 0 : $valor;},
						'on' => ['insert', 'update']
						];
		/*
		 * FIN MISC
		 */
    	
    	return $ret;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod' => 'Código',
            'nombre' => 'Nombre',
            'sistema' => 'Sistema',
            'aldia' => 'Sin Límite',
            'aplica' => 'Sin Límite',
            'deuda' => 'Sin Límite',
            'cantcuotas' => 'Sin límite',
            'importecuota' => 'Sin límite',
            'sinplan' => 'Sin planes vigentes',
            
            'aldiadesde' => 'Desde',
            'aldiadesdea' => 'Periodo al día año desde',
            'aldiadesdec' => 'Periodo al día periodo desde',
            'aldiahastaa' => 'Periodo al día año hasta',
            'aldiahastac' => 'Periodo al día per hasta',
            'aldiahasta' => 'Hasta',
            
            'aplicadesde' => 'Desde',
            'aplicahasta' => 'Hasta',
            'aplicadesdea' => 'Aplicable a posiciones año desde',
            'aplicadesdec' => 'Aplicable a posiciones per desde',
            'aplicahastaa' => 'Aplicable a posiciones año hasta',
            'aplicahastac' => 'Aplicable a posiciones per hasta',
            
            
            'mindeuda' => 'Deuda nominal mínimo',
            'maxdeuda' => 'Deuda nominal máximo',
            'mincantcuo' => 'Cantidad de cuotas mínimo',
            'maxcantcuo' => 'Cantidad de cuotas máximo',
            'minmontocuo' => 'Monto cuota mínimo',
            'maxmontocuo' => 'Monto cuota máximo',
            'diavenc' => 'Dia venc.',
            'descnominal' => 'Quita de nominal',
            'descinteres' => 'Quita de accesorio',
            'descmulta' => 'Quita de multa',
            'vigenciadesde' => 'Desde',
            'vigenciahasta' => 'Hasta',
            'tactiva' => '',
            'tactivaporc' => 'Tasa activa porcentual',
            'interes' => 'Interes',
            'sellado' => 'Sellado $',
            'anticipo' => 'Anticipo',
            'anticipocuota' => 'Anticipo >= Cuota',
            'anticipomanual' => 'Con anticipo manual',
            'multa' => 'Multa decae',
            'usarctaper' => 'Indica si se usan las cuentas de los periodos afectados para la imputaci�n del pago o bien la cuenta definida',
            'cta_id' => 'Cuenta',
            'cta_id_rec' => 'Cuenta recargo',
            'cta_id_sellado' => 'Cuenta sellado',
            'cta_id_multa' => 'Cuenta multa',
            'texto_id' => 'Texto',
            'fchmod' => 'Fecha de modificacion',
            'usrmod' => 'Codigo de usuario que modifico',
        ];
    }
    
    /**
     * @override
     * Se completan los campos que no estan almacenados en la tabla luego de encontrar un registro existente
     */
    public function afterFind()
    {
    	parent::afterFind();
    	
    	if($this->aldiadesde != null){
    		
    		$this->aldiadesdea= intval($this->aldiadesde / 1000);
    		$this->aldiadesdec= $this->aldiadesde - $this->aldiadesdea;
    	}
    	
    	if($this->aldiahasta != null){
    		
    		$this->aldiahastaa= intval($this->aldiahasta / 1000);
    		$this->aldiahastac= $this->aldiahasta - $this->aldiahastaa;
    	}
    	
    	if($this->aplicadesde != null){
    		
    		$this->aplicadesdea= intval($this->aplicadesde / 1000);
    		$this->aplicadesdec= $this->aplicadesde - $this->aplicadesdea;
    	}
    	
    	if($this->aplicahasta != null){
    		
    		$this->aplicahastaa= intval($this->aplicahasta / 1000) ;
    		$this->aplicahastac= $this->aplicahasta - $this->aplicahastaa;
    	}
    	
    	$this->conanticipo = $this->anticipo > 0 ? 1 : 0;
    }
    
    /**
     * @override
     * formateo de propiedades que no se obtienen del usuario directamente, se hacen de forma indirecta a traves de otra variables
     * 
     */
    public function afterValidate()
    {
    	
    	$this->aldiadesde = $this->aldiadesdea * 1000 + $this->aldiadesdec;
    	$this->aldiahasta = $this->aldiahastaa * 1000 + $this->aldiahastac;

    	$this->aplicadesde = $this->aplicadesdea * 1000 + $this->aplicadesdec;
    	$this->aplicahasta = $this->aplicahastaa * 1000 + $this->aplicahastac;
    	
    	if($this->hasErrors()){
    		
    		if($this->vigenciadesde != null){
    			
				$vigencia = explode('/', $this->vigenciadesde);
				$this->vigenciadesde = $vigencia[1] . '/' . $vigencia[0] . '/' . $vigencia[2];
			}
    			
    		if($this->vigenciahasta != null){

				$vigencia = explode('/', $this->vigenciahasta);
				$this->vigenciahasta = $vigencia[1] . '/' . $vigencia[0] . '/' . $vigencia[2];
    		}
    		
    		//se borra el valor cero de los campos que no es necesario ingresar el valor				
			$campos= [
	    		'aldia' => ['aldiadesdea', 'aldiadesdec', 'aldiahastaa', 'aldiahastac'],
	    		'aplica' => ['aplicadesdea', 'aplicadesdec', 'aplicahastaa', 'aplicahastac'],
	    		'conanticipo' => ['anticipo'],
	    		'importecuota' => ['minmontocuo', 'maxmontocuo']
	    	];
	    	
	    	foreach($campos as $clave => $valor)
	    		if($this->$clave == 0)
	    			foreach($valor as $campo)
	    				$this->$campo= null;

    	
    	}
    	
    	return true;
    }
    
    /**
     * @override
     * formatea con valores por defecto las propiedades que no pertenecen al modelo y que no han sido inicializadas
     */
    public function beforeValidate()
    {
    	
    	if(!parent::beforeValidate())
    		return false;

		/*
		 * aldia
		 */
		if($this->aldia == 0)
    	{
    		$this->aldiadesdea = 0;
    		$this->aldiadesdec = 0;
    		$this->aldiahastaa = 0;
    		$this->aldiahastac = 0;
    	}
    	/*
    	 * fin aldia
    	 */
    	 
    	 
    	 /*
    	  * aplica
    	  */
    	  if($this->aplica == 0)
    	  {
    	  	$this->aplicadesdea = 0;
    	  	$this->aplicadesdec = 0;
    	  	$this->aplicahastaa = 0;
    	  	$this->aplicahastac = 0;
    	  }
    	  /*
    	   * fin aplica
    	   */
    	
    	if($this->conanticipo == 0){
    		$this->anticipo = 0;
    	}
    	
    	
    	if($this->importecuota == 0)
    	{
    		$this->minmontocuo = 0;
    		$this->maxmontocuo = 0;
    	}
    	
    	return true;
    }
    
    public function scenarios()
    {
    	return [
    		
    		'insert' => ['cod', 'nombre', 'sistema', 'vigenciadesde', 'cta_id', 'cta_id_rec', 'cta_id_sellado', 'cta_id_multa', 'texto_id', 'vigenciahasta', 'aldia',
    					'aldiadesdea', 'aldiadesdec', 'aldiahastaa', 'aldiahastac', 'aplica', 'aplicadesdea', 'aplicadesdec', 'aplicahastaa', 'aplicahastac',
    					'deuda', 'mindeuda', 'maxdeuda', 'cantcuotas', 'maxcantcuo', 'mincantcuo', 'importecuota', 'maxmontocuo', 'minmontocuo', 'sellado',
    					'multa', 'cta_id_multa', 'tactiva', 'interes', 'tactivaporc', 'anticipomanual', 'anticipo', 'sinplan', 'aldiadesde', 'diavenc', 'descnominal',
    					'descinteres', 'descmulta', 'usarctaper', 'anticipocuota', 'cambiosUsuario', 'cambiosTributo', 'conanticipo'
    		],

    		'update' => ['nombre', 'sistema', 'vigenciadesde', 'cta_id', 'cta_id_rec', 'cta_id_sellado', 'cta_id_multa', 'texto_id', 'vigenciahasta', 'aldia',
    					'aldiadesdea', 'aldiadesdec', 'aldiahastaa', 'aldiahastac', 'aplica', 'aplicadesdea', 'aplicadesdec', 'aplicahastaa', 'aplicahastac',
    					'deuda', 'mindeuda', 'maxdeuda', 'cantcuotas', 'maxcantcuo', 'mincantcuo', 'importecuota', 'maxmontocuo', 'minmontocuo', 'sellado',
    					'multa', 'cta_id_multa', 'tactiva', 'interes', 'tactivaporc', 'anticipomanual', 'anticipo', 'sinplan', 'aldiadesde', 'diavenc', 'descnominal',
    					'descinteres', 'descmulta', 'usarctaper', 'anticipocuota', 'cambiosUsuario', 'cambiosTributo', 'conanticipo'],
    					
    		'delete' => ['cod'],
    		
    		'select' => ['cod', 'nombre', 'sistema', 'vigenciadesde', 'cta_id', 'cta_id_rec', 'cta_id_sellado', 'cta_id_multa', 'texto_id', 'vigenciahasta', 'aldia',
    					'aldiadesdea', 'aldiadesdec', 'aldiahastaa', 'aldiahastac', 'aplica', 'aplicadesdea', 'aplicadesdec', 'aplicahastaa', 'aplicahastac',
    					'deuda', 'mindeuda', 'maxdeuda', 'cantcuotas', 'maxcantcuo', 'mincantcuo', 'importecuota', 'maxmontocuo', 'minmontocuo', 'sellado',
    					'multa', 'cta_id_multa', 'tactiva', 'interes', 'tactivaporc', 'anticipomanual', 'anticipo', 'sinplan', 'aldiadesde', 'diavenc', 'descnominal',
    					'descinteres', 'descmulta', 'usarctaper', 'anticipocuota', 'conanticipo']
    	];
    }
    
    /**
     * Valida que el parametro sea menor al atributo correspondiente.
     * Si se provee 'mincantcuo', valida que sea menor a 'maxcantcuo'.
     * Si se provee 'minmontocuo', valida que sea menor a 'maxmontocuo'
     * Si se provee 'mindeuda', valida que sea menor a maxmontocuo
     * 
     * @param string $atributo - atributo a validar
     */
    public function esMenor($atributo)
    {
    	switch($atributo)
    	{
    		case 'mincantcuo':
    		
    			if($this->mincantcuo > $this->maxcantcuo)
    				$this->addError($this->mincantcuo, 'Rango de cantidad de cuotas incorrecto');
    		
    			break;
    			
    		case 'minmontocuo':
    		
    			if($this->minmontocuo >= $this->maxmontocuo)
    				$this->addError($this->minmontocuo, 'Rango de monto de cuotas incorrecto');//'El monto minimo de la cuota no puede ser mayor al monto de la cuota maxima.');
    			break;
    			
    			
    		case 'mindeuda' : 
    			if($this->mindeuda >= $this->maxdeuda)
    				$this->addError($this->mindeuda, 'Rango de deuda nominal incorrecto');//'El valor minimo  de la deuda no puede ser mayor al valor maximo de la deuda.');
    	}
    	
    }
    
    
    /**
     * Valida que el periodo sea valido
     * 
     * @param String - Nombre del atributo que contiene el valor del periodo
     */
    public function periodoValido($periodo)
    {
    	$yMax = 9999;
    	$cMax = 999;
    	$maximo = 9999999;
    	
    	switch($periodo)
    	{
    		case 'aldia' :
    			
    			if($this->aldiadesdea > $this->aldiahastaa)
    				$this->addError($this->aldiadesdea, 'Periodo al día: Año desde no puede ser posterior a Año hasta');
    			else if($this->aldiadesdec > $this->aldiahastac && $this->aldiadesdea == $this->aldiahastaa)
    				$this->addError($this->aldiadesdec, 'Periodo al día: Cuota desde no puede ser posterior o igual a Cuota hasta cuando los años son iguales');	
    			
    			if($this->aldiadesdea > $yMax)
    				$this->addError($this->aldiadesdea, 'Periodo al día: Año desde es demasiado grande');
    			
    			
    			if($this->aldiahastaa > $yMax)
    				$this->addError($this->aldiahastaa, 'Periodo al día: Año hasta es demasiado grande');
    			
    			if($this->aldiadesdec > $cMax)
    				$this->addError($this->aldiadesdec, 'Periodo al día: Per desde es demasiado grande');
    			
    			if($this->aldiahastac > $cMax)
    				$this->addError($this->aldiahastac, 'Periodo al día: Per hasta es demasiado grande');
    			
    			break;
    		
    		case 'aplica' :
    			
    			if($this->aplicadesdea > $this->aplicahastaa)
    				$this->addError($this->aplicadesdea, 'Año desde no puede ser posterior a Año hasta');
    			else if($this->aplicadesdec > $this->aplicahastac && $this->aplicadesdea == $this->aplicahastaa)
    				$this->addError($this->aplicadesdec, 'Cuota desde no puede ser posterior o igual a Cuota hasta cuando los años son iguales.');
    				
      			if($this->aplicadesdea > $yMax)
    				$this->addError($this->aplicadesdea, 'Aplicable a posiciones: Año desde es demasiado grande');
    			
    			
    			if($this->aplicahastaa > $yMax)
    				$this->addError($this->aplicahastaa, 'Aplicable a posiciones: Año hasta es demasiado grande');
    			
    			if($this->aplicadesdec > $cMax)
    				$this->addError($this->aplicadesdec, 'Aplicable a posiciones: Per desde es demasiado grande');
    			
    			if($this->aplicahastac > $cMax)
    				$this->addError($this->aplicahastac, 'Aplicable a posiciones: Per hasta es demasiado grande'); 			
	    			
    				
    				
    			break;
    	}	
    }
    
    /**
     * Valida que el sistema exista en la base de datos
     */
    public function existeSistema()
    {
    	
    	$sql = "Select count(cod) From plan_tsistema Where cod = :_sistema";
    	$cmd = Yii::$app->db->createCommand($sql);
    	
    	$cmd->bindValue(':_sistema', $this->sistema, PDO::PARAM_INT);
    	
    	if($cmd->queryScalar() <= 0)
    		$this->addError($this->sistema, 'Sistema invalido.');
    }
    
    /**
     * Valida que la cuenta exista en la base de datos
     * 
     * @param String - nombre de la propiedad que tiene almacenado el valor de la cuenta
     */
    public function existeCuenta($cuenta)
    {    	
    	$sql = "Select count(cta_id) From cuenta Where cta_id = :_cta_id";
    	$cmd = Yii::$app->db->createCommand($sql);
    	
    	$cmd->bindValue(':_cta_id', $this->$cuenta, PDO::PARAM_INT);
    	
    	if($cmd->queryScalar() <= 0)
    		$this->addError($this->$cuenta, 'La cuenta no existe');	
    }
    
    
    /**
     * Valida que el texto exista en la base de datos
     */
    public function existeTexto()
    {
    	$sql = "Select count(texto_id) From texto Where texto_id = :_texto_id";
    	
    	$cmd = Yii::$app->db->createCommand($sql);
    	
    	$cmd->bindValue(':_texto_id', $this->texto_id, PDO::PARAM_INT);
    	
    	if($cmd->queryScalar() <= 0)
    		$this->addError($this->texto_id, 'Debe elegir un texto valido.');
    }
    
    /**
     * Busca un registro del modelo en la base de datos
     * 
     * @param string $scenario = 'select' - Scenario en el cual aplicar las validaciones de datos
     * 
     * @return PlanConfig - Datos del plan de configuracion obtenidos, o si algunas de las validaciones no fueron exitosas.
     */
    public function buscarUno($scenario = 'select')
    {
  		if($this->cod == null)
  			return new PlanConfig();
    	    		
    	$this->scenario = $scenario;	
    		
    	if(!$this->validate())
    		return $this;
		    		
    	
    	$ret =  PlanConfig::findOne($this->cod);
    	
    	if($ret != null)
    	{
    		
    		if($ret->aldia == 1)
    		{    			
    			$ret->aldiadesdea = intval($ret->aldiadesde / 1000);
    			$ret->aldiadesdec = $ret->aldiadesde % 1000;
    			
    			$ret->aldiahastaa = intval($ret->aldiahasta / 1000);
    			$ret->aldiahastac = $ret->aldiahasta % 1000;
    		}
    		
    		if($ret->aplica == 1)
    		{    			
    			$ret->aplicadesdea = intval($ret->aplicadesde / 1000);
    			$ret->aplicadesdec = $ret->aplicadesde % 1000;
    			
    			$ret->aplicahastaa = intval($ret->aplicahasta / 1000);
    			$ret->aplicahastac = $ret->aplicahasta  % 1000;
    		}
    		
    		return $ret;
    	}
    		
    		
    		
    	$this->addError($this->cod, 'Plan de configuración no encontrado.');
    	return $this;    		    		
    }
    
    /**
     * Inserta o modifica el plan de configuracion actual, dependiendo de si es un registro nuevo o no.
     * Tambien modifica(elimina, inserta o modifica) los usuarios y tributos correspondientews
     * 
     * @return boolean - true si se ha insertado o modificado correctamente, false si ha ocurrido un error.
     */
    public function grabar()
    {
    	
    	$cmd = Yii::$app->db->createCommand();
    	
    	
    	//insertar
    	if($this->isNewRecord)
    	{
    		$this->scenario = 'insert';
    		
    		if(!$this->validate())
    			return false;
    			
    
    		$transaccion = Yii::$app->db->beginTransaction();
    		
    		$proximo = $this->getProximoCodigo();
    		
    		$cols = 'cod, nombre, sistema, aldia, aplica, deuda, cantcuotas, importecuota, sinplan, aldiadesde, aldiahasta, aplicadesde, aplicahasta';
    		$cols .= ', mindeuda, maxdeuda, mincantcuo, maxcantcuo, minmontocuo, maxmontocuo, diavenc, descnominal, descinteres, descmulta';
    		$cols .= ', vigenciadesde, vigenciahasta, interes, tactiva, tactivaporc, sellado, multa, anticipo, anticipocuota, anticipomanual';
    		$cols .= ', usarctaper, cta_id, cta_id_rec, cta_id_sellado, cta_id_multa, texto_id, fchmod, usrmod';
    		
    		$parametros = ':_cod, :_nombre, :_sistema, :_aldia, :_aplica, :_deuda, :_cantcuotas, :_importecuota, :_sinplan, :_aldiadesde, :_aldiahasta, :_aplicadesde, :_aplicahasta, :_mindeuda, :_maxdeuda ';
    		$parametros .= ', :_mincantcuo, :_maxcantcuo, :_minmontocuo, :_maxmontocuo, :_diavenc, :_descnominal, :_descinteres, :_descmulta, :_vigenciadesde, :_vigenciahasta, :_interes ';
    		$parametros .= ', :_tactiva, :_tactivaporc, :_sellado, :_multa, :_anticipo, :_anticipocuota, :_anticipomanual, :_usarctaper, :_cta_id, :_cta_id_rec, :_cta_id_sellado, :_cta_id_multa ';
    		$parametros .= ', :_texto_id, current_timestamp, :_usrmod';
    		
    		
    		$sql = "Insert Into plan_config($cols) Values($parametros)";
    		
    		$cmd = Yii::$app->db->createCommand($sql);
    		$params = array_merge($this->getSqlParams($sql), [':_usrmod' => Yii::$app->user->id, ':_cod' => $proximo]);
    		
    		$cmd->bindValues($params);
	
    		
    		$res = $cmd->execute();
    		
    		//se inserto un dato
    		if($res > 0)
    		{
    			$codigo = $proximo;
    			
    			/*
    			 * se insertan los usuarios
    			 */

    			if(count($this->cambiosUsuario) > 0)
    			{
    			$cmdUsuarios = $cmd->batchInsert('plan_config_usuario', ['usr_id', 'tplan'], array_map(function($usr_id) use($codigo){return [$usr_id, $codigo];}, $this->cambiosUsuario ) );
    			
    			$resUsuarios = $cmd->execute();
    			
    			if($resUsuarios == false || $resUsuarios == 0)
    			{
    				$this->addError($this->cod, 'Ocurrio un error cuando se queria dar de alta a los usuarios');
    				$transaccion->rollBack();
    				return false;
    			}
    			}
    			/*
    			 * se terminan de insertar los usuarios
    			 */
    			 
    			 /*
    			  * se insertan los tributos
    			  */    			  
    			 if(count($this->cambiosTributo) > 0)
    			 {
    			 $cmdTributos = $cmd->batchInsert('plan_config_trib', ['trib_id', 'tplan'], array_map(function($trib_id) use($codigo){return [$trib_id, $codigo]; }, $this->cambiosTributo ) );
    			 
    			 $resTributos = $cmd->execute();
    			 
    			 if($resTributos == false || $resTributos == 0)
    			 {
    			 	$this->addError($this->cod, 'Ocurrio un error cuando se queria dar de alta a los tributos');
    			 	$transaccion->rollBack();
    			 	return false;
    			 }
    			 }
    			 /*
    			  * se terminan de insertar los tributos
    			  */
    			  
    			  
    			$this->cod = $proximo;
    		}
    		else
    		{
    			$transaccion->rollBack();
    			return false;
    		}
    		
    		
    		$transaccion->commit();
    		
    		return $res > 0;	
    	}
    	//modificar
    	else
    	{   
    		
    		$this->scenario = 'update';
    		
    		if(!$this->validate())
    			return false;
    		 
    		$transaccion = Yii::$app->db->beginTransaction();
    		 		
    		$sql = 'Update plan_config Set nombre = :_nombre, sistema = :_sistema, aldia = :_aldia, aplica = :_aplica, deuda = :_deuda, cantcuotas = :_cantcuotas, importecuota = :_importecuota ';
    		$sql .= ', sinplan = :_sinplan, aldiadesde = :_aldiadesde, aldiahasta = :_aldiahasta, aplicadesde = :_aplicadesde, aplicahasta = :_aplicahasta, mindeuda = :_mindeuda, maxdeuda = :_maxdeuda ';
    		$sql .= ', mincantcuo = :_mincantcuo, maxcantcuo = :_maxcantcuo, minmontocuo = :_minmontocuo, maxmontocuo = :_maxmontocuo, diavenc = :_diavenc, descnominal = :_descnominal';
			$sql .= ', descinteres = :_descinteres, descmulta = :_descmulta ';
    		$sql .= ', vigenciadesde = :_vigenciadesde, vigenciahasta = :_vigenciahasta, interes = :_interes, tactiva = :_tactiva, tactivaporc = :_tactivaporc, sellado = :_sellado ';
    		$sql .= ', multa = :_multa, anticipo = :_anticipo, anticipocuota = :_anticipocuota, anticipomanual = :_anticipomanual, usarctaper = :_usarctaper, cta_id = :_cta_id ';
    		$sql .= ', cta_id_rec = :_cta_id_rec, cta_id_sellado = :_cta_id_sellado, cta_id_multa = :_cta_id_multa, texto_id = :_texto_id, fchmod = current_timestamp, usrmod = :_usrmod ';
    		$sql .= ' Where cod = :_cod';
    		
    		$cmd = Yii::$app->db->createCommand($sql);
    		$params = array_merge($this->getSqlParams($sql), [':_usrmod' => Yii::$app->user->id]);
    		
    		$cmd->bindValues($params);
    		
    		$res = $cmd->execute();
    		
    		if($res >= 0)
    		{
    			
    			$codigo = $this->cod;
    			
    			//usuarios a quitar

    			$sql = 'Select usr_id From plan_config_usuario Where tplan = :_tplan';
    			$cmd = Yii::$app->db->createCommand($sql);
    			$cmd->bindValue(':_tplan', $codigo, PDO::PARAM_INT);
    			
    			
    			$usuariosRegistrados = $cmd->queryAll();
    			
    			if(count($usuariosRegistrados > 0))
    			{
    				$usuariosRegistrados = array_map(function($valor){return $valor['usr_id']; }, $usuariosRegistrados);

    				
    				$sqlDelete = 'Delete From plan_config_usuario Where tplan = :_tplan';
    				$cmd = Yii::$app->db->createCommand($sqlDelete);
    				$cmd->bindValue(':_tplan', $codigo, PDO::PARAM_INT);
    				$cmd->execute();
    				
    				$interseccion = array_intersect($this->cambiosUsuario, $usuariosRegistrados);
    			
    				$this->cambiosUsuario = array_diff($this->cambiosUsuario, $interseccion);
    				$usuariosRegistrados = array_diff($usuariosRegistrados, $interseccion);
    			
	    			$aInsertar = array_merge( $this->cambiosUsuario, $usuariosRegistrados);

					if(count($aInsertar) > 0)
					{
    			
    					$cmdUsuarios = $cmd->batchInsert('plan_config_usuario', ['usr_id', 'tplan'], array_map(function($usr_id) use($codigo){return [$usr_id, $codigo];}, $aInsertar ) );
    			
    					$resUsuarios = $cmd->execute();
    			
    					if($resUsuarios == false || $resUsuarios == 0)
	    				{
    						$this->addError($this->cod, 'Ocurrio un error cuando se queria dar de alta a los usuarios');
	    					$transaccion->rollBack();
    						return false;
    					}
    				}    				
    			}
    			//fin de la insercion de los usuarios
    			
    			
    			//tributos a quitar
    			$sql = 'Select trib_id From plan_config_trib Where tplan = :_tplan';
    			$cmd = Yii::$app->db->createCommand($sql);
    			$cmd->bindValue(':_tplan', $codigo, PDO::PARAM_INT);
    			
    			$tributosRegistrados = $cmd->queryAll();
    			
    			if(count($tributosRegistrados > 0))
    			{
    				$tributosRegistrados = array_map(function($valor){return $valor['trib_id']; }, $tributosRegistrados);

					$sqlDelete = 'Delete From plan_config_trib Where tplan = :_tplan';
					$cmd = Yii::$app->db->createCommand($sqlDelete);
					$cmd->bindValue(':_tplan', $codigo, PDO::PARAM_INT);
					$cmd->execute();
    			
    			
    				$interseccion = array_intersect($this->cambiosTributo, $tributosRegistrados);
    			
    				$this->cambiosTributo = array_diff($this->cambiosTributo, $interseccion);
    				$tributosRegistrados = array_diff($tributosRegistrados, $interseccion);
    			
	    			$aInsertar = array_merge( $this->cambiosTributo, $tributosRegistrados);
	    			
	    			
	    			//se valida que se vuelva a insertar el menos un tributo
	    			if(count($aInsertar) == 0)
	    			{
	    				
	    				$this->addError($this->cod, 'Debe elegir al menos un tributo asociado');
	    				$transaccion->rollBack();
	    				return false;
	    			}

					if(count($aInsertar) > 0)
					{
    					$cmdTributos = $cmd->batchInsert('plan_config_trib', ['trib_id', 'tplan'], array_map(function($trib_id) use($codigo){return [$trib_id, $codigo];}, $aInsertar ) );
    			
    					$resTributos = $cmd->execute();
    			
    					if($resTributos == false || $resTributos == 0)
	    				{
    						$this->addError($this->cod, 'Ocurrio un error cuando se queria dar de alta a los tributos');
	    					$transaccion->rollBack();
    						return false;
    					}
    				}    				
    			}
    			
    			//fin de insercion de los tributos

    		}
    		
    		$transaccion->commit();
    		
    		return true;
    	}
    	
    	
    	return false;
    }
    
    
    
    /**
     * Borra el plan de configuracion actual, los usuarios y tributos que tiene asignados de la base de datos
     * 
     * @return boolean - true si se ha eliminado correctamente, false si ha ocurrido un error.
     */
    public function borrar()
    {
    	$this->scenario = 'delete';
    	
    	
    	if(!$this->validate())
    		return false;
    		
    	//busca si existen planes con la configuracion que se quiere eliminar    	
    	$sql = 'Select count(*) From plan Where tplan = :_cod';
    	$cmd = Yii::$app->db->createCommand($sql);
    	$cmd->bindValue(':_cod', $this->cod, PDO::PARAM_INT);
    	
    	
    	$cant = $cmd->queryScalar();
    	
    	//existen planes con la configuracion actual, no se puede eliminar el plan de configuracion
    	if($cant > 0)
    	{
    		$this->addError($this->cod, 'Existen planes relacionados con la configuración de plan.');
    		return false;
    	}
    	
    	
    	//busca si existen planes de configuracion de decaimiento
    	$sql = 'Select count(*) From plan_config_decaer Where tplan = :_cod';
    	$cmd = Yii::$app->db->createCommand($sql);
    	$cmd->bindValue(':_cod', $this->cod, PDO::PARAM_INT);
    	
    	$cant = $cmd->queryScalar();
    	
    	if($cant > 0)
    	{
    		$this->addError($this->cod, 'Existen configuraciones de decaimiento de convenios relacionados con la configuración de plan');
    		return false;
    	}
    	
    	/*
    	 * se eliminan los tributos asignados
    	 */
    	$sql = 'Delete From plan_config_trib Where tplan = :_cod';
    	$cmd = Yii::$app->db->createCommand($sql);
    	$cmd->bindValue(':_cod', $this->cod, PDO::PARAM_INT);
    	$cmd->execute();
    	
    	
    	
    	/*
    	 * se eliminan los usuarios asignados
    	 */
    	$sql = 'Delete From plan_config_usuario Where tplan = :_cod';
    	$cmd = Yii::$app->db->createCommand($sql);
    	$cmd->bindValue(':_cod', $this->cod, PDO::PARAM_INT);
    	$cmd->execute();
    	
    	
    	/*
    	 * se elimina el plan de configuracion
    	 */
    	$sql = 'Delete From plan_config Where cod = :_cod';
    	$cmd = Yii::$app->db->createCommand($sql);
    	$cmd->bindValue(':_cod', $this->cod, PDO::PARAM_INT);
    	
    	return $cmd->execute() > 0;	
    }
    
    /**
     * Genera un DataProvider con los codigos y nombres de los sistemas
     * 
     * @return DataProvider - Contiene los codigos (codigo) y los nombres (nombre) de los sistemas
     */
    public function getSistemas()
    {
    	return utb::getAux('plan_tsistema');
    }
    
    /**
     * Genera una DataProvider con codigos y nombres de las cuentas aplicando un filtro.
     * 
     * @param String $nombre = '' - Filtro a aplicar al nombre.
     * 
     * @return DataProvider - Contiene los codigos (codigo) y los nombres (nombre) de las cuentas
     */
    public function getDPCuentas($nombre = '')
    {
    	return utb::DataProviderAux('cuenta', "lower(nombre) Like ( '%' || '$nombre' || '%') And tcta = 1", 'cta_id');
    }
    
    /**
     * Genera el nombre de la cuenta
     * 
     * @param int $cta_id = 0 - Codigo de la cuenta.
     * 
     * @return String - Nombre de la cuenta. Si la cuenta no existe se retornara 'Cuenta no encontrada'
     */
    public function getNombreCuenta($cta_id = 0)
    {
    	$cond = 'cta_id = ' . $cta_id;
    	
    	$res = utb::getAux('cuenta', 'cta_id', 'nombre', 0, $cond);
    	
    	if(array_key_exists($cta_id, $res))
    		return $res[$cta_id];
    		
    		
    	return '';
    }
    
    /**
     * Genera los tributos que estan asignados al plan de configuracion actual.
     * Si no se provee un plan de configuracion, se retornara una consulta vacia
     * 
     * 
     * @return Array - cada elemento es de la forma # => ['trib_id' => #, 'nombre' => 'nombre de tributo'], donde # es el codigo del tributo
     */
    public function getTributosAsignados()
    {    	
    	if($this->cod === null)
    		return [];
    	
    	$queryIn = 'Select trib_id From plan_config_trib Where tplan = :_cod';
    	
    	$sql = 'Select trib_id As cod, trib_id, nombre From trib Where trib_id In (' . $queryIn . ') And trib_id Not In ' . utb::getTribEsp();
    	
    	$cmd = Yii::$app->db->createCommand($sql);
    	$cmd->bindValue(':_cod', $this->cod, PDO::PARAM_INT);
    	
    	$res = $cmd->queryAll();
    	
    	return ArrayHelper::map( $res, 'cod', function($model){return [ 'trib_id' => $model['trib_id'], 'nombre' => $model['nombre'] ];} );
    }
    
    /**
     * genera los tributos que no estan asignados al plan de configuracion actual.
     * Si no se provee un plan de configuracion, se retornaran todos los tributos que se pueden asignar.
     * 
     * @return Array - cada elemento es de la forma # => ['trib_id' => #, 'nombre' => 'nombre de tributo'], donde # es el codigo del tributo
     * 
     */
    public function getTributos()
    {
    	
    	$cod = -1;
    	
    	if(!$this->isNewRecord)
    		$cod = $this->cod;
    	    	
    	$sql = "Select trib_id As cod, trib_id, nombre From trib Where upper(est) = 'A' And trib_id Not in " . utb::getTribEsp() . "";
    	$sql .= ' And trib_id Not In (Select trib_id From plan_config_trib Where tplan = :_cod)';

    	
    	$cmd = Yii::$app->db->createCommand($sql);
    	$cmd->bindParam(':_cod', $cod, PDO::PARAM_INT);
    	
    	$res = $cmd->queryAll();
    	
    	return ArrayHelper::map( $res, 'cod', function($model){return [ 'trib_id' => $model['trib_id'], 'nombre' => $model['nombre'] ];} );
    }
    
    
    /**
     * Genera los usuarios que estan asignados al plan de confiuracion actual.
     * Si no se provee un plan de configuracion, se retornara una consulta vacia.
     * 
     * @return Array - cada elemento es de la forma # => ['usr_id' => #, 'nombre' => 'nombre del usuario'], donde '#' es el codigo de usuario
     */
    public function getUsuariosAsignados()
    {
    	$idPlan = 0;//provoca que el data provider resulte vacio
    	
    	
    	if($this->cod != null)
    		$idPlan = $this->cod;
    	
    	
    	$sql = 'Select usr_id As cod, usr_id, apenom As nombre From sam.sis_usuario Where usr_id In (Select usr_id From plan_config_usuario Where tplan = :_cod)' .
    			' And usr_id In (Select usr_id From sam.sis_usuario_proceso Where pro_id = 3341)';
    			
    	$cmd = Yii::$app->db->createCommand($sql);
    	$cmd->bindValue(':_cod', $idPlan, PDO::PARAM_INT);		    	

    	$res = $cmd->queryAll();
    	
    	return ArrayHelper::map( $res, 'cod', function($model){return [ 'usr_id' => $model['usr_id'], 'nombre' => $model['nombre'] ];} );
    }
    
    /**
     * Genera los usuarios que no estan asignados al plan de configuracion actual. En caso de no existir el plan se procede a obtener todos los usuarios que se pueden asignar
     * 
     * @return Array - cada elemento es de la forma  # => ['usr_id' => #, 'nombre' => 'nombre del usuario'], donde '#' es el codigo de usuario. 
     */
    public function getUsuarios()
    {
    
    	$sql = 'Select usr_id As cod, usr_id, apenom As nombre From sam.sis_usuario Where usr_id In (Select usr_id From sam.sis_usuario_proceso Where pro_id = 3341)';
    	$params = [];
    
    	if($this->cod != null && $this->cod > 0)
    	{
    		$sql .= ' And usr_id Not In (Select usr_id From plan_config_usuario Where tplan = :_cod)';
    		$params = array_merge($params, [':_cod' => $this->cod]);
    	}
    	
    	$cmd = Yii::$app->db->createCommand($sql);
    	$cmd->bindValues($params);
    	
    	$res = $cmd->queryAll();
    	
    	return ArrayHelper::map( $res ,'cod', function($model){return [ 'usr_id' => $model['usr_id'], 'nombre' => $model['nombre'] ]; } );
    }
    
    /**
     * Genera un mapa que contiene los usuarios pertenecientes a un grupo. La clave del mapa es el codigo de usuario y el valor su nombre
     * 
     * @param int $grupo_id - Codigo del grupo de usuarios. null o 0 generan un mapa vacio
     * 
     * @return Array - donde la clave corresponde al grupo de usuario y el valor al nombre
     */
    public function getUsuariosPorGrupo($grupo_id = 0)
    {
    	if($grupo_id == null)
    		$grupo_id = 0;
    	
    	$sql = new Query();
    	
    	$sql->select(['usr_id', 'nombre'])->from('sam.sis_usuario');
    	$sql->where(['grupo' => $grupo_id]);
    	
    	
    	$queryIn = new Query();
    	
    	$queryIn->select('usr_id')->from('sam.sis_usuario_proceso')->where(['pro_id' => 3341]);
    	
    	
    	$sql->andWhere(['in', 'usr_id', $queryIn]);
    	
    	return ArrayHelper::map($sql->all(), 'usr_id', 'nombre');
    }
    
    /**
     * Genera un data provider con los codigos y nombre de los grupos de usuario
     * 
     * @return DataProvider
     */
    public function getGruposUsuario()
    {
    	$cond = 'gru_id In (Select p.gru_id From Sam.sis_grupo_proceso As p where p.pro_id = 3341)';
    	return utb::getAux('Sam.sis_grupo', 'gru_id', 'nombre', 0, $cond);
    }
    
    /**
     * Genera un DataProvider con los codigos y nombre de los textos
     * 
     * @return DataProvider
     */
    public function getTextos()
    {
    	return utb::getAux('texto', 'texto_id', 'nombre', 0, 'tuso=1', 'nombre Asc', true);
    }
    
    /**
     * Genera el proximo codigo a insertar en la base de datos
     * 
     * @return int - Codigo a insertar en la base de datos
     */
    private function getProximoCodigo()
    {
    	return (new Query())->select("nextval('seq_plan_config')")->scalar() + 1;
    }
    
    /**
     * Crea un arreglo asociativo donde las claves son los placeholdes de la consulta ($query) y los valores es lo que se inserta en la consulta.
     * 
     * 
     * @param string $query - consulta SQL con uno o mas placeholders
     * @param string $prefijo = ':_' - prefijo a utilizar para detectar los placeholders dentro de la consulta SQL
     * 
     * @return Array - Arreglo asociativo de tipo clave = $prefijo . $propiedadDelModelo => $valorDeLaPropiedad 
     */
    private function getSqlParams($query, $prefijo = ':_')
    {

		$propiedades = [
						'cod', 'nombre', 'sistema', 'aldia', 'aplica', 'deuda', 'cantcuotas', 'importecuota', 'sinplan', 'aldiadesde', 'aldiahasta', 'aplicadesde', 'aplicahasta',
						'mindeuda', 'maxdeuda', 'mincantcuo', 'maxcantcuo', 'minmontocuo', 'maxmontocuo', 'diavenc', 'descnominal', 'descinteres', 'descmulta', 'vigenciadesde',
						'vigenciahasta', 'interes', 'tactiva', 'tactivaporc', 'sellado', 'multa', 'anticipo', 'anticipocuota', 'anticipomanual', 'usarctaper', 'cta_id', 'cta_id_rec',
						'cta_id_sellado', 'cta_id_multa', 'texto_id', 'usrmod'
    					];
        	
    	$ret = [];	  		

		foreach($propiedades as $p)
		{
			$original = $prefijo . $p;
			$pos = stripos($query, $original);
		
			if($pos != false && $pos > -1)
				$ret = array_merge($ret, [$original => $this->$p]);
		}

		
		return $ret;
    }
}