<?php
session_start();
require_once 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérez la tâche depuis le formulaire
    $task = $_POST['task'];

    // Vérifiez si la tâche n'est pas vide
    if (!empty($task)) {
        // Ajoutez la tâche à la base de données
        $pdo = Database::connect();
        $stmt = $pdo->prepare("INSERT INTO tasks (user_id, task) VALUES (:user_id, :task)");
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->bindParam(':task', $task);
        $stmt->execute();
        // ...
        Database::disconnect();
    } else {
        // Gérez le cas où la tâche est vide
        echo "La tâche ne peut pas être vide.";
    }
}

?>