<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use \yii\widgets\Pjax;
use app\utils\db\utb;
use yii\helpers\BaseUrl;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$title = 'Configuración DDJJ';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = $title;

?>

<style type="text/css">

td {

	padding-top: 6px;

}

.form {

	margin-top:10px;
	padding-bottom: 8px;

}

</style>

<div id='config_ddjj'>

	<div class="pull-left">

		<h1><?= Html::encode($title) ?></h1>

	</div>

	<div class="pull-right">

		<?php

			if( $action == 1 ){

				echo Html::a( 'Nuevo', [ 'create' ], [
					'class'	=> 'btn btn-success',
				]);

			}

		?>

	</div>

	<div class="clearfix"></div>

	<div id="config_ddjj_divMensaje" class="mensaje alert-success" style="display:none">
	</div>

	<?php Pjax::begin([ 'id' => 'config_ddjj_pjaxDatos', 'enableReplaceState' => false, 'enablePushState' => false, 'timeout' => 100000 ]); ?>

	<?php
		
		$form = ActiveForm::begin([
			'id' => 'config_ddjj_form',
		]);

	?>

	<?= Html::input( 'hidden', 'txAction', $action ); ?>

	<div id="config_ddjj_divFiltro" class="form<?= ( $action == 0 ? " disabled" : "" ) ?>" >

		<div style="width: 500px">

			<h3><label>Tributos:</label></h3>

			<?php

				Pjax::begin();

					echo GridView::widget([
						'id' => 'GrillaLiqddjj',
						'headerRowOptions' => ['class' => 'grilla'],
						'rowOptions' => function( $model ){

				            return [
				                'onclick'   => 'f_cambiaTributo(' . $model['trib_id'] . ', 1 )',
				                'class'     => 'grilla',
				            ];

				        },
						'dataProvider' => $dpTributos,
						'summaryOptions' => ['class' => 'hidden'],
						'columns' => [
							['attribute'=>'trib_id','header' => 'Tributo', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
							['attribute'=>'nombre','header' => 'Nombre', 'contentOptions'=>['style'=>'text-align:left','width'=>'300px']],
							[
								'class' => 'yii\grid\ActionColumn',
								'contentOptions'=>['style'=>'width:6px'],
								'template' =>'{update}',
								'buttons'=>[

									'update' =>  function($url, $model, $key) use ( $action )
												{
													if( $action == 1 ){

														return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model['trib_id']], [
															'class'=>'bt-buscar-label',
															'style'=>'color:#337ab7',
														]);

													} else {

														return false;
													}

												},

								]
							],
						],
					]);

				Pjax::end();

			?>

		</div>

	</div>

	<div class="form">

		<table>
			<tr>
				<td><label>Tributo:</label></td>
				<td>
					<?=
						Html::activeDropDownList( $model, 'trib_id', $tributos, [
							'id'	=> 'config_ddjj_dlTributo',
							'class'	=> 'form-control' . ( $action == 0 ? '' : ' solo-lectura' ),
							'style' => 'width: 400px',
							'tabIndex'	=> ( $action == 0 ? '0' : '-1' ),
						]);
					?>
				</td>
			</tr>

			<tr>
				<td width="120px"><label>Ítem Comp. Multa:</label></td>
				<td>
					<?=
						Html::activeDropDownList( $model, 'itemcompensamulta', $itemMulta, [
							'class'	=> 'form-control' . ( $action == 1 ? ' solo-lectura' : '' ),
							'style' => 'width: 200px',
							'tabIndex'	=> ( $action == 1 ? '-1' : '0' ),
						]);
					?>
				</td>
			</tr>
			<tr>
				<td width="120px"><label>Ítem Básico:</label></td>
				<td>
					<?=
						Html::activeDropDownList( $model, 'itembasico', $itemBasico, [
							'class'	=> 'form-control' . ( $action == 1 ? ' solo-lectura' : '' ),
							'style' => 'width: 200px',
							'tabIndex'	=> ( $action == 1 ? '-1' : '0' ),
						]);
					?>
				</td>
			</tr>
			<tr>
				<td><label>Ítem Retención:</label></td>
				<td>
					<?=
						Html::activeDropDownList( $model, 'itemrete', $itemRete, [
							'class'	=> 'form-control' . ( $action == 1 ? ' solo-lectura' : '' ),
							'style' => 'width: 200px',
							'tabIndex'	=> ( $action == 1 ? '-1' : '0' ),
						]);
					?>
				</td>
			</tr>
			<tr>
				<td><label>Ítem Bonificación:</label></td>
				<td>
					<?=
						Html::activeDropDownList( $model, 'itembonif', $itemBonif, [
							'class'	=> 'form-control' . ( $action == 1 ? ' solo-lectura' : '' ),
							'style' => 'width: 200px',
							'tabIndex'	=> ( $action == 1 ? '-1' : '0' ),
						]);
					?>
				</td>
			</tr>
			<tr>
				<td><label>Ítem Saldo:</label></td>
				<td>
					<?=
						Html::activeDropDownList( $model, 'itemsaldo', $itemSaldo, [
							'class'	=> 'form-control' . ( $action == 1 ? ' solo-lectura' : '' ),
							'style' => 'width: 200px',
							'tabIndex'	=> ( $action == 1 ? '-1' : '0' ),
						]);
					?>
				</td>
			</tr>
		</table>

		<table>
			<tr>
				<td>
					<?= Html::activeCheckbox( $model, 'djanual', [
							'label' 	=> 'Genera DJ Anual',
							'uncheck' 	=> 0,
							'disabled'	=> ( $action == 1 ? true : false ),
						]);
					?>
				</td>
			</tr>
		</table>

		<table>
			<tr>
				<td width="120px"><label>Último Nro. Versión:</label></td>
				<td>
					<?= Html::activeInput( 'text', $model, 'nversion', [
							'class'	=> 'form-control' . ( $action == 1 ? ' solo-lectura' : '' ),
							'style' => 'width: 60px;',
							'onkeypress' => 'return justNumbers( event )',
							'maxlength' => 3,
							'tabIndex'	=> ( $action == 1 ? '-1' : '0' ),
						]);
					?>
				</td>
			</tr>
			<tr>
				<td colspan="2"><label>Convenio Multilateral:</label></td>
				<td>
					<?= Html::activeCheckbox( $model, 'cm_dj', [
							'label' => 'Puede Presentar DJ.',
							'uncheck' => 0,
							'disabled'	=> ( $action == 1 ? true : false ),
						]);
					?>
				</td>
				<td width="10px"></td>
				<td>
					<?= Html::activeCheckbox( $model, 'cm_min', [
							'label' => 'Se aplica el mínimo.',
							'uncheck' => 0,
							'disabled'	=> ( $action == 1 ? true : false ),
						]);
					?>
				</td>
			</tr>
			<tr>
				<td colspan="2"><label>Acuerdo Interjurisdiccional:</label></td>
				<td>
					<?= Html::activeCheckbox( $model, 'ai_dj', [
							'label' => 'Puede Presentar DJ.',
							'uncheck' => 0,
							'disabled'	=> ( $action == 1 ? true : false ),
						]);
					?>
				</td>
				<td width="10px"></td>
				<td>
					<?= Html::activeCheckbox( $model, 'ai_min', [
							'label' => 'Se aplica el mínimo.',
							'uncheck' => 0,
							'disabled'	=> ( $action == 1 ? true : false ),
						]);
					?>
				</td>
			</tr>

			<tr>
				<td colspan="2" rowspan="3"><label>Permitir:</label></td>
				<td>
					<?= Html::activeCheckbox( $model, 'perm_retemanual', [
							'label' => 'Cargar Retenciones manualmente.',
							'uncheck' => 0,
							'disabled'	=> ( $action == 1 ? true : false ),
						]);
					?>
				</td>
				<td width="10px"></td>
				<td>
					<?= Html::activeCheckbox( $model, 'perm_djfalta', [
							'label' => 'Ingresar DJ habiendo DJ anteriores faltantes.',
							'uncheck' => 0,
							'disabled'	=> ( $action == 1 ? true : false ),
						]);
					?>
				</td>
			</tr>
			<tr>
				<td>
					<?= Html::activeCheckbox( $model, 'perm_saldo', [
							'label' => 'Ingresar saldo a favor.',
							'uncheck' => 0,
							'disabled'	=> ( $action == 1 ? true : false ),
						]);
					?>
				</td>
				<td width="10px"></td>
				<td>
					<?= Html::activeCheckbox( $model, 'perm_bonif', [
							'label' => 'Ingresar bonificaciones manualmente.',
							'uncheck' => 0,
							'disabled'	=> ( $action == 1 ? true : false ),
						]);
					?>
				</td>
			</tr>
			<tr>
				<td colspan='3'> 
					<label> Tipos de Comercios permitidos: </label> 
					<?= Html::activeInput( 'text', $model, 'perm_tipos', [
							'class'	=> 'form-control' . ( $action == 1 ? ' solo-lectura' : '' ),
							'style' => 'width: 100%',
							'maxlength' => 255,
							'tabIndex'	=> ( $action == 1 ? '-1' : '0' ),
						]);
					?>
				</td>
			</tr>
		</table>

	</div>

    <?php if ( $action != 1 ){ ?>

		<div style="margin-top: 8px">

			<?= Html::submitButton('Aceptar',[
					'class' => 'btn btn-success',
				]);
			?>

			&nbsp;&nbsp;

			<?= Html::a('Cancelar', ['index'], [
					'class' => 'btn btn-primary',
					'data-pjax' => '0',
				]);
			?>
		</div>

	<?php } ?>


	<?php ActiveForm::end(); ?>

	<?php Pjax::end(); ?>

	<?php if( $mensaje != '' ){ ?>

		<script>mostrarMensaje( "<?= $mensaje ?>", "#config_ddjj_divMensaje" );</script>

	<?php } ?>

	<?php

		echo $form->errorSummary( $model, [

			'style' => 'margin-top: 8px',
		]);

	?>

</div>

<script>

function f_cambiaTributo( trib_id ){

	$.pjax.reload({
		container 	: "#config_ddjj_pjaxDatos",
		type 		: "GET",
		replace 	: false,
		push		: false,
		timeout 	: 100000,
		data:{
			"trib_id"	: trib_id,
		},
	});

}
</script>
