<?php
require_once PATH_JOB . "/Grid.php";
require_once PATH_JOB . "/Game.php";

class GameVue {

  /**
  * Method to display the Game vue.
  * @param game Game to display.
  * @param bestScore Best score of the player to display.
  **/
  public function vue(Game $game, int $bestScore) { ?>
    <!DOCTYPE html>
    <html lang="en">
      <head>
        <title>2048 - Lobby</title>
        <meta charset="utf-8"/>
        <meta name="description" content="2048 full php game page made by Emilien Bidet.
                                          Play your game with controls or go back to the main lobby.">
        <link rel="stylesheet" href="vue/css/2048.css"/>
        <link rel="stylesheet" href="vue/css/game.css"/>
      </head>
      <body>
        <?php include("header.php"); ?>
        <div class="content">
          <div class="score">
            <div class="bestScore">
              <h2>Best</h2>
              <p><?php echo $bestScore; ?></p>
            </div>
            <div class="currentScore">
              <h2>Score</h2>
              <p><?php echo $game->getGrid()->getScore(); ?></p>
            </div>
            <?php if ($game->getState() != GAME::INGAME): ?>
            <div class="currentState">
              <h2><?php echo $game->getState() ?></h2>
            </div>
            <?php endif; ?>
          </div>
          <div class="game">
          <?php
          foreach($game->getGrid()->getGrid() as $row) {
            foreach($row as $item) { ?>
              <div class="tile tile-<?php echo $item; ?>"><?php if ($item != 0) echo $item; ?></div>
          <?php } } ?>
          </div>
          <div class="controls">
            <div class="moves">
              <form action="" method="get">
                <input type="hidden" name="page" value="game" readonly>
                <input type="hidden" name="id" value="<?php echo $game->getId(); ?>" readonly>
                <button class="move moveUp" type="submit" name="action" value="up" <?php if(!$game->getGrid()->canMoveUpOrDown() or $game->getState() != Game::INGAME) echo "disabled"; ?>>↑</button>
                <button class="move moveLeft" type="submit" name="action" value="left" <?php if(!$game->getGrid()->canMoveRightOrLeft() or $game->getState() != Game::INGAME) echo "disabled"; ?>>←</button>
                <button class="move return" type="submit" name="action" value="return" <?php if($game->getState() != Game::INGAME) echo "disabled"; ?>>↵</button>
                <button class="move moveRight" type="submit" name="action" value="right" <?php if(!$game->getGrid()->canMoveRightOrLeft() or $game->getState() != Game::INGAME) echo "disabled"; ?>>→</button>
                <button class="move moveDown" type="submit" name="action" value="down" <?php if(!$game->getGrid()->canMoveUpOrDown() or $game->getState() != Game::INGAME) echo "disabled"; ?>>↓</button>
              </form>
            </div>
            <div class="abandon">
              <form action="" method="get">
                <input type="hidden" name="page" value="game" readonly>
                <input type="hidden" name="id" value="<?php echo $game->getId(); ?>" readonly>
                <button type="submit" name="action" value="abandon" <?php if($game->getState() != Game::INGAME) echo "disabled"; ?>>Give up</button>
              </form>
              <form action="" method="get">
                <button type="submit" name="page" value="lobby">Lobby</button>
              </form>
            </div>
          </div>
        </div>
        <?php include("footer.php"); ?>
      </body>
    </html>
<?php } } ?>
