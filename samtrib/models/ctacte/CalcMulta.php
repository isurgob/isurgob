<?php

namespace app\models\ctacte;

use Yii;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use app\utils\db\utb;

/**
 * This is the model class for table "calc_multa".
 *
 * @property integer $trib_id
 * @property integer $perdesde
 * @property integer $perhasta
 * @property integer $tipo
 * @property string $montodesde
 * @property string $montohasta
 * @property integer $item_id
 * @property integer $tcalculo
 * @property string $valor
 * @property string $alicuota
 * @property integer $finmes
 * @property integer $diasvenc
 * @property string $quita
 * @property string $valormaximo
 * @property string $fchmod
 * @property integer $usrmod
 */
class CalcMulta extends \yii\db\ActiveRecord
{
    public $aniodesde;
	public $cuotadesde;
	public $aniohasta;
	public $cuotahasta;
	public $modif;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'calc_multa';
    }
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['trib_id', 'perdesde', 'perhasta', 'montodesde', 'montohasta', 'item_id', 'tcalculo'], 'required'],
            [['trib_id', 'perdesde', 'perhasta', 'aniodesde', 'aniohasta','cuotadesde','cuotahasta', 'tipo', 'item_id', 'tcalculo', 'finmes', 'diasvenc', 'usrmod'], 'integer'],
            [['montodesde', 'montohasta', 'valor', 'alicuota', 'quita', 'valormaximo'], 'number'],
            [['fchmod'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'trib_id' => 'Tributo',
            'perdesde' => 'Desde',
            'perhasta' => 'Hasta',
            'aniodesde' => 'Periodo',
            'cuotadesde' => 'Desde',
            'aniohasta' => 'Periodo',
            'cuotahasta' => 'Hasta',
            'tipo' => 'Tipo',
            'montodesde' => 'Monto Desde',
            'montohasta' => 'Monto Hasta',
            'item_id' => 'Item',
            'tcalculo' => 'Form. Calc.',
            'valor' => 'Valor',
            'alicuota' => 'Alicuota',
            'finmes' => '',
            'diasvenc' => '',
            'quita' => 'Quita (%)',
            'valormaximo' => 'Valor Max.',
            'modif' => 'Modificación: ',
        ];
    }
    
    
    /**
	 * Funci�n que valida los datos antes de guardar datos en la BD 
	 * 
	 * @return string error Valor con los errores ocurridos al validar. Devuelve cadena vacia ("") en caso de no haber errores.
	 */
	private function validar() {
		
		$error = "";
	
		if ($this->perdesde == 0) {

			$error .= "<li>Completar Periodo Desde.</li>";
		}
		
		if ($this->perhasta == 0) {

			$error .= "<li>Completar Periodo Hasta.</li>";
		}
		
		if ($this->perdesde > $this->perhasta) {

			$error .= "<li>Rango de Per�odos Incorrecto.</li>";
		}
		
		if ($this->montodesde > $this->montohasta) {

			$error .= "<li>Rango de Monto Incorrecto.</li>";
		}
		
		if ((int)utb::GetTObjTrib($this->trib_id) == 2)
		{
			if ($this->tipo == null or $this->tipo==0)
			{
				$error .= "<li>Seleccione un Tipo.</li>";
			}
		}

		if (($this->tcalculo == 1 or $this->tcalculo == 4 or $this->tcalculo == 5) and $this->valor < 0) {

			$error .= "<li>Valor Incorrecto para la F�rmula de C�lculo.</li>";

		}

		if (($this->tcalculo == 3 or $this->tcalculo == 4 or $this->tcalculo == 5) and $this->alicuota < 0) {

			$error .= "<li>Al�cuota Incorrecta para la F�rmula de C�lculo.</li>";

		}
		
		if ($this->diasvenc < 0){
			
			$error .= "<li>D�as de Vencimiento Incorrectos.</li>";
			
		}
		
		if ($this->quita < 0){
			
			$error .= "<li>Quita Incorrecta.</li>";
			
		}

		$sql = "select count(*) from item where item_id = " . $this->item_id;

		$existeItem = Yii :: $app->db->createCommand($sql)->queryScalar();

		if ($existeItem == 0) {

			$error .= "<li>No existe el item.</li>";

		}
		
		return $error;

	}
    
    /**
	 * Funcion que crea o modifica una multa en la base de datos.
	 * @return string Valor con los errores ocurridos. "" en caso de no haber ocurrido ning�n error. 
	 */
	public function grabar() {
		
		if ($this->aniodesde=="") $this->aniodesde=0;
		if ($this->cuotadesde=="") $this->cuotadesde=0;
		if ($this->aniohasta=="") $this->aniohasta=0;
		if ($this->cuotahasta=="") $this->cuotahasta=0;
		if ($this->valormaximo=="") $this->valormaximo=0;
		if ($this->valor=="") $this->valor=0;
		if ($this->alicuota=="") $this->alicuota=0;
		if ($this->quita=="") $this->quita=0;
		if ($this->tipo=="") $this->tipo=0;
		if ($this->diasvenc=="") $this->diasvenc=0;
			
		if ($this->quita==0) {
			$this->finmes=0;
			$this->diasvenc=0;	
		}
			
		
		$this->perdesde = $this->aniodesde*1000+$this->cuotadesde;
		$this->perhasta = $this->aniohasta*1000+$this->cuotahasta;
		
				
		//Si es un nuevo registro
		if ($this->isNewRecord) {
			
			//$validar string que almacenar� los mensajes de error ocurridos durante la validaci�n (en caso de ocurrir)
			$validar = $this->validar();

			//SQL correspondiente a la validaci�n de una nueva multa
			$sql = "Select count(*) From calc_multa Where Trib_id=".$this->trib_id." and perdesde=". $this->perdesde;
			$sql .= " and perhasta=". $this->perhasta." and  montodesde=".str_replace(",", ".", $this->montodesde);
			$sql .= " and montohasta=".str_replace(",", ".", $this->montohasta);
			$sql .= " and tipo=" . $this->tipo. " and item_id=".$this->item_id." and tcalculo=".$this->tcalculo;

			// 	$cantidad almacenar� el valor devuelto por la consulta realizada a la BD.
			// 	$cantidad -> Si es 0, indica que la multa no existe. 
			//	$cantidad -> Si es 1, indica que la multa ya existe.

			$cantidad = Yii :: $app->db->createCommand($sql)->queryScalar();

			if ($cantidad > 0) {

				$validar .= "<li>Multa Repetida.</li>";

				return $validar;

			} else {

				//C�digo que se ejecuta en el caso de ingresar nuevo valores a la tabla calc_desc     		

				if ($validar == "") {

					$sql = "insert into calc_multa values (".$this->trib_id.",".$this->perdesde.",".$this->perhasta;
					$sql .= ",".$this->tipo. ",".str_replace(",", ".", $this->montodesde).",".str_replace(",", ".", $this->montohasta);
					$sql .= ",".$this->item_id.",".$this->tcalculo.",".str_replace(",", ".", $this->valor);
					$sql .= ",".str_replace(",", ".", $this->alicuota).",".$this->finmes.",".$this->diasvenc.",".str_replace(",", ".", $this->quita);
					$sql .= ",".str_replace(",", ".", $this->valormaximo).",current_timestamp,".Yii::$app->user->id.")";

					$cmd = Yii :: $app->db->createCommand($sql);
					$rowCount = $cmd->execute();

					if ($rowCount > 0) 
					{
						return "";

					} else {

						return "Ocurrio un error al intentar grabar en la BD.";
					}

				} else {

					return $validar;

				}

			}

		} else {
			
			//$validar string que almacenar� los mensajes de error ocurridos durante la validaci�n (en caso de ocurrir)
			$validar = $this->validar();
			
			//C�digo que se ejecuta en el caso de actualizar valores a la tabla calc_multa
			
			if ($validar == "") {

				$sql = "update calc_multa set ";
				$sql .= "perhasta=".$this->perhasta.",item_id=".$this->item_id.",valor=".str_replace(",", ".", $this->valor) ;
				$sql .= ",alicuota=".str_replace(",", ".", $this->alicuota).",tcalculo=".$this->tcalculo;
				$sql .= ",finmes=".$this->finmes.",diasvenc=".$this->diasvenc.",quita=".str_replace(",", ".", $this->quita);
				$sql .= ",valormaximo=".str_replace(",", ".", $this->valormaximo).",fchmod=current_timestamp,usrmod=".Yii::$app->user->id;
				$sql .= " where trib_id=".$this->trib_id." and perdesde=".$this->perdesde." and montodesde=".str_replace(",", ".", $this->montodesde);
				$sql .= " and montohasta=".str_replace(",", ".", $this->montohasta)." and tipo=".$this->tipo;

				
            	$cmd = Yii :: $app->db->createCommand($sql);
				$rowCount = $cmd->execute();

				if ($rowCount > 0) {

					return $validar;

				} else {

					return "Ocurrio un error al intentar actualizar los datos en la BD.";
				}

			} else {

				return $validar;

			}

		}
	}
    
    /**
     * Funcion que realiza la baja de una multa en la base de datos
     */
    public function borrar() {
    	$sql = "delete from calc_multa where trib_id=" . $this->trib_id." and perdesde=".$this->perdesde;
    	$sql = $sql." and perhasta=".$this->perhasta." and tipo=".$this->tipo." and montodesde=".$this->montodesde;
    	$sql = $sql." and montohasta=".$this->montohasta;
    	$cmd = Yii::$app->db->createCommand($sql);
    	$rowCount = $cmd->execute();
    	return $rowCount > 0; 
    }
        
    public function BuscarMulta($cond,$orden)
    {       
        $count = Yii::$app->db->createCommand('Select count(*) From v_calc_multa '.($cond!=="" ? " where ".$cond : ""))->queryScalar();
         
        $sql = 'Select * from v_calc_multa ';
        if ($cond !== "") $sql = $sql.' where '.$cond;
        $sql = $sql.' Order By '.$orden; 
        
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'key' => 'trib_id',
            'totalCount' => (int)$count,
			'pagination'=> [
				'pageSize'=>20,
			],
        ]); 

        return $dataProvider;
    }
    
    /**
	 * Funci�n para calcular multa
	 * @param integer c�digo de tributo
	 * @param string c�digo de objeto
	 * @param integer periodo
	 * @param numeric monto
	 * @param string fecha
	 */
    public function CalcularMulta($trib,$obj,$periodo,$monto,$fecha)
    { 
    	$multa = 0;
    	
    	$sql = "Select sum(multa) from sam.uf_calc_multa(".$trib.",".$obj.",".$periodo.",";
    	$sql .= str_replace(",", ".", $monto).",".$fecha.")";
    	
    	   	
    	$multa = Yii::$app->db->createCommand($sql)->queryScalar();
    	
    	return $multa;
    }
    
}
