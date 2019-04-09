<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ctacte\ItemVigencia */

?>
<div class="item-vigencia-update">

	<div class="row">
		<div class="col-xs-12">
    

    <?php echo $this->render('_form', [
        'model' => $model,
        'consulta' => 3,
        'extras' => $extras,
        'selectorModal' => $selectorModal
    ]) ?>
		</div>
	</div>
</div>