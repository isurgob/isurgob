<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\bootstrap\Modal;
use \yii\widgets\Pjax;
use app\utils\db\utb;
use app\utils\db\Fecha;

/* @var $this yii\web\View */
/* @var $model app\models\caja\Caja */
/* @var $form yii\widgets\ActiveForm */


$form = ActiveForm::begin([
    	'id'=>'form-caja',
    	'fieldConfig' => [
        	'labelOptions' => ['class' => 'col-lg-2 control-label'],
			'template' => "{label}{input}",
		],
	]);

$param = Yii::$app->params;

?>

<div class="form" style="padding:5px;">

	<table border='0'>
		<tr>
			<td><label>Código:</label>
			<td><?= $form->field($model, 'caja_id')->textInput(['style' => 'width:50px;text-align: center', 'maxlength' => 40])->label(false) ?></td>
			<td width="20px"></td>
			<td><label>Nombre:</label>
			<td><?= $form->field($model, 'nombre')->textInput(['maxlength' => 20,'style' => 'width:200px;'])->label(false) ?></td>
			<td width="20px"></td>
			<td>
				<?php
					if ( $model->est == 'A' || $consulta == 0 )
						$model->sub_est = 1;
					else
						$model->sub_est = 0;
				?>
				<?= $form->field($model, 'sub_est')->checkbox() ?>
			</td>
		</tr>

		<tr>
			<td colspan="3"></td>
			<td><label>Tipo:</label></td>

			<td><?= $form->field($model, 'tipo')->dropDownList(utb::getAux('caja_tipo','cod','nombre',1),['style' => 'width:100%;', 'onchange'=>'tipoCaja()', 'value'=>1])->label(false) ?></td>
			<td></td>
			<td><?= $form->field($model, 'editamonto')->checkbox() ?>
			</td>
		</tr>
	</table>


	<?php

		Pjax::begin(['id'=>'supervisores']);

		if (isset($_POST['teso_id']))
		{
			echo '<script>actualizaSupervisores()</script>';
			$model->teso_id = $_POST['teso_id'];
		}
	?>

	<table border="0">
		<tr>
			<td width="80px"><label>Tesorería: </label></td>
			<td><?php

					$condicion = "est='A' and teso_id IN (SELECT teso_id FROM sam.sis_usuario_tesoreria WHERE usr_id = " . Yii :: $app->user->id . ")";
					$arr = utb::getAux('caja_tesoreria','teso_id','nombre',0,$condicion);
					if(!isset($arr[0]))
						$arr[0] = ' ';
					sort($arr,SORT_STRING);
					echo $form->field($model, 'teso_id')->dropDownList($arr, ['style' => 'width:100%;','onchange'=>'$.pjax.reload({container:"#supervisores",method:"POST",data:{teso_id:$(this).val()}})'])->label(false);

			?></td>
			<td width="10px"></td>
			<td width="80px"><label>Cajero: </label></td>
			<td><?= $form->field($model, 'usr_id')->dropDownList(utb::getAux('sam.sis_usuario','usr_id','apenom',1,"cajero=1 AND est='A' AND usr_id IN (SELECT usr_id FROM sam.sis_usuario_tesoreria WHERE teso_id = ".($model->teso_id == null ? 0 : $model->teso_id).")"),['style' => 'width:100%;'])->label(false) ?></td>
		</tr>

	<?php
		//Obtengo los usuarios que pueden ser supervisores
		if ($model->teso_id == '')
			$arraySupervisores = $model->getSupervisores(0);
		else
			$arraySupervisores = $model->getSupervisores($model->teso_id);
	?>
		<tr>
			<td><label>Supervisor 1: </label></td>
			<td><?= $form->field($model, 'sup1')->dropDownList($arraySupervisores,['style' => 'width:100%;', 'onchange' => 'actualizaSupervisores();'])->label(false) ?></td>
			<td></td>
			<td><label>Supervisor 2: </label></td>
			<td><?= $form->field($model, 'sup2')->dropDownList($arraySupervisores,['style' => 'width:100%;', 'onchange' => 'actualizaSupervisores();'])->label(false) ?></td>
		</tr>

		<tr>
			<td><label>Supervisor 3: </label></td>
			<td><?= $form->field($model, 'sup3')->dropDownList($arraySupervisores,['style' => 'width:100%;', 'onchange' => 'actualizaSupervisores();'])->label(false) ?></td>
			<td></td>
			<td><label>Supervisor 4: </label></td>
			<td><?= $form->field($model, 'sup4')->dropDownList($arraySupervisores,['style' => 'width:100%;', 'onchange' => 'actualizaSupervisores();'])->label(false) ?></td>
		</tr>
	</table>


	<?php
		Pjax::end();

	?>
	<table border="0" width="100%">
		<tr>
			<td><label>Destino:</label></td><td></td>
			<td><?= $form->field($model, 'destino')->dropDownList(utb::getAux('caja_tdestino','cod','nombre',1),['style' => 'width:100%;', 'onchange' => 'cambiaDestino($(this).val());'])->label(false) ?></td>
			<td width="20px"></td>

			<td width="300px">
				<div class="form">
				<table>
					<tr>
						<td><?= $form->field($model, 'validar')->checkbox(['id' => 'ckValidar'])->label(false) ?></td><td width="25px"></td>
						<td><?= $form->field($model, 'copia')->checkbox(['id' => 'ckCopia'])->label(false) ?></td></td><td width="25px"></td>
						<td><?= $form->field($model, 'resumen')->checkbox(['id' => 'ckResumen'])->label(false) ?></td>
					</tr>
				</table>
				</div>
			</td>
		</tr>
	</table>

    <h3><strong>Medios de Pago</strong></h3>
    <div class="form-panel" id="form-mediosDePago" style="width:100%; padding:5px;">
        <table>
            <tr>
            <?php

                $saltador = 0;

                if( count( $model->arrayMediosPago ) > 0 ){
					foreach ( $model->arrayMediosPago as $medioPago ){

						if( $saltador == 3 ){

							$saltador = 0;
							echo '</tr><tr>';
						}

						$saltador++;

						?>
						<td width="150px">
						<?=
                            Html::checkbox( 'Caja[mediosPago]['.$medioPago['mdp'].']', $medioPago['activa'], [
								'id'    => $medioPago['mdp'],
								'label' => $medioPago['nombre'],
                                'uncheck' => '0',
							]);
						?>
						</td>
						<?php
					}
				}
            ?>
            </tr>
        </table>
    </div>

	<h3><strong>Agentes externos</strong></h3>
	<div class="form-panel" id="form-agentesExternos" style="width:100%; padding:5px;">


		<?php

		//Se encarga de modificar el valor del nombre de contribuyente
		//
		Pjax::begin(['id'=>'actualizaNombreContrib']);

		if (isset($_POST['contrib_id']))
		{

			$persona_id = $_POST['contrib_id'];

			if (strlen($persona_id) < 8)
			{
				$persona_id = utb::GetObjeto(3,(int)$persona_id);
				echo '<script>$("#caja-ext_num").val("'.$persona_id.'")</script>';
			}

			$obj_nom = utb::getNombObj("'".$persona_id."'");

			echo '<script>$("#caja-cuenta_nombre1").val("'.$obj_nom.'")</script>';

		}

		Pjax::end();

		Pjax::begin(['id'=>'actualizaNombreBanco']);

		if (isset($_POST['banco_id'])){

				$valor = $model->getNombre('banco_entidad', 'nombre', 'bco_ent='.$_POST['banco_id']);

				echo "<script>actualizaBanco('". $valor . "');</script>";

			}

		Pjax::end();

		?>

		<table width="100%">
			<tr>
				<td><label>Contrib.</label></td>
				<td></td>
				<td colspan="5">
					<?= $form->field($model, 'ext_num', ['options' => ['style' => 'display:inline-block;']])->textInput(['maxlength' => 8,'style' => 'width:80px;', 'onchange'=>'$.pjax.reload({container:"#actualizaNombreContrib",data:{contrib_id:this.value},method:"POST"})'])->label(false) ?>

					<!-- INICIO Botón Búsqueda Persona -->
					<?= Html::Button("<i class='glyphicon glyphicon-search'></i>",[
							'class' => 'bt-buscar',
							'id' => 'caja-btBuscaContribuyente',
							'disabled' => false,
							'onclick' => '$("#BuscaObjModalBuscaPersona").modal("show");']);
					?>
					<!-- FIN Botón Búsqueda Persona -->

				<?= Html::input('text', 'cuenta_nombre1', $model->getNombre("objeto","nombre","obj_id='" . $model->ext_num . "'"), [
						'id' => 'caja-cuenta_nombre1',
						'class' => 'form-control solo-lectura',
						'style' => 'width:313px',
						'tabindex' => -1,
					]);
				?>
				</td>

			</tr>

			<tr>
				<td><label>Banco:</label></td>
				<td></td>
				<td colspan="5"><?= $form->field($model, 'ext_bco_ent', ['options' => ['style' => 'display:inline-block;']])->textInput(['id'=>'caja-bt_banco','style' => 'width:80px;', 'onchange'=>'$.pjax.reload({container:"#actualizaNombreBanco",data:{banco_id:this.value},method:"POST"})'])->label(false) ?>

					<!-- boton de búsqueda modal -->
					<?php
					Modal::begin([
		                'id' => 'BuscaAux1',
						'toggleButton' => [
		                    'label' => '<i class="glyphicon glyphicon-search"></i>',
		                    'class' => 'bt-buscar',
		                    'id'=>'caja-btnBuscaBanco'
		                ],
		                'closeButton' => [
		                  'label' => '<b>X</b>',
		                  'class' => 'btn btn-danger btn-sm pull-right',
		                ],
		                 ]);


		            echo $this->render('//taux/auxbusca', [	'tabla' => 'banco_entidad',
		            										'campocod' => 'bco_ent',
		            										'boton_id' => 'BuscaAux1',
		            										'idcampocod' => 'caja-bt_banco',
		            										'idcamponombre' => 'caja-cuenta_nombre2'

		            									]);

					Modal::end();
		            ?>
		            <!-- fin de boton de búsqueda modal -->
           		<?php echo Html::input('text', 'cuenta_nombre2', $model->getNombre("banco_entidad","nombre","bco_ent=" . ($model->ext_bco_ent == '' ? 0 : $model->ext_bco_ent)), ['id' => 'caja-cuenta_nombre2','class' => 'form-control', 'style' => 'width:313px;' . 'background-color:#E6E6FA', 'readonly' => 'readonly' ]); ?>
           		</td>

			</tr>

			<tr>
				<td><label>Origen:</label></td>
				<td width="5px"></td>
				<?php if ($model->ext_tori == '') $model->ext_tori = 0; ?>
				<td><?= $form->field($model, 'ext_tori')->dropDownList(utb::getAux('caja_tori','cod','nombre'),['style' => 'width:100%;', 'onchange'=>'cambiaOrigen($(this).val());'])->label(false) ?></td>
				<td width="20px"></td>
				<td><label>Diseño:</label></td>
				<td width="5px"></td>
				<td><?= $form->field($model, 'ext_tdisenio')->dropDownList(utb::getAux('caja_tdisenio','cod','nombre',1),['style' => 'width:100%;'])->label(false) ?></td>


			</tr>



			<tr>
				<td colspan="7">
					<label>Código asignado por la entidad:</label>
					<?= $form->field($model, 'ext_cod_ent', ['options' => ['style' => 'display:inline-block; width:305px;']])->textInput(['maxlength' => 20,'style' => 'width:100%;'])->label(false) ?>
				</td>
			</tr>



			<tr>
				<td><label>Host:</label></td>
				<td width="5px"></td>
				<td><?= $form->field($model, 'ext_host')->textInput(['maxlength' => 30,'style' => 'width:100%;', 'id' => 'txHost'])->label(false) ?></td>
				<td width="20px"></td>
				<td><label>Recurso:</label>
				<td width="5px"></td>
				<td><?= $form->field($model, 'ext_recurso')->textInput(['maxlength' => 30,'style' => 'width:100%;', 'id' => 'txRecurso'])->label(false) ?></td>
			</tr>
			<tr>
				<td><label>Usuario:</label></td>
				<td></td>
				<td><?= $form->field($model, 'ext_usr')->textInput(['maxlength' => 30,'style' => 'width:100%;', 'id' => 'txUsuario'])->label(false) ?></td>
				<td width="20px"></td>
				<td><label>Contraseña:</label>
				<td></td>
				<td><?= $form->field($model, 'ext_pwd')->textInput(['maxlength' => 30,'style' => 'width:100%;', 'id' => 'txPassword'])->label(false) ?></td>
			</tr>

	   		<tr>
		   		<td colspan="2"></td>

		   		<?php

		   	   	$fecha = $model->fchmod;

	   			if ($fecha != ""){

		   			$año = substr($fecha, 0, 4);

		   			$mes = substr($fecha, 5,2);

		   			$dia = substr($fecha, 8,2);

		   			$fecha = $dia . '/' . $mes .'/' . $año;

	   			}

		   		?>

		   		<td align="right"><label>Modificación: </label></td>
		   		<td></td>
		   		<td colspan="3"><?php echo Html::input('text', 'usrmod', $model->getUsuarioModifica($model->usrmod) . ' - ' .$fecha, ['id' => 'usrmod','class' => 'form-control', 'style' => 'width:100%;;' . 'background-color:#E6E6FA', 'readonly' => 'readonly' ]); ?></td>

			</tr>
		</table>
	</div>
