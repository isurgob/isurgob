<?php

namespace app\models\ctacte;


use Yii;
use Yii\db\Exception;
use app\utils\helpers\DBException;
use app\utils\db\Fecha;



/**
 * This is the model class for table "calc_interes".
 *
 * @property string $fchdesde
 * @property string $fchhasta
 * @property string $indice
 * @property string $fchmod
 * @property integer $usrmod
 * 
 * @property number $nominal
 */
class CalcInteres extends \yii\db\ActiveRecord
{
	private $error= null;
	
	public $nominal= 0;
	public $monto;
	
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'calc_interes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fchdesde', 'fchhasta', 'indice'], 'required'],
            [['fchdesde', 'fchhasta'], 'safe'],
            [['indice', 'monto', 'nominal'], 'number', 'min' => 0, 'max' => 15],
			[['fchdesde', 'fchhasta'], 'date', 'format' => 'php:Y/m/d'],
			[['fchdesde', 'fchhasta'], 'default', 'value' => null]
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
            'indice' => 'Índice mensual',
            'fchmod' => 'Fecha de modificación',
            'usrmod' => 'Código de usuario que modifica',
        ];
    }
	
	public function beforeValidate(){
		
		if($this->fchdesde != null && $this->fchdesde != '')
			$this->fchdesde= Fecha::usuarioToBD($this->fchdesde);
		
		if($this->fchhasta != null && $this->fchhasta != '')
			$this->fchhasta= Fecha::usuarioToBD($this->fchhasta);
		
		return true;
	}
	
	public function afterFind(){
		
		$this->fchdesde= Fecha::bdToUsuario($this->fchdesde);
		$this->fchhasta= Fecha::bdToUsuario($this->fchhasta);
	}

	
	/**
	 * Resetea el error.
	 */
	private function resetearError()
	{
		$this->error = null;	
	}
	
	/**
	 * Establece un mensaje de error en $error.
	 * Si ya existía un mensaje de error, el nuevo mensaje se concatena luego de un '.'.
	 * 
	 * @param String $err Mensaje de error a guardar.
	 */
	private function setError($err)
	{		
		$this->error = $this->error == null ? $err : $this->error . '. ' . $err;
	}
	
	/**
	 * Valida el periodo dado desde $fchdesde hasta $fchhasta.
	 * 
	 * @param string $fchdesde Fecha incial del periodo
	 * @param String $fchhasta Fecha final del periodo
	 * 
	 * @return boolean true si el periodo es valido, false de lo contrario.
	 */
	private function validarPeriodo($fchdesde, $fchhasta)
	{
		$fchMaximo = Yii::$app->db->createCommand("Select To_Char(coalesce(Max(FchHasta),'1900/01/01') , 'YYYY/MM/DD') From Calc_Interes ")->queryScalar();
		$fchMinimo = Yii::$app->db->createCommand("Select To_Char(coalesce(Min(FchDesde),'1900/01/01') , 'YYYY/MM/DD') From Calc_Interes ")->queryScalar();		
		
		$res = Fecha::validarPeriodo($fchdesde, $fchhasta, $fchMinimo, $fchMaximo);
		$ret = $res == '';
		
		if( !$ret )
			$this->addError( 'fchdesde', $res );
			
		return $ret;
	}
	
	/**
	 * Valida que el indice sea correcto.
	 * En caso de que se produzca un error, el mensaje del mismo se guardará en $error.
	 * 
	 * @param number $indice El valor de indice a validar.
	 * 
	 * @return boolean true si el indice es valido, false si el indice es invalido.
	 */
	private function validarIndice($indice)
	{
		if(!is_numeric($indice))
		{
			$this->addError('indice', "El índice debe ser un número.");
			return false;
		}
			
		
		if($indice > 0 && $indice < 15)
			return true;
			
		$this->addError('indice', "El índice no puede ser menor a 0 ni mayor a 15. Tiene un valor de $indice");
		return false;
	}
	
	/**
	 * Función para el cálculo de interés.
	 * 
	 * Si se retorna -1, ejecutar el método getError para obtener el mensaje del error.
	 * 
	 * @param String $fchvencimiento Fecha de vencimiento.
	 * @param String $fchpago Fecha de pago.
	 * @param number $nominal Nominal sobre el cual calcular el interés.
	 * 
	 * @return number	-1 en caso de que se haya producido un error que se puede obtener con getError(), de lo contrario el interés cálculado.
	 */    
    public function calcularIntereses($fchvencimiento, $fchpago, $nominal)
    {
    	$this->resetearError();
    	$ejecutar = true;
    	
    	$venc = Fecha::usuarioToBd($fchvencimiento);
    	$pago = Fecha::usuarioToBd($fchpago);
    	
    	$periodoValido = Fecha::validarPeriodo($venc, $pago, $venc, $pago, true); 
    	
    	if($periodoValido != '')
    	{
    		$this->setError($periodoValido);
    		$ejecutar = false;
    	}
    	
    	if(!is_numeric($nominal))
    	{
    		$this->setError("El nominal ingresado debe ser un número.");
    		$ejecutar = false;
    	}
    	else $nominal = (float) $nominal;
    	    	
    	if($nominal <= 0){
    		$this->setError("El nominal ingresado debe ser mayor a cero.");
    		$ejecutar = false;
    	}
    		
    	if(!$ejecutar)
    		return -1;
    	
    	
    	$sql = "Select sam.uf_calc_interes (" . $nominal . ",'" . $fchvencimiento . "','" . $fchpago . "')";
    	
 		try{
 			$cmd = Yii::$app->db->createCommand($sql);
 			$monto = $cmd->queryScalar();
 		}
 		catch(Exception $e){
 			$this->setError(DBException::getMensaje($e));
 			$monto = -1;
 		}
 		
 		return $monto;
    }
    
    /**
     * Si el interés no existe, el procedimiento grabará el nuevo interés.
     * Si el interés ya existe, el procidimiento modificará los datos del interés.
     * 
     * Si se retorna false, se podrá obtener el mensaje de error a través del método getError().
     * 
     * @return boolean true si se ha logrado grabar el interés, false de lo contrario.
     */
    public function grabar()
    {
    	$sql = null;
    	$ejecutar = true;
    	$this->resetearError();
    	
    	$indiceValido = $this->validarIndice($this->indice);
    	
    		
    	if(!$indiceValido)
    		$ejecutar = false;
    	else $this->indice = (float) $this->indice;
    	 
    	    	  	
    	if($this->isNewRecord)
    	{
    		if(!$this->validarPeriodo($this->fchdesde, $this->fchhasta))
    			$ejecutar = false;
    		
    		$sql = "Insert Into Calc_Interes Values ('" . $this->fchdesde . "',";
        	$sql .=  "'" . $this->fchhasta . "'," . $this->indice . ", current_timestamp," . Yii::$app->user->id . ")";	
    	}
    	else
    	{    		
    		$sql = "Update Calc_Interes Set Indice = " . $this->indice . ", FchMod=current_timestamp, UsrMod=" . Yii::$app->user->id;
      		$sql .= " Where FchDesde='" . $this->fchdesde . "' and FchHasta = '" . $this->fchhasta . "' ";
    	}	
    	
    	
    	if(!$ejecutar)
    		return false;
    	
    	$res = 0;
    	
    	try{
    		$cmd= Yii::$app->db->createCommand($sql);
	    	$res= $cmd->execute();	
    	} catch(Exception $e){
    		$this->setError(DBException::getMensaje($e));
    		$res = 0;
    	}
    	
    	return $res > 0;
    }
    
    /**
     * Procedimiento para eliminar el interés.
     * 
     * @return boolean true si el interés fue eliminado, false de lo contrario.
     */
    public function borrar()
    {
    	$this->resetearError();
    	$sql = "Delete From Calc_Interes Where FchDesde='" . $this->fchdesde . "' and FchHasta = '" . $this->fchhasta . "'";
    	
    	$res = false;
    	
    	try
    	{
    		$cmd = Yii::$app->db->createCommand($sql);
    		$res = $cmd->execute() > 0;	
    	}
    	catch(Exception $e)
    	{
    		$this->setError(DBException::getMensaje($e));
    		$res = false;
    	}
    	
    	return $res;
    }
    
    /**
     * Función que modifica el mínimo de interes.
     * 
     * @param number $monto Monto de interés.
     * 
     * @return boolean true si se modificó el mínimo de interés, false si no se realizó.
     */
    public function grabarMinimoInteres($monto)
    {
    	$this->resetearError();
    	$sql = "Update SAM.Config Set Interes_Min = " . $monto;
    	
    	if(!is_numeric($monto))
    	{
    		$this->setError("El monto ingresado debe ser un número.");
    		return false;
    	}
    	else $monto = (float) $monto;
    		
    	
    	if($monto < 0)
    	{
    		$this->setError("El monto ingresado no puede ser menor a 0.");
    		return false;
    	}
    	
    	$res= false;
    	
    	try
    	{
    		$cmd = Yii::$app->db->createCommand($sql);
    		$res = $cmd->execute() >= 0;	
    	}
    	catch(Exception $e)
    	{
    		$this->setError(DBException::getMensaje($e));
    		$res = false;
    	}
    	
    	return $res;
    	
    }
    
    /**
     * Función que obtiene el mínimo de interes.
     * 
     * @return number Interés mínimo, 0 si no existe registro ó -1 si ha ocurrido un error que se puede obtener con getError().
     */
    public function obtenerMinimoInteres()
    {
    	$this->resetearError();
    	$sql = "Select Interes_Min From SAM.Config ";
    	
    	$res = 0;
    	
    	try
    	{
    	$cmd = Yii::$app->db->createCommand($sql);
    	$res = $cmd->queryScalar();	
    	}
    	catch(Exception $e)
    	{
    		$this->setError(DBException::getMensaje($e));
    		$res = -1;
    	}
    	
    	return $res; 
    }
    
    /**
     * Devuelve el mensaje del último error que se haya producido
     * 
     * @return String|null Mensaje del último error que se haya producido. null en caso de que no se haya producido un error.
     */
    public function getError()
    {
    	return $this->error;
    }
    
}
