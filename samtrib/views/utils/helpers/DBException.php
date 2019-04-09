<?php

namespace app\utils\helpers;

	abstract class DBException{
	
		 /**
		 * Obtiene el mensaje de error devuelto por la base de datos.
		 * 
		 * @param yii\db\Exception
		 * 
		 * @return String Mensaje de error devuelto por la base de datos.
 		*/
		public static function getMensaje($excepcion)
		{
			if(is_a($excepcion, PDOException::class)) return DBException::getMensajePDO($excepcion);
			
			$error = $excepcion->errorInfo;
			
			switch($error[0])
			{
				case '42501' : return "Permisos insuficientes para realizar la acción.";
								
				default : return $error[2];
			}
			
			return 'Error no detectado';
		}
		
		private static function getMensajePDO($excepcion){
			
			return $excepcion->getMessage();
		}
		
		public static function getCodigo($excepcion){
			
			if(is_a($excepcion, PDOException::class)) return $excepcion->getCode();
			
			return $excepcion->errorInfo[1];
		}
	}
 ?>