<?php

namespace app\models\caja;
use yii\data\SqlDataProvider;

use Yii;

/**
 * This is the model class for table "caja_opera".
 *
 * @property integer $opera
 * @property integer $caja_id
 * @property string $fecha
 * @property string $lote
 * @property integer $cant
 * @property string $monto
 * @property string $cobrado
 * @property string $comision
 * @property string $deposito
 * @property string $fchrecep
 * @property string $fchproc
 * @property integer $ctacte_id
 * @property string $est
 * @property string $fchmod
 * @property integer $usrmod
 * @property integer $cant_lotes
 * @property string $caja_nom
 * @property string $teso_nom
 * @property string $entregado
 * @property string $vuelto
 */
class CajaOpera extends \yii\db\ActiveRecord
{
	
	public $caja_nom;
	public $teso_nom;
	public $entregado;
	public $vuelto;
	
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'caja_opera';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['opera', 'caja_id', 'fecha', 'cant', 'monto'], 'required'],
            [['opera', 'caja_id', 'cant', 'ctacte_id', 'cant_lotes'], 'integer'],
            [['fecha', 'fchrecep', 'fchproc'], 'safe'],
            [['monto', 'cobrado', 'comision', 'deposito'], 'number'],
            [['lote'], 'string', 'max' => 10],
            [['est'], 'string', 'max' => 1],
            [['caja_nom'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'opera' => 'Codigo de operacion',
            'caja_id' => 'Codigo de caja',
            'fecha' => 'Fecha de la caja',
            'lote' => 'Número de lote externo',
            'cant' => 'Cantidad de comprobantes',
            'monto' => 'Monto total de la operacion',
            'cobrado' => 'Monto ingresado (para cajas externas)',
            'comision' => 'Comisión liquidada al agente externo',
            'deposito' => 'Importe depositado por agente, restando las comisiones',
            'fchrecep' => 'Fecha de recepci�n del lote',
            'fchproc' => 'Fecha de procesamiento de pagos',
            'ctacte_id' => 'Comprobante caja externa vinculado',
            'est' => 'Estado (a/b)',
            'fchmod' => 'Fecha de modificacion',
            'usrmod' => 'Usuario de modificacion',
            'cant_lotes' => 'Cantidad de lotes generados por el agente externo',
        ];
    }
    
    /**
     * Función que obtiene los datos de las operaciones de la BD
     */
    public function obtenerDatosOpera()
    {
    	$arreglo = Yii::$app->db->createCommand("SELECT * FROM v_caja_opera WHERE opera = " . $this->opera)->queryAll();
    	
    	$this->caja_nom = $arreglo[0]['caja_nom'];
    	$this->teso_nom = $arreglo[0]['teso_nom'];
    	$this->entregado = $arreglo[0]['entregado'];
    	$this->vuelto = $arreglo[0]['vuelto'];

    }
    
    public function consultaPermitida($opera, $comprob)
    {
    	if ($comprob != '')
    	{
			$sql = "SELECT count(*) FROM caja_ticket t ";
			$sql .= "LEFT JOIN caja c ON t.caja_id = c.caja_id ";
			
			if ($opera == "T")
			    $sql .= "WHERE ticket = '" . $comprob . "'";
			else if ($opera == 'O')
			    $sql .= "WHERE opera = " . $comprob;
			
			$sql .= " AND c.teso_id IN (SELECT teso_id FROM sam.sis_usuario_tesoreria WHERE usr_id= " . Yii::$app->user->id . ")";
			
			return Yii::$app->db->createCommand($sql)->queryScalar() > 0;	
    	}
    }
    
    /**
     * Función que carga el detalle de un ticket
     */
    public function CargarDetalle()
    {
    	$id = ($this->opera != '' ? $this->opera : 0);

        $sql = "SELECT * FROM v_caja_ticket WHERE opera = " . $id . "ORDER BY ticket";
        
        $count = Yii::$app->db->createCommand("SELECT count(*) FROM v_caja_ticket WHERE opera = " . $id)->queryScalar(); 
    	
    	$dataProvider = new SqlDataProvider([
			 	'sql' => $sql,
	            'key' => 'ticket',
				'totalCount' => (int)$count,
				'pagination' =>
					['pageSize' => 10, 
					],
	        ]); 
	        
	    return $dataProvider;
    
    }
    
    /**
     * Función que carga el MDP
     */
    public function CargarMDP()
    {
    	$id = ($this->opera != '' ? $this->opera : 0);

        $sql = "SELECT * FROM v_caja_opera_mdp WHERE opera = " . $id . " ORDER BY orden";
        
        $count = Yii::$app->db->createCommand("SELECT count(*) FROM v_caja_opera_mdp WHERE opera = " . $id)->queryScalar(); 
    	
    	$dataProvider = new SqlDataProvider([
			 	'sql' => $sql,
	            'key' => 'opera',
				'totalCount' => (int)$count,
				'pagination' =>
					['pageSize' => 10, 
					],
	        ]); 
	        
	    return $dataProvider;
    
    }
}
