<?php

namespace app\models\objeto;

use Yii;

use yii\data\SqlDataProvider;

use app\utils\db\utb;
use app\utils\db\Fecha;
use app\utils\helpers\DBException;

/**
 * This is the model class for table "cem".
 *
 * @property string $obj_id
 * @property string $nc
 * @property string $cuadro_id
 * @property string $cuerpo_id
 * @property string $tipo
 * @property integer $piso
 * @property string $fila
 * @property integer $nume
 * @property integer $cat
 * @property integer $deleg
 * @property integer $sup
 * @property string $tomo
 * @property string $folio
 * @property string $fchcompra
 * @property string $fchingreso
 * @property string $fchvenc
 * @property integer $exenta
 * @property integer $edicto
 * @property string $cod_ant
 * 
 * @property integer $adesde
 * @property integer $cdesde
 * @property integer $ahasta
 * @property integer $chasta
 */
class Cem extends \yii\db\ActiveRecord
{	
	//almacena el domicilio postal
	public $domicilio = null;
	
	//traslado ctacte
	public $adesde;
	public $cdesde;
	public $ahasta;
	public $chasta;
	
	//atributos usados en alquiler
	public $tipo_nom;
	public $cuadro_nom;
	public $cuerpo_nom;
	public $alquiler_titulo;
	public $alquiler_superficie;
	public $alquiler_fecha;
	public $alquiler_fecha_inicio;
	public $alquiler_duracion;
	public $alquiler_fecha_fin;
	public $alquiler_codigo_responsable;
	public $alquiler_nombre_responsable;
	public $alquiler_texto;
	public $alquiler_codigo;
	
	private $perdesde;
	private $perhasta;
	
	public function __construct(){
		
		parent::__construct();
		
		$this->alquiler_fecha= date('Y/m/d');
		$this->alquiler_duracion= 1;
		$this->alquiler_fecha_inicio= $this->alquiler_fecha;
		$this->alquiler_fecha_fin= date('d/m/Y', strtotime("+$this->alquiler_duracion years"));
		$this->alquiler_superficie= 0;
		$this->alquiler_codigo= 0;
	}
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cem';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
    	$ret = [];
    	
    	/*
    	 * CAMPOS REQUERIDOS
    	 */
    	$ret[] = [
    			'obj_id',
    			'required',
    			'on' => ['select', 'traslado', 'alquiler']
    			];
    	
    	$ret[] = [
    			['adesde', 'cdesde', 'ahasta', 'chasta'],
    			'required',
    			'on' => ['traslado']
    			];
    	 
    	$ret[] = [
    			['deleg'],
    			'required',
    			'on' => ['insert', 'update']
    			];
    			
    	$ret[] = [
    			'cua_id',
    			'required',
    			'on' => ['insert', 'update'],
    			'message' => 'Selecciones el cuadro'
    			];
    			
    	$ret[]= [
    			['tipo'],
    			'required',
    			'when' => function($model){return $model->cua_id != '';},
    			'on' => ['insert', 'update'],
    			'message' => 'Seleccione el tipo'
    			];
    	
    	$ret[]= [
    			['cat'],
    			'required',
    			'when' => function($model){return $model->tipo != '';},
    			'on' => ['insert', 'update'],
    			'message' => 'Seleccione la categoría'
    			];		
    			
    	$ret[]= [
    			['alquiler_fecha_inicio', 'alquiler_duracion'],
    			'required',
    			'on' => ['alquiler']
    			];
    	
    	//se valida que se ingrese piso, fila y nume en caso de que el cuadro lo requiera
    	$ret[] = [
    			'cua_id',
    			'requiereAdicionales',
    			'on' => ['insert', 'update']
    			];
    			
    	//edicto es requerido cuando exenta = 'edicto' (1)
    	$ret[] = [
    			'edicto',
    			'required',
    			'when' => function($model){return $model->exenta == 1;},
    			'on' => ['insert', 'update']
    			];
    			
