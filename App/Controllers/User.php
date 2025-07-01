<?php

namespace App\Controllers;

use App\Config;
use App\Models\Articles;
use App\Utility\Hash;
use \Core\View;
use Exception;

/**
 * User controller
 */
class User extends \Core\Controller
{

    /**
     * Affiche la page de login
     */
    public function loginAction()
    {
        if(isset($_POST['submit'])){
            $f = $_POST;

            // TODO: Validation

            $this->login($f);

            // Si login OK, redirige vers le compte
            header('Location: /account');
        }

        View::renderTemplate('User/login.html');
    }

    /**
     * Page de création de compte
     */
    public function registerAction()
    {
        if (isset($_POST['submit'])) {
            $f = $_POST;

            if ($f['password'] !== $f['password-check']) {
                // TODO: Gestion d'erreur côté utilisateur
            }

            // Validation (optionnelle)

            $this->register($f);

            // ✅ Connexion automatique
            $this->login($f);

            // ✅ Redirection vers le compte
            header('Location: /account');
            return;
        }

        View::renderTemplate('User/register.html');
    }

    /**
     * Affiche la page du compte
     */
    public function accountAction()
    {
        $articles = Articles::getByUser($_SESSION['user']['id']);

        View::renderTemplate('User/account.html', [
            'articles' => $articles
        ]);
    }

    /*
     * Fonction privée pour enregister un utilisateur
     */
    private function register($data)
    {
        try {
            // Generate a salt, which will be applied to the during the password
            // hashing process.
            $salt = Hash::generateSalt(32);

            $userID = \App\Models\User::createUser([
                "email" => $data['email'],
                "username" => $data['username'],
                "password" => Hash::generate($data['password'], $salt),
                "salt" => $salt
            ]);

            return $userID;

        } catch (Exception $ex) {
            // TODO : Set flash if error : utiliser la fonction en dessous
            /* Utility\Flash::danger($ex->getMessage());*/
        }
    }

    private function login($data)
    {
        try {
            if (!isset($data['email'])) {
                throw new Exception('TODO');
            }

            $user = \App\Models\User::getByLogin($data['email']);

            if (!$user || Hash::generate($data['password'], $user['salt']) !== $user['password']) {
                return false;
            }

            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
            ];

            // ✅ Remember me
            if (!empty($data['remember'])) {
                $token = bin2hex(random_bytes(32)); // 64-char
                \App\Models\User::storeRememberToken($user['id'], $token);

                setcookie(
                    'remember_me',
                    $token,
                    time() + (86400 * 30), // 30 jours
                    '/',
                    '', // domaine
                    isset($_SERVER['HTTPS']),
                    true // httpOnly
                );
            }

            return true;

        } catch (Exception $ex) {
            // TODO : Set flash if error
            /* Utility\Flash::danger($ex->getMessage());*/
        }
    }


    /**
     * Logout: Delete cookie and session. Returns true if everything is okay,
     * otherwise turns false.
     * @access public
     * @return boolean
     * @since 1.0.2
     */
    public function logoutAction()
    {
        if (isset($_COOKIE['remember_me'])) {
            setcookie('remember_me', '', time() - 3600, '/');
            if (isset($_SESSION['user']['id'])) {
                \App\Models\User::storeRememberToken($_SESSION['user']['id'], null);
            }
        }

        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();

        header("Location: /");

        return true;
    }

}
