<?php
use app\utils\db\utb;
?>
<div class='body' >
	<div class='divredon' style='margin:10px auto; padding:5px; width:60%'>
		<p class='tt14' align="center"> <b><u>Comprobante Alta Usuario Web</u> </b> </p>
        <table class='cond' width='100%' cellspacing='4'>
			<tr><td width='50px'><b>Objeto:</b></td><td colspan="3"><?= $datos->obj_id ?></td></tr>
			<tr><td><b>Nombre:</b></td><td colspan="3"><?= utb::getNombObj($datos->obj_id,false) ?></td></tr>
			<tr><td><b>Usuario:</b></td><td colspan="3"><?= $datos->nombre ?></td></tr>
            <tr><td><b>Clave:</b></td><td colspan="3" class='tt14'><b><?= Yii::$app->session->getFlash('clave') ?></b></td></tr>
			<tr><td><b>Mail:</b></td><td colspan="3"><?= $datos->mail ?></td></tr>
		</table>
        <hr />
        <font class="cond"><u><b>Permisos Asignados</b></u></font>
        <ul class="cond">
			<?php
                if ($datos->acc_contrib == 'S') echo "<li>Consulta Contribuyente</li>";
                if ($datos->acc_dj == 'S') echo "<li>Administración de DDJJ</li>";
                if ($datos->acc_proveedor == 'S') echo "<li>Consulta Provedor</li>";
                if ($datos->acc_agrete == 'S') echo "<li>Formulario Agente de Retención</li>";
				if ($datos->acc_escribano == 'S') echo "<li>Administración Escribano</li>";
            ?>
        </ul>
		<?php if (count($texto) > 0){ ?>
			<hr/>
			<p class="cond"><u><b><?= $texto[0]['titulo'] ?></b></u></p>
			<font class="cond"><?= $texto[0]['detalle'] ?></font>
		<?php	} ?>
		<br><br>
		<p class='cond'><b>Sitio Usuario Web: <u><?= utb::samMuni()['url'] ?>/samservweb/</u></b></p> 
	</div>
</div>    