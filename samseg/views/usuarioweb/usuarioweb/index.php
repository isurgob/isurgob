<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use yii\helpers\BaseUrl;

/* @var $this yii\web\View */

$title = 'Usuarios Web';
$this->params['breadcrumbs'][] = $title;

$imprimir = isset(Yii::$app->session['imprimir']) ? Yii::$app->session->getFlash('imprimir') : '0';
$id = isset(Yii::$app->session['id']) ? Yii::$app->session->getFlash('id') : 0;

?>
<div class="intima-view">

	<h1><?= Html::encode($title) ?></h1>

	<!-- INICIO Div Mensaje -->
	<div id="usuarioweb_divMensaje" class="mensaje alert-success" style="display:none">
	</div>
	<!-- FIN Div Mensaje -->

	<!-- INICIO Div Filtros -->
	<div class="form" style="padding: 8px">
		<label><u>Filtro</u></label>
		<table border = '0' >

			<tr>
				<td><label>Nombre:</label></td>
				<td>
					<?=
						Html::input('text','txFiltroNombre','',[
							'id'=>'usuarioweb_txFiltroNombre',
							'class'=>'form-control',
							'style'=>'width:120px;text-transform:uppercase',
							'maxlength' => 11,
						]);
					?>
				</td>
				<td width="10px"></td>
				<td><label>Objeto:</label></td>
				<td>
					<?php Pjax::begin([ 'id' => 'usuarioweb_pjaxInputObjeto', 'enableReplaceState' => false, 'enablePushState' => false, 'timeout' => 100000 ]); ?>

						<?=
							Html::input('text','txFiltroObjeto', $obj_id,[
								'id'=>'usuarioweb_txFiltroObjeto',
								'class'=>'form-control',
								'style'=>'width:90px;',
								'maxlength' => 8,
								'onchange'	=> 'f_cambiaInputObjeto()',
							]);
						?>
						<?=
							Html::button('<i class="glyphicon glyphicon-search"></i>',[
		                        'id'        => 'usuarioweb_btObjeto',
		                        'class' 	=> 'bt-buscar',
		                        'onclick'   => 'f_btObjeto()',
		                    ]);
		                ?>
						<?=
							Html::input( 'text', 'txFiltroObjetoNom', $obj_nom,[
								'id'=>'usuarioweb_txFiltroObjetoNom',
								'class'=>'form-control solo-lectura',
								'style'=>'width:225px;',
								'tabIndex' => '-1',
							]);
						?>

					<?php Pjax::end(); ?>
				</td>
				<td colspan='2'></td>
				<td>
					<?=
						Html::checkbox('ckFiltroBaja', null,[
							'id'		=> 'usuarioweb_ckFiltroBaja',
							'label'		=> 'Ver Bajas'
						]);
					?>
				</td>
			</tr>

			<tr>
				<td width='50px'><label>Documento:</label></td>
				<td>
					<?=
						Html::input('text','txFiltroDoc','',[
							'id'=>'usuarioweb_txFiltroDoc',
							'class'=>'form-control',
							'style'=>'width:90px;',
							'onkeypress' => 'return justNumbers( event )',
							'maxlength' => 12,
						]);
					?>
				</td>
				<td></td>
				<td ><label>Apellido y Nombre:</label></td>
				<td colspan="2">
					<?=
						Html::input('text','txFiltroApeNom','', [
							'id'		=>'usuarioweb_txFiltroApeNom',
							'class'		=>'form-control',
							'style'		=>'width:350px;text-transform:uppercase',
							'maxlength' => 50,
						]);
					?>
				</td>
				<td width="20px"></td>
				<td align='left' rowspan='2'>
					<?=
						Html::Button('Buscar', [
							'id' 		=> 'btBuscarUsrWeb',
							'class' 	=> 'btn btn-primary',
							'onclick'	=> 'f_buscarUsuario()',
						]);
					?>
				</td>
			</tr>
		</table>
	</div>
	<!-- FIN Div Filtros -->

	<!-- INICIO Div Mensaje -->
	<div id="usuarioweb_errorSummary" class="error-summary" style="display:none; margin-right: 0px; margin-top: 8px">
	</div>
	<!-- FIN Div Mensaje -->

	<div style="margin:10px 0px; width='98%'">

		<table width='100%'>
			<tr>
				<td><h1 id='h1titulo'>Resultado</h1></td>
				<td align='right'>
					<?php
						Modal::begin([
				            'id' => 'Exportar',
							'header' => '<h2>Exportar Datos</h2>',
							'toggleButton' => [
				                'label' => 'Exportar',
				                'class' => 'btn btn-success',
				            ],
				            'closeButton' => [
				              'label' => '<b>X</b>',
				              'class' => 'btn btn-danger btn-sm pull-right',
				            ]
				        ]);

				        	echo $this->render('//site/exportar',['titulo'=>'Listado de Usuarios Web','grilla'=>'Exportar']);

				        Modal::end();

			        ?>
					<?= Html::a('Nuevo',['view','consulta'=>0],['class' => 'btn btn-success', 'id' => 'btNuevoUsrWeb']) ?>
				</td>
			</tr>
		</table>
		<?php

			Pjax::begin(['id' => 'PjaxGrilla', 'enablePushState' => false, 'enableReplaceState' => false, 'timeout' => 100000 ]);

				echo GridView::widget([
					'id' => 'GrillaUsuariosWeb',
					'headerRowOptions' => ['class' => 'grilla'],
					'rowOptions' => ['class' => 'grilla'],
					'dataProvider' => $dataProvider,
					'summaryOptions' => ['class' => 'hidden'],
					'columns' => [

							['attribute'=>'usr_id','label' => 'Cod.', 'contentOptions'=>['style'=>'text-align:center','width'=>'1px']],
							['attribute'=>'nombre','label' => 'Nombre', 'contentOptions'=>['style'=>'text-align:left','width'=>'1px']],
							['attribute'=>'apenom','label' => 'Apellido y Nombre', 'contentOptions'=>['style'=>'text-align:left','width'=>'200px']],
							['attribute'=>'ndoc','label' => 'Documento', 'contentOptions'=>['style'=>'text-align:left','width'=>'1px']],
							['attribute'=>'obj_id','label' => 'Objeto', 'contentOptions'=>['style'=>'text-align:center','width'=>'1px']],
							['attribute'=>'acc_contrib','label' => 'Acc.Cont', 'contentOptions'=>['style'=>'text-align:center;width:1px']],
							['attribute'=>'acc_dj','label' => 'Acc.DJ', 'contentOptions'=>['style'=>'text-align:center;width:1px']],
							['attribute'=>'acc_proveedor','label' => 'Acc.Prov', 'contentOptions'=>['style'=>'text-align:center;width:1px']],
							['attribute'=>'acc_agrete','label' => 'Acc.Ag', 'contentOptions'=>['style'=>'text-align:center;width:1px']],
							['attribute'=>'acc_escribano','label' => 'Acc.Escrib', 'contentOptions'=>['style'=>'text-align:center;width:1px']],
							['attribute'=>'est','label' => 'Est.', 'contentOptions'=>['style'=>'text-align:center;width:1px']],
							[
								'class' => 'yii\grid\ActionColumn',
								'contentOptions'=>['style'=>'width:60px','align'=>'left'],
								'template' =>'{view}{update}{delete}{activar}{blanqueoclave}',
								'buttons'=>[
									'view' =>  function($url, $model, $key)
												{
													return Html::a('<span class="glyphicon glyphicon-eye-open"></span>&nbsp;', ['view','id' => $model['usr_id'], 'consulta' => '1']);
												},

									'update' => function($url, $model, $key)
												{
													if ($model['est'] != 'B')
														return Html::a('<span class="glyphicon glyphicon-pencil"></span>&nbsp;', ['view','id' => $model['usr_id'], 'consulta' => '3']);
												},
									'delete' => function($url, $model, $key)
												{
													if ($model['est'] != 'B')
														return Html::a('<span class="glyphicon glyphicon-trash"></span>&nbsp;', ['view','id' => $model['usr_id'], 'consulta' => '2']);
												},
									'activar' => function($url, $model, $key)
												{
													if ($model['est'] == 'B')
														return Html::a('<span class="glyphicon glyphicon-ok"></span>&nbsp;', ['view','id' => $model['usr_id'], 'consulta' => '4']);
												},
									'blanqueoclave' => function($url, $model, $key)
												{
													if ($model['est'] != 'B')
														return Html::a('<span class="glyphicon glyphicon-lock"></span>', ['limpiarclave','usr' => $model['usr_id']]);
												},
								]
							]
						],
				]);

			Pjax::end();
		?>

	</div>
