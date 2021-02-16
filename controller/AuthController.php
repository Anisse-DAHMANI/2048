<?php
require_once PATH_MODEL . '/PlayerDao.php';
require_once PATH_VUE . '/LoginVue.php';
require_once PATH_VUE . '/RegisterVue.php';

class AuthController {

  private $playerDao;
  private $loginVue;
  private $registerVue;

  public function __construct() {
    $this->playerDao = new PlayerDao();
    $this->loginVue = new LoginVue();
    $this->registerVue = new RegisterVue();
  }

  /**
  * Method to check if authentification is avaible.
  * @return state True if it is
  **/
  public function authAvailable() : bool {
    return isset($_POST['login']) && isset($_POST['password']);
  }

  /**
  * Method to check if the player is authentificated
  * @return state True if the player is authentificated
  **/
  public function isAuthenticated() {
    return isset($_SESSION['login']);
  }

  /** Method to try to login
  * @param login login to try to login with
  * @param password password to try to login with
  * @return state True if the authentification succeed
  **/
  public function tryLogin($login, $password) : bool {
    return $this->playerDao->login($login, $password);
  }

  /** Method to try to register
  * @param login login to try to register with
  * @param password password to try to register with
  * @return state True if the authentification succeed
  **/
  public function tryRegister($login, $password) : bool {
    return $this->playerDao->register($login, $password);
  }

  /**
  * Method to display the login vue
  **/
  public function displayLoginVue($error = NULL) {
    return $this->loginVue->vue($error);
  }


  /**
  * Method to display the register vue
  **/
  public function displayRegisterVue($error = NULL) {
    return $this->registerVue->vue($error);
  }
}
