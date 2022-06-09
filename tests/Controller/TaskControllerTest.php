<?php

namespace App\Tests\Controller;

use App\Entity\Order;
use App\Entity\Task;
use App\Infrastructure\ApiClient;
use App\Infrastrucure\KpnApi\KpnApiClientInterface;
use App\Repository\OrderRepository;
use App\Repository\TaskRepository;
use App\Tests\AbstractTestCase;
use App\Tests\DataFixtures\OrderFixtures;
use App\Tests\DataFixtures\Response\XmlResponse;
use App\Tests\DataFixtures\TaskFixture;
use App\Tests\DataFixtures\TaskFixtures;
use App\ValueObject\Status;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends AbstractTestCase
{
    private const EXPECTED_COUNT_OF_TASKS = 3;
    private const TASKS_TITLES = [
        "Example fixture: 1",
        "Example fixture: 2",
        "Example fixture: 3"
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->addFixture(new TaskFixtures());
        $this->executeFixtures();
    }

     /**
     * @test
     * @small
     */
    public function getTaskReturnsAllTasks()
    {
        $this->client->request('GET', 'api/tasks');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $tasks = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertCount(self::EXPECTED_COUNT_OF_TASKS, $tasks);

        $taskRepository = $this->getTaskRepository();
        $tasksFromRepo = $taskRepository->findAll();

        for ($i = 0; $i < self::EXPECTED_COUNT_OF_TASKS; $i++) {
            $this->assertEquals($tasksFromRepo[$i]->getId(), $tasks[$i]['id']);
            $this->assertEquals(self::TASKS_TITLES[$i], $tasks[$i]['title']);
        }
    }

    // /**
    //  * @test
    //  * @small
    //  */
    // public function getTasksByIdReturnsTask()
    // {
    //     $taskRepository = $this->getOrderRepository();
    //     $task = $taskRepository->findByUuid(Uuid::fromString('7ac4fed5-dd1b-492f-af03-a09dd0e78660'));
    //     $taskId = $task->getId();
    //     $this->client->request('GET', "v1/tasks/$taskId");
    //     $this->assertResponseIsSuccessful();
    //     $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    //     $response = json_decode($this->client->getResponse()->getContent());
    //     $this->assertEquals($task->getId(), $response->id);
    // }

     /**
      * @test
      * @small
      */
     public function postTaskCreateNewTaskWithSuccess()
     {
         $this->client->request(
             'POST',
             'api/tasks',
             [],
             [],
             ['CONTENT_TYPE' => 'application/json'],
            '{"title": "Create task example","status": true}'

         );
         $this->assertResponseIsSuccessful();
         $this->assertResponseStatusCodeSame(Response::HTTP_OK);

         $responseContent = $this->client->getResponse()->getContent();
         $this->assertJson($responseContent);
         $responseData = json_decode($responseContent);
         $task = $this->getTaskRepository()->findOneBy(['id' => $responseData]);

         $this->assertInstanceOf(Task::class, $task);
         $this->assertEquals($responseData, $task->getId());
         $this->assertEquals("Create task example", $task->getTitle());
     }

//    /**
//     * @test
//     * @small
//     */
//    public function postTranslateFrazeSuccess()
//    {
////        $this->mockApiClient();
//
//        $this->client->request(
//            'POST',
//            '/api/translate/yoda',
//            [],
//            [],
//            ['Content-Type' => 'application/json'],
//            '{"text": "Poland lost to Belgium by a score of 1:6. I was really sad to watch this disaster."}'
//        );
//
//        $this->assertResponseIsSuccessful();
//        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
//
//        $responseContent = $this->client->getResponse()->getContent();
//        $this->assertJson($responseContent);
//        $responseData = json_decode($responseContent);
//
//        $this->assertEquals("To belgium by a score of 1: 6,  poland lost.Really sad to watch this disaster,  I was.",
//            $responseData);
//    }

     private function getTaskRepository()
     {
         return $this->client->getContainer()->get(TaskRepository::class);
     }

//     private function mockApiClient()
//     {
//         $clientMock = $this->createMock(ApiClient::class);
//         $clientMock
//             ->expects($this->once())
//             ->method('sendRequest')
//             ->willReturn(self::MOCK_API_RESPONSE);
//
//         static::getContainer()->set(ApiClient::class, $clientMock);
//     }

//     private const MOCK_API_RESPONSE = <<<JSON
//    {
//        "success": {
//            "total": 1
//        },
//        "contents": {
//            "translated": "To belgium by a score of 1: 6,  poland lost.Really sad to watch this disaster,  I was.",
//            "text": "Poland lost to Belgium by a score of 1:6. I was really sad to watch this disaster.",
//            "translation": "yoda"
//        }
//    }
//    JSON;


}