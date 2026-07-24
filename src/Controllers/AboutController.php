<?php
namespace App\Controllers;

use App\Core\Controller;

class AboutController extends Controller
{
    public function index()
    {
        $this->render('about/index', [
            'title' => 'About Us'
        ]);
    }
}
