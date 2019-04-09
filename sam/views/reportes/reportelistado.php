<?php
 ini_set('memory_limit', '-1'); 
 set_time_limit(0) ;

use yii\helpers\Html;
use yii\grid\GridView;


echo "<div class='body'>";
echo "<p class='tt'>".$titulo."</p>";
if ($datos['descripcion'] != "") echo "<p class='cond'><u><b>Condici&oacute;n:</b></u>&nbsp;" . $datos['descripcion'] . "</p>";
 
echo GridView::widget([
		'id' => 'GrillaReporteList',
		'dataProvider' => $datos['dataProviderResultados'],
		'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
		'summaryOptions' => ['style' => 'display:none'],
		'tableOptions' => ['class' => 'GrillaHeard'],
		'columns' => $datos['columnas'],
		'showFooter' => true
    ]); 
		
echo "</div>";

?>
 	
