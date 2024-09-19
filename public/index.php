<?php

// Load Composer's autoloader
require_once '../vendor/autoload.php';

// Start the application
use Core\Bootstrap;
use Core\Router;

$bootstrap = new Bootstrap();

require_once __DIR__ . '/../app/web/routes.php';
Router::dispatch();
