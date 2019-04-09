<?php

namespace app\models\config;

use Yii;
use app\utils\db\utb;
use app\utils\helpers\DBException;

/**
 * This is the model class for table "sam.config_ddjj".
 *
 * @property integer $itemcompensamulta
 * @property integer $itemrete
 * @property integer $djanual
 * @property string $nversion
 * @property integer $cm_dj
 * @property integer $cm_min
 * @property integer $ai_dj
 * @property integer $ai_min
 * @property integer perm_retemanual
 * @property integer perm_djfalta
 * @property integer perm_saldo
 * @property integer perm_bonif
 * @property string $fchmod
 * @property integer $usrmod
 */
class Config_ddjj extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sam.config_ddjj';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
    	$ret = [];

    	/**
    	 * VALORES REQUERIDOS
    	 */
    	$ret[] = [
    			[
                    'trib_id', 'itembasico', 'djanual', 'nversion', 'cm_dj', 'cm_min', 'ai_dj', 'ai_min','perm_retemanual',
                    'perm_bonif','perm_saldo','perm_djfalta' ],
    			'required'
    			];

		/**
		 * FIN VALORES REQUERIDOS
		 */
		 
		 $ret[] = [
    			[
                    'itemcompensamulta', 'itemrete', 'itembonif', 'itemsaldo'],
    			'integer'
    			];
				
		$ret[] = [ 
					['perm_tipos'], 'string'
    			];		

    	return $ret;
    }

    /**
     * @inheritdoc
     */

    public function attributeLabels()
    {
        return [

        ];
    }

    public function getTributos(){

        $sql = "SELECT t.trib_id, t.nombre FROM trib t WHERE t.tipo = 2 AND t.est = 'A' AND t.trib_id IN ( select trib_id FROM sam.config_ddjj )";

        return Yii::$app->db->createCommand( $sql )->queryAll();

    }

    /**
     * Función que se utiliza para obtener los tributos que se pueden crear.
     */
    public static function getTributosNuevo(){

        return utb::getAux( 'trib','trib_id','nombre',1,"tipo = 2 AND est = 'A' AND trib_id NOT IN ( select trib_id FROM sam.config_ddjj )");
    }

    /**
     * Función que se utiliza para obtener los tributos que se pueden modificar.
     */
    public static function getTributosModificar(){

        return utb::getAux( 'trib','trib_id','nombre',1,"tipo = 2 AND est = 'A' AND trib_id IN ( select trib_id FROM sam.config_ddjj )");
    }

    public static function getItemMulta(){

        return utb::getAux( 'item', 'item_id', 'nombre', 1, "Trib_id in (Select trib_id from trib where dj_tribprinc > 0 and est= 'A') and Tipo=5" );
    }
	
	public static function getItemBasico(){

        return utb::getAux( 'item', 'item_id', 'nombre', 3, "Trib_id in (Select trib_id from trib where dj_tribprinc > 0 and est= 'A') and Tipo=1" );
    }

    public static function getItemRetencion(){

        return utb::getAux('item','item_id','nombre',1,"Trib_id in (Select trib_id from trib where dj_tribprinc>0 and est= 'A') and Tipo=1");
    }
	
	public static function getItemBonif(){

        return utb::getAux('item','item_id','nombre',1,"Trib_id in (Select trib_id from trib where dj_tribprinc>0 and est= 'A') and Tipo=1");
    }
	
	public static function getItemSaldo(){

        return utb::getAux('item','item_id','nombre',1,"Trib_id in (Select trib_id from trib where dj_tribprinc>0 and est= 'A') and Tipo=1");
    }

    public function grabar( $action ){

        if( $this->trib_id == 0 ){

            $this->addError( 'trib_id', 'Ingrese un tributo.' );

        }
		if ( $this->itembasico <= 0 )
    	{
    		$this->addError( 'itembasico', 'Seleccione un Item Básico.' );

    	}
    	if ( $this->nversion == '' || $this->nversion <= 0 )
    	{
    		$this->addError( 'nversion', 'Ingrese un número de versión.' );

    	}

        if( $this->hasErrors() ){

            return false;
        }

        if( $action == 0 ){

            $sql =  "INSERT INTO sam.config_ddjj ( trib_id, itemcompensamulta, itembasico, itemrete, itembonif, itemsaldo, djanual, nversion, " .
                    "cm_dj, cm_min, ai_dj, ai_min, perm_retemanual, perm_djfalta, perm_saldo, perm_bonif, perm_tipos, fchmod, usrmod ) VALUES ( " .
                    "$this->trib_id," . intVal( $this->itemcompensamulta ) . "," . intVal( $this->itembasico ) . "," . intVal( $this->itemrete ) . "," .
                    intVal( $this->itembonif ) . "," . intVal( $this->itemsaldo ) . ",$this->djanual, $this->nversion, $this->cm_dj, $this->cm_min, " .
                    " $this->ai_dj, $this->ai_min, $this->perm_retemanual, $this->perm_djfalta, " .
                    " $this->perm_saldo, $this->perm_bonif, '$this->perm_tipos', CURRENT_TIMESTAMP, " . Yii::$app->user->id . ")";
        } else {

            $sql = "UPDATE sam.config_ddjj SET " .
        			"itemcompensamulta=" . ($this->itemcompensamulta == '' ? 0 : $this->itemcompensamulta) .
        			",itembasico = " . intVal($this->itembasico) .
					",itemrete = " . $this->itemrete .
        			",itembonif = " . intVal($this->itembonif) .
					",itemsaldo = " . intVal($this->itemsaldo) .
					",djanual = " . $this->djanual .
        			",nversion = " . $this->nversion .
        			",cm_dj = " . $this->cm_dj .
        			",cm_min = " . $this->cm_min .
        			",ai_dj = " . $this->ai_dj .
        			",ai_min = " . $this->ai_min .
                    ",perm_retemanual = " . $this->perm_retemanual .
                    ",perm_djfalta = " . $this->perm_djfalta .
                    ",perm_saldo = " . $this->perm_saldo .
                    ",perm_bonif = " . $this->perm_bonif .
					",perm_tipos = '" . $this->perm_tipos . 
        			"',fchmod = CURRENT_TIMESTAMP " .
        			",usrmod = " . Yii::$app->user->id .
                    " WHERE trib_id = " . $this->trib_id;
        }

		try{
			
			Yii::$app->db->createCommand($sql)->execute();

        } catch(\Exception $e){

	 		//$this->addError( DBException::getMensaje( $e ) );

	 		return false;
	 	}

    	return true;
    }


}
