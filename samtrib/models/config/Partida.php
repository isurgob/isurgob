<?php

namespace app\models\config;

use Yii;

/**
 * This is the model class for table "partida".
 *
 * @property integer $part_id
 * @property integer $grupo
 * @property integer $subgrupo
 * @property integer $rubro
 * @property integer $cuenta
 * @property integer $subcuenta
 * @property integer $conc
 * @property integer $subconc
 * @property string $formato
 * @property string $formatoaux
 * @property string $nombre
 * @property integer $padre
 * @property integer $nivel
 * @property integer $bcocta_id
 * @property string $est
 * @property string $fchmod
 * @property integer $usrmod
 *
 * @property PartidaGrupo $grupo0
 */
class Partida extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partida';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['grupo', 'subgrupo', 'rubro', 'cuenta', 'subcuenta', 'conc', 'subconc', 'nombre', 'nivel', 'usrmod'], 'required'],
            [['grupo', 'subgrupo', 'rubro', 'cuenta', 'subcuenta', 'conc', 'subconc', 'padre', 'nivel', 'bcocta_id', 'usrmod'], 'integer'],
            [['fchmod'], 'safe'],
            [['formato', 'formatoaux', 'nombre'], 'string', 'max' => 50],
            [['est'], 'string', 'max' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'part_id' => 'Identificador de cuenta',
            'grupo' => 'Grupo',
            'subgrupo' => 'Subgrupo',
            'rubro' => 'Rubro',
            'cuenta' => 'Cuenta',
            'subcuenta' => 'Subcuenta',
            'conc' => 'Concepto',
            'subconc' => 'Subconcepto',
            'formato' => 'Formato para identificacion',
            'formatoaux' => 'Formato auxiliar para identificacion',
            'nombre' => 'Nombre',
            'padre' => 'Codigo del padre',
            'nivel' => 'Nivel',
            'bcocta_id' => 'Cuenta bancaria asociada (0==>ninguna)',
            'est' => 'Estado',
            'fchmod' => 'Fecha de modificacion',
            'usrmod' => 'Codigo de usuario que modifico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupo0()
    {
        return $this->hasOne(PartidaGrupo::className(), ['cod' => 'grupo']);
    }
}
