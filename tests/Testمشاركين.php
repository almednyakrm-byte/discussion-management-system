<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ParticipansController;
use App\Repository\ParticipansRepository;
use App\Entity\Participans;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PHPUnit\Framework\MockObject\MockObject;

class TestParticipans extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ParticipansRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->controller = new ParticipansController($this->repository, $this->entityManager);
    }

    public function testGetAllParticipans()
    {
        $participans = [
            new Participans('1', 'name1', 'email1'),
            new Participans('2', 'name2', 'email2'),
        ];

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($participans);

        $response = $this->controller->getAllParticipans();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($participans), $response->getContent());
    }

    public function testGetParticipanById()
    {
        $participans = new Participans('1', 'name1', 'email1');

        $this->repository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn($participans);

        $response = $this->controller->getParticipanById('1');

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($participans), $response->getContent());
    }

    public function testGetParticipanByIdNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->repository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn(null);

        $this->controller->getParticipanById('1');
    }

    public function testCreateParticipan()
    {
        $participans = new Participans('1', 'name1', 'email1');

        $this->repository->expects($this->once())
            ->method('save')
            ->with($participans);

        $request = new Request([], [], ['participan' => $participans]);

        $response = $this->controller->createParticipan($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode($participans), $response->getContent());
    }

    public function testUpdateParticipan()
    {
        $participans = new Participans('1', 'name1', 'email1');

        $this->repository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn($participans);

        $this->repository->expects($this->once())
            ->method('save')
            ->with($participans);

        $request = new Request([], [], ['participan' => $participans]);

        $response = $this->controller->updateParticipan('1', $request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($participans), $response->getContent());
    }

    public function testUpdateParticipanNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $participans = new Participans('1', 'name1', 'email1');

        $this->repository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn(null);

        $request = new Request([], [], ['participan' => $participans]);

        $this->controller->updateParticipan('1', $request);
    }

    public function testDeleteParticipan()
    {
        $participans = new Participans('1', 'name1', 'email1');

        $this->repository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn($participans);

        $this->repository->expects($this->once())
            ->method('remove')
            ->with($participans);

        $response = $this->controller->deleteParticipan('1');

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteParticipanNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->repository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn(null);

        $this->controller->deleteParticipan('1');
    }
}