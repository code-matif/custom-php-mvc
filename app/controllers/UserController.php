<?php

namespace App\Controllers;

use Core\Controller;
use Core\View;

class UserController extends Controller
{
    public function index()
    {
        $data = ['name' => 'Home Page'];
        View::render('home/index', $data);
    }

    public function create()
    {
       return "Users crewate";
    }
    public function list()
    {
       return "Users list";
    }
}
