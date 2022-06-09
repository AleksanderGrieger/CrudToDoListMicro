<?php

namespace App\Controller;

use App\Dto\TaskRequest;
use App\Dto\TaskResponse;
use App\Service\TaskServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;
use AutoMapperPlus\AutoMapperInterface;

#[Rest\Route('/api/tasks')]
#[OA\Tag(name: 'Task')]
class TaskController extends AbstractController
{
    public function __construct(
        private readonly AutoMapperInterface $autoMapper,
        private readonly TaskServiceInterface $taskService
        )
    {
    }

    #[Rest\Get('', name: 'get_tasks')]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Returns all Tasks',
        content: new OA\JsonContent(
            type: 'array', 
            items: new OA\Items(ref: new Model(type: TaskResponse::class))
        )
    )]
    public function getTasks(): View
    {
        $tasks = $this->taskService->getAllTasks();
        $response = $this->autoMapper->mapMultiple($tasks, TaskResponse::class);

        return  View::create($response, Response::HTTP_OK);
    }

    #[Rest\Get('/{id}', name: 'get_task')]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Returns Task by ID',
        content: new OA\JsonContent(
            ref: new Model(type: TaskResponse::class),
            type: 'object'
        )
    )]
    public function getTask($id): View
    {
        $task = $this->taskService->getTaskById($id);
        $response = $this->autoMapper->map($task, TaskResponse::class);

        return  View::create($response, Response::HTTP_OK);
    }

    #[Rest\Post('', name: 'create_task')]
    #[OA\RequestBody( required: true, content: new OA\JsonContent(
        ref: new Model(type: TaskRequest::class),
        type: 'object'
    ))]
    public function create(Request $request): View
    {
        $body = json_decode($request->getContent());
        $taskId = $this->taskService->createTask($body);

        return  View::create($taskId, Response::HTTP_OK);
    }
 
    #[Rest\Patch('/switch-status/{id}', name: 'switch_status')]
    #[OA\Response(response: Response::HTTP_NO_CONTENT, description: "Task status updated")]
    public function switchStatus($id): View
    {
        $this->taskService->switchStatus($id);

        return View::create(Response::HTTP_NO_CONTENT);
    }
    
    #[Rest\Patch('/{id}/update', name: 'update_task')]
    #[OA\RequestBody( required: true, content: new OA\JsonContent(
        ref: new Model(type: TaskRequest::class),
        type: 'object'
    ))]
    #[OA\Response(response: Response::HTTP_NO_CONTENT, description: "Task updated")]
    public function updateTask($id, Request $request)
    {
        $body = json_decode($request->getContent());

        $this->taskService->updateTask($id, $body);

        return View::create(Response::HTTP_NO_CONTENT);
    }
 
    #[Rest\Delete('/{id}/delete', name: 'delete_task')]
    #[OA\Response(response: Response::HTTP_NO_CONTENT, description: "Task deleted")]
    public function deleteTask($id): View
    {
        $this->taskService->deleteTask($id);

        return View::create(Response::HTTP_NO_CONTENT);
    }
}
