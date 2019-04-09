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
	$base = Yii::$app->param->sis_url;
	$form = ActiveForm::begin([
        'id' => 'config-form',
        'options' => ['class' => 'form-horizontal'],
    ]); ?>

	<div class="cfg" style="width:200px; min-height: 139px;">
		<div class="titulo">General</div>
		<div class="content">
			<?= Html::a('Datos Municipales',Yii::$app->param->urlsam.'config/muni/index'); ?><br/>
			<?= Html::a('Configuraci&oacute;n General',Yii::$app->param->urlsam.'/config/config/index'); ?><br/>
			<?= Html::a('Feriados',Yii::$app->param->urlsam.'/config/feriado/index'); ?><br/>
			<?= Html::a('Oficinas',Yii::$app->param->urlsam.'/taux/oficina/index'); ?><br/>
			<?= Html::a('Secretarías',Yii::$app->param->urlsam.'site/auxedit&t=139'); ?><br/>
			<?= Html::a('Definici&oacute;n de Textos',$base.'/config/texto/index'); ?><br/>

		</div>
	</div>

	<div class="cfg" style="width:200px; min-height: 139px;">
		<div class="titulo">Accesorios</div>
		<div class="content">
			<?= Html::a('Interés',$base.'/ctacte/calcinteres/index'); ?><br/>
			<?= Html::a('Descuentos',$base.'/ctacte/calcdesc/index'); ?><br/>
			<?= Html::a('Multa',$base.'/ctacte/calcmulta/index'); ?><br/>
			<?= Html::a('M&oacute;dulos Municipales',$base.'/ctacte/calcmm/index'); ?><br/>
			<?= Html::a('Actualización de Deuda',$base.'/ctacte/calcact/index'); ?><br/>
		</div>
	</div>

	<div class="cfg" style="width:200px; min-height: 139px;">
		<div class="titulo">Partidas y Cuentas</div>
		<div class="content">
			<?= Html::a('Partidas Presupuestarias','/samfin/index.php?r=/presup/formulac/viewfor&caracter=1'); ?><br/>
			<?= Html::a('Cuentas de Ingreso',$base.'/config/cuenta/indexcuenta'); ?><br/>
			<?= Html::a('Cuentas Bancarias','/samfin/index.php?r=/taux/cuenta/cuenta'); ?><br/>
		</div>
	</div>


	<div class="cfg" style="width:200px; min-height: 156px;">
		<div class="titulo">Objetos</div>
		<div class="content">
			<?= Html::a('Tipo de Objeto',$base.'/config/objetotipo/index'); ?><br/>
			<?= Html::a('Tipo de Acci&oacute;n',$base.'/config/objetotaccion/index'); ?><br/>
			<?= Html::a('Motivo de Baja',$base.'/config/objetotbaja/index'); ?><br/>
		</div>
	</div>

	<div class="cfg" style="width:200px; min-height: 156px;">
		<div class="titulo">Tributos</div>
		<div class="content">
			<?= Html::a('Tributos',$base.'/ctacte/trib/index'); ?><br/>
			<?= Html::a('Items',$base.'/ctacte/item/index'); ?><br/>
			<?= Html::a('Resoluciones',$base.'/ctacte/resol/index'); ?><br/>
			<?= Html::a('Vencimientos',$base.'/ctacte/tribvenc/index'); ?><br/>
			<?= Html::a('DDJJ Configuración',$base.'/config/config_ddjj/index'); ?><br/>
			<?= Html::a('Rubros / Actividad Econ&oacute;mica',$base.'/config/rubro/index'); ?><br/>
			<?= Html::a('Vigencia General',$base.'/config/vigenciageneral/index'); ?><br/>
		</div>
	</div>

	<div class="cfg" style="width:200px; min-height: 156px;">
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
			<?= Html::a('Coeficientes de Mejoras',$base.'/config/valcoefmej/index'); ?><br/>
			<?= Html::a('S1/S2/S3',$base.'/config/inmsecciones/index'); ?><br/>
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
