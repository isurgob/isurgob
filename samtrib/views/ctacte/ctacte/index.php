<?php
use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use \yii\bootstrap\Modal;
use yii\jui\DatePicker;
use \yii\widgets\Pjax;
use yii\bootstrap\Alert;
use yii\data\Pagination;

use app\utils\db\utb;
use app\utils\db\Fecha;
?>
<style>
#menuContextualResumen {
	min-height: 70px !important;
}

#GrillaResumen .pagination > li > a, .pagination > li > span{
	padding:1px 12px !important;
	font-size: 8px !important;
}

.menuContextual {

	display: none;
	background-color: white;
	border: 1px solid #999;
	border-radius:8px;
	padding:5px;
}

.menuContextual a {
	 text-decoration: none;
	 color: black;
	 display:block;
}

.menuContextual a:hover {

	cursor: pointer;
	background-color: #286090;
	border-color: #204d74;
	color: white;
}
</style>
<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = 'Cuenta Corriente';
?>

<?php
Pjax::begin(['id' => 'ObjNombre']);
?>
<script type="text/javascript">

<?php
	$objeto_id = '';
	$tobj = isset($obj_id) && $obj_id != "" ? utb::GetTObj($obj_id) : 3;
	$num = '';
	if (isset($_POST['objeto_id'])) $objeto_id=$_POST['objeto_id'];
	if (isset($_POST['tobj'])) {
		$tobj=$_POST['tobj'];

	}

	?>

	$("#nombreObjeto").val("");
	<?php

	if (strlen($objeto_id) < 8 && strlen($objeto_id) > 0){

		$objeto_id = utb::GetObjeto((int)$tobj,(int)$objeto_id);
		$num = utb::getCampo('objeto',"obj_id='" . $objeto_id . "'",'num');
		$tobj= utb::GetTObj($objeto_id);
		?>

		$("#codigoObjeto").val("<?= $objeto_id; ?>");
		$("#botonCtaCteNUM").attr("href", "<?= BaseUrl::toRoute(['index']); ?>&obj_id=<?=$num?>");
		$("#botonCtaCteNUM").attr("disabled", <?= $tobj == 3 ? 'true' : 'false'; ?>);

		<?php
	}

	$objeto_nom = str_replace('"','',utb::getNombObj("'".$objeto_id."'"));

	if($objeto_nom !== false){
		?>
		$("#nombreObjeto").val('<?= $objeto_nom; ?>');
		<?php
	}


	if($tobj > 0){
		?>
		$(document).ready(function(){
			$.pjax.reload({
				container: "#pjaxBusquedaAvanzada",
				data: {
					tipoObjeto: "<?= $tobj; ?>"
				},
				type: "GET",
				replace:false,
				push:false
			});

			$("#dlFiltroTObjeto").prop("disabled", <?= ($tobj == 3 ? 'false' : 'true') ?>);
			$("#codigoObjeto").focus();
		});
		<?php
	}

?>
</script>

<?php
Pjax::end();
?>

