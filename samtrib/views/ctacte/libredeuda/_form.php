<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\utils\db\utb;
use yii\jui\DatePicker;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;

$action = Yii::$app->request->baseUrl.'/index.php?r=ctacte/libredeuda/imprimir';

Pjax::begin(['id' => 'ObjNombre']);

	$objeto_id = Yii::$app->request->post( 'objeto_id', '' );
	$tobj = Yii::$app->request->post( 'tobj', 4 );
	$objeto_nom = '';
	
	if (strlen($objeto_id) < 8)
	{
		$objeto_id = utb::GetObjeto((int)$tobj,(int)$objeto_id);
	}
	
	
	if ( $tobj != utb::getTObj( $objeto_id ) )
			$objeto_id = '';
			
	# Validar existencia del objeto	
	if ( utb::verificarExistencia('objeto',"obj_id = '" . $objeto_id . "'") )
	{
		//Buscar el nombre de comercio y asignarlo en el Edit correspondiente
		$objeto_nom = utb::getNombObj( "'" . $objeto_id . "'" );
		
	} else 
	{
		$objeto_id = '';
	}
	
	echo '<script>$("#txObj_Id").val("'.$objeto_id.'")</script>';
	echo '<script>$("#txObjNom").val("'.$objeto_nom.'")</script>';		
	
	if ($tobj == 3)
		echo '<script>$("#trTributo").css("visibility","visible")</script>';
	else	echo '<script>$("#trTributo").css("visibility","hidden")</script>';	
	
	if ( $objeto_id != '' )
	{
		if (utb::getCampo('ctacte_libredeuda_bloq',"obj_id='".$objeto_id."' and est='A'",'count(*)') > 0){
			$obs_bloq = utb::getCampo('ctacte_libredeuda_bloq',"obj_id='".$objeto_id."' and est='A'",'obs');
			echo "<script>";
			echo 'mostrarErrores( ["El Objeto ' . $objeto_id . ' se encuentro bloqueado. '.$obs_bloq.'. No podrá emitir libre deuda." ], "#libreDeuda_errorSummary" );';
			echo '$("#txObj_Id").val("");';
			echo '$("#txObjNom").val("");';
			echo "</script>";
		} else {
			echo "<script>";
			echo '$("#libreDeuda_errorSummary").css("display", "none");';
			echo "</script>";
		}
	} else 
	{
		echo "<script>";
		echo '$("#libreDeuda_errorSummary").css("display", "none");';
		echo "</script>";
	}
		
	
	Modal::begin([
    	'id' => 'BuscaObjcc',
    	'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Objeto</h2>',
       	'closeButton' => [
       		'label' => '<b>X</b>',
           	'class' => 'btn btn-danger btn-sm pull-right',
       	],
       	'size' => 'modal-lg',
    ]);
      
    echo $this->render('//objeto/objetobuscarav',[
			'id' => 'cc', 'txCod' => 'txObj_Id', 'txNom' => 'txObjNom', 'tobjeto' => $tobj, 'selectorModal' => '#BuscaObjcc'
    	]);
       
    Modal::end();
	
Pjax::end();

?>