    	//cuerpo_id es requerido si el cuadro_id que se escogio tiene cuerpos en la base de datos
    	$ret[] = [
    			'cue_id',
    			'required',
    			'skipOnEmpty' => true,
    			'when' => function($model){return $this->cuadroTieneCuerpos($model->cua_id);},
    			'on' => ['insert', 'update'],
    			'message' => 'No se selecciono ningún cuerpo'
    			];
    			
    	//tomo es requerido cuando folio se ha ingresado
    	$ret[] = [
    			'tomo',
    			'required',
    			'when' => function($model){return ( $model->folio != null &&  trim( $model->folio ) != '' );},
    			'on' => ['insert', 'update'],
    			'message' => 'Ingrese el Tomo correspondiente al Folio'
    			];
    			
    	//folio es requerido cuando tomo se ha ingresado
    	$ret[] = [
    			'folio',
    			'required',
    			'when' => function($model){return ( $model->tomo != null && trim( $model->folio ) != '' );},
    			'on' => ['insert', 'update'],
    			'message' => 'Ingrese el Folio correspondiente al Tomo'
    			];
    	/*
    	 * FIN CAMPOS REQUERIDOS
    	 */
    	 
    	/*
    	 * TIPO Y RANGO DE DATOS
    	 */
    	$ret[] = [
    			['piso', 'nume', 'sup', 'edicto', 'fila'],
    			'integer',
    			'min' => 0,
    			'on' => ['insert', 'update']
    			];
    			
    	$ret[] = [
    			'cod_ant',
    			'integer',
    			'min' => 1,
    			'on' => ['insert', 'update', 'delete']
    			];
    			
    	$ret[] = [
    			'bis',
    			'string',
    			'min' => 0,
    			'max' => 1,
    			'on' => ['insert', 'update']
    			];
    			
    	$ret[] = [
    			['obj_id', 'alquiler_codigo_responsable'],
    			'string',
    			'min' => 8,
    			'max' => 8,
    			'on' => ['select', 'traslado', 'update', 'delete', 'alquiler']	
    			];
    			
    	$ret[] = [
    			['folio', 'tomo'],
    			'string',
    			'min' => 0,
    			'max' => 10,
    			'on' => ['insert', 'update']
    			];
    			
    	$ret[] = [
    			['fchcompra', 'fchingreso', 'fchvenc'],
    			'date',
    			'format' => 'yyyy/mm/dd',
    			'skipOnEmpty' => true,
    			'isEmpty' => function($value){return ($value == null || trim($value) == '');},
    			'on' => ['insert', 'update']
    			];
    			
    	$ret[] = [
    			'exenta',
    			'integer',
    			'min' => 0,
    			'on' => ['insert', 'update']
    			];
    	
    	$ret[] = [
    			'cat',
    			'integer',
    			'min' => 1,
    			'on' => ['insert', 'update'],
    			'message' => 'Elija una categoria'
    			];
    			
    	$ret[] = [
    			'adesde',
    			'integer',
    			'min' => 0,
    			'max' => 9999,
    			'on' => ['traslado'],
    			'message' => 'Año desde debe ser mayor a 0 y menor a 9999'
    			];
    			
    	$ret[] = [
    			'ahasta',
    			'integer',
    			'min' => 0,
    			'max' => 9999,
    			'on' => ['traslado'],
    			'message' => 'Año hasta debe ser mayor a 0 y menor a 9999'
    			];
    			
    	$ret[] = [
    			'cdesde',
    			'integer',
    			'min' => 0,
    			'max' => 999,
    			'on' => ['traslado'],
    			'message' => 'Cuota desde debe ser mayor a 0 y menor a 999'
    			];
    			
    	$ret[] = [
    			'chasta',
    			'integer',
    			'min' => 0,
    			'max' => 999,
    			'on' => ['traslado'],
    			'message' => 'Cuota hasta debe ser mayor a 0 y menor a 999'
    			];
    			
