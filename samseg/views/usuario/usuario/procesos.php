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

$cod_usuario = Yii::$app->request->post('usuario_id','');
/**
 * Forma que se dibuja cuando se edita un usuario.
 * Recibo:
 * 			=> $model -> Modelo de Usuario
 *
 *
 */

$title = 'Procesos';

$this->params['breadcrumbs'][] = ['label' => 'Seguridad', 'url' => ['view']];
$this->params['breadcrumbs'][] = $title;

?>

<style>
.div_grilla {

	padding-right: 10px;
	padding-bottom: 10px;
}
</style>

<table width="100%">
	<tr>
		<td align="left"><h1><?= Html::encode($title) ?></h1></td>
	</tr>
</table>


<!-- INICIO DIV Procesos -->
<div id="div_seguridad_procesos" style="display:block;margin:0px">

<div class="form-panel" style="padding-bottom: 5px">

<table>
	<tr>
		<td align="left"><label>Sistema:</label></td>
		<td align="left"><?= Html::dropDownList('dlSistema',0,utb::getAux('sam.sis_sistema','sis_id','nombre',1),[
								'id' => 'seguridad_procesos_dlSistema',
								'class' => 'form-control',
								'onchange' => 'cambiaSistema()']); ?></td>
		<td width="20px"></td>
		<td align="left"><label>Módulo:</label></td>
		<td align="left" width="130px">

			<?php

				Pjax::begin(['id'=>'PjaxRecargarModulo']);

					$sistema = Yii::$app->request->post('sistema',0);

					echo Html::dropDownList('dlModulo',0,utb::getAux('sam.sis_modulo','mod_id','nombre',1,'sis_id = ' . $sistema),[
							'id' => 'seguridad_procesos_dlModulo',
							'class' => 'form-control',
							'style' => 'width: 99%',
                            'onchange' => 'f_limpiar_grilla()',
						]);

				Pjax::end();
			?>
		</td>
		<td width="20px"></td>
		<td align="left"><label>Nombre:</label></td>
		<td align="left">
			<?= Html::input('text','txNombre','',[
					'id' => 'seguridad_procesos_txNombre',
					'class' => 'form-control',
                    'onchange' => 'f_limpiar_grilla()',
				]);
			?>
		</td>
		<td width="60px"></td>
		<td align="right">
			<?= Html::button('Buscar', [
					'class' => 'btn btn-primary',
					'onclick' => 'buscarProcesos()',
				]);
			?>
		</td>
	</tr>
</table>


</div>


<?php

Pjax::begin(['id'=>'PjaxProcesos', 'enablePushState' => false, 'enableReplaceState' => false]);

$sistema_id = Yii::$app->request->get('sistema',0);
$modulo_id = Yii::$app->request->get('modulo',0);
$nombre = Yii::$app->request->get('nombre',0);

if ( $sistema_id != 0 )
{
    $dataProviderProcesos = new ArrayDataProvider(
    [
        'allModels' => $model->getProcesoSistema( $sistema_id, $modulo_id, $nombre ),
        'key' => 'pro_id',
        'pagination' => [
                    'pageSize' => 1000,
        ],
        'sort' => [
            'attributes' => [
                'pro_id',
            ]

        ],
    ]);
} else {
    $dataProviderProcesos = new ArrayDataProvider([]);
}

?>

	<!-- INICIO Grilla Procesos -->
	<div class="div_grilla">

	<?php



        Pjax::begin();

	 	echo GridView::widget([
			'id' => 'GrillaUsuarioProcesos',
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => ['class' => 'grilla'],
			'dataProvider' => $dataProviderProcesos,
			'summaryOptions' => ['class' => 'hidden'],
			'columns' => [
				['attribute'=>'pro_id','label' => 'Cód.', 'contentOptions'=>['style'=>'text-align:left','width'=>'50px']],
				['attribute'=>'sistema','label' => 'Sistema', 'contentOptions'=>['style'=>'text-align:left','width'=>'150px']],
				['attribute'=>'modulo','label' => 'Módulo', 'contentOptions'=>['style'=>'text-align:left','width'=>'100px']],
				['attribute'=>'nombre','label' => 'Nombre', 'contentOptions'=>['style'=>'text-align:left','width'=>'150px']],
				['attribute'=>'detalle','label' => 'Detalle', 'contentOptions'=>['style'=>'text-align:left','width'=>'250px']],
				[
					'class' => 'yii\grid\ActionColumn',
					'contentOptions'=>['style'=>'width:4px'],
					'template' =>'{usuariogrupo}',
					'buttons'=>[
						'usuariogrupo' =>  function($url, $model, $key)
									{
										return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url);
									},
					]
				]
	        ],
		]);

        Pjax::end();
	?>
	</div>
	<!-- FIN Grilla Procesos -->

<?php

Pjax::end();

?>

<!-- INICIO Mensajes de error -->
<table width="100%">
	<tr>
		<td width="100%">

			<?php

			Pjax::begin(['id'=>'errorFormUsuarioProcesos']);

			$mensaje = Yii::$app->request->post('mensaje','');
			$m = Yii::$app->request->post('m',2);

			if (isset($_POST['mensaje'])) $mensaje = $_POST['mensaje'];

			if($mensaje != "")
			{

		    	Alert::begin([
		    		'id' => 'AlertaFormUsuarioProcesos',
					'options' => [
		        	'class' => ($m == 2 ? 'alert-danger' : 'alert-success'),
		        	'style' => $mensaje !== '' ? 'display:block' : 'display:none'
		    		],
				]);

				echo $mensaje;

				Alert::end();

				echo "<script>window.setTimeout(function() { $('#AlertaFormUsuarioProcesos').alert('close'); }, 5000)</script>";
			 }

			 Pjax::end();

			?>
		</td>
	</tr>
</table>
<!-- FIN Mensajes de error -->

</div>

<script>

function buscarProcesos()
{
	var sistema = $("#seguridad_procesos_dlSistema").val(),
		modulo = $("#seguridad_procesos_dlModulo").val(),
		nombre = $("#seguridad_procesos_txNombre").val(),
		datos = {};

	datos.sistema = sistema;
	datos.modulo= modulo;
	datos.nombre = nombre;

	$.pjax.reload({
		container:"#PjaxProcesos",
		type:"GET",
        replace: false,
        ush: false,
		data:datos,
	});
}

function cambiaSistema()
{
	var sistema = $("#seguridad_procesos_dlSistema").val();

	$.pjax.reload({
        container:"#PjaxRecargarModulo",
        method:"POST",
        data:{
            sistema:sistema,
        },
    });

}

$( "#PjaxRecargarModulo" ).on( "pjax:end", function()
{
    f_limpiar_grilla()
});

function f_limpiar_grilla()
{
    $.pjax.reload( "#PjaxProcesos" );
}

</script>
