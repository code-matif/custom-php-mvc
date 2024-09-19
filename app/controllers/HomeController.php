<?php

namespace App\Controllers;

use Core\Controller;
use Core\View;

class HomeController extends Controller
{
    public function index()
    {
        $data = ['name' => 'Home Page'];
        View::render('home/index', $data);
    }
    public function ab($id){
        echo $id;
        exit;
    }

    public function about()
    {
        $data = ['title' => 'About Us'];
        View::render('home/about', $data);
    }
}
