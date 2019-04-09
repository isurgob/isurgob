<?php

namespace app\models\ctacte;
use yii\data\SqlDataProvider;
use yii\data\ActiveDataProvider;

use Yii;

/**
 * This is the model class for table "resol_tabla_col".
 *
 * @property integer $tabla_id
 * @property integer $orden
 * @property string $nombre
 * @property integer $param
 * @property string $compara

 */
class ResolTablaCol extends \yii\db\ActiveRecord
{    
    
	public $param;
	public $aplicable;
	
	private $strOrden;
	
	public function __construct($aplicable = false){
		
		parent::__construct();
		
		$this->param= '';
		$this->aplicable= $aplicable;
		
		$this->strOrden= ['paramstr', 'param1', 'param2', 'param3', 'param4', 'param5'];
	}
     
    public static function tableName()
    {
        return 'resol_tabla_col';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $ret= [];
        
        /**
         * CAMPOS REQUERIDOS
         */
        $ret[]= [
        		['tabla_id', 'nombre'],
        		'required',
        		'on' => ['insert']
        		];
        		
        $ret[]= [
        		['compara'],
        		'required',
        		'on' => ['insert'],
        		'message' => 'Elija un {attribute}'
        		];
        
        $ret[]= [
        		'tabla_id',
        		'integer',
        		'min' => 1,
        		'on' => ['insert']
        		];
        		
        $ret[]= [
        		'nombre',
        		'string',
        		'max' => 15,
        		'on' => ['insert']
        		];
        		
        $ret[]= [
        		'compara',
        		'string',
        		'max' => 2,
        		'on' => ['insert']
        		];
        		
        $ret[]= [
        		'aplicable',
        		'boolean',
        		'falseValue' => 0,
        		'trueValue' => 1,
        		'on' => ['insert']
        		];
        
        $ret[]= [
        		'aplicable',
        		'default',
        		'value' => 0,
        		'on' => ['insert']
        		];
        
        return $ret;
    }
    
    public function scenarios(){
    	
    	return [
    			'insert' => ['tabla_id', 'nombre', 'param', 'compara', 'aplicable']
    			];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tabla_id' => 'Código de tabla',
            'orden' => 'Orden',
            'nombre' => 'Nombre',
            'param' => 'Parámetro',
            'compara'=>'Método de comparación'

        ];
    }   

	public function afterFind(){
		
		$this->param= $this->strOrden[$this->orden];
		$this->aplicable= true;
	}
	
	/**
	 * Borra todas las columnas asociadas a la tabla dada.
	 * 
	 * @param int $tabla_id - Codigo de la tabla a la cual se le deben borrar las columnas.
	 * 
	 * @return boolean - Si se han eliminado correctamente las columnas.
	 */
	public static function borrarTodas($tabla_id){
		
		$sql= "Delete From resol_tabla_col Where tabla_id = $tabla_id";
		Yii::$app->db->createCommand($sql)->execute();
		return true;
	}

	/**
	 * Dado un arreglo con modelos ResolTablaCol y que el tamaño del arreglo sea menor a 6, se completa el arreglo (hasta 6 elementos) cons columnas sin datos.
	 * 
	 * @param Array $lista - Arreglo con modelos ResolTablaCol.
	 * 
	 * @return Array - La lista completa.
	 */
	public static function completarLista($lista, $orden){
		
		$total= count($lista);
		if($total === 0) return [];

		$ret= [];

		foreach($orden as $clave)
			if(!array_key_exists($clave, $lista))
				$ret[$clave]= new ResolTablaCol(false);
			else $ret[$clave]= $lista[$clave];
		
		return $ret;
	}
}
