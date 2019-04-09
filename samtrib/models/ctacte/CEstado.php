<?php

namespace app\models\ctacte;

use Yii;

use yii\data\ArrayDataProvider;

use app\utils\helpers\DBException;
use app\utils\db\Fecha;
use app\utils\db\utb;
/**
 * @property string $obj_id
 */
class CEstado extends \yii\db\ActiveRecord
{	
	public $tobj;
	public $obj_nom;
	public $anio;
	public $cuota;
	public $info;
	public $array_est_destino;
	public $est_orig_nom;
		
	public static function tableName()
    {
        return 'ctacte_cambioest';
    }
	
    public function rules()
    {
        return [
            [['trib_id', 'anio', 'cuota', 'tobj','est_orig', 'est_dest'], 'integer'],
            [['trib_id', 'obj_id', 'anio', 'cuota', 'tobj',], 'required'],
            [['obj_id'], 'string', 'max' => 8],
            [['expe'], 'string', 'max' => 12],
            [['obs','info'], 'string', 'max' => 500],
            [['obj_nom','est_orig_nom'], 'string'],
            //[['array_est_destino'],'array'],
        ];
    } 
    
    public function __construct()
    {
    	$this->array_est_destino = []; 
    	$this->subcta = 0;
    }
    
    /**
     * Función que se utiliza para cambiar el estado de una Cta. Cte.
     */
    public function cambioEstado()
    {
    	$error = [];
    	
    	# Genero período
    	$periodo = ( intval ( $this->anio ) * 1000 ) + intval ( $this->cuota );
    	
    	$sql = "Select coalesce( ctacte_id, 0 ) From CtaCte  ";
        $sql .= "Where Trib_Id=" . $this->trib_id . " and Obj_Id = '" . $this->obj_id . "' and SubCta = " . $this->subcta . " and Anio*1000+Cuota = " . $periodo . " and Est= '" . $this->est_orig . "'";
        
        $transaction = Yii::$app->db->beginTransaction();

        $ctacte_id = Yii::$app->db->createCommand( $sql )->queryScalar();
         
        if ( $ctacte_id == 0 )
            $error[] = "No se encontró el período en el estado Origen.";
        else
        {
	        try
	        {
	            $sql = "Update CtaCte set Est='" . $this->est_dest . "', Obs = Obs || '/Se cambió estado. " . $this->obs . "', FchMod= current_timestamp, " .
	            		"UsrMod=" . Yii::$app->user->id . ", expe = '" . $this->expe . "' " .
	    				"Where Trib_Id=" . $this->trib_id . " and Obj_Id = '" . $this->obj_id . "' and SubCta = " . $this->subcta . " and Anio*1000+Cuota = " . $periodo . " and Est= '" . $this->est_orig . "'";
				    				
	            Yii::$app->db->createCommand( $sql )->execute();
	
	            // Si el estado Origen es Pagado, doy de baja las operaciones de pago en el detale de la ctacte
	            if ( $this->est_orig == "P" )
	            {
	                $sql = "Update ctacte_det set est='B' Where ctacte_id=" . $ctacte_id . " and topera in (3,4,5,7,8,10,17)";
	                Yii::$app->db->createCommand( $sql )->execute();
	                
	                $sql = "Select sam.uf_ctacte_ajuste(" . $ctacte_id . ")";
	                Yii::$app->db->createCommand( $sql )->execute();
	            }
	
	            $cambio_id = intval( Yii::$app->db->createCommand( "Select nextval('seq_ctacte_cambio_est')" )->queryScalar() );
	
	            $sql = "Insert into CtaCte_CambioEst values (" . $cambio_id . ",3," . $this->trib_id . ",'" . $this->obj_id . "'," . $this->subcta . "," . $periodo . "," . $periodo;
	            $sql .= ",'" . $this->est_orig . "','" . $this->est_dest . "','" . $this->expe . "','" . $this->obs . "',current_timestamp," . Yii::$app->user->id . ")";
	            
	            Yii::$app->db->createCommand( $sql )->execute();
	            
	            $transaction->commit();
	            
	        } catch (\Exception $e)
			{
				$transaction->rollback();
				
	        	$error[] = DBException::getMensaje( $e );
			}
		
        }
        	
		$this->addErrors( $error );
		
    }

