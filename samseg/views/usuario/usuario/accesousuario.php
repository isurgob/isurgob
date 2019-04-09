<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use app\utils\db\Fecha;
use app\utils\db\utb;
use yii\data\ArrayDataProvider;
use yii\jui\DatePicker;
use yii\bootstrap\Alert;

/**
 * Forma que se dibuja cuando se edita un usuario.
 * Recibo:
 * 			=> $model -> Modelo de Usuario
 *
 *
 */

 /**
 * @param $consulta es una variable que:
 * 		=> $consulta == 1 => El formulario se dibuja en el index
 * 		=> $consulta == 0 => El formulario se dibuja en el create
 * 		=> $consulta == 3 => El formulario se dibuja en el update
 * 		=> $consulta == 2 => El formulario se dibuja en el delete
 */

$title = 'Accesos del Usuario';

$this->params['breadcrumbs'][] = ['label' => 'Seguridad', 'url' => ['view']];
$this->params['breadcrumbs'][] = $title;

?>

<style>

.form-panel {

	padding-bottom: 8px;
}

.div_grilla {

	padding-right: 10px;
	padding-bottom: 8px;
}
</style>

<table width="100%">
	<tr>
		<td align="left"><h1><?= Html::encode($title) ?></h1></td>
	</tr>
</table>


<!-- INICIO DIV Seguridad -->
<div id="div_seguridad_accesoUsuario_editausuario" style="display:block;margin:0px">

<div class="form-panel">

<table width="100%">
	<tr>
		<td align="left"><h3><strong><label>Usuario:</label></strong></h3></td>
	</tr>
</table>


<table>
	<tr>
		<td width="20px"><label>Cod.</label></td>
		<td width="40px"><?= Html::input('text','Usuario[usr_id]',$model->usr_id,['id'=>'seguridad_accesoUsuario_Usuario[usr_id]','class'=>'form-control solo-lectura','style'=>'width:40px;text-align:center','tabindex'=>-1]); ?></td>
		<td width="30px"></td>
		<td width="60px"><label>Nombre:</label></td>
		<td><?= Html::input('text','Usuario[apenom]',$model->nombre,['id'=>'seguridad_accesoUsuario_Usuario[apenom]','class'=>'form-control solo-lectura','style'=>'width:100px;text-align:left']); ?></td>
		<td width="30px"></td>
		<td ><label>Nombre y Apellido:</label></td>
		<td><?= Html::input('text','Usuario[nombre]',$model->apenom,['id'=>'seguridad_accesoUsuario_Usuario[nombre]','class'=>'form-control solo-lectura','style'=>'width:200px;text-align:left']); ?></td>
		<td width="20px"></td>
		<td>
			<?php

				if ( $activaQuitarUltAcc )
					echo Html::button('Quitar último Acceso',[
						'id'=>'seguridad_accesoUsuario_btUltimoAcceso',
						'class'=>'btn btn-primary',
						'onclick' => '$.pjax.reload({container:"#PjaxQuitarUltimoAcceso",method:"POST",data:{quitar:1}})']);
			?>
		</td>
	</tr>
</table>

</div>

<div class="form-panel">
	<table>
		<tr>
			<td><h4><label>Fecha de Consulta:</label></h4></td>
			<td width="20px"></td>
			<td><label>Desde:</label></td>
			<td>
				<?= DatePicker::widget(['id'=>'seguridad_accesoUsuario_fchDesde',
										'name'=>'fchDesde',
										'dateFormat' => 'dd/MM/yyyy',
										'value' => date('Y-m-d', strtotime('-1 month')), //Resto 1 mes a la fecha actual
										'options' => ['class'=>'form-control','style'=>'width:80px;text-align:center']]); ?>
			</td>
			<td width="20px"></td>
			<td><label>Hasta:</label></td>
			<td>
				<?= DatePicker::widget(['id'=>'seguridad_accesoUsuario_fchHasta',
										'name'=>'fchHasta',
										'dateFormat' => 'dd/MM/yyyy',
										'value' => Fecha::usuarioToDatePicker(Fecha::getDiaActual()),
										'options' => ['class'=>'form-control','style'=>'width:80px;text-align:center']]); ?>
			</td>
			<td width="50px"></td>
			<td><?= Html::button('Consultar',[
									'id'=>'seguridad_accesoUsuario_btConsultar',
									'class'=>'btn btn-primary',
									'onclick' => 'consultarAccesos()']); ?></td>
		</tr>
	</table>
