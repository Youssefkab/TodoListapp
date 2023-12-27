<?php

function getTasks()
{
    $db = Database::connect();
    $user_id = $_SESSION['user_id'];
    $tasks = $db->query("SELECT * FROM tasks WHERE user_id = $user_id")->fetchAll(PDO::FETCH_ASSOC);
    Database::disconnect();
    return $tasks;
}

function addTask($task)
{
    $db = Database::connect();
    $user_id = $_SESSION['user_id'];
    $stmt = $db->prepare("INSERT INTO tasks (task, user_id) VALUES (?, ?)");
    $stmt->execute([$task, $user_id]);
    Database::disconnect();
}

function deleteTask($taskId)
{
    $db = Database::connect();
    $stmt = $db->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->execute([$taskId]);
    Database::disconnect();
}

function toggleTaskStatus($taskId)
{
    $db = Database::connect();
    $stmt = $db->prepare("UPDATE tasks SET status = NOT status WHERE id = ?");
    $stmt->execute([$taskId]);
    Database::disconnect();
}

?>
