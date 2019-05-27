<?php
use yii\bootstrap\Collapse;

$base = Yii::$app->param->sis_url;
$sam = Yii::$app->param->urlsam;

$menu = Collapse::widget([
					'id' => 'navigation',
					'encodeLabels' => true,
					'options' => ['class' => 'list-group'],
				    'items' => [
				        [
				        	'id' => 'mnuPersona',
				            'label' => 'Persona',
				            'content' =>
				            		'<a class="list-group-item" href="'.$base.'/objeto/persona/view">Administraci&oacute;n</a></li>'.
				            		'<a class="list-group-item" href="'.$base.'/objeto/listadopersona/index">Listado</a></li>'.
				            		'<a class="list-group-item" href="'.$base.'/objeto/persona/reemplaza">Reemplaza</a></li>'.
									'<a class="list-group-item" href="'.$base.'/objeto/persona/consultaweb">Consultas Web</a></li>'.
									'<a class="list-group-item" href="'.$base.'/objeto/persona/ajusteweb">Ajustes Web</a></li>',
				            'contentOptions' => ['class' => 'submenu']
				        ],
				        [
				        	'id' => 'mnuInmueble',
				            'label' => 'Inmueble',
				            'content' =>
				            		'<a class="list-group-item" href="'.$base.'/objeto/inm/view">Administraci&oacute;n</a></li>'.
				            		'<a class="list-group-item" href="'.$base.'/objeto/listadoinm/index">Listado</a></li>'.
				            		'<a class="list-group-item" href="'.$base.'/site/auxedit&t=130">Calles</a></li>'.
				            		'<a class="list-group-item" href="'.$base.'/objeto/inm/coef">Coeficiente</a></li>'.
				            		'<a class="list-group-item" href="'.$base.'/objeto/inm/revaluo">Revaluar</a></li>'.
									'<a class="list-group-item" href="'.$base.'/objeto/escribano/index">Escribano</a></li>',
				            'contentOptions' => ['class' => 'submenu']
				        ],
				        [
				        	'id' => 'mnuComercio',
				            'label' => 'Act. Económica',
				            'content' =>
				            		'<a class="list-group-item" href="'.$base.'/objeto/comer/view">Administraci&oacute;n</a></li>'.
				            		'<a class="list-group-item" href="'.$base.'/objeto/listadocomer/index">Listado</a></li>'.
				            		'<a class="list-group-item" href="'.$base.'/ctacte/retencion/index">Agente Retención</a></li>'.
				            		'<a class="list-group-item" href="'.$base.'/ctacte/listadoretencion/index">Listado Retenciones</a></li>'.
									'<a class="list-group-item" href="'.$base.'/ctacte/retencion/importarfinanciero">Retenc. Financiero</a></li>'.
									'<a class="list-group-item" href="'.$base.'/ctacte/retencion/pendientes">Retenc. Pendientes</a></li>'.
									'<a class="list-group-item" href="'.$base.'/config/rubro/index">Rubros</a></li>',
				            'contentOptions' => ['class' => 'submenu']
				        ],
				        [
				        	'id' => 'mnuCementerio',
				            'label' => 'Cementerio',
				            'content' =>
				            		'<a class="list-group-item" href="'.$base.'/objeto/cem/view">Adm. Cementerio</a></li>'.
				            		'<a class="list-group-item" href="'.$base.'/objeto/cem/viewfall">Adm. Fallecidos</a></li>'.
				            		'<a class="list-group-item" href="'.$base.'/objeto/listadocem/index">Listado Cementerio</a></li>'.
				            		'<a class="list-group-item" href="'.$base.'/objeto/listadocemfall/index">Listado Fallecido</a></li>',
				            'contentOptions' => ['class' => 'submenu']
				        ],
				        [
				        	'id' => 'mnuRodado',
				            'label' => 'Rodado',
				            'content' =>
				            		'<a class="list-group-item" href="'.$base.'/objeto/rodado/view">Administraci&oacute;n</a></li>'.
				            		'<a class="list-group-item" href="'.$base.'/objeto/listadorodado/index">Listado</a></li>'.
									'<a class="list-group-item" href="'.$base.'/site/auxedit&t=49">Aforos</a></li>',
				            'contentOptions' => ['class' => 'submenu']
				        ],
				        [
				        	'id' => 'mnuTrib',
				            'label' => 'Tributos',
				            'content' =>
				            		'<a class="list-group-item" href="'.$base.'/ctacte/liquida/view">Eventuales</a></li>'.
				            		'<a class="list-group-item" href="'.$base.'/ctacte/ddjj/index">DDJJ</a></li>'.
									'<a class="list-group-item" href="'.$base.'/ctacte/listadotribacc/index">Asignaciones</a></li>'.
									'<a class="list-group-item" href="'.$base.'/ctacte/listadotribacc/index&tipo=excepcion">Excepciones</a></li>'.
									'<a class="list-group-item" href="'.$base.'/ctacte/listadotribacc/index&tipo=inscripcion">Inscrip.Tributos</a></li>'.
									'<a class="list-group-item" href="'.$base.'/ctacte/listadotribacc/index&tipo=condona">Condonación</a></li>'.
									'<a class="list-group-item" href="'.$base.'/ctacte/tribacc/prescrip">Prescripción</a></li>'.
									'<a class="list-group-item" href="'.$base.'/ctacte/tribacc/djfalt">DDJJ Faltantes</a></li>',
				            'contentOptions' => ['class' => 'submenu']
				        ],
				        [
				        	'id' => 'mnuOpera',
				            'label' => 'Operaciones',
				            'content' =>
				            		'<a class="list-group-item" href="'.$base.'/ctacte/ctacte/index">Cuenta Corriente</a></li>'.
				            		'<a class="list-group-item" href="'.$base.'/ctacte/listadolibredeuda/index">Libre Deuda</a></li>'.
				            		'<a class="list-group-item" href="'.$base.'/ctacte/convenio/plan">Convenios de Pago</a></li>'.
				            		'<a class="list-group-item" href="'.$base.'/ctacte/facilida/view">Facilidades de Pago</a></li>'.
				            		'<a class="list-group-item" href="'.$base.'/ctacte/pagocta/view">Pagos a Cuenta</a></li>'.
				            		'<a class="list-group-item" href="'.$base.'/ctacte/listadoajuste/index">Ajustes</a></li>'.
				            		'<a class="list-group-item" href="'.$base.'/ctacte/cestado/view">Cambio Estado</a></li>'.
				            		'<a class="list-group-item" href="'.$base.'/ctacte/saldoneg/view">Saldo Negativo</a></li>'.
									'<a class="list-group-item" href="'.$base.'/ctacte/comp/view">Compensa</a></li>'.
				            		'<a class="list-group-item" href="'.$base.'/ctacte/judi/view">Apremio</a></li>'.
				            		'<a class="list-group-item" href="'.$base.'/ctacte/fiscaliza/view">Fiscalización</a></li>',
				            'contentOptions' => ['class' => 'submenu']
				        ],
				        [
				        	'id' => 'mnuCaja',
				            'label' => 'Caja',
				            'content' =>
				            		'<a class="list-group-item" href="'.$base.'//caja/listadocobrocticket/index">Listado Cobros</a></li>'.
				            		'<a class="list-group-item" href="'.$base.'/caja/cajaticket/supervision">Supervisión</a></li>'.
				            		'<a class="list-group-item" href="'.$base.'/caja/cajacobro/view&i=1">Cobros</a></li>'.
				            		'<a class="list-group-item" href="'.$base.'/caja/cajaticket/pagoant">Reg. Pagos Ant.</a></li>'.
				            		'<a class="list-group-item" href="'.$base.'/caja/cajaticket/viewrecibomanual">Recibo Manual</a></li>'.
				            		'<a class="list-group-item" href="'.$base.'/caja/cajaticket/chequecartera">Cheque Cartera</a></li>'.
				            		'<a class="list-group-item" href="'.$base.'/caja/debito/view">Débito</a></li>'.
				            		'<a class="list-group-item" href="'.$base.'/caja/cajaticket/hbank">Home Banking</a></li>'.
				            		'<a class="list-group-item" href="'.$base.'/caja/cajaticket/opera">Cons.Operación</a></li>'.
				            		'<a class="list-group-item" href="'.$base.'/caja/cajaticket/ticket">Cons.Ticket</a></li>'.
									'<a class="list-group-item" href="'.$base.'/caja/cajaticket/prueba">Caja Prueba</a></li>',
				            'contentOptions' => ['class' => 'submenu']
				        ],
				    ]
				]);


return $menu;
?>
