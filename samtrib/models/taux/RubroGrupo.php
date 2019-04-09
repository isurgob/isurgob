<?php

namespace app\models\taux;

use Yii;
use app\utils\db\utb;
use app\utils\db\Fecha;
use yii\data\ArrayDataProvider;


class RubroGrupo extends \yii\db\ActiveRecord{

    public static function tableName(){

        return 'rubro_grupo';
    }

    
    public function rules(){

        return [
            [['cod', 'nomen_id', 'nombre'], 'required', 'on' => ['insert', 'update', 'delete']],
			[[ 'cod', 'nomen_id', 'nombre' ], 'string', 'on' => [ 'insert', 'update', 'delete' ]],
            
        ];  
    }
    
    public function scenarios(){

        return[
            'insert'    => [ 'cod', 'nomen_id', 'nombre' ],
			'update'    => [ 'cod', 'nomen_id', 'nombre' ],
            'delete'    => [ 'cod', 'nomen_id' ]
        ];

    }

    public function attributeLabels(){

        return [
            'cod' => 'Código',
			'nomen_id' => 'Nomeclador'
        ];
    }
    
    public function Grabar(){

        try{

            switch( $this->scenario ){

                case 'insert':  //Insert

                    $sql =  "INSERT INTO rubro_grupo VALUES ( '" .
                            "$this->cod', '$this->nomen_id', '$this->nombre', CURRENT_TIMESTAMP, " . Yii::$app->user->id . ")";

                    Yii::$app->db->createCommand( $sql )->execute();

                    break;

                case 'update': //UPDATE

                    $sql =  "UPDATE rubro_grupo SET nombre = '$this->nombre', fchmod = CURRENT_TIMESTAMP, usrmod = " . Yii::$app->user->id . " WHERE cod = '$this->cod' and nomen_id='$this->nomen_id'";
							
					Yii::$app->db->createCommand( $sql )->execute();

                    break;

                case 'delete': //Delete

                    $sql =  "delete from rubro_grupo WHERE cod = '$this->cod' and nomen_id='$this->nomen_id'";

                    Yii::$app->db->createCommand( $sql )->execute();

                    break;
            }

        } catch( \Exception $e ){

            $this->addError( 'cod', $e->getMessage() );

            return false;
        }

        return true;

    }
	
	public function getGrupoRubro( $nomen_id = '' ){
	
		$sql = "select g.cod, g.nomen_id, n.nombre nomen_nom, g.nombre 
				from rubro_grupo g
				inner join rubro_tnomen n on g.nomen_id = n.nomen_id";
				
		if ( $nomen_id !='' )		
			$sql .= " where g.nomen_id = '$nomen_id' ";
				
		$datos = Yii::$app->db->createCommand( $sql )->queryAll();
		
		$dpDatos = new ArrayDataProvider([ 'allModels' => $datos, 'pagination' => false ]);
		
		return $dpDatos;
		
	}
    
}
