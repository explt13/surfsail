<?php
namespace Surfsail\models;

use PDOException;
use Surfsail\interfaces\UserModelInterface;
use \Respect\Validation\Validator as v;

class UserModel extends AppModel implements UserModelInterface
{
    protected array $attributes = [
        'password' => "",
        'email' => "",
        'first_name' => "",
        'last_name' => "",
        'profile_pic' => "",
        'address' => "",
        'role' => 'user'
    ];


    public function register($data): array
    {
        try{
            $this->setDefinedAttributes($data);
            foreach ($this->attributes as $k => $v) {
                $v = trim($v);
                if ($k === 'email') {
                    v::email()->check($v);
                }
                if ($k === 'password') {
                    v::notEmpty()->length(8, null)->check($v);
                    if (!isset($data['password-confirm']) || $v !== trim($data['password-confirm'])) {
                        throw new \InvalidArgumentException('Password confirmation does not match.');
                    }
                    $this->attributes['password'] = password_hash($v, PASSWORD_BCRYPT, ['cost' => 12]);
                }
            }
            $stmt = $this->pdo->prepare('INSERT INTO user (email, password, role) VALUES (:email, :password, :role)');
            $stmt->execute([
                'email' => $this->attributes['email'],
                'password' => $this->attributes['password'],
                'role' => $this->attributes['role']
            ]);
            
            $user_id = $this->pdo->lastInsertId();
            $_SESSION['user'] = [
                'id' => $user_id,
                'email' => $this->attributes['email'],
                'role' => $this->attributes['role'],
            ];
            session_regenerate_id(true);

            $this->generateRememberToken($data, $user_id);
            return ['response_code' => 200, 'message' => 'Registered successfully'];
          
        } catch (\PDOException $e) {
            if ($e->getCode() === '23000') {
                return ['response_code' => 400, 'message' => 'Email is already registered.'];
            } else {
                return ['response_code' => 500, 'message' => 'Error has occured. Try again later.'];
            }
        } catch (\Exception $e) {
            return ['response_code' => 400, 'message' => $e->getMessage()];
        }
        
    }


    public function login($data): array
    {
        $stmt = $this->pdo->prepare('SELECT u.* FROM user u WHERE u.email = :email');
        $stmt->execute(['email' => $data['email']]);
        $user = $stmt->fetch();
        
        if (!$user || !password_verify($data['password'], $user['password'])) {
            return ['response_code' => 400, 'message' => 'Email/password is incorrect'];
        }
        $this->authenticate($user);
        $this->generateRememberToken($data, $user['id']);
        return ['response_code' => 200, 'message' => 'Login successfully'];
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
        setcookie('id', "", time() - 60 * 60 * 24, '/');
        if (isset($_COOKIE['rem_token'])) {
            $token = $_COOKIE['rem_token'];
            $stmt = $this->pdo->prepare('DELETE FROM user_remember ur WHERE ur.token = :token');
            $stmt->execute(['token' => $token]);
            setcookie('rem_token', "", time() - 60 * 60 * 24, '/');
        }
    }

    
    public function getUserByEmail(string $email): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM user u WHERE u.email = :email');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }
    
    public function getUserById(int $user_id): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM user u WHERE u.id = :user_id');
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetch();
    }
    public function authenticate(array $user): void
    {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'role' => $user['role'],
        ];
        session_regenerate_id(true);
    }

    public function isUserAuthenticated(): array|false
    {
        try {
            $rem_token = $_COOKIE['rem_token'] ?? null;

            if (is_null($rem_token)) {
                return false;
            }

            $stmt = $this->pdo->prepare('SELECT * FROM user_remember ur WHERE ur.token = :token');
            $stmt->execute(['token' => $rem_token]);
            $result = $stmt->fetch();
            if ($result === false) {
                return false;
            }
            return $result;        
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function isAdmin(): bool
    {
        return (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin');
    }

    private function generateRememberToken(array $data, int $user_id)
    {
        if (isset($data['remember'])) {
            try {
                $token = bin2hex(random_bytes(32));
                $expires = strtotime('+1 year');
                $stmt = $this->pdo->prepare('INSERT INTO user_remember (user_id, token, expires) VALUES (:user_id, :token, FROM_UNIXTIME(:expires))');
                $stmt->execute(['user_id' => $user_id, 'token' => $token,'expires' => $expires]);
                setcookie('rem_token', $token, $expires, '/', "", true, true);
            } catch (\PDOException $e) {
                return ['response_code' => 200, 'message' => 'Couldn\'t remember you, next time you will have to log in again'];
            }
           
        }
    }
}