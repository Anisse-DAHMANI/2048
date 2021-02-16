<?php
require_once PATH_JOB . "/Game.php";

class LobbyVue {

  /**
  * Method to display the Lobby vue.
  * @param historyGames games of the player
  * @param rankingGames top 5 games
  * @param login login of the player
  **/
  public function vue($historyGames, $rankingGames, $login) { ?>
    <!DOCTYPE html>
    <html lang="en">
      <head>
        <title>2048 - Lobby</title>
          <meta charset="utf-8" />
          <meta name="description" content="2048 full php lobby page made by Emilien Bidet.
                                            Start a new game with the play button.
                                            Continue your previous games.">
          <link rel="stylesheet" href="vue/css/2048.css"/>
          <link rel="stylesheet" href="vue/css/lobby.css"/>
      </head>
      <body>
        <?php include("header.php"); ?>
        <div class="content">
          <div class="history">
            <h2>Game history</h2>
            <hr>
            <nav>
              <ul>
                <?php
                if (is_array($historyGames) || is_object($historyGames)) {
                  $id = 1;
                  foreach ($historyGames as $game) { ?>
                    <li class="<?php echo $game->getState() ?>"><a href=".?page=game&id=<?php echo $game->getId() ?>"><?php echo "#".$id." ".$game->getState()." ".$login." ".$game->getGrid()->getScore(); ?></a></li>
                <?php $id++; } } ?>
              </ul>
            </nav>
          </div>
          <div class="game">
            <form action="" method="get">
              <button type="submit" name="page" value="game">PLAY</button>
            </form>
          </div>
          <div class="ranking">
            <h2>Ranking</h2>
            <hr>
            <div class="rankingGames">
              <ul>
                <?php
                if (is_array($rankingGames) || is_object($rankingGames)) {
                  $rank = 1;
                  foreach ($rankingGames as $game) { ?>
                    <li><?php echo $rank.". ".$game->getLogin()." - ".$game->getScore(); $rank += 1; ?></li>
                <?php } } ?>
              </ul>
            </div>
          </div>
        </div>
        <?php include("footer.php"); ?>
      </body>
    </html>
  <?php } } ?>
