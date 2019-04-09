<?php


namespace app\models\taux;
use yii\data\ArrayDataProvider;
use app\utils\db\utb;
use app\utils\helpers\DBException;
use Yii;


class CemCuadro extends \yii\db\ActiveRecord {

    public $cuerpos;
	
	public static function tableName(){

        return 'cem_cuadro';
    }
	
	public function __construct(){
		
		$this->cuerpos = $this->getCuerpos();
	}

    public function rules(){
		
		return [
            [['cua_id', 'nombre'], 'required', 'on' => ['nuevo', 'modificar', 'eliminar']  ],
			
			[[ 'cua_id', 'nombre', 'tipo' ], 'string'],
			[[ 'piso', 'fila', 'nume', 'bis' ], 'integer'],
			
			[['cua_id'], 'validarCuadro', 'on' => ['nuevo'] ],
			[['nombre'], 'validarNombre', 'on' => ['nuevo', 'modificar'] ],
			
			[['cua_id'], 'validarExisteCem', 'on' => ['eliminar'] ]
        ];

    }
	
		public function attributeLabels(){

		return [
			'cua_id' => 'ID'
		];
	}


	
	public function validarCuadro(){
	
		if ( $this->cua_id == '' )
			$this->addError( 'cua_id',  "Ingrese un Id de cuadro." );
			
		$sql = "select count(*) from cem_cuadro where cua_id='$this->cua_id'";
		$count = intVal( Yii::$app->db->createCommand( $sql )->queryScalar() );
		
		if ( $count > 0 )
			$this->addError( 'cua_id',  "El Id de cuadro ya existe." );
	}
	
	public function validarNombre(){
	
		if ( $this->nombre == '' )
			$this->addError( 'nombre',  "Ingrese un Nombre de cuadro." );
			
		$sql = "select count(*) from cem_cuadro where upper(nombre)=upper('$this->nombre')";
		if ( $this->scenario == 'modificar' )
			$sql .= " and cua_id<>'$this->cua_id'";
			
		$count = intVal( Yii::$app->db->createCommand( $sql )->queryScalar() );
		
		if ( $count > 0 )
			$this->addError( 'nombre',  "El Nombre de cuadro ya existe." );
	}
	
	public function validarExisteCem(){
		
		$sql = "select count(*) from cem where cua_id='$this->cua_id'";
		$count = intVal( Yii::$app->db->createCommand( $sql )->queryScalar() );
		
		if ( $count > 0 )
			$this->addError( 'cua_id',  "Existen cementerio vinculados al Cuadro. No puede eliminarlo." );
	}
	
	public function scenarios(){

        return[

            'nuevo' => [ 'cua_id', 'nombre', 'tipo', 'piso', 'fila', 'nume', 'bis' ],

            'modificar' => [ 'cua_id', 'nombre', 'tipo', 'piso', 'fila', 'nume', 'bis' ],

            'eliminar' => [ 'cua_id' ],

        ];
    }
	
	public function afterFind(){
	
		$this->cuerpos = $this->getCuerpos();
	}
	
	public function getCuadros(){
	
		$sql = "select c.*, t.nombre tipo_nom, u.nombre || ' - ' || to_char(c.fchmod, 'dd/mm/yyyy') modif  from cem_cuadro c " . 
				"inner join sam.sis_usuario u on c.usrmod = u.usr_id " . 
				"left join cem_tipo t on c.tipo=t.cod " . 
				"order by c.cua_id";
		
		$data = Yii::$app->db->createCommand( $sql )->queryAll();

        $dataProvider = new ArrayDataProvider([

            'allModels' => $data,
            'pagination'=> [
				'pageSize' => count($data)
			],
        ]);

        return $dataProvider;
	}
	
	public function getCuerpos(){
	
		$sql = "select cua_id, cue_id, nombre from cem_cuerpo " . 
				"where cua_id='$this->cua_id'";
		
		$data = Yii::$app->db->createCommand( $sql )->queryAll();
		
		$dataProvider = new ArrayDataProvider([

            'allModels' => $data,
            'pagination'=> [
				'pageSize' => count($data)
			],
        ]);

        return $dataProvider;
	}
	
