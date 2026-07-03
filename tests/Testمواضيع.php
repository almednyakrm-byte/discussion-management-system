<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\مواضيعController;
use App\Repository\مواضيعRepository;
use App\Entity\مواضيع;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Testمواضيع extends TestCase
{
    private $controller;
    private $repository;
    private $request;

    public function setUp(): void
    {
        $this->repository = $this->createMock(MواضيعRepository::class);
        $this->controller = new مواضيعController($this->repository);
        $this->request = new Request();
    }

    public function testGetAll()
    {
        $expectedResponse = new Response(json_encode([new مواضيع()]));

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([new مواضيع()]);

        $response = $this->controller->getAction($this->request);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetOne()
    {
        $expectedResponse = new Response(json_encode(new مواضيع()));

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(new مواضيع());

        $this->request->query->set('id', 1);
        $response = $this->controller->getAction($this->request);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testPost()
    {
        $expectedResponse = new Response(json_encode(new مواضيع()));

        $this->repository->expects($this->once())
            ->method('save')
            ->with(new مواضيع())
            ->willReturn(new مواضيع());

        $this->request->request->set('title', 'Title');
        $response = $this->controller->postAction($this->request);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testPut()
    {
        $expectedResponse = new Response(json_encode(new مواضيع()));

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(new مواضيع());

        $this->repository->expects($this->once())
            ->method('save')
            ->with(new مواضيع())
            ->willReturn(new مواضيع());

        $this->request->request->set('title', 'Title');
        $this->request->query->set('id', 1);
        $response = $this->controller->putAction($this->request);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDelete()
    {
        $expectedResponse = new Response();

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(new مواضيع());

        $this->repository->expects($this->once())
            ->method('remove')
            ->with(new مواضيع());

        $this->request->query->set('id', 1);
        $response = $this->controller->deleteAction($this->request);
        $this->assertEquals($expectedResponse, $response);
    }
}



// App\Controller\مواضيعController.php
namespace App\Controller;

use App\Repository\مواضيعRepository;
use App\Entity\مواضيع;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class مواضيعController
{
    private $repository;

    public function __construct(MواضيعRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAction(Request $request)
    {
        if ($request->query->has('id')) {
            return new Response(json_encode($this->repository->find($request->query->get('id'))));
        } else {
            return new Response(json_encode($this->repository->findAll()));
        }
    }

    public function postAction(Request $request)
    {
        $مواضيع = new مواضيع();
        $مواضيع->setTitle($request->request->get('title'));
        $this->repository->save($مواضيع);
        return new Response(json_encode($مواضيع));
    }

    public function putAction(Request $request)
    {
        $مواضيع = $this->repository->find($request->query->get('id'));
        $مواضيع->setTitle($request->request->get('title'));
        $this->repository->save($مواضيع);
        return new Response(json_encode($مواضيع));
    }

    public function deleteAction(Request $request)
    {
        $مواضيع = $this->repository->find($request->query->get('id'));
        $this->repository->remove($مواضيع);
        return new Response();
    }
}