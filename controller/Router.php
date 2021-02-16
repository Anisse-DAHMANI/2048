<?php

require_once PATH_CONTROLLER . '/AuthController.php';
require_once PATH_CONTROLLER . '/GameController.php';
require_once PATH_CONTROLLER . '/LobbyController.php';

class Router {

  private $authCtrl; // Authentification controller
  private $gameCtrl; // Game controller
  private $lobbyCtrl; // Lobby controller

  /**
  * Method to construct a router
  * @return router the router created
  **/
  public function __construct() {
    $this->authCtrl = new AuthController();
    $this->gameCtrl = new GameController();
    $this->lobbyCtrl = new LobbyController();
  }

  /**
  * Method to lead the user on the website
  **/
  public function request() {
    if (!isset($_GET['page'])) {
      if ($this->authCtrl->isAuthenticated()) {
        $this->gotoPage("lobby");
      } else {
        $this->gotoPage("login");
      }
      return;
    }

    $page = $_GET['page'];

    switch ($page) {
      case 'login':
        if ($this->authCtrl->isAuthenticated()) {
          $this->gotoPage("lobby");
        } else {
          if ($this->authCtrl->authAvailable()) {
            $login = $_POST['login'];
            $password = $_POST['password'];
            if ($this->authCtrl->tryLogin($login,$password)) {
              $_SESSION['login'] = $login;
              $this->gotoPage("lobby");
            } else {
              $this->authCtrl->displayLoginVue("login/password doesn't exists.");
            }
          } else {
            $this->authCtrl->displayLoginVue();
          }
        }
        break;
      case 'register':
        if ($this->authCtrl->isAuthenticated()) {
          $this->gotoPage("lobby");
        } else {
          if ($this->authCtrl->authAvailable()) {
            $login = $_POST['login'];
            $password = $_POST['password'];
            if ($this->authCtrl->tryRegister($login,$password)) {
              $this->gotoPage("login");
            } else {
              $this->authCtrl->displayRegisterVue("Login already exists.");
            }
          } else {
            $this->authCtrl->displayRegisterVue();
          }
        }
        break;

      case 'lobby':
        if ($this->authCtrl->isAuthenticated()) {
          $this->lobbyCtrl->display($_SESSION['login']);
        } else {
          $this->gotoPage("login");
        }
        break;

      case 'game':
        if ($this->authCtrl->isAuthenticated()) {
          if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $login = $_SESSION['login'];
            if ($this->lobbyCtrl->isOwner($id, $login)) {
              $_SESSION['game'] = $this->gameCtrl->getGame($id);
              $this->gameCtrl->display();
            } else { // Game doesn't exists or it's not the player's game
              $this->gotoPage("lobby");
            }
          } else {
            $_SESSION['game'] = $this->lobbyCtrl->createGame($_SESSION['login']);
            $this->gotoPage("game&id=".$_SESSION['game']->getId());
            $this->gameCtrl->display($id, $login);
          }
        } else {
          $this->gotoPage("login");
        }
        break;

      case 'disconnect':
        session_destroy();
        $this->gotoPage("login");
        break;

      default:
        echo "Error 404 : Page not found";
        break;
    }
  }

  public function gotoPage($page) {
    header("Location: ?page=" . $page);
  }

}
