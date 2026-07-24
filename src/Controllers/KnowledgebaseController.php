<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Knowledgebase;

class KnowledgebaseController extends Controller
{
    public function index()
    {
        $articles = Knowledgebase::all();
        $this->render('knowledgebase/index', [
            'articles' => $articles,
            'title' => 'Knowledge Base'
        ]);
    }

    public function view($slug = '')
    {
        if (empty($slug)) {
            $this->redirect('/knowledgebase');
        }

        $article = Knowledgebase::findBySlug($slug);
        if (!$article) {
            $this->redirect('/knowledgebase');
        }

        $this->render('knowledgebase/view', [
            'article' => $article,
            'title' => $article['title']
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
        $articles = Knowledgebase::all();
        $this->render('knowledgebase/admin', [
            'articles' => $articles,
            'title' => 'Knowledge Base Admin'
        ]);
    }

    public function create()
    {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->requireCsrf();
            Knowledgebase::create($_POST);
            $this->redirect('/knowledgebase/admin');
        }
    }

    public function edit($id = null)
    {
        $this->checkAuth();
        if (!$id) $this->redirect('/knowledgebase/admin');

        $item = \App\Models\Knowledgebase::findById($id);
        if (!$item) $this->redirect('/knowledgebase/admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->requireCsrf();
            \App\Models\Knowledgebase::update($id, $_POST);
            $this->redirect('/knowledgebase/admin');
        }

        $this->render('knowledgebase/edit', [
            'article' => $item,
            'title' => 'Edit ' . ucfirst('Knowledgebase')
        ]);
    }
    public function delete($id = null)
    {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $this->requireCsrf();
            Knowledgebase::delete($id);
            $this->redirect('/knowledgebase/admin');
        }
    }
}
