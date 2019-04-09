<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;
use app\utils\db\utb;
use \yii\bootstrap\Modal;
use yii\bootstrap\Alert;
use yii\web\Session;

$this->title = "Cambio ".utb::getCampo("objeto_taccion","cod=".$taccion);
$id = $modelObjeto->obj_id;
if (Yii::$app->param->sis_id) $id = utb::getCampo("rh.leg","obj_id='" . $modelObjeto->obj_id . "'","leg_id");
$this->params['breadcrumbs'][] = ['label' => 'Objeto: '.$modelObjeto->obj_id, 'url' => ['view', 'id' => $id]];
$this->params['breadcrumbs'][] = $this->title;

//Cargo las variables correspondientes al domicilio que me llegan desde la Sesión.

Pjax::begin(['id'=>'1234']);

$session = new Session;

	$domi = $session['domic'];
	
$session->close();


	
	$form = ActiveForm::begin([
		'id' => 'form-inmueble',
		'fieldConfig' => [
		'template' => "{label}{input}",
			],
	
	]);

/* 
 * Me llegan:
 * 	-> $modelObjeto == modelo del objeto
 *  -> $taccion == Tipo de acción (7 u 9)
 */
 $consulta = 1;
 $grabado = 0;
 
 /*
  * Variables que se usarán
  */
  
  $expe = "";
  $obs = "";
  $distrib = 0;
  $tdistrib = 0;
  
  if (!isset($tor)) $tor = 'GENERAL';
  if ($tor=='GENERAL') $idgrilla = 'G';
  if ($tor=='INM') $idgrilla = 'I';
  if ($tor=='OBJ') $idgrilla = 'P';
  if ($tor=='COM') $idgrilla = 'C';
  if ($tor=='PRE') $idgrilla = 'R';
?>



<div class="form-objetoCambioDomicilio" style="width:600px">
<h1><?= Html::encode($this->title) ?></h1>

<div class="separador-horizontal"></div>

<div class="form" style="padding:5px;">
<table width="100%">
	<tr>
		<td width="30px"><label>Objeto</label></td> 
		<td width="60px"><?= Html::input('text','cambioDomi-codObjeto', $modelObjeto->obj_id, ['id'=>'cambioDomi-codObjeto','disabled' => true, 'class' => 'form-control', 'style' => 'width:65px; background:#E6E6FA']) ?></td>
		<td ><?= Html::input('text','cambioDomi-nombreObjeto', $modelObjeto->nombre, ['id'=>'cambioDomi-nombreObjeto', 'disabled' => true,'class' => 'form-control', 'style' => 'width:100%; background:#E6E6FA']) ?></td>
	</tr>

	<tr>
		<td colspan="2"><label>Domicilio Actual</label></td>
		<td><?= Html::input('text','cambioDomi-domActual', $domiAnt, ['id'=>'cambioDomi-domActual', 'class' => 'form-control','disabled' => true, 'style'=>'width:100%; background:#E6E6FA']) ?></td>
	</tr>
</table>

<table width="100%">	
	<tr>
		<td width="105px"><label>Domicilio Nuevo</label></td>
		<td width="20px">
			<!--INICIO Botón para seleccionar nuevo domicilio-->
					<?php
					Modal::begin([
		                'id' => 'BuscaDomi'.$idgrilla, 
						'header' => '<h2>Búsqueda de Domicilio</h2>',
						'toggleButton' => [
		                    'label' => '<i class="glyphicon glyphicon-search"></i>',
		                    'class' => 'bt-buscar',
		                ],
		                'closeButton' => [
		                  'label' => '<b>X</b>',
		                  'class' => 'btn btn-danger btn-sm pull-right',
		                ]
		            ]);
		            
		            echo $this->render('//objeto/domiciliobusca', ['modelodomi' => $domi, 'tor' => $tor]);
		            
		            Modal::end();
		            ?>
		    </td>
			<!--FIN Botón para seleccionar nuevo domicilio-->
			<td><?= Html::input('text','cambioDomi-domNuevo', ($domiAnt !== $domi->domicilio ? $domi->domicilio : null), ['id'=>'cambioDomi-domNuevo', 'class' => 'form-control solo-lectura', 'tab-index' => -1, 'style'=>'width:100%']) ?></td>
	</tr>
