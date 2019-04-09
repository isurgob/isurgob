<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use app\utils\db\utb;
use \yii\widgets\Pjax;
use app\models\ctacte\Intima;
use \yii\bootstrap\Modal;
use yii\jui\DatePicker;

$this->params['breadcrumbs'][] = ['label' => 'Gestión de Incumplimiento','url' => ['view']];
$this->params['breadcrumbs'][] = ['label' => 'Impresión de Intimaciones'];

?>
<div class="intima-view">
	
	<table border='0' width='100%'>
	    <tr>
	    	<td><h1>Impresión de Intimaciones</h1></td>
	    	<td align='right'>
	    		<?= Html::Button('Imprimir',['class'=>'btn btn-success','onclick'=>'btImprimirClick()']); ?>
	    	</td>
		</tr>
	</table>
<?php
$orden = [
		'0' => 'Distribuidor',
		'1' => 'Domicilio Postal del Objeto',
		'2' => 'Domicilio Parcelario del Comercio',
		'3' => 'Domicilio Resp. del Plan',
		'4' => 'Objeto',
	];
	
$form = ActiveForm::begin(['id'=>'frmImprimirInti','action'=>['imprimirinti','id'=>$id],'options'=>['target'=>'_black']]);
	echo '<input type="hidden" name="txObjSelec" value="" id="txObjSelec">';
?>

<div class='form' style='padding:5px'>
	<label>Lote</lote> &nbsp; <?= Html::input('text', 'txImpLote', $id, ['id' => 'txImpLote', 'maxlength'=>'4','class' => 'form-control', 'style'=>'width:60px;text-align:center']); ?> 
	<label>Orden</lote> &nbsp; <?= Html::dropDownList('dlImpOrden', null,$orden, ['id'=>'dlImpOrden', 'class' => 'form-control']); ?>
</div>
<div id="error" style="display:none;margin:5px 0px" class="alert alert-danger alert-dismissable"></div>
<div class='form' style='padding:5px;margin-top:5px'>
	<label><u>Filtros</u></label>
	<table width='100%' cellspacing='4'>
		<tr>
			<td>Caracter:</td><td><?= Html::dropDownList('dlImpFilCaract', null,utb::getAux('Intima_TCaracter','cod','nombre',2), ['id'=>'dlImpFilCaract', 'class' => 'form-control']); ?></td>
			<td>Resultado:</td><td><?= Html::dropDownList('dlImpFilResult', null,utb::getAux('Intima_TResult','cod','nombre',2), ['id'=>'dlImpFilResult', 'class' => 'form-control']); ?></td>
			<td>Estado:</td><td><?= Html::dropDownList('dlImpFilEst', 0,utb::getAux('Intima_TEst'), ['id'=>'dlImpFilEst', 'class' => 'form-control']); ?></td>
			<td>Distribuidor:</td><td><?= Html::dropDownList('dlImpFilDist', null,utb::getAux('Sam.Sis_Usuario','usr_id','nombre',2,'distrib=1'), ['id'=>'dlImpFilDist', 'class' => 'form-control']); ?></td>
		</tr>
	</table>
	<?= Html::Button('Cargar',['class'=>'btn btn-success', 'onclick'=>'btnImpCargarClick(0)']); ?>
