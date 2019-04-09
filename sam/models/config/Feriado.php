<?php

namespace app\models\config;

use Yii;

/**
 * This is the model class for table "calc_feriado".
 *
 * @property string $fecha
 * @property string $detalle
 * @property string $fchmod
 * @property integer $usrmod
 */
class Feriado extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'calc_feriado';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fecha', 'detalle'], 'required'],
            [['fecha'], 'safe'],
            [['detalle'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fecha' => 'Fecha',
            'detalle' => 'Detalle',
            'fchmod' => 'Fecha de modificacion',
            'usrmod' => 'Codigo de usuario que modifico',
        ];
    }
}
