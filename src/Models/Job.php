<?php
namespace App\Models;

use App\Core\Database;

class Job
{
    public static function all()
    {
        $db = Database::getInstance();
        $stmt = $db->query('SELECT * FROM jobs ORDER BY id DESC');
        return $stmt->fetchAll();
    }

    public static function create($data)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('INSERT INTO jobs (title, description, location) VALUES (:title, :description, :location)');
        return $stmt->execute([
            'title' => $data['title'],
            'description' => $data['description'],
            'location' => $data['location'] ?? null
        ]);
    }

    public static function findById($id)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM jobs WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public static function update($id, $data)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('UPDATE jobs SET title = :title, description = :description, location = :location WHERE id = :id');
        return $stmt->execute([
            'id' => $id,
            'title' => $data['title'],
            'description' => $data['description'],
            'location' => $data['location'] ?? null
        ]);
    }

    public static function delete($id)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('DELETE FROM jobs WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
