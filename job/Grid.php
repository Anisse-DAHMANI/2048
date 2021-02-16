<?php

class Grid {

  const GOALTILE = 2048;

  private $grid; // The grid representing all tiles of the game
  private $score; // The score of the grid

  /**
  * Method construct the 2048 Grid to play with
  **/
  public function __construct() {
    $this->grid = array(
                  array(0, 0, 0, 0),
                  array(0, 0, 0, 0),
                  array(0, 0, 0, 0),
                  array(0, 0, 0, 0));
    $this->addRand();
    $this->score = 0;
  }

  /**
  * Method to get the grid
  * @return grid the grid
  **/
  public function getGrid() : array {
    return $this->grid;
  }

  /**
  * Method to get the score of the grid
  * @return score the score
  **/
  public function getScore() : int {
    return $this->score;
  }

  /**
	* Method to copy the current grid
  * @return grid new grid with same attributes
	**/
  public function copy() : Grid {
    $grid = new Grid();
    $grid->grid = $this->grid;
    $grid->score = $this->score;
    return $grid;
  }

  /**
  * Method to get the value at x, y in the grid
  * @param x pos x of the value wanted
  * @param y pos y of the value wanted
  * @return value the value at x, y in the grid
  **/
  private function get(int $x, int $y) {
    return $this->grid[$y][$x];
  }

  /**
  * Method to set the score of the grid
  * @param score the score to apply
  **/
  public function setScore($score) : void {
    $this->score = $score;
  }

  /**
  * Method to set the value at x, y in the grid
  * @param x pos x in the grid
  * @param y pos y in the grid
  * @param value value to set at x, y in the grid
  **/
  private function set(int $x, int $y, int $value) {
    $this->grid[$y][$x] = $value;
  }

  /**
  * Method add randomly 2 or 4 at an empty place in the grid
  **/
  private function addRand() : void {
    $x = rand(0, 3);
    $y = rand(0, 3);
    $val = rand(1, 2) * 2;
    // If the place is empty set the value else retry
    if($this->get($x, $y) == 0){
      $this->set($x, $y, $val);
    }
    else $this->addRand();
  }

  /**
  * Method to check is for this grid the game is over
  * @return isOver true if the game is over
  */
  public function isOver() : bool {
    return !$this->canMoveUpOrDown() and !$this->canMoveRightOrLeft();
  }

  /**
  * Method to check if a move right or left is possible.
  * @return canMoveRightOrLeft True if it is possible.
  **/
  public function canMoveRightOrLeft() : bool {
    for ($j = 0; $j < 4; $j++) {
      for ($i = 0; $i < 3; $i++) {
        if ($this->get($i, $j) == $this->get($i + 1, $j)) {
          return true;
        }
      }
    }
    return $this->contains(0);
  }

  /**
  * Method to check if a move up or down is possible.
  * @return canMoveUpOrDown True if it is possible.
  **/
  public function canMoveUpOrDown() : bool {
    for ($i = 0; $i < 4; $i++) {
      for ($j = 0; $j < 3; $j++) {
        if ($this->get($i, $j) == $this->get($i, $j + 1)) {
          return true;
        }
      }
    }
    return $this->contains(0);
  }

  /**
  * Method to verify if a grid contains a value
  * @param value Value to search
  * @return state True if the value is in the grid
  **/
  private function contains(int $value) {
    foreach($this->grid as $row)
      foreach($row as $item)
        if($item == $value) {
          return true;
        }
    return false;
  }

  /**
  * Method to verify if a grid is won
  * @return state True if grid is won
  **/
  public function isWon() : bool {
    foreach($this->grid as $row)
      foreach($row as $item)
        if($item >= Grid::GOALTILE) {
          return true;
        }
    return false;
  }

  // Importing and exporting grid from database.

  /**
  * Method to export the grid to a string to save it in the data base
  * @return grid The grid's string
  **/
  public function export() : String {
    $buffer = "";
    foreach($this->grid as $row)
      foreach($row as $item)
        $buffer .= $item . ',';
    $buffer = substr($buffer, 0, strlen($buffer) - 1);
    return $buffer;
  }

  /**
  * Method to import the grid from a string stocked in the data base
  * @param data The grid's string
  **/
  public function import(String $data) : void {
    $i = 0;
    $values = explode(',', $data);
    $grid = array();
    foreach($values as $value) {
      if($i % 4 == 0)
        array_push($grid, array());
        array_push($grid[$i / 4], $value);
        $i++;
    }
    $this->grid = $grid;
  }

  // Moving the grid

