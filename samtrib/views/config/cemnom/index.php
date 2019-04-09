<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\config\Config_ddjj;
use yii\bootstrap\Alert;
use app\utils\db\utb;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$title = 'Cementerio - Nomenclatura';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = $title;

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

<div id='config_cem_nc'>
	
	<table width="100%" style='border-bottom:1px solid #ddd; margin-bottom: 8px'>
		<tr>
			<td align="left" style="text-align:left">
				<h1><?= Html::encode($title) ?></h1>
			</td>
			
			<td align="right" style="text-align:right">
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
				'id' => 'MensajeInfoConfig_cem_nc',
				'options' => [
				'class' => 'alert-success',
				'style' => $mensaje != '' ? 'display:block' : 'display:none' 
				],
			]);	
	
				echo $mensaje;
			
			Alert::end();
			
			echo "<script>window.setTimeout(function() { $('#MensajeInfoConfig_cem_nc').alert('close'); }, 5000)</script>"; 
		
		}
	    	
    	$form = ActiveForm::begin([
    		'id' => 'config_cem_nc_form',
		]);
		
	?>
	
		<div id="cuadro" class="form">
		
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
					<h3><label>Cuadro</label></h3>
				</td>
				<td width="20px"></td>
				<td>
					<?= Html::activeCheckbox( $model, 'cuadro_aplica', [
							'label' => false,
							'uncheck' => 0,
						]);
					?>
				</td>
				<td width="20px"></td>
				<td>
					<?= Html::activeInput( 'text', $model, 'cuadro_nom', [
							'class' => 'form-control',
							'style' => 'width: 80px;',
							'maxlength' => 10,
						]);
					?>
				</td>
				<td width="20px"></td>
				<td>
					<?= Html::activeInput( 'text', $model, 'cuadro_max', [
							'class' => 'form-control',
							'style' => 'width: 30px;',
							'maxlength' => 4,
						]);
					?>
				</td>
				<td width="20px"></td>
				<td>
					<?= Html::activeCheckbox( $model, 'cuadro_nro', [
							'label' => false,
							'uncheck' => 0,
						]);
					?>
				</td>
			</tr>

			<tr>
				<td style="text-align: left">
					<h3><label>Cuerpo</label></h3>
				</td>
				<td></td>
				<td>
					<?= Html::activeCheckbox( $model, 'cuerpo_aplica', [
							'label' => false,
							'uncheck' => 0,
						]);
					?>
				</td>
				<td width="20px"></td>
				
				<td>
					<?= Html::activeInput( 'text', $model, 'cuerpo_nom', [
							'class' => 'form-control',
							'style' => 'width: 80px;',
							'maxlength' => 10,
						]);
					?>
				</td>
				<td width="20px"></td>
				<td>
					<?= Html::activeInput( 'text', $model, 'cuerpo_max', [
							'class' => 'form-control',
							'style' => 'width: 30px;',
							'maxlength' => 4,
						]);
					?>
				</td>
				<td width="20px"></td>
				<td>
					<?= Html::activeCheckbox( $model, 'cuerpo_nro', [
							'label' => false,
							'uncheck' => 0,
						]);
					?>
				</td>
			</tr>

			<tr>
				<td style="text-align: left">
					<h3><label>Tipo</label></h3>
				</td>
				<td></td>
				<td>
					<?= Html::activeCheckbox( $model, 'tipo_aplica', [
							'label' => false,
							'uncheck' => 0,
						]);
					?>
				</td>
				<td width="20px"></td>
				<td>
					<?= Html::activeInput( 'text', $model, 'tipo_nom', [
							'class' => 'form-control',
							'style' => 'width: 80px;',
							'maxlength' => 10,
						]);
					?>
				</td>
				<td width="20px"></td>
				<td>
					<?= Html::activeInput( 'text', $model, 'tipo_max', [
							'class' => 'form-control',
							'style' => 'width: 30px;',
							'maxlength' => 4,
						]);
					?>
				</td>
				<td width="20px"></td>
				<td>
					<?= Html::activeCheckbox( $model, 'tipo_nro', [
							'label' => false,
							'uncheck' => 0,
						]);
					?>
				</td>
			</tr>
			
			<tr>
				<td style="text-align: left">
					<h3><label>Piso</label></h3>
				</td>
				<td></td>
				<td>
					<?= Html::activeCheckbox( $model, 'piso_aplica', [
							'label' => false,
							'uncheck' => 0,
						]);
					?>
				</td>
				<td width="20px"></td>
				<td>
					<?= Html::activeInput( 'text', $model, 'piso_nom', [
							'class' => 'form-control',
							'style' => 'width: 80px;',
							'maxlength' => 10,
						]);
					?>
				</td>
				<td width="20px"></td>
				<td>
					<?= Html::activeInput( 'text', $model, 'piso_max', [
							'class' => 'form-control',
							'style' => 'width: 30px;',
							'maxlength' => 4,
						]);
					?>
				</td>
				<td width="20px"></td>
				<td>
					<?= Html::activeCheckbox( $model, 'piso_nro', [
							'label' => false,
							'uncheck' => 0,
						]);
					?>
				</td>
			</tr>
			
			<tr>
				<td style="text-align: left">
					<h3><label>Fila</label></h3>
				</td>
				<td></td>
				<td>
					<?= Html::activeCheckbox( $model, 'fila_aplica', [
							'label' => false,
							'uncheck' => 0,
						]);
					?>
				</td>
				<td width="20px"></td>
				<td>
					<?= Html::activeInput( 'text', $model, 'fila_nom', [
							'class' => 'form-control',
							'style' => 'width: 80px;',
							'maxlength' => 10,
						]);
					?>
				</td>
				<td width="20px"></td>
				<td>
					<?= Html::activeInput( 'text', $model, 'fila_max', [
							'class' => 'form-control',
							'style' => 'width: 30px;',
							'maxlength' => 4,
						]);
					?>
				</td>
				<td width="20px"></td>
				<td>
					<?= Html::activeCheckbox( $model, 'fila_nro', [
							'label' => false,
							'uncheck' => 0,
						]);
					?>
				</td>
			</tr>
			
			<tr>
				<td style="text-align: left">
					<h3><label>Número</label></h3>
				</td>
				<td></td>
				<td>
					<?= Html::activeCheckbox( $model, 'nume_aplica', [
							'label' => false,
							'uncheck' => 0,
						]);
					?>
				</td>
				<td width="20px"></td>
				<td>
					<?= Html::activeInput( 'text', $model, 'nume_nom', [
							'class' => 'form-control',
							'style' => 'width: 80px;',
							'maxlength' => 10,
						]);
					?>
				</td>
				<td width="20px"></td>
				<td>
					<?= Html::activeInput( 'text', $model, 'nume_max', [
							'class' => 'form-control',
							'style' => 'width: 30px;',
							'maxlength' => 4,
						]);
					?>
				</td>
				<td width="20px"></td>
				<td>
					<?= Html::activeCheckbox( $model, 'nume_nro', [
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
			echo '<script>DesactivarFormPost("config_cem_nc_form");</script>';
		} else 
	?>
	
</div>
	
			