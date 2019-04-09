<?php
use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\widgets\ActiveForm;
use app\models\config\Config_ddjj;
use yii\bootstrap\Alert;
use app\utils\db\utb;
use yii\imagine\Image;
use yii\imagine\Imagick;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$title = 'Datos Municipales';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => Yii::$app->param->sis_url.'site/config'];
$this->params['breadcrumbs'][] = $title;

?>

<style type="text/css">

	td {

		//padding-top: 6px;

	}

	.form {

		margin-top:10px;
		padding-bottom: 8px;

	}

	.imagen {

		padding-bottom: 12px;
	}


</style>

<div id='config_muni_datos'>

	<table width="100%" style='border-bottom:1px solid #ddd' border="0">
		<tr>
			<td align="left">
				<h1><?= Html::encode($title) ?></h1>
			</td>

			<td align="right">
			    <?php
			    	if (utb::getExisteProceso(3054))
			    		echo Html::a('Modificar', ['update'], [
								'class' => 'btn btn-primary',
								'disabled' => $action == 1 ? false : true,
							]);
			    ?>
	    	</td>
	    </tr>
	</table>

	<?php

		if( $mensaje != '' )
		{

			Alert::begin([
				'id' => 'MensajeInfoConfig_muni_datos',
				'options' => [
				'class' => 'alert-success',
				'style' => $mensaje != '' ? 'display:block' : 'display:none'
				],
			]);

				echo $mensaje;

			Alert::end();

			echo "<script>window.setTimeout(function() { $('#MensajeInfoConfig_muni_datos').alert('close'); }, 5000)</script>";

		}

    	$form = ActiveForm::begin([
    		'id' => 'config_muni_datos_form',
    		'options' => [
    			'enctype' => 'multipart/form-data',
    		],
    		'fieldConfig' => ['template' => '{label}{input}']
		]);

	?>

	<table border="0">
		<tr>
			<td>
	<div class="form" style="width:600px;height:650px;display:block">

	<table border="0">
		<tr>
			<td colspan="5">
				<h3><label>Identificación</label></h3>
			</td>
		</tr>
		<tr>
			<td valign="top" width="60px"><label>Código:</label></td>
			<td valign="top">
				<?= Html::activeInput('text', $model, 'codigo', [
						'class' => 'form-control solo-lectura',
						'style' => 'width:80px; text-align: center',
						'tabindex' => -1,
					]);
				?>
			</td>
			<td></td>
			<td valign="top"><label>Nombre:</label></td>
			<td colspan="4">
				<?= Html::activeTextarea( $model, 'nombre', [
						'class' => 'form-control solo-lectura',
						'style' => 'width: 325px;max-width: 325px; height: 60px; max-height: 100px; resize: none',
						'tabindex' => -1,
					]);
				?>
			</td>
		</tr>

		<tr>
			<td width="60px"><label>País:</label></td>
			<td>
				<?= Html::activeInput('text', $model, 'pais_nom', [
						'class' => 'form-control solo-lectura',
						'style' => 'width:110px; text-align: left',
						'tabindex' => -1,
					]);
				?>
			</td>
			<td width="15px"></td>
			<td width="60px"><label>Provincia:</label></td>
			<td>
				<?= Html::activeInput('text', $model, 'prov_nom', [
						'class' => 'form-control solo-lectura',
						'style' => 'width:98%; text-align: left',
						'tabindex' => -1,
					]);
				?>
			</td>
			<td width="15px"></td>
			<td><label>Localidad:</label></td>
			<td>
				<?= Html::activeInput('text', $model, 'loc_nom', [
						'class' => 'form-control solo-lectura',
						'style' => 'width:100px; text-align: left',
						'tabindex' => -1,
					]);
				?>
			</td>
		</tr>
			<td><label>Domicilio:</label></td>
			<td colspan="4">
				<?= Html::activeInput('text', $model, 'domi', [
						'class' => 'form-control',
						'style' => 'width:99%; text-align: left'
					]);
				?>
			</td>
			<td></td>
			<td><label>Teléfono:</label></td>
			<td>
				<?= Html::activeInput('text', $model, 'tel', [
						'class' => 'form-control',
						'style' => 'width:100px; text-align: left'
					]);
				?>
			</td>
		</tr>

		<tr>
			<td><label>mail:</label></td>
			<td colspan="4">
				<?= Html::activeInput('text', $model, 'mail', [
						'class' => 'form-control',
						'style' => 'width:99%; text-align: left'
					]);
				?>
			</td>
		</tr>

		<tr>
			<td><label>skype:</label></td>
			<td colspan="4">
				<?= Html::activeInput('text', $model, 'skype', [
						'class' => 'form-control',
						'style' => 'width:99%; text-align: left'
					]);
				?>
			</td>
		</tr>

		<tr>
			<td><label>URL:</label></td>
			<td colspan="4">
				<?= Html::activeInput('text', $model, 'url', [
						'class' => 'form-control',
						'style' => 'width:99%; text-align: left'
					]);
				?>
			</td>
		</tr>

		<tr>
			<td colspan="5">
				<h3><label>Para Atención de Reclamos</label></h3>
			</td>
		</tr>

		<tr>
			<td><label>Domicilio:</label></td>
			<td colspan="4">
				<?= Html::activeInput('text', $model, 'recl_domi', [
						'class' => 'form-control',
						'style' => 'width:99%; text-align: left',
					]);
				?>
			</td>
			<td></td>
			<td><label>Teléfono:</label></td>
			<td>
				<?= Html::activeInput('text', $model, 'recl_tel', [
						'class' => 'form-control',
						'style' => 'width:100px; text-align: left',
					]);
				?>
			</td>
		</tr>

		<tr>
			<td><label>email:</label></td>
			<td colspan="4">
				<?= Html::activeInput('text', $model, 'recl_mail', [
						'class' => 'form-control',
						'style' => 'width:99%; text-align: left',
					]);
				?>
			</td>
		</tr>

		<tr>
			<td colspan="5">
				<h3><label>Para Fiscalización Tributaria</label></h3>
			</td>
		</tr>

		<tr>
			<td><label>Domicilio:</label></td>
			<td colspan="4">
				<?= Html::activeInput('text', $model, 'fisc_domi', [
						'class' => 'form-control',
						'style' => 'width:99%; text-align: left',
					]);
				?>
			</td>
			<td></td>
			<td><label>Teléfono:</label></td>
			<td>
				<?= Html::activeInput('text', $model, 'fisc_tel', [
						'class' => 'form-control',
						'style' => 'width:100px; text-align: left',
					]);
				?>
			</td>
		</tr>

		<tr>
			<td><label>email:</label></td>
			<td colspan="4">
				<?= Html::activeInput('text', $model, 'fisc_mail', [
						'class' => 'form-control',
						'style' => 'width:99%; text-align: left',
					]);
				?>
			</td>
		</tr>

		<tr>
			<td colspan="5">
				<h3><label>Para Juzgado de Faltas</label></h3>
			</td>
		</tr>

		<tr>
			<td><label>Domicilio:</label></td>
			<td colspan="4">
				<?= Html::activeInput('text', $model, 'juz_domi', [
						'class' => 'form-control',
						'style' => 'width:99%; text-align: left',
					]);
				?>
			</td>
			<td></td>
			<td><label>Teléfono:</label></td>
			<td>
				<?= Html::activeInput('text', $model, 'juz_tel', [
						'class' => 'form-control',
						'style' => 'width:100px; text-align: left',
					]);
				?>
			</td>
		</tr>

		<tr>
			<td><label>email:</label></td>
			<td colspan="4">
				<?= Html::activeInput('text', $model, 'juz_mail', [
						'class' => 'form-control',
						'style' => 'width:99%; text-align: left',
					]);
				?>
			</td>
		</tr>

		<tr>
			<td colspan="5">
				<h3><label>Otros Datos</label></h3>
			</td>
		</tr>

		<tr>
			<td><label>Sit. IVA:</label></td>
			<td colspan="1">
				<?= Html::activeDropDownList( $model, 'iva', utb::getAux( 'comer_tiva' ),[
						'class' => 'form-control',
						'style' => 'text-align: left',
					]);
				?>
			</td>
			<td></td>
			<td><label>CUIT:</label></td>
			<td>
				<?= Html::activeInput('text', $model, 'cuit', [
						'class' => 'form-control',
						'style' => 'width:98%; text-align: left',
						'onkeypress' => "return justNumbers(event);"
					]);
				?>
			</td>
			<td></td>
			<td width="70px"><label>Ing. Brutos:</label></td>
			<td>
				<?= Html::activeInput('text', $model, 'ingbrutos', [
						'class' => 'form-control',
						'style' => 'width:100px; text-align: left',
					]);
				?>
			</td>
		</tr>

		<tr>
			<td><label>Intendente/ Pdte.Mun.:</label></td>
			<td colspan="4">
				<?= Html::activeInput('text', $model, 'presidente', [
						'class' => 'form-control',
						'style' => 'width:99%; text-align: left',
					]);
				?>
			</td>
		</tr>
	</table>

	</div>

	</td>

	<td width="180px" >

	<div class="form" style="height:650px;padding-left: 5px;padding-right: 5px; margin-left:3px;">
	<table>
		<tr>
			<td colspan="5">
				<h3><label>Definición de Logos</label></h3>
			</td>
		</tr>

		<tr>
			<td><label>General:</label>
				<?= Html::button('<span class="glyphicon glyphicon-search"></span>', [
						'class' => 'bt-buscar pull-right',
						'style' => $action == 3 ? 'display_block' : 'display:none',
						'onclick' => 'cargarLogo($("#imagenLogoGeneral"));',
					]);
				?>
				<?= $form->field($model, 'logo')->fileInput(['id' => 'imagenLogoGeneral', 'class' => 'imageLoader hidden', 'data-target' => '#representacionImagenLogoGeneral'])->label(false); ?>
			</td>
		</tr>
		<tr>
			<td class="text-center imagen" style="padding-top: 5px;">
				<div style="width:165px; max-width:165px;">
					<?php
					$imagen= BaseUrl::toRoute(['imagen', 'logo' => 1]);
					?>
					<img src="<?= $imagen ?>" class="img img-responsive img-thumbnail logo" id="representacionImagenLogoGeneral" alt="Logo General.">
				</div>
			</td>
		</tr>

		<tr>
			<td>
				<label>Comprobante Superior:</label>

				<?= Html::button('<span class="glyphicon glyphicon-search"></span>', [
						'class' => 'bt-buscar pull-right',
						'style' => $action == 3 ? 'display_block' : 'display:none',
						'onclick' => 'cargarLogo($("#imagenLogoComprobanteSuperior"));',
					]);
				?>
				<?= $form->field($model, 'logo_grande')->fileInput(['id' => 'imagenLogoComprobanteSuperior', 'class' => 'imageLoader hidden', 'data-target' => '#representacionImagenComprobanteSuperior'])->label(false); ?>
			</td>
		</tr>

		<tr>
			<td class="text-center imagen" style="padding-top: 5px">
				<div style="width:165px; max-width:165px;">
				<?php
				$imagen= BaseUrl::toRoute(['imagen', 'logo' => 2]);
				?>
				<img src="<?= $imagen ?>" class="img img-responsive img-thumbnail logo" id="representacionImagenComprobanteSuperior" alt="Logo Comprobante Superior.">
				</div>
			</td>
		</tr>

		<tr>
			<td>
				<label>Comprobante Talones:</label>
				<?= Html::button('<span class="glyphicon glyphicon-search"></span>', [
						'class' => 'bt-buscar pull-right',
						'style' => $action == 3 ? 'display_block' : 'display:none',
						'onclick' => 'cargarLogo($("#imagenLogoTalon"));',
					]);
				?>
				<?= $form->field($model, 'logo_talon')->fileInput(['id' => 'imagenLogoTalon', 'class' => 'imageLoader hidden', 'data-target' => '#representacionImagenTalon'])->label(false); ?>
			</td>
		</tr>

		<tr>
			<td class="text-center imagen" style="padding-top: 5px">
				<div style="width:165px; max-width:165px;">
					<?php
					$imagen= BaseUrl::toRoute(['imagen', 'logo' => 3]);
					?>
					<img src="<?= $imagen ?>" class="img img-responsive img-thumbnail logo" id="representacionImagenTalon" alt="Logo Comprobante Talones.">
				</div>
			</td>
		</tr>
		
		<tr>
			<td>
				<br><br>
				<?= $form->field($model, 'incluir_logo2')->checkbox() ?>
			</td>
		</tr>

	</table>
	</div>

	</tr>
	</table>


    <?php

		if ( $action != 1 )
		{
			?>

			<div style="margin-top: 8px">

				<?= Html::submitButton('Aceptar',[
						'class' => 'btn btn-success',
					]);
				?>

				&nbsp;&nbsp;

				<?= Html::a('Cancelar', ['index'], [
						'class' => 'btn btn-primary',
					]);
				?>
			</div>

			<?php
		}

		echo $form->errorSummary( $model, [

			'style' => 'margin-top: 8px',
		] );

		ActiveForm::end();

		if ( $action == 1 )
		{
			echo '<script>DesactivarFormPost("config_muni_datos_form");</script>';
		} else
	?>

</div>

<script type="text/javascript">

function cargarLogo($target){
	$target.click();
}

$(document).ready(function()
{


	$(".imageLoader").change(function(){

		$(".imageLoader").removeClass("active");
		$(this).addClass("active");
		var input = $(this)[0];
      	var file = input.files[0];

		// load file into preview pane
		var reader = new FileReader();

		reader.onload = function(e){
			$imagen= $($(".imageLoader.active").data("target"));

			$imagen.attr('src', e.target.result);
     	};

     	reader.readAsDataURL(file);
	});

	$(".logo").on("error", function(){
		$(this).attr("src", "https://placehold.it/150x100.png?text=Imagen%20no%20disponible.");
		$(this).attr("alt", "Imagen no disponible.");
	});
});
</script>
