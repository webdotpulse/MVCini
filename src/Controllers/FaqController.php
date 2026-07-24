<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::all();
        $this->render('faq/index', [
            'faqs' => $faqs,
            'title' => 'FAQ'
        ]);
    }

    private function checkAuth()
    {
        if (empty(\App\Core\Session::get('user_id')) || \App\Core\Session::get('role') !== 'admin') {
            $this->redirect('/auth/login');
        }
    }

    public function admin()
    {
        $this->checkAuth();
        $faqs = Faq::all();
        $this->render('faq/admin', [
            'faqs' => $faqs,
            'title' => 'FAQ Admin'
        ]);
    }

    public function create()
    {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->requireCsrf();
            Faq::create($_POST);
            $this->redirect('/faq/admin');
        }
    }

    public function edit($id = null)
    {
        $this->checkAuth();
        if (!$id) $this->redirect('/faq/admin');

        $item = \App\Models\Faq::findById($id);
        if (!$item) $this->redirect('/faq/admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->requireCsrf();
            \App\Models\Faq::update($id, $_POST);
            $this->redirect('/faq/admin');
        }

        $this->render('faq/edit', [
            'faq' => $item,
            'title' => 'Edit ' . ucfirst('Faq')
        ]);
    }
    public function delete($id = null)
    {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $this->requireCsrf();
            Faq::delete($id);
            $this->redirect('/faq/admin');
        }
    }
}
