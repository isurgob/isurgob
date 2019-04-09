<?php
use yii\helpers\Html;
use yii\widgets\Pjax;

use app\utils\helpers\DBException;


$this->params['breadcrumbs'][] = 'Inmueble';
$this->params['breadcrumbs'][] = 'Coeficiente';
?>

</style>

<div class="inm-coeficiente">

	<h1>C&aacute;lculo de coeficiente</h1>
	<div style="border-bottom:1px solid #DDDDDD; margin-bottom:5px;"></div>

	
	
	<table width="100%">
		<tr>
			<td valign="top">
				<div class="form" style="padding:5px; height:420px;">
				
				<?php
				$elementos = [
					['label' => 'Rectangular', 'imagen' => './images/coef/00_rectang.jpg', 'data-labels' => 'Frente:, Fondo:', 'tcalculo' => 1],
					['label' => 'Pasillo o Martillo al Fondo', 'imagen' => './images/coef/01_martfon.jpg', 'data-labels' => 'Frente:, Contrafrente:, Fondo largo:, Fondo corto:', 'tcalculo' => 2],
					['label' => 'Martillo al Frente', 'imagen' => './images/coef/02_martfre.jpg', 'data-labels' => 'Frente:, Contrafrente:, Fondo largo:, Fondo corto:', 'tcalculo' => 3],
					['label' => 'Romboidal', 'imagen' => './images/coef/03_romboid.jpg', 'data-labels' => 'Frente:, Fondo:', 'tcalculo' => 4],
					['label' => 'Frente Falsa Escuadra', 'imagen' => './images/coef/04_frenfal.jpg', 'data-labels' => 'Frente:, Frondo largo:, Fondo corto:', 'tcalculo' => 5],
					['label' => 'Contrafrente Falsa Escuadra', 'imagen' => './images/coef/05_contfal.jpg', 'data-labels' => 'Frente:, Frondo largo:, Fondo corto:', 'tcalculo' => 6],
					['label' => 'Esquina', 'imagen' => './images/coef/06_esquina.jpg', 'data-labels' => 'Frente A:, Valor A:, Frente B:, Valor B:', 'tcalculo' => 7],
					['label' => 'Salida a 2 Calles Transversales', 'imagen' => './images/coef/07_2calesq.jpg', 'data-labels' => 'Frente A:, Valor A:, Fnd largo A:, Frente B:, Valor B:, Fnd corto B:', 'tcalculo' => 8],
					['label' => 'Salida a 2 Calles Opuestas', 'imagen' => './images/coef/08_2calopu.jpg', 'data-labels' => 'Frente A:, Valor A:, Fondo A:, Frente B:, Valor B:, Fondo B:', 'tcalculo' => 9],
					['label' => 'Frente a 3 Calles', 'imagen' => './images/coef/09_3calesq.jpg', 'data-labels' => 'Frente A:, Valor A:, Frente B:, Valor B:, Frente C:, Valor C:', 'tcalculo' => 10],
					['label' => 'Frente a 3 Calles (sin esquina)', 'imagen' => './images/coef/10_3calles.jpg', 'data-labels' => 'Frente A:, Valor A:, Frente B:, Valor B:, Frente C:, Valor C:, Fondo largo:, Fnd corto C:', 'tcalculo' => 11],
					['label' => 'Triangular Frente a calle', 'imagen' => './images/coef/11_trifren.jpg', 'data-labels' => 'Frente:, Fondo:', 'tcalculo' => 12],
					['label' => 'Triangular Vértice a Calle', 'imagen' => './images/coef/12_trivert.jpg', 'data-labels' => 'Contrarente:, Fondo:', 'tcalculo' => 13],
					['label' => 'Manzana Triangular', 'imagen' => './images/coef/13_trimanz.jpg', 'data-labels' => 'Superficie', 'tcalculo' => 14],
					['label' => 'Trapezoidal', 'imagen' => './images/coef/14_trapeci.jpg', 'data-labels' => 'Frente:, Contrafrente:, Fondo:', 'tcalculo' => 15],
					['label' => 'Lote Interno', 'imagen' => './images/coef/15_interno.jpg', 'data-labels' => 'Frente:, Fondo lote:, Fondo largo:', 'tcalculo' => 16],
					['label' => 'Superficie entre 2.000 y 15.000m2', 'imagen' => './images/coef/16_sup2_15.jpg', 'data-labels' => 'Superficie:, Fondo:', 'tcalculo' => 17],
					['label' => 'Esquina o Manzana con Sup. entre 2.000 y 15.000m2', 'imagen' => './images/coef/17_esq2_15.jpg', 'data-labels' => 'Superficie:, Fondo:', 'tcalculo' => 18],
					['label' => 'Superficie superior a 15.000m2', 'imagen' => './images/coef/18_sup15__.jpg', 'data-labels' => 'Superficie', 'tcalculo' => 19]
				];
				?>
				
					<ul class="list-unstyled">
						<?php
						$actual = 1;
						foreach($elementos as $e){
							
							?>
							<li>
								<?= Html::a($e['label'], "#", ['data-imagen' => $e['imagen'], 'id' => "link$actual", 'data-labels' => $e['data-labels'], 'data-tcalculo' => $e['tcalculo'], 'onclick' => 'cargarTipo($(this));']); ?>
							</li>
							<?php
						}
						
						?>
					</ul>
					
					
				</div>
			</td>
			<td width="50%">
				<div class="form" style="padding:5px; height:420px; margin-left:5px;">
				<table>
				
					<tr>
						<td colspan="8">
							<span><b>Tipo de c&aacute;lculo: </b><b id="textoTipoCalculo"></b></span>
						</td>
					</tr>
					<tr>
						<td colspan="8">
							<img src="http://placehold.it/320x240?text=Imagen%20del%20terreno" class="img-responsive img-rounded" id="imagen" />
						</td>
					</tr>
					
					<?php
					
					$elementos = [
					
						[ ['label' => 'label 1', 'tabindex' => 1, 'length' => 4], ['label' => 'label 5', 'tabindex' => 5, 'length' => 5] ],
						[ ['label' => 'label 2', 'tabindex' => 2, 'length' => 5], ['label' => 'label 6', 'tabindex' => 6, 'length' => 5] ],
						[ ['label' => 'label 3', 'tabindex' => 3, 'length' => 6], ['label' => 'label 7', 'tabindex' => 7, 'length' => 6] ],
						[ ['label' => 'label 4', 'tabindex' => 4, 'length' => 6], ['label' => 'label 8', 'tabindex' => 8, 'length' => 6] ]
					];
					
					foreach($elementos as $e){
						
						?>
							<tr>
								<td width="60px"><b id="lb<?= $e[0]['tabindex']; ?>" class="labelTexto" style="visibility:hidden;"><?= $e[0]['label']; ?>:</b></td>
								<td width="5px"></td>
								<td width="50px"><?= Html::textInput(null, null, ['class' => 'form-control input', 'style' => 'width:50px; visibility:hidden;', 'value' => 0, 'id' => 'in' . $e[0]['tabindex'], 'tabindex' => $e[0]['tabindex'], 'maxlength' => $e[0]['length']]); ?></td>
								<td width="10px"></td>
								<td width="90px"><b id="lb<?= $e[1]['tabindex']; ?>" class="labelTexto" style="visibility:hidden;"><?= $e[1]['label']; ?>:</b></td>
								<td width="5px"></td>
								<td width="50px"><?= Html::textInput(null, null, ['class' => 'form-control input', 'style' => 'width:50px; visibility:hidden;', 'value' => 0, 'id' => 'in' . $e[1]['tabindex'], 'tabindex' => $e[1]['tabindex'], 'maxlength' => $e[1]['length']]); ?></td>
								<td width="120px"></td>
							</tr>	
						<?php
					}
					
					?>
					
					<tr>
						<td><?= Html::button('Calcular', ['class' => 'btn btn-success', 'id' => 'botonCalcular', 'onclick' => 'calcular();', 'disabled' => true]) ?></td>
						<td></td>
						<td><?= Html::textInput(null, null, ['class' => 'form-control solo-lectura', 'tabindex' => -1, 'id' => 'resultado', 'style' => 'width:50px;']) ?></td>
					</tr>
				</table>
				</div>
			</td>
		</tr>
	</table>
	
	
	<input type="hidden" id="requeridos">
	<input type="hidden" id="tcalculo" >
