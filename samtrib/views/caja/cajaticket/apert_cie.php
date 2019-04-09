<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\Session;
use app\models\caja\CajaTicket;
use \yii\widgets\Pjax;
use yii\jui\DatePicker;
use app\utils\db\utb;
use app\utils\db\Fecha;
use yii\widgets\ActiveForm;
use yii\bootstrap\Alert;
/*
 *
 * View que se encarga de listar las cajas para apertura/cierre a cargo de un supervisor.
 *
 */


$title = 'Apertura/Cierre';
$this->params['breadcrumbs'][] = 'Supervisión';
$this->params['breadcrumbs'][] = $title;


?>
<table width="100%">
	<tr>
		<td><h1><?= Html::encode($title) ?></h1></td>
	</tr>
</table>

<table>
	<tr>
		<td valign="top">

<?php

$array = ['0' => 'Apertura', '1' => 'Cierre'];

$teso_id = 2;
$tipo = 0;
$fecha = $dia;
$confirmar = 0;
$codigos = []; //Voy a almacenar los códigos que se deberán actualizar

Pjax::begin(['id'=>'error']);

	$m = Yii::$app->request->post('m',$m);
	$mensaje = Yii::$app->request->post('mensaje',$mensaje);
    $error = Yii::$app->request->post('error',$error);

	if ( $mensaje != '' )
	{
		Alert::begin([
			'id' => 'AlertaApertCie',
			'options' => [
			'class' => ( $m == 1 ? 'alert-success' : 'alert-danger'),
			],
		]);

			echo $mensaje;

		Alert::end();

		echo "<script>window.setTimeout(function() { $('#AlertaApertCie').alert('close'); }, 5000)</script>";

	}

Pjax::end();


$form = ActiveForm::begin([
		'id'=>'form-apertCie',
		'action' => ['apertcie'],
		]);

?>

<div>

<div class="form-panel" style="padding-bottom:4px;padding-right:8px;margin-right: 8px">
<table>
	<tr>
		<td width="150px">
			<?= Html::dropDownList('apertcie-tipo', null, $array, [
					'id'=>'apertcie-tipo',
					'style'=>'width:130px',
					'class' => 'form-control'
				]);
			?>
		</td>
		<td width="150px">
			<?php

				$cond = "est='A' And teso_id > 0 AND teso_id IN (SELECT teso_id FROM sam.sis_usuario_tesoreria WHERE usr_id=".Yii::$app->user->id.")";

				echo Html::dropDownList('apertcie-tesoreria', null, utb::getAux('caja_tesoreria','teso_id','nombre',0,$cond ), [
						'id'=>'apertcie-tesoreria',
						'style'=>'width:130px',
						'class' => 'form-control'
					]);
			?>
		</td>
		<td width="100px">
			<?= DatePicker::widget([
					'id' => 'apertcie-fchpago',
					'name' => 'apertcie-fchpago',
					'dateFormat' => 'dd/MM/yyyy',
					'options' => [
						'class' => 'form-control',
						'style' => 'width:80px;text-align:center',
					],
					'value' => Fecha::usuarioToDatePicker($dia),
				]);
			?>
		</td>
		<td width="30px"></td>
		<td><?= Html::Button('Buscar',['class'=>'btn btn-primary','onclick'=>'buscar()']); ?></td>
		<td width="30px"></td>
		<td><?= Html::Button('Confirmar',['id' => 'apertcie_btConfirmar','class'=>'btn btn-primary', 'onclick' => 'enviarDatos()']); ?></td>
	</tr>
</table>
</div>

