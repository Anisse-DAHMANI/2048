<?php
require_once("BDException.php");
class SqliteConnexion{

	private static $instance;
	private $connexion;

  /**
  * Method to construct the SQL connexion
  * @return sqlConnexion the SQLConnxion created
  **/
	private function  __construct() {
		try {
			$this->connexion = new PDO("sqlite:" . DIR . "/db2048.db");
			$this->connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			throw new ConnexionException("Connexion error when creating SQL Connexion.");
		}
	}

  /**
  * Method to get the connexion SQL instance
  * @return instance the instance if exists else a new instance
  **/
	public static function getInstance(): SqliteConnexion {
		if(is_null(self::$instance)){
			self::$instance = new SqliteConnexion();
		}
		return self::$instance;
	}

  /**
  * Method to get the connexion
  * @return connexion the connexion
  **/
	public function getConnexion(): PDO {
		return $this->connexion;
	}

  /**
  * Method to close the connexion
  **/
	public function closeConnexion() {
		$this->connexion=null;
	}

}
?>
