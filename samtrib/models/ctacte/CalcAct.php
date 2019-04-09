<?php

namespace app\models\ctacte;


use Yii;
use Yii\db\Exception;
use app\utils\helpers\DBException;
use app\utils\db\Fecha;
use app\utils\db\utb;
use yii\data\ArrayDataProvider;

class CalcAct extends \yii\db\ActiveRecord
{
	public $modificacion;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'calc_act';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fchdesde', 'fchhasta', 'indice'], 'required'],
            [['fchdesde', 'fchhasta'], 'safe'],
            [['indice'], 'number', 'min' => 0, 'max' => 15],
			[['fchdesde', 'fchhasta'], 'default', 'value' => null],
			[['fchdesde'], 'validarRangoFecha' ]
        ];
    }
	
	public function scenarios(){

        return [

            'nuevo' => [
                'fchdesde', 'fchhasta', 'indice'
            ],

            'modificar' => [ 'fchdesde', 'fchhasta', 'indice' ],

            'eliminar' => [
                'fchdesde', 'fchhasta'
            ],

            'consultar' => [
                'fchdesde', 'fchhasta', 'indice'
            ]
        ];

    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fchdesde' => 'Fecha desde',
            'fchhasta' => 'Fecha hasta',
            'indice' => 'Índice mensual',
            'fchmod' => 'Fecha de modificación',
            'usrmod' => 'Código de usuario que modifica',
        ];
    }
	
	public function validarRangoFecha(){
		
		if ( !Fecha::verificarRangoFecha($this->fchdesde, $this->fchhasta) )
			$this->addError( 'fchdesde','Rango de Fecha mal Ingresado' );
	}
	
	public function beforeValidate(){
		
		if($this->fchdesde != null && $this->fchdesde != '')
			$this->fchdesde= Fecha::getFormatoFechaDB($this->fchdesde);
		
		if($this->fchhasta != null && $this->fchhasta != '')
			$this->fchhasta= Fecha::getFormatoFechaDB($this->fchhasta);
			
		$this->indice = floatVal( $this->indice );	
				
		return true;
	}
	
	public function afterFind(){
		
		$this->modificacion = utb::getFormatoModif( $this->usrmod, $this->fchmod );
	}
	
	public function Listar(){
	
		return CalcAct::find()->all();
		
	}
	
	public function dpListar(){
	
		$datos = new ArrayDataProvider([
    		'allModels' => $this->Listar(),
    		'pagination' => false
    	]);
		
		return $datos;
		
	}
	
	public function Grabar(){
	
		try{
    		
			if ( $this->scenario == "nuevo" )
				$sql = "insert into calc_act values ('$this->fchdesde', '$this->fchhasta', $this->indice, current_timestamp, " . Yii::$app->user->id . ")";
			elseif ( $this->scenario == "modificar" )
				$sql = "update calc_act set indice=$this->indice, fchmod=current_timestamp, usrmod=" . Yii::$app->user->id . " where fchdesde='$this->fchdesde' and fchhasta='$this->fchhasta'";	
			elseif ( $this->scenario == "eliminar" )
				$sql = "delete from calc_act where fchdesde='$this->fchdesde' and fchhasta='$this->fchhasta'";		
			else
				return false;
			
			Yii::$app->db->createCommand($sql)->execute();	
			
    	} catch( \Exception $e ){
    		$this->addError( null, $e->getMessage() );
    		return false;
    	}
		
		return true;
	}
	
	public function Calcular( $tributo, $fchdvenc, $fchpago, $nominal, &$montocalculado ){
	
		if ( $tributo == 0 ) return "Ingrese un Tribuo";
		if ( $fchdvenc == "" ) return "Ingrese una Fecha de Vencimiento";
		if ( $fchpago == "" ) return "Ingrese una Fecha de Pago";
		
		try{
    		
			$sql = "select * from sam.uf_calc_act( $tributo, $nominal, '$fchdvenc', '$fchpago' )";
			
			$montocalculado = floatVal( Yii::$app->db->createCommand($sql)->queryScalar() );	
			
    	} catch( \Exception $e ){
    		return $e->getMessage();
    	}
		
		return "";
	}
    
}
