<div class='body' >
	<div class='divredon' style='margin:10px auto; padding:5px; width:60%'>
		<p class='tt14' align="center"> <b>Cupón de Pago </b> </p>
        <table class='cond' width='100%' cellspacing='4'>
			<tr><td width='100px'><b>Objeto:</b></td><td colspan="3"><?= $datos[0]['obj_id'] ?></td></tr>
			<tr><td><b>Titular:</b></td><td colspan="3"><?= $datos[0]['num_nom'] ?></td></tr>
			<tr>
            	<td><b>CUIT/DNI:</b></td><td><?= $datos[0]['cuit'] ?></td>
                <?php if ($datos[0]['trib'] == 23) { ?>
                	<td width="30px"><b>IIBB:</b></td><td><?= $datos[0]['ib'] ?></td>
                <?php } ?>
            </tr>
			<tr><td><b>Tributo:</b></td><td colspan="3"><?= $datos[0]['trib_nom'] ?></td></tr>
			<tr><td><b>Vigencia hasta:</b></td><td colspan="2"><?= $datos[0]['obj_id'] != '' ? $fchvenc : '' ?></td></tr>
			<tr><td>&nbsp;</td></tr>
			<tr><td colspan="4" align="center"><?= $datos[0]['codcuponpago'] ?></td></tr>
			<tr><td colspan="4" align="center"><barcode code="<?=$datos[0]['codcuponpago']?>" type="C128C" size="0.9"  /> </td></tr>
		</table>
	</div>
</div>
