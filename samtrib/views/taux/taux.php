<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

$this->params['breadcrumbs'][] = 'Tablas Auxiliares';
?>
<div class="site-login">
    <h1>Tablas Auxiliares</h1>

    <?php
	$base = Yii::$app->param->sis_url;
	$generico = $base.'/site/auxedit';

	$form = ActiveForm::begin([
        'id' => 'config-form',
        'options' => ['class' => 'form-horizontal'],
    ]); ?>

	<div class="cfg" style="min-height: 207px;">
		<div class="titulo">Persona</div>
		<div class="content">
			<?= Html::a('Tipo Persona',Yii::$app->param->urlsam.'site/auxedit&t=142'); ?><br/>
			<?= Html::a('Clasificaci&oacute;n',$generico.'&t=1'); ?><br/>
			<?= Html::a('Estado Civil',$generico.'&t=2'); ?><br/>
			<?= Html::a('Tipo de Documento',$generico.'&t=3'); ?><br/>
			<?= Html::a('Sexo',Yii::$app->param->urlsam.'site/auxedit&t=7'); ?><br/>
			<?= Html::a('Nacionalidad',$generico.'&t=4'); ?><br/>
			<?= Html::a('V&iacute;nculo entre Objetos',$generico.'&t=5'); ?><br/>
			<?= Html::a('V&iacute;nculo entre Personas',$generico.'&t=6'); ?><br/>
			<?= Html::a('Consulta Web - Tema',Yii::$app->param->urlsam.'site/auxedit&t=141'); ?><br/>
			<?= Html::a('Tipo Baja IB',Yii::$app->param->urlsam.'site/auxedit&t=349'); ?><br/>
		</div>
	</div>

	<div class="cfg" style="min-height: 207px;">
		<div class="titulo">Inmueble</div>
		<div class="content">
			<?= Html::a('Urbano/SubUrb',$generico.'&t=8'); ?><br/>
			<?= Html::a('Titularidad',$generico.'&t=9'); ?><br/>
			<?= Html::a('R&eacute;gimen',$generico.'&t=10'); ?><br/>
			<?= Html::a('Tipo de Inmueble',$generico.'&t=11'); ?><br/>
			<?= Html::a('Uso',$generico.'&t=12'); ?><br/>
			<?= Html::a('Tipo de Matr&iacute;cula',$generico.'&t=13'); ?><br/>
			<?= Html::a('Tipo de Patrimonio',$generico.'&t=14'); ?><br/>
			<?= Html::a('Restricciones',$generico.'&t=15'); ?><br/>
		</div>
	</div>

	<div class="cfg" style="min-height: 207px;">
		<div class="titulo">Mejoras/Zonas</div>
		<div class="content">
			<?= Html::a('Origen del Dato',$generico.'&t=16'); ?><br/>
			<?= Html::a('Formulario',$generico.'&t=17'); ?><br/>
			<?= Html::a('Destino',$generico.'&t=18'); ?><br/>
			<?= Html::a('Estado Conservaci&oacute;n',$generico.'&t=19'); ?><br/>
			<?= Html::a('Tipo de Obra',$generico.'&t=20'); ?><br/>
			<?= Html::a('Categor&iacute;as de Mejoras',$generico.'&t=146'); ?><br/>
			<?= Html::a('Zona Tributaria',$generico.'&t=21'); ?><br/>
			<?= Html::a('Zona Valuatoria',$generico.'&t=22'); ?><br/>
		</div>
	</div>

	<div class="cfg" style="min-height: 207px;">
		<div class="titulo">Obras Particulares</div>
		<div class="content">
			<?= Html::a('Estado Expediente',$generico.'&t=23'); ?><br/>
			<?= Html::a('Estado Mejoras',$generico.'&t=24'); ?><br/>
			<?= Html::a('Etapa',$generico.'&t=25'); ?><br/>
			<?= Html::a('Infracci&oacute;n',$generico.'&t=26'); ?><br/>
			<?= Html::a('Profesionales',$generico.'&t=27'); ?><br/>
			<?= Html::a('Tipo de Profesi&oacute;n',$generico.'&t=28'); ?><br/>
			<?= Html::a('Tipo de Obra',$generico.'&t=29'); ?><br/>
			<?= Html::a('Zona O.P.',$generico.'&t=30'); ?><br/>
		</div>
	</div>

	<div style="float:left;" style="min-height: 220px;">
		<div class="cfg" style="float:none;">
			<div class="titulo">Domicilios</div>
			<div class="content">
				<?= Html::a('Localidad/Prov/Pa&iacute;s', Yii::$app->param->urlsam.'config/localidad/index'); ?><br/>
				<?= Html::a('Barrios',$generico.'&t=32'); ?><br/>
				<?= Html::a('Tipo de Distribución',$generico.'&t=132'); ?><br/>
			</div>
		</div>

		<div class="cfg" style="min-height: 112px;">
			<div class="titulo">Calle</div>
			<div class="content">
				<?= Html::a('Calle',$generico.'&t=130'); ?><br/>
				<?= Html::a('Tipo de Alumbrado',$generico.'&t=140'); ?><br/>
				<?= Html::a('Tipo de Calle',$generico.'&t=34'); ?><br/>
				<?= Html::a('Tipo de Pavimento',$generico.'&t=33'); ?><br/>
			</div>
		</div>
	</div>

	<div class="cfg" style="min-height: 220px;">
		<div class="titulo">Cementerio</div>
		<div class="content">
			<?= Html::a('Tipo de Cuenta',$generico.'&t=36'); ?><br/>
			<?= Html::a('Cuadros y Cuerpos',$base.'//taux/cemcuadro/index'); ?><br/>
			<?= Html::a('Delegaciones',$generico.'&t=38'); ?><br/>
			<?= Html::a('Exenciones',$generico.'&t=39'); ?><br/>
			<?= Html::a('Estado Fallecido',$generico.'&t=40'); ?><br/>
			<?= Html::a('Causa de Muerte',$generico.'&t=41'); ?><br/>
			<?= Html::a('Empresa F&uacute;nebre',$generico.'&t=42'); ?><br/>
			<?= Html::a('Estado Alquiler',$generico.'&t=43'); ?><br/>
		</div>
	</div>

	<div class="cfg" style="min-height: 220px;">
		<div class="titulo">Rodado</div>
		<div class="content">
			<?= Html::a('Marcas',$generico.'&t=47'); ?><br/>
			<?= Html::a('Modelos',$generico.'&t=48'); ?><br/>
			<?= Html::a('Categor&iacute;a',$generico.'&t=50'); ?><br/>
			<?= Html::a('Delegaciones',$generico.'&t=51'); ?><br/>
			<?= Html::a('Uso',$generico.'&t=52'); ?><br/>
			<?= Html::a('Tipo de Alta',$generico.'&t=53'); ?><br/>
			<?= Html::a('Tipo de Combustible',$generico.'&t=54'); ?><br/>
			<?= Html::a('Tipo de Liquidaci&oacute;n',$generico.'&t=55'); ?><br/>
			<?= Html::a('Tipo de Formulario',$generico.'&t=348'); ?><br/>
		</div>
	</div>

	<div class="cfg" style="min-height: 220px;">
		<div class="titulo">Comercio</div>
		<div class="content">
			<?= Html::a('Org.Jur&iacute;dica',$generico.'&t=58'); ?><br/>
			<?= Html::a('Tipo de Comercio',$generico.'&t=59'); ?><br/>
			<?= Html::a('Tipo de Infracci&oacute;n',$generico.'&t=60'); ?><br/>
			<?= Html::a('Tipo de IVA',$generico.'&t=61'); ?><br/>
			<?= Html::a('Tipo de Rubro',$generico.'&t=62'); ?><br/>
			<?= Html::a('Tipo de Zona',$generico.'&t=63'); ?><br/>
			<?= Html::a('Tipo de Liquidaci&oacute;n',$generico.'&t=64'); ?><br/>
			<?= Html::a('Tipo de Habilitaci&oacute;n',$generico.'&t=147'); ?><br/>
		</div>
	</div>

	<div style="float:left;">
	<div class="cfg" style="float:none;min-height: 139px;">
		<div class="titulo">Tributos</div>
		<div class="content">
			<?= Html::a('Tipo de Tributo',$generico.'&t=65'); ?><br/>
			<?= Html::a('Tipo de Item',$generico.'&t=68'); ?><br/>
			<?= Html::a('F&oacute;rmula de C&aacute;lculo',$generico.'&t=67'); ?><br/>
			<?= Html::a('Categor&iacute;as Inscripci&oacute;n',$generico.'&t=69'); ?><br/>
			<?= Html::a('Tipo de C&aacute;lculo Mejora',$generico.'&t=145'); ?><br/>
		</div>
	</div>

	<div class="cfg" >
		<div class="titulo">Cuenta Corriente</div>
		<div class="content">
			<?= Html::a('Estado del Per&iacute;odo',$generico.'&t=70'); ?><br/>
			<?= Html::a('Tipo de Cuenta',$generico.'&t=71'); ?><br/>
			<?= Html::a('Tipo de Operaci&oacute;n',$generico.'&t=72'); ?><br/>
		</div>
	</div>
	</div>

	<div style="float:left;">
	<div class="cfg" style="float:none;min-height: 139px;">
		<div class="titulo">Convenio</div>
		<div class="content">
			<?= Html::a('Estado de Convenio',$generico.'&t=75'); ?><br/>
			<?= Html::a('Sistema Financiero',$generico.'&t=76'); ?><br/>
			<?= Html::a('Forma de Pago',$generico.'&t=77'); ?><br/>
			<?= Html::a('Origen',$generico.'&t=78'); ?><br/>
			<?= Html::a('Tipo Empleado',$generico.'&t=128'); ?><br/>
		</div>
	</div>

	<div class="cfg" style="min-height: 88px;">
		<div class="titulo">DDJJ</div>
		<div class="content">
			<?= Html::a('Tipo de DDJJ',$generico.'&t=73'); ?><br/>
			<?= Html::a('Grupo de Rubros', ['//taux/rubrogrupo/index']); ?><br/>
			<?= Html::a('Nomeclador de Rubros', ['//taux/rubronomeclador/index']); ?><br/>
		</div>
	</div>
	</div>

	<div style="float:left;">
	<div class="cfg" style="float:none;">
		<div class="titulo">Caja</div>
		<div class="content">
			<?= Html::a('Tesorer&iacute;a',$generico.'&t=81'); ?><br/>
			<?= Html::a('Tipo de Caja',$generico.'&t=82'); ?><br/>
			<?= Html::a('Destino Ticket',$generico.'&t=83'); ?><br/>
			<?= Html::a('Estado Caja',$generico.'&t=84'); ?><br/>
			<?= Html::a('Medio de Pago', ['//taux/mdp/view']); ?><br/>
			<?= Html::a('Dise&ntilde;o Agente Externo',$generico.'&t=86'); ?><br/>
		</div>
	</div>

	<div class="cfg"  style="min-height: 88px;">
		<div class="titulo">Banco</div>
		<div class="content">
			<?= Html::a('Entidades',$generico.'&t=79'); ?><br/>
			<?= Html::a('Sucursales',Yii::$app->param->urlsam.'site/auxedit&t=80'); ?><br/>
		</div>
	</div>
	</div>

	<div class="cfg" style="min-height: 243px;">
		<div class="titulo">Apremio / Incump.</div>
		<div class="content">
			<?= Html::a('Estado Planilla',$generico.'&t=87'); ?><br/>
			<?= Html::a('Juzgado',Yii::$app->param->urlsam.'site/auxedit&t=88'); ?><br/>
			<?= Html::a('Repartici&oacute;n',$generico.'&t=90'); ?><br/>
			<?= Html::a('Etapa Judicial',$generico.'&t=91'); ?><br/>
			<?= Html::a('Tipo de Supuesto',$generico.'&t=93'); ?><br/>
			<?= Html::a('Motivo Devoluci&oacute;n',$generico.'&t=94'); ?><br/>
			<?= Html::a('Honorarios Judiciales', ['//taux/judihono/index']); ?><br/>
			<?= Html::a('Etapas de Intimaciones',$generico.'&t=136'); ?><br/>
		</div>
	</div>

	<div class="cfg">
		<div class="titulo">Juzgado</div>
		<div class="content">
			<?= Html::a('Jueces',$generico.'&t=107'); ?><br/>
			<?= Html::a('Secretarios',$generico.'&t=108'); ?><br/>
			<?= Html::a('Tipo de Acta',$generico.'&t=109'); ?><br/>
			<?= Html::a('Tipo de Notificaci&oacute;n',$generico.'&t=110'); ?><br/>
			<?= Html::a('Tipo de Fallo',$generico.'&t=111'); ?><br/>
			<?= Html::a('Tipo de Infracci&oacute;n',$generico.'&t=112'); ?><br/>
			<?= Html::a('Tipos de Textos',$generico.'&t=129'); ?><br/>
		</div>
	</div>

	<div class="cfg" style="min-height: 156px;display:none">
		<div class="titulo">Reclamos</div>
		<div class="content">
			<?= Html::a('Tipo de Reclamo',$generico.'&t=115'); ?><br/>
			<?= Html::a('Verificaci&oacute;n',$generico.'&t=113'); ?><br/>
			<?= Html::a('Modo de Verificaci&oacute;n',$generico.'&t=114'); ?><br/>
			<?= Html::a('Origen',$generico.'&t=116'); ?><br/>
			<?= Html::a('Estado del Reclamo',$generico.'&t=117'); ?><br/>
		</div>
	</div>



	<table width="700px" cellspacing="1" cellpadding="3" border="0" style="border-radius:10px;">
	<tr>
	   <td width="140px" bgcolor="#5FA6D7" align="center" style="border-radius:10px;">
	   </td>
	</tr>

	<tr>
	   <td colspan="3">&nbsp;

	   </td>
	</tr>
	</table>

    <?php ActiveForm::end(); ?>
</div>
