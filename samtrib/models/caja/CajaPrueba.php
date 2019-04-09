<?php

namespace app\models\caja;
use yii\data\SqlDataProvider;
use yii\data\ArrayDataProvider;
use app\utils\db\Fecha;
use app\utils\db\utb;
use yii\helpers\ArrayHelper;

use Yii;

/**
 * Estas son las propiedades que obtenemos de llamar a
 * sam.Uf_Caja('Cod de Barra',0)
 *
 * @property integer $ctacte_id;
 * @property integer $trib_id;
 * @property string $trib_nom;
 * @property integer $trib_tipo;
 * @property string $obj_id;
 * @property integer $subcta;
 * @property integer $anio;
 * @property integer $cuota;
 * @property integer $faci_id;
 * @property string $num;
 * @property string $fchvenc;
 * @property integer $monto;
 * @property integer $anual;
 */
class CajaPrueba extends \yii\db\ActiveRecord
{
	public $codBarra;
	public $ctacte_id;
	public $trib_id;
	public $trib_nom;
	public $trib_tipo;
	public $obj_id = '';
	public $subcta;
	public $anio;
	public $cuota;
	public $faci_id;
	public $num;
	public $fchvenc;
	public $monto;
	public $anual;
	public $validaDebito = false;
	public $fechaPago; //Variable que almacenará la fecha que ingresa el usuario
	public $arreglo;

    /**
     * Función que obtiene los datos de la BD
     */
    public function obtenerDatos( $codBarra )
    {
		//Si el codBarra existe, asigno los valores al modelo, de lo contrario, muestro el error
		if ($codBarra == '')
			return 'Ingrese un valor.';
    	try{

	    	$arreglo = Yii::$app->db->createCommand("SELECT * FROM sam.uf_caja('" . $codBarra . "',0)")->queryAll();

			$this->ctacte_id 	= $arreglo[0]['ctacte_id'];
			$this->trib_id 		= $arreglo[0]['trib_id'];
			$this->trib_nom 	= $arreglo[0]['trib_nom'];
			$this->trib_tipo 	= $arreglo[0]['trib_tipo'];
			$this->obj_id 		= $arreglo[0]['obj_id'];
			$this->subcta 		= $arreglo[0]['subcta'];
			$this->anio 		= $arreglo[0]['anio'];
			$this->cuota 		= $arreglo[0]['cuota'];
			$this->faci_id 		= $arreglo[0]['faci_id'];
			$this->num 			= $arreglo[0]['num'];
			$this->fchvenc 		= $arreglo[0]['fchvenc'];
			$this->monto 		= $arreglo[0]['monto'];
			$this->anual 		= $arreglo[0]['anual'];

			//Formateo la fecha. Primero obtengo la fecha y descarto la hora, luego la transformo al formato utilizado
			$this->fchvenc = substr($this->fchvenc,0,10);
			//$this->fchvenc = Fecha::bdToUsuario($this->fchvenc);

			//Verificar la condición de Débito
			$this->varificarValidaDebito();

			$this->codBarra = $codBarra;

			return '';

    	} catch(\Exception $e) {

    		$error = strstr($e->getMessage(), 'The SQL being', true);
    		$error = substr($error, strlen('SQLSTATE[P0001]: Raise exception: 7'));
    		return $error;
    	}


    }

    /**
     * Función que verifica si se valida o no el débito
     */
    public function varificarValidaDebito()
    {
    	$arraySamConfig = utb::samConfig(); //Arreglo con valores de configuración

    	$debito = $arraySamConfig['cajaverifdebito'];
    	/*
    	 * $debito 	= 0	=> No controlar.
    	 * 			= 1	=> Controlar e Informar.
    	 * 			= 2 => Controlar y Bloquear Cobro
    	 */

    	if ($debito > 0)
    	{	//Se controla, verifico si exise adhesión para el período

    		if ($this->trib_id != 1)	//Si el tributo no es convenio
    		{
    			$sql = "SELECT count(*) FROM debito_adhe WHERE trib_id = " . $this->trib_id . " AND obj_id = '" . $this->obj_id . "' " .
    					"AND subcta = " . $this->subcta . " AND " . ($this->anio * 1000 +$this->cuota) . " BETWEEN perdesde AND perhasta AND est = 'A'";
    		} else {
    			$sql = "SELECT count(*) FROM plan p WHERE p.plan_id = " . $this->anio . " AND p.tpago = 3";
    		}

    		$this->validaDebito = Yii::$app->db->createCommand($sql)->queryScalar() > 0;

    	}
    }

    public function cargarDetalle()
    {
    	$arraySamConfig = utb::samConfig(); //Arreglo con valores de configuración

		$pagocta = ( $this->trib_id == 10 ? 1 : 0 );

		if ($pagocta == 1){
			$ctacte = $this->anio;
		}elseif ($this->faci_id > 0){
			$ctacte = $this->faci_id;
		}else {
			$ctacte = $this->ctacte_id;
		}

    	$sql = "SELECT cta_id, cta_nom, tcta, monto ";
    	$sql .= "FROM sam.uf_caja_det(" . $ctacte . ",'".Fecha::usuarioToBD($this->fchvenc) . "','" . Fecha::usuarioToBD($this->fechaPago);
    	$sql .= "'," . $this->anual . ",0," . $pagocta . "," . ($this->faci_id > 0 ? 1 : 0) . "," . ($arraySamConfig['ctaredondeo'] > 0 ? 1 : 0) .") c ";
    	$sql .=	"ORDER BY cta_id ";

    	$cmd = Yii :: $app->db->createCommand( $sql );

    	$this->arreglo = $cmd->queryAll();

		$dataProvider = new ArrayDataProvider([
			 	'models' => $this->arreglo,
	            'key' => 'cta_id',
	        ]);

	    return $dataProvider;
    }

    public function sumarMonto()
    {
    	$sum = 0;
    	foreach($this->arreglo as $array)
    	{
    		$sum += (float)$array['monto'];
    	}

    	return $sum;
    }
}
