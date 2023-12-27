<?php
session_start();
require_once 'Database.php';
include 'task_functions.php'; // Assurez-vous d'avoir cette inclusion

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $taskId = $_GET['id'];

    // Supprimer la tâche
    deleteTask($taskId);

    // Rediriger vers la page principale
    header("Location: index.php");
    exit();
} else {
    // Rediriger en cas de paramètre manquant
    header("Location: index.php");
    exit();
}
?>
