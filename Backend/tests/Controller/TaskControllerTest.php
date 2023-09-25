<?php

/*
 * This file was created by Jakub Szczerba
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
 */

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testAddTaskEndpoint(): void
    {
        $client = static::createClient();

        $data = [
            'content' => 'Test utworzenia nowego zadania',
            'done' => false,
        ];

        $client->request('POST', '/api/tasks', [], [], [], json_encode($data));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testUpdateTaskEndpoint(): void
    {
        $client = static::createClient();

        $data = [
            'content' => 'Testowe zadanie do edycji',
            'done' => false,
        ];

        $client->request('POST', '/api/tasks', [], [], [], json_encode($data));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $responseContent = json_decode($client->getResponse()->getContent(), true);
        $task = json_decode($responseContent[0], true);
        $taskId = $task['id'];

        $dataEdit = [
            'content' => 'Test zaktualizowania zadania',
            'done' => true,
        ];

        $client->request('PATCH', '/api/tasks/'.$taskId, [], [], [], json_encode($dataEdit));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testDeleteTaskEndpoint(): void
    {
        $client = static::createClient();

        $data = [
            'content' => 'Zadanie do usuniecia',
            'done' => false,
        ];

        $client->request('POST', '/api/tasks', [], [], [], json_encode($data));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $responseContent = json_decode($client->getResponse()->getContent(), true);
        $task = json_decode($responseContent[0], true);
        $taskId = $task['id'];

        $client->request('DELETE', '/api/tasks/'.$taskId);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}