<div class="ctacte-index">

    <table width='100%'  border='0'  style='border-bottom:1px solid #ddd; margin-bottom:5px'>
    <tr>
    	<td><h1>Cuenta Corriente</h1></td>
    	<td align='right'>
    		<?= Html::a('Misc', ['//objeto/objeto/miscelaneas', 'id' => $obj_id], ['class' => 'btn btn-success', 'id' => 'botonMiscelaneas', 'disabled' => ($obj_id == null || $obj_id == "")]) ?>
    		<?php
    			if (utb::getExisteProceso(3301))
    				echo Html::a('Exp. Resumen', null,['class' => 'btn btn-success','style'=>'cursor:pointer', 'data-pjax' => "0", 'onclick' => 'exportarResumen();', 'disabled' => true, 'id' => 'botonExportarResumen']);
			?>
    		<?= Html::a('Exp. Completo', null, ['class' => 'btn btn-success','style'=>'cursor:pointer', 'data-pjax' => "0", 'onclick' => 'exportarCompleto();', 'disabled' => true, 'id' => 'botonExportarCompleto']); ?>
    	</td>
    </tr>
    </table>

    <div class="form" style='padding:10px; margin-bottom:5px;width:100%'>
    <?php

    $form = ActiveForm::begin(['action' => ['index'],'id' => 'frmCtaCteResumen',
    								'fieldConfig' => [
        										'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>",
        										],
        						]);

    ?>
    <table border='0' width='100%'>
    <tr>
    	<td>
    	<label> Tipo</label>
    	<?php
			$tobjeto= utb::GetTObj($obj_id);
			If ($tobjeto == null) $tobjeto = 3;
		?>
    	<?= Html::dropDownList('dlTObjeto', $tobjeto, utb::getAux('objeto_tipo'), ['class' => 'form-control', 'id' => 'dlTObjeto', 'onchange' => 'cambiaTipoObjeto($(this).val(), true);']); ?>

    	<label> Objeto</label>
    	<?= Html::input('text', 'codigoObjeto', $obj_id, ['class' => 'form-control', 'id' => 'codigoObjeto', 'style' => 'width:70px', 'maxlength'=>'8', 'onchange'=>'cambiaObjeto($(this).val());']); ?>
		</td>
		<td>
		<!-- boton de b�squeda modal -->
		<?php
		Modal::begin([
        	'id' => 'modalBusquedaAvanzadaObjeto',
        	'header' => '<h2>Búsqueda de Objeto</h2>',
			'toggleButton' => [
           		'label' => '<i class="glyphicon glyphicon-search"></i>',
               	'class' => 'bt-buscar'
           	],
           	'closeButton' => [
           		'label' => '<b>&times;</b>',
               	'class' => 'btn btn-danger btn-sm pull-right',
           	],
           	'size' => 'modal-lg',
        ]);

		Pjax::begin(['id' => 'pjaxBusquedaAvanzada']);
		$tobjeto= intval(Yii::$app->request->get('tipoObjeto', 0));
        echo $this->render('//objeto/objetobuscarav',['id' => 'cc', 'txCod' => 'codigoObjeto', 'txNom' => 'nombreObjeto', 'tobjeto' => $tobjeto, 'selectorModal' => '#modalBusquedaAvanzadaObjeto']);
		Pjax::end();

        Modal::end();
        ?>
        </td>
        <td>
        <!-- fin de boton de b�squeda modal -->
		<?= Html::input('text', 'nombreObjeto', str_replace('"','',utb::getNombObj("'$obj_id'")), ['class' => 'form-control','id'=>'nombreObjeto','style'=>'width:330px','disabled'=>'true']); ?>
		</td>
		<td>
		<?= Html::a('Objeto', ['objeto/objeto/view','id' => $obj_id], ['class' => 'btn btn-success', 'id' => 'botonIrAlObjeto', 'disabled' => ($obj_id == null || $obj_id == "")]) ?>
		</td>
		<td>&nbsp;
		<?= Html::a('CtaCte.NUM', ['index','obj_id' => utb::getCampo('objeto',"obj_id='".$obj_id."'",'num')], ['class' => 'btn btn-success', 'id' => 'botonCtaCteNUM', 'disabled' => ($obj_id == null || $obj_id == "" || utb::GetTObj($obj_id) == 3)]) ?>
		</td>
	</tr>
	</table>
	</div>

	<table border='0' width='100%'>
	<tr>
		<td valign='top'>
		<div class="form" style='padding:10px; margin-bottom:5px; font-size:11px'>
			<label>Desde:</label>
			<?= Html::input('text', 'txAnioDesde', $aniodesde, ['class' => 'form-control','id'=>'txAnioDesde','style'=>'width:40px','maxlength'=>'4']); ?>
			<?= Html::input('text', 'txCuotaDesde', $cuotadesde, ['class' => 'form-control','id'=>'txCuotaDesde','style'=>'width:35px','maxlength'=>'3']); ?>
			<label>Hasta:</label>
			<?= Html::input('text', 'txAnioHasta', $aniohasta, ['class' => 'form-control','id'=>'txAnioHasta','style'=>'width:40px','maxlength'=>'4']); ?>
			<?= Html::input('text', 'txCuotaHasta', $cuotahasta, ['class' => 'form-control','id'=>'txCuotaHasta','style'=>'width:35px','maxlength'=>'3']); ?>
			<br>
			<label title="Tipo de Objeto">TObj:</label>
			<?= Html::dropDownList('dlFiltroTObjeto', $tobjpersona, utb::getAux('objeto_tipo'), ['prompt'=>'Todos', 'class' => 'form-control','id'=>'dlFiltroTObjeto','style' =>'font-size:10px; padding:3px', 'disabled' => true]); ?>
			<label title="Estado">Est:</label>
			<?= Html::dropDownList('dlEstado', $est, utb::getAux('ctacte_test'), ['prompt'=>'Todos', 'class' => 'form-control','id'=>'dlEstado','style' =>'font-size:10px; padding:3px', ]); ?>
			<br>
			<?= Html::checkbox('ckPlanNoVig', $planvig,['id'=>'ckPlanNoVig','label'=>'Plan No Vig.', 'title' => 'Planes no vigentes'])?>&nbsp;
			<?= Html::checkbox('ckBajas',$bajas,['id'=>'ckBajas','label'=>'Bajas', 'title' => 'Planes dados de baja'])?>&nbsp;
			<?= Html::checkbox('ckSoloPerVenc', $pervenc,['id'=>'ckSoloPerVenc','label'=>'Sólo Venc.', 'title' => 'Sólo períodos vencidos'])?>&nbsp;
			<?= Html::checkbox('ckObjAct', $estobj,['id'=>'ckObjAct','label'=>'Obj.Act.', 'title' => 'Sólo objetos activos'])?>
			<br>
			<label title="Fecha Consolidación de Accesorios">Fecha Cons:</label>
    		<?=
				DatePicker::widget(
				[
					'id' => 'fchcons',
					'name' => 'fchcons',
					'dateFormat' => 'dd/MM/yyyy',
					'options' => ['style' => 'width:80px','class' => 'form-control'],
					'value' => $fchcons
				]
				);
			?>


			<?= Html::Button('Aplicar',['class' => 'btn btn-primary','style' => 'margin-left:30px', 'onClick' => 'aplicar()', 'disabled' => ($obj_id == null || $obj_id == ''), 'id' => 'botonAplicar']) ?>
    	</div>

    	<div class="form" style='padding:10px; height:37px; font-size:11px; color:#337ab7'>
    		<?php
    			Pjax::begin(['id' => 'pjaxBanderas', 'enableReplaceState' => false, 'enablePushState' => false]);

    			if(count($banderas) > 0){

    				if (array_key_exists('misc', $banderas) && $banderas['misc'] == 1) echo Html::a('<i class="glyphicon glyphicon-comment"></i>', ['objeto/objeto/miscelaneas','id' => $obj_id], ['class' => 'bt-buscar-label','style'=>'margin-right:3px','title' => 'Misceláneas', 'data-pjax' => 'false']);
	    			if (array_key_exists('emi', $banderas) && $banderas['emi'] == 1) echo "<i class='glyphicon glyphicon-file' style='margin-right:3px;cursor:pointer;' title = '".$arrayAccBand['emi']."'> </i>";
	    			if (array_key_exists('condona', $banderas) && $banderas['condona'] == 1) echo "<i class='glyphicon glyphicon-trash' style='margin-right:3px;cursor:pointer;' title = '".$arrayAccBand['condona']."'></i>";
	    			if (array_key_exists('reten', $banderas) && $banderas['reten'] == 1) echo "<i class='glyphicon glyphicon-minus-sign' style='margin-right:3px;cursor:pointer;' title = '".$arrayAccBand['reten']."'> </i>";
	    			if (array_key_exists('exerec', $banderas) && $banderas['exerec'] == 1) echo "<i class='glyphicon glyphicon-arrow-down' style='margin-right:3px;cursor:pointer;' title = '".$arrayAccBand['exerec']."'> </i>";
	    			if (array_key_exists('compensa', $banderas) && $banderas['compensa'] == 1) echo "<i class='glyphicon glyphicon-stats' style='margin-right:3px' title = '".$arrayAccBand['comp']."'> </i>";
					if (array_key_exists('debito', $banderas) && $banderas['debito'] == 1) echo "<i class='glyphicon glyphicon-barcode' style='margin-right:3px;cursor:pointer;' title = '".$arrayAccBand['debito']."'> </i>";
	    			if (array_key_exists('fisc', $banderas) && $banderas['fisc'] == 1) echo "<i class='glyphicon glyphicon-eye-open' style='margin-right:3px' title='fiscaliza'> </i>";
	    			if (array_key_exists('djfalta', $banderas) && $banderas['djfalta'] == 1) echo "<i class='glyphicon glyphicon-remove-sign' style='margin-right:3px;cursor:pointer;' title = '".$arrayAccBand['djfalta']."'> </i>";
	    			if (array_key_exists('judic', $banderas) && $banderas['judic'] == 1) echo Html::a('<i class="glyphicon glyphicon-bullhorn"></i>', ['#'], ['class' => 'bt-buscar-label','style'=>'margin-right:3px;color:#ff0000','title' => 'Apremio Pendiente', 'data-pjax' => 'false']);
	    			if (array_key_exists('judic', $banderas) && $banderas['judic'] == 2) echo "<i class='glyphicon glyphicon-bullhorn' style='margin-right:3px;cursor:pointer;' title = 'Apremio Pagado'> </i>";
	    			if (array_key_exists('inti', $banderas) && $banderas['inti'] == 1) echo Html::a('<i class="glyphicon glyphicon-warning-sign"></i>', ['#'], ['class' => 'bt-buscar-label','style'=>'margin-right:3px;color:#ff0000','title' => 'Incumplimiento Pendiente', 'data-pjax' => 'false']);
	    			if (array_key_exists('inti', $banderas) && $banderas['inti'] == 2) echo "<i class='glyphicon glyphicon-warning-sign' style='margin-right:3px;cursor:pointer;' title = 'Incumplimiento Pagado'> </i>";
	    			if (array_key_exists('conv', $banderas) && $banderas['conv'] == 1) echo "<i class='glyphicon glyphicon-list-alt' style='margin-right:3px;cursor:pointer;' title = '".$arrayAccBand['conv']."'> </i>";
	    			if (array_key_exists('conv', $banderas) && $banderas['conv'] == 2) echo "<i class='glyphicon glyphicon-save' style='margin-right:3px;cursor:pointer;' title = 'Convenio Decaído'> </i>";
	    			if (array_key_exists('conv', $banderas) && $banderas['conv'] == 3) echo "<i class='glyphicon glyphicon-check' style='margin-right:3px;cursor:pointer;' title = 'Convenio Pagado'> </i>";
    			}

    			echo Html::hiddenInput(null, null);//evita que se recargue la pagina si no hay banderas que mostrar
    			Pjax::end();

    		?>
    	</div>

    	</td>
    	<td valign='top' align='right'>
    	<div class="form" style='padding:10px;margin-left:5px;font-size:10px;min-height:192px;height:260px; width:500px; position:relative;'>

    	<?php

    	echo Html::hiddenInput(null, null, ['id' => 'filaSeleccionada']);

    	Pjax::begin(['id' => 'pjaxDatosPrincipales', 'enableReplaceState' => false, 'enablePushState' => false]);

    		$gridView= GridView::begin([
			'dataProvider' => $dataProvider,
			'id' => 'GrillaResumen',
			'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => function ($model,$key,$index,$grid)
        					{
        						return
        						[
									'onclick' => 'clickFilaPrincipal(' . $model['trib_id'] . ', ' . $model['plan_id'] . ', "' . $model['obj_id'] . '", ' . $model['subcta'] . ', $(this));',
									'oncontextmenu' => 'botonDerecho("menuContextualResumen","'.$model['obj_id'].'",'.$model['plan_id'].',0,0,'.$model['trib_id'].',0,'.$model['subcta'].','.utb::getTTrib($model['trib_id']).',"","",0);return false;'
        						];
        					},
			'columns' => [

					['attribute'=>'tobj_nom','contentOptions'=>['style'=>'width:30px;','class' => 'grilla'],'header' => 'Tipo'],
					['attribute'=>'obj_id','contentOptions'=>['style'=>'width:60px;','class' => 'grilla'],'header' => 'Objeto'],
            		['attribute'=>'subcta','contentOptions'=>['style'=>'width:20px; text-align:center','class' => 'grilla'],'header' => 'SCta'],
            		['attribute'=>'obj_dato','contentOptions'=>['style'=>'width:150px','class' => 'grilla'],'header' => 'Dato'],
            		['attribute'=>'trib_nom','contentOptions'=>['style'=>'width:90px','class' => 'grilla'],'header' => 'Tributo'],
            		['attribute'=>'plan_id','contentOptions'=>['align'=>'center','style'=>'width:30px; text-align:center','class' => 'grilla'],'header' => 'Conv'],
            		['attribute'=>'saldo_n', 'format' => ['decimal', 2], 'contentOptions'=>['style'=>'width:45px;text-align:right','class' => 'grilla'],'header' => 'Neg'],
            		['attribute'=>'saldo', 'format' => ['decimal', 2], 'contentOptions'=>['style'=>'width:65px;text-align:right','class' => 'grilla'],'header' => 'Saldo'],
            		['attribute'=>'trib_id','contentOptions'=>['style'=>'display:none','class' => 'grilla'],'headerOptions' => ['style' => 'display:none']],
    			],
    		'pager' => [
    				'options' => ['style' => 'padding:0px;margin:1px 0px -6px !important;', 'class' => 'pagination'],
    				'pagination' => new Pagination(['totalCount' => count($dataProvider->getModels())]),
    				'linkOptions' => [
    					'registerLinkTags' => false,
						'class' => 'linkPaginadorDatosPrincipales principal'
    				]
    			],


    		]);

    		GridView::end();
    	?>

    	<div style="position:absolute; bottom:0px; right:10px;left:0;">
    		<?php if (!$existectacte or $liquidaposteriorbaja) { ?>
				<div style="position: absolute;left: 10px;bottom: 5px;">
					<?php
						if (!$existectacte)
							echo Html::a('Reliquidar', null, [
									'class' => 'btn btn-success',
									'style'=>'cursor:pointer;margin-right:5px;',
									'onclick'=>'botonReliquidarExiteCtaCteClick()',
									'id' => 'botonReliquidarExiteCtaCte'
							]);

						if ($liquidaposteriorbaja and utb::getExisteProceso(3436)){

							//eliminar liquidaciones
							Modal::begin([
								'id' => 'modalBorrarLiquida',
								'header' => '<h2 style="text-align:left">Liquidaciones a Borrar</h2>',
								'toggleButton' => ['label' => 'Borrar Liquidación','class' => 'btn btn-success'],
								'closeButton' => [
								  'label' => '<b>&times;</b>',
								  'class' => 'btn btn-danger btn-sm pull-right',
								]
							]);
								echo GridView::widget([
									'dataProvider' => $liqBorrar,
									'id' => 'GrillaBorrarLiq',
									'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
									'headerRowOptions' => ['class' => 'grilla'],
									'columns' => [

											['attribute'=>'trib_nom','contentOptions'=>['class' => 'grilla'],'header' => 'Tributo'],
											['attribute'=>'anio','contentOptions'=>['class' => 'grilla','style'=>'width:50px;text-align:center'],'header' => 'Año'],
											['attribute'=>'cuota','contentOptions'=>['style'=>'width:50px;text-align:center', 'class' => 'grilla'],'header' => 'Cuota'],
											['attribute'=>'est','contentOptions'=>['style'=>'width:50px;text-align:center', 'class' => 'grilla'],'header' => 'Estado'],
										]
									]);
								echo "<br>"	;
								echo Html::a('Borrar Liquidación', null, [
									'class' => 'btn btn-success',
									'style'=>'cursor:pointer',
									'onclick'=>'botonBorrarLiquidaClick()',
									'id' => 'botonBorrarLiquida'
								]);

								echo Html::Button('Cancelar',['class' => 'btn btn-primary', 'style'=>'margin-left:5px', 'onClick' => "$('#modalBorrarLiquida').modal('hide')"]);
							Modal::end();
						}
					?>
				</div>
			<?php } ?>
			<label>Deuda:</label>
    		<?= Html::input('text', 'txDeuda', $deuda, ['class' => 'form-control solo-lectura', 'id' => 'txDeuda', 'style' => 'width:70px; text-align:right;', 'disabled' => true]); ?>
    	</div>


    	<script type="text/javascript">
    	$(document).ready(function(){

	    	//evento onclick de cada link del paginador de la tabla de resumen
	    	$(".linkPaginadorDatosPrincipales").each(function(){

				$(this).attr("href", "#");
				$(this).click(function(e){

					e.preventDefault();
					e.stopPropagation();

					if($(this).hasClass("principal"))
						aplicar($(this).data("page"), false);
				});
			});


			$("#GrillaResumen table tbody td").contextMenu({
				menuSelector: "#menuContextualResumen"
			});
		});
    	</script>
    	<?php

    	//exportar resumen
		Modal::begin([
		    'id' => 'modalResumenCuentaCorriente',
			'header' => '<h2>Exportar Resumen</h2>',
		    'closeButton' => [
		      'label' => '<b>&times;</b>',
		      'class' => 'btn btn-danger btn-sm pull-right',
		    ]
		]);

		Pjax::begin(['id' => 'pjaxExportarResumen', 'enableReplaceState' => false, 'enablePushState' => false]);
		echo $this->render('//site/exportar',['titulo'=>'Resumen de Cuenta Corriente','desc'=>$descripcionExportarResumen,'grilla'=>'modalResumenCuentaCorriente']);
		Pjax::end();

		Modal::end();


    	Pjax::end();
		?>

    	<?php
    	ActiveForm::end();
    	?>
    	</div>


    	</td>
    <tr>
    </table>
    <div id="contenedorErrores" style="display:none; margin-top:5px;" class="error-summary"></div>

    <div class="form" style='padding:10px; margin-top:5px; widht:100%'>

    	<?php
    	Pjax::begin(['id' => 'divTablaMovimientos']);

    	echo GridView::widget([
			'dataProvider' => $dataProviderMovimientos,
			'id' => 'tablaMovimientos',
			'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
			'headerRowOptions' => ['class' => 'grilla'],
        	'rowOptions' => function ($model,$key,$index,$grid)
        					{
        						$array = [
        									'ctacte_id' => $model['ctacte_id'],
        									'f' => Fecha::bdToUsuario($model['fchcons']),
        									'p' => $model['plan_id'],
        									'sc' => $model['subcta'],
        									'a' => $model['anio'],
        									'c' => $model['cuota'],
        									'e' => $model['estado'],
        									'b' => $model['baja']
        								];

        						$url= BaseUrl::toRoute(['ctactedet']);

        						foreach($array as $clave => $valor)
        							$url .= "&$clave=$valor";

        						$cond = "obj_id='".$model['obj_id']."' and anio=".$model['anio']." and est='D' and trib_id=".$model['trib_id'] ." and cuota BETWEEN ".$model['cuota']." and ".($model['cuota']+2);
								$trim = utb::getCampo("ctacte",$cond,"count(*)");

								return
        						[
									'ondblclick' => 'window.location = "'.$url.'&num="+$("#codigoObjeto").val()',
									'oncontextmenu' => 'botonDerecho("menuContextualCompleto","' . $model['obj_id'] . '", ' . $model['plan_id'] . ','.$model['anio'].','.$model['cuota'].','. $model['trib_id'] .','.$model['ctacte_id'].','.$model['subcta'].', '.$model['tipo_tributo'].', "'. str_replace('"','',$model['nombre_objeto']) .'", "' . $model['est'] . '",' .$trim . ');' .
											'return false;'
        						];
        					},
			'columns' => [

					['attribute'=>'anio','contentOptions'=>['class' => 'grilla'],'header' => 'Año'],
					['attribute'=>'cuota','contentOptions'=>['style'=>'width:30px;text-align:center', 'class' => 'grilla'],'header' => 'Cuo'],
            		['attribute'=>'est','contentOptions'=>['style'=>'width:20px;text-align:center', 'class' => 'grilla'],'header' => 'Est'],
            		['attribute'=>'nominal', 'format' => ['decimal', 2], 'contentOptions'=>['style'=>'width:80px;text-align:right', 'class' => 'grilla'],'header' => 'Nominal'],
            		['attribute'=>'nominalcub', 'format' => ['decimal', 2], 'contentOptions'=>['style'=>'width:80px;text-align:right', 'class' => 'grilla'],'header' => 'Cubierto'],
            		['attribute'=>'multa','contentOptions'=>['style'=>'width:40px;text-align:right', 'class' => 'grilla'],'header' => 'Multa'],
            		['attribute'=>'accesor', 'format' => ['decimal', 2], 'contentOptions'=>['style'=>'width:40px;text-align:right', 'class' => 'grilla'],'header' => 'Accesor'],
            		['attribute'=>'pagado', 'format' => ['decimal', 2], 'contentOptions'=>['style'=>'width:80px;text-align:right', 'class' => 'grilla'],'header' => 'Pagado'],
            		['attribute'=>'saldo', 'format' => ['decimal', 2], 'contentOptions'=>['style'=>'width:80px;text-align:right', 'class' => 'grilla'],'header' => 'Saldo'],
            		['attribute'=>'fchemi', 'format' => ['date', 'php:d/m/Y'], 'contentOptions'=>['style'=>'width:80px;text-align:center', 'class' => 'grilla'],'header' => 'FchEmi'],
            		['attribute'=>'fchvenc', 'format' => ['date', 'php:d/m/Y'],'contentOptions'=>['style'=>'width:80px;text-align:center', 'class' => 'grilla'],'header' => 'FchVenc'],
            		['attribute'=>'fchpago', 'format' => ['date', 'php:d/m/Y'],'contentOptions'=>['style'=>'width:80px;text-align:center', 'class' => 'grilla'],'header' => 'FchPago'],
            		['attribute'=>'caja_id','contentOptions'=>['style'=>'width:30px;text-align:center', 'class' => 'grilla'],'header' => 'Caja'],
            		['attribute'=>'baja','contentOptions'=>['style'=>'width:20px', 'class' => 'grilla'],'header' => '*'],
            		['attribute'=>'expe','contentOptions'=>['style'=>'width:60px', 'class' => 'grilla'],'header' => 'Expte'],
            		['attribute'=>'obs_asc','contentOptions'=>['style'=>'width:20px;text-align:center', 'class' => 'grilla'],'header' => 'Ob'],
    			]
    		]);

    		Modal::begin([
	            'id' => 'modalExportarCompleto',
				'header' => '<h2>Exportar Completo</h2>',
	            'closeButton' => [
	              'label' => '<b>&times;</b>',
	              'class' => 'btn btn-danger btn-sm pull-right',
	            ]
	        ]);

			Pjax::begin(['id' => 'pjaxExportarCompleto', 'enableReplaceState' => false, 'enablePushState' => false]);
	        echo $this->render('//site/exportar',['titulo'=>'Cuenta Corriente Completa','desc'=>$descripcionExportarCompleto,'grilla'=>'modalExportarCompleto']);
			Pjax::end();

	        Modal::end();

    		?>
    		<script type="text/javascript">
    		$(document).ready(function(){

    			$("#tablaMovimientos table tbody td").contextMenu({
					menuSelector: "#menuContextualCompleto"
				});
    		});

    		</script>
    		<?php
    	Pjax::end();
    	?>
    </div>

