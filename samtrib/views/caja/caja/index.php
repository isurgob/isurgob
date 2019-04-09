<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \yii\widgets\Pjax;
use app\models\caja\Caja;
use app\utils\db\utb;
use yii\bootstrap\Alert;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

/**
 * Forma que se dibuja para la consulta y gestión de las cajas de cobro.
 *
 * Recibe:
 *		+ $tesorerias	-> Arreglo con las tesorerías disponibles para el usuario.
 */

$title = 'Cajas';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = $title;

$model = new Caja();

	//Obtengo las keys para cada valor del arreglo de tesoreria
	$arrayKeyTesoreria = array_keys( $tesorerias );

	//Creo un string que se podrá usar para mostrar las tesorerúias en caso de que no se filtre por tipo de tesorería

	$stringKeysTesoreria = '';

	if( count( $arrayKeyTesoreria ) > 0 ){

		foreach($arrayKeyTesoreria as $valor)
		{
			$stringKeysTesoreria .= $valor . ',';
		}
	}

	//Elimino la última coma
	$stringKeysTesoreria = trim($stringKeysTesoreria, ',');
//Fin manejo condiciones tesorería

?>

<div class="row">
	<div class="col-xs-4">
		<div class="caja-index">
			<table border="0" width="100%">
				<tr>
					<td><h1><?= Html::encode($title) ?></h1></td>
					<td align="right"><p><?= Html::a('Exportar', ['//ctacte/item/index'], ['class' => 'btn btn-default', 'style'=>"visibility:hidden"]) ?></p>
					<td align="right"><p>
					<?php
						if (utb::getExisteProceso(3417))
							echo Html::a('Nueva Caja', ['create'], ['class' => 'btn btn-success'])
					?></p>
				</tr>
			</table>

			<div style="border: 1px solid #ddd; border-radius: 10px; padding: 13px; padding-top:0px; margin-bottom: 8px" >
				<h3><strong>Filtrar</strong></h3>
				<table border="0" width="100%">
					<tr>
						<td>
							<?=
								Html::checkbox('tesorería', false, [
									'id' => 'caja-tesoreria',
									'label' => 'Tesorería',
									'onchange' => 'desactivaTesoreria()',
								]);
							?>
						</td>
						<td width="20px"></td>
						<td><?= Html::checkbox('Soloactivas', false, ['id' => 'caja-soloactivas', 'label' => 'Sólo Activas', 'onchange' => 'soloActivas()']) ?></td>
					</tr>
				</table>
				<?php
				//Inicio actualiza grilla datos
				$request = Yii::$app->request;

				?>

				<table width="100%">
					<tr>
						<td width="100%">
							<?php

								echo Html::dropDownList('tesoreria', null, $tesorerias , [
									'id' => 'caja-tipotesoreria',
									'class' => 'form-control',
									'style' => 'width:100%',
									'disabled'	=> true,
									'onchange' => '$.pjax.reload({container:"#idGrilla",data:{tesoreria:this.value,soloactivas:$("#caja-soloactivas").val()},method:"GET"})'
								]);
							?>
						</td>
					</tr>
				</table>

    		</div>

		    <?php

				//Inicio de bloque de grilla
		    	Pjax::begin(['id' => 'idGrilla','enableReplaceState' => false, 'enablePushState' => false, 'timeout' => 120000 ]);


			    echo GridView::widget([
			        'dataProvider' => $dpCajas,
			        'rowOptions' => function($model,$key,$index,$grid) {return EventosGrilla($model);},
					'summaryOptions' => ['class' => 'hidden'],

			        'columns' => [
			            ['attribute'=> 'caja_id', 'label' => 'Cod', 'contentOptions' => ['style' => 'width:10px;text-align:center']],
			            ['attribute'=> 'nombre', 'label' => 'Nombre'],

			            ['class' => 'yii\grid\ActionColumn', 'options' => ['style' => 'width:45px;'],'template'=>(utb::getExisteProceso(3417) ? '{update}{delete}' : ''),
			            'buttons' => [
			            	'view' => function()
						            	{
						            		return null;
						            	},
			            	'update' => function( $url,$model,$key)
			            				{
			            					return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url);
			            				},
			            	'delete' => function ($url,$model,$key)
			            				{
			            					$url .= '&accion=0';
			            					return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url);
			            				}
			            	]
			            ],
			        ],
			    ]);
			    //Fin de bloque de grilla
			    Pjax::end();

			?>

	 	</div>
	</div>
	<div class="col-xs-8">

    		<?php
		    //Inicio de bloque de _form

		    Pjax::begin(['id'=> 'formDatos']);

		    $request = Yii::$app->request;

		    $id = $request->post('caja_id', 0);

			$model = Caja::findOne($id);

			if ($model == null)
				$model = new Caja();

			echo $this->render('_form',['model'=> $model, 'consulta'=>1]);

		    Pjax::end();
		    //Fin de bloque de _form
     ?>

	</div>
