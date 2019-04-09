<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use \yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;
use app\utils\db\utb;
use yii\helpers\Url;

/* @var $this yii\web\View */

if ($consulta == 0)
		$title = 'Nuevo Usuarios Web';
elseif ($consulta == 2)
		$title = 'Eliminar Usuarios Web';
elseif ($consulta == 3)
		$title = 'Editar Usuarios Web';
elseif ($consulta == 4)
		$title = 'Activar Usuarios Web';
else
	$title = 'Consultar Usuarios Web';

$this->params['breadcrumbs'][] = ['label' => 'Usuarios Web', 'url' => Yii::$app->param->sis_url.'usuarioweb/usuarioweb/index'];
$this->params['breadcrumbs'][] = $title;

$form = ActiveForm::begin([ 'id' => 'formUsrWeb' ]);

?>
<div class="intima-view">

	<div class="pull-left">
		<h1><?= Html::encode($title) ?></h1>
	</div>

	<div class="pull-right" style="padding-right: 15px">
		<?php
			if( $consulta == 1 ){
				echo Html::a( 'Volver', [ 'index' ], [
					'class'	=> 'btn btn-primary',
				]);
			}
		?>
	</div>

	<div class="clearfix"></div>

	<div class="form-panel">
		<table border="0">
			<tr>
				<td><label>Id:</label></td>
				<td>
					<?=
						Html::input('text','txUsrId',$model->usr_id,[
							'id'=>'txUsrId',
							'class'=>'form-control solo-lectura',
							'style'=>'width:40px;text-align:center'
						]);
					?>
				</td>
				<td width="10px"></td>
				<td><label>Nombre:</label>
					<?=
						Html::input('text','txUsrNom',$model->nombre,[
							'id'	=>'txUsrNom',
							'class'	=>'form-control' . ( $consulta == 0 ? '' : ' solo-lectura' ),
							'style'	=>'width:150px;',
							'maxlength' => 15,
							'tabIndex'	=> ( $consulta == 0 ? '0' : '-1' ),
						]);
					?>
				</td>
			</tr>

			<tr>
				<td colspan="2"><label>Persona:</label></td>
				<td colspan="3" width="400px">
					<?php Pjax::begin([ 'id' => 'usuarioweb_form_pjaxInputObjeto', 'enableReplaceState' => false, 'enablePushState' => false ]); ?>

						<?=
							Html::input( 'text','txUsrObjeto', $model->obj_id,[
								'id'=>'txUsrObjeto',
								'class'=>'form-control' . ( $consulta == 0 ? '' : ' solo-lectura' ),
								'style'=>'width:20%;text-align:center',
								'maxlength' => 8,
								'onchange'	=> 'f_cambiaInputObjeto()',
								'tabIndex'	=> ( $consulta == 0 ? '0' : '-1' ),
							]);
						?>
						<?=
							Html::button('<i class="glyphicon glyphicon-search"></i>',[
		                        'id'        => 'usuarioweb_btObjeto',
		                        'class' 	=> 'bt-buscar',
		                        'onclick'   => 'f_btObjeto()',
								'disabled'	=> ( $consulta == 0 ? false : true ),
		                    ]);
		                ?>
						<?=
							Html::input( 'text', 'txUsrObjetoNom', $model->obj_nom,[
								'id'=>'txUsrObjetoNom',
								'class'=>'form-control solo-lectura',
								'style'=>'width:70%;',
								'tabIndex' => '-1',
							]);
						?>

					<?php Pjax::end(); ?>

				</td>
			</tr>
			<tr>
				<td colspan="2"><label>Mail:</label></td>
				<td colspan="2">
					<?=
						Html::input('text','txMail',$model->mail,[
							'id'=>'txMail',
							'class'=>'form-control' . ( in_array( $consulta, [ 0, 3 ] ) ? '' : ' solo-lectura' ),
							'style'=>'width:99%;',
							'maxlength' => 40,
							'tabIndex' => ( in_array( $consulta, [ 0, 3 ] ) ? '0' : '-1' ),
						]);
					?>
				</td>
			</tr>
		</table>

		<div class="separador-horizontal" style="margin: 12px 0px"></div>

		<label><u>Accesos:</u></label> <br>

		<table border="0" width='99%' cellpadding='5'>
			<tr>
				<td width="20%">
					<?=
						Html::checkbox('ckUsrWebAccContrib', $model->acc_contrib == 'S',[
							'id'		=> 'ckUsrWebAccContrib',
							'label'		=> 'Contribuyente',
							'disabled'	=> ( in_array( $consulta, [ 0, 3 ] ) ? false : true ),
						]);
					?>
				</td>
				<td width="20%">
					<?=
						Html::checkbox('ckUsrWebAccDJ', $model->acc_dj == 'S',[
							'id'		=> 'ckUsrWebAccDJ',
							'label'		=> 'Declaración Jurada',
							'disabled'	=> ( in_array( $consulta, [ 0, 3 ] ) ? false : true ),
						]);
					?>
				</td>
				<td width="20%">
					<?=
						Html::checkbox('ckUsrWebAccAgRete', $model->acc_agrete == 'S',[
							'id'		=> 'ckUsrWebAccAgRete',
							'label'		=> 'Agente de Retención',
							'disabled'	=> ( in_array( $consulta, [ 0, 3 ] ) ? false : true ),
						]);
					?>
				</td>
				<td width="20%">
					<?=
						Html::checkbox( 'ckUsrWebAccProv', $model->acc_proveedor == 'S', [
							'id'		=>'ckUsrWebAccProv',
							'label'		=>'Proveedor',
							'disabled'	=> ( in_array( $consulta, [ 0, 3 ] ) ? false : true ),
						]);
					?>
				</td>
				<td width="20%">
					<?=
						Html::checkbox( 'ckUsrWebAccEscrib', $model->acc_escribano == 'S', [
							'id'		=>'ckUsrWebAccEscrib',
							'label'		=>'Escribano',
							'disabled'	=> ( in_array( $consulta, [ 0, 3 ] ) ? false : true ),
						]);
					?>
				</td>

			</tr>
		</table>

		<div id="DivContribuyentesAsociados" style= "<?= $model->acc_dj == 'S' ? "display:block" : "display:none" ?>" >
			<hr align="center" width="100%" ></hr>

			<div class="pull-left">
				<label><u>Contribuyentes Asociados:</u></label> <br>
			</div>

			<div class="pull-right">
				<?=
					Html::button( 'Nuevo Contribuyente', [
						'class' 	=> 'btn btn-success' . ( in_array( $consulta, [ 0, 3 ] ) ? '' : ' hidden' ),
						'id' 		=> 'btBuscarComer',
						'onclick'	=> 'f_agregarContribuyente()',
					]);
				?>
			</div>
			
			<div class="pull-right">
				<?=
					Html::button( 'Auto Asociarse', [
						'class' 	=> 'btn btn-success' . ( in_array( $consulta, [ 0, 3 ] ) ? '' : ' hidden' ),
						'id' 		=> 'btMismoContrib',
						'onclick'	=> 'f_manejoContribuyentes( "' . $model->obj_id . '", 0 )',
						'style' => 'margin:0 5px',
						'title' => 'Vincularse a sí mismo como Contribuyente Asociado'
					]);
				?>
			</div>

			<div class="clearfix"></div>

			<div style="width:100%;margin-bottom:5px;" align="right">

			<?php
				Modal::begin([
					'id' => 'modalBusquedaAvanzadaPersona',
					'header' => '<h2 style="text-align:left">Búsqueda de Contribuyente</h2>',
					'closeButton' => [
						'label' => '<b>&times;</b>',
						'class' => 'btn btn-danger btn-sm pull-right',
					],
					'size' => 'modal-lg',
				]);

					echo "<div style='text-align:left'>";
						echo $this->render('/objeto/objetobuscarav',[
								'id' => 'comer',
								'txCod' => 'txContribuyente',
								'txNom' => 'txContribuyenteNom',
								'tobjeto' => 3,
								'selectorModal' => '#modalBusquedaAvanzadaPersona'
							]);
					echo "</div>";

				Modal::end();
			?>
			</div>

			<input type="text" name="txContribuyente" id ="txContribuyente" value="" style="display:none">
			<input type="text" name="txContribuyenteNom" id ="txContribuyenteNom" value="" style="display:none">

			<?php
				Pjax::begin(['id' => 'usuarioweb_pjaxContribuyentes']);

					echo '<input type="text" name="arrayComerAsoc" id="arrayComerAsoc" value="'.urlencode(serialize($model->comer_asoc)).'" style="display:none">';

					echo GridView::widget([
						'id' => 'grillaContribuyentes',
						'headerRowOptions' => ['class' => 'grilla'],
						'rowOptions' => ['class' => 'grilla'],
						'dataProvider' => $dpContribuyentes,
						'summaryOptions' => ['class' => 'hidden'],
						'columns' => [
								['attribute'=>'obj_id','label' => 'Objeto', 'contentOptions'=>['style'=>'text-align:center','width'=>'30px']],
								['attribute'=>'nombre','label' => 'Nombre', 'contentOptions'=>['style'=>'text-align:left','width'=>'150px']],
								['attribute'=>'cuit','label' => 'CUIT', 'contentOptions'=>['style'=>'text-align:center','width'=>'60px']],
								['attribute'=>'ib','label' => 'IB', 'contentOptions'=>['style'=>'text-align:center','width'=>'60px']],
								['attribute'=>'est_ib_nom','label' => 'Est. IB', 'contentOptions'=>['style'=>'text-align:center','width'=>'60px']],
								['attribute'=>'tipoliq_nom','label' => 'Liquidación', 'contentOptions'=>['style'=>'text-align:left','width'=>'70px']],
								['attribute'=>'modif','label' => 'Modificacion', 'contentOptions'=>['style'=>'text-align:left','width'=>'180px']],
								[
									'class' => 'yii\grid\ActionColumn',
									'contentOptions'=>['style'=>'width:10px','align'=>'center'],
									'template' => ( in_array( $consulta, [ 0, 3 ] ) ? '{delete}' : '' ),
									'buttons'=>[
										'delete' => function($url, $model, $key)
													{
														return Html::a('<span class="glyphicon glyphicon-trash"></span>&nbsp;', '#', [
															'onclick' => "f_manejoContribuyentes('" . $model['obj_id'] . "', 2 )",
														]);
													},
									]
								]
						],
					]);
				Pjax::end();
			?>
		</div>
		<?php if ($consulta != 0) { ?>
			<div style="margin-top:20px;margin-right:10px" align="right">

				<label>Modificación:</label>
				<?=
					Html::input('text','txUsrModif',utb::getFormatoModif($model->usrmod,$model->fchmod),[
						'id'=>'txUsrModif',
						'class'=>'form-control solo-lectura',
						'style'=>'width:200px;'
					]);
				?>
			</div>
		<?php } ?>

	</div>

	<div style="margin-top:20px">
		<?php
			if ($consulta != 1) {
				echo Html::Button($consulta == 2 ? 'Eliminar' : 'Aceptar',[
						'class' => $consulta == 2 ? 'btn btn-danger' : 'btn btn-success', 'id' => 'btAceptarUsrWeb'
					]);

				echo "&nbsp;&nbsp;";

				echo Html::a('Cancelar',['index'],['class' => 'btn btn-primary', 'id' => 'btCancelarUsrWeb']);
			}
		?>
	</div>

	<div style="display:none;margin-top:10px;" class="error-summary">
	</div>


