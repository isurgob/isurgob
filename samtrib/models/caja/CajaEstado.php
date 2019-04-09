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
 * v_caja_estado
 *
 * @property integer $teso_id;
 * @property integer $caja_id;
 * @property string $caja_nom;
 * @property string $cajero;
 * @property integer $est;
 * @property string $est_nom;
 * @property string $fecha;
 * @property string $apesup;
 * @property string $fchapesup;
 * @property string $apecaj;
 * @property string $fchapecaj;
 * @property string $ciecaj;
 * @property string $fchciecaj;
 * @property string $ciesup;
 * @property string $fchsiesup;
 */
class CajaEstado extends \yii\db\ActiveRecord
{
	 public $teso_id = '';
	 public $caja_id = '';
	 public $caja_nom = '';
	 public $cajero = '';
	 public $est = -1;
	 public $est_nom = '';
	 public $fecha = '';
	 public $apesup = '';
	 public $fchapesup = '';
	 public $apecaj = '';
	 public $fchapecaj = '';
	 public $ciecaj = '';
	 public $fchciecaj = '';
	 public $ciesup = '';
	 public $fchciesup = '';


    /**
     *
     */
    public function buscarEstadoCaja($teso_id)
    {
    	$sql = "SELECT * FROM v_caja_estado WHERE teso_id = " . $teso_id;

    	$dataProvider = new SqlDataProvider([
		 	'sql' => $sql,
            'key' => 'teso_id',

        ]);

        return $dataProvider;
    }


    /**
     * Función que obtiene los datos de la BD
     */
    public function obtenerDatos($caja_id)
    {
		$sql = "SELECT teso_id,caja_id,caja_nom,cajero,est,est_nom,to_char(fecha, 'dd/MM/yyyy') as fecha, apesup, " .
		"to_char(fchapesup, 'dd/MM/yyyy HH:MI') as fchapesup, apecaj, to_char(fchapecaj, 'dd/MM/yyyy HH:MI') as fchapecaj," .
		"ciecaj,to_char(fchciecaj, 'dd/MM/yyyy HH:MI') as fchciecaj,ciesup,to_char(fchciesup, 'dd/MM/yyyy HH:MI') as fchciesup" .
		" FROM v_caja_estado " .
		" WHERE caja_id = " . $caja_id;

		 $arreglo = Yii::$app->db->createCommand( $sql )->queryAll();

		 if (count($arreglo) > 0)
		 {
			 $this->teso_id = $arreglo[0]['teso_id'];
			 $this->caja_id = $arreglo[0]['caja_id'];
			 $this->caja_nom = $arreglo[0]['caja_nom'];
			 $this->cajero = $arreglo[0]['cajero'];
			 $this->est = $arreglo[0]['est'];
			 $this->est_nom = $arreglo[0]['est_nom'];
			 $this->fecha = $arreglo[0]['fecha'];
			 $this->apesup = $arreglo[0]['apesup'];
			 $this->fchapesup = $arreglo[0]['fchapesup'];
			 $this->apecaj = $arreglo[0]['apecaj'];
			 $this->fchapecaj = $arreglo[0]['fchapecaj'];
			 $this->ciecaj = $arreglo[0]['ciecaj'];
			 $this->fchciecaj = $arreglo[0]['fchciecaj'];
			 $this->ciesup = $arreglo[0]['ciesup'];
			 $this->fchciesup = $arreglo[0]['fchciesup'];

		 }

    }

    /**
     *  Función que anula la apertura de una caja.
     * Antes de anularla verifica que no halla sido abierta un cajero
     * @param integer $caja_id Código de la caja
     * @param string $fecha Fecha de caja
     */
    public function anularApertura($caja_id,$fecha)
    {
    	//Valido q no haya sido abierta por un cajero
        $sql = "SELECT COUNT(*) FROM Caja_Estado ";
        $sql .= "Where Caja_id =" . $caja_id . " and Fecha = '" . Fecha::usuarioToBD($fecha) . "' and not apecaj is null";

    	try{
    		$cant = Yii::$app->db->createCommand($sql)->queryScalar();
    	} catch (\Exception $e)
    	{
    		return $e;
    	}

        if ($cant > 0)
            return "La Caja ya fue abierta por un Cajero.";
       else
       {
       		$sql = "Delete From Caja_Estado ";
            $sql .= " Where Caja_id = " . $caja_id . " and Fecha = '" . Fecha::usuarioToBD($fecha) . "'";

       		$cant = Yii::$app->db->createCommand($sql)->execute();

        	if ($cant > 0)
        		return "";
        	else
            	return "No se pudo anular la Apertura.";
       }

    }

    /**
     * Función que anula el cierre de una Caja.
     * Antes de anular el cierre, verifica que no halla sido cerrada por el supervisor
     * @param integer $caja_id Código de la Caja
     * @param string $fecha Fecha de Caja
     * @return string Resultado de la anulación. "" en caso afirmativo.
     *
     */
    public function anularCierre($caja_id,$fecha)
    {
    	$sql = "Select count(*) From Caja_Estado ";
        $sql .= "Where Caja_id =" . $caja_id . " and Fecha = '" . Fecha::usuarioToBD($fecha) . "' and not ciesup is null";

        $cant = Yii::$app->db->createCommand($sql)->queryScalar();

        	if ($cant > 0)
        		return "La Caja ya fue cerrada por un Supervisor.";

        		//Se debe agregar la validación sobre los permisos del usuario
        	else
        	{
        		$sql = "UPDATE caja_Estado SET CieCaj = null, fchciecaj = null, est = 2 ";
            	$sql .= " Where Caja_id = " . $caja_id . " and Fecha = '" . Fecha::usuarioToBD($fecha) . "'";

    	        $cant = Yii::$app->db->createCommand($sql)->execute();

        		if ($cant > 0)
        			return "";
    			else
        			return "No se pudo anular el cierre.";

        	}
    }

    /**
     * Función que anula el cierre de una Caja.
     * Antes de anular el cierre, verifica que no haya cobros posteriores
     * @param integer $caja_id Código de la Caja
     * @param string $fecha Fecha de Caja
     * @return string Resultado de la anulación. "" en caso afirmativo.
     *
     */
    public function anularCierreSup($caja_id,$fecha)
    {
    	$sql = "SELECT COUNT(*) FROM caja_ticket ";
        $sql .= "WHERE caja_id =" . $caja_id . " AND fecha > '" . Fecha::usuarioToBD($fecha) . "'";

        $cant = Yii::$app->db->createCommand($sql)->queryScalar();

        $sql2 = "SELECT COUNT(*) FROM caja WHERE caja_id=" . $caja_id . " and (sup1=" . Yii::$app->user->id . " or sup2=" . Yii::$app->user->id . " or sup3=" . Yii::$app->user->id . " or sup4=" . Yii::$app->user->id . ")";

    	if ($cant > 0)
    		return "Existen cobros posteriores. No puede eliminar el cierre.";
//    	else if ()
//    		Se debe agregar la validación sobre los permisos del usuario

    	else if (Yii::$app->db->createCommand($sql2)->queryScalar() == 0)
    		return "No es Supervisor de la Caja " . $caja_id . ". No puede eliminar el cierre.";
    	else
    	{
     		$sql = "UPDATE caja_Estado SET ciesup = null, fchciesup = null, est = 3 ";
        	$sql .= " WHERE Caja_id = " . $caja_id . " and fecha = '" . Fecha::usuarioToBD($fecha) . "'";

	        $cant = Yii::$app->db->createCommand($sql)->execute();

    		if ($cant > 0)
    			return "";
			else
    			return "No se pudo anular el cierre.";
        }
	}

}