</div>

<?php
	Pjax::begin([ 'id' => 'usuarioweb_pjaxObjeto', 'enableReplaceState' => false, 'enablePushState' => false, 'timeout' => 100000 ]);

		Modal::begin([
			'id' => 'usuarioweb_modalObjeto',
			'size' => 'modal-normal',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Objeto</h2>',
			'closeButton' => [
			  'label' => '<b>X</b>',
			  'class' => 'btn btn-danger btn-sm pull-right',
			 ],
		]);

			echo $this->render('//objeto/objetobuscarav', [
				'id' 		=> 'objeto',
				'txCod' 	=> 'usuarioweb_txFiltroObjeto',
				'txNom' 	=> 'usuarioweb_txFiltroObjetoNom',
				'selectorModal'	=> '#usuarioweb_modalObjeto',
				'tobjeto' 	=> 3,
			]);

		Modal::end();

	Pjax::end();
?>

<?php if( $mensaje != '' ){ ?>

	<script>

		mostrarMensaje( "<?= $mensaje ?>", "#usuarioweb_divMensaje" );
	</script>

<?php } ?>

<?php if( $error != '' ){ ?>

	<script>

		mostrarErrores( ["<?= $error ?>"], "#usuarioweb_divMensaje" );
	</script>

<?php } ?>

<script>
<?php if ($imprimir == '1') { ?>
	window.open("<?= BaseUrl::toRoute(['comprobanteusrweb','id' => $id]); ?>",'_blank');
<?php } ?>

function f_buscarUsuario(){
	var ver_baja = ( $('#usuarioweb_ckFiltroBaja').is(':checked') ? 1 : 0 );
	
	$.pjax.reload({
		container	: '#PjaxGrilla',
		type		: 'GET',
		replace		: false,
		push		: false,
		timeout 	: 100000,
		data: {
			usr_nom		: $('#usuarioweb_txFiltroNombre').val(),
			usr_doc		: $('#usuarioweb_txFiltroDoc').val(),
			usr_apenom	: $('#usuarioweb_txFiltroApeNom').val(),
			usr_obj		: $('#usuarioweb_txFiltroObjeto').val(),
			baja		: ver_baja
		},
	});
}

function f_cambiaInputObjeto(){

	var obj_id = $( "#usuarioweb_txFiltroObjeto" ).val();

	$.pjax.reload({
		container	: '#usuarioweb_pjaxInputObjeto',
		type		: 'GET',
		replace		: false,
		push		: false,
		timeout 	: 100000,
		data: {
			"obj_id"	: obj_id,
		},
	});
}

function f_btObjeto(){

	$( "#usuarioweb_modalObjeto" ).modal( "toggle" );
}

</script>
