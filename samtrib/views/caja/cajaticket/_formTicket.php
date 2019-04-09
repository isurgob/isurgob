<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use yii\bootstrap\Alert;
use \yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\caja\CajaTicket */
/* @var $form yii\widgets\ActiveForm */


/**
 * Recibo:
 *
 * 	$model => Modelo de "Ticket"
 *  $consulta => Indica el modo en que se debe dibujar la forma
 *  $alert => Indica un mensaje, en caso de existir el mismo
 *  $botonVolver => Indica si debe existir un botón "Volver", y a que action debe dirigir.
 * 		-> 0. No existe botón "Volver". No redirige a ningún action.
 * 		-> 1. Existe botón "Volver". Redirige a "listado".
 * 		-> 2. Existe botón "Volver". Redirige a "opera".
 */


 switch ( $botonVolver )
 {
 	case 0:

 		$title = 'Consulta de Tickets de Caja';
		$this->params['breadcrumbs'][] = 'Tickets';

		break;

	case 1:

 		$title = 'Consulta de Tickets de Caja';
		$this->params['breadcrumbs'][] = ['label' => 'Opciones', 'url' => ['//caja/listadocobrocticket/index']];
		$this->params['breadcrumbs'][] = 'Tickets';

		break;

	case 2:

		$title = 'Consulta de Tickets de Caja';
		$this->params['breadcrumbs'][] = ['label' => 'Opciones', 'url' => ['//caja/listadocobrocticket/index']];
		$this->params['breadcrumbs'][] = ['label' => 'Operaciones', 'url' => ['caja/cajaticket/opera&id='.$oid.'&rei=0']];
		$this->params['breadcrumbs'][] = 'Tickets';

		break;
 }

?>


<div class="caja-ticket-form" style="width:500px">
	<table width="100%">
		<tr>
			<td>

				<h1><?= Html::encode($title) ?></h1>

			    <?php

                    $form = ActiveForm::begin([
							'id'=>'form-caja-ticket',
							'action' => ['buscar']]);

                    // //Input oculto que determinará si se modifica o no el Ticket
                    // echo Html::input('hidden','editaTicket',$edita,[
                    //      'id' => 'ticket_editaTicket',
                    //      'onclick' => 'editarTicket()',
                    //  ]);
                ?>
			</td>
			<td align="right">
				<?php

					if ( $botonVolver == 1 )
						echo Html::a('Volver',['//caja/listadocobrocticket/index'],['class'=>'btn btn-primary', 'style'=> 'display:visible' ]);

					if ( $botonVolver == 2 )
						echo Html::a('Volver',['opera', 'id' => $oid, 'rei' => 0],['class'=>'btn btn-primary', 'style'=> 'display:visible' ]);

				?>
			</td>
		</tr>
	</table>

<div class="form-panel" style="padding-bottom:4px;margin-right:0px">
<table>
	<tr>
		<td width="50px"><label>Ticket:</label></td>
		<td>
			<?= Html::input('text','ticket-id',$model->ticket,[
					'id'=>'ticket-id',
					'class'=>'form-control text-center' . ( $botonVolver || $edita ? ' solo-lectura' : ''),
					'style'=>'width:80px;',
					'onkeypress'=>'return justNumbers(event)',
					'maxlength'=>9,
				]);
			?>
		</td>
		<td width="50px"></td>
		<td>
			<?= Html::Button('Aceptar',[
					'class' => 'btn btn-success',
                    'style' => $botonVolver || $edita ? 'display:none' : 'display:block',
					'onclick'=>'validarClick()',
				]);
			?>
		</td>
    <td width="20px"></td>
    <td width="50px">
        <?=
            Html::a( 'Editar', ['editaticket', 'id' => $model->ticket],  [
                'id' => 'ticket_btEditar',
                'class' => 'btn btn-primary',
                'style' => $model->ticket == '' || $model->est == 'B' || $botonVolver || $edita ? 'display:none' : 'display:block',
            ]);
        ?>
    </td>
	</tr>
</table>
</div>

<div class="form-panel" style="margin-right:0px">
<table border='0'>
	<tr><td><h3><b>Datos del Ticket</b></h3></td></tr>
</table>

