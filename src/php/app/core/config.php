<?php

if ($_SERVER['SERVER_NAME'] == 'localhost') {
    define('ROOT', 'http://localhost/php-mvc-01/public');

    define('DB_NAME', 'mydb');
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASSWORD', '');
    define('DB_DRIVER', '');
} else {
    define('ROOT', 'https://www.yoursite.com');

    define('DB_NAME', 'mydb');
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASSWORD', '');
    define('DB_DRIVER', '');
}

define('APP_NAME', 'My Website');
//if true errors will show
define('DEBUG', true);
