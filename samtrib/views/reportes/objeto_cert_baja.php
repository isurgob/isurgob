<?php
use app\utils\db\utb;
use app\models\objeto\ComerRubro;
use app\models\objeto\Comer;
use app\models\objeto\Domi;

$deuda = utb::getCampo("ctacte","est = 'D' and obj_id='" . $model->obj_id . "'","sum(nominal)");
$motivo = utb::getCampo("objeto_tbaja","cod=".$modelobjeto->tbaja);

if ($modelobjeto->tobj == 3)
	$titulo = "Certificado de Baja de Ingresos Brutos";
else
	$titulo = "Certificado de Baja de ". utb::getTObjNom($model->obj_id);

if ($modelobjeto->tobj == 3) exit;
?>
<div class='body' >
	<p class='tt'> <?= $titulo ?> </p>
	<div class='tt14' style='margin-top:10px;'>
		Certificamos que <?= ($modelobjeto->tobj == 3 ? 'la ' : 'el ') . utb::getTObjNom($model->obj_id) .($modelobjeto->tobj == 3 ? ' identificada ' : ' identificado ') ?> como
		<br><br>
		<?php if ($modelobjeto->tobj == 1){ ?>
			<table width='90%' class="tt14" align="center">
				<tr><td width='170px'><b>Nombre:</b></td><td><?= $modelobjeto->nombre ?></td></tr>
				<tr><td><b>Objeto:</b></td><td><?= $modelobjeto->obj_id ?></td></tr>
				<tr><td><b>Alta:</b></td><td><?= date("d/m/Y", strtotime($modelobjeto->fchalta))  ?></td></tr>
				<tr><td><b>Partida Prov.:</b></td><td><?= $model->parp ?></td></tr>
				<tr><td><b>Plano:</b></td><td><?= $model->plano ?></td></tr>
				<tr><td><b>Partida Prov.Origen:</b></td><td><?= $model->parporigen ?></td></tr>
				<tr><td><b>Nomeclatura:</b></td><td><?= utb::getCampo('inm i',"obj_id='".$model->obj_id."'",'sam.uf_inm_armar_nc_guiones(i.s1, i.s2, i.s3, i.manz, i.parc)') ?></td></tr>
				<tr><td><b>Unidad Funcional:</b></td><td><?= $model->uf ?></td></tr>
				<tr><td><b>Pocentaje Unidad:</b></td><td><?= $model->porcuf ?></td></tr>
				<tr><td><b>Tipo:</b></td><td><?= utb::getCampo('inm_tipo',"cod='".$model->tinm."'") ?></td></tr>
				<tr><td><b>Titularidad:</b></td><td><?= utb::getCampo('inm_ttitularidad',"cod='".$model->titularidad."'") ?></td></tr>
				<tr><td><b>Barrio:</b></td><td><?= utb::getCampo('domi_barrio',"barr_id=".intVal($model->barr_id)) ?></td></tr>
				<tr><td><b>Domicilio Parcelario:</b></td><td><?= $model->domi_parcelario ?></td></tr>
				<tr><td><b>Fecha Baja:</b></td><td><?= date_format(date_create($modelobjeto->fchbaja), 'd/m/Y') ?></td></tr>
			</table>
			<br><br>
			ha sido dado de baja de este Municipio, en  concepto de Impuesto Inmobiliario.
		<?php } ?>
		<?php
			if ($modelobjeto->tobj == 2){
				$rubro = ComerRubro::findOne(['obj_id' => $model->obj_id,'tipo_nom' => 'Principal']);
				$responsablePrincipal = Comer::getDatosResponsable( $modelobjeto->num );
		?>
			<table width='90%' class="tt14" align="center">
				<tr><td width='170px'><b>Nombre:</b></td><td><?= $modelobjeto->nombre ?></td></tr>
				<tr><td><b>Objeto:</b></td><td><?= $modelobjeto->obj_id ?></td></tr>
				<tr><td><b>Alta:</b></td><td><?= date("d/m/Y", strtotime($modelobjeto->fchalta))  ?></td></tr>
				<tr><td><b>CUIT:</b></td><td><?= $responsablePrincipal[ 'cuit' ] ?></td></tr>
				<tr><td><b>Tipo:</b></td><td><?= utb::getCampo('comer_tipo',"cod='".$model->tipo."'") ?></td></tr>
				<tr><td><b>Domicilio Parcelario:</b></td><td><?= (new Domi())->cargarDomi('COM', $model->obj_id, 0)->domicilio; ?></td></tr>
				<tr><td><b>Domicilio Fiscal:</b></td><td><?= (new Domi())->cargarDomi('OBJ', $model->obj_id, 0)->domicilio; ?></td></tr>
				<tr><td><b>Actividad Principal:</b></td><td><?= $rubro->rubro_nom; ?></td></tr>
				<tr><td><b>Fecha Baja:</b></td><td><?= date_format(date_create($modelobjeto->fchbaja), 'd/m/Y') ?></td></tr>
			</table>
			<br><br>
			ha sido dado de baja de este Municipio
		<?php } ?>
		<?php if ($modelobjeto->tobj == 3){ ?>
			<table width='90%' class="tt14" align="center">
				<tr><td width='170px'><b>Nombre:</b></td><td><?= $modelobjeto->nombre ?></td></tr>
				<tr><td><b>Objeto:</b></td><td><?= $modelobjeto->obj_id ?></td></tr>
				<tr><td><b>Alta:</b></td><td><?= date("d/m/Y", strtotime($modelobjeto->fchalta))  ?></td></tr>
				<tr><td><b>Sexo:</b></td><td><?= utb::getCampo("persona_tsexo","cod='".$model->sexo."'") ?></td></tr>
				<tr><td><b>Documento:</b></td><td><?= utb::getCampo("persona_tdoc","cod=".$model->tdoc) . " - " . $model->ndoc ?></td></tr>
				<tr><td><b>CUIL:</b></td><td><?= $model->cuit ?></td></tr>
				<tr><td><b>Nacionalidad:</b></td><td><?= utb::getCampo("persona_tnac","cod=".$model->nacionalidad) ?></td></tr>
				<tr><td><b>Estado Civil:</b></td><td><?= utb::getCampo("persona_testcivil","cod=".$model->estcivil) ?></td></tr>
				<tr><td><b>Situación IVA:</b></td><td><?= utb::getCampo("comer_tiva","cod=".$model->iva) ?></td></tr>
				<tr><td><b>Domicilio Residencial:</b></td><td><?= $model->domi_res ?></td></tr>
				<tr><td><b>Domicilio Legal:</b></td><td><?= $model->domi_legal ?></td></tr>
				<tr><td><b>Fecha Baja:</b></td><td><?= date_format(date_create($modelobjeto->fchbaja), 'd/m/Y') ?></td></tr>
			</table>
			<br><br>
			ha sido dado de baja de este Municipio, en  concepto de Impuesto a los Ingresos Brutos. La presente no implica Libre de Deuda.
		<?php } ?>
		<?php if ($modelobjeto->tobj == 4){ ?>
			<table width='90%' class="tt14" align="center">
				<tr><td width='70px'><b>Nombre:</b></td><td><?= $modelobjeto->nombre ?></td></tr>
				<tr><td><b>Objeto:</b></td><td><?= $modelobjeto->obj_id ?></td></tr>
				<tr><td><b>Alta:</b></td><td><?= date("d/m/Y", strtotime($modelobjeto->fchalta))  ?></td></tr>
				<tr><td><b>Tipo:</b></td><td><?= utb::getCampo("cem_tipo","cod='".$model->tipo."'") ?></td></tr>
				<tr><td><b>Cuadro:</b></td><td><?= $model->cuadro_id ?></td></tr>
				<tr><td><b>Cuerpo:</b></td><td><?= $model->cuerpo_id ?></td></tr>
				<tr><td><b>Fila:</b></td><td><?= $model->fila ?></td></tr>
				<tr><td><b>Nume:</b></td><td><?= $model->nume ?></td></tr>
				<tr><td><b>Piso:</b></td><td><?= $model->piso ?></td></tr>
				<tr><td><b>Fecha Baja:</b></td><td><?= date_format(date_create($modelobjeto->fchbaja), 'd/m/Y') ?></td></tr>
			</table>
			<br><br>
			ha sido dado de baja de este Municipio.
		<?php } ?>
		<?php if ($modelobjeto->tobj == 5){ ?>
			<table width='90%' class="tt14" align="center">
				<tr><td width='150px'><b>Nombre:</b></td><td><?= $modelobjeto->nombre ?></td></tr>
				<tr><td><b>Objeto:</b></td><td><?= $modelobjeto->obj_id ?></td></tr>
				<tr><td><b>Alta:</b></td><td><?= date("d/m/Y", strtotime($modelobjeto->fchalta))  ?></td></tr>
				<tr><td><b>Dominio:</b></td><td><?= $model->dominio ?></td></tr>
				<tr><td><b>Año:</b></td><td><?= $model->anio ?></td></tr>
				<tr><td><b>Marca:</b></td><td><?= utb::getCampo("rodado_marca","cod=".$model->marca) ?></td></tr>
				<tr><td><b>Modelo:</b></td><td><?= $model->modelo_nom ?></td></tr>
				<tr><td><b>Motor:</b></td><td><?= utb::getCampo("rodado_marca","cod=".$model->marcamotor) . ' - ' . $model->nromotor ?></td></tr>
				<tr><td><b>Chasis:</b></td><td><?= utb::getCampo("rodado_marca","cod=".$model->marcachasis) . ' - ' . $model->nrochasis ?></td></tr>
				<tr><td><b>Domicilio Postal:</b></td><td><?= $modelobjeto->domi_postal ?></td></tr>
				<tr><td><b>Fecha Baja:</b></td><td><?= date_format(date_create($modelobjeto->fchbaja), 'd/m/Y') ?></td></tr>
			</table>
			<br><br>
			ha sido dado de baja de este Municipio, en concepto de Impuesto Automotor.
		<?php } ?>

		<br><br>
		Motivo de Baja: <?=$motivo?>
		<br>
		Observación:<?=$modelobjeto->obs?>

		<br><br><br><br><br>
		<div style='float:left;width:200px' align='center' class='desc'>
			<hr style="border:1px dashed  red;" />
			Firma Responsable
		</div>
<!--
		<div style='float:right;width:200px' align='center' class='desc'>
			<hr style="border:1px dashed  red;" />
			Firma Contribuyente
		</div>	
-->
	</div>
</div>