<table>
	<tr>
		<td width="50px"><label>Operac.</label></td>
		<td><?= Html::input('text','opera',$model->opera,['class'=>'form-control text-center','style'=>'width:80px;background-color:#E6E6FA','disabled'=>true]) ?></td>
		<td width="30px"></td>
		<td><label>Fecha</label></td>
		<td><?= Html::input('text','fecha',$model->fecha,['class'=>'form-control','style'=>'width:70px;background-color:#E6E6FA;text-align:center','disabled'=>true]) ?></td>
		<td width="10px"></td>
		<td><label>Hora</label></td>
		<td><?= Html::input('text','fecha',$model->hora,['class'=>'form-control','style'=>'width:60px;background-color:#E6E6FA;text-align:center','disabled'=>true]) ?></td>
		<td width="20px"></td>

		<td><label>Estado</label></td>
		<td><?= Html::input('text','est',$model->est,['maxlength' => 1,'style' => 'width:10px;','class'=>'form-control','style'=>'width:52px;background-color:#E6E6FA;text-align:center','disabled'=>true]) ?></td>
	</tr>
</table>

<table>
	<tr>
		<td width="50px"><label>Caja</label></td>
		<td><?= Html::input('text','caja_id',$model->caja_id,['class'=>'form-control','style'=>'width:50px;background-color:#E6E6FA;text-align:center','disabled'=>true]) ?></td>
		<td width="50px"></td>
		<td><label>Tesorería</label></td>
		<td><?= Html::input('text','teso_nom',$model->teso_nom,['class'=>'form-control','style'=>'width:175px;background-color:#E6E6FA','disabled'=>true]) ?></td>
	</tr>
</table>

<table>
	<tr>
		<td width="50px"><label>Objeto</label></td>
		<td><?= Html::input('text','obj_id',$model->obj_id,['class'=>'form-control','style'=>'width:80px;background-color:#E6E6FA','disabled'=>true]) ?></td>
		<td><?= Html::input('text','subcta',$model->subcta,['class'=>'form-control','style'=>'width:30px;background-color:#E6E6FA','disabled'=>true]) ?></td>
	</tr>
</table>

<table>
	<tr>
		<td width="50px"><label>Contrib.</label></td>
		<td><?= Html::input('text','num',$model->num,['class'=>'form-control','style'=>'width:80px;background-color:#E6E6FA','disabled'=>true]) ?></td>
		<td><?= Html::input('text','num_nom',$model->num_nom,['class'=>'form-control','style'=>'width:250px;background-color:#E6E6FA','disabled'=>true]) ?></td>
	</tr>
</table>

<table>
	<tr>
		<td width="50px"><label>Tributo</label></td>
		<td><?= Html::input('text','trib_id',$model->trib_id,['class'=>'form-control','style'=>'width:40px;background-color:#E6E6FA;text-align:center','disabled'=>true]) ?></td>
		<td><?= Html::input('text','trib_nom',$model->trib_nom,['class'=>'form-control','style'=>'width:290px;background-color:#E6E6FA','disabled'=>true]) ?></td>
	</tr>
</table>

<table>
	<tr>
		<td width="50px"><label>Año</label></td>
		<td><?= Html::input('text','anio',$model->anio,['style' => 'width:100px;','class'=>'form-control','style'=>'width:60px;background-color:#E6E6FA;text-align:center','disabled'=>true]) ?></td>
		<td width='5px'></td>
		<td width='40px'><label>Cuota</label></td>
		<td><?= Html::input('text','cuota',$model->cuota,['style' => 'width:100px;','class'=>'form-control','style'=>'width:41px;background-color:#E6E6FA;text-align:center','disabled'=>true]) ?></td>
		<td width='5px'></td>
		<td><label>Monto</label></td>
		<td><?= Html::input('text','monto',$model->monto,['class'=>'form-control','style'=>'width:100px;background-color:#E6E6FA;text-align:right','disabled'=>true]) ?></td>
	</tr>
	<tr>
		<td width="50px"><label>MDP</label></td>
		<td colspan='4'><?= Html::input('text','mdps',$model->mdps,['class'=>'form-control','style'=>'width:160px;background-color:#E6E6FA;text-align:left','disabled'=>true]) ?></td>
		<td></td>
		<td><label>Facilidad Nº</label></td>
		<td><?= Html::input('text','faci_id',$model->faci_id,['class'=>'form-control','style'=>'width:100px;background-color:#E6E6FA;text-align:right','disabled'=>true]) ?></td>
	</tr>
</table>

