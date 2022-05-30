<?php

namespace App\Service;

interface TaskServiceInterface
{
    public function getAllTasks();
    public function createTask($body);
    public function getTaskById($id);
    public function switchStatus($id);
    public function updateTask($id, $body);
    public function deleteTask($id);
}