</div>
<div class='form' style='padding:5px;margin-top:5px'>
	<label><u>Imprimir</u></label>
	<table width='95%' cellspacing='4'>
		<tr>
			<td>Texto:</td><td><?= Html::dropDownList('dlImpTexto', null,utb::getAux('texto','texto_id','nombre',0,'tuso=4'), ['id'=>'dlImpTexto', 'class' => 'form-control']); ?></td>
			<td>Caracter:</td><td><?= Html::dropDownList('dlImpCaract', null,utb::getAux('Intima_TCaracter'), ['id'=>'dlImpCaract', 'class' => 'form-control']); ?></td>
			<td>Empresa:</td><td><?= Html::dropDownList('dlImpEmp', null,utb::getAux('Intima_Emp_Postal'), ['id'=>'dlImpEmp', 'class' => 'form-control']); ?></td>
		</tr>
		<tr>
			<td>Firma 1:</td>
			<td>
				<?= Html::dropDownList('dlImpFirma1', null,utb::getAux('Intima_Firma','firma_id','nombre',1,'tuso=4'), 
					['id'=>'dlImpFirma1', 'class' => 'form-control','onchange'=>'$("#ckImpImg1").prop("disabled",($("#dlImpFirma1").val()==0));']); ?>
				<?= Html::checkbox('ckImpImg1',false, ['id' => 'ckImpImg1','label' => 'Imagen','disabled'=>true]); ?>
			</td>
			<td>Firma 2:</td>
			<td>
				<?= Html::dropDownList('dlImpFirma2', null,utb::getAux('Intima_Firma','firma_id','nombre',1,'tuso=4'), 
					['id'=>'dlImpFirma2', 'class' => 'form-control','onchange'=>'$("#ckImpImg2").prop("disabled",($("#dlImpFirma2").val()==0));']); ?>
				<?= Html::checkbox('ckImpImg2',false, ['id' => 'ckImpImg2','label' => 'Imagen','disabled'=>true]); ?>
			</td>
			<td>Plazo Presentación:</td>
			<td>
				<?= DatePicker::widget([
						'id' => 'fchPresent',
						'name' => 'fchPresent',
						'dateFormat' => 'dd/MM/yyyy',
						'options' => ['style' => 'width:70px','class' => 'form-control'],
					]); ?>
			</td>
		</tr>
	</table>
	<div style='overflow:hidden; margin-top:15px'>
		<label><u>Opciones de Impresión</u></label>
		<table border='0' width='100%'>
			<tr>
				<td><?= Html::checkbox('ckImpSinRes',false, ['id' => 'ckImpSinRes','label' => 'Sin Resumen']); ?></td>
				<td><?= Html::checkbox('ckImpSinMonto',false, ['id' => 'ckImpSinMonto','label' => 'Sin Monto']); ?></td>
				<td><?= Html::checkbox('ckImpSinCanPer',false, ['id' => 'ckImpSinCanPer','label' => 'Sin cant. de períodos']); ?></td>
				<td><?= Html::checkbox('ckImpSinFirma',false, ['id' => 'ckImpSinFirma','label' => 'Sin Firma']); ?></td>
				<td><?= Html::checkbox('ckImpSinRec',false, ['id' => 'ckImpSinRec','label' => 'Sin Recibido']); ?></td>
				<td>
					<?= Html::checkbox('ckImpAgrup',false, ['id' => 'ckImpAgrup','label' => 'Agrupado', 
							'onClick'=>'if (this.checked) $("#ckImpCuo").prop("checked",false);']); ?>
				</td>
				<td>
					<?= Html::checkbox('ckImpCuo',false, ['id' => 'ckImpCuo','label' => 'Cuota por Cuota',
							'onClick'=>'if (this.checked) $("#ckImpAgrup").prop("checked",false);']); ?>
				</td>
			</tr>
		</table>
	</div>
