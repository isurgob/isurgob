<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

$this->title = 'Acerca de ...';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
	<table border='0' width='100%' style='border-bottom:1px solid #ddd; margin-bottom:10px'>
    <tr>
    	<td><h1><?= Html::encode($this->title) ?></h1></td>
    </tr>
    <tr>
    	<td colspan='2'></td>
    </tr>
    </table>

	<img src="images/sam_logo.gif" width="160px" height="120px" style="float:left;padding-right: 25px;" />
	<div>
		<h1><i><?php echo 'Sistema para la Administraci&oacute;n Municipal'; ?></i></h1>	
		<h1><i><?php echo Yii::$app->name; ?></i></h1>	

		<p>Desarrollado por <a href="http://www.aari.com.ar/" target="_blank">AARI</a></p>

		<p>&nbsp;<br><br></p>
		<br>
	</div>

	<h1>Tecnolog&iacute;as Utilizadas</h1>
		
	<div align="center">
		<table width="95%" border="0" cellpadding="2" cellspacing="2">
		  <tbody>
			<tr height="10"><td>&nbsp;</td></tr>
			<tr>
			  <td colspan="2" align="left"><b>Las tecnolog&iacute;as utilizadas son todas herramientas de Software Libre</b></td>
			</tr>
			<tr height="10"><td>&nbsp;</td></tr>
			<tr>
			  <td colspan="2" align="left"><h3><b>Base de Datos</b></h3></td>
			</tr>
			<tr>

			  <td align="left"><b>PostGreSQL:</b> es un servidor de base de datos relacional libre, liberado bajo la licencia BSD esta es una licencia 
			  de software otorgada principalmente para los sistemas Berkeley Software Distribution. Pertenece al grupo de licencias de software Libre. 
			  Esta licencia tiene menos restricciones en comparaci&oacute;n con otras como la GPL estando muy cercana al dominio p&uacute;blico. 
			  La licencia BSD al contrario que la GPL permite el uso del c&oacute;digo fuente en software no libre.</td>

			  <td style="text-align: center;"><img alt="PostgreSQL" src="images/about/postgres.jpg"></td>

			</tr>
			<tr height="10"><td>&nbsp;</td></tr>
			<tr>
			  <td colspan="2" align="left"><h3><b>Administrador de Base de Datos</b></h3></td>
			</tr>
			<tr>

			  <td align="left"><b>PgAdmin:</b> es una herramienta de c&oacute;digo abierto para la administraci&oacute;n de bases de datos PostgreSQL y derivado<br>
			  Se dise&ntilde;a para responder a las necesidades de la mayor&iacute;a de los usuarios, desde escribir simples consultas SQL hasta desarrollar bases de datos complejas.<br>
				La interfase gr&aacute;fica soporta todas las caracter&iacute;siticas de PostgreSQL y hace simple la administraci&oacute;n. 
				Est&aacute; disponible en m&aacute;s de una docena de lenguajes y para varios sistemas operativos, incluyendo Microsoft Windows, 
				Linux, FreeBSD, Mac OSX y Solaris.			  
			  </td>

			  <td style="text-align: center;"><img alt="PostgreSQL" src="images/about/pgadmin.jpg"></td>

			</tr>
			<tr height="10"><td>&nbsp;</td></tr>
			<tr>
			  <td colspan="2" align="left"><h3><b>Servidor Web</b></h3></td>
			</tr>
			<tr>

			  <td align="left">El servidor HTTP <b>Apache</b> es un servidor web HTTP de c&oacute;digo abierto para plataformas Unix, Microsoft Windows, 
			  Macintosh y otras, que implementa el protocolo	 HTTP/1.1 y la noci&oacute;n de sitio virtual.<br> 
				Apache presenta entre otras caracter&iacute;sticas altamente configurables, bases de datos de autenticaci&oacute;n y negociado de 
				contenido, pero fue criticado por la falta de una interfaz.
			  </td>

			  <td style="text-align: center;"><img alt="Apache" src="images/about/apache.jpg"></td>

			</tr>
			<tr height="10"><td>&nbsp;</td></tr>
			<tr>
			  <td colspan="2" align="left"><h3><b>Lenguaje de Desarrollo</b></h3></td>
			</tr>
			<tr>

			  <td align="left"><b>PHP</b> es un lenguaje de programaci&oacute;n usado generalmente para la creaci&oacute;n de contenido para sitios web. 
			  El nombre es el acr&oacute;nimo recursivo de &quote;PHP: Hypertext Preprocessor&quote; (inicialmente PHP Tools, o, Personal Home Page Tools), 
			  y se trata de un lenguaje interpretado usado para la creaci&oacute;n de aplicaciones para servidores, o creaci&oacute;n de contenido 
			  din&aacute;mico para sitios web. &Uacute;ltimamente tambi&eacute;n para la creaci&oacute;n de otro tipo de programas incluyendo 
			  aplicaciones con interfaz gr&aacute;fica usando la librer&iacute;a GTK+. Es publicado bajo la PHP License, la Free Software Foundation 
			  considera esta licencia como software libre.</td>

			  <td style="text-align: center;"><img alt="php" src="images/about/php.jpg"></td>

			</tr>
			<tr height="10"><td>&nbsp;</td></tr>
			<tr>
			  <td colspan="2" align="left"><h3><b>Framework PHP</b></h3></td>
			</tr>
			<tr>

			  <td align="left"><b>Yii</b> es un framework PHP basado en componentes de alta performance para desarrollar aplicaciones Web 
			  de gran escala, orientado a objetos, software libre y de alto rendimiento. El mismo permite la m&aacute;xima reutilizaci&oacute;n 
			  en la programaci&oacute;n web y puede acelerar el proceso de desarrollo. Yii se pronuncia en espa&ntilde;ol como se escribe y 
			  es un acr&oacute;nimo para &quote;Yes It Is!&quote; (en espa&ntilde;ol: S&iacute; lo es!).</td>

			  <td style="text-align: center;"><img alt="Prado" src="images/about/yii.jpg"></td>

			</tr>
			<tr height="10"><td>&nbsp;</td></tr>
			<tr>
			  <td colspan="2" align="left"><h3><b>IDE</b></h3></td>
			</tr>
			<tr>

			  <td align="left"><b>Eclipse</b> constituye un Entorno de Desarrollo Integrado compuesto por un conjunto de herramientas de programaci&oacute;n de c&oacute;digo abierto.</td>

			  <td style="text-align: center;"><img alt="Eclipse" src="images/about/eclipse.jpg"></td>

			</tr>
			<tr height="10"><td>&nbsp;</td></tr>
			<tr>
			  <td colspan="2" align="left"><h3><b>Librer&iacute;as para el Dise&ntilde;o de Interfaz</b></h3></td>
			</tr>
			<tr>

			  <td align="left"><b>AJAX</b>, acr&oacute;nimo de Asynchronous JavaScript And XML (JavaScript as&iacute;ncrono y XML), es una t&eacute;cnica
			   de desarrollo web para crear aplicaciones interactivas o RIA (Rich Internet Applications). Estas aplicaciones se ejecutan en el cliente, 
			   es decir, en el navegador de los usuarios mientras se mantiene la comunicaci&oacute;n as&iacute;ncrona con el servidor en segundo plano. 
			   De esta forma es posible realizar cambios sobre las p&aacute;ginas sin necesidad de recargarlas, mejorando la interactividad, velocidad 
			   y usabilidad en las aplicaciones.</td>

			  <td style="text-align: center;"><img alt="AJAX" src="images/about/ajax.jpg"></td>

			</tr>
			<tr height="10"><td>&nbsp;</td></tr>

			<tr>

			  <td align="left"><b>Bootstrap</b> hace que el desarrollo de interfaces de usuario sea m&aacute;s r&aacute;pido y f&aacute;cil. 
			  Est&aacute; hecho para personas de todos los niveles de conocimiento, dispositivos de todo tipo y proyectos de todos los tama&ntilde;os.<br>
			  Twitter Bootstrap es un framework o conjunto de herramientas de software libre para dise&ntilde;o de sitios y aplicaciones web. 
			  Contiene plantillas de dise&ntilde;o con tipograf&iacute;a, formularios, botones, cuadros, men&uacute;s de navegaci&oacute;n y otros 
			  elementos de dise&ntilde;o basado en HTML y CSS, as&iacute; como, extensiones de JavaScript opcionales adicionales.
			  </td>

			  <td style="text-align: center;"><img alt="Bootstrap" src="images/about/bootstrap.jpg"></td>

			</tr>

			<tr height="10"><td>&nbsp;</td></tr>
			<tr>

			  <td align="left"><b>jQuery</b> es una biblioteca de JavaScript, creada inicialmente por John Resig, que permite simplificar la manera 
			  de interactuar con los documentos HTML, manipular el &aacute;rbol DOM, manejar eventos, desarrollar animaciones y agregar interacci&oacute;n 
			  con la t&eacute;cnica AJAX a p&aacutE;ginas web. Fue presentada el 14 de enero de 2006 en el BarCamp NYC. jQuery es la biblioteca de 
			  JavaScript m&aacute;s utilizada.
			  </td>

			  <td style="text-align: center;"><img alt="JQuery" src="images/about/jquery.jpg"></td>

			</tr>
			<tr height="10"><td>&nbsp;</td></tr>
		  </tbody>
		</table>
	</div>
			
</div>
