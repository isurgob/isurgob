<?php
use yii\helpers\Html;
use app\utils\db\utb;

?>
<div class='divredon desc' height='150px'>
	<div class='cond' style='padding:0px 5px'>Datos Generales:</div>
	<hr style="color: #000; margin:1px" />
	<!-- Plan -->
	<div id='DivPlan' style='display:<?= ($emision[$i]['trib_id']==1 ? 'block' : 'none')?>'>
		<?php 
			if ($emision[$i]['trib_id']==1){ 
				$tipo_nom = utb::getCampo("v_plan","plan_id=" . $sub2[0]['plan_id'],"tplan_nom");
		?>
		<table border='0' style='padding:0px 5px' class='desc'>
			<tr><td>Tipo:&nbsp;</td><td colspan='2'><b><?=$tipo_nom?></b></td></tr>
			<tr><td>Cant. Cuotas:&nbsp;</td><td><b><?=$sub2[0]['cuotas']."</b></td><td>Plan Anterior: <b>".$sub2[0]['planant']?></b></td></tr>
			<tr><td>Firmante:&nbsp;</td><td colspan='2'><b><?=$sub2[0]['resp'] ?></b></td></tr>
			<tr><td>Documento:&nbsp;</td><td colspan='2'><b><?=$sub2[0]['resptdoc_nom']." ".$sub2[0]['resp_ndoc'] ?></b></td></tr>
			<tr><td colspan='3'><b><?= ($sub2[0]['cuota_adelanta'] != 0 ? 'Cuotas Adelantadas: '.$sub2[0]['cuota_adelanta'] : '') ?></b></td></tr>
			<tr><td colspan='3'><b>
			<?php 
				switch (utb::getTobj($emision[$i]['obj_id'])) {
					case 1:
						echo "Nomeclatura: ".$emision[$i]['obj_dato'];
						break;
					case 2:
						echo "CUIT: ".$emision[$i]['obj_dato'];
						break;
					case 3:
						echo "Documento: ".$emision[$i]['obj_dato'];
						break;
					case 4:
						echo "Nomeclatura: ".$emision[$i]['obj_dato'];
						break;	
					case 5:
						echo "Dominio: <font style='text-transform: uppercase;'>".$emision[$i]['obj_dato']."</font>";
						break;
					case 6:
						echo "Dominio: <font style='text-transform: uppercase;'>".$emision[$i]['obj_dato']."</font>";
						break;	
    				default:
    					echo '';
    			}
			?>
			</b></td></tr>
		</table>
			<?php if ( $emision[$i]['tobj']==1 ){ ?>
				<table border='0' style='padding:0px 5px' class='desc'>
					<tr>
						<td> <b> Frente: </b> </td> <td> <?= $sub2[0]['frente'] ?> </td>
						<td> <b> Sup.Terreno: </b> </td> <td> <?= $sub2[0]['supt'] ?> </td>
						<td> <b> Sup.Mejoras: </b> </td> <td> <?= $sub2[0]['supm'] ?> </td>
					</tr>
				</table>	
			<?php } ?>
		<?php } ?>
	</div> 
	<!-- Inmueble -->
	<div id='DivInmueble' style='display:<?= ($emision[$i]['tobj']==1 && !in_array($emision[$i]['trib_id'],[1]) ? 'block' : 'none')?>'>
		<?php if ($emision[$i]['tobj']==1){ ?>
		<table class='desc' style='padding:5px;'>
			<tr><td>Parc.Muni.:</td><td><b><?= $sub2[0]['obj_id'] ?>&nbsp;&nbsp;Nombre:&nbsp;<?= $sub2[0]['nombre'] ?></b></td></tr>
			<tr><td>Dom.Parcelario:</td><td><b><?= $sub2[0]['dompar_dir'] ?></b></td></tr>
			<tr><td>Barrio:</td><td><b><?= $sub2[0]['barr_nom'] ?></b></td></tr>
			<tr><td></td>
				<td>
					<table class='desc' style='text-align:center;border-bottom:1px solid #000;'>
						<tr><td><b>Urb/Rur</b><td><b>Par.Prov</b></td><td><b>Nomenc.</b></td><td><b>Manz.</b></td><td><b>Parcela</b></td><td><b>UF</b></td><td><b>ZonaT</b></td></tr>
						<tr><td><?= $sub2[0]['urbsub'] ?></td><td><?= $sub2[0]['parp'] ?></td><td><?= $sub2[0]['s1'].' - '.$sub2[0]['s2'].' - '.$sub2[0]['s3'] ?></td><td><?= $sub2[0]['manz'] ?></td><td><?= $sub2[0]['parc'] ?></td><td><?= $sub2[0]['uf'] ?></td><td><?= $sub2[0]['zonat_nom'] ?></td></tr>
					</table>
				</td>
			</tr>
			<tr><td>NC Anterior:</td><td><b><?= $sub2[0]['nc_ant'] ?></b></td></tr>
		</table>
			<?php if ($emision[$i]['trib_id'] != 3) { ?>
				<table class='desc' style='padding:5px' cellspacing='5'>
					<tr><td>Comprador:</td><td colspan='5'><b><?= $sub2[0]['comprador'] ?></b></td></tr>
					<tr><td>Coef:</td><td><b><?= number_format($sub2[0]['coef'],2) ?></b></td><td>Val.Básico:</td><td><b><?= number_format($sub2[0]['valbas'],2) ?></b></td><td>Frente:</td><td><b><?= number_format($sub2[0]['frente'],2) ?></b></td></tr>
					<tr><td>Sup.Terreno:</td><td><b><?= number_format($sub2[0]['supt'],2) ?></b></td><td>Mejoras:</td><td colspan='3'><b><?= number_format($sub2[0]['supm'],2) ?></b></td></tr>
					<tr><td>Aval.Total:</td><td><b><?= number_format($sub2[0]['avalt'] + $sub2[0]['avalm'],2) ?></b></td></tr>
					<tr><td>Régimen:</td><td colspan='3'><b><?= $sub2[0]['regimen_nom'] ?></b></td><td>Servicio:</td><td colspan='2'><b><?= $sub2[0]['serv_nom'] ?></b></td></tr>
				</table>
			<?php } ?>	
		<?php } ?>
	</div>
	<!-- Comercio -->
	<div id='DivComercio' style='display:<?= ($emision[$i]['tobj']==2 && !in_array($emision[$i]['trib_id'],[1,3]) ? 'block' : 'none')?>'>
		<?php if ($emision[$i]['tobj']==2){ ?>
		<table class='desc' style='padding:5px' cellspacing='2'>
			<tr><td>Nombre:</td><td colspan='3'><b><?= $sub2[0]['nombre'] ?></b></td></tr>
			<tr><td>Legajo:</td><td colspan='3'><b><?= $sub2[0]['legajo'] ?></b></td></tr>
			<tr><td>Tipo:</td><td><b><?= $sub2[0]['tipo_nom'] ?></b></td><td>Habilitación:</td><td><b><?= $sub2[0]['fchhab'] ?></b></td></tr>
			<tr><td>Cond.IVA:</td><td><b><?= $sub2[0]['iva_nom'] ?></b></td><td>CUIT:</td><td><b><?= $sub2[0]['cuit'] ?></b></td></tr>
			<tr><td>Domicilio:</td><td colspan='3'><b><?= $sub2[0]['dompar_dir'] ?></b></td></tr>
			<tr><td>Superficio:</td><td><b><?= number_format($sub2[0]['sup'],2) ?></b></td><td>Cant.Emple.:</td><td><b><?= $sub2[0]['cantemple'] ?></b></td></tr>
			<tr><td>Parque Industrial:</td><td><b><?= ($sub2[0]['pi'] == 1 ? 'SI' : 'NO') ?></b></td><td>Sin Local Comercial:</td><td><b><?= ($sub2[0]['sinlocal'] == 1 ? 'SI' : 'NO') ?></b></td></tr>
		</table>
		<?php } ?>
	</div>
	<!-- Personas -->
	<div id='DivPersona' style='display:<?= ($emision[$i]['tobj']==3 && !in_array($emision[$i]['trib_id'],[1,3]) ? 'block' : 'none')?>'>
		<?php if ($emision[$i]['tobj']==3){ ?>
		<table class='desc' style='padding:5px'>
			<tr><td>Tipo Doc.:</td><td><b><?= $sub2[0]['tdoc_nom'] ?>&nbsp;&nbsp;Número:&nbsp;<?= $sub2[0]['ndoc'] ?></b></td></tr>
			<tr><td>CUIT/CUIL:</td><td><b><?= $sub2[0]['cuit'] ?></b></td></tr>
			<tr><td>Cond.IVA:</td><td><b><?= $sub2[0]['iva_nom'] ?></b></td></tr>
			<tr><td>Dom.Legal:</td><td><b><?= $sub2[0]['domleg_dir'] ?></b></td></tr>
			<tr><td>Dom.Resid:</td><td><b><?= $sub2[0]['domres_dir'] ?></b></td></tr>
		</table>
		<?php } ?>
	</div>
	<!-- Cementerio -->
	<div id='DivCementerio' style='display:<?= ($emision[$i]['tobj']==4 && !in_array($emision[$i]['trib_id'],[1,3]) ? 'block' : 'none')?>'>
		<?php if ($emision[$i]['tobj']==4){ ?>
		<table class='desc' style='padding:5px' cellspacing='5'>
			<tr><td>Tipo:</td><td><b><?= $sub2[0]['tipo_nom'] ?></b></td><td>Categoría:</td><td><b><?= $sub2[0]['cat'] ?></b></td></tr>
			<tr><td colspan='4'>Cuadro: &nbsp; <b><?= $sub2[0]['cuadro_id'] ?></b>&nbsp;Cuerpo:&nbsp;<b><?= $sub2[0]['cuerpo_id'] ?></b> &nbsp; Piso: &nbsp;<b> <?= $sub2[0]['piso'] ?></b> &nbsp;Fila: &nbsp; <b><?= $sub2[0]['fila'] ?></b> &nbsp; Nume: &nbsp;<b> <?= $sub2[0]['nume'] ?></b></td></tr>
			<tr><td>Cod. Ant:</td><td><b><?= $sub2[0]['cod_ant'] ?></b></td><td>Delegación:</td><td><b><?= $sub2[0]['deleg_nom'] ?></b></td></tr>
			<tr><td>Ingreso:</td><td><b><?= $sub2[0]['fchingreso'] ?></b></td><td>Vencimiento:</td><td><b><?= $sub2[0]['fchvenc'] ?></b></td></tr>
		</table>
		<?php } ?>
	</div>
	<!-- Rodado --> 
	<div id='DivRodado' style='display:<?= ($emision[$i]['tobj']==5 && !in_array($emision[$i]['trib_id'],[1,3]) ? 'block' : 'none')?>'>
		<?php if ($emision[$i]['tobj']==5){ ?>
		<table class='desc' style='padding:5px' cellspacing='5'>
			<tr><td>Dominio:</td><td><b><?= $sub2[0]['dominio'] ?></b></td><td>Año:</td><td><b><?= $sub2[0]['anio'] ?></b></td></tr>
			<tr><td>Categoría:</td><td colspan='3'><b><?= $sub2[0]['cat_nom'] ?></b></td></tr>
			<tr><td>Marca:</td><td><b><?= $sub2[0]['marcamotor_nom'] ?></b></td><td>Modelo:</td><td><b><?= $sub2[0]['modelo_nom'] ?></b></td></tr>
			<tr><td>Cilindrada:</td><td><b><?= $sub2[0]['cilindrada'] ?></b></td><td>Peso:</td><td><b><?= $sub2[0]['peso'] ?></b></td></tr>
			<tr><td>Nro.Motor:</td><td colspan='3'><b><?= $sub2[0]['nromotor'] ?></b></td></tr>
			<tr><td>Nro.Chasis:</td><td colspan='3'><b><?= $sub2[0]['nrochasis'] ?></b></td></tr>
			<tr><td>Período Inicial:</td><td><b><?= $sub2[0]['per_ini'] ?></b></td><td>Compra:</td><td><b><?= $sub2[0]['fchcompra'] ?></b></td></tr>
			<tr><td>Delegación:</td><td colspna='3'><b><?= $sub2[0]['deleg_nom'] ?></b></td></tr>
			<?php 
				if ( $sub2[0]['tliq'] == 1 ){ // es por aforo
			?>	
					<tr><td>Aforo:</td><td colspna='3'><b><?= $sub2[0]['aforo_id'] ?></b></td><td>Valor:</td><td><b><?= $sub2[0]['aforo_valor'] ?></b></td></tr>
			<?php } ?>
		</table>
		<?php } ?>
	</div>
	<!-- Transporte -->
	<div id='DivTransporte' style='display:<?= ($emision[$i]['tobj']==6 && !in_array($emision[$i]['trib_id'],[1,3]) ? 'block' : 'none')?>'>
		<?php if ($emision[$i]['tobj']==6){ ?>
		<table class='desc' style='padding:5px' cellspacing='5'>
			<tr><td>Categoría:</td><td><b><?= $sub2[0]['cat_nom'] ?></b></td><td>Legajo:</td><td><b><?= $sub2[0]['legajo'] ?></b></td></tr>
			<tr><td>Empresa:</td><td colspan='3'><b><?= $sub2[0]['emp_nom'] ?></b></td></tr>
			<tr><td>Marca:</td><td colspan='3'><b><?= $sub2[0]['marca_nom'] ?></b></td></tr>
			<tr><td>Modelo:</td><td colspan='3'><b><?= $sub2[0]['modelo_nom'] ?></b></td></tr>
			<tr><td>Año:</td><td><b><?= $sub2[0]['anio'] ?></b></td><td>Dominio:</td><td><b><?= $sub2[0]['dominio'] ?></b></td></tr>
			<tr><td>Cilindrada:</td><td><b><?= $sub2[0]['cilindrada'] ?></b></td><td>Capacidad:</td><td><b><?= $sub2[0]['capacidad'] ?></b></td></tr>
			<tr><td>Habilitación:</td><td><b><?= $sub2[0]['fchhab'] ?></b></td><td>Carnet:</td><td><b><?= $sub2[0]['fchcarnet'] ?></b></td></tr>
			<tr><td>Salud:</td><td><b><?= $sub2[0]['fchsalud'] ?></b></td><td>Seguro:</td><td><b><?= $sub2[0]['fchseguro'] ?></b></td></tr>
		</table>
		<?php } ?>
	</div>
	<!-- Mej Plan -->
	<div id='DivMejPlan' style='display:<?= ($emision[$i]['trib_id']==3 ? 'block' : 'none')?>'>
		<?php if ($emision[$i]['trib_id']==3){ ?>
		<table class='desc' style='padding:5px' cellspacing='5'>
			<tr><td>Tipo Obra:</td><td colspan='5'><b><?= $sub2[0]['tobra'].' - '.$sub2[0]['tobra_nom'] ?></b></td></tr>
			<tr><td>Obra: </td><td colspan='5'><b><?= $sub2[0]['obra_nom'] ?></b></td></tr>
			<tr><td>Cuadra:</td><td colspan='5'><b><?= $sub2[0]['cuadra_nom'] ?></b></td></tr>
			<tr>
				<td>Sup.T:</td><td><b><?= $sub2[0]['supt'] ?></b></td>
				<td>Sup.M:</td><td><b><?= $sub2[0]['supm'] ?></b></td>
				<td>Régimen:</td><td><b><?= $sub2[0]['regimen_nom'] ?></b></td>
			</tr>
			<tr>
				<td>Frente:</td><td><b><?= $sub2[0]['frente'] ?></b></td>
				<td>Sup.Afect.:</td><td><b><?= $sub2[0]['supafec'] ?></b></td>
				<td>Coef.:</td><td><b><?= $sub2[0]['coef'] ?></b></td>
			</tr>
			<tr>
				<td>Bonif.:</td><td><b><?= $sub2[0]['bonif'] ?></b></td>
				<td>Valor Metro:</td><td><b><?= $sub2[0]['valormetro'] ?></b></td>
				<td>Valor Total:</td><td><b><?= $sub2[0]['valortotal'] ?></b></td>
			</tr>
			<tr>
				<td>Item:</td><td colspan='3'><b><?= $sub2[0]['item_id'] ?> - <?= $sub2[0]['item_nom'] ?></b></td>
				<td>Monto:</td><td><b><?= $sub2[0]['monto'] ?></b></td>
			</tr>
			<tr>
				<td colspan='4'></td>	
				<td>Total:</td><td><b><?= $sub2[0]['total'] ?></b></td>
			</tr>
		</table>
		<?php } ?>
	</div>
</div>