</div>
<?php ActiveForm::end(); ?>

<?php
	Pjax::begin([ 'id' => 'usuarioweb_form_pjaxObjeto', 'enableReplaceState' => false, 'enablePushState' => false ]);

		Modal::begin([
			'id' => 'usuarioweb_form_modalObjeto',
			'size' => 'modal-normal',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Objeto</h2>',
			'closeButton' => [
			  'label' => '<b>X</b>',
			  'class' => 'btn btn-danger btn-sm pull-right',
			 ],
		]);

			echo $this->render('//objeto/objetobuscarav', [
				'id' 		=> 'objeto',
				'txCod' 	=> 'txUsrObjeto',
				'txNom' 	=> 'txUsrObjetoNom',
				'selectorModal'	=> '#usuarioweb_form_modalObjeto',
				'tobjeto' 	=> 3,
			]);

		Modal::end();

	Pjax::end();
?>


<script>

function f_btObjeto(){

	$( "#usuarioweb_form_modalObjeto" ).modal( "toggle" );
}

function f_cambiaInputObjeto(){

	var obj_id = $( "#txUsrObjeto" ).val();

	$.pjax.reload({
		container	: '#usuarioweb_form_pjaxInputObjeto',
		type		: 'GET',
		replace		: false,
		push		: false,
		data: {
			"obj_id"	: obj_id,
		},
	});
}

