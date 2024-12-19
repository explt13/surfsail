<?php

namespace app\models\interfaces;

interface UserModelInterface
{
    public function signup($data);
    public function login($data);
    public function logout();
    public function getUserByEmail(string $email);
    public static function isAdmin();
}