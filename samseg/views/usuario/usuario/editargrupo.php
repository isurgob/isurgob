<?php

use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use app\utils\db\Fecha;
use app\utils\db\utb;
use yii\bootstrap\Tabs;
use yii\web\Session;
use yii\bootstrap\Alert;
use yii\data\ArrayDataProvider;
use app\models\caja\CajaCobro;

$cod_usuario = Yii::$app->request->post('usuario_id','');
/**
 * Forma que se dibuja cuando se edita un grupo.
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

/* Inicio Inicializar Variables */

 if ($consulta == 0)
 	$title = 'Nuevo Grupo';
 else if ($consulta == 3)
 	$title = 'Modificar Grupo';
 else if ($consulta == 2)
 	$title = 'Eliminar Grupo';

/* Fin Inicializar Variables */

$this->params['breadcrumbs'][] = ['label' => 'Seguridad', 'url' => ['view']];
$this->params['breadcrumbs'][] = $title;

$form = ActiveForm::begin(['id' => 'fromGrupo',/*'action' => [$consulta== 0 ? 'grupocreate' : 'grupoupdate']*/]);

?>

<style>
.div_izquierdo
{
	width: 100%;
	height: 100%;
	padding: 0px;
}

.div_derecho
{
	width: 100%;
	height: 100%;
	padding: 0px;
}

.form-group
{
	margin:0px;
}

.check_grillaTesoreria
{
	width: 12px;
    height: 12px;
    padding: 1px;
}

</style>

<table width="100%">
	<tr>
		<td align="left"><h1><?= Html::encode($title) ?></h1></td>
	</tr>
</table>

<!-- INICIO DIV Seguridad -->
<div id="div_seguridad_grupo_editausuario" style="display:block;margin-right:20px;padding-right:20px;padding-bottom: 8px" class="form-panel">

<table>
	<tr>
		<td width="55px"><label>Código</label></td>
		<td width="40px"><?= Html::input('text','Usuario[gru_id]',$model->gru_id,['id'=>'seguridad_grupo_Usuario[gru_id]','class'=>'form-control solo-lectura','style'=>'width:40px;text-align:center','tabIndex'=> -1]); ?></td>
		<td width="20px"></td>
		<td width="60px"><label for="seguridad_grupo_Usuario[gru_nombre]">Nombre:</label></td>
		<td><?= Html::input('text','Usuario[gru_nombre]',$model->gru_nombre,['id'=>'seguridad_grupo_Usuario[gru_nombre]','class'=>'form-control' . ($consulta == 2 ? ' solo-lectura' : ''),'style'=>'width:200px;text-align:left']); ?></td>
	</tr>
	<tr>
		<td colspan="2"><label for="seguridad_grupo_Usuario[gru_admin1]">Administrador 1:</label></td>
		<td></td>
		<td colspan="2">
			<?= Html::dropDownList('Usuario[gru_admin1]',$model->gru_admin1,utb::getAux('sam.sis_usuario','usr_id','apenom',3),[
					'id'=>'seguridad_grupo_Usuario[gru_admin1]',
					'class'=>'form-control' . ($consulta == 2 ? ' solo-lectura' : ''),
					'style'=>'width:99%',
				]);
			?>
		</td>
	</tr>
	<tr>
		<td colspan="2"><label for="seguridad_grupo_Usuario[gru_admin2]">Administrador 2:</label></td>
		<td></td>
		<td colspan="2">
			<?= Html::dropDownList('Usuario[gru_admin2]',$model->gru_admin2,utb::getAux('sam.sis_usuario','usr_id','apenom',3),[
					'id'=>'seguridad_grupo_Usuario[gru_admin2]',
					'class'=>'form-control' . ($consulta == 2 ? ' solo-lectura' : ''),
					'style'=>'width:99%',
				]);
			?>
		</td>
	</tr>
</table>

</div>

<table style="margin-top:10px;margin-bottom:5px">
	<tr>
		<td><?= Html::submitButton($consulta != 2 ? 'Aceptar' : 'Eliminar', ['id' => 'seguridad_grupo_btAceptar','class'=>$consulta != 2 ? 'btn btn-success' : 'btn btn-danger']); ?></td>
		<td width="20px"></td>
		<td><?= Html::a('Cancelar',['view'],['id' => 'seguridad_grupo_btCancelar','class'=>'btn btn-primary']); ?></td>
	</tr>
</table>

<?php

echo $form->errorSummary( $model, [
		'style' => 'margin: 8px 20px 8px 0px',
	]);

/*
 * Si se inserta un nuevo grupo ($consulta = 0), no se debe mostrar la grilla de procesos
 */
