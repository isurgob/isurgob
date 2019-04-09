<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use app\utils\db\Fecha;
use app\utils\db\utb;
use yii\helpers\BaseUrl;
use \yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model app\models\ctacte\TribVenc */
/* @var $form yii\widgets\ActiveForm */

	/**
	 * @param $consulta es una variable que:
	 * 		=> $consulta == 1 => El formulario se dibuja en el index
	 * 		=> $consulta == 0 => El formulario se dibuja en el create
	 * 		=> $consulta == 3 => El formulario se dibuja en el update
	 * 		=> $consulta == 2 => El formulario se dibuja en el delete
	 */


 	$form = ActiveForm::begin([
		'id'=>'form-vencimientos',
	    'fieldConfig' => [

			'template' => "{label}{input}",
        ]]);

	$param = Yii::$app->params;

    if (!isset($consulta)){

    	$consulta = 1;
    }

?>

<style type="text/css">

div.triv-desc-form .row{
	height : auto;
	min-height : 17px;
}

</style>

<div class="triv-desc-form">

<div class="form">

<table>
	<tr>
		<td><label>Tributo</label></td>
		<td width="30px">
		<td><label>Año</label></td>
		<td><label>Cuota</label></td>
	</tr>
	<tr>
		<td>
    		<?= Html::activeDropDownList($model, 'trib_id', utb::getAux('trib','trib_id','nombre', 3, 'tipo IN (1,2,4,5)'), [
					'id' => 'selectVenc',
					'style' => 'width:234px;',
					'class' => 'form-control' . ( $consulta == 3 ? ' read-only' : '' ),
				]);
			?>
		</td>
		<td></td>
		<td width="50px">
			<?= $form->field($model, 'anio')->textInput([
					'style' => 'width:40px;' ,
					'maxlength' => '4',
					'class' => 'form-control' . ( $consulta == 3 ? ' read-only' : '' ),
				])->label(false);
			?>
		</td>
		<td>
			<?= $form->field($model, 'cuota')->textInput([
					'style' => 'width:40px;',
					'maxlength' => '3',
					'class' => 'form-control' . ( $consulta == 3 ? ' read-only' : '' ),
				])->label(false);
			?>
		</td>
	</tr>
</table>

<table>
	<tr>
		<td><label>Vencimiento 1</label></td>
		<td width="40px"></td>
		<td><label>Vencimiento 2</label></td>
	<tr>
		<td>
			<?= $form->field($model, 'fchvenc1')->widget(DatePicker::classname(), [
					'dateFormat' => 'dd/MM/yyyy',
					'value' => ( Fecha::usuarioToDatePicker( ( $model->fchvenc1 == '' ? Fecha::getDiaActual() : $model->fchvenc1 ) ) ),
					'options' => [
						'class'=>'form-control'],
				])->label(false);
			?>
		</td>
		<td></td>
		<td>
			<?= $form->field($model, 'fchvenc2')->widget(DatePicker::classname(), [
					'dateFormat' => 'dd/MM/yyyy',
					'value' => ( Fecha::usuarioToDatePicker( ( $model->fchvenc2 == '' ? Fecha::getDiaActual() : $model->fchvenc2 ) ) ),
					'options' => [
						'class'=>'form-control',
					],
				])->label(false);
			?>
		</td>
	</tr>
	<tr>
		<td rowspan="2"><?= $form->field($model, 'habilitarpagoanual')->checkbox(['onchange' => 'aplicaPagoAnual()', 'uncheck' => 0]); ?></td>
		<td></td>
		<td><label>Vencimiento anual</label></td>
	</tr>
	<tr>
		<td></td>
		<td>
			<?= $form->field($model, 'fchvencanual')->widget(DatePicker::classname(), [
					'dateFormat' => 'dd/MM/yyyy',
					'value' => ( Fecha::usuarioToDatePicker( ( $model->fchvencanual == '' ? '' : $model->fchvencanual ) ) ),
					'options' => [
						'class'=>'form-control',
					],
				])->label(false);
			?>
		</td>
	</tr>
</table>

