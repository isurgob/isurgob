<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use app\utils\db\utb;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;

//INICIO Bloque actualiza los códigos de objeto
Pjax::begin(['id' => 'ObjNombreBuscar']);

	$objeto_id = Yii::$app->request->post( 'busquedaDDJJ_objeto_id', '' );
	$trib = Yii::$app->request->post( 'trib', 0 );
	$tobj = 0;
	$subcta = '';
	$objeto_nom = '';

	if ( $trib != 0 )
	{
		$tobj = utb::getTTrib($trib);
		$subcta = utb::getCampo('trib','trib_id = ' . $trib,'uso_subcta');

		echo '<script>$.pjax.reload({container:"#PjaxObjBusAvBuscador",data:{tobjeto:'.$tobj.'},method:"POST"});</script>';
	}

	if ( strlen( $objeto_id ) < 8 && $objeto_id != '' )
	{
		$objeto_id = utb::GetObjeto( (int)$tobj, (int)$objeto_id );

	}

	if ( utb::GetTObj($objeto_id) == $tobj )
	{
		$objeto_nom = utb::getNombObj("'".$objeto_id."'");

	} else
	{
		$objeto_id = '';
	}

	echo '<script>$("#txObj_Id").val("'.$objeto_id.'")</script>';
	echo '<script>$("#txObjNom").val("'.$objeto_nom.'")</script>';

	//Habilitar sucursal si trib.uso_subcta = 1
	if ($subcta == 1)
		echo '<script>$("#txSuc").removeAttr("disabled");</script>';
	else
		echo '<script>$("#txSuc").attr("disabled",true);</script>';

	//Habilitar o deshabilitar el edit de objeto según el tributo seleccionado
	echo '<script>$("#txObj_Id").toggleClass("read-only",'.$trib.' == 0);</script>';
	echo '<script>$("#btBuscarObj").prop("disabled", ' . ($trib == 0 ? 'true' : 'false') . ');</script>';

Pjax::end();
//FIN Bloque actualiza los códigos de objeto

$form = ActiveForm::begin(['action' => ['buscar'],'id'=>'frmBuscarDDJJ']);
?>

<div style="color:#000;font-family:Helvetica Neue, Helvetica, Arial, sans-serif; font-size:12px">

<table>
	<tr>
		<td>
			<!-- Combo para la búsqueda de DDJJ -->
			<?= Html::dropDownList( 'ddjj_busqueda', 0, [ 0 => 'N° de DDJJ', 1 => 'Tributo y Objeto'], [
					'id' => 'ddjj_busqueda_combo',
					'class' => 'form-control',
					'onchange' => 'ControlesBuscarDDJJ( $(this).val() )',
				]);
			?>
		</td>

		<td>
			<!-- Div para Nº de DDJJ -->
			<div id="num_ddjj">
				<?= Html::input('text','txNumDDJJ',null,[
						'id'=>'txNumDDJJ',
						'class'=>'form-control',
						'style'=>'width:100px;',
						'onkeypress'=>'return justNumbers(event)',
						'maxlength'=>'8',
					]);
				?>
			</div>

			<!-- Div para Obj -->
			<div id="objeto_ddjj" class="hidden">
				<?= Html::dropDownList('dlTrib', null, utb::getAux('trib','trib_id','nombre',3,"tipo = 2 AND dj_tribprinc = trib_id AND est = 'A'"),[
						'class' => 'form-control',
						'id'=>'dlTrib',
						'onchange'=>'$.pjax.reload({container:"#ObjNombreBuscar",data:{busquedaDDJJ_objeto_id:$("#txObj_Id").val(),trib:$("#dlTrib").val()},method:"POST"})'
					]);
				?>

			   	<?= Html::input('text', 'txObj_Id', null, [
			   			'id'=>'txObj_Id',
			   			'class' => 'form-control read-only',
			   			'style'=>'width:70px;text-align:center',
			   			'maxlength'=>'8',
						'onchange'=>'$.pjax.reload({container:"#ObjNombreBuscar",data:{busquedaDDJJ_objeto_id:$("#txObj_Id").val(),trib:$("#dlTrib").val()},method:"POST"})',
					]);
				?>
				<?= Html::Button("<i class='glyphicon glyphicon-search'></i>",[
						'class' => 'bt-buscar',
						'id' => 'btBuscarObj',
						'disabled' => true,
						'onclick' => '$("#BuscaObjDDJJBuscar").toggle();',
					]);
				?>
				<?= Html::input('text', 'txObjNom', null, [
						'class' => 'form-control solo-lectura',
						'id'=>'txObjNom',
						'style'=>'width:200px',
						'tabIndex' => '-1',
					]);
				?>

				<label>Local:</label>
		   		<?= Html::input('text', 'txSuc', null, ['class' => 'form-control','id'=>'txSuc','style'=>'width:50px','disabled'=>true]); 	?>
			</div>

	   	</td>
	</tr>
</table>

<table style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif; font-size:12px; margin-top: 8px">
	<tr>
		<td>
			<?= Html::Button('Buscar',['class' => 'btn btn-primary', 'id' => 'btBuscarPlanAceptar', 'onClick' => 'ControlesBuscarDDJJ("btAceptar");'])?>
		</td>
	</tr>
</table>

<table border="0" style="color:#000;font-family:Helvetica Neue, Helvetica, Arial, sans-serif; font-size:12px">
	<tr>
		<td>
			<div id='BuscaObjDDJJBuscar' style='display:none;margin:20px 0px;'>
				<div id='BuscaFacilidaObjPag'>
					<?php
						echo $this->render('//objeto/objetobuscarav',[
								'idpx' => 'Buscador','id' => 'facilidabuscar', 'txCod' => 'txObj_Id', 'txNom' => 'txObjNom'
			        		]);
					?>
		     	</div>
		    </div>
		</td>
	</tr>
</table>

<div id='buscarDDJJ_errorSummary' class='error-summary' style='margin-top: 8px;display:none;font-family:Helvetica Neue, Helvetica, Arial, sans-serif; font-size:12px'>

	<ul>
	</ul>

</div>

</div>
<?php

	ActiveForm::end();

?>

<script>
function ControlesBuscarDDJJ( control )
{

	$("#BuscaObjddjjbuscar").css("display", "none");

	$( "#num_ddjj" ).toggleClass( "hidden", control != 0 );
	$( "#objeto_ddjj" ).toggleClass( "hidden", control != 1 );

	if ( control != "btAceptar" )
	{
		//Elimino los valores que hayan podido quedar cargados
		$("#txObj_Id").val("");
		$("#txObjNom").val("");
		$("#txNumDDJJ").val("");

	}

	if ( control == "btAceptar" )
	{
		var error = new Array();

		if ($("#txNumDDJJ").val()=='' && $('input:radio[name=rbCodigo]:checked').val()==1)
			error.push( "Ingrese un Código de DDJJ." );

		if ($("#txObjNom").val() == "" && $("#txObj_Id").val() == "" && $('input:radio[name=rbBuscaObj]:checked').val()==1)
			error.push( "Ingrese un objeto." );

		if ( error.length == 0 )
		{
			$("#frmBuscarDDJJ").submit();
		} else
		{
			mostrarErrores( error, "#buscarDDJJ_errorSummary");
		}
	}

}

</script>
