<?php
    require_once 'task.php';
    require_once 'user.php';
    
    session_start();
    
    $host = 'localhost';
    $dbname = 'cibizov';
    $user = 'cibizov';
    $pass = 'neto1762';
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ];
    
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    $LOG = isset($_SESSION['user']) ? $_SESSION['user'] : '';
    if ($LOG) {
        
        $users = new User($pdo);
        $userId = $_SESSION['user'];
        $user = $users->find($userId);
        
        $descr = isset($_POST['description']) ? $_POST['description'] : '';
        $doneId = isset($_POST['done']) ? $_POST['done'] : '';
        $deleteId = isset($_POST['delete']) ? $_POST['delete'] : '';
        $editId = isset($_POST['editId']) ? $_POST['editId'] : '';
        $assignId = isset($_POST['assign']) ? $_POST['assign'] : '';
        $assignedUserId = isset($_POST['assignedUser']) ? $_POST['assignedUser'] : '';
        
        $task = new Task($pdo);
        
        if ($descr) {
            if ($editId) {
                $task->updateTask($editId, $descr);
            } else {
                $task->insertTask($userId, $descr);
            }
        }
        if ($doneId) {
            $task->completeTask($doneId);
        }
        if ($deleteId) {
            $task->deleteTask($deleteId);
        }
        if ($assignedUserId) {
            $task->assignTask($assignId, $assignedUserId);
        }
        
        $columnOrder = isset($_POST['column']) ? $_POST['column'] : 'id asc';
        $myTasks = $task->findByUserOrderBy($userId, $columnOrder);
        $assignedTasks = $task->findByAssignedUserOrderBy($userId, $columnOrder);
    }
    
    /**
     * Created by PhpStorm.
     * User: konstantin
     * Date: 07.06.2018
     * Time: 10:17
     */