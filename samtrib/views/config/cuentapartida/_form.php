<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use \yii\bootstrap\Modal;
use \yii\widgets\Pjax;
use app\utils\db\utb;
use yii\jui\DatePicker;
use yii\bootstrap\Tabs;
use yii\web\Session;
use yii\bootstrap\Alert;
use app\utils\db\Fecha;
use yii\data\ArrayDataProvider;

/**
 * Forma que se encarga de mostrar un buscador para "Partidas Presupuestarias".
 *
 * Recibo:
 *
 * 		+ $model => Modelo de "Partida Presupuestaria"
 */

$title = 'Partidas Presupuestarias';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = $title;
?>

<style>

#div_grilla
{
	margin-right: 10px;
	padding: 5px 0px;
}
</style>

<div class="cuentapartida-form">

<h1><?=  $title ?></h1>

<div class="form-panel">
<table>
	<tr>
		<td><h3><label for="filtros">Filtro:</label></h3></td>
		<td width="20px"></td>
		<td><label for="subgrupo">SubGrupo:</label></td>
		<td><?= Html::dropDownList('dlSubGrupo',0,utb::getAux('cuenta_partida','subgrupo','nombre',2,'nivel = 2', '', true),[
					'id' => 'cuentapartida_dlSubGrupo',
					'class' => 'form-control',
					'style' => 'width:200px',
					'onchange' => 'cambia_subGrupo( $(this).val() )',
					]); ?>
		</td>
		<td width="20px"></td>
		<td><label for="rubro">Rubro:</label></td>
		<td>
			<?php

				Pjax::begin(['id' => 'PjaxRubro', 'enablePushState' => false, 'enableReplaceState' => false]);

					$padre = Yii::$app->request->get('subgrupo',-1);

					echo Html::dropDownList('dlRubro',0,utb::getAux('cuenta_partida','rubro','nombre',2,'nivel = 3 AND subgrupo = ' . $padre, '', true),[
						'id' => 'cuentapartida_dlRubro',
						'class' => 'form-control',
						'style' => 'width:230px',
						'onchange' => 'cambiaGrupo()',
						]);

				Pjax::end();

			?>
		</td>
		<td width="20px"></td>
		<td align="right" width="100px"><?= Html::button('Buscar',[
					'id' => 'cuentapartida_btBuscar',
					'class' => 'btn btn-primary',
					'onclick' => 'btBuscarClick()']); ?>
		</td>
	</tr>
</table>


<!-- INICIO Grilla Partida Presupuestarias -->
<div id="div_grilla">