	/**
	 * Función que se ejecuta para obtener el estado de una Cta Cte
	 * @param integer $trib_id Identificador de tributo.
	 * @param string $obj_id Identificador de objeto
	 * @param integer $anio Año del período.
	 * @param integer $cuota Cuota del período.
	 */
	public function recuperarEstado( /*$trib_id, $obj_id, $anio, $cuota*/ )
	{
		$error = [];
		
		$this->est_orig = '';
		$this->est_orig_nom = '';
		$this->array_est_destino = [];
		$this->info = '';
		
		$periodo = ( intval ( $this->anio ) * 1000 ) + intval ( $this->cuota );
		
		$sql = "Select case when p.est=1 then 'CV' else c.est end ";
        $sql .= "From ctacte c Left Join plan_periodo pp On c.ctacte_id=pp.ctacte_id ";
        $sql .= "Left Join plan p On p.plan_id = pp.plan_id ";
        $sql .= "Where c.trib_id = " . $this->trib_id . " and c.obj_id = '" . $this->obj_id . "' and ";
        $sql .= "c.anio*1000+c.cuota = " . $periodo . " and c.est in ('C','D','J','P') ";
        
        $est = trim( Yii::$app->db->createCommand( $sql )->queryScalar() );
        
        $this->est_orig = $est;
        
        if ( $est != '' )
        {
        	if ( $est <> 'CV' )
        		$this->est_orig_nom = $est . ' - ' . utb::getCampo('ctacte_test',"cod = '" . $est . "'",'nombre');
        	
        	# Cargo el valor a est_origen
        	$this->est_orig_nom = $est . ' - ' . utb::getCampo('ctacte_test', "cod = '" . $est . "'", 'nombre');
        	
        	switch ( $est )
        	{
        		case 'D':
        			
        			$this->array_est_destino = utb::getAux('ctacte_test','cod','nombre',0,"cod IN ('J')");
        			$this->info = "Para estado origen Deuda sólo se permite cambiar estado a Juicio, Si desea establecer como Pagado utilice la opción de Registrar Pagos Anteriores en el menú Caja.";
        			break;
        			
        		case 'P':
        			
                	$this->array_est_destino = utb::getAux('ctacte_test','cod','nombre',0,"Cod in ('D','J')");
                	$this->info = "Para un período con estado origen Pagado, cambiar el estado implica dar de baja las operaciones de pago en el detalle de la Cuenta Corriente.";
                	break;
                	
            	case 'C':
            	
	                $this->array_est_destino = utb::getAux('ctacte_test','cod','nombre',0,"Cod in ('J','D') ");
	                $this->info = "Si desea establecer como Pagado, primero cambie el estado como Deuda y luego utilice la opción de Registrar Pagos Anteriores en el menú Caja.";
	                break;
	            
	            case 'CV':
	            	
	                $this->est_orig_nom = "Convenio Vigente";
	                $this->info = "No se permite cambiar el estado de un período en Convenio que se encuentra asociado a un Convenio Vigente, utilice las acciones disponibles en la Administración de Convenios de Pago.";
	            	break;
	            	
	            case 'J':
	            	
	               $this->array_est_destino = utb::getAux('ctacte_test','cod','nombre',0,"Cod in ('D') ");
	               $this->info = "Si desea establecer como Pagado, primero cambie el estado como Deuda y luego utilice la opción de Registrar Pagos Anteriores en el menú Caja.";
	               break;
			
        	}
        	
        }
        
        $this->addErrors( $error );
        
	}

}
