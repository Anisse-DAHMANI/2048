<?php
require_once PATH_JOB . "/Game.php";
require_once PATH_JOB . "/Grid.php";
require_once "SqliteConnexion.php";

class GameDao{

	private $connexion;

  /**
  * Method to construct the game dao
  * @return gameDao the game dao
  **/
	public function __construct(){
		$this->connexion=SqliteConnexion::getInstance()->getConnexion();
	}

	/**
	* Method to create a game and to return it.
	* @param login login of the player who create the game.
	* @return game the empty created game.
	**/
	public function createGame(String $login) : Game {
		try {
			$state = GAME::INGAME;
			$score = 0;
			$grid = new Grid();
			$grid = $grid->export();
			$statement = $this->connexion->prepare("INSERT INTO GAMES (`login`, `state`, `score`, `grid`, `scoreLastMove`, `gridLastMove`) VALUES (?, ?, ?, ?, ?, ?)");
			$statement->bindParam(1, $login, PDO::PARAM_STR);
			$statement->bindParam(2, $state, PDO::PARAM_STR);
			$statement->bindParam(3, $score, PDO::PARAM_STR);
			$statement->bindParam(4, $grid, PDO::PARAM_STR);
			$statement->bindParam(5, $score, PDO::PARAM_STR);
			$statement->bindParam(6, $grid, PDO::PARAM_STR);
			$statement->execute();
			$statement = $this->connexion->prepare("SELECT * FROM GAMES WHERE login = ? ORDER BY id DESC LIMIT 1");
			$statement->bindParam(1, $login, PDO::PARAM_STR);
			$statement->execute();
			return $statement->fetchObject("Game");
		} catch(PDOException $e) {
			throw new SQLException("Error on creating a game");
		}
	}

	/**
	* Method to get the game by it's gameId.
	* @param gameId the id of the game wanted.
	* @return game the game if it present in the database.
	**/
	public function getGame(String $gameId) : Game {
		try {
			$statement = $this->connexion->prepare("SELECT * FROM GAMES WHERE id = ?");
			$statement->bindParam(1, $gameId, PDO::PARAM_STR);
			$statement->execute();
			return $statement->fetchAll(PDO::FETCH_CLASS, "Game")[0];
		} catch(PDOException $e) {
			throw new SQLException("Error on getting the game");
		}
	}

	/**
	* Method to check if the game is owned by the player.
	* @param gameId the id of the game
	* @param login the owner of the game
	* @return isOwner True if the game exists and owned by the login.
	**/
	public function isOwner(int $gameId, String $login) : bool {
		$statement = $this->connexion->prepare("SELECT * FROM GAMES WHERE id = ? AND login = ?");
		$statement->bindParam(1, $gameId, PDO::PARAM_STR);
		$statement->bindParam(2, $login, PDO::PARAM_STR);
		$statement->execute();
		$game = $statement->fetchAll(PDO::FETCH_CLASS, "Game");
		if (empty($game)) {
			return false;
		}
		return true;
  }

	/**
	* Method to get the top 5 games in the data base
	* @return games top 5 games
	**/
	public function getRankingGames(): array {
		try {
			$statement = $this->connexion->prepare("SELECT * FROM GAMES ORDER BY score DESC LIMIT 5");
			$statement->execute();
			$games = $statement->fetchAll(PDO::FETCH_CLASS, "Game");
			foreach ($games as $game) {
				$game->updateScore();
			}
			return $games;
		} catch(PDOException $e) {
			throw new SQLException("Error on getting ranking games");
		}
	}

	/**
	* Method to get the best score of the player
	* @param login the player's login
	* @return score Player's best score if he has already played else 0.
	**/
	public function getBestScore(String $login) : int {
		try {
			$statement = $this->connexion->prepare("SELECT score FROM GAMES WHERE login = ? ORDER BY score DESC LIMIT 1");
			$statement->bindParam(1, $login, PDO::PARAM_STR);
			$statement->execute();
			$bestScore = $statement->fetchColumn();
			if (!$bestScore) {
				return 0;
			}
			return $bestScore;
		} catch(PDOException $e) {
			throw new SQLException("Error on getting best score");
		}
	}

	/**
	* Method to get all games from the player
	* @param login login of the player
	* @return games games of the player
	**/
	public function getHistoryGames(String $login): array {
		try {
			$statement = $this->connexion->prepare("SELECT * FROM GAMES WHERE login = ? ORDER BY id");
			$statement->bindParam(1, $login, PDO::PARAM_STR);
			$statement->execute();
			$games = $statement->fetchAll(PDO::FETCH_CLASS, "Game");
			foreach ($games as $game) {
				$game->updateScore();
			}
			return $games;
		}
		catch(PDOException $e){
			throw new SQLException("Error on getting game history");
		}
  }

	/**
	* Method to update the game in the database.
	* @param game the game to update.
	**/
	public function updateGame(Game $game) : void {
		try {
			$statement = $this->connexion->prepare("UPDATE GAMES SET state = ?, score = ?, grid = ?, scoreLastMove = ?, gridLastMove = ? WHERE id = ?");
			$state = $game->getState();
			$score = $game->getScore();
			$grid = $game->getGrid()->export();
			$scoreLastMove = $game->getScoreLastMove();
			$gridLastMove = $game->getGridLastMove()->export();
			$id = $game->getId();
			$statement->bindParam(1, $state, PDO::PARAM_STR);
			$statement->bindParam(2, $score, PDO::PARAM_STR);
			$statement->bindParam(3, $grid, PDO::PARAM_STR);
			$statement->bindParam(4, $scoreLastMove, PDO::PARAM_STR);
			$statement->bindParam(5, $gridLastMove, PDO::PARAM_STR);
			$statement->bindParam(6, $id, PDO::PARAM_STR);
			$statement->execute();
		}
		catch(PDOException $e){
			throw new SQLException("Error on updating game");
		}
	}
}
?>
