<?php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class User extends Model
{
    protected static string $table = 'users';

    public static function findByUsernameOrEmail(string $identifier)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM " . static::$table . " WHERE username = ? OR email = ?");
        $stmt->execute([$identifier, $identifier]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function incrementFailedAttempts(int $userId)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE " . static::$table . " SET failed_attempts = failed_attempts + 1, last_failed_login = NOW() WHERE id = ?");
        $stmt->execute([$userId]);
    }

    public static function resetFailedAttempts(int $userId)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE " . static::$table . " SET failed_attempts = 0, last_failed_login = NULL WHERE id = ?");
        $stmt->execute([$userId]);
    }

    public static function saveRememberToken(int $userId, string $token, string $expiresAt)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO remember_tokens (user_id, token, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([$userId, hash('sha256', $token), $expiresAt]);
    }

    public static function verifyRememberToken(int $userId, string $token): bool
    {
        $db = Database::getInstance();
        $hashedToken = hash('sha256', $token);

        // Fast path: Try finding the SHA-256 hashed token directly
        $stmt = $db->prepare("SELECT id FROM remember_tokens WHERE user_id = ? AND token = ? AND expires_at > NOW()");
        $stmt->execute([$userId, $hashedToken]);
        if ($stmt->fetch()) {
            return true;
        }

        // Slow path / Upgrade path: Fallback to fetching all tokens for legacy bcrypt hashes
        $stmt = $db->prepare("SELECT * FROM remember_tokens WHERE user_id = ? AND expires_at > NOW()");
        $stmt->execute([$userId]);
        $records = $stmt->fetchAll();

        foreach ($records as $record) {
            if (str_starts_with($record['token'], '$2') && password_verify($token, $record['token'])) {
                // Upgrade token to SHA-256
                $updateStmt = $db->prepare("UPDATE remember_tokens SET token = ? WHERE id = ?");
                $updateStmt->execute([$hashedToken, $record['id']]);
                return true;
            }
        }
        return false;
    }

    public static function clearRememberTokens(int $userId)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM remember_tokens WHERE user_id = ?");
        $stmt->execute([$userId]);
    }
}
