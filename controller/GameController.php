<?php
require_once PATH_MODEL . '/GameDao.php';
require_once PATH_JOB . '/Grid.php';
require_once PATH_VUE . '/GameVue.php';
require_once PATH_JOB . '/Game.php';

class GameController {

  private $gameDao;
  private $gameVue;

  public function __construct() {
    $this->gameDao = new GameDao();
    $this->gameVue = new GameVue();
  }

  /**
  * Method to get the game from id
  * @param gameId game id to get
  * @return game game
  **/
  public function getGame(int $gameId) : Game {
    return $this->gameDao->getGame($gameId);
  }

  /**
  * Method to execute actions of the player
  **/
  public function onAction() {
    if(!isset($_GET['action'])) return;

    $action = $_GET['action'];
    $grid = $_SESSION['game']->getGrid()->copy();
    switch($action) {
      case 'up':
        $_SESSION['game']->getGrid()->moveUp();
        $_SESSION['game']->setGridLastMove($grid);
        break;
      case 'left':
        $_SESSION['game']->getGrid()->moveLeft();
        $_SESSION['game']->setGridLastMove($grid);
        break;
      case 'right':
        $_SESSION['game']->getGrid()->moveRight();
        $_SESSION['game']->setGridLastMove($grid);
        break;
      case 'down':
        $_SESSION['game']->getGrid()->moveDown();
        $_SESSION['game']->setGridLastMove($grid);
        break;
      case 'return':
        $_SESSION['game']->returnToLastMove();
        break;
      case 'abandon':
        $_SESSION['game']->giveUp();
        break;
      }
    }

    /**
    * Method to display the game vue.
    **/
    public function display() {
      $_SESSION['game']->updateScore();
      $_SESSION['game']->updateState();
      if ($_SESSION['game']->getState() == Game::INGAME) {
        $this->onAction();
        $_SESSION['game']->updateState();
      }
      $this->gameDao->updateGame($_SESSION['game']);
      $this->gameVue->vue($_SESSION['game'], $this->gameDao->getBestScore($_SESSION['login']));
    }
}
