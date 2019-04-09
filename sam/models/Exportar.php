<?php

namespace app\models;

use Yii;
use app\utils\db\utb;
use yii\base\Model;
use yii\export2excel\Export2ExcelBehavior;
use app\controllers\SiteController;

class Exportar extends Model {

    /*
	$formato = indica el formato al cual se quiere exportar los datos.
	Puede ser los siguientes formatos:
		- 1 = Libre Office
		- 2 = Excel
		- 3 = Texto
	*/
	public $formato;
	
	/*
	$titulo = titulo a mostrar en el archivo generado.
	Se usa para todos los formatos.
	*/
	public $titulo;
	
	/*
	$detalle = descripción a mostrar en el archivo generado.
	Se usa para los formatos de Libre Office o Excel
	*/
	public $detalle;
	
	/*
	$delimitador = indica el delimitador del campo para el archivo a generar.
	Se usa en caso de formatos de texto.
	Puede ser:
		- 1 = tabulacion
		- 2 = línea vertical
		- 3 = coma
		- 4 = punto y coma
		- 5 = otro
	*/
	public $delimitador;
	
	/*
	$delimitadorotro = delimitador ingresador por el usuario.
	Se usa para generar los archivos de texto cuando se selecciona como delimitador 'otro'
	*/
	public $delimitadorotro;
	
	/*
	$separadorfila = indica el separador para las filas en el archivo a generar.
	Se usa en caso de formatos de texto.
	Puede ser:
		- 'LF' = salto de linea
		- 'CR' = retorno de carro
	*/
	public $separadorfila;
	
	/*
	$incluirtitulo = indica si se va a incluir el título en el archivo de texto.
	Se usa en caso de formatos de texto.
	*/
	public $incluirtitulo;
	
	/*
	$campos_desc = json detalle de los campos a filtrar
	*/
	public $campos_desc;
	
	/*
	$datos = json con los datos a mostrar
	*/
	public $datos;
	
	/*
	$action = string con el nombre de la funcion de donde se obtendran los datos
	*/
	public $action;
	
	/*
	$parametros = string json con los parametros que necesita la funcion para obtener los datos
	*/
	public $parametros;
	
	public function __construct(){

		// inicializo con formato 1 = Libre Office
		$this->formato = 1;
		
		// inicializo las variables para archivos de texto 
		$this->delimitador = 1;
		$this->separadorfila = 'LF';
		$this->incluirtitulo = 1;

    }
	
	public function rules(){

        return 	[
					[ ['formato', 'delimitador', 'incluirtitulo'], 'integer' ],
					
					[ ['titulo', 'detalle', 'separadorfila', 'delimitadorotro', 'action', 'parametros', 'campos_desc', 'datos'], 'string' ]

				];
    }
	
	public function Exportar(){
		
		$resultado = null;
		
		switch ( $this->formato )
		{
			case 1: // Libre Office

				$tabla = "<table>";
				$tabla .= "<tr>";
				
				// obtengo la descripcion de los campos a mostrar 
				foreach ( $this->campos_desc as $c ){
					$tabla .= "<td>" . $c . "</td>";
				}
				
				$tabla .= "</tr>";
				
				// obtengo los datos a mostrar
				// primero recorro fila por fila
				foreach( $this->datos as $fila ){
					$tabla .= "<tr>"; // abro fila
					
					// recorro los datos
					foreach ( $fila as $valor ){
						$tabla .= "<td>" . $valor . "</td>";
					}
					
					$tabla .= "</tr>"; // cierro fila 
				}
				
				$tabla .= "</table>";
				
				$resultado = $tabla;
				
				break;

			case 2: // Excel
				
				$exportar = [];
				$arrayAux = [];
				
				// recorro fila por fila los datos 
				foreach( $this->datos as $fila ){
					$posCol = 0; // indica posicion actual de las columnas de una fila
										
					// recorro los datos de cada fila 
					foreach ( $fila as $valor ){
						if ( isset($this->campos_desc[$posCol]) ){
							$arrayAux[ $this->campos_desc[$posCol] ] = $valor;
							$posCol += 1;
						}	
					}
					$exportar[] = $arrayAux;
				}
				
				$resultado = $exportar;

				break;

			case 3: // Texto

				// Delimitador de Campo
				switch ( $this->delimitador ){
					case 1: 
						$dc = chr(9); //Tab
						break;
					case 2: 
						$dc = '|'; //Línea Vertical
						break;
					case 3: 
						$dc = ','; //Coma
						break;
					case 4: 
						$dc = ';'; // Punto y Coma
						break;
					case 5: 
						$dc = $this->delimitadorotro; // Otro
						break;
				}
				
				// Separador de Fila
				$sf  = '';
				if ($this->separadorfila == 'LF') $sf = "\r\n";
				if ($this->separadorfila == 'CR') $sf = "\r\n";
				
				// si incluye el titulo lo inserto en la primera linea 
				$texto = $this->incluirtitulo ? $this->titulo . $sf : '';	
				// linea para ir llenando el archivo de texot
				
				// inserto descripción de los campos
				foreach ($this->campos_desc as $value) {
					$texto .= $value . $dc;
				}
				$texto .= $sf;
				
				// recorro fila por fila los datos 
				foreach( $this->datos as $fila ){
					$linea = '';											
					// recorro los datos de cada fila 
					foreach ( $fila as $valor ){
						$linea .= $valor . $dc; // genero la linea con los separadores
					}
					$texto .= $linea . $sf; // inserto la linea con el separador de fila
				}
				
				$resultado = $texto;
				
				break;
		}
		
		return $resultado;
		
	}

}
?>
