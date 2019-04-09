<?php

namespace app\models\config;

use Yii;
use app\utils\helpers\DBException;

/**
 * This is the model class for table "sam.config_inm_nc".
 *
 * @property integer $manz_aplica
 * @property integer $manz_nom
 * @property integer $manz_max
 * @property string $manz_nro
 * @property integer $parc_aplica
 * @property integer $parc_nom
 * @property integer $parc_max
 * @property string $parc_nro
 * @property integer $s1_aplica
 * @property integer $s1_nom
 * @property integer $s1_max
 * @property string $s1_nro
 * @property integer $s2_aplica
 * @property integer $s2_nom
 * @property integer $s2_max
 * @property string $s2_nro
 * @property integer $s3_aplica
 * @property integer $s3_nom
 * @property integer $s3_max
 * @property string $s3_nro
 */
class Inmnom extends \yii\db\ActiveRecord
{
	 public $manz_aplica;
	 public $manz_nom;
	 public $manz_max;
	 public $manz_nro;
	 public $parc_aplica;
	 public $parc_nom;
	 public $parc_max;
	 public $parc_nro;
	 public $s1_aplica;
	 public $s1_nom;
	 public $s1_max;
	 public $s1_nro;	 
	 public $s2_aplica;
	 public $s2_nom;
	 public $s2_max;
	 public $s2_nro;
	 public $s3_aplica;
	 public $s3_nom;
	 public $s3_max;
	 public $s3_nro;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sam.config_inm_nc';
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
    			['manz_aplica', 'manz_nom', 'manz_max', 'manz_nro',
				 'parc_aplica', 'parc_nom', 'parc_max', 'parc_nro',
				 's1_aplica', 's1_nom', 's1_max', 's1_nro',
				 's2_aplica', 's2_nom', 's2_max', 's2_nro',
				 's3_aplica', 's3_nom', 's3_max', 's3_nro',
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
    			"FROM sam.config_inm_nc " .
    			"WHERE campo = 'manz'";
    	
    	$data = Yii::$app->db->createCommand( $sql )->queryAll();
    	
    	$this->manz_aplica = $data[0]['aplica'];
    	$this->manz_nom = $data[0]['nombre'];
    	$this->manz_max = $data[0]['max_largo'];
    	$this->manz_nro = $data[0]['solo_nro'];
    	
    	$sql= 	"SELECT aplica,nombre,max_largo,solo_nro " .
    			"FROM sam.config_inm_nc " .
    			"WHERE campo = 'parc'";
    	
    	$data = Yii::$app->db->createCommand( $sql )->queryAll();
    	
    	$this->parc_aplica = $data[0]['aplica'];
    	$this->parc_nom = $data[0]['nombre'];
    	$this->parc_max = $data[0]['max_largo'];
    	$this->parc_nro = $data[0]['solo_nro'];
    	
    	$sql= 	"SELECT aplica,nombre,max_largo,solo_nro " .
    			"FROM sam.config_inm_nc " .
    			"WHERE campo = 's1'";
    	
    	$data = Yii::$app->db->createCommand( $sql )->queryAll();
    	
    	$this->s1_aplica = $data[0]['aplica'];
    	$this->s1_nom = $data[0]['nombre'];
    	$this->s1_max = $data[0]['max_largo'];
    	$this->s1_nro = $data[0]['solo_nro'];
    	
    	$sql= 	"SELECT aplica,nombre,max_largo,solo_nro " .
    			"FROM sam.config_inm_nc " .
    			"WHERE campo = 's2'";
    	
    	$data = Yii::$app->db->createCommand( $sql )->queryAll();
    	
    	$this->s2_aplica = $data[0]['aplica'];
    	$this->s2_nom = $data[0]['nombre'];
    	$this->s2_max = $data[0]['max_largo'];
    	$this->s2_nro = $data[0]['solo_nro'];
    	
    	$sql= 	"SELECT aplica,nombre,max_largo,solo_nro " .
    			"FROM sam.config_inm_nc " .
    			"WHERE campo = 's3'";
    	
    	$data = Yii::$app->db->createCommand( $sql )->queryAll();
    	
    	$this->s3_aplica = $data[0]['aplica'];
    	$this->s3_nom = $data[0]['nombre'];
    	$this->s3_max = $data[0]['max_largo'];
    	$this->s3_nro = $data[0]['solo_nro'];
    	
    }
    
