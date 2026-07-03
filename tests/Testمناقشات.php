<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\مناقشاتController;
use App\Repository\مناقشاتRepository;
use App\Entity\مناقشات;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Testمناقشات extends TestCase
{
    private $controller;
    private $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(مناقشاتRepository::class);
        $this->controller = new مناقشاتController($this->repository);
    }

    public function testGetAll(): void
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([new مناقشات()]);

        $request = new Request();
        $response = $this->controller->getAll($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetOne(): void
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(new مناقشات());

        $request = new Request();
        $response = $this->controller->getOne($request, 1);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetOneNotFound(): void
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $request = new Request();
        $this->expectException(NotFoundHttpException::class);
        $this->controller->getOne($request, 1);
    }

    public function testCreate(): void
    {
        $data = ['title' => 'Test Title', 'content' => 'Test Content'];
        $this->repository->expects($this->once())
            ->method('create')
            ->with(new مناقشات($data))
            ->willReturn(new مناقشات($data));

        $request = new Request([], [], ['title' => 'Test Title', 'content' => 'Test Content']);
        $response = $this->controller->create($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdate(): void
    {
        $data = ['title' => 'Test Title', 'content' => 'Test Content'];
        $this->repository->expects($this->once())
            ->method('update')
            ->with(1, new مناقشات($data))
            ->willReturn(new مناقشات($data));

        $request = new Request([], [], ['title' => 'Test Title', 'content' => 'Test Content']);
        $response = $this->controller->update($request, 1);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testUpdateNotFound(): void
    {
        $data = ['title' => 'Test Title', 'content' => 'Test Content'];
        $this->repository->expects($this->once())
            ->method('update')
            ->with(1, new مناقشات($data))
            ->willReturn(null);

        $request = new Request([], [], ['title' => 'Test Title', 'content' => 'Test Content']);
        $this->expectException(NotFoundHttpException::class);
        $this->controller->update($request, 1);
    }

    public function testDelete(): void
    {
        $this->repository->expects($this->once())
            ->method('delete')
            ->with(1);

        $request = new Request();
        $response = $this->controller->delete($request, 1);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteNotFound(): void
    {
        $this->repository->expects($this->once())
            ->method('delete')
            ->with(1)
            ->willReturn(null);

        $request = new Request();
        $this->expectException(NotFoundHttpException::class);
        $this->controller->delete($request, 1);
    }
}