    	$ret[]= [
    			'alquiler_duracion',
    			'integer',
    			'min' => 1,
    			'on' => ['alquiler']
    			];
    			
    	$ret[] = [
    			['alquiler_fecha_inicio'],
    			'date',
    			'format' => 'yyyy/mm/dd',
    			'on' => ['alquiler']
    			];
    	
    	$ret[]= [
    			'alquiler_superficie',
    			'number',
    			'min' => 0,
    			'on' => ['alquiler']
    			];
    			
    	$ret[]= [
    			'alquiler_texto',
    			'integer',
    			'min' => 0,
    			'on' => ['alquiler'],
    			'message' => 'Elija un texto'
    			];
    			
    	$ret[]= [
    			'alquiler_titulo',
    			'string',
    			'max' => 10,
    			'on' => ['alquiler']
    			];
    	/*
    	 * FIN TIPO Y RANGO DE DATOS
    	 */
    	 
    	 
    	/*
    	 * VALORES POR DEFECTO
    	 */ 
    	$ret[] = [
    			['piso', 'nume', 'sup', 'fila'],
    			'default',
    			'value' => 0,
    			'on' => ['insert', 'update']
    			]; 
    			
    	$ret[] = [
    			'bis',
    			'default',
    			'value' => '',
    			'on' => ['insert', 'update']
    			];
    			
    	$ret[] = [
    			'edicto',
    			'default',
    			'value' => 0,
    			'when' => function($model){return ($model->exenta == null || $model->exenta != 1);},
    			'on' => ['insert', 'update']
    			];
    			
    	$ret[] = [
    			'exenta',
    			'default',
    			'value' => 0,
    			'on' => ['insert', 'update']
    			];
    			
    	$ret[] = [
    			['cua_id', 'cue_id'],
    			'default',
    			'value' => '',
    			'on' => ['insert', 'update']
    			];
    			
    	$ret[]= [
    			['alquiler_superficie'],
    			'default',
    			'value' => 0,
    			'on' => ['alquiler']
    			];
    			
    	$ret[]= [
    			'alquiler_titulo',
    			'default',
    			'value' => '',
    			'on' => ['alquiler']
    			];
    	/*
    	 * FIN VALORES POR DEFECTO
    	 */
    	$ret[] = [
    			['cua_id', 'cue_id', 'obj_id'],
    			'filter',
    			'filter' => 'trim',
    			'on' => ['insert', 'update']
    			];
    	/*
    	 * FILTRO DE VALORES
    	 */
    			
    	//fchcompra es nulo cuando delegacion es municipal
    	$ret[] = [
    			'fchcompra',
    			'filter',
    			'filter' => function(){return null;},
    			'when' => function($model){return $model->fchcompra == 1;},
    			'on' => ['insert', 'update']
    			];
    			
    	$ret[]= [
    			'alquiler_fecha',
    			'filter',
    			'filter' => function(){return date('Y/m/d');},
    			'on' => ['alquiler']
    			];
    	/*
    	 * FIN FILTRO DE VALORES
    	 */
    	 
    	 $ret[] = [
    	 		'adesde',
    	 		function(){
    	 			
    	 			if($this->adesde > $this->ahasta || $this->cdesde > $this->chasta)
    	 				$this->addError($this->adesde, 'Periodo invalido');
    	 		},
    	 		'on' => 'traslado'
    	 		];
    	 		
    	 //se valida que las cuentas existan
    	 $ret[] = [
    	 		'obj_id',
    	 		function(){
    	 			
    	 			$sql = "Select Exists (Select 1 From v_cem Where obj_id = '$this->obj_id')";
    	 			
    	 			$res = Yii::$app->db->createCommand($sql)->queryScalar();
    	 			
    	 			if(!$res)
    	 				$this->addError($this->obj_id, "La cuenta de cementerio $this->obj_id no existe");
    	 			
    	 			return $res;
    	 		},
    	 		'on' => ['select', 'traslado']
    	 		];
    	 		
    	 		
		//la fecha de inicio del alquiler es futura
		$ret[]= [
				'alquiler_fecha_inicio',
				'fechaInicioAlquilerEsFuturo',
				'on' => ['alquiler']
				];


