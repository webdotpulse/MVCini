<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Download;

class DownloadsController extends Controller
{
    public function index()
    {
        $downloads = Download::all();
        $this->render('downloads/index', [
            'downloads' => $downloads,
            'title' => 'Downloads'
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
        $downloads = Download::all();
        $this->render('downloads/admin', [
            'downloads' => $downloads,
            'title' => 'Downloads Admin'
        ]);
    }

    public function create()
    {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->requireCsrf();
            Download::create($_POST);
            $this->redirect('/downloads/admin');
        }
    }

    public function edit($id = null)
    {
        $this->checkAuth();
        if (!$id) $this->redirect('/downloads/admin');

        $item = \App\Models\Download::findById($id);
        if (!$item) $this->redirect('/downloads/admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->requireCsrf();
            \App\Models\Download::update($id, $_POST);
            $this->redirect('/downloads/admin');
        }

        $this->render('downloads/edit', [
            'download' => $item,
            'title' => 'Edit ' . ucfirst('Download')
        ]);
    }
    public function delete($id = null)
    {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $this->requireCsrf();
            Download::delete($id);
            $this->redirect('/downloads/admin');
        }
    }
}
