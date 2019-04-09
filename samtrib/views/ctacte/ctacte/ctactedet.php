<?php
use yii\helpers\Html;
use yii\helpers\BaseUrl;
use app\utils\db\utb;
use yii\bootstrap\Tabs;
use yii\widgets\Pjax;

$this->params['breadcrumbs'][] = ['label' => 'Cuenta Corriente', 'url' => ['index', 'obj_id' => $_GET["num"]]];
$this->params['breadcrumbs'][] = 'Detalle y Liquidación';
?>

<div class="ctacte-index">
	<table width='100%'  border='0'  style='border-bottom:1px solid #ddd; margin-bottom:5px'>
    <tr>
    	<td><h1><?= Html::encode('Detalle y Liquidación') ?></h1></td>
    	<td align='right'>
    		<?= Html::Button('Desagrupar', ['class' => 'btn btn-success', 'id' => 'btAgrupa', 'onclick' => 'ControlesCtaCteDet("Desagrupar")']);?>
    		<?= Html::a('Imprimir', ['//ctacte/ctacte/ctactedet', 'ctacte_id' => $model->ctacte_id, 'b' => $arrayCtaCteDet['baja'], 'accion' => 'Agrupar', 'imprimir' => true], ['class' => 'btn btn-success','target' => '_blank', 'id' => 'botonImprimir']) ?>
			<?= Html::a('Comprobante Pago', ['//ctacte/ctacte/constanciapago', 'cc' => $model->ctacte_id,'o' => $model->obj_id], ['class' => 'btn btn-success','target' => '_blank', 'id' => 'botonImprimirCompPago']) ?>
            <?= Html::a('Cta.Cte.', ['index', 'obj_id' => $_GET["num"]], ['class' => 'btn btn-success','id' => 'botonCtaCte']) ?>
    	</td>
    </tr>
    </table>
	
	<div class="form" style='padding:10px; margin-bottom:5px;'>
		<div>
			<label style="width:50px;">Objeto</label>
			<?= Html::input('text', 'txTObj', utb::getTObjNom($arrayCtaCteDet['obj_id']), ['class' => 'form-control solo-lectura','id'=>'txTObj','style'=>'width:80px;','disabled'=>true]); ?>
			<?= Html::input('text', 'txObj', $model->obj_id, ['class' => 'form-control solo-lectura','id'=>'txObj','style'=>'width:70px','disabled'=>true]); ?>
			<?= Html::input('text', 'txObjNom', utb::getNombObj($model->obj_id, false), ['class' => 'form-control solo-lectura','id'=>'txObjNom','style'=>'width:395px;','disabled'=>true]); ?>
			&nbsp;<label>N° Ref.</label>
			<?= Html::input('text', 'txNRef', $model->ctacte_id, ['class' => 'form-control solo-lectura','id'=>'txNRef','style'=>'width:80px;font-weight:bold','disabled'=>true]); ?>
		</div>
		
		<div>
			<label style="width:50px;">Tributo</label>
			<?= Html::input('text', 'txTrib', utb::getCampo('trib','trib_id='.$model->trib_id,'nombre_redu'), ['class' => 'form-control solo-lectura','id'=>'txTrib','style'=>'width:120px;','disabled'=>true]); ?>
			<label>Año</label>
			<?= Html::input('text', 'txAnio', $model->anio, ['class' =>  'form-control solo-lectura','id'=>'txAnio','style'=>'width:40px;','disabled'=>true]); ?>
			<label>Cuota</label>
			<?= Html::input('text', 'txCuota', $model->cuota, ['class' => 'form-control solo-lectura','id'=>'txTrib','style'=>'width:35px;text-align:center;','disabled'=>true]); ?>
			
			<label>Consolidación</label>
			<?= Html::input('text', 'txFchCons', $arrayCtaCteDet['fecha'], ['class' => 'form-control solo-lectura','id'=>'txFchCons','style'=>'width:70px;','disabled'=>true]); ?>
			<label>Accesorios</label>
			<?= Html::input('text', 'txAcc', $arrayCtaCteDet['accesor'], ['class' => 'form-control solo-lectura','id'=>'txAcc','style'=>'width:70px; text-align:right;','disabled'=>true]); ?>
			<label>Saldo</label>
			<?= Html::input('text', 'txSaldo', $arrayCtaCteDet['saldo'], ['class' => 'form-control solo-lectura','id'=>'txSaldo','style'=>'width:70px; text-align:right;','disabled'=>true]); ?>
		</div>
		
		<div>
			<label style="width:50px; vertical-align:top;">Obs.</label>
			<?= Html::textarea('txObs', $arrayCtaCteDet['obs'], ['class' => 'form-control solo-lectura','id'=>'txObs','style'=>'width:685px;height:50px;resize:none','disabled'=>true]); ?>
		</div> 
		
		<?php if ( $model->nominal == 0 and $model->ucm > 0 ) { ?>
			<div class="alert alert-warning" style="margin-bottom:0px">	
				Los valores están expresados en módulos.
			</div>
		<?php } ?>	
	</div>
	
	<?php
	Pjax::begin(['id' => 'pjaxAgrupar', 'enableReplaceState' => false, 'enablePushState' => false]);
	
	//	muestro tab de datos
	echo Tabs :: widget ([ 
 		'id' => 'TabCtaCteDet',
		'items' => [ 
 			['label' => 'Detalle', 
 			'content' => $this->render('ctactedet_detalle',['ctacte_id' => $model->ctacte_id, 'dataProvider' => $dataProviderDetalle, 'model' => $model, 'accion' => $accion, 'selectorPjax' => 'pjaxAgrupar', 'arrayCtaCteDet' => $arrayCtaCteDet]),
 			'options' => ['class'=>'tabItem', 'style' => 'padding:5px;']
 			],
 			['label' => 'Liquidación' , 
 			'content' => $this->render('ctactedet_liq',['dataProviderLiquidacion' => $dataProviderLiquidacion]),
 			'options' => ['class'=>'tabItem', 'style' => 'padding:5px;']
 			],
 			['label' => 'Cambio Estado' , 
 			'content' => $this->render('ctactedet_cambioest',['dataProviderCambioEstado' => $dataProviderCambioEstado]),
 			'options' => ['class'=>'tabItem', 'style' => 'padding:5px;']
 			],
 			['label' => 'Excepción' , 
 			'content' => $this->render('ctactedet_ctacteexcep',['dataProviderCtaCteExcepcion' => $dataProviderCtaCteExcepcion]),
 			'options' => ['class'=>'tabItem', 'style' => 'padding:5px;']
 			],
 			['label' => 'Reliq/Baja' , 
 			'content' => $this->render('ctactedet_ctactebaja',['dataProviderCtaCteBaja' => $dataProviderCtaCteBaja]),
 			'options' => ['class'=>'tabItem', 'style' => 'padding:5px;']
 			],
 		],
 		
	]);
	
	Pjax::end();
 ?>  
	
</div>

<script>
function ControlesCtaCteDet(control){
	
	var acc= $("#btAgrupa").text();
	var url= "";
	var baja= $("#ckBaja").is(":checked");
	
	switch(control){
		
		case 'Desagrupar':

			$("#btAgrupa").text(acc == "Agrupar" ? "Desagrupar" : "Agrupar");
			
			url= "<?= BaseUrl::toRoute(['//ctacte/ctacte/ctactedet', 'ctacte_id' => $model->ctacte_id, 'imprimir' => true]); ?>&b=" + baja + "&accion=" + acc;
			$("#botonImprimir").attr("href", url);
			
			break;
		
		case 'ckBajas':
		
			acc= acc == "Agrupar" ? "Desagrupar" : "Agrupar"
			break;
	}

	$("#btAgrupa").attr("disabled", true);
	
	$.pjax.reload({
		container:"#pjaxAgrupar",
		replace: false,
		push: false,
		type: "GET",
		data:{
				"b": $("#ckBajas").is(":checked"),
				"accion": acc
			},
		timeout: 3000
	});
}

$(document).ready(function(){
	
	$("#pjaxAgrupar").on("pjax:complete", function(){
		$("#btAgrupa").removeAttr("disabled");
	});
});
</script>