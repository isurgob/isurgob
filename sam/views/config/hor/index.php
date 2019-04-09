<?php
use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\widgets\ActiveForm;
use yii\bootstrap\Alert;
use app\utils\db\utb;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$title = 'Configuración de Horario';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => Yii::$app->param->sis_url.'site/config'];
$this->params['breadcrumbs'][] = $title;

?>

<div id='config_muni_datos'>
	
	<table width="100%" style='border-bottom:1px solid #ddd' border="0">
		<tr>
			<td align="left">
				<h1><?= Html::encode($title) ?></h1>
			</td>
			
			<td align="right">
			    <?php 
			    	if (utb::getExisteProceso(3835))
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
				'id' => 'MensajeConfigHor',
				'options' => [
				'class' => 'alert-success',
				'style' => $mensaje != '' ? 'display:block' : 'display:none' 
				],
			]);	
	
				echo $mensaje;
			
			Alert::end();
			
			echo "<script>window.setTimeout(function() { $('#MensajeConfigHor').alert('close'); }, 5000)</script>"; 
		
		}
	
    	$form = ActiveForm::begin([
    		'id' => 'config_hor',
    		'options' => [
    			'enctype' => 'multipart/form-data',
    		],
    		'fieldConfig' => ['template' => '{label}{input}']
		]);
		
	?>
	
	<table border="0">
		<tr>
			<td>
	<div class="form" style="width:600px;height:610px;display:block">
	
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
						'style' => 'width:30px; text-align: center',
						'tabindex' => -1,
					]);
				?>
			</td>
			<td></td>
			<td valign="top"><label>Nombre:</label></td>
			<td colspan="4">
				<?= Html::activeInput('text', $model, 'nombre', [
						'class' => 'form-control solo-lectura',
						'style' => 'width:110px; text-align: left',
						'tabindex' => -1,
					]);
				?>
			</td>
		</tr>

		<tr>
			<td width="60px"><label>Tipo:</label></td>
			<td>
				<?= Html::activeDropDownList( $model, 'tipo', utb::getAux( 'rh.hor_tipo' ),[
						'class' => 'form-control',
						'style' => 'text-align: left',
					]);
				?>
			</td>
			<td width="15px"></td>
			<td width="60px"><label>Descanso:</label></td>
			<td>
				<?= Html::activeInput('text', $model, 'descanso', [
						'class' => 'form-control solo-lectura',
						'style' => 'width:98%; text-align: left',
						'tabindex' => -1,
					]);
				?>
			</td>
			<td width="15px"></td>
			<td><label>Min. Extra:</label></td>
			<td>
				<?= Html::activeInput('text', $model, 'tol_minextra', [
						'class' => 'form-control solo-lectura',
						'style' => 'width:100px; text-align: left',
						'tabindex' => -1,
					]);
				?>
			</td>
		</tr>
			<td><label>Min. Tarde:</label></td>
			<td colspan="4">
				<?= Html::activeInput('text', $model, 'tol_mintarde', [
						'class' => 'form-control',
						'style' => 'width:99%; text-align: left'
					]);
				?>
			</td>
		</tr>
	</table>

	</div>
	
	</td>
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
			echo '<script>DesactivarFormPost("config_hor");</script>';
		} else 
	?>
	
</div>