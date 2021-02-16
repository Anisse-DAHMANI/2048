<?php
require_once PATH_MODEL . '/GameDao.php';
require_once PATH_JOB . '/Game.php';
require_once PATH_VUE . '/LobbyVue.php';

class LobbyController {

  private $gameDao;
  private $lobbyVue;

  public function __construct() {
    $this->lobbyVue = new LobbyVue();
    $this->gameDao = new GameDao();
  }

  /**
  * Method to check if the player own the game
  * @param gameId game id to check
  * @param login login of the player to check
  * @return state True if the player owned the game
  **/
  public function isOwner(int $gameId, String $login) : bool {
    return $this->gameDao->isOwner($gameId, $login);
  }

  /**
  * Method to create a game owned by the player
  * @param login login of the player
  * @return game game created for the player
  **/
  public function createGame(String $login) : Game {
    return $this->gameDao->createGame($login);
  }

  /**
  * Method to display the lobby vue
  **/
  public function display() {
    $this->lobbyVue->vue($this->gameDao->getHistoryGames($_SESSION['login']), $this->gameDao->getRankingGames(), $_SESSION['login']);
  }
}
