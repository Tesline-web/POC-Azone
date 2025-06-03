<?php
session_start();
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'controllers/Router.php';

$router = new Router();
$router->route();
