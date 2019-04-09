<?php
use app\utils\db\utb;

?>
<div class='body' >
	<p class='tt'> Certificado de Valuaci&oacute;n Fiscal </p>
	<div class='tt14' style='margin-top:10px;'>
		Certificamos que el Inmueble identificado como
		<br><br>

		<table width='90%' class="tt14" align="center">
			<tr><td width='170px'><b>Nombre:</b></td><td><?= $modelobjeto->nombre ?></td></tr>
			<tr><td><b>Objeto:</b></td><td><?= $modelobjeto->obj_id ?></td></tr>
			<tr><td><b>Alta:</b></td><td><?= date("d/m/Y", strtotime($modelobjeto->fchalta))  ?></td></tr>
			<tr><td><b>Partida Prov.:</b></td><td><?= $model->parp ?></td></tr>
			<tr><td><b>Plano:</b></td><td><?= $model->plano ?></td></tr>
			<tr><td><b>Partida Prov.Origen:</b></td><td><?= $model->parporigen ?></td></tr>
			<tr><td><b>Nomeclatura:</b></td><td><?= utb::getCampo('inm i',"obj_id='".$model->obj_id."'",'sam.uf_inm_armar_nc_completo(i.s1, i.s2, i.s3, i.manz, i.parc)') ?></td></tr>
			<tr><td><b>Nomec. Anterior:</b></td><td><?= $model->nc_ant ?></td></tr>
			<tr><td><b>Unidad Funcional:</b></td><td><?= $model->uf ?></td></tr>
			<tr><td><b>Pocentaje Unidad:</b></td><td><?= $model->porcuf ?></td></tr>
			<tr><td><b>Tipo:</b></td><td><?= utb::getCampo('inm_tipo',"cod='".$model->tinm."'") ?></td></tr>
			<tr><td><b>Titularidad:</b></td><td><?= utb::getCampo('inm_ttitularidad',"cod='".$model->titularidad."'") ?></td></tr>
			<tr><td><b>Barrio:</b></td><td><?= utb::getCampo('domi_barrio',"barr_id=".($model->barr_id == '' ? 0 : $model->barr_id)) ?></td></tr>
			<tr><td><b>Superficie Terreno:</b></td><td><?= $model->supt ?></td></tr>
			<tr><td><b>Superficie Mejoras:</b></td><td><?= $model->supm ?></td></tr>
			<tr><td><b>Valor Básico:</b></td><td><?= $model->valbas ?></td></tr>
		</table>

		<br><br>
		esta registrado en este Municipio con el siguiente,
		<table width='90%' class="tt14" align="center">
			<tr><td width='170px'><b>Aval&uacute;o Terreno: $ </b></td><td><?= $model->avalt ?></td></tr>
			<tr><td width='170px'><b>Aval&uacute;o Mejoras: $ </b></td><td><?= $model->avalm ?></td></tr>
			<tr><td width='170px'><b>Valor Fiscal: $ </b></td><td><?= number_format($model->avalt + $model->avalm,2) ?></td></tr>
		</table>

        <br><br>
        <p>A solicitud de quien lo requiera, se extiende el presente con sello y firma en la localidad de <?= str_replace('Municipalidad de','',Yii::$app->param->muni_name) ?>, provincia de
        <?= Yii::$app->db->createCommand('select p.nombre from sam.muni_datos m inner join domi_localidad l on m.loc_id=l.loc_id inner join domi_provincia p on l.prov_id=p.prov_id')->queryScalar(); ?>
        a los <?= date("j").' días del mes de '.utb::getNombreMes()[date("n")].' del año '.date("Y").'.' ?></p>
	</div>
</div>
