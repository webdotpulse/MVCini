<?php
namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $this->render('home/index', [
            'title' => \App\Core\I18n::get('home.title'),
            'meta_description' => \App\Core\I18n::get('home.meta_description'),
            'meta_keywords' => \App\Core\I18n::get('home.meta_keywords')
        ]);
    }
}
