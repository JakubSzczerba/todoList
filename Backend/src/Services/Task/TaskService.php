<?php

/*
 * This file was created by Jakub Szczerba
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace App\Services\Task;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class TaskService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function addTask(string $content): Task
    {
        $task = new Task();
        $task
            ->setContent($content)
            ->setDone(Task::NOT_DONE);
        $this->em->persist($task);

        $this->em->flush();

        return $task;
    }

    public function deleteTask(Task $task): Response
    {
        $this->em->remove($task);
        $this->em->flush();

        return new Response();
    }

    public function updateTask(Task $task): Task
    {
        if (!($task->isDone())) {
            $task->setDone(true);
        }

        $this->em->flush();

        return $task;
    }

}