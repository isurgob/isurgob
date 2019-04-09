<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\config\Config_ddjj;
use yii\bootstrap\Alert;
use app\utils\db\utb;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$title = 'Inmueble - Nomenclatura';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = $title;

$action = isset($action) ? $action : 1;
$mensaje = isset($mensaje) ? $mensaje : '';
?>

<style type="text/css">
	
	td {
		
		padding-top: 6px;
		text-align: center;
	}
	
	.form {
	
		margin-top:10px;
		padding-bottom: 8px;
	
	}


</style>

<div id='config_inm_nc'>
	
	<table width="100%" style='border-bottom:1px solid #ddd; margin-bottom: 8px'>
		<tr>
			<td align="left" style="text-align: left">
				<h1><?= Html::encode($title) ?></h1>
			</td>
			
			<td align="right" style="text-align: right">
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
				'id' => 'MensajeInfoConfig_inm_nm',
				'options' => [
				'class' => 'alert-success',
				'style' => $mensaje != '' ? 'display:block' : 'display:none' 
				],
			]);	
	
				echo $mensaje;
			
			Alert::end();
			
			echo "<script>window.setTimeout(function() { $('#MensajeInfoConfig_inm_nm').alert('close'); }, 5000)</script>"; 
		
		}
		    	
    	$form = ActiveForm::begin([
    		'id' => 'config_inm_nc_form',
		]);
		
	?>
	
		<div id="manz" class="form">
		
		
		
		<table>
			<tr>
				<td colspan="2">
				</td>
				<td colspan="1">
					<h4><label>Aplica</label><h4>
				</td>
				<td></td>
				<td>
					<h4><label>Nombre</label><h4>
				</td>
				<td></td>
				<td>
					<h4><label>Largo</label><h4>
				</td>
				<td></td>
				<td>
					<h4><label>Sólo Numérico</label><h4>
				</td>
			</tr>

			<tr>
				<td style="text-align: left">
					<h3><label>s1</label></h3>
				</td>
				<td></td>
				<td>
					<?= Html::activeCheckbox( $model, 's1_aplica', [
							'label' => false,
							'uncheck' => 0,
						]);
					?>
				</td>
				<td width="20px"></td>
				<td>
					<?= Html::activeInput( 'text', $model, 's1_nom', [
							'class' => 'form-control',
							'style' => 'width: 80px;',
							'maxlength' => 10,
						]);
					?>
				</td>
				<td width="20px"></td>
				<td>
					<?= Html::activeInput( 'text', $model, 's1_max', [
							'class' => 'form-control',
							'style' => 'width: 30px;',
							'maxlength' => 4,
						]);
					?>
				</td>
				<td width="20px"></td>
				<td>
					<?= Html::activeCheckbox( $model, 's1_nro', [
							'label' => false,
							'uncheck' => 0,
						]);
					?>
				</td>
			</tr>

			<tr>
				<td style="text-align: left">
					<h3><label>s2</label></h3>
				</td>
				<td></td>
				<td>
					<?= Html::activeCheckbox( $model, 's2_aplica', [
							'label' => false,
							'uncheck' => 0,
						]);
					?>
				</td>
				<td width="20px"></td>
				<td>
					<?= Html::activeInput( 'text', $model, 's2_nom', [
							'class' => 'form-control',
							'style' => 'width: 80px;',
							'maxlength' => 10,
						]);
					?>
				</td>
				<td width="20px"></td>
				<td>
					<?= Html::activeInput( 'text', $model, 's2_max', [
							'class' => 'form-control',
							'style' => 'width: 30px;',
							'maxlength' => 4,
						]);
					?>
				</td>
				<td width="20px"></td>
				<td>
					<?= Html::activeCheckbox( $model, 's2_nro', [
							'label' => false,
							'uncheck' => 0,
						]);
					?>
				</td>
			</tr>

			<tr>
				<td style="text-align: left">
					<h3><label>s3</label></h3>
				</td>
				<td></td>
				<td>
					<?= Html::activeCheckbox( $model, 's3_aplica', [
							'label' => false,
							'uncheck' => 0,
						]);
					?>
				</td>
				<td width="20px"></td>
				<td>
					<?= Html::activeInput( 'text', $model, 's3_nom', [
							'class' => 'form-control',
							'style' => 'width: 80px;',
							'maxlength' => 10,
						]);
					?>
				</td>
				<td width="20px"></td>
				<td>
					<?= Html::activeInput( 'text', $model, 's3_max', [
							'class' => 'form-control',
							'style' => 'width: 30px;',
							'maxlength' => 4,
						]);
					?>
				</td>
				<td width="20px"></td>
				<td>
					<?= Html::activeCheckbox( $model, 's3_nro', [
							'label' => false,
							'uncheck' => 0,
						]);
					?>
				</td>
			</tr>
			
			<tr>
				<td style="text-align: left">
					<h3><label>Manzana</label></h3>
				</td>
				<td width="20px"></td>
				<td>
					<?= Html::activeCheckbox( $model, 'manz_aplica', [
							'label' => false,
							'uncheck' => 0,
						]);
					?>
				</td>
				<td width="20px"></td>
				<td>
					<?= Html::activeInput( 'text', $model, 'manz_nom', [
							'class' => 'form-control',
							'style' => 'width: 80px;',
							'maxlength' => 10,
						]);
					?>
				</td>
				<td width="20px"></td>
				<td>
					<?= Html::activeInput( 'text', $model, 'manz_max', [
							'class' => 'form-control',
							'style' => 'width: 30px;',
							'maxlength' => 4,
						]);
					?>
				</td>
				<td width="20px"></td>
				<td>
					<?= Html::activeCheckbox( $model, 'manz_nro', [
							'label' => false,
							'uncheck' => 0,
						]);
					?>
				</td>
			</tr>
			
			<tr>
				<td style="text-align: left">
					<h3><label>Parcela</label></h3>
				</td>
				<td></td>
				<td>
					<?= Html::activeCheckbox( $model, 'parc_aplica', [
							'label' => false,
							'uncheck' => 0,
						]);
					?>
				</td>
				<td width="20px"></td>
				
				<td>
					<?= Html::activeInput( 'text', $model, 'parc_nom', [
							'class' => 'form-control',
							'style' => 'width: 80px;',
							'maxlength' => 10,
						]);
					?>
				</td>
				<td width="20px"></td>
				<td>
					<?= Html::activeInput( 'text', $model, 'parc_max', [
							'class' => 'form-control',
							'style' => 'width: 30px;',
							'maxlength' => 4,
						]);
					?>
				</td>
				<td width="20px"></td>
				<td>
					<?= Html::activeCheckbox( $model, 'parc_nro', [
							'label' => false,
							'uncheck' => 0,
						]);
					?>
				</td>
			</tr>
		</table>
	</div>
    
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
			echo '<script>DesactivarFormPost("config_inm_nc_form");</script>';
		} else 
	?>
	
</div>
	
			