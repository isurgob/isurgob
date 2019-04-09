<?php

use yii\helpers\Html;

?>
<style>
td { 
    padding: 5px;
}
</style>

<?php
function ArmarCalendario($mes)
{
	$arraymeses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octuble','Noviembre','Diciembre'];
	$calendario = "<table style='text-align:center'>";
	$calendario .= "<tr><td colspan='7' align='center' style='background:#f6a828;color:#ffffff'><b>".$arraymeses[$mes-1]."</b></td></tr>";
	$calendario .= "<tr style='font-size:10px'><td><b>L</b></td><td><b>M</b></td><td><b>M</b></td><td><b>J</b></td><td><b>V</b></td><td><b>S</b></td><td><b>D</b></td></tr>";
	$i = 1;
	for($d=1; $d<=cal_days_in_month(CAL_GREGORIAN, $mes, date('Y')); $d++)
	{
		$fecha = $mes.'/'.$d.'/'.date('Y'); // Utilizo formato m/d/a para poder obtener el nro del mes, sino lo invierte con el dia
		$fechaid = date('d',strtotime($fecha)).date('m',strtotime($fecha)); // formate la fecha para ponerla como id 
		if ($i==1) echo "<tr>";
		if (date('N',strtotime($fecha))==$i){
			$calendario .= "<td style='font-size:10px' id='".$fechaid."' onclick='MostrarFeriado(this.id,1)'>".$d."</td>";
		} else {
			$calendario .= "<td></td>";
			$d -= 1;
		}
		
		if ($i==7) {
			$calendario .= "</tr>";
			$i = 1;
		}else {
			$i += 1;
		}
	}
		
	$calendario .= "</table>";
	return $calendario;
}
?>

<div id='DivCalen'>
	<div class="form" style='padding:5px;float:left;height:200px;margin-bottom:5px'>
		<?= ArmarCalendario(1) ?>
	</div>
	<div class="form" style='padding:5px;float:left;margin-left:15px;height:200px;margin-bottom:5px'>
		<?= ArmarCalendario(2) ?>
	</div>
	<div class="form" style='padding:5px;float:left;margin-left:15px;height:200px;margin-bottom:5px'>
		<?= ArmarCalendario(3) ?>
	</div>
	<div class="form" style='padding:5px;float:left;margin-left:15px;height:200px;margin-bottom:5px'>
		<?= ArmarCalendario(4) ?>
	</div>
	<div class="form" style='padding:5px;float:left;height:200px;margin-bottom:5px'>
		<?= ArmarCalendario(5) ?>
	</div>
	<div class="form" style='padding:5px;float:left;margin-left:15px;height:200px;margin-bottom:5px'>
		<?= ArmarCalendario(6) ?>
	</div>
	<div class="form" style='padding:5px;float:left;margin-left:15px;height:200px;margin-bottom:5px'>
		<?= ArmarCalendario(7) ?>
	</div>
	<div class="form" style='padding:5px;float:left;margin-left:15px;height:200px;margin-bottom:5px'>
		<?= ArmarCalendario(8) ?>
	</div>
	<div class="form" style='padding:5px;float:left;height:200px;margin-bottom:5px'>
		<?= ArmarCalendario(9) ?>
	</div>
	<div class="form" style='padding:5px;float:left;margin-left:15px;height:200px;margin-bottom:5px'>
		<?= ArmarCalendario(10) ?>
	</div>
	<div class="form" style='padding:5px;float:left;margin-left:15px;height:200px;margin-bottom:5px'>
		<?= ArmarCalendario(11) ?>
	</div>
	<div class="form" style='padding:5px;float:left;margin-left:15px;height:200px;margin-bottom:5px'>
		<?= ArmarCalendario(12) ?>
	</div>
</div>
<?php
foreach($feriados as $item)
{
	$id = date('d',strtotime($item['fecha'])).date('m',strtotime($item['fecha']));
	echo "<script>$('#".$id."').css('background','#d7d7d7');</script>";
	echo "<script>$('#".$id."').css('color','#ffffff');</script>";
	echo "<script>$('#".$id."').css('cursor','pointer');</script>";
	echo "<script>$('#".$id."').attr('title','".$item['detalle']."');</script>";
}
?>
