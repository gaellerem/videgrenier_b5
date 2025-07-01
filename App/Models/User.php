<?php

namespace App\Models;

use App\Utility\Hash;
use Core\Model;
use App\Core;
use Exception;
use App\Utility;

/**
 * User Model:
 */
class User extends Model {

    /**
     * Crée un utilisateur
     */
    public static function createUser($data) {
        $db = static::getDB();

        $stmt = $db->prepare('INSERT INTO users(username, email, password, salt) VALUES (:username, :email, :password,:salt)');

        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $data['password']);
        $stmt->bindParam(':salt', $data['salt']);

        $stmt->execute();

        return $db->lastInsertId();
    }

    public static function getByLogin($login)
    {
        $db = static::getDB();

        $stmt = $db->prepare("
            SELECT * FROM users WHERE ( users.email = :email) LIMIT 1
        ");

        $stmt->bindParam(':email', $login);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function storeRememberToken($userId, $token)
    {
        $db = static::getDB();
        $stmt = $db->prepare("UPDATE users SET remember_token = :token WHERE id = :id");
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
    }

    public static function getByRememberToken($token)
    {
        $db = static::getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE remember_token = :token LIMIT 1");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }


}
