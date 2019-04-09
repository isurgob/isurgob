<?php

namespace app\models\config;

use Yii;

/**
 * This is the model class for table "judi_hono".
 *
 * @property string $instancia
 * @property integer $supuesto
 * @property string $deuda_desde
 * @property string $deuda_hasta
 * @property string $hono_min
 * @property string $hono_porc
 * @property string $gastos
 * @property string $fchmod
 * @property integer $usrmod
 */
class JudiHono extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'judi_hono';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['instancia', 'supuesto', 'deuda_desde', 'deuda_hasta', 'usrmod'], 'required'],
            [['supuesto', 'usrmod'], 'integer'],
            [['deuda_desde', 'deuda_hasta', 'hono_min', 'hono_porc', 'gastos'], 'number'],
            [['fchmod'], 'safe'],
            [['instancia'], 'string', 'max' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'instancia' => 'Instancia',
            'supuesto' => 'Supuesto',
            'deuda_desde' => 'Monto de deuda desde',
            'deuda_hasta' => 'Monto de deuda hasta',
            'hono_min' => 'Mãnimo de honorario',
            'hono_porc' => 'Porcentaje para calcular honorarios',
            'gastos' => 'Monto de gastos judiciales',
            'fchmod' => 'Fecha de modificaciã“n',
            'usrmod' => 'Codigo de usuario de modificacion',
        ];
    }
}
