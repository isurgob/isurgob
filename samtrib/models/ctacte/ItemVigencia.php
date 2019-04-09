<?php

namespace app\models\ctacte;

use Yii;
use PDO;
use yii\data\SqlDataProvider;
use yii\data\Sort;

use yii\db\Expression;
use yii\db\Query;

use yii\base\Exception;

use app\utils\helpers\DBException;
use app\utils\db\utb;

/**
 * This is the model class for table "item_vigencia".
 *
 * @property integer $adesde
 * @property integer $cdesde
 * @property integer $ahasta
 * @property integer $chasta
 * @property integer $item_id
 * @property integer $perdesde
 * @property integer $perhasta
 * @property integer $tcalculo
 * @property string $monto
 * @property string $porc
 * @property string $minimo
 * @property string $paramnombre1
 * @property string $paramnombre2
 * @property string $paramnombre3
 * @property string $paramnombre4
 * @property string $paramcomp1
 * @property string $paramcomp2
 * @property string $paramcomp3
 * @property string $paramcomp4
 * @property string $fchmod
 * @property integer $usrmod
 *
 * @property number $montoasoc
 * @property number $param1asoc
 * @property number $param2asoc
 * @property number $param3asoc
 * @property number $param4asoc
 */
class ItemVigencia extends \yii\db\ActiveRecord
{
	public $adesde = null;
	public $ahasta = null;
	public $cdesde = null;
	public $chasta = null;

	public $masoc = null;
	public $p1asoc = null;
	public $p2asoc = null;
	public $p3asoc = null;
	public $p4asoc = null;


	/**
	 *
	 *
	 * YII
	 *
	 *
	 */


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item_vigencia';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
    	$indice = 0;

    	$ret = [];

    	/*
    	 * campos requeridos
    	 */
    	 $ret[$indice++] = [
    	 					'item_id',
    	 					'required',
    	 					'on' => ['select', 'insert', 'update', 'delete', 'asoc']
    	 					];


    	$ret[$indice++] = [
    						['adesde', 'cdesde'],
    						'required',
    						'on' => ['insert', 'update', 'delete', 'select', 'asoc']
    						];

    	$ret[$indice++] = [
    						['ahasta', 'chasta'],
    						'required',
    						'on' => ['insert', 'update', 'asoc']
    						];



    	$ret[$indice++] = [
    						['tcalculo'],
    						'required',
    						'on' => ['insert', 'update']
    						];


    	$ret[$indice++] = [
    						'paramcomp1',
    						'required',
    						'when' => function($model){return ( isset($model->paramnombre1) && trim($model->paramnombre1) != '' && $model->tcalculo == 6 ); },
    						'on' => ['insert', 'update']
    					];

    	$ret[$indice++] = [
    						'paramcomp2',
    						'required',
    						'when' => function($model){return ($model->paramnombre2 != null && trim($model->paramnombre2) != '' && $model->tcalculo == 6 ); },
    						'on' => ['insert', 'update']
    					];

    	$ret[$indice++] = [
    						'paramcomp3',
    						'required',
    						'when' => function($model){return ($model->paramnombre3 != null && trim($model->paramnombre3) != '' && $model->tcalculo == 6 ); },
    						'on' => ['insert', 'update']
    					];

    	$ret[$indice++] = [
    						'paramcomp4',
    						'required',
    						'when' => function($model){return ($model->paramnombre4 != null && trim($model->paramnombre4) != '' && $model->tcalculo == 6 ); },
    						'on' => ['insert', 'update']
    					];


    	/*
    	 * fin campos requeridos
    	 */


    	 /*
    	  * tipos y rango de datos
    	  */
    	$ret[$indice++] = [
    						'item_id',
    						'integer',
    						'min' => 1,
    						'max' => 999999999,
    						'on' => ['select', 'update', 'insert', 'delete']
    						];

    	$ret[$indice++] = [
    						'tcalculo',
    						'integer',
    						'min' => 0,
    						'on' => ['insert', 'update']
    					];

