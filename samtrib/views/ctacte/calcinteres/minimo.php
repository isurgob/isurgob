<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Alert;
use yii\widgets\Pjax;
use yii\helpers\BaseUrl;
use app\models\ctacte\CalcInteres;
use app\utils\db\utb;
?>

<style type="text/css">
#calcinteres-form-minimo div.row{
	height : auto;
	min-height : 17px;
}

#calcinteres-form-minimo .form-control{
	width : 100%;
}
</style>


<?php
Pjax::begin(['id' => 'calcinteres-minimo']);

$model = new CalcInteres();
$minimo = isset($minimo) ? $minimo : $model->obtenerMinimoInteres();
?>

									
<div class="row">
    
	<div class="col-xs-2" style="text-align:center;">
		<label for="monto">Monto m&iacute;nimo</label>
   		<?php echo Html::input('text', null, $minimo, ['class' => 'form-control', 'id' => 'montoMinimo']); ?>
   	</div>
    	
    <div class="col-xs-2">
    	<label></label>
    	<?php echo Html::button('Grabar', ['class' => 'btn btn-success form-control','style'=>'display:'.(utb::getExisteProceso(3051) ? 'block' : 'none'),
    										'onclick' => '$.pjax.reload({container : "#calcinteres-minimo", url : "' . BaseUrl::to(['minimo']) . '", data : {"monto" : $("#montoMinimo").val() } , method : "POST"} );',
    										]); ?>
	</div>
	
	
</div>



<?php

if(isset($mensaje))
{

Alert::widget([
	'body' => $mensaje,
	'options' => ['class' => 'alert-danger alert-dissmisible']
]);

}

Pjax::end();
?>