</div>

<?php
	if(isset($mensajeError) && $mensajeError != ''){

		Alert::begin([
			'id' => 'mensajeCuentaCorriente',
			'options' => [
			'class' => 'alert-info',
			'style' => ($mensajeError != '' ? 'display:block;' : 'display:none;') . 'margin-top:5px;'
			],
		]);

		echo $mensajeError;
		?>

		<script type="text/javascript">
		setTimeout(function(){$("#mensajeCuentaCorriente").fadeOut();}, 5000);
		</script>
		<?php

		Alert::end();
	}
?>

<!-- Menus contextuales -->

    <div id='menuContextualResumen' class="menuContextual">
    	<p><?= Html::a('Imprimir Resumen', null, ['class' => 'menu_contextual','target' => '_black','data-pjax' => "0", 'id' => 'botonImprimirResumenMenuContextual']); ?></p>
		<p><?= Html::a('Cupón de Pago', null, ['class' => 'menu_contextual','data-pjax' => "0", 'id' => 'botonCuponPago']); ?></p>
    	<p><?= Html::a('Ir al Objeto', null, ['class' => 'menu_contextual', 'id' => 'botonIrAlObjetoMenuContextual']); ?></p>
    	<p><?= Html::a('Consultar Cta. Cte. Objeto', null, ['class' => 'menu_contextual', 'id' => 'botonConsultarCuentaCorrienteMenuContextual']); ?></p>
    	<p><a href="#" class="menu_contextual" id="botonIrPlanMenuContextual"></a></p>
    </div>

    <div id='menuContextualCompleto' class="menuContextual">

    	<p><?= Html::a('Imprimir Listado de Períodos', null, ['class' => 'menu_contextual','target' => '_blank','data-pjax' => "0", 'id' => 'botonImprimirListadoPeriodosMenuContextual']) ?></p>
    	<p><?= Html::a('Imprimir Períodos Impagos', null, ['class' => 'menu_contextual','target' => '_blank','data-pjax' => "0", 'id' => 'botonImprimirPeriodosImpagosMenuContextual']) ?></p>
    	<p><?= Html::a('Imprimir Completo', null, ['class' => 'menu_contextual','target' => '_blank','data-pjax' => "0", 'id' => 'botonImprimirCompletoMenuContextual']) ?></p>
    	<p>
    	<?php
    		if (utb::getExisteProceso(3433))
    			echo Html::a('Imprimir Comprobante', null, ['class' => 'menu_contextual','target' => '_blank','data-pjax' => "0", 'id' => 'botonImprimirComprobanteMenuContextual'])
    	?>
    	</p>
		<p>
    	<?php
    		if (utb::getExisteProceso(3433))
    			echo Html::a('Imprimir Comprobante de 3 cuotas', null, ['class' => 'menu_contextual','target' => '_blank','data-pjax' => "0", 'id' => 'botonImprimirComprobante3CuotaMenuContextual'])
    	?>
    	</p>
    	<div style="border-bottom: 1px solid #ddd;margin-bottom:5px" ></div>
    	<p><?= Html::a('Constancia de Período Pago', null, ['id'=>'MCCConsPago', 'class' => 'menu_contextual','data-pjax' => "0", 'target' => '_blank', 'id' => 'botonConstanciaPagoMenuContextual']) ?></p>
    	<p><?= Html::a('Consultar Detalle', null, ['class' => 'menu_contextual', 'id' => 'botonCuentaCorrienteDetalleMenuContextual']) ?></p>
    	<div style="border-bottom: 1px solid #ddd;margin-bottom:5px" ></div>
    	<p>
    	<?php
    		if (utb::getExisteProceso(3341))
    			echo Html::a('Generar Convenio de Pago', null, ['class' => 'menu_contextual', 'id' => 'botonPlanNuevoMenuContextual'])
		?>
		</p>
    	<p>
    	<?php
    		if (utb::getExisteProceso(3441))
    			echo Html::a('Generar Facilidad', null, ['class' => 'menu_contextual', 'id' => 'botonGenerarFacilidadMenuContextual'])
		?>
		</p>
    	<div style="border-bottom: 1px solid #ddd;margin-bottom:5px;display:none" ></div>
    	<p id='opReliq'>
    	<?php
    		if (utb::getExisteProceso(3314))
    			echo Html::a('Reliquidar', null, ['class' => 'menu_contextual','style'=>'cursor:pointer', 'onclick'=>'$("#modalReliquidar").modal("show");', 'id' => 'botonReliquidarMenuContextual'])
		?>
		</p>
    	<p>
    	<?php
    		if (utb::getExisteProceso(3312))
    			echo Html::a('Eliminar Liquidación', null,['class' => 'menu_contextual','style'=>'cursor:pointer', 'data-toggle' => 'modal', 'data-target' => '#modalEliminarLiquidacion', 'id' => 'botonEliminarLiquidacionMenuContextual'])
		?>
		</p>
		<p>
    	<?php
    		if (utb::getExisteProceso(3312))
    			echo Html::a('Eliminar ReLiquidación', null,['class' => 'menu_contextual','style'=>'cursor:pointer', 'data-toggle' => 'modal', 'data-target' => '#modalEliminarReLiquidacion', 'id' => 'botonEliminarReLiquidacionMenuContextual'])
		?>
		</p>
		<p>
		<?php
    		echo Html::a('Editar Período', null,['class' => 'menu_contextual','style'=>'cursor:pointer', 'data-toggle' => 'modal', 'data-target' => '#modalEditarPeriodo', 'id' => 'botonEditarPeriodoMenuContextual'])
		?>
		</p>
		<p id='opDDJJFalatante'>
    	<?php
    		echo Html::a('Eliminar DJ Faltante', null, ['class' => 'menu_contextual','style'=>'cursor:pointer', 'onclick'=>'$("#modalDDJJFaltante").modal("show");', 'id' => 'botonDDJJFaltante'])
		?>
		</p>
		<p id='opEventual'>
    	<?php
    		echo Html::a('Ir a Eventual', null, ['class' => 'menu_contextual','style'=>'cursor:pointer', 'id' => 'botonIrEventual']);
		?>
		</p>
    </div>