</div>

	<table width="100%">
					<tr>
						<td width="100%">

				<?php

				$mensaje = $request->get('a', '');

				if($mensaje != ""){

					if ($mensaje == 'update')
						$mensaje = 'Los datos se grabaron correctamente.';

					if ($mensaje == 'create')
						$mensaje = 'Los datos se grabaron correctamente.';

					if ($mensaje == 'delete')
						$mensaje = 'Los datos se grabaron correctamente.';

			    	Alert::begin([
			    		'id' => 'AlertaMensaje',
						'options' => [
			        	'class' => 'alert-success',// : 'alert-info',
			        	'style' => $mensaje !== '' ? 'display:block' : 'display:none'
			    		],
					]);

					echo $mensaje;

					Alert::end();

					echo "<script>window.setTimeout(function() { $('#AlertaMensaje').alert('close'); }, 5000)</script>";
				 }

				?>
			</td>
		</tr>
	</table>

<?php

	//Función que carga los datos
	function EventosGrilla ($m)
	{
      $par = "caja_id:".$m['caja_id'];

      return ['onclick' => '$.pjax.reload({container:"#formDatos",data:{'.$par.',tab:$("#TabDescuento li.active").index()},method:"POST"})'];

    }//Fin función que carga los datos

 ?>


<script>

function desactivaTesoreria(){

	var checkTesoreria = document.getElementById('caja-tesoreria');
	var tesoreria = document.getElementById('caja-tipotesoreria');
	var checkActivas = document.getElementById('caja-soloactivas');

	if(!checkTesoreria.checked)
	{
		tesoreria.disabled = true;

	} else {

		tesoreria.disabled = false;

	}

	$.pjax.reload({
		container:"#idGrilla",
		method:"GET",
		replace	: false,
		push	: false,
		timeout : 100000,
		data:{
			tesoreria:tesoreria.value,
			soloactivas:checkActivas.value,
		},
	});
}

/* Función que selecciona las cuentas según su estado. Activa/Todas	*/
function soloActivas()
{
	var checkActivas 	= document.getElementById('caja-soloactivas');
	var tesoreria 		= document.getElementById('caja-tipotesoreria');
	var checkTesoreria	= document.getElementById('caja-tesoreria');

	/* En el caso de que se esté filtrando por tesorería. (Solo le paso el valor de tesorería)*/
	if (checkTesoreria.checked)
	{

		if(!checkActivas.checked)
		{
			checkActivas.value = 0;

		} else {

			checkActivas.value = 1;

		}

		$.pjax.reload({
			container:"#idGrilla",
			method	: "GET",
			replace	: false,
			push	: false,
			timeout : 100000,
			data:{
				tesoreria:tesoreria.value,
				soloactivas:checkActivas.value,
			},
		});

	} else { /* En el caso que no se esté filtrando por tesorería. (Le tengo que pasar el string que contiene todos los keys de las tesorerías)*/

		if(!checkActivas.checked)
		{
			checkActivas.value = 0;

		} else {

			checkActivas.value = 1;

		}

		$.pjax.reload({
			container:"#idGrilla",
			method:"GET",
			replace	: false,
			push	: false,
			timeout : 100000,
			data:{
				tesoreria:'<?= $stringKeysTesoreria; ?>',
				soloactivas:checkActivas.value,
			},
		});
	}
}

$(document).ready(function() {

	soloActivas();

});

</script>
