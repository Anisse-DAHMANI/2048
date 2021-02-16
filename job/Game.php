<?php

class Game {

  const INGAME = "In-Game";
  const WON = "Won";
  const LOST = "Lost";

  private $id;
  private $login;
  private $state;
  private $score;
  private $scoreLastMove;

  public function __toString() {
    return $this->id." ".$this->login." ".$this->state." ".$this->score;
  }

  public function __construct() {
  }

  public function __set($name, $value) : void {
    switch ($name) {
      case 'grid':
        $grid = new Grid();
        $grid->import($value);
        $this->grid = $grid;
        break;

      case 'gridLastMove':
        $grid = new Grid();
        $grid->import($value);
        $this->gridLastMove = $grid;
        break;
    }
  }

  /**
  * Method to get the id of the game
  * @return id game id
  **/
  public function getId() : int {
    return $this->id;
  }

  /**
  * Method to get the login of the owner of the game
  * @return login login of the game owner
  **/
  public function getLogin() : String {
    return $this->login;
  }

  /**
  * Method to get the state of the game
  * @return state game state
  **/
  public function getState() : String {
    return $this->state;
  }

  /**
  * Method to get game score
  * @return score game score
  **/
  public function getScore() : int {
    return $this->grid->getScore();
  }

  /**
  * Method to get game grid
  * @return grid game grid
  **/
  public function getGrid() : Grid {
    return $this->grid;
  }

  /**
  * Method to get game score from the last move
  * @return score game score from the last move
  **/
  public function getScoreLastMove() : int {
    return $this->gridLastMove->getScore();
  }

  /**
  * Method to get game grid from the last move
  * @return grid game grid from the last move
  **/
  public function getGridLastMove() : Grid {
    return $this->gridLastMove;
  }

  /**
  * Method to set the gridLastMove
  * @param grid grid from last move
  **/
  public function setGridLastMove(Grid $grid) : void {
    $this->gridLastMove = $grid;
    $this->scoreLastMove = $grid->getScore();
  }

  /**
  * Method to update the state of the game depending on the grid
  **/
  public function updateState() {
    if ($this->grid->isOver() and $this->state == GAME::INGAME) {
      if ($this->grid->isWon()) {
        $this->state = GAME::WON;
      } else {
        $this->state = GAME::LOST;
      }
    }
  }

  /**
  * Method to update scores of the game depending on grid scores
  **/
  public function updateScore() {
    $this->grid->setScore($this->score);
    $this->gridLastMove->setScore($this->scoreLastMove);
  }

  /**
  * Method to give up the game
  **/
  public function giveUp() {
    if ($this->grid->isWon()) {
      $this->state = GAME::WON;
    } else {
      $this->state = GAME::LOST;
    }
  }

  /**
  * Me thod to return to the last move and to save the current move as last move.
  **/
  public function returnToLastMove() {
    $score = $this->score;
    $grid = $this->grid;
    $this->score = $this->scoreLastMove;
    $this->grid  = $this->gridLastMove;
    $this->scoreLastMove = $score;
    $this->gridLastMove = $grid;
  }
}