<!-- Fin Menus contextuales -->



<?php
/**
 * MODALES
 */

//reliquidar
Modal::begin([
	'id' => 'modalReliquidar',
	'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Reliquidar</h2>',
	'closeButton' => [
          'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
          'class' => 'btn btn-danger btn-sm pull-right',
        ]
	]);

	echo $this->render('emisionliq', ['obj_id' => $obj_id, 'subcta' => $subcta, 'selectorModal' => '#modalReliquidar']);

Modal::end();

Modal::begin([
	'id' => 'modalEliminarLiquidacion',
	'size' => 'modal-sm',
	'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Eliminar Liquidación</h2>',
	'closeButton' => [
          'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
          'class' => 'btn btn-danger btn-sm pull-right',
        ]
	]);

	$form = ActiveForm::begin(['action' => ['eliminarliq'],'id' => 'formEliminarLiquidacion',
							'fieldConfig' => ['template' => "{label}\n<div class=\"col-lg-3\">{input}</div>"]]);
		?>
		<div class="text-center">
			<b>¿Está seguro de eliminar la/s liquidación/nes seleccionada/s?</b>
		</div>
		<br><br>
		<div class="text-center">
			<b>Ingrese el motivo por el cual realiza la baja:</b>
		</div>
		<?= Html::hiddenInput('codigoTributoEliminarLiquidacion', null, ['id' => 'codigoTributoEliminarLiquidacion']); ?>
		<?= Html::hiddenInput('cuentaCorrienteEliminarLiquidacion', null, ['id' => 'cuentaCorrienteEliminarLiquidacion']); ?>
		<?= Html::hiddenInput('obj_id', null, ['id' => 'codigoObjetoEliminarLiquidacion']); ?>
		<?= Html::input('text', 'motivoEliminarLiquidacion', null, ['class' => 'form-control', 'id' => 'eliminarLiquidacionMotivo', 'style' => 'width:250px;', 'maxlength' => 150]); ?>
		<br><br>
		<div class="text-center">
		<?= Html::button('Aceptar', ['class' => 'btn btn-success', 'id' => 'btEliminarLicAceptar', 'onclick' => 'eliminarLiquidacion();']); ?>
		&nbsp;&nbsp;
		<?= Html::button('Cancelar', ['class' => 'btn btn-primary', 'id' => 'btEliminarLicCanc', 'onclick' => '$("#modalEliminarLiquidacion").modal("hide")']); ?>
		</div>
		<br>

		<div id="contenedorErroresEliminarLiquidacion" class="error-summary" style="display:none; margin-top:5px;"></div>
		<?php

 	ActiveForm::end();

