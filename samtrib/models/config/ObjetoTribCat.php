<?php

namespace app\models\config;

use Yii;

/**
 * This is the model class for table "objeto_trib_cat".
 *
 * @property integer $trib_id
 * @property string $cat
 * @property string $nombre
 * @property string $fchmod
 * @property integer $usrmod
 */
class ObjetoTribCat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'objeto_trib_cat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['trib_id', 'cat', 'usrmod'], 'required'],
            [['trib_id', 'usrmod'], 'integer'],
            [['fchmod'], 'safe'],
            [['cat'], 'string', 'max' => 1],
            [['nombre'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'trib_id' => 'Codigo de tributo',
            'cat' => 'Categoria',
            'nombre' => 'Nombre',
            'fchmod' => 'Fecha de modificacion',
            'usrmod' => 'Codigo de usuario que modifico',
        ];
    }
}
