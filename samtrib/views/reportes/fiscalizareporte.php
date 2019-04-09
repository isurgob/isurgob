<?php 
use app\utils\db\utb;

for($i=0; $i<count($sub);$i++) { 
?>
	<div class='body' >
		<!-- Encabezado -->
		<?php include('fiscalizareporte_enc.php'); ?>
		<!-- Fin Encabezado -->
		<?php 
			if ($sub[$i]['cod'] == 5) echo "<p class='tt18' align='center'><u><b>Anexo 1A (Ventas)</b></u></p>";
			if ($sub[$i]['cod'] == 6) echo "<p class='tt18' align='center'><b>Anexo 1B (Compras)</b></p>"; 
			if ($sub[$i]['cod'] == 7) echo "<p class='tt18' align='center'><b>Anexo 2 (Contribuyentes de Convenio Multilateral - Coeficientes)</b></p>";
			if ($sub[$i]['cod'] == 8) echo "<p class='tt18' align='center'><b>Anexo 3 (Regímenes de regularización)</b></p>";
			if ($sub[$i]['cod'] == 9) echo "<p class='tt18' align='center'><b>Anexo 4 </b> (Cuestionario) </p>";
			if ($sub[$i]['cod'] == 10) echo "<p class='tt18' align='center'><b>Anexo 5 </b> (Contribuyentes de CM - Discriminación de ingresos y gastos) </p>"; 
			if ($sub[$i]['cod'] == 11) echo "<p class='tt18' align='center'><b>Anexo 6 </b> (Conciliación con Balances) </p>";
			if ($sub[$i]['cod'] == 12) echo "<p class='tt18' align='center'><b>Anexo 7 </b> Información Complementaria </p>";
		?>
		<p class='cond12' align='center'><b>
			<?php 
				if ($sub[$i]['cod'] == 9) 
					echo 'Información general';
				elseif ($sub[$i]['cod'] != 12) 
					echo $sub[$i]['detalle'];  
			?>
		</b></p>
		<?php 
			if ($sub[$i]['cod'] == 1 or $sub[$i]['cod'] == 2 or $sub[$i]['cod'] == 3 or $sub[$i]['cod'] == 4){
				echo "<p class='cond12' align='center'>".$datos[0]['detalle']."</p>";
				
				// Acta de Constatacion o Acta de Inicio de Inspeccion
				if ($sub[$i]['cod'] == 1 or $sub[$i]['cod'] == 3){
					echo "<table class='cond' width='90%' cellspacing='10' align='center' style='margin-top:100px'>";
					echo "<tr><td class='border_top' width='40%' align='center'>Firma y sello del actuante</td><td width='10%'></td><td class='border_top' width='40%' align='center'>Firma y sello del contribuyente/responsable</td></tr>";
					echo "</table>";
				}
				
				// Intimacion de Pago
				if ($sub[$i]['cod'] == 2){
					echo "<table class='cond' width='100%' cellspacing='4' align='center' style='margin-top:100px'>";
					echo "<tr><td width='5%'></td><td width='45%'></td><td width='40%' class='border_top' align='center'>".utb::getCampo('intima_firma','firma_id='.$sub[$i]['firma_id'],'nombre').'<br>'.utb::getCampo('intima_firma','firma_id='.$sub[$i]['firma_id'],'cargo')."</td></tr>";
					echo "<tr><td colspan='2'>Contribuyente:</td><td></td></tr>";
					echo "<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Día y hora:</td><td class='border_bottom'></td><td></td></tr>";
					echo "<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Firma:</td><td class='border_bottom'></td><td></td></tr>";
					echo "<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Aclaración:</td><td class='border_bottom'></td><td></td></tr>";
					echo "<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DNI:</td><td class='border_bottom'></td><td></td></tr>";
					echo "<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Carácter:</td><td class='border_bottom'></td><td></td></tr>";
					echo "<tr><td height='50px'></td><td></td><td></td></tr>";
					echo "<tr><td></td><td></td><td align='center' class='border_top'>Notificador actuante</td></tr>";
					echo "</table>";
				}
				
				//Acta de Retiro de Información
				if ($sub[$i]['cod'] == 4){
					include('fiscalizareporte_retiro.php'); 			
				}
			} 
			
			if ($sub[$i]['cod'] == 5 or $sub[$i]['cod'] == 6 or $sub[$i]['cod'] == 7 or $sub[$i]['cod'] == 8 
				or $sub[$i]['cod'] == 10 or $sub[$i]['cod'] == 11){
				echo "<table class='cond' width='100%' cellspacing='0' cellpadding='5' align='center'>";
				//Anexo 1A - Anexo 1B
				if ($sub[$i]['cod'] == 5 or $sub[$i]['cod'] == 6)
					echo "<tr class='border'>" .
						"<td align='center' width='50px'><b>Mes</b></td>" .
						"<td align='center'><b>".(date('Y')-5)."</b></td>" .
						"<td align='center'><b>".(date('Y')-4)."</b></td>" .
						"<td align='center'><b>".(date('Y')-3)."</b></td>" .
						"<td align='center'><b>".(date('Y')-2)."</b></td>" .
						"<td align='center'><b>".(date('Y')-1)."</b></td>" .
						"<td align='center'><b>".date('Y')."</b></td>" .
					"</tr>";
				//Anexo 2	
				if ($sub[$i]['cod'] == 7){
					echo "<tr class='border'><td align='center' rowspan='2' width='50px'><b>Mes</b></td><td align='center' colspan='6'><b>Año:</b></td></tr>";
					echo "<tr class='border'>" .
						"<td align='center'><b>Total País</b></td>" .
						"<td align='center'><b>Coef. Prov</b>(2)</td>" .
						"<td align='center'><b>Base I. Prov</b>(3)</td>" .
						"<td align='center'><b>Base Imponible</b></td>" .
						"<td align='center'><b>Coef.</b>(4)</td>" .
						"<td align='center'><b>B.I. Resto Pcia.</b>(5)</td>" .
					"</tr>";
				}
				if ($sub[$i]['cod'] == 5 or $sub[$i]['cod'] == 6 or $sub[$i]['cod'] == 7){
					echo "<tr class='border'><td align='center'>Enero</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
					echo "<tr class='border'><td align='center'>Febrero</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
					echo "<tr class='border'><td align='center'>Marzo</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
					echo "<tr class='border'><td align='center'>Abril</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
					echo "<tr class='border'><td align='center'>Mayo</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
					echo "<tr class='border'><td align='center'>Junio</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
					echo "<tr class='border'><td align='center'>Julio</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
					echo "<tr class='border'><td align='center'>Agosto</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
					echo "<tr class='border'><td align='center'>Septiembre</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
					echo "<tr class='border'><td align='center'>Octubre</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
					echo "<tr class='border'><td align='center'>Noviembre</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
					echo "<tr class='border'><td align='center'>Diciembre</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
				}
				//Anexo 3	
				if ($sub[$i]['cod'] == 8){
					echo "<tr class='border'><td align='center'><b>Organismo</b></td><td align='center'><b>Norma</b></td>" .
							"<td align='center'><b>Concepto</b></td><td align='center'><b>Fecha</b></td>" .
							"<td align='center'><b>Monto Total</b></td><td align='center'><b>Períodos incluidos</b></td></tr>";
					for ($j=0; $j<16; $j++){
						echo "<tr class='border'><td height='30px'></td><td height='30px'></td><td height='30px'></td>" .
								"<td height='30px'></td><td height='30px'></td><td height='30px'></td></tr>";
					}
				}
				//Anexo 5	
				if ($sub[$i]['cod'] == 10){
					echo "<tr class='border'><td align='center'><b>Períoso</b></td><td align='center'><b>Gastos</b></td>" .
							"<td align='center'><b>Coeficiente</b></td><td align='center'><b>Ingresos</b></td>" .
							"<td align='center'><b>Coeficiente</b></td><td align='center'><b>Coeficiente unificado</b></td></tr>";
					for ($j=0; $j<6; $j++){
						echo "<tr class='border'><td height='30px'></td><td height='30px'></td><td height='30px'></td>" .
								"<td height='30px'></td><td height='30px'></td><td height='30px'></td></tr>";
					}
				}
				//Anexo 2	
				if ($sub[$i]['cod'] == 11){
					echo "<tr class='border'><td align='center' rowspan='2' width='200px'><b>Concepto</b></td>" .
							"<td align='center' colspan='6'><b>Importes por Totales por año</b></td></tr>";
					echo "<tr class='border'>" .
						"<td align='center'><b>".(date('Y')-5)."</b></td>" .
						"<td align='center'><b>".(date('Y')-4)."</b></td>" .
						"<td align='center'><b>".(date('Y')-3)."</b></td>" .
						"<td align='center'><b>".(date('Y')-2)."</b></td>" .
						"<td align='center'><b>".(date('Y')-1)."</b></td>" .
						"<td align='center'><b>".date('Y')."</b></td>" .
					"</tr>";
					echo "<tr class='border'><td class='desc'>Ingresos Actividad 1</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
					echo "<tr class='border'><td class='desc'>Ingresos Actividad 2</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
					echo "<tr class='border'><td class='desc'>Ingresos Actividad 3</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
					echo "<tr class='border'><td class='desc'>Total de Ingresos</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
					echo "<tr class='border'><td>( + - ) Otros ingresos no incluidos</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
					echo "<tr class='border'><td class='desc'>a)</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
					echo "<tr class='border'><td class='desc'>b)</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
					echo "<tr class='border'><td class='desc'>c)</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
					echo "<tr class='border'><td class='desc'>( + - ) Ajuste por inflación</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
					echo "<tr class='border'><td>( + - ) Resultados financieros</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
					echo "<tr class='border'><td class='desc'>a)</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
					echo "<tr class='border'><td class='desc'>b)</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
					echo "<tr class='border'><td class='desc'>c)</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
					echo "<tr class='border'><td class='desc'>( + - ) Ajuste por inflación</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
					echo "<tr class='border'><td class='desc'>Resultado financiero ajustado</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
					echo "<tr class='border'><td>( + - ) Otros conceptos</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
					echo "<tr class='border'><td class='desc'>a)</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
					echo "<tr class='border'><td class='desc'>b)</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
					echo "<tr class='border'><td class='desc'>c)</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
					echo "<tr class='border'><td><b>Total Ingresos según Balance</b></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
				}
				echo "</table>"; 			
				
				if ($sub[$i]['cod'] == 5 or $sub[$i]['cod'] == 6){
					echo "<p class='cond'>Fuente de la información:</p>";
					echo "<p><hr style='color: #000; margin:1px' /></p>";
					echo "<p><hr style='color: #000; margin:1px' /></p>";
				}
				if ($sub[$i]['cod'] == 7){
					echo "<p class='cond'><b>Notas:</b></p>";
					echo "<div class='cond' style='text-indent:25px;'>(1) Fecha de Inicio de la Actividad en la Provincia</div>";
					echo "<div class='cond' style='text-indent:25px;'>(2) Coeficiente unificado para la Provincia</div>";
					echo "<div class='cond' style='text-indent:25px;'>(3) Base Imponible para la Provincia</div>";
					echo "<div class='cond' style='text-indent:25px;'>(4) Coeficiente aplicado</div>";
					echo "<div class='cond' style='text-indent:25px;'>(5) Base imponible ingresada al resto de las municipalidades de la Provincia</div>";
					
				}
								
				if ($sub[$i]['cod'] == 6) echo "<p class='cond' style='text-align:justify;'>Declaro bajo juramento que los datos consignados en el presente Anexo 1 son fiel expresión de la verdad y el mismo se ha confeccionado sin omitir ni falsear datos.</p>";
				if ($sub[$i]['cod'] == 7) echo "<p class='cond' style='text-align:justify;'>Declaro bajo juramento que los datos consignados en el presente Anexo 2 son fiel expresión de la verdad y el mismo se ha confeccionado sin omitir ni falsear datos.</p>";
				if ($sub[$i]['cod'] == 8) echo "<p class='cond' style='text-align:justify;'>Declaro bajo juramento que los datos consignados en el presente Anexo 3 son fiel expresión de la verdad y el mismo se ha confeccionado sin omitir ni falsear datos.</p>";
				if ($sub[$i]['cod'] == 10) echo "<p class='cond' style='text-align:justify;'>Declaro bajo juramento que los datos consignados en el presente Anexo 5 son fiel expresión de la verdad y el mismo se ha confeccionado sin omitir ni falsear datos.</p>";
				if ($sub[$i]['cod'] == 11) echo "<p class='cond' style='text-align:justify;'>Declaro bajo juramento que los datos consignados en el presente Anexo 6 son fiel expresión de la verdad y el mismo se ha confeccionado sin omitir ni falsear datos.</p>";
				
				echo "<table class='cond' width='90%' cellspacing='10' align='center' style='margin-top:90px'>";
				echo "<tr><td class='border_top' width='40%' align='center'>Nombre y Cargo</td><td width='10%'></td><td class='border_top' width='40%' align='center'>Firma del Contribuyente/Responsable</td></tr>";
				echo "</table>";
			}
			
			//Anexo 4
			if ($sub[$i]['cod'] == 9) include('fiscalizareporte_anexo4.php'); 			
			
			//Anexo 7
			if ($sub[$i]['cod'] == 12) include('fiscalizareporte_anexo7.php');
			
		?>
	</div>
	<?php if ($i < count($sub)-1) { ?>
	<!-- Salto de Página --> <div style="PAGE-BREAK-AFTER: always"></div> 
	<?php } ?>
<?php } ?>