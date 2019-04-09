
<div class='body' >
	<p class='tt'> Notificación de Mejoras </p>
	
	<div class='divredon cond' style='padding:5px'> <?= nl2br($texto['detalle']); ?> </div>
	
	<div class='divredon' style='margin-top:10px; padding:5px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr>
				<td width='10px'> <b> Objeto: </b> </td> <td> <?= $plan->obj_id ?> </td>
				<td width='10px'> <b> Nomeclatura: </b> </td> <td><?= $plan->inm_nc ?> </td>
				<td width='10px'> <b> Anterior: </b> </td> <td><?= $plan->inm_nc_ant ?> </td>
			</tr>
			<tr>
				<td> <b> Titular: </b> </td> <td colspan='5'> <?= $plan->inm_titular ?> </td>
			</tr>
			<tr>
				<td> <b> Domicilio: </b> </td> <td colspan='5'> <?= $plan->dompar ?> </td>
			</tr>
		</table>
	</div>
	
	<div class='divredon' style='padding:5px;margin-top:10px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr>
				<td width='80px'><b>Nº Plan:</b></td> <td> <?= $alternativa->plan_id ?> </td>
			</tr>
			<tr>
				<td><b>Obra:</b></td> <td> <?= $plan->obra_nom ?> </td>
				<td width='80px'><b>Tipo:</b></td> <td> <?= $obra->tobra_nom ?> </td>
				<td width='80px'><b>Decreto:</b></td> <td> <?= $obra->decreto ?> </td>
			</tr>
			<tr>
				<td><b>Frente:</b></td> <td> <?= $plan->frente ?> </td>
				<td><b>Sup.Afec.:</b></td> <td> <?= $plan->supafec ?> </td>
				<td><b>Coef.:</b></td> <td> <?= $plan->coef ?> </td>
			</tr>
			<tr>
				<td><b>Bonif. Obra:</b></td> <td> <?= $plan->bonifobra ?> </td>
				<td><b>Valor Metro:</b></td> <td> <?= $plan->valormetro ?> </td>
				<td><b>Valor Total:</b></td> <td> <?= $plan->valortotal ?> </td>
				<td width='10px'><b>Fijo:</b></td> <td> <?= $plan->fijo ?> </td>
			</tr>
			<tr>
				<td><b>Monto:</b></td> <td> <?= $plan->item_monto ?> </td>
				<td><b>Item:</b></td> <td colspan='3'> <?= $plan->item_nom ?> </td>
				<td><b>Total:</b></td> <td> <?= $plan->total ?> </td>
			</tr>
		</table>
	</div>
	
	<div class='divredon' style='padding:5px;margin-top:10px'>
		<table class='cond' width='100%' cellspacing='4'>
			<tr>
				<td colspan='10' class='cond12'> <u> <b> Alternativa 1: </b>  <?= $alternativa->titulo1 ?> </u> </td>
			</tr>
			<tr>
				<td width='10px'> <b> Cuotas: </b> </td> <td> <?= $alternativa->cuotas1 ?> </td>
				<td width='50px'> <b> Entrega: </b> </td> <td> <?= $alternativa->entrega1 ?> </td>
				<td width='85px'> <b> Monto Cuota: </b> </td> <td> <?= $alternativa->montocuo1 ?> </td>
				<td width='50px'> <b> Financia: </b> </td> <td> <?= $alternativa->financia1 ?> </td>
				<td width='50px'> <b> Sellado: </b> </td> <td> <?= $alternativa->sellado1 ?> </td>
			</tr>
			<tr>
				<td colspan='10'> <hr> </td>
			</tr>
			<?php if ( $alternativa->titulo2 != "" ) { ?>
				<tr>
					<td colspan='10' class='cond12'> <u> <b> Alternativa 2: </b>  <?= $alternativa->titulo2 ?> </u> </td>
				</tr>
				<tr>
					<td> <b> Cuotas: </b> </td> <td> <?= $alternativa->cuotas2 ?> </td>
					<td> <b> Entrega: </b> </td> <td> <?= $alternativa->entrega2 ?> </td>
					<td> <b> Monto Cuota: </b> </td> <td> <?= $alternativa->montocuo2 ?> </td>
					<td> <b> Financia: </b> </td> <td> <?= $alternativa->financia2 ?> </td>
					<td> <b> Sellado: </b> </td> <td> <?= $alternativa->sellado2 ?> </td>
				</tr>
				<tr>
					<td colspan='10'> <hr> </td>
				</tr>
			<?php } ?>	
			<?php if ( $alternativa->titulo3 != "" ) { ?>
				<tr>
					<td colspan='10' class='cond12'> <u> <b> Alternativa 3: </b>  <?= $alternativa->titulo3 ?> </u> </td>
				</tr>
				<tr>
					<td> <b> Cuotas: </b> </td> <td> <?= $alternativa->cuotas3 ?> </td>
					<td> <b> Entrega: </b> </td> <td> <?= $alternativa->entrega3 ?> </td>
					<td> <b> Monto Cuota: </b> </td> <td> <?= $alternativa->montocuo3 ?> </td>
					<td> <b> Financia: </b> </td> <td> <?= $alternativa->financia3 ?> </td>
					<td> <b> Sellado: </b> </td> <td> <?= $alternativa->sellado3 ?> </td>
				</tr>
			<?php } ?>		
		</table>
	</div>	
	
	<p class='cond12' align='center' > <b> "Consulte por otros plazos de Financiación" </b> </p>
</div>