<?php
//Código que se ejecuta sólo si nos encontramos en el update
	if ($consulta == 3)
	{

		?>

			<table>
				<tr>
					<td>
					<?=
						Html::activeCheckbox( $model, 'actualizactacte', [
							'onchange' => 'actualizaCtaCte()',
							'unchecked' => 0,
						]);
					?>

					</td>
				</tr>
				<tr>
					<td>
						<div class="alert alert-info hidden" id="tribvenc-alert">Se actualizarán los vencimientos en la Cuenta Corriente</div>
					</td>
				</tr>
			</table>

	<script>
		$(document).ready(function() {

			actualizaCtaCte();

		});

	</script>

	<?php
	}

	if ( $model->fchvencanual != "" || $model->fchvencanual != null )
	{

		?>

		<script>

			$(document).ready(function() {

				aplicaPago();

			});

		</script>

		<?php

	}

	?>

	</div>

	<?php

if($consulta == 0 || $consulta == 2 || $consulta == 3) { ?>
	 <div class="form-group" style="margin-top: 8px; margin-bottom: 8px">

	        <?php

	        	if ($consulta == 0 || $consulta == 3)
	        	{
	        		echo Html::button($model->isNewRecord ? 'Grabar' : 'Grabar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success', 'onclick' => 'f_grabar()' ]);

	        	} else {

					echo Html::a('Eliminar', ['delete', 'accion'=>1,'trib_id' => $model->trib_id, 'anio' => $model->anio, 'cuota' => $model->cuota], [
				            'class' => 'btn btn-danger',
				            'data' => [
				                'confirm' => 'Esta seguro que desea eliminar ?',
				       	         'method' => 'post',
			            	],
        				]);
	        	}

	        	echo '&nbsp;&nbsp;';

	        	echo Html::a('Cancelar',['index'], [
	        			'class' => 'btn btn-primary',
	        	]);

	        ?>

	</div>

	    <?php }


	 echo $form->errorSummary($model);

     ActiveForm::end();

    	if ($consulta==1 || $consulta == 2) {
    		echo "<script>DesactivarForm('form-vencimientos');</script>";
    	}

	    ?>
</div>

<?php 
Modal::begin([
		'id' => 'modalMensajeVencAnual',
		'class' => 'container',
		'size' => 'modal-normal',
		'header' => '<h2><b>Vencimiento Anual</b></h2>',
		'closeButton' => [
			'label' => '<b>X</b>',
			'class' => 'btn btn-danger btn-sm pull-right',
			'id' => 'btCancelarModalMetas'
			],
	]);

	echo "<div class='alert alert-warning'> 
			<b>	
				La fecha de vencimiento anual no se corresponde con el año de vencimiento. <br>
				Desea grabar de todas maneras?
			</b>
		 </div>";
		 
		 echo Html::button('SI', [ 'class' => 'btn btn-success', 'onclick' => '$("#form-vencimientos").submit()' ]);
		 echo Html::button('NO', [ 'class' => 'btn btn-primary', 'onclick' => '$("#modalMensajeVencAnual").modal( "hide" )' ]);

Modal::end();
?>

<script>

function actualizaCtaCte()
{
	var alerta = $('#tribvenc-alert');

	var checkalerta = $( "#tribvenc-actualizactacte" ).is( ":checked");

	if ( checkalerta ) {

		alerta.removeClass("hidden");

	} else {

		alerta.addClass("hidden");
		checkalerta.checked = false;

	}
}

function aplicaPago() {

	var check = document.getElementById('tribvenc-habilitarpagoanual');

	var fchvencanual = document.getElementById('tribvenc-fchvencanual');

	check.checked = true;

	check.value= 1;

	$("#tribvenc-fchvencanual").attr("readonly",false);

}

function aplicaPagoAnual()
{

	var check = document.getElementById('tribvenc-habilitarpagoanual');

	var fchvencanual = document.getElementById('tribvenc-fchvencanual');

	if (!check.checked) {

		$("#tribvenc-fchvencanual").attr("readonly",true);
		check.value = 0;
		fchvencanual.value = null;

	} else {

		check.value = 1;

		$("#tribvenc-fchvencanual").attr("readonly",false);

	}
}

$(document).ready(function() {

	aplicaPagoAnual();

});

function f_grabar(){

	var check = document.getElementById('tribvenc-habilitarpagoanual');
	var fchvencanual = $("#tribvenc-fchvencanual").val();
	
	if ( check.checked ) {
		$.post( "<?= BaseUrl::toRoute('verificaraniovencanual');?>", { "fchvencanual": fchvencanual }
		).success(function(data){
			datos = jQuery.parseJSON(data); 
			
			if ( datos.aniovenc < $("#tribvenc-anio").val() )
				$( "#modalMensajeVencAnual" ).modal( "show" );
			else
				$("#form-vencimientos").submit();
		});
	}else {
		$("#form-vencimientos").submit();
	}
}

</script>
