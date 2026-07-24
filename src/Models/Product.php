<?php
namespace App\Models;

use App\Core\Database;

class Product
{
    public static function all()
    {
        $db = Database::getInstance();
        $stmt = $db->query('SELECT * FROM products ORDER BY id DESC');
        return $stmt->fetchAll();
    }

    public static function create($data)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('INSERT INTO products (name, description, price) VALUES (:name, :description, :price)');
        return $stmt->execute([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => empty($data['price']) ? null : $data['price']
        ]);
    }

    public static function findById($id)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM products WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public static function update($id, $data)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('UPDATE products SET name = :name, description = :description, price = :price WHERE id = :id');
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => empty($data['price']) ? null : $data['price']
        ]);
    }

    public static function delete($id)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('DELETE FROM products WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
