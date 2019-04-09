<?php

use yii\helpers\Html;

use app\models\ctacte\ItemVigencia;


/* @var $this yii\web\View */
/* @var $model app\models\ctacte\ItemVigencia */

?>
<div class="item-vigencia-create">

	<div class="row">
		<div class="col-xs-12">
    

    <?php echo $this->render('_form', [
        'model' => $model,
        'consulta' => 0,
        'extras' => $extras,
        'selectorModal' => $selectorModal
    ]) ?>
		</div>
	</div>
</div>