	public function grabarCuerpo( $id, $nombre, $action ){
		
		
		$arrayCuerpos = $this->cuerpos; // llega como array
		
		$keyId = array_search( $id, array_column($arrayCuerpos, 'cue_id'));
		$keyNom = array_search( $nombre, array_column($arrayCuerpos, 'nombre'));
		
		// si es nuevo y no existe id y nombre del cuerpo
		if ( $action == 0 ){ 
			if ( $keyNom === false and $keyId === false ){
				$arrayCuerpos[] = [
						'cua_id' => $this->cua_id,
						'cue_id' => $id,
						'nombre' => $nombre
					];
			}else 
				$this->addError( "cua_id", "El id o nombre ya existen." );		
		}
		
		// si es modificacion y no exite el nombre 
		if ( $action == 3 ){ 
			if ( $keyId === $id or $keyNom === false )
				$arrayCuerpos[$keyId]['nombre'] = $nombre;
			else 
				$this->addError( "cua_id", "El nombre ya existe." );	
		}
		
		// si se elimina 
		if ( $action == 2 ){ 
			
			$sql = "select count(*) from cem where cua_id='$this->cua_id' and cue_id='$id'";
			$count = intVal( Yii::$app->db->createCommand( $sql )->queryScalar() );
			
			if ( $count == 0 )
				unset($arrayCuerpos[$keyId]);
			else 	
				$this->addError( "cua_id", "El cuadro y cuerpo existe en cementerio. No podrá eliminarlo." );
		}
		
		// convierto a dataProvider
		$this->cuerpos = new ArrayDataProvider([
            'allModels' => $arrayCuerpos,
            'pagination'=> [
				'pageSize' => count($arrayCuerpos)
			],
        ]);
		
	}
	
	public function Grabar(){

		$transaction = Yii::$app->db->beginTransaction();

		try{
		
			switch( $this->scenario ){

				case 'nuevo':	//Alta 
					
					$sql = 	"INSERT INTO cem_cuadro " . 
							" VALUES ('$this->cua_id', '$this->nombre', '$this->tipo', $this->piso, $this->fila, $this->nume, $this->bis, CURRENT_TIMESTAMP," . Yii::$app->user->id . ")";

					break;

				case 'eliminar':	//Baja 

					$sql = "delete from cem_cuadro where cua_id='$this->cua_id'";

					break;

				case 'modificar':	//Modificación 

					$sql = 	"UPDATE cem_cuadro SET nombre = '$this->nombre',tipo = '$this->tipo',piso = $this->piso, fila=$this->fila, nume=$this->nume,bis=$this->bis," .
							"fchmod = CURRENT_TIMESTAMP, usrmod = " . Yii::$app->user->id .
							" WHERE cua_id = '$this->cua_id'";

					break;
				
			}
			
			
			// ejecuto consulta de ABM de cuadro 
			Yii::$app->db->createCommand( $sql )->execute();	
			
			// elimino los cuerpos asociados al cuadro
			$sql = "delete from cem_cuerpo where cua_id='$this->cua_id'";
			Yii::$app->db->createCommand( $sql )->execute();	
			
			// inserto los cuerpos del cuadro si es existen 
			if ( count($this->cuerpos) > 0 ){
				foreach( $this->cuerpos as $c ){
					$sql = "insert into cem_cuerpo values( '$this->cua_id', '" . $c['cue_id'] . "', '" . $c['nombre'] . "', CURRENT_TIMESTAMP, " . Yii::$app->user->id . " )";
					Yii::$app->db->createCommand( $sql )->execute();	
				}
			}
			
			$transaction->commit();

		} catch(\Exception $e ){

			$transaction->rollback();
			$this->addError( 'cua_id',  DBException::getMensaje($e) );
			
			return false;
		}
		
		return true;
		
	}

}

?>
