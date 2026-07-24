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
        $this->render('item/index', [
            'items' => $items,
            'title' => I18n::get('item.title'),
            'meta_description' => I18n::get('item.meta_description'),
            'meta_keywords' => I18n::get('item.meta_keywords')
        ]);
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

        $this->render('item/create', [
            'title' => I18n::get('global.create_item'),
            'meta_description' => I18n::get('item.meta_description'),
            'meta_keywords' => I18n::get('item.meta_keywords')
        ]);
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

        $this->render('item/edit', [
            'item' => $item,
            'title' => I18n::get('global.edit_item'),
            'meta_description' => I18n::get('item.meta_description'),
            'meta_keywords' => I18n::get('item.meta_keywords')
        ]);
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

}
