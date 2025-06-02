<?php

namespace Surfsail\interfaces;

interface UserModelInterface
{
    public function register($data): array;
    public function login($data): array;
    public function logout(): void;
    public function getUserByEmail(string $email): array;
    public function getUserById(int $user_id): array;
    public function authenticate(array $user): void;
    public function isUserAuthenticated(): array|false;
    public static function isAdmin(): bool;

}