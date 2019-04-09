<?php

namespace app\models\config;

use Yii;
use app\utils\helpers\DBException;

/**
 * This is the model class for table "sam.config_cem_nc".
 *
 * @property integer $cuadro_aplica
 * @property integer $cuadro_nom
 * @property integer $cuadro_max
 * @property string $cuadro_nro
 * @property integer $cuerpo_aplica
 * @property integer $cuerpo_nom
 * @property integer $cuerpo_max
 * @property string $cuerpo_nro
 * @property integer $tipo_aplica
 * @property integer $tipo_nom
 * @property integer $tipo_max
 * @property string $tipo_nro
 * @property integer $piso_aplica
 * @property integer $piso_nom
 * @property integer $piso_max
 * @property string $piso_nro
 * @property integer $fila_aplica
 * @property integer $fila_nom
 * @property integer $fila_max
 * @property string $fila_nro
 * @property integer $nume_aplica
 * @property integer $nume_nom
 * @property integer $nume_max
 * @property string $nume_nro
 */
class Cemnom extends \yii\db\ActiveRecord
{
	 public $cuadro_aplica;
	 public $cuadro_nom;
	 public $cuadro_max;
	 public $cuadro_nro;
	 public $cuerpo_aplica;
	 public $cuerpo_nom;
	 public $cuerpo_max;
	 public $cuerpo_nro;
	 public $tipo_aplica;
	 public $tipo_nom;
	 public $tipo_max;
	 public $tipo_nro;
	 public $piso_aplica;
	 public $piso_nom;
	 public $piso_max;
	 public $piso_nro;
	 public $fila_aplica;
	 public $fila_nom;
	 public $fila_max;
	 public $fila_nro;
	 public $nume_aplica;
	 public $nume_nom;
	 public $nume_max;
	 public $nume_nro;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sam.config_cem_nc';
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
    			['cuadro_aplica', 'cuadro_nom', 'cuadro_max', 'cuadro_nro',
				 'cuerpo_aplica', 'cuerpo_nom', 'cuerpo_max', 'cuerpo_nro',
				 'tipo_aplica', 'tipo_nom', 'tipo_max', 'tipo_nro',
				 'piso_aplica', 'piso_nom', 'piso_max', 'piso_nro',
				 'fila_aplica', 'fila_nom', 'fila_max', 'fila_nro',
				 'nume_aplica', 'nume_nom', 'nume_max', 'nume_nro',
				 ],
    			'required'
    			];
		
		/**
		 * FIN VALORES REQUERIDOS
		 */
    	
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
    
    public function __construct()
    {
    	
    	$this->cargarDatos();
    	
    }
    
    /**
     * Función que se utiliza para obtener los datos de la BD.
     */
    public function cargarDatos()
    {
    	$sql= 	"SELECT aplica,nombre,max_largo,solo_nro " .
    			"FROM sam.config_cem_nc " .
    			"WHERE campo = 'cuadro_id'";
    	
    	$data = Yii::$app->db->createCommand( $sql )->queryAll();
    	
    	$this->cuadro_aplica = $data[0]['aplica'];
    	$this->cuadro_nom = $data[0]['nombre'];
    	$this->cuadro_max = $data[0]['max_largo'];
    	$this->cuadro_nro = $data[0]['solo_nro'];
    	
    	$sql= 	"SELECT aplica,nombre,max_largo,solo_nro " .
    			"FROM sam.config_cem_nc " .
    			"WHERE campo = 'cuerpo_id'";
    	
    	$data = Yii::$app->db->createCommand( $sql )->queryAll();
    	
    	$this->cuerpo_aplica = $data[0]['aplica'];
    	$this->cuerpo_nom = $data[0]['nombre'];
    	$this->cuerpo_max = $data[0]['max_largo'];
    	$this->cuerpo_nro = $data[0]['solo_nro'];
    	
    	$sql= 	"SELECT aplica,nombre,max_largo,solo_nro " .
    			"FROM sam.config_cem_nc " .
    			"WHERE campo = 'tipo'";
    	
    	$data = Yii::$app->db->createCommand( $sql )->queryAll();
    	
    	$this->tipo_aplica = $data[0]['aplica'];
    	$this->tipo_nom = $data[0]['nombre'];
    	$this->tipo_max = $data[0]['max_largo'];
    	$this->tipo_nro = $data[0]['solo_nro'];
    	
    	$sql= 	"SELECT aplica,nombre,max_largo,solo_nro " .
    			"FROM sam.config_cem_nc " .
    			"WHERE campo = 'piso'";
    	
    	$data = Yii::$app->db->createCommand( $sql )->queryAll();
    	
    	$this->piso_aplica = $data[0]['aplica'];
    	$this->piso_nom = $data[0]['nombre'];
    	$this->piso_max = $data[0]['max_largo'];
    	$this->piso_nro = $data[0]['solo_nro'];
    	
    	$sql= 	"SELECT aplica,nombre,max_largo,solo_nro " .
    			"FROM sam.config_cem_nc " .
    			"WHERE campo = 'fila'";
    	
    	$data = Yii::$app->db->createCommand( $sql )->queryAll();
    	
    	$this->fila_aplica = $data[0]['aplica'];
    	$this->fila_nom = $data[0]['nombre'];
    	$this->fila_max = $data[0]['max_largo'];
    	$this->fila_nro = $data[0]['solo_nro'];
    	
    	$sql= 	"SELECT aplica,nombre,max_largo,solo_nro " .
    			"FROM sam.config_cem_nc " .
    			"WHERE campo = 'nume'";
    	
    	$data = Yii::$app->db->createCommand( $sql )->queryAll();
    	
    	$this->nume_aplica = $data[0]['aplica'];
    	$this->nume_nom = $data[0]['nombre'];
    	$this->nume_max = $data[0]['max_largo'];
    	$this->nume_nro = $data[0]['solo_nro'];
    	
    }
    
    public function validar()
    {
  
      	if ( $this->cuadro_max == '' )
    	{
    		$this->addError( 'cuadro_max', 'Ingrese un largo máximo de cuadro.' );
    	
		} else if ( $this->cuadro_max < 0 )
    	{
    		$this->addError( 'cuadro_max', 'Ingrese un largo máximo de cuadro válido.' );
    	} 
    	
    	if ( $this->cuerpo_max == '' )
    	{
    		$this->addError( 'cuerpo_max', 'Ingrese un largo máximo de cuerpo.' );
    	
    	} else if ( $this->cuerpo_max < 0 )
    	{
    		$this->addError( 'cuerpo_max', 'Ingrese un largo máximo de cuerpo válido.' );
    	} 
    	
    	if ( $this->tipo_max == '' )
    	{
    		$this->addError( 'nume_max', 'Ingrese un largo máximo de tipo.' );
    	
    	} else if ( $this->tipo_max < 0 )
    	{
    		$this->addError( 'nume_max', 'Ingrese un largo máximo de tipo válido.' );
    	} 
    	
    	if ( $this->piso_max == '' )
    	{
    		$this->addError( 'nume_max', 'Ingrese un largo máximo de piso.' );
    	
    	} else if ( $this->piso_max < 0 )
    	{
    		$this->addError( 'nume_max', 'Ingrese un largo máximo de piso válido.' );
    	}
    	
    	if ( $this->fila_max == '' )
    	{
    		$this->addError( 'nume_max', 'Ingrese un largo máximo de fila.' );
    	
    	} else if ( $this->fila_max < 0 )
    	{
    		$this->addError( 'nume_max', 'Ingrese un largo máximo de fila válido.' );
    	}
    	
    	if ( $this->nume_max == '' )
    	{
    		$this->addError( 'nume_max', 'Ingrese un largo máximo de número.' );
    	
    	} else if ( $this->nume_max < 0 )
    	{
    		$this->addError( 'nume_max', 'Ingrese un largo máximo de número válido.' );
    	}
    	
    }
    
    public function grabar()
    {
		
		$this->validar();
		
		if ( $this->hasErrors() )
			return false;	
    	
    	$sql1 = "UPDATE sam.config_cem_nc SET " .
    			"aplica=" . ( $this->cuadro_aplica == 1 ? 'true' : 'false' ) .
    			",nombre = '" . $this->cuadro_nom . "'" .
    			",max_largo = " . $this->cuadro_max .
    			",solo_nro = " . ( $this->cuadro_nro == 1 ? 'true' : 'false' ) .
    			" WHERE campo = 'cuadro_id'";
    			
    	$sql2 = "UPDATE sam.config_cem_nc SET " .
    			"aplica=" . ( $this->cuerpo_aplica == 1 ? 'true' : 'false' ) .
    			",nombre = '" . $this->cuerpo_nom . "'" .
    			",max_largo = " . $this->cuerpo_max .
    			",solo_nro = " . ( $this->cuerpo_nro == 1 ? 'true' : 'false' ) .
    			" WHERE campo = 'cuerpo_id'";
    	
    	$sql3 = "UPDATE sam.config_cem_nc SET " .
    			"aplica=" . ( $this->tipo_aplica == 1 ? 'true' : 'false' ) .
    			",nombre = '" . $this->tipo_nom . "'" .
    			",max_largo = " . $this->tipo_max .
    			",solo_nro = " . ( $this->tipo_nro == 1 ? 'true' : 'false' ) .
    			" WHERE campo = 'tipo'";
    			
    	$sql4 = "UPDATE sam.config_cem_nc SET " .
    			"aplica=" . ( $this->piso_aplica == 1 ? 'true' : 'false' ) .
    			",nombre = '" . $this->piso_nom . "'" .
    			",max_largo = " . $this->piso_max .
    			",solo_nro = " . ( $this->piso_nro == 1 ? 'true' : 'false' ) .
    			" WHERE campo = 'piso'";
    			
    	$sql5 = "UPDATE sam.config_cem_nc SET " .
    			"aplica=" . ( $this->fila_aplica == 1 ? 'true' : 'false' ) .
    			",nombre = '" . $this->fila_nom . "'" .
    			",max_largo = " . $this->fila_max .
    			",solo_nro = " . ( $this->fila_nro == 1 ? 'true' : 'false' ) .
    			" WHERE campo = 'fila'";
    			
    	$sql6 = "UPDATE sam.config_cem_nc SET " .
    			"aplica=" . ( $this->nume_aplica == 1 ? 'true' : 'false' ) .
    			",nombre = '" . $this->nume_nom . "'" .
    			",max_largo = " . $this->nume_max .
    			",solo_nro = " . ( $this->nume_nro == 1 ? 'true' : 'false' ) .
    			" WHERE campo = 'nume'";
    			
		try
		{					
			Yii :: $app->db->createCommand($sql1)->execute();
			Yii :: $app->db->createCommand($sql2)->execute();
			Yii :: $app->db->createCommand($sql3)->execute();
			Yii :: $app->db->createCommand($sql4)->execute();
			Yii :: $app->db->createCommand($sql5)->execute();
			Yii :: $app->db->createCommand($sql6)->execute();
	 	
	 	} catch( \Exception $e )
	 	{
	 		$this->addError( DBException::getMensaje( $e ) );
	 		
	 		return false;
	 	}
	 	
    	return true;
    }
    
    
}