Modal::end();

Modal::begin([
	'id' => 'modalEliminarReLiquidacion',
	'size' => 'modal-sm',
	'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Eliminar Re Liquidación</h2>',
	'closeButton' => [
          'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
          'class' => 'btn btn-danger btn-sm pull-right',
        ]
	]);

	$form = ActiveForm::begin(['action' => ['eliminarreliq'],'id' => 'formEliminarReLiquidacion',
							'fieldConfig' => ['template' => "{label}\n<div class=\"col-lg-3\">{input}</div>"]]);
		?>
		<div class="text-center">
			<b>¿Está seguro de eliminar la reliquidación seleccionada?</b>
		</div>
		
		<?= Html::hiddenInput('codigoTributoEliminarReLiquidacion', null, ['id' => 'codigoTributoEliminarReLiquidacion']); ?>
		<?= Html::hiddenInput('cuentaCorrienteEliminarReLiquidacion', null, ['id' => 'cuentaCorrienteEliminarReLiquidacion']); ?>
		<?= Html::hiddenInput('obj_id', null, ['id' => 'codigoObjetoEliminarReLiquidacion']); ?>
		<br><br>
		<div class="text-center">
		<?= Html::button('Aceptar', ['class' => 'btn btn-success', 'id' => 'btEliminarReLicAceptar', 'onclick' => 'eliminarReLiquidacion();']); ?>
		&nbsp;&nbsp;
		<?= Html::button('Cancelar', ['class' => 'btn btn-primary', 'id' => 'btEliminarReLicCanc', 'onclick' => '$("#modalEliminarReLiquidacion").modal("hide")']); ?>
		</div>
		<br>

		<div id="contenedorErroresEliminarReLiquidacion" class="error-summary" style="display:none; margin-top:5px;"></div>
		<?php

 	ActiveForm::end();

Modal::end();

Modal::begin([
	'id' => 'modalEditarPeriodo',
	'size' => 'modal-sm',
	'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Editar Periodo</h2>',
	'closeButton' => [
          'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
          'class' => 'btn btn-danger btn-sm pull-right',
        ]
	]);

	$form = ActiveForm::begin(['action' => ['editarperiodo'],'id' => 'formEditarPeriodo',
							'fieldConfig' => ['template' => "{label}\n<div class=\"col-lg-3\">{input}</div>"]]);
		?>
		<div class="text-center">
			<b>Ingrese los datos del periodo:</b>
		</div>
		<?= Html::hiddenInput('cuentaCorrienteEditarPeriodo', null, ['id' => 'cuentaCorrienteEditarPeriodo']); ?>
		<?= Html::hiddenInput('obj_id', null, ['id' => 'codigoObjetoEditarPeriodo']); ?>
		<p align='center'>
			<?= Html::input('text', 'periodoAnio', null, ['class' => 'form-control', 'id' => 'periodoAnio', 'style' => 'width:70px;text-align:center', 'maxlength' => 4,'readonly'=>true]); ?>
			<?= Html::input('text', 'periodoCuota', null, ['class' => 'form-control', 'id' => 'periodoCuota', 'style' => 'width:50px;text-align:center', 'maxlength' => 2]); ?>
		</p>
		<div class="text-center">
			<?= Html::button('Aceptar', ['class' => 'btn btn-success', 'id' => 'btEditarPeriodoAceptar', 'onclick' => 'eliminarEditarPeriodo();']); ?>
			&nbsp;&nbsp;
			<?= Html::button('Cancelar', ['class' => 'btn btn-primary', 'id' => 'btEditarPeriodoCanc', 'onclick' => '$("#modalEditarPeriodo").modal("hide")']); ?>
		</div>
		<br>

		<div id="contenedorErroresEditarPeriodo" class="error-summary" style="display:none; margin-top:5px;"></div>
		<?php

 	ActiveForm::end();

Modal::end();

Modal::begin([
	'id' => 'modalCuponPago',
	'size' => 'modal-sm',
	'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Cupón de Pago</h2>',
	'closeButton' => [
          'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
          'class' => 'btn btn-danger btn-sm pull-right',
        ]
	]);

	$form = ActiveForm::begin(['action' => ['cuponpago'],'id' => 'formCuponPago','options'=>['target' => '_black'],
							'fieldConfig' => ['template' => "{label}\n<div class=\"col-lg-3\">{input}</div>"]]);
		?>
		<div class="text-center">
			<b>Seleccione la Fecha de Vencimiento:</b>
		</div>
		<?= Html::hiddenInput('tributoCuponPago', null, ['id' => 'tributoCuponPago']); ?>
		<?= Html::hiddenInput('objetoCuponPago', null, ['id' => 'objetoCuponPago']); ?>
		<p align='center'>
			<?=
				DatePicker::widget(
				[
					'id' => 'fechaCuponPago',
					'name' => 'fechaCuponPago',
					'dateFormat' => 'dd/MM/yyyy',
					'options' => ['style' => 'width:80px','class' => 'form-control']
				]
				);
			?>
		</p>
		<div class="text-center">
			<?= Html::button('Aceptar', ['class' => 'btn btn-success', 'id' => 'btAceptarCuponPago', 'onclick' => 'btAceptarCuponPagoClick();']); ?>
			&nbsp;&nbsp;
			<?= Html::button('Cancelar', ['class' => 'btn btn-primary', 'id' => 'btEditarPeriodoCanc', 'onclick' => '$("#modalCuponPago").modal("hide")']); ?>
		</div>
		<br>

		<div id="contenedorErroresCuponPago" class="error-summary" style="display:none; margin-top:5px;"></div>
		<?php

 	ActiveForm::end();

Modal::end();

Modal::begin([
	'id' => 'modalDDJJFaltante',
	'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Eliminar Faltante DJ</h2>',
	'closeButton' => [
          'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
          'class' => 'btn btn-danger btn-sm pull-right',
        ]
	]);

	echo $this->render('ddjjfaltante', ['obj_id' => $obj_id, 'subcta' => $subcta, 'selectorModal' => '#modalDDJJFaltante']);

Modal::end();