</div>

<?php
Pjax::begin(['id' => 'pjaxCalcular', 'enableReplaceState' => false, 'enablePushState' => false]);

$tipoCalculo = intval(Yii::$app->request->get('tcalculo', 0));
$sql = "";
$error = "";

if($tipoCalculo > 0){
	
	$tipos = [
		1 => 'rectang', 2 => 'martfon', 3 => 'martfre',
		4 => 'romboid', 5 => 'frenfal', 6 => 'contfal',
		7 => 'esquina', 8 => '2calesq', 9 => '2calopu',
		10 => '3calesq', 11 => '3calles', 12 => 'trifren',
		13 => 'trivert', 14 => 'trimanz', 15 => 'trapez',
		16 => 'interno', 17 => 'sup2_15', 18 => 'esq2_15',
		19 => 'sup15'
	];
	
	if(array_key_exists($tipoCalculo, $tipos)){
		
		$val1 = floatval(Yii::$app->request->get('val1', 0));
		$val2 = floatval(Yii::$app->request->get('val2', 0));
		$val3 = floatval(Yii::$app->request->get('val3', 0));
		$val4 = floatval(Yii::$app->request->get('val4', 0));
		$val5 = floatval(Yii::$app->request->get('val5', 0));
		$val6 = floatval(Yii::$app->request->get('val6', 0));
		$val7 = floatval(Yii::$app->request->get('val7', 0));
		$val8 = floatval(Yii::$app->request->get('val8', 0));
		
		$t = $tipos[$tipoCalculo];
		$sql = "Select sam.uf_inm_coef('$t', $val1, $val2, $val3, $val4, $val5, $val6, $val7, $val8)";
		
		try{
			$resultado = Yii::$app->db->createCommand($sql)->queryScalar();
			
			
			?>
			<script type="text/javascript">
			$(document).ready(function(){
				$("#resultado").val("<?= $resultado ?>");
			});
			</script>
			<?php
			
		} catch(\Exception $e){
			$error = DBException::getMensaje($e);
		}
	}
}


