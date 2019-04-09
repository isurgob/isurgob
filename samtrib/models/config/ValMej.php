<?php

namespace app\models\config;

use Yii;
use yii\data\ArrayDataProvider;
use app\utils\helpers\DBException;

/**
 * This is the model class for table "val_mej".
 *
 * @property string $cat
 * @property string $form
 * @property integer $perdesde
 * @property integer $perhasta
 * @property string $valor
 */
class ValMej extends \yii\db\ActiveRecord
{
	public $aniodesde; 
	public $aniohasta;
	public $cuotadesde;
	public $cuotahasta;
		
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'val_mej';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cat', 'form', 'aniodesde', 'aniohasta', 'cuotadesde', 'cuotahasta', 'valor'], 'required', 'on' => ['insert', 'update']],
            [['aniodesde', 'aniohasta','cuotadesde','cuotahasta', 'perdesde', 'perhasta'], 'integer'],
            [['valor'], 'number'],
            [['cat', 'form'], 'string', 'max' => 2],
			
			[['perdesde', 'perhasta'], 'validarPeriodo', 'on' => ['insert', 'update']],
			[['valor'], 'validarValor', 'on' => ['insert', 'update']],
			
			[['cat'], 'validarExistencia', 'on' => ['insert']],
        ];
    }
	
	public function scenarios(){

		return [
			'insert' => [
				'aniodesde', 'aniohasta','cuotadesde','cuotahasta', 'perdesde', 'perhasta',
				'valor', 'cat', 'form'
				
			],
			'update' => [
				'aniodesde', 'aniohasta','cuotadesde','cuotahasta', 'perdesde', 'perhasta',
				'valor', 'cat', 'form'
			],
			'delete' => ['cat', 'form', 'aniodesde', 'cuotadesde', 'perdesde']
			
		];
	}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cat' => 'Categoria',
            'form' => 'Tipo de formulario',
            'perdesde' => 'Periodo desde',
            'perhasta' => 'Periodo hasta',
            'valor' => 'Valor',
            'aniodesde' => 'Fecha desde',
            'aniohasta' => 'Fecha hasta',
            'cuotadesde' => 'Cuota desde',
            'cuotahasta' => 'Cuota hasta'
        ];
    }
	
	/* Antes de validar */
	public function beforeValidate(){
		
		$this->aniodesde = intVal($this->aniodesde);
		$this->aniohasta = intVal($this->aniohasta);
		$this->cuotadesde = intVal($this->cuotadesde);
		$this->cuotahasta = intVal($this->cuotahasta);
		$this->valor = floatVal($this->valor);
		
		$this->perdesde = intVal( ($this->aniodesde * 1000) + $this->cuotadesde );
		$this->perhasta = intVal( ($this->aniohasta * 1000) + $this->cuotahasta );
		
		return true;	
	}
	
	/* despues de obtener el modelo */
	public function afterFind() {
	
		$this->aniodesde = intVal( substr( $this->perdesde, 0, 4 ) );
		$this->cuotadesde = intVal( substr( $this->perdesde, -3 ) );
		
		$this->aniohasta = intVal( substr( $this->perhasta, 0, 4 ) );
		$this->cuotahasta = intVal( substr( $this->perhasta, -3 ) );
	}
	
	/* Valido periodo */
	public function validarPeriodo(){
	
		if( $this->perdesde <= 0 )
			$this->addError( 'perdesde', "Periodo desde mal ingresado." );
			
		if($this->perhasta <= 0)
			$this->addError( 'perhasta', "Periodo hasta mal ingresado." );
			
		if( $this->perdesde > $this->perhasta )
			$this->addError( 'perdesde', "Rango de Periodo Mal ingresado." );
	}
	
	/* valido ingreso de valor mayor a 0 */
	public function validarValor(){
	
		if ( $this->valor < 1 ) 
			$this->addError( 'valor', "Valor debe ser mayor a 0." );
	}
	
	/* valido que no exista la mejora */
	
	public function validarExistencia(){
	
		$sql = "SELECT COUNT(*) FROM val_mej WHERE cat='".$this->cat."' and form='".$this->form."' and perdesde=".$this->perdesde;
		$cantidad = Yii::$app->db->createCommand($sql)->queryScalar();
		
		if($cantidad > 0)
			$this->addError( 'cat', "El valor de mejora ya existe." );
	}
	
	/* obtengo listado de mejoras */
	public function listadoValMej( $cond = ''){
	
		$sql = "select vm.cat,c.nombre cat_nom, vm.form, f.nombre_redu as form_nom, vm.valor," .
    			" substring(to_char(vm.perdesde, '9999999'),0,6) || '-' || substring(to_char(vm.perdesde, '9999999'),6,7) AS perdesde_str," .
    			" substring(to_char(vm.perhasta, '9999999'),0,6) || '-' || substring(to_char(vm.perhasta, '9999999'),6,7) AS perhasta_str, " .
				" perdesde, perhasta " . 
    		   " from val_mej vm " . 
			   " left join inm_mej_tcat c on vm.cat=c.cod" . 
			   " left join inm_mej_tform f on vm.form=f.cod" . 
			   ( $cond != "" ? " where $cond" : "" );
			   
		$datos = Yii::$app->db->createCommand($sql)->queryAll();	   
		
		return new ArrayDataProvider([
			'allModels' => $datos,
			'totalCount' => count($datos),
			'pagination' => [
				'pageSize' => count($datos)
			]
		]);
	}
	
	/* 
	verifico si existen datos en inm_mej_tcat. 
	de esta forma se si la categoria se ingresa manual o se selecciona de un combo.
	en caso que la tabla inm_mej_tcat tenga datos, entonces se selecciona en un combo.
	*/
	public function getExisteInmMejTCat(){
	
		$sql = "select count(*) from inm_mej_tcat";
		$cant =  Yii::$app->db->createCommand( $sql )->queryScalar();
		
		return ($cant > 0) ;
	}
	
	/* realiza el abm de valores de mejoras */
	public function Grabar(){
		
		$sql = '';
		
		if ( $this->scenario == 'insert' )
			$sql = "INSERT INTO val_mej VALUES('" . $this->cat . "','" . $this->form . "'," . $this->perdesde . "," . $this->perhasta . "," . $this->valor . ")";
			
		if ( $this->scenario == 'update' )
			$sql = "UPDATE val_mej SET perhasta=" . $this->perhasta . ", valor=" . $this->valor . " WHERE cat='" . $this->cat . "' and form='" . $this->form . "' and perdesde=" . $this->perdesde;
			
		if ( $this->scenario == 'delete' )
			$sql = "DELETE FROM val_mej WHERE cat='" . $this->cat . "' and form='" . $this->form . "' and perdesde=" . $this->perdesde;
		
		if ( $sql != '' ){
			
			try{						
				
				Yii::$app->db->createCommand($sql)->execute();
				
			}
			catch(\Exception $e){
			
				$this->addError( 'cat', $e->getMessage() );
				return false;
				
			}
			
		}
		
		return true;
		
	}    
    
}
