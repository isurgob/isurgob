<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

$this->params['breadcrumbs'][] = 'Configuraciones / Auxiliares';
?>
<div class="site-login">
    <h1><?= Html::encode('Configuraciones / Auxiliares') ?></h1>

    <?php 
	$base = Yii::$app->request->baseUrl.'/index.php?r=';
	
	$form = ActiveForm::begin([
        'id' => 'config-form',
        'options' => ['class' => 'form-horizontal'],
    ]); ?>

	<div class="cfg" style="min-height: 156px; width:160px">
		<div class="titulo">Persona</div>
		<div class="content">
			<?= Html::a('Tipo de Documento',Yii::$app->param->urlsam.'site/auxedit&t=3'); ?><br/>
            <?= Html::a('Sexo',Yii::$app->param->urlsam.'site/auxedit&t=141'); ?><br/>
			<?= Html::a('Nacionalidad',Yii::$app->param->urlsam.'site/auxedit&t=4'); ?><br/>
			<?= Html::a('Estado Civil',Yii::$app->param->urlsam.'site/auxedit&t=2'); ?><br/>
		</div>
	</div>
    
    <div class="cfg" style="min-height: 156px; width:160px">
		<div class="titulo">Empleado</div>
		<div class="content">
			<?= Html::a('Estado',Yii::$app->param->urlsam.'site/auxedit&t=3'); ?><br/>
            <?= Html::a('Jubilación',Yii::$app->param->urlsam.'site/auxedit&t=1'); ?><br/>
			<?= Html::a('Obra Socual',Yii::$app->param->urlsam.'site/auxedit&t=4'); ?><br/>
			<?= Html::a('Sistema Médico',Yii::$app->param->urlsam.'site/auxedit&t=2'); ?><br/>
            <?= Html::a('Tipo de Convenio',Yii::$app->param->urlsam.'site/auxedit&t=2'); ?><br/>
            <?= Html::a('Sindicato',Yii::$app->param->urlsam.'site/auxedit&t=2'); ?><br/>
            <?= Html::a('Motivo Baja',Yii::$app->param->urlsam.'site/auxedit&t=2'); ?><br/>
		</div>
	</div>
    
    <div class="cfg" style="min-height: 156px; width:160px">
		<div class="titulo">Cargo</div>
		<div class="content">
			<?= Html::a('Funciones',Yii::$app->param->urlsam.'site/auxedit&t=3'); ?><br/>
            <?= Html::a('Grupo',Yii::$app->param->urlsam.'site/auxedit&t=1'); ?><br/>
			<?= Html::a('Sit. Revista',Yii::$app->param->urlsam.'site/auxedit&t=4'); ?><br/>
		</div>
	</div>
    
    <div class="cfg" style="min-height: 156px; width:160px">
		<div class="titulo">Familia</div>
		<div class="content">
			<?= Html::a('Discapacidad',Yii::$app->param->urlsam.'site/auxedit&t=3'); ?><br/>
            <?= Html::a('Escolaridad',Yii::$app->param->urlsam.'site/auxedit&t=1'); ?><br/>
			<?= Html::a('Relación Familia',Yii::$app->param->urlsam.'site/auxedit&t=4'); ?><br/>
		</div>
	</div>
    
     <div class="cfg" style="min-height: 139px; width:160px">
		<div class="titulo">Estudios/Habilidades</div>
		<div class="content">
			<?= Html::a('Tipo Curso',Yii::$app->param->urlsam.'site/auxedit&t=3'); ?><br/>
            <?= Html::a('Tipo Estudio',Yii::$app->param->urlsam.'site/auxedit&t=1'); ?><br/>
			<?= Html::a('Tipo Habilidad',Yii::$app->param->urlsam.'site/auxedit&t=4'); ?><br/>
            <?= Html::a('Idiomas',Yii::$app->param->urlsam.'site/auxedit&t=4'); ?><br/>
            <?= Html::a('Estado Estudio',Yii::$app->param->urlsam.'site/auxedit&t=4'); ?><br/>
            <?= Html::a('Profesión',Yii::$app->param->urlsam.'site/auxedit&t=4'); ?><br/>
		</div>
	</div>
    
    <div class="cfg" style="min-height: 139px; width:160px">
		<div class="titulo">Banco</div>
		<div class="content">
			<?= Html::a('Entidades',Yii::$app->param->urlsam.'site/auxedit&t=79'); ?><br/>
			<?= Html::a('Sucursales',Yii::$app->param->urlsam.'site/auxedit&t=80'); ?><br/>
            <?= Html::a('Cuentas',Yii::$app->param->urlsam.'site/auxedit&t=80'); ?><br/>
            <?= Html::a('Préstamo Estado',Yii::$app->param->urlsam.'site/auxedit&t=80'); ?><br/>
		</div>
	</div>
    
    <div class="cfg" style="min-height: 139px; width:160px">
		<div class="titulo">Asistencia</div>
		<div class="content">
			<?= Html::a('Origen Medición',Yii::$app->param->urlsam.'site/auxedit&t=79'); ?><br/>
			<?= Html::a('Estado Ausencia',Yii::$app->param->urlsam.'site/auxedit&t=80'); ?><br/>
            <?= Html::a('Grupo Ausencia',Yii::$app->param->urlsam.'site/auxedit&t=80'); ?><br/>
            <?= Html::a('Tipo Ausencia',Yii::$app->param->urlsam.'site/auxedit&t=80'); ?><br/>
            <?= Html::a('Tipo Horario',Yii::$app->param->urlsam.'site/auxedit&t=80'); ?><br/>
            <?= Html::a('Unidad Vacaciones',Yii::$app->param->urlsam.'site/auxedit&t=80'); ?><br/>
		</div>
	</div>
    
    <div class="cfg" style="min-height: 139px; width:160px">
		<div class="titulo">Acc/Enfermedad</div>
		<div class="content">
			<?= Html::a('Tipo Accidente',Yii::$app->param->urlsam.'site/auxedit&t=79'); ?><br/>
			<?= Html::a('Tipo Enfermedad',Yii::$app->param->urlsam.'site/auxedit&t=80'); ?><br/>
            <?= Html::a('Tipo Lesión',Yii::$app->param->urlsam.'site/auxedit&t=80'); ?><br/>
		</div>
	</div>
    
    <div class="cfg" style="min-height: 156px; width:160px">
		<div class="titulo">Municipio</div>
		<div class="content">
			<?= Html::a('Datos Municipales',Yii::$app->param->urlsam.'config/muni/index'); ?><br/>
			<?= Html::a('Oficinas',Yii::$app->param->urlsam.'site/auxedit&t=133'); ?><br/>
            <?= Html::a('Secretarías',$base.'/config/config/index'); ?><br/>
			<?= Html::a('Pais/Prov/Loc',$base.'/ctacte/calcferiado/index'); ?><br/>
		</div>
	</div>

	<div class="cfg" style="min-height: 156px; width:160px">
		<div class="titulo">Configuraciones</div>
		<div class="content">
			<?= Html::a('General',$base.'/ctacte/calcinteres/index'); ?><br/>
			<?= Html::a('Horarios',$base.'/ctacte/calcdesc/index'); ?><br/>
			<?= Html::a('Licencias',$base.'/ctacte/calcmulta/index'); ?><br/>
			<?= Html::a('Vacaciones',$base.'/ctacte/calcmm/index'); ?><br/>
            <?= Html::a('Feriados',$base.'/ctacte/calcmm/index'); ?><br/>
            <?= Html::a('Préstamos',$base.'/ctacte/calcmm/index'); ?><br/>
            <?= Html::a('Conceptos',$base.'/ctacte/calcmm/index'); ?><br/>
		</div>
	</div>
    
     <div class="cfg" style="min-height: 156px; width:160px">
		<div class="titulo">Conceptos</div>
		<div class="content">
			<?= Html::a('Configuración',$base.'/ctacte/calcinteres/index'); ?><br/>
			<?= Html::a('Tipo Concepto',$base.'/ctacte/calcdesc/index'); ?><br/>
			<?= Html::a('Tipo Novedad',$base.'/ctacte/calcmulta/index'); ?><br/>
            <?= Html::a('Tipo Descuento',$base.'/ctacte/calcmulta/index'); ?><br/>
            <?= Html::a('Vigencia',$base.'/ctacte/calcmulta/index'); ?><br/>
            <?= Html::a('Estado Liquidación',$base.'/ctacte/calcmulta/index'); ?><br/>
            <?= Html::a('Tipo Liquidación',$base.'/ctacte/calcmulta/index'); ?><br/>
		</div>
	</div>
    
    <div class="cfg" style="min-height: 156px; width:160px">
		<div class="titulo">Novedades</div>
		<div class="content">
			<?= Html::a('Empresa',$base.'/ctacte/calcinteres/index'); ?><br/>
			<?= Html::a('Formato',$base.'/ctacte/calcdesc/index'); ?><br/>
			<?= Html::a('Juzgado',$base.'/ctacte/calcmulta/index'); ?><br/>
		</div>
	</div>

    <?php ActiveForm::end(); ?>
</div>
