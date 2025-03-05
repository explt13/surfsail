<?php
namespace app\models;

use app\models\interfaces\UserModelInterface;
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


    public function signup($data)
    {
        try{
            $this->load($data);
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

    public function login($data)
    {
        $stmt = $this->pdo->prepare('SELECT u.* FROM user u WHERE u.email = :email');
        $stmt->execute(['email' => $data['email']]);
        $user = $stmt->fetch();
        if (!$user) {
            return ['response_code' => 400, 'message' => 'Email/password is incorrect'];
        }
        if (!password_verify($data['password'], $user['password'])) {
            return ['response_code' => 400, 'message' => 'Email/password is incorrect'];
        }
        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'role' => $user['role'],
        ];
        return ['response_code' => 200, 'message' => 'Login successfully'];
    }

    public function logout()
    {
        unset($_SESSION['user']);
        // session_destroy();
        redirect();
    }

    public function getUserByEmail(string $email)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM user u WHERE u.email = :email');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public static function isAdmin()
    {
        return (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin');
    }
}