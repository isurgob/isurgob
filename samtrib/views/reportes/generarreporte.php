<?php
 ini_set('memory_limit', '-1'); 
 set_time_limit(0) ;
 
if ( count($datos) == 0 )
	echo "No se encontraron resultados";
else {	
 
	$monto = 0;
	$monto2 = 0;
	
	// obtengo las claves de los campos para luego colocar las descripciones de los reportes
	$descripcion = array_keys( $datos[0] );
			
	// filtro solo los campos a mostrar
	$desc = array_filter($desc, function ($var) use ($descripcion) {
		return ( in_array($var['id'], $descripcion) );
	});
	
	// actualizo el orden de los campos a mostrar
	foreach ( $desc as $k => $d ){ // recorro los campos a mostrar 
		foreach ( $descripcion as $kd => $vd ){ // recorro los campos claves a mostrar 
			if ( $d['id'] == $vd ) // cuando encuentro actualizo el orden 
				$desc[$k]['orden'] = $kd;
		}
	}
	
	// ordeno el array por el campo orden 
	usort($desc, function($a, $b) {
	  return $a['orden'] - $b['orden'];
	});
			
	// muestro los datos
	
	echo "<p class='tt'> Listado Movimiento Bancario </p>";
	
	echo "<p class='cond'> <b>Organismo:</b> " . Yii::$app->param->muni_name . " - <b>CUIT:</b> " . Yii::$app->param->muni_cuit . " </p>";
	
	echo "<table width='100%' class='cond' cellspacing='4'>";
		echo "<tr class='border_bottom'>";
			foreach ( $desc as $d ){
				echo "<td><b>" . $d['nombre'] . "</b></td>";
			}
		echo "</tr>";
		foreach ( $datos as $d ){
			$monto += isset($d['monto']) ? $d['monto'] : 0;
			$monto2 += isset($d['monto']) ? $d['monto2'] : 0;
			
			echo "<tr>";
				foreach ( $d as $v ){
					echo "<td>" . $v . "</td>";
				}	
			echo "</tr>";	
		}
	echo "</table>";
	
	echo "<hr>";
	if ( $monto != 0 ) echo "<p class='cond'><b>Total Monto:</b> $monto </p>";
	if ( $monto2 != 0 ) echo "<p class='cond'><b>Total Monto2:</b> $monto2 </p>";
	echo "<p class='cond'><b>Cantidad de Registro:</b> " . count($datos) . " </p>";
}
?>
 	
