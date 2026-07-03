<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\مواضيع_مفتوحةController;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Testمواضيع_مفتوحة extends TestCase
{
    private $controller;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->controller = new مواضيع_مفتوحةController($this->pdoMock);
    }

    public function testGetAll(): void
    {
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM مواضيع_مفتوحة')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $response = $this->controller->getAll($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetById(): void
    {
        $id = 1;
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM مواضيع_مفتوحة WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('execute')
            ->with(['id' => $id])
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $response = $this->controller->getById($request, $id);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreate(): void
    {
        $data = ['title' => 'Test Title', 'description' => 'Test Description'];
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO مواضيع_مفتوحة (title, description) VALUES (:title, :description)')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('execute')
            ->with($data)
            ->willReturn(true);

        $request = new Request([], [], ['json' => json_encode($data)]);
        $response = $this->controller->create($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testUpdate(): void
    {
        $id = 1;
        $data = ['title' => 'Updated Title', 'description' => 'Updated Description'];
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE مواضيع_مفتوحة SET title = :title, description = :description WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('execute')
            ->with(array_merge($data, ['id' => $id]))
            ->willReturn(true);

        $request = new Request([], [], ['json' => json_encode($data)]);
        $response = $this->controller->update($request, $id);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDelete(): void
    {
        $id = 1;
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM مواضيع_مفتوحة WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('execute')
            ->with(['id' => $id])
            ->willReturn(true);

        $request = new Request();
        $response = $this->controller->delete($request, $id);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}