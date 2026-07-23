<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Item;
use App\Core\I18n;

class ItemController extends Controller
{
    /**
     * List all items
     */
    public function index()
    {
        $items = Item::all();
        $this->render('item/index', ['items' => $items, 'title' => I18n::get('items')]);
    }

    /**
     * Show create form and handle submission
     */
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->requireCsrf();
            $data = [
                'name' => $_POST['name'] ?? '',
                'description' => $_POST['description'] ?? ''
            ];
            Item::create($data);
            $this->redirect('/');
        }

        $this->render('item/create', ['title' => I18n::get('create_item')]);
    }

    /**
     * Show edit form and handle submission
     */
    public function edit($id = null)
    {
        if (!$id) {
            $this->redirect('/');
        }

        $item = Item::find((int) $id);
        if (!$item) {
            $this->redirect('/');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->requireCsrf();
            $data = [
                'name' => $_POST['name'] ?? '',
                'description' => $_POST['description'] ?? ''
            ];
            Item::update((int) $id, $data);
            $this->redirect('/');
        }

        $this->render('item/edit', ['item' => $item, 'title' => I18n::get('edit_item')]);
    }

    /**
     * Handle item deletion
     */
    public function delete($id = null)
    {
        if ($id) {
            Item::delete((int) $id);
        }
        $this->redirect('/');
    }

    /**
     * Demo AJAX endpoint
     */
    public function api()
    {
        $items = Item::all();
        $this->jsonResponse(['status' => 'success', 'data' => $items]);
    }

    /**
     * Switch Language
     */
    public function lang($lang = 'en')
    {
        I18n::setLang($lang);
        // Redirect back to previous page
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($referer);
    }
}
