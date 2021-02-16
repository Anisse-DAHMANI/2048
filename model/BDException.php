<?php

// BD error
class BDException extends Exception {
  public function __construct($error){
    parent::__construct($error);
  }
}

// SQL error
class SQLException extends BDException{
  public function __construct($error){
    parent::__construct($error);
  }
}

// Connexion error
class ConnexionException extends BDException{
  public function __construct($error){
    parent::__construct($error);
  }
}