</table>
<?php 
			//Bloque que se encarga de cargar los datos de los domicilios.
			//El id del Pjax debe ser "CargarModeloDomi" ya que así está creada la función.
			Pjax::begin(['id' => 'CargarModeloDomi']);
			$session->open();
			
			$domiCambio = $session['domic'];
			
				if(isset($_POST['tor']))
				{
	
					$domiCambio->torigen = $tor;		
						
					$domiCambio->obj_id = $modelObjeto->obj_id;
		 			$domiCambio->id = 0;
					$domiCambio->prov_id = isset($_POST['prov_id']) ? $_POST['prov_id'] : $domiCambio->prov_id;
					$domiCambio->loc_id = isset($_POST['loc_id']) ? $_POST['loc_id'] : $domiCambio->loc_id;
		 			$domiCambio->cp = isset($_POST['cp']) ? $_POST['cp'] : $domiCambio->cp;
		 			$domiCambio->barr_id = isset($_POST['barr_id']) ? $_POST['barr_id'] : $domiCambio->barr_id;
		 			$domiCambio->calle_id = isset($_POST['calle_id']) ? $_POST['calle_id'] : $domiCambio->calle_id;
		 			$domiCambio->nomcalle = isset($_POST['nomcalle']) ? $_POST['nomcalle'] : $domiCambio->nomcalle;
		 			$domiCambio->puerta = isset($_POST['puerta']) ? $_POST['puerta'] : $domiCambio->puerta;
		 			$domiCambio->det = isset($_POST['det']) ? $_POST['det'] : $domiCambio->det;
		 			$domiCambio->piso = isset($_POST['piso']) ? $_POST['piso'] : $domiCambio->piso;
		 			$domiCambio->dpto = isset($_POST['dpto']) ? $_POST['dpto'] : $domiCambio->dpto;
					
//		 			$domiCambio->domicilio = $domiCambio->nomcalle . ' ' . $domiCambio->puerta . ' ' . $domiCambio->det . ' Piso: ' . $domiCambio->piso;
//					$domiCambio->domicilio .= ' Dpto: ' . $domiCambio->dpto . ' - ' . utb::getCampo("domi_localidad","loc_id=" . $domiCambio->loc_id, "nombre");
//					$domiCambio->domicilio .= ' - ' . utb::getCampo("domi_provincia","prov_id=" . $domiCambio->prov_id,"nombre");
		 	
		 			echo '<script>$("#cambioDomi-domNuevo").val("' . $domiCambio->armarDescripcion() . '")</script>';
		 			
		 			$session['domic'] = $domiCambio;
				}	
				 
				 if (isset($_POST['consulta'])) $consulta = $_POST['consulta'];
				 if (isset($_POST['expe'])) $expe = $_POST['expe'];
				 if (isset($_POST['obs'])) $obs = $_POST['obs'];
				 if (isset($_POST['distrib'])) $distrib = $_POST['distrib'];
				 if (isset($_POST['tdistrib'])) $tdistrib = $_POST['tdistrib'];
				 if (isset($_POST['domi'])) $domiCambio = $_POST['domi'];
				 
				 //Actualización
				 if ($consulta == 3)
				 {
					
					$mensj = $domiCambio->cbioDomicilio($taccion, $expe, $obs, $distrib, $tdistrib);
					
					
					if ($mensj == '')
					{
						$session['banderaDomic'] = 1; //Pongo en 1 la bandera, lo que vaciará los datos la próxima vez que se ejecute el controlador
						$session['domic'] = '';
										
						//Necesito redirigir a la vista principal e informar que los datos se grabaron correctamente
						echo '<script>ActualizaDatosClick();</script>';
						        			        				
        				
						
					} else {
						
						echo '<script>$.pjax.reload({container:"#error",data:{mensaje:"'.$mensj.'"},method:"POST"})</script>';
					}
					
				}
					
				$session->close();
				
	if ($taccion == 7)
	{
?>
<div style="border: 1px solid #ddd; border-radius: 6px; padding-left:5px">
<table>
	<tr>
		<td width="85px"><label>Tipo Distrib:</label></td>
		<td width="220px"><?= Html::dropDownList('cambioDomi-tDistrib',$tdistrib, utb::getAux('objeto_tdistrib'), ['id' => 'cambioDomi-tDistrib', 'style' => 'width:200px','class' => 'form-control'])?></td>
		<td width="85px" align="right"><label>Distrib:</label></td>
		<td width="220px"><?= Html::dropDownList('cambioDomi-distrib',$distrib, utb::getAux('sam.sis_usuario','usr_id','nombre',0,'distrib=1'), ['id' => 'cambioDomi-distrib', 'style' => 'width:200px','class' => 'form-control'])?></td>
	</tr>
</table>
</div>

<?php
	}