if ( $consulta == 3 )
{

	Pjax::begin(['id'=>'PjaxActualizaListaProcesosGrupo', 'enablePushState' => false, 'enableReplaceState' => false]);


?>

<div class="form-panel" style="padding: 5px 8px 8px 15px">

<h3><strong><label>Procesos:</label></strong></h3>

<table>
	<tr>
		<td><label>Sistema</label></td>
		<td>
            <?=
                Html::dropDownList('dlSistema','',utb::getAux('sam.sis_sistema','sis_id','nombre',2),[
					'id'=>'seguridad_dlSistema',
					'class'=>'form-control',
					'style'=>'width:150px',
					'onchange' => 'f_cambiaSistema()',
                ]);
            ?>
		</td>
		<td width="20px"></td>
		<td><label>Módulo</label></td>
		<td>
			<?php

			//INICIO Bloque de código que dibuja el combobox de Módulo
			Pjax::begin(['id'=>'PjaxActualizaModuloGrupo', 'enablePushState' => false, 'enableReplaceState' => false]);

				$sistema = Yii::$app->request->get('seguridad_sistema',0);
                
				echo Html::dropDownList('dlModulo','',utb::getAux('sam.sis_modulo','mod_id','nombre',2,'sis_id = ' . $sistema),[
					'id'=>'seguridad_dlModulo',
					'class'=>'form-control',
					'style'=>'width:150px',
					'onchange' => '$.pjax.reload({' .
									'container:"#PjaxGrillaGrupoProcesos",' .
									'method:"GET",' .
									'replace:false,' .
									'push:false,' .
									'data:{' .
										'seguridad_sistema:$("#seguridad_dlSistema").val(),' .
										'seguridad_modulo:$(this).val()}});']);


			Pjax::end();
			//FIN Bloque de código que dibuja el combobox de Módulo
			?>
		</td>
	</tr>
</table>

<div style="margin-top: 8px;">
<?php

	Pjax::begin(['id' => 'PjaxGrillaGrupoProcesos', 'enablePushState' => false, 'enableReplaceState' => false]);

		$sistema_id = Yii::$app->request->get('seguridad_sistema',0);
		$modulo_id = Yii::$app->request->get('seguridad_modulo',0);

		$dataProviderGrupo = new ArrayDataProvider([
			'allModels' => $model->getGrupos($sistema_id,$modulo_id),
			'pagination' => [
				'pageSize' => 1000,
			],
			'sort' => [
				'attributes' => [
					'pro_id',
					'sistema' => [
							'asc' => ['sistema' => SORT_ASC, 'modulo' => SORT_ASC, 'nombre' => SORT_ASC],
							'desc' => ['sistema' => SORT_DESC, 'modulo' => SORT_DESC, 'nombre' => SORT_DESC],
									],
				],
			]]);

	 	echo GridView::widget([
			'id' => 'GrillaGrupoProcesos',
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => ['class' => 'grilla'],
			'dataProvider' => $dataProviderGrupo,
			'summaryOptions' => ['class' => 'hidden'],
			'columns' => [
					['content'=> function($model, $key, $index, $column) use ($consulta) {return Html::checkbox('Usuario[gru_procesos]['.$model['pro_id'].']',$model['existe'] == 1 ? 1 : 0,
															[
																'style'=>'margin:0px',
																'id' => 'seguridad_grupo_Usuario[gru_procesos]'.$model['pro_id'],
																'class'=>'check_grilla ' . ($consulta == 2 ? 'solo-lectura' : ''),
																'onchange' => 'cambiarCheck()',
																'uncheck' => 0]);
																},
															'contentOptions'=>['style'=>'width:4px'],
															'header' => Html::checkBox('selection_all', false,
															[
																'id' => 'seguridad_grupo_ckProcesoTodos',
														        'onchange'=>'cambiarChecksGrillaGrupoProcesos()',
														        'class' => ($consulta == 2 ? 'solo-lectura' : ''),

														   	]),
					],
					['attribute'=>'pro_id','label' => 'Cod.', 'contentOptions'=>['style'=>'text-align:center','width'=>'1px']],
					['attribute'=>'sistema','label' => 'Sistema', 'contentOptions'=>['style'=>'text-align:center','width'=>'1px']],
					['attribute'=>'modulo','label' => 'Módulo', 'contentOptions'=>['style'=>'text-align:center','width'=>'1px']],
					['attribute'=>'nombre','label' => 'Nombre', 'contentOptions'=>['style'=>'text-align:left','width'=>'1px']],
                    ['attribute'=>'detalle','label' => 'Descripción', 'contentOptions'=>['style'=>'text-align:left','width'=>'150px']],
	        ],
		]);

	Pjax::end();

	?>

</div>
</div>
	<?php

	Pjax::end();
}

ActiveForm::end();

?>



<script>
function cambiarCheck()
{
	$("#seguridad_grupo_ckProcesoTodos").prop('checked','');
}

function cambiarChecksGrillaGrupoProcesos()
{
	var checks = $('#GrillaGrupoProcesos input[type="checkbox"]');

	if ($("#seguridad_grupo_ckProcesoTodos").is(":checked"))
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

function f_cambiaSistema(){

    sistema = $( "#seguridad_dlSistema" ).val();

    $.pjax.reload({
        container:"#PjaxActualizaModuloGrupo",
        method:"GET",
        replace:false,
        push:false,
        data:{
            "seguridad_sistema": sistema,
        },
    });
}

$("#PjaxActualizaModuloGrupo").on("pjax:end",function(){

    $.pjax.reload({
        container:"#PjaxGrillaGrupoProcesos",
        method:"GET",
        replace:false,
        push:false,
        data:{
            seguridad_sistema:$("#seguridad_dlSistema").val(),
        }});

});
</script>
