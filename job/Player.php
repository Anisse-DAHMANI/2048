<?php

class Player {

  private $login;
  private $password;

  /**
	* Method to get the player's login
  * @return login player's login
	**/
  public function getLogin() : String {
    return $this->login;
  }

  /**
	* Method to get the player's password hash
  * @return password player's password hash
	**/
  public function getPassword() : String {
    return $this->password;
  }
}