?>

<table width="100%">
	<tr>
		<td width="100px"><label>Expediente:</label></td>
		<td><?= Html::input('text','cambioDomi-expediente', $expe, ['id'=>'cambioDomi-expediente', 'class' => 'form-control', 'style' => 'width:100px', 'maxlength' => '20']) ?></td>
	</tr>

	<tr>
		<td valign="top"><label>Observaciones:</label></td>
		<td>
			<?= Html::textarea('cambioDomi-detalle', $obs, ['class' => 'form-control','id'=>'cambioDomi-detalle','maxlength'=>'500','style'=>'width:490px; height:100px; max-width:490px; max-height:150px;']); ?>
		</td>
	</tr>
</table>

</div>

<?php
Pjax::end();

ActiveForm::end();

Pjax::end();


?>

<div style="margin-top:5px;">
<?php 
	
			
			echo Html::button('Grabar',['class' => 'btn btn-success', 'onclick' => 'btGrabarClick()']); 
			echo "&nbsp;&nbsp;";
			echo Html::a('Cancelar', ['view', 'id' => $id], [
            			'class' => 'btn btn-primary',
        			]);
        			
			echo Html::a('1234', ['view', 'id' => $id, 'm_text' => 'Datos grabados correctamente'], [
            			'class' => 'btn btn-primary', 'style'=>'visibility:hidden', 'id' => 'actualizaDatos'
        			]);		
			
				
?>
</div>

<?php 

Pjax::begin(['id'=>'error', 'options' => ['style' => 'margin-top:5px; width:600px;']]);

$mensaje = '';

if (isset($_POST['mensaje'])) $mensaje = $_POST['mensaje'];


if($mensaje != ""){ 

	Alert::begin([
		'id' => 'AlertaMensaje',
		'options' => [
    	'class' => 'alert-danger',
    	'style' => $mensaje !== '' ? 'display:block' : 'display:none' 
		],
	]);

	echo $mensaje;
			
	Alert::end();
	
	echo "<script>window.setTimeout(function() { $('#AlertaMensaje').fadeOut(); }, 5000)</script>";
 }
 
 Pjax::end();

?>

<script>


function btGrabarClick()
{
	var error = '';
	var nuevoDomicilio = $("#cambioDomi-domNuevo").val().trim();
	var expe = $("#cambioDomi-expediente").val();
	var obs = $("#cambioDomi-detalle").val();
	var tdistrib = $("#cambioDomi-tDistrib option:selected").val();
	var distrib = $("#cambioDomi-distrib option:selected").val();
	
	if(nuevoDomicilio == '') error = "Debe ingresar un nuevo domicilio.";
	
	if (error != '')
	{
		$.pjax.reload({container:"#error",data:{mensaje:error},method:"POST"});		
	} else {

		$.pjax.reload({	
			container:"#CargarModeloDomi", 
			method:"POST",
			data:{	
				"consulta" : 3,
				"expe" : expe,
				"obs" : obs,
				"distrib" : distrib,
				"tdistrib" : tdistrib
			}
		});
	}
}

function ActualizaDatosClick() 
{
	//$("#actualizaDatos").click()
	
	url = $("#actualizaDatos").attr("href");
    window.open(url,'_self');
}

</script>

</div>		