</div>


<div id="div_accesos" style="margin-right: 8px; display: none">

<?php

Pjax::begin(['id'=>'PjaxConsultaAcceso']);

$acceso = Yii::$app->request->post('acceso',0);
$desde = Yii::$app->request->post('desde','');
$hasta = Yii::$app->request->post('hasta','');

if ( $acceso == 1 && $desde != '' && $hasta != '' )
{
    $dataProviderAccesos = new ArrayDataProvider(
    [
        'allModels' => $model->getAccesos($model->usr_id,$desde,$hasta),
        'pagination' => [
                    'pageSize' => 1000,
        ],
    ]);

    $dataProviderAccesosFallidos = new ArrayDataProvider(
    [
        'allModels' => $model->getAccesosFallidos($model->usr_id,$desde,$hasta),
        'pagination' => [
                    'pageSize' => 1000,
        ],
    ]);

} else
{
    $dataProviderAccesos = new ArrayDataProvider( [] );

    $dataProviderAccesosFallidos = new ArrayDataProvider( [] );
}
?>

<!-- INICIO DIV Accesos -->
<div id="div_acceso" style="width:49%;height:100%;margin-right: 0px" class="form pull-left" >

    <h4><label>Accesos:</label></h4>

    	<!-- INICIO Grilla Accesos -->
    	<div class="div_grilla">

    	<?php

    	 	echo GridView::widget([
    			'id' => 'GrillaUsuarioAccesos',
    			'headerRowOptions' => ['class' => 'grilla'],
    			'rowOptions' => ['class' => 'grilla'],
    			'dataProvider' => $dataProviderAccesos,
    			'summaryOptions' => ['class' => 'hidden'],
    			'columns' => [
    					['attribute'=>'fchingreso','header' => 'Ingreso', 'contentOptions'=>['style'=>'text-align:center','width'=>'120px']],
    					['attribute'=>'fchsalida','header' => 'Salida', 'contentOptions'=>['style'=>'text-align:center','width'=>'120px']],
    					['attribute'=>'ip','header' => 'IP', 'contentOptions'=>['style'=>'text-align:center','width'=>'10px']],
    					['attribute'=>'nombre','header' => 'Modo', 'contentOptions'=>['style'=>'text-align:center','width'=>'10px']],
    	        ],
    		]);

    	?>
    	</div>
    	<!-- FIN Grilla EMdp -->

</div>
<!-- FIN DIV Accesos -->

<!-- INICIO DIV Accesos Fallidos -->
<div id="div_acceso_fal" style="width:49%;height:100%;margin-right: 8px" class="form-panel pull-right">

    <h4><label>Accesos Fallidos:</label></h4>

    	<!-- INICIO Grilla Accesos Fallidos -->
    	<div class="div_grilla">
    	<?php

    	 	echo GridView::widget([
    			'id' => 'GrillaUsuarioAccesosFallidos',
    			'headerRowOptions' => ['class' => 'grilla'],
    			'rowOptions' => ['class' => 'grilla'],
    			'dataProvider' => $dataProviderAccesosFallidos,
    			'summaryOptions' => ['class' => 'hidden'],
    			'columns' => [
    					['attribute'=>'fchintento','header' => 'Fecha', 'contentOptions'=>['style'=>'text-align:center','width'=>'120px']],
    					['attribute'=>'ip','header' => 'IP', 'contentOptions'=>['style'=>'text-align:center','width'=>'100px']],
    	        ],
    		]);

    	?>

    	</div>
    	<!-- FIN Grilla Accesos Fallidos -->

</div>
<!-- FIN DIV Accesos Fallidos -->



<?php

Pjax::end();

?>

</div>

<!-- Inicio Mensajes Error -->
<div id="accesoUsuario_errorSummary" class="error-summary" style="display:none;margin-right: 15px">

</div>
<!-- Fin Mensajes Error -->
<?php