</div>
<?php
ActiveForm::end();
	
	Pjax::begin(['id'=>'PjImpObj']);
		$lote = (isset($_POST['lote']) ? $_POST['lote'] : 0);
		$caract = (isset($_POST['caract']) ? $_POST['caract'] : 0);
		$result = (isset($_POST['result']) ? $_POST['result'] : 0);
		$est = (isset($_POST['est']) ? $_POST['est'] : 0);
		$distrib = (isset($_POST['distrib']) ? $_POST['distrib'] : 0);
		$orden = (isset($_POST['orden']) ? $_POST['orden'] : 0);
		$mostrar = (isset($_POST['mostrar']) ? $_POST['mostrar'] : 0);
		$error = '';
		$consusr = '';
		
		if ($lote != 0){
			$impobj = (new Intima)->ImprimirPrevio($lote,$caract,$result,$est,$distrib,$orden,$mostrar,$error,$consusr);
			
			if ($error != '') 
				echo '<script>$("#error").html("'.$error.'");$("#error").css("display", "block");</script>';
			else
				echo '<script>$("#error").html("");$("#error").css("display", "none");</script>';
				
			if ($consusr != '') echo '<script>$(document).ready(function(){$("#ImprimirPreg").modal("show");})</script>';
			
			if ($mostrar == 1 or ($consusr == '' and $error == '')){
				echo  GridView::widget([
					'dataProvider' => $impobj,
					'id' => 'GrillaImpObj',
					'headerRowOptions' => ['class' => 'grilla'],
					'rowOptions' => ['class' => 'grilla'],
		        	'columns' => [
    					['class' => 'yii\grid\CheckboxColumn','headerOptions'=>['onclick'=>'selectTodos()'],'contentOptions'=>['style'=>'padding:0px !important;text-align:center']],	
    					['attribute'=>'obj_id','header' => 'Objeto','contentOptions'=>['style'=>'text-align:center;vertical-align:middle']],
        				['attribute'=>'num','header' => 'Num','contentOptions'=>['style'=>'text-align:center;vertical-align:middle']],
        				['attribute'=>'obj_nom','header' => 'Nombre','contentOptions'=>['style'=>'text-align:left;vertical-align:middle']],
        				['attribute'=>'plan_id','header' => 'Plan','contentOptions'=>['style'=>'text-align:center;vertical-align:middle']],
        				['attribute'=>'periodos','header' => 'Per.','contentOptions'=>['style'=>'text-align:center;vertical-align:middle']],
        				['attribute'=>'nominal','header' => 'Nominal','contentOptions'=>['style'=>'text-align:right;vertical-align:middle']],
        				['attribute'=>'accesor','header' => 'Accesor','contentOptions'=>['style'=>'text-align:right;vertical-align:middle']],
        				['attribute'=>'multa','header' => 'Multa','contentOptions'=>['style'=>'text-align:right;vertical-align:middle']],
        				['attribute'=>'total','header' => 'Total','contentOptions'=>['style'=>'text-align:right;vertical-align:middle']],
				   ],
	   			]);
			}
		}
   	Pjax::end();
   	
   	Modal::begin([
		'id' => 'ImprimirPreg',
		'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Impresión de Intimaciones</h2>',
		'closeButton' => [
              'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
              'class' => 'btn btn-danger btn-sm pull-right',
            ],
           'size' => 'modal-sm'
		]);

		echo '<p align="center" style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;color:#333;font-size:12px">';
		echo '<b>Se recomienda correr el proceso de seguimiento antes de imprimir. Continúa?</b>';
		echo '</p>';
		
		echo '<p align="center">';
		echo Html::Button('Si',['style'=>'color:#fff;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px','class'=>'btn btn-success', 'onclick'=>'$("#ImprimirPreg").modal("hide");btnImpCargarClick(1);']);
		echo '&nbsp;';
		echo Html::Button('No',['style'=>'color:#fff;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px','class'=>'btn btn-primary', 'onclick'=>'$("#ImprimirPreg").modal("hide")']);
		echo '</p>'; 

	Modal::end();
?>
</div>

<script>
function btnImpCargarClick(vmostrar)
{
	vlote = $("#txImpLote").val();
	vcarat = $("#dlImpFilCaract").val();
	vresul = $("#dlImpFilResult").val();
	vest = $("#dlImpFilEst").val();
	vdistrib = $("#dlImpFilDist").val();
	vorden = $("#dlImpOrden").val();
	
	$.pjax.reload(
	{
		container:"#PjImpObj",
		data:{
			lote:vlote,
			carat:vcarat,
			resul:vresul,
			est:vest,
			distrib:vdistrib,
			orden:vorden,
			mostrar:vmostrar
		},
		method:"POST"
	});
}

function btImprimirClick()
{
	obj = '';
	$("#GrillaImpObj input:checkbox:checked").each(function(){
		//cada elemento seleccionado
		if ($(this).val() != 1){
			if (obj != '') obj += ',';
			obj += $(this).val();
		}
	});
		
	error = '';
		
	if ($("#txImpLote").val() == '' || $("#txImpLote").val() == 0) error += '<li>Ingrese un Lote</li>';
	if (obj == '') error += '<li>No hay Objetos seleccionados </li>';
	if ($("#dlImpTexto").val() == '') error += '<li>Seleccione un texto de intimación</li>';
	if ($("#dlImpCaract").val() == '') error += '<li>Seleccione un caracter de intimación</li>';
	if ($("#dlImpFirma1").val() == 0) error += '<li>Seleccione una firma para la intimación</li>';
	
	if (error=='')
	{
		$("#txObjSelec").val(obj);
		$("#error").html('');
		$("#error").css("display", "none");
		
		$("#frmImprimirInti").submit();
	}else {
		$("#error").html(error);
		$("#error").css("display", "block");
	}
}

function selectTodos()
{
	$("#GrillaImpObj input[type=checkbox]").prop("checked", $("#GrillaImpObj input[type=checkbox]").prop("checked"));	
}

</script>
