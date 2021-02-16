<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

# Imports
require_once 'config/Config.php';
require_once PATH_CONTROLLER . '/Router.php';
require_once PATH_JOB . '/Grid.php';
require_once PATH_JOB . '/Game.php';

session_start();

$router = new Router();

$router->request();
?>
