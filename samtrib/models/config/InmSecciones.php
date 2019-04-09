<?php

namespace app\models\config;

use Yii;
use app\utils\helpers\DBException;
use app\utils\db\utb;
use yii\data\ArrayDataProvider;


class InmSecciones extends \yii\db\ActiveRecord
{
	
	protected static $tabla_name;

	public function __construct($tablename = 'inm_s3')
    {
        
        self::$tabla_name = $tablename;
        
        if ( in_array($tablename, ['inm_s2', 'inm_s3']) )
        	$this->s2 = '';
			
		if ( $tablename == 'inm_s3' )
        	$this->s3 = '';	
			
		parent::__construct();		
    }

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return self::$tabla_name;
    }

	public function scenarios(){

        return[

            'nuevos1' => [ 's1' ],
            'eliminars1' => [ 's1' ],

            'nuevos2' => [ 's1', 's2' ],
            'eliminars2' => [ 's1', 's2' ],
			
			'nuevos3' => [ 's1', 's2', 's3' ],
            'eliminars3' => [ 's1', 's2', 's3' ]

        ];
    }

    public function rules()
	{
	    return [
	        
	        [['s1'], 'required', 'on' => ['nuevos1', 'eliminars1'] ],

	        [['s2'], 'required', 'on' => ['nuevos2', 'eliminars2']],
			
			[['s3'], 'required', 'on' => ['nuevos3', 'eliminars3']],

	        [['s1'], 'validarUnicoS1', 'on' => ['nuevos1'] ],

	        [['s1'], 'validarExiteS1', 'on' => ['eliminars1'] ],
			
			[['s1', 's2'], 'validarUnicoS2', 'on' => ['nuevos2'] ],
			
			[['s1', 's2'], 'validarExiteS2', 'on' => ['eliminars2'] ],
			
			[['s1', 's2', 's3'], 'validarUnicoS3', 'on' => ['nuevos3'] ],

	    ];
	}

	public function validarUnicoS1(){

		$sql = "select count(*) from inm_s1 where s1='$this->s1'";

		$count = intVal( Yii::$app->db->createCommand( $sql )->queryScalar() );
		
		if ( $count > 0 )
			$this->addError( 's1',  "La sección ya existe." );
	}
	
	public function validarUnicoS2(){

		$sql = "select count(*) from inm_s2 where s1='$this->s1' and s2='$this->s2'";

		$count = intVal( Yii::$app->db->createCommand( $sql )->queryScalar() );
		
		if ( $count > 0 )
			$this->addError( 's2',  "La sección ya existe." );
	}

	public function validarExiteS1(){

		$sql = "select count(*) from inm_s2 where s1='$this->s1'";

		$count = intVal( Yii::$app->db->createCommand( $sql )->queryScalar() );
		
		if ( $count > 0 )
			$this->addError( 's1',  "La sección $this->s1 es utilizada en S2. No podrá eliminarla" );

		$sql = "select count(*) from inm_s3 where s1='$this->s1'";

		$count = intVal( Yii::$app->db->createCommand( $sql )->queryScalar() );
		
		if ( $count > 0 )
			$this->addError( 's1',  "La sección $this->s1 es utilizada en S3. No podrá eliminarla" );
		
	}
	
	public function validarExiteS2(){

		$sql = "select count(*) from inm_s3 where s1='$this->s1' and s2='$this->s2'";

		$count = intVal( Yii::$app->db->createCommand( $sql )->queryScalar() );
		
		if ( $count > 0 )
			$this->addError( 's2',  "La sección $this->s1 - $this->s2 es utilizada en S3. No podrá eliminarla" );		

	}
	
	public function validarUnicoS3(){

		$sql = "select count(*) from inm_s3 where s1='$this->s1' and s2='$this->s2' and s3='$this->s3'";

		$count = intVal( Yii::$app->db->createCommand( $sql )->queryScalar() );
		
		if ( $count > 0 )
			$this->addError( 's3',  "La sección ya existe." );
	}

    public function findModel( $scenario, $s1, $s2, $s3 ){

    	if ( in_array($scenario, ['nuevos1', 'eliminars1']) ){
    		$modelS1 = new InmSecciones( "inm_s1" );
    		$model = $modelS1::findOne([ 's1' => $s1 ]);

			if ( $model == null or $scenario == 'nuevos1' )	
				$model = new InmSecciones( "inm_s1" );
					
    		return $model;
    	}

    	if ( in_array($scenario, ['nuevos2', 'eliminars2']) ){
    		$modelS2 = new InmSecciones( "inm_s2" );
    		$model = $modelS2::findOne([ 's1' => $s1, 's2' => $s2 ]);

			if ( $model == null or $scenario == 'nuevos2' )	
				$model = new InmSecciones( "inm_s2" );
					
    		return $model;
    	}
		
		$modelS3 = new InmSecciones( "inm_s3" );
    	$model = $modelS3::findOne([ 's1' => $s1, 's2' => $s2,'s3' => $s3 ]);
		
		if ( $model == null )	
			$model = new InmSecciones();
				
		return $model;
    }
    
	
	public function getArrayS1(){
		
		return utb::getAux( 'inm_s1', 's1', 's1' );	
	}
	
	public function getArrayS2(){
		
		return utb::getAux( 'inm_s2', 's2', 's2',0, ( $this->s1 == "" ? "" : "s1='" . $this->s1 . "'" ));	
	}
	
	public function dataProviderS2(){
	
		$sql = "select s1, s2 from inm_s2 where s1='$this->s1'";
		
		$data = Yii::$app->db->createCommand( $sql )->queryAll();
		
		array_unshift($data, [ 's1' => $this->s1, 's2' => '' ]);

        $dataProvider = new ArrayDataProvider([

            'allModels' => $data,
            'pagination'=> [
				'pageSize' => count($data)
			],
        ]);

        return $dataProvider;
	}
	
	public function dataProviderS3(){
	
		$sql = "select * from inm_s3 where s1='$this->s1' and s2='$this->s2'";
		
		$data = Yii::$app->db->createCommand( $sql )->queryAll();

        $dataProvider = new ArrayDataProvider([

            'allModels' => $data,
            'pagination'=> [
				'pageSize' => count($data)
			],
        ]);

        return $dataProvider;
	}

	public function Grabar(){

		$transaction = Yii::$app->db->beginTransaction();

		try{
		
			switch( $this->scenario ){

				case 'nuevos1':	//Alta S1
					
					$sql = 	"INSERT INTO inm_s1 " . 
							" VALUES ('$this->s1', CURRENT_TIMESTAMP," . Yii::$app->user->id . ")";

					break;

				case 'eliminars1':	//Eliminar S1 

					$sql = 	"Delete from inm_s1 WHERE s1 = '$this->s1'";

					break;
				
				case 'nuevos2':	//Alta S2
					
					$sql = 	"INSERT INTO inm_s2 " . 
							" VALUES ('$this->s1', '$this->s2', CURRENT_TIMESTAMP," . Yii::$app->user->id . ")";

					break;
					
				case 'eliminars2':	//Eliminar S2

					$sql = 	"Delete from inm_s2 WHERE s1 = '$this->s1' and s2 = '$this->s2'";

					break;	
					
				case 'nuevos3':	//Alta S3
					
					$sql = 	"INSERT INTO inm_s3 " . 
							" VALUES ('$this->s1', '$this->s2', '$this->s3', CURRENT_TIMESTAMP," . Yii::$app->user->id . ")";

					break;	
					
				case 'eliminars3':	//Eliminar S3

					$sql = 	"Delete from inm_s3 WHERE s1 = '$this->s1' and s2 = '$this->s2' and s3 = '$this->s3'";
					
					break;		
			}
			
			
			// ejecuto consulta de ABM de seccion
			Yii::$app->db->createCommand( $sql )->execute();	
			
			$transaction->commit();

		} catch(\Exception $e ){

			$transaction->rollback();
			$this->addError( 's1',  DBException::getMensaje($e) );
			
			return false;
		}
		
		return true;
	}
    
}
