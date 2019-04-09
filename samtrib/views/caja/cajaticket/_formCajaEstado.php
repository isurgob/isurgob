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


$title = 'Estado Caja';
$this->params['breadcrumbs'][] = 'Supervisión';
$this->params['breadcrumbs'][] = $title;


?>
<div class="estadoCaja-view">
	<table width="100%">
		<tr>
			<td><h1><?= Html::encode($title) ?></h1></td>
		</tr>
	</table>
</div>

<?php

$caja = 0;

$teso_id = 2;
$tipo = 0;
$fecha = $dia;
$confirmar = 0;
$codigos = []; //Voy a almacenar los códigos que se deberán actualizar

$form = ActiveForm::begin([
		'id'=>'form-estadoCaja',
		'action' => ['estadocaja'],
		]);

?>
<table>
	<tr>
		<td>
			<div>

				<?php
					Pjax::begin(['id'=>'errorEstadoCaja']);

						if (isset($_POST['m'])) $m = $_POST['m'];
						if (isset($_POST['mensaje'])) $mensaje = $_POST['mensaje'];

						Alert::begin([
							'id' => 'AlertaEstadoCaja',
							'options' => [
							'class' => ($m == 1 ? 'alert-info' : 'alert-danger'),
							'style' => ($m != 0 ? 'display:block' : 'display:none')
							],
						]);

						if ($mensaje !== '') echo $mensaje;

						Alert::end();

						if ($mensaje !== '') echo "<script>window.setTimeout(function() { $('#AlertaEstadoCaja').alert('close'); }, 5000)</script>";

					Pjax::end();
				?>
			</div>

<div>
<div class="form-panel" style="padding-bottom:4px">
<table>
	<tr>
		<td width="150px"><?= Html::dropDownList('estadoCaja-tesoreria', null, utb::getAux('caja_tesoreria','teso_id','nombre',0,"est='A' And teso_id > 0 AND teso_id IN (SELECT teso_id FROM sam.sis_usuario_tesoreria WHERE usr_id=".Yii::$app->user->id.")"), ['id'=>'estadoCaja-tesoreria', 'style'=>'width:130px', 'class' => 'form-control']); ?></td>
		<td width="30px"></td>
		<td><?= Html::Button('Buscar',['class'=>'btn btn-primary','onclick'=>'buscar()']); ?></td>
	</tr>
</table>
</div>

<div style="padding-right:15px">
<?php

	Pjax::begin(['id'=>'datosGrilla']);

		if (isset($_POST['tesoreria'])) $teso_id = $_POST['tesoreria'];
		if (isset($_POST['fecha'])) $fecha = $_POST['fecha'];

		echo GridView::widget([
				'id' => 'GrillaDetalle',
				'dataProvider' => $model->buscarEstadoCaja($teso_id),
				'summaryOptions' => ['class' => 'hidden'],
				'rowOptions' => function ($model,$key,$index,$grid) {return EventosGrilla($model);},
				'columns' => [
		            ['attribute'=>'caja_id','header' => 'Caja'],
					['attribute'=>'caja_nom','header' => 'Nombre'],
					['attribute'=>'cajero','contentOptions'=>['align'=>'center', 'style'=>'width:260px;text-align:left'],'header' => 'Cajero'],
					['attribute'=>'est_nom','header' => 'Estado'],

		        ],
		    ]);


		 if (count($error) > 0)
		{
			echo '<div class="error-summary">';
			echo '<label>Error</label>';
			foreach ($error as $mens)
				echo '<li>'.$mens.'</li>';

			echo '</div>';
		}



	Pjax::end();
?>

</div>

<?php

	Pjax::begin(['id'=>'formDatos']);

  	if (isset($_POST['caja'])) $caja = $_POST['caja'];

 	 $model->obtenerDatos($caja);

 ?>

<table width="100%" style="margin-top:8px" border="0">
	<tr>
		<td width="500px">
<div class="form-panel">
<div class="form-panel" style="padding-bottom:4px;padding-right: 5px">
<table border="0">
	<tr>
		<td width="60px"><label>Cód.Caja:</label><td>
		<td><?= Html::input('text','estadoCaja-codCaja', $model->caja_id, ['id'=>'estadoCaja-codCaja','class'=>'form-control','style'=>'width:40px;background-color:#E6E6FA;text-align:center','readOnly'=>'readOnly']) ?></td>
		<td width="20px"></td>
		<td width="50px"><label>Estado:</label><td>
		<td><?= Html::input('text','estadoCaja-estadoNom', $model->est_nom, ['id'=>'estadoCaja-estadoNom','class'=>'form-control','style'=>'width:180px;background-color:#E6E6FA;text-align:center','readOnly'=>'readOnly']) ?></td>
	</tr>
	<tr>
		<td width="60px"><label>Nombre:</label><td>
		<td colspan="5"><?= Html::input('text','estadoCaja-nombre', $model->caja_nom, ['id'=>'estadoCaja-nombre','class'=>'form-control','style'=>'width:292px;background-color:#E6E6FA;text-align:left','readOnly'=>'readOnly']) ?></td>
	</tr>
	<tr>
		<td width="60px"><label>Cajero:</label><td>
		<td colspan="5"><?= Html::input('text','estadoCaja-estadoNom', $model->cajero, ['id'=>'estadoCaja-estadoNom','class'=>'form-control','style'=>'width:292px;background-color:#E6E6FA;text-align:left','readOnly'=>'readOnly']) ?></td>
	</tr>
