<?php

namespace app\models\ctacte;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use app\utils\db\Fecha;
use app\utils\helpers\DBException;

use Yii;

/**
 * This is the model class for table "calc_mm".
 *
 * @property string $fchdesde
 * @property string $fchhasta
 * @property string $valor
 * @property string $fchmod
 * @property integer $usrmod
 */
class CalcMm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'calc_mm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fchdesde', 'fchhasta', 'valor'], 'required'],
            [['fchdesde', 'fchhasta'], 'safe'],
            [['valor'], 'number']
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
            'valor' => 'Valor',
            'fchmod' => 'Fecha de modificacion',
            'usrmod' => 'Codigo de usuario que modifico',
        ];
    }
    
    /**
     * Función que se utiliza para obtener los datos de los 
     * módulos municipales.
     */
    public function obtenerDatos()
    {
    	$count = Yii::$app->db->createCommand('select count(*) from calc_mm')->queryScalar();
    	
        $sql = "Select valor,to_char(fchdesde,'dd/mm/yyyy') as fchdesde, to_char(fchhasta,'dd/mm/yyyy') as fchhasta, to_char(fchmod,'dd/mm/yyyy') as fchmod FROM calc_mm";
        
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => (int)$count,
            'pagination'=> [
				'pageSize'=>$count,
			],
	        'sort' => [
	        	'attributes' => [
	        		'fchdesde',
	        		'fchhasta',
	        		'valor',
	        		'fchmod',
	        	],
	        	
	        	'defaultOrder' => [
	        		'fchdesde' => SORT_ASC,
	        	],
	        ],
        ]);
        
        return $dataProvider;
    }


		 /**
		 * Funci�n que valida los datos antes de guardar datos en la BD 
		 * 
		 * @return string error Valor con los errores ocurridos al validar. Devuelve cadena vacia ("") en caso de no haber errores.
		 */
		
	private function validar() {
		
		$error = [];
		
		//Validaciones que se ejecutan en caso de ingresar un nuevo registro
		if ( $this->isNewRecord )
		{
			if ( $this->fchdesde == 0 || $this->fchdesde == '' || $this->fchdesde == null ) {
	
				$error[] = 'Ingrese una Fecha Desde.';
			}
			
			if ( $this->fchhasta == 0 || $this->fchhasta == '' || $this->fchhasta == null ) {
	
				$error[] = 'Ingrese una Fecha Hasta.';
			}
			
			//Validar el rango de las fechas
			if ( !Fecha::menor( Fecha::usuarioToBD( $this->fchdesde ), Fecha::usuarioToBD( $this->fchhasta ) ) ) {
	
				$error[] = 'Rango de Períodos Incorrecto.';
			}
					 
			//Verificar si la fecha es distinta a vacío 
			if ( ! ( $this->fchdesde == 0 || $this->fchdesde == '' || $this->fchdesde == null ) )
			{
				$sql = "select Coalesce(max(fchdesde), '1970/01/01') from calc_mm where fchdesde < '".$this->fchdesde."'";
				
				$maxFchDesde = Yii :: $app->db->createCommand( $sql )->queryScalar();
				
				$sql = "select valor from calc_mm where fchdesde = '".$maxFchDesde."'";
				
				$valorModuloAnterior = Yii :: $app->db->createCommand($sql)->queryScalar();
				
				if ( floatval( $valorModuloAnterior ) >= floatval( $this->valor ) ){
					
					$error[] = 'Valor incorrecto.';
					
				}
			}  
		
		} else	//Validaciones que se dan en caso de que sea una actualización
		{
			
		}
	
		//Validaciones que son comunes tanto a insert como a update	
		
		if( floatval( $this->valor ) < 0 ){
			
			$error[] = 'Ingrese un valor mayor a 0';
		 } 
		
		$this->addErrors( $error );

	}
	
	/**
	 * Función utilizada para cargar y modificar datos en la BD.
	 */
	public function grabar()
	{
		//Validar los datos
		$this->validar();
		
		if ( ! ( $this->hasErrors() ) )
		{
			//Declaro la variable que almacenará los errores
			$error = [];
						
			/*SI ES UN NUEVO REGISTRO*/
			if ($this->isNewRecord) 
			{
				//SQL correspondiente a la validacion de una nuevo modulo municipal
	            $sql = "SELECT EXISTS (select 1 from calc_mm where fchdesde = " . Fecha::usuarioToBD( $this->fchdesde, 1 ) . " and fchhasta=". Fecha::usuarioToBD( $this->fchhasta, 1 ) . ")";
	
				// 	$cantidad almacenara el valor devuelto por la consulta realizada a la BD.
				// 	$cantidad -> Si es 0, indica que el modulo municipal no existe. 
				//	$cantidad -> Si es 1, indica que el modulo municipal ya existe.
	
				$cantidad = Yii :: $app->db->createCommand($sql)->queryScalar();
	
				if ( $cantidad == 1 ) {
	
					$error[] = 'Módulo Municipal Repetido.';
	
				} else {

					$sql = "insert into calc_mm values (" . Fecha::usuarioToBD( $this->fchdesde, 1 )  . "," . Fecha::usuarioToBD( $this->fchhasta, 1 ) ."," .
							number_format( floatval( $this->valor ), 2, ".", "" ).",current_timestamp,".Yii::$app->user->id.")";
					
					try
					{						
						$rowCount = Yii :: $app->db->createCommand($sql)->execute();
						
						if ( $rowCount == 0 ) 
						{
							$error[] = 'Ocurrió un error al intentar grabar en la BD.';
						}
			 		
			 		} catch(\Exception $e)
			 		{
						$error[] = DBException::getMensaje( $e );
			 		}
					
				}
				
			} else //Actualización de datos
			{
					
				$sql = 	"update calc_mm set valor = " . number_format( floatval( $this->valor ), 2, ".", "" ) . ",fchmod=current_timestamp,usrmod=".Yii::$app->user->id . 
						" where fchdesde = " . Fecha::usuarioToBD( $this->fchdesde, 1 ) . " and fchhasta = " . Fecha::usuarioToBD( $this->fchhasta, 1 );
				
				try
				{						
					$rowCount = Yii :: $app->db->createCommand($sql)->execute();
					
					if ( $rowCount == 0 ) 
					{
						$error[] = 'Ocurrió un error al intentar grabar en la BD.';
					}
					
		 		} catch(\Exception $e)
		 		{
		 			
		 			$error[] = DBException::getMensaje( $e );
		 		}

			}
			
			$this->addErrors( $error );
			
	    }
	    
	    return !$this->hasErrors();
	}
			
	     
}
