<?php

/*
 * This file was created by Jakub Szczerba
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Services\Task\TaskService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('api')]
class TaskController extends AbstractController
{
    private TaskService $taskService;
    private TaskRepository $repository;
    private SerializerInterface $serializer;
    private EntityManagerInterface $em;

    public function __construct(TaskService $taskService, TaskRepository $repository, SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $this->taskService = $taskService;
        $this->repository = $repository;
        $this->serializer = $serializer;
        $this->em = $em;
    }

    #[Route('/tasks', name: "listTask", methods: ['GET'])]
    public function listTask(): JsonResponse
    {
        $data = $this->repository->findAll();

        return new JsonResponse([$this->serializer->serialize($data, 'json')], 200);
    }

    #[Route('/tasks', name: "addTask", methods: ['POST'])]
    public function addTask(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return new JsonResponse(['error' => 'Błędny format JSON'], 400);
        }

        $task = $this->taskService->addTask($data['content']);

        return new JsonResponse([$this->serializer->serialize($task, 'json')], 200);
    }

    #[Route('/tasks/{id}', name: "deleteTask", methods: ['DELETE'])]
    public function deleteTask(int $id): JsonResponse
    {
        $task = $this->em->getRepository(Task::class)->find($id);

        if (!$task) {
            return new JsonResponse(['error' => 'Zadanie nie istnieje'], 404);
        }

        $this->taskService->deleteTask($task);

        return new JsonResponse(['message' => 'Zadanie zostało usunięte'], 200);
    }

    #[Route('/tasks/{id}', name: "updateTask", methods: ['PATCH'])]
    public function updateTask(Request $request, int $id): JsonResponse
    {
        $task = $this->em->getRepository(Task::class)->find($id);

        if (!$task) {
            return new JsonResponse(['error' => 'Zadanie nie istnieje'], 404);
        }

        $this->taskService->updateTask($task);

        return new JsonResponse(['message' => 'Zadanie zostało zaktualizowane'], 200);
    }
}
