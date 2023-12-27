<?php
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $taskId = $_GET['id'];

    // Obtient l'état actuel de la tâche
    $pdo = Database::connect();
    $query = $pdo->prepare("SELECT status FROM tasks WHERE id = :id");
    $query->execute(['id' => $taskId]);
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $currentStatus = $result['status'];

        // Met à jour l'état de la tâche
        $newStatus = ($currentStatus === 'done') ? 'undo' : 'done';
        $updateQuery = $pdo->prepare("UPDATE tasks SET status = :status WHERE id = :id");
        $updateQuery->execute(['status' => $newStatus, 'id' => $taskId]);
        

        // Retourne le nouvel état
        echo json_encode(['status' => $newStatus]);
        exit();
    }
}

// Retourne une erreur en cas de problème
echo json_encode(['error' => 'Invalid request']);