  /**
  * Method to move the grid to the right
  **/
  public function moveRight() : void {
    for($y = 0; $y < 4; $y++) {
      $x = 3;
      while($x >= 1) {
        $placeA = $this->get($x, $y);
        $placeB = $this->get($x - 1, $y);
        if($placeA == 0 && $placeB != 0) {
          $this->set($x, $y, $placeB);
          $this->set($x - 1, $y, 0);
          $x = 3;
          continue;
        }
        $x--;
      }
    }
    for($y = 0; $y < 4; $y++) {
      $x = 3;
      while($x >= 1) {
        $placeA = $this->get($x, $y);
        $placeB = $this->get($x - 1, $y);
        if($placeA == $placeB) {
          $this->set($x, $y, $placeB * 2);
          $this->score += $placeB * 2;
          $this->set($x - 1, $y, 0);
        }
        $x--;
      }
    }
    for($y = 0; $y < 4; $y++) {
      $x = 3;
      while($x >= 1) {
        $placeA = $this->get($x, $y);
        $placeB = $this->get($x - 1, $y);
        if($placeA == 0 && $placeB != 0) {
          $this->set($x, $y, $placeB);
          $this->set($x - 1, $y, 0);
          $x = 3;
          continue;
        }
        $x--;
      }
    }
    $this->addRand();
  }

  /**
  * Method to move the grid to the left
  **/
  public function moveLeft() : void {
    for($y = 0; $y < 4; $y++) {
      $x = 0;
      while($x <= 2) {
        $placeA = $this->get($x, $y);
        $placeB = $this->get($x + 1, $y);
        if($placeA == 0 && $placeB != 0) {
          $this->set($x, $y, $placeB);
          $this->set($x + 1, $y, 0);
          $x = 0;
          continue;
        }
        $x++;
      }
    }
    for($y = 0; $y < 4; $y++) {
      $x = 0;
      while($x <= 2) {
        $placeA = $this->get($x, $y);
        $placeB = $this->get($x + 1, $y);
        if($placeA == $placeB) {
          $this->set($x, $y, $placeB * 2);
          $this->score += $placeB * 2;
          $this->set($x + 1, $y, 0);
        }
        $x++;
        }
    }
    for($y = 0; $y < 4; $y++) {
      $x = 0;
      while($x <= 2) {
        $placeA = $this->get($x, $y);
        $placeB = $this->get($x + 1, $y);
        if($placeA == 0 && $placeB != 0) {
          $this->set($x, $y, $placeB);
          $this->set($x + 1, $y, 0);
          $x = 0;
          continue;
        }
        $x++;
        }
    }
    $this->addRand();
  }

  /**
  * Method to move the grid to the down
  **/
  public function moveDown() : void {
    for($x = 0; $x < 4; $x++) {
      $y = 3;
      while($y >= 1) {
        $placeA = $this->get($x, $y);
        $placeB = $this->get($x, $y - 1);
        if($placeA == 0 && $placeB != 0) {
          $this->set($x, $y, $placeB);
          $this->set($x, $y - 1, 0);
          $y = 3;
          continue;
        }
        $y--;
      }
    }
    for($x = 0; $x < 4; $x++) {
      $y = 3;
      while($y >= 1) {
        $placeA = $this->get($x, $y);
        $placeB = $this->get($x, $y - 1);
        if($placeA == $placeB) {
          $this->set($x, $y, $placeB * 2);
          $this->score += $placeB * 2;
          $this->set($x, $y - 1, 0);
        }
        $y--;
      }
    }
    for($x = 0; $x < 4; $x++) {
      $y = 3;
      while($y >= 1) {
        $placeA = $this->get($x, $y);
        $placeB = $this->get($x, $y - 1);
        if($placeA == 0 && $placeB != 0) {
          $this->set($x, $y, $placeB);
          $this->set($x, $y - 1, 0);
          $y = 3;
          continue;
        }
        $y--;
      }
    }
    $this->addRand();
  }

  /**
  * Method to move the grid to the up
  **/
  public function moveUp() : void {
    for($x = 0; $x < 4; $x++) {
      $y = 0;
      while($y <= 2) {
        $placeA = $this->get($x, $y);
        $placeB = $this->get($x, $y + 1);
        if($placeA == 0 && $placeB != 0) {
          $this->set($x, $y, $placeB);
          $this->set($x, $y + 1, 0);
          $y = 0;
          continue;
        }
        $y++;
      }
    }
    for($x = 0; $x < 4; $x++) {
      $y = 0;
      while($y <= 2) {
        $placeA = $this->get($x, $y);
        $placeB = $this->get($x, $y + 1);
        if($placeA == $placeB) {
          $this->set($x, $y, $placeB * 2);
          $this->score += $placeB * 2;
          $this->set($x, $y + 1, 0);
        }
        $y++;
      }
    }
    for($x = 0; $x < 4; $x++) {
      $y = 0;
      while($y <= 2) {
        $placeA = $this->get($x, $y);
        $placeB = $this->get($x, $y + 1);
        if($placeA == 0 && $placeB != 0) {
          $this->set($x, $y, $placeB);
          $this->set($x, $y + 1, 0);
          $y = 0;
          continue;
        }
        $y++;
      }
    }
    $this->addRand();
  }
}
