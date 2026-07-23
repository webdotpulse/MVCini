<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Post;
use App\Models\Tag;
use App\Core\I18n;

class BlogController extends Controller
{
    /**
     * Public blog index (list posts)
     */
    public function index()
    {
        $lang = $_SESSION['lang'] ?? 'en';
        $search = $_GET['q'] ?? '';
        $tagFilter = $_GET['tag'] ?? '';

        $posts = Post::getPublished($lang, $search, $tagFilter);
        $tags = Tag::all();

        // Attach tags to posts for display
        foreach ($posts as &$post) {
            $post['tags'] = Post::getTags($post['id']);
        }
        unset($post);

        $this->render('blog/index', [
            'posts' => $posts,
            'tags' => $tags,
            'search' => $search,
            'tagFilter' => $tagFilter,
            'title' => I18n::get('blog.title') ?? 'Blog'
        ]);
    }

    /**
     * View a single post
     */
    public function view($slug = '')
    {
        if (empty($slug)) {
            $this->redirect('/blog');
        }

        $lang = $_SESSION['lang'] ?? 'en';
        $post = Post::findBySlug($slug, $lang);

        if (!$post) {
            $this->redirect('/blog');
        }

        $postTags = Post::getTags($post['id']);

        $this->render('blog/view', [
            'post' => $post,
            'tags' => $postTags,
            'title' => $post['title']
        ]);
    }

    /**
     * Check admin auth
     */
    private function checkAuth()
    {
        if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            $this->redirect('/auth/login');
        }
    }

    /**
     * Admin list posts
     */
    public function admin()
    {
        $this->checkAuth();
        $posts = Post::all();
        $this->render('blog/admin', [
            'posts' => $posts,
            'title' => 'Blog Admin'
        ]);
    }

    /**
     * Create post
     */
    public function create()
    {
        $this->checkAuth();
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->requireCsrf();

            $data = [
                'slug' => $_POST['slug'] ?? '',
                'lang' => $_POST['lang'] ?? 'en',
                'title' => $_POST['title'] ?? '',
                'content' => $_POST['content'] ?? '',
                'header_type' => $_POST['header_type'] ?? 'none',
                'header_url' => $_POST['header_url'] ?? '',
                'has_sidebar' => isset($_POST['has_sidebar']) ? 1 : 0
            ];

            if (empty($data['slug']) || empty($data['title'])) {
                $error = 'Slug and title are required.';
            } else {
                if (Post::create($data)) {
                    $db = \App\Core\Database::getInstance();
                    $postId = $db->lastInsertId();

                    if (!empty($_POST['tags'])) {
                        Tag::setForPost($postId, $_POST['tags']);
                    }

                    $this->redirect('/blog/admin');
                } else {
                    $error = 'Failed to create post.';
                }
            }
        }

        $this->render('blog/create', [
            'error' => $error,
            'title' => 'Create Post'
        ]);
    }

    /**
     * Edit post
     */
    public function edit($id = null)
    {
        $this->checkAuth();
        if (!$id) $this->redirect('/blog/admin');

        $post = Post::findById($id);
        if (!$post) $this->redirect('/blog/admin');

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->requireCsrf();

            $data = [
                'slug' => $_POST['slug'] ?? '',
                'lang' => $_POST['lang'] ?? 'en',
                'title' => $_POST['title'] ?? '',
                'content' => $_POST['content'] ?? '',
                'header_type' => $_POST['header_type'] ?? 'none',
                'header_url' => $_POST['header_url'] ?? '',
                'has_sidebar' => isset($_POST['has_sidebar']) ? 1 : 0
            ];

            if (empty($data['slug']) || empty($data['title'])) {
                $error = 'Slug and title are required.';
            } else {
                if (Post::update($id, $data)) {
                    Tag::setForPost($id, $_POST['tags'] ?? '');
                    $this->redirect('/blog/admin');
                } else {
                    $error = 'Failed to update post.';
                }
            }
        }

        // Get current tags as string
        $tags = Post::getTags($id);
        $tagNames = array_map(function($t) { return $t['name']; }, $tags);
        $tagsStr = implode(', ', $tagNames);

        $this->render('blog/edit', [
            'post' => $post,
            'tagsStr' => $tagsStr,
            'error' => $error,
            'title' => 'Edit Post'
        ]);
    }

    /**
     * Delete post
     */
    public function delete($id = null)
    {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $this->requireCsrf();
            Post::delete($id);
        }
        $this->redirect('/blog/admin');
    }
}