</table>
</div>

<h3><strong>Apertura</strong></h3>
<div class="form-panel" style="padding-bottom:4px;padding-right: 5px">

<table>
	<tr>
		<td width="70px"><label>Supervisor:</label><td>
		<td><?= Html::input('text','estadoCaja-Apsupervisor', $model->apesup, ['id'=>'estadoCaja-Apsupervisor','class'=>'form-control','style'=>'width:180px;background-color:#E6E6FA;text-align:left','readOnly'=>'readOnly']) ?></td>
		<td width="10px"></td>
		<td><?= Html::input('text','estadoCaja-ApFchSupervisor', $model->fchapesup, ['id'=>'estadoCaja-ApFchSupervisor','class'=>'form-control','style'=>'width:100px;background-color:#E6E6FA;text-align:center','readOnly'=>'readOnly']) ?></td>
	</tr>
	<tr>
		<td width="70px"><label>Cajero:</label><td>
		<td><?= Html::input('text','estadoCaja-ApCajero', $model->apecaj, ['id'=>'estadoCaja-ApCajero','class'=>'form-control','style'=>'width:180px;background-color:#E6E6FA;text-align:left','readOnly'=>'readOnly']) ?></td>
		<td width="10px"></td>
		<td><?= Html::input('text','estadoCaja-ApFchCajero', $model->fchapecaj, ['id'=>'estadoCaja-ApFchCajero','class'=>'form-control','style'=>'width:100px;background-color:#E6E6FA;text-align:center','readOnly'=>'readOnly']) ?></td>
	</tr>
</table>
</div>

<h3><strong>Cierre</strong></h3>
<div class="form-panel" style="padding-bottom:4px;padding-right: 5px">
<table>
	<tr>
		<td width="70px"><label>Cajero:</label><td>
		<td><?= Html::input('text','estadoCaja-CierreSupervisor', $model->ciecaj, ['id'=>'estadoCaja-CierreSupervisor','class'=>'form-control','style'=>'width:180px;background-color:#E6E6FA;text-align:left','readOnly'=>'readOnly']) ?></td>
		<td width="10px"></td>
		<td><?= Html::input('text','estadoCaja-CierreFchSupervisor', $model->fchciecaj, ['id'=>'estadoCaja-CierreFchSupervisor','class'=>'form-control','style'=>'width:100px;background-color:#E6E6FA;text-align:center','readOnly'=>'readOnly']) ?></td>
	</tr>
	<tr>
		<td width="70px"><label>Supervisor:</label><td>
		<td><?= Html::input('text','estadoCaja-CierreCajero', $model->ciesup, ['id'=>'estadoCaja-CierreCajero','class'=>'form-control','style'=>'width:180px;background-color:#E6E6FA;text-align:left','readOnly'=>'readOnly']) ?></td>
		<td width="10px"></td>
		<td><?= Html::input('text','estadoCaja-CierreFchCajero', $model->fchciesup, ['id'=>'estadoCaja-CierreFchCajero','class'=>'form-control','style'=>'width:100px;background-color:#E6E6FA;text-align:center','readOnly'=>'readOnly']) ?></td>
	</tr>
</table>
</div>
</div>
		</td>
		<td width="40%">

		<?php

			$text = '';
			switch($model->est)
			{
				case 0:
					$text = 'Anular Cierre Supervisor';
					break;

				case 1:
					$text = 'Anular Apertura Supervisor';
					break;

				case 3:
					$text = 'Anular Cierre Cajero';
					break;
			}
			if ($text != '')
				echo Html::SubmitButton($text, ['class'=>'btn btn-primary']);



		?>
		</td>


	</tr>
</table>
</div>
<?php

	Pjax::end();

	?>

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
 function buscar()
 {
 	/*var fecha;
	fecha = $("#estadoCaja-fchpago").val();
 	if (ValidarRangoFechaJs(fecha, '<?= $dia ?>') == 1)
 	{
 		$.pjax.reload({container:"#error",method:"POST",data:{m:2,mensaje:"La fecha no puede ser superior a la fecha actual."}})
 	} else {*/

 		$.pjax.reload({container:"#datosGrilla",method:"POST",data:{tesoreria:$("#estadoCaja-tesoreria option:selected").val(),fecha:$("#estadoCaja-fchpago").val()}})
 	/*}*/
 }

 </script>

 <?php

   ActiveForm::end();

	//Función que carga los datos
	function EventosGrilla ($m)
	{

      return ['onclick' => '$.pjax.reload({container:"#formDatos",data:{caja:'.$m["caja_id"].'},method:"POST"})'];

    }//Fin función que carga los datos


 ?>
