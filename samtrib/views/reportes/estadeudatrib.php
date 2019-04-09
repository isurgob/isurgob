<?php
ini_set('memory_limit', '-1'); 
set_time_limit(0) ;

$estad = Yii::$app->session['estad'];
$condicion = Yii::$app->session['cond'];
$objetos = Yii::$app->session['sub1'];

for ($i=0; $i<count($objetos); $i++){
	$sql = "Select * from sam.uf_inti_alta_deuda ('".$objetos[$i]["obj_id"]."',".$objetos[$i]["trib_id"].",2,'";
    $sql .= date('d/m/Y')."',".$objetos[$i]["desde"].",".$objetos[$i]["hasta"].",".$objetos[$i]["deudadesde"].",";
    $sql .= $objetos[$i]["deudahasta"].",0,999999,0,100)";
    
    $inti = Yii::$app->db->createCommand($sql)->queryAll();
    //echo count($inti);exit();
    if (count($inti) > 0){
        $sub1 [] = [
        	'obj_id' => $inti[0]['obj_id'],
        	'num' => $inti[0]['num'],
        	'obj_nom' => $inti[0]['nombre'],
        	'dompos_dir' => $inti[0]['dompos_dir'],
        	'periodos' => $inti[0]['periodos'],
        	'nominal' => $inti[0]['nominal'],
        	'accesor' => $inti[0]['accesor'],
        	'multa' => $inti[0]['multa'],
        	'total' => $inti[0]['total']
        ];
    }
}

$nominal = 0;
$accesor = 0;
$multa = 0;
$total = 0;

?>
<div class='body' >
	<p class='tt'><?= $estad->nombre ?></p>
	<p class='cond12'><?= $condicion ?></p>
	<table class='desc' cellpadding='3' cellspacing='0' style='margin:0px 10px 10px 10px'>
		<tr class='border_bottom'>
			<td><b>Objeto</b></td><td><b>Num</b></td><td><b>Nombre</b></td><td><b>Dirección</b></td><td><b>Cant</b></td>
			<td><b>Nominal</b></td><td><b>Interés</b></td><td><b>Multa</b></td><td><b>Total</b></td>
		</tr>
		<?php
		for ($i=0; $i<count($sub1); $i++)
		{
			if ( $sub1[$i]['nominal'] > 0 ) {
				$nominal += $sub1[$i]['nominal'];
				$accesor += $sub1[$i]['accesor'];
				$multa += $sub1[$i]['multa'];
				$total += $sub1[$i]['total'];
				
				echo "<tr><td>".$sub1[$i]['obj_id']."</td><td align='center'>".$sub1[$i]['num']."</td>" .
						"<td>".$sub1[$i]['obj_nom']."</td><td>".$sub1[$i]['dompos_dir']."</td><td>".$sub1[$i]['cant']."</td>".
						"<td align='right'>".number_format($sub1[$i]['nominal'],2)."</td>".
						"<td align='right'>".number_format($sub1[$i]['accesor'],2)."</td>".
						"<td align='right'>".number_format($sub1[$i]['multa'],2)."</td>".
						"<td align='right'>".number_format($sub1[$i]['total'],2)."</td></tr>";
			}			
		}
		?>
		<tr class='border_top'>
			<td></td><td></td><td></td><td></td><td></td>
			<td align='right'><b><?= $nominal ?> </b></td><td align='right'><b><?= $accesor ?></b></td>
			<td align='right'><b><?= $multa ?></b></td><td align='right'><b><?= $total ?></b></td>
		</tr>
	</table>
</div>	