		//la fecha de fin de alquiler = fecha de inicio de alquiler + duracion del alquiler en años
    	$ret[]= [
    			'alquiler_fecha_fin',
    			'filter',
    			'filter' => function($model){return date('Y/m/d', strtotime("+$model->alquiler_duracion years"));},
    			'on' => ['alquiler']
    			];
    			
    	$ret[]= [
    			'alquiler_texto',
    			'existeTexto',
    			'skipOnEmpty' => true,
    			'on' => ['alquiler']
    			];
    			
    	$ret[]= [
    			'alquiler_codigo_responsablke',
    			'existeResponsable',
    			'on' => ['alquiler']
    			];
    	
    	
    	return $ret;
    }
    
    public function scenarios(){
    	return [
    		'insert' => ['tipo', 'cat', 'deleg', 'obj_id', 'piso', 'fila', 'nume', 'nombre', 'cua_id', 'cue_id', 'piso', 'fila', 'nume', 'bis', 'sup', 'edicto', 'exenta', 'fchcompra', 'fchingreso', 'fchvenc'],
    		'select' => ['obj_id'],
    		'update' => ['tipo', 'cat', 'deleg', 'obj_id', 'piso', 'fila', 'nume', 'nombre', 'cua_id', 'cue_id', 'piso', 'fila', 'nume', 'bis', 'sup', 'edicto', 'exenta', 'fchcompra', 'fchingreso', 'fchvenc'],
    		'traslado' => ['obj_id', 'adesde', 'cdesde', 'ahasta', 'chasta'],
    		'alquiler' => ['obj_id', 'tipo_nom', 'cuadro_nom', 'cuerpo_nom', 'alquiler_titulo', 'alquiler_superficie', 'alquiler_fecha', 'alquiler_fecha_inicio', 'alquiler_duracion', 'alquiler_fecha_fin',
    						'alquiler_codigo_responsable', 'alquiler_nombre_responsable', 'alquiler_texto'
    						],
			'cbioparcela' => ['cua_id','cue_id','tipo','fila','nume','bis']				
    	];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'obj_id' => 'Codigo de objeto',
            'nc' => 'Nomenclatura',
            'cua_id' => 'Codigo de cuadro',
            'cue_id' => 'Codigo de cuerpo',
            'tipo' => 'Tipo',
            'piso' => 'Piso',
            'fila' => 'Fila',
            'nume' => 'Nume',
            'cat' => 'Categoria',
            'deleg' => 'Delegacion',
            'sup' => 'Superficie',
            'tomo' => 'Tomo',
            'folio' => 'Folio',
            'fchcompra' => 'Fecha de compra',
            'fchingreso' => 'Fecha de ingreso',
            'fchvenc' => 'Fecha de vencimiento',
            'exenta' => 'Si se encuentra exento',
            'edicto' => 'Numero de edicto',
            'cod_ant' => 'Codigo anterior',
            'adesde' => 'Año desde',
            'cdesde' => 'Cuota desde',
            'ahasta' => 'Año hasta',
            'chasta' => 'Cuota hasta',
            'alquiler_titulo' => 'Título',
            'alquiler_superficie' => 'Superficie',
            'alquiler_fecha' => 'Fecha de alquiler',
            'alquiler_fecha_inicio' => 'Fecha de inicio',
            'alquiler_duracion' => 'Duración',
            'alquiler_fecha_fin' => 'Fecha de fin',
            'alquiler_codigo_responsable' => 'Código del responsable',
            'alquiler_texto' => 'Texto'
        ];
    }
    
    /**
     * 
     */
    public function beforeValidate(){
    	
    	if(!parent::beforeValidate())
    		return false;
    	
    	if($this->scenario == 'traslado'){
    		$this->perdesde = $this->adesde * 1000 + $this->cdesde;
    		$this->perhasta = $this->ahasta * 1000 + $this->chasta;
    	}
    	
    	if($this->scenario == 'alquiler' && isset($this->alquiler_fecha_inicio) && trim($this->alquiler_fecha_inicio) != '')
    		$this->alquiler_fecha_inicio= Fecha::usuarioToBD($this->alquiler_fecha_inicio);
    		
    	
    	if(in_array($this->getScenario(), ['insert', 'update'])){
    		
    		if(isset($this->fchcompra) && trim($this->fchcompra) !== '') $this->fchcompra= Fecha::usuarioToBD($this->fchcompra);
    		if(isset($this->fchingreso) && trim($this->fchingreso) !== '') $this->fchingreso= Fecha::usuarioToBD($this->fchingreso);
    		if(isset($this->fchvenc) && trim($this->fchvenc) !== '') $this->fchvenc= Fecha::usuarioToBD($this->fchvenc);
    	}
    	
		$this->nc = $this->cua_id . $this->cue_id . $this->tipo . str_pad($this->fila, 2, "0", STR_PAD_LEFT) . str_pad($this->nume, 5, "0", STR_PAD_LEFT) . $this->bis;
    	
    	return true;
    }
    
    public function afterFind(){
    	
    	$this->tipo_nom= utb::getCampo('cem_tipo', "cod = '$this->tipo'", 'nombre');
    	$this->cuadro_nom= utb::getCampo('cem_cuadro', "cua_id = '$this->cua_id'", 'nombre');
    	$this->cuerpo_nom= utb::GetCampo('cem_cuerpo', "cue_id = '$this->cue_id' And cua_id = '$this->cua_id'", 'nombre');
    }
    
    /**
     * Si es un registro nuevo, lo inserta. Si es un registro existente, lo modifica
     */
    public function grabar(){
    	
    	$this->scenario = $this->isNewRecord ? 'insert' : 'update';
    	
    	if(!$this->validate())	
    		return false;
    	    		
    	
    	$fchcompra = ($this->fchcompra == null || trim($this->fchcompra) == '') ? 'null' : $this->fchcompra;
    	$fchvenc = ($this->fchvenc == null || trim($this->fchvenc) == '') ? 'null' : $this->fchvenc;
    	$fchingreso = ($this->fchingreso == null || trim($this->fchingreso) == '') ? 'null' : $this->fchingreso;
    	
    	if($this->isNewRecord){
    								
    		$sql = "Insert Into cem(obj_id, nc, cua_id, cue_id, tipo, piso, fila, nume, bis, cat, deleg, sup, tomo, folio, fchcompra, fchingreso, fchvenc, exenta, edicto, cod_ant)" .
    				" Values('$this->obj_id', '$this->nc', '$this->cua_id', '$this->cue_id', '$this->tipo', $this->piso, $this->fila, $this->nume, '$this->bis', $this->cat, $this->deleg, $this->sup," .
    				" '$this->tomo', '$this->folio', " .
    				"" . ($fchcompra === 'null' ? $fchcompra : "'$fchcompra'") . ", " .
    				"" . ($fchingreso === 'null' ? $fchingreso : "'$fchingreso'") . ", " .
    				"" . ($fchvenc === 'null' ? $fchvenc : "'$fchvenc'") . ", " .
    				"$this->exenta, $this->edicto, '$this->cod_ant')";
    		
    		
    		$cmd = Yii::$app->db->createCommand($sql);
    		
    		$res = $cmd->execute();
    		
    		if($res == 0)
    			$this->addError($this->obj_id, 'Ocurrio un error al intentar grabar los datos');
    		
    		return $res > 0;
    	}
    	else{
    		
    		$sql = "Update cem Set nc = '$this->nc', cua_id = '$this->cua_id', cue_id = '$this->cue_id', tipo = '$this->tipo'" .
    				", piso = $this->piso, fila = $this->fila, nume = $this->nume, bis = '$this->bis', cat = $this->cat, deleg = $this->deleg, " .
    				"fchcompra = " . ($fchcompra === 'null' ? $fchcompra : "'$fchcompra'") . ", " .
    				"fchingreso = " . ($fchingreso === 'null' ? $fchingreso : "'$fchingreso'") . ", " .
    				"fchvenc = " . ($fchvenc === 'null' ? $fchvenc : "'$fchvenc'") . ", " .
    				"sup = $this->sup, tomo = '$this->tomo', folio = '$this->folio', exenta = $this->exenta, edicto = $this->edicto, cod_ant = '$this->cod_ant' Where obj_id = '$this->obj_id'";
    				
    		return Yii::$app->db->createCommand($sql)->execute() > 0;
    	}
    	
    	//$this->addError($this->obj_id, "no ejecuta la grabacion");
    	return false;
    }
    
    /**
     * Determina si el cuadro elegido requiere que se ingrese piso, fila y nume
     */
    public function requiereAdicionales(){
    	
    	$sql = "Select piso, fila, nume, bis From cem_cuadro Where cua_id = '$this->cua_id'";
    	
    	$res = Yii::$app->db->createCommand($sql)->queryOne();
    	
    	if($res != false){
    		
    		if($res['piso'] == 1 && $this->piso == null) $this->addError($this->piso, "Debe ingresar piso");
    		if($res['fila'] == 1 && ($this->fila == null || trim($this->fila) == '')) $this->addError($this->fila, "Debe ingresar fila");
    		if($res['nume'] == 1 && $this->nume == null) $this->addError($this->nume, "Debe ingresar nume");
			if($res['bis'] == 1 && $this->bis == null) $this->addError($this->bis, "Debe ingresar bis");
    	}
    	else $this->addError($this->cua_id, "El cuadro no existe");
    }
    
    /**
     * Busca cuentas de cementerio a partir de una condicion
     * 
     * @param string $cond Condicion de busqueda
     * @param string $order Criterio de ordenamiento
     * @param boolean $fallecido true si se deben buscar los datos de fallecidos, false si deben obtenerse los datos de las cuentas de cementerio
     * 
     * @return SqlDataProvider Con el resultado de busqueda
     */
    public static function buscarCemAv($cond = '', $order = 'obj_id Asc', $fallecido = false, $cantidad = 40){
    	
    	$vista = "v_cem";
    	
    	if($fallecido)
    		$vista = "v_cem_fall";
    	
    	$sql = "Select * From $vista";
    	$sqlCount = "Select Count(*) From $vista";
    	
    	if($cond != ''){
    		$sql .= " Where " . $cond;
    		$sqlCount .= " Where " . $cond;
    	}
    	
    	$sql .= " Order By " . $order;
    	
    	$count = Yii::$app->db->createCommand($sqlCount)->queryScalar();
    	
    	
    	return new SqlDataProvider([
    		'sql' => $sql,
    		'totalCount' => $count,
    		'key' => 'obj_id',
    		'pagination' => [
    			'pageSize' => $cantidad,
    			'totalCount' => $count,
    		]
    	]);
    }
    
    /**
     * Obtiene los datos del cuadro
     * 
     * @param string $cuadro_id Cuadro del que se quieren obtener los datos
     * 
     * @return Array Arreglo asociativo con los datos del cuadro
     */
    public static function getCuadro($cua_id = ''){
    	    	
    	$sql = "Select * From cem_cuadro Where cua_id = '$cua_id'";
    	
    	return Yii::$app->db->createCommand($sql)->queryOne();
    }
    
    /**
     * Obtiene los datos del cuerpo
     * 
     * @param string $cuadro_id Cuadro al que pertenece el cuerpo
     * @param string $cuerpo_id Cuerpo del que se quieren obtener los datos
     * 
     * @return Array Arreglo asociativo con los datos del cuerpo 
     */
    public static function getCuerpo($cua_id = '', $cue_id = ''){
    	
    	$sql = "Select * From cem_cuerpo Where trim(both ' ' from cua_id) = '$cua_id'";
    	
    	return Yii::$app->db->createCommand($sql)->queryOne();
    }
    
    /**
     * Determina si un cuadro tiene cuerpos asociados
     * 
     * @param string $cuadro_id Cuadro del que se quiere saber si tiene cuerpos
     * 
     * @return boolean true si tiene cuerpos asociados, false de lo contrario
     */
    public static function cuadroTieneCuerpos($cua_id = ''){
    	
    	$sql = "Select count(*) From cem_cuerpo Where trim(both ' ' from cua_id) = trim(both ' ' from '$cua_id')";
    	
    	return Yii::$app->db->createCommand($sql)->queryScalar() > 0;
    }
    
    /**
     * Obtiene los fallecidos asociados al cementerio
     * 
     * @param string $obj_id Codigo del cementerio
     * 
     * @return Array Arreglo con los fallecidos asociados al cementerio. Cada elemento del arreglo es a su vez un arreglo con los datos de un fallecido
     */
    public function getFallecidos($obj_id = ''){
    	
    	$sql = "Select * From v_cem_fall Where obj_id = '$obj_id' Order By apenom";
    	
    	return Yii::$app->db->createCommand($sql)->queryAll();
    }
    
    /**
     * Obtiene los alquileres del cementerio
     * 
     * @param string $obj_id Codigo del cementerio
     * 
     * @return Array Arreglo con los alquileres asociados al cementerio. Cada elementeo del arreglo es a su vez un arreglo con los datos de un alquiler
     */
    public function getAlquileres($obj_id = ''){
    	
    	$sql = "Select * From v_cem_alq Where obj_id = '$obj_id' And est <> 'B'";
    	
    	return Yii::$app->db->createCommand($sql)->queryAll();
    }
    
    /**
     * Realiza el traslado de una cuenta a otra
     * 
     * @param string $cuentaDestino Objeto destino
     * @param int $perdesde Periodo desde
     * @param int $perhasta Periodo hasta
     * 
     * @return 
     */
    public function trasladarCuenta($cuentaDestino){
    	
    	$this->scenario = 'traslado';
    	
    	if(!$this->validate())
			return false;
			    	
		
			    	
    	$sql = "Select sam.uf_Cem_TrasCtaCte('$this->obj_id', '$cuentaDestino', $this->perdesde, $this->perhasta, " . Yii::$app->user->id . ")";
    	
    	try{
    		Yii::$app->db->createCommand($sql)->queryScalar();	
    	}
    	catch (\Exception $e){
    		$this->addError($this->obj_id, DBException::getMensaje($e));
    		return false;
    	}
    	
    	return true;
    }
    
    public function Imprimir($id,&$sub1,&$sub2,&$sub3)
    {
    	$sql = "Select * From V_Cem Where obj_id='".$id."'";
   		$array = Yii::$app->db->createCommand($sql)->queryAll();
   		
   		$sql = "Select * From v_objeto_tit where obj_id='".$id."' and est='A'";
   		$sub1 = Yii::$app->db->createCommand($sql)->queryAll();
   		
   		$sql = "Select * From V_Cem_Fall where obj_id='".$id."' Order by FchDef";
   		$sub2 = Yii::$app->db->createCommand($sql)->queryAll();
   		
   		$sql = "Select * From V_Cem_Alq where obj_id='".$id."' Order by FchIni";
   		$sub3 = Yii::$app->db->createCommand($sql)->queryAll();
   		   		
   		return $array;
    }
    
    /**
     * Carga los atributos del modelo correspondientes al alquiler
     */
    public function cargarAlquiler(){
    	
    	$sql= "Select * From v_cem_alq Where obj_id = '$this->obj_id' And est = 'A'";
    	$datos= Yii::$app->db->createCommand($sql)->queryOne();
    	
    	if($datos !== false){
    		
    		$this->alquiler_titulo= $datos['titulo'];
    		$this->alquiler_codigo_responsable= $datos['resp'];
    		$this->alquiler_nombre_responsable= $datos['resp_nom'];
    		$this->alquiler_fecha= $datos['fchalq'];
    		$this->alquiler_fecha_inicio= $datos['fchini'];
    		$this->alquiler_fecha_fin= $datos['fchfin'];
    		$this->alquiler_duracion= $datos['duracion'];
    		$this->alquiler_codigo= $datos['alq_id'];
    		
    		//la superficie se carga del objeto
    		$this->alquiler_superficie= $this->sup;
    	}
    }
    
    public function grabarAlquiler(){
    	
    	$this->setScenario('alquiler');
    	if(!$this->validate()) return false;
    	
    	$trans= Yii::$app->db->beginTransaction();
    	
    	//proximo codigo
    	$sql= "Select nextval('seq_cem_alq_id')";
    	$codigo= Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	//insercion
    	$sql= "Insert Into cem_alquiler(alq_id, obj_id, titulo, fchalq, fchini, fchfin, duracion, resp, est, fchmod, usrmod)" .
    			" Values($codigo, '$this->obj_id', '$this->alquiler_titulo', current_timestamp, '$this->alquiler_fecha_inicio'," .
    			" '$this->alquiler_fecha_fin', $this->alquiler_duracion, '$this->alquiler_codigo_responsable', 'A', current_timestamp, " . Yii::$app->user->id . ")";
    			
    	$res= Yii::$app->db->createCommand($sql)->execute() > 0;
    	
    	if(!$res){
    		$this->addError($this->obj_id, 'Ocurrió un error al intentar realizar la acción');
    		$trans->rollBack();
    		return false;
    	}
    	
    	//se actualiza el estado de los alquileres anteriores a renovado
    	$sql= "Update cem_alquiler Set est = 'R' Where obj_id = '$this->obj_id' And est <> 'B' And alq_id <> $codigo";
    	Yii::$app->db->createCommand($sql)->execute();
    	
    	//se actualiza el estado del objeto a reservado
    	$sql= "Update objeto Set est = 'R' Where obj_id = '$this->obj_id' And est = 'D'";
    	Yii::$app->db->createCommand($sql)->execute();
    	
    	//se actualiza la fecha de vencimiento de la cuenta y la superficie
    	$sql= "Update cem Set fchvenc = '$this->alquiler_fecha_fin', sup = $this->alquiler_superficie Where obj_id = '$this->obj_id'";
    	Yii::$app->db->createCommand($sql)->execute();
    	
    	$trans->commit();
    	return true;
    }
    
    public function fechaInicioAlquilerEsFuturo(){
    	
    	if(!Fecha::esFuturo($this->alquiler_fecha_inicio, true))
    		$this->addError($this->alquiler_fecha_inicio, 'El inicio de la fecha de alquiler no debe ser una fecha pasada');
    }
    
    public function existeTexto(){
    	
    	$sql= "Select Exists (Select 1 From texto Where texto_id = $this->alquiler_texto)";
    	$res= Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	if(!$res) $this->addError($this->alquiler_text, 'El texto no existe');
    }
    
    public function existeResponsable(){
    	
    	$sql= "Select Exists (Select 1 From persona Where obj_id = '$this->alquiler_codigo_responsable')";
    	$existe= Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	if(!$existe) $this->addError($this->alquiler_codigo_responsable, 'El responsable no existe');
    }
	
	public function getNombreResponsable(){
	
		$sql= "Select num_nom from v_cem Where obj_id = '$this->obj_id'";
    	$responsable = Yii::$app->db->createCommand($sql)->queryScalar();
		
		return $responsable;
	}
}