</div>

	<?php

    ActiveForm::end();

	$tab = 0;

	echo Tabs :: widget ([

    	 	'id' => 'TabDescuento',
			'items' => [
 				['label' => 'Detalle',
 				'content' => $this->render('detalle', ['model' => $model, 'edita' => $edita]),
 				'active' => ( $tab==0 ) ?  true : false,
 				'options' => ['class'=>'tabItem'],
 				],
 				['label' => 'Obs' ,

 				'content' => $form->field($model, 'obs')->textarea([
                    'maxlength' => 1000,
                    'style' => 'width:450px;height:100px;resize:none;background-color:#E6E6FA',
                    'disabled'=>true,
                ]),
 				'active' => ( $tab == 1 ) ?  true : false,
 				'options' => ['class'=>'tabItem'],
                'headerOptions' => ['style' => ($edita ? 'display:none' : 'display:block')]
 				],

 				['label' => 'Boleto' ,
				'content' => $this->render('boleto',['model' => $model]),
 				'active' => ($tab==2) ?  true : false,
 				'options' => ['class'=>'tabItem'],
 				'headerOptions' => ['style' => ($model->trib_tipo == 7 ? 'display:visible' : 'display:none')]
 				],
 				['label' => 'Anula' ,
 				'content' => $form->field($model, 'motivo_baja')->textarea(['maxlength' => 1000,'style' => 'width:450px;height:100px;background-color:#E6E6FA','disabled'=>true]),
 				'active' => ($tab==3 and $model->est == 'B') ?  true : false,
 				'options' => ['class'=>'tabItem'],
 				'headerOptions' => ['style' => ($model->est == 'B' ? 'display:visible' : 'display:none')]
 				],

	 			['label' => 'Recibo' ,
	 			'content' => $this->render('recibo',['model' => $model]),
	 			'options' => ['class'=>'tabItem'],
	 			'active' => $tab == 4 ? true : false,
	 			'headerOptions' => ['style' => ($model->trib_id == 12 ? 'display:visible' : 'display:none')]
	 			]
    	]
    ]);

    $form2 = ActiveForm::begin([
            'id' => 'form_modificaTicket',
        ]);

        echo Html::input( 'hidden', 'txGrabar', 1 );

    //Conjunto de botones que se activan en caso de editar el ticket
    if ( $edita )
    {
        ?>
        <div style="margin-top: 8px">
            <?=
                Html::Button( 'Aceptar', [
                    'id' => 'ticket_edita_btAceptar',
                    'class' => 'btn btn-success',
                    'onclick' => 'f_aceparModificacionTicket()',
                ]);
            ?>

            &nbsp;&nbsp;

            <?=
                Html::Button( 'Cancelar', [
                    'id' => 'ticket_edita_btAceptar',
                    'class' => 'btn btn-primary',
                    'onclick' => 'validarClick()',
                ]);
            ?>
        </div>
        <?php
    }

    echo $form2->errorSummary( $model, [
        'id' => 'ticket_mensajeError',
        'style' => 'margin-top: 8px'
    ]);

    ActiveForm::end();
?>

</div>

<?php
	$mensaje = '';

    if (isset($alerta))
    {
    	switch ($alerta)
    	{
    		case 0:
    			$mensaje = '';
    			break;
    		case 1:

    			$mensaje = 'No se encontró información del Ticket o la consulta no está permitida.';
    			break;

    		case 2:
    			$mensaje = 'Ingrese un código de Ticket.';
    			break;
    	}
    }

    if ( $mensaje != '' )
    {
        //Mostrar mensaje de error
        echo '<script>mostrarErrores( ["' . $mensaje . '"], "#ticket_mensajeError" );</script>';
    }

?>

<script>
function validarClick()
{
    //Ocultar Div de Errores
    $( "#ticket_mensajeError" ).css( "display", "none" );

	if($("#ticket-id").val() == '')
	{
		mostrarErrores( ['Ingrese un código de Ticket.'], "#ticket_mensajeError" );

	} else {

        $( "#ticket_editaTicket").val( "0" );

		$("#form-caja-ticket").submit();
	}
}

function f_aceparModificacionTicket()
{
    var monto = parseFloat( $( "#modificaTicket_montoTicket" ).val() ),
        monto_actual = parseFloat( $( "#modificaTicket_montoTicketActual" ).val() ),
        error = new Array();

    if ( monto == monto_actual )
        $( "#form_modificaTicket" ).submit();
    else {
        error.push( "El monto de Ticket y el monto actual no coinciden." );

        mostrarErrores( error, "#ticket_mensajeError" );
    }
}

</script>
