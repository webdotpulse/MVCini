<?php
namespace App\Models;

use App\Core\Database;

class Knowledgebase
{
    public static function all()
    {
        $db = Database::getInstance();
        $stmt = $db->query('SELECT * FROM knowledge_articles ORDER BY id DESC');
        return $stmt->fetchAll();
    }

    public static function create($data)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('INSERT INTO knowledge_articles (title, content, slug) VALUES (:title, :content, :slug)');
        return $stmt->execute([
            'title' => $data['title'],
            'content' => $data['content'],
            'slug' => $data['slug']
        ]);
    }

    public static function findById($id)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM knowledge_articles WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public static function findBySlug($slug)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM knowledge_articles WHERE slug = :slug');
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch();
    }

    public static function update($id, $data)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('UPDATE knowledge_articles SET title = :title, content = :content, slug = :slug WHERE id = :id');
        return $stmt->execute([
            'id' => $id,
            'title' => $data['title'],
            'content' => $data['content'],
            'slug' => $data['slug']
        ]);
    }

    public static function delete($id)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('DELETE FROM knowledge_articles WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
