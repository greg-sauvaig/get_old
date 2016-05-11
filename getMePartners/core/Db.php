<?php

abstract class Db{
	private static $_dbInstance = NULL;

	public static function dbConnect()
	{
		if (self::$_dbInstance == NULL)
		{
			try{
				self::$_dbInstance = new PDO("mysql:host=".HOST.";dbname=".DB.";charset=utf8",DBUSER,DBPASS);
			}catch (Exception $e){
				echo "Erreur : ", $e->getMessage(), "\n";
				return;
			}
		}
		return (self::$_dbInstance);
	}
}

?>