Modal::begin([
	'id' => 'modalGenerarFacilidad',
	'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Generar Facilidad</h2>',
	'closeButton' => [
          'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
          'class' => 'btn btn-danger btn-sm pull-right',
        ],
	'size'	=> 'modal-sm'
	]);

	$form = ActiveForm::begin(['action' => ['generaracilida'],'id' => 'formGenerarFacilidad',
							'fieldConfig' => ['template' => "{label}\n<div class=\"col-lg-3\">{input}</div>"]]);
		?>
		<div class="text-center">
			<b>Seleccione Fecha de Vencimiento:</b>
			<?= Html::hiddenInput('codigoTributoGenerarFacilidad', null, ['id' => 'codigoTributoGenerarFacilidad']); ?>
			<?= Html::hiddenInput('codigoObjetoGenerarFacilidad', null, ['id' => 'codigoObjetoGenerarFacilidad']); ?>
			<?= Html::hiddenInput('periododesdeGenerarFacilidad', null, ['id' => 'periododesdeGenerarFacilidad']); ?>
			<?= Html::hiddenInput('periodohastaGenerarFacilidad', null, ['id' => 'periodohastaGenerarFacilidad']); ?>
			<?= DatePicker::widget([
					'name' => 'fchvencGenerarFacilidad',
					'id' => 'fchvencGenerarFacilidad',
					'dateFormat' => 'dd/MM/yyyy',
					'value' => Fecha::bdToUsuario(date('d/m/Y')),
					'options' => [
							'class'=>'form-control',
							'style' => 'width:80px;text-align:center'
						],
				]);
			?>
		</div>
		<br><br>
		<div class="text-center">
		<?= Html::button('Aceptar', ['class' => 'btn btn-success', 'id' => 'btGenerarFacilidadAceptar', 'onclick' => 'generarFacilidadClick();']); ?>
		&nbsp;&nbsp;
		<?= Html::button('Cancelar', ['class' => 'btn btn-primary', 'id' => 'btGenerarFacilidadCanc', 'onclick' => '$("#modalGenerarFacilidad").modal("hide")']); ?>
		</div>
		<br>

		<div id="contenedorErroresGenerarFacilidad" class="error-summary" style="display:none; margin-top:5px;"></div>
		<?php

 	ActiveForm::end();

Modal::end();
?>


<script>
/*
* Limpia la tabla de resumen
*/
function limpiarTablaPrincipal(){

	$("#GrillaResumen tbody").empty();

	//se agrega la fila con el texto de que no hay datos
	var $div= $("<div />");
	$div.addClass("empty");
	$div.text("No se encontraron resultados.");

	$div.appendTo($("<td />", {"colspan":9}).appendTo($("<tr />").appendTo($("#GrillaResumen tbody"))));

	$("#GrillaResumen .pagination").empty();
	$("#txDeuda").val("0.00");
}

/*
* Limpia la tabla de movimientos
*/
function limpiarTablaMovimientos(){

	$("#tablaMovimientos tbody").empty();
	$("#tablaMovimientos .pagination").empty();

	var $div= $("<div />");
	$div.addClass("empty");
	$div.text("No se encontraron resultados.");

	$div.appendTo($("<td />", {"colspan":16}).appendTo($("<tr />").appendTo($("#tablaMovimientos tbody"))));
}

/*
* Borra las banderas
*/
function limpiarBanderas(){

	$("#pjaxBanderas").empty();
}


function eliminarLiquidacion()
{
	error = [];
	if ($("#eliminarLiquidacionMotivo").val() == "") error.push("Ingrese un motivo de baja");

	if (error.length == 0)
		$("#formEliminarLiquidacion").submit();
	else
		mostrarErrores(error, $("#contenedorErroresEliminarLiquidacion"));
}

function eliminarReLiquidacion()
{
	error = [];
	
	if (error.length == 0)
		$("#formEliminarReLiquidacion").submit();
	else
		mostrarErrores(error, $("#contenedorErroresEliminarReLiquidacion"));
}

function eliminarEditarPeriodo()
{
	error = [];
	if ($("#periodoAnio").val() == "") error.push("Ingrese un Año");
	if ($("#periodoCuota").val() == "") error.push("Ingrese una Cuota");

	if (error.length == 0)
		$("#formEditarPeriodo").submit();
	else
		mostrarErrores(error, $("#contenedorErroresEditarPeriodo"));
}

/*
* Carga las banderas
*/
function cargarBanderas(){

	var datos= obtenerDatos();

	$.pjax.reload({
		container: "#pjaxBanderas",
		type: "GET",
		replace: false,
		push: false,
		data: {
			"obj_id": datos.codigoObjeto,
			"perdesde": datos.periodoDesde,
			"perhasta": datos.periodoHasta,
			"cargarBanderas": true
		}
	});
}

/*
* Evento onClick del boton "Aplicar"
*
* @param pagina integer - Numero de página
* @param banderas boolean - Si se deben cargar las banderas. Si no se cargan, se dejan las que estan.
*/
function aplicar(pagina, banderas){

	var error= new Array();
	var datos= obtenerDatos();
	pagina= parseInt(pagina);
	if(!banderas && banderas !== false) banderas= true;

	pagina= (isNaN(pagina) || pagina < 0) ? 0 : pagina;


	limpiarTablaPrincipal();
	limpiarTablaMovimientos();



	if (datos.nombreObjeto == "" || datos.codigoObjeto == "") error.push("Ingrese un objeto.");
	if (datos.anioDesde == 0 || datos.cuotaDesde == 0) error.push("Complete el periódo desde");
	if (datos.anioHasta == 0 || datos.cuotaHasta == 0) error.push("Complete el periódo hasta");
	if( !isDate(datos.fechaConsolidacion) ) error.push("La fecha es incorrecta");

	if (isNaN(datos.periodoDesde) || isNaN(datos.periodoHasta) || datos.periodoDesde > datos.periodoHasta) error.push("Rango de período mal ingresado");

	if (error == ""){

		removerErrores();

		$.pjax.reload({
			container: "#pjaxDatosPrincipales",
			url: "<?= BaseUrl::toRoute(['index']); ?>&page=" + pagina,
			type: "POST",
			replace: false,
			push: false,
			data: {
				"obj_id": datos.codigoObjeto,
				"perdesde": datos.periodoDesde,
				"perhasta": datos.periodoHasta,
				"fchcons": datos.fechaConsolidacion,
				"est": datos.estado,
				"planvig": datos.planesVigentes,
				"bajas": datos.bajas,
				"pervenc": datos.periodosVencidos,
				"tobjpersona": datos.tipoObjeto,
				"cargarDatosPrincipales": true,
				"estobj": datos.estobj,
			}
		});


		if(banderas){

			$("#pjaxDatosPrincipales").on("pjax:complete", function(){

				cargarBanderas();

				$("#botonExportarResumen").removeAttr("disabled");
				$("#pjaxDatosPrincipales").off("pjax:complete");
			});
		}
	}else {
		mostrarErrores(error, "#contenedorErrores");
	}

}


