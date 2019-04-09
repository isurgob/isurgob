<?php

namespace app\models\objeto;

use Yii;
use app\utils\db\utb;
use app\models\objeto\Objeto;


class PersonaAltaRapida extends \yii\db\ActiveRecord
{
      
    public $prov_id;
	public $loc_id;
	public $calle_id;
	public $calle_nom;
	public $puerta;
	public $cp;
	public $piso;
	public $dpto;
	
	public static function tableName()
    {
        return 'v_persona';
    }
	
	public function rules()
    {
        return [
            
            [['nombre', 'cuit', 'calle_nom'], 'required'],
			['ndoc', 'required', 'when' => function($model) {
												return intVal($model->tdoc) !== 0;
											}
			],
			['calle_id', 'required', 'when' => function($model) {
												return intVal($model->loc_id) == intVal(utb::samMuni()['loc_id']) && $model->calle_id == 0;
											}
			],
			[['nombre', 'tipo_nom', 'sexo', 'cuit', 'tel', 'cel', 'mail', 'cp', 'piso', 'dpto', 'calle_nom' ], 'string'], 
			[['tipo', 'tdoc', 'ndoc', 'iva', 'prov_id', 'loc_id', 'calle_id', 'puerta'], 'integer'], 
        ];
    }
	
	public function attributeLabels(){

		return [
			'ndoc' => 'Nro. Documento',
			'calle_id' => 'Cod. de Calle',
			'calle_nom' => 'Nombre de Calle',
		];
	}
	
	public function beforeValidate(){

		$this->puerta = intVal( $this->puerta );
		$this->cuit = str_replace("-", "", $this->cuit);
		$this->ndoc = intVal( $this->ndoc );
		$this->iva = intVal( $this->iva );
		$this->calle_id = intVal( $this->calle_id );
		
		return true;
	}
	
	public function Grabar(){
	
		$transaction = Yii::$app->db->beginTransaction();

		try{
			
			// -------------------------------------- inserto datos en objeto
			// objeto siguiente codigo de objeto
			$this->obj_id = $this->GetMxObjeto();
			
			// guardo en tabla objeto
			$sql = "insert into objeto values ('$this->obj_id',3,'$this->obj_id',UPPER('$this->nombre'),'', 'A',0,0,'','',0,'',current_date," . Yii::$app->user->id;
			$sql .= ",null,0,0,current_timestamp," . Yii::$app->user->id . ")";
			
			Yii::$app->db->createCommand( $sql )->execute();
			
			// -------------------------------------- inserto datos en persona
			$sql =  "insert into persona ( obj_id, inscrip, tipo, tdoc, ndoc, fchnac, sexo, nacionalidad, estcivil, clasif, iva, cuit, ag_rete, tel, cel, mail ) values " . 
					"('$this->obj_id', 0, $this->tipo, $this->tdoc, $this->ndoc, null, '$this->sexo', 1, 0, 0, $this->iva,'$this->cuit', '', '$this->tel', '$this->cel', '$this->mail')";
			
			Yii::$app->db->createCommand( $sql )->execute();	
			
			// -------------------------------------- inserto datos en domicilio
			$sql = "insert into domi values ('OBJ', '$this->obj_id', 0, $this->loc_id, '$this->cp', 0, $this->calle_id, '".substr(utb::ComillasSimples($this->calle_nom),0,40) . "',";
			$sql .= "$this->puerta, '', '$this->piso', '$this->dpto', current_timestamp, " . Yii::$app->user->id . ")";
			
			Yii::$app->db->createCommand( $sql )->execute();	
			
			$transaction->commit();

		} catch(\Exception $e ){

			$transaction->rollback();
			$this->addError( 'obj_id',  $e->getMessage() );
			
			return false;
		}
		
		return true;
	}
	
	private function GetMxObjeto()
    {
    	$objeto = '';

    	$sql = "Select coalesce(max(obj_id),'X0000000') From objeto Where tobj=3";
		$objeto = Yii :: $app->db->createCommand($sql)->queryScalar();
		$objeto = (int)substr($objeto,1,7)+1;

    	$objeto = "P" . str_pad($objeto, 7, "0", STR_PAD_LEFT);

    	return $objeto;
    }

}
