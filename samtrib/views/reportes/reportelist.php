<?php
 ini_set('memory_limit', '-1'); 
 set_time_limit(0) ;

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\Session;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;

if (count(array_column($columnas,'footer','attribute')) > 0){
	foreach ($columnas as $clave){
		if (array_key_exists('footer', $clave)) {
			Yii::$app->session['contentOptions'] = $clave['contentOptions']['style'];
			
			$clave['contentOptions'] = function ($model, $key, $index, $column) {
											if ($index == Yii::$app->session['cant_reg'])
												$style = 'font-weight:bold;border-top:1px solid #000;'.Yii::$app->session['contentOptions'];
											else
												$style = Yii::$app->session['contentOptions'];
											
											return ['style' => $style];
											
										};
		}
		$array[] = $clave;
	}

	$columnas = $array;
}

echo "<div class='body'>";
echo "<p class='tt'>".$titulo."</p>";
echo "<p class='cond'><u><b>Condici&oacute;n:</b></u>&nbsp;".$condicion."</p>";
 
echo GridView::widget([
		'id' => 'GrillaReporteList',
		'dataProvider' => $provider,
		'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
		'summaryOptions' => ['style' => 'display:none'],
		'tableOptions' => ['class' => 'GrillaHeard'],
		'columns' => $columnas,
		'showFooter' => false
    ]); 
		
echo "</div>";

Yii::$app->session['cant_reg'] = null;
Yii::$app->session['contentOptions'] = null;
?>
 	
