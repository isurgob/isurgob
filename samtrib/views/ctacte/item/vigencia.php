<?php

use yii\helpers\Html;
use yii\helpers\BaseUrl;

use yii\widgets\Pjax;

use yii\bootstrap\Modal;
use yii\bootstrap\Alert;

use app\models\ctacte\ItemVigencia;
use app\controllers\ctacte\ItemvigenciaController;

$model = new ItemVigencia();

if(isset($item_id))
{
	$model->item_id = $item_id;
	$model = $model->buscarActual();
}
?>

<style type="text/css" rel="stylesheet">



.item-vigencia,
.item-vigencia .row{
	height : auto;
	min-height : 17px;
}
</style>

<div class="item-vigencia">

	<div class="row">

		<div class="col-xs-12">

		<?php
			echo $this->render('//ctacte/itemvigencia/_form', ['consulta' => 1, 'model' => $model, 'extras' => ItemvigenciaController::getDefaultExtras()]);
		?>
	
		</div>
	</div>

</div>