?>
<div class="error-summary" style="margin-top:5px; display:<?= $error != '' ? 'block' : 'none' ?>">

	<p>Por favor corrija los siguientes errores:</p>
	
	<ul class="contenedor">
		<?= $error != '' ? "<li>$error</li>" : null ?>
	</ul>	
</div>
<?php

echo Html::input('hidden', null, null);//para que el pjax no vuelva a cargar la pagina
Pjax::end();
?>

<script type="text/javascript">
function cargarTipo($sender){
	
	$(".error-summary").css("display", "none");
	$("#textoTipoCalculo").text($sender.text());
	var imagen = $sender.data('imagen');
	var labels = $sender.data('labels').split(",");
	var actual = 1;
	var requeridos = "";
	var tcalculo = $sender.data('tcalculo');
	
	
	if(tcalculo == 17 || tcalculo == 18 || tcalculo == 19)
		$("#in1").attr("maxlength", 5);
	else $("#in1").attr("maxlength", 4);
	
	$("#botonCalcular").prop("disabled", false);
	$("#imagen").attr("src", imagen);
	$(".labelTexto").text("");
	$(".labelTexto").css("visibility", "hidden");
	$(".input").val(0);
	$(".input").css("visibility", "hidden");
	$("#tcalculo").val(tcalculo);
	
	for(l in labels){

		$("#lb" + actual).css("visibility", "visible");
		$("#in" + actual).css("visibility", "visible");
		$("#lb" + actual).text(labels[l].trim());
		requeridos += actual + ",";

		actual++;
	}
	
	requeridos = requeridos.slice(0, -1);
	
	$("#requeridos").val(requeridos);
}

