<?php
    require_once 'task.php';
    require_once 'user.php';
    
    session_start();
    
    // local database
    $host = 'localhost';
    $dbname = 'netology';
    $user = 'root';
    $pass = 'php23net';
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ];
    
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    // Task manager page
    if ($_SESSION['user'] ?? '') {
        
        $users = new User($pdo);
        $userId = $_SESSION['user'];
        $user = $users->find($userId);
        
        $descr = $_POST['description'] ?? '';
        $doneId = $_POST['done'] ?? '';
        $deleteId = $_POST['delete'] ?? '';
        $editId = $_POST['editId'] ?? '';
        $assignId = $_POST['assign'] ?? '';
        $assignedUserId = $_POST['assignedUser'] ?? '';
        
        $task = new Task($pdo);
        
        if($descr) {
            if($editId) {
                $task->updateTask($editId, $descr);
            } else {
                $task->insertTask($userId, $descr);
            }
        }
        if($doneId) {
            $task->completeTask($doneId);
        }
        if($deleteId) {
            $task->deleteTask($deleteId);
        }
        if ($assignedUserId) {
            $task->assignTask($assignId, $assignedUserId);
        }
        
        $columnOrder = $_POST['column'] ?? 'id asc';
        $myTasks = $task->findByUserOrderBy($userId, $columnOrder);
        $assignedTasks = $task->findByAssignedUserOrderBy($userId, $columnOrder);
    }
    
    /**
     * Created by PhpStorm.
     * User: konstantin
     * Date: 07.06.2018
     * Time: 10:17
     */