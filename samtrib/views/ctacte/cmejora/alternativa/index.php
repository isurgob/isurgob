<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;
use yii\widgets\ActiveForm;
use app\utils\db\utb;
use yii\helpers\BaseUrl;


$this->params['breadcrumbs'][] = ['label' => 'Plan de Mejora Nro. ' . $model->plan_id, 'url' => ['//ctacte/mejoraplan/index', 'id' => $model->plan_id]];
$this->params['breadcrumbs'][] = ['label' => 'Alternativas'];
?>
<div class="rodado-view">

    <h1 id='h1titulo'>Alternativas de Contribuci&oacute;n por Mejoras</h1>

    <table border='0' width="100%">
		<tr>
			<td valign="top">
				<?php 
					if (!isset($mensaje) || trim($mensaje) == '') $mensaje = trim( Yii::$app->request->get('m_text', '') );
					else $mensaje = trim($mensaje);

					Alert::begin([
						'id' => 'alertaAltMej',
						'options' => [
						'class' => ( ( intval( Yii::$app->request->get('m', 0) ) !== 10) ? 'alert-success' : 'alert-info'),
						'style' => ($mensaje != '' ? 'display:block; width:640px;' : 'display:none') 
						],
					]);

					if ($mensaje != '') echo $mensaje;
					
					Alert::end();
					
					if ($mensaje != '') echo "<script>window.setTimeout(function() { $('#alertaAltMej').alert('close'); }, 5000)</script>";
					
				?>
			</td>
		</tr>
    </table>
	
	<div class="form" style="padding:10px;">
		<table width = '100%'  >
			<tr>
				<td width='10%'> <label> Plan Nro.: </label> </td>
				<td width='15%'>
					<?=
						Html::input( 'text', 'mejplan_plan_id', $modelPlan->plan_id, [
							'id' => 'mejplan_plan_id',
							'class' => 'form-control solo-lectura',
							'style' => 'width:90%; text-align:center',
							'tabIndex' => '-1'
						]);
					?>
				</td>
				<td width='5%'> <label> Obra: </label> </td>
				<td>
					<?=
						Html::input( 'text', 'mejplan_obra_nom', $modelPlan->obra_nom, [
							'id' => 'mejplan_obra_nom',
							'class' => 'form-control solo-lectura',
							'style' => 'width:100%;',
							'tabIndex' => '-1'
						]);
					?>
				</td>
			</tr>
			<tr>
				<td> <label> Objeto: </label> </td>
				<td>
					<?=
						Html::input( 'text', 'mejplan_obj_id', $modelPlan->obj_id, [
							'id' => 'mejplan_obj_id',
							'class' => 'form-control solo-lectura',
							'style' => 'width:90%; text-align:center',
							'tabIndex' => '-1'
						]);
					?>
				</td>
				<td colspan='2' >	
					<?=
						Html::input( 'text', 'mejplan_obj_nom', $modelPlan->obj_nom, [
							'id' => 'mejplan_obj_nom',
							'class' => 'form-control solo-lectura',
							'style' => 'width:100%;',
							'tabIndex' => '-1'
						]);
					?>
				</td>
			</tr>
		</table>
	</div>
	
	<?php 

	$form = ActiveForm::begin([
		'id' => 'form_mejora_alternativa',
		'options' => [
			'enctype' => 'multipart/form-data',
		]
	]);
	
		echo Html::activeInput( 'hidden', $model, 'plan_id');

	?>
	
		<div class="form" style="padding:10px; margin-top:10px">
			<table width = '100%'  >
				<tr>
					<td width='11%'> <label> <u> Alternativas </u> </label> </td>
					<td align='center'> <label> <u> 1 </u> </label> </td>
					<td align='center'> <label> <u> 2 </u> </label> </td>
					<td align='center'> <label> <u> 3 </u> </label> </td>
				</tr>
				<tr>
					<td> <label> T&iacute;tulo: </label> </td>
					<td>
						<?=
							Html::activeInput( 'text', $model, 'titulo1', [
								'id' => 'titulo1',
								'class' => 'form-control',
								'style' => 'width:98%',
								'maxlength' => 50
							]);
						?>
					</td>
					<td>
						<?=
							Html::activeInput( 'text', $model, 'titulo2', [
								'id' => 'titulo2',
								'class' => 'form-control',
								'style' => 'width:98%',
								'maxlength' => 50
							]);
						?>
					</td>
					<td>
						<?=
							Html::activeInput( 'text', $model, 'titulo3', [
								'id' => 'titulo3',
								'class' => 'form-control',
								'style' => 'width:98%',
								'maxlength' => 50
							]);
						?>
					</td>
				</tr>
				<tr>
					<td> <label> Tipo Plan: </label> </td>
					<td>
						<?php 
						$cond = "cod in (select tplan from plan_config_usuario where usr_id=".Yii::$app->user->id.")";
						$cond .= " and cod in (select tplan from plan_config_trib where trib_id=3)";
						$cond .= " and (VigenciaHasta is null or to_char(VigenciaHasta,'YYYY/mm/dd')>='".date('Y/m/d')."')";
						
							echo Html::activeDropDownList( $model, 'tplan1', utb::getAux('plan_config', 'cod', 'nombre', 1, $cond), [
								'id' => 'tplan1',
								'class' => 'form-control',
								'style' => 'width:98%',
								'onchange' => 'TipoPlanSelec( 1 );'
							]);
						?>
					</td>
					<td>
						<?php 
							echo Html::activeDropDownList( $model, 'tplan2', utb::getAux('plan_config', 'cod', 'nombre', 1, $cond), [
								'id' => 'tplan2',
								'class' => 'form-control',
								'style' => 'width:98%',
								'onchange' => 'TipoPlanSelec( 2 );'
							]);
						?>
					</td>
					<td>
						<?php 
							echo Html::activeDropDownList( $model, 'tplan3', utb::getAux('plan_config', 'cod', 'nombre', 1, $cond), [
								'id' => 'tplan3',
								'class' => 'form-control',
								'style' => 'width:98%',
								'onchange' => 'TipoPlanSelec( 3 );'
							]);
						?>
					</td>
				</tr>
				<tr>
					<td> <label> Cuotas: </label> </td>
					<td>
						<?=
							Html::activeInput( 'text', $model, 'cuotas1', [
								'id' => 'cuotas4',
								'class' => 'form-control',
								'style' => 'width:98%',
								'onkeypress'    => 'return justNumbers( event )',
								'maxlength' => 3
							]);
						?>
					</td>
					<td>
						<?=
							Html::activeInput( 'text', $model, 'cuotas2', [
								'id' => 'cuotas2',
								'class' => 'form-control',
								'style' => 'width:98%',
								'onkeypress'    => 'return justNumbers( event )',
								'maxlength' => 3
							]);
						?>
					</td>
					<td>
						<?=
							Html::activeInput( 'text', $model, 'cuotas3', [
								'id' => 'cuotas3',
								'class' => 'form-control',
								'style' => 'width:98%',
								'onkeypress'    => 'return justNumbers( event )',
								'maxlength' => 3
							]);
						?>
					</td>
				</tr>
				<tr>
					<td> <label> Entrega: </label> </td>
					<td>
						<?=
							Html::activeInput( 'text', $model, 'entrega1', [
								'id' => 'entrega1',
								'class' => 'form-control',
								'style' => 'width:98%; text-align:right',
								'onkeypress' => 'return justDecimal( $(this).val(), event )',
								'maxlength' => 12
							]);
						?>
					</td>
					<td>
						<?=
							Html::activeInput( 'text', $model, 'entrega2', [
								'id' => 'entrega2',
								'class' => 'form-control',
								'style' => 'width:98%; text-align:right',
								'onkeypress' => 'return justDecimal( $(this).val(), event )',
								'maxlength' => 12
							]);
						?>
					</td>
					<td>
						<?=
							Html::activeInput( 'text', $model, 'entrega3', [
								'id' => 'entrega3',
								'class' => 'form-control',
								'style' => 'width:98%; text-align:right',
								'onkeypress' => 'return justDecimal( $(this).val(), event )',
								'maxlength' => 12
							]);
						?>
					</td>
				</tr>
				<tr>
					<td> <label> Monto Cuota: </label> </td>
					<td>
						<?=
							Html::activeInput( 'text', $model, 'montocuo1', [
								'id' => 'montocuo1',
								'class' => 'form-control solo-lectura',
								'style' => 'width:98%; text-align:right',
								'tabIndex' => '-1'
							]);
						?>
					</td>
					<td>
						<?=
							Html::activeInput( 'text', $model, 'montocuo2', [
								'id' => 'montocuo2',
								'class' => 'form-control solo-lectura',
								'style' => 'width:98%; text-align:right',
								'tabIndex' => '-1'
							]);
						?>
					</td>
					<td>
						<?=
							Html::activeInput( 'text', $model, 'montocuo3', [
								'id' => 'montocuo3',
								'class' => 'form-control solo-lectura',
								'style' => 'width:98%; text-align:right',
								'tabIndex' => '-1'
							]);
						?>
					</td>
				</tr>
				<tr>
					<td> <label> Financia: </label> </td>
					<td>
						<?=
							Html::activeInput( 'text', $model, 'financia1', [
								'id' => 'financia1',
								'class' => 'form-control solo-lectura',
								'style' => 'width:98%; text-align:right',
								'tabIndex' => '-1'
							]);
						?>
					</td>
					<td>
						<?=
							Html::activeInput( 'text', $model, 'financia2', [
								'id' => 'financia2',
								'class' => 'form-control solo-lectura',
								'style' => 'width:98%; text-align:right',
								'tabIndex' => '-1'
							]);
						?>
					</td>
					<td>
						<?=
							Html::activeInput( 'text', $model, 'financia3', [
								'id' => 'financia3',
								'class' => 'form-control solo-lectura',
								'style' => 'width:98%; text-align:right',
								'tabIndex' => '-1'
							]);
						?>
					</td>
				</tr>
				<tr>
					<td> <label> Sellado: </label> </td>
					<td>
						<?=
							Html::activeInput( 'text', $model, 'sellado1', [
								'id' => 'sellado1',
								'class' => 'form-control solo-lectura',
								'style' => 'width:98%; text-align:right',
								'tabIndex' => '-1'
							]);
						?>
					</td>
					<td>
						<?=
							Html::activeInput( 'text', $model, 'sellado2', [
								'id' => 'sellado2',
								'class' => 'form-control solo-lectura',
								'style' => 'width:98%; text-align:right',
								'tabIndex' => '-1'
							]);
						?>
					</td>
					<td>
						<?=
							Html::activeInput( 'text', $model, 'sellado3', [
								'id' => 'sellado3',
								'class' => 'form-control solo-lectura',
								'style' => 'width:98%; text-align:right',
								'tabIndex' => '-1'
							]);
						?>
					</td>
				</tr>
			</table>
			
		</div>
		
		<div class="text-center" style="margin-top: 8px">

		<?php

			echo Html::submitbutton( 'Generar', [
					'id' => 'btGenerar',
					'class' => 'btn btn-success',
				]);

			echo '&nbsp;&nbsp;';
			
			echo Html::a( 'Borrar Previo', ['borrar', 'plan' => $model->plan_id], [
				'id' => 'btBorrar',
				'class' => 'btn btn-primary'
			]);
			
			echo '&nbsp;&nbsp;';
			
			echo Html::a( 'Imprimir', [ 'imprimir', 'plan' => $model->plan_id ],[
					'id' => 'btImprimir',
					'class' => 'btn btn-success',
					'target' => '_black'
				]);

		?>

		</div>
		
	<?php

	//INICIO Div Errores
	echo $form->errorSummary( $model, [
		'id' => "form_errorSummary",
		'class' => "error-summary",
		'style' => "margin-top: 8px",
	]);
	//FIN Div Errores

	ActiveForm::end();

	?>	

</div>

<script>

TipoPlanSelec( 0 );

function TipoPlanSelec( alt ) {

	var tplan = ( isNaN( parseInt( $( "#tplan" + alt ).val() ) ) ? 0 : parseInt( $( "#tplan" + alt ).val() ) );
	
	$.get( "<?= BaseUrl::toRoute('//ctacte/mejoraplan/datostplan');?>&tplan=" + tplan
	).success(function(data){
		datos = jQuery.parseJSON(data); 
		
		$("#entrega" + alt ).attr('disabled', (datos.anticipomanual != 1));
		if ( $("#entrega" + alt ).attr('disabled') ) $("#entrega" + alt ).val("");
		
	});
}

</script>