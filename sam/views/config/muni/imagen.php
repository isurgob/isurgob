<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\config\Config_ddjj;
use app\models\config\Muni;

$model = new Muni();

header('Content-type: image/jpeg');
echo $model->getImagen();

?>

		