    public function validar()
    {
  
    	if ( $this->s1_max == '' )
    	{
    		$this->addError( 's1_max', 'Ingrese un largo máximo de s1.' );
    	
    	} else if ( $this->s1_max < 0 )
    	{
    		$this->addError( 's1_max', 'Ingrese un largo máximo de s1 válido.' );
    	}
    	
    	if ( $this->s2_max == '' )
    	{
    		$this->addError( 's2_max', 'Ingrese un largo máximo de s2.' );
    	
    	} else if ( $this->s2_max < 0 )
    	{
    		$this->addError( 's2_max', 'Ingrese un largo máximo de s2 válido.' );
    	}
    	
    	if ( $this->s3_max == '' )
    	{
    		$this->addError( 's3_max', 'Ingrese un largo máximo de s3.' );
    	
    	} else if ( $this->s3_max < 0 )
    	{
    		$this->addError( 's3_max', 'Ingrese un largo máximo de s3 válido.' );
    	}
    	
    	if ( $this->manz_max == '' )
    	{
    		$this->addError( 'manz_max', 'Ingrese un largo máximo de manzana.' );
    	
    	} else if ( $this->manz_max < 0 )
    	{
    		$this->addError( 'manz_max', 'Ingrese un largo máximo de manzana válido.' );
    	}
    	
    	if ( $this->parc_max == '' )
    	{
    		$this->addError( 'parc_max', 'Ingrese un largo máximo de parcela.' );
    	
    	} else if ( $this->parc_max < 0 )
    	{
    		$this->addError( 'parc_max', 'Ingrese un largo máximo de parcela válido.' );
    	}
    	
    }
    
    public function grabar()
    {
		
		$this->validar();
		
		if ( $this->hasErrors() )
			return false;	
    	
    	$sql1 = "UPDATE sam.config_inm_nc SET " .
    			"aplica=" . ( $this->manz_aplica == 1 ? 'true' : 'false' ) .
    			",nombre = '" . $this->manz_nom . "'" .
    			",max_largo = " . $this->manz_max .
    			",solo_nro = " . ( $this->manz_nro == 1 ? 'true' : 'false' ) .
    			" WHERE campo = 'manz_id'";
    			
    	$sql2 = "UPDATE sam.config_inm_nc SET " .
    			"aplica=" . ( $this->parc_aplica == 1 ? 'true' : 'false' ) .
    			",nombre = '" . $this->parc_nom . "'" .
    			",max_largo = " . $this->parc_max .
    			",solo_nro = " . ( $this->parc_nro == 1 ? 'true' : 'false' ) .
    			" WHERE campo = 'parc_id'";
    	
    	$sql3 = "UPDATE sam.config_inm_nc SET " .
    			"aplica=" . ( $this->s1_aplica == 1 ? 'true' : 'false' ) .
    			",nombre = '" . $this->s1_nom . "'" .
    			",max_largo = " . $this->s1_max .
    			",solo_nro = " . ( $this->s1_nro == 1 ? 'true' : 'false' ) .
    			" WHERE campo = 's1'";
    	
    	$sql4 = "UPDATE sam.config_inm_nc SET " .
    			"aplica=" . ( $this->s2_aplica == 1 ? 'true' : 'false' ) .
    			",nombre = '" . $this->s2_nom . "'" .
    			",max_largo = " . $this->s2_max .
    			",solo_nro = " . ( $this->s2_nro == 1 ? 'true' : 'false' ) .
    			" WHERE campo = 's2'";
    			
    	$sql5 = "UPDATE sam.config_inm_nc SET " .
    			"aplica=" . ( $this->s3_aplica == 1 ? 'true' : 'false' ) .
    			",nombre = '" . $this->s3_nom . "'" .
    			",max_largo = " . $this->s3_max .
    			",solo_nro = " . ( $this->s3_nro == 1 ? 'true' : 'false' ) .
    			" WHERE campo = 's3'";
		try
		{					
			Yii :: $app->db->createCommand($sql1)->execute();
			Yii :: $app->db->createCommand($sql2)->execute();
			Yii :: $app->db->createCommand($sql3)->execute();
			Yii :: $app->db->createCommand($sql4)->execute();
			Yii :: $app->db->createCommand($sql5)->execute();
	 	
	 	} catch( \Exception $e )
	 	{
	 		$this->addError( DBException::getMensaje( $e ) );
	 		
	 		return false;
	 	}
	 	
    	return true;
    }
    
    
}
