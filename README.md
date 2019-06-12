## ISURGOB

### Sistema Integrado de Gobierno
---
ISURGOB (Sistema Integrado de Administración Municipal), comprende la sistematización de un conjunto de procesos Administrativos, Tributarios y Financieros.

Esto permite:
- Mejor eficiencia en la obtención y aplicación de los Recursos Públicos.

- La generación de información oportuna y confiable, indispensable para la toma de decisiones, sobre la situación de cada objeto imponible y contribuyente del Municipio y demás relaciones funcionales de la operatoria.

### Subsistemas
---
Está integrado por los siguientes SubSistemas:
- ISURGOB Tributario
- ISURGOB Seguridad

El Subsistema de Seguridad es el encargado de gestionar los usuarios, perfiles y permisos de acceso a los distintos Subsistemas. Además permite manejar los módulos de cada Subsistema.
También dispone de algunas auditorías, en especial en lo que se refiere a control de acceso, accesos fallidos, blanqueos de clave, control de accesos múltiples, etc.
Físicamente de ubica en una carpeta distinta del Subsistema Tributario, aunque comparte algunas librerías comunes de todos los Subsistemas.
El Subsistema Tributario posee todos los módulos detallados de la Administración Tributaria. De acuerdo a los permisos del usuario definidos en el Subsistema de Seguridad se habilitan las opciones disponibles.

 	
### Servicios
---
Que es lo que hacemos y lo que brindamos
- Múltiples canales de acceso
- Apoyo a la Infraestructura necesaria
- Mejora en la recaudación
- Sistema intuitivo y dinámico
- Ajustes totalmente parametrizables
- Entrenamiento y capacitación.

### Guía de instalación
---
1. Prerequisitos
-   Sistema Operativo de Servidor: preferentemente Linux Server kernel versión 4.4 o superior, distribuciones recomendadas Debian/Ubuntu
-	Base de Datos: PostgreSQL 9.4 o superior
-	Servidor Web: Apache 2.4 o superior
-	Lenguaje: PHP 7.0
-   Librerías PHP adicionales: 
    -   php-mbstring
	-   php-xml
	-   php-mcrypt
	-   php-gd
	-   php-zip
	-   pdo-pgsql
-   Ejemplo: $ sudo -E apt-get -yq --no-install-suggests --no-install-recommends install php7.0-xml php7.0-mbstring php7.0-mcrypt php7.0-gd php7.0-zip php7.0-pgsql

2. Instalación de la Base de Datos
-   Soporte para PostgreSQL.

Para instalar las bases de datos es necesario cargar los scripts en las herramientas específicas de las bases de datos como psql o PgAdmin

 -  Instalación en PostgreSQL
 -  Abrir los script ubicados en la carpeta "db".
 -  Ejecutar mediante psql. El fichero Readme.txt contiene las instrucciones específicas.

3. Descarga e Instalación del Código
-   Descarga de código desde el Repositorio de github
    -   $ wget https://github.com/isurgob/isurgob/archive/master.zip
-   Descomprimir el código del paso anterior en el directorio de publicación de Apache
    -   $ cd /var/www/html o similar según la distriución a utilizar.
	-   $ unzip master.zip.

