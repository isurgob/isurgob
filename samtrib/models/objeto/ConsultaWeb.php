<?php

namespace app\models\objeto;

use Yii;
use yii\validators\EmailValidator;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use app\utils\db\utb;
use app\utils\helpers\DBException;

use app\utils\db\Fecha;

/**
 * This is the model class for table "sam.cons".
 *
 * @property integer $usr_id
 * @property string $fecha
 * @property string $nombre
 * @property integer $tdoc
 * @property bigint $ndoc
 * @property string $domi
 * @property string $loc
 * @property string $mail
 * @property string $tel
 * @property string $cel
 * @property integer $tema
 * @property string $detalle
 * @property string $respuesta
 * @property string $est
 * @property string $fchmod
 * @property string $usrmod
 */
class ConsultaWeb extends \yii\db\ActiveRecord
{
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sam.cons';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre', 'tdoc', 'doc'], 'required'],
            [['cons_id', 'tdoc', 'ndoc', 'tema'], 'integer'],
            [['fecha','fchmod'], 'safe'],
			[['nombre','domi','loc'], 'string'],
            [['obj_id'], 'string', 'max' => 8],
            [['est','tel'], 'string', 'max' => 1],
            [['tel', 'cel'], 'string', 'max' => 15],
            [['mail'], 'string', 'max' => 40],
            [['detalle','respuesta'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cons_id' => 'Consulta',
            'fecha' => 'Fecha',
            'nombre' => 'Nombre',
            'tdoc' => 'Tipo Doc',
            'ndoc' => 'Nº Doc',
            'domi' => 'Domicilio',
            'loc' => 'Localidad',
            'mail' => 'Mail',
            'tel' => 'Telefono',
            'cel' => 'Celular',
            'tema' => 'Tema',
            'detalle' => 'Detalle',
            'respuesta' => 'Respuesta',
            'est' => 'Estado',
            'fchmod' => 'Fecha Modificación',
            'usrmod' => 'Usuario Modificación',
        ];
    }
    
     /**
	 * Funcion que graba la consulta en la base de datos.
	 * @return string Valor con los errores ocurridos. "" en caso de no haber ocurrido ning�n error. 
	 */
	public function Grabar($consulta) 
	{
		$sql = "update sam.cons set est='" . ($consulta == 3 ? 'R' : 'B') . "',fchmod=current_timestamp,usrmod=" . Yii::$app->user->id . " where cons_id='" . $this->cons_id ."'";
		
		$cmd = Yii :: $app->db->createCommand($sql);
		$rowCount = $cmd->execute();

		if ($rowCount > 0) 
		{
			return "";
		} else {
				return "Ocurrio un error al intentar grabar en la BD.";
		}
	}
	
	public function ConsultasWeb($cond)
   {
		$sql = "Select *,to_char(fecha,'dd/mm/yyyy') fecha From sam.v_cons ";
		if ($cond != '') $sql .= " where " . $cond;
		$sql .= " order by nombre";
		
		$data = Yii::$app->db->createCommand($sql)->queryAll();

		return $data;
   }
   
   public function AjustesWeb($cond)
   {
		$sql = "Select *,to_char(fecha,'dd/mm/yyyy') fecha,case when est='P' then 'Pendiente' else 'Aprobado' end est_nom From persona_ajuste ";
		if ($cond != '') $sql .= " where " . $cond;
		$sql .= " order by nombre";
		
		$data = Yii::$app->db->createCommand($sql)->queryAll();

		return $data;
   }
}
