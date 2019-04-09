<?php 
use app\utils\db\utb;
?>
<div class='body' >
	<p class='tt'> <?= $datos[0]['titulo'] ?> </p>
	<p class='cond14' style = "text-indent: 100px;text-align: justify;">
		<?= stripcslashes(nl2br(htmlentities(($datos[0]['detalle'])))) ?>
	</p>
</div>