<?php
namespace App\Models;

use App\Core\Database;

class Download
{
    public static function all()
    {
        $db = Database::getInstance();
        $stmt = $db->query('SELECT * FROM downloads ORDER BY id DESC');
        return $stmt->fetchAll();
    }

    public static function create($data)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('INSERT INTO downloads (title, file_url) VALUES (:title, :file_url)');
        return $stmt->execute([
            'title' => $data['title'],
            'file_url' => $data['file_url']
        ]);
    }

    public static function findById($id)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM downloads WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public static function update($id, $data)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('UPDATE downloads SET title = :title, file_url = :file_url WHERE id = :id');
        return $stmt->execute([
            'id' => $id,
            'title' => $data['title'],
            'file_url' => $data['file_url']
        ]);
    }

    public static function delete($id)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('DELETE FROM downloads WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
