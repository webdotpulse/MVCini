<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Job;

class JobsController extends Controller
{
    public function index()
    {
        $jobs = Job::all();
        $this->render('jobs/index', [
            'jobs' => $jobs,
            'title' => 'Jobs'
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
        $jobs = Job::all();
        $this->render('jobs/admin', [
            'jobs' => $jobs,
            'title' => 'Jobs Admin'
        ]);
    }

    public function create()
    {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->requireCsrf();
            Job::create($_POST);
            $this->redirect('/jobs/admin');
        }
    }

    public function edit($id = null)
    {
        $this->checkAuth();
        if (!$id) $this->redirect('/jobs/admin');

        $item = \App\Models\Job::findById($id);
        if (!$item) $this->redirect('/jobs/admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->requireCsrf();
            \App\Models\Job::update($id, $_POST);
            $this->redirect('/jobs/admin');
        }

        $this->render('jobs/edit', [
            'job' => $item,
            'title' => 'Edit ' . ucfirst('Job')
        ]);
    }
    public function delete($id = null)
    {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $this->requireCsrf();
            Job::delete($id);
            $this->redirect('/jobs/admin');
        }
    }
}