function f_agregarContribuyente(){

	$( "#modalBusquedaAvanzadaPersona" ).modal( "toggle" );
}

$('#modalBusquedaAvanzadaPersona').on( 'hide.bs.modal', function() {

	var obj_id	= $( "#txContribuyente" ).val(),
		action	= 0;

	$( "#txContribuyente" ).val( "" );

	if( obj_id != '' ){

		f_manejoContribuyentes( obj_id, action );
	}


});

function f_manejoContribuyentes( obj_id, action ){

	$.pjax.reload({
		container	: '#usuarioweb_pjaxContribuyentes',
		type		: "GET",
		replace		: false,
		push		: false,
		data: {
			"obj_id"	: obj_id,
			"action"	: action,
		},
	});

}

$("#ckUsrWebAccDJ").change(function(){
	if ($("#ckUsrWebAccDJ").prop('checked'))
		$("#DivContribuyentesAsociados").css("display","block");
	else
		$("#DivContribuyentesAsociados").css("display","none");
});

$("#btAceptarUsrWeb").click(function(){
	var url = $("#formUsrWeb").attr("action");

	$.ajax({
           type: "POST",
           url: url,
           data: $("#formUsrWeb").serialize(), // Adjuntar los campos del formulario enviado.
           success: function(data)
           {
               $(".error-summary").css("display","block");
			   $(".error-summary").html(data); // Mostrar la respuestas del script PHP.
           }
         });

    return false; // Evitar ejecutar el submit del formulario.
});
</script>
