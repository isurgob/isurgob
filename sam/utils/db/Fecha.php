<?php

namespace app\utils\db;

use Yii;
use DateTime;
use DateInterval;

	abstract class Fecha{

		private static function intercambiar($fecha) {
			$aux = explode('/', $fecha);

			return $aux[1] . '/' . $aux[2] . '/' . $aux[0];
		}

		/**
		 * Obtiene el dia, en formato dd, correspondiente a la fecha provista en $fecha dada en formato yyyy/mm/dd o yyyy-mm-dd
		 * @param string $fecha - Fecha de donde sacar el dia.
		 * @return string - Dia de la fecha provista
		 */
		public static function getDia($fecha) {

			$ret = -1;
			$aux = explode('/', $fecha);

			if(count($aux) === 3){
				return $aux[2];
			} else{
				$aux = explode('/', $fecha);

				if(count($aux) === 3){
					return $aux[2];
				} else $ret = -1;
			}

			return $ret;
		}

		/**
		 * Obtiene el mes, en formato mm, correspondiente a la fecha provista en $fecha en formato yyyy/mm/dd o yyyy-mm-dd
		 * @param string $fecha - Fecha de donde sacar el mes.
		 * @return string - Mes de la fecha provista
		 */
		public static function getMes($fecha) {

			$ret = -1;
			$aux = explode('/', $fecha);

			if(count($aux) === 3){
				return $aux[1];
			} else{
				$aux = explode('/', $fecha);

				if(count($aux) === 3){
					return $aux[1];
				} else $ret = -1;
			}

			return $ret;
		}

		/**
		 * Obtiene el año, en formato yyyy, correspondiente a la fecha provista en $fecha en formato yyyy/mm/dd o yyyy-mm-dd
		 * @param string $fecha - Fecha de donde sacar el año.
		 * @return string - Año de la fecha provista
		 */
		public static function getAnio($fecha) {

			$ret = -1;
			$aux = explode('/', $fecha);

			if(count($aux) === 3){
				return $aux[0];
			} else{
				$aux = explode('/', $fecha);

				if(count($aux) === 3){
					return $aux[0];
				} else $ret = -1;
			}

			return $ret;
		}

		/**
		 * Valida el periodo dado desde $desde hasta $hasta. Los parametros de fecha, dados como string, deben estar en el formato yyyy/mm/dd
		 *
		 * @param string $desde - Fecha incial del periodo.
		 * @param String $hasta - Fecha final del periodo.
		 * @param string $fechaMinima - Fecha minima que puede tomar el periodo.
		 * @param string $fechaMaxima - Fecha maxima que puede tomar el periodo.
		 * @param boolean $inclusives - Por defecto false. Si es true, $desde puede ser igual a $fechaMinima y $hasta puede ser igual a $fechaMaxima, no se permite si es false.
		 * @return string - Cadena vacia si el periodo es valido. En caso de que el periodo no sea valido se retorna el mensaje de error para mostrar al usuario
		 */
		public static function validarPeriodo($desde, $hasta, $fechaMinima, $fechaMaxima, $inclusives = false) {
			$formato = 'Y/m/d';

			$fchdesde= DateTime::createFromFormat($formato, $desde);
			$fchhasta = DateTime::createFromFormat($formato, $hasta);
			$fchminima = DateTime::createFromFormat($formato, $fechaMinima);
			$fchmaxima = DateTime::createFromFormat($formato, $fechaMaxima);

			if($fchdesde > $fchhasta)
				return "Rango de fechas incorrecto.";
			else
			if(!$inclusives) {
				if($fchdesde <= $fchmaxima && $fchdesde >= $fchminima)
					return "Fecha de inicio se superpone.";
				else if($fchhasta <= $fchmaxima && $fchhasta >= $fchminima)
					return "Fecha de finalización se superpone.";
				else if($fchdesde <= $fchminima && $fchhasta >= $fchmaxima)
					return "Se superponen las fechas";
			} else {
				if($fchdesde < $fchmaxima && $fchdesde > $fchminima)
					return "Fecha de inicio se superpone.";
				else if($fchhasta < $fchmaxima && $fchhasta > $fchminima)
					return "Fecha de finalización se superpone.";
				else if($fchdesde < $fchminima && $fchhasta > $fchmaxima)
					return "Se superponen las fechas";
			}

			return '';
		}

		/**
		 * Función que compara dos fechas y devuelve true si la primer fecha es menor o igual a la segunda
		 * El formato de las fechas debe ser yyyy/mm/dd
		 * @param string $fecha1 - Fecha menor para comparar
		 * @param string $fecha2 - Fecha mayor para comparar
		 * @param boolean $igual = true - Las fecha pueden ser iguales. false para que $fecha1 si o si sea menor a $fecha2
		 * @return boolean true Si la $fecha1 es menor/igual a la $fecha2
		 */
		public static function menor($fecha1, $fecha2, $igual = true) {
			$formato = 'Y/m/d';

			if(strpos('-', $fecha1) !== -1) $fecha1 = str_replace('-', '/', $fecha1);
			if(strpos('-', $fecha2) !== -1) $fecha2 = str_replace('-', '/', $fecha2);

			$fch1 = DateTime::createFromFormat($formato, $fecha1);
			$fch2 = DateTime::createFromFormat($formato, $fecha2);

			return $igual ? $fch1 < $fch2 || $fch1 == $fch2 : $fch1 < $fch2;
		}

		/**
		 * Determina si la fecha es posterior a la actual y no igual.
		 * El formtato de la fecha debe ser yyyy/mm/dd
		 *
		 * @param string $fecha - Fecha a comparar
		 * @param boolean $incluirPresente = false - Si la fecha presenta es valida como fecha futura
		 *
		 * @return boolean - true si la fecha es futura, false si es menor o igual a la actual
		 */
		public static function esFuturo($fecha, $incluirPresente = false){

			$hoy = date('Y/m/d');

			return Fecha::menor($hoy, $fecha, $incluirPresente);
		}

		/**
		 * Formatea la fecha provista para mostrarla al usuario de la forma dd/mm/yyyy.
		 * @param string $fecha - Fecha a formatear;
		 * @return string - Fecha formateada para mostrar al usuario.
		 */
		public static function bdToUsuario($fecha) {

			if ($fecha == null || empty($fecha))
				return $fecha;

			$aux = $fecha;

			if(strlen($fecha) > 10 ) $aux = substr($fecha, 0, 10);

			if(strpos($aux, '-') > -1)
				$aux = explode('-', $aux);
			else
				$aux = explode('/', $aux);

			return $aux[2] . '/' . $aux[1] . '/' . $aux[0];
		}

        public static function bdToDatePicker($fecha)
        {
        	return self::usuarioToDatePicker( self::bdToUsuario($fecha) );
        }

		/**
		 * Formatea la fecha provista para utilizarla en la base de datos de la forma yyyy/mm/dd.
		 * @param string $fecha - Fecha a formatear.
         * @param integer $parentesis - Si es 1, devuelve la fecha con "'". Además, si la fecha es '' devuelva el string null
		 * @return string - Fecha formateada para mostrar al usuario
		 */
		public static function usuarioToBD($fecha,$parentesis = 0) {

			if ($fecha == null || empty($fecha))
                if ($parentesis == 0)
                    return $fecha;
                else
                    return 'null';


			$aux = $fecha;

			if(strpos($fecha, '-') > -1)
				$aux = explode('-', $fecha);
			else
				$aux = explode('/', $fecha);

            if ($parentesis == 0)
			     return $aux[2] . '/' . $aux[1] . '/' . $aux[0];
            else
                return "'" . $aux[2] . "/" . $aux[1] . "/" . $aux[0] . "'";
		}



		/**
		 * Formatea la fecha provista para utilizarla con el DatePicker de la forma mm/dd/yyyy.
		 * @param string $fecha - Fecha a formatear.
		 * @return string - Fecha formateada para mostrar al usuario
		 */
		public static function usuarioToDatePicker($fecha) {

            if ($fecha == null || empty($fecha))
                return $fecha;

			if(strpos($fecha, '-') > -1)
				$aux = explode('-', $fecha);
			else
				$aux = explode('/', $fecha);

			return $aux[1] . '/' . $aux[0] . '/' . $aux[2];
		}


		/**
		 * Formatea la fecha provista por el DatePicker y la transforma al formato del usuario como dd/mm/yyyy.
		 * @param string $fecha - Fecha a formatear.
		 * @return string - Fecha formateada para mostrar al usuario
		 */
		public static function DatePickerToUsuario($fecha) {
			$aux = explode('/', $fecha);
			return $aux[1] . '/' . $aux[0] . '/' . $aux[2];
		}

		/**
		* Valida que el dato provisto sea una fecha de la forma dd/MM/yyyy o dd-MM-yyyy
		* @param string $fehca - Fecha a validar
		* @return boolean - true si la fecha es valida, false de lo contrario
		*/
		public static function isFecha($fecha){

			//fecha
			$fch = explode('/', $fecha);
			$valida = true;

			if(count($fch) == 3){
				$valida = checkDate($fch[1], $fch[0], $fch[2]);
			}else {
				$fch = explode('-', $fecha);

				if(count($fch) == 3){
					$valida = !checkDate($fch[1], $fch[0], $fch[2]);
				} else $valida = (count($fch) == 3);
			}

			return $valida;
		}

		/**
		 * Función que retorna el día actual en formato de usuario
		 */
		public static function getDiaActual()
		{
			return date('d') . '/' . date('m') . '/' . date('Y');
		}

		public static function getHoraMinuto($min)
		{
			$horas = floor($min/60);
			$minutos = $min-($horas*60);

			$horas = str_pad($horas,2,"0",STR_PAD_LEFT);
			$minutos = str_pad($minutos,2,"0",STR_PAD_LEFT);

			return $horas . ":" . $minutos;
		}

		/*
		 Obtiene la diferencia en días entre dos Fecha recibidas.
		*/
		public static function getDifDiaFecha( $fchDesde, $fchHasta ){

			$sql = "SELECT ('$fchHasta'::date - '$fchDesde'::date) + 1 ";

			$cant_dias = Yii::$app->db->createCommand($sql)->queryScalar();

			return $cant_dias;
		}

		public static function getNombreMes($mes)
		{
			if ($mes <= 0) $mes = 1;
			$arraymeses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octuble','Noviembre','Diciembre'];
			return $arraymeses[$mes];
		}

		/*
		Obtiene el año de una fecha desde la base.
		Para evitar que php invierta la fecha.
		Recibe como parametro la fecha
		*/
		public static function getAnioFechaDB( $fecha ){

			$sql = "select extract( year from '$fecha'::date )";
			return Yii::$app->db->createCommand($sql)->queryScalar();
		}

		public static function getFormatoFechaDB( $fecha ){

			$sql = "select to_char( '$fecha'::date, 'dd/mm/yyyy' )";
			return Yii::$app->db->createCommand($sql)->queryScalar();
		}
		
		/*
		Obtiene fecha actual desde la base
		*/
		public static function getFechaActualDB(){

			$sql = "select current_date";
			return Yii::$app->db->createCommand($sql)->queryScalar();
		}
		
		/*
		Verifica que fecha1 sea meno que fecha2.
		*/
		
		public static function verificarRangoFecha( $fecha1, $fecha2, $signo='<=' ){
		
			$sql = "select '$fecha1'::date $signo '$fecha2'::date ";
			return Yii::$app->db->createCommand($sql)->queryScalar();
		}
		
	}

?>
