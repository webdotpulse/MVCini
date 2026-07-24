<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $this->render('products/index', [
            'products' => $products,
            'title' => 'Products'
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
        $products = Product::all();
        $this->render('products/admin', [
            'products' => $products,
            'title' => 'Products Admin'
        ]);
    }

    public function create()
    {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->requireCsrf();
            Product::create($_POST);
            $this->redirect('/products/admin');
        }
    }

    public function edit($id = null)
    {
        $this->checkAuth();
        if (!$id) $this->redirect('/products/admin');

        $item = \App\Models\Product::findById($id);
        if (!$item) $this->redirect('/products/admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->requireCsrf();
            \App\Models\Product::update($id, $_POST);
            $this->redirect('/products/admin');
        }

        $this->render('products/edit', [
            'product' => $item,
            'title' => 'Edit ' . ucfirst('Product')
        ]);
    }
    public function delete($id = null)
    {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $this->requireCsrf();
            Product::delete($id);
            $this->redirect('/products/admin');
        }
    }
}
