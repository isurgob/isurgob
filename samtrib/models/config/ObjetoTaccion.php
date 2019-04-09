<?php

namespace app\models\config;

use yii\data\ArrayDataProvider;
use app\utils\db\utb;
use Yii;
use app\utils\helpers\DBException;

class ObjetoTaccion extends \yii\db\ActiveRecord
{
	public $modif;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'objeto_taccion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre', 'interno', 'estactual', 'estnuevo',], 'required'],
            [['cod', 'tobj', 'desdehasta', 'usrmod'], 'integer'],
            [['nombre'], 'string', 'max' => 40],
            [['interno', 'estnuevo'], 'string', 'max' => 1],
            [['estactual'], 'string', 'max' => 10],

            [['nombre'], 'unicoNombre', 'on' => ['nuevo', 'modifica'] ],

            [['cod'], 'verifiqueExistencia', 'on' => ['elimina'] ],
			
			
        ];
    }

    public function scenarios(){

        return[

            'consulta' => [ 'cod', 'tobj', 'desdehasta', 'nombre', 'interno', 'estnuevo', 'estactual' ],
            'nuevo' => [ 'cod', 'tobj', 'desdehasta', 'nombre', 'interno', 'estnuevo', 'estactual' ],
            'modifica' => [ 'cod', 'tobj', 'desdehasta', 'nombre', 'interno', 'estnuevo', 'estactual' ],
            'elimina' => [ 'cod' ]

        ];
    }

    public function unicoNombre(){

        $sql = "SELECT count(*) FROM objeto_taccion WHERE upper(nombre)=upper('".$this->nombre."')";
        if ( $this->scenario == 'modifica' ) 
            $sql .= " and cod<>$this->cod";

        $count = Yii::$app->db->createCommand($sql)->queryScalar();

        if ( $count > 0 )
            $this->addError( 'nombre',  "El Nombre ya existe." );
    }

    public function verifiqueExistencia(){

        $sql = "SELECT count(*) FROM objeto_accion WHERE taccion=$this->cod";

        $count = Yii::$app->db->createCommand($sql)->queryScalar();

        if ( $count > 0 )
            $this->addError( 'cod',  "No se puede eliminar el codigo esta siendo utilizado." );
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod' => '',
            'tobj' => 'Tipo de Objeto',
            'nombre' => 'Nombre',
            'interno' => '',
            'estactual' => 'Estado actual',
            'estnuevo' => 'Nuevo estado',
            'desdehasta' => 'Pedir Rango de Fechas',
            'fchmod' => 'fchmod',
            'usrmod' => 'Codigo de usuario que modifico',
            'modif' => 'Modif',
        ];
    }
    
    /**
     *  
     */
    public function afterFind()
    {
    	$this->modif = utb::getFormatoModif($this->usrmod,$this->fchmod);
    }


    public function buscarObjetoTaccion( $cond = '' ){  

        $sql = "select t.*,CASE WHEN t.tobj = 0 THEN 'Todos' ELSE o.nombre END as tobj_nom, " .
                "(u.nombre || ' - ') || to_char(t.fchmod, 'DD/MM/YYYY') AS modif, " .
                "(case t.interno when 'N' then 'NO' else 'SI' end) as Interno_Nom " .
                "from objeto_taccion t ";
        
        $sql .= " left join objeto_tipo o on t.tobj=o.cod " .
                "inner join sam.sis_usuario u on t.usrmod=u.usr_id ";   
        
        if ( $cond != '' )
            $sql .= " WHERE " . $cond;
            
        $sql .= " order by t.tobj, t.nombre";
        
        $data = Yii::$app->db->createCommand( $sql )->queryAll();
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [ 'pagesize' => count($data) ]
        ]);
        
        return $dataProvider;
    }

    public function Grabar(){

        $transaction = Yii::$app->db->beginTransaction();

        try{
        
            switch( $this->scenario ){

                case 'nuevo': 
                    
                    $cod = $this->obtenerCodAutoincremental();

                    $sql = "INSERT INTO objeto_taccion VALUES(".$cod.",".$this->tobj.",'".$this->nombre."','".strtoupper( $this->interno )."'";
                    $sql .= ",'".$this->estactual."','".$this->estnuevo."','".$this->desdehasta."'";
                    $sql .= ",current_timestamp,".Yii::$app->user->id.")";

                    break;

                case 'modifica':

                    $sql = "UPDATE objeto_taccion SET ";
                    $sql .= " tobj=".$this->tobj.",nombre='".$this->nombre."',interno='".strtoupper( $this->interno )."'";
                    $sql .= ",estactual='".$this->estactual."',estnuevo='".$this->estnuevo."',desdehasta=".$this->desdehasta;
                    $sql .= ",fchmod=current_timestamp,usrmod=".Yii::$app->user->id;
                    $sql .= " WHERE cod = ".$this->cod;

                    break;

                 case 'elimina':

                    $sql = "DELETE FROM objeto_taccion WHERE cod=".$this->cod;

                    break;    
                
            }
            
            
            Yii::$app->db->createCommand( $sql )->execute();    
            
            $transaction->commit();

        } catch(\Exception $e ){

            $transaction->rollback();
            $this->addError( 'cod',  $e->getMessage() );
            
            return false;
        }
        
        return true;
    }

    public function obtenerCodAutoincremental(){
        $sql = "select MAX(cod) from objeto_taccion";
        $valor = Yii::$app->db->createCommand($sql)->queryScalar() + 1;     
        return $valor;  
    }
    
    /*public function NuevaTaccion()
    {
    	$interno = strtoupper( $this->interno );

		$validar = $this->validar( 0 );
		
		if( count( $validar ) > 0 )
			return $validar;

		$cod = $this->obtenerCodAutoincremental();
		
		$sql = "INSERT INTO objeto_taccion VALUES(".$cod.",".$this->tobj.",'".$this->nombre."','".$interno."'";
		$sql .= ",'".$this->estactual."','".$this->estnuevo."','".$this->desdehasta."'";
		$sql .= ",current_timestamp,".Yii::$app->user->id.")";
		
		try
		{						
			$cmd = Yii::$app->db->createCommand($sql);
			$rowCount = $cmd->execute();
		
		} catch(\Exception $e)
		{
			$validar[] = DBException::getmensaje( $e );
		}
				
		if ( $rowCount == 0 )
		{
			$validar[] = 'Ocurrió un error al intentar grabar en la BD.';
		}
		
		return $validar;
    }
    
    public function ModificarTaccion()
    {
    	
    	$interno = strtoupper($this->interno);
    	    			
		$validar = $this->validar( 3 );
		
		if( count( $validar > 0 ) )
			return $validar;
		
		$sql = "UPDATE objeto_taccion SET ";
		$sql .= " tobj=".$this->tobj.",nombre='".$this->nombre."',interno='".$interno."'";
		$sql .= ",estactual='".$this->estactual."',estnuevo='".$this->estnuevo."',desdehasta=".$this->desdehasta;
		$sql .= ",fchmod=current_timestamp,usrmod=".Yii::$app->user->id;
		$sql .= " WHERE cod = ".$this->cod;

		try
		{	
			$cmd = Yii :: $app->db->createCommand($sql);
			$rowCount = $cmd->execute();
	 	}
	 	catch(\Exception $e)
	 	{
	 		$validar[] = DBException::getMensaje( $e );
	 	}
	 	
	 	if ( $rowCount == 0 ) {
			$validar[] = 'Ocurrió un error al intentar actualizar los datos en la BD.';
		}
		
		return $validar;
    }
    
    
    public function BorrarTaccion($cod){
	
	$sql = "SELECT count(*) FROM objeto_accion WHERE taccion=".$cod;
	$cantidad = Yii::$app->db->createCommand($sql)->queryScalar();
		
	if($cantidad > 0){
		$validar .= "<li>No se puede eliminar el codigo esta siendo utilizado</li>";
	}else{
    	$sql = "DELETE FROM objeto_taccion";
		$sql .= " WHERE cod=".$cod;
		try{					
			$cmd = Yii :: $app->db->createCommand($sql);
			$cmd->execute();
	 	}
	 	catch(\Exception $e){
	 		$validar = strstr($e->getMessage(), 'The SQL being', true);
			$validar = substr($validar, strlen('SQLSTATE[P0001]: Raise exception: 7'));
	 		return "<li>".$validar."</li>";
	 	}
	}
 	
 	return $validar;    	
    }*/
    
    
    
    
   /**/
    
    
}