function mostrarErrores(errores){
	
	$(".error-summary").css("display", "block");
	$contenedor = $(".error-summary .contenedor");
	$contenedor.empty();
	$el = null;
	
	for(e in errores){
		
		$el = $("<li />");
		$el.text(errores[e]);
		$el.appendTo($contenedor);
	}
}

function evaluarCampos(tcalculo, val1, val2, val3, val4, val5, val6, val7, val8){
	
	errores = [];
	
	if(isNaN(tcalculo) || tcalculo <= 0){
	 errores.push("Seleccione un tipo de cálculo válido");
	 mostrarErrores(errores);
	 return;
	}
	
	
	switch(tcalculo){
		
		case 1:
		case 4:
		case 12:
		
			if(isNaN(val1) || val1 <= 0) errores.push("Frente debe ser un número mayor a 0");
			if(isNaN(val2) || val2 <= 0) errores.push("Fondo debe ser un número mayor a 0");
			
			break;
		
		case 2:
		case 3:
					
			if(isNaN(val1) || val1 <= 0) errores.push("Frente debe ser un número mayor a 0");
			if(isNaN(val2) || val2 <= 0) errores.push("Contrafrente debe ser un número mayor a 0");
			if(isNaN(val3) || val3 <= 0) errores.push("Fondo largo debe ser un número mayor a 0");
			if(isNaN(val4) || val4 <= 0) errores.push("Fondo corto debe ser un número mayor a 0");			
			
			break;

		case 5:
		case 6:
		
			if(isNaN(val1) || val1 <= 0) errores.push("Frente debe ser un número mayor a 0");
			if(isNaN(val2) || val2 <= 0) errores.push("Fondo largo debe ser un número mayor a 0");
			if(isNaN(val3) || val3 <= 0) errores.push("Fondo corto debe ser un número mayor a 0");			
			
			break;
			
		case 7:
				
			if(isNaN(val1) || val1 <= 0) errores.push("Frente A debe ser un número mayor a 0");
			if(isNaN(val2) || val2 <= 0) errores.push("Valor A debe ser un número mayor a 0");
			if(isNaN(val3) || val3 <= 0) errores.push("Frente B debe ser un número mayor a 0");
			if(isNaN(val4) || val4 <= 0) errores.push("Valor B debe ser un número mayor a 0");			
			
			break;
			
		case 8:
			
			if(isNaN(val1) || val1 <= 0) errores.push("Frente A debe ser un número mayor a 0");		
			if(isNaN(val2) || val2 <= 0) errores.push("Valor A debe ser un número mayor a 0");
			if(isNaN(val3) || val3 <= 0) errores.push("Fnd largo A debe ser un número mayor a 0");
			if(isNaN(val4) || val4 <= 0) errores.push("Frente B debe ser un número mayor a 0");			
			if(isNaN(val5) || val5 <= 0) errores.push("Valor B debe ser un número mayor a 0");
			if(isNaN(val6) || val6 <= 0) errores.push("Fnd corto B debe ser un número mayor a 0");
			
			break;
			
		case 9:
		
			if(isNaN(val1) || val1 <= 0) errores.push("Frente A debe ser un número mayor a 0");		
			if(isNaN(val2) || val2 <= 0) errores.push("Valor A debe ser un número mayor a 0");
			if(isNaN(val3) || val3 <= 0) errores.push("Fondo A debe ser un número mayor a 0");
			if(isNaN(val4) || val4 <= 0) errores.push("Frente B debe ser un número mayor a 0");			
			if(isNaN(val5) || val5 <= 0) errores.push("Valor B debe ser un número mayor a 0");
			if(isNaN(val6) || val6 <= 0) errores.push("Fondo B debe ser un número mayor a 0");
			
			break;
			
		case 10:
		
			if(isNaN(val1) || val1 <= 0) errores.push("Frente A debe ser un número mayor a 0");		
			if(isNaN(val2) || val2 <= 0) errores.push("Valor A debe ser un número mayor a 0");
			if(isNaN(val3) || val3 <= 0) errores.push("Frente B debe ser un número mayor a 0");
			if(isNaN(val4) || val4 <= 0) errores.push("Valor B debe ser un número mayor a 0");			
			if(isNaN(val5) || val5 <= 0) errores.push("Frente C debe ser un número mayor a 0");
			if(isNaN(val6) || val6 <= 0) errores.push("Valor C debe ser un número mayor a 0");
			
			break;
			
		case 11:
		
			if(isNaN(val1) || val1 <= 0) errores.push("Frente A debe ser un número mayor a 0");		
			if(isNaN(val2) || val2 <= 0) errores.push("Valor A debe ser un número mayor a 0");
			if(isNaN(val3) || val3 <= 0) errores.push("Frente B debe ser un número mayor a 0");
			if(isNaN(val4) || val4 <= 0) errores.push("Valor B debe ser un número mayor a 0");			
			if(isNaN(val5) || val5 <= 0) errores.push("Frente C debe ser un número mayor a 0");
			if(isNaN(val6) || val6 <= 0) errores.push("Valor C debe ser un número mayor a 0");
			if(isNaN(val7) || val7 <= 0) errores.push("Fondo largo debe ser un número mayor a 0");
			if(isNaN(val8) || val8 <= 0) errores.push("Fnd corto C debe ser un número mayor a 0");
			
			break;
			
		case 13:
		
			if(isNaN(val1) || val1 <= 0) errores.push("Contrafrente debe ser un número mayor a 0");
			if(isNaN(val2) || val2 <= 0) errores.push("Fondo debe ser un número mayor a 0");
			
			break;
			
		case 14:
		case 19:
			
			if(isNaN(val1) || val1 <= 0) errores.push("Superficie debe ser un número mayor a 0");
			
			break;
			
		case 15:
		
			if(isNaN(val1) || val1 <= 0) errores.push("Frente debe ser un número mayor a 0");
			if(isNaN(val2) || val2 <= 0) errores.push("Contrafrente debe ser un número mayor a 0");
			if(isNaN(val3) || val3 <= 0) errores.push("Fondo debe ser un número mayor a 0");
			
			break;
			
		case 16:
		
			if(isNaN(val1) || val1 <= 0) errores.push("Frente debe ser un número mayor a 0");
			if(isNaN(val2) || val2 <= 0) errores.push("Fondo lote debe ser un número mayor a 0");
			if(isNaN(val3) || val3 <= 0) errores.push("Fondo largo debe ser un número mayor a 0");
			
			break;
			
		case 17:
		case 18:
		
			if(isNaN(val1) || val1 <= 0) errores.push("Superficie debe ser un número mayor a 0");
			if(isNaN(val2) || val2 <= 0) errores.push("Fondo debe ser un número mayor a 0");
			
			break;
			
		default: errores.push('Seleccione un tipo de cálculo válido');
	}
	
	if(errores.length > 0){
		mostrarErrores(errores);
		return false;
	}
	
	return true;
}

function calcular(){
	
	var tcalculo= val1= val2= val3= val4= val5= val6= val7= 0;
	
	$(".error-summary").css("display", "none");
	$("#resultado").val("");
	
	tcalculo = parseInt($("#tcalculo").val());
	val1 = parseFloat($("#in1").val());
	val2 = parseFloat($("#in2").val());
	val3 = parseFloat($("#in3").val());
	val4 = parseFloat($("#in4").val());
	val5 = parseFloat($("#in5").val());
	val6 = parseFloat($("#in6").val());
	val7 = parseFloat($("#in7").val());
	val8 = parseFloat($("#in8").val());
	
	if(!evaluarCampos(tcalculo, val1, val2, val3, val4, val5, val6, val7, val8)) return;
	
	$.pjax.reload({
		container : "#pjaxCalcular",
		type : "GET",
		replace : false,
		push : false,
		data : {
			
			"tcalculo" : tcalculo,
			"val1" : val1,
			"val2" : val2,
			"val3" : val3,
			"val4" : val4,
			"val5" : val5,
			"val6" : val6,
			"val7" : val7,
			"val8" : val8
		}
	});
}

$(document).ready(function(){
	
	cargarTipo($("#link1"));
});
</script>