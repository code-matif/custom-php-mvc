<?php

namespace App\Controllers;

use Core\Controller;
use Core\View;

class AdminController extends Controller
{
    public function dashboard()
    {
        return "admin dashbord";
    }
    
    public function settings()
    {
        return "admin settings";
    }


}
