<?php 
use app\models\config\Muni;

$logo=$_GET['logo'];
$model= new Muni();

$imagen= $model->getImagen(1);

if($imagen !== null) header('Content-type: image/jpeg');
else Yii::$app->response->setStatusCode(404, 'Imagen no disponible');

echo $imagen;
?>