4. Puesta en Marcha
Una vez instalado, tipear en su navegador Web http://ip_dns/sam.
Primeros Pasos en la imagen: (https://github.com/isurgob/isurgob/tree/master/docs/ISURGOB-Instala.gif)

5. Configuración Inicial
En forma previa a la utilización de los módulos del Sistema, es necesario precargar los datos auxiliares y de configuración en función de las normativas propias del organismo.
Cada municipio tendrá su propia reglamentación en materia de Tributos, Tasas, Contribuciones, Resoluciones y demás.
Asimismo, las tipologías y categorizaciones respecto de los Objetos Imponibles y otros módulos son propias de cada organismo en particular y es importante su ingreso desde el área de configuraciones, cito en el encabezado de la página.
Accesos desde la barra superior del Sistema: (https://github.com/isurgob/isurgob/tree/master/docs/sam-config.jpg)

6. Vinculación de ISURGOB Tributario con el GIS

-   Web Service: La vinculación con otros sistemas se podrá llevar a cabo por medio de WebService.
-   Nomenclatura: La clave unívoca de todo Registro Gráfico se centra en la nomenclatura parcelaria.
-   Solicitud de Información: A partir de la identificación de una parcela en el GIS, se podrá invocar una función definida en el WebService de ISURGob Tributario para recuperar información alfanumérica a modo de consulta.
-   Desde el Formulario de consulta de inmueble, se dispondrá de link que permite el acceso al GIS. El acceso se realiza mediante URL, la cual se configura dentro del Módulo de Configuración. En la URL se envía como parámetro la Nomenclatura del inmueble a localizar.
-   Los métodos que se proveen son los siguientes:
    a)	Alta, Baja y modificación unitaria de inmuebles:
    b)	Actualización de Valuaciones: Permitirá actualizar la información asociada a las valuaciones de inmuebles y mejoras, incluyendo todos los elementos necesarios, tales como: superficie, zona, coeficiente, valor básico, categoría, etc. Este método será invocado por el Sistema de Catastro, ante un proceso de revalúo, ya sea parcial o total.
    c)	Semáforo de deuda: Consistirá en un semáforo que indicará si se pueden realizar gestiones sobre un inmueble en el Sistema de Catastro, de acuerdo al estado de deuda del mismo, teniendo en cuenta los parámetros para determinar la misma. El Sistema Comarcal incluirá las llamadas a este “semáforo” cuando se inicien trámites que cambien el estado del inmueble (Planos de obra, declaraciones de mejoras, etc.)
-   Funciones y Aspectos Técnicos: (https://github.com/isurgob/isurgob/tree/master/docs/InterfacesGIS.pdf)
   
### Arquitectura
---
El modelo se enmarca en por lo menos dos principios de la gestión pública de la calidad: principio de continuidad en la prestación de servicios, que propone que los servicios públicos se prestarán de manera regular e ininterrumpida, previendo las medidas necesarias para evitar o minimizar los perjuicios que pudieran ocasionarle al ciudadano en las posibles suspensiones del servicio. Y el principio de evaluación permanente y mejora continua que propone que una gestión pública de calidad es aquella que contempla la evaluación permanente, interna y externa, orientada a la identificación de oportunidades para la mejora continua de los procesos, servicios y prestaciones públicas centrados en el servicio al ciudadano y para resultados, proporcionando insumos para la adecuada rendición de cuentas.
- Patrón de Diseño MVC
- Entorno Visual
- Usabilidad y Accesibilidad
- Ayuda en línea
- Interoperabilidad
- Escalabilidad y extensibilidad
- Alto grado de Parametrización

### Tecnologías
---
Para obtener las ventajas competitivas de la solución multipropósito deseada, es básico contar con las herramientas de tecnología necesarias.
-	Arquitectura Web Enabled
-	Base de Datos: PostgreSQL como servidor de Base de Datos Relacional
-	Servidor Web: Apache 2.4
-	Librerías para interfase: BootStrap
-	Lenguaje de Desarrollo: PHP 7.0
-	Framework Yii 2.0
Las PC clientes, deberán disponer de un navegador Web de última generación.


### Autor/es
---
- Gabriel Martinez (gabrielmart@gmail.com)
- Sandra Martinez (sandracmart@gmail.com)
- ISUR.

### Información adicional
---
Se deberá contar con un Servidor de Base de Datos y Aplicaciones, preferentemente en Linux.
Se podrá migrar la información existente actualmente.

### Licencia 
---
[LICENCIA](https://github.com/isurgob/isurgob/blob/master/LICENSE.md)


## Limitación de responsabilidades

ISUR, los autores mencionados, y el BID no serán responsables, bajo circunstancia alguna, de daño ni indemnización, moral o patrimonial; directo o indirecto; accesorio o especial; o por vía de consecuencia, previsto o imprevisto, que pudiese surgir:

i. Bajo cualquier teoría de responsabilidad, ya sea por contrato, infracción de derechos de propiedad intelectual, negligencia o bajo cualquier otra teoría; y/o

ii. A raíz del uso de la Herramienta Digital, incluyendo, pero sin limitación de potenciales defectos en la Herramienta Digital, o la pérdida o inexactitud de los datos de cualquier tipo. Lo anterior incluye los gastos o daños asociados a fallas de comunicación y/o fallas de funcionamiento de computadoras, vinculados con la utilización de la Herramienta Digital.
