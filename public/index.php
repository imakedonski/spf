<?php

require '../vendor/autoload.php';

use SPF\App\App;
use SPF\Routing\Router;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

App::init();
Router::dispatch();