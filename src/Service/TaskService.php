<?php

namespace App\Service;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TaskService implements TaskServiceInterface
{
    public function __construct(
        private TaskRepository $taskRepository
        )
    {
    }

    public function getAllTasks()
    {
        return $this->taskRepository->findAll();
    }

    public function getTaskById($id)
    {
        $task = $this->taskRepository->findOneBy(['id' => $id]);
        if (!$task) {
            throw new NotFoundHttpException(
                "No task found for id: $id"
            );
        }

        return $task;
    }
    
    public function createTask($body)
    {
        $task = new Task();
        $task->setTitle($body->title);
        $task->setStatus($body->status);
        $this->taskRepository->add($task, true);
        return $task->getId();
    }

    public function switchStatus($id)
    {
        $task = $this->taskRepository->findOneBy(['id' => $id]);
        if (!$task) {
            throw new NotFoundHttpException(
                'No task found for id '.$id
            );
        }

        $task->setStatus(!$task->getStatus());
        $this->taskRepository->save($task);
    }

    public function updateTask($id, $body)
    {
        $task = $this->taskRepository->findOneBy(['id' => $id]);
        if (!$task) {
            throw new NotFoundHttpException(
                'No task found for id '.$id
            );
        }

        $task->setTitle($body->title);
        $task->setStatus($body->status);
        $this->taskRepository->save($task);
    }

    public function deleteTask($id)
    {
        $task = $this->taskRepository->findOneBy(['id' => $id]);
        if (!$task) {
            throw new NotFoundHttpException(
                'No task found for id '.$id
            );
        }
        $this->taskRepository->remove($task, true);
    }
}