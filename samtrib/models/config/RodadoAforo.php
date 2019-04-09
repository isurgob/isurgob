<?php

namespace app\models\config;

use Yii;

/**
 * This is the model class for table "rodado_aforo".
 *
 * @property integer $anioval
 * @property string $origen_id
 * @property string $marca_id
 * @property string $tipo_id
 * @property string $modelo_id
 * @property integer $anio
 * @property string $valor
 * @property string $fchmod
 * @property integer $usrmod
 */
class RodadoAforo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rodado_aforo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['anioval', 'origen_id', 'marca_id', 'tipo_id', 'modelo_id', 'anio', 'valor', 'usrmod'], 'required'],
            [['anioval', 'anio', 'usrmod'], 'integer'],
            [['valor'], 'number'],
            [['fchmod'], 'safe'],
            [['origen_id'], 'string', 'max' => 1],
            [['marca_id', 'tipo_id', 'modelo_id'], 'string', 'max' => 3]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'anioval' => 'Aã‘o de aforo',
            'origen_id' => 'Origen (n/i)',
            'marca_id' => 'Marca rodado',
            'tipo_id' => 'Tipo rodado',
            'modelo_id' => 'Modelo rodado',
            'anio' => 'Aã‘o valuacion',
            'valor' => 'Valuacion',
            'fchmod' => 'Fecha de modificacion',
            'usrmod' => 'Codigo de usuario que modifico',
        ];
    }
}
