<?php

namespace Core;

class View
{
    public static function render($view, $data = [])
    {
        $viewFile = '../app/views/' . $view . '.php';
        if (file_exists($viewFile)) {
            extract($data);
            require_once $viewFile;
        } else {
            echo "View not found";
        }
    }
}
