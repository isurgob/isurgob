<?php
use yii\helpers\Html;
use yii\bootstrap\Alert;
use yii\bootstrap\Tabs;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

use app\utils\db\Fecha;


$lote = Yii::$app->request->get('lote_id', '');

$this->params['breadcrumbs'][] = ['label' => 'Gestión de Incumplimiento', 'url' => ['view', 'id' => $lote]];
$this->params['breadcrumbs'][] = 'Seguimiento de Intimación';

$atributosComunes = ['readonly' => true, 'class' => 'form-control solo-lectura'];

Pjax::begin(['enableReplaceState' => false, 'enablePushState' => false, 'id' => 'pjaxPrincipal']);
?>

<div class="seguimiento" style="width:100%;">
	<h1>Seguimiento de Intimaci&oacute;n</h1>
	
	
	<?php
	if(isset($mensaje) && !empty(trim($mensaje)))
	{
		Alert::begin([
			'id' => 'alertMensajeSeguimiento',
			'options' => ['class' => 'alert-success alert-dissmissible']
		]); 	
		
		echo $mensaje;
		echo '<script type="text/javascript">setTimeout(function(){$("#alertMensajeSeguimiento").addClass("hidden");}, 5000);</script>';
		
		Alert::end();
	}
	?>
	<table id="asd">
		<tr>
			<td valign="top">
				<div class="form" style="padding:10px;">
					<table border="0">
						<tr>
							<td width="85px"><label>Id. Intimaci&oacute;n: </label></td>
							<td> <?= Html::textInput(null, $extras['datos']['inti_id'], $atributosComunes + ['style' => 'width:70px;']) ?></td>
							<td width="15px"></td>
							<td width="75px"><label>Lote: </label></td>
							<td><?= Html::textInput(null, $extras['datos']['lote_id'], $atributosComunes + ['style' => 'width:40px;text-align:center']) ?></td>
							<td width="45px"></td>
							<td width="45px"><label>Objeto: </label></td>
							<td><?= Html::textInput(null, $extras['datos']['obj_id'], $atributosComunes + ['style' => 'width:68px;']) ?></td>
							<td></td>
						</tr>
					</table>
					
					<table>
						<tr>
							<td width="85px"><label>Num: </label></td>
							<td></td>
							<td><?= Html::textInput(null, $extras['datos']['num'], $atributosComunes + ['style' => 'width:70px;'])?></td>
							<td width="15px"></td>
							<td width="75px"><label>Nombre: </label></td>
							<td width="200px"><?= Html::textInput(null, $extras['datos']['nombre'], $atributosComunes + ['style' => 'width:200px;']) ?></td>
							<td width="15px"></td>
							<td width="45px"><label>CUIT: </label></td>
							<td width="100px"><?= Html::textInput(null, $extras['datos']['cuit'], $atributosComunes + ['style' => 'width:100px;']) ?></td>
						</tr>
					</table>
					
					<table>
						<tr>
							<td width="85px"><label>Domicilio: </label></td>
							<td><?= Html::textInput(null, $extras['datos']['dompos_dir'], $atributosComunes + ['style' => 'width:362px;']) ?></td>
							<td width="15px"></td>
							<td width="45px"><label>Tel: </label></td>
							<td><?= Html::textInput(null, $extras['datos']['tel'], $atributosComunes + ['style' => 'width:100px;']) ?></td>
						</tr>
					</table>
					
					<table>	
						<tr>
							<td width="85px"><label>Car&aacute;cter: </label></td>
							<td><?= Html::textInput(null, $extras['datos']['caracter_nom'], $atributosComunes + ['style' => 'width:150px;']) ?></td>
							<td width="15px"></td>
							<td width="70px"><label>Resultado: </label></td>
							<td><?= Html::textInput(null, $extras['datos']['resultado_nom'], $atributosComunes + ['style' => 'width:125px;']) ?></td>
							<td width="15px"></td>
							<td width="45px"><label>Estado: </label></td>
							<td><?= Html::textInput(null, $extras['datos']['est_nom'], $atributosComunes + ['style' => 'width:100px;']) ?></td>
							
						</tr>
					</table>
					<table>	
						<tr>
							<td width="85px"><label>Impreso: </label></td>
							<td></td>
							<td><?= DatePicker::widget(['name' => 'a', 'dateFormat' => 'dd/MM/yyyy', 'value' => $extras['datos']['fchimpreso'], 'options' => $atributosComunes + ['style' => 'width:70px;']]); ?></td>
							<td width="15px"></td>
							<td width="75px"><label>Plazo:</label></td>
							<td><?= DatePicker::widget(['name' => 'a', 'dateFormat' => 'dd/MM/yyyy', 'value' => $extras['datos']['fchplazo'], 'options' => $atributosComunes + ['style' => 'width:70px;']]) ?></td>
						</tr>
					</table>
					
					<table>	
						<tr>
							<td width="85px"><label>Nominal: </label></td>
							<td><?= Html::textInput(null, $extras['datos']['nominal'], $atributosComunes + ['style' => 'width:70px;text-align:right']) ?></td>
							<td width="15px"></td>
							<td width="75px"><label>Accesorios: </label></td>
							<td><?= Html::textInput(null, $extras['datos']['accesor'], $atributosComunes + ['style' => 'width:70px;text-align:right'])?></td>
							<td width="15px"></td>
							<td width="45px"><label>Multa: </label></td>
							<td><?= Html::textInput(null, $extras['datos']['multa'], $atributosComunes + ['style' => 'width:68px;text-align:right']) ?></td>
						</tr>
					</table>
				</div>
				
				<?php
				$tab = Yii::$app->request->post('tab', 1);
				?>
				
				<div style="margin-top:15px;">
					<table border="0" width="100%">
						<tr>
							<td>
								<?= 								
								Tabs::widget([
										'items' => [
											['label' => 'Períodos', 'content' => $this->render('_periodos', ['extras' => $extras, 'pjaxErrorSummary' => '#pjaxPrincipal', 'tab' => 1]), 'active' => $tab == 1, 'options' => ['class' => 'tabItem']],
											['label' => 'Entregas', 'content' => $this->render('_entregas', ['extras' => $extras, 'pjaxErrorSummary' => '#pjaxPrincipal', 'tab' => 2]), 'active' => $tab == 2, 'options' => ['class' => 'tabItem']],
											['label' => 'Etapas', 'content' => $this->render('_etapas', ['extras' => $extras, 'pjaxErrorSummary' => '#pjaxPrincipal', 'tab' => 3]), 'active' => $tab == 3, 'options' => ['class' => 'tabItem']],
											['label' => 'Compás de Espera', 'content' => $this->render('_espera', ['extras' => $extras, 'pjaxErrorSummary' => '#pjaxPrincipal', 'tab' => 4]), 'active' => $tab == 4, 'options' => ['class' => 'tabItem']]
										]
								]);
								?>
							</td>
						</tr>
					</table>
				</div>
			</td>
			<td width="15%" valign="top">
				<?= $this->render('menu_derecho_seguimiento', ['extras' => $extras])?>
			</td>
		</tr>
	</table>
</div>

<?php
$form = ActiveForm::begin();

echo $form->errorSummary($extras['model']);

ActiveForm::end();
Pjax::end();
?>




