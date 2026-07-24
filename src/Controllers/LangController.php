<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\I18n;

class LangController extends Controller
{
    /**
     * Switch Language
     * Handles /lang/{lang}
     */
    public function index($lang = 'en')
    {
        I18n::setLang($lang);
        // Redirect back to previous page
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($referer);
    }
}