function botonDerecho(menuContextual, codigoObjeto, codigoPlan, anio, cuota, codigoTributo, cuentaCorriente, subCuenta, tipoTributo, nombreObjeto, estado,trimestre){

	var datos= obtenerDatos();

	if (menuContextual == "menuContextualResumen"){

		//modifica la url a la que redirige la opcion para imprimir el resumen
		$("#botonImprimirResumenMenuContextual").attr("href", "<?= BaseUrl::toRoute(['imprimirresumen']); ?>"
															+ "&o=" + codigoObjeto
															+ "&f=" + datos.fechaConsolidacion
															+ "&d=" + datos.periodoDesde
															+ "&h=" + datos.periodoHasta
															+ "&e=" + datos.estado
															+ "&p=" + datos.planesVigentes
															+ "&b=" + datos.bajas
															+ "&v=" + datos.periodosVencidos
															+ "&t=" + datos.tipoObjeto

													);

		if (tipoTributo == 1 && subCuenta == 0){
			$("#botonCuponPago").css("display","block");
			$("#botonCuponPago").attr("onclick", '$("#modalCuponPago").modal("show");');
			$("#objetoCuponPago").val(codigoObjeto);
			$("#tributoCuponPago").val(codigoTributo);
		}else {
			$("#botonCuponPago").css("display","none");
		}

		$("#botonIrAlObjetoMenuContextual").attr("href", "<?= BaseUrl::toRoute(['//objeto/objeto/view']); ?>" + "&id=" + codigoObjeto);
		$("#botonConsultarCuentaCorrienteMenuContextual").attr("href", "<?= BaseUrl::toRoute(['index']); ?>" + "&obj_id=" + codigoObjeto);


		var url= "<?= BaseUrl::toRoute(['irplan']); ?>&id=" + codigoPlan + "&trib=" + codigoTributo;
		$("#botonIrPlanMenuContextual").attr("href", url);

		if ( codigoTributo == 1 || codigoTributo == 2 || codigoTributo == 3 ){
		   var texto= codigoTributo == 1 ? "Ir al Plan" : (codigoTributo == 2 ? "Ir a la Facilidad" : "Ir a la Contr. por Mejoras");
		   $("#botonIrPlanMenuContextual").text(texto);
		}

	} else if (menuContextual == "menuContextualCompleto"){

		$("#botonImprimirListadoPeriodosMenuContextual").attr("href", "<?= BaseUrl::toRoute(['imprimirlistper']); ?>&t=" + codigoTributo + "&p=" + codigoPlan + "&o=" + codigoObjeto + "&sc=" + subCuenta + "&f=" + datos.fechaConsolidacion + "&d=" + datos.periodoDesde + "&h=" + datos.periodoHasta + "&e=" + datos.estado + "&b=" + datos.bajas + "&v=" + datos.periodosVencidos);
		$("#botonImprimirPeriodosImpagosMenuContextual").attr("href", "<?= BaseUrl::toRoute(['imprimirperimpagos']); ?>&o=" + codigoObjeto + "&f=" + datos.fechaConsolidacion + "&d=" + datos.periodoDesde + "&h=" + datos.periodoHasta);
		$("#botonImprimirCompletoMenuContextual").attr("href", "<?= BaseUrl::toRoute(['imprimircompleto']); ?>&o=" + codigoObjeto + "&f=" + datos.fechaConsolidacion + "&d=" + datos.periodoDesde + "&h=" + datos.periodoHasta + "&e=" + datos.estado);

		//if(estado != 'P'){
			$("#botonImprimirComprobanteMenuContextual").attr("href", "<?= BaseUrl::toRoute(['imprimircomprobante']); ?>&t=" + codigoTributo + "&o=" + codigoObjeto + "&c=" + cuota + "&a=" + anio + "&cc=" + cuentaCorriente + "&sc=" + subCuenta);
			$("#botonImprimirComprobanteMenuContextual").removeClass("hidden");
		//}
		//else $("#botonImprimirComprobanteMenuContextual").addClass("hidden");

		if(estado != 'P' && (cuota==1 || cuota==4 || cuota==7 || cuota==10) && (tipoTributo == 1 || tipoTributo == 4) && trimestre==3){
			$("#botonImprimirComprobante3CuotaMenuContextual").attr("href", "<?= BaseUrl::toRoute(['imprimircomprobante']); ?>&t=" + codigoTributo + "&o=" + codigoObjeto + "&c=" + cuota + "&a=" + anio + "&cc=" + cuentaCorriente + "&sc=" + subCuenta + "&sem=1");
			$("#botonImprimirComprobante3CuotaMenuContextual").removeClass("hidden");
		}
		else $("#botonImprimirComprobante3CuotaMenuContextual").addClass("hidden");

		if(estado == 'P'){

			$("#botonConstanciaPagoMenuContextual").attr("href", "<?= BaseUrl::toRoute(['constanciapago']); ?>&cc=" + cuentaCorriente + "&o=" + codigoObjeto);
			$("#botonConstanciaPagoMenuContextual").removeClass("hidden");
		}
		else ($("#botonConstanciaPagoMenuContextual").addClass("hidden"));

		$("#botonCuentaCorrienteDetalleMenuContextual").attr("href", "<?= BaseUrl::toRoute(['ctactedet']); ?>&ctacte_id=" + cuentaCorriente + "&num=" + $("#codigoObjeto").val());
		$("#botonPlanNuevoMenuContextual").attr("href", "<?= BaseUrl::toRoute(['plannuevo']); ?>&o=" + codigoObjeto);
		if(estado != 'C' && codigoTributo != 1){
			$("#botonGenerarFacilidadMenuContextual").removeClass("hidden");
			$("#botonGenerarFacilidadMenuContextual").attr("onclick", '$("#modalGenerarFacilidad").modal("show");');
			$("#codigoObjetoGenerarFacilidad").val(codigoObjeto);
			$("#codigoTributoGenerarFacilidad").val(codigoTributo);
			$("#periododesdeGenerarFacilidad").val(datos.periodoDesde);
			$("#periodohastaGenerarFacilidad").val(datos.periodoHasta);
		}
		else ($("#botonGenerarFacilidadMenuContextual").addClass("hidden"));

		if(tipoTributo != 1)
			$("#botonReliquidarMenuContextual").css("display", "none");
		else{

			var $modal= $("#modalReliquidar");
			$modal.data("codigo-objeto", codigoObjeto);
			$modal.data("nombre-objeto", nombreObjeto);
			$modal.data("subcuenta", subCuenta);
			$modal.data("codigo-tributo", codigoTributo);
			$modal.data("anio", anio);
			$modal.data("cuota", cuota);

			$("#botonReliquidarMenuContextual").css("display", "block");
		}

		$("#codigoTributoEliminarLiquidacion").val(codigoTributo);
		$("#cuentaCorrienteEliminarLiquidacion").val(cuentaCorriente);
		$("#codigoObjetoEliminarLiquidacion").val(codigoObjeto);
		
		$("#codigoTributoEliminarReLiquidacion").val(codigoTributo);
		$("#cuentaCorrienteEliminarReLiquidacion").val(cuentaCorriente);
		$("#codigoObjetoEliminarReLiquidacion").val(codigoObjeto);

		$("#cuentaCorrienteEditarPeriodo").val(cuentaCorriente);
		$("#codigoObjetoEditarPeriodo").val(codigoObjeto);
		$("#periodoAnio").val(anio);
		$("#periodoCuota").val(cuota);

		if(estado != 'X')
			$("#botonDDJJFaltante").css("display", "none");
		else{

			var $modal= $("#modalDDJJFaltante");
			$modal.data("codigo-objeto", codigoObjeto);
			$modal.data("nombre-objeto", nombreObjeto);
			$modal.data("subcuenta", subCuenta);
			$modal.data("codigo-tributo", codigoTributo);
			$modal.data("anio", anio);
			$modal.data("cuota", cuota);

			$("#botonDDJJFaltante").css("display", "block");
		}

		if ( tipoTributo == 3 ){
			$("#botonIrEventual").attr("href", "<?= BaseUrl::toRoute(['//ctacte/liquida/view']); ?>&id=" + cuentaCorriente);
			$("#opEventual").css('display', 'block');
		}else
			$("#opEventual").css('display', 'none');

	}
}

function obtenerPosicionMouse(mouse, direction, scrollDir, settings){

	var win = $(window)[direction](),
        scroll = $(window)[scrollDir](),
        menu = $(settings.menuSelector)[direction](),
        position = mouse + scroll;

    // opening menu would pass the side of the page
    if (mouse + menu > win && menu < mouse)
        position -= menu;

    return position;
}

function cambiaTipoObjeto(nuevo, limpiar){

	$("#botonExportarResumen").attr("disabled", true);
	$("#botonExportarCompleto").attr("disabled", true);

	$("#botonMiscelaneas").attr("disabled", true);
	$("#botonIrAlObjeto").attr("disabled", true);
	$("#botonIrAplicar").attr("disabled", true);

	limpiarTablaPrincipal();
	limpiarTablaMovimientos();
	limpiarBanderas();

	$("#codigoObjeto").val("");

	$.pjax.reload({
		container:"#ObjNombre",
		data:{
			objeto_id: $("#codigoObjeto").val(),
			tobj: nuevo,
			soloobj:1
		},
		method:"POST"
	});

	$("#ObjNombre").on("pjax:complete", function(){

		$("#codigoObjeto").focus();
		$("#ObjNombre").off("pjax:complete");
	});
}

function cambiaObjeto(nuevo){

	limpiarTablaPrincipal();
	limpiarTablaMovimientos();
	limpiarBanderas();
	$("#botonExportarResumen").attr("disabled", true);
	$("#botonExportarCompleto").attr("disabled", true);

	if(nuevo != ""){

		$.pjax.reload({
			container:"#ObjNombre",
			data:{
				objeto_id: $("#codigoObjeto").val(),
				tobj:$("#dlTObjeto").val(),
				soloobj:1
			},
			method:"POST"
		});

		$("#ObjNombre").on("pjax:complete", function(){

			var nombreObjeto= $("#nombreObjeto").val();
			var codigoObjeto= $("#codigoObjeto").val();
			var tipoObjeto= $("#dlTObjeto").val();
			console.log(tipoObjeto);
			if(nombreObjeto !== ""){



				$("#botonMiscelaneas").attr("href", "<?= BaseUrl::toRoute(['//objeto/objeto/miscelaneas']) ?>&id=" + codigoObjeto);
				$("#botonMiscelaneas").removeAttr("disabled");

				$("#botonIrAlObjeto").attr("href", "<?= BaseUrl::toRoute(['//objeto/objeto/view']); ?>&id=" + codigoObjeto);
				$("#botonIrAlObjeto").removeAttr("disabled");

				if(tipoObjeto == 3)
					$("#botonCtaCteNUM").attr("disabled", true);
				else $("#botonCtaCteNUM").removeAttr("disabled");

				$("#botonAplicar").removeAttr("disabled");


				removerErrores();

			} else {

				$("#botonMiscelaneas").attr("disabled", true);
				$("#botonIrAlObjeto").attr("disabled", true);
				$("#botonAplicar").attr("disabled", true);
				$("#botonCtaCteNum").attr("disabled", true);

				if(codigoObjeto !== "")
					mostrarErrores(["El objeto no existe."], $("#contenedorErrores"));
			}
		});
	}
}

