<?php

namespace App\Controllers;

use SPF\Controller\Controller;
use SPF\View\View;

class HomeController extends Controller
{
    public function index()
    {
        return new View('index', [
          'framework' => 'SPF'
        ]);
    }
}