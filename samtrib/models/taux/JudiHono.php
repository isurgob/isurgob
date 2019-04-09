<?php

namespace app\models\taux;

use Yii;
use app\utils\db\utb;
use yii\helpers\ArrayHelper;
use app\utils\db\Fecha;
use app\utils\helpers\DBException;


class JudiHono extends \yii\db\ActiveRecord{

    public $est_nom;
    
    public static function tableName(){

        return 'judi_hono';
    }

    
    public function rules(){

        return [
            [[ 'est', 'supuesto', 'deuda_desde', 'deuda_hasta' ], 'required', 'on' => [ 'insert', 'delete', 'update' ]],
            [[ 'est' ], 'string', 'max' => 1, 'on' => [ 'insert', 'update', 'delete' ]],
            [[ 'supuesto' ], 'integer', 'on' => [ 'insert', 'update', 'delete' ]],
            [[ 'deuda_desde', 'deuda_hasta', 'hono_min', 'hono_porc', 'gastos' ], 'double', 'on' => [ 'insert', 'update' ]],
            [[ 'deuda_desde', 'deuda_hasta'], 'validarDeuda', 'on' => [ 'insert' ], 'skipOnEmpty' => false, 'skipOnError' => false ],
            [[ 'est', 'supuesto', 'deuda_desde', 'deuda_hasta'], 'validarExistencia', 'on' => [ 'insert' ], 'skipOnEmpty' => false, 'skipOnError' => false ],
            
            [['hono_min', 'hono_porc', 'gastos'], 'default', 'value' => 0, 'on' => [ 'insert', 'update' ]]
        ];  
    }
    
    public function validarDeuda(){

        if( $this->deuda_desde > $this->deuda_hasta ){

            $this->addError( $this->deuda_desde, "Rango de Deuda mal ingresado." );
        }

    }

    public function validarExistencia(){

        $cant = Yii::$app->db->createCommand( "SELECT count(*) from judi_hono where est='$this->est' and supuesto=$this->supuesto and deuda_desde=$this->deuda_desde and deuda_hasta=$this->deuda_hasta" )->queryScalar();
        if( $cant > 0 ){

            $this->addError( $this->est, "El registro ya existe." );
        }

    }

    public function scenarios(){

        return[
            'insert'    => [
                'est', 'supuesto', 'deuda_desde', 'deuda_hasta', 'hono_min', 'hono_porc', 'gastos'
            ],

            'delete'    => [ 'est', 'supuesto', 'deuda_desde', 'deuda_hasta' ],

            'update'    => [
                'est', 'supuesto', 'deuda_desde', 'deuda_hasta', 'hono_min', 'hono_porc', 'gastos'
            ],
        ];

    }

    public function attributeLabels(){

        return [
            'est' => 'Estado',
        ];
    }
    
    public function afterFind(){
        $this->est_nom = utb::getCampo("judi_test","cod='" . $this->est."'");
    }
    
    public function grabar( $action ){

        try{

            switch( $action ){

                case 0:  //Insert

                    $sql =  "INSERT INTO judi_hono ( est, supuesto, deuda_desde, deuda_hasta, hono_min, hono_porc, gastos, fchmod, usrmod ) VALUES ( '" .
                            "$this->est', $this->supuesto, $this->deuda_desde, $this->deuda_hasta, $this->hono_min, $this->hono_porc, " .
                            "$this->gastos, CURRENT_TIMESTAMP, " . Yii::$app->user->id . ")";

                    Yii::$app->db->createCommand( $sql )->execute();

                    break;

                case 2: //Delete

                    $sql =  "delete from judi_hono WHERE est = '$this->est' and supuesto=$this->supuesto and deuda_desde=$this->deuda_desde and deuda_hasta=$this->deuda_hasta ";

                    Yii::$app->db->createCommand( $sql )->execute();

                    break;

                case 3: //Update

                    $sql =  "UPDATE judi_hono SET hono_min = $this->hono_min, hono_porc = $this->hono_porc, gastos = $this->gastos, " .
                            " fchmod = CURRENT_TIMESTAMP, usrmod = " . Yii::$app->user->id . " WHERE est = '$this->est' and supuesto=$this->supuesto and deuda_desde=$this->deuda_desde and deuda_hasta=$this->deuda_hasta  ";

                    Yii::$app->db->createCommand( $sql )->execute();

                    break;
            }

        } catch( \Exception $e ){

            $this->addError( 'est', DBException::getMensaje( $e ) );

            return false;
        }

        return true;

    }
    
}
