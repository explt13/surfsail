<?php

namespace app\models\interfaces;

interface UserModelInterface
{
    public function register($data);
    public function login($data);
    public function logout();
    public function getUserByEmail(string $email);
    public static function isAdmin();
    public function loginRemembered();
}