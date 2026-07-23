<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Tag
{
    /**
     * Get or create a tag by name
     */
    public static function getOrCreate($name)
    {
        $name = trim($name);
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT id FROM tags WHERE name = :name');
        $stmt->execute(['name' => $name]);
        $tag = $stmt->fetch();

        if ($tag) {
            return $tag['id'];
        }

        $stmt = $db->prepare('INSERT INTO tags (name) VALUES (:name)');
        $stmt->execute(['name' => $name]);
        return $db->lastInsertId();
    }

    /**
     * Set tags for a post
     */
    public static function setForPost($postId, $tagsStr)
    {
        $db = Database::getInstance();
        // Delete existing tags
        $stmt = $db->prepare('DELETE FROM post_tags WHERE post_id = :post_id');
        $stmt->execute(['post_id' => $postId]);

        if (empty(trim($tagsStr))) return;

        $tags = array_map('trim', explode(',', $tagsStr));
        foreach ($tags as $tagName) {
            if (empty($tagName)) continue;
            $tagId = self::getOrCreate($tagName);

            $stmt = $db->prepare('INSERT IGNORE INTO post_tags (post_id, tag_id) VALUES (:post_id, :tag_id)');
            $stmt->execute(['post_id' => $postId, 'tag_id' => $tagId]);
        }
    }

    /**
     * Get all tags
     */
    public static function all()
    {
        $db = Database::getInstance();
        $stmt = $db->query('SELECT * FROM tags ORDER BY name ASC');
        return $stmt->fetchAll();
    }
}
