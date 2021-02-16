<?php
require_once PATH_JOB . "/Player.php";
require_once "SqliteConnexion.php";

class PlayerDao{

	private $connexion;

  /**
  * Method to construct the player dao
  * @return playerDao the player dao
  **/
	public function __construct(){
    $this->connexion = SqliteConnexion::getInstance()->getConnexion();
	}

	/**
	* Method to register a player in the data base if the login doesn't exist yet
	* @param login login of the player
	* @param password password of the player
	* @return state True if the player has been created
	**/
	public function register(String $login, String $password) : bool {
		try {
			if ($this->exists($login)) {
				return false;
			}
			$hash = password_hash($password, PASSWORD_DEFAULT);
			$statement = $this->connexion->prepare("INSERT INTO PLAYERS (`login`, `password`) VALUES (?, ?)");
			$statement->bindParam(1, $login, PDO::PARAM_STR);
			$statement->bindParam(2, $hash, PDO::PARAM_STR);
			$statement->execute();
			return true;
		} catch(PDOException $e) {
      throw new SQLException("Error when register".$e);
    }
	}

  /**
  * Method to check if the couple login, password is a player registered
  * @return state True is the couple corresponding to a player registered
  **/
  public function login(String $login, String $password) : bool {
    try {
      $statement = $this->connexion->prepare("SELECT password FROM PLAYERS WHERE login = ?");
      $statement->bindParam(1, $login, PDO::PARAM_STR);
      $statement->execute();
      $result = $statement->fetchColumn();
      return password_verify($login, $result);
    } catch(PDOException $e) {
      throw new SQLException("Error when login");
    }
  }

  /**
  * Method to get all users registered
  * @return players All player registered
  **/
	public function getAllUsers(): array {
		try {
			$statement = $this->connexion->query("SELECT * FROM PLAYERS");
			$players = $statement->fetchAll(PDO::FETCH_CLASS,'Player');
			return $players;
		} catch(PDOException $e) {
			throw new SQLException("Error when getting all users");
		}
	}

  /**
  * Method to check if the player with the login exists
  * @param login login to check
  * @return state True if the player with login exists
  **/
	public function exists(String $login): bool {
		try {
			$statement = $this->connexion->prepare("SELECT login FROM PLAYERS WHERE login = ?");
			$statement->bindParam(1, $login, PDO::PARAM_STR);
			$statement->execute();
			$result = $statement->fetch(PDO::FETCH_ASSOC);
      return $result['login'] != NULL;
		}
		catch(PDOException $e){
			throw new SQLException("Error when checking if the player exists");
		}
  }
}
?>
