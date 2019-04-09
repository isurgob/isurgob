<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

$this->params['breadcrumbs'][] = 'Configuraciones';
?>
<div class="site-login">
    <h1><?= Html::encode('Configuraciones') ?></h1>

    <?php 
	$base = Yii::$app->request->baseUrl.'/index.php?r=';
	$form = ActiveForm::begin([
        'id' => 'config-form',
        'options' => ['class' => 'form-horizontal'],
    ]); ?>

	<div class="cfg" style="width:200px; min-height: 125px;">
		<div class="titulo">General</div>
		<div class="content">
			<?= Html::a('Datos Municipales',$base.'/config/muni/index'); ?><br/>
			<?= Html::a('Configuraci&oacute;n General',$base.'/config/config/index'); ?><br/>
			<?= Html::a('Feriados',$base.'/ctacte/calcferiado/index'); ?><br/>
			<?= Html::a('Oficinas',$base.'/site/auxedit&t=133'); ?><br/>
			<?= Html::a('Definici&oacute;n de Textos',$base.'/config/texto/index'); ?><br/>
			
		</div>
	</div>

	<div class="cfg" style="width:200px; min-height: 125px;">
		<div class="titulo">Accesorios</div>
		<div class="content">
			<?= Html::a('Interés',$base.'/ctacte/calcinteres/index'); ?><br/>
			<?= Html::a('Descuentos',$base.'/ctacte/calcdesc/index'); ?><br/>
			<?= Html::a('Multa',$base.'/ctacte/calcmulta/index'); ?><br/>
			<?= Html::a('M&oacute;dulos Municipales',$base.'/ctacte/calcmm/index'); ?><br/>
		</div>
	</div>

	<div class="cfg" style="width:200px; min-height: 125px;">
		<div class="titulo">Partidas y Cuentas</div>
		<div class="content">
			<?= Html::a('Partidas Presupuestarias',$base.'/config/cuentapartida/index'); ?><br/>
			<?= Html::a('Cuentas de Ingreso',$base.'/config/cuentapartida/indexcuenta'); ?><br/>
			<?= Html::a('Cuentas Bancarias',$base.'/caja/bancocuenta/index'); ?><br/>
		</div>
	</div>


	<div class="cfg" style="width:200px; min-height: 141px;">
		<div class="titulo">Objetos</div>
		<div class="content">
			<?= Html::a('Tipo de Objeto',$base.'/config/objetotipo/index'); ?><br/>
			<?= Html::a('Tipo de Acci&oacute;n',$base.'/config/objetotaccion/index'); ?><br/>
			<?= Html::a('Motivo de Baja',$base.'/config/objetotbaja/index'); ?><br/>
		</div>
	</div>

	<div class="cfg" style="width:200px; min-height: 141px;">
		<div class="titulo">Tributos</div>
		<div class="content">
			<?= Html::a('Tributos',$base.'/ctacte/trib/index'); ?><br/>
			<?= Html::a('Items',$base.'/ctacte/item/index'); ?><br/>
			<?= Html::a('Resoluciones',$base.'/ctacte/resol/index'); ?><br/>
			<?= Html::a('Vencimientos',$base.'/ctacte/tribvenc/index'); ?><br/>
			<?= Html::a('DDJJ Configuración',$base.'/config/config_ddjj/index'); ?><br/>
			<?= Html::a('Rubros / Actividad Econ&oacute;mica',$base.'/config/rubro/index'); ?><br/> 
		</div>
	</div>

	<div class="cfg" style="width:200px; min-height: 141px;">
		<div class="titulo">Operaciones</div>
		<div class="content">
			<?= Html::a('Configuraci&oacute;n de Cajas',$base.'/caja/caja/index'); ?><br/>
			<?= Html::a('Configuraci&oacute;n de Convenios',$base.'/ctacte/planconfig/index'); ?><br/>
			<?= Html::a('Convenios por Usuario',$base.'/ctacte/convenio/configusr'); ?><br/>
			<?= Html::a('Convenios a Decaer',$base.'/ctacte/convenio/configdecae'); ?><br/>
		</div>
	</div>


	<div class="cfg" style="width:200px; min-height: 100px;">
		<div class="titulo">Inmueble</div>
		<div class="content">
			<?= Html::a('Nomenclatura',$base.'/config/inmnom/index'); ?><br/> 
	   		<?= Html::a('Valores de Mejoras',$base.'/config/valmej/index'); ?><br/> 
		</div>
	</div>
	
	<div class="cfg" style="width:200px; min-height: 100px;">
		<div class="titulo">Cementerio</div>
		<div class="content">
			<?= Html::a('Nomenclatura',$base.'/config/cemnom/index'); ?><br/>
			<?= Html::a('Servicios Fallecido',$base.'/config/cemfalltserv/index'); ?><br/>
			<?= Html::a('Alquileres',$base.'/config/cemtalq/index'); ?><br/>
		</div>
	</div>

	<div class="cfg" style="width:200px; min-height: 100px;">
		<div class="titulo">Rodado</div>
		<div class="content">
	   		<?= Html::a('Valuaciones (RNPA)',$base.'/site/auxedit&t=49'); ?><br/>
			<?= Html::a('Valores por Categor&iacute;a y Peso',$base.'/config/rodadoval/valor'); ?><br/>
		</div>
	</div>


    <?php ActiveForm::end(); ?>
</div>
