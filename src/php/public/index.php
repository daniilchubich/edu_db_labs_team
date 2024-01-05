<?php

session_start();

require '../app/core/init.php';

//if display_errors is 1, errors will show
DEBUG ? ini_set('display_errors', 1) : ini_set('display_errors', 0);

$app = new App;
$app->loadController();