</div>

	<?php

	/**
	 * @param $consulta es una variable que:
	 * 		=> $consulta == 1 => El formulario se dibuja en el index
	 * 		=> $consulta == 0 => El formulario se dibuja en el create
	 * 		=> $consulta == 3 => El formulario se dibuja en el update
	 * 		=> $consulta == 2 => El formulario se dibuja en el delete
	 */

	if(!isset($consulta)){

		$consulta = 1;

	}

	 if($consulta == 0 || $consulta == 2 || $consulta == 3) { ?>
    <div class="text-center" style="margin-top:10px;margin-bottom:10px">

    	<?php
    		if ( $consulta == 0 || $consulta == 3 )
    	        echo Html::submitButton('Grabar', ['class' =>'btn btn-success']);
    		else {

				echo Html::a('Eliminar', ['delete', 'accion'=>1, 'id' => $model->caja_id], [
		            'class' => 'btn btn-danger',
		            'data' => [
		                'confirm' => 'Esta seguro que desea eliminar ?',
		       	         'method' => 'post',
	            	],
				]);
    		}
    		echo '&nbsp;&nbsp;&nbsp;';
    		echo Html::a('Cancelar',['index'],['class' => 'btn btn-primary',])
    	?>
	</div>

    <?php
	 	}

	 echo $form->errorSummary( $model );

 	ActiveForm::end();



	if (isset($error) && $error != '')
	{

	?>


	<div class="error-summary">

		<p>Por favor corrija los siguientes errores:<p>

		<?= $error; ?>
	</div>

	<?php

		$error = '';
	}



    	if ($consulta == 1 or $consulta == 2) {
    		echo "<script>DesactivarForm('form-caja');</script>";
    	}

    	//En caso de que se cree o se actualize
    	if ($consulta == 0 or $consulta == 3){

    	?>

<script>

	//Función que habilita y deshabilita los edit dependiendo del tipo de caja seleccionada
	function tipoCaja(){

		/*
		 	TipoCaja == 1 => TipoCaja == OnLine
			TipoCaja == 3 => TipoCaja == Débito
			TipoCaja == 6 => TipoCaja == HomeBanking
		*/

		var tipoCaja = document.getElementById('caja-tipo');


		if (tipoCaja.value == 1)
		{
			$("#caja-editamonto").attr('checked',false);
			$("#caja-editamonto").attr('disabled','disabled');
			$("#form-agentesExternos").find('input').attr('disabled','disabled');
			$("#form-agentesExternos").find('select').attr('disabled','disabled');
			$("#caja-destino").removeAttr('disabled');
			$("#caja-validar").removeAttr('disabled');
			$("#caja-copia").removeAttr('disabled');
			$("#caja-resumen").removeAttr('disabled');
			$("#caja-teso_id").removeAttr('disabled');
			$("#caja-ext_tori option[value=0]").attr('selected',true);
            $("#caja-ext_num").val("");
            $("#caja-cuenta_nombre1").val("");
			$("#caja-btBuscaContribuyente").attr('disabled','disabled');

		} else {

			$("#caja-editamonto").removeAttr('disabled');
			$("#form-agentesExternos").find('input').removeAttr('disabled');
			$("#form-agentesExternos").find('select').removeAttr('disabled');
			$("#caja-destino").attr('disabled','disabled');
			$("#caja-validar").attr('disabled','disabled');
			$("#caja-copia").attr('disabled','disabled');
			$("#caja-resumen").attr('disabled','disabled');
			$("#caja-teso_id option[value=0]").attr('selected',true);
			$("#caja-teso_id").attr('disabled','disabled');
			$("#caja-btBuscaContribuyente").removeAttr('disabled');

		}

		if (tipoCaja.value == 3)
		{
			$("#caja-btnBuscaBanco").removeAttr('disabled');
			$("#caja-cuenta_nombre2").removeAttr('readOnly');
			$("#caja-bt_banco").removeAttr('readOnly');

		} else {

			$("#caja-cuenta_nombre2").val('');
			$("#caja-bt_banco").val('');
			$("#caja-btnBuscaBanco").attr('disabled','disabled');
			$("#caja-cuenta_nombre2").attr('disabled','disabled');
			$("#caja-bt_banco").attr('disabled','disabled');
		}

		if( tipoCaja.value == 6 || tipoCaja.value == 3 || tipoCaja.value == 4 ){
			$("#caja-ext_cod_ent").prop("disabled", false);
		} else {
			$("#caja-ext_cod_ent").prop("disabled", true);
			$("#caja-ext_cod_ent").val("");
		}

		cambiaOrigen($("#caja-ext_tori").val());
		cambiaDestino($("#caja-destino").val());
	}

	//Función que activa el select cuando origen != 0
	function cambiaOrigen(nuevo)
	{
		nuevo = parseInt(nuevo);
		habilitar = !(isNaN(nuevo) || (nuevo != 2 && nuevo != 3));

		$("#caja-ext_tdisenio").prop("disabled", nuevo == 0);
		if(nuevo == 0) $("#caja-ext_tdisenio").val(0);

		$("#txHost").prop("disabled", !habilitar);
		$("#txRecurso").prop("disabled", !habilitar);
		$("#txUsuario").prop("disabled", !habilitar);
		$("#txPassword").prop("disabled", !habilitar);
	}

	/* Función que se encarga de hablilitar los dropDownList de supervisores */
	function actualizaSupervisores(){

		$sup1 = $("#caja-sup1");
		$sup2 = $("#caja-sup2");
		$sup3 = $("#caja-sup3");
		$sup4 = $("#caja-sup4");

		if($sup1.val() == 0)
		{
			$sup2.attr('readOnly',true); $sup2.val(0);
			$sup3.attr('readOnly',true); $sup3.val(0);
			$sup4.attr('readOnly',true); $sup4.val(0);

		} else {

			$sup2.removeAttr('readOnly');

			if($sup2.val() == 0)
			{
				$sup3.attr('readOnly',true); $sup3.val(0);
				$sup4.attr('readOnly',true); $sup4.val(0);

			} else {

				$sup3.removeAttr('readOnly');

				if($sup3.val() == 0)
				{
					$sup4.attr('readOnly',true); $sup4.val(0);

				} else {
					$sup4.removeAttr('readOnly');
				}
			}
		}

	}

	actualizaSupervisores();


	function actualizaContrib(dato){

		var elementoNombreContrib = document.getElementById('caja-cuenta_nombre1');

		elementoNombreContrib.value = dato;

	}

		function actualizaBanco(dato){

		var elementoNombreBanco = document.getElementById('caja-cuenta_nombre2');

		elementoNombreBanco.value = dato;

	}

	function cambiaDestino(nuevo){

		nuevo = parseInt(nuevo);
		var habilitar = !(isNaN(nuevo) || nuevo == 0);

		$("#ckValidar").prop("disabled", !habilitar);
		$("#ckCopia").prop("disabled", !habilitar);
		$("#ckResumen").prop("disabled", !habilitar);
	}

	tipoCaja();




</script>


    	<?php

    	}

    	//En el caso en que se dibuje el _form en el update,
    	//se pone en modo readOnly el campo caja_id
    	if ($consulta == 3)
    	{
    		?>
    		<script>

    		$("#caja-caja_id").attr('readOnly',true);

    		</script>

    		<?php
    	}

    	//INICIO Ventana Modal "Buscar Persona"
		Modal::begin([
			'id' => 'BuscaObjModalBuscaPersona',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Persona</h2>',
	        'closeButton' => [
	              'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
	              'class' => 'btn btn-danger btn-sm pull-right',
	            ],
	        'size' => 'modal-lg',
			]);

			echo $this->render('//objeto/objetobuscarav',[
									'idpx' => 'Busca',
									'id' => 'ModalBuscaPersona',
									'txCod' => 'caja-ext_num',
									'txNom' => 'caja-cuenta_nombre1',
									'tobjeto' => 3,
									'selectorModal' => '#BuscaObjModalBuscaPersona',
				]);

		Modal::end();
		//FIN Ventana Modal "Buscar Persona"

    ?>