<div style="padding-bottom:4px;padding-right:8px">
<?php

	Pjax::begin(['id'=>'datosGrilla']);

		if (isset($_POST['tesoreria'])) $teso_id = $_POST['tesoreria'];
		if (isset($_POST['tipo'])) $tipo = $_POST['tipo'];
		if (isset($_POST['fecha'])) $fecha = $_POST['fecha'];

		echo GridView::widget([
				'id' => 'GrillaDetalle',
				'dataProvider' => ($tipo == 0 ? $model->CargarDetalleAperturaCaja($teso_id) : $model->CargarDetalleCierreCaja($teso_id,$fecha)),
				'summaryOptions' => ['class' => 'hidden'],
				'columns' => [
		            [
						'content'=> function($model, $key, $index, $column) {
			            				return Html::checkbox('listadoCobranza-ckTicket[]',false, [
													'value'=>$model['caja_id'],
													'id' => 'listadoCobranza-ckTicket'.$model['caja_id'],
													'onchange' => 'cambiaCheckGrilla()',
										]);
									},
						'contentOptions'=>['style'=>'width:4px'],
					],
		            ['attribute'=>'caja_id','header' => 'Caja'],
					['attribute'=>'nombre','header' => 'Nombre'],
					['attribute'=>'cajero','contentOptions'=>['align'=>'center', 'style'=>'width:260px;text-align:left'],'header' => 'Cajero'],

		        ],
		    ]);

		//Si teso_id = 2, se debe ocultar la grilla, de lo contrario, se muestra.
		if ($teso_id == 2)
		{
			?>
				<script>$("#GrillaDetalle").css('display','none');</script>
			<?php
		} else {
			?>
				<script>$("#GrillaDetalle").css('display','block');</script>
			<?php
		}

	Pjax::end();


ActiveForm::end();


if (count($error) > 0)
{
	?>
	<div id="apertcie_errorSummary" class="error-summary" style="display:none;margin-top: 8px">

	<script>

		var error = new Array();

		<?php

			foreach ($error as $mens)
				echo 'error.push( "'.$mens.'")';
		?>

		mostrarErrores( error, "#apertcie_errorSummary" );

	</script>

<?php

}

?>


 	<ul>
 	</ul>

 </div>

 </div>
 </div>
		</td>
		<td width="20%" valign="top">
			<div class="menu_derecho">
			<?php

			echo $this->render('_supervision_menu_derecho');

			?>
			</div>
		</td>
	</tr>
</table>

 <script>
 function cambiaCheckGrilla()
 {
 	var codigos = 0;

 	$("input[type=checkbox]:checked").each(function() {

		codigos = codigos + 1;
	});

	$("#apertcie_btConfirmar").toggleClass("disabled",!codigos > 0);

 }


 function buscar()
 {
 	var fecha = $("#apertcie-fchpago").val(),
 		error = new Array();

 	$("#apertcie_btConfirmar").addClass("disabled");

 	if ( fecha == "" )
 	{
 		error.push( "Ingrese una fecha." )

 	} else if ( fecha.length != 10 )
 	{
 		error.push( "Ingrese una fecha válida." );

 	} else if (ValidarRangoFechaJs(fecha, '<?= $dia ?>') == 1 )
 	{
 		error.push( "La fecha no puede ser superior a la fecha actual." );

 	}

 	if ( error.length == 0 )
 	{

 		//Oculto los mensajes de error
 		$("#apertcie_errorSummary").css("display","none");

 		$.pjax.reload({
 			container:"#datosGrilla",
 			method:"POST",
 			data:{
 				tipo:$("#apertcie-tipo option:selected").val(),
 				tesoreria:$("#apertcie-tesoreria option:selected").val(),
 				fecha:$("#apertcie-fchpago").val(),
 			}
 		});

 	} else
 	{
 		mostrarErrores( error, "#apertcie_errorSummary" );
 	}
 }

 function enviarDatos()
 {
 	var codigos = 0,
 		fecha = $("#apertcie-fchpago").val();

 	$("input[type=checkbox]:checked").each(function() {

		codigos = codigos + 1;
	});

	if ( fecha.length < 10 )
	{
		mostrarErrores( [ "Ingrese una fecha válida." ], "#apertcie_errorSummary" );

	} else if ( codigos > 0 )
 	{
 		$("#form-apertCie").submit();
 	}
 }

 $(document).ready(function() {
 	cambiaCheckGrilla();
 });
 </script>
