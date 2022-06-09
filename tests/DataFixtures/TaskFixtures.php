<?php

namespace App\Tests\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TaskFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i < 4; $i++){
            $task = new Task();
            $task->setStatus(true);
            $task->setTitle("Example fixture: $i");
            $manager->persist($task);
        }

        $manager->flush();
    }
}