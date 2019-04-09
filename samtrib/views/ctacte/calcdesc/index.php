<?php

use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\bootstrap\Alert;
use yii\data\ArrayDataProvider;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $form yii\widgets\ActiveForm */

$title = 'Descuentos';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = $title;
?>

<style type="text/css">

.form-control{

	width : 100%;
}

.calc-desc-index div.row{
	height : auto;
	min-height: 17px !important;
}

#div_grilla {

	margin-bottom: 8px;
}

.template{
	display: none;
}

</style>

<div class="calc-desc-index" >

	<table border="0" width="100%" style='border-bottom:1px solid #ddd'>
		<tr>
			<td><h1><?= $title; ?></h1></td>
			<td align="right"><?= Html::a('Nuevo Descuento', ['create'], ['class' => 'btn btn-success']) ?></td>
		</tr>
	</table>



	<div class="row" style="margin-top:5px">
		<div class="col-xs-2">
			<label>Filtrar por Tributo</label>
		</div>
		<div class="col-xs-6">
			<select class="form-control" id="selectTrib"></select>
		</div>

	</div>

   <!-- INICIO Div Grilla -->
   <div id="div_grilla">

	<div class="grid-view">
		<table class="table table-striped table-bordered" id="tablaDatos" data-tributo="0">
			<thead>
				<tr>
					<td data-orden="anual"><b>Anual</b></td>
					<td data-orden="perdesde"><b>PerDesde</b></td>
					<td data-orden="perhasta"><b>PerHasta</b></td>
					<td data-orden="cta_nom"><b>Cuenta</b></td>
					<td data-orden="desc1"><b>Desc1</b></td>
					<td data-orden="desc2"><b>Desc2</b></td>
					<td data-orden="modif"><b>Modificaci&oacute;n</b></td>
					<td></td>
				</tr>
			</thead>
			<tbody>
				<tr class="template">
					<td data-match="anual"></td>
					<td data-match="perdesde"></td>
					<td data-match="perhasta"></td>
					<td data-match="cta_nom"></td>
					<td data-match="desc1"></td>
					<td data-match="desc2"></td>
					<td data-match="modif"></td>
					<td>
						<a href="" class="modificar"><span class="glyphicon glyphicon-pencil"></span></a>
						<a href="" class="eliminar"><span class="glyphicon glyphicon-trash"></span></a>
					</td>
				</tr>

				<tr>
					<td colspan="8">
						<div class="empty">No se encontraron resultados.</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	</div>
	<!-- FIN Div Grilla -->

	<ul class="nav nav-tabs">
		<li class="active">
			<a href="#" data-toggle="tab" data-target="#descuentoTabDatos" aria-expanded="true">Datos</a>
		</li>

		<li>
			<a href="#" data-toggle="tab" data-target="#descuentoTabCalcular" aria-expanded="false">Calcular</a>
		</li>
	</ul>

	<div class="tab-content">
		<div id="descuentoTabDatos" class="tabItem tab-pane active loader" data-target="<?= BaseUrl::toRoute(['view']); ?>"></div>

		<div id="descuentoTabCalcular" class="tabItem tab-pane loader" data-target="<?= BaseUrl::toRoute(['calcular']); ?>">
			<?= $this->render('calcular', ['trib_id' => 0]); ?>
		</div>
	</div>
</div>

<div style="margin-top: 8px">

<?php

	if(isset($mensaje) && $mensaje != "")
	{

    	Alert::begin([
    		'id' => 'AlertaMensaje',
			'options' => [
        	'class' => 'alert-success',
        	'style' => $mensaje !== '' ? 'display:block' : 'display:none'
    		],
		]);

		echo $mensaje;

		Alert::end();

		echo "<script>window.setTimeout(function() { $('#AlertaMensaje')fadeOut(); }, 5000)</script>";
	 }

?>

</div>

<script type="text/javascript">
rutas= {};
rutas.cargarTributosUsados= "<?= BaseUrl::toRoute(['tributos', 'todos' => false], true); ?>";
rutas.cargarModelos= "<?= BaseUrl::toRoute(['modelos'], true); ?>";

rutas.modificar= "<?= BaseUrl::toRoute(['update']); ?>";
rutas.eliminar= "<?= BaseUrl::toRoute(['delete']); ?>";

rutas.datos= "<?= BaseUrl::toRoute(['view']); ?>";
rutas.calcular= "<?= BaseUrl::toRoute(['calcular']); ?>";
</script>
<?php
$this->registerJsFile(BaseUrl::to('sam/js/ctacte/calcdesc/index.js'), ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