function clickFilaPrincipal(codigoTributo, codigoPlan, codigoObjeto, subCuenta, $fila){

	var datos= obtenerDatos();
	$("#filaSeleccionada").val($fila.data("key"));
	$("#GrillaResumen tr.success").removeClass("success");
	$fila.addClass("success");

	$.pjax.reload({

		container: "#divTablaMovimientos",
		replace: false,
		push: false,
		type: "POST",
		data: {
			"trib_id": codigoTributo,
			"plan_id": codigoPlan,
			"obj_id": codigoObjeto,
			"subcta": subCuenta,
			"fecha": datos.fechaConsolidacion,
			"perdesde": datos.periodoDesde,
			"perhasta": datos.periodoHasta,
			"est": datos.estado,
			"baja": datos.bajas,
			"pervenc": datos.periodosVencidos,
			"cargarMovimientos2": true
		}
	});

	$("#divTablaMovimientos").on("pjax:complete", function(){

		$("#botonExportarCompleto").removeAttr("disabled");
	});
}

function exportarResumen(){

	var datos= obtenerDatos();

	$.pjax.reload({
		container:"#pjaxExportarResumen",
		url: "<?= BaseUrl::toRoute(['exportarresumen']); ?>",
		method:"GET",
		replace: false,
		push: false,
		data: {
			"o": datos.codigoObjeto,
			"f": datos.fechaConsolidacion,
			"d": datos.periodoDesde,
			"h": datos.periodoHasta,
			"e": datos.estado,
			"p": datos.planesVigentes,
			"t": datos.tipoObjeto,
			"eo": datos.estobj,
			"cargarDatosPrincipales": true
		}
	});

	$("#pjaxExportarResumen").on("pjax:complete", function(){

		$("#modalResumenCuentaCorriente").modal("show");
		$("#pjaxExportarResumen").off("pjax:complete");
	});
}

function exportarCompleto(){

	var datos= obtenerDatos();

	$.pjax.reload({
		container: "#pjaxExportarCompleto",
		url: "<?= BaseUrl::toRoute(['exportarcompleto']); ?>",
		method: "GET",
		replace: false,
		push: false,
		data: {
			"o": datos.codigoObjeto,
			"f": datos.fechaConsolidacion,
			"d": datos.periodoDesde,
			"h": datos.periodoHasta,
			"e": datos.estado
		}
	});

	$("#pjaxExportarCompleto").on("pjax:complete", function(){

		$("#modalExportarCompleto").modal("show");
		$("#pjaxExportarCompleto").off("pjax:complete");
	});
}

/*
* Retorna un objeto javascript con los datos que se encuentran cargados en los controles
*/
function obtenerDatos(){

	var datos= {};

	datos.codigoObjeto= $("#codigoObjeto").val();
	datos.fechaConsolidacion= $("#fchcons").val();

	datos.anioDesde= parseInt($("#txAnioDesde").val());
	datos.cuotaDesde= parseInt($("#txCuotaDesde").val());
	datos.anioHasta= parseInt($("#txAnioHasta").val());
	datos.cuotaHasta= parseInt($("#txCuotaHasta").val());

	if(isNaN(datos.anioDesde) || datos.anioDesde < 0) datos.anioDesde= 0;
	if(isNaN(datos.cuotaDesde) || datos.cuotaDesde < 0) datos.cuotaDesde= 0;
	if(isNaN(datos.anioHasta) || datos.anioHasta < 0) datos.anioHasta= 0;
	if(isNaN(datos.cuotaHasta) || datos.cuotaHasta < 0) datos.cuotaHasta= 0;

	datos.periodoDesde= datos.anioDesde * 1000 + datos.cuotaDesde;
	datos.periodoHasta= datos.anioHasta * 1000 + datos.cuotaHasta;

	datos.estado= $("#dlEstado").val();
	datos.planesVigentes= $("#ckPlanNoVig").is(":checked");
	datos.periodosVencidos= $("#ckSoloPerVenc").is(":checked");
	datos.bajas= $("#ckBajas").is(":checked");

	datos.tipoObjeto= $("#dlFiltroTObjeto option:selected").text();
	if(datos.tipoObjeto == "Todos") datos.tipoObjeto= "";

	datos.estobj= $("#ckObjAct").is(":checked");

	return datos;
}

/*
* boton reliquiedar cuando no existen datos en ctacte
*/
function botonReliquidarExiteCtaCteClick()
{
	var $modal= $("#modalReliquidar");
	$modal.data("codigo-objeto", $("#codigoObjeto").val());
	$modal.data("nombre-objeto", $("#nombreObjeto").val());
	$modal.data("subcuenta", <?= $subcta ?>);
	$modal.data("anio", <?= date('Y') ?>);
	$modal.data("cuota", $("#txCuotaDesde").val());

	$("#modalReliquidar").modal("show");
}

function botonBorrarLiquidaClick()
{
	$.post("<?= BaseUrl::toRoute(['index']); ?>", {objeto_id: $("#codigoObjeto").val(), soloobj: "1", pjelimliq: "1"},
		function(htmlexterno){
			aplicar();
		}
	);
}

function btAceptarCuponPagoClick()
{
	if ($("#fechaCuponPago").val() == '') {
		mostrarErrores(["Seleccione una Fecha."], $("#contenedorErroresCuponPago"));
	}else {
		$("#formCuponPago").submit();
		$("#modalCuponPago").modal("hide");
	}
}

function generarFacilidadClick()
{
	var from = $("#fchvencGenerarFacilidad").val().split("/");
	var f = new Date(from[2], from[1] - 1, from[0]);
	var t = new Date();
	t = new Date(t.getFullYear(),t.getMonth(),t.getDate());

	if ($("#fchvencGenerarFacilidad").val() == '') {
		mostrarErrores(["Seleccione una Fecha."], $("#contenedorErroresGenerarFacilidad"));
	}else if ( f < t ) {
		mostrarErrores(["La Fecha debe ser mayor a la actual"], $("#contenedorErroresGenerarFacilidad"));
	}else {
		$("#formGenerarFacilidad").submit();
		$("#modalGenerarFacilidad").modal("hide");
	}
}

/*
* Limpia los errores
*/
function removerErrores(){

	$("#contenedorErrores").css("display", "none");
}

$(document).ready(function(){

	<?php

	if ($aplicar){
		?>
		$("#pjaxBusquedaAvanzada").on("pjax:complete", aplicar);
		<?php
	}


	if(isset($error) && $error != ''){
		?>
			mostrarErrores(["<?= $error ?>"], $("#contenedorErrores"));
		<?php
	}
	?>

	$.pjax.defaults.timeout= 10000;

	//habilita el boton "aplicar" cuando se cierra el modal de busqueda avanzada, siempre y cuando se haya cargado un objeto valido
	$("#modalBusquedaAvanzadaObjeto").on("hide.bs.modal", function(){

		$("#botonIrAlObjeto").attr("href", "<?= BaseUrl::toRoute(['//objeto/objeto/view']); ?>&id=" + $("#codigoObjeto").val());
		$("#botonAplicar").prop("disabled", $("#codigoObjeto").val() == "" || $("#nombreObjeto").val() == "");
	});
	$("#botonAplicar").prop("disabled", $("#codigoObjeto").val() == "" || $("#nombreObjeto").val() == "");
});


/*
* Menu contextual
*/
(function ($, window) {

    $.fn.contextMenu = function (settings) {

        return this.each(function () {

            // Open context menu
            $(this).on("contextmenu", function (e) {

            	$(".menuContextual").css("display", "none");

                // return native menu if pressing control
                if (e.ctrlKey) return;

                //open menu
                var $menu = $(settings.menuSelector)
                    .data("invokedOn", $(e.target))
                    .show()
                    .css({
                        position: "fixed",
                        left: getMenuPosition(e.clientX, 'width', 'scrollLeft'),
                        top: getMenuPosition(e.clientY, 'height', 'scrollTop')
                    });

                return true;
            });

            //make sure menu closes on any click
            $('body').click(function () {
                $(settings.menuSelector).hide();
            });
        });

        function getMenuPosition(mouse, direction, scrollDir) {
            var win = $(window)[direction](),
                scroll = $(window)[scrollDir](),
                menu = $(settings.menuSelector)[direction](),
                position = mouse;

            // opening menu would pass the side of the page
            if (mouse + menu > win && menu < mouse)
                position -= menu;

            return position;
        }

    };
})(jQuery, window);

$(window).on("scroll", function(){

	$(".menuContextual").hide();
});

</script>
