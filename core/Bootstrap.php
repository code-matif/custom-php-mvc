<?php

namespace Core;

use Dotenv\Dotenv;
use Core\Router;

class Bootstrap
{
    public function __construct()
    {
        $this->loadEnvironment();
        $this->startSession();
        $this->handleErrors();
        $this->run();
    }

    protected function loadEnvironment()
    {
        if (file_exists(__DIR__ . '/../.env')) {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
            $dotenv->load();
        }
    }

    protected function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    protected function handleErrors()
    {
        if ($_ENV['APP_ENV'] === 'development') {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        } else {
            error_reporting(0);
            ini_set('display_errors', 0);
        }
    }

    protected function run()
    {
        Router::init();
    }
}