<?php

	//INICIO Pjax para grilla de partidas presupuestarias
	Pjax::begin(['id' => 'PjaxGrillaPartidaPresupuestaria', 'enablePushState' => false, 'enableReplaceState' => false]);

		$subgrupo = Yii::$app->request->get('g_subgrupo',0);
		$rubro = Yii::$app->request->get('g_rubro',0);

		//por defecto se traen todos los registros
		$cargar = 1;//Yii::$app->request->get('g_cargar',0);

		$dataProvider = new ArrayDataProvider( [] );
		$modelos= $model->getPartidasPresupuestaria( $subgrupo, $rubro );

		if ($cargar == 1)
		{
			//Genero el dataProvider para la grilla
			$dataProvider = new ArrayDataProvider([
								'allModels' => $modelos,
								'key' => 'part_id',
								'pagination' => false,
								'sort' => [
									'attributes' => [
										'part_id',
										'formato',
										'nombre',
									]
								]
								]);

		}

		echo GridView::widget([
			'id' => 'cuentapartida_GrillaPartidaPresupuestaria',
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => ['class' => 'grilla'],
			'dataProvider' => $dataProvider,
			'summaryOptions' => ['class' => 'hidden'],
			'columns' => [
					['attribute'=>'part_id','label' => 'Cod.', 'contentOptions'=>['style'=>'text-align:center','width'=>'1px']],
					['attribute'=>'formato','label' => 'Formato', 'contentOptions'=>['style'=>'text-align:left','width'=>'1px']],
					['attribute'=>'nombre','label' => 'Nombre', 'contentOptions'=>['style'=>'text-align:left','width'=>'250px']],
					['attribute'=>'nivel','label' => 'Nivel', 'contentOptions'=>['style'=>'text-align:center','width'=>'1px']],
					['attribute'=>'modif','label' => 'Modificación', 'contentOptions'=>['style'=>'text-align:right','width'=>'100px']],
					[
						'class' => 'yii\grid\ActionColumn',
						'contentOptions'=>['style'=>'width:50px'],
						'template' =>(utb::getExisteProceso(3040) ? '{viewpartida}' : '').(utb::getExisteProceso(3041) ? '{update}{eliminar}{insert}' : ''),
						'buttons'=>[

							'viewpartida' =>  function($url, $model, $key)
										{
											return Html::a('<span class="glyphicon glyphicon-eye-open" style="margin: 0px 1px"></span>', $url);
										},

							'update' => function($url, $model, $key)
										{
											return Html::a('<span class="glyphicon glyphicon-pencil" style="margin: 0px 1px"></span>', $url);
										},

							'eliminar' => function($url, $model, $key)
										{
											return Html::button('<span class="glyphicon glyphicon-trash" style="margin: 0px 1px"></span>', [
														'onclick' => 'eliminaPartida('.$model["part_id"].')',
														'class'=>'bt-buscar-label',
														'style'=>'color:#337ab7',
														]);
										},

							'insert' => function($url, $model, $key)
										{
											if ( $model['nivel'] < 7 && $model['posee'] == 0 )
												return Html::a('<span class="glyphicon glyphicon-plus" style="margin: 0px 1px"></span>', $url);
										},
						]
					]
	        	],
		]);

	Pjax::end();
	//FIN Pjax para grilla de partidas presupuestarias
?>

</div>
<!-- FIN Grilla Partida Presupuestarias -->



</div>

<!-- INICIO Mensajes de error -->
<div style="margin-top: 8px;margin-right: 15px" >

<table width="100%">
	<tr>
		<td width="100%">

			<?php

				$mensaje = $alert;

				if($mensaje != "")
				{

			    	Alert::begin([
			    		'id' => 'AlertaFormFiscalizacion',
						'options' => [
			        	'class' => ($m == 2 ? 'alert-danger' : 'alert-success'),
			        	'style' => $mensaje !== '' ? 'display:block' : 'display:none'
			    		],
					]);

					echo $mensaje;

					Alert::end();

					echo "<script>window.setTimeout(function() { $('#AlertaFormFiscalizacion').alert('close'); }, 5000)</script>";
				 }

			?>
		</td>
	</tr>
</table>

</div>
<!-- FIN Mensajes de error -->

<?php

$form = ActiveForm::begin();

	echo $form->errorSummary( $model, ['id' => 'cuentapartida_errorSummary', 'style' => 'margin-top: 8px; margin-right: 15px']);

	$model->clearErrors();


ActiveForm::end();

?>

</div>

<?php

	Pjax::begin(['id' => 'PjaxEliminaPartida', 'enablePushState' => false, 'enableReplaceState' => false]);

		$cuenta_id = Yii::$app->request->get('eliminaPartidaID',0);

		//INICIO Modal Eliminar Partida Presupuestaria
		Modal::begin([
				'id' => 'ModalEliminarPartida',
				'size' => 'modal-sm',
				'header' => '<h4><b>Eliminar Partida</b></h4>',
				'closeButton' => [
					'label' => '<b>X</b>',
					'class' => 'btn btn-danger btn-sm pull-right',
					'id' => 'btCancelarModalEliminarPartida'
					],
			]);

			?>

			<div class="text-center">
				<label>Se eliminará la partida <?= $cuenta_id ?>. ¿Desea continuar?</label>
				<br />
				<br />
				<?= Html::button('Aceptar',[
						'id' => 'cuentapartida_btCancelar',
						'class'=>'btn btn-success',
						'onclick' => 'eliminarPartida('.$cuenta_id.')']);
				?>
				<?= Html::button('Cancelar',[
						'id' => 'cuentapartida_btCancelar',
						'class'=>'btn btn-primary',
						'onclick' => '$("#ModalEliminarPartida").modal("hide")',
					]);
				?>
			</div>

			<?php



		Modal::end();
		//FIN Modal Eliminar Partida Presupuestaria

	Pjax::end();

	//INICIO Bloque de código que realiza la baja lógica de una cuenta.
	Pjax::begin(['id' => 'PjaxEliminaPartidaDato']);

		$cuenta_id = Yii::$app->request->get('eliminaPartida_id',0);

		if ( $cuenta_id != 0 )
		{
			echo '<script>$("#ModalEliminarPartida").modal("hide")</script>';

			if ( $model->eliminarPartida( $cuenta_id ) )
			{
				echo Html::a('pepe',['index','men' => 1],[
						'style' => 'display:block',
						'id' => 'cuentapartida_index',
					]);
				echo '<script>$("#cuentapartida_index").click();</script>';
			} else
			{
 				$errores = $model->getErrors( null );

				echo '<script> var array = new Array();</script>';

				foreach( $errores[0] as $array )
				{
					echo '<script>array.push("'.$array.'");</script>';
				}

				echo '<script>mostrarErrores(array, "#cuentapartida_errorSummary" );</script>';
				//echo '<script>$("#div_grilla").css("display","none");</script>';
			}
		}

	Pjax::end();
	//FIN Bloque de código que realiza la baja lógica de una cuenta.
?>

<script>
function eliminarPartida(id)
{
	$.pjax.reload({
		container:"#PjaxEliminaPartidaDato",
		type:"GET",
		replace:false,
		push:false,
		data:{
			eliminaPartida_id: id,
		}});
}

function btBuscarClick()
{
	var datos = {},
		subgrupo = $("#cuentapartida_dlSubGrupo").val(),
		rubro = $("#cuentapartida_dlRubro").val(),
		error = new Array();

	datos.g_cargar = 1;
	datos.g_subgrupo = subgrupo;
	datos.g_rubro = rubro;

	$("#cuentapartida_errorSummary").css("display","none");

	//$("#div_grilla").css("display","block");

	$.pjax.reload({
		container:"#PjaxGrillaPartidaPresupuestaria",
		method:"GET",
		replace:false,
		push:false,
		data:datos,
	});

}

function eliminaPartida(id)
{
	$.pjax.reload({
		container:"#PjaxEliminaPartida",
		type:"GET",
		replace:false,
		push:false,
		data:{
			eliminaPartidaID:id,
		}
	});

}

$("#PjaxEliminaPartida").on("pjax:end",function() {

	$("#ModalEliminarPartida").modal("show");
});

function cambia_subGrupo( subgrupo )
{

	$.pjax.reload({
		container:"#PjaxRubro",
		method:"GET",
		replace:false,
		push:false,
		data:{
			"subgrupo": subgrupo,
		},
	});

	$( "#PjaxRubro" ).on( "pjax:end", function() {

		$.pjax.reload( "#PjaxGrillaPartidaPresupuestaria" );

		//$( "#div_grilla" ).css( "display", "none" );

		$( "#PjaxRubro" ).off( "pjax:end" );
	});

}

function cambiaGrupo()
{
	$.pjax.reload( "#PjaxGrillaPartidaPresupuestaria" );

	//$( "#div_grilla" ).css( "display", "none" );
}

$( document ).ready( function() {

	var subgrupo = $( "#cuentapartida_dlSubGrupo" ).val();

	cambia_subGrupo( subgrupo );
});
</script>
