<?php
namespace app\models;
use \Respect\Validation\Validator as v;

class UserModel extends AppModel
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


    public function signUp($data)
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
            $result = $stmt->execute([
                'email' => $this->attributes['email'],
                'password' => $this->attributes['password'],
                'role' => $this->attributes['role']
            ]);
            if ($result) {
                $user_id = $this->pdo->lastInsertId();
                $_SESSION['user'] = [
                    'id' => $user_id,
                    'email' => $this->attributes['email'],
                    'role' => $this->attributes['role'],
                ];
                return ['response_code' => 200, 'message' => 'Registered successfully'];
            } else {
                throw new \Exception('Cannot create a user');
            }

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

    public function loginUser($data)
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
}