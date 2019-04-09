<?php

namespace app\models\ctacte;

use Yii;
use yii\data\SqlDataProvider;
use app\utils\db\utb;
use app\utils\db\Fecha;

/**
 * This is the model class for table "rete".
 *
 * @property integer $rete_id
 * @property string $expe
 * @property integer $tipo
 * @property integer $aplic_num
 * @property string $fchalta
 * @property string $fchaplic
 * @property string $fchconsolida
 * @property string $fchbaja
 * @property integer $trib_ori
 * @property string $obj_ori
 * @property integer $trib_dest
 * @property string $obj_dest
 * @property string $monto
 * @property string $monto_aplic
 * @property string $est
 * @property string $obs
 * @property string $fchmod
 * @property integer $usrmod
 */

class Saldoneg extends \yii\db\ActiveRecord
{
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rete';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tipo', 'aplic_num', 'fchalta', 'monto', 'monto_aplic', 'est', 'obs'], 'required'],
            [['tipo', 'aplic_num', 'trib_ori', 'trib_dest'], 'integer'],
            [['fchalta', 'fchaplic', 'fchconsolida', 'fchbaja'], 'safe'],
            [['monto', 'monto_aplic'], 'number'],
            [['expe'], 'string', 'max' => 12],
            [['obj_ori', 'obj_dest'], 'string', 'max' => 8],
            [['est'], 'string', 'max' => 1],
            [['obs'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rete_id' => 'Identificador de retencion',
            'expe' => 'Expediente',
            'tipo' => 'Codigo de tipo de retencion',
            'aplic_num' => 'Si se aplica al num - responsable ',
            'fchalta' => 'Fecha de alta',
            'fchaplic' => 'Fecha de aplicacion',
            'fchconsolida' => 'Fecha de consolidacion',
            'fchbaja' => 'Fecha de baja',
            'trib_ori' => 'Codigo de tributo origen',
            'obj_ori' => 'Codigo de objeto origen',
            'trib_dest' => 'Codigo de tributo destino',
            'obj_dest' => 'Codigo de objeto destino',
            'monto' => 'Monto',
            'monto_aplic' => 'Monto aplicado',
            'est' => 'Estado',
            'obs' => 'Observaciones',
            'fchmod' => 'Fecha de modificacion',
            'usrmod' => 'Codigo de usuario que modifico',
        ];
    }
    
  	/**
  	 * Función que se encarga de obtener el listado de cuentas corrientes con saldo negativo
  	 * @param integer $trib_id Identificador del tributo
  	 * @param integer $objdesde Objeto desde
  	 * @param integer $objhasta Objeto hasta
  	 * @param integer $perdesde_anio Año desde
  	 * @param integer $perdesde_cuota Cuota desde
  	 * @param integer $perhasta_anio Año hasta
  	 * @param integer $perhasta_cuota Cuota hasta
  	 */
    public function cargarDatos($trib_id, $objdesde, $objhasta, $perdesde_anio, $perdesde_cuota, $perhasta_anio, $perhasta_cuota)
    {
		
		$perdesde = ( $perdesde_anio * 1000 ) + $perdesde_cuota;
		$perhasta = ( $perhasta_anio * 1000 ) + $perhasta_cuota;

        $sql = "Select c.Ctacte_id, c.Obj_Id, o.nombre,  Anio, Cuota, (Nominal-NominalCub+Multa) as Saldo " .
        		" From CtaCte c " .
        		" left join objeto o on c.obj_Id = o.obj_Id " .
        		" Where trib_Id =" . $trib_id . " and c.obj_Id between '" . $objdesde . "' and '" . $objhasta . "'" .
        		" and anio*1000+cuota between " . $perdesde . " and " . $perhasta . " and (Nominal-NominalCub+Multa) < 0 and c.Est<>'B'" .
        		" Order By Saldo Asc";
    	
    	//return $sql;
    	return Yii::$app->db->createCommand( $sql )->queryAll();
    	
    }
    
}