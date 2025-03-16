<?php declare(strict_types=1);

namespace tests\unit\models;

use Surfsail\models\UserModel;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;


final class UserModelTest extends TestCase
{
    public static function registerProvider(): array
    {
        return [
            'validData' => [
                'testuser',
                'testuser@example.com',
                'password123'
            ],
            // 'missingUsername' => [
            //     'username' => '',
            //     'email' => 'testuser@example.com',
            //     'password' => 'password123'
            // ],
            // 'invalidEmail' => [
            //     'username' => 'testuser',
            //     'email' => 'invalid-email',
            //     'password' => 'password123'
            // ],
            // 'shortPassword' => [
            //     'username' => 'testuser',
            //     'email' => 'testuser@example.com',
            //     'password' => 'short'
            // ],
        ];
    }

    #[DataProvider('registerProvider')]
    public function testRegister($a, $b, $c)
    {
        $dataset = [$a, $b, $c];
        $user_model = new UserModel();
        $user_model->register($dataset);
    }
}