    	$ret[$indice++] = [
    					['adesde', 'ahasta'],
    					'integer',
    					'min' => 1900,
    					'max' => 9999,
    					'on' => ['select', 'insert', 'update', 'delete']
    					];

    	$ret[$indice++] = [
    					['cdesde', 'chasta'],
    					'integer',
    					'min' => 0,
    					'max' => 999,
    					'on' => ['select', 'insert', 'update', 'delete']
    					];

    	/*$ret[$indice++] = [
    						['minimo', 'monto'],
    						'number',
    						'min' => 0,
    						'on' => ['insert', 'update']
    						];*/

    	$ret[$indice++] =  [
    						'porc',
    						'number',
    						'min' => -100,
    						'max' => 100,
    						'on' => ['insert', 'update']
    						];


    	$ret[$indice++] = [
    						['paramnombre1', 'paramnombre2', 'paramnombre3', 'paramnombre4'],
							'string',
							'max' => 15,
							'on' => ['insert', 'update']
    						];

    	$ret[$indice++] = [
    						['paramcomp1', 'paramcomp2', 'paramcomp3', 'paramcomp4'],
    						'string',
    						'max' => 2,
    						'on' => ['insert', 'update']
    						];


    	$ret[$indice++] = [
    						['paramcomp1', 'paramcomp2', 'paramcomp3', 'paramcomp4'],
    						'in',
    						'range' => ['<', '>', '=', '<>', '<=', '>='],
    						'strict' => true,
    						'on' => ['insert', 'update']
    					];


    	$ret[$indice++] = [
    					['p1asoc', 'p2asoc', 'p3asoc', 'p4asoc', 'masoc'],
    					'number',
    					'min' => 0,
    					'on' => ['asoc']
    					];
    	/*
    	 * fin tipo y rango de daots
    	 */

    	 /*
    	  * valores por defecto
    	  */
    	$ret[$indice++] = [
    						['paramnombre1', 'paramnombre2', 'paramnombre3', 'paramnombre4'],
    						'default',
    						'value' => '',
    						'on' => ['insert', 'update']
    						];

    	$ret[$indice++] = [
    					'paramcomp1',
    					'default',
    					'value' => '',
    					'on' => ['insert', 'update']
    					];

    	$ret[$indice++] = [
    					'paramcomp2',
    					'default',
    					'value' => '',
    					'on' => ['insert', 'update']
    					];

    	$ret[$indice++] = [
    					'paramcomp3',
    					'default',
    					'value' => '',
    					'on' => ['insert', 'update']
    					];

    	$ret[$indice++] = [
    					'paramcomp4',
    					'default',
    					'value' => '',
    					'on' => ['insert', 'update']
    					];

    	$ret[$indice++] = [
    						['monto', 'minimo', 'porc'],
    						'default',
    						'value' => 0,
    						'on' => ['insert', 'update']
    						];

    	$ret[$indice++] = [
    					['p1asoc', 'p2asoc', 'p3asoc', 'p4asoc', 'masoc'],
    					'default',
    					'value' => 0,
    					'on' => ['asoc']
    					];

    	/*
    	  * fin valores por defecto
    	  */

    	  /*
    	   * validaciones de base de datos
    	   */
    	$ret[$indice++] = [
    						'tcalculo',
    						'validarTipoCalculo',
    						'on' => ['insert', 'update']
    						];


    	$ret[$indice++] = [
    						'item_id',
    						'validarItem',
    						'on' => ['insert', 'update', 'select']
    						];

    	$ret[$indice++] = [
    						['perdesde', 'perhasta'],
    						'periodoValido',
    						'when' => function($model){return !$this->hasErrors();},
    						'on' => ['insert', 'update']
    						];



    	$ret[$indice++] = [
    						'perdesde',
    						'filter',
    						'filter' => function(){return $this->adesde * 1000 + $this->cdesde;},
    						'skipOnArray' => true,
    						'when' => function($model){return ($model->adesde != null && $model->cdesde != null);},
    						'on' => ['insert', 'update', 'asoc']
    						];

