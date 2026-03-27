<?php

$host = $_SERVER['HTTP_HOST'];
$script = dirname($_SERVER['SCRIPT_NAME']);

define('BASE_URL', 'http://' . $host . str_replace('public', '', $script));

define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);

define('APP_PATH', ROOT_PATH);

define('APP_NAME', 'Library Management System');

define('FINE_PER_DAY', 5);       // ₱5 per day overdue
define('DEFAULT_BORROW_DAYS', 14); // 14 days borrow period
