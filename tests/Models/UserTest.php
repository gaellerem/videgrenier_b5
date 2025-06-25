<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Models\User;
use PDO;
use PDOStatement;

class UserTest extends TestCase {

    public function testGetByLoginReturnsUserData() {
        // Simule un résultat de requête
        $expectedUser = [
            'id' => 1,
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'hashedpassword',
            'salt' => 'randomsalt'
        ];

        // Mock du statement PDO
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
             ->method('bindParam')
             ->with(':email', 'test@example.com');
        $stmt->expects($this->once())
             ->method('execute');
        $stmt->expects($this->once())
             ->method('fetch')
             ->with(PDO::FETCH_ASSOC)
             ->willReturn($expectedUser);

        // Mock du PDO
        $pdo = $this->createMock(PDO::class);
        $pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        // On injecte le mock dans le modèle User
        User::setDB($pdo);

        $result = User::getByLogin('test@example.com');

        $this->assertEquals($expectedUser, $result);
    }
}