    	$ret[$indice++] = [
    						'perhasta',
    						'filter',
    						'filter' => function(){return $this->ahasta * 1000 + $this->chasta;},
    						'skipOnArray' => true,
    						'when' => function($model){return ($model->ahasta != null && $model->chasta != null);},
    						'on' => ['insert', 'update', 'asoc']
    						];
    	  /*
    	   * fin validaciones de base de datos
    	   */

        /*
         * FILTROS
         */
        $ret[$indice++] = [
        				'porc',
        				'filter',
        				'filter' => function($valor){return $valor / 100;}
           				];

        $ret[$indice++] = [
        				'paramcomp1',
        				'filter',
        				'filter' => function(){return '';},
        				'when' => function($model){return ($model->paramnombre1 == null || trim( $model->paramnombre1 ) == '' ); },
        				'on' => ['insert', 'update']
        				];

       	$ret[$indice++] = [
        				'paramcomp2',
        				'filter',
        				'filter' => function(){return '';},
        				'when' => function($model){return ($model->paramnombre2 == null || trim( $model->paramnombre2 ) == '' ); },
        				'on' => ['insert', 'update']
        				];

        $ret[$indice++] = [
        				'paramcomp3',
        				'filter',
        				'filter' => function(){return '';},
        				'when' => function($model){return ($model->paramnombre3 == null || trim( $model->paramnombre3 ) == '' ); },
        				'on' => ['insert', 'update']
        				];


        $ret[$indice++] = [
        				'paramcomp4',
        				'filter',
        				'filter' => function(){return '';},
        				'when' => function($model){return ($model->paramnombre4 == null || trim( $model->paramnombre4 ) == '' ); },
        				'on' => ['insert', 'update']
        				];


        $ret[$indice++] = [
        				['paramnombre1', 'paramnombre2', ' paramnombre3', 'paramnombre4', 'paramcomp1', 'paramcomp2', 'paramcomp3', 'paramcomp4'],
        				'filter',
        				'filter' => 'trim',
        				'on' => ['insert', 'update']
        				];

        /*
         * FIN FILTROS
         */