<div class="ctacte-form" >
    <?php 
    	$form = ActiveForm::begin(['id' => 'frmLibreDeuda', 'action' => $action,'options'=>['target'=>'_black'],
    								'fieldConfig' => [
        										'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>",
        										],
        						]);
	?>
	    
	<div class="form" style='padding-right:5px;padding-bottom:15px; margin-bottom:5px; width:660px'>
		<table border='0' style='color:#000;font-family:"Helvetica Neue", Helvetica, Arial, sans-serif; font-size:12px'>
		<tr>
			<td><label> Objeto:</label></td>
			<td>
		    	<?= Html::dropDownList('dlTObjeto', Yii::$app->session['LDeudaNuevo']['tobj'], utb::getAux('objeto_tipo'), ['class' => 'form-control','id'=>'dlTObjeto',
		    		'onchange'=>'$.pjax.reload({container:"#ObjNombre",data:{tobj:$("#dlTObjeto").val()},method:"POST"})']); ?>
		    	
		    	<?= Html::input('text', 'txObj_Id', Yii::$app->session['LDeudaNuevo']['obj'], ['class' => 'form-control','id'=>'txObj_Id','style'=>'width:80px','maxlength'=>'8',
					'onchange'=>'$.pjax.reload({container:"#ObjNombre",data:{objeto_id:$("#txObj_Id").val(),tobj:$("#dlTObjeto").val()},method:"POST"})']); ?>
			</td>
			<td>
				<!-- boton de b�squeda modal -->
				<?= Html::button('<i class="glyphicon glyphicon-search"></i>',[
           				'class' => 'bt-buscar',
           				'id' => 'libreDeuda_btBuscaObj',
           				'onclick' => '$("#BuscaObjcc").modal("show")',
       				]);
       			?>
	        </td>
	        <td>
		        <!-- fin de boton de b�squeda modal -->
				<?= Html::input('text', 'txObjNom', utb::getNombObj("'".Yii::$app->session['LDeudaNuevo']['obj']."'"), ['class' => 'form-control','id'=>'txObjNom','style'=>'width:340px','disabled'=>'true']); ?>
			</td>
		</tr>
		<tr id='trTributo' style='visibility:hidden'>
			<td><label>Tributo:</label></td>
			<td colspan='3'>
				<?= Html::dropDownList('dlTributo', Yii::$app->session['LDeudaNuevo']['trib_id'], utb::getAux('trib','trib_id','nombre',2,"tobj=3 and tipo=2"), ['class' => 'form-control','id'=>'dlTributo',
		    		]); ?>
			</td>
		</tr>
		<tr>
			<td><label>Escribano:</label></td>
			<td colspan='3'>
				<?= Html::input('text', 'txEsc', Yii::$app->session['LDeudaNuevo']['escrib'], ['class' => 'form-control','id'=>'txEsc','style'=>'width:340px','maxlength'=>'40']); ?>
			</td>
		</tr>
		<tr>
			<td><label>Texto:</label></td>
			<td colspan='3'>
				<?= Html::dropDownList('dlTexto', Yii::$app->session['LDeudaNuevo']['texto'], utb::getAux('texto','texto_id','nombre',1,'tuso=9'), ['class' => 'form-control','id'=>'dlTexto']); ?>		    	
			</td>
		</tr>
		<tr>
			<td><label>Firma:</label></td>
			<td colspan='3'>
				<?= Html::dropDownList('dlFirma', Yii::$app->session['LDeudaNuevo']['firma'], utb::getAux('intima_firma','firma_id','nombre',0,'tuso=9'), ['class' => 'form-control','id'=>'dlFirma']); ?>		    	
			</td>
		</tr>
		<tr>
			<td valign='top'><label>Observación:</label></td>
			<td colspan='4'>
				<?= Html::textarea('txObs', Yii::$app->session['LDeudaNuevo']['obs'], ['class' => 'form-control','id'=>'txObs','style'=>'width:550px;height:70px;resize:none']); ?>
			</td>
		</tr>
	</table>
	
	
	</div>
	
	<table>
		<tr>
			<td>
				<?php echo Html::Button('Grabar',['class' => 'btn btn-success','onClick' => 'btImprimir()']); ?>
			</td>
			<td width="10px"></td>
			<td>
				<?php echo Html::a('Cancelar',['//ctacte/listadolibredeuda/index'],['class' => 'btn btn-primary']); ?>
			</td>
		</tr>
	</table>
	
	<div id="libreDeuda_errorSummary" class="error-summary" style="display:none;margin-top: 8px">
		
		<ul>
		</ul>
	
	</div>
	
	<?php
	
	 ActiveForm::end(); 
	 
	 
	if( isset( $error ) && $error !== '' )
	{  
		?>
		<script>
		
		$(document).ready(function() {
			
			mostrarErrores( [<?= $error ?>], "#libreDeuda_errorSummary" );
		});
		
		<?php
	} 
	 ?>
</div>

<script>
function btImprimir()
{
	var error = new Array();
	
	if ($("#txObjNom").val() == '') error.push( "Ingrese un Objeto válido." );
		
	if ( error.length == 0 ){
		$("#errorlibredeuda").html(error);
		$("#errorlibredeuda").css("display", "none");
		$("#frmLibreDeuda").submit();
		
		$('#frmLibreDeuda input').attr('readonly',true);
		$('#frmLibreDeuda select').attr('readonly',true);
		$('#frmLibreDeuda textarea').attr('readonly',true);
		$('#frmLibreDeuda button').attr('disabled',true);
	}else {
		mostrarErrores( error, "#libreDeuda_errorSummary" );
	}
}

</script>