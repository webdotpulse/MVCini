<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Post
{
    /**
     * Create a new post
     */
    public static function create($data)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('
            INSERT INTO posts (slug, lang, title, content, header_type, header_url, has_sidebar)
            VALUES (:slug, :lang, :title, :content, :header_type, :header_url, :has_sidebar)
        ');
        return $stmt->execute([
            'slug' => $data['slug'],
            'lang' => $data['lang'] ?? 'en',
            'title' => $data['title'],
            'content' => $data['content'],
            'header_type' => $data['header_type'] ?? 'none',
            'header_url' => $data['header_url'] ?? null,
            'has_sidebar' => $data['has_sidebar'] ?? 1
        ]);
    }

    /**
     * Get a post by ID
     */
    public static function findById($id)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM posts WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Get a post by slug and lang
     */
    public static function findBySlug($slug, $lang = 'en')
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM posts WHERE slug = :slug AND lang = :lang');
        $stmt->execute(['slug' => $slug, 'lang' => $lang]);
        return $stmt->fetch();
    }

    /**
     * Update a post
     */
    public static function update($id, $data)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('
            UPDATE posts
            SET slug = :slug,
                lang = :lang,
                title = :title,
                content = :content,
                header_type = :header_type,
                header_url = :header_url,
                has_sidebar = :has_sidebar
            WHERE id = :id
        ');
        return $stmt->execute([
            'id' => $id,
            'slug' => $data['slug'],
            'lang' => $data['lang'] ?? 'en',
            'title' => $data['title'],
            'content' => $data['content'],
            'header_type' => $data['header_type'] ?? 'none',
            'header_url' => $data['header_url'] ?? null,
            'has_sidebar' => $data['has_sidebar'] ?? 1
        ]);
    }

    /**
     * Delete a post
     */
    public static function delete($id)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('DELETE FROM posts WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Get all posts (for admin)
     */
    public static function all()
    {
        $db = Database::getInstance();
        $stmt = $db->query('SELECT * FROM posts ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    /**
     * Get posts with search and filter
     */
    public static function getPublished($lang = 'en', $search = '', $tag = '')
    {
        $db = Database::getInstance();
        $query = 'SELECT DISTINCT p.* FROM posts p ';
        $params = ['lang' => $lang];

        if ($tag !== '') {
            $query .= 'JOIN post_tags pt ON p.id = pt.post_id ';
            $query .= 'JOIN tags t ON pt.tag_id = t.id ';
        }

        $query .= 'WHERE p.lang = :lang ';

        if ($tag !== '') {
            $query .= 'AND t.name = :tag ';
            $params['tag'] = $tag;
        }

        if ($search !== '') {
            $query .= 'AND (p.title LIKE :search OR p.content LIKE :search) ';
            $params['search'] = '%' . $search . '%';
        }

        $query .= 'ORDER BY p.created_at DESC';

        $stmt = $db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Get tags for a post
     */
    public static function getTags($postId)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('
            SELECT t.* FROM tags t
            JOIN post_tags pt ON t.id = pt.tag_id
            WHERE pt.post_id = :post_id
        ');
        $stmt->execute(['post_id' => $postId]);
        return $stmt->fetchAll();
    }
}