        return $ret;
    }

	public function scenarios()
	{
		return [
				'insert' => ['item_id', 'adesde', 'cdesde', 'ahasta', 'chasta', 'perdesde', 'perhasta', 'tcalculo', 'minimo', 'monto', 'porc',
							'paramnombre1', 'paramnombre2', 'paramnombre3', 'paramnombre4', 'paramcomp1', 'paramcomp2', 'paramcomp3', 'paramcomp4'],
				'update' => ['item_id', 'adesde', 'cdesde', 'ahasta', 'chasta', 'perdesde', 'perhasta', 'tcalculo', 'minimo', 'monto', 'porc',
							'paramnombre1', 'paramnombre2', 'paramnombre3', 'paramnombre4', 'paramcomp1', 'paramcomp2', 'paramcomp3', 'paramcomp4'],
				'delete' => ['item_id', 'adesde', 'cdesde', 'perdesde'],
				'select' => ['item_id', 'adesde', 'cdesde', 'ahasta', 'chasta', 'perdesde', 'perhasta', 'tcalculo', 'minimo', 'monto', 'porc',
							'paramnombre1', 'paramnombre2', 'paramnombre1', 'paramnombre2', 'paramnombre3', 'paramnombre4', 'paramcomp1', 'paramcomp2', 'paramcomp3', 'paramcomp4'],

				'asoc' => ['item_id', 'adesde', 'cdesde', 'perdesde', 'ahasta', 'chasta', 'perhasta', 'p1asoc', 'p2asoc', 'p3asoc', 'p4asoc', 'masoc']
		];
	}


	public function beforeValidate()
	{
		$this->perdesde = $this->adesde * 1000 + $this->cdesde;

		if($this->ahasta != null && $this->chasta != null)
			$this->perhasta = $this->ahasta * 1000 + $this->chasta;

		return true;
	}


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_id' => 'Ítem',
            'perdesde' => 'Desde',
            'perhasta' => 'Hasta',
            'tcalculo' => 'Tipo de cálculo',
            'monto' => 'Monto',
            'porc' => 'Porc. ',
            'minimo' => 'Mínimo',
            'paramnombre1' => 'Parámetro 1',
            'paramnombre2' => 'Parámetro 2',
            'paramnombre3' => 'Parámetro 3',
            'paramnombre4' => 'Parámetro 4',
            'paramcomp1' => 'Comparación parametro 1',
            'paramcomp2' => 'Comparación parametro 2',
            'paramcomp3' => 'Comparación parametro 3',
            'paramcomp4' => 'Comparación parametro 4',
            'fchmod' => 'Fecha de modificación',
            'usrmod' => 'Código de usuario que modificó',
            'adesde' => 'Año desde',
            'cdesde' => 'Período desde',
            'ahasta' => 'Año hasta',
            'chasta' => 'Período hasta',

            'masoc' => 'Monto',
            'p1asoc' => 'P1',
            'p2asoc' => 'P2',
            'p3asoc' => 'P3',
            'p4asoc' => 'P4'
        ];
    }



   public function afterFind()
   {

		$this->adesde = intval($this->perdesde / 1000);
		$this->cdesde = $this->perdesde - $this->adesde * 1000;

		$this->ahasta = intval($this->perhasta / 1000);
		$this->chasta = $this->perhasta - $this->ahasta * 1000;

		if($this->porc != null)
			$this->porc *= 100;
   }

    /**
     *
     *
     * ABM
     *
     *
     */

    /**
     * Graba el registro si es nuevo o lo modifica si no lo es.
     *
     * @return boolean - true si se ha grabado o modificado el registro con exito, false de lo contrario
     */
    public function grabar()
    {
    	$this->scenario = $this->isNewRecord ? 'insert' : 'update';


    	if(!$this->validate())
    		return false;

    	if($this->isNewRecord)
    	{

    		$columnas = "item_id, perdesde, perhasta, tcalculo, monto, porc, minimo, paramnombre1, paramnombre2, paramnombre3, paramnombre4, paramcomp1, paramcomp2, paramcomp3, paramcomp4, fchmod, usrmod";

    		$sql = "Insert Into item_vigencia($columnas) Values(:_item_id, :_perdesde, :_perhasta, :_tcalculo, :_monto, :_porc, :_minimo, :_param1, :_param2, :_param3, :_param4, :_comp1, :_comp2, :_comp3, :_comp4, current_timestamp, ". Yii::$app->user->id .")";


    		$res = 0;

    		try{

    			$cmd = Yii::$app->db->createCommand($sql, [
													':_item_id' => $this->item_id,
													':_perdesde' => $this->perdesde,
													':_perhasta' => $this->perhasta,
													':_tcalculo' => $this->tcalculo,
													':_monto' => $this->monto,
													':_porc' => $this->porc,
													':_minimo' => $this->minimo,
													':_param1' => $this->paramnombre1,
													':_param2' => $this->paramnombre2,
													':_param3' => $this->paramnombre3,
													':_param4' => $this->paramnombre4,
													':_comp1' => $this->paramcomp1,
													':_comp2' => $this->paramcomp2,
													':_comp3' => $this->paramcomp3,
													':_comp4' => $this->paramcomp4
										    		]);

    			$res = $cmd->execute();
    		}
    		catch(Exception $e)
    		{
    			$this->addError($this->item_id, $e);//'Ocurrio un error al intentar grabar el registro.');

    			return false;
    		}

    		return $res > 0;
    	}
    	else
    	{
    		$sql = "Update item_vigencia set perhasta = :_perhasta, tcalculo = :_tcalculo, monto = :_monto, porc = :_porc, minimo = :_minimo,";
    		$sql .= "paramnombre1 = :_param1, paramnombre2 = :_param2, paramnombre3 = :_param3, paramnombre4 = :_param4, ";
    		$sql .= " paramcomp1 = :_comp1, paramcomp2 = :_comp2, paramcomp3 = :_comp3, paramcomp4 = :_comp4, fchmod = current_timestamp, usrmod = " . Yii::$app->user->id;
    		$sql .= " Where item_id = :_item_id And perdesde = :_perdesde";

    		$res = -1;

    		try{

    			$cmd = Yii::$app->db->createCommand($sql, [
    												':_perhasta' => $this->perhasta,
    												':_tcalculo' => $this->tcalculo,
    												':_monto' => $this->monto,
    												':_porc' => $this->porc,
    												':_minimo' => $this->minimo,
    												':_param1' => $this->paramnombre1,
    												':_param2' => $this->paramnombre2,
    												':_param3' => $this->paramnombre3,
    												':_param4' => $this->paramnombre4,
    												':_comp1' => $this->paramcomp1,
    												':_comp2' => $this->paramcomp2,
    												':_comp3' => $this->paramcomp3,
    												':_comp4' => $this->paramcomp4,
    												':_item_id' => $this->item_id,
    												':_perdesde' => $this->perdesde
    												]);

    			$res = $cmd->execute();
    		}
    		catch(Exception $e)
    		{
    			$this->addError($this->item_id, $e);//'Ocurrio un error al intentar modificar el registro.');
    			return false;
    		}

    		//se modifican el perhasta los valores asociados
    		if($res > 0)
    		{
    			$sql = "Update item_asoc Set perhasta = :_perhasta Where item_id = :_item_id And perdesde = :_perdesde";

    			$cmd = Yii::$app->db->createCommand($sql);

    			$cmd->bindValue(':_perhasta', $this->perhasta, PDO::PARAM_INT);
    			$cmd->bindValue(':_item_id', $this->item_id, PDO::PARAM_INT);
    			$cmd->bindValue(':_perdesde', $this->perdesde, PDO::PARAM_INT);


    			$cmd->execute();
    		}

    		return $res >= 0;
    	}

    	return false;
    }

    /**
     * Elimina el registro de la bse de datos.
     *
     * @return boolean - true si se ha podido eliminar el registro, false si ha ocurrido un error.
     */
    public function borrar()
    {
    	$this->scenario = 'delete';

    	if(!$this->validate())
    		return false;


    	try{
    		$sql = "Delete From item_vigencia Where item_id = :_item_id And perdesde = :_perdesde";

    		$cmd = Yii::$app->db->createCommand($sql, [':_item_id' => $this->item_id, ':_perdesde' => $this->perdesde]);

    		$res = $cmd->execute();
    	}
    	catch(Exception $e)
    	{
    		$this->addError($this->item_id, 'Ocurrio un error al intentar borrar el registo.');
    		return false;
    	}

    	return $res >= 0;
    }



    public function grabarValorAsoc()
    {

    	if($this->p1asoc <= 0 && $this->p2asoc <= 0 && $this->p3asoc <= 0 && $this->p4asoc <= 0)
    		return 'Debe ingresar algun valor';

    	$sql = "Select count(item_id) From item_asoc Where item_id = :_item_id And perdesde = :_perdesde And perhasta = :_perhasta And param1 = :_p1 And param2 = :_p2 And param3 = :_p3 And param4 = :_p4";
    	$cmd = Yii::$app->db->createCommand($sql);

    	$cmd->bindValue(':_item_id', $this->item_id, PDO::PARAM_INT);
    	$cmd->bindValue(':_perdesde', $this->perdesde, PDO::PARAM_INT);
    	$cmd->bindValue(':_perhasta', $this->perhasta, PDO::PARAM_INT);
    	$cmd->bindValue(':_p1', $this->p1asoc, PDO::PARAM_INT);
    	$cmd->bindValue(':_p2', $this->p2asoc, PDO::PARAM_INT);
    	$cmd->bindValue(':_p3', $this->p3asoc, PDO::PARAM_INT);
    	$cmd->bindValue(':_p4', $this->p4asoc, PDO::PARAM_INT);


    	$cantidad = $cmd->queryScalar();

    	if($cantidad > 0)
    		$sql = "Update item_asoc Set monto = :_monto, fchmod = current_timestamp, usrmod = :_usrmod Where item_id = :_item_id And perdesde = :_perdesde And perhasta = :_perhasta And param1 = :_p1 And param2 = :_p2 And param3 = :_p3 And param4 = :_p4";
 	  	else
       		$sql = "Insert Into item_asoc(item_id, perdesde, perhasta, param1, param2, param3, param4, monto, fchmod, usrmod) " .
    				" Values(:_item_id, :_perdesde, :_perhasta, :_p1, :_p2, :_p3, :_p4, :_monto, current_timestamp, :_usrmod)";


   		$cmd = Yii::$app->db->createCommand($sql);

    	$cmd->bindValue(':_item_id', $this->item_id, PDO::PARAM_INT);
    	$cmd->bindValue(':_perdesde', $this->perdesde, PDO::PARAM_INT);
    	$cmd->bindValue(':_perhasta', $this->perhasta, PDO::PARAM_INT);
    	$cmd->bindValue(':_p1', $this->p1asoc, PDO::PARAM_INT);
    	$cmd->bindValue(':_p2', $this->p2asoc, PDO::PARAM_INT);
    	$cmd->bindValue(':_p3', $this->p3asoc, PDO::PARAM_INT);
    	$cmd->bindValue(':_p4', $this->p4asoc, PDO::PARAM_INT);
    	$cmd->bindValue(':_monto', $this->masoc);
    	$cmd->bindValue(':_usrmod', Yii::$app->user->id, PDO::PARAM_INT);


    	$res = $cmd->execute();

    	return $res > 0 ? 'Datos grabados correctamente' : 'Error al intentar modificar o crear el valor asociado.';
    }

    public function eliminarValorAsoc()
    {

    	$sql = "Delete From item_asoc Where item_id = :_item_id And perdesde = :_perdesde And perhasta = :_perhasta And param1 = :_p1 And param2 = :_p2 And param3 = :_p3 And param4 = :_p4";

    	$cmd = Yii::$app->db->createCommand($sql);

    	$cmd->bindValue(':_item_id', $this->item_id, PDO::PARAM_INT);
    	$cmd->bindValue(':_perdesde', $this->perdesde, PDO::PARAM_INT);
    	$cmd->bindValue(':_perhasta', $this->perhasta, PDO::PARAM_INT);
    	$cmd->bindValue(':_p1', $this->p1asoc, PDO::PARAM_INT);
    	$cmd->bindValue(':_p2', $this->p2asoc, PDO::PARAM_INT);
    	$cmd->bindValue(':_p3', $this->p3asoc, PDO::PARAM_INT);
    	$cmd->bindValue(':_p4', $this->p4asoc, PDO::PARAM_INT);

    	return $cmd->execute() >= 0;
    }
    /**
     *
     *
     *
     * VALIDACIONES
     *
     *
     *
     *
     */

    /**
     * Valida que el tipo de calculo sea valido y exista, ademas que las variables correspondientes hayan sido establecidas.
     *
     * En caso de que la variable no este en la formula de calculo, se le asigna el valor '' a las cadenas de texto y 0 a los demas (que son numeros)
     */
    public function validarTipoCalculo()
    {
    	$formulas = utb::getAux('item_tfcalculo');

    	if(!array_key_exists($this->tcalculo, $formulas))
    	{
    		$this->addError($this->tcalculo, 'El tipo de calculo no existe.');
    		return;
    	}

    	$nombre = $formulas[$this->tcalculo];
    	$variables = ['monto' => 'monto', 'porc' => 'porc', 'param1' => 'paramnombre1', 'param2' => 'paramnombre2', 'param3' => 'paramnombre3', 'param4' => 'paramnombre4',
    				'comp1' => 'paramcomp1', 'comp2' => 'paramcomp2', 'comp3' => 'paramcomp3' , 'comp4' => 'paramcomp4'
    				];

    	$mensaje = ['monto' => 'monto', 'porc' => 'porc', 'param1' => 'parametro 1', 'param2' => 'parametro 2', 'param3' => 'parametro 3', 'param4' => 'parametro 4',
    				'comp1' => 'comparación parametro 1', 'comp2' => 'comparación parametro 2', 'comp3' => 'comparación parametro 3', 'comp4' => 'comparación parametro 4'];

    	$isString = ['param1', 'param2', 'param3', 'param4', 'comp1', 'comp2', 'comp3', 'comp4'];
    	$hasDefault = ['monto', 'porc'];


    	if(strtolower($nombre) != 's/tabla asoc.')
    	{
    		foreach($variables as $campo => $nombreVar)
    		{
    			if(stripos($nombre, $campo) !== false)
    			{
    				if($this->$nombreVar == null && !in_array($campo, $hasDefault))
    					$this->addError($nombreVar, 'Debe completar el campo ' . $mensaje[$campo]);
    				else if( in_array( $campo, $isString )  && trim($this->$nombreVar) == '' )
    					$this->addError($nombreVar, 'Debe completar el cammpo ' . $mensaje[$campo]);
    			}
    			else if( in_array( $campo, $isString ) )
    				$this->$nombreVar = '';
    				else $this->$nombreVar = 0;


    		}
    	}
    	else
    	{

    	}


    }

    /**
     * Valida que el codigo del item sea valido y que exista
     */
    public function validarItem()
    {
    	// "Select count(item_id) From item Where item_id = $this->item_id"
    	$res = 0;

    	$sql = "Select count(item_id) From item Where item_id = :_item_id";

    	try{
    		$cmd = Yii::$app->db->createCommand($sql, [':_item_id' => $this->item_id]);
    		$res = $cmd->queryScalar();
    	}
    	catch(Exception $e)
    	{
    		$this->addError($this->item_id, 'El código del item no existe');
    	}

    	if($res <= 0)
    		$this->addError($this->item_id, 'El código del item no existe');
    }

    /**
     * Determina si el periodo es valido
     *
     *
     */
    public function periodoValido()
    {
    	if($this->perdesde > $this->perhasta)
    	{
    		$this->addError($this->adesde, "El periodo hasta no puede ser menor al periodo desde");
    		return;
    	}


    	$sql = "Select count(*) From v_item_vigencia Where item_id = :_item_id And :_perdesde <= perhasta And :_perhasta >= perdesde ";

    	if(!$this->isNewRecord)
    		$sql .= " And perdesde <> :_perdesde";


    	$cmd = Yii::$app->db->createCommand($sql, [':_item_id' => $this->item_id, ':_perdesde' => $this->perdesde, ':_perhasta' => $this->perhasta]);
    	$res = $cmd->queryScalar();

    	if($res > 0)
    		$this->addError($this->adesde, 'Periodo invalido');
     }



    /**
     *
     *
     *
     * Metodos extras
     *
     *
     *
     *
     */

     /**
      * Obtiene un DataProvider con las vigencias del item provisto en $item_id
      *
      * @param int $item_id = 0 - Item del cual se quiere obtener las vigencias. El valor 0 establece que el DataProvider contendra todas las vigencias.
      *
      * @return DataProvider|null - DataProvider con las vigencias del item solicitado. null en caso de que $item_id no sea valido
      */
     public function getDP($item_id)
     {
     	if($item_id == null || $item_id == 0)
     		return null;



     	$cmd = Yii::$app->db->createCommand( "Select count(*) From v_item_vigencia Where item_id = :_item_id", [':_item_id' => $item_id] );
     	$count = $cmd->queryScalar();

     	$sql = new Query();

     	$columnas = 'item_id, per_desde, per_hasta, substr(cast(perdesde As varchar), 1, 4) As adesde, substr(cast(perdesde As varchar), 5, 3) As cdesde';
     	$columnas .= ', substr(cast(perhasta As varchar), 1, 4) As ahasta, substr(cast(perhasta As varchar), 5 , 3) As chasta, tcalculo_nom As tcalculo';
     	$columnas .= ', monto, (porc * 100) AS porc, minimo, paramnombre1, paramnombre2, modif As modificacion';


     	$sql = "Select $columnas From v_item_vigencia Where item_id = :_item_id";

     	return new SqlDataProvider([

     		'sql' => $sql,
     		'totalCount' => $count,
     		'params' => [':_item_id' => $item_id],
     		'sort' => [

     			'attributes' => [
     				'item_id',
     				'per_desde' => ['default' => SORT_DESC],
     				'per_hasta',
					'tcalculo'
     			]
     		]
		]);
     }

     /**
      * Obtiene los registros de la tabla de valores asociados de la vigencia
      */
     public function getValoresAsoc()
     {

     	$idItem = $this->item_id > 0 ? $this->item_id : 0;
     	$perdesde = $this->perdesde > 0 ? $this->perdesde : 0;
     	$perhasta = $this->perhasta > 0 ? $this->perhasta : 0;

     	$count = "Select count(*) from item_asoc Where item_id = :_item_id And perdesde = :_perdesde And perhasta = :_perhasta";
     	$count = Yii::$app->db->createCommand($count, [':_item_id' => $idItem, ':_perdesde' => $perdesde, ':_perhasta' => $perhasta])->queryScalar();


     	$sql = "Select * From item_asoc Where item_id = :_item_id And perdesde = :_perdesde And perhasta = :_perhasta";


     	return new SqlDataProvider([
     		'sql' => $sql,
     		'params' => [':_item_id' => $idItem, ':_perdesde' => $perdesde, ':_perhasta' => $perhasta],
     		'totalCount' => $count,

     		'pagination' => [
     			'pageSize' => 4,
     			'totalCount' => $count
     		]
     	]);
     }

     /**
     * Obtiene la vigencia actual
     *
     * @return Array - Arreglo asociativo con 1 elemento que corresponde a la vigencia actual dada por $item_id.
     * El arreglo tiene la forma ['ItemVigencia' => [arreglo asociativos con los datos de la vigencia actual]].
     */
    public function buscarActual()
    {
    	$res = ItemVigencia::find()->where(['item_id' => $this->item_id])->orderBy( ['perdesde' => SORT_DESC, 'perhasta' => SORT_DESC] )->one();

    	if($res == null)
    	{
    		$this->addError($this->item_id, 'El item no posee vigencias.');
    		return $this;
    	}

    	$res->adesde = intval($res->perdesde / 1000);
     	$res->cdesde = $res->perdesde % 1000;

     	$res->ahasta = intval($res->perhasta / 1000);
     	$res->chasta = $res->perhasta  % 1000;

     	return $res;
    }

    /**
     * Obtiene la vigencia que corresponde al id del item y al periodo desde
     *
     * @return ItemVigencia - La vigencia obtenida en caso de haber pasado las validaciones. Los mismos datos que se han provisto en caso de que alguna validacion no haya pasado
     */
    public function buscarUno($scenario = 'select')
    {

    	$this->scenario = $scenario;

     	if(!$this->validate())
     		return $this;


     	$res = ItemVigencia::find()->where(['item_id' => $this->item_id, 'perdesde' => $this->perdesde])->one();

     	if($res == false)
     	{
     		$this->addError($this->item_id, 'La vigencia no ha podido ser encontrada.');
     		return $this;
     	}

     	$res->masoc = $this->masoc;
     	$res->p1asoc = $this->p1asoc;
     	$res->p2asoc = $this->p2asoc;
     	$res->p3asoc = $this->p3asoc;
     	$res->p4asoc = $this->p4asoc;

     	return $res;
    }
}
