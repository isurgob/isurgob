<?php 
$items = [
  ['label' => '<i class="glyphicon glyphicon-home" width="15px"></i>', 'url' => Yii::$app->param->urlsam.'site/index'],
  ['label' => '<i class="glyphicon glyphicon-info-sign" width="15px"></i>', 'url' => Yii::$app->param->urlsam.'site/about'],
  ['label' => '<i class="glyphicon glyphicon-envelope" width="15px"></i>', 'url' => ['#']], ///site/contact
  ['label' => '<i class="glyphicon glyphicon-wrench" width="15px"></i>', 'url' => Yii::$app->param->urlsam.'site/cbioclave'],
  ['label' => '<i class="glyphicon glyphicon-off" width="15px"></i>', 'url' => Yii::$app->param->urlsam.'site/logout'],
];

return $items; 
?>