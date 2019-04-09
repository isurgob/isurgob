<?php
namespace app \ models \ ctacte;


use Yii;
use yii \ helpers \ ArrayHelper;
use yii\data\SqlDataProvider;
use app\utils\db\Fecha;

/**
 * This is the model class for table "calc_desc".
 *
 * @property integer $desc_id
 * @property integer $trib_id
 * @property integer $item_id
 * @property integer $anual
 * @property integer $perdesde
 * @property integer $perhasta
 * @property integer $aniodesde
 * @property integer $cuotadesde
 * @property integer $aniohasta
 * @property integer $cuotahasta
 * @property integer $aplicavenc
 * @property date $pagodesde
 * @property date $pagohasta
 * @property string $montodesde
 * @property string $montohasta
 * @property integer $verificadeuda
 * @property integer $existedeuda
 * @property integer $verificadebito
 * @property integer $verificaexen
 * @property string $desc1
 * @property string $desc2
 * @property integer $cta_id
 * @property string $fchmod
 * @property integer $usrmod
 */
class CalcDesc extends \ yii \ db \ ActiveRecord {
	
	public $aniodesde;
	public $cuotadesde;
	public $aniohasta;
	public $cuotahasta;
	public $modif;
	
	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'calc_desc';
	}
	/*
	 * La variable $error es una variable que mantendrá los errores que fueron ocurriendo
	 */
	private $error = "";
	
	private function resetError(){
		
		$this->error = "";
		
	}
	
	private function setError($mensajeError){
		
		$this->error = $this->error . $mensajeError;
		
	} 
	
	public function getError () {
		
		return $this->error;
		
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		
		return[[['trib_id', 'anual', 'perdesde', 'perhasta', 'aplicavenc', 'montodesde', 'montohasta', 'verificadeuda', 'existedeuda', 'desc1', 'desc2', 'cta_id','aniodesde','aniohasta'], 'required'],
				[['trib_id', 'item_id', 'anual', 'aplicavenc', 'aniodesde', 'cuotadesde', 'aniohasta', 'cuotahasta', 'verificadeuda', 'existedeuda', 'verificadebito', 'verificaexen', 'cta_id', 'usrmod'], 'integer'],
				[['pagodesde', 'pagohasta', 'fchmod'], 'safe'],
				//[['pagodesde, pagohasta'], 'match', 'pattern' => '/^\d{4}\/\d{1,2}\/\d{1,2}/'],
				//[['pagodesde, pagohasta'], 'date', /*'dateFormat' => 'yyyy-MM-dd'*/],
				[['montodesde', 'montohasta', 'desc1', 'desc2'], 'number'],
				[['aplicavenc'], 'validaCheck']
			];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return[
				'trib_id' => 'Tributo', 
				'item_id' => 'Item',
				'anual' => 'Anual', 
				'aniodesde' => 'Año/Cuota desde:',
				'cuotadesde' => 'Cuota:',  
				'aniohasta' => 'Hasta: ', 
				'cuotahasta' => 'Cuota hasta:', 
				'aplicavenc' => 'Aplica vencimiento', 
				'pagodesde' => 'Pago desde: ', 
				'pagohasta' => 'Hasta: ', 
				'montodesde' => 'Monto desde: ', 
				'montohasta' => 'Hasta: ', 
				'verificadeuda' => 'Verificar Deuda',
				'verificadebito' => 'Verificar Debito',
				'desc1' => 'Desc. 1er. Vencimiento',
				'desc2' => 'Desc. 2do. Vencimiento',
				'existedeuda' => 'Existedeuda',
				'verificaexen' => 'Verificar Eximision', 
				'fchmod' => 'Fchmod', 
				'usrmod' => 'Modificación',
				'cta_id' => 'Cuenta',
				'modif' => 'Modificación',];
	}
	
	    public function validaCheck (){
    	
    	$check1 = User::findByUsername($this->aplicavenc);
    	
    	if (/*$check == 0 or*/ $check == 1){
    		
    	} else {
    		
    		$this->addError('aplicavenc', 'Valor incorrecto para aplicavenc.');
    	}
    }
	 
	 /**
	  * Función que recibe un string con una fecha y lo transforma en un arreglo.
	  * @param string fechaString Valor del string con formato de fecha
	  * @return array fecha con resultado:
	  * 	$fecha[0] => "Año"
	  * 	$fecha[1] => "Mes"
	  * 	$fecha[2] => "Día"
	  */
	 private function stringToDate($fechaString) {
	 	
		$fecha = explode("/", $fechaString);
		
		return $fecha;
	 }

	/**
	 * Función que valida los datos antes de guardar datos en la BD 
	 * 
	 * @return string error Valor con los errores ocurridos al validar. Devuelve cadena vacia ("") en caso de no haber errores.
	 */
	private function validar() 
	{
		//Índice y error son variables usadas para crear un array de errores
		$indice = 0;
		$error = [];
		
		//Si desc2 es vacío, le asigno un 0
		if ($this->desc2 == "") 
			$this->desc2 = 0;
		
		//Transformo perdesde y perhasta para que puedan ser insertados en la BD
		$this->perdesde = $this->aniodesde * 1000 + $this->cuotadesde;
		$this->perhasta = $this->aniohasta * 1000 + $this->cuotahasta;
		
		
		//Si aplicavenc = 1, no se necesitan validar las fechas
		if ($this->aplicavenc != 1) 
		{
			if ($this->pagodesde == "")
				$error[$indice++] = "Pago desde no puede ser vacío.";

			if ($this->pagohasta == "")
				$this->pagohasta = "NULL";
		}	
			
		//Si "verifica deuda" es falso, no se debe validar "Existe Deuda", se graba con 0.
		//Cambia el valor de ExisteDeuda en caso de alguna anomalía
		if ($this->verificadeuda == 0){
			$this->existedeuda = 0;
		}
		
		//Valida las fechas en caso de que no haya ocurrido ningún error
		if (count($error) == 0)
		{
		
			if ($this->aplicavenc == 0)
			{	
				if($this->pagohasta != "NULL")
				{
					if (!Fecha::menor(Fecha::usuarioToBD($this->pagodesde), Fecha::usuarioToBD($this->pagohasta)))
						$error[$indice++] = "Pago hasta es menor a Pago desde.";	
				}
			} else {
				$this->pagodesde = "NULL";
				$this->pagohasta = "NULL";
			}
		}
				
		if ($this->perdesde > $this->perhasta) {

			$error[$indice++] = "Rango de Períodos Incorrecto.";

		}

		if ($this->existedeuda < 0 and $this->existedeuda > 2) {

			$error[$indice++] = "Valor de Existe Deuda Incorrecto.";

		}

		if ($this->desc1 < 0 or $this->desc2 < 0) {

			$error[$indice++] = "Valor de Descuento Incorrecto.";

		}

		$sql = "select count(*) from cuenta where cta_id = " . $this->cta_id;

		$existeCuenta = Yii :: $app->db->createCommand($sql)->queryScalar();

		if ($existeCuenta == 0) {

			$error[$indice++] = "No existe la cuenta.";

		}
		
		//Pongo en blanco los datePicker en el caso de que haya errores
		if (count($error) != 0)
		{
			$this->pagodesde = '';
			$this->pagohasta = '';
		}
		
		//Agrego los errores en caso de que existan
		$this->addErrors($error);

	}

	/**
	 * Funcion que crea o modifica un descuento en la base de datos.
	 * @return string Valor con los errores ocurridos. "" en caso de no haber ocurrido ning�n error. 
	 */
	public function grabar() {
		
		//$validar string que almacenará los mensajes de error ocurridos durante la validación (en caso de ocurrir)
		$this->validar();

		if(!$this->hasErrors()){
			
			$this->pagodesde= $this->aplicavenc != 1 ? Fecha::usuarioToBD($this->pagodesde) : 'NULL';
			$this->pagohasta= $this->aplicavenc != 1 ? Fecha::usuarioToBD($this->pagohasta) : 'NULL';
			$this->item_id = (integer)$this->item_id;
			//Índice y error son variables usadas para crear un array de errores
			$indice = 0;
			$error = [];
			
			//Si es un nuevo registro
			if ($this->isNewRecord) {
	
				//SQL correspondiente a la validación de un nuevo descuento
				$sql = "select count(*) from calc_desc where trib_id = " . $this->trib_id . " and perdesde = " . $this->perdesde;
				$sql .= " and perhasta = " . $this->perhasta . " and aplicavenc=" . $this->aplicavenc . ($this->pagodesde != 'NULL' ? "and pagodesde='" . $this->pagodesde . "'" : "and pagodesde= " . $this->pagodesde);
				$sql .= " and  montodesde = " . str_replace(",", ".", $this->montodesde) . " and montohasta = " . str_replace(",", ".", $this->montohasta);
				$sql .= " and  verificadeuda=" . $this->verificadeuda . " and existedeuda=" . $this->existedeuda . " and verificadebito=" . $this->verificadebito;
	
				// 	$cantidad almacenará el valor devuelto por la consulta realizada a la BD.
				// 	$cantidad -> Si es 0, indica que el descuento no existe. 
				//	$cantidad -> Si es 1, indica que el descuento ya existe.
	
				$cantidad = Yii :: $app->db->createCommand($sql)->queryScalar();
	
				if ($cantidad > 0) {
	
					$error[$indice++] = "Descuento repetido.";
	
					return false;
					// "Descuento repetido"; 
	
				} else {
	
					$this->desc_id = Yii :: $app->db->createCommand("SELECT nextval('seq_calc_desc')")->queryScalar();

					$sql = "INSERT INTO calc_desc VALUES (" . $this->desc_id . "," . $this->trib_id . "," . $this->item_id . "," . $this->anual . "," . $this->perdesde . "," . $this->perhasta;
					$sql .= "," . $this->aplicavenc . "," . ($this->pagodesde != 'NULL' ? "'" . $this->pagodesde . "'" : $this->pagodesde) . "," . ($this->pagohasta != 'NULL' ? "'" . $this->pagohasta . "'" : $this->pagohasta) . ",";
					$sql .= str_replace(",", ".", $this->montodesde) . "," . str_replace(",", ".", $this->montohasta);
					$sql .= ", " . $this->verificadeuda . "," . $this->existedeuda . "," . $this->verificadebito . "," . $this->verificaexen . ",";
					$sql .= str_replace(",", ".", $this->desc1) . "," . str_replace(",", ".", $this->desc2) . "," . $this->cta_id;
					$sql .= ",  current_timestamp," . Yii :: $app->user->id . ")";

					try
					{
						$cmd = Yii :: $app->db->createCommand($sql);
						$rowCount = $cmd->execute();
					} catch (Exception $e){
			    		$error[$indice++] = DBException::getMensaje($e);
			    		$rowCount = 0;
						$this->addErrors($error);
					}

					return $rowCount > 0;

				} 	
				 
			} else {
							
				//Código que se ejecuta en el caso de actualizar valores a la tabla calc_desc

				$sql = "UPDATE calc_desc SET ";
				$sql .= "trib_id=" . $this->trib_id . ",item_id=" . $this->item_id . ",anual=" . $this->anual . ",perdesde=" .$this->perdesde . ",perhasta=" . $this->perhasta;
				$sql .= ",aplicavenc=" . $this->aplicavenc . "," . ($this->pagodesde != 'NULL' ? "pagodesde='" . $this->pagodesde . "'" : "pagodesde= " . $this->pagodesde) . ",";
				$sql .= ($this->pagohasta != 'NULL' ? "pagohasta='" . $this->pagohasta . "'" : "pagohasta= " . $this->pagohasta) . ",montodesde=" . str_replace(",", ".", $this->montodesde);
				$sql .= ",montohasta=" . str_replace(",", ".", $this->montohasta) . ",verificadeuda=" . $this->verificadeuda;
				$sql .= ",existedeuda=" . $this->existedeuda . ",verificadebito=" . $this->verificadebito . ",verificaexen=" . $this->verificaexen;
				$sql .= ", desc1 = " . str_replace(",", ".", $this->desc1) . ", desc2 = " . str_replace(",", ".", $this->desc2);
				$sql .= " ,cta_id = " . $this->cta_id . ", fchmod=current_timestamp, usrmod=" . Yii :: $app->user->id;
				$sql .= " WHERE desc_id = " . $this->desc_id;

				try
				{
					$cmd = Yii :: $app->db->createCommand($sql);
					$rowCount = $cmd->execute();
				} catch (Exception $e){
		    		$error[$indice++] = DBException::getMensaje($e);
		    		$rowCount = 0;
				}


				return $rowCount > 0;
	
			}
			
		} else return false;
	
	}

	/**
	 * Funcion que realiza la baja de un descuento en la base de datos
	 */
	public function borrar() {
		
		//Índice y error son variables usadas para crear un array de errores
		$indice = 0;
		$error = [];

		$sql = "DELETE FROM calc_desc WHERE Desc_Id = " . $this->desc_id;

		try
		{
			$cmd = Yii :: $app->db->createCommand($sql);
			$rowCount = $cmd->execute();
		} catch(Exception $e){
			$error[$indice++] = DBException::getMensaje($e);
			$rowCount = 0;
		}


		return $rowCount > 0;

	}

	/**
	 * Función que recupera el nombre de los distintos tributos  
	 */	
	public function getTrib() {

		$sql = "SELECT trib_id cod, nombre FROM Trib WHERE est = 'A' ORDER BY Nombre";
		$cmd = Yii :: $app->db->createCommand($sql);
		
		return ArrayHelper :: map($cmd->queryAll(), 'cod', 'nombre');
	}
	
	/**
	 * Función que recupera el nombre de los distintos items
	 */	
	public function getItem($trib) {
		if ($trib == null or $trib == '') $trib = 0;
		
		$sql = "SELECT item_id cod, nombre FROM item WHERE trib_id=$trib ORDER BY Nombre";
		$cmd = Yii :: $app->db->createCommand($sql);
		
		return ArrayHelper :: map($cmd->queryAll(), 'cod', 'nombre');
	}
	
	/**
	 * Función que retorna el nombre de una cuenta
	 * @param integer id Variable que almacenará el valor del id que se buscará en la BD
	 * @return string Value Nombre de la cuenta con identificación = id
	 */
	public function getNombreCuenta($id,$cond="") {
		
		$cmd = "";
		
		if ($id != ""){
		
		$sql = "SELECT nombre FROM Cuenta WHERE cta_id =" . $id;
		if ($cond != "") $sql .= " and " . $cond;
		$cmd = Yii :: $app->db->createCommand($sql)->queryScalar();
		
		}
		
		return $cmd;
		
		
	}
	
	/**
	 * Función que retorna el usuario que realizá la última modificación
	 * @param integer id Variable que almacenará el valor del id del usuario
	 * @return string cmd Nombre del usuario que realizá la última modificación
	 */
	public function getUsuarioModifica ($id){
		
		$cmd = "";
		
		if ($id != ""){
			
			$sql = "SELECT nombre FROM sam.sis_usuario WHERE usr_id = " . $id;
			$cmd = Yii :: $app->db->createCommand($sql)->queryScalar();
			
		}
		
		return $cmd;
			
	}
	
	/**
	 * Función que hace una búsqueda en la BD en base a ciertas condiciones
	 * @param string cond  Condición que filtra la búsqueda en la BD 
	 * @param string orden Condición según la cual se ordenarán los datos 
	 */
	 public function buscarDescuento ( $cond, $orden )
	{
				
		$sql = "SELECT d.desc_id, d.trib_id, d.anual,d.aplicavenc, d.pagodesde, d.pagohasta,";
		$sql .= "(substr(cast(perhasta as varchar), 1, 4) || '-') || substr(cast(perhasta as varchar),5, 3) as perhasta,";
		$sql .= "(substr(cast(perdesde as varchar), 1, 4) || '-') ||substr(cast(perdesde as varchar), 5, 3) as perdesde,";
        $sql .= "substr(cast(perdesde as varchar), 1, 4) as aniodesde,";
		$sql .= "substr(cast(perdesde as varchar), 5, 3) as cuotadesde, ";
        $sql .= "substr(cast(perhasta as varchar), 1, 4) as aniohasta,";
		$sql .= "substr(cast(perhasta as varchar), 5, 3) as cuotahasta, ";
        $sql .= "montodesde, montohasta, verificadeuda, existedeuda, verificadebito, verificaexen,";
        $sql .= "desc1, desc2, d.cta_id, c.nombre as cta_nom,";
        $sql .= "case when verificadeuda=0 then '' when existedeuda=0 then 'buen pagador' when existedeuda=1 then 'todo pago' when existedeuda=2 then 'con deuda' else '' end as existedeudadescr,";
        $sql .= "u.nombre || ' - ' || to_char(d.fchmod,'dd/mm/yyyy') as modif";
        
        $sql2 = " FROM calc_desc d left join trib t on d.trib_id = t.trib_id";
        $sql2 .= " left join sam.sis_usuario u on d.usrmod = u.usr_id ";
        $sql2 .= " left join cuenta c on c.cta_id = d.cta_id ";
        
 		/*		Seleccionar la cantidad de elementos de la tabla calc_desc cuando:
		 * 		-> $cond == ""
		 * 		-> $cond == Alg�n elemento de la tabla trib_id	
		 */
		 
 		$count = Yii::$app->db->createCommand('SELECT COUNT(*) ' . $sql2 . ($cond!=="" ? 'WHERE ' . $cond : ""))->queryScalar();
				
		if ($cond != "") $sql2 .= 'WHERE ' . $cond;
		$sql2 .= ' ORDER BY ' . $orden;
		
		$dataProvider = new SqlDataProvider([
		 	'sql' => $sql . $sql2,
            //'key'=>'trib_id',
            'key' => 'desc_id',
			'totalCount' => (int)$count,
			'pagination'=> [
				'pageSize'=>20,
			],
        ]); 

        return $dataProvider;
        
    }
    
    public function calcularDescuento( $trib_id, $obj_id, $periodo, $monto, $fecha2 )
    {
    	$this->resetError();
    	
    	$descuento = 0;
    	
    	try {
    				
	    	$sql = "SELECT sam.uf_calc_desc(" . $trib_id . ",'" . $obj_id . "'," . $periodo . "," . str_replace(",",".",$monto) . ",'" . $fecha2 . "')";
	
	    	$descuento = Yii::$app->db->createCommand( $sql )->queryScalar();
	    	
    	} catch ( Exception $e )
    	{
			$this->setError( DBException::getMensaje( $e ) );
			$descuento = 0;
		}
    
    	return $descuento;
    	
    }
}