Pjax::begin(['id' => 'PjaxQuitarUltimoAcceso']);

$quitar = Yii::$app->request->post('quitar',0);

if ( $quitar != 0 )
{

	$model->limpiarUltimoAcceso( $model->usr_id );

	?>

	<script>
	$("#PjaxQuitarUltimoAcceso").on("pjax:end",function()
	{
		$.pjax.reload({
			container:"#errorFormUsuarioAcceso",
			method:"POST",
			data:{
				mensaje:"Se quitó correctamente el último acceso del usuario.",
				m:1,
			},
		});

		$("#errorFormUsuarioAcceso").on("pjax:end", function() {

			consultarAccesos();
			$("#errorFormUsuarioAcceso").off("pjax:end")
		});

		$("#PjaxQuitarUltimoAcceso").off("pjax:end");
	});
	</script>

	<?php
}

Pjax::end();
?>
<!-- INICIO Mensajes de error -->
<table width="100%">
	<tr>
		<td width="100%">

			<?php

			Pjax::begin(['id'=>'errorFormUsuarioAcceso']);

			$mensaje = Yii::$app->request->post('mensaje','');
			$m = Yii::$app->request->post('m',2);

			if (isset($_POST['mensaje'])) $mensaje = $_POST['mensaje'];

			if($mensaje != "")
			{

		    	Alert::begin([
		    		'id' => 'AlertaFormUsuarioAcceso',
					'options' => [
		        	'class' => 'alert-success',
		        	'style' => $mensaje !== '' ? 'display:block' : 'display:none'
		    		],
				]);

				echo $mensaje;

				Alert::end();

				echo "<script>window.setTimeout(function() { $('#AlertaFormUsuarioAcceso').alert('close'); }, 5000)</script>";
			 }

			 Pjax::end();

			?>
		</td>
	</tr>
</table>
<!-- FIN Mensajes de error -->

</div>

<script>
function cambiarCheck()
{
	$("#seguridad_ckTesoreriaTodos").prop('checked','');
}

function cambiarChecksGrillaUsuarioTesoreria()
{
	var checks = $('#GrillaUsuarioTesoreria input[type="checkbox"]');

	if ($("#seguridad_ckTesoreriaTodos").is(":checked"))
	{
		checks.each(function() {

			checks.prop('checked','checked');

		});
	} else
	{
		checks.each(function() {

			checks.prop('checked','');

		});
	}
}

function consultarAccesos()
{
	var desde = $("#seguridad_accesoUsuario_fchDesde").val(),
        hasta = $("#seguridad_accesoUsuario_fchHasta").val(),
        error = new Array();

	if (desde == '')
		error.push( "Ingrese una fecha desde." );

	if (hasta == '')
		error.push( "Ingrese una fecha hasta." );

	//if (ValidarRangoFechaJs(desde,hasta) == 1)
	//	error.push( "Rango de fecha mal ingresado." );

	if ( error.length == 0 )
    {
        // Oculto el div de errores
        $( "#accesoUsuario_errorSummary" ).css( "display", "none" );

		$.pjax.reload({
			container:"#PjaxConsultaAcceso",
			method:"POST",
			data:{
				acceso:1,
				desde:desde,
				hasta:hasta,
			}
		});

        //Mostrar el div de accesos
        $( "#div_accesos" ).css( "display", "block" );

	} else
    {
		mostrarErrores( error, "#accesoUsuario_errorSummary" );

        //Ocultar el div de Accesos
        $( "#div_accesos" ).css( "display", "none" );

    }
}

function actualizaAltura()
{
	var alt_acceso =       parseFloat( $("#div_acceso").css("height") );
	var alt_acceso_fal =   parseFloat( $("#div_acceso_fal").css("height") );

	if ( alt_acceso > alt_acceso_fal )
		$("#div_acceso_fal").css("height", alt_acceso );
	else
		$("#div_acceso").css("height", alt_acceso_fal );
}

$( document ).ready(function() {

	$( "#PjaxConsultaAcceso" ).on("pjax:end", function() {
        actualizaAltura();
    });

    //Ocultar el div de Accesos
    $( "#div_accesos" ).css( "display", "none" );
});
</script>
