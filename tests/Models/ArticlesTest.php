<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Models\User;
use PDO;
use PDOStatement;

class ArticlesTest extends TestCase {

    public function testGetAllReturnsArticles() {
        // Simule un résultat de requête
        $expectedArticles = [
            ['id' => 1, 'title' => 'Article 1', 'views' => 100, 'published_date' => '2023-01-01'],
            ['id' => 2, 'title' => 'Article 2', 'views' => 200, 'published_date' => '2023-02-01']
        ];

        // Mock du statement PDO
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
             ->method('fetchAll')
             ->with(PDO::FETCH_ASSOC)
             ->willReturn($expectedArticles);

        // Mock du PDO
        $pdo = $this->createMock(PDO::class);
        $pdo->expects($this->once())
            ->method('query')
            ->willReturn($stmt);

        // On injecte le mock dans le modèle Articles
        \App\Models\Articles::setDB($pdo);

        $result = \App\Models\Articles::getAll('views');

        $this->assertEquals($expectedArticles, $result);
    }

    public function testGetOneReturnsArticle() {
        // Simule un résultat de requête
        $expectedArticle = [
            ['id' => 1, 'title' => 'Article 1', 'views' => 100, 'published_date' => '2023-01-01']
        ];

        // Mock du statement PDO
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
             ->method('execute')
             ->with([1]);
        $stmt->expects($this->once())
             ->method('fetchAll')
             ->with(PDO::FETCH_ASSOC)
             ->willReturn($expectedArticle);

        // Mock du PDO
        $pdo = $this->createMock(PDO::class);
        $pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        // On injecte le mock dans le modèle Articles
        \App\Models\Articles::setDB($pdo);

        $result = \App\Models\Articles::getOne(1);

        $this->assertEquals($expectedArticle, $result);
    }

    public function testSaveInsertsArticleAndReturnsId()
    {
        $data = [
            'name' => 'Test Article',
            'description' => 'This is a test description.',
            'user_id' => 42
        ];

        $stmt = $this->createMock(PDOStatement::class);

        $stmt->expects($this->exactly(4))->method('bindParam');
        $stmt->expects($this->once())->method('execute');

        $pdo = $this->createMock(PDO::class);
        $pdo->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('INSERT INTO articles'))
            ->willReturn($stmt);

        $pdo->expects($this->once())
            ->method('lastInsertId')
            ->willReturn('99');

        \App\Models\Articles::setDB($pdo);

        $articleId = \App\Models\Articles::save($data);

        $this->assertEquals('99', $articleId);
    }
}