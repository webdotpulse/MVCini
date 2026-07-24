<?php
namespace App\Models;

use App\Core\Database;

class Faq
{
    public static function all()
    {
        $db = Database::getInstance();
        $stmt = $db->query('SELECT * FROM faqs ORDER BY id DESC');
        return $stmt->fetchAll();
    }

    public static function create($data)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('INSERT INTO faqs (question, answer, category) VALUES (:question, :answer, :category)');
        return $stmt->execute([
            'question' => $data['question'],
            'answer' => $data['answer'],
            'category' => $data['category'] ?? 'General'
        ]);
    }

    public static function findById($id)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM faqs WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public static function update($id, $data)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('UPDATE faqs SET question = :question, answer = :answer, category = :category WHERE id = :id');
        return $stmt->execute([
            'id' => $id,
            'question' => $data['question'],
            'answer' => $data['answer'],
            'category' => $data['category'] ?? 'General'
        ]);
    }

    public static function delete($id)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('